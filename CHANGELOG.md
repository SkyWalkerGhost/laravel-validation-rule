# CHANGELOG

All notable changes to `shergela/validation-rule` will be documented in this file.

## 1.0.7 | 12 September - 2024
- Refactoring code.
- Added support for substitutions in messages, This is needed to be able to write good messages.
  Placeholder will allow you to write messages using positioning.
- New syntax for messages "The :name field is required.", or "The :email field is required."


## 1.0.6 | 06 September - 2024

- add new `uppercaseWord()` method.
- add new `lowercaseWord()` method.
- add new `lettersAndSpaces()` method.

## 1.0.5 | 30 August - 2024

- add new `array()` method.
- add new `arrayDistinct()` method.
- add new `arrayDistinctStrict()` method.
- add new `arrayDistinctIgnoreCase()` method.

## 1.0.4 | 26 August - 2024

- add new `length()` method.
- add a custom message support in the methods.

## 1.0.3 | 23 August - 2024

## Modified Rule::in() and Rule::notIn() rules.
### Add tests.


## 1.0.2 | 19 August - 2024

## These methods work with timezones
### You need to provide city names in the methods.

- add new `timezones()` method
- add new `timezoneAfrica()` method
- add new `timezoneAsia()` method
- add new `timezoneEurope()` method
- add new `timezoneAmerica()` method
- add new `timezoneAntarctica()` method
- add new `timezoneArctic()` method
- add new `timezoneAtlantic()` method
- add new `timezoneAustralia()` method
- add new `timezoneIndian()` method
- add new `timezonePacific()` method

## 1.0.1 | 16 August - 2024

- add new `lowercaseFirstLetter()` method
- add new `separateIntegersByComma()` method
- add new `separateStringsByComma()` method
- add new `separateStringsByUnderscore()` method

## 1.0.0 | 23 July - 2024

- add new `size()` method
- add new `endsWith()` method
- add new `doesntStartWith()` method
- add new `doesntEndWith()` method
- add new `in()` method
- add new `notIn()` method
