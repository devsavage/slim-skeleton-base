# Slim Skeleton Base
Base skeleton app using the Slim framework. This project currently supports Slim v4.

Be sure to check the wiki for more information on setup and options.

## Features

- Structured to easily create and manage routes using controllers and views
- Command based controller and view creation
- Easily add modules to enable more features, such as authentication
- Configurable via bootstrap, config and routes files

## Installation
In your web server root directory, run the following command, where destination path is the name of your project
```sh
composer create-project savagedev/slim-skeleton-base [DESTINATION PATH]
```
Point your web server to the public folder in your project and enjoy!

This project will support Apache out of the box. Nginx will require a bit more configuration to pass traffic through the index.php file.

## License
MIT
