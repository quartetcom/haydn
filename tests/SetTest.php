<?php
namespace Quartet\Haydn;

use Quartet\Common\CsvUtil\Csv;
use Quartet\Haydn\IO\Source\ArraySource;
use Quartet\Haydn\IO\Source\CsvSource;

class SetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function 配列ソース()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250,300],
            ["お","か", 50,3000],
        ];

        $aSource = new ArraySource($data);

        $set = new Set($aSource, 'array');

        $this->assertThat($set, $this->isInstanceOf(Set::class));

        $result = [];
        foreach ($set as $record) {
            $result[] = $record;
        }

        $this->assertThat($result[0]['array.0'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.2'], $this->equalTo(150));
        $this->assertThat($result[1]['array.0'], $this->equalTo('う'));
        $this->assertThat($result[1]['array.1'], $this->equalTo('え'));
        $this->assertThat($result[2]['array.0'], $this->equalTo('お'));
        $this->assertThat($result[2]['array.3'], $this->equalTo(3000));
    }

    /**
     * @test
     */
    public function CSVソース()
    {
        $aSource = new CsvSource(new Csv(__DIR__.'/fixtures/google_ad_group_report.csv'));

        $set = new Set($aSource, 'gad_group_report');

        $this->assertThat($set, $this->isInstanceOf(Set::class));

        $result = [];
        foreach ($set as $record) {
            $result[] = $record;
        }

        $this->assertThat($result[0]['gad_group_report.0'], $this->equalTo('google_adgroup (2013/06/01-2013/06/30)'));
        $this->assertThat($result[1]['gad_group_report.0'], $this->equalTo('設定'));
        $this->assertThat($result[1]['gad_group_report.1'], $this->equalTo('広告グループ'));
        $this->assertThat($result[2]['gad_group_report.0'], $this->equalTo('一時停止'));
        $this->assertThat($result[1177]['gad_group_report.0'], $this->equalTo('合計'));
        $this->assertThat($result[1177]['gad_group_report.7'], $this->equalTo('269659'));
    }

    /**
     * @test
     */
    public function testSet()
    {
        $fruits = [
            ['name' => 'Apple',  'price' => 100],
            ['name' => 'Banana', 'price' =>  80],
        ];

        $drinks = [
            ['name' => 'Yoghurt', 'price' => 200],
            ['name' => 'Soda',    'price' => 120],
            ['name' => 'Spirit',  'price' => 160],
        ];

        $fruitSet = new Set(new ArraySource($fruits), 'fruit');
        $drinkSet = new Set(new ArraySource($drinks), 'drink');

        $fruitDrinkSet = $fruitSet->product($drinkSet);
        $count = 0;
        foreach ($fruitDrinkSet as $fruitDrink) {
            $count++;
        }
        $this->assertThat($count, $this->equalTo(6));


        $fruitDrinkMenu = $fruitDrinkSet->select([function($r){
            return [
                'item' => $r['fruit.name'] . ' ' . $r['drink.name'],
                'price' => $r['fruit.price'] + $r['drink.price']
            ];
        }]);

        $count = 0;
        foreach ($fruitDrinkMenu as $fruitDrink) {
            $count++;
        }
        $this->assertThat($count, $this->equalTo(6));

        $fruitDrinkMenuWithRev = $fruitDrinkSet->select([function($r){
                return [
                    'item' => $r['fruit.name'] . ' ' . $r['drink.name'],
                    'price' => $r['fruit.price'] + $r['drink.price']
                ];},function($r){
                return [
                    'item' => $r['drink.name'] . ' ' . $r['fruit.name'],
                    'price' => $r['fruit.price'] + $r['drink.price']
                ];}]
        );

        $count = 0;
        foreach ($fruitDrinkMenuWithRev as $fruitDrink) {
            $count++;
        }
        $this->assertThat($count, $this->equalTo(12));
    }

    /**
     * @test
     * @group performance
     */
    public function testLarger()
    {
        $setA = new Set(new ArraySource($this->numarray(1000)), 'a');
        $setB = new Set(new ArraySource($this->strarray(100)), 'b');

        $abSet = $setA->product($setB);

        $count = 0;
        foreach ($abSet as $ab) {
            $count++;
        }
        $this->assertThat($count, $this->equalTo(100000));
    }

    private function numarray($size)
    {
        $a = [];
        for ($i = 0; $i < $size; $i++)
        {
            $a[] = ['num' => rand(0, 10000)];
        }

        return $a;
    }

    private function strarray($size)
    {
        $a = [];
        for ($i = 0; $i < $size; $i++)
        {
            $a[] = ['char' => array_rand(range('a', 'z'))];
        }

        return $a;
    }

    /**
     * @test
     * @dataProvider 行生成データ
     */
    public function 行生成($data, $expected)
    {
        $set = new Set(new ArraySource($data), 'test');
        $result = [];
        foreach ($set as $row) {
            $result[] = $row;
        }

        $this->assertEquals($result, $expected);
    }

    public function 行生成データ()
    {
        return [
            '単純配列' => [
                [1, 2, 3, 4], [['test'=>1], ['test'=>2], ['test'=>3], ['test'=>4]]
            ],
            '連想配列' => [
                [['name'=>'a'], ['name'=>'b'], ['name'=>'c'], ['name'=>'d']],
                [['test.name'=>'a'], ['test.name'=>'b'], ['test.name'=>'c'], ['test.name'=>'d']]
            ]
        ];
    }
}
