<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // Este método mostrará la página de trabajos
    public function index(Request $request) {

        $categories = Category::where('status',1)->get();
        $jobTypes = JobType::where('status',1)->get();

        $jobs = Job::where('status',1);

        // Buscar usando keyword
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function($query) use($request){
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }

        // Buscar usando location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', $request->location);
        }

        // Buscar usando category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }

        $jobTypeArray = [];
        // Buscar usando Job Type
        if (!empty($request->jobType)) {
            // 1,2,3
            $jobTypeArray = explode(',',$request->jobType);
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        // Buscar usando experience
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType','category']);

        if ($request->sort == '0') {
            $jobs = $jobs->orderBy('created_at','ASC');
        } else {
            $jobs = $jobs->orderBy('created_at','DESC');
        }

        $jobs = $jobs->paginate(9);

        return view('frontend.jobs',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }

    // Este método mostrará los detalles de la página trabajo
    public function detail($id) {

        $job = Job::where(['id' => $id, 'status' => 1])->with(['jobType','category'])->first();

        if ($job == null) {
            abort(404);
        }

        $count = 0;
        if (Auth::user()) {
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
            ])->count();
        }

        // Buscar candidatos

        $applications = JobApplication::where('job_id',$id)->with('user')->get();

        return view('frontend.jobDetail',[  'job' => $job,
                                            'count' => $count,
                                            'applications' => $applications
                                        ]);
    }

    public function applyJob(Request $request) {
        $id = $request->id;

        $job = Job::where('id',$id)->first();

        //Si el trabajo no se encuentra en la BD
        if ($job == null) {
            // $message = 'La solicitud de trabajo no existe.';
            session()->flash('error','La solicitud de empleo no existe' );
            return response()->json([
                'status' => false,
                'message' => 'La solicitud de empleo no existe'
            ]);
        }

        // Tu no puedes aplicar en tu propio trabajo
        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            // $message = 'Tu no puedes aplicar en tu propia solicitud.';
            session()->flash('error','Tu no puedes aplicar en tu propia solicitud');
            return response()->json([
                'status' => false,
                'message' => 'Tu no puedes aplicar en tu propia solicitud'
            ]);
        }

        // Tu no puedes aplicar en un trabajo 2 veces
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($jobApplicationCount > 0) {
            // $message = 'Tu ya has aplicado a esta solicitud trabajo.';
            session()->flash('error','Tu ya has aplicado a esta solicitud empleo');
            return response()->json([
                'status' => false,
                'message' => 'Tu ya has aplicado a esta solicitud empleo'
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        //Enviar notificación de correo electrónico a empleado
        $employer = User::where('id',$employer_id)->first();

        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];

        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        // $message = 'Aplicaste a esta solicitud de empleo satisfactoriamente.';

        session()->flash('success','Aplicaste a esta solicitud de empleo satisfactoriamente');
        return response()->json([
            'status' => true,
            'message' => 'Aplicaste a esta solicitud de empleo satisfactoriamente'
        ]);
    }

    public function saveJob(Request $request) {

        $id = $request->id;

        $job = Job::find($id);

        if ($job == null) {
            session()->flash('error','Empleo no encontrado');
            return response()->json([
                'status' => false,
                'message' => 'Empleo no encontrado',
            ]);
        }

        // Revisar si el usuario ya ha guardado el empleo
        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($count > 0) {
            session()->flash('error','Tu ya has guardado este empleo');
            return response()->json([
                'status' => false,
                'message' => 'Tu ya has guardado este empleo',
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success','Has guardado satisfactoriamente este empleo');

        return response()->json([
            'status' => true,
            'message' => 'Has guardado satisfactoriamente este empleo'
        ]);
    }
}
