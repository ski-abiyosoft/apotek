<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
<<<<<<< HEAD
        // $this->load->library('dpsPcare/Services/Pcare_dokter', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_peserta', ["kdppk" => $this->session->userdata("kdppk")]);
        // $this->load->library('dpsPcare/Services/Pcare_poli', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_tindakan', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_obat', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_kunjungan', ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library('dpsPcare/Services/Pcare_pendaftaran', ["kdppk" => $this->session->userdata("kdppk")]);
=======
        $this->load->library('dpsAccounting/Services/cash_disbursement_service');
        $this->load->model('M_piutang', 'piutang', TRUE);
        $this->load->model('M_cetak', 'pdf');
        $this->load->model('M_rs', 'rs');
>>>>>>> development
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
<<<<<<< HEAD
            ->set_output(json_encode([
                "data" => $this->db->list_fields("bpjs_pcare_obat_kunjungan")
            ]));
        // return $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode([
        //         "data" => $this->pcare_pendaftaran->get_pendaftaran_provider("23-01-2023")
        //     ]));
    }

    public function view_sess ()
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "data" => $this->session->userdata()
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

    public function get_rujukan (string $noKunjungan) {
        $result = $this->pcare_kunjungan->get_rujukan($noKunjungan);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($result->status)
            ->set_output(json_encode($result->data));
    }

    public function rujukan (string $noreg = "") {
        $data = [
            "noreg"         => $noreg,
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
            $data["pcare_visit_data"]   = $this->db->where("noreg", $noreg)->get("bpjs_pcare_kunjungan")->row();
            $data["data_peserta"]       = $this->db->where("noKartu", $regist_data->nobpjs)->get("bpjs_pcare_peserta")->row();
            $data["local_regist_data"]  = $regist_data;
            $data["local_mr"]           = $this->db->where("noreg", $noreg)->get("tbl_rekammedisrs")->row();
            $data["diagnosa"]           = $this->db->select("b.code, b.str")->join("tbl_icdinb b", "b.code = a.icdcode")
                                            ->where("noreg", $noreg)->order_by("a.utama", "desc")->get("tbl_icdtr a")->result();
        }

        $this->load->view("test", $data);
    }

    public function cetak_rujukan (string $noRujukan)
    {
        $result = $this->pcare_kunjungan->get_rujukan($noRujukan);
        $data["rujukan"] = $this->db->where("noRujukan", $noRujukan)->get("bpjs_pcare_rujukan")->row();
        $data["info_ppk"] = $this->db->select("koders, namers")->where("kode_rs", $this->session->userdata("unit"))->get("tbl_bpjsset")->row();
        $timestamp = strtotime("now");

        $mpdf = new \Mpdf\Mpdf(["format" => [210, 297], "default_font" => "FreeSans", "margin_top" => 7]);

        $mpdf->WriteHTML($this->load->view("pcare/cetak_rujukan", $data, true));

        $mpdf->Output("cetak_rujukan_{$noRujukan}_{$timestamp}.PDF", "I");
    }

    public function obat_kunjungan (string $noKunjungan) 
    {
        $no_reg = $this->db->select("noReg")->where("noKunjungan", $noKunjungan)->get("bpjs_pcare_kunjungan")->row()->noReg;

        $data = [
            "noKunjungan" => $noKunjungan,
            "resep_poli" => $this->db->where("noreg", $no_reg)->get("tbl_eresep")->result()
        ];

        return $this->load->view("pcare/obat_kunjungan", $data);
    }

    public function tindakan_kunjungan (string $noKunjungan) 
    {
        $no_reg = $this->db->select("noReg")->where("noKunjungan", $noKunjungan)->get("bpjs_pcare_kunjungan")->row()->noReg;

        $data = [
            "noKunjungan"   => $noKunjungan,
            "kdTkp"      => $this->db->select("kdTkp")->where("noReg", $no_reg)->get("bpjs_pcare_pendaftaran")->row()->kdTkp,
            "tindakan_poli" => $this->db->select("td.kodetarif, pcare_kdTindakan")->where("noreg", $no_reg)->join("tbl_tarifh tth", "tth.kodetarif = td.kodetarif", "left")->get("tbl_dpoli td")->result()
        ];

        return $this->load->view("pcare/tindakan_kunjungan", $data);
    }

    public function mcu_kunjungan (string $noKunjungan) 
    {

    }

    public function get_obat_dpho ()
    {
        $keywords = $this->input->get("term");
        $result = $this->db->select("kdObat id, CONCAT(kdObat, ' | ', nmObat, ' (', sedia, ')') text")->where("kodeRs", $this->session->userdata("unit"))
                    ->where("kdObat like '%$keywords%' OR nmObat like '%$keywords%'")->get("bpjs_pcare_obat")->result();

        return $this->output->set_content_type("application/json")
                ->set_status_header(200)
                ->set_output(json_encode($result));
    }

    public function get_ref_tindakan ($kdTkp)
    {
        $keywords = $this->input->get("term");
        $kdTkp = [];

        switch ($kdTkp) {
            case "10":
                $kdTkp = ["rjtp" => 1];
                break;
            case "20":
                $kdTkp = ["ritp" => 1];
                break;
            case "50":
                $kdTkp = ["promotif" => 1];
                break;
        }

        $result = $this->db->select("kdTindakan id, CONCAT(kdTindakan, ' | ', nmTindakan, ' (Max. ', maxTarif, ')') text")
                    ->where([
                        "kodeRs" => $this->session->userdata("kdppk"),
                    ])
                    ->where($kdTkp)
                    ->where("(kdTindakan like '%$keywords%' OR nmTindakan like '%$keywords%')")->get("bpjs_pcare_tindakan")->result();

        return $this->output->set_content_type("application/json")
                ->set_status_header(200)
                ->set_output(json_encode($result));
    }

    public function get_obat_kunjungan (string $noKunjungan)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "data" => $this->pcare_obat->get_obat_kunjungan($noKunjungan)
            ]));
    }

    public function get_tindakan_kunjungan (string $noKunjungan)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "data" => $this->pcare_tindakan->get_tindakan_kunjungan($noKunjungan)
            ]));
    }

    public function riwayat_kunjungan (string $noKartu)
    {
        $this->pcare_kunjungan->get_kunjungan($noKartu);

        $data["kunjungan"] = $this->db->where("noKartu", $noKartu)->get("bpjs_pcare_kunjungan")->result();

        return $this->load->view("pcare/riwayat_kunjungan", $data);
=======
            ->set_output(json_encode($this->cash_disbursement_service->test()));
>>>>>>> development
    }
}