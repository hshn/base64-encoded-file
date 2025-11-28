<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hshn\Base64EncodedFile\Serializer\Base64EncodedFileNormalizer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->set(Base64EncodedFileNormalizer::class, Base64EncodedFileNormalizer::class)
        ->private()
        ->tag('serializer.normalizer', ['priority' => 1]);
};
