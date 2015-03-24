<?php
class SetupServiceTables extends Migration {
  
  function change() {
    $this->create_table("service", function ($table) {
      $table->reference("userID", "-1");
      $table->timecreated();
      $table->tinyint("deleted", "0");
    });

    $this->create_table("serviceMeta", function ($table) {
      $table->reference("serviceID");
      $table->reference("vehicleID");
      $table->string("key", 1024);
      $table->add_index("key");
      $table->string("value", 1024);
      $table->timecreated();
      $table->reference("userID", "-1");
	 
    });

  }

}