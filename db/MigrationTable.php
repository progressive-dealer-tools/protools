<?php
class MigrationTable {
  private $name;
  private $DBH;
  function __construct($name, $DBH) {
    $this->name = $name;
    $this->DBH = $DBH;
  }
  function create() {
    $STH = $this->DBH->prepare("CREATE TABLE ".$this->name.
                        " (id INT (11) UNSIGNED AUTO_INCREMENT PRIMARY KEY)");
    $STH->execute();
  }
  function add_index($name) {
    $STH = $this->DBH->prepare("ALTER TABLE  `".$this->name."` ADD INDEX (  `".$name."` )");
    $STH->execute();
  }
  function add_Column($name, $type, $default = null) {
    if ($default == null){
      $STH = $this->DBH->prepare("ALTER TABLE `".$this->name."` ADD `$name` $type");      
    } else{
      $STH = $this->DBH->prepare("ALTER TABLE `".$this->name."` ADD `$name` $type DEFAULT $default");
    }
    $STH->execute();
  }
  function timecreated() {
    $this->add_Column("timecreated", "timestamp", "CURRENT_TIMESTAMP");
  }
  function timestamp($name) {
    $this->add_Column($name, "timestamp default 0");
  }
  function string($name, $size = 255) {
    $this->add_Column($name, "varchar($size)");
  }
  function text($name) {
    $this->add_Column($name, "text");
  }
  function int($name, $default = null) {
    $this->add_Column($name, "int(11)", $default);
  }
  function reference($name, $default= null) {
    $this->int($name, $default);
  }
  function tinyint($name, $default = null) {
      $this->add_Column($name, "tinyint(4)", $default);
  }
}