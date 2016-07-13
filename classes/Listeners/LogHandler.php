<?php


namespace ExposureSoftware\SlackLogs\Listeners;


use ExposureSoftware\SlackLogs\Slack\Attachment;
use ExposureSoftware\SlackLogs\Slack\Slacker;
use Illuminate\Container\Container;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

class LogHandler extends AbstractHandler
{
    /**
     * Handles the incoming log record.
     *
     * @param array $record
     *
     * @return void
     */
    public function handle(array $record)
    {
        if ($record['level'] > Logger::WARNING) {
            /** @var Slacker $slack */
            $slack = Container::getInstance()->make('\App\Libs\Slack\Slacker', ['#lv2-logs', 'LV2 Logs', ':warning:']);
            /** @var Attachment $attachment */
            $attachment = Container::getInstance()->make('\App\Libs\Slack\Attachment', ["New Log Entry", $record['message']]);

            $attachment->setColor($this->colorForLevel($record['level_name']));
            $attachment->addField('Log Level', $record['level_name'], $record['level']);

            $slack->addAttachment($attachment);
            $slack->setEmoji($this->emojiForLevel($record['level_name']));

            $slack->send();
        }
    }

    /**
     * Retrieves the appropriate color for the attachment.
     *
     * @param $level
     *
     * @return string
     */
    private function colorForLevel($level)
    {
        switch(strtoupper($level)) {
            case 'DEBUG':
                $color = '#ffff00';
                break;
            case 'WARNING':
                $color = '#ffa500';
                break;
            case 'ERROR':
                $color = '#880000';
                break;
            default:
                $color = '#36a64f';
        }

        return $color;
    }

    /**
     * Retrieves the appropriate emoji for the given log level.
     *
     * @param $level
     *
     * @return string
     */
    private function emojiForLevel($level)
    {
        switch(strtoupper($level)) {
            case 'ERROR':
                $emoji = ':fire:';
                break;
            default:
                $emoji = ':warning:';
        }

        return $emoji;
    }
}
