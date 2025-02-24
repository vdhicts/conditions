<?php

namespace Vdhicts\Conditions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\ConditionCollection;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionCollectionTest extends TestCase
{
    public function test_initialization(): void
    {
        $conditionCollection = new ConditionCollection();

        $this->assertTrue($conditionCollection->isFulfilled());
        $this->assertFalse($conditionCollection->isNotFulfilled());
    }

    public function test_with_conditions_fulfilled(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', true),
            new Condition('test2', true),
        ]));

        $this->assertTrue($conditionCollection->isFulfilled());
        $this->assertFalse($conditionCollection->isNotFulfilled());
        $this->assertCount(2, $conditionCollection->get());
        $this->assertCount(2, $conditionCollection->getFulfilled());
        $this->assertCount(0, $conditionCollection->getNotFulfilled());
    }

    public function test_with_conditions_not_fulfilled(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', true),
            new Condition('test2', false),
        ]));

        $this->assertFalse($conditionCollection->isFulfilled());
        $this->assertTrue($conditionCollection->isNotFulfilled());
        $this->assertCount(2, $conditionCollection->get());
        $this->assertCount(1, $conditionCollection->getFulfilled());
        $this->assertCount(1, $conditionCollection->getNotFulfilled());
    }

    public function test_adding_conditions(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', true),
            new Condition('test2', true),
        ]));

        $this->assertTrue($conditionCollection->isFulfilled());
        $this->assertFalse($conditionCollection->isNotFulfilled());

        $conditionCollection->add(new Condition('test3', false));

        $this->assertFalse($conditionCollection->isFulfilled());
        $this->assertTrue($conditionCollection->isNotFulfilled());
        $this->assertCount(3, $conditionCollection->get());
        $this->assertCount(2, $conditionCollection->getFulfilled());
        $this->assertCount(1, $conditionCollection->getNotFulfilled());
    }

    public function test_only(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', false, ConditionLevel::Info),
            new Condition('test1', true, ConditionLevel::Warning),
            new Condition('test2', true, ConditionLevel::Error),
        ]));

        $this->assertFalse($conditionCollection->isFulfilled());
        $this->assertTrue($conditionCollection->isNotFulfilled());

        $infoConditionCollection = $conditionCollection->only(ConditionLevel::Info);

        $this->assertFalse($infoConditionCollection->isFulfilled());
        $this->assertTrue($infoConditionCollection->isNotFulfilled());
        $this->assertCount(1, $infoConditionCollection->get());

        $otherConditionCollection = $conditionCollection->only([ConditionLevel::Error, ConditionLevel::Warning]);

        $this->assertTrue($otherConditionCollection->isFulfilled());
        $this->assertFalse($otherConditionCollection->isNotFulfilled());
        $this->assertCount(2, $otherConditionCollection->get());
    }

    public function test_except(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', false, ConditionLevel::Info),
            new Condition('test1', true, ConditionLevel::Warning),
            new Condition('test2', true, ConditionLevel::Error),
        ]));

        $this->assertFalse($conditionCollection->isFulfilled());
        $this->assertTrue($conditionCollection->isNotFulfilled());

        $infoConditionCollection = $conditionCollection->except([ConditionLevel::Error, ConditionLevel::Warning]);

        $this->assertFalse($infoConditionCollection->isFulfilled());
        $this->assertTrue($infoConditionCollection->isNotFulfilled());
        $this->assertCount(1, $infoConditionCollection->get());

        $otherConditionCollection = $conditionCollection->except(ConditionLevel::Info);

        $this->assertTrue($otherConditionCollection->isFulfilled());
        $this->assertFalse($otherConditionCollection->isNotFulfilled());
        $this->assertCount(2, $otherConditionCollection->get());
    }

    public function test_merge(): void
    {
        $conditionCollection = new ConditionCollection(collect([
            new Condition('test1', true, ConditionLevel::Info),
        ]));
        $otherConditionCollection = new ConditionCollection(collect([
            new Condition('test3', false, ConditionLevel::Info),
            new Condition('test4', true, ConditionLevel::Warning),
            new Condition('test5', true, ConditionLevel::Error),
        ]));

        $mergedConditionCollection = $conditionCollection->merge($otherConditionCollection);

        $this->assertFalse($mergedConditionCollection->isFulfilled());
        $this->assertCount(4, $mergedConditionCollection->get());
        $this->assertCount(1, $conditionCollection->get());
        $this->assertCount(3, $otherConditionCollection->get());
    }
}
