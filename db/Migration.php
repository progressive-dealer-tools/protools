<?php

require "MigrationTable.php";

class Migration {
  public static $host_env_var = "RA_DB_HOST";
  public static $password_env_var = "RA_DB_PASSWORD";
  public static $user_env_var = "RA_DB_USER";

  public static $migration_table = "RA_Migrations";


  private $database;
  private $DBH;
  private $mysqli;
  private $host;
  private $user;
  private $password;


  private $migration_file;
  private $expected_class_name;
  private $parse_filename;
  private $full_path;
  private $directory;

    /* An instance of Migration loads a subclass, so only the parent
       should have run called, or else we will infinite loop. The
       parent object should call the change() method on an instance of the
       Migration Subclass. 
    */
  function __construct($db = "" , $directory = false, $migration_file = false, $bootstrap = false) {
    if ($db instanceof PDO) {
      $this->DBH = $db;
    } else {
      $this->database = $db;

      $this->host = getenv(Migration::$host_env_var);
      $this->password = getenv(Migration::$password_env_var);
      $this->user = getenv(Migration::$user_env_var);
      $this->connect_to_db();



       if(!empty($db))  {
        $this->verify_migration_table_exists($bootstrap);

        $this->directory = $directory;
        $this->full_path = $directory . DIRECTORY_SEPARATOR . $migration_file;
        $this->migration_file = $migration_file;
        $this->parse_filename($migration_file);
      } 

      if (!is_subclass_of($this, "Migration") && $directory && $migration_file) {
        $this->run();
      }
    }

  }

  function already_run() {
    return $this->DBH->query("SELECT id FROM `" . Migration::$migration_table . "` WHERE `migration` = " . $this->migration_id)->rowCount() > 0; 
  }

  function run() {
    if (! $this->already_run()) {
       echo "Running: $this->full_path \n";

      require_once $this->full_path;
      $obj = new $this->expected_class_name($this->DBH);

      try {
        $this->DBH->beginTransaction();
        $obj->change();
        $this->DBH->commit();
        $this->change_database($this->database);
      } catch (Exception $e) {
        echo "Rolling back transaction (not some statements cannot be rolledback in MYSQL) \n";
        $this->DBH->rollback();
        throw $e;
      }
      $this->DBH->query("INSERT INTO `" . Migration::$migration_table . "` (migration) VALUES ('" . $this->migration_id . "')"); 
    }
  }

  function parse_filename($migration_file) {
    $file_name_array = preg_split( "/[_\.]+/", $migration_file);
    $id = $file_name_array[0];

    if (!is_numeric($id))
        throw new RuntimeException("migration id not numeric " . $id);
    $this->migration_id = $id;
    //Remove the migration id and '.php'
    array_shift($file_name_array);
    array_pop($file_name_array);
    #Upper case first letter of all words
    $file_name_array  = array_map(function($s) { return ucfirst($s); }, $file_name_array ); 
    $this->expected_class_name = join($file_name_array);
  }

  public static function migration_files($directory) {
    $files = array_diff(scandir($directory), array('..', '.'));
    $files = array_filter($files, function($file) {
      $path = pathinfo($file);
      return $path['extension'] == 'php';
    });
    return $files;
  }

  public static function run_all($directory, $database, $bootstrap = false) {
    try {
      $files = Migration::migration_files($directory);
      sort($files);
      foreach ($files as $migration_file) {
        $migration = new Migration($database, $directory, $migration_file, $bootstrap);
      }
    } catch (Exception $e) {
      echo "Fault in migration, terminating remaining migrations\n";
      echo $e->getTraceAsString();
      throw $e;
    }
  }

  function create_migration_table() {
    $this->create_table(Migration::$migration_table, function ($table) {
      $table->string("migration");
      $table->timecreated();

    });
  }

  function connect_to_db() {
    $this->verify_database_settings();
    try {
    # MySQL with PDO_MYSQL
      $this->DBH = new PDO("mysql:host=".$this->host.";dbname=" .
                             $this->database, $this->user, $this->password);

      $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
  }

  function table_exists($table) {
    return $this->DBH->query("SHOW TABLES LIKE '".$table."'")->rowCount() > 0; 
  }

  function verify_migration_table_exists($bootstrap = false) {
    if (! $this->table_exists(Migration::$migration_table)) {
      if ($bootstrap) {
        $this->create_migration_table();
      } else {
        throw new RuntimeException(Migration::$migration_table . " migration tracking table doesn't exists");
      }
    }
  }

  function verify_field_set($field, $field_name) {
    if (empty($field))
      throw new RuntimeException($field_name . ' not set in Migration');
  }

  function verify_database_settings() {
    $this->verify_field_set($this->host, "host <".Migration::$host_env_var.">");
    $this->verify_field_set($this->user, "user <".Migration::$user_env_var.">");
  }
  
  function create_table($name, $alterTable) {
    if (!$this->table_exists($name)) {
     $this->alter_table($name, $alterTable, true);
    } else {
      echo "Skipping creation of $name, already exists.\n";
    }
  }
  function drop_table($name) {
     $this->DBH->query("DROP TABLE IF EXISTS $name");
  }
  function alter_table($name, $alterTable, $create = false) {
    $table = new MigrationTable($name, $this->DBH);
    if ($create)
      $table->create();
    $alterTable($table);
  }

  function create_database($database) {
     $this->DBH->query("CREATE DATABASE IF NOT EXISTS $database");
  }

  function drop_database($database) {
     $this->DBH->query("DROP DATABASE IF EXISTS $database");
  }

  function change_database($database) {
     $this->DBH->query("USE $database");
  }
}


