<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\CallbackArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\CaseArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\EnumArgument;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;

$callbacks = new CallbackRegistry();
$callbacks->register('round2', static fn (float $value): float => round($value, 2));
$callbacks->register('clampPercentage', static fn (float $value): float => max(0.0, min(100.0, $value)));
$callbacks->register('titleCase', static fn (string $value): string => ucwords(strtolower($value)));

$callbackDetailsList = array_map(
    static fn (string $name) => $callbacks->getCallbackDetails($name),
    $callbacks->names(),
);

$callbackTiles = '';

foreach ($callbackDetailsList as $details) {
    $callbackTiles .= sprintf(
        '<button class="callback-tile" type="button" data-callback-name="%s"><span class="callback-tile-name">%s</span></button>' . "\n",
        htmlspecialchars($details->name, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($details->name, ENT_QUOTES, 'UTF-8'),
    );
}

$callbacksJson = json_encode(
    array_map(
        static fn ($details): array => [
            'name' => $details->name,
            'signature' => $details->signature,
            'parameters' => array_map(
                static fn ($parameter): array => ['name' => $parameter->name, 'type' => $parameter->type],
                $details->parameters,
            ),
            'returnType' => $details->returnType,
        ],
        $callbackDetailsList,
    ),
    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
);

$registry = ExpressionRegistry::default();
$entries = $registry->getAll();

$entriesByGroup = [];

foreach ($entries as $entry) {
    $groupValue = $entry->attributes->group?->value ?? 'Other';
    [$topGroup, $subGroup] = array_pad(explode(' | ', $groupValue, 2), 2, null);

    $entriesByGroup[$topGroup][$subGroup ?? ''][] = $entry;
}

ksort($entriesByGroup);

$tiles = '';

foreach ($entriesByGroup as $topGroup => $subGroups) {
    ksort($subGroups);

    $tiles .= sprintf(
        '<h2 class="group-title">%s</h2>' . "\n",
        htmlspecialchars($topGroup, ENT_QUOTES, 'UTF-8'),
    );

    foreach ($subGroups as $subGroup => $groupEntries) {
        if ($subGroup !== '') {
            $tiles .= sprintf(
                '<h3 class="subgroup-title">%s</h3>' . "\n",
                htmlspecialchars($subGroup, ENT_QUOTES, 'UTF-8'),
            );
        }

        foreach ($groupEntries as $entry) {
            $key = htmlspecialchars($entry->key, ENT_QUOTES, 'UTF-8');
            $type = htmlspecialchars($entry->type, ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($entry->attributes->name?->value ?? $entry->key, ENT_QUOTES, 'UTF-8');

            $tiles .= sprintf(
                '<button class="tile" type="button" data-key="%s"><span class="tile-type">%s</span><span class="tile-name">%s</span></button>' . "\n",
                $key,
                $type,
                $name,
            );
        }
    }
}

$catalog = array_map(
    static fn ($entry): array => [
        'key' => $entry->key,
        'fqcn' => $entry->fqcn,
        'type' => $entry->type,
        'group' => $entry->attributes->group?->value,
        'name' => $entry->attributes->name?->value,
        'formula' => $entry->attributes->formula?->value,
        'description' => $entry->attributes->description?->value,
        'arguments' => array_map(
            static fn ($argument): array => [
                'name' => $argument->name,
                'type' => $argument->type,
                'itemType' => $argument->itemType,
                'spread' => $argument->spread,
                'optional' => $argument->optional,
                'defaultValue' => $argument->defaultValue,
                'kind' => match (true) {
                    $argument instanceof EnumArgument => 'enum',
                    $argument instanceof CallbackArgument => 'callback',
                    $argument instanceof CaseArgument => 'case',
                    default => 'expression',
                },
                'options' => $argument instanceof EnumArgument ? $argument->options : null,
                'fields' => $argument instanceof CaseArgument
                    ? array_map(
                        static fn ($field): array => ['name' => $field->name, 'type' => $field->type],
                        $argument->fields,
                    )
                    : null,
            ],
            $entry->arguments,
        ),
    ],
    $entries,
);

$catalogJson = json_encode(
    $catalog,
    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
);

$template = file_get_contents(__DIR__ . '/template.html');
$style = file_get_contents(__DIR__ . '/style.css');
$script = file_get_contents(__DIR__ . '/app.js');

$html = strtr($template, [
    '{{TITLE}}' => 'Lazy Operators — Builder Demo',
    '{{STYLE}}' => $style,
    '{{CALLBACK_TILES}}' => $callbackTiles,
    '{{SIDEBAR_TILES}}' => $tiles,
    '{{CATALOG_JSON}}' => $catalogJson,
    '{{CALLBACKS_JSON}}' => $callbacksJson,
    '{{SCRIPT}}' => $script,
]);

header('Content-Type: text/html; charset=utf-8');
echo $html;
