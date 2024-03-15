<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',About::class);
        $user_id=Auth::id();
        $about=About::where('user_id',$user_id)->first();
        if (!empty($about)){
        $response= [
                    "id"=>$about->id,
                    "user"=>$about->user->f_name." ".$about->user->l_name,
                    "description"=>$about->description,
                    "created_at" => $about->created_at,
                    "updated_at"=>$about->updated_at
        ];


        return response()->json($response , 202);
    }
        return response()->json(["message"=>"no about section yet"]);

    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
    $this->authorize('create',About::class);
    $request->validate([
        'description' => 'required|string',
    ]);

    $user_id = auth()->user()->id;

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $description = $purifier->purify($request->input('description'));
    $about = new About();
    $about->description = $description;
    $about->user_id = $user_id;

    $user = User::find($user_id);
    $user->about()->save($about);

    return response()->json(['message' => 'About added successfully'],202);
}




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
       $this->authorize('update',$about);
        if(!empty($about)){
        $request->validate([
           "description"=>"required|string",
        ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $about->description=$purifier->purify($request->input('description'));
        $user=User::find($about->user_id);
        $user->about()->save($about);
        return response()->json(["message"=>"about updated successfully"]);
    }
    return response()->json(["message "=>"about not found"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        $this->authorize('delete',$about);
        if(!empty($about)){
            $about->delete();
            return response()->json(["messsage"=>"About deleted successfully"]);
        }
        return response()->json(["message"=>"no about with these id"]);

    }
}
