<?php

require "Database.php";

$query_string = "DROP TABLE IF EXISTS `tbl_apotelaah`;";

Database::exec($query_string);

$query_string = "CREATE TABLE `tbl_apotelaah`  (
    `orderno` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `kode` int NOT NULL,
    `aspek` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `ok` tinyint NOT NULL,
    `resepno` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
  ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;";

Database::exec($query_string);