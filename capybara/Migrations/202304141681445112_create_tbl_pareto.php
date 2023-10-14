<?php 

use Facades\Schema;

return new Class {
    public function up()
    {
        Schema::new('tbl_pareto', function ($table) {
            $table->id();
            $table->string('kode_pareto',20);
            $table->string('kodebarang',20);
            $table->string('namabarang',50);
            $table->string('satuan',10);
            $table->decimal('saldo', 10,2);
            $table->decimal('qty_rencana', 10,2);
            $table->datetime('tanggal');
            $table->string('vendor_id', 10);
            $table->integer('status', 1);
        });
    }
};