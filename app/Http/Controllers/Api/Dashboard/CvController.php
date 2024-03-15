<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class CvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Cv::class);
        $user_id=Auth::id();
        $cv=Cv::where('user_id',$user_id)->first();
        if(!empty($cv)){
            $cv=[
                "user"=>$cv->user->f_name." ".$cv->user->l_name,
                "cv_file"=>$cv->getCvUrlAttribute(),
                'created_at' => $cv->created_at,
                'updated_at'=>$cv->updated_at
            ];
            return response()->json($cv , 202);
        }
        return response()->json(["message"=>"no cvs yet",202]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create',Cv::class);
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf'
        ]);
        $cv = new Cv();
        $user_id = auth()->user()->id;
        $cv->user_id = $user_id;
        if($request->hasFile('cv_file')){
            $cv_file = $request->file('cv_file');
            $cv_file_path = $cv_file->store('public/cv_files');
            $cv->cv_file = $cv_file_path;
        }
        $user = User::find($user_id);
        $user->cv()->save($cv);
        return response()->json(["message"=>"cv added successfuly"],202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cv $cv)
    {
        $this->authorize('view',$cv);
        if(!empty($cv)){
            $cv = [
                "user_id"=>$cv->user_id,
                "cv_file"=>$cv->getCvUrlAttribute(),
                "updated_at"=>$cv->updated_at,
                "created_at"=>$cv->created_at,
                'created_at' => $cv->created_at,
                'updated_at'=>$cv->updated_at

            ];
            return response()->json($cv,202);
        }
        return response()->json(["message"=>"cv not found"],404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,CV $cv)
    {
        $this->authorize('update',$cv);
        if(!empty($cv)){
            $request->validate([
                'cv_file'=>'required|file|mimes:pdf'
            ]);
            $user=User::find($cv->user_id);
            if($request->hasFile('cv_file')){
                $cv_file = $request->file('cv_file');
                $cv_file_path = $cv_file->store('public/cv_files');
                $cv->cv_file = $cv_file_path;
            }
            $user->cv()->save($cv);
            return response()->json(["message"=>"cv updated successfuly"],202);

        }else{
            return response()->json(["message"=>"cv not found"],404);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cv $cv)
    {
        $this->authorize('delete',$cv);
        if(!empty($cv)){
            $cv->delete();
            return response()->json(["message"=>"cv deleted successfully"],202);
        }
        return response()->json(["message"=>"cv not found"],404);
    }
}
