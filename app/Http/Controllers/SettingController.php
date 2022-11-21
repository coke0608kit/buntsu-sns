<?php

namespace App\Http\Controllers;

use App\Article;
use App\Hobby;
use App\User;
use App\Profile;
use App\Ticket;
use App\Sponser;
use App\Question;
use App\Information;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use InterventionImage;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index');
    }

    public function profile()
    {
        $user = Auth::user();
        $hobbyNames = $user->hobbies->map(function ($hobby) {
            return ['text' => $hobby->name.'('.$hobby->users->count().'件)'];
        });

        $allHobbyNames = Hobby::all()->map(function ($hobby) {
            return ['text' => $hobby->name.'('.$hobby->users->count().'件)'
                    , 'count' => $hobby->users->count()];
        });
        $allHobbyNames = $allHobbyNames->sortByDesc('count')->toArray();

        foreach ($allHobbyNames as $key => $val) {
            $convert[]['text'] = $allHobbyNames[$key]['text'];
        }
        if (empty($convert)) {
            $allHobbyNames = Hobby::all()->map(function ($hobby) {
                return ['text' => $hobby->name];
            });
        } else {
            $allHobbyNames = collect($convert);
        }

        return view('setting.profile', [
            'hobbyNames' => $hobbyNames,
            'allHobbyNames' => $allHobbyNames,
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($request->filename == 'none') {
            //画像の変更なし
        } else {
            if ($user->profile->icon != null) {
                $s3 = Storage::disk('S3');
                if (\App::environment(['production'])) {
                    $filename = explode('/icon', $user->profile->icon);
                    $s3->delete('icon'.$filename[1]);
                } else {
                    $filename = explode('/testIcon', $user->profile->icon);
                    $s3->delete('testIcon'.$filename[1]);
                }
            }
            $user->profile->icon = 'https://buntsu-sns.s3.ap-northeast-1.amazonaws.com/'.substr($request->filename, 2);
        }
        $user->profile->user_id = $request->id;
        $user->profile->gender = $request->gender;
        $user->profile->year = $request->year;
        $user->profile->month = $request->month;
        $user->profile->day = $request->day;
        $user->profile->zipcode1 = $request->zipcode1;
        $user->profile->zipcode2 = $request->zipcode2;
        $user->profile->pref = $request->pref;
        $user->profile->address1 = $request->address1;
        $user->profile->address2 = $request->address2;
        $user->profile->realname = $request->realname;
        $user->profile->profile = $request->profile;
        $user->profile->canSendGender = $request->canSendGender;
        $user->profile->status = $request->status;
        $user->profile->condition = $request->condition;
        $user->profile->save();

        $user->hobbies()->detach();
        foreach ($request->hobbies as $key => $val) {
            $hobby = Hobby::firstOrCreate(['name' => $val]);
            $user->hobbies()->attach($hobby);
        }

        $user->nickname = $request->nickname;
        $user->save();
        return 'success';
    }

    public function block()
    {
        return view('setting.block', [
            'user' => Auth::user()
        ]);
    }

    public function plan()
    {
        $user = Auth::user();
        if ($user->plan != 'free' && $user->planStatus == 0) {
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
            $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
            foreach ($cu['subscriptions']['data'] as $subId) {
                if ($subId['status'] == 'active' && $subId['plan']['id'] == $user->plan) {
                    $su = \Payjp\Subscription::retrieve($subId['id']);
                    break;
                }
            }
        } elseif ($user->plan != 'free' && $user->planStatus == 1) {
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
            $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
            foreach ($cu['subscriptions']['data'] as $subId) {
                if ($subId['status'] == 'canceled' && $subId['plan']['id'] == $user->plan) {
                    $su = \Payjp\Subscription::retrieve($subId['id']);
                    break;
                }
            }
        } else {
            $su = 'free';
        }
        return view('setting.plan', [
            'user' => $user
            ,'su' => $su
        ]);
    }

    public function compressImage(UploadedFile $img_file, string $path, int $quality, $size)
    {
        $image = InterventionImage::make($img_file);
        $image->save($path, $quality);
    }

    public function credit()
    {
        $user = Auth::user();
        $cardList = $this->cardList();

        // 既にpayjpに登録済みの場合
        if (!empty($user->payjp_customer_id)) {
            // カード一覧を取得
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.credit', compact('cardList', 'user', 'default_card'));
        }
        return view('setting.credit', compact('cardList', 'user'));
    }

    public function cardList()
    {
        $user = Auth::user();
        $cardList = [];

        // 既にpayjpに登録済みの場合
        if (!empty($user->payjp_customer_id)) {
            // カード一覧を取得
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
            $cardDatas = \Payjp\Customer::retrieve($user->payjp_customer_id)->cards->data;
            foreach ($cardDatas as $cardData) {
                $cardList[] = [
                'id' =>  $cardData->id,
                'cardNumber' =>  "**** **** **** {$cardData->last4}",
                'brand' =>  $cardData->brand,
                'exp_year' =>  $cardData->exp_year,
                'exp_month' =>  $cardData->exp_month,
                'name' =>  $cardData->name,
                ];
            }
        }
        return $cardList;
    }

    public function addressStore(Request $request)
    {
        $user = $request->user();
        $profile = $request->user()->profile;
        $profile->fill($request->all());
        $profile->user_id = $request->user()->id;
        $profile->save();

        $cardList = $this->cardList();
        $message = '住所の登録が完了しました';
        $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
        return view('setting.credit', compact('cardList', 'message', 'user', 'default_card'));
    }

    public function creditStore(Request $request)
    {
        $user = Auth::user();
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
        if (empty($request->get('payjp-token')) && !$request->get('payjp_card_id')) {
            $cardList = $this->cardList();
            $message = 'カードが選択されていません';
            return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
        } elseif ($request->get('submit') == 'delete') {
            $cu = \Payjp\Customer::retrieve($user->payjp_customer_id);
            $card = $cu->cards->retrieve($request->get('payjp_card_id'));
            $card->delete();
            $cardList = $this->cardList();
            $message = 'カードの削除が完了しました';
            return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
        } else {
            try {
                //  以前使用したカードを使う場合
                if (!empty($request->get('payjp_card_id'))) {
                    $customer = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    // 使用するカードを設定
                    $customer->default_card = $request->get('payjp_card_id');
                    $customer->save();

                    $cardList = $this->cardList();
                    $message = 'メインカードを変更しました';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));

                //  既にpayjpに登録済みの場合
                } elseif (!empty($user['payjp_customer_id'])) {
                    // カード情報を追加
                    $customer = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    $card = $customer->cards->create([
                        'card' => $request->get('payjp-token'),
                    ]);
                    // 使用するカードを設定
                    $customer->default_card = $card->id;
                    $customer->save();
                    $cardList = $this->cardList();
                    $message = 'カードの登録が完了しました';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
                //  payjp未登録の場合
                } else {
                    // payjpで顧客新規登録 & カード登録
                    $customer = \Payjp\Customer::create([
                        'card' => $request->get('payjp-token'),
                    ]);
                    // DBにcustomer_idを登録
                    $user->payjp_customer_id = $customer->id;
                    $user->save();
                    $cardList = $this->cardList();
                    $message = 'カードの登録が完了しました';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
                }
            } catch (\Exception $e) {
                if (strpos($e, 'has already been used') !== false) {
                    $cardList = $this->cardList();
                    $message = '既に登録されているカード情報です';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
                }
                $cu = \Payjp\Customer::retrieve($user->payjp_customer_id);
                $card = $cu->cards->retrieve($request->get('payjp_card_id'));
                $card->delete();
                $cardList = $this->cardList();
                $message = '失効済みのカード情報です';
                $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                return redirect()->route('setting.credit')->with(compact('cardList', 'message', 'user', 'default_card'));
            }
        }
    }

    public function purchaseHistory(Request $request)
    {
        $user = Auth::user();
        return view('setting.purchaseHistory', compact('user'));
    }

    public function deleteConfirm()
    {
        return view('setting.deleteConfirm');
    }

    public function delete()
    {
        $user = Auth::user();
        //s3からアイコン画像を削除

        if ($user->profile->icon != '') {
            $s3 = Storage::disk('S3');
            if (\App::environment(['production'])) {
                $filename = explode('/icon', $user->profile->icon);
                $s3->delete('icon'.$filename[1]);
            } else {
                $filename = explode('/testIcon', $user->profile->icon);
                $s3->delete('testIcon'.$filename[1]);
            }
        }

        //s3から投稿画像を削除
        $articles = $user->articles();
        foreach ($articles as $article) {
            $s3 = Storage::disk('S3');
            if (\App::environment(['production'])) {
                $filename = explode('/items', $article->image);
                $s3->delete('items'.$filename[1]);
            } else {
                $filename = explode('/testItems', $article->image);
                $s3->delete('testItems'.$filename[1]);
            }
        }

        if ($user->payjp_customer_id != '') {
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
            $cu = \Payjp\Customer::retrieve($user->payjp_customer_id);
            $cu->delete();
        }
        $user->delete();
        return redirect()->route('top.index');
    }

    public function history()
    {
        $user = Auth::user();
        $userId = $user->id;

        $tickets = Ticket::where(function ($tickets) use ($userId) {
            $tickets->orWhere('from', $userId)->orWhere('to', $userId);
        })->where('done', 1)->orderBy('updated_at', 'desc')->get();


        $usersInfo = array();
        $count = 0;
        $resultCount = 0;
        $userIds = array();
        foreach ($tickets as $ticket) {
            $fromUser = User::where('id', $ticket->from)->first();
            $toUser = User::where('id', $ticket->to)->first();
            $usersInfo[$count]['fromUser'] = $fromUser;
            $usersInfo[$count]['toUser'] = $toUser;
            $usersInfo[$count]['ticket'] = $ticket;
            $usersInfo[$count]['status'] = $ticket->done;
            $count++;
            $userIds[] = $fromUser->id;
            $userIds[] = $toUser->id;
        }
        $userIds = array_unique($userIds);
        $resultUserIds = array();
        foreach ($userIds as $key => $value) {
            $userInfo = User::where('id', $value)->first();

            $userId = $userInfo->id;
            $ticket = Ticket::where(function ($tickets) use ($userId) {
                $tickets->orWhere('from', $userId)->orWhere('to', $userId);
            })->where('done', 1)->orderBy('updated_at', 'desc')->first();

            if (empty($resultUserIds[$resultCount]['latest'])) {
                $resultUserIds[$resultCount]['latest'] = $ticket->updated_at;
            }
            if ($value != $user->id) {
                $resultUserIds[$resultCount]['id'] = $userInfo->id;
                $resultUserIds[$resultCount]['nickname'] = $userInfo->nickname;
                $resultUserIds[$resultCount]['name'] = $userInfo->name;
                $resultUserIds[$resultCount]['icon'] = $userInfo->profile->icon;
                $resultCount++;
            } else {
                unset($resultUserIds[$resultCount]);
            }
        }

        return view('setting.history', compact('usersInfo', 'resultUserIds'));
    }

    public function qutte()
    {
        return view('setting.qutte');
    }

    public function sponsers()
    {
        $sponsersA = Sponser::where('rank', 'A')->get();
        $sponsersB = Sponser::where('rank', 'B')->get();
        $sponsersC = Sponser::where('rank', 'C')->get();
        return view('setting.sponsers', compact('sponsersA', 'sponsersB', 'sponsersC'));
    }

    public function questions()
    {
        return view('setting.questions');
    }

    public function fetchQuestions(Request $request)
    {
        $decodedFetchedQuestionsList = json_decode($request->fetchedQuestionsList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }
        // ツイートを取得
        $questions = $this->extractShowQuestions($decodedFetchedQuestionsList, $request);

        return response()->json(['questions' => $questions], 200);
    }

    public function extractShowQuestions($fetchedQuestionsList, $request)
    {
        $limit = 30; // 一度に取得する件数
        $offset = $request->page * $limit; // 現在の取得開始位置
        $questions = Question::orderBy('id', 'desc')->offset($offset)->take($limit)->get();

        if (is_null($questions)) {
            return [];
        }

        if (is_null($fetchedQuestionsList)) {
            return $questions;
        }

        $showableQuestions = [];
        foreach ($questions as $question) {
            if (!in_array($question->id, $fetchedQuestionsList)) {
                $showableQuestions[] = $question;
            }
        }

        return $showableQuestions;
    }

    public function information()
    {
        return view('setting.information');
    }

    public function fetchInformation(Request $request)
    {
        $decodedFetchedInformationList = json_decode($request->fetchedInformationList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }
        // ツイートを取得
        $information = $this->extractShowInformation($decodedFetchedInformationList, $request);

        return response()->json(['information' => $information], 200);
    }

    public function extractShowInformation($fetchedInformationList, $request)
    {
        $limit = 30; // 一度に取得する件数
        $offset = $request->page * $limit; // 現在の取得開始位置
        $information = Information::orderBy('id', 'desc')->offset($offset)->take($limit)->get();

        if (is_null($information)) {
            return [];
        }

        if (is_null($fetchedInformationList)) {
            return $information;
        }

        $showableInformation = [];
        foreach ($information as $data) {
            if (!in_array($data->id, $fetchedInformationList)) {
                $showableInformation[] = $data;
            }
        }

        return $showableInformation;
    }

    public function news($id)
    {
        $information = Information::where('id', $id)->first();
        if ($information == null) {
            abort(404);
        }
        return view('setting.news', compact('information'));
    }

    public function getPreSignedUrl(Request $request)
    {
        $filename = $request->params['id'].'-'.now()->format('YmdHis').'.'.$request->params['ext'];
        $s3 = Storage::disk('preS3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+3 minutes";

        if (\App::environment(['production'])) {
            $filename = './icon/'.$filename;
        } else {
            $filename = './testIcon/'.$filename;
        }

        $command = $client->getCommand('PutObject', [
        'Bucket' => 'converted-buntsu-sns-image',
        'Key' => $filename,
        'ACL' => 'public-read'
        ]);
        $request = $client->createPresignedRequest($command, $expiry);

        $data[] = $request->getUri();
        $data[] = $filename;

        return $data;
    }

    public function howtouse()
    {
        return view('setting.howtouse');
    }
}
