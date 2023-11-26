<?php

namespace Vdhicts\Conditions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\ConditionTransformer;
use Vdhicts\Conditions\Enums\ConditionLevel;

class ConditionTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $condition = new Condition(
            name: 'test',
            fulfilled: true,
            level: ConditionLevel::Warning,
            message: 'test message',
            data: ['test' => 'data']
        );

        $array = ConditionTransformer::toArray($condition);

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('fulfilled', $array);
        $this->assertArrayHasKey('level', $array);
        $this->assertArrayHasKey('message', $array);
        $this->assertArrayHasKey('data', $array);
        $this->assertSame('test', $array['name']);
        $this->assertTrue($array['fulfilled']);
        $this->assertSame(ConditionLevel::Warning->value, $array['level']);
        $this->assertSame('test message', $array['message']);
        $this->assertSame(['test' => 'data'], $array['data']);
    }

    public function testFromArray(): void
    {
        $array = [
            'name' => 'test',
            'fulfilled' => true,
            'level' => ConditionLevel::Warning->value,
            'message' => 'test message',
            'data' => ['test' => 'data'],
        ];

        $condition = ConditionTransformer::fromArray($array);

        $this->assertInstanceOf(Condition::class, $condition);
        $this->assertSame('test', $condition->name);
        $this->assertTrue($condition->fulfilled);
        $this->assertSame(ConditionLevel::Warning, $condition->level);
        $this->assertSame('test message', $condition->message);
        $this->assertSame(['test' => 'data'], $condition->data);
    }
}
