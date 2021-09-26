<?php

namespace BlockListCheck\Tests;

use BlockListCheck\BlocklistProcessor;
use BlockListCheck\Checkers\RegexChecker;

class BlocklistProcessorTest extends TestCase
{

    /** @test */
    public function has_getters_setters()
    {
        $processor = new BlocklistProcessor();

        $this->assertEmpty($processor->getCheckers());
        $this->assertTrue(is_array($processor->getCheckers()));

        $processor = new BlocklistProcessor([
            $checker = new RegexChecker(
                [ '/.*uk$/', '/.*com$/' ],
                [ 'email', 'my_filed', 'other_field' ],
            ),
        ]);

        $this->assertCount(1, $processor->getCheckers());
        $this->assertEquals($checker, $processor->getCheckers()[0]);

        $processor->setCheckers([
            $checker2 = new RegexChecker(
                [ '/.*uk$/', '/.*com$/' ],
                [ 'email', 'my_filed', 'other_field' ],
            ),
        ]);

        $this->assertCount(1, $processor->getCheckers());
        $this->assertEquals($checker2, $processor->getCheckers()[0]);

        $processor->addChecker($checker);

        $this->assertCount(2, $processor->getCheckers());
        $this->assertEquals($checker2, $processor->getCheckers()[0]);
        $this->assertEquals($checker, $processor->getCheckers()[1]);
    }

    /** @test */
    public function correct_passed()
    {
        $processor = new BlocklistProcessor([
            $checker = new RegexChecker(
                [ '/.*uk$/', '/.*com$/' ],
                [ 'email', 'my_filed' ],
            ),
        ]);

        $this->assertTrue($processor->passed([ 'email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar' ]));
        $this->assertFalse($processor->passed([ 'email' => 'value_one', 'my_filed' => 'value_2uk', 'foo' => 'bar' ]));
        $this->assertFalse($processor->passed([ 'email' => 'value_one', 'my_filed' => 'value_2com', 'foo' => 'bar' ]));
        $this->assertTrue($processor->passed([ 'email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'barcom' ]));
        $this->assertFalse($processor->passed([ 'email' => 'uk', 'my_filed' => 'com', 'foo' => 'barcom' ]));
    }

    /** @test */
    public function invalid_argument_exception()
    {
        $processor = new BlocklistProcessor([
            new \stdClass(),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $processor->passed([ 'email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar' ]);
    }
}
