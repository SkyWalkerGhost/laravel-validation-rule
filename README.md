# Build custom laravel validation rules easily

## Installation
Require the package with composer using the following command:

```
composer require shergela/laravel-validation-rule
```

### ServiceProvide
```php
<?php
return [
    Shergela\LaravelValidationRule\ShergelaLaravelValidationRuleProvide
];
```

## Artisan Command List

<!-- List Of Command -->
<div>
  	<ol>
        <li><a href="#create-rule">Build rule</a></li>
  	</ol>
</div>

```
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Shergela\LaravelValidationRule\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, Rule|array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => [Rule::required()->string()->min(3)],
        ];
    }
}


```



# License

The MIT License (MIT). Please see [License](LICENSE) for more information.
