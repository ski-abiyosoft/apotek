<?php

class Pcare_api extends CI_Controller
{      
    /**
     * We load all PCare Class in this constructor
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library("dpsPcare/Services/pcare_pendaftaran", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/pcare_peserta", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/pcare_kunjungan", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/pcare_kelompok", ["kdppk" => $this->session->userdata("kdppk")]);
        $this->load->library("dpsPcare/Services/pcare_rujukan", ["kdppk" => $this->session->userdata("kdppk")]);
    }

    /**
     * Method for getting doctors record on BPJS
     * 
     */
    public function dokter ()
    {
        // Code here
    }

    /**
     * Method for getting diagnose data from BPJS
     * 
     */
    public function diagnosa ()
    {
        $is_logged_in = $this->session->userdata('level');
        
        if ($is_logged_in) {
            // Code here
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * Method for getting peserta from BPJS
     * 
     */
    public function peserta ()
    {
        $is_logged_in = $this->session->userdata('level');
        
        if ($is_logged_in) {
            $result = $this->pcare_peserta->get_peserta($this->input->get("no_kartu"), $this->input->get("jenis_kartu"));
    
            if ($result->status >= 200 AND $result->status < 300) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header($result->status)
                    ->set_output(json_encode($result->data));
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output(json_encode($result->message));
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * +==========================================================================================+
     * + Method for handling registration process, there are 4 main method for registration,      +
     * + i.e. create, delete, details, and show.                                                  +
     * +                                                                                          +
     * +==========================================================================================+
     */


    /**
     * Method for creating registration. Make sure that the paramters you have sent is same with the
     * table columns (bpjs_pcare_pendaftaran). This method will return server error when BPJS web
     * service reject your request, otherwise it will return serial number that given by BPJS web
     * service.
     * 
     */
    public function create_pendaftaran ()
    {
        $is_logged_in = $this->session->userdata('level');
        
        if ($is_logged_in) {
            $fields     = $this->db->list_fields("bpjs_pcare_pendaftaran");
            $request    = (object) $this->input->post();
            $data_set   = (object) [];

            foreach ($fields as $field) {   
                if ($field == "id") continue;

                $data_set->$field = isset($request->$field) ? $request->$field : "";
            }

            $id = $this->pcare_pendaftaran->save_or_update($data_set);
            $result = $this->pcare_pendaftaran->add_pendaftaran($id);

            if ($result->status >= 200 AND $result->status < 300) {
                $data_set->noUrut  = $result->data->message;
                $data_set->deleted = 0;
                $this->pcare_pendaftaran->save_or_update($data_set);

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header($result->status)
                    ->set_output(json_encode($result->data));
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output(json_encode($result->message));
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * Method for deleting registration data. The parameter that needed by this method is the table
     * id and card number. Make sure that you use POST method when make request to this method, or 
     * it will fail. 
     * 
     */
    public function delete_pendaftaran ()
    {
        $is_logged_in = $this->session->userdata('level');
        
        if ($is_logged_in) {
            $id         = $this->input->post("id");
            $result     = $this->pcare_pendaftaran->delete_pendaftaran($id);
            $info       = $this->db->where("id", $id)->get("bpjs_pcare_pendaftaran")->row();

            if ($result->status >= 200 AND $result->status < 300) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header($result->status)
                    ->set_output(json_encode($result->data));
            }

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header($result->status)
                    ->set_output(json_encode($result->message));
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * Method for handling data table request for showing all registartion records that have been created 
     * by provider based of given date. For performance reason we use data table request format. Please
     * append date (tglDaftar) parameter to data table request (fnServerParams), if not your request 
     * will fail.
     * 
     */
    public function get_pendaftaran ()
    {
        $request = (object) $this->input->get();
        
        // First we sync our database with BPJS database
        $this->pcare_pendaftaran->sync_data(date("d-m-Y", strtotime($request->tglDaftar)));

        // Data table request processed here
        $sEcho = $request->sEcho;
        $columns = ["noUrut", "noKartuPeserta", "namaPeserta", "sex", "tglLahir", "nmPoli", "'BRIDGING' as sumber", "status", "id"];
        $sortable_columns = ["noUrut", "noKartuPeserta", "namaPeserta", "sex", "tglLahir", "nmPoli", "status", "id"];
        $limit = intval($request->iDisplayLength); 
        $offset = intval($request->iDisplayStart); 
        $search_value = $request->sSearch; 
        $order_column = $request->iSortCol_0; 
        $order_type = $request->sSortDir_0;
        $where_clause = [
            'tglDaftar' => $request->tglDaftar,
            'noUrut <>' => "",
            'deleted' => 0,
        ];

        $columns_str    = implode(',', $columns);
        $total_count = $this->db->from("bpjs_pcare_pendaftaran")->select("COUNT(*) as jumlah");

        if (count($where_clause) > 0) $total_count = $total_count->where($where_clause);

        $total_count = $total_count->get()->row()->jumlah;

        $filtered_count = $this->db->from("bpjs_pcare_pendaftaran pn")->select("COUNT(*) as jumlah")
                            ->join("bpjs_pcare_peserta ps", "ps.noKartu = pn.noKartuPeserta", "left")
                            ->join("bpjs_pcare_poli pl", "pl.kdPoli = pn.kdPoli", 'left');

        if (count($where_clause) > 0) $filtered_count = $filtered_count->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($sortable_columns as $key => $column1) {
                if ($key == 0) $where .= "$column1 like '%{$search_value}%'";
                $where .= "or $column1 like '%{$search_value}%'";
            }

            $filtered_count = $filtered_count->where("($where)");
        }

        $filtered_count = $filtered_count->get()->row()->jumlah;
        $filtered_data  = $this->db->from("bpjs_pcare_pendaftaran pn")->select("$columns_str")
                            ->join("bpjs_pcare_peserta ps", "ps.noKartu = pn.noKartuPeserta", "left")
                            ->join("bpjs_pcare_poli pl", "pl.kdPoli = pn.kdPoli", 'left');

        if (count($where_clause) > 0) $filtered_data = $filtered_data->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($sortable_columns as $key => $column2) {
                if ($key == 0) $where .= "$column2 like '%{$search_value}%'";
                else $where .= "or $column2 like '%{$search_value}%'";
            }

            $filtered_data = $filtered_data->where("($where)");
        }

        $filtered_data = $filtered_data->limit($limit, $offset)->order_by($sortable_columns[$order_column], $order_type)->get()->result();

        $result = [
            'iTotalRecords' => $total_count,
            'iTotalDisplayRecords' => $filtered_count,
            'sEcho' => intval($sEcho),
            'aaData' => $filtered_data
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    /**
     * Method for handling registration detail request. The parameter that needed by this request is table
     * id.
     * 
     * @param string $noreg
     */
    public function get_detail_pendaftaran (string $noreg)
    {
        // First we sync our database with BPJS database
        $this->pcare_pendaftaran->sync_data(date("d-m-Y"));
        
        // Then return the result
        $result = $this->db->where("noReg", $noreg)->get("bpjs_pcare_pendaftaran")->row();
        
        return $this->output
            ->set_content_type("application/json")
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    /**
     * +==========================================================================================+
     * + Method for handling visitation process, there are 5 main method for visitation,          +
     * + i.e. create, edit, delete, detail, and referal detail.                                   +
     * +                                                                                          +
     * +==========================================================================================+
     */

    /**
     * Method for getting TACC
     * 
     * @param string $kdTacc
     * @return mixed
     */
    public function get_tacc ()
    {
        return $this->output
            ->set_content_type("application/json")
            ->set_status_header("200")
            ->set_output(json_encode($this->pcare_kunjungan->get_tacc()));
    }

    /**
     * Method for creating visitation record. Make sure that parameter you have sent are same with
     * table columns (bpjs_pcare_kunjungan). 
     * 
     */
    public function create_kunjungan ()
    {
        $request    = (object) $this->input->post();
        $fields     = $this->db->list_fields("bpjs_pcare_kunjungan");
        $data_set   = (object) [];

        foreach ($fields as $field) {
            $data_set->$field = isset($request->$field) ? $request->$field : NULL;
        }

        $id = $this->pcare_kunjungan->save_or_update($data_set);

        $result = $this->pcare_kunjungan->add_kunjungan($id);

        if ($result->status >= 400){
            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header($result->status)
                    ->set_output(json_encode($result->message));
        }

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($result->data));
    }

    /**
     * Method for updating visitation record. Make sure that parameter you have sent are same with
     * table columns (bpjs_pcare_kunjungan). 
     * 
     */
    public function update_kunjungan (int $id)
    {
        $request    = (object) $this->input->post();
        $fields     = $this->db->list_fields("bpjs_pcare_kunjungan");
        $data_set   = (object) [];

        foreach ($fields as $field) {
            $data_set->$field = isset($request->$field) ? $request->$field : NULL;
        }

        unset($data_set->id);

        $this->db->update("bpjs_pcare_kunjungan", $data_set, ["id" => $id]);
        
        $updated_id         = $id;
        $registration_info  = $this->db->where("noReg", $request->noReg)->get("bpjs_pcare_pendaftaran")->row();

        try {
            $this->kunjunganService->editKunjungan($updated_id, $registration_info->noKartuPeserta, $request->tglDaftar);
        }catch (Exception $e) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        "metaCode"  => -1,
                        "message"   => "Gagal mendapatkan data. Terjadi Kesalahan server."
                    ]));
        }

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output($this->kunjunganService->response());
    }

    /**
     * 
     */

    /**
     * Method for deleting visitation record. Parameter that you shold give to this method
     * is id and visitation number (noKunjungan). Make sure you use POST method to access this method.
     * 
     */
    public function delete_kunjungan ()
    {
        $id             = $this->input->post("id");
        $noKunjungan    = $this->input->post("noKunjungan");

        try {
            $this->kunjunganService->deleteKunjungan($id, $noKunjungan);
        }catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "metaCode"  => -1,
                    "message"   => "Gagal mendapatkan data. Terjadi Kesalahan server."
                ]));
        }

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "noKunjungan"   => $noKunjungan,
                    "message"       => "Data berhasil dihapus."
                ]));
    }


    /**
     * Method for handling visitation history using it card number
     * 
     * @param string $noKartu
     */
    public function get_riwayat_kunjungan (string $noKartu)
    {
        $this->kunjunganService->getRiwayatKunjungan($noKartu);

        $result = $this->db->where("noKartu", $noKartu)->get("bpjs_pcare_kunjungan")->result();

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status"        => true,
                    "data"          => $result
                ]));
    }

    /**
     * Method for handling rujukan records from the table
     * 
     * @param string $noKunjungan
     */
    public function get_rujukan (string $noKunjungan)
    {
        $this->kunjunganService->getRujukan($noKunjungan);

        $result = $this->db->where("noKunjungan", $noKunjungan)->get("bpjs_pcare_rujukan")->row();

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status"        => true,
                    "data"          => $result
                ]));
    }

    /**
     * Method for getting supspecialist reference from BPJS WS
     * 
     */
    public function get_rujukan_subspesialis ()
    {
        $subspesialis   = $this->input->get("subspesialis");
        $sarana         = $this->input->get("sarana");
        $tglEstRujuk    = $this->input->get("tglEstRujuk");

        $result = $this->pcare_rujukan->get_subspesialis ($subspesialis, $sarana, $tglEstRujuk);

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output(json_encode($result->message));
    }

    /**
     * Method for getting supspecialist reference from BPJS WS
     * 
     */
    public function get_rujukan_khusus ()
    {
        $subspesialis   = $this->input->get("subspesialis");
        $kdKhusus       = $this->input->get("kdKhusus");
        $sarana         = $this->input->get("sarana");
        $noKartu        = $this->input->get("noKartu");
        $tglEstRujuk    = $this->input->get("tglEstRujuk");

        $result = $this->pcare_rujukan->get_khusus ($noKartu, $kdKhusus, $subspesialis, $tglEstRujuk);

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output(json_encode($result->data));
    }

    /**
     * Method for saving kegiatan kelompok
     * 
     */
    public function add_kegiatan_kelompok ()
    {
        $result = $this->pcare_kelompok->add_kegiatan_kelompok((object) $this->input->post());
        $output = isset($result->data) ? $result->data : $result->message;

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output(json_encode($output));
    }

    /**
     * Method for getting data kegiatan from BPJS
     * 
     * @param string $date
     */
    public function get_kegiatan_kelompok (string $date) 
    {
        $result = $this->pcare_kelompok->get_kegiatan_kelompok($date);

        return $this->output
                ->set_content_type('application/json')
                ->set_status_header($result->status)
                ->set_output($result->data);
    }
}