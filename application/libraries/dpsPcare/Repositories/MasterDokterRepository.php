<?php

if (!class_exists("Repository")) {
    require APPPATH .  "libraries/dpsAccounting/Repositories/Repository.php";
}

class MasterDokterRepository extends Repository
{
    protected $table = "tbl_dokter";
}