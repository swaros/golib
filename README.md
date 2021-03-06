# golib
basic php tools lib. Includes a xpath based Xml Reader and Define some Types like Enums or Propertie Classes

#### Install

you can get this lib via composer `composer require gorgo/golib`


### Enum

The basic Enum Class provides a easy Way to check against valid Entires. Extends from Enum and define a Array inside the method 'getPossibleValueArray()' that contains all possible values.
```php
use golib\Types\Enum;
class MyEnum extends Enum {
   function getPossibleValueArray(){
        return array('a','b','c');
    }
}
```
now simply initiate the new class by using the value that should be used.
```php
$myEnum = new MyEnum('b'); // will be accepted
echo $myEnum->geValue();   // 'b'

$exceptionEnum = new MyEnum('g'); // a EnumException will be Trown beause g is not valid

$failEnum = new MyEnum('g',EnumDef::ERROR_MODE_TRIGGER_ERROR); // just triggers an PHP error
```
### ConstEnum

this is a Enum that can be used just by adding 'const' like so
```php
use golib\Types\ConstEnum;
class MyEnum extends ConstEnum {
  const INTEGER = 1;
  const STRING = 2;
  const ARRAY = 3;
}
```
the usage is the same as Enum but you can use the 'const' what improves the codestyle.
```php
$myEnum = new MyEnum(MyEnum::INTEGER); // of course valid
$value = 2;
$myEnum = new MyEnum($value); // also valid because 2 is defined as ARRAY
$myEnum = new MyEnum(6); // will throw a EnumException
$myEnum = new MyEnum(6, EnumDef::ERROR_MODE_TRIGGER_ERROR); // instead of a exception a error is triggered
```

### Props

the `Props` class is the Object oriented way to work with Content that will be provided as Array.
For example Database results:

The regular way to work with these looks like so
```php
$query = sprintf("SELECT firstname, lastname, address, age FROM friends ");
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
    echo $row['firstname'];
    echo $row['lastname'];
    echo $row['address'];
    echo $row['age'];
}

```
That means youre code depends on the database design. And if you access this data later, for exampel in another class, you have to know about the database structure.
Also it will be difficult if on some point the database will be refactored.

`Props` is a Container that forces OOP Style to accessing this data.
Like so:
```php
use golib\Props;
class Person extends Props {
    public $firstname = NULL;
    public $lastname = NULL;
    public $adress = NULL;
    public $age = NULL;
}
```
the usage looks like over engineered but just shows the different way to acess the data.
```php
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
    $person = new Person($row);
    echo $person->firstname;
    echo $person->lastname;
    echo $person->adress;
    echo $person->age;
}

```
All modern IDE's support code completion so the usage of `Props` will help you to get the right propertie.


