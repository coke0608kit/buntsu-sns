<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\Hobby;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function article()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name.'('.$tag->articles->count().'件)'
                    , 'count' => $tag->articles->count()];
        });
        $allTagNames = $allTagNames->sortByDesc('count')->toArray();

        foreach ($allTagNames as $key => $val) {
            $convert[]['text'] = $allTagNames[$key]['text'];
        }
        if (empty($convert)) {
            $allTagNames = Tag::all()->map(function ($tag) {
                return ['text' => $tag->name];
            });
        } else {
            $allTagNames = collect($convert);
        }

        $randomTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name.' ('.$tag->articles->count().'件)',
                    'tag' => $tag->name,
                    'count' => $tag->articles->count()];
        });

        if ($randomTagNames->count() > 20) {
            $randomTagNames = $randomTagNames->random(20)->shuffle()->toArray();
        }

        return view('search.article', [
            'allTagNames' => $allTagNames,
            'randomTagNames' => $randomTagNames,
        ]);
    }

    public function fetchSearchArticle(Request $request)
    {
        $decodedFetchedTweetIdList = json_decode($request->fetchedTweetIdList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }
        // ツイートを取得
        $tweets = $this->extractShowTweets($decodedFetchedTweetIdList, $request);

        return response()->json(['tweets' => $tweets], 200);
    }

    public function extractShowTweets($fetchedTweetIdList, $request)
    {
        $limit = 10; // 一度に取得する件数
        $offset = $request->page * $limit; // 現在の取得開始位置

        if ($request->pageType == 'search') {
            $tags = Tag::where('name', $request->word)->first()->load(['articles.user', 'articles.likes', 'articles.tags']);
            $tweets = $tags->articles->sortByDesc('id')->forPage($request->page + 1, $limit);
        }

        if (is_null($tweets)) {
            return [];
        }

        if (is_null($fetchedTweetIdList)) {
            return $tweets;
        }

        $showableTweets = [];
        foreach ($tweets as $tweet) {
            if (!in_array($tweet->id, $fetchedTweetIdList)) {
                unset($tweet->user->profile->year);
                unset($tweet->user->profile->month);
                unset($tweet->user->profile->day);
                unset($tweet->user->profile->zipcode1);
                unset($tweet->user->profile->zipcode2);
                unset($tweet->user->profile->address1);
                unset($tweet->user->profile->address2);
                unset($tweet->user->profile->realname);
                unset($tweet->user->profile->updated_at);
                unset($tweet->user->email);
                unset($tweet->user->email_verified_at);
                unset($tweet->user->created_at);
                unset($tweet->user->blockers);
                unset($tweet->user->blockee);
                unset($tweet->user->payjp_customer_id);

                if ($request->user()) {
                    if (!($tweet->user->isBlockedBy($request->user()) ||  $request->user()->isBlockedBy($tweet->user))) {
                        $showableTweets[] = $tweet;
                    }
                } else {
                    $showableTweets[] = $tweet;
                }
            }
        }

        return $showableTweets;
    }

    public function user()
    {
        $allTagNames = Hobby::all()->map(function ($tag) {
            return ['text' => $tag->name.'('.$tag->users->count().'件)'
                    , 'count' => $tag->users->count()];
        });
        $allTagNames = $allTagNames->sortByDesc('count')->toArray();

        foreach ($allTagNames as $key => $val) {
            $convert[]['text'] = $allTagNames[$key]['text'];
        }
        if (empty($convert)) {
            $allTagNames = Hobby::all()->map(function ($tag) {
                return ['text' => $tag->name];
            });
        } else {
            $allTagNames = collect($convert);
        }

        return view('search.user', [
            'allHobbyNames' => $allTagNames
        ]);
    }

    public function fetchSearchUser(Request $request)
    {
        $decodedFetchedUserIdList = json_decode($request->fetchedUserIdList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }
        $users = $this->extractShowUsers($decodedFetchedUserIdList, $request);
        $count = $this->extractShowUsersCount($request);

        return response()->json(['users' => $users,'page' => $request->page,'count' => $count], 200);
    }

    public function extractShowUsers($fetchedUserIdList, $request)
    {
        $limit = 10; // 一度に取得する件数
        $offset = $request->page * $limit; // 現在の取得開始位置

        $json = mb_convert_encoding($request->hobbies, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json, true);

        $query = User::query();
        $query->leftJoin('profiles', function ($query) {
            $query->on('profiles.user_id', 'users.id');
        });
        $query->leftJoin('hobby_user', function ($query) {
            $query->on('hobby_user.user_id', 'users.id');
        });

        //プラン
        if ($request->plan1 == 'false' && $request->plan2 == 'false' && $request->plan3 == 'false') {
            //なにもなし
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'false' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->where('plan', 'standard');
            });
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'true' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->where('plan', 'lite');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'false' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->where('plan', 'free');
            });
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'true' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'lite')->orWhere('plan', 'standard');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'true' && $request->plan3 == 'true') {
            //なにもなし
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'true' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'free')->orWhere('plan', 'lite');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'false' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'free')->orWhere('plan', 'standard');
            });
        }

        //都道府県
        if ($request->pref == '' || $request->pref == 'all') {
        } else {
            $pref = $request->pref;
            $query->where(function ($query) use ($pref) {
                return $query->where('profiles.pref', $pref);
            });
        }

        //性別
        if ($request->male == 'false' && $request->female == 'false' && $request->none == 'false') {
            //なにもなし
        } elseif ($request->male == 'false' && $request->female == 'false' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 9);
            });
        } elseif ($request->male == 'false' && $request->female == 'true' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 2);
            });
        } elseif ($request->male == 'true' && $request->female == 'false' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 1);
            });
        } elseif ($request->male == 'false' && $request->female == 'true' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 2)->orWhere('profiles.gender', 9);
            });
        } elseif ($request->male == 'true' && $request->female == 'true' && $request->none == 'true') {
            //なにもなし
        } elseif ($request->male == 'true' && $request->female == 'true' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 1)->orWhere('profiles.gender', 2);
            });
        } elseif ($request->male == 'true' && $request->female == 'false' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 1)->orWhere('profiles.gender', 9);
            });
        }

        if (($request->status1 == 'false' && $request->status2 == 'false') || ($request->status1 == 'true' && $request->status2 == 'true')) {
            //なにもなし
        } else {
            if ($request->status1 == 'true' && $request->status2 == 'false') {
                $query->where(function ($query) {
                    return $query->where('profiles.status', false);
                });
            } else {
                $query->where(function ($query) {
                    return $query->where('profiles.status', true);
                });
            }
        }

        if (($request->condition1 == 'false' && $request->condition2 == 'false') || ($request->condition1 == 'true' && $request->condition2 == 'true')) {
            //なにもなし
        } else {
            if ($request->condition1 == 'true' && $request->condition2 == 'false') {
                $query->where(function ($query) {
                    return $query->where('profiles.condition', false);
                });
            } else {
                $query->where(function ($query) {
                    return $query->where('profiles.condition', true);
                });
            }
        }

        $keyword = '%'.trim($request->keyword).'%';
        if ($request->keyword != '') {
            $query->where(function ($query) use ($keyword) {
                return $query->where('profiles.profile', 'like', $keyword);
            });
        }

        if (!empty($arr)) {
            $hobby = $arr[0]['text'];
            $hobby = Hobby::where('name', $hobby)->first();
            $query->where(function ($query) use ($hobby) {
                return $query->where('hobby_user.hobby_id', $hobby->id);
            });
        }

        $thisYear = date('Y');
        $thisMonth = date('m');
        $thisDay = date('d');
        $tomorrow = date('Ymd', strtotime('+1 day'));
        $tomorrowYear = substr($tomorrow, 0, 4);
        $tomorrowMonth = substr($tomorrow, 4, 2);
        $tomorrowDay = substr($tomorrow, 6, 2);
        $age1 = $request->age1;
        $age2 = $request->age2;
        if ($request->age1 == 0 && $request->age2 == 100) {
            //全年齢なので、何もしない
        } elseif ($request->age1 == $request->age2) {
            if ($request->age1 == 0 || $request->age1 == 100) {
                //存在しない年齢なので、何もしない
            } else {
                if ($request->age1 == 10) {
                    $query->where(function ($query) use ($tomorrowYear) {
                        return $query->where('profiles.year', '>=', $tomorrowYear - 20);
                    });
                } else {
                    $query->where(function ($query) use ($tomorrowYear, $thisYear, $age1) {
                        return $query->where('profiles.year', '>=', $tomorrowYear - $age1 - 10)->where('profiles.year', '<=', $thisYear - $age1);
                    });
                }
            }
        } else {
            if ($request->age2 == 10) {
                $query->where(function ($query) use ($tomorrowYear) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - 20);
                });
            } elseif ($request->age1 == 0 || $request->age1 == 10) {
                $query->where(function ($query) use ($tomorrowYear, $thisYear, $age2) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - $age2 - 10);
                });
            } else {
                $query->where(function ($query) use ($tomorrowYear, $thisYear, $age1, $age2) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - $age2 - 10)->where('profiles.year', '<=', $thisYear - $age1);
                });
            }
        }

        if ($request->user()) {
            $authId = $request->user()->id;
            $query->whereNotIn('users.id', function ($query) use ($authId) {
                return $query->select('blockee_id')->from('blocks')->where('blocker_id', $authId)->whereNotNull('blockee_id');
            });
            $query->whereNotIn('users.id', function ($query) use ($authId) {
                return $query->select('blocker_id')->from('blocks')->where('blockee_id', $authId)->whereNotNull('blocker_id');
            });
        }

        $users = $query->distinct('users.id')->orderBy('users.id', 'desc')->forPage($request->page + 1, $limit)->get(['users.id']);

        if (is_null($users)) {
            return [];
        }

        if (is_null($fetchedUserIdList)) {
            return $users;
        }

        $showableUsers = [];

        foreach ($users as $user) {
            $user = User::where('id', $user->id)->with(['hobbies','profile'])->first();

            if (!in_array($user->id, $fetchedUserIdList)) {
                if (($user->profile->year != null && $user->profile->month != null && $user->profile->day != null) || (isset($user->profile->year) && isset($user->profile->month) && isset($user->profile->day))) {
                    $birthday = sprintf('%04d', $user->profile->year).sprintf('%02d', $user->profile->month).sprintf('%02d', $user->profile->day);
                    $today = date('Ymd');
                    if (floor(($today - $birthday) / 100000) * 10 <= 1) {
                        $user->profile->age = "10代以下";
                    } else {
                        $user->profile->age = floor(($today - $birthday) / 100000) * 10 . "代";
                    }
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
                }
                $showableUsers[] = $user;
            }
        }
        return $showableUsers;
    }

    public function extractShowUsersCount($request)
    {
        $json = mb_convert_encoding($request->hobbies, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json, true);

        $query = User::query();
        $query->leftJoin('profiles', function ($query) {
            $query->on('profiles.user_id', 'users.id');
        });
        $query->leftJoin('hobby_user', function ($query) {
            $query->on('hobby_user.user_id', 'users.id');
        });

        //プラン
        if ($request->plan1 == 'false' && $request->plan2 == 'false' && $request->plan3 == 'false') {
            //なにもなし
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'false' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->where('plan', 'standard');
            });
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'true' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->where('plan', 'lite');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'false' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->where('plan', 'free');
            });
        } elseif ($request->plan1 == 'false' && $request->plan2 == 'true' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'lite')->orWhere('plan', 'standard');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'true' && $request->plan3 == 'true') {
            //なにもなし
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'true' && $request->plan3 == 'false') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'free')->orWhere('plan', 'lite');
            });
        } elseif ($request->plan1 == 'true' && $request->plan2 == 'false' && $request->plan3 == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('plan', 'free')->orWhere('plan', 'standard');
            });
        }

        //都道府県
        if ($request->pref == '' || $request->pref == 'all') {
        } else {
            $pref = $request->pref;
            $query->where(function ($query) use ($pref) {
                return $query->where('profiles.pref', $pref);
            });
        }

        //性別
        if ($request->male == 'false' && $request->female == 'false' && $request->none == 'false') {
            //なにもなし
        } elseif ($request->male == 'false' && $request->female == 'false' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 9);
            });
        } elseif ($request->male == 'false' && $request->female == 'true' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 2);
            });
        } elseif ($request->male == 'true' && $request->female == 'false' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->where('profiles.gender', 1);
            });
        } elseif ($request->male == 'false' && $request->female == 'true' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 2)->orWhere('profiles.gender', 9);
            });
        } elseif ($request->male == 'true' && $request->female == 'true' && $request->none == 'true') {
            //なにもなし
        } elseif ($request->male == 'true' && $request->female == 'true' && $request->none == 'false') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 1)->orWhere('profiles.gender', 2);
            });
        } elseif ($request->male == 'true' && $request->female == 'false' && $request->none == 'true') {
            $query->where(function ($query) {
                return $query->orWhere('profiles.gender', 1)->orWhere('profiles.gender', 9);
            });
        }

        if (($request->status1 == 'false' && $request->status2 == 'false') || ($request->status1 == 'true' && $request->status2 == 'true')) {
            //なにもなし
        } else {
            if ($request->status1 == 'true' && $request->status2 == 'false') {
                $query->where(function ($query) {
                    return $query->where('profiles.status', false);
                });
            } else {
                $query->where(function ($query) {
                    return $query->where('profiles.status', true);
                });
            }
        }

        if (($request->condition1 == 'false' && $request->condition2 == 'false') || ($request->condition1 == 'true' && $request->condition2 == 'true')) {
            //なにもなし
        } else {
            if ($request->condition1 == 'true' && $request->condition2 == 'false') {
                $query->where(function ($query) {
                    return $query->where('profiles.condition', false);
                });
            } else {
                $query->where(function ($query) {
                    return $query->where('profiles.condition', true);
                });
            }
        }

        $keyword = '%'.trim($request->keyword).'%';
        if ($request->keyword != '') {
            $query->where(function ($query) use ($keyword) {
                return $query->where('profiles.profile', 'like', $keyword);
            });
        }

        if (!empty($arr)) {
            $hobby = $arr[0]['text'];
            $hobby = Hobby::where('name', $hobby)->first();
            $query->where(function ($query) use ($hobby) {
                return $query->where('hobby_user.hobby_id', $hobby->id);
            });
        }

        $thisYear = date('Y');
        $thisMonth = date('m');
        $thisDay = date('d');
        $tomorrow = date('Ymd', strtotime('+1 day'));
        $tomorrowYear = substr($tomorrow, 0, 4);
        $tomorrowMonth = substr($tomorrow, 4, 2);
        $tomorrowDay = substr($tomorrow, 6, 2);
        $age1 = $request->age1;
        $age2 = $request->age2;
        if ($request->age1 == 0 && $request->age2 == 100) {
            //全年齢なので、何もしない
        } elseif ($request->age1 == $request->age2) {
            if ($request->age1 == 0 || $request->age1 == 100) {
                //存在しない年齢なので、何もしない
            } else {
                if ($request->age1 == 10) {
                    $query->where(function ($query) use ($tomorrowYear) {
                        return $query->where('profiles.year', '>=', $tomorrowYear - 20);
                    });
                } else {
                    $query->where(function ($query) use ($tomorrowYear, $thisYear, $age1) {
                        return $query->where('profiles.year', '>=', $tomorrowYear - $age1 - 10)->where('profiles.year', '<=', $thisYear - $age1);
                    });
                }
            }
        } else {
            if ($request->age2 == 10) {
                $query->where(function ($query) use ($tomorrowYear) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - 20);
                });
            } elseif ($request->age1 == 0 || $request->age1 == 10) {
                $query->where(function ($query) use ($tomorrowYear, $thisYear, $age2) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - $age2 - 10);
                });
            } else {
                $query->where(function ($query) use ($tomorrowYear, $thisYear, $age1, $age2) {
                    return $query->where('profiles.year', '>=', $tomorrowYear - $age2 - 10)->where('profiles.year', '<=', $thisYear - $age1);
                });
            }
        }

        if ($request->user()) {
            $authId = $request->user()->id;
            $query->whereNotIn('users.id', function ($query) use ($authId) {
                return $query->select('blockee_id')->from('blocks')->where('blocker_id', $authId)->whereNotNull('blockee_id');
            });
            $query->whereNotIn('users.id', function ($query) use ($authId) {
                return $query->select('blocker_id')->from('blocks')->where('blockee_id', $authId)->whereNotNull('blocker_id');
            });
        }
        return $query->distinct('users.id')->count('users.id');
    }
}
