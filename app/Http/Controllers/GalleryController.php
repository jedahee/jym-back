<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Gallery;
use JWTAuth;


class GalleryController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');

        $token = $request->header('authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function uploadPicture(Request $request) {
        $path = $request->file('photo')->move(public_path('/images/gallery/'), $request->file('photo')->getClientOriginalName());

        $gallery = Gallery::create([
            'path' => '/images/gallery/'.$request->file('photo')->getClientOriginalName(),
            'mScore' => null,
            'yScore' => null,
        ]);
        return response()->json(['status'=>'success', 'gallery' => $gallery], Response::HTTP_OK);
    }

    public function getPictures(Request $request) {
        $galleries = Gallery::all();
        return response()->json(['status'=>'success', 'galleries' => $galleries], Response::HTTP_OK);
    }

    public function updateValue(Request $request, $photo_id, $user_id) {

        $data = $request->only('value');

        $validator = Validator::make($data, [
            'value' => 'required|integer',
        ]);

        if ($validator->fails())
           return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

       try {
           $photo = Gallery::findOrFail($photo_id);
       } catch (Exception $e) {
           return response()->json([
               'msg' => 'No se encuentra la foto'
           ], Response::HTTP_BAD_REQUEST);
       }

       if ($user_id == 1) {
           $photo->yScore = $request->value;
       } else if ($user_id == 2) {
           $photo->mScore = $request->value;
       }

       $photo->save();

       return response()->json([
           'msg' => 'Se ha actualizado correctamente'
       ], Response::HTTP_OK);
    }

    public function getValues(Request $request, $id) {
        try {
            $photo = Gallery::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'No se encuentra la foto'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'yScore' => $photo->yScore,
            'mScore' => $photo->mScore
        ], Response::HTTP_OK);
    }

    public function removePhoto(Request $request, $id) {
        try {
            $photo = Gallery::findOrFail($id);

        } catch (Exception $e) {
            return response()->json([
                'msg' => 'No se encuentra la foto'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (File::exists(public_path($photo->path))) {
            File::delete(public_path($photo->path));
        }

        if ($photo->delete()) {
            return response()->json([
                'msg' => 'Se ha eliminado la foto correctamente'
            ], Response::HTTP_OK);
        }
    }
}
