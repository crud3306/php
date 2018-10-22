

insert
-------
````
collection = (new MongoDB\Client)->test->users;

$insertOneResult = $collection->insertOne([
    'username' => 'admin',
    'email' => 'admin@example.com',
    'name' => 'Admin User',
]);

printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());

var_dump($insertOneResult->getInsertedId());


The output would then resemble:

Inserted 1 document(s)
object(MongoDB\BSON\ObjectId)#11 (1) {
  ["oid"]=>
  string(24) "579a25921f417dd1e5518141"
}
```

updatemany
-------
```
$collection = (new MongoDB\Client)->test->restaurants;

$updateResult = $collection->updateMany(
    [ 'borough' => 'Queens' ],
    [ '$set' => [ 'active' => 'True' ]]
);

printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
printf("Modified %d document(s)\n", $updateResult->getModifiedCount());

The output would then resemble:

Matched 5656 document(s)
Modified 5656 document(s)
```


find
-------
```
$collection = (new MongoDB\Client)->test->restaurants;

$cursor = $collection->find(
    [
        'cuisine' => 'Italian',
        'borough' => 'Manhattan',
    ],
    [
        'limit' => 5,
        'projection' => [
            'name' => 1,
            'borough' => 1,
            'cuisine' => 1,
        ],
    ]
);

foreach ($cursor as $restaurant) {
   var_dump($restaurant);
};
The output would then resemble:

object(MongoDB\Model\BSONDocument)#10 (1) {
  ["storage":"ArrayObject":private]=>
  array(4) {
    ["_id"]=>
    object(MongoDB\BSON\ObjectId)#8 (1) {
      ["oid"]=>
      string(24) "576023c6b02fa9281da3f983"
    }
    ["borough"]=>
    string(9) "Manhattan"
    ["cuisine"]=>
    string(7) "Italian"
    ["name"]=>
    string(23) "Isle Of Capri Resturant"
  }
}
object(MongoDB\Model\BSONDocument)#13 (1) {
  ["storage":"ArrayObject":private]=>
  array(4) {
    ["_id"]=>
    object(MongoDB\BSON\ObjectId)#12 (1) {
      ["oid"]=>
      string(24) "576023c6b02fa9281da3f98d"
    }
    ["borough"]=>
    string(9) "Manhattan"
    ["cuisine"]=>
    string(7) "Italian"
    ["name"]=>
    string(18) "Marchis Restaurant"
  }
}
object(MongoDB\Model\BSONDocument)#8 (1) {
  ["storage":"ArrayObject":private]=>
  array(4) {
    ["_id"]=>
    object(MongoDB\BSON\ObjectId)#10 (1) {
      ["oid"]=>
      string(24) "576023c6b02fa9281da3f99b"
    }
    ["borough"]=>
    string(9) "Manhattan"
    ["cuisine"]=>
    string(7) "Italian"
    ["name"]=>
    string(19) "Forlinis Restaurant"
  }
}
object(MongoDB\Model\BSONDocument)#12 (1) {
  ["storage":"ArrayObject":private]=>
  array(4) {
    ["_id"]=>
    object(MongoDB\BSON\ObjectId)#13 (1) {
      ["oid"]=>
      string(24) "576023c6b02fa9281da3f9a8"
    }
    ["borough"]=>
    string(9) "Manhattan"
    ["cuisine"]=>
    string(7) "Italian"
    ["name"]=>
    string(22) "Angelo Of Mulberry St."
  }
}
object(MongoDB\Model\BSONDocument)#10 (1) {
  ["storage":"ArrayObject":private]=>
  array(4) {
    ["_id"]=>
    object(MongoDB\BSON\ObjectId)#8 (1) {
      ["oid"]=>
      string(24) "576023c6b02fa9281da3f9b4"
    }
    ["borough"]=>
    string(9) "Manhattan"
    ["cuisine"]=>
    string(7) "Italian"
    ["name"]=>
    string(16) "V & T Restaurant"
  }
}
```


deletemany
---------
```
$collection = (new MongoDB\Client)->test->users;
$collection->drop();

$collection->insertOne(['name' => 'Bob', 'state' => 'ny']);
$collection->insertOne(['name' => 'Alice', 'state' => 'ny']);
$deleteResult = $collection->deleteMany(['state' => 'ny']);

printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount());
The output would then resemble:

Deleted 2 document(s)
```


