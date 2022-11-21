<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function terms()
    {
        return view('rule.terms');
    }

    public function privacy()
    {
        return view('rule.privacy');
    }

    public function tokushoho()
    {
        return view('rule.tokushoho');
    }
}
