<?php

namespace Shergela\Validations;


use Shergela\Validations\Enums\TestEnum;
use Shergela\Validations\Validation\Rule;
use Tests\TestCase;

class RuleInTest extends TestCase
{
    public function test_in_validation()
    {
        $rule = Rule::in(['Laravel', 'Framework', 'PHP'])->getRule();

        $this->assertSame('in:"Laravel","Framework","PHP"', (string) $rule);

        $rule = Rule::in(collect(['Taylor', 'Michael', 'Tim']))->getRule();

        $this->assertSame('in:"Taylor","Michael","Tim"', (string) $rule);

        $rule = Rule::in(['Life, the Universe and Everything', 'this is a "quote"'])->getRule();

        $this->assertSame('in:"Life, the Universe and Everything","this is a ""quote"""', (string) $rule);

        $rule = Rule::in(collect([1, 2, 3, 4]))->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in(collect([1, 2, 3, 4]))->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in(["a,b\nc,d"])->getRule();

        $this->assertSame("in:\"a,b\nc,d\"", (string) $rule);

        $rule = Rule::in([1, 2, 3, 4])->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in(collect([1, 2, 3, 4]))->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in('1', '2', '3', '4')->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in('1', '2', '3', '4')->getRule();

        $this->assertSame('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in([TestEnum::ONE])->getRule();

        $this->assertSame('in:"1"', (string) $rule);

        $rule = Rule::in([TestEnum::TWO])->getRule();

        $this->assertSame('in:"2"', (string) $rule);

        $rule = Rule::in([TestEnum::STRING_ONE])->getRule();

        $this->assertSame('in:"one"', (string) $rule);
    }
}
