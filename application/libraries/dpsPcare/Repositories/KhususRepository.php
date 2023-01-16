<?php

if (!class_exists("Repository.php")) {
    require_once APPPATH . "libraries/dpsAccounting/Repositories/Repository.php";
}

class KhususRepository extends Repository
{
    protected $table = "bpjs_pcare_khusus";
}