<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class Pcare extends CI_Controller{

        public function __construct(){
            parent::__construct();

            // $user   = $this->session->userdata("username");
            // if(empty($user)){
            //     redirect("/app/logout");
            // }

            $this->session->set_userdata('menuapp', '2900');
		    $this->session->set_userdata('submenuapp', '2901');
        }

        public function index(){
            show_404();
            // $data   = [
            //     "noreg"         => $noreg == "" ? "" : $noreg,
            //     "pcare_poli"    => $this->db->get_where("bpjs_pcare_poli", ["poliSakit" => "1"]),
            //     "pcare_dr"		=> $this->db->get("bpjs_pcare_dokter"),
            //     "pcare_sp"		=> $this->db->get("bpjs_pcare_status_pulang"),
            //     "pcare_sadar"   => $this->db->get("bpjs_pcare_kesadaran"),		
            // ];

            // $this->load->view("pcare/index", $data);
        }

        // GET

        public function get_rekam_medis($noreg){
            $get_rekam_medis    = $this->db->get_where("pasien_rajal", ["noreg" => $noreg]);
            $get_pcare_regis    = $this->db->get_where("bpjs_pcare_pendaftaran", ["noReg" => $noreg]);

            if($get_pcare_regis->num_rows() == 0){
                $status_pcare   = false;
                $data_pcare     = "";
            } else {
                $status_pcare   = true;
                $data_pcare     = $get_pcare_regis->row();
            }

            if($get_rekam_medis->num_rows() == 0){
                $status_rekmed  = false;
                $data_rekmed    = "";
            } else {
                $status_rekmed  = true;
                $data_rekmed    = $get_rekam_medis->row();
            }

            $data   = [
                "status_rekmed" => $status_rekmed,
                "status_pcare"  => $status_pcare,
                "data_rekmed"   => $data_rekmed,
                "data_pcare"    => $data_pcare
            ];

            echo json_encode($data, JSON_UNESCAPED_SLASHES);
        }

        public function pcare_get_data_pas($noreg){
            $trx		= $this->db->get_where("tbl_icdtr", ["noreg" => $noreg]);
            $ins		= $this->db->get_Where("tbl_rekammedisrs", ["noreg" => $noreg]);
    
            $res		= [];
            $keluhan_awal = $tinggi_badan = $berat_badan = "";
    
            switch(true){
                case ($trx == false) : 
                    $status		= "error";
                    $message	= "Gagal memuat diagnosa";
                    break;
                case ($ins == false) : 
                    $status		= "error";
                    $message	= "Gagal memuat data pasien";
                    break;
                default : 
                    $data_pas	= $ins->row();
    
                    $status		= "success";
                    $message	= "";
    
                    $keluhan_awal	= $data_pas->keluhanawal;
                    $tinggi_badan	= $data_pas->tinggibadan;
                    $berat_badan	= str_replace(".00", "", $data_pas->beratbadan);
    
                    foreach($trx->result() as $t){
                        $res[]	= [
                            "icd_code"	=> $t->icdcode,
                            "icd_name"	=> data_master("tbl_icdinb", ["code" => $t->icdcode])->str
                        ];
                    }
                    break;
            }
    
            echo json_encode([
                "status"	=> $status,
                "message"	=> $message,
                "data_diag"	=> $res,
                "data_pas"	=> [
                    "keluhan_awal"	=> $keluhan_awal,
                    "tinggi_badan"	=> $tinggi_badan,
                    "berat_badan"	=> $berat_badan
                ]
            ], JSON_UNESCAPED_SLASHES);
        }

        public function poli_by_status($status){
            $list   = [];

            $query_poli = $this->db->get_where("bpjs_pcare_poli", ["poliSakit" => $status]);

            foreach($query_poli->result() as $qp){
                $list[] = [
                    "kdPoli"    => $qp->kdPoli,
                    "nmPoli"    => $qp->nmPoli
                ];
            }

            echo json_encode($list);
        }
        
        public function status_pulang_by_status($status){
            switch($status){
                case "10" : $query_status_pulang    = $this->db->get_where("bpjs_pcare_status_pulang", ["rawatJalan" => 1])->result(); break;
                case "20" : $query_status_pulang    = $this->db->get_where("bpjs_pcare_status_pulang", ["rawatInap" => 1])->result(); break;
                default   : $query_status_pulang    = $this->db->get_where("bpjs_pcare_status_pulang")->result(); break;
            }

            echo json_encode($query_status_pulang);
        }

        public function get_kunjungan($noreg){
            $query_kunjungan = $this->db->get_where("bpjs_pcare_kunjungan", ["noReg" => $noreg]);

            if($query_kunjungan->num_rows() == 0){
                $status     = "failed";
                $data       = null;
                $data_reg   = null;
            } else {
                $status     = "success";
                $data       = $query_kunjungan->row();
                $data_reg   = $this->db->get_where("bpjs_pcare_pendaftaran", ["noReg" => $noreg])->row();
            }

            echo json_encode([
                "status"    => $status,
                "data"      => $data,
                "data_reg"  => $data_reg
            ]);
        }

        public function get_diagnosa($code = ""){
            if($code == ""){
                $icdnb  = $this->db->get_where("tbl_icdinb", ["code" => $code])->row();
                echo json_encode(["nmdiag" => ""]);
            } else {
                $icdnb  = $this->db->get_where("tbl_icdinb", ["code" => $code])->row();
                echo json_encode(["nmdiag" => $icdnb->str]);
            }
        }

        public function get_sarana_khusus(){
            $query_sarana_khusus    = $this->db->get("bpjs_pcare_khusus")->result();

            echo json_encode($query_sarana_khusus);
        }

    }