<?php


namespace ExposureSoftware\SlackLogs\Slack;
use GuzzleHttp\Client;

/**
 * Class Slacker
 *
 * Generic Slack messaging.
 *
 * @package App\Libs
 */
class Slacker
{
    private $webhook = 'https://hooks.slack.com/services/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';
    private $channel = '#development';
    private $user = 'LV2';
    private $message = '';
    private $emoji = ':robot_face:';
    private $attachments = [];
    private $icon = '';

    public function __construct($channel = null, $user = null, $emoji = null)
    {
        $this->channel = $channel ?: $this->channel;
        $this->user = $user ?: $this->user;
        $this->emoji = $emoji ?: $this->emoji;
    }

    /**
     * Quickly send basic message.
     *
     * @param $message
     *
     * @return mixed
     */
    public function send($message = '')
    {
        $this->message = $message;

        return $this->emit();
    }

    /**
     * Adds an attachment to the Slack message.
     *
     * Slack recommends not sending more than twenty attachments.
     *
     * @param Attachment $attachment
     */
    public function addAttachment(Attachment $attachment)
    {
        array_push($this->attachments, $attachment->toArray());
    }

    /**
     * Set the channel this message will be sent to.
     *
     * @param string $channel
     */
    public function setChannel($channel)
    {
        if (strpos($channel, '#') !== 0) {
            $channel = "#{$channel}";
        }

        $this->channel = $channel;
    }

    /**
     * Set the user this message should be sent to.
     *
     * @param string $user
     */
    public function setUser($user)
    {
        if (strpos($user, '@') !== 0) {
            $user = "@{$user}";
        }

        $this->channel = $user;
    }

    /**
     * Sets the name of the bot this message will come from.
     *
     * @param string $name
     */
    public function setSender($name)
    {
        $this->user = $name;
    }

    /**
     * Set the Emoji to use with the message.
     *
     * If the emoji is set it will take precedence over any give icon.
     *
     * @param string $emoji
     *
     * @see setIcon
     */
    public function setEmoji($emoji)
    {
        if (strpos($emoji, ':') !== 0)
        {
            $emoji = ":{$emoji}";
        }

        if (strpos($emoji, ':') !== strlen($emoji) - 1)
        {
            $emoji = "{$emoji}:";
        }

        $this->emoji = $emoji;
    }

    /**
     * Set the icon to use via the provided URL.
     *
     * Emoji will take precedence.
     *
     * @param string $url
     *
     * @see setEmoji
     */
    public function setIcon($url)
    {
        $this->emoji = '';
        $this->icon = $url;
    }

    /**
     * Actually sends the message to the Slack API.
     *
     * cURL is handled here without a wrapper so that this class can be self-contained and shared.
     *
     * @return string
     */
    private function emit()
    {
        $payload = [
            "username"    => $this->user,
            "text"        => $this->message,
            'icon_emoji'  => $this->emoji,
            'channel'     => $this->channel,
            'attachments' => $this->attachments,
        ];

        if ($this->emoji) {
            $payload['icon_emoji'] = $this->emoji;
        } elseif ($this->icon) {
            $payload['icon_url'] = $this->icon;
        }

        /** @var Client $client */
        $client = new Client();
        $output = $client->request('POST', $this->webhook, [
            'json' => $payload,
        ]);

        return $output;
    }
}
