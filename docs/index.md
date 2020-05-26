# Micro MVC Framework
The framework is based on a loose implementation of the MVC pattern. All code in the system folder is and should remain ambiguous from the app.

- System
- - Introduction
Installation
Constants
Request
Directory Structure
Parser
App
Introduction
Directory Structure
Tutorial
Config
Themes
Router
Controllers
Models
Views
Source Code

# System
## Introduction
### Overview
The system folder is the store of all system and common app classes. It is not a place that should be modified without serious caution. DO NOT EDIT THESE FILES UNLESS YOU KNOW WHAT YOU ARE DOING!
The file /system/Core.php is the master program file and controls all child operations from then on. 
Email support is provided by PHPMailer.
Base CSS is provided by BootStrap4  (requires IE>9).
The system/app operates on the Class/Object principle. Refer to the UML when coding.
Adding features is most often done either by creating new Classes that "Model" an object that the application needs to perform complex operations on, or by extending one of the core classes to build upon the included functionality of said core class.
Important Notes
Application stream output (eg:echo) is blocked if the system is not in debug mode
The HTML tags and loading of the BootStrap4 CSS Framework are handled by the system renderer. There are two options to override this style:
Override the style with a custom css in the application, available to clients via the public folder
Disable BootStrap4 in the system config and create a complete custom CSS for the app (don't forget to style the system/app log outputs)
App Output
It is important to know that the app object and it's children are not allowed to print directly to the output (while system debug is off). For example, echo "test";would not print test on the page. All application output from child objects need to be buffered through the parser and then returned to the app and then up to the core as a string.

To capture the output of a command the view function must be used, then you would add the return value to the classes output variable, and the calling class must extend the system Parser class.

The core is responsible for actually sending the application output string to the client.

If you need to grab information from inside the app you can use the debug logger or return early with the information you're looking for.

System Configuration File
Path
The system config file is located in /system/Config.php

Default Contents
The system config file is very important. It prepares all the constant variables for the core system. An error in this file leads to core failure.

The contents of this file are loaded directly into the core and then executed. Be careful what you put in here.

define('APPPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app");
define('VIEWPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "views");
define('DEBUG_MODE', FALSE);
Constants
Constants are static variables that are available through an execution thread of a PHP app.

The system uses constants to define resource paths within the application.

Constants should only be defined in the system config file.

The following constants are defined.

ROOTPATH
Defines the root working directory of the web framework.

This is defined in the core as the first command. It is referenced by the execution of chdir("../"); in the public index.

This should never be modified.

APPPATH
Defines the path to the app folder.

By default this relies on the ROOTPATH being already defined.

This can be changed if the folder is moved/renamed.

VIEWPATH
Defines the path to the views folder.

By default this relies on the APPPATH being already defined.

This can be changed if the folder is moved/renamed.

DEBUG_MODE
Defines the state of debug logging and drawing for the system core.

Turn this on to debug the core files.

Debug
A debug mode for the system is available in the event that a system bug is suspected. To enable debug mode change the appropriate value in the system config file.

 

UML
uml.png

Customization
If customization are performed to the system core a few guidelines must be followed.

The system was originally written with NetBeans. To maintain IDE intelligence, JavaDoc comments must be used on all class variables to define the class the variable "should" represent. Without these comments, auto-completion is severely hindered. If another IDE is transitioned to, add additional appropriate comments for the respective IDE.
System
Installation
Requirements
PHP 7.0+
URL Rewrite must be enabled on web server
Windows client or server operating system
Powershell AD-Module must be available
Install
Windows Server
Windows 10
System
Constants
Description
Constants are static variables that are available through an execution thread of a PHP app.

The system uses constants to define resource paths within the application.

Constants should only be defined in the system config file.

The following constants are defined.

ROOTPATH
Defines the root working directory of the application

This should never be modified.

APPPATH
Defines the path to the app folder.

By default this relies on the ROOTPATH being already defined.

This can be changed if the folder is moved/renamed.

VIEWPATH
Defines the path to the views folder.

By default this relies on the APPPATH being already defined.

This can be changed if the folder is moved/renamed.

DEBUG_MODE
Defines the state of debug logging and drawing for the system core.

Turn this on to debug the core files.

System
Request
System
Directory Structure
There currently is no structure, just a single system root folder.

System
Parser
The Parser class is the link between the app and the core output. By using this class's view function, the contents of any file can be executed and displayed.

 

Usage
Because echo from within the app is not allowed, unless the system is in debug mode, the return value from the view function must be returned up the tree to the App outputBody variable. It is then combined with the desired layout.

 

When within a view you can use echo anywhere, and the objects within the controller will be available to the view. If you want to show another view within a view use echo $this->view('viewname');, there is no limit to nesting of this command.

App
App
Introduction
App
Directory Structure
app/
    config/
          MasterConfig
          ADConfig
          AppConfig
          DistrictConfig
          EmailConfig
          GoogleConfig
          Router
          WebConfig
    controllers/
    models/
    views/
         homepage
         layouts/
         
App
Tutorial
App
Config
App
Themes
Themes are controlled by the Theme class which hold constant values of what the theme prefix is called.

The theme prefix is then used on public facing css files in the format of [color]_theme.css

App
Router
What is a router?
The router class takes the incoming request (eg: http://localhost/test/show/all) and breaks it apart into a "routable" path for the app. In the example provided, the app would use the class named "test", create an object of it, and call the method (function) show, passing the parameter of "all". Any further extensions to the requested URI are ignored.

All components of a route are optional, and are replaced with the defaults below if not provided. These defaults can be overrided in the Router.php file in app/config.

Dealing with dashes
If dashes (-) are used for an internal link the router will handle it by removing the dash and capitalizing the next letter. It will do this for both the controller and the method. For example, /account-status would become accountStatus.

 Default Parameter
If no parameter is supplied, none is given to the called class->method.

Default Method
If no method is supplied, index will be used, unless otherwise specified.

Default Class
If no class is supplied, the Home class is used, unless otherwise specified.

App
Controllers
What is a controller?
The controller is a class (collection of functions and data) that work as the first part of a URI. It must have an index method or it will be invalid. The if the router found a method to call the app runs that method instead.

How do I use one?
App
Models
What is a model?
Models are just more classes that represent "things" you are manipulating in the app. Think of it like a workshop where you send some data to feed the workers and they spit out a perfectly polished ready to go product. When you need to work on things like the User ADUser's GAUser's anything that represents a collection of data, it is wise to use or create a model for it.

How do I use one?
App
Views
What is a view?
Views are one of two things. Templates or content.

Views connect the application data to the output the user will eventually see. The entire application is available to an app view (different for system views) so any public data is readily accessible, but keep the necessary data inside the controller method that is rendering the view to keep scopes small.

Examples
The HTML start and end tags along with the Bootstrap 4 CSS framework is loaded through a template in the system views folder.

The navigation menu is a template that pulls data from the Menu object that renders it.

The homepage is a content segment that contains only div's. It is loaded in-between the app header and footer templates.

How do I use one?
Keep as much processing in the controller as possible, avoid putting heavy logic/calculation in the views themselves.

Source Code
https://github.com/jacobsen9026/School-Accounts-Manager































You can use the [editor on GitHub](https://github.com/jacobsen9026/MicroMVC/edit/master/docs/index.md) to maintain and preview the content for your website in Markdown files.

Whenever you commit to this repository, GitHub Pages will run [Jekyll](https://jekyllrb.com/) to rebuild the pages in your site, from the content in your Markdown files.

### Markdown

Markdown is a lightweight and easy-to-use syntax for styling your writing. It includes conventions for

```markdown
Syntax highlighted code block

# Header 1
## Header 2
### Header 3

- Bulleted
- List

1. Numbered
2. List

**Bold** and _Italic_ and `Code` text

[Link](url) and ![Image](src)
```

For more details see [GitHub Flavored Markdown](https://guides.github.com/features/mastering-markdown/).

### Jekyll Themes

Your Pages site will use the layout and styles from the Jekyll theme you have selected in your [repository settings](https://github.com/jacobsen9026/MicroMVC/settings). The name of this theme is saved in the Jekyll `_config.yml` configuration file.

### Support or Contact

Having trouble with Pages? Check out our [documentation](https://help.github.com/categories/github-pages-basics/) or [contact support](https://github.com/contact) and we’ll help you sort it out.
