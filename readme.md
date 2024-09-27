This lib allows you to make operations on arrays in functional style. It is like LINQ, but for PHP.

Designed for PHP 7.4 and higher.


# Installation
```bash
composer require mkrawczyk/funquery
```

# Example
````php
<?php
use MKrawczyk\FunQuery\FunQuery;
$data=[];//some array of objects
$result=FunQuery::create($data)->filter(fn($x) => $x->a > 5)->sort(fn($x) => $x->b)->map(fn($x) => $x->c)->slice(2,5)->toArray();
?>
````

Result can be used as iterator, or converted to Array
````php
<?php
$result=FunQuery::create($data)->filter(fn($x) => $x->a > 5)->sort(fn($x) => $x->b)->map(fn($x) => $x->c)->slice(2,5);
foreach($result as $x){

}
?>
````

Like LINQ, it uses lazy execution
````php
<?php

function generator(){
    for ($i = 0; ; $i++) {
        yield $i;
    }
}

$result=FunQuery::create(generator())->filter(fn($x)=>$x%2==0)->limit(10)->toArray();//it will 
````
# methods
## FunQuery::create(array<T>|IteratorAggregateNode<T>|IteratorNode<T>|FunQuery<T> $init)
Creates FunQuery object from array, generator or iterator.
```php
$obj1=FunQuery::create([1,2,3,4,5]);
$obj2=FunQuery::create(generator());
$obj3=FunQuery::create(new ArrayIterator([1,2,3,4,5]));
```

## filter(callable(T):bool $fun)
Executes function for every item and sends to output only, if function returns true
```php
FunQuery::create([1,2,3,4,5])->filter(fn($x) => $x > 2)->toArray() // [3,4,5]
```

## sort(...$funs)
Sorts items. If function is passed, it sorts by result of this function

```php
FunQuery::create([5,2,3,1,4])->sort()->toArray() // [1,2,3,4,5]
FunQuery::create([5,2,3,1,4])->sort(fn($x) => -$x)->toArray() // [5,4,3,2,1]
```
## map(callable(T):T2 $fun)
It keeps the same number of items, but each item is replaced by what was returned by passed function.
```php
FunQuery::create([1,2,3,4,5])->map(fn($x) => $x * 2)->toArray() // [2,4,6,8,10]
```

## first()
Returns first object. Throws exception if pipeline is empty.

```php
FunQuery::create([1,2,3,4,5])->first() // 1
```

## firstOrNull()
Returns first object or null if pipeline is empty.
    
    ```php
    FunQuery::create([1,2,3])->firstOrNull() // 1
    FunQuery::create([])->firstOrNull() // null
    ```
## skip(int $count = 0)
Skips first $count items.
```php
FunQuery::create([1,2,3,4,5])->skip(2)->toArray() // [3,4,5]
```

## limit(?int $limit = null)
Limits number of items to $limit. If $limit is null, it will return all items.
```php
FunQuery::create([1,2,3,4,5])->limit(2)->toArray() // [1,2]
```

## slice(int $skip = 0, ?int $limit = null)
Combines skip and limit. It skips $skip items and limits to $limit items.
```php
FunQuery::create([1,2,3,4,5])->slice(1,2)->toArray() // [2,3]
```

## flat()
Flattens array of arrays.
```php
FunQuery::create([[1,2],[3,4],[5,6]])->flat()->toArray() // [1,2,3,4,5,6]
```

## distinct(callable(T):T2 $fun=null)
Removes duplicates. If function is passed, it will compare results of this function.
```php
FunQuery::create([1,2,2,3,3,3])->distinct()->toArray() // [1,2,3]
FunQuery::create([1,2,2,3,3,3])->distinct(fn($x) => $x % 2)->toArray() // [1,2]
```

## toArray()
Converts FunQuery to array.
```php
FunQuery::create([1,2,3,4,5])->toArray() // [1,2,3,4,5]
```

## toAssoc(callable(T):string $keyFun, callable(T):T2 $valueFun)
Converts FunQuery to associative array. If there are duplicates, it will throw exception.
```php
FunQuery::create([1,2,3,4,5])->toAssoc(fn($x) => "key$x", fn($x) => $x * 2) // ["key1" => 2, "key2" => 4, "key3" => 6, "key4" => 8, "key5" => 10]
```

## toAssocArrayIgnoreDuplicates(callable(T):string $keyFun, callable(T):T2 $valueFun)
Converts FunQuery to associative array. If there are duplicates, it will ignore them.
```php
FunQuery::create([1,2,1,3,4])->toAssocArrayIgnoreDuplicates(fn($x) => "key$x", fn($x) => $x * 2) // ["key1" => 2, "key2" => 4, "key3" => 6, "key4" => 8]
```

## some(callable(T):bool $fun)
Returns true if at least one item matches condition.
```php
FunQuery::create([1,2,3,4,5])->some(fn($x) => $x > 2) // true
```

## every(callable(T):bool $fun)
Returns true if all items match condition.
```php
FunQuery::create([1,2,3,4,5])->every(fn($x) => $x > 2) // false
```

## count()
Returns number of items.
```php
FunQuery::create([1,2,3,4,5])->count() // 5
```

## reduce(callable(T,T):T $fun, T $initial)
Reduces items to single value.
```php
FunQuery::create([1,2,3,4,5])->reduce(fn($acc, $x) => $acc + $x, 0) // 15
```

## groupBy(callable(T):T2 $fun)
Groups items by result of function.
```php
FunQuery::create([1,2,3,4,5])->groupBy(fn($x) => $x % 2) // [0 => [2,4], 1 => [1,3,5]]
```

## reverse()
Reverses items.
```php
FunQuery::create([1,2,3,4,5])->reverse() // [5,4,3,2,1]
```

## execute()
Executes pipeline. By default FunQuery uses lazy execution, but sometimes you may want to force execution.
```php
$obj = FunQuery::create([1,2,3,4,5])->map(fn($x) => someLongRunningFunction($x))->execute();
$result1=$obj->filter(fn($x) => $x > 2)->toArray();
$result2=$obj->filter(fn($x) => $x < 2)->toArray();
```

## min(callable(T):T2 $fun)
Returns minimal value. If function is passed, it will compare results of this function, but returns original value.
```php
FunQuery::create([1,2,3,4,5])->min() // 1
FunQuery::create([1,2,3,4,5])->min(fn($x) => -$x) // 5
```

## max(callable(T):T2 $fun)
Returns maximal value. If function is passed, it will compare results of this function, but returns original value.
```php
FunQuery::create([1,2,3,4,5])->max() // 5
FunQuery::create([1,2,3,4,5])->max(fn($x) => -$x) // 1
```

## sum(callable(T):T2 $fun)
Returns sum of values. If function is passed, it will sum results of this function.
```php
FunQuery::create([1,2,3,4,5])->sum() // 15
FunQuery::create([1,2,3,4,5])->sum(fn($x) => $x * 2) // 30
```

## avg(callable(T):T2 $fun)

Returns average of values. If function is passed, it will average results of this function.
```php
FunQuery::create([1,2,3,4,5])->avg() // 3
FunQuery::create([1,2,3,4,5])->avg(fn($x) => $x * 2) // 6
```

## concat(FunQuery<T> $funQuery)
Concatenates two FunQuery objects.
```php
FunQuery::create([1,2,3])->concat(FunQuery::create([4,5,6]))->toArray() // [1,2,3,4,5,6]
```
