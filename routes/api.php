<?php

use App\Http\Controllers\Api\Dashboard\AboutController;
use App\Http\Controllers\Api\Dashboard\AuthController;
use App\Http\Controllers\Api\Dashboard\CertificateController;
use App\Http\Controllers\Api\Dashboard\CommunicateController;
use App\Http\Controllers\Api\Dashboard\CvController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Dashboard\EducatedegreeController;
use App\Http\Controllers\Api\Dashboard\ExperienceController;
use App\Http\Controllers\Api\Dashboard\ImageController;
use App\Http\Controllers\Api\Dashboard\JobController;
use App\Http\Controllers\Api\Dashboard\PortfolioController;
use App\Http\Controllers\Api\Dashboard\ProjectController;
use App\Http\Controllers\Api\Dashboard\ServiceController;
use App\Http\Controllers\Api\Dashboard\SkillController;
use App\Models\Communicate;
use App\Models\Educatedegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('guest')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
});

Route::middleware(['auth:sanctum'])->group(function()
{
Route::post('logout',[AuthController::class,'logout']);
Route::resource('abouts',AboutController::class);

Route::resource('certificates', CertificateController::class);
Route::resource('communicates', CommunicateController::class);
Route::resource('cvs', CvController::class);
Route::resource('educatedegrees', EducatedegreeController::class);
Route::resource('experiences', ExperienceController::class);
Route::resource('projects', ProjectController::class);
Route::resource('services', ServiceController::class);
Route::resource('skills', SkillController::class);
Route::resource('images', ImageController::class);
Route::resource('jobs', JobController::class);

// Route::middleware('PortfolioOwner')->get('portfolio',[DashboardController::class,'ShowPortfolioSections']);
// Route::put('portfolio/updateAbout',[DashboardController::class,'updateAbout']);
// Route::put('portfolio/updateCertificate/{id}',[DashboardController::class,'updateCertificate']);
// Route::put('portfolio/updateCommunicate/{id}',[DashboardController::class,'updateCommunicate']);
// Route::put('portfolio/updateCv/',[DashboardController::class,'updateCv']);
// Route::put('portfolio/updateEducateDegree/{id}',[DashboardController::class,'updateEducateDegree']);
// Route::put('portfolio/updateExperience/{id}',[DashboardController::class,'updateExperience']);
// Route::put('portfolio/updateProject/{id}',[DashboardController::class,'updateProject']);
// Route::put('portfolio/updateSkill/{id}',[DashboardController::class,'updateSkill']);


// });

Route::get('portfolio/',[PortfolioController::class,'showPortfolio']);
});
