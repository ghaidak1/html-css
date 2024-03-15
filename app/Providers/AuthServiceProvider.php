<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\About;
use App\Models\Certificate;
use App\Models\Communicate;
use App\Models\Cv;
use App\Models\Educatedegree;
use App\Models\Experience;
use App\Models\Image;
use App\Models\Job;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\User;
use App\Policies\AboutPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\CommunicatePolicy;
use App\Policies\CvPolicy;
use App\Policies\EducatedegreePolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\ImagePolicy;
use App\Policies\JobPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SkillPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        About::class => AboutPolicy::class,
        Certificate::class => CertificatePolicy::class,
        Communicate::class => CommunicatePolicy::class,
        Cv::class => CvPolicy::class,
        Educatedegree::class => EducatedegreePolicy::class,
        Experience::class => ExperiencePolicy::class,
        Project::class => ProjectPolicy::class,
        Service::class => ServicePolicy::class,
        Skill::class => SkillPolicy::class,
        Job::class => JobPolicy::class,
        Image::class => ImagePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    }
}
