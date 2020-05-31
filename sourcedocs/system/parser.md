# Parser

The Parser class is the link between the app and the core output. By using this class's **view** function, the contents of any file can be executed and displayed.

## Usage

Because echo from within the app is not allowed, unless the system is in debug mode, the return value from the **view** function must be returned up the tree to the App outputBody variable. It is then combined with the desired layout.

 

When within a view you can use echo anywhere, and the objects within the controller will be available to the view. If you want to show another view within a view use `echo $this->view('viewname');`, there is no limit to nesting of this command.