<?php
use LazyRecord\Schema\SchemaLoader;
$finder = new LazyRecord\Schema\SchemaFinder;
if ($app = kernel()->getApp()) {
    $finder->in($app->locate() . DIRECTORY_SEPARATOR . 'Model');
}


if ($bundles = kernel()->bundles) {
    foreach ($bundles as $bundle) {
        $finder->in($bundle->locate() . DIRECTORY_SEPARATOR . 'Model');
    }
}
$finder->find();
if (method_exists($finder,"getSchemas")) {
    return $finder->getSchemas();
}
return $finder;
return SchemaLoader::loadDeclaredSchemas();
