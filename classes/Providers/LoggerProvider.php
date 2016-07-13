<?php


namespace ExposureSoftware\SlackLogs\Providers;


use ExposureSoftware\SlackLogs\Listeners\LogHandler;
use Illuminate\Container\Container;
use Illuminate\Log\Writer;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

/**
 * Class LoggerProvider
 *
 * Thanks Steve Bauman, http://stackoverflow.com/a/32230400/583608
 *
 * @package App\Providers
 */
class LoggerProvider extends ServiceProvider
{
    /**
     * Push the eloquent handler to monolog on boot.
     */
    public function boot()
    {
        $logger = Container::getInstance()->make('log', []);

        // Make sure the logger is a Writer instance
        if($logger instanceof Writer) {
            $monolog = $logger->getMonolog();

            // Make sure the Monolog Logger is returned
            if($monolog instanceof Logger) {
                // Create your custom handler
                $handler = new LogHandler();

                // Push it to monolog
                $monolog->pushHandler($handler);
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
