<?php
namespace Quartet\Haydn\Matcher;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataForTestNormalMatcher
     */
    public function testNormalMatcher($row, $expected)
    {
        $matcher = new Matcher(['name' => 'foo']);
        $result = $matcher->match($row);
        $this->assertThat($result, $this->equalTo($expected));
    }

    public function dataForTestNormalMatcher()
    {
        return [
            'match' => [['name' => 'foo'], true],
            'not match' => [['name' => 'bar'], false],
        ];
    }

    /**
     * @test
     * @dataProvider dataForTestClosureMatcher
     */
    public function testClosureMatcher($row, $expected)
    {
        $matcher = new Matcher(['name' => function ($value, $calledRow) use ($row) {
            $this->assertThat($calledRow, $this->equalTo($row));
            return $value !== 'foo';
        }]);
        $result = $matcher->match($row);
        $this->assertThat($result, $this->equalTo($expected));
    }

    public function dataForTestClosureMatcher()
    {
        return [
            'not match' => [['name' => 'foo'], false],
            'match' => [['name' => 'bar'], true],
        ];
    }

}
