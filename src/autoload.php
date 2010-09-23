<?php

require_once __DIR__.'/vendor/symfony/src/Symfony/Component/HttpFoundation/UniversalClassLoader.php';

use Symfony\Component\HttpFoundation\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\\Bootstrapper' => __DIR__,
    'Symfony'               => __DIR__.'/vendor/symfony/src/',
));
$loader->register();
