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
                "data" => $this->pcare_pendaftaran->get_pendaftaran_provider("14-01-2023")
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

    public function rujukan () {
        $data = [
            "spesialis" => $this->db->get("bpjs_pcare_spesialis")->result(),
            "subspesialis" => $this->db->get("bpjs_pcare_spesialis_sub")->result(),
            "khusus" => $this->db->get("bpjs_pcare_khusus")->result(),
            "sarana" => $this->db->get("bpjs_pcare_sarana")->result()
        ];
        $this->load->view("test", $data);
    }
}