<?php


namespace ExposureSoftware\SlackLogs\Slack;

/**
 * Class Attachment
 *
 * A Slack attachment for more robust formatting options.
 *
 * @package App\Libs\Slack
 */
class Attachment
{
    private $fallback;
    private $author = [
        'name' => 'LV2 Logs',
        'icon' => '',
        'url'  => '',
    ];
    private $title = [
        'text' => '',
        'url'  => '',
    ];
    private $image = [
        'full'  => '',
        'thumb' => '',
    ];
    private $footer = [
        'text' => "Slacker (Logs to Slack)",
        'icon' => '',
    ];
    private $color = "#36a64f"; // Green
    private $timestamp;
    private $fields = [];

    /**
     * Attachment constructor.
     *
     * The message parameter is the text displayed before the content, and the fallback message.
     *
     * @param string $message
     * @param string $title
     * @param string $content
     */
    public function __construct($title, $content, $message = '')
    {
        $this->message = $message;
        $this->content = $content;
        $this->title['text'] = $title;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        if (strpos($color, '#') !== 0) {
            $color = "#{$color}";
        }

        $this->color = $color;
    }

    /**
     * Adds a field to the attachment.
     *
     * @param string $title
     * @param mixed  $value
     * @param mixed  $short
     */
    public function addField($title, $value, $short = null)
    {
        array_push($this->fields, [
            'title' => $title,
            'value' => $value,
            'short' => $short,
        ]);
    }

    /**
     * Updates information about the author.
     *
     * @param string $name
     * @param string $url
     * @param string $image
     */
    public function setAuthor($name, $url = '', $image = '')
    {
        $this->author = array_merge($this->author, [
            'name' => $name,
            'url'  => $url,
            'icon' => $image,
        ]);
    }

    /**
     * Updates the attachment title.
     *
     * @param string $title
     * @param string $url
     */
    public function setTitle($title, $url = '')
    {
        $this->title = array_merge($this->title, [
            'text' => $title,
            'url'  => $url,
        ]);
    }

    /**
     * Sets the message for the attachment.
     *
     * The message is the text that appears before the formatted attachment and after the sent input.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Sets the formatted content.
     *
     * The content is within the formatted attachment. Special markup is allowed. See Slack documentation for full details.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Sets the attachment image.
     *
     * @param string $url
     * @param string $thumb
     */
    public function setImage($url, $thumb = '')
    {
        $this->image = array_merge($this->image, [
            'full'  => $url,
            'thumb' => $thumb,
        ]);
    }

    /**
     * Sets the time of the attachment.
     *
     * Useful if the Slack message is referring to an event that has or will happen in another time.
     *
     * @param $timestamp
     */
    public function setTime($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Converts the Attachment into array required for inclusion in a Slack message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'fallback'    => $this->fallback ?: $this->title,
            "color"       => $this->color,
            "pretext"     => $this->message,
            "author_name" => $this->author['name'],
            "author_link" => $this->author['url'],
            "author_icon" => $this->author['icon'],
            "title"       => $this->title['text'],
            "title_link"  => $this->title['url'],
            "text"        => $this->content,
            "fields"      => $this->fields,
            "image_url"   => $this->image['full'],
            "thumb_url"   => $this->image['thumb'],
            "footer"      => $this->footer['text'],
            "footer_icon" => $this->footer['icon'],
            "ts"          => $this->timestamp ?: time(),
        ];
    }
}
