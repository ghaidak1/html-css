<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Certificate::class);
        $user_id = Auth::id();
        $certificates=Certificate::where('user_id' , $user_id)->get();
        if(!$certificates->isEmpty()){
            $certificates=$certificates->map(function($certificate){
                return [
                    $certificate=[
                            "user" => $certificate->user->f_name." ".$certificate->user->l_name,
                            "title" => $certificate->title,
                            "description" => $certificate->description,
                            "image" => $certificate->getImageUrlAttribute(),
                            'created_at' => $certificate->created_at,
                            'updated_at'=>$certificate->updated_at

                        ]
                   ];
            });
            return response()->json($certificates,202);

        }
        return response()->json(["message"=>"no certificate yet",202]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Certificate::class);
        $request->validate([
            'description'=>'required|string',
            'title'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg'
        ]);

        $certificate = new Certificate();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $certificate->description=$purifier->purify($request->input('description'));
        $certificate->title = $purifier->purify($request->input('title'));
        $user_id=auth()->user()->id;
        $certificate->user_id=$user_id;
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imagePath = $image->store('public/certificates');
            $certificate->image = $imagePath;
        }
        $user=User::find($user_id);
        $user->certificates()->save($certificate);
        return response()->json(['message'=>'certificate added successfully']);
        }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $this->authorize('view',$certificate);
        if(!empty($certificate)){
            $certificate=[
            "id"=> $certificate->id,
            "user"=>$certificate->user->f_name." ".$certificate->user->l_name,
            "title" => $certificate->title,
            "description" => $certificate->description,
            "image" => $certificate->getImageUrlAttribute(),
            "created_at" => $certificate->created_at,
            "updated_at"=>$certificate->updated_at
           ];
            return response()->json($certificate);
        }
            return response()->json(["message"=>"no certeficate with this id"],404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Certificate $certificate)
    {
        $this->authorize('update',$certificate);
        if(!empty($certificate)){
        $request->validate([
            'description'=>'required|string',
            'title'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg'
        ]);
        $user_id=$certificate->user_id;
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $certificate->description=$purifier->purify($request->input('description'));
        $certificate->title = $purifier->purify($request->input('title'));
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imagePath = $image->store('public/certificates');
            $certificate->image = $imagePath;
        }
        $user=User::find($user_id);
        $user->certificates()->save($certificate);
        return response()->json(["message"=>"certificate updated successfully"]);
    }
        return response()->json(["message"=>"no certeficate with this id"],404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $this->authorize('delete',$certificate);
        if(!empty($certificate)){
        $certificate->delete();
        return response()->json(["message" => "certificate deleted successfullly"]);
        }
        return response()->json(["message"=>"no certeficate with this id"],404);
    }
}
