<?php
namespace App\Components\Slack;

use App\Components\Slack\Entity\Groups;

class GroupClient extends Base
{
    /**
     * Получаем список пользователей в приватной группе.
     *
     * @return mixed
     */
    public function getMembers()
    {
        $url = sprintf("%sgroups.list?token=%s", self::BASE_URL, $this->getToken());
        $response = $this->send($url);

        $groups = new Groups($response);
        return $groups->getMembersIds();
    }
}