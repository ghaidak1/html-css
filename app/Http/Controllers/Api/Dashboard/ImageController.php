<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Image::class);
        $user_id = Auth::id();
        $image = Image::where('user_id' , $user_id)->first();
        if(!empty($image)){
            $image = [
                "user"=>$image->user->f_name." ".$image->user->l_name,
                "image" =>$image->getImageUrlAttribute(),
                "created_at"=>$image->created_at,
                "updated_at"=>$image->updated_at
            ];
            return response()->json($image,202);
        }
        return response()->json(["message"=>"no image yet"],202);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Image::class);
        $request->validate([
            "image"=>"required|image|mimes:png,jpg,jpeg|max:2048"
        ]);
        $image = new Image();
        if ($request->hasFile('image'))
        {
            $requestImage = $request->file('image');
            $requestImagePath = $requestImage->store('public/users');
            $image->image = $requestImagePath;
        }
        $user = $request->user();
        $user->image()->save($image);
        return response()->json(["message"=>"image added successfully"],202);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        $this->authorize('update',$image);
        if (!empty($image))
        {
        $request->validate([
            "image"=>"required|image|mimes:png,jpg,jpeg|max:2048"
        ]);

        if ($request->hasFile('image'))
        {
            $requestImage = $request->file('image');
            $requestImagePath = $requestImage->store('public/users');
            $image->image = $requestImagePath;
        }
        $user = $request->user();
        $user->image()->save($image);
        return response()->json(["message"=>"image updated successfully"],202);
    }
    return response()->json(["message"=>"image not found"],404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $this->authorize('delete',$image);
        if (!empty($image))
        {
            $image->delete();
            return response()->json(["message"=>"image deleted successfully",202]);
        }
        return response()->json(["message"=>"image not found"],404);
    }
}
