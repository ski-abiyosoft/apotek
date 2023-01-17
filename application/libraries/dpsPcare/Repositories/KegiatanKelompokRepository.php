<?php

if (!class_exists("Repository")) {
    require APPATH . "libraries/dpsAccounting/Respositories/Repository.php";
}

class KegiatanKelompokRepository extends Repository
{
    protected $table = "bpjs_pcare_kelompok";
}