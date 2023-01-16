<?php

if (!class_exists("Repository")) {
    require APPPATH . "libraries/dpsAccounting/Repositories/Repository.php";
}

class SpesialisRepository extends Repository
{
    protected $table = "bpjs_pcare_spesialis";
}