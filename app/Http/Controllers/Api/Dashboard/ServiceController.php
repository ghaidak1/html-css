<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Service::class);
        $user_id = Auth::id();
        $services = Service::where('user_id' , $user_id)->get();
        if(!empty($services)){
            $services = $services->map(function($service){
                return [
                    "user"=>$service->user->f_name." ".$service->user->l_name,
                    $service=[
                    "id" => $service->id,
                    "title" => $service->title,
                    "description" => $service->description,
                    "created_at" =>$service->created_at,
                    "updated_at" =>$service->updated_at
                    ]
                    ];
            });

            return response()->json($services);
        }
        return response()->json(["messages"=>"no services yet"]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Service::class);
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
        ]);
        $user_id = auth()->user()->id;
        $service = new Service();
        $service->user_id = $user_id;

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $service->title =$purifier->purify($request->input('title'));
        $service->description = $purifier->purify($request->input('description'));
        $user = User::find($user_id);
        $user->services()->save($service);
        return response()->json(["message"=>"service added successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $this->authorize('view',$service);
        if(!empty($service)){
            $service=[
            "id" => $service->id,
            "user"=>$service->user->f_name." ".$service->user->l_name,
            "title" => $service->title,
            "description" => $service->description,
            "created_at" =>$service->created_at,
            "updated_at" =>$service->updated_at
            ];
            return response()->json($service,202);
        }
        return response()->json(["message"=>"no service with this id"],404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Service $service)
    {
        $this->authorize('update',$service);
        if(!empty($service))
        {
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
        ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $service->title =$purifier->purify($request->input('title'));
        $service->description = $purifier->purify($request->input('description'));
        $user = User::find($service->user_id);
        $user->services()->save($service);

        return response()->json(["message"=>"service updated successfully"],202);
        }
        return response()->json(["message"=>"service not found"],404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete',$service);
        if(!empty($service))
        {
           $service->delete();
           return response()->json(["message"=>"service deleted successfully"],202);
        }
        return response()->json(["message"=>"service not found"],404);
        }
}
