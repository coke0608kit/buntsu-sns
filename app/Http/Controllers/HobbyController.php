<?php

namespace App\Http\Controllers;

use App\Hobby;
use Illuminate\Http\Request;

class HobbyController extends Controller
{
    public function show(string $name)
    {
        $hobby = Hobby::where('name', $name)->first();
        $hobbyCount = $hobby->users->count();
        return view('hobbies.show', [
        'tagCount' => $hobbyCount,
        'tagName' => $name,
        ]);
    }

    public function fetch(Request $request)
    {
        $decodedFetchedUserIdList = json_decode($request->fetchedUserIdList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }
        // ツイートを取得
        $users = $this->extractShowUsers($decodedFetchedUserIdList, $request);

        return response()->json(['users' => $users], 200);
    }

    public function extractShowUsers($fetchedUserIdList, $request)
    {
        $limit = 10; // 一度に取得する件数
        $offset = $request->page * $limit; // 現在の取得開始位置

        $hobbies = Hobby::where('name', $request->test)->first();
        $users = $hobbies->users->load(['hobbies'])->sortByDesc('id')->forPage($request->page + 1, $limit);

        if (is_null($users)) {
            return [];
        }

        if (is_null($fetchedUserIdList)) {
            return $users;
        }

        $showableUsers = [];
        foreach ($users as $user) {
            if (!in_array($user->id, $fetchedUserIdList)) {
                if (($user->profile->year != null && $user->profile->month != null && $user->profile->day != null) || (isset($user->profile->year) && isset($user->profile->month) && isset($user->profile->day))) {
                    $birthday = sprintf('%04d', $user->profile->year).sprintf('%02d', $user->profile->month).sprintf('%02d', $user->profile->day);
                    $today = date('Ymd');
                    $user->profile->age = floor(($today - $birthday) / 100000) * 10 . "代";
                }
                unset($user->profile->year);
                unset($user->profile->month);
                unset($user->profile->day);
                unset($user->profile->zipcode1);
                unset($user->profile->zipcode2);
                unset($user->profile->address1);
                unset($user->profile->address2);
                unset($user->profile->realname);
                unset($user->profile->updated_at);
                unset($user->email);
                unset($user->email_verified_at);
                unset($user->created_at);
                unset($user->blockers);
                unset($user->blockee);
                unset($user->payjp_customer_id);
                $user->canSend = false;
                $user->isFollowedBy = false;
                $user->isMe = false;
                $user->countFollowings = $user->count_followings;
                $user->countFollowers = $user->count_followers;

                if ($request->user()) {
                    $user->isFollowedBy = $user->isFollowedBy($request->user());
                    if ($request->user()->id == $user->id) {
                        $user->isMe = true;
                    }
                    if (($user->profile->canSendGender == 9 && $user->profile->gender == $request->user()->profile->canSendGender) || ($request->user()->profile->canSendGender == 9 && $request->user()->profile->gender == $user->profile->canSendGender) || (($user->profile->gender == 2 && $user->profile->canSendGender == 9) && ($request->user()->profile->gender == 1 && $request->user()->profile->canSendGender == 9)) || (($request->user()->profile->gender == 2 && $request->user()->profile->canSendGender == 9) && ($user->profile->gender == 1 && $user->profile->canSendGender == 9)) || ($user->profile->canSendGender == $request->user()->profile->gender && $user->profile->gender == $request->user()->profile->canSendGender) || (($user->profile->gender == 1 && $user->profile->canSendGender == 1) && ($request->user()->profile->gender == 1 && $request->user()->profile->canSendGender == 1)) || ($user->profile->canSendGender == 9 && ($user->profile->gender == $request->user()->profile->gender) && ($user->profile->canSendGender == $request->user()->profile->canSendGender)) || (($user->profile->gender == 2 && $user->profile->canSendGender == 1) && ($request->user()->profile->gender == 1 && $request->user()->profile->canSendGender == 2)) || (($user->profile->gender == 1 && $user->profile->canSendGender == 2) && ($request->user()->profile->gender == 2 && $request->user()->profile->canSendGender == 1))) {
                        $user->canSend = true;
                    }
                    if (!($user->isBlockedBy($request->user()) ||  $request->user()->isBlockedBy($user))) {
                        $showableUsers[] = $user;
                    }
                } else {
                    $showableUsers[] = $user;
                }
            }
        }

        return $showableUsers;
    }
}
