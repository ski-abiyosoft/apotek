<?php

if (!class_exists("Repository")) {
    require APPPATH . "libraries/dpsAccounting/Repositories/Repository.php";
}

class SaranaRepository extends Repository
{
    protected $table = "bpjs_pcare_sarana";
}