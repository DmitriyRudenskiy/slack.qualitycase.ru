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
}

