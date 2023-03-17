<?php

require "Database.php";

$query_string = "ALTER TABLE tbl_apohpolog ADD alasan text";

Database::exec($query_string);