<?php 

class Pcare_sync extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("dpsPcare/Services/Pcare_peserta", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_diagnosa", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_dokter", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_poli", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_kesadaran", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_status_pulang", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_obat", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_tindakan", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_provider", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_spesialis", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_subspesialis", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_sarana", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_khusus", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_kelompok", ["kdppk" => $this->session->userdata("kdppk")]);
    }

    public function index ()
    {
        $data = [
            "obat"          => $this->db->select("kodebarang, pcare_kdObat")->where("pcare_kdObat <>", NULL)->get("tbl_barang")->result(),
            "all_obat"      => $this->db->select("kodebarang id, CONCAT(namabarang, ' (', satuan1, ')') text")->get("tbl_barang")->result(),
            "tindakan"      => $this->db->select("kodetarif, pcare_kdTindakan")->where("pcare_kdTindakan <>", NULL)->get("tbl_tarifh")->result(),
            "all_tindakan"  => $this->db->select("kodetarif id, tindakan text")->get("tbl_tarifh")->result(),
            "spesialis"     => $this->db->select("kdSpesialis id, nmSpesialis text")->get("bpjs_pcare_spesialis")->result(),
            "dokter"        => $this->db->select("kodokter, pcare_kdDokter")->where("pcare_kdDokter <>", NULL)->get("tbl_dokter")->result(),
            "all_dokter"    => $this->db->select("kodokter id, nadokter text")->get("tbl_dokter")->result(),
        ];

        $this->load->view("pcare/pcare_sync", $data);
    }

    /**
     * Method for synchronizing doctors
     * 
     * @param int $limit
     */
    public function dokter (int $limit = 100)
    {
        $result = $this->pcare_dokter->get_dokter(0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for updating tbl_dokter
     * 
     * @param int $limit
     */
    public function update_master_dokter (int $limit = 100)
    {
        $master_dokter = $this->input->post("master_dokter");

        foreach($master_dokter as $key => $value) {
            $result = $this->pcare_dokter->update_master_dokter($key, $value);
        }

        if ($result) {
            return $this->output
                    ->set_content_type("application/json")
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        "status" => true,
                        "data"   => null
                    ]));
        }

        return $this->output
                    ->set_content_type("application/json")
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        "status" => false,
                        "data"   => null
                    ]));
    }

    /**
     * Method for synchronizing kesadaran
     * 
     * @param int $limit
     */
    public function kesadaran (int $limit = 100)
    {
        $result = $this->pcare_kesadaran->get_kesadaran(0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing poliklinik
     * 
     * @param int $limit
     */
    public function poliklinik (int $limit = 100)
    {
        $result = $this->pcare_poli->get_poli(0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing status pulang
     * 
     * @param string $jenis_perawatan
     * @param int $limit
     */
    public function status_pulang (string $jenis_perawatan, int $limit = 100)
    {
        if ($jenis_perawatan == "ri") {
            $result = $this->pcare_status_pulang->get_status_pulang_ri (0, $limit);
        }else {
            $result = $this->pcare_status_pulang->get_status_pulang_rj (0, $limit);
        }

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing medicine
     * 
     * @param string $search_term
     * @param int $limit
     */
    public function obat (string $search_term, int $limit = 100)
    {
        $result = $this->pcare_obat->get_obat_dpho ($search_term, 0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for saving to master obat
     * 
     */
    public function update_master_obat () 
    {
        $master_code = $this->input->post("master_code");
        $result      = true;

        foreach($master_code as $key => $value) {
            $result = $this->pcare_obat->update_master_barang($key, $value);
        }

        if ($result) {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status" => true,
                    "data"   => null
                ]));
        }

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header(500)
            ->set_output(json_encode([
                "status" => false,
                "data"   => null
            ]));
    }

    /**
     * Method for synchronizing actions
     * 
     * @param string $kdTkp
     * @param int $limit
     */
    public function tindakan ($kdTkp, int $limit = 200)
    {
        $result = $this->pcare_tindakan->get_tindakan ($kdTkp, 0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method save to master tindakan
     * 
     */
    public function update_master_tindakan ()
    {
        $master_tindakan = $this->input->post("master_tindakan");

        foreach($master_tindakan as $key => $value) {
            $result = $this->pcare_tindakan->update_master_tindakan($key, $value);
        }

        if ($result) {
            return $this->output
                    ->set_content_type("application/json")
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        "status" => true,
                        "data"   => null
                    ]));
        }

        return $this->output
                    ->set_content_type("application/json")
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        "status" => false,
                        "data"   => null
                    ]));
    }

    /**
     * Method for synchronizing provider
     *
     * @param int $limit
     */
    public function provider (int $limit = 200)
    {
        $result = $this->pcare_provider->get_provider (0, $limit);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing spesialis
     * 
     */
    public function spesialis ()
    {
        $result = $this->pcare_spesialis->get_spesialis();

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing subspesialis
     * 
     * @param string $spesialis
     */
    public function subspesialis (string $spesialis)
    {
        $result = $this->pcare_subspesialis->get_subspesialis($spesialis);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing sarana
     * 
     */
    public function sarana ()
    {
        $result = $this->pcare_sarana->get_sarana();

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing khusus
     * 
     */
    public function khusus ()
    {
        $result = $this->pcare_khusus->get_khusus();

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing diagnosa
     * 
     * @param string $search_term
     */
    public function diagnosa (string $search_term)
    {
        $result = $this->pcare_diagnosa->get_diagnosa($search_term);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing diagnosa
     * 
     */
    public function peserta ()
    {
        $search_term = $this->input->post("search_term");
        $jenis_kartu = $this->input->post("jenis_kartu");

        $result = $this->pcare_peserta->get_peserta($search_term, $jenis_kartu);

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    /**
     * Method for synchronizing club prolanis
     * 
     * @param string $kdProgram
     */
    public function club_prolanis (string $kdProgram)
    {
        $search_term = $this->input->post("search_term");
        $jenis_kartu = $this->input->post("jenis_kartu");

        $result      = $this->pcare_kelompok->get_club_prolanis($kdProgram);
        $result_db   = $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_club_prolanis")->result();

        return $this->output
            ->set_content_type("application/json")
            ->set_status_header($result->status)
            ->set_output(json_encode($result_db));
    }
}