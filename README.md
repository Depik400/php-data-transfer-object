# Example

```php
class T extends DataTransferObject
{
    #[PropertyMapTo('test.job.0')]
    #[PropertyMapFrom('tt')]
    #[PropertyParse(T::class, 'parse', true)]
    #[PropertySerialize(T::class, 'serString', true, 'string')]
    #[PropertySerialize(T::class, 'serInt', true, PhpType::Integer)]
    #[PropertySerialize(T::class, 'serNull', true, PhpType::NULL)]
    public mixed $t;

    public static function parse(mixed $v)
    {
        return 'Hello world!';
    }

    public static function serString(string $v)
    {
        return substr($v, 0, 5);
    }

    public static function serInt(int $v)
    {
        return $v * 2;
    }

    public static function serNull(mixed $v)
    {
        return 'undefined';
    }
}

$t = T::wrap(['tt' => null]);

var_dump($t->toArray());
/*
 array(1) {
  ["test"]=>
  array(1) {
    ["job"]=>
    array(1) {
      [0]=>
      string(5) "Hello"
    }
  }
}

 */
```

