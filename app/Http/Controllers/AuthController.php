<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('name', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], Response::HTTP_OK);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], Response::HTTP_OK);
    }

    public function me() {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ], Response::HTTP_OK);
    }

    public function getPathImage(Request $request) {
        $request->validate([
            'id_user' => 'required|integer',
        ]);

        $id = $request->only('id_user');

        try {
            $user = User::findOrFail($id);
            return response()->json(['status'=>'success', 'path' => $user[0]->path], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
        }


    }

    public function getUserById(Request $request, $id) {
        try {
            $user = User::findOrFail($id);
            return response()->json(['status'=>'success', 'user' => $user], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
        }
    }

    public function setName(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string'
        ]);

        $credentials = $request->only('id', 'name');

        try {
            $user = User::findOrFail($credentials["id"]);
            $user->update([
                'name' => $credentials["name"],
            ]);

            return response()->json(['msg'=>'Se ha actualizado el nombre correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
        }

    }

    public function setPhoto(Request $request, $id) {
        // Jesús
        if ($id == 1) {
            $path = $request->file('photo')->move(public_path('/images/user/jesus/'), $request->file('photo')->getClientOriginalName());
            try {
                $user = User::findOrFail($id);
                $user->update([
                    'path' => 'public/images/user/jesus/'.$request->file('photo')->getClientOriginalName()
                ]);
                return response()->json(['msg'=>'Se ha actualizado la foto correctamente', 'path' => $user->path], Response::HTTP_OK);

            } catch (Exception $e) {
                return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
            }
        // Macarena
        } else if ($id == 2) {
            $path = $request->file('photo')->move(public_path('/images/user/macarena/'), $request->file('photo')->getClientOriginalName());
            try {
                $user = User::findOrFail($id);
                $user->update([
                    'path' => 'public/images/user/macarena/'.$request->file('photo')->getClientOriginalName()
                ]);
                return response()->json(['msg'=>'Se ha actualizado la foto correctamente', 'path' => $user->path], Response::HTTP_OK);

            } catch (Exception $e) {
                return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
            }
        // Error
        } else
            return response()->json(['status'=>'error', 'msg' => 'Fallo interno. id no esperado'], Response::SERVER_ERROR);
    }

    public function setPhotoOld(Request $request, $id) {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'path' => $request->path
            ]);
            return response()->json(['msg'=>'Se ha actualizado la foto correctamente', 'path' => $user->path], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['status'=>'error', 'msg' => 'El usuario no existe'], Response::HTTP_NOT_FOUND);
        }

    }

    public function getAllPhotos(Request $request, $id) {
        // Jesús
        if ($id == 1)
            return response()->json(['photos'=>scandir(public_path('/images/user/jesus/')), 'path'=>'/images/user/jesus/'], Response::HTTP_OK);

        // Macarena
        else if ($id == 2)
            return response()->json(['photos'=>scandir(public_path('/images/user/macarena/')), 'path'=>'/images/user/macarena/'], Response::HTTP_OK);

    }


}
