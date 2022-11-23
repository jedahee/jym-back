<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Calendar;
use JWTAuth;

class CalendarController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');

        $token = $request->header('authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function getDetailsOfMonth(Request $request) {
        $data = $request->only('month_no', 'year');

        $validator = Validator::make($data, [
            'month_no' => 'required|integer',
            'year' => 'required|integer',
        ]);

        if ($validator->fails())
           return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        $all_details = Calendar::all();
        $all_details_filtered = [];

        foreach ($all_details as $detail) {
            $day = explode('/', $detail["date"])[0];
            $month = explode('/', $detail["date"])[1];
            $year = explode('/', $detail["date"])[2];

            if ($request->year == $year && $request->month_no == $month)
                $all_details_filtered[] = ['day' => $day, 'detail'=>$detail];
        }

        return response()->json($all_details_filtered, Response::HTTP_OK);

    }
}
