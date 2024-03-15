<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Educatedegree;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducatedegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Educatedegree::class);
        $user_id=Auth::id();
        $educatedegrees=Educatedegree::where('user_id',$user_id)->get();
        if(!$educatedegrees->isEmpty()){
            $educatedegrees=$educatedegrees->map(function($educatedegree){
                return [
                    $educatedegree=[
                        'user'=>$educatedegree->user->f_name." ".$educatedegree->user->l_name,
                        'degree'=>$educatedegree->degree,
                        'description'=>$educatedegree->description,
                        'university'=>$educatedegree->university,
                        'from'=>$educatedegree->from,
                        'to'=>$educatedegree->to,
                        'created_at' => $educatedegree->created_at,
                        'updated_at'=>$educatedegree->updated_at
                    ]
                    ];
            });
            return response()->json($educatedegrees);
        }
        return response()->json(["message"=>"no educatedegrees yet"],202);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Educatedegree::class);
        $request->validate([
            'degree'=>'required|string',
            'description'=>'required|string',
            'university'=>'required|string',
            'from'=>'required|integer|min:2000|max:2024',
            'to'=>'integer|min:2000|max:2024'
        ]);
        $user_id=auth()->user()->id;
        $educatedegree = new Educatedegree();
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $educatedegree->degree = $purifier->purify( $request->input('degree'));
        $educatedegree->user_id = $user_id;
        $educatedegree->description = $purifier->purify($request->input('description'));
        $educatedegree->university =$purifier->purify( $request->input('university'));
        $educatedegree->from = $purifier->purify($request->input('from'));
        $educatedegree->to = $purifier->purify($request->input('to'));
        $user = User::find($user_id);
        $user->educatedegrees()->save($educatedegree);
        return response()->json(["message"=>"educatedegree added successfully"],202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Educatedegree $educatedegree)
    {
        $this->authorize('view',$educatedegree);
        if(!empty($educatedegree)){
            $educatedegree=[
                'user'=>$educatedegree->user->f_name." ".$educatedegree->user->l_name,
                'degree'=>$educatedegree->degree,
                'description'=>$educatedegree->description,
                'university'=>$educatedegree->university,
                'from'=>$educatedegree->from,
                'to'=>$educatedegree->to,
                'created_at' => $educatedegree->created_at,
                'updated_at' =>$educatedegree->updated_at
            ];
            return response()->json($educatedegree,202);
        }
        return response()->json(["message"=>"educatedegree with this id not found"],404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Educatedegree $educatedegree)
    {
        $this->authorize('update',$educatedegree);
        if(!empty($educatedegree)){
        $request->validate([
            'degree'=>'required|string',
            'description'=>'required|string',
            'university'=>'required|string',
            'from'=>'required|integer|min:2000|max:2024',
            'to'=>'integer|min:2000|max:2024'
        ]);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $educatedegree->degree = $purifier->purify( $request->input('degree'));
        $educatedegree->description = $purifier->purify($request->input('description'));
        $educatedegree->university =$purifier->purify( $request->input('university'));
        $educatedegree->from = $purifier->purify($request->input('from'));
        $educatedegree->to = $purifier->purify($request->input('to'));
        $user=User::find($educatedegree->user_id);
        $user->educatedegrees()->save($educatedegree);
        return response()->json(["message"=>"educatedegree updated successfully"],202);
       }
       return response()->json(["message" => "educatedegree not found"],404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Educatedegree $educatedegree)
    {
        $this->authorize('delete',$educatedegree);
        if(!empty($educatedegree))
        {
           $educatedegree->delete();
           return response()->json(["message"=>"educatedegree deleted successfully"],202);
        }
        return response()->json(["message"=>"educatedegree not found"],404);
    }
}
