const unzipper = require("unzipper");
const fs = require("fs");
const path = require("path");
const chalk = require("chalk");
const { spawn } = require("child_process");

const binPath = path.join(__dirname, "..", "bin");

async function downloadFile(url, outputPath) {
  console.log(`Downloading ${chalk.blue(url)} ...`);
  const fetch = (await import("node-fetch")).default;
  const response = await fetch(url);
  const fileStream = fs.createWriteStream(outputPath);
  return new Promise((resolve, reject) => {
    response.body.pipe(fileStream);
    response.body.on("error", reject);
    fileStream.on("finish", () => {
      console.log(`Downloaded to: ${chalk.blue(outputPath)}`);
      resolve();
    });
  });
}

function createDir(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
}

async function unzipFile(zipPath, extractPath) {
  console.log(`Unzipping ${chalk.blue(zipPath)} ...`);
  return new Promise((resolve, reject) => {
    fs.createReadStream(zipPath)
      .pipe(unzipper.Extract({ path: extractPath }))
      .on("close", () => {
        console.log(`Unzipped to: ${chalk.blue(extractPath)}`);
        resolve();
      })
      .on("error", reject);
  });
}

function moveSubDir(srcDir, destDir) {
  const subDirs = fs
    .readdirSync(srcDir, { withFileTypes: true })
    .filter((dirent) => dirent.isDirectory());
  if (subDirs.length >= 1) {
    const subDirPath = path.join(srcDir, subDirs[0].name);
    console.log(`Moving ${chalk.blue(subDirPath)} ...`);
    fs.renameSync(subDirPath, destDir);
    console.log(`Moved to: ${chalk.blue(destDir)}`);
  }
}

function cleanupFile(filePath) {
  if (fs.existsSync(filePath)) {
    console.log(`Cleanup temp. file ${chalk.red(filePath)} ...`);
    fs.unlinkSync(filePath);
  }
}

function cleanupDir(dirPath) {
  if (fs.existsSync(dirPath)) {
    console.log(`Cleanup directory ${chalk.red(dirPath)} ...`);
    fs.rmSync(dirPath, { recursive: true, force: true });
  }
}

function createAdminConfigFile(installDirPath) {
  const configFilePath = path.join(installDirPath, "config.inc.php");

  const configContent = `<?php
$i = 1;
$cfg['Servers'][$i]['auth_type']       = 'config';
$cfg['Servers'][$i]['user']            = 'root';
$cfg['Servers'][$i]['password']        = '';
$cfg['Servers'][$i]['AllowNoPassword'] = true;
`;
  fs.writeFileSync(configFilePath, configContent);
  console.log(`Config file ${chalk.blue(configFilePath)} created.`);
}

async function runCommand(command, args, workingDir) {
  console.log(`Executing ${chalk.yellow(command)} ${chalk.blue(args)} ...`);
  return new Promise((resolve, reject) => {
    const process = spawn(command, args, { cwd: workingDir });
    process.stdout.on("data", (data) => console.log(chalk.grey(data)));
    process.stderr.on("data", (data) => console.log(chalk.red(data)));
    process.on("close", (code) => {
      console.log(`Process exited with code ${chalk.blue(code)}.`);
      if (code !== 0) {
        reject(new Error(`Process exited with code ${code}`));
      } else {
        resolve();
      }
    });
  });
}

function createComposerStartScript(installDirPath) {
  const filePath = path.join(installDirPath, "composer.cmd");

  const fileContent = `@echo off
%~dp0php\\php.exe %~dp0composer\\composer.phar %*
`;
  fs.writeFileSync(filePath, fileContent);
  console.log(`Start script ${chalk.blue(filePath)} created.`);
}

function createPhpConfigFile(installDirPath) {
  const templateFilePath = path.join(installDirPath, "php.ini-development");
  const iniFilePath = path.join(installDirPath, "php.ini");

  const templateContent = fs.readFileSync(templateFilePath, "utf8");
  const configContent = `
; ===============
; Custom Settings
; ===============
extension_dir=${installDirPath}\\ext
extension=pdo_mysql
extension=pdo_sqlite

; required by phpmyadmin
extension=mysqli

; required by composer
extension=curl
extension=openssl
extension=zip
`;

  fs.writeFileSync(iniFilePath, templateContent + configContent);
  console.log(`Config file ${chalk.blue(iniFilePath)} created.`);
}

function createPhpStartScript(installDirPath) {
  const filePath = path.join(installDirPath, "php.cmd");

  const fileContent = `@echo off
%~dp0php\\php.exe %*
`;
  fs.writeFileSync(filePath, fileContent);
  console.log(`Start script ${chalk.blue(filePath)} created.`);
}

async function installPhpMyAdmin(fastMode) {
  const url =
    "https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip";
  const zipPath = path.join(binPath, "phpMyAdmin.zip");
  const tempTargetPath = path.join(binPath, "phpMyAdmin.temp");
  const targetPath = path.join(binPath, "phpMyAdmin");

  console.log(chalk.magenta("\nInstalling phpMyAdmin ..."));
  createDir(binPath);

  if (fastMode && fs.existsSync(zipPath)) {
    console.log(
      `${chalk.yellow("Fast mode")} uses cache: ${chalk.blue(zipPath)}`
    );
  } else {
    cleanupFile(zipPath);
    await downloadFile(url, zipPath);
  }

  cleanupDir(targetPath);
  cleanupDir(tempTargetPath);
  await unzipFile(zipPath, tempTargetPath);
  moveSubDir(tempTargetPath, targetPath);
  cleanupDir(tempTargetPath);
  createAdminConfigFile(targetPath);

  console.log(
    chalk.green(
      `Installation of ${chalk.magenta("phpMyAdmin")} was successful.`
    )
  );
}

