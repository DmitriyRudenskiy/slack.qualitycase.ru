<?php

namespace App\Console\Commands;

use App\Components\Slack\Client;
use App\Components\Slack\GroupClient;
use App\Components\Slack\UserClient;
use App\Models\Channels;
use App\Models\Members;
use App\Models\Messages;
use App\Models\Students;
use Illuminate\Console\Command;
use InvalidArgumentException;

class AutoFillFromSlack extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:fill:students';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $groups = new GroupClient();
        $members = $groups->getMembers();

        if (empty($members)) {
            $this->error('Not find students');
            return 0;
        }

        $userClient = new UserClient();

        $users = [];


        // Формируем список пользователей
        foreach ($members as $slackId) {
            $user = $userClient->getUserInfo($slackId);

            if ($user['email'] != env('SLACK_IGOR') && $user['email'] != env('SLACK_DMITRIY')) {
                $user['access_key'] = str_random(32);
                $users[] = $user;
            }
        }


        foreach ($users as $value) {
            try {
                Students::forceCreate($value);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}