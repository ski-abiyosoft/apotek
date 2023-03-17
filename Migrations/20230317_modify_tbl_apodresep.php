<?php

require "Database.php";

$query_string = "ALTER TABLE tbl_apodresep ADD cetak int NULL";

Database::exec($query_string);