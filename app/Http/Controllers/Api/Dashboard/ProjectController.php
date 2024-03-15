<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Project::class);
        $user_id = Auth::id();
        $projects = Project::where('user_id' , $user_id)->get();
        if(!$projects->isEmpty()){
            $response=$projects->map(function($project){
                return [
                    "id"=>$project->id,
                    "user"=>$project->user->f_name." ".$project->user->l_name,
                    "title" => $project->title,
                    "description" => $project->description,
                    "image" => $project->getImageUrlAttribute(),
                    "project_url" => url($project->project_url),
                    "created_at" => $project->created_at,
                    "updated_at"=>$project->updated_at
                ];
            });
            return response()->json($response,202);
        }
        return response()->json(["message"=>"no projects yet"],202);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Project::class);
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'project_url'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg',
        ]);
        $user_id=auth()->user()->id;
        $project = new Project();
        $project->user_id =  $user_id;

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $project->title =$purifier->purify($request->input('title'));
        $project->description = $purifier->purify($request->input('description'));
        $project->project_url =$purifier->purify( $request->input('project_url'));

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imagePath = $image->store('public/projects');
            $project->image = $imagePath;
        }
        $user = User::find($user_id);
        $user->projects()->save($project);
        return response()->json(["message"=>"project added successfully"],202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view',$project);
        if(!empty($project)){
            $project=[
            "id"=>$project->id,
            "user"=>$project->user->f_name." ".$project->user->l_name,
            "title" => $project->title,
            "description" => $project->description,
            "image" => $project->getImageUrlAttribute(),
            "project_url" => url($project->project_url),
            "created_at" => $project->created_at,
            "updated_at"=>$project->updated_at
           ];
           return response()->json($project,202);
        }
        return response()->json(["message"=>"no project with this id"],404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Project $project)
    {
        $this->authorize('update',$project);
        if(!empty($project))
        {
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'project_url'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg',
        ]);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $project->title =$purifier->purify($request->input('title'));
        $project->description = $purifier->purify($request->input('description'));
        $project->project_url =$purifier->purify( $request->input('project_url'));

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imagePath = $image->store('public/projects');
            $project->image = $imagePath;
        }
        $user = User::find($project->user_id);
        $user->projects()->save($project);
        return response()->json(["message"=>"project updated successfully"],202);
        }
        return response()->json(["message"=>"project not found"],404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete',$project);
        if(!empty($project))
        {
            $project->delete();
            return response()->json(["message"=>"project deleted successfully"],202);
        }
        return response()->json(["message"=>"project not found"],404);
    }
}
