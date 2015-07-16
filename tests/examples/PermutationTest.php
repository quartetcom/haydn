<?php
namespace Haydn\Examples;

use Quartet\Haydn\SetInterface;

class PermutationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Permutation
     */
    private $SUT;

    /**
     * @test
     */
    public function testGenerate()
    {
        $input = [1,3,6];

        $result = $this->SUT->generate($input);

        $this->assertThat($result, $this->isInstanceOf(SetInterface::class));

        $expectedRecords = [
            [1,3,6],
            [1,6,3],
            [3,1,6],
            [3,6,1],
            [6,1,3],
            [6,3,1]
        ];

        $this->hasSameRecords($result, $expectedRecords);
    }

    /**
     * @test
     */
    public function testGenerateLarge()
    {
        $input = range(1,8);

        $result = $this->SUT->generate($input);

        $this->assertThat($result, $this->isInstanceOf(SetInterface::class));

        $count = 0;
        foreach ($result as $row) {
            $count++;
            //echo implode(' ', $row) . PHP_EOL;
        }

        $this->assertThat($count, $this->equalTo(8*7*6*5*4*3*2));
    }

    protected function setUp()
    {
        $this->SUT = new Permutation();
    }

    private function hasSameRecords($result, $expected)
    {
        $count = 0;
        $expectedFlat = array_map(function ($values) {
            return implode(' ', $values);
        }, $expected);

        foreach ($result as $row)
        {
            $count++;
            $flat = implode(' ', $row);
            $index = array_search($flat, $expectedFlat, true);
            if ($index !== false) {
                $this->assertTrue(true, $flat);
                unset($expectedFlat[$index]);
            } else {
                $this->fail($flat);
            }
        }

        $this->assertThat($count, $this->equalTo(count($expected)));
    }
}
