# VALIDATION DIRECTIVES
Below is a complete list of validation directives.


`eq`  
The value passed must equal the one defined in the directive.
```php
$v = validate(5)->withRules(['eq:5']);
```
`neq`    
The value passed must **not** equal the one defined in the directive.
```php
$v = validate(5)->withRules(['neq:4']);
```
`lt`    
The value passed must be less than the one defined in the directive.
```php
$v = validate(2)->withRules(['lt:5']);
```
`lte`  
The value passed must be less than or equal to the one defined in the directive.  
```php
$v = validate(4)->withRules(['lte:4']);
```
`gt`  
The value passed must be greater than the one defined in the directive.  
```php
$v = validate(5)->withRules(['gt:2']);
```
`gte`  
The value passed must be greater than or equal to the one defined in the directive.  
```php
$v = validate(5)->withRules(['gte:5']);
```
`min`  
The **number** value passed must be greater than or at least equal to the one defined in the directive.  
```php
$v = validate(5)->withRules(['min:5']);
```
`max`  
The **number** value passed must be less than or at most equal to the one defined in the directive.  
```php
$v = validate(5)->withRules(['max:5']);
```  
`range`  
The **number** value passed must be between the values (**inclusive**) defined in the directive.
```php
$v = validate(5)->withRules(['range:2-10']);
```
`minLen`  
The character length of the value passed must be greater than or at least equal to the value defined in the directive.  
```php
$v = validate('chase')->withRules(['minLen:5']);
```
`maxLen`  
The character length of the value passed must be less than or at most equal to the value defined in the directive.
```php
$v = validate('chase')->withRules(['maxLen:5']);
```  
`rangeLen`  
The character length of the value passed must be between values (**inclusive**) defined in the directive. It makes it easier to define both `maxLen` and `minLen` at a go.
```php
$v = validate('chase')->withRules(['rangeLen:5-10']);
``` 
`len`  
The value passed must be equal to the one defined in the directive.  
```php
$v = validate('chase')->withRules(['len:5']);
```
`regex`  
The value passed must match the regular expression defined in the directive.  
```php
$v = validate('chase')->withRules(['regex:/^c/']);
```  
`in`  
The value passed must be one or the list defined in the directive. Works for both strings and numbers.
```php
$v = validate('peter')->withRules(['in:paul,peter,james']);
// Or with numbers
$v = validate(2)->withRules(['in:3,2,5']);
```
**Notice how there is no space after the comma.**  

`integer`    
The value passed must be an integer. The value can be a string or a number.
```php
$v = validate(5)->withRules(['integer']);
```  
`alnum`  
The value passed must be made up of only numbers and letters of the alphabet i.e. alphanumeric.  
```php
$v = validate('abcd1234')->withRules(['alnum']);
```  
`alpha`  
The value passed must be made up of only letters of the alphabet.  
```php
$v = validate('abcd')->withRules(['alpha']);
```  

`float`  
The value passed must be a floating point number.  
```php
$v = validate(.5)->withRules(['float']);
$v = validate(3.5)->withRules(['float']);
```

`email`  
The value passed must have a valid email syntax.  
```php
$v = validate('chase@chase.com')->withRules(['email']);
```

`url`  
The value passed must have a valid url syntax.  
```php
$v = validate('chase.com')->withRules(['url']);
$v = validate('http://chase.com')->withRules(['url']);
```

`required`  
The value passed must not be null or be an empty string. A value of `zero` will pass validation.
```php
$v = validate('')->withRules(['required']);
```

`unique`  
The value passed must be unique. That is it must not already exists. How to check for uniqueness is left up to the user.

A callable which checks for uniqueness should return `true` if the value is present and `false` if the value is present.

Assuming some rules are already there  
```php
$v = validate('chase@chase.com')->withRules(['email'])->unique(function($value){
    //Perform your lookup here and return true or false accordingly.
});
```  
If there are no other rules  
```php
$v = validate('chase@chase.com')->unique(function($value){
    //Perform your lookup here and return true or false accordingly.
});
```

