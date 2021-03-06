<?php

namespace Phifty\ServiceProvider;

use Phifty\Kernel;
use Pux\Mux;
use Pux\MuxBuilder\RESTfulMuxBuilder;

class PuxRouterServiceProvider extends ServiceProvider
{
    public function getId()
    {
        return 'router';
    }

    public function register(Kernel $kernel, array $options = array())
    {
        $kernel->mux = function () {
            return new Mux();
        };
        $kernel->restful = function () use ($kernel) {
            return new RESTfulMuxBuilder($kernel->mux, ['prefix' => '/=']);
        };
    }
}
