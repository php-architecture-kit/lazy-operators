(function () {
    'use strict';

    var catalogElement = document.getElementById('catalog-data');
    var catalog = JSON.parse(catalogElement.textContent);

    var catalogByKey = {};
    catalog.forEach(function (entry) {
        catalogByKey[entry.key] = entry;
    });

    var detail = document.getElementById('detail');
    var detailGroup = document.getElementById('detail-group');
    var detailType = document.getElementById('detail-type');
    var detailName = document.getElementById('detail-name');
    var detailFormula = document.getElementById('detail-formula');
    var detailDescription = document.getElementById('detail-description');
    var detailArguments = document.getElementById('detail-arguments');

    var currentEntry = null;
    var currentArguments = [];
    var assignments = {};
    var draggingKey = null;
    var lastHoverLi = null;
    var openDropdownLi = null;

    var TYPE_HIERARCHY = {
        IntegerValue: ['IntegerValue', 'NumberValue', 'Expression'],
        FloatValue: ['FloatValue', 'NumberValue', 'Expression'],
        NumberValue: ['NumberValue', 'Expression'],
        BooleanValue: ['BooleanValue', 'Expression'],
        StringValue: ['StringValue', 'Expression'],
        ArrayValue: ['ArrayValue', 'Expression'],
        ObjectValue: ['ObjectValue', 'Expression'],
        Expression: ['Expression']
    };

    function typeLabel(argument) {
        return argument.itemType ? argument.type + '<' + argument.itemType + '>' : argument.type;
    }

    function parseArgumentTypeTokens(rawType) {
        return String(rawType).split('|').map(function (token) {
            return token.trim().replace(/^\?/, '');
        });
    }

    function isTypeCompatible(argumentRawType, entryType) {
        if (!argumentRawType) {
            return false;
        }

        var entryChain = TYPE_HIERARCHY[entryType] || [entryType];

        return parseArgumentTypeTokens(argumentRawType).some(function (token) {
            return Object.prototype.hasOwnProperty.call(TYPE_HIERARCHY, token)
                && entryChain.indexOf(token) !== -1;
        });
    }

    function getCompatibleEntries(argument) {
        return catalog.filter(function (entry) {
            return isTypeCompatible(argument.type, entry.type);
        }).sort(function (a, b) {
            var groupA = a.group || '';
            var groupB = b.group || '';

            if (groupA !== groupB) {
                return groupA < groupB ? -1 : 1;
            }

            var nameA = a.name || a.key;
            var nameB = b.name || b.key;

            return nameA < nameB ? -1 : (nameA > nameB ? 1 : 0);
        });
    }

    function findArgument(argumentName) {
        return currentArguments.find(function (candidate) {
            return candidate.name === argumentName;
        });
    }

    function assignArgument(argumentName, entryKey) {
        var entry = catalogByKey[entryKey];

        if (!entry) {
            return;
        }

        var value = { key: entry.key, name: entry.name || entry.key, type: entry.type };
        var argument = findArgument(argumentName);

        if (argument && argument.spread) {
            assignments[argumentName] = (assignments[argumentName] || []).concat([value]);
        } else {
            assignments[argumentName] = value;
        }

        renderArguments(currentArguments);
    }

    function assignEnumArgument(argumentName, value) {
        assignments[argumentName] = { value: value };
        renderArguments(currentArguments);
    }

    function unassignArgument(argumentName, index) {
        if (index === undefined || index === null) {
            delete assignments[argumentName];
            renderArguments(currentArguments);

            return;
        }

        var list = assignments[argumentName];

        if (!Array.isArray(list)) {
            return;
        }

        list.splice(index, 1);

        if (list.length === 0) {
            delete assignments[argumentName];
        }

        renderArguments(currentArguments);
    }

    function renderArguments(args) {
        closeArgumentDropdown();
        detailArguments.innerHTML = '';

        args.forEach(function (argument) {
            var item = document.createElement('li');
            item.className = 'argument argument-' + argument.kind;
            item.dataset.argumentName = argument.name;
            item.dataset.argumentKind = argument.kind;

            if (argument.kind === 'expression') {
                item.dataset.argumentType = argument.type;
            }

            var name = document.createElement('span');
            name.className = 'argument-name';
            name.textContent = argument.name;
            item.appendChild(name);

            var type = document.createElement('span');
            type.className = 'argument-type';
            type.textContent = typeLabel(argument);
            item.appendChild(type);

            if (argument.kind !== 'expression') {
                var kind = document.createElement('span');
                kind.className = 'argument-kind';
                kind.textContent = argument.kind;
                item.appendChild(kind);
            }

            if (argument.spread) {
                var spread = document.createElement('span');
                spread.className = 'argument-kind';
                spread.textContent = 'spread';
                item.appendChild(spread);
            }

            if (argument.optional) {
                var optional = document.createElement('span');
                optional.className = 'argument-kind';
                optional.textContent = 'optional';
                item.appendChild(optional);
            }

            if (argument.defaultValue !== null && argument.defaultValue !== undefined) {
                var defaultValue = document.createElement('span');
                defaultValue.className = 'argument-options';
                defaultValue.textContent = 'default: ' + argument.defaultValue;
                item.appendChild(defaultValue);
            }

            if (argument.kind === 'enum') {
                var assignedEnum = assignments[argument.name];

                if (assignedEnum) {
                    item.classList.add('argument-assigned');

                    var assignedEnumValue = document.createElement('span');
                    assignedEnumValue.className = 'argument-assigned-value';
                    assignedEnumValue.textContent = assignedEnum.value;
                    item.appendChild(assignedEnumValue);

                    var unassignEnum = document.createElement('button');
                    unassignEnum.type = 'button';
                    unassignEnum.className = 'argument-unassign';
                    unassignEnum.title = 'Unassign';
                    unassignEnum.textContent = '×';
                    item.appendChild(unassignEnum);
                } else if (!argument.options || !argument.options.length) {
                    var noOptions = document.createElement('span');
                    noOptions.className = 'argument-no-targets';
                    noOptions.textContent = 'no options available';
                    item.appendChild(noOptions);
                } else {
                    var enumHint = document.createElement('span');
                    enumHint.className = 'argument-options argument-assign-hint';
                    enumHint.textContent = 'pick a value:';
                    item.appendChild(enumHint);

                    var enumChoose = document.createElement('button');
                    enumChoose.type = 'button';
                    enumChoose.className = 'argument-choose';
                    enumChoose.textContent = 'choose ▾';
                    enumHint.appendChild(enumChoose);
                }
            }

            if (argument.kind === 'case' && argument.fields) {
                var fields = document.createElement('span');
                fields.className = 'argument-options';
                fields.textContent = 'each case: ' + argument.fields.map(function (field) {
                    return field.name + ': ' + field.type;
                }).join(', ');
                item.appendChild(fields);
            }

            if (argument.kind === 'expression' && argument.spread) {
                var assignedList = assignments[argument.name] || [];

                if (assignedList.length) {
                    item.classList.add('argument-assigned');

                    var list = document.createElement('span');
                    list.className = 'argument-assigned-list';

                    assignedList.forEach(function (assignedItem, index) {
                        var row = document.createElement('span');
                        row.className = 'argument-assigned-item';

                        var rowValue = document.createElement('span');
                        rowValue.className = 'argument-assigned-value';
                        rowValue.textContent = assignedItem.name + ' (' + assignedItem.type + ')';
                        row.appendChild(rowValue);

                        var rowUnassign = document.createElement('button');
                        rowUnassign.type = 'button';
                        rowUnassign.className = 'argument-unassign';
                        rowUnassign.title = 'Unassign';
                        rowUnassign.textContent = '×';
                        rowUnassign.dataset.argumentIndex = String(index);
                        row.appendChild(rowUnassign);

                        list.appendChild(row);
                    });

                    item.appendChild(list);
                }

                if (getCompatibleEntries(argument).length === 0) {
                    if (!assignedList.length) {
                        var noSpreadTargets = document.createElement('span');
                        noSpreadTargets.className = 'argument-no-targets';
                        noSpreadTargets.textContent = 'no matching expressions (raw value)';
                        item.appendChild(noSpreadTargets);
                    }
                } else {
                    var spreadHint = document.createElement('span');
                    spreadHint.className = 'argument-options argument-assign-hint';
                    spreadHint.textContent = assignedList.length ? 'add another, or' : 'drop an expression here, or';
                    item.appendChild(spreadHint);

                    var spreadChoose = document.createElement('button');
                    spreadChoose.type = 'button';
                    spreadChoose.className = 'argument-choose';
                    spreadChoose.textContent = 'choose ▾';
                    spreadHint.appendChild(spreadChoose);
                }
            } else if (argument.kind === 'expression') {
                var assigned = assignments[argument.name];

                if (assigned) {
                    item.classList.add('argument-assigned');

                    var assignedValue = document.createElement('span');
                    assignedValue.className = 'argument-assigned-value';
                    assignedValue.textContent = assigned.name + ' (' + assigned.type + ')';
                    item.appendChild(assignedValue);

                    var unassign = document.createElement('button');
                    unassign.type = 'button';
                    unassign.className = 'argument-unassign';
                    unassign.title = 'Unassign';
                    unassign.textContent = '×';
                    item.appendChild(unassign);
                } else if (getCompatibleEntries(argument).length === 0) {
                    var noTargets = document.createElement('span');
                    noTargets.className = 'argument-no-targets';
                    noTargets.textContent = 'no matching expressions (raw value)';
                    item.appendChild(noTargets);
                } else {
                    var hint = document.createElement('span');
                    hint.className = 'argument-options argument-assign-hint';
                    hint.textContent = 'drop an expression here, or';
                    item.appendChild(hint);

                    var choose = document.createElement('button');
                    choose.type = 'button';
                    choose.className = 'argument-choose';
                    choose.textContent = 'choose ▾';
                    hint.appendChild(choose);
                }
            }

            detailArguments.appendChild(item);
        });
    }

    function markCompatibleArgumentSlots(entry) {
        detailArguments.querySelectorAll('.argument[data-argument-kind="expression"]').forEach(function (li) {
            var compatible = isTypeCompatible(li.dataset.argumentType, entry.type);
            li.classList.toggle('argument-compatible', compatible);
            li.classList.toggle('argument-incompatible', !compatible);
        });
    }

    function clearArgumentDragState() {
        detailArguments.querySelectorAll('.argument').forEach(function (li) {
            li.classList.remove('argument-compatible', 'argument-incompatible', 'argument-drop-hover');
        });
    }

    function closeArgumentDropdown() {
        if (!openDropdownLi) {
            return;
        }

        var dropdown = openDropdownLi.querySelector('.arg-dropdown');

        if (dropdown) {
            dropdown.remove();
        }

        openDropdownLi = null;
        document.removeEventListener('click', onDocumentClickWhileDropdownOpen, true);
        document.removeEventListener('keydown', onKeydownWhileDropdownOpen, true);
    }

    function onDocumentClickWhileDropdownOpen(event) {
        if (event.target.closest('.arg-dropdown') || event.target.closest('.argument-choose')) {
            return;
        }

        closeArgumentDropdown();
    }

    function onKeydownWhileDropdownOpen(event) {
        if (event.key === 'Escape') {
            closeArgumentDropdown();
        }
    }

    function openArgumentDropdown(li) {
        if (openDropdownLi === li) {
            closeArgumentDropdown();

            return;
        }

        closeArgumentDropdown();

        var argumentName = li.dataset.argumentName;
        var argument = findArgument(argumentName);

        if (!argument) {
            return;
        }

        var dropdown = document.createElement('div');
        dropdown.className = 'arg-dropdown';

        if (argument.kind === 'enum') {
            (argument.options || []).forEach(function (option) {
                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'arg-dropdown-item';
                item.dataset.enumValue = option;

                var itemName = document.createElement('span');
                itemName.textContent = option;
                item.appendChild(itemName);

                dropdown.appendChild(item);
            });
        } else {
            var entries = getCompatibleEntries(argument);
            var previousGroup = null;

            entries.forEach(function (entry) {
                var group = entry.group || 'Other';

                if (group !== previousGroup) {
                    var header = document.createElement('div');
                    header.className = 'arg-dropdown-group';
                    header.textContent = group;
                    dropdown.appendChild(header);
                    previousGroup = group;
                }

                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'arg-dropdown-item';
                item.dataset.entryKey = entry.key;

                var itemName = document.createElement('span');
                itemName.textContent = entry.name || entry.key;
                item.appendChild(itemName);

                var itemType = document.createElement('span');
                itemType.className = 'arg-dropdown-item-type';
                itemType.textContent = entry.type;
                item.appendChild(itemType);

                dropdown.appendChild(item);
            });
        }

        li.appendChild(dropdown);
        openDropdownLi = li;
        document.addEventListener('click', onDocumentClickWhileDropdownOpen, true);
        document.addEventListener('keydown', onKeydownWhileDropdownOpen, true);
    }

    detailArguments.addEventListener('dragover', function (event) {
        if (!draggingKey) {
            return;
        }

        var li = event.target.closest('.argument-compatible');

        if (!li || !detailArguments.contains(li)) {
            return;
        }

        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';

        if (li !== lastHoverLi) {
            if (lastHoverLi) {
                lastHoverLi.classList.remove('argument-drop-hover');
            }

            li.classList.add('argument-drop-hover');
            lastHoverLi = li;
        }
    });

    detailArguments.addEventListener('dragleave', function (event) {
        var li = event.target.closest('.argument-drop-hover');

        if (li && !li.contains(event.relatedTarget)) {
            li.classList.remove('argument-drop-hover');
            lastHoverLi = null;
        }
    });

    detailArguments.addEventListener('drop', function (event) {
        var li = event.target.closest('.argument-compatible');

        if (!li || !draggingKey) {
            return;
        }

        event.preventDefault();
        assignArgument(li.dataset.argumentName, draggingKey);
    });

    detailArguments.addEventListener('click', function (event) {
        var chooseButton = event.target.closest('.argument-choose');

        if (chooseButton) {
            openArgumentDropdown(chooseButton.closest('.argument'));

            return;
        }

        var dropdownItem = event.target.closest('.arg-dropdown-item');

        if (dropdownItem) {
            var li = dropdownItem.closest('.argument');

            if (dropdownItem.dataset.enumValue !== undefined) {
                assignEnumArgument(li.dataset.argumentName, dropdownItem.dataset.enumValue);
            } else {
                assignArgument(li.dataset.argumentName, dropdownItem.dataset.entryKey);
            }

            return;
        }

        var unassignButton = event.target.closest('.argument-unassign');

        if (unassignButton) {
            var argumentName = unassignButton.closest('.argument').dataset.argumentName;
            var index = unassignButton.dataset.argumentIndex;

            unassignArgument(argumentName, index === undefined ? undefined : parseInt(index, 10));
        }
    });

    document.querySelectorAll('.tile').forEach(function (tile) {
        var key = tile.dataset.key;

        tile.addEventListener('click', function () {
            var entry = catalogByKey[key];

            if (!entry) {
                return;
            }

            currentEntry = entry;
            currentArguments = entry.arguments || [];
            assignments = {};

            detailGroup.textContent = entry.group ? entry.group + ' · ' : '';
            detailType.textContent = entry.type;
            detailName.textContent = entry.name || entry.key;
            detailFormula.textContent = entry.formula || '';
            detailDescription.textContent = entry.description || '';
            renderArguments(currentArguments);
            detail.hidden = false;

            document.querySelectorAll('.tile').forEach(function (t) {
                t.classList.toggle('is-selected', t === tile);
            });
        });

        tile.draggable = true;

        tile.addEventListener('dragstart', function (event) {
            draggingKey = key;
            tile.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'copy';
            event.dataTransfer.setData('text/plain', key);
            markCompatibleArgumentSlots(catalogByKey[key]);
        });

        tile.addEventListener('dragend', function () {
            draggingKey = null;
            tile.classList.remove('is-dragging');
            clearArgumentDragState();
            lastHoverLi = null;
        });
    });

}());
