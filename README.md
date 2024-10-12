# PHP Starter Template

This is the starter template for my PHP projects.  
_Simply use it as a base and delete everything you don't need._

* Designed to be used with Git, NodeJS and VSCode on Windows
* All other development tools are installed locally only:
  * PHP 8 with dev webserver
  * Composer (PHP dependency manager)
  * Leaf CLI
  * <strike>Maria DB (as replacement for MySQL)</strike>
* Easy cleanup of local dev tools to save disk space

Included PHP frameworks:

* Leaf (Web API, MVC and beyond)
* Flintstone (file based key-value store)

Have fun!  
_/Mischa_

## Setup

* Install Git, NodeJs and VSCode.
* Clone the GitHub repo `https://github.com/ramdacxp/php-starter` in VSCode.
* Open the created local folder in VSCode and confirm the installation of all recommended extensions.
* Install dependencies by executing `npm install`.

## Available Scripts

| NPM                        | Batch                         | Description                                     |
|----------------------------|-------------------------------|-------------------------------------------------|
| `npm install`              | n.a                           | install PHP, Composer, and project dependencies |
| `npm run install-php`      | `install-php.cmd [fast]`      | download, install locally & configure PHP       |
| `npm run install-composer` | `install-composer.cmd [fast]` | download, install locally & configure Composer  |
| `npm compose`              | `composer.cmd install`        | install PHP dependencies from `composer.json`   |
| `npm run uninstall`        | `uninstall.cmd`               | remove folders `bin`, `node_modules`, `vendor`  |

Optional argument `fast` skips the download and uses cached ZIP archives from last install.

## Usage

`npm start` starts the development webserver at <http://127.0.0.1:8080/>.

  ```json
  {
    "message": "Hello World!"
  }
  ```

  The [Leaf DevTools](https://leafphp.dev/modules/devtools/) are available at <http://127.0.0.1:8080/devtools>.

`composer.cmd` starts the Composer CLI.

```txt
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 2.8.1 2024-10-04 11:31:01
```

`leaf.cmd` starts the CLI of the Leaf Framework.

```txt
 _              __    ___ _    ___ 
| |   ___ __ _ / _|  / __| |  |_ v2.13.0
| |__/ -_) _` |  _| | (__| |__ | |
|____\___\__,_|_|    \___|____|___|
```

## License

[MIT](LICENSE)
