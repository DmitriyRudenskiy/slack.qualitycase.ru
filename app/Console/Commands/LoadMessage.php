<?php

namespace App\Console\Commands;

use App\Components\Slack\Client;
use App\Models\Channels;
use App\Models\Members;
use App\Models\Messages;
use Illuminate\Console\Command;
use InvalidArgumentException;

class LoadMessage extends Command
{
    const FLAG_FINISH_SYNC = "FINISH";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:message';

    private $users;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        do {
            $channel = Channels::where("cursor", null)
                ->orWhere("cursor", "<>", self::FLAG_FINISH_SYNC)
                ->first();

            $this->info("Load");

            $this->loadFromChanel($channel);

            sleep(5);
        } while ($channel !== null);
    }

    protected function loadFromChanel($channel)
    {
        if ($channel === null) {
            return null;
        }

        $this->info("Load chanel ID: " . $channel->id);

        $client = new Client();
        $response = $client->getMessage($channel->name, $channel->cursor);

        if ($response === null) {
            $this->error("Empty response");
            return null;
        }

        foreach ($response->message as $value) {
            $userId = $this->getUser($value->user);
            $createAt = new \DateTime();
            $createAt->setTimestamp(((int)$value->ts));

            $message = Messages::where("channel_id", $channel->id)
                ->where("user_id", $userId)
                ->where("added_on", $createAt)
                ->first();

            if ($message === null) {
                $this->info($userId);

                $message = new Messages();
                $message->channel_id = $channel->id;
                $message->user_id = $userId;
                $message->added_on = $createAt;
                $message->description = $value->text;
                $message->save();
            }
        }

        if (!empty($response->cursor)) {
            $channel->cursor = $response->cursor;
        } else {
            $channel->cursor = self::FLAG_FINISH_SYNC;
        }

        $channel->save();
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