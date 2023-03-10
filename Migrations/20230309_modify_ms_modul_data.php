<?php

require "Database.php";

$query_string = "INSERT INTO ms_modul (kode, nama, main_menu, url, icon, lev, aktif) values (3202, 'Penjualan Obat Bebas', 3200, 'penjualan_bebas', 'fa-angle-double-right', 2, 1)";

Database::exec($query_string);