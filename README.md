# PHP Starter Template

This is the starter template for my PHP projects.  
_Simply use it as a base and delete everything you don't need._

* Designed to be used with
  [Git](https://git-scm.com/downloads),
  [NodeJS (npm)](https://nodejs.org/en/download/prebuilt-installer) and
  [Visual Studio Code](https://code.visualstudio.com/download)
  on Windows.

* All other development tools can be installed locally with the single `npm install` command:

  * [PHP8](https://windows.php.net/downloads/releases/archives/) with development webserver
  * [Composer](https://getcomposer.org/download/) (PHP dependency manager)
  * [MariaDB](https://mariadb.org/) (compatible to MySQL)
  * [phpMyAdmin](https://www.phpmyadmin.net/) (database management web UI)
  * [Leaf CLI](https://leafphp.dev/docs/cli/)

* Easy cleanup of all local dev tools to save disk space (`npm run uninstaller`).

* Tested on Win10/11-x64 and Win11-arm64 (e.g. "Copilot PC").

Included PHP frameworks:

* [Leaf](https://leafphp.dev/docs/introduction/) (Web API, MVC and beyond)
* [Flintstone](https://github.com/fire015/flintstone) (file based key-value store)

Have fun!  
_/Mischa_

## Setup

* Install
  [Git](https://git-scm.com/downloads),
  [NodeJS](https://nodejs.org/en/download/prebuilt-installer) and
  [Visual Studio Code](https://code.visualstudio.com/download)
* Clone the GitHub repo `https://github.com/ramdacxp/php-starter` in VSCode.
* Open the created local folder in VSCode and confirm the installation of all recommended extensions.
* Install remaining tooling by executing `npm install`.

```txt
npm install

> php-starter@0.2.0 installer fast
> node .\node-scripts\installer.js fast

  ____  _  _  ___  ____    __    __    __    ____  ____ 
 (_  _)( \( )/ __)(_  _)  /__\  (  )  (  )  ( ___)(  _ \
  _)(_  )  ( \__ \  )(   /(  )\  )(__  )(__  )__)  )   /
 (____)(_)\_)(___/ (__) (__)(__)(____)(____)(____)(_)\_)

Execute with npm run installer with any combination of
the following arguments: fast php db admin composer.
Installs all tools by default.

Installer is running in fast mode.

Installing PHP 8.3.11 ...
Fast mode uses cache: C:\Users\Mischa\Source\Repos\php-starter\bin\php.zip
Cleanup directory C:\Users\Mischa\Source\Repos\php-starter\bin\php ...    
Unzipping C:\Users\Mischa\Source\Repos\php-starter\bin\php.zip ...
Unzipped to: C:\Users\Mischa\Source\Repos\php-starter\bin\php
Config file C:\Users\Mischa\Source\Repos\php-starter\bin\php\php.ini created.
Start script C:\Users\Mischa\Source\Repos\php-starter\bin\php.cmd created.   
Installation of PHP was successful.

...
```

## Available Scripts

| Command                         | Description                                                     |
|---------------------------------|-----------------------------------------------------------------|
| `npm i`, `npm install`          | Install all tools                                               |
| `tools`                         | Adapt `%PATH%` in current shell to find all tools               |
| `npm start`                     | Start database and development web servers                      |
| `npm run installer <tool> ...`  | Individual tool install: `php`, `db`, `composer` and/or `admin` |
| `npm run installer fast <tool>` | Individual tool install without download (if cached)            |
| `npm run uninstaller`           | Cleanup installed tools and database data                       |
| `npm run uninstaller all`       | Full cleanup including `node_modules` folder                    |
| `npm run compose <command>`     | Run the given Composer `command`                                |
| `npm run serve`                 | Start the PHP development webserver                             |
| `npm run db`                    | Start the Maria DB                                              |
| `npm run admin`                 | Start the webserver for the database dashboard phpMyAdmin       |

## Usage

Use `npm start` to spin up the database and development web servers.

Open the main website at <http://localhost:8080>, which shows a simple WebAPI response:

```json
{
  "message": "Hello World!"
}
```

A dynamic greeting is returned on <http://localhost:8080/hello/mischa>:

```json
{
  "message": "Hello Mischa!"
}
```

The [Leaf DevTools](https://leafphp.dev/modules/devtools/) are available at <http://localhost:8080/devtools>.

The PhpMyAdmin MariaDB Dashboard is available at <http://localhost:8181>.

## Command Shell

Prepare each instance of a `CMD` shell with the script `tools.cmd` like this:

```cmd
❯ tools
Setting up environment:
- PATH already contains C:\Users\Mischa\Source\Repos\leaf\bin
- PATH already contains C:\Users\Mischa\Source\Repos\leaf\vendor\bin

Tools available in this shell:
- composer
- php
- leaf
- php-parse
- psysh
- var-dump-server
```

Now `php`, the `leaf` CLI and `composer` are available.

```txt
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 2.8.1 2024-10-04 11:31:01

 _              __    ___ _    ___ 
| |   ___ __ _ / _|  / __| |  |_ v2.13.0
| |__/ -_) _` |  _| | (__| |__ | |
|____\___\__,_|_|    \___|____|___|
```

## License

[MIT](LICENSE)
