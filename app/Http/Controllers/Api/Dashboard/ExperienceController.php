<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Experience::class);
        $user_id = Auth::id();
        $experiences = Experience::where('user_id' , $user_id)->get();
        if(!$experiences->isEmpty()){
            $experiences=$experiences->map(function($experience){
                return [
                $experience=[
                    'user'=>$experience->user->f_name." ".$experience->user->l_name,
                    'title'=>$experience->title,
                    'description'=>$experience->description,
                    'company'=>$experience->company,
                    'from'=>$experience->from,
                    'to'=>$experience->to,
                    'created_at' => $experience->created_at,
                    'updated_at'=>$experience->updated_at
                ]
                ];
            });
            return response()->json($experiences,202);
        }
        return response()->json(["message"=>"no experiences yet"],202);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Experience::class);
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'company'=>'required|string',
            'from'=>'required|integer|min:2000|max:2024',
            'to'=>'integer|min:2000|max:2024'
        ]);
        $user_id=auth()->user()->id;
        $experience = new Experience();
        $experience->user_id = $user_id;

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $experience->title =$purifier->purify($request->input('title'));
        $experience->description = $purifier->purify($request->input('description'));
        $experience->company =$purifier->purify( $request->input('company'));
        $experience->from = $purifier->purify($request->input('from'));
        $experience->to = $purifier->purify($request->input('to'));


        $user=User::find($user_id);
        $user->experiences()->save($experience);
        return response()->json(["message"=>"experience added successfully"],202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
    {
        $this->authorize('view',$experience);
        if(!empty($experience)){
            $experience=[
                'user'=>$experience->user->f_name." ".$experience->user->l_name,
                'title'=>$experience->title,
                'description'=>$experience->description,
                'company'=>$experience->company,
                'from'=>$experience->from,
                'to'=>$experience->to,
                'created_at' => $experience->created_at,
                'updated_at'=>$experience->updated_at
            ];
            return response()->json($experience,202);
        }
        return response()->json(["message"=>"no experience with this id"],404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Experience $experience)
    {
        $this->authorize('update',$experience);
        if(!empty($experience)){
            $request->validate([
                'title'=>'required|string',
                'description'=>'required|string',
                'company'=>'required|string',
                'from'=>'required|integer|min:2000|max:2024',
                'to'=>'integer|min:2000|max:2024'
            ]);

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $experience->title =$purifier->purify($request->input('title'));
            $experience->description = $purifier->purify($request->input('description'));
            $experience->company =$purifier->purify( $request->input('company'));
            $experience->from = $purifier->purify($request->input('from'));
            $experience->to = $purifier->purify($request->input('to'));

            $user=User::find($experience->user_id);
            $user->experiences()->save($experience);
            return response()->json(["message"=>"experience updated successfully"],202);
           }
           return response()->json(["message" => "experience not found"],404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        $this->authorize('delete',$experience);
        if(!empty($experience))
        {
           $experience->delete();
           return response()->json(["message"=>"experience deleted successfully"],202);
        }
        return response()->json(["message"=>"experience not found"],404);
    }
}
