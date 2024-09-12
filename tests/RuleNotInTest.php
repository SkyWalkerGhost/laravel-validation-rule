<?php

namespace Shergela\Validations;


use Exception;
use Shergela\Validations\Enums\TestEnum;
use Shergela\Validations\Validation\Rule;
use Tests\TestCase;

class RuleNotInTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_not_in_validation()
    {
        $rule = Rule::notIn(['Caldera', 'Aramis', 'Athos'])->getRule(1);

        $this->assertSame('not_in:"Caldera","Aramis","Athos"', (string) $rule);

        $rule = Rule::notIn(collect(['Taylor', 'Michael', 'Tim']))->getRule(1);

        $this->assertSame('not_in:"Taylor","Michael","Tim"', (string) $rule);

        $rule = Rule::notIn(['Life, the Universe and Everything', 'this is a "quote"'])->getRule(1);

        $this->assertSame('not_in:"Life, the Universe and Everything","this is a ""quote"""', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]))->getRule(1);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]))->getRule(1);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(["a,b\nc,d"])->getRule(1);

        $this->assertSame("not_in:\"a,b\nc,d\"", (string) $rule);

        $rule = Rule::notIn([1, 2, 3, 4])->getRule(1);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]))->getRule(1);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(['1', '2', '3', '4'])->getRule(1);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn([TestEnum::ONE])->getRule(1);

        $this->assertSame('not_in:"1"', (string) $rule);

        $rule = Rule::notIn([TestEnum::TWO])->getRule(1);

        $this->assertSame('not_in:"2"', (string) $rule);

        $rule = Rule::notIn([TestEnum::STRING_ONE])->getRule(1);

        $this->assertSame('not_in:"one"', (string) $rule);
    }
}
