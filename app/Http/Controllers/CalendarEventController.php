<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use JWTAuth;

class CalendarEventController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');

        $token = $request->header('authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function addEventCalendar(Request $request) {
        $data = $request->only('eventId', 'date');
        $canAdd = true;

        $validator = Validator::make($data, [
            'eventId' => 'required|integer',
            'date' => 'required|string'
        ]);

        if ($validator->fails())
           return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        $calendar_events = CalendarEvent::all();

        foreach ($calendar_events as $ce) {
            if ($ce["eventId"] == $request->eventId && $ce["date"] == $request->date)
                $canAdd = false;
        }

        if ($canAdd) {
            $calendar_event = CalendarEvent::create([
                'eventId'=>$request->eventId,
                'date'=>$request->date,

            ]);
            return response()->json(['calendar_event'=>$calendar_event], Response::HTTP_OK);

        }

        return response()->json(['msg' => 'Este evento ya esta añadido'], Response::HTTP_BAD_REQUEST);


    }

    public function getCalendarEvents(Request $request) {
        $calendar_events = CalendarEvent::all();
        return response()->json(['calendar_events' => $calendar_events], Response::HTTP_OK);
    }

    public function delCalendarEvent(Request $request, $id) {
        try {
            $ce = CalendarEvent::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Este evento no existe'], Response::HTTP_BAD_REQUEST);
        }

        $ce->delete();

        return response()->json(['success' => true], Response::HTTP_OK);
    }
}
