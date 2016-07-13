<?php


namespace ThatsUs\SlackLogs\Providers;


use ThatsUs\SlackLogs\Listeners\LogHandler;
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
    /** @var string $channel */
    protected $channel;
    /** @var string $user */
    protected $user;
    /** @var string $hook */
    protected $hook;
    /** @var int $level */
    protected $level;

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
                // Create custom handler
                $handler = new LogHandler($this->hook, $this->channel, $this->user, $this->level);

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
