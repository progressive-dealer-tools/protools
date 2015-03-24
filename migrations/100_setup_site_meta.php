<?php
class SetupSiteMeta extends Migration {
  
  function change() {
    $this->create_table("siteMeta", function ($table) {
      $table->string("key", 1024);
      $table->string("value", 1024);
      $table->timecreated();
    });

  }

}