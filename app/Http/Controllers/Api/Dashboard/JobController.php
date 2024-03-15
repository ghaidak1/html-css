<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Job;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Job::class);
        $user_id=Auth::id();
        $job = Job::where('user_id' , $user_id)->first();
        if(!empty($job)){
            $job = [
                "user"=>$job->user->f_name." ".$job->user->l_name,
                "job-title" =>$job->job_title,
                "created_at" => $job->created_at,
                "updated_at" => $job->updated_at
            ];
            return response()->json($job,202);
        }
        return response()->json(["message"=>"no job yet"],202);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Job::class);
        $request->validate([
            'job_title'=>'required|string'
        ]);
        $job = new Job();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $job->job_title=$purifier->purify($request->input('job_title'));

        $user = $request->user();
        $user->job()->save($job);
        return response()->json(["message"=>"job added successfully"],202);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , $job)
    {
        $this->authorize('update',$job);
        if (!empty($job))
        {
        $request->validate([
            'job_title'=>'required|string'
        ]);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $job->job_title=$purifier->purify($request->input('job_title'));
        $user = $request->user();
        $user->job()->save($job);
        return response()->json(["message"=>"job updated successfully"],202);
        }
        return response()->json(["message"=>"job not found"],404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $this->authorize('delete',$job);
        if (!empty($job)){
            $job->delete();
            return response()->json(["message"=>"job deleted successfully"],202);
        }
        return response()->json(["message"=>"job not found"],404);
    }
}
