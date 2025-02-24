<?php

namespace Vdhicts\Conditions;

use Vdhicts\Conditions\Enums\ConditionLevel;

class Condition
{
    public function __construct(
        public string $name,
        public bool $fulfilled = true,
        public ConditionLevel $level = ConditionLevel::Info,
        public ?string $message = null,
        public array $data = []
    ) {}

    public function setFulfilled(bool $fulfilled): Condition
    {
        $this->fulfilled = $fulfilled;

        return $this;
    }

    public function setLevel(ConditionLevel $level): Condition
    {
        $this->level = $level;

        return $this;
    }

    public function setMessage(?string $message): Condition
    {
        $this->message = $message;

        return $this;
    }

    public function setData(array $data): Condition
    {
        $this->data = $data;

        return $this;
    }
}
