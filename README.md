# Build custom laravel validation rules easily

## Installation
Require the package with composer using the following command:

```
composer require shergela/validation-rule
```

### Service Provider
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
        <li><a href="#writing-messages">Build Messages</a></li>
  	</ol>
</div>

<div id="create-rule">

## Available rules (47)

|        **Methods**        |   **Laravel Rule**    |           **Methods**           |             **Rule**              |
|:-------------------------:|:---------------------:|:-------------------------------:|:---------------------------------:|
|     Rule::required()      |       required        |         ->startsWith()          |      starts_with:foo,bar...       |
|     Rule::nullable()      |       nullable        |    ->uppercaseFirstLetter()     |    new UppercaseFirstLetter()     |
|      Rule::boolean()      |        boolean        |            ->size()             |               size                |
|       Rule::rules()       |     custom rules      |          ->endsWith()           |         ends_with:foo,bar         |
|         ->email()         |         email         |       ->doesntStartWith()       |     doesnt_start_with:foo,bar     |
|      ->uniqueEmail()      |  unique:users,email   |        ->doesntEndWith()        |      doesnt_end_with:foo,bar      |
|          ->min()          |          min          |             ->in()              |           in:foo,bar...           |
|          ->max()          |          max          |            ->notIn()            |       not_in:foo,bar,baz...       |
|       ->minDigits()       |      min_digits       |   ->separateIntegersByComma()   |   new SeparateIntegersByComma()   |
|       ->maxDigits()       |      max_digits       |   ->separateStringsByComma()    |   new SeparateStringsByComma()    |
|        ->integer()        |        integer        | ->separateStringsByUnderscore() | new SeparateStringsByUnderscore() |
|        ->numeric()        |        numeric        |          ->timezones()          |     new TimezoneValidation()      |
|        ->digits()         |       digits:1        |        ->timezoneAsia()         |  new TimezoneRegionValidation()   |
|     ->digitsBetween()     |      digits:1,4       |       ->timezoneEurope()        |  new TimezoneRegionValidation()   |
|        ->decimal()        |        decimal        |       ->timezoneAmerica()       |  new TimezoneRegionValidation()   |
|         ->alpha()         |         alpha         |     ->timezoneAntarctica()      |  new TimezoneRegionValidation()   |
|       ->alphaDash()       |      alpha_dash       |       ->timezoneArctic()        |  new TimezoneRegionValidation()   |
|       ->alphaNum()        |       alpha_num       |      ->timezoneAtlantic()       |  new TimezoneRegionValidation()   |
|        ->string()         |        string         |      ->timezoneAustralia()      |  new TimezoneRegionValidation()   |
|       ->uppercase()       |       uppercase       |       ->timezoneIndian()        |  new TimezoneRegionValidation()   |
|       ->lowercase()       |       lowercase       |       ->timezonePacific()       |  new TimezoneRegionValidation()   |
|         ->regex()         |     regex:pattern     |    ->lowercaseFirstLetter()     |    new LowercaseFirstLetter()     |
|       ->hexColor()        |       hex_color       |                                 |                                   |
|         ->json()          |         json          |                                 |                                   |
|          ->url()          | url or url:http,https |                                 |                                   |
|         ->uuid()          |         uuid          |                                 |                                   |
|         ->ulid()          |         ulid          |                                 |                                   |
|       ->timezone()        |       timezone        |                                 |                                   |
|         ->date()          |         date          |                                 |                                   |
|      ->dateFormat()       |      date_format      |                                 |                                   |
|      ->dateEquals()       |      date_equals      |                                 |                                   |
|      ->dateBefore()       |        before         |                                 |                                   |
|   ->dateBeforeOrEqual()   |    before_or_equal    |                                 |                                   |
|       ->dateAfter()       |         after         |                                 |                                   |
| ->dateAfterOrEqualToday() | after_or_equal:today  |                                 |                                   |
|   ->dateAfterOrEquals()   |    after_or_equal     |                                 |                                   |
|          ->ip()           |          ip           |                                 |                                   |
|         ->ipv4()          |         ipv4          |                                 |                                   |
|         ->ipv6()          |         ipv6          |                                 |                                   |
|      ->macAddress()       |      mac_address      |                                 |                                   |

</div>


<div id="writing-messages">

### Writing custom messages

```php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Shergela\Validations\Validation\Rule;

class TestRequest extends FormRequest
{
/**
* @return bool
*/
public function authorize(): bool
{
return true;
}

    public function rules(): array
    {
        return [
            'name' => [
                Rule::required()->email()->messages(
                    messages: [
                        'name.required' => 'The name field is required.',
                        'email.required' => 'The email field is required.',
                    ]
                )
            ],
        ];
    }
}
```

</div>


# License

The MIT License (MIT). Please see [License](LICENSE) for more information.
