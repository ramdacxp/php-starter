const fs = require("fs");
const path = require("path");
const chalk = require("chalk");

const binPath = path.join(__dirname, "..", "bin");
const vendorPath = path.join(__dirname, "..", "vendor");
const nodePath = path.join(__dirname, "..", "node_modules");

function cleanupDir(dirPath) {
  if (fs.existsSync(dirPath)) {
    console.log(`Cleanup directory ${chalk.red(dirPath)} ...`);
    fs.rmSync(dirPath, { recursive: true, force: true });
  }
}

async function main() {
  try {
    console.log(
      chalk.magenta(` __  __  _  _  ____  _  _  ___  ____    __    __    __    ____  ____
(  )(  )( \\( )(_  _)( \\( )/ __)(_  _)  /__\\  (  )  (  )  ( ___)(  _ \\
 )(__)(  )  (  _)(_  )  ( \\__ \\  )(   /(  )\\  )(__  )(__  )__)  )   /
(______)(_)\\_)(____)(_)\\_)(___/ (__) (__)(__)(____)(____)(____)(_)\\_)
`)
    );

    console.log(`Execute with ${chalk.blue("npm run uninstaller")}.`);
    console.log(
      `Pass argument ${chalk.blue("all")} to remove node_modules as well.\n`
    );

    const args = process.argv.slice(2);
    const allMode = args.includes("all");

    cleanupDir(binPath);
    cleanupDir(vendorPath);
    if (allMode) {
      cleanupDir(nodePath);
    }

    console.log(chalk.green("Uninstallation was successful."));
  } catch (error) {
    console.error(chalk.red("ERROR:", error));
  }
}

main();
