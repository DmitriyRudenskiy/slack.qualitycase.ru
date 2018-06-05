<?php

namespace Tests\Unit\Slack;

use App\Components\Slack\Base;
use App\Components\Slack\GroupClient;
use App\Components\Slack\UserClient;
use Tests\TestCase;

class RoomTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasic()
    {
        $base = new Base();

        $this->assertTrue($base->check());
    }

    public function testGroups()
    {
        $groups = new GroupClient();
        $members = $groups->getMembers();
        $this->assertNotEmpty($members);

        $userClient = new UserClient();

        $users = [];

        foreach ($members as $slackId) {
            $user = $userClient->getUserInfo($slackId);

            if ($user['email'] != env('SLACK_IGOR') && $user['email'] != env('SLACK_DMITRIY')) {
                $users[] = $user;
            }
        }

        dd($users);
    }
}
