<?php

// PHP-CS-Fixer v2.4.0
return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony'                                 => true,
        'align_multiline_comment'                  => [
            'comment_type' => 'all_multiline',
        ],
        'array_syntax'                             => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces'                   => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'class_definition'                         => [
            'multiLineExtendsEachSingleLine' => true,
            'singleItemSingleLine'           => true,
        ],
        'concat_space'                             => [
            'spacing' => 'one',
        ],
//        'declare_strict_types'                     => true,
        'dir_constant'                             => true,
        'ereg_to_preg'                             => true,
        'function_to_constant'                     => true,
        'general_phpdoc_annotation_remove'         => [
            'annotations' => [
                'codeCoverageIgnore',
                'codeCoverageIgnoreEnd',
                'codeCoverageIgnoreStart',
                'expectedException',
                'expectedExceptionCode',
                'expectedExceptionMessage',
                'expectedExceptionMessageRegExp',
            ],
        ],
        'heredoc_to_nowdoc'                        => true,
        'is_null'                                  => [
            'use_yoda_style' => true,
        ],
        'linebreak_after_opening_tag'              => true,
        'list_syntax'                              => [
            'syntax' => 'long',
        ],
        'mb_str_functions'                         => true,
        'method_argument_space'                    => [
            'ensure_fully_multiline'           => true,
            'keep_multiple_spaces_after_comma' => false,
        ],
        'modernize_types_casting'                  => true,
        'no_alias_functions'                       => true,
        'no_extra_consecutive_blank_lines'         => [
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
        'no_unreachable_default_argument_value'     => true,
        'no_useless_else'                           => true,
        'no_useless_return'                         => true,
        'non_printable_character'                   => true,
        'ordered_class_elements'                    => true,
        'ordered_imports'                           => [
            'sortAlgorithm' => 'alpha',
        ],
        'php_unit_construct'                       => true,
        'php_unit_dedicate_assert'                 => true,
        'php_unit_strict'                          => true,
        'php_unit_test_class_requires_covers'      => false,
        'phpdoc_order'                             => true,
        'phpdoc_types_order'                       => [
            'null_adjustment' => 'always_last',
            'sort_algorithm'  => 'alpha',
        ],
        'pow_to_exponentiation'                    => true,
        'psr4'                                     => true,
        'random_api_migration'                     => true,
        'return_type_declaration'                  => [
            'space_before' => 'one',
        ],
        'semicolon_after_instruction'              => true,
        'silenced_deprecation_error'               => true,
        'strict_comparison'                        => true,
        'strict_param'                             => true,
        'ternary_to_null_coalescing'               => true,
        'unary_operator_spaces'                    => false,
        'visibility_required'                      => [
            'elements' => [
//                'const', -- enable after dropping PHP 5 support
                'property',
                'method',
            ],
        ],
        'yoda_style'                               => [
            'equal'            => false,
            'identical'        => false,
            'less_and_greater' => false,
        ],
    ]);
