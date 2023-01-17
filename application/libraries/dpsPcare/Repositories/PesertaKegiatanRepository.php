<?php

if (!class_exists("Repository")) {
    require APPATH . "libraries/dpsAccounting/Respositories/Repository.php";
}

class PesertaKegiatanRepository extends Repository
{
    protected $table = "bpjs_pcare_kelompok_peserta";
}