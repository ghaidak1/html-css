<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Communicate;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Communicate::class);
        $user_id = Auth::id();
        $communicates=Communicate::where('user_id' , $user_id)->get();
        if(!$communicates->isEmpty()){
            $communicates = $communicates->map(function($communicate){
                return [
                    $communicate=[
                        'id'=>$communicate->id,
                        "user" => $communicate->user->f_name." ".$communicate->user->l_name,
                        'title'=>$communicate->title,
                        'link'=>$communicate->link,
                        'created_at' => $communicate->created_at,
                        'updated_at'=>$communicate->updated_at
                    ]
                    ];

            });
        return response()->json($communicates,202);
        }
        return response()->json(["message"=>"no communicates yet"]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Communicate::class);
        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string'
        ]);
        $user_id=auth()->user()->id;
        $communicate = new Communicate();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $communicate->title = $purifier->purify($request->input('title'));
        $communicate->link = $purifier->purify($request->input('link'));
        $communicate->user_id=$request->$user_id;
        $user = User::find($user_id);
        $user->communicates()->save($communicate);
        return response()->json(['message'=>'comm added succsessfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Communicate $communicate)
    {
        $this->authorize('view',$communicate);
        if(!empty($communicate)){
            $communicate=[
                "user" => $communicate->user->f_name." ".$communicate->user->l_name,
                'title'=>$communicate->title,
                'link'=>$communicate->link,
                'created_at' => $communicate->created_at,
                'updated_at'=>$communicate->updated_at
            ];
            return response()->json($communicate,202);
        }
        return response()->json(["message"=>"no communicate with this id"],404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Communicate $communicate)
    {
        $this->authorize('update',$communicate);
        if(!empty($communicate)){
        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string'
        ]);
        $user=User::find($communicate->user_id);

         $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $communicate->title = $purifier->purify($request->input('title'));
        $communicate->link = $purifier->purify($request->input('link'));
        $user->communicates()->save($communicate);
        return response()->json(['message'=>'communicate updated successfully'],202);

    }
        return response()->json(["message" => "no communicate with this id"],404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Communicate $communicate)
    {
        $this->authorize('delete',$communicate);
        if(!empty($communicate)){
        $communicate->delete();
        return response()->json(['message'=>'communicate deleted succsessfully'],202);
        }
        return response()->json(["message"=>"communicate not found"],404);

    }
}
