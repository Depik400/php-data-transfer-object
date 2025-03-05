# PHP Data Transfer Object

![Размер репозитория GitHub](https://img.shields.io/github/repo-size/Depik400/php-data-transfer-object)
![Язык программирования GitHub](https://img.shields.io/github/languages/top/Depik400/php-data-transfer-object)
![Последний коммит GitHub](https://img.shields.io/github/last-commit/Depik400/php-data-transfer-object)
![Проблемы GitHub](https://img.shields.io/github/issues/Depik400/php-data-transfer-object)

Проект предназначен для работы с умными объектами (Smart objects) в PHP.

## Содержание

- [Установка](#установка)
- [Использование](#использование)
- [Умные Атрибуты](#умные-атрибуты)
- [Вклад](#вклад)
- [Лицензия](#лицензия)

### Требования

- PHP 8.2 и выше
- Composer

### Установка

```bash
composer require paulo/data-transfer-object
```

## Умные Атрибуты

### `PropertyMapFrom`

Позволяет указать источник данных для свойства объекта.

```php
#[PropertyMapFrom('source.from.key.innerKey')]
public mixed $property;
```

### `PropertyMapTo`

Позволяет указать место назначения данных для свойства объекта.

```php
#[PropertyMapTo('destination.to.qwe')]
public mixed $property;
```

### `PropertyIgnoreParse`

Игнорирует свойство при парсинге данных.

```php
#[PropertyIgnoreParse]
public mixed $property;
```

### `PropertyIgnoreSerialize`

Игнорирует свойство при сериализации данных.

```php
#[PropertyIgnoreSerialize]
public mixed $property;
```

### `PropertyInternal`

Обозначает внутреннее свойство, которое не будет парситься или сериализоваться.

```php
#[PropertyInternal]
public mixed $property;
```

### `PropertyParse`

Позволяет указать класс, который будет использоваться для парсинга данных для свойства.

```php
#[PropertyParse(DateTime::class)]
public DateTime $property;
```

### `PropertyParseArray`

Позволяет указать класс и дополнительные параметры для парсинга массива данных.

```php
#[PropertyParseArray(InternalClass::class, 'wrap', true)]
public array $property;
```

### `PropertySerialize`

Позволяет указать класс и метод для сериализации данных для свойства.

```php
#[PropertySerialize(InternalClass2::class, 'serialize', true, 'array')]
public InternalClass2 $property;
```

### `PropertySerializeArray`

Позволяет указать класс и метод для сериализации массива данных.

```php
#[PropertySerializeArray(InternalClass2::class, 'serialize', true, InternalClass2::class)]
public array $property;
```

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

