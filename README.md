# Haydn

[![Build Status](https://travis-ci.org/quartetcom/haydn.svg?branch=master)](https://travis-ci.org/quartetcom/haydn)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/quartetcom/haydn/badges/quality-score.png?b=master&s=5c21dd26d116f4a475b37189eb62e382dfd9c5c8)](https://scrutinizer-ci.com/g/quartetcom/haydn/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/quartetcom/haydn/badges/coverage.png?b=master&s=fd7af523e540354bbc7006be628f07c0c251be20)](https://scrutinizer-ci.com/g/quartetcom/haydn/?branch=master)
[![Total Downloads](https://poser.pugx.org/quartet/haydn/downloads.png)](https://packagist.org/packages/quartet/haydn)
[![Latest Stable Version](https://poser.pugx.org/quartet/haydn/v/stable.png)](https://packagist.org/packages/quartet/haydn)
[![Latest Unstable Version](https://poser.pugx.org/quartet/haydn/v/unstable.png)](https://packagist.org/packages/quartet/haydn)


<div style="float: right;">
<img src="https://cloud.githubusercontent.com/assets/89830/7813142/4a65559a-03f3-11e5-8087-4cd0d7e114d2.jpg">
</div>

配列に対してかけ算や列演算を宣言的に指定できるライブラリ。
実装の特徴として、Setに対する各種演算は単に宣言的に行われるのみで、Setオブジェクトを`foreach`等でイテレーションしないかぎり、中身の走査は行われない点。

```php
$fruits = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$drinks = [
    ['name' => 'Yoghurt', 'price' => 200],
    ['name' => 'Soda',    'price' => 120],
    ['name' => 'Spirit',  'price' => 160],
];

$fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
$drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

$fruitDrinkSet = $fruitSet->product($drinkSet);
$result = $fruitDrinkSet->toArray();
```

# Install

    composer require quartet/haydn

# Usage

## Set declaration (using source object)

```php
$fruitsAssoc = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$fruitsSet = new Set(new ArraySource('fruit', $fruitsAssoc, new HashKeyColumnMapper());

foreach ($fruitSet as $fruit) {
    echo 'name:' . $fruit['name'] . ' price:' . $fruit['price'] . PHP_EOL;
}
```

### Specific Sets

#### IdenticalSet

```php
$fruitsAssoc = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$fruitsSet = new Set(new ArraySource('fruit', $fruitsAssoc, new HashKeyColumnMapper());

$identicalSet = new IdenticalSet();

$all = $fruitSet->product($identicalSet);

// same as $fruitSet

$all = $identicalSet->product($fruitSet);

// same as $fruitSet

$all = $fruitSet->union($identicalSet);

// same as $fruitSet

$all = $identicalSet->union($fruitSet);

// same as $fruitSet
```

#### EmptySet

```php
$fruitsAssoc = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$fruitsSet = new Set(new ArraySource('fruit', $fruitsAssoc, new HashKeyColumnMapper());

$emptySet = new EmptySet();

$all = $fruitSet->product($emptySet);

// empty

$all = $emptySet->product($fruitSet);

// empty

$all = $fruitSet->union($emptySet);

// same as $fruitSet

$all = $emptySet->union($fruitSet);

// same as $fruitSet
```

## Set operations

### ProductSet

`Set#product(Set $target)`

2つのSetをかけたSetを作る。（デカルト積）

```php
$fruits = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$drinks = [
    ['name' => 'Yoghurt', 'price' => 200],
    ['name' => 'Soda',    'price' => 120],
    ['name' => 'Spirit',  'price' => 160],
];

$fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
$drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

$fruitDrinkSet = $fruitSet->product($drinkSet);
```

### SelectSet

`Set#select(array $selectors)`

Setの各要素に対して、何らかの加工を加えた結果のSet。

- 列の選択
- 列の値の演算
− 複数行への分割


```php
$fruits = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$fruitMenuSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()))
    ->select([function($row) {
        return [
            'menu item name' => $row['name']
        ];
    }]);
;

```

### FilterSet

`Set#filter(MatcherInterface $matcher)`

Setの要素に対して、マッチしたものだけを取り出したSet。

```php
$products = [
    ['name' => 'Apple',   'price' => 100, 'type' => 'fruit'],
    ['name' => 'Yoghurt', 'price' => 200, 'type' => 'drink'],
    ['name' => 'Soda',    'price' => 120, 'type' => 'drink'],
    ['name' => 'Banana',  'price' =>  80, 'type' => 'fruit'],
    ['name' => 'Spirit',  'price' => 160, 'type' => 'drink'],
];

$fruitSet = new Set(new ArraySource('product', $products, new HashKeyColumnMapper()))
    ->filter(new Matcher(['type' => 'fruit']));
;
```

Matcherは、対象列の名前をキーとして、完全一致する文字列を指定する。
また、完全一致の値ではなく、Closureを渡して動的に評価させることも可能。

```php
new Matcher(['type' => function($value) {
    return strpos($value, ':') !== false;
}])
```

### Devide

`Set#devide(array $matchers)`

Matcherを複数指定して、それぞれのMatcherに対応するSetへ分割する。

```php
$products = [
    ['name' => 'Apple',   'price' => 100, 'type' => 'fruit'],
    ['name' => 'Yoghurt', 'price' => 200, 'type' => 'drink'],
    ['name' => 'Soda',    'price' => 120, 'type' => 'drink'],
    ['name' => 'Banana',  'price' =>  80, 'type' => 'fruit'],
    ['name' => 'Spirit',  'price' => 160, 'type' => 'drink'],
];

$productSet = new Set(new ArraySource('product', $products, new HashKeyColumnMapper()));

list($fruitSet, $drinkSet) = $productSet->devide([
    'fruit' => new Matcher(['type' => 'fruit']),
    'drink' => new Matcher(['type' => 'drink']),
]);

```

### Union

`Set#union(Set $target)`

複数のSetを結合して、つながったSetを返す。

```php
$fruits = [
    ['name' => 'Apple',  'price' => 100],
    ['name' => 'Banana', 'price' =>  80],
];

$drinks = [
    ['name' => 'Yoghurt', 'price' => 200],
    ['name' => 'Soda',    'price' => 120],
    ['name' => 'Spirit',  'price' => 160],
];

$fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
$drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

$allSet = $fruitSet->union($drinkSet);
```

### Grouping

グルーピング演算したSetを返す。ここでグルーピング演算とは、キーとなる集合の各要素それぞれがさらに明細の集合を有するような、「キーの数だけグループを持つ集合」を作成することを指す。
具体的には以下のとおり。

* キー集合Aがあり、
* Aの各要素a1,a2,a3,...ぞれに対して明細集合B1,B2,B3,...を作成したい場合に、
* Aのある要素anを以下のような行に展開し、それをa1,a2,a3,...すべてに適用する
    * ヘッダー行（anグループの先頭を宣言する行）
    * 明細集合Bnの各行
    * フッター行（anグループの末尾を宣言する行）

```php
$k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく']));
$k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def']));
$k3 = new Set(new SingleColumnArraySource('k3', ['123', '456']));

$g1 = new Set\GroupingSet(
        // Key Set
        $k1->product($k2),
        // Header Generator
        function ($r) { return ['type' => 'header', 'name' => $r['k1'] . '-' . $r['k2']]; },
        // Detail Set Generator
        function ($r) use ($k3) {
            $set = new Set(new SingleRowSource('k1k2', $r));
            $resultSet = $set->product($k3)->select([function ($r) {
                return [
                    'type' => 'detail',
                    'content' => $r['k1'] . ' ' . $r['k2'] . ' ' . $r['k3'],
                ];
            }]);
            return $resultSet;
        },
        // Footer Generator
        null
    );
    
$all = $g1->toArray();
var_dump($all);

// [
//     'type' => 'header',
//     'name' => 'あいう-abc',
// ], [
//     'type' => 'detail',
//     'content' => 'あいう abc 123',
// ], [
//     'type' => 'detail',
//     'content' => 'あいう abc 456',
// ], [
//     'type' => 'header',
//     'name' => 'あいう-def',
// ], [
//     'type' => 'detail',
//     'content' => 'あいう def 123',
// ], [
//     'type' => 'detail',
//     'content' => 'あいう def 456',
// ], [
//     'type' => 'header',
//     'name' => 'かきく-abc',
// ], [
//     'type' => 'detail',
//     'content' => 'かきく abc 123',
// ], [
//     'type' => 'detail',
//     'content' => 'かきく abc 456',
// ], [
//     'type' => 'header',
//     'name' => 'かきく-def',
// ], [
//     'type' => 'detail',
//     'content' => 'かきく def 123',
// ], [
//     'type' => 'detail',
//     'content' => 'かきく def 456',
// ],
```

## Source

Setのデータ供給源（データソース）。

- ArraySource
- SingleColumnArraySource
- SingleRowSource

### ArraySource

PHPの配列（2次元）をデータソースとして利用する。

```php
$fruitArray = [
    ['id' => 1, 'name' => 'Apple'],
    ['id' => 2, 'name' => 'Banana'],
    ['id' => 3, 'name' => 'Lemon'],
];

$fruitSet = new Set(new ArraySource('fruit', $fruitArray, new HashKeyColumnMapper()));

$output = $fruitSet->toArray();
var_dump($output);

// ['id' => 1, 'name' => 'Apple'],
// ['id' => 2, 'name' => 'Banana'],
// ['id' => 3, 'name' => 'Lemon']
```

### SingleColumnArraySource

1列のみのPHP配列（1次元）をデータソースとして利用する。

```php
$fruitArray = [
  'Apple',
  'Banana',
  'Lemon'];

$fruitSet = new Set(new SingleColumnArraySource('fruit', $fruitArray, new NullColumnMapper()));

$output = $fruitSet->toArray();
var_dump($output);

// ['fruit' => 'Apple'],
// ['fruit' => 'Banana'],
// ['fruit' => 'Lemon'],
```

### SingleRowSource

1行のみのPHP配列（1次元）をデータソースとして利用する。

```php
$fruitArray = ['1','Apple','140'];

$fruitSet = new Set(new SingleRowSource('fruit', $fruitArray, new SimpleArrayColumnMapper([
    'id', 'name', 'price'
])));

$output = $fruitSet->toArray();
var_dump($output);

// ['id'=>1, 'name' => 'Apple', 'price'=>140],
```

## ColumnMapper

Sourceの列名マッピング

- HashKeyColumnMapper
- SimpleArrayColumnMapper
- NullColumnMapper
- ChainMapper

### HashKeyColumnMapper

各行が連想配列になっているデータソースで、連想配列のキー名をそのまま列名として使う。

```php
$fruitArray = [
    ['id' => 1, 'name' => 'Apple'],
    ['id' => 2, 'name' => 'Banana'],
    ['id' => 3, 'name' => 'Lemon'],
];

$fruitSet = new Set(new ArraySource('fruit', $fruitArray, new HashKeyColumnMapper()));

$output = $fruitSet->toArray();
var_dump($output);

// ['id' => 1, 'name' => 'Apple'],
// ['id' => 2, 'name' => 'Banana'],
// ['id' => 3, 'name' => 'Lemon']
```

### SimpleArrayColumnMapper

キーの無い配列データソースに、列名を配列で与える。

```php
$fruitColumn = ['id', 'name'];

$fruitArray = [
    [1, 'Apple'],
    [2, 'Banana'],
    [3, 'Lemon'],
];

$fruitSet = new Set(new ArraySource('fruit', $fruitArray, new SimpleArrayColumnMapper($fruitColumn)));

$output = $fruitSet->toArray();
var_dump($output);

// ['id' => 1, 'name' => 'Apple'],
// ['id' => 2, 'name' => 'Banana'],
// ['id' => 3, 'name' => 'Lemon']
```

### NullColumnMapper

列名マップを使わない。

```php
$fruitArray = [
    [1, 'Apple'],
    [2, 'Banana'],
    [3, 'Lemon'],
];

$fruitSet = new Set(new ArraySource('fruit', $fruitArray, new NullColumnMapper()));

$output = $fruitSet->toArray();
var_dump($output);

// [0 => 1, 1 => 'Apple'],
// [0 => 2, 1 => 'Banana'],
// [0 => 3, 1 => 'Lemon']
```

### ChainMapper

列名マッパーを複数チェインさせる。

# Support

If you find a bug or have a question, or want to request a feature, create an issue or pull request for it on [Issues](https://github.com/quartetcom/haydn/issues).

# Copyright

Copyright (c) 2015 GOTO Hidenori, All rights reserved.

# License

[The BSD 2-Clause License](http://opensource.org/licenses/BSD-2-Clause)
