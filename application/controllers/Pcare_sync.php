<?php 

class Pcare_sync extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("dpsPcare/Services/Pcare_dokter", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_poli", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_kesadaran", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_status_pulang", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_obat", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/Pcare_tindakan", ["kdppk" => $this->session->userdata("kdppk")]);
    }

    public function index ()
    {
        $data = [
            "obat"          => $this->db->where("pcare_kdObat <>", NULL)->get("tbl_barang")->result(),
            "all_obat"      => $this->db->select("kodebarang id, namabarang text")->get("tbl_barang")->result(),
            "tindakan"      => $this->db->where("pcare_kdTindakan <>", NULL)->get("tbl_tarifh")->result(),
            "all_tindakan"  => $this->db->select("kodetarif id, tindakan text")->get("tbl_tarifh")->result(),
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
    public function update_master () {
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
}