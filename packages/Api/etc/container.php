<?php
declare(strict_types=1);

use DI\ContainerBuilder;

require_once __DIR__.'/../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    
]);

return $containerBuilder->build();