async function installComposer(fastMode) {
  const url =
    "https://raw.githubusercontent.com/composer/getcomposer.org/11cb825ad3d659a4f63fe591226b8f3545897914/web/installer";
  const composerPath = path.join(binPath, "composer");
  const setupPath = path.join(composerPath, "composer-setup.php");
  const toolPath = path.join(composerPath, "composer.phar");
  const scriptPath = path.join(binPath, "composer.cmd");
  const phpPath = path.join(binPath, "php", "php.exe");

  console.log(chalk.magenta("\nInstalling Composer ..."));
  createDir(composerPath);

  if (fastMode && fs.existsSync(setupPath)) {
    console.log(
      `${chalk.yellow("Fast mode")} uses cache: ${chalk.blue(setupPath)}`
    );
  } else {
    cleanupFile(setupPath);
    await downloadFile(url, setupPath);
  }

  cleanupFile(toolPath);
  await runCommand(phpPath, [setupPath], composerPath);

  cleanupFile(scriptPath);
  createComposerStartScript(binPath);

  console.log(
    chalk.green(`Installation of ${chalk.magenta("Composer")} was successful.`)
  );
}

async function installPhp(fastMode) {
  const version = "8.3.11";
  const url = `https://windows.php.net/downloads/releases/archives/php-${version}-Win32-vs16-x64.zip`;
  const zipPath = path.join(binPath, "php.zip");
  const toolPath = path.join(binPath, "php");

  console.log(chalk.magenta(`\nInstalling PHP ${version} ...`));
  createDir(binPath);

  if (fastMode && fs.existsSync(zipPath)) {
    console.log(
      `${chalk.yellow("Fast mode")} uses cache: ${chalk.blue(zipPath)}`
    );
  } else {
    cleanupFile(zipPath);
    await downloadFile(url, zipPath);
  }

  cleanupDir(toolPath);
  await unzipFile(zipPath, toolPath);

  createPhpConfigFile(toolPath);
  createPhpStartScript(binPath);

  console.log(
    chalk.green(`Installation of ${chalk.magenta("PHP")} was successful.`)
  );
}

async function installDatabase(fastMode) {
  const version = "11.7.0";
  const url = `https://mirror.wtnet.de/mariadb/mariadb-${version}/winx64-packages/mariadb-${version}-winx64.zip`;
  const zipPath = path.join(binPath, "mariadb.zip");
  const toolPath = path.join(binPath, "db");
  const tempPath = path.join(binPath, "db.temp");
  const installToolFilename = path.join(
    toolPath,
    "bin",
    "mysql_install_db.exe"
  );

  console.log(chalk.magenta(`\nInstalling MariaDB ${version} ...`));
  createDir(binPath);

  if (fastMode && fs.existsSync(zipPath)) {
    console.log(
      `${chalk.yellow("Fast mode")} uses cache: ${chalk.blue(zipPath)}`
    );
  } else {
    cleanupFile(zipPath);
    await downloadFile(url, zipPath);
  }

  cleanupDir(tempPath);
  await unzipFile(zipPath, tempPath);
  cleanupDir(toolPath);
  moveSubDir(tempPath, toolPath);
  cleanupDir(tempPath);

  await runCommand(installToolFilename, [], toolPath);

  console.log(
    chalk.green(`Installation of ${chalk.magenta("MariaDB")} was successful.`)
  );
}

async function main() {
  try {
    console.log(
      chalk.magenta(`  ____  _  _  ___  ____    __    __    __    ____  ____
 (_  _)( \\( )/ __)(_  _)  /__\\  (  )  (  )  ( ___)(  _ \\
  _)(_  )  ( \\__ \\  )(   /(  )\\  )(__  )(__  )__)  )   /
 (____)(_)\\_)(___/ (__) (__)(__)(____)(____)(____)(_)\\_)
`)
    );

    console.log(
      `Execute with ${chalk.blue("npm run installer")} with any combination of`
    );
    console.log(
      `the following arguments: ${chalk.blue("fast php db admin composer")}.`
    );
    console.log(`Installs all tools by default.`);

    const args = process.argv.slice(2);

    const fastMode = args.includes("fast");
    if (fastMode)
      console.log(`\nInstaller is running in ${chalk.yellow("fast mode")}.`);

    let instAdmin = args.includes("admin");
    let instPhp = args.includes("php");
    let instDb = args.includes("db");
    let instComposer = args.includes("composer");
    if (!instAdmin && !instPhp && !instDb && !instComposer) {
      // activate all parts if none of them was given on cmd line
      instAdmin = true;
      instPhp = true;
      instDb = true;
      instComposer = true;
    }

    if (instPhp) await installPhp(fastMode);
    if (instDb) await installDatabase(fastMode);
    if (instComposer) await installComposer(fastMode);
    if (instAdmin) await installPhpMyAdmin(fastMode);
  } catch (error) {
    console.error(chalk.red("ERROR:", error));
  }
}

main();
