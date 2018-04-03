<?php
namespace App\Components\Slack;


class Client
{
    const BASE_URL = "https://slack.com/api";

    public function getMessage($channel, $cursor = null)
    {
        $url = self::BASE_URL
            . "/conversations.history?"
            . "token=" . env("SLACK_TOKEN");

        if (!empty($cursor)) {
            $url .= "&cursor=" . $cursor;
        }

        $url .= "&pretty=1";

        $json = json_decode(file_get_contents($url));

        if (empty($json->messages)) {
            return null;
        }

        return (object)[
            "message" => $json->messages,
            "cursor" => !empty($json->response_metadata->next_cursor) ? $json->response_metadata->next_cursor : null
        ];
    }

    /**
     * @param $slackId
     * @return string
     */
    public function getChannel($slackId)
    {
        $url = sprintf(
            "https://slack.com/api/im.open?token=%s&user=%s&pretty=1",
            env("SLACK_TOKEN"),
            $slackId
        );

        $content = file_get_contents($url);

        if (empty($content)) {
            dd(__METHOD__, $url);
        }

        $json = json_decode($content);

        if (empty($json->channel->id)) {
            dd(__METHOD__, $json);
        }

        return $json->channel->id;
    }

    public function getMessageFromChannel($channelId)
    {
        $url = sprintf(
            "https://slack.com/api/im.history?token=%s&channel=%s&count=1000&pretty=1",
            env("SLACK_TOKEN"),
            $channelId
        );

        $content = file_get_contents($url);

        if (empty($content)) {
            dd(__METHOD__, $url);
        }

        $json = json_decode($content);

        if (empty($json->messages)) {
            $this->error(__METHOD__);
            dd($url);
        }

        return $json->messages;
    }
}

