<?php

namespace Vdhicts\Conditions;

use Illuminate\Support\Arr;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionTransformer
{
    public static function fromArray(array $condition): Condition
    {
        return new Condition(
            name: Arr::get($condition, 'name'),
            fulfilled: Arr::get($condition, 'fulfilled', false),
            level: ConditionLevel::tryFrom(Arr::get($condition, 'level')),
            message: Arr::get($condition, 'message'),
            data: Arr::get($condition, 'data', []),
        );
    }

    public static function toArray(Condition $condition): array
    {
        return [
            'name' => $condition->name,
            'fulfilled' => $condition->fulfilled,
            'level' => $condition
                ->level
                ->value,
            'message' => $condition->message,
            'data' => $condition->data,
        ];
    }
}
