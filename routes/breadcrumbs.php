<?php

use App\User;

// ホーム
Breadcrumbs::for('home', function ($trail) {
    $trail->push('HOME', url('home'));
});

// ホーム > [$user->nickname]
Breadcrumbs::for('user', function ($trail, $user) {
    $trail->parent('home');
    $trail->push($user->nickname, url('users/' . $user->name));
});

// ホーム > [$user->nickname] >  投稿
Breadcrumbs::for('post', function ($trail, $article) {
    $user = User::where('id', $article->user_id)->first();
    $trail->parent('user', $user);
    $trail->push('投稿', url('articles/' . $article->id));
});

// ホーム > [$user->nickname] >  フォロワーリスト
Breadcrumbs::for('followers', function ($trail, $user) {
    $trail->parent('user', $user);
    $trail->push('フォロワーリスト', url('users/' . $user->id .'/followers'));
});

// ホーム > [$user->nickname] >  フォローしたリスト
Breadcrumbs::for('followings', function ($trail, $user) {
    $trail->parent('user', $user);
    $trail->push('フォローしたリスト', url('users/' . $user->id .'/followings'));
});

// ホーム > 投稿検索
Breadcrumbs::for('searchArticle', function ($trail) {
    $trail->parent('home');
    $trail->push('投稿検索', url('search/article'));
});

// ホーム > ユーザ検索
Breadcrumbs::for('searchUser', function ($trail) {
    $trail->parent('home');
    $trail->push('ユーザ検索', url('search/user'));
});

// ホーム > タグ #[$tag]
Breadcrumbs::for('tag', function ($trail, $tag) {
    $trail->parent('home');
    $trail->push("タグ #".$tag, url('tags/' . $tag));
});

// ホーム > 趣味 #[$tag]
Breadcrumbs::for('hobby', function ($trail, $hobby) {
    $trail->parent('home');
    $trail->push("趣味 #".$hobby, url('hobbies/' . $hobby));
});

// ホーム > 設定
Breadcrumbs::for('setting', function ($trail) {
    $trail->parent('home');
    $trail->push("設定", url('setting/'));
});

// ホーム > 設定 > プロフィール
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('setting');
    $trail->push('プロフィール', url('setting/profile'));
});

// ホーム > 設定 > ブロック
Breadcrumbs::for('block', function ($trail) {
    $trail->parent('setting');
    $trail->push('ブロック', url('setting/block'));
});

// ホーム > 設定 > プラン
Breadcrumbs::for('plan', function ($trail) {
    $trail->parent('setting');
    $trail->push('プラン', url('setting/plan'));
});

// ホーム > 設定 > プラン > 支払い
Breadcrumbs::for('payment', function ($trail) {
    $trail->parent('plan');
    $trail->push('お支払い', url('setting/payment'));
});

// ホーム > 設定 > クレジットカード管理
Breadcrumbs::for('credit', function ($trail) {
    $trail->parent('setting');
    $trail->push('カード管理', url('setting/credit'));
});

// ホーム > 設定 > クレジットカード管理
Breadcrumbs::for('purchaseHistory', function ($trail) {
    $trail->parent('setting');
    $trail->push('Q手購入履歴', url('setting/purchaseHistory'));
});

// ホーム > 設定 > プロフィール
Breadcrumbs::for('delete', function ($trail) {
    $trail->parent('setting');
    $trail->push('退会', url('setting/deleteConfirm'));
});

// ホーム > 設定 > やりとり
Breadcrumbs::for('history', function ($trail) {
    $trail->parent('setting');
    $trail->push('やりとり', url('setting/history'));
});

// ホーム > お問い合わせ
Breadcrumbs::for('contact', function ($trail) {
    $trail->parent('home');
    $trail->push('お問い合わせ', url('/contact'));
});

// ホーム > 利用規約
Breadcrumbs::for('terms', function ($trail) {
    $trail->parent('home');
    $trail->push('利用規約', url('/terms'));
});

// ホーム > プライバシーポリシー
Breadcrumbs::for('privacy', function ($trail) {
    $trail->parent('home');
    $trail->push('プライバシーポリシー', url('/privacy'));
});

// ホーム > プライバシーポリシー
Breadcrumbs::for('tokushoho', function ($trail) {
    $trail->parent('home');
    $trail->push('特定商取引法に基づく表記', url('/tokushoho'));
});

// ホーム > プライバシーポリシー
Breadcrumbs::for('sponser', function ($trail) {
    $trail->parent('home');
    $trail->push('スポンサー', url('/sponsers'));
});

// ホーム > よくある質問
Breadcrumbs::for('questions', function ($trail) {
    $trail->parent('home');
    $trail->push('よくある質問', url('/questions'));
});

// ホーム > お知らせ一覧
Breadcrumbs::for('information', function ($trail) {
    $trail->parent('home');
    $trail->push('お知らせ一覧', url('/information'));
});

// ホーム > お知らせ一覧
Breadcrumbs::for('news', function ($trail) {
    $trail->parent('information');
    $trail->push('お知らせ', url('/'));
});
