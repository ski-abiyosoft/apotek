<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->library('dpsPcare/Services/Pcare_dokter', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_peserta', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_poli', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_status_pulang', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_kesadaran', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_kunjungan', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_pendaftaran', ["kdppk" => $this->session->userdata("kdppk")]);
    }
    
    public function index()
    {
        // var_dump($this->piutang->get_ar_aging_data((object) [
        //         'vendor' => 'BPJS'
        // ]));
        // exit;
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "data" => $this->pcare_pendaftaran->get_pendaftaran_provider("18-01-2023")
            ]));
    }

    public function peserta (string $noKartu)
    {
        $result = $this->pcare_peserta->get_peserta($noKartu);
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    public function delete_pendaftaran (int $id) 
    {
        $result = $this->pcare_pendaftaran->delete_pendaftaran($id);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    public function get_kunjungan (string $noKartu) {
        $result = $this->pcare_kunjungan->get_kunjungan($noKartu);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    public function rujukan (string $noreg = "") {
        $data = [
            "kesadaran"     => $this->db->get("bpjs_pcare_kesadaran")->result(),
            "poli"          => $this->db->get("bpjs_pcare_poli")->result(),
            "status_pulang" => $this->db->get("bpjs_pcare_status_pulang")->result(),
            "kdppk"         => $this->db->where("koders", $this->session->userdata("kdppk"))->get("tbl_bpjsset")->row(),
            "dokter"        => $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_dokter")->result(),
            "spesialis"     => $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_spesialis")->result(),
            "subspesialis"  => $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_spesialis_sub")->result(),
            "khusus"        => $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_khusus")->result(),
            "sarana"        => $this->db->where("kodeRs", $this->session->userdata("kdppk"))->get("bpjs_pcare_sarana")->result(),
            "icd_all"       => $this->db->select("CONCAT(code, ' - ', str) id, CONCAT(code, ' - ', str) text")->where("sab", "ICD10_1998          ")->get("tbl_icdinb")->result()
        ];

        if (strlen($noreg) > 0) {
            $regist_data                = $this->db->where("noreg", $noreg)->get("tbl_regist")->row();
            $data["pcare_regist_data"]  = $this->db->where("noreg", $noreg)->get("bpjs_pcare_pendaftaran")->row();
            $data["data_peserta"]       = $this->db->where("noKartu", $regist_data->nobpjs)->get("bpjs_pcare_peserta")->row();
            $data["local_regist_data"]  = $regist_data;
            $data["local_mr"]           = $this->db->where("noreg", $noreg)->get("tbl_rekammedisrs")->row();
            $data["diagnosa"]           = $this->db->select("b.code, b.str")->join("tbl_icdinb b", "b.code = a.icdcode")
                                            ->where("noreg", $noreg)->order_by("a.utama", "desc")->get("tbl_icdtr a")->result();
        }

        $this->load->view("test", $data);
    }
}