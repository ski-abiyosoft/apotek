<?php

require "Database.php";

$query_string = "DROP TABLE IF EXISTS `tbl_aspektelaah`;";

Database::exec($query_string);

$query_string = "CREATE TABLE `tbl_aspektelaah`  (
    `kode` int NOT NULL,
    `aspek` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
  ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;";

Database::exec($query_string);

$query_string = "INSERT INTO `tbl_aspektelaah` VALUES (1, 'KELENGKAPAN PENULISAN RESEP'), 
(2, 'KEJELASAN PENULISAN RESEP'),
(3, 'BENAR PASIEN'),
(4, 'BB PASIEN'),
(5, 'NAMA, BENTUK, KEKUATAN, JUMLAH OBAT'),
(6, 'SIGNA/ATURAN PAKAI'),
(7, 'BENAR OBAT'),
(8, 'BENAR DOSIS'),
(9, 'BENAR RUTE'),
(10, 'BENAR WAKTU'),
(11, 'DUPLIKASI'),
(12, 'INTERAKSI OBAT'),
(13, 'KONTRA INDIKASI'),
(14, 'BENAR OBAT'),
(15, 'BENAR PASIEN'),
(16, 'BENAR DOSIS'),
(17, 'BENAR CARA PEMBERIAN/RUTE'),
(18, 'BENAR ATURAN PAKAI'),
(19, 'DOBEL CEK'),
(20, 'NAMA OBAT'),
(21, 'KEGUNAAN'),
(22, 'CARA MINUM/PAKAI'),
(23, 'EFEK SAMPING'),
(24, 'INTERAKSI');";

Database::exec($query_string);