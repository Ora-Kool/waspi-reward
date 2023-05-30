<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use App\Models\Like;
use App\Models\Post;
use App\Models\Point;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function storePost(PostRequest $postRequest): RedirectResponse
    {
        Post::create([
            "user_id" => $postRequest->user()->id,
            "title" => $postRequest->input("title"),
            "message" => $postRequest->input("message")
        ]);

        return back();
    }

    public function likePost(Post $post): RedirectResponse
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            $this->createPostLike($post);

            $this->setLikeBadge($user);

            $this->deleteLike($post);

            DB::commit();
            return back();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function storeComment(Post $post, CommentRequest $commentRequest): RedirectResponse
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            $this->setCommentBadge($user);

            Comment::create([
                'user_id' => auth()->user()->id,
                'post_id' => $post->id,
                'message' => $commentRequest->input('comment')
            ]);

            DB::commit();
            return back();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deleteComment(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back();
    }

    public function createPostLike($post): void
    {
        $filtered = $post->likes->contains(function (object $like, int $key) {
            return $like->user_id === auth()->user()->id;
        });

        if ($filtered === false) {
            $post->likes()->create([
                "user_id" => auth()->user()->id
            ]);
        }
    }

    public function deleteLike($post)
    {
        $like = $post->likes->filter(function (object $like, int $key) {
            return $like->user_id === auth()->user()->id;
        });

        if ($like->isNotEmpty()) {
            $like->first()->delete();
        }
    }

    public function setLikeBadge($user)
    {
        if (count($user->likes) === 10) {
            $user->increment('points', 500);

            if ($user->badges->contains(1) === false) {
                $user->badges()->attach(1);
            }
        }
    }

    public function setCommentBadge($user): void
    {
        if (count($user->comments()->withTrashed()->get()) === 0) {
            $user->increment('points', 50);

            if ($user->badges->contains(1) === false) {
                $user->badges()->attach(1);
            }
        }

        if (count($user->comments()->withTrashed()->get()) === 29) {
            $user->increment('points', 2500);
            $user->badges()->attach(2);
        }

        if (count($user->comments()->withTrashed()->get()) === 49) {
            $user->increment('points', 5000);
            $user->badges()->attach(3);
        }
    }
}