Note that `unique` can be called multiple times, especially when dealing with multiple values  

```php
$values = [
    'name' => 'chase',
    'email' => 'chase@gmail.com',
    'age' => 50
];

$rules = [
    'name' => 'alpha',
    'age' => 'integer',
    'email' => 'email'
];

$v = validate($values)->withRules($rules)->unique('email', function($value){ return true; })->unique('name', function($value){ return true; });
```

`exists`  
The value passed must already exist. This is the opposite of `unique`. How to check for existence is left up to the user.

A callable which checks for existence should return `true` if the value is present and `false` if the value is present.

Assuming some rules are already there  
```php
$v = validate('chase@chase.com')->withRules(['email'])->exists(function($value){
    //Perform your lookup here and return true or false accordingly.
});
```  
If there are no other rules  
```php
$v = validate('chase@chase.com')->exists(function($value){
    //Perform your lookup here and return true or false accordingly.
});
```

Note that `exists` can be called multiple times, especially when dealing with multiple values  

```php
$values = [
    'name' => 'chase',
    'email' => 'chase@gmail.com',
    'age' => 50
];

$rules = [
    'name' => 'alpha',
    'age' => 'integer',
    'email' => 'email'
];

$v = validate($values)->withRules($rules)->exists('email', function($value){ return true; })->exists('name', function($value){ return true; });
```

## VALIDATING FILES
For file validation, the value passed is the file path. When trying to validate uploaded files, use `$_FILES['htmlname']['tmp_name']`. For files on disk, just point to the location.

`fileRequired`  
The file path passed must exist and be readable. It can also be used to check if a file was uploaded, when dealing with web forms.
```php
$path = '/files/image.png';
$v = validate($path)->withRules(['fileRequired']);
```

`fileMinSize`  
The file size must be at least the value passed (**in bytes**) in the directive.
```php
$path = '/files/image.png';

// 2 MB
$size = 2 * (1024 * 1024);
$v = validate($path)->withRules(["fileMinSize:$size"]);
```

`fileMaxSize`  
The file size must be at least the value passed (**in bytes**) in the directive.  
```php
$path = '/files/image.png';

// 2 MB
$size = 2 * (1024 * 1024);
$v = validate($path)->withRules(["fileMaxSize:$size"]);
```

`check`  
This is used for custom validation.

A callable which contains code for the validation should return `true` if it passes and `false` if it fails. The callable should not take any arguments.

It is distinct from both `unique` and `exists` in that it does not apply to any value in particular.

Assuming some rules are already there  
```php
$v = validate('chase@chase.com')->withRules(['email'])->check(function(){
    //Perform custom check here
});
```  
If there are no other rules  
```php
$v = validate('chase@chase.com')->check(function(){
    //Perform custom check here
});
```

Note that `check` can be called multiple times. And the syntax does not change even when dealing with multiple values, because, again, **it does not apply to any value in particular**.

```


`fileImage`  
The path passed must point to a valid image file. That is one of : `jpeg, png, gif, or webp`.  
```php
$path = '/files/image.png';
$v = validate($path)->withRules(['fileImage']);
```

`filePdf`  
The path passed must point to a valid pdf file.  
```php
$path = '/files/pdf.pdf';
$v = validate($path)->withRules(['filePdf']);
```

`fileOffice`  
The path passed must point to a valid office file. That is one of : `doc, ppt, and xls`. Not any of `docx, pptx, xlsx`.
```php
$path = '/files/doc.doc';
$v = validate($path)->withRules(['fileOffice']);
```

`mimes`  
The path passed must point to a file that has one of the mimes defined in the directive.  
```php
$path = '/files/image.jpg';
$v = validate($path)->withRules(['mimes:image/jpeg,image/png,application/pdf']);
```

`mimeTypes`  
The path passed must point to a file that has one of the mime types defined in the directive.  
```php
$path = '/files/image.jpg';
$v = validate($path)->withRules(['mimes:jpeg,png,pdf']);
```


