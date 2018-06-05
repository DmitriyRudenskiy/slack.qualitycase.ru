<?php
namespace App\Components\Slack\Entity;

use stdClass;

class Groups
{
    private $membersIds = [];

    public function __construct(stdClass $json)
    {
        if (!isset($json->ok)) {
            // Error response
            throw new \RuntimeException();
        }

        $this->ok = $json->ok;

        $group = $json->groups[0];

        // не добавляем создателя группы
        foreach ($group->members as $value) {
            if ($value != $group->creator) {
                $this->membersIds[] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getMembersIds()
    {
        return $this->membersIds;
    }
}