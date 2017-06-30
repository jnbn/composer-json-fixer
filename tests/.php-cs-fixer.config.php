<?php

// PHP-CS-Fixer v2.3.2
return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony'                                 => true,
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
//        'declare_strict_types'                     => true, // forcing strict types will stop non strict code from working -- enable after dropping PHP 5 support
        'dir_constant'                             => true, // risky when the function "dirname()" is overridden
        'ereg_to_preg'                             => true, // risky if the "ereg" function is overridden
        'function_to_constant'                     => true,  // risky when any of the configured functions to replace are overridden.
        'general_phpdoc_annotation_remove'         => [
            'annotations' => [
                'author',
                'category',
                'copyright',
                'version',
            ],
        ],
        'heredoc_to_nowdoc'                        => true,
        'is_null'                                  => [ // risky when the function "is_null()" is overridden.
            'use_yoda_style' => true,
        ],
        'linebreak_after_opening_tag'              => true,
        'list_syntax'                              => [
            'syntax' => 'long',
        ],
        'mb_str_functions'                         => true, // risky when any of the functions are overridden.
        'method_argument_space'                    => [
//            'ensure_fully_multiline'           => true, // enable after new PHP-CS-Fixer is released
            'keep_multiple_spaces_after_comma' => false,
        ],
        'modernize_types_casting'                  => true, // risky if any of the functions "intval", "floatval", "doubleval", "strval" or "boolval" are overridden.
        'no_alias_functions'                       => true, // risky when any of the alias functions are overridden.
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
        'no_php4_constructor'                       => true, // risky when old style constructor being fixed is overridden or overrides parent one
        'no_unreachable_default_argument_value'     => true, // modifies the signature of functions; therefore risky when using systems (such as some Symfony components) that rely on those (for example through reflection)
        'no_useless_else'                           => true,
        'no_useless_return'                         => true,
        'non_printable_character'                   => true, // risky when strings contain intended invisible characters
        'ordered_class_elements'                    => true,
        'ordered_imports'                           => [
            'sortAlgorithm' => 'alpha',
        ],
        'php_unit_construct'                       => true, // fixer could be risky if one is overriding PHPUnit's native methods
        'php_unit_dedicate_assert'                 => true, // fixer could be risky if one is overriding PHPUnit's native methods
        'php_unit_strict'                          => true, // risky when any of the functions are overridden
        'phpdoc_order'                             => true,
        'pow_to_exponentiation'                    => true, // risky when the function "pow()" is overridden
        'psr4'                                     => true, // this fixer may change you class name, which will break the code that is depended on old name
        'random_api_migration'                     => true, // risky when the configured functions are overridden
        'return_type_declaration'                  => [
            'space_before' => 'one',
        ],
        'semicolon_after_instruction'              => true,
        'silenced_deprecation_error'               => true, // silencing of deprecation errors might cause changes to code behaviour
        'strict_comparison'                        => true, // changing comparisons to strict might change code behavior
        'strict_param'                             => true, // risky when the fixed function is overridden or if the code relies on non-strict usage
        'ternary_to_null_coalescing'               => true,
        'visibility_required'                      => [
            'elements' => [
//                'const', -- enable after dropping PHP 5 support
                'property',
                'method',
            ],
        ],
    ]);
