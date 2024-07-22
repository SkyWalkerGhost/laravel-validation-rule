# Build custom laravel validation rules easily

## Installation
Require the package with composer using the following command:

```
composer require shergela/validation-rule
```

### ServiceProvide
```php
<?php
return [
    Shergela\Validations\ShergelaValidationsServiceProvider
];
```

## Build rules

<div>
  	<ol>
        <li><a href="#create-rule">Build rule</a></li>
  	</ol>
</div>

<div id="create-rule">

|        **Methods**        |    **Laravel Rule**     |
|:-------------------------:|:-----------------------:|
|     Rule::required()      |        required         |
|     Rule::nullable()      |        nullable         |
|      Rule::boolean()      |         boolean         |
|       Rule::rules()       |      custom rules       |
|         ->email()         |          email          |
|      ->uniqueEmail()      |   unique:users,email    |
|     **Integer Rules**     |    **Integer Rules**    |
|          ->min()          |           min           |
|          ->max()          |           max           |
|       ->minDigits()       |       min_digits        |
|       ->maxDigits()       |       max_digits        |
|        ->integer()        |         integer         |
|        ->numeric()        |         numeric         |
|        ->digits()         |        digits:1         |
|     ->digitsBetween()     |       digits:1,4        |
|        ->decimal()        |         decimal         |
|     **String Rules**      |    **String Rules**     |
|         ->alpha()         |          alpha          |
|       ->alphaDash()       |       alpha_dash        |
|       ->alphaNum()        |        alpha_num        |
|        ->string()         |         string          |
|       ->uppercase()       |        uppercase        |
|       ->lowercase()       |        lowercase        |
|         ->regex()         |      regex:pattern      |
|       ->hexColor()        |        hex_color        |
|       ->hexColor()        |        hex_color        |
|         ->json()          |          json           |
|          ->url()          |  url or url:http,https  |
|         ->uuid()          |          uuid           |
|         ->ulid()          |          ulid           |
|      **Date Rules**       |     **Date Rules**      |
|       ->timezone()        |        timezone         |
|         ->date()          |          date           |
|      ->dateFormat()       |       date_format       |
|      ->dateEquals()       |       date_equals       |
|      ->dateBefore()       |         before          |
|   ->dateBeforeOrEqual()   |     before_or_equal     |
|       ->dateAfter()       |          after          |
| ->dateAfterOrEqualToday() |  after_or_equal:today   |
|   ->dateAfterOrEquals()   |     after_or_equal      |
|  **IP Mac Address Rule**  | **IP Mac Address Rule** |
|          ->ip()           |           ip            |
|         ->ipv4()          |          ipv4           |
|         ->ipv6()          |          ipv6           |
|      ->macAddress()       |       mac_address       |

</div>


# License

The MIT License (MIT). Please see [License](LICENSE) for more information.
