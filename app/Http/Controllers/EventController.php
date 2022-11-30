<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Event;
use JWTAuth;


class EventController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');

        $token = $request->header('authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getEvents(Request $request) {
        $events = Event::all();

        return response()->json(['events' => $events], Response::HTTP_OK);
    }

    public function addEvent(Request $request) {
        $data = $request->only('name');

        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        if ($validator->fails())
           return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        $event = Event::create([
            'name'=>$request->name,
        ]);

        return response()->json(['event'=>$event, 'msg'=>'Se añadió correcctamente'], Response::HTTP_OK);
    }

    public function delEvent(Request $request, $id) {
        try {
            Event::destroy($id);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Este evento no existe'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['msg' => 'Evento eliminado correctamente'], Response::HTTP_OK);
    }

}
