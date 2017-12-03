<?php

// PHP-CS-Fixer v2.8
return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setFinder(
        PhpCsFixer\Finder::create()
             ->files()
             ->in(__DIR__ . '/../src')
             ->in(__DIR__ . '/../tests')
             ->name('*.php')
    )
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP56Migration:risky'   => true,
        '@Symfony'                => true,
        '@Symfony:risky'          => true,
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline',
        ],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal',
        ],
        'class_definition' => [
            'multiLineExtendsEachSingleLine' => true,
            'singleItemSingleLine'           => true,
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint'  => true,
        'concat_space'               => [
            'spacing' => 'one',
        ],
        'declare_equal_normalize' => [
            'space' => 'single',
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author',
                'category',
                'codeCoverageIgnore',
                'codeCoverageIgnoreEnd',
                'codeCoverageIgnoreStart',
                'copyright',
                'date',
                'expectedException',
                'expectedExceptionCode',
                'expectedExceptionMessage',
                'expectedExceptionMessageRegExp',
                'license',
                'since',
                'static',
                'version',
            ],
        ],
        'heredoc_to_nowdoc' => true,
        'increment_style'   => [
            'style' => 'post',
        ],
        'linebreak_after_opening_tag' => true,
        'list_syntax'                 => [
            'syntax' => 'long',
        ],
        'mb_str_functions'      => true,
        'method_argument_space' => [
            'ensure_fully_multiline'           => true,
            'keep_multiple_spaces_after_comma' => false,
        ],
        'native_function_invocation'       => true,
        'no_extra_consecutive_blank_lines' => [
            'tokens' => [
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
                'use_trait',
            ],
        ],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_null_property_initialization'           => true,
        'no_php4_constructor'                       => true,
        'no_superfluous_elseif'                     => true,
        'no_unreachable_default_argument_value'     => true,
        'no_useless_else'                           => true,
        'no_useless_return'                         => true,
        'ordered_class_elements'                    => true,
        'ordered_imports'                           => [
            'sortAlgorithm' => 'alpha',
        ],
        'php_unit_strict'                     => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_order'                        => true,
        'phpdoc_types_order'                  => [
            'null_adjustment' => 'always_last',
            'sort_algorithm'  => 'alpha',
        ],
        'return_type_declaration' => [
            'space_before' => 'one',
        ],
        'strict_comparison'     => true,
        'strict_param'          => true,
        'unary_operator_spaces' => false,
        'visibility_required'   => [
            'elements' => [
                'property',
                'method',
            ],
        ],
        'yoda_style' => [
            'equal'            => false,
            'identical'        => false,
            'less_and_greater' => false,
        ],
    ]);
