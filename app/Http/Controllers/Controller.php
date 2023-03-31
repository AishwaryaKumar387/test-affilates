<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Mail\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function contactUs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Mail::to('sharmagopi435@gmail.com')->send(new ContactUs($request->all()));
        return response()->json(['success' => true, 'message' => 'Thank you For Contacting Us. We will get back to you Soon.'], 200);
    }

    public function home()
    {
        $footer = self::common();
        $agents = Page::whereType('agent')->whereStatus(1)->get();
        $news = Page::whereType('news')->whereStatus(1)->get();
        $sliders = Page::whereType('home-slider')->whereStatus(1)->get();
        $services = Page::whereType('service')->whereStatus(1)->get();
        $home = Page::whereSlug('home')->first();
        return view('index', compact('home', 'services', 'sliders', 'footer', 'agents', 'news'));
    }

    public function aboutUs()
    {
        $agents = Page::whereType('agent')->whereStatus(1)->get();
        $footer = self::common();
        $home = Page::whereSlug('about-us')->first();
        return view('about-us', compact('home', 'footer','agents'));
    }

    public function contact()
    {
        $footer = self::common();
        $home = Page::whereSlug('contact-us')->first();
        return view('contact-us', compact('home', 'footer'));
    }

    public function privacyPolicy()
    {
        $footer = self::common();
        $home = Page::whereSlug('privacy-policy')->first();
        return view('privacy-policy', compact('home', 'footer'));
    }

    public function termsConditions()
    {
        $footer = self::common();
        $home = Page::whereSlug('terms-conditions')->first();
        return view('terms-condition', compact('home', 'footer'));
    }

    public function services()
    {
        $footer = self::common();
        $home = Page::whereSlug('services')->first();
        $services = Page::whereType('service')->whereStatus(1)->get();
        return view('services', compact('home', 'services', 'footer'));
    }

    private function common()
    {
        $data['general'] = Page::whereType('general-settings')->first();
        $data['footer'] = Page::whereSlug('home')->first();
        return $data;
    }

}
