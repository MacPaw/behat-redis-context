<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon.dist'])
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: false,
        typeDeclarations: false,
        typeDeclarationDocblocks: false,
        privatization: false,
        naming: false,
        instanceOf: false,
        earlyReturn: true,
        phpunitCodeQuality: true,
        symfonyCodeQuality: true,
        symfonyConfigs: false,
    )
    ->withImportNames(importNames: true, importDocBlockNames: true, removeUnusedImports: true);
