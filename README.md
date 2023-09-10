# Helga
![github stars](https://img.shields.io/github/stars/phrenotype/helga?style=social)
![license](https://img.shields.io/github/license/phrenotype/helga)
![contributors](https://img.shields.io/github/contributors/phrenotype/helga)
![code size](https://img.shields.io/github/languages/code-size/phrenotype/helga)


This is a library for validation with easy customization of error messages. It validates everything from variables to files. It is not limited to form validation.

It offers the surest way to safely upload files (especially images), even if they are improperly stored or included directly.

It has a clean free of unneccesary clutters. 

Additionally, there are no external dependencies, apart from php, of course :).

## Install
```composer require helga/helga```

## Usage

The most basic use is to validate single values.

```php
<?php

use function Helga\validate;

$v = validate("chase")->withRules(['required', 'minLen:5', 'maxLen:10']);

if($v->passes()){
    echo "Validation passed.";
}else{
    var_dump($v->errors());
}
```


```php
<?php

use function Helga\validate;;

$v = validate("paul@gmail.com")->withRules(['email']);

if($v->fails()){
    var_dump($v->errors());
}else{
    echo "Validation passed()";
}
```

For multiple values, pass an associative array like so :
```php
<?php 

use function Helga\validate;

$values = [
    'name' => '',
    'age' => 34,
    'address' => '#5 Cool street',
    'email' => 'fake@gmail'
]

$rules = [
    'name' => ['required', 'minLen:4', 'alpha'],
    'age' => ['required', 'integer'],
    'address' => ['alnum'],
    'email' => ['email']
]

$v = validate($values)->withRules($rules);

if($v->passes()){
    // Do something
}else{
    // Do something else
}

```

The variable `$values` above could be any associative array, including super globals.

**A complete list of the rule directives can be found [ here ]( docs/rules.md )**

### Retrieving Error Messages
You will notice that the method `->errors()` is used to retrieve error messages. This is okay if you are only validating one value.  

When validating multiple values, the errors come back as an associative array, with the key being the the name of the field, and the value being an array of all the errors associated with the field.

To get a flattened list of errors with dealing with multiple fields, use the `->flatErrors` method.

By default, it only flattens and returns the first error for each field. Passing boolean `false` as an argument will flatten all the errors.

```php
<?php 

use function Helga\validate;

$values = [
    'name' => 'ab1',
    'age' => '233yy',
    'address' => '#5 Cool street',
    'email' => 'fake@gmail'
]

$rules = [
    'name' => ['required', 'minLen:4', 'alpha'],
    'age' => ['required', 'integer'],
    'address' => ['alnum', 'minLen:5', 'maxLen:15'],
    'email' => ['email', 'alpha']
]

$v = validate($values)->withRules($rules);

var_dump($v->flatErrors());
var_dump($v->flatErrors(false));

```  

### Anatomy Of A Directive
The first thing to notice is the syntax of a rule.  

`directive[:arguments][:customMessage]`  

The only required field is the directive, like `required`, `min`, `fileImage` e.t.c.  

The arguments are only required if the directive requires arguments. For instance `integer`, `email` or `url` do not require arguments, but `minLen`, `max` or `min` require arguments.  

Finally, you can pass a custom error message as a third argument.

### Custom Error Messages

For directives without arguments i.e single directives  

```php
<?php

use function Helga\validate;

$v = validate("c")->withRules([
    'integer::It must be a number', 
    'alpha::It must be an alphabet'
    ]);

var_dump($v->errors());
```

For directives with arguments  

```php
<?php

use function Helga\validate;

$v = validate("c")->withRules([
    'minLen:4:Cannot be less than four',
    'regex:/^\d/:Failed to match'    
    ]);

var_dump($v->errors());
```  

## Contact
**Email** : dev@paulrobert.xyz

## In Summary...
Happy validation :)

