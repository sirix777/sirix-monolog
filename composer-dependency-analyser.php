<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->ignoreErrorsOnPackages([
        'graylog2/gelf-php',
        'mongodb/mongodb',
        'ruflin/elastica',
        'sirix/redaction',
    ], [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackages([
        'elasticsearch/elasticsearch',
    ], [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnExtensions([
        'ext-mongodb',
    ], [ErrorType::SHADOW_DEPENDENCY]);
