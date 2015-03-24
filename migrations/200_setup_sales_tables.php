<?php
class SetupSalesTables extends Migration {
  
  function change() {
    $this->create_table("sales", function ($table) {
      $table->reference("userID", "-1");
      $table->timecreated();
      $table->tinyint("deleted", "0");

    });

    $this->create_table("saleMeta", function ($table) {
      $table->reference("saleID");
      $table->reference("vehicleID");
      $table->string("key", 1024);
      $table->add_index("key");
      $table->string("value", 1024);
      $table->timecreated();
      $table->reference("userID", "-1");

    });

  }

}