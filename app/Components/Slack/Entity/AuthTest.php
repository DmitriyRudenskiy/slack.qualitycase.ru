<?php
namespace App\Components\Slack\Entity;

use stdClass;

class AuthTest
{
    /**
     * @var
     */
    private $ok;

    /**
     * @var
     */
    private $error;

    /**
     * @var
     */
    private $url;

    /**
     * @var
     */
    private $team;

    /**
     * @var
     */
    private $user;

    /**
     * @var
     */
    private $teamId;

    /**
     * @var
     */
    private $userId;

    public function __construct(stdClass $json)
    {
        if (!isset($json->ok)) {
            // Error response
            throw new \RuntimeException();
        }

        $this->ok = $json->ok;

        if (isset($json->error)) {
            $this->error = $json->error;
        }

        if (isset($json->url)) {
            $this->url = $json->url;
        }

        if (isset($json->team)) {
            $this->team = $json->team;
        }

        if (isset($json->user)) {
            $this->user = $json->user;
        }

        if (isset($json->team_id)) {
            $this->teamId = $json->team_id;
        }

        if (isset($json->user_id)) {
            $this->userId = $json->user_id;
        }
    }

    public function isValid()
    {
        return $this->ok;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}