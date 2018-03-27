<?php

namespace App\Console\Commands;

use App\Components\Slack\Client;
use App\Models\Channels;
use App\Models\Members;
use App\Models\Messages;
use Illuminate\Console\Command;
use InvalidArgumentException;

class ImportFromDirectMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:direct:message';

    private $users;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->getListUsers();

        foreach ($users as $value) {
            $this->info("Load message for user: " . $value);
            $this->getMessages($value);
        }
    }

    protected function getListUsers()
    {
        /*
        return [
            //0 => "USLACKBOT",
            1 => "U94H68GJ0",
            2 => "U94S8M3QQ",
            3 => "U95EF8G03",
            4 => "U9606TPMY",
            5 => "U966PTWD9",
            6 => "U96DDMSRJ",
            7 => "U96H2HS4A",
            8 => "U96KHGXF0",
            9 => "U974M969G",
            10 => "U97ALMHFS",
            11 => "U97PA7DLK",
            12 => "U99DS6W6N",
            13 => "U99UE3G5P",
            14 => "U9AURAG1E",
            15 => "U9B19DC9H",
            16 => "U9BPX5JCE"
        ];

        $url = "https://slack.com/api/im.list?token=" . env("SLACK_TOKEN") . "&pretty=1";

        $json = json_decode(file_get_contents($url));

        $data = array_map(function($value) {
            return $value->user;
        }, (array)$json->ims);

        return $data;
        */

        $result = [];

        $list = Members::where("chanel_id", "<>", null)->get();

        foreach ($list as $value) {
            $result[] = $value->slack_id;
        }

        return $result;
    }

    protected function getChannel($user)
    {
        $url = sprintf(
            "https://slack.com/api/im.open?token=%s&user=%s&pretty=1",
            env("SLACK_TOKEN"),
            $user
        );

        $json = json_decode(file_get_contents($url));
        sleep(3);

        if (empty($json->channel->id)) {
            $this->error(__METHOD__);
            dd($json);
        }

        return $json->channel->id;
    }

    protected function getMessages($user)
    {
        $channel = $this->getChannel($user);

        $url = sprintf(
            "https://slack.com/api/im.history?token=%s&channel=%s&count=1000&pretty=1",
            env("SLACK_TOKEN"),
            $channel
        );

        $json = json_decode(file_get_contents($url));
        sleep(3);

        if (empty($json->messages)) {
            $this->error(__METHOD__);
            dd($url);
        }

        foreach ($json->messages as $value) {
            $userId = $this->getUser($value->user);
            $createAt = new \DateTime();
            $createAt->setTimestamp(((int)$value->ts));

            $message = Messages::where("channel_id", $channel)
                ->where("user_id", $userId)
                ->where("added_on", $createAt)
                ->first();

            if ($message === null) {
                $message = new Messages();
                $message->channel_id = $channel;
                $message->user_id = $userId;
                $message->added_on = $createAt;
                $message->description = $value->text;
                $message->save();
            }
        }
    }

    public function getUser($slackId)
    {
        if (!empty($this->users[$slackId])) {
            return $this->users[$slackId];
        }

        $member = Members::where("slack_id", $slackId)->first();

        if ($member === null) {
            $this->error("Not find for: " . $slackId);
            return null;
        }

        $this->users[$slackId] = $member->id;

        return $member->id;
    }
}