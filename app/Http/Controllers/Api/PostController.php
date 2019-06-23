<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\ResourceCollection;
use App\Http\Responses\SuccessResponse;
use App\Models\BotChat;
use App\Models\Post;
use App\Services\Bots\Telegram\TelegramBot;
use React\EventLoop\Factory;

class PostController extends Controller
{
    public function all(JsonRequest $request): ResourceCollection
    {
        return new ResourceCollection($request->user()->posts()->orderDescById()->get());
    }

    public function get(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function create(CreatePostRequest $request): PostResource
    {
        $post = new Post();
        $post->user()->associate($request->user());
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

    public function immediatelySend(Post $post, BotChat $botChat): SuccessResponse
    {
        $loop = Factory::create();
        $message = new Message($post->message);
        $message->setPhotoPath($post->getPhotoFullPath());
        // TODO use bot factory
        (new TelegramBot($loop, $botChat->bot))->sendMessage($botChat, $message);
        $loop->run();

        return new SuccessResponse();
    }
}
