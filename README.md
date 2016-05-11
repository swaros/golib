# golib
basic php tools lib. Includes a xpath based Xml Reader and Define some Types like Enums or Propertie Classes

you can get this lib via ...
### composer
    "repositories": [
      {
          "type": "git",
          "url": "https://github.com/swaros/golib.git"
      }
      ]
      
## Enum

The basic Enum Class provides a easy Way to check against valid Entires. Extends from Enum and define a Array inside the method 'getPossibleValueArray()' that contains all possible values.

    use golib\Types\Enum;
    class MyEnum extends Enum {
       function getPossibleValueArray(){
            return array('a','b','c');  
       }
    }

now simply initiate the new class by using the value that should be used.

    $myEnum = new MyEnum('b'); // will be accepted
    echo $myEnum->geValue();   // 'b'
    
    $exceptionEnum = new MyEnum('g'); // a EnumException will be Trown beause g is not valid
    
    $failEnum = new MyEnum('g',EnumDef::ERROR_MODE_TRIGGER_ERROR); // just triggers an PHP error

## ConstEnum

this is a Enum that can be used just by adding 'const' like so

   use golib\Types\ConstEnum;
   class MyEnum extends ConstEnum {
      const INTEGER = 1;
      const STRING = 2;
      const ARRAY = 3;
   }
the usage is the same as Enum but you can use the 'const' what improves the codestyle.

  $myEnum = new MyEnum(MyEnum::INTEGER); // of course valid
  
  $value = 2;
  $myEnum = new MyEnum($value); // also valid because 2 is defined as ARRAY
  
  $myEnum = new MyEnum(6); // will throw a EnumException
  
  $myEnum = new MyEnum(6, EnumDef::ERROR_MODE_TRIGGER_ERROR); // instead of a exception a error is triggered
  
    
