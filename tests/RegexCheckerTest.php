<?php

namespace BlockListCheck\Tests;

use BlockListCheck\Checkers\RegexChecker;
use BlockListCheck\Exceptions\BlockListCheckException;
use BlockListCheck\Tests\Fixtures\ClassWithBlocklistCheckValue;
use BlockListCheck\Tests\Fixtures\ClassWithGetAttribute;

class RegexCheckerTest extends TestCase
{

    /**
     * @var RegexChecker
     */
    protected RegexChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checker = new RegexChecker();
    }

    /** @test */
    public function has_default_fields_and_patterns()
    {
        $this->assertEmpty($this->checker->getFields());
        $this->assertTrue(is_array($this->checker->getFields()));
        $this->assertEmpty($this->checker->getPatterns());
        $this->assertTrue(is_array($this->checker->getPatterns()));

        $checker = new RegexChecker(
            ['/.*uk$/', '/.*com$/'],
            ['email', 'my_filed', 'other_field'],
        );

        $this->assertCount(3, $checker->getFields());
        $this->assertCount(2, $checker->getPatterns());
    }

    /** @test */
    public function can_update_fields_and_patterns()
    {
        $this->assertEquals($this->checker, $this->checker->setFields(['email', 'my_filed', 'other_field']));
        $this->assertEquals($this->checker, $this->checker->setPatterns(['/.*uk$/', '/.*com$/']));

        $this->assertCount(3, $this->checker->getFields());
        $this->assertCount(2, $this->checker->getPatterns());

        $this->assertEquals($this->checker, $this->checker->addField('foo_bar'));
        $this->assertEquals($this->checker, $this->checker->addPattern('/.*ua$/'));

        $this->assertCount(4, $this->checker->getFields());
        $this->assertCount(3, $this->checker->getPatterns());
    }

    /** @test */
    public function only_unique_fields_and_patterns()
    {
        $this->assertEquals($this->checker, $this->checker->setFields(['my_filed', 'email', 'my_filed', 'my_filed']));
        $this->assertEquals($this->checker, $this->checker->setPatterns(['/.*uk$/', '/.*uk$/','/.*uk$/']));

        $this->assertCount(2, $this->checker->getFields());
        $this->assertCount(1, $this->checker->getPatterns());

        $this->assertEquals($this->checker, $this->checker->addField('my_filed'));
        $this->assertEquals($this->checker, $this->checker->addPattern('/.*uk$/'));

        $this->assertCount(2, $this->checker->getFields());
        $this->assertCount(1, $this->checker->getPatterns());

        $this->assertTrue(in_array('my_filed', $this->checker->getFields()));
        $this->assertTrue(in_array('email', $this->checker->getFields()));
        $this->assertTrue(in_array('/.*uk$/', $this->checker->getPatterns()));
    }

    /** @test */
    public function check_array()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $this->assertTrue($this->checker->check(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar']));
        $this->assertFalse($this->checker->check(['email' => 'value_one', 'my_filed' => 'value_2.uk', 'foo' => 'bar']));
        $this->assertFalse($this->checker->check(['email' => 'value_one.uk', 'my_filed' => 'value_2', 'foo' => 'bar']));
        $this->assertFalse($this->checker->check(['email' => 'value_one.uk', 'my_filed' => 'value_2.uk', 'foo' => 'bar']));
        $this->assertTrue($this->checker->check(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar.uk']));
    }

    /** @test */
    public function check_array_exception_if_not_found()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $this->expectException(BlockListCheckException::class);
        $this->checker->check(['email' => 'value_one', 'foo' => 'bar']);
    }

    /** @test */
    public function check_object()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $obj           = new \stdClass();
        $obj->email    = 'value_one';
        $obj->my_filed = 'value_2';
        $obj->foo      = 'bar';
        $this->assertTrue($this->checker->check($obj));
        $obj->email    = 'value_one';
        $obj->my_filed = 'value_2.uk';
        $obj->foo      = 'bar';
        $this->assertFalse($this->checker->check($obj));
        $obj->email    = 'value_one.uk';
        $obj->my_filed = 'value_2';
        $obj->foo      = 'bar';
        $this->assertFalse($this->checker->check($obj));
        $obj->email    = 'value_one.uk';
        $obj->my_filed = 'value_2.uk';
        $obj->foo      = 'bar';
        $this->assertFalse($this->checker->check($obj));
        $obj->email    = 'value_one';
        $obj->my_filed = 'value_2';
        $obj->foo      = 'bar.uk';
        $this->assertTrue($this->checker->check($obj));
    }

    /** @test */
    public function check_object_exception_if_not_found()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $this->expectException(BlockListCheckException::class);

        $obj        = new \stdClass();
        $obj->email = 'value_one';
        $obj->foo   = 'bar';
        $this->checker->check($obj);
    }

    /** @test */
    public function check_object_with_blocklist_check_value()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $obj = new ClassWithBlocklistCheckValue();
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar']);
        $this->assertTrue($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2.uk', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one.uk', 'my_filed' => 'value_2', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one.uk', 'my_filed' => 'value_2.uk', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar.uk']);
        $this->assertTrue($this->checker->check($obj));
    }

    /** @test */
    public function check_object_with_blocklist_check_value_exception()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $this->expectException(\InvalidArgumentException::class);

        $obj = new ClassWithBlocklistCheckValue();
        $obj->setValue(['email' => 'value_one', 'foo' => 'bar.uk']);
        $this->checker->check($obj);
    }

    /** @test */
    public function check_object_with_get_attribute()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $obj = new ClassWithGetAttribute();
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar']);
        $this->assertTrue($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2.uk', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one.uk', 'my_filed' => 'value_2', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one.uk', 'my_filed' => 'value_2.uk', 'foo' => 'bar']);
        $this->assertFalse($this->checker->check($obj));
        $obj->setValue(['email' => 'value_one', 'my_filed' => 'value_2', 'foo' => 'bar.uk']);
        $this->assertTrue($this->checker->check($obj));
    }

    /** @test */
    public function check_object_with_get_attribute_exception()
    {
        $this->checker
            ->setFields(['email', 'my_filed'])
            ->setPatterns(['/.*\.uk$/']);

        $this->expectException(\InvalidArgumentException::class);

        $obj = new ClassWithGetAttribute();
        $obj->setValue(['email' => 'value_one', 'foo' => 'bar.uk']);
        $this->checker->check($obj);
    }
}
