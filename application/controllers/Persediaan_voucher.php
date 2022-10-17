<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    Class Persediaan_voucher extends CI_Controller{

        public function __construct(){
            parent::__construct();
            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2304');
            $this->load->model(array("M_persediaan_voucher","M_global"));
            $this->load->helper(array("app_global"));
        }

        public function index(){
            $unit = $this->session->userdata("unit");
            $cek = $this->session->userdata("level");
            $period = date("Y")."-".date("m")."-";
            $bulan          = $this->M_global->_periodebulan();
            $nbulan   	  = $this->M_global->_namabulan($bulan);
            $data["periode"] = 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();
            $data["cabang"] = $this->M_global->getcabang_all(1);
            $data["listv"] = $this->M_persediaan_voucher->listvoucher()->result();
            if(!empty($cek)){	
                $this->load->view('klinik/v_persediaan_voucher', $data);
            } else {
                header('location:'.base_url());
            }
        }

        public function cabang($cabang){
            $push = $this->M_persediaan_voucher->listbycabang($cabang);
            if($push->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                $list_arr = array();
                $list_result = $push->result();
                foreach($list_result as $plval){
                    if($plval->terjual <= 0 && $plval->terpakai <= 0){
                        $status = "<center class='text-success'>Tersedia</center>";
                    } else {
                        if($plval->terjual > 0 && $plval->terpakai > 0){
                            $status = "<center class='text-primary'>Terjual dan Terpakai</center>";
                        } else {
                            if($plval->terpakai > 0){
                                $status = "<center class='text-info'>Terpakai</center>";
                            } else
                            if($plval->terjual > 0){
                                $status = "<center class='text-primary'>Terjual</center>";
                            }
                        }
                    }
                    $list_arr[] = array(
                    "koders" => $plval->koders,
                    "nokir" => $plval->nokir,
                    "novoucher" => $plval->novoucher,
                    "tglkirim" => ($plval->tglkirim == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($plval->tglkirim)),
                    "tgjual" => ($plval->tgjual == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($plval->tgjual)),
                    "cabangvoc" => $plval->cabangvoc,
                    "cabangpakai" => $plval->cabangpakai,
                    "nominal" => number_format($plval->nominal, 0, ',', '.'),
                    "diskon" => $plval->diskon,
                    "rekmed" => $plval->rekmed,
                    "nokwitansi" => $plval->nokwitansi,
                    "tglpakai" => ($plval->tglpakai == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($plval->tglpakai)),
                    "rekmedpakai" => $plval->rekmedpakai,
                    // "terjual" => ($plval->terjual > 0)? "Terjual" : "Tersedia",
                    // "terpakai" => ($plval->terpakai > 0)? "Terpakai" : "Tersedia",
                    "status" => $status
                    );
                }
                echo json_encode($list_arr,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        
        public function filter($param){
            $cek                = $this->session->userdata('level');
            $unit               = $this->session->userdata('unit');

            $period_length      = explode("~", $param);
            $dari_Periode       = date("Y-m-d", strtotime($period_length[0]));
            $ke_periode         = date("Y-m-d", strtotime($period_length[1]));
            $bulan_dari_priode  = date("n", strtotime($period_length[0]));
            $bulan              = $this->M_global->_periodebulan();

            $tahun1             =  explode("-", $period_length[0]);
            $tahun2             =  explode("-", $period_length[1]);

            $data["listv"]      = $this->M_persediaan_voucher->filter($dari_Periode, $ke_periode)->result();
            //$data["listv"]      = $this->M_pembuatan_voucher->filter($unit, $dari_Periode, $ke_periode)->result();

            $dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);
            $ke_bulan           = $this->M_global->_namabulan($bulan);
            $data["periode"]    = 'Periode '.$dari_bulan.'-'.$tahun1[0].' s/d '.$ke_bulan.'-'.$tahun2[0];
            if(!empty($cek)){
                $this->load->view("klinik/v_persediaan_voucher", $data);
            } else {
                header("location:".base_url());
            }
        }

    }