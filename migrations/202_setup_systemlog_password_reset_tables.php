<?php
class SetupSystemlogPasswordResetTables extends Migration {
  
  function change() {
    $this->create_table("systemLog", function ($table) {
      $table->string("user", 256);
      $table->string("tag", 256);
      $table->string("message", 1024);
      $table->timecreated();
    });

    $this->create_table("password_reset", function ($table) {
      $table->string("link", 1024);
      $table->reference("user_id");
      $table->string("ip", 256);
      $table->timestamp("time_generated");
      $table->timestamp("time_expire");
	 
    });

  }

}