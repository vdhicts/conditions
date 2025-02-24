<?php

namespace Vdhicts\Conditions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionTest extends TestCase
{
    public function test_initializing(): void
    {
        $condition = new Condition(
            name: 'test',
            fulfilled: true,
            level: ConditionLevel::Warning,
            message: 'test message',
            data: ['test' => 'data']
        );

        $this->assertSame('test', $condition->name);
        $this->assertTrue($condition->fulfilled);
        $this->assertSame(ConditionLevel::Warning, $condition->level);
        $this->assertSame('test message', $condition->message);
        $this->assertSame(['test' => 'data'], $condition->data);
    }

    public function test_initializing_fluent(): void
    {
        $condition = (new Condition('test'))
            ->setFulfilled(true)
            ->setLevel(ConditionLevel::Warning)
            ->setMessage('test message')
            ->setData(['test' => 'data']);

        $this->assertSame('test', $condition->name);
        $this->assertTrue($condition->fulfilled);
        $this->assertSame(ConditionLevel::Warning, $condition->level);
        $this->assertSame('test message', $condition->message);
        $this->assertSame(['test' => 'data'], $condition->data);
    }
}
