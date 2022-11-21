<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm;

class PagesController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    //Contactフォーム メール送信
    public function contactSend(Request $request)
    {
        if (isset($request->honeypot) || strpos($request->input('uname'), 'Cry') !== false || strpos($request->input('uname'), 'enrottew') !== false) {
            abort(404);
        }
        if (strpos($request->input('email'), '.pt') !== false || strpos($request->input('email'), '.br') !== false || strpos($request->input('email'), '.fr') !== false) {
            abort(404);
        }
        if (strpos($request->input('body'), '>>>') !== false || strpos($request->input('body'), '$') !== false || strpos($request->input('body'), 'IPHONE') !== false || strpos($request->input('body'), 'iPhone') !== false) {
            abort(404);
        }
        //バリデーション
        $request->validate([
            'uname' => 'required',
            'email' => 'required|email',
            'body' => 'required'
        ]);

        //メール送信
        $data = [
            'uname' => $request->input('uname'),
            'email' => $request->input('email'),
            'body' => $request->input('body')
        ];

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactForm($data));
        Mail::to($data['email'])->send(new ContactForm($data));

        //リダイレクト
        return redirect('/contact')->with('success', 'お問い合わせを受け付けました。');
    }
}
