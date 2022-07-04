<?php

declare(strict_types=1);

use PhpCsFixer\Finder;

$finder = Finder::create()
                ->in(__DIR__.'/bin')
                ->in(__DIR__.'/config')
                ->in(__DIR__.'/public')
                ->in(__DIR__.'/src')
                ->in(__DIR__.'/tests')
                ->append([
                             __DIR__.'/composer-unused.php',
                             __FILE__,
                         ]);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
                   '@PHP81Migration'        => true,
                   '@Symfony:risky'         => true,
                   'declare_strict_types'   => true,
                   '@Symfony'               => true,
                   'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
                   'braces'                 => [
                       'position_after_control_structures'   => 'next',
                       'position_after_anonymous_constructs' => 'next',
                   ],
                   'no_unneeded_curly_braces' => false,
                   'phpdoc_align'             => [
                       'tags' => [
                           'param',
                           'property-read',
                           'property-write',
                           'property',
                           'return',
                           'throws',
                           'type',
                           'var',
                           'method',
                       ],
                   ],
                   'phpdoc_no_alias_tag' => [
                       'replacements' => [
                           'type' => 'var',
                           'link' => 'see',
                       ],
                   ],
                   'phpdoc_order'      => true,
                   'phpdoc_to_comment' => [
                       'ignored_tags' => ['psalm-var', 'psalm-suppress'],
                   ],
                   '@PHP80Migration:risky' => true,
               ])
    ->setFinder($finder);
