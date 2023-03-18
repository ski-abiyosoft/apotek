<?php

require "Database.php";

$date     = date("Y-m-d");
$password = md5("B15m1ll4h@!");

// add new field "shift" type text -> length (5), null (yes)
$query_string = "ALTER TABLE tbl_kartukredit ADD shift char(5)";

Database::exec($query_string);