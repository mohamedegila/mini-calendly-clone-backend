<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Repository\EventRepository;
use App\Repository\UserRepository;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\ErrorResource;
use Symfony\Component\HttpFoundation\Response;


use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EventController extends Controller
{

    public function __construct(private EventRepository $eventRepository,
                                private UserRepository $userRepository){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter = ['user_id'=> auth()->user()->id];
        $events =  $this->eventRepository->get( $filter, false, false, false, false, false, "created_at", "id", "asc", false );

        foreach($events as $event){
            $event['timeSteps'] = $this->timeSteps($event['duration'], $event['start_time'], $event['end_time']);
        }
        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $input = $request->all();
        $input['user_id'] =  auth()->user()->id;
        $event = $this->eventRepository->store($input);
        // $event['timeSteps'] = $this->timeSteps($event['duration'], $event['start_time'], $event['end_time']);
        return EventResource::make($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if($event['user_id'] === auth()->user()->id){
            $event['timeSteps'] = $this->timeSteps($event['duration'], $event['start_time'], $event['end_time']);
            return EventResource::make($event);
        }else{
            return new ErrorResource(Response::HTTP_NOT_FOUND, 'Event not found', 'NOT_FOUND');

        }
    }

       /**
     * Display the specified resource.
     * @param   $user_id
     * @param   $event
     * @return \Illuminate\Http\Response
     */
    public function userEvent($user_slug, $event_slug)
    {
        $user = $this->userRepository->show($user_slug, false, ['events' => function($q) use($event_slug){
            $q->where('slug', $event_slug)->get();
        }], 'slug');

        // dd($user->events[0]);
        if($user && count($user->events) > 0){
            $event = $user->events[0];
            $event['timeSteps'] = $this->timeSteps( $event['start_date'], $event['end_date'], $event['start_time'], $event['end_time'], $event['duration'],);
            return EventResource::make($event->load('manger'));
        }else{
            return new ErrorResource(Response::HTTP_NOT_FOUND, 'Event not found', 'NOT_FOUND');

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    private function timeSteps($start_date, $end_date, $start, $end, $step){
        // $stepHours = substr($step, 0, 2);
        // $stepMinutes = substr($step, 3, 2);
        // $stepSeconds = substr($step, 6, 2);

        $period = CarbonPeriod::create($start_date, $end_date);


// Convert the period to an array of dates
$dates = $period->toArray();

foreach ($dates as $date) {
    $date = $date->format('Y-m-d');
    $startTime = Carbon::createFromFormat('H:i:s', $start);
    $endTime = Carbon::createFromFormat('H:i:s', $end);

    // $result[$date] = [];

    while ($startTime->lt($endTime)) {
        $item = [];
        array_push($item, $startTime->format('H:i:s'));

        // $startTime->addHours($stepHours);
        $startTime->addMinutes($step);
        // $startTime->addSeconds($stepSeconds);

        array_push($item, $startTime->format('H:i:s'));

        //  array_push($result, [$date => $item] );

         $result[$date][] = $item;
    }
}


        return $result;
    }
}
