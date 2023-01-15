<?php

if (!class_exists("Repository.php")) {
    require_once APPPATH . "libraries/dpsAccounting/Repositories/Repository.php";
}

class ProviderRepository extends Repository
{
    protected $table = "bpjs_pcare_provider";
}