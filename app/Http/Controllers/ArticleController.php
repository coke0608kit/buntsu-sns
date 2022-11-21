<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\User;
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
        return view('articles.index');
    }

    public function allArticles()
    {
        return view('articles.all');
    }

    public function fetch(Request $request)
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

        if ($request->pageType == 'index') {
            $user = User::where('name', $request->user()->name)->first()
                ->load('followings.followers');
            $tweets = $user
                ? $user->followings
                    ->map(function ($following) {
                        return $following->articles->load(['user', 'likes', 'tags', 'user.profile']);
                    })
                    ->push($user->articles->load(['user', 'likes', 'tags', 'user.profile'])) // 自分自身の記事を追加
                    ->flatten()
                    ->sortByDesc('id')
                    ->forPage($request->page + 1, $limit)
                : collect();
        } elseif ($request->pageType == 'all') {
            $limit = 30; // 一度に取得する件数
            $offset = $request->page * $limit; // 現在の取得開始位置
            $tweets = Article::orderBy('id', 'desc')->offset($offset)->take($limit)->get()->load(['user', 'likes', 'tags']);
        } elseif ($request->pageType == 'user') {
            $user = User::where('name', $request->user)->first()
            ->load(['articles.user', 'articles.likes', 'articles.tags']);
            $articles = $user->articles->sortByDesc('id');
            $tweets = $user->articles->load(['user', 'likes', 'tags'])->sortByDesc('id')->forPage($request->page + 1, $limit);
        } elseif ($request->pageType == 'tags') {
            $tags = Tag::where('name', $request->test)->first()->load(['articles.user', 'articles.likes', 'articles.tags']);
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


    public function create()
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

        return view('articles.create', [
            'allTagNames' => $allTagNames,
            'authId' => Auth::user()->id,
        ]);
    }

    public function store(Request $request, Article $article)
    {
        $url = 'https://buntsu-sns.s3.ap-northeast-1.amazonaws.com/';
        $article->image = $url.substr($request->filename, 2);
        $article->user_id = $request->id;
        $article->save();
        foreach ($request->tags as $key => $val) {
            $tag = Tag::firstOrCreate(['name' => $val]);
            $article->tags()->attach($tag);
        }
        return 'success';
    }

    public function edit(Article $article)
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

        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name.'('.$tag->articles->count().'件)'];
        });

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });
        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        // s3オブジェクト生成
        $s3 = Storage::disk('S3');
        if (\App::environment(['production'])) {
            $filename = explode('/items', $article->image);
            $s3->delete('items'.$filename[1]);
        } else {
            $filename = explode('/testItems', $article->image);
            $s3->delete('testItems'.$filename[1]);
        }
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        if (!Auth::user() || !($article->user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($article->user))) {
            return view('articles.show', ['article' => $article]);
        } else {
            return redirect()->route('articles.index');
        }
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function getPreSignedUrl(Request $request)
    {
        $filename = $request->params['id'].'-'.now()->format('YmdHis').'.'.$request->params['ext'];
        $s3 = Storage::disk('preS3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+3 minutes";

        if (\App::environment(['production'])) {
            $filename = './items/'.$filename;
        } else {
            $filename = './testItems/'.$filename;
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
}
