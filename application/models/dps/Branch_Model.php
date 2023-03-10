<?php

if (!class_exists('Repository')) {
    include APPPATH . '/libraries/dpsAccounting/Repositories/Repository.php';
}

class Branch_Model extends Repository
{
    protected $table = "tbl_namers";
    protected $primary_key = "koders";
}