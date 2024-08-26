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
        <li><a href="#writing-custom-message">Writing a custom Messages into method</a></li>
  	</ol>
</div>

<div id="create-rule">

## Available rules

|           **Methods**           |         **Laravel Rule**          |
|:-------------------------------:|:---------------------------------:|
|        Rule::required()         |             required              |
|        Rule::nullable()         |             nullable              |
|         Rule::boolean()         |              boolean              |
|          Rule::rules()          |           custom rules            |
|            ->email()            |               email               |
|         ->uniqueEmail()         |        unique:users,email         |
|             ->min()             |                min                |
|             ->max()             |                max                |
|          ->minDigits()          |            min_digits             |
|          ->maxDigits()          |            max_digits             |
|           ->integer()           |              integer              |
|           ->numeric()           |              numeric              |
|           ->digits()            |             digits:1              |
|        ->digitsBetween()        |            digits:1,4             |
|           ->decimal()           |              decimal              |
|            ->alpha()            |               alpha               |
|          ->alphaDash()          |            alpha_dash             |
|          ->alphaNum()           |             alpha_num             |
|           ->string()            |              string               |
|          ->uppercase()          |             uppercase             |
|          ->lowercase()          |             lowercase             |
|            ->regex()            |           regex:pattern           |
|          ->hexColor()           |             hex_color             |
|            ->json()             |               json                |
|             ->url()             |       url or url:http,https       |
|            ->uuid()             |               uuid                |
|            ->ulid()             |               ulid                |
|          ->timezone()           |             timezone              |
|            ->date()             |               date                |
|         ->dateFormat()          |            date_format            |
|         ->dateEquals()          |            date_equals            |
|         ->dateBefore()          |              before               |
|      ->dateBeforeOrEqual()      |          before_or_equal          |
|          ->dateAfter()          |               after               |
|    ->dateAfterOrEqualToday()    |       after_or_equal:today        |
|      ->dateAfterOrEquals()      |          after_or_equal           |
|             ->ip()              |                ip                 |
|            ->ipv4()             |               ipv4                |
|            ->ipv6()             |               ipv6                |
|         ->macAddress()          |            mac_address            |
|         ->startsWith()          |      starts_with:foo,bar...       |
|            ->size()             |               size                |
|          ->endsWith()           |         ends_with:foo,bar         |
|       ->doesntStartWith()       |     doesnt_start_with:foo,bar     |
|        ->doesntEndWith()        |      doesnt_end_with:foo,bar      |
|             ->in()              |           in:foo,bar...           |
|            ->notIn()            |       not_in:foo,bar,baz...       |
|            ->regex()            |           regex:pattern           |
|    ->uppercaseFirstLetter()     |    new UppercaseFirstLetter()     |
|    ->lowercaseFirstLetter()     |  new TimezoneRegionValidation()   |
|   ->separateIntegersByComma()   |   new SeparateIntegersByComma()   |
|   ->separateStringsByComma()    |   new SeparateStringsByComma()    |
| ->separateStringsByUnderscore() | new SeparateStringsByUnderscore() |
|          ->timezones()          |     new TimezoneValidation()      |
|        ->timezoneAsia()         |  new TimezoneRegionValidation()   |
|       ->timezoneEurope()        |  new TimezoneRegionValidation()   |
|       ->timezoneAmerica()       |  new TimezoneRegionValidation()   |
|     ->timezoneAntarctica()      |  new TimezoneRegionValidation()   |
|       ->timezoneArctic()        |  new TimezoneRegionValidation()   |
|      ->timezoneAtlantic()       |  new TimezoneRegionValidation()   |
|      ->timezoneAustralia()      |  new TimezoneRegionValidation()   |
|       ->timezoneIndian()        |  new TimezoneRegionValidation()   |
|       ->timezonePacific()       |  new TimezoneRegionValidation()   |
|           ->length()            |               size                |
</div>


<div id="writing-messages">

### Writing custom rule and message

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

## Writing a custom message in the methods
<div id="writing-custom-message">

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
                'name' => Rule::required(message: 'Please enter your name')
                    ->min(min: 3, message: 'Please enter at least 3 characters'),
            ];
          }
      }
```
</div> 


# License

The MIT License (MIT). Please see [License](LICENSE) for more information.
