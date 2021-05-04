<?php

$excluded_folders = [
    'node_modules',
    'storage',
    'vendor',
];

$finder = PhpCsFixer\Finder::create()
    ->exclude($excluded_folders)
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'               => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
            ],
        ],
        'array_syntax'                      => ['syntax' => 'short'],
        'linebreak_after_opening_tag'       => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports'                   => ['sort_algorithm' => 'length'],
        'phpdoc_order'                      => true,
        'method_argument_space'             => ['on_multiline' => 'ensure_fully_multiline'],
    ])
    ->setFinder($finder)
;
