#!/usr/bin/php
<?php
/* This is the CLI entry point for the ProDB tool,
  which provides DB facilities to the Recon Advisor Project

  */

require "Migration.php";

class ProDB {
  public static $bootstrap_file = "bootstrap.php";

  public static function check_args_present($argv) {
    if (count($argv) <= 1) {
      echo "Usage: prodb [cmd]\n";
      die();
    }
  }

  private static function unable_to_load() {
    echo "Unable to find " .ProDB::$bootstrap_file . "\n";
    die();
  }

  public static function load_site($directory) {
    $dir_components = explode("/", $directory);
    if (count($dir_components) == 1)
      return ProDB::unable_to_load();
    $file = join(DIRECTORY_SEPARATOR, array($directory, ProDB::$bootstrap_file));
    if (file_exists($file)) {
      require $file;
      return;
    } else {
      array_pop($dir_components);
      ProDB::load_site(join(DIRECTORY_SEPARATOR, $dir_components));
    }
  }

  public static function cli_behavior($argv) {
    ProDB::check_args_present($argv);

    $action = $argv[1];

    if ($action == "migrate") {
      $database = $argv[2];
      Migration::run_all("./migrations", $database, true);
    }
  }

}

ProDB::cli_behavior($argv);

ProDB::load_site(getcwd());
