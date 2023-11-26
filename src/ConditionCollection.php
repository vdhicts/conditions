<?php

namespace Vdhicts\Conditions;

use Illuminate\Support\Collection;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionCollection
{
    /** @var Collection<Condition> */
    private readonly Collection $conditions;

    public function __construct(Collection $conditions = null)
    {
        $this->conditions = $conditions ?? collect();
    }

    public function get(): Collection
    {
        return $this->conditions;
    }

    public function add(Condition $condition): self
    {
        $this
            ->conditions
            ->push($condition);

        return $this;
    }

    public function only(array|ConditionLevel $level): self
    {
        if ($level instanceof ConditionLevel) {
            $level = [$level];
        }

        $conditions = $this
            ->conditions
            ->filter(fn (Condition $condition) => in_array($condition->level, $level, true));

        return new self($conditions);
    }

    public function except(array|ConditionLevel $level): self
    {
        if ($level instanceof ConditionLevel) {
            $level = [$level];
        }

        $conditions = $this
            ->conditions
            ->filter(fn (Condition $condition) => ! in_array($condition->level, $level, true));

        return new self($conditions);
    }

    public function isFulfilled(): bool
    {
        if ($this->conditions->isEmpty()) {
            return true;
        }

        return $this
            ->getNotFulfilled()
            ->isEmpty();
    }

    public function isNotFulfilled(): bool
    {
        if ($this->conditions->isEmpty()) {
            return false;
        }

        return $this
            ->getNotFulfilled()
            ->isNotEmpty();
    }

    public function getFulfilled(): Collection
    {
        return $this
            ->conditions
            ->filter(fn (Condition $condition) => $condition->fulfilled);
    }

    public function getNotFulfilled(): Collection
    {
        return $this
            ->conditions
            ->filter(fn (Condition $condition) => ! $condition->fulfilled);
    }
}
