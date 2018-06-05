<?php
namespace App\Components\Slack;

use App\Components\Slack\Entity\AuthTest;
use App\Components\Slack\Exception\EmptyToken;

class Base
{
    const BASE_URL = 'https://slack.com/api/';

    /**
     * @var string
     */
    private $token;

    public function __construct()
    {
        if (empty(env("SLACK_TOKEN"))) {
            throw new EmptyToken();
        }

        $this->token = env("SLACK_TOKEN");
    }

    public function check()
    {
        $url = sprintf("%sauth.test?token=%s", self::BASE_URL, $this->getToken());
        $response = $this->send($url);
        $authTest = new AuthTest($response);

        return $authTest->isValid();
    }

    /**
     * @param string $url
     * @return mixed
     */
    protected function send($url)
    {
        // TODO: replace guzzle
        return json_decode(file_get_contents($url));
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}