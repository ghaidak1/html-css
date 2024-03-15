<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Skill::class);
        $user_id = Auth::id();
        $skills = Skill::where('user_id' , $user_id)->get();
        if(!$skills->isEmpty()){
            $skills = $skills->map(function($skill){
                return [
                    "user"=>$skill->user->f_name." ".$skill->user->l_name,
                    $skill=[
                    "id" => $skill->id,
                    "title" => $skill->title,
                    "description" => $skill->description,
                    "level" => $skill->level,
                    "created_at" =>$skill->created_at,
                    "updated_at" =>$skill->updated_at
                    ]
                    ];
            });
            return response()->json($skills,202);
        }
        return response()->json(["message"=>"no skills yet"],202);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Skill::class);
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'level' => 'required|string'
        ]);
        $user_id=auth()->user()->id;
        $skill = new Skill();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $skill->title =$purifier->purify($request->input('title'));
        $skill->description = $purifier->purify($request->input('description'));
        $skill->level =$purifier->purify( $request->input('level'));

        $user = User::find($user_id);
        $user->skills()->save($skill);
        return response()->json(["message"=>"skill added successfully"],202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        $this->authorize('view',$skill);
        if(!empty($skill)){
            $skill=[
            "id" => $skill->id,
            "user"=>$skill->user->f_name." ".$skill->user->l_name,
            "title" => $skill->title,
            "description" => $skill->description,
            "level" => $skill->level,
            "created_at" =>$skill->created_at,
            "updated_at" =>$skill->updated_at
            ];
            return response()->json($skill,202);
        }
            return response()->json(["message"=>"no skill with this id"],404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Skill $skill)
    {
        $this->authorize('update',$skill);
        if(!empty($skill))
        {
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'level' => 'required|string'
        ]);
        $user_id=$skill->user_id;

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $skill->title =$purifier->purify($request->input('title'));
        $skill->description = $purifier->purify($request->input('description'));
        $skill->level =$purifier->purify( $request->input('level'));

        $user = User::find($user_id);
        $user->skills()->save($skill);
        return response()->json(["message"=>"skill updated successfully"],202);
        }
        return response()->json(["message"=>"skill not found"],404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        $this->authorize('delete',$skill);
        if(!empty($skill))
        {
           $skill->delete();
           return response()->json(["message"=>"skill deleted successfully"],202);
        }
        return response()->json(["message"=>"skill not found"],404);
    }
}
