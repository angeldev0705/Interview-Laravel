<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Campaign;
use App\Models\User;

use Storage;
use Carbon\Carbon;
use Str;
use Image;
use FFMpeg;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function setCampaign(string $slug)
    {
        session()->flush();
        session()->put('slug', $slug);
        $campaign = Campaign::where('slug', $slug)->first();
        $company = User::where('id', $campaign->company_id)->first();
        session()->put('logo', $company->logo);
        session()->put('background', $company->background);
        return redirect()->route("index");
    }

    public function saveApplicant(Request $request)
    {
        $request->validate([
            'name'  =>  'required',
            'age'   =>  'required|max:8',
            'email' =>  'required',
            'phone' =>  'required',
            'image' =>  'required'
        ]);

        $slug = Str::slug($request->name)."-".$this->getRandomString();

        $currentDate = Carbon::now()->toDateString();
        $imagename = 'avatar-'.$slug.'.png';

        if(!Storage::disk('public')->exists('Avatar')){
            Storage::disk('public')->makeDirectory('Avatar');
        }

        $typeImage = Image::make($request->image)->save($imagename);
        Storage::disk('public')->put('Avatar/'.$imagename, $typeImage);

        $applicant = Applicant::updateOrCreate(
            ['email' => $request->email],
            ['image' => $imagename,'slug' => $slug, 'phone' => $request->phone, 'date' => date('Y-m-d'), 'name' => $request->name, 'age' => $request->age],
        );

        $request->session()->put('applicant', $applicant);

        return redirect()->route('test.start');
    }

    public function testStart()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test');
    }

    public function testNo()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test_no');
    }

    public function testYes()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test_yes');
    }

    public function realStart($confirm)
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        session()->put('confirm_test', $confirm);

        if (session()->has('slug'))
        {
            $campaign = Campaign::where('slug', session('slug'))->first();
            $questions = Question::where('campaign_id', $campaign->id)->paginate(1);
        }

        else {
            return redirect()->back()->withErrors(['No questions!', 'msg']);
        }

        if (count($questions) == 0 ) {
            return redirect()->back()->withErrors(['No questions!', 'msg']);
        }
        return view('frontend.real', compact('questions'));
    }

    public function upload(Request $request) {
        try {
            $interview = Interview::updateOrCreate(
                ['applicant_id' => $request->applicant_id, 'question_id' => $request->question_id],
                ['file' => $request->video_filename],
            );

            if(Storage::disk('public')->exists('Interview')){
                Storage::disk('public')->delete('Interview');
            }
            $video = $request->file('video_blob');
            Storage::disk('public')->put('Interview/'.$request->video_filename, file_get_contents($video));

            if (session()->has('slug'))
            {
                $campaign = Campaign::where('slug', session('slug'))->first();
                $questions = Question::where('campaign_id', $campaign->id)->paginate(1);
            }

            else {
                return redirect()->back()->withErrors(['No questions!', 'msg']);
            }

            return $questions;
        } catch(Exception $ex) {
            return 'failed';
        }
    }

    public function thanks()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        session()->flush();


        return view('frontend.thanks');
    }
}
