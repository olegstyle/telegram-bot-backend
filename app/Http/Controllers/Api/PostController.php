<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostsResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Post;

class PostController extends Controller
{
    public function all(JsonRequest $request): PostsResource
    {
        return new PostsResource($request->user()->posts);
    }

    public function get(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function create(CreatePostRequest $request): PostResource
    {
        $post = new Post();
        $post->title = $request->title;
        $post->message = $request->message;
        $post->active = $request->isActive();
        if ($request->photo) {
            $post->setPhoto($request->photo);
        }
        $post->save();

        return new PostResource($post);
    }

    public function update(Post $post, UpdatePostRequest $request): PostResource
    {
        if ($request->title) {
            $post->title = $request->title;
        }
        if ($request->message) {
            $post->message = $request->message;
        }
        if ($request->active !== null) {
            $post->active = $request->isActive();
        }
        if ($request->photo) {
            $post->setPhoto($request->photo);
        }
        $post->save();

        return new PostResource($post);
    }

    public function delete(Post $post): SuccessResponse
    {
        $post->delete();

        return new SuccessResponse();
    }
}
