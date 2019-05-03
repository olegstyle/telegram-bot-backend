<?php declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Enums\ParseMode;
use OlegStyle\ValueObject\ValueObject;

class Message extends ValueObject
{
    /** @var string */
    protected $text;
    /** @var string|null */
    protected $photoPath;
    /** @var ParseMode */
    protected $parseMode;

    public function __construct(string $text, ?ParseMode $parseMode = null)
    {
        $this->text = $text;
        $this->parseMode = $parseMode ?? ParseMode::getDefault();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getParseMode(): ParseMode
    {
        return $this->parseMode;
    }

    public function setParseMode(ParseMode $parseMode): void
    {
        $this->parseMode = $parseMode;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): void
    {
        $this->photoPath = $photoPath;
    }

    public function hasPhoto(): bool
    {
        return $this->photoPath !== null;
    }
}
