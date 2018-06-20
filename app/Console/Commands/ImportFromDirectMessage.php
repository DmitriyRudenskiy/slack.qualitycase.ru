<?php

namespace App\Console\Commands;

use App\Components\Slack\Client;
use App\Models\Channels;
use App\Models\Members;
use App\Models\Messages;
use App\Models\Students;
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
        $students = Students::where("is_master", false)->get();

        foreach ($students as $value) {
            $this->getMessages($value);
        }

        $this->info("Finish");
    }

    /**
     * @param Students $members
     */
    protected function getMessages(Students $members)
    {
        $this->info("Load message for user: " . $members->name);

        // номер чата для общения с пользователем
        $apiClient = new Client();
        $channelId = $apiClient->getChannel($members->slack_id);

        $this->info("Find chanel: " . $channelId);

        // сообщение
        $messages = $apiClient->getMessageFromChannel($channelId);

        $this->info("Load all message: " . sizeof($messages));

        foreach ($messages as $value) {
            $this->add($members->id, $value);
        }
    }

    /**
     * @param string $channelMemberId
     * @param $message
     */
    protected function add($channelMemberId, $message)
    {
        $userId = $this->getUser($message->user);
        $createAt = new \DateTime();
        $createAt->setTimestamp(((int)$message->ts));

        $model = Messages::where("channel_member_id", $channelMemberId)
            ->where("member_id", $userId)
            ->where("added_on", $createAt)
            ->first();

        if ($model === null) {
            $model = new Messages();
            $model->channel_member_id = $channelMemberId;
            $model->member_id = $userId;
            $model->added_on = $createAt;
            $model->description = $message->text;
            $model->save();
        }
    }

    /**
     * @param string $slackId
     * @return integer
     */
    public function getUser($slackId)
    {
        if (!empty($this->users[$slackId])) {
            return $this->users[$slackId];
        }

        $member = Students::where("slack_id", $slackId)->first();

        if ($member === null) {
            $this->error("Not find for: " . $slackId);
            return null;
        }

        $this->users[$slackId] = $member->id;

        return $member->id;
    }
}