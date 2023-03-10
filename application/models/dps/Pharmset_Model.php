<?php

if (!class_exists('Repository')) {
    include APPPATH . '/libraries/dpsAccounting/Repositories/Repository.php';
}

class Pharmset_Model extends Repository
{
    protected $table = "tbl_hset_farma";
}