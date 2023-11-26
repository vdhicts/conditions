<?php

namespace Vdhicts\Conditions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\ConditionCollection;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionCollectionTest extends TestCase
{
    public function testInitialization(): void
    {
        $conditionCollection = new ConditionCollection();

        $this->assertTrue($conditionCollection->isFulfilled());
        $this->assertFalse($conditionCollection->isNotFulfilled());
    }

    public function testWithConditionsFulfilled(): void
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

    public function testWithConditionsNotFulfilled(): void
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

    public function testAddingConditions(): void
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

    public function testOnly(): void
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

    public function testExcept(): void
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
}
