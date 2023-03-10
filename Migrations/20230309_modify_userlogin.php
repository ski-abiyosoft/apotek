<?php

require "Database.php";

$date     = date("Y-m-d");
$password = md5("B15m1ll4h@!");

$query_string = "INSERT INTO userlogin (uidlogin, pwdlogin, username, insertdate, insertby, 
updatedate, locked, level, website, avatar, cabang, pegawai_id, user_level, job_role, shift)
VALUES ('sysadmin', '{$password}', 'System Administrator', '{$date}', 'SYSTEM', '{$date}', 0, 10, 
'https://demo.sistemkesehatan.id/', 'foto.jpg', 'ABI', 1, 1, 1, 0)";

Database::exec($query_string);