# Haydn

[![Circle CI](https://circleci.com/gh/quartetcom/haydn/tree/master.svg?style=shield&circle-token=7fa1285eed7256aab2cad085d139dd4a9b26f0ff)](https://circleci.com/gh/quartetcom/haydn/tree/master)

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

グルーピング演算したSetを返す。ここでグルーピング演算とは、グループのキーとなる集合Aと集合の各要素aに紐付く集合Bがある時、次のものを指す。

1. キー集合AのSet
2. a ごとのヘッダー行セレクタ
3. a ごとの明細行のSet（aに紐付くBのSet）
4. a ごとのフッター行セレクタ

1の要素ごとに、2、3、4への展開を行う。
この演算をA全体に適用した結果を持つSetが返される。

```php
$k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく', 'さしす']));
$k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def']));

$g1 = new Set\GroupingSet($k1->product($k2),
        // Header Selector
        function ($r) { return ['type' => 'header', 'name' => $r['k1'] . '-' . $r['k2']]; },
        // Detail Set
        function ($r) use ($k3) {
            $set = new Set(new SingleRowSource('k1k2', $r));
            $resultSet = $set->product($k3)->select([function ($r) {
                return [
                    'type' => 'キーワード',
                    'keyword' => $r['k1'] . ' ' . $r['k2'] . ' ' . $r['k3'],
                ];
            }]);
            return $resultSet;
        },
        // Footer Selector
        null
    );
    
$all = $g1->toArray();
var_dump($all);
```
