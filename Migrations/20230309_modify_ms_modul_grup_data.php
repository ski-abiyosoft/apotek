<?php

require "Database.php";

$query_string = "INSERT INTO ms_modul_grupd (nomor_grup, nomor_modul) values (22, 1209)";

Database::exec($query_string);