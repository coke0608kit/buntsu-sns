<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Information;
use App\Question;

class topController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            return redirect()->route('articles.index');
        }
        $information = Information::orderBy('id', 'DESC')->limit(3)->get();
        $questions = Question::orderBy('id', 'DESC')->limit(3)->get();
        return view('top.index', compact('information', 'questions'));
    }
}
