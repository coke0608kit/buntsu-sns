<?php

namespace App\Admin\Controllers;

use App\User;
use App\Mail\SendAllUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController
{
    public function index()
    {
        return view('laravel-admin.mail');
    }

    public function send(Request $request)
    {
        $users = User::all();
        foreach ($users as $user) {
            $data = [
                'email' => $user->email,
                'title' => $request->title,
                'body' => $request->content
            ];
            Mail::to($data['email'])->send(new SendAllUser($data));
        }

        //リダイレクト
        return redirect('/admin/mail')->with('success', '一斉送信完了！');
    }
}
