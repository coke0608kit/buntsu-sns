<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();
        $tagCount = $tag->articles->count();
        return view('tags.show', [
        'tagCount' => $tagCount,
        'tagName' => $name,
        ]);
    }
}
