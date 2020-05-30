# Constants

Constants are static variables that are available through an execution thread of a PHP app.

The system uses constants to define resource paths within the application.

Constants should only be defined in the system config file.

The following constants are defined.

## ROOTPATH

Defines the root working directory of the web framework.

This is defined in the core as the first command. It is referenced by the execution of chdir("../"); in the public index.

This should never be modified.

## APPPATH

Defines the path to the app folder.

By default this relies on the ROOTPATH being already defined.

This can be changed if the folder is moved/renamed.

## VIEWPATH

Defines the path to the views folder.

By default this relies on the APPPATH being already defined.

This can be changed if the folder is moved/renamed.

## DEBUG_MODE

Defines the state of debug logging and drawing for the system core.

Turn this on to debug the core files.

Debug

A debug mode for the system is available in the event that a system bug is suspected. To enable debug mode change the appropriate value in the system config file.