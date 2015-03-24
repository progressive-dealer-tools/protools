<?php
class SetupUserTables extends Migration {
  
  function change() {

    $this->create_table("users", function ($table) {
      $table->string("username");
      $table->timecreated();
      $table->string("password", 1024);
      $table->tinyint("deleted");
    });



    $this->create_table("userMeta", function ($table) {
      $table->reference("userID");
      $table->string("key", 1024);
      $table->string("value", 1024);
      $table->timecreated();
    });


    $this->create_table("userPermissions", function ($table) {
      $table->reference("userID");
      $table->string("key", 1024);
      $table->string("value", 1024);
      $table->timecreated();
    });




  }

}