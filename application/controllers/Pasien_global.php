<?php

    defined("BASEPATH") or exit("No Direct Script Access Allowed");

    Class Pasien_global extends CI_Controller{

        Public function __construct(){
            parent::__construct();
        }

        Public function list(){
            $session_check  = $this->session->userdata("is_logged_in");
            if($session_check != false){
                $unit       = $this->session->userdata("unit");

                $query      = $this->db->query("SELECT *, noreg AS no_registrasi, rekmed AS rekam_medis, CONCAT(namapas ,' ', IF(jkel = 'P', 'Pria', 'Wanita') ,' DOB ', REPLACE(tgllahir, ' 00:00:00', '')) AS nama_pasien, CONCAT(REPLACE(tglmasuk, ' 00:00:00', '') ,' ', jam) AS tangal_masuk, IF(tujuan = 1, 'RAJAL', 'RANAP') AS layanan, IF(tujuan = 1, namapost ,CONCAT('RUANG ', namaruang ,' BED ', namakamar ,' KELAS ', namakelas)) AS tujuan, IF(jenispas = 'PAS1', 'UMUM', cust_nama) AS jenis_pasien, nadokter AS dokter, nosep 
                FROM pasien_daftar
                WHERE koders = '$unit' AND keluar = 0")->result();

                if(!$query){
                    echo "No Data<br />Error : ". $this->db->error();
                } else {
                    header("Content-Type:Application/JSON");
                    echo json_encode($query, JSON_UNESCAPED_SLASHES);
                }
            } else {
                echo "Have No Authority";
            }
        }

        Public function get($param1, $param2){
            $session_check  = $this->session->userdata("is_logged_in");

            if($session_check != false){
                $unit       = $this->session->userdata("unit");

                switch($param1){
                    case "noreg" : 
                        $query = $this->db->query("SELECT *, noreg AS no_registrasi, rekmed AS rekam_medis, CONCAT(namapas ,' ', IF(jkel = 'P', 'Pria', 'Wanita') ,' DOB ', REPLACE(tgllahir, ' 00:00:00', '')) AS nama_pasien, CONCAT(REPLACE(tglmasuk, ' 00:00:00', '') ,' ', jam) AS tangal_masuk, IF(tujuan = 1, 'RAJAL', 'RANAP') AS layanan, IF(tujuan = 1, namapost ,CONCAT('RUANG ', namaruang ,' BED ', namakamar ,' KELAS ', namakelas)) AS tujuan, IF(jenispas = 'PAS1', 'UMUM', cust_nama) AS jenis_pasien, nadokter AS dokter, nosep, CONCAT(REPLACE(tgllahir, ' 00:00:00', '')) AS tanggallahir
                        FROM pasien_daftar
                        WHERE koders = '$unit' AND keluar = 0 AND noreg = '$param2'")->row(); 
                        break;
                    case "rekmed" : 
                        $query      = $this->db->query("SELECT *, noreg AS no_registrasi, rekmed AS rekam_medis, CONCAT(namapas ,' ', IF(jkel = 'P', 'Pria', 'Wanita') ,' DOB ', REPLACE(tgllahir, ' 00:00:00', '')) AS nama_pasien, CONCAT(REPLACE(tglmasuk, ' 00:00:00', '') ,' ', jam) AS tangal_masuk, IF(tujuan = 1, 'RAJAL', 'RANAP') AS layanan, IF(tujuan = 1, namapost ,CONCAT('RUANG ', namaruang ,' BED ', namakamar ,' KELAS ', namakelas)) AS tujuan, IF(jenispas = 'PAS1', 'UMUM', cust_nama) AS jenis_pasien, nadokter AS dokter, nosep, CONCAT(REPLACE(tgllahir, ' 00:00:00', '')) AS tanggallahir
                        FROM pasien_daftar
                        WHERE koders = '$unit' AND keluar = 0 AND rekmed = '$param2'")->row();
                        break;
                    default : 
                        echo "Invalid Method"; 
                        break;
                }
                
                if(!$query){
                    echo "No Data<br />Error : ". $this->db->error();
                } else {
                    header("Content-Type:Application/JSON");
                    echo json_encode($query,JSON_UNESCAPED_SLASHES);
                }
            } else {
                echo "Have No Authority";
            }
        }

    }