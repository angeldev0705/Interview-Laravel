<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Auth;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Comment;

use Storage;
use Carbon\Carbon;
use Str;
use Image;
use Session;

class BackendController extends Controller
{
    public function loginShow()
    {
        return view('backend.login');
    }

    public function authenticate(Request $request)
    {
        $validator = $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $rememberMe = false;

        if(isset($request->remember)) {
            $rememberMe = true;
        }

        if (Auth::attempt($validator, $rememberMe)) {
            return redirect('/home');
        }
        else {
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return back();
    }

    public function dashboard(Request $request)
    {
        if ($request->sort)
        {
            $sort = $request->sort;
        }
        else {
            $sort = 'ranking';
        }

        switch ($sort) {
            case 'ranking':
                $orderBy = 'mark';
                break;
            case 'age':
                $orderBy = 'age';
                break;
            case 'date':
                $orderBy = 'date';
                break;
        }

        if (Auth::user()->is_admin == 1) {
            $applicants = Applicant::latest()->paginate(12);
        }

        else {
            $applicants = Applicant::whereHas('interviews', function($query) {
                $query->whereHas('question', function($query1) {
                    $query1->whereHas('campaign', function($query2) {
                        $query2->where('company_id', Auth::user()->id);
                    });
                });
            })->latest()->paginate(12);
        }
        foreach ($applicants as $key => $applicant) {
            $mark_sum = Comment::where('applicant_id', $applicant->id)->sum('rate');
            $mark_count = Comment::where('applicant_id', $applicant->id)->count('rate');
            if ($mark_count == null)
                $mark = 0;
            else {
                $mark = $mark_sum / $mark_count;
            }
            $applicant->mark = $mark;
        }


        $applicants->setCollection(
            $applicants->sortByDesc(function ($applicant) use($orderBy) {
                return $applicant[$orderBy];
            })
        );

        return view('backend.dashboard', compact('applicants', 'sort'));
    }

    public function deleteApplicant($id)
    {
        $applicant = Applicant::find($id);

        $interviews = Interview::where('applicant_id', $id)->get();

        foreach ($interviews as $key => $interview) {
            $interview->delete();
        }

        $applicant->delete();

        return redirect()->back();
    }

    public function showVideos(string $slug)
    {
        $applicant = Applicant::where('slug', $slug)->first();

        if($applicant == null)
            return redirect()->route('dashboard');

        $mark = Comment::where('applicant_id', $applicant->id)->avg('rate');
        if ($mark == null)
            $mark = 0;
        $applicant->mark = $mark;

        if (Auth::user()->is_admin == 1) {
            $interviews = Interview::latest()->where('applicant_id', $applicant->id)->latest()->get();
        }

        else {
            $interviews = Interview::whereHas('question', function($query1) {
                $query1->whereHas('campaign', function($query2) {
                    $query2->where('company_id', Auth::user()->id);
                });
            })->where('applicant_id', $applicant->id)->latest()->get();
        }

        $comment = Comment::where('applicant_id', $applicant->id)->where('company_id', Auth::user()->id)->first();

        return view('backend.video', compact('applicant', 'interviews', 'comment'));
    }

    public function showSettings()
    {
        return view('backend.setting');
    }

    public function saveComment(Request $request)
    {
        $company_id = Auth::user()->id;
        try {
            Comment::updateOrCreate(
                [
                  'applicant_id' => $request->id,
                  'company_id' => $company_id
                ],
                [
                  'comment' => $request->comment,
                  'rate' => $request->rate
                ]
            );

            return response()->json(
                [
                  'success' => true,
                  'message' => 'Comment saved successfully'
                ]
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                  'success' => false,
                  'message' => 'Something went wrong!'
                ]
            );
        }
    }

    public function companies() {
        $companies = User::where('is_admin', false)->latest()->get();

        return request()->ajax() ? response()->json($companies,Response::HTTP_OK) : abort(404);
    }

