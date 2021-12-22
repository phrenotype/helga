<?php

use PHPUnit\Framework\TestCase;

use function Chase\Validator\validate;

class CustomMessagesTest extends TestCase
{
    protected function setUp(): void
    {
        $this->customMessage = "This is my custom message";
    }

    protected function tearDown(): void
    {
        $this->customMessage = null;
    }

    public function testMessage()
    {
        $v1 = validate(5)->withRules(["min:7:$this->customMessage"]);
        $v2 = validate(5)->withRules(["alpha::$this->customMessage"]);

        $this->assertEquals($v1->errors()[0], $this->customMessage);
        $this->assertEquals($v2->errors()[0], $this->customMessage);
    }
}
