<?php

if (!class_exists("Repository")) {
    require_once APPPATH . "libraries/dpsAccounting/Repositories/Repository.php";
}

class MasterTarifHeaderRepository extends Repository
{
    protected $table = "tbl_tarifh";
}