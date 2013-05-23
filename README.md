# cli.class.php

Class for Commandline PHP applications

## LICENSE
Copyright (c) 2012-2013, Duane Jeffers <<duane@jeffe.rs>>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## USAGE
The purpose for the cli object is to provide quick access to the common functions for a cli script/program.

### Starting the object
```php
// Starting the cli class is easy...
$cli = new cli();

// Printing with a carrage return
$cli->print_line('Welcome to cli.class.php');

// Dumping an object or array to the screen with a carrage return.
$array = array(
    0 => array(
      'maple',
		'blueberry'
	),
	1 => array(
	    'pancakes',
		'waffles'
	)
);

$cli->print_dump($array);
```

### Color Output
```php
// The class has the default color options for the terminal. Use the color_str static function to create the string.

$output = 'This is ';
$output .= cli::color_str('a ', cli::ColorFgLtRed);
$output .= cli::color_str('color ', cli::ColorFgBlue);
$output .= cli::color_str('string', cli::ColorFgBlack, cli::ColorBgLtGray);
$output .= cli::color_str('!!!', cli::ColorFgLtGreen);

$cli->print_line($output);
```

### Option Handling
```php
// Options with the cli object are as simple as passing an array through the constructor.
$cli = new cli(array(
    'email:' => 'Email Address', // option that requires an input
    'username::' => 'Username', // option that allows for an optional input
    'download' => 'Download switch' // option that acts as a bool switch
));


```
