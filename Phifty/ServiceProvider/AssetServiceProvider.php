<?php

namespace Phifty\ServiceProvider;

use AssetKit;
use AssetKit\AssetConfig;
use AssetKit\AssetLoader;
use AssetKit\AssetRender;
use AssetKit\Cache;
use UniversalCache\ApcuCache;
use UniversalCache\FileSystemCache;
use UniversalCache\UniversalCache;
use Phifty\Kernel;

class AssetServiceProvider extends BaseServiceProvider
{
    public function getId()
    {
        return 'Asset';
    }


    public static canonicalizeConfig(Kernel $kernel, array $options)
    {
        if (!isset($options['BaseUrl'])) {
            $options['BaseUrl'] = '/assets';
        }
        if (!isset($options['BaseDir'])) {
            $options['BaseDir'] = 'webroot/assets';
        }
        return $options;
    }

    /**
     * $kernel->asset->loader
     * $kernel->asset->writer.
     */
    public function register(Kernel $kernel, $options = array())
    {
        $self = $this;
        $kernel->asset = function () use ($kernel, $options, $self) {
            // $assetFile = PH_APP_ROOT . DIRECTORY_SEPARATOR . 'config/assetkit.yml';
            $config = new AssetConfig($options);
            $config->setEnvironment($kernel->environment);
            $config->setNamespace($kernel->getApplicationUUID());
            $cache = new UniversalCache(array(
                new ApcuCache($kernel->getApplicationUUID()),
                new FileSystemCache($kernel->getCacheDir()),
            ));
            $config->setCache($cache);
            $config->setRoot(PH_APP_ROOT);

            $loader = new AssetLoader($config);
            $render = new AssetRender($config, $loader);
            $compiler = $render->getCompiler();
            $compiler->defaultJsCompressor = 'uglifyjs';
            $compiler->defaultCssCompressor = null;

            return (object) array(
                'loader' => $loader,
                'config' => $config,
                'render' => $render,
                'compiler' => $compiler,
                'cache' => $cache,
            );
        };
    }
}
