<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Events\Message;
use JWTAuth;

class ChatController extends Controller
{

    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');

        $token = $request->header('authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function message(Request $request) {
        $data = $request->only('username', 'message');

        $validator = Validator::make($data, [
            'username' => 'required|string',
            'message' => 'required|string'
        ]);

        if ($validator->fails())
           return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        event(new Message($request->username, $request->message));

        return response()->json(['username'=>$request->username, 'message'=>$request->message], Response::HTTP_OK);
    }
}
