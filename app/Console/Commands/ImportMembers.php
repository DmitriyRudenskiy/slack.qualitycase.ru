<?php

namespace App\Console\Commands;

use App\Models\Members;
use Illuminate\Console\Command;
use InvalidArgumentException;

class ImportMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:members {--filename=}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = $this->option('filename');

        $filename = storage_path($filename);

        if (!file_exists($filename)) {
            throw new InvalidArgumentException();
        }

        $this->info("Load users form: " . $filename);

        $list = json_decode(file_get_contents($filename));


        foreach ($list as $value) {
            if (!empty($value->real_name)) {
                $this->addMember($value->id, $value->real_name);
            }
        }
    }

    protected function addMember($slackId, $name)
    {
        $member = new Members();
        $member->slack_id = $slackId;
        $member->slack_name = $name;

        try {
            $member->save();
        } catch (\Exception $e) {

        }
    }
}