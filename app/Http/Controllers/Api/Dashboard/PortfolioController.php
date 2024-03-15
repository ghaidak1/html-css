<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    public function showPortfolio( )
    {
        $user = Auth::user();
            $response = [
                "user_name" => $user->f_name . " " . $user->l_name,
                "email" => $user->email,
                "about" => $user->about->description,
                "image"=> $user->image->getImageUrlAttribute(),
                "job"=> $user->job->job_title,
                "certificates" => $user->certificates->map(function ($certificate) {
                    return [
                        "title" => $certificate->title,
                        "description" => $certificate->description,
                        "image" => $certificate->getImageUrlAttribute()
                    ];
                }),
                "communicates" => $user->communicates->map(function ($communicate) {
                    return [
                        "title" => $communicate->title,
                        "link" => $communicate->link,
                    ];
                }),
                "cv" => $user->cv->cv_file,
                "educatedegres" => $user->educatedegrees->map(function ($educatedegree) {
                    return [
                        "degree" => $educatedegree->degree,
                        "description" => $educatedegree->description,
                        "university" => $educatedegree->university,
                        "from" => $educatedegree->from,
                        "to" => $educatedegree->to,
                    ];
                }),
                "experiences" => $user->experiences->map(function ($experience) {
                    return [
                        "title" => $experience->title,
                        "description" => $experience->description,
                        "company" => $experience->company,
                        "from" => $experience->from,
                        "to" => $experience->to,
                    ];
                }),
                "projects" => $user->projects->map(function ($project) {
                    return [
                        "title" => $project->title,
                        "description" => $project->description,
                        "image" => $project->getImageUrlAttribute(),
                        "project_url" => url($project->project_url)
                    ];
                }),
                "services" => $user->services->map(function ($service) {
                    return [
                        "title" => $service->title,
                        "description" => $service->description,
                    ];
                }),
                "skills" => $user->skills->map(function ($skill) {
                    return [
                        "title" => $skill->title,
                        "description" => $skill->description,
                        "level" => $skill->level
                    ];
                })
            ];
            return response()->json($response);

    }
}
