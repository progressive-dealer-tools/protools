<?php
class SetupVehicleTables extends Migration {
  
  function change() {
    $this->create_table("vehicles", function ($table) {
      $table->reference("userID", "-1");
      $table->timecreated();
      $table->tinyint("deleted", "0");
      $table->add_index("deleted");
      $table->tinyint("initialImport");
    });

    $this->create_table("vehicleMeta", function ($table) {
      $table->reference("vehicleID");
      $table->add_index("vehicleID");

      $table->string("key", 1024);
      $table->add_index("key");

      $table->string("value", 1024);
      $table->add_index("value");

      $table->timecreated();
      $table->add_index('timecreated');
      
      $table->reference("userID", "-1");
      $table->tinyint("current");
      $table->int("last");


    });

  }

}