<?php
class RA_Unit_Database_TestCase extends PHPUnit_Framework_TestCase
{
  //getenv("RA_ENV")  RA_DB_TEST

  public function setUp() {
    $test_db = getenv("RA_DB_TEST");
    $migrations_folder = getenv("RA_MIGRATIONS_FOLDER");
    if (empty($test_db)) {
      echo "Test database not set. Expected env var <RA_DB_TEST>\n";
      die();
    } 
    if (empty($migrations_folder)) {
      echo "Migrations folder not set. Expected env var <RA_MIGRATIONS_FOLDER>\n";
      die();
    }


    echo "Using $test_db as the test database.\n";
    echo "Using migrations from $migrations_folder.\n";
    $m = new Migration();
    $m->drop_database($test_db);
    $m->create_database($test_db);

    Migration::run_all($migrations_folder, $test_db, true);
  }

  public function tearDown() {
  }
}