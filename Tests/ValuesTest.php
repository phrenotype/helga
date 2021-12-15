<?php

use PHPUnit\Framework\TestCase;

use function Chase\validate;

class ValuesTest extends TestCase
{

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }


    public function testMin()
    {
        $v1 = validate(5)->withRules(['min:5']);
        $v2 = validate(5)->withRules(['min:4']);
        $v3 = validate(5)->withRules(['min:6']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
    }

    public function testMax()
    {
        $v1 = validate(5)->withRules(['max:5']);
        $v2 = validate(5)->withRules(['max:4']);
        $v3 = validate(5)->withRules(['max:6']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }

    public function testRange()
    {
        $v1 = validate(5)->withRules(['range:5-10']);
        $v2 = validate(5)->withRules(['range:1-5']);
        $v3 = validate(3)->withRules(['range:5-10']);
        $v4 = validate(5)->withRules(['range:1-4']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), false);
    }

    public function testMinLen()
    {
        $v1 = validate("chase")->withRules(['minLen:5']);
        $v2 = validate("chase")->withRules(['minLen:6']);
        $v3 = validate("chase")->withRules(['minLen:4']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }

    public function testMaxLen()
    {
        $v1 = validate("chase")->withRules(['maxLen:5']);
        $v2 = validate("chase")->withRules(['maxLen:6']);
        $v3 = validate("chase")->withRules(['maxLen:4']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
    }

    public function testRangeLen()
    {
        $v1 = validate("chase")->withRules(['rangeLen:5-10']);
        $v2 = validate("chase")->withRules(['rangeLen:1-5']);
        $v3 = validate("cha")->withRules(['rangeLen:5-10']);
        $v4 = validate("chase")->withRules(['rangeLen:1-4']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), false);
    }

    public function testLen()
    {
        $v1 = validate("chase")->withRules(['len:5']);
        $v2 = validate("chase")->withRules(['len:6']);
        $v3 = validate("chase")->withRules(['len:4']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), false);
    }

    public function testRegex()
    {
        $v1 = validate("chase")->withRules(['regex:/^chase$/']);
        $v2 = validate("CHASE")->withRules(['regex:/^chase$/']);
        $v3 = validate("CHASE")->withRules(['regex:/^chase$/i']);
        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }

    public function testIn()
    {
        $v1 = validate("chase")->withRules(['in:james,hadley,chase']);
        $v2 = validate("chase")->withRules(['in:james,hadley,peter']);
        $v3 = validate(7)->withRules(['in:7,6,4']);
        $v4 = validate(7)->withRules(['in:0,6,4']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
        $this->assertEquals($v4->passes(), false);
    }

    public function testInteger()
    {
        $v1 = validate("12345")->withRules(['integer']);
        $v2 = validate("0abc")->withRules(['integer']);
        $v3 = validate(744433)->withRules(['integer']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }

    public function testAlnum()
    {
        $v1 = validate("12345")->withRules(['alnum']);
        $v2 = validate("abcd")->withRules(['alnum']);
        $v3 = validate("abcd1234")->withRules(['alnum']);
        $v4 = validate("'abcd")->withRules(['alnum']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), true);
        $this->assertEquals($v4->passes(), false);
    }

    public function testAlpha()
    {
        $v1 = validate("12345")->withRules(['alpha']);
        $v2 = validate("abcd")->withRules(['alpha']);
        $v3 = validate("abcd1234")->withRules(['alpha']);
        $v4 = validate("'abcd")->withRules(['alpha']);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), false);
    }

    public function float()
    {
        $v1 = validate(.6)->withRules(['float']);
        $v2 = validate(3.4)->withRules(['float']);
        $v3 = validate(12)->withRules(['alnum']);
        $v4 = validate(12.0)->withRules(['alnum']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), true);
    }


    public function email()
    {
        $v1 = validate("chase@example.com")->withRules(['email']);
        $v2 = validate("chase")->withRules(['email']);
        $v3 = validate("chase@example")->withRules(['email']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), false);
    }

    public function url()
    {
        $v1 = validate("chase.com")->withRules(['url']);
        $v2 = validate("http://chase.com")->withRules(['url']);
        $v3 = validate("fakescheme://chase.com")->withRules(['url']);
        $v4 = validate("something.chase.com")->withRules(['url']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), true);
    }

    public function testRequired()
    {
        $v1 = validate('')->withRules(['required']);
        $v2 = validate(null)->withRules(['required']);
        $v3 = validate(false)->withRules(['required']);
        $v4 = validate(0)->withRules(['required']);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), true);
    }

    public function testUnique()
    {
        $v1 = validate(5)->unique(function ($subject) {
            return false;
        });
        $v2 = validate(5)->unique(function ($subject) {
            return true;
        });

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
    }

    public function testExists()
    {
        $v1 = validate(5)->exists(function ($subject) {
            return false;
        });
        $v2 = validate(5)->exists(function ($subject) {
            return true;
        });

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), true);
    }

    public function testCheck()
    {
        $v1 = validate(5)->check(function () {
            return false;
        });
        $v2 = validate(5)->check(function () {
            return true;
        });

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), true);
    }
}
