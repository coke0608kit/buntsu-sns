<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompleteCheckout;
use App\Buying;
use App\First;
use App\Shipment;
use App\User;

class PaymentController extends Controller
{
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

    public function index()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
        $type = 'index';
        return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
    }

    public function payment(Request $request)
    {
        $user = Auth::user();
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        if (empty($request->get('payjp-token')) && !$request->get('payjp_card_id')) {
            try {
                if ($request->get('type') == 'cancelLite') {
                    $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    foreach ($cu['subscriptions']['data'] as $subId) {
                        if ($subId['status'] == 'active' && $subId['plan']['id'] == 'lite') {
                            $su = \Payjp\Subscription::retrieve($subId['id']);
                            $su->cancel();
                        }
                    }
                    $message = 'ライトプランの解約手続きが完了しました。次の更新タイミングで自動的に解約になります。';
                    $this->checkoutSendForUser($user, $message);
                    $user->planStatus = '1';
                    $user->save();
                    $cardList = $this->cardList();
                    $type = $request->get('type');
                    $message = '解約手続きが完了しました';
                    return view('setting.plan', [
                        'message' => $message
                        , 'user' => $user
                        , 'su' => $su
                    ]);
                } elseif ($request->get('type') == 'cancelStandard') {
                    $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    foreach ($cu['subscriptions']['data'] as $subId) {
                        if ($subId['status'] == 'active' && $subId['plan']['id'] == 'standard') {
                            $su = \Payjp\Subscription::retrieve($subId['id']);
                            $su->cancel();
                        }
                    }
                    $message = 'スタンダードプランの解約手続きが完了しました。次の更新タイミングで自動的に解約になります。';
                    $this->checkoutSendForUser($user, $message);
                    $user->planStatus = '1';
                    $user->save();
                    $cardList = $this->cardList();
                    $type = $request->get('type');
                    $message = '解約手続きが完了しました';
                    return view('setting.plan', [
                        'message' => $message
                        , 'user' => $user
                        , 'su' => $su
                    ]);
                } else {
                    $cardList = $this->cardList();
                    $type = $request->get('type');
                    $message = 'カードが選択されていません';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user', 'default_card'));
                }
            } catch (\Exception $e) {
                $message = '処理が失敗しました';
                return view('setting.plan', [
                    'message' => $message
                    , 'user' => $user
                    , 'su' => $user->plan
                ]);
            }
        } elseif ($request->get('submit') == 'delete') {
            try {
                $cu = \Payjp\Customer::retrieve($user->payjp_customer_id);
                $card = $cu->cards->retrieve($request->get('payjp_card_id'));
                $card->delete();
                $cardList = $this->cardList();
                $type = $request->get('type');
                $message = 'カードの削除が完了しました';
                $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user', 'default_card'));
            } catch (\Exception $e) {
                $message = '処理が失敗しました';
                return view('setting.plan', [
                    'message' => $message
                    , 'user' => $user
                    , 'su' => $user->plan
                ]);
            }
        } else {
            try {
                //  以前使用したカードを使う場合
                if (!empty($request->get('payjp_card_id'))) {
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    $customer = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    // 使用するカードを設定
                    $customer->default_card = $request->get('payjp_card_id');
                    $customer->save();

                    if ($request->get('type') == 'singleQutte') {
                        \Payjp\Charge::create(array(
                            "customer" => $customer->id,
                            'amount' => $request->get('price') * $request->get('quantity'),
                            'currency' => 'jpy'
                          ));
                        $buying = new Buying();
                        $buying->fill($request->all());
                        $buying->totalPrice = $request->get('price') * $request->get('quantity');
                        $buying->done = 0;
                        $buying->plan = $user->plan;
                        $buying->user_id = $user->id;
                        $buying->save();
                        $message = 'Q手の購入が完了しました。'.$request->get('quantity').'枚分で合計'.$request->get('price') * $request->get('quantity').'円です。';
                        $this->checkoutSendForUser($user, $message, 'singleQutte');
                        $message = 'Q手の購入が完了しました。'.$request->get('quantity').'枚分で合計'.$request->get('price') * $request->get('quantity').'円です。';
                        $this->checkoutSendForAdmin($user, $message, 'singleQutte');
                    } elseif ($request->get('type') == 'setQutte') {
                        //  支払い処理
                        // 新規支払い情報作成
                        \Payjp\Charge::create(array(
                            "customer" => $customer->id,
                            'amount' => $request->get('price') * $request->get('quantity'),
                            'currency' => 'jpy'
                          ));
                        $buying = new Buying();
                        $buying->fill($request->all());
                        $buying->totalPrice = $request->get('price') * $request->get('quantity');
                        $buying->done = 0;
                        $buying->plan = $user->plan;
                        $buying->user_id = $user->id;
                        $buying->save();
                        $message = 'Q手の購入が完了しました。'.$request->get('quantity').'セット分で合計'.$request->get('price') * $request->get('quantity').'円です。';
                        $this->checkoutSendForUser($user, $message, 'setQutte');
                        $message = 'Q手の購入が完了しました。'.$request->get('quantity').'セット分で合計'.$request->get('price') * $request->get('quantity').'円です。';
                        $this->checkoutSendForAdmin($user, $message, 'setQutte');
                    } elseif ($request->get('type') == 'lite') {
                        \Payjp\Charge::create(array(
                            "customer" => $customer->id,
                            'amount' => 550,
                            'currency' => 'jpy'
                          ));
                        \Payjp\Subscription::create([
                            // 上記で登録した顧客のidを指定
                            "customer" => $customer->id,
                            "plan" => 'lite',
                        ]);
                        $user->plan = 'lite';
                        $user->save();
                        $message = 'ライトプランの加入が完了しました。';
                        $this->checkoutSendForUser($user, $message);
                        $message = '新規のライトプラン加入が完了しました。初期配送手続きが必要です';
                        $this->checkoutSendForAdmin($user, $message);
                    } elseif ($request->get('type') == 'changeLite') {
                        $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);

                        if ($user->planStatus == 0) {
                            foreach ($cu['subscriptions']['data'] as $subId) {
                                if ($subId['status'] == 'active' && $subId['plan']['id'] == 'standard') {
                                    $su = \Payjp\Subscription::retrieve($subId['id']);
                                    $su->next_cycle_plan = "lite";
                                    $su->save();
                                    break;
                                }
                            }
                        } else {
                            foreach ($cu['subscriptions']['data'] as $subId) {
                                if ($subId['status'] == 'canceled' && $subId['plan']['id'] == 'standard') {
                                    $su = \Payjp\Subscription::retrieve($subId['id']);
                                    $su->next_cycle_plan = "lite";
                                    $su->save();
                                    break;
                                }
                            }
                        }
                        $message = 'スタンダードプランを解約手続きし、ライトプランの加入設定が完了しました。スタンダードプランとして月末まで継続され、次の更新タイミングでライトプランとなります。';
                        $this->checkoutSendForUser($user, $message);
                    } elseif ($request->get('type') == 'restartLite') {
                        $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                        foreach ($cu['subscriptions']['data'] as $subId) {
                            if ($subId['status'] == 'canceled' && $subId['plan']['id'] == 'lite') {
                                $su = \Payjp\Subscription::retrieve($subId['id']);
                                $su->next_cycle_plan = "lite";
                                $su->resume();
                                break;
                            }
                        }
                        $message = 'ライトプランの復帰手続きが完了しました。';
                        $this->checkoutSendForUser($user, $message);
                        $user->planStatus = '0';
                        $user->save();
                        $cardList = $this->cardList();
                        $type = $request->get('type');
                        $message = '復帰手続きが完了しました';
                        return view('setting.plan', [
                            'message' => $message
                            , 'user' => $user
                            , 'su' => $su
                        ]);
                    } elseif ($request->get('type') == 'standard') {
                        \Payjp\Charge::create(array(
                            "customer" => $customer->id,
                            'amount' => 550,
                            'currency' => 'jpy'
                          ));
                        \Payjp\Subscription::create([
                            // 上記で登録した顧客のidを指定
                            "customer" => $customer->id,
                            "plan" => 'standard',
                        ]);
                        $user->plan = 'standard';
                        $user->save();
                        $message = 'スタンダードプランの加入が完了しました。';
                        $this->checkoutSendForUser($user, $message);
                        $message = '新規のスタンダードプラン加入が完了しました。初期配送手続きが必要です';
                        $this->checkoutSendForAdmin($user, $message);
                    } elseif ($request->get('type') == 'changeStandard') {
                        $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);

                        if ($user->planStatus == 0) {
                            foreach ($cu['subscriptions']['data'] as $subId) {
                                if ($subId['status'] == 'active' && $subId['plan']['id'] == 'lite') {
                                    $su = \Payjp\Subscription::retrieve($subId['id']);
                                    $su->next_cycle_plan = "standard";
                                    $su->save();
                                    break;
                                }
                            }
                        } else {
                            foreach ($cu['subscriptions']['data'] as $subId) {
                                if ($subId['status'] == 'canceled' && $subId['plan']['id'] == 'lite') {
                                    $su = \Payjp\Subscription::retrieve($subId['id']);
                                    $su->next_cycle_plan = "standard";
                                    $su->save();
                                    break;
                                }
                            }
                        }
                        $message = 'ライトプランを解約手続きし、スタンダードプランの加入設定が完了しました。ライトプランとして月末まで継続され、次の更新タイミングでスタンダードプランとなります。';
                        $this->checkoutSendForUser($user, $message);
                    } elseif ($request->get('type') == 'restartStandard') {
                        $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                        foreach ($cu['subscriptions']['data'] as $subId) {
                            if ($subId['status'] == 'canceled' && $subId['plan']['id'] == 'standard') {
                                $su = \Payjp\Subscription::retrieve($subId['id']);
                                $su->next_cycle_plan = "standard";
                                $su->resume();
                                break;
                            }
                        }
                        $message = 'スタンダードプランの復帰手続きが完了しました。';
                        $this->checkoutSendForUser($user, $message);
                        $user->planStatus = '0';
                        $user->save();
                        $cardList = $this->cardList();
                        $type = $request->get('type');
                        $message = '復帰手続きが完了しました';
                        return view('setting.plan', [
                            'message' => $message
                            , 'user' => $user
                            , 'su' => $su
                        ]);
                    }
                    $cardList = $this->cardList();
                    $type = $request->get('type');
                    $message = '支払いが完了しました';
                    $cu = \Payjp\Customer::retrieve($user['payjp_customer_id']);
                    foreach ($cu['subscriptions']['data'] as $subId) {
                        if ($subId['status'] == 'active' && $subId['plan']['id'] == $user->plan) {
                            $su = \Payjp\Subscription::retrieve($subId['id']);
                            break;
                        }
                    }
                    if (empty($su)) {
                        $su = 'free';
                    }
                    return view('setting.plan', [
                        'message' => $message
                        , 'user' => $user
                        , 'su' => $su
                    ]);

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
                    $type = $request->get('type');
                    $message = 'カードの登録が完了しました';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user'));
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
                    $type = $request->get('type');
                    $message = 'カードの登録が完了しました';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user', 'default_card'));
                }
            } catch (\Exception $e) {
                logger('aaa', ['error' => $e]);

                if (strpos($e, 'has already been used') !== false) {
                    $cardList = $this->cardList();
                    $type = $request->get('type');
                    $message = 'カードが以上です。処理に失敗しました。';
                    $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                    return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user', 'default_card'));
                }
                $cardList = $this->cardList();
                $type = $request->get('type');
                $message = '処理に失敗しました。時間を空けて再度アクセスしてください。';
                $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
                return redirect()->route('payment.'.$type)->with(compact('cardList', 'type', 'message', 'user', 'default_card'));
            }
        }
    }

    public function singleQutte()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'singleQutte';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function setQutte()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'setQutte';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function lite()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'lite';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function standard()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'standard';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function changeLite()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'changeLite';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function changeStandard()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'changeStandard';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function cancelLite()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'cancelLite';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function restartLite()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'restartLite';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function cancelStandard()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'cancelStandard';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function restartStandard()
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = Auth::user();
        $cardList = $this->cardList();
        $type = 'restartStandard';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function addressStore(Request $request)
    {
        \Payjp\Payjp::setApiKey(config('payjp.secret_key'));
        $user = $request->user();
        $profile = $request->user()->profile;
        $profile->fill($request->all());
        $profile->user_id = $request->user()->id;
        $profile->save();

        $cardList = $this->cardList();
        $type = $request->get('type');
        $message = '住所の登録が完了しました';
        if (isset($user->payjp_customer_id)) {
            $default_card = \Payjp\Customer::retrieve($user->payjp_customer_id)->default_card;
            return view('setting.payment', compact('cardList', 'type', 'user', 'default_card'));
        } else {
            return view('setting.payment', compact('cardList', 'type', 'user'));
        }
    }

    public function webhook(Request $request)
    {
        $headers = getallheaders();
        $check = $headers['X-Payjp-Webhook-Token'] === config('payjp.webhook') ? true : false;
        if ($check) {
            if ($request->livemode) {
                logger('hoban', ['payjp' => $request]);
                if ($request->type == 'subscription.deleted') {
                    $user = User::where('subId', $request->data['id'])->first();
                    $user->plan = 'free';
                    $user->subId = '';
                    $user->planStatus = 0;
                    $user->save();
                } else {
                    if ($request->type == 'subscription.created') {
                        $user = User::where('payjp_customer_id', $request->data['customer'])->first();
                        if ($request->data['status'] == 'active') {
                            $user->subId = $request->data['id'];
                            $user->save();
                        } else {
                            $user->plan = 'free';
                            $user->planStatus = 0;
                            $user->save();
                        }
                    } elseif ($request->type == 'subscription.renewed') {
                        if ($request->data['status'] == 'active') {
                            $user = User::where('payjp_customer_id', $request->data['customer'])->first();
                            $user->subId = $request->data['id'];
                            $user->plan = $request->data['plan']['id'];
                            $user->planStatus = 0;
                            $user->save();
                            $shipment = new Shipment();
                            $shipment->user_id = $user->id;
                            $shipment->plan = $user->plan;
                            $shipment->year = date('Y');
                            $shipment->month = date('m');
                            $shipment->term = 'second';
                            $shipment->shipment = 0;
                        } else {
                            $user->plan = 'free';
                            $user->planStatus = 0;
                            $user->save();
                        }
                    } else {
                        /*
                        関係のない定期購入情報(webhookでは対応しない)
                        subscription.paused
                        subscription.resumed
                        subscription.canceled
                        */
                    }
                }
            } else {
                logger('test', ['payjp' => $request]);
                if ($request->type == 'subscription.deleted') {
                    $user = User::where('subId', $request->data['id'])->first();
                    $user->plan = 'free';
                    $user->subId = '';
                    $user->planStatus = 0;
                    $user->save();
                } else {
                    if ($request->type == 'subscription.created') {
                        $user = User::where('payjp_customer_id', $request->data['customer'])->first();
                        if ($request->data['status'] == 'active') {
                            $user->subId = $request->data['id'];
                            $user->save();
                        } else {
                            $user->plan = 'free';
                            $user->planStatus = 0;
                            $user->save();
                        }
                    } elseif ($request->type == 'subscription.renewed') {
                        if ($request->data['status'] == 'active') {
                            $user = User::where('payjp_customer_id', $request->data['customer'])->first();
                            $user->subId = $request->data['id'];
                            $user->plan = $request->data['plan']['id'];
                            $user->planStatus = 0;
                            $user->save();
                            $shipment = new Shipment();
                            $shipment->user_id = $user->id;
                            $shipment->plan = $user->plan;
                            $shipment->year = date('Y');
                            $shipment->month = date('m');
                            $shipment->term = 'second';
                            $shipment->shipment = 0;
                            $shipment->save();

                            /*
                            $user = User::where('id', '01g9h3abdkyhqnj68ap3ve79hp')->first();
                            $user->subId = 'sub_ad0122df62c896b2fd1c30445e1d';
                            $user->plan = 'standard';
                            $user->planStatus = 0;
                            $user->save();
                            logger('aaaaaaaaaaaaaaaaaaaa', ['payjp' => $user]);
                            $shipment = new Shipment();
                            $shipment->user_id = $user->id;
                            $shipment->plan = $user->plan;
                            $shipment->year = date('Y');
                            $shipment->month = date('m');
                            $shipment->term = 'second';
                            $shipment->shipment = 0;
                            $shipment->save();
                            */
                        } else {
                            $user->plan = 'free';
                            $user->planStatus = 0;
                            $user->save();
                        }
                    } else {
                        /*
                        関係のない定期購入情報(webhookでは対応しない)
                        subscription.paused
                        subscription.resumed
                        subscription.canceled
                        */
                    }
                }
            }
        }
    }

    //メール送信
    public function checkoutSendForUser($user, $message, $qutte = 'none')
    {
        //メール送信
        $data = [
            'message' => $message,
            'email' => $user->email,
            'qutte' => $qutte,
            'userId' => '',
            'plan' => '',
        ];

        Mail::to($data['email'])->send(new CompleteCheckout($data));
        return true;
    }

    //メール送信
    public function checkoutSendForAdmin($user, $message, $qutte = '')
    {
        $first = new First();
        $first->user_id = $user->id;
        $first->done = 0;
        $first->save();

        //メール送信
        $data = [
            'message' => $message,
            'email' => $user->email,
            'userId' => $user->id,
            'plan' => $user->plan,
            'url' => 'https://buntsu-sns.com/admin/manager/firsts/'.$first->id.'/edit',
            'qutte' => $qutte,
        ];

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new CompleteCheckout($data));
        return true;
    }
}
