<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(string $name)
    {
        $user = User::where('name', $name)->first();
        if (!empty($user)) {
            if (($user->profile->year != null && $user->profile->month != null && $user->profile->day != null) || (isset($user->profile->year) && isset($user->profile->month) && isset($user->profile->day))) {
                $birthday = sprintf('%04d', $user->profile->year).sprintf('%02d', $user->profile->month).sprintf('%02d', $user->profile->day);
                $today = date('Ymd');
                if (floor(($today - $birthday) / 100000) * 10 <= 1) {
                    $user->profile->age = "10代以下";
                } else {
                    $user->profile->age = floor(($today - $birthday) / 100000) * 10 . "代";
                }
            }
            return view('users.show', [
                'user' => $user
            ]);
        } else {
            return redirect()->route('articles.index');
        }
    }

    public function likes(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load(['likes.user', 'likes.likes', 'likes.tags']);

        $articles = $user->likes->sortByDesc('created_at');

        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function followings(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('followings.followers');

        $followings = $user->followings->sortByDesc('created_at');

        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }

    public function followers(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('followers.followers');

        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $name];
    }

    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }

    public function fetch(Request $request)
    {
        $followCheck = User::find($request->userId)->isFollowedBy(Auth::user());
        return json_encode($followCheck);
    }

    public function blockings(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('blockings.blockers');

        $blockings = $user->blockings->sortByDesc('created_at');

        return view('users.blockings', [
            'user' => $user,
            'blockings' => $blockings,
        ]);
    }

    public function blockers(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('blockers.blockers');

        $blockers = $user->blockers->sortByDesc('created_at');

        return view('users.blockers', [
            'user' => $user,
            'blockers' => $blockers,
        ]);
    }

    public function block(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->blockings()->detach($user);
        $request->user()->blockings()->attach($user);
        $request->user()->followings()->detach($user);
        $user->followings()->detach($request->user());

        return ['name' => $name];
    }

    public function unblock(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->blockings()->detach($user);

        return ['name' => $name];
    }

    public function fetchBlock(Request $request)
    {
        $blockCheck = User::find($request->userId)->isBlockedBy(Auth::user());
        return json_encode($blockCheck);
    }
}
