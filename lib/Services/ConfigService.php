<?php

namespace App\Services;

use Leaf\App;
use Leaf\Db;

class ConfigService
{
  private const CONFIG_FILE_DEFAULT = "config.default.php";
  private const CONFIG_FILE_USER = "config.php";

  private function getConfigFilePath(string $filename): string
  {
    return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . $filename;
  }

  public function hasUserConfig(): bool
  {
    return file_exists($this->getConfigFilePath(self::CONFIG_FILE_USER));
  }

  public function getConfig(): ?array
  {
    $userConfigFile = $this->getConfigFilePath(self::CONFIG_FILE_USER);
    if (file_exists($userConfigFile)) {
      include_once($userConfigFile);
    } else {
      $defaultConfigFile = $this->getConfigFilePath(self::CONFIG_FILE_DEFAULT);
      if (file_exists($defaultConfigFile)) {
        include_once($defaultConfigFile);
      }
    }

    if (is_array($config)) {
      return $config;
    }

    return null;
  }

  public function saveUserConfig(array $config): bool
  {
    $userConfigFile = $this->getConfigFilePath(self::CONFIG_FILE_USER);
    if (file_exists($userConfigFile)) {
      // not allowed to overwrite existing file
      return false;
    }

    $content = "<?php\n";
    foreach ($config as $key => $value) {
      $content .= "\$config[\"$key\"]=\"$value\";\n";
    }

    return file_put_contents($userConfigFile, $content) !== false;
  }

  public function initDatabaseTables(Db $db): void
  {
    $db->createTableIfNotExists(
      "users",
      [
        "id" => "int(11) NOT NULL AUTO_INCREMENT",
        "name" => "varchar(255) DEFAULT NULL",
        "login" => "varchar(255) NOT NULL",
        // "salt" => "varchar(36) NOT NULL DEFAULT (UUID())",
        "password" => "varchar(255) DEFAULT NULL",
        "created" => "timestamp NOT NULL DEFAULT current_timestamp()",
        "PRIMARY KEY" => "(id)",
      ]
    )->execute();
  }
}
