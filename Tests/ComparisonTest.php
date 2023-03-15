<?php

use PHPUnit\Framework\TestCase;

use function Helga\validate;

class ComparisonTest extends TestCase
{

    protected function setUp(): void
    {        
    }

    protected function tearDown(): void
    {
    }

    public function testEq(){        
        $v1 = validate(5)->withRules(['eq:5']);
        $v2 = validate(5)->withRules(['eq:6']);
        $this->assertEquals($v1->passes(), true);        
        $this->assertNotEquals($v2->passes(), true);
    }

    
    public function testNeq(){
        $v1 = validate(5)->withRules(['neq:5']);
        $v2 = validate(5)->withRules(['neq:6']);
        $this->assertNotEquals($v1->passes(), true);        
        $this->assertEquals($v2->passes(), true);
    }

    
    public function testLt(){
        $v1 = validate(5)->withRules(['lt:5']);
        $v2 = validate(5)->withRules(['lt:6']);
        $v3 = validate(5)->withRules(['lt:4']);
        $this->assertEquals($v1->passes(), false);        
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
    }

    
    public function testLte(){
        $v1 = validate(5)->withRules(['lte:5']);
        $v2 = validate(5)->withRules(['lte:6']);
        $v3 = validate(5)->withRules(['lte:4']);
        $this->assertEquals($v1->passes(), true);        
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
    }

    
    public function testGt(){
        $v1 = validate(5)->withRules(['gt:5']);
        $v2 = validate(5)->withRules(['gt:6']);
        $v3 = validate(5)->withRules(['gt:4']);
        $this->assertEquals($v1->passes(), false);        
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }
    
    
    public function testGte(){
        $v1 = validate(5)->withRules(['gte:5']);
        $v2 = validate(5)->withRules(['gte:6']);
        $v3 = validate(5)->withRules(['gte:4']);
        $this->assertEquals($v1->passes(), true);        
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }
    
}