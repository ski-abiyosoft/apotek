<?php

class Pcare_kegiatan_kelompok extends CI_controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->session->set_userdata("menuapp", 2000);
        $this->session->set_userdata("submenuapp", 2905);
    }

    public function index()
    {
        $data["kelompok"] = [
            0 => (object) [
                "kdProgram" => "01",
                "nmProgram" => "Diabetes Melitus"
            ],
            1 => (object) [
                "kdProgram" => "02",
                "nmProgram" => "Hipertensi"
            ]
        ];

        $data["kegiatan"] = [
            0 => (object) [
                "kdProgram" => "01",
                "nmProgram" => "Senam"
            ],
            1 => (object) [
                "kdProgram" => "10",
                "nmProgram" => "Penyuluhan"
            ],
            2 => (object) [
                "kdProgram" => "11",
                "nmProgram" => "Penyuluhan dan Senam"
            ]
        ];
        $data["club_prolanis"] = $this->db->get("bpjs_pcare_club_prolanis")->result();

        return $this->load->view("pcare/kegiatan_kelompok", $data);
    }
}