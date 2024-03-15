<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email'=>'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user =new User();
        $user->f_name =$request->input('f_name');
        $user->l_name =$request->input('l_name');
        $user->email = $request->input('email');
        $user->password=Hash::make($request->input('password'));
        $user->save();
        $response=[
            'message'=>'user created successfully',
                'user'=>[
                    "id"=> $user->id,
                    "f_name"=> $user->f_name,
                    "l_name"=> $user->l_name,
                    "email"=> $user->email,
                    "updated_at"=> $user->updated_at,
                    "created_at"=> $user->created_at,

                ],
        ];

       return response()->json($response,201);



    }


   /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $request->validate([
        'email'=>'required|string|email',
        'password'=>'required|string|min:8'
        ]);

        $user=User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'status'=>'failed',
                'message'=>'invailed user informations'
            ]);
        }
        $token=$user->createToken('auth_token')->plainTextToken;
        $response=[
            'message'=>'user logged in successfully',
                'token'=>$token,
        ];
        return response()->json($response,201);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
         $request->user()->tokens()->delete();
            return response()->json([
            'status'=>'success',
            'message'=>'user logged out successfully',
        ]);
    }
}
