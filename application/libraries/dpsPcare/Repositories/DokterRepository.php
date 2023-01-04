<?php

require_once(APPPATH . "libraries/dpsAccounting/Repositories/Repository.php");

class DokterRepository extends Repository
{
    protected $table = "bpjs_pcare_dokter";
}