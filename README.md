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

## Build rules

<div>
  	<ol>
        <li><a href="#create-rule">Build rule</a></li>
  	</ol>
</div>

<div id="create-rule">

|         **<span style="color: red;">Methods</span>**          |     **<span style="color: red;">Laravel Rule</span>**     |
|:-------------------------------------------------------------:|:---------------------------------------------------------:|
|     <span style="color: #CA473F">Rule::required()</span>      |       <span style="color: #CA473A">required</span>        |
|     <span style="color: #CA473F">Rule::nullable()</span>      |       <span style="color: #CA473A">nullable</span>        |
|      <span style="color: #CA473F">Rule::boolean()</span>      |        <span style="color: #CA473A">boolean</span>        |
|       <span style="color: #CA473F">Rule::rules()</span>       |     <span style="color: #CA473A">custom rules</span>      |
|         <span style="color: #CA473F">->email()</span>         |         <span style="color: #CA473A">email</span>         |
|      <span style="color: #CA473F">->uniqueEmail()</span>      |  <span style="color: #CA473A">unique:users,email</span>   |
|       **<span style="color: red">Integer Rules</span>**       |     **<span style="color: red">Integer Rules</span>**     |
|          <span style="color: #CA473F">->min()</span>          |          <span style="color: #CA473A">min</span>          |
|          <span style="color: #CA473F">->max()</span>          |          <span style="color: #CA473A">max</span>          |
|       <span style="color: #CA473F">->minDigits()</span>       |      <span style="color: #CA473A">min_digits</span>       |
|       <span style="color: #CA473F">->maxDigits()</span>       |      <span style="color: #CA473A">max_digits</span>       |
|        <span style="color: #CA473F">->integer()</span>        |        <span style="color: #CA473A">integer</span>        |
|        <span style="color: #CA473F">->numeric()</span>        |        <span style="color: #CA473A">numeric</span>        |
|        <span style="color: #CA473F">->digits()</span>         |       <span style="color: #CA473A">digits:1</span>        |
|     <span style="color: #CA473F">->digitsBetween()</span>     |      <span style="color: #CA473A">digits:1,4</span>       |
|        <span style="color: #CA473F">->decimal()</span>        |        <span style="color: #CA473A">decimal</span>        |
|       **<span style="color: red">String Rules</span>**        |     **<span style="color: red">String Rules</span>**      |
|         <span style="color: #CA473F">->alpha()</span>         |         <span style="color: #CA473A">alpha</span>         |
|       <span style="color: #CA473F">->alphaDash()</span>       |      <span style="color: #CA473A">alpha_dash</span>       |
|       <span style="color: #CA473F">->alphaNum()</span>        |       <span style="color: #CA473A">alpha_num</span>       |
|        <span style="color: #CA473F">->string()</span>         |        <span style="color: #CA473A">string</span>         |
|       <span style="color: #CA473A">->uppercase()</span>       |       <span style="color: #CA473A">uppercase</span>       |
|       <span style="color: #CA473A">->lowercase()</span>       |       <span style="color: #CA473A">lowercase</span>       |
|         <span style="color: #CA473A">->regex()</span>         |     <span style="color: #CA473A">regex:pattern</span>     |
|       <span style="color: #CA473A">->hexColor()</span>        |       <span style="color: #CA473A">hex_color</span>       |
|       <span style="color: #CA473A">->hexColor()</span>        |       <span style="color: #CA473A">hex_color</span>       |
|         <span style="color: #CA473A">->json()</span>          |         <span style="color: #CA473A">json</span>          |
|          <span style="color: #CA473A">->url()</span>          | <span style="color: #CA473A">url or url:http,https</span> |
|         <span style="color: #CA473A">->uuid()</span>          |         <span style="color: #CA473A">uuid</span>          |
|         <span style="color: #CA473A">->ulid()</span>          |         <span style="color: #CA473A">ulid</span>          |
|        **<span style="color: red">Date Rules</span>**         |      **<span style="color: red">Date Rules</span>**       |
|       <span style="color: #CA473A">->timezone()</span>        |       <span style="color: #CA473A">timezone</span>        |
|         <span style="color: #CA473F">->date()</span>          |         <span style="color: #CA473A">date</span>          |
|      <span style="color: #CA473F">->dateFormat()</span>       |      <span style="color: #CA473A">date_format</span>      |
|      <span style="color: #CA473F">->dateEquals()</span>       |      <span style="color: #CA473A">date_equals</span>      |
|      <span style="color: #CA473F">->dateBefore()</span>       |        <span style="color: #CA473A">before</span>         |
|   <span style="color: #CA473F">->dateBeforeOrEqual()</span>   |    <span style="color: #CA473A">before_or_equal</span>    |
|       <span style="color: #CA473F">->dateAfter()</span>       |         <span style="color: #CA473A">after</span>         |
| <span style="color: #CA473F">->dateAfterOrEqualToday()</span> | <span style="color: #CA473A">after_or_equal:today</span>  |
|   <span style="color: #CA473F">->dateAfterOrEquals()</span>   |    <span style="color: #CA473A">after_or_equal</span>     |
|    **<span style="color: red">IP Mac Address Rule</span>**    |  **<span style="color: red">IP Mac Address Rule</span>**  |
|          <span style="color: #CA473A">->ip()</span>           |          <span style="color: #CA473A">ip</span>           |
|         <span style="color: #CA473A">->ipv4()</span>          |         <span style="color: #CA473A">ipv4</span>          |
|         <span style="color: #CA473A">->ipv6()</span>          |         <span style="color: #CA473A">ipv6</span>          |
|      <span style="color: #CA473A">->macAddress()</span>       |      <span style="color: #CA473A">mac_address</span>      |

</div>


# License

The MIT License (MIT). Please see [License](LICENSE) for more information.
