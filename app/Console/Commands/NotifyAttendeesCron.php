<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\EventAtendee;

use App\Events\NotifyAttendeeMail;

class NotifyAttendeesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyAttendees:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        \Log::info("Notify Attendees");


        $timeBeforeEvent = Carbon::now()->addHour()->format('H:i');
        $date =  Carbon::today()->format('Y-m-d');
        $data = EventAtendee::whereDate('date', '=' , $date)->where('start_time', $timeBeforeEvent)->with('atendee.manger')->get();

        $emails = [];
        $details = [];
        foreach($data as $idx => $item){
            $details[$idx]['title'] = $item['atendee']['name'] . " Meeting is about to start";
            $details[$idx]['link'] = $item['link'];

            $details[$idx]['users'][] = $item['email'];
            $details[$idx]['users'][] = $item['atendee']['manger']['email'];

            event(new NotifyAttendeeMail( $details[$idx]['users'], $details[$idx] ));
        }
        \Log::info($details);
        return true;
    }
}