    public function createCompany(Request $request) {
        $slug = Str::slug($request->name)."-".$this->getRandomString();

        $currentDate = Carbon::now()->toDateString();
        $logoname = 'company-'.$slug.'.png';
        $backname = 'background-'.$slug.'.png';


        if ($request->password == null) {
            if ($request->id == null) {
                $password = bcrypt($request->email);
            }
            else {
                $password = User::where('id', $request->id)->first()['password'];
            }
        }

        else {
            $password = bcrypt($request->password);
        }

        if ($request->logo == 'undefined' || $request->background == 'undefined') {
            if ($request->logo == 'undefined' && $request->background == 'undefined') {
                User::updateOrCreate(
                    [
                      'id' => $request->id
                    ],
                    [
                      'name' => $request->name,
                      'email' => $request->email,
                      'password' => $password,
                      'is_admin' => $request->is_admin
                    ]
                );
            }
            else if ($request->logo == 'undefined') {
                if(!Storage::disk('public')->exists('Background')){
                    Storage::disk('public')->makeDirectory('Background');
                }
                $backImage = Image::make($request->background);
                $backImage->resize(500, 600);
                $backImage->save($backname);
                Storage::disk('public')->put('Background/'.$backname, $backImage);
                User::updateOrCreate(
                    [
                      'id' => $request->id
                    ],
                    [
                      'name' => $request->name,
                      'email' => $request->email,
                      'password' => $password,
                      'is_admin' => $request->is_admin,
                      'background' => $backname
                    ]
                );
            }
            else if ($request->background == 'undefined') {
                if(!Storage::disk('public')->exists('Company')){
                    Storage::disk('public')->makeDirectory('Company');
                }

                $logoImage = Image::make($request->logo);
                $logoImage->resize(300, 300);
                $logoImage->save($logoname);
                Storage::disk('public')->put('Company/'.$logoname, $logoImage);

                User::updateOrCreate(
                    [
                      'id' => $request->id
                    ],
                    [
                      'name' => $request->name,
                      'email' => $request->email,
                      'password' => $password,
                      'is_admin' => $request->is_admin,
                      'logo' => $logoname,
                    ]
                );
            }
        }
        else {
            if(!Storage::disk('public')->exists('Company')){
                Storage::disk('public')->makeDirectory('Company');
            }
            if(!Storage::disk('public')->exists('Background')){
                Storage::disk('public')->makeDirectory('Background');
            }
            $logoImage = Image::make($request->logo);
            $logoImage->resize(300, 300);
            $logoImage->save($logoname);
            Storage::disk('public')->put('Company/'.$logoname, $logoImage);

            $backImage = Image::make($request->background);
            $backImage->resize(500, 600);
            $backImage->save($backname);
            Storage::disk('public')->put('Background/'.$backname, $backImage);
            User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $password,
                    'is_admin' => $request->is_admin,
                    'logo' => $logoname,
                    'background' => $backname
                ]
            );
        }


          return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
          );
    }

    public function updateCompany($id)
    {
        $company  = User::find($id);

        return response()->json([
            'data' => $company
        ]);
    }

    public function deleteCompany($id)
    {
        $company = User::find($id);

        $campaigns = Campaign::where('company_id', $id)->get();

        foreach ($campaigns as $key => $campaign) {
            deleteCampaign($campaign->id);
        }


        $company->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }

    public function campaigns() {
        if (Auth::user()->is_admin) {
            $campaigns = Campaign::latest()->get();
        }
        else {
            $campaigns = Campaign::where('company_id', Auth::user()->id)->latest()->get();
        }

        foreach ($campaigns as $key => $campaign) {
            $campaign['company'] = User::where('id', $campaign->company_id)->first()['name'];
        }

        return request()->ajax() ? response()->json($campaigns,Response::HTTP_OK) : abort(404);
    }

    public function createCampaign(Request $request) {

        $slug = Str::slug($request->name)."-".$this->getRandomString();

        Campaign::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'company_id' => Auth::user()->id,
                'name' => $request->name,
                'slug' => $slug
            ]
        );

        return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
        );
    }

    public function updateCampaign($id)
    {
        $campaign  = Campaign::find($id);

        return response()->json([
            'data' => $campaign
        ]);
    }

    public function deleteCampaign($id)
    {
        $campaign = Campaign::find($id);

        $questions = Question::where('campaign_id', $id)->get();

        foreach ($questions as $key => $question) {
            deleteQuestion($question->id);
        }

        $campaign->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }

    public function questions(Request $request) {
        if (Auth::user()->is_admin) {
            $questions = Question::where('campaign_id', $request->id)->latest()->get();
        }
        else {
            $questions = Question::where('campaign_id', $request->id)->whereHas('campaign', function($query){
                $query->where('company_id', Auth::user()->id);
            })->get();
        }

        foreach ($questions as $key => $question) {
            $question->campaign_name = Campaign::where('id', $question->campaign_id)->first()['name'];
        }

        return request()->ajax() ? response()->json($questions,Response::HTTP_OK) : abort(404);
    }

    public function createQuestion(Request $request) {
        Question::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'campaign_id' => $request->campaign_id,
                'name' => $request->name
            ]
        );

        return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
        );
    }

    public function updateQuestion($id)
    {
        $question  = Question::find($id);

        $question->campaign_name = Campaign::where('id', $question->campaign_id)->first()['name'];

        return response()->json([
            'data' => $question
        ]);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);

        $question->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }
}
