<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    // Define what rule sets will be applied
    $containerConfigurator->import(DowngradeSetList::PHP_80);

    // get services (needed for register a single rule)
//     $services = $containerConfigurator->services();

    // register a single rule
//     $services
//         ->set(DowngradeReadonlyPropertyRector::class)
//         ->set(DowngradeArraySpreadStringKeyRector::class)
//         ->set(DowngradeFinalizePublicClassConstantRector::class)
//         ->set(DowngradeFirstClassCallableSyntaxRector::class)
//         ->set(DowngradeNeverTypeDeclarationRector::class)
//         ->set(DowngradeNewInInitializerRector::class)
//         ->set(DowngradePhp81ResourceReturnToObjectRector::class)
//         ->set(DowngradePureIntersectionTypeRector::class);
};
