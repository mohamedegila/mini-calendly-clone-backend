<?php

namespace App\Http\Controllers\Api\V1;

use App\Service\Zoom\ZoomService;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\ZoomApiHelper;
use Illuminate\Http\Request;
use App\Models\EventAtendee;
use App\Models\Event;


use App\Http\Resources\ErrorResource;




class EventAtendeeController extends Controller
{

    public function __construct(private ZoomService $zoomService){}

    function store(Request $request){

        $input = $request->all();



        $eventInfo = Event::where('slug', $input['event_slug'])->first();

        if( $eventInfo){
            $input['event_id'] =  $eventInfo->id ;

            $validatedData = $request->validate([
                'email' => 'unique:event_atendees,email,event_id'. $input['event_id'],
            ]);

            $config = [
                'topic'       => $eventInfo->name,
                'type'        => "2",
                'start_time'  => $input['date'] . 'T'.  $input['start_time'] .'Z',
                'duration'    => $input['duration'],
                'password'    => ''

            ];
            $this->zoomService->configSetter($config);
            $res =  $this->zoomService->createZoomMeeting();


            if($res['response']){
                $input['link'] =  $res['response']['join_url'];

                $eventAtendee = EventAtendee::create($input);

                $details = [
                    'title' => 'Invitation for '. $eventAtendee->atendee->name . ' meeting',
                    'body' => 'You have invited to this meeting with ' . $eventAtendee->atendee->manger->username ,
                    'date' => $eventAtendee['date'],
                    'start_time' => $eventAtendee['start_time'],
                    'end_time' => $eventAtendee['end_time'],
                    'duration' => $eventAtendee['duration'],

                    'link' => $eventAtendee['link']
                ];

                $users[] = $eventAtendee['email'];
                $users[] = $eventAtendee->atendee->manger->email;

                event(new SendMail( $users, $details ));

                return new SuccessResource(Response::HTTP_OK, 'Event registered successfully');
            }else{
                return new ErrorResource(Response::HTTP_NOT_FOUND, 'zoom credentials incorrect', '');

            }

        }else{
            return new ErrorResource(Response::HTTP_NOT_FOUND, 'Event not found', '');
        }

    }
}
