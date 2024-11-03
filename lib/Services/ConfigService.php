<?php

namespace App\Services;

use Leaf\App;

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

  public function setAppConfig(App $app): bool
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
      $app->config("db", $config);
      return true;
    }

    return false;
  }

  public function setUserConfig(array $config): bool
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
}
