<?php
/**
 *
 * This file is part of the Phifty package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phifty\Environment;

use Exception;
use ErrorException;
use Phifty\Kernel;

class Development extends Environment
{
    public static function exceptionErrorHandler($errno, $errstr, $errfile, $errline)
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function exceptionHandler(Exception $e)
    {
        // var_dump( $e );
    }

    // XXX: does not work with E_PARSE error
    // register_shutdown_function(array(__CLASS__,'shutdownHandler'));
    public static function shutdownHandler()
    {
        if (is_null($e = error_get_last()) === false) {
            print_r($e);
        }
    }

    public static function boot(Kernel $kernel)
    {
        error_reporting(E_ALL);

        // @link http://www.php.net/manual/en/function.set-error-handler.php
        set_error_handler([__CLASS__,'exceptionErrorHandler'], E_ALL & ~E_NOTICE);
        // set_exception_handler([__CLASS__,'exceptionHandler']);

        // if firebug supports
        if ($kernel->isCli && getenv('PHIFTY_PROFILE')) {
            $kernel->event->register('phifty.console.run.after', function () use ($kernel) {
                // echo 'memory usage: ', (int) (memory_get_usage() / 1024  ) , ' KB', PHP_EOL;
                echo 'Memory peak usage: ', (int) (memory_get_peak_usage() / 1024), ' KB' . PHP_EOL;
                echo 'Duration: ', ceil((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000000), ' microseconds' ;
            });
        }
    }
}
