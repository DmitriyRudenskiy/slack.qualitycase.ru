<?php
namespace App\Components\Slack;

class UserClient extends Base
{
    /**
     * Получаем список пользователей в приватной группе.
     *
     * @return mixed
     */
    public function getUserInfo($slackId)
    {
        $url = sprintf(
            "%susers.info?token=%s&user=%s&include_locale=0",
            self::BASE_URL,
            $this->getToken(),
            $slackId
        );

        $response = $this->send($url);

        $data = [
            'slack_id' => $response->user->id,
            'email' => $response->user->profile->email
        ];

        if (empty($response->user->profile->first_name) || empty($response->user->profile->last_name)) {
            $data['first_name'] = $response->user->real_name;
        } else {
            $data['first_name'] = $response->user->profile->first_name;
            $data['last_name'] = $response->user->profile->last_name;
        }

        return $data;
    }
}