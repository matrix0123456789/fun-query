This lib allows you to make operations on arrays in functional style. It is like LINQ, but for PHP.

Designed for PHP 7.4 and higher.
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

#FunQuery::create()
Creates FunQuery object from array, generator or iterator.

#filter()
Executes function for every item and sends to output only, if function returns true
#sort()
Sorts items. If function is passed, it sorts by result of this function
#map()
It keeps the same number of items, but each item is replaced by what was returned by passed function.

#first()
Returns first object. Throws exception if pipeline is empty.
#firstOrNull()
Returns first object or null if pipeline is empty. 