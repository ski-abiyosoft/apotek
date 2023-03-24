<?php 

use Facades\Schema;

return new Class {
    public function up()
    {
        Schema::modify('tbl_apohresep', function ($table) {
            $table->string('shift',5);
        });
    }
};