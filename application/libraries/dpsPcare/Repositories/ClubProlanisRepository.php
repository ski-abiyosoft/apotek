<?php

if (!class_exists("Repository")) {
    require APPATH . "libraries/dpsAccounting/Respositories/Repository.php";
}

class ClubProlanisRepository extends Repository
{
    protected $table = "bpjs_pcare_club_prolanis";
}