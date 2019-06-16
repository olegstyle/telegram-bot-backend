<?php declare(strict_types=1);

namespace App\Http\Requests\Schedule;

use App\Http\Requests\Api\JsonRequest;

/**
 * @property-read string $title
 * @property-read array|null $minutes
 * @property-read array|null $hours
 * @property-read array|null $day
 * @property-read array|null $month
 * @property-read array|null $weekDay
 * @property-read string|null $active
 */
class CreateOrUpdateScheduleRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'title' => array_merge(['required'], $this->getCommonRules()->getBotLabel()),
            'minutes' => ['nullable', 'array'],
            'minutes.*' => ['required', 'numeric', 'min:0', 'max:59'],
            'hours' => ['nullable', 'array'],
            'hours.*' => ['required', 'numeric', 'min:0', 'max:23'],
            'day' => ['nullable', 'array'],
            'day.*' => ['required', 'numeric', 'min:1', 'max:31'],
            'month' => ['nullable', 'array'],
            'month.*' => ['required', 'numeric', 'min:1', 'max:12'],
            'weekDay' => ['nullable', 'array'],
            'weekDay.*' => ['required', 'numeric', 'min:0', 'max:6'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function getMinutes(): ?array
    {
        return $this->minutes ? array_unique($this->minutes) : null;
    }

    public function getHours(): ?array
    {
        return $this->hours ? array_unique($this->hours) : null;
    }

    public function getDay(): ?array
    {
        return $this->day ? array_unique($this->day) : null;
    }

    public function getMonth(): ?array
    {
        return $this->month ? array_unique($this->month) : null;
    }

    public function getWeekDay(): ?array
    {
        return $this->weekDay ? array_unique($this->weekDay) : null;
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }
}
