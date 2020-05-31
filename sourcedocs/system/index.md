# Introduction

## Overview

- The system folder is the store of all system and common app classes. 
It is not a place that should be modified without serious caution. 
**DO NOT EDIT THESE FILES UNLESS YOU KNOW WHAT YOU ARE DOING!**
- The file `/system/Core.php` is the master program file and controls all child operations from then on. 
- Email support is provided by PHPMailer.
- Base CSS is provided by BootStrap4  (requires IE>9).
- The system/app operates on the Class/Object principle. Refer to the UML when coding.
- Adding features is most often done either by creating new Classes that "Model" an object that the application needs to perform complex operations on, or by extending one of the core classes to build upon the included functionality of said core class.

### Important Notes
Application stream output (eg:echo) is blocked if the system is not in debug mode
The HTML tags and loading of the BootStrap4 CSS Framework are handled by the system renderer. There are two options to override this style:
Override the style with a custom css in the application, available to clients via the public folder
Disable BootStrap4 in the system config and create a complete custom CSS for the app (don't forget to style the system/app log outputs)

## App Output

It is important to know that the app object and it's children are not allowed to print directly to the output (while system debug is off). For example, echo "test";would not print test on the page. All application output from child objects need to be buffered through the parser and then returned to the app and then up to the core as a string.

To capture the output of a command the view function must be used, then you would add the return value to the classes output variable, and the calling class must extend the system Parser class.

The core is responsible for actually sending the application output string to the client.

If you need to grab information from inside the app you can use the debug logger or return early with the information you're looking for.

## System Configuration File

### Path

The system config file is located in /system/Config.php

### Default Contents

The system config file is very important. It prepares all the constant variables for the core system. An error in this file leads to core failure.

The contents of this file are loaded directly into the core and then executed. Be careful what you put in here.


