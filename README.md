# PHP Starter Template

This is the starter template for my PHP projects.  
_Simply use it as a base and delete everything you don't need._

* Designed to be used with Git, NodeJS and VSCode on Windows
* All other development tools are installed locally only
  * PHP 8 with dev webserver
  * Maria DB (as replacement for MySQL)
  * Composer (PHP dependency manager)
  * Leaf CLI
* Easy dependency cleanup by deleting `bin` and `node_modules`

Included PHP frameworks:

* Leaf (Web API, MVC and beyond)
* Flintstone (file based key-value store)

Have fun!  
/Mischa

## Setup

* Install Git, NodeJs and VSCode.
* Clone the GitHub repo `https://github.com/ramdacxp/php-starter` in VSCode.
* Open the created local folder in VSCode and confirm the installation of all recommended extensions.
* Install dependencies by executing `npm install`.

## Available Scripts

* `npm install` - run all initial installations
* `install-php [fast]` - download, install & configure PHP8
* `install-composer [fast]` - download, install & configure PHP Composer
* `composer install` - installs PHP dependencies from `composer.json`
* `uninstall` - removes the `bin` folder

Optional argument `fast` skips the download and uses the cached ZIP.

## Usage

* `npm start` - starts the development webserver at <http://127.0.0.1:8080/>.

  ```json
  {
    "message": "Hello World!"
  }
  ```

  The [Leaf DevTools](https://leafphp.dev/modules/devtools/) are available at <http://127.0.0.1:8080/devtools>.

* `composer` - Composer CLI
* `leaf` - Leaf CLI
