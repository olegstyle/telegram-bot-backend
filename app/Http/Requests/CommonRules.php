<?php declare(strict_types=1);


namespace App\Http\Requests;


class CommonRules
{
    public function getBotLabel(): array
    {
        return ['string', 'min:1', 'max:32'];
    }

    public function getPostTitle(): array
    {
        return ['string', 'min:1', 'max:64'];
    }

    public function getPostMessage(): array
    {
        return ['string', 'min:1', 'max:2048'];
    }

    public function getPostPhoto(): array
    {
        return ['file', 'mimes:jpeg,png'];
    }
}
