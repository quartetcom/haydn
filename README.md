# Haydn

[![Circle CI](https://circleci.com/gh/quartetcom/haydn/tree/master.svg?style=shield&circle-token=7fa1285eed7256aab2cad085d139dd4a9b26f0ff)](https://circleci.com/gh/quartetcom/haydn/tree/master)

配列に対してかけ算や列演算を宣言的に指定できるライブラリ。次のような事ができる。

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

$fruitSet = new Set(new ArraySource($fruits), 'fruit');
$drinkSet = new Set(new ArraySource($drinks), 'drink');

$fruitDrinkSet = $fruitSet->product($drinkSet);

// fruit.name: Apple,  fruit.price: 100, drink.name: Yoghurt, drink.price: 230
// fruit.name: Apple,  fruit.price: 100, drink.name: Soda,    drink.price: 120
// fruit.name: Apple,  fruit.price: 100, drink.name: Spirit,  drink.price: 160
// fruit.name: Banana, fruit.price:  80, drink.name: Yoghurt, drink.price: 230
// fruit.name: Banana, fruit.price:  80, drink.name: Soda,    drink.price: 120
// fruit.name: Banana, fruit.price:  80, drink.name: Spirit,  drink.price: 160
```
