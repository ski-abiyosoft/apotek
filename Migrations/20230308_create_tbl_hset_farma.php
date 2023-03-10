<?php

require "Database.php";

$query_string = "CREATE TABLE `tbl_hset_farma` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `koders` char(10) NOT NULL,
    `qty_obat` char(100) NOT NULL DEFAULT '0',
    `harga` decimal(10,0) NOT NULL DEFAULT 0,
    `total` decimal(10,0) DEFAULT NULL,
    `default_ctk` int(11) DEFAULT NULL,
    `ubah_harga` int(11) DEFAULT NULL,
    `jns_harga` int(11) DEFAULT NULL,
    `penentuan_harga_jual` varchar(255) DEFAULT NULL,
    `peringatan_stock` varchar(255) DEFAULT NULL,
    `update_price` decimal(10,0) DEFAULT NULL,
    `margin_rj_umum` decimal(10,0) DEFAULT NULL,
    `margin_rj_jaminan` decimal(10,0) DEFAULT NULL,
    `margin_obat_bebas` decimal(10,0) DEFAULT NULL,
    `margin_ri_umum` decimal(10,0) DEFAULT NULL,
    `margin_ri_jaminan` decimal(10,0) DEFAULT NULL,
    `margin_obat_ugd` decimal(10,0) DEFAULT NULL,
    `jasa_dr_umum` decimal(10,0) DEFAULT NULL,
    `jasa_dr_spesialis` decimal(10,0) DEFAULT NULL,
    `harga_kertas` decimal(10,0) DEFAULT NULL,
    `harga_kapsul` decimal(10,0) DEFAULT NULL,
    `uang_r` decimal(10,0) DEFAULT NULL,
    `uang_racik` decimal(10,0) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tbl_barangsetup_aponame` (`qty_obat`),
    KEY `tbl_barangsetup_apogroup` (`harga`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci";

Database::exec($query_string);