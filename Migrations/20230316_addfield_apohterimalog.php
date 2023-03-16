<?php

require "Database.php";

$date     = date("Y-m-d");
$password = md5("B15m1ll4h@!");

// add new field "alasan" type text -> length (no), null (yes)
$query_string = "ALTER TABLE tbl_apohterimalog ADD alasan text";

Database::exec($query_string);