<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class Ro2 extends CI_Controller{

        public function __construct(){
            parent::__construct();

            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2750');
            $this->load->model(array('M_ro2','M_barang','M_pasien_global', 'M_cetak'));
        }

        public function index(){
            $filter_date    = $this->input->get("filterdate");

            if(isset($filter_date)){
                $extract	= explode("~", $filter_date);
    
                $date_extarct	= (object) [
                    "start"	=> $extract[1],
                    "end"	=> $extract[2]
                ];
    
                if($extract[0] == "1"){
                    $list_eorder	= $this->M_ro2->get_list_eorder($date_extarct)->result();
                    $list_order	    = $this->M_ro2->get_list_order()->result();
                } else
                if($extract[0] == "2"){
                    $list_eorder	= $this->M_ro2->get_list_eorder()->result();
                    $list_order	    = $this->M_ro2->get_list_order($date_extarct)->result();
                } else {
                    header("Location:/ro2/");
                }
            } else {
                $list_eorder	= $this->M_ro2->get_list_eorder()->result();
                $list_order	    = $this->M_ro2->get_list_order()->result();
            }

            $data   = [
                "modul"     => "Radiologi",
                "title"     => "Radiologi",
                "submodul"  => "Index",
                "order"     => $list_order,
                "eorder"    => $list_eorder,
                "total_order"   => $this->M_ro2->count_order()->num_rows(),
                "total_eorder"  => $this->M_ro2->count_eorder()->num_rows(),
            ];

            $this->load->view("Ro2/index", $data);
        }

        public function eorder($param){
            $unit   = $this->session->userdata("unit");

            if($param == ""){
                redirect(base_url("ro2"));
            } else {
                $eorder = $this->M_ro2->data_eorder($param, $unit)->row();

                $data   = [
                    "modul"         => "Radiologi",
                    "title"         => "Tambah Pemeriksaan Dari E-Order",
                    "submodul"      => "Proses E-Order",

                    "orderno"       => $param,
                    "eorder"        => $eorder,
                    
                    "dokter1"       => $this->M_ro2->get_dokter1($unit, $eorder->asal)->result(),
                    "dokter_rad"    => $this->M_ro2->dokter_radio($unit)->result(),
                    "petugas"       => $this->M_ro2->petugas()->result(),
                    "listreg"       => $this->M_pasien_global->list(),
                    "listtindakan"  => $this->M_ro2->tindakan()->result(),
                    "detail_order"  => $this->M_ro2->data_eorder_detail($param)->result(),
                ];
    
                $this->load->view("Ro2/eorder", $data);

            }
        }

        public function create(){
            $unit   = $this->session->userdata("unit");

                // $order = $this->M_ro2->data_order($param)->row();

                $data   = [
                    "modul"         => "Radiologi",
                    "title"         => "Tambah Pemeriksaan",
                    "submodul"      => "Entri",

                    // "orderno"       => $param,
                    // "order"        => $eorder,
                    
                    "dokter1"       => $this->M_ro2->get_dokter1($unit)->result(),
                    "dokter_rad"    => $this->M_ro2->dokter_radio($unit)->result(),
                    "petugas"       => $this->M_ro2->petugas()->result(),
                    "listreg"       => $this->M_pasien_global->list(),
                    "listtindakan"  => $this->M_ro2->tindakan()->result(),
                ];
    
                $this->load->view("Ro2/create", $data);

        }

        public function update($param){
            $unit   = $this->session->userdata("unit");

            $order = $this->M_ro2->data_order($param);

            if($order->num_rows() == 0){
                $this->session->set_flashdata("error", "No Radiologi tidak ditemukan");
                redirect(base_url("ro2"));
            } else {
                $data   = [
                    "modul"         => "Radiologi",
                    "title"         => "Tambah Pemeriksaan",
                    "submodul"      => "Entri",

                    "order"         => $order->row(),
                    "files"         => $this->M_ro2->get_files($param)->result(),
                    "expertise"     => $this->M_ro2->get_expertise($param)->row(),
                    
                    "dokter1"       => $this->M_ro2->get_dokter1($unit)->result(),
                    "dokter_rad"    => $this->M_ro2->dokter_radio($unit)->result(),
                    "petugas"       => $this->M_ro2->petugas()->result(),
                    "listreg"       => $this->M_pasien_global->list(),
                    "listtindakan"  => $this->M_ro2->tindakan()->result(),

                    "list_bill"     => $this->M_ro2->billing($param)->result(),
                    "list_bhp"      => $this->M_ro2->bhp($param)->result(),
                ];

                $this->load->view("Ro2/update", $data);
            }
        }

        // GET

        public function get_tindakan($kode){
            $unit = $this->session->userdata('unit');
    
            $data = $this->db->query("SELECT *	FROM daftar_tarif_nonbedah AS a
            WHERE a.koders = '$unit' 
            AND a.kodetarif ='$kode' 
            AND a.kodepos = 'RADIO'")->row();
    
            echo json_encode($data);
        }

        public function get_barang($kode){
            $unit = $this->session->userdata('unit');
    
            $data = $this->db->query("SELECT *	FROM tbl_barang AS a
            WHERE a.kodebarang = '$kode'
            AND a.icgroup = 'BR-4'")->row();
    
            echo json_encode($data);
        }

        public function get_last_radio(){
            $unit	= $this->session->userdata("unit");
            echo json_encode(array(
                "noradio" => temp_urut_transaksi("URUT_RADIOLOGI", $unit, 19)
            ));
        }

        public function show_file($param){
            if($param == ""){
                $status     = "error";
                $message    = "<p>Indikator gambar yang ingin ditampilkan</p>tidak ditemukan";
                $image      = "";
                $nama_file  = "";
            } else {
                $this_files = $this->M_ro2->get_files_byid($param);

                if($this_files->num_rows() == 0){
                    $status     = "error";
                    $message    = "Gambar tidak ditemukan";
                    $image      = "";
                    $nama_file  = "";
                } else {
                    $files_row  = $this_files->row();
                    
                    $status     = "success";
                    $message    = "";
                    $image      = $files_row->lokasifile.$files_row->namafile;
                    $nama_file  = $files_row->namafile;
                }
            }

            echo json_encode(array(
                "status"    => $status,
                "message"   => $message,
                "image"     => $image,
                "namefile"  => $nama_file
            ), JSON_UNESCAPED_SLASHES);
        }

        // POST

        public function save($param = ""){
            $unit			= $this->session->userdata("unit");
            $replace_umur	= str_replace(array( " Tahun", " Bulan", " Hari"), array("","",""), $this->input->post("umur"));
            $explode_umur	= explode(" ", $replace_umur);

            $nolaborat		= $this->input->post('noradio');
            $orderno		= $this->input->post('orderno');

            $data_header    = [
                "koders"        => $unit,
                "noradio"       => $nolaborat,
                "radiomohon"    => "",
                "tglradio"      => $this->input->post("tglperiksa"),
                "noreg"         => $this->input->post("noreg"),
                "rekmed"        => $this->input->post("rekmed"),
                "tglfoto"       => "",
                "jamfoto"       => "",
                "drperiksa"     => $this->input->post("drlaborat"),
                "radiotype"     => "",
                "drpengirim"    => $this->input->post("drpengirim"),
                "asal"          => $this->input->post("asal"),
                "kelas"         => $this->input->post("kelas"),
                "kodepetugas"   => $this->input->post("petugas"),
                "username"      => $this->session->userdata('username'),
                "shipt"         => "",
                "jam"           => date("H:i:s"),
                "dibaca"        => "",
                "bayar"         => "",
                "keluar"        => "",
                "posting"       => "",
                "ambiljasa1"    => "",
                "ambiljasa2"    => "",
                "cnt_radio"     => "",
                "insentif"      => "",
                "nokwitansi"    => "",
                "tglselesai"    => "",
                "tglambil"      => "",
                "jamselesai"    => "",
                "jamambil"      => "",
                "diambiloleh"   => "",
                "namapas"       => $this->input->post("namapas"),
                "jpas"          => $this->input->post("jenispas"),
                "rujuk"         => $this->input->post("pemeriksaan"),
                "tgllahir"      => $this->input->post("tgllahir"),
                "umurth"        => $explode_umur[0],
                "umurbl"        => $explode_umur[1],
                "umurhr"        => $explode_umur[2],
                "jkel"          => $this->input->post("jeniskelamin"),
                "jenisperiksa"  => $this->input->post("jenisperiksa"),
                "langsung"      => "",
                "drluar"        => "",
                "useredit"      => "",
                "tgledit"       => "",
                "orderno"       => $orderno,
                "diagnosa"      => $this->input->post("diagnosa"),
            ];

            // BILLING
            $billing_tindakan	= $this->input->post("billing_tindakan");
            $billing_qty		= $this->input->post("billing_qty");
            $billing_cito		= $this->input->post("billing_cito");
            $billing_tarifrs	= str_replace(",", "", $this->input->post("billing_tarifrs"));
            $billing_tarifdr	= str_replace(",", "", $this->input->post("billing_tarifdr"));
            $billing_citorp		= str_replace(",", "", $this->input->post("billing_citorp"));
            $billing_tarifrp	= str_replace(",", "", $this->input->post("billing_tarifrp"));
            $billing_totalbiaya	= str_replace(",", "", $this->input->post("billing_totalbiaya"));

            if(!empty($billing_tindakan)){
                $this->db->query("DELETE FROM tbl_dradio WHERE noradio = '$nolaborat'");

                foreach($billing_tindakan as $btkey => $btval){
                    $data_insert	= array(
                        "noradio"		=> $nolaborat,
                        "kodetarif"		=> $btval,
                        "qty"			=> $billing_qty[$btkey],
                        "jenis"			=> $billing_cito[$btkey],
                        "tarifrs"		=> $billing_tarifrs[$btkey],
                        "tarifdr"		=> $billing_tarifdr[$btkey],
                        "cito_rp"		=> $billing_citorp[$btkey],
                        "tarif_rp"		=> $billing_tarifrp[$btkey],
                        "total_biaya"   => $billing_totalbiaya[$btkey],
                    );

                    $this->db->insert("tbl_dradio", $data_insert);
                }
            }

            // BHP
            $bhp_barang			= $this->input->post("bhp_barang");
            $bhp_qty			= $this->input->post("bhp_qty");
            $bhp_satuan			= $this->input->post("bhp_satuan");
            $bhp_harga			= str_replace(",", "", $this->input->post("bhp_harga"));
            $bhp_total			= str_replace(",", "", $this->input->post("bhp_total"));
            $bhp_gudang			= $this->input->post("bhp_gudang");
            $bhp_bill			= $this->input->post("bhp_bill");

            if(!empty($bhp_barang)){
                $this->db->query("DELETE FROM tbl_alkestransaksi WHERE notr = '$nolaborat'");

                foreach($bhp_barang as $bbkey => $bbval){

                    $data_insert	= array(
                        "koders"		=> $unit,
                        "notr"			=> $nolaborat,
                        "kodeobat"		=> $bbval,
                        "qty"			=> $bhp_qty[$bbkey],
                        "satuan"		=> $bhp_satuan[$bbkey],
                        "harga"			=> $bhp_harga[$bbkey],
                        "totalharga"    => $bhp_total[$bbkey],
                        "tgltransaksi"	=> $this->input->post("tglperiksa"),
                        "gudang"		=> $bhp_gudang[$bbkey],
                        "dibebankan"	=> $bhp_bill[$bbkey]
                    );

                    $this->db->insert("tbl_alkestransaksi", $data_insert);
                }
            }

            urut_transaksi("URUT_RADIOLOGI", 19);
			if(!empty($orderno)){
				$this->db->query("UPDATE tbl_orderperiksa SET radio = 0, radiook = 1 WHERE orderno = '$orderno'");
			}
			// $query_laboratorium	= $this->db->insert("tbl_hradio", $data_header);
            $query_laboratorium = $this->M_ro2->save($data_header);

            if($query_laboratorium){
                $status     = "success";
                $message    = "Berhasil disimpan";
            } else {
                $status     = "error";
                $message    = "Gagal disimpan (server)";
            }

            echo json_encode(array(
                "status"    => $status,
                "message"   => $message,
                "noradio"   => $nolaborat,
                "direct"    => $param == "" ? false : true
            ), JSON_UNESCAPED_SLASHES);
        }

        public function update_process($param = ""){
            $unit			= $this->session->userdata("unit");
            $replace_umur	= str_replace(array( " Tahun", " Bulan", " Hari"), array("","",""), $this->input->post("umur"));
            $explode_umur	= explode(" ", $replace_umur);

            $nolaborat		= $this->input->post('noradio');
            $orderno		= $this->input->post('orderno');

            $data_header    = [
                "koders"        => $unit,
                "noradio"       => $nolaborat,
                "radiomohon"    => "",
                "tglradio"      => $this->input->post("tglperiksa"),
                "noreg"         => $this->input->post("noreg"),
                "rekmed"        => $this->input->post("rekmed"),
                "tglfoto"       => $this->input->post("tglfoto"),
                "jamfoto"       => $this->input->post("jamfoto"),
                "drperiksa"     => $this->input->post("drlaborat"),
                "radiotype"     => "",
                "drpengirim"    => $this->input->post("drpengirim"),
                "asal"          => $this->input->post("asal"),
                "kelas"         => $this->input->post("kelas"),
                "kodepetugas"   => $this->input->post("petugas"),
                "username"      => "",
                "shipt"         => "",
                "jam"           => "",
                "dibaca"        => "",
                "bayar"         => "",
                "keluar"        => ($this->input->post("rilis") === 'on')? 1 : 0,
                "posting"       => "",
                "ambiljasa1"    => "",
                "ambiljasa2"    => "",
                "cnt_radio"     => "",
                "insentif"      => "",
                "nokwitansi"    => "",
                "tglselesai"    => $this->input->post("tglselesai"),
                "tglambil"      => $this->input->post("tglfoto"),
                "jamselesai"    => $this->input->post("jamselesai"),
                "jamambil"      => $this->input->post("jamfoto"),
                "diambiloleh"   => $this->input->post("sampeloleh"),
                "pemeriksa"     => $this->input->post("kodepemeriksa"),
                "namapas"       => $this->input->post("namapas"),
                "jpas"          => $this->input->post("jenispas"),
                "rujuk"         => $this->input->post("pemeriksaan"),
                "tgllahir"      => $this->input->post("tgllahir"),
                "umurth"        => $explode_umur[0],
                "umurbl"        => $explode_umur[1],
                "umurhr"        => $explode_umur[2],
                "jkel"          => $this->input->post("jeniskelamin"),
                "jenisperiksa"  => $this->input->post("jenisperiksa"),
                "langsung"      => "",
                "drluar"        => "",
                "useredit"      => $this->session->userdata("username"),
                "tgledit"       => date("Y-m-d H:i:s"),
                "orderno"       => $orderno,
                "diagnosa"      => $this->input->post("diagnosa"),
            ];

            // BILLING
            $billing_tindakan	= $this->input->post("billing_tindakan");
            $billing_qty		= $this->input->post("billing_qty");
            $billing_cito		= $this->input->post("billing_cito");
            $billing_tarifrs	= str_replace(",", "", $this->input->post("billing_tarifrs"));
            $billing_tarifdr	= str_replace(",", "", $this->input->post("billing_tarifdr"));
            $billing_citorp		= str_replace(",", "", $this->input->post("billing_citorp"));
            $billing_tarifrp	= str_replace(",", "", $this->input->post("billing_tarifrp"));
            $billing_totalbiaya	= str_replace(",", "", $this->input->post("billing_totalbiaya"));

            if(!empty($billing_tindakan)){
                $this->db->query("DELETE FROM tbl_dradio WHERE noradio = '$nolaborat'");

                foreach($billing_tindakan as $btkey => $btval){
                    $data_insert	= array(
                        "noradio"		=> $nolaborat,
                        "kodetarif"		=> $btval,
                        "qty"			=> $billing_qty[$btkey],
                        "jenis"			=> $billing_cito[$btkey],
                        "tarifrs"		=> $billing_tarifrs[$btkey],
                        "tarifdr"		=> $billing_tarifdr[$btkey],
                        "cito_rp"		=> $billing_citorp[$btkey],
                        "tarif_rp"		=> $billing_tarifrp[$btkey],
                        "total_biaya"   => $billing_totalbiaya[$btkey],
                    );

                    $this->db->insert("tbl_dradio", $data_insert);
                }
            }

            // BHP
            $bhp_barang			= $this->input->post("bhp_barang");
            $bhp_qty			= $this->input->post("bhp_qty");
            $bhp_satuan			= $this->input->post("bhp_satuan");
            $bhp_harga			= str_replace(",", "", $this->input->post("bhp_harga"));
            $bhp_total			= str_replace(",", "", $this->input->post("bhp_total"));
            $bhp_gudang			= $this->input->post("bhp_gudang");
            $bhp_bill			= $this->input->post("bhp_bill");

            if(!empty($bhp_barang)){
                $this->db->query("DELETE FROM tbl_alkestransaksi WHERE notr = '$nolaborat'");

                foreach($bhp_barang as $bbkey => $bbval){

                    $data_insert	= array(
                        "koders"		=> $unit,
                        "notr"			=> $nolaborat,
                        "kodeobat"		=> $bbval,
                        "qty"			=> $bhp_qty[$bbkey],
                        "satuan"		=> $bhp_satuan[$bbkey],
                        "harga"			=> $bhp_harga[$bbkey],
                        "totalharga"    => $bhp_total[$bbkey],
                        "tgltransaksi"	=> $this->input->post("tglperiksa"),
                        "gudang"		=> $bhp_gudang[$bbkey],
                        "dibebankan"	=> $bhp_bill[$bbkey]
                    );

                    $this->db->insert("tbl_alkestransaksi", $data_insert);
                }
            }

            // CATATAN

            $catatan_expertise  = $this->input->post("expertise");
            $check_expertise    = $this->M_ro2->expertise_check($nolaborat);

            if($check_expertise->num_rows() == 0){
                $this->M_ro2->expertise_save(array("noradio" => $nolaborat, "catatan" => $catatan_expertise));
            } else {
                $this->M_ro2->expertise_update(array("noradio" => $nolaborat, "catatan" => $catatan_expertise), $nolaborat);
            }

            // FILES

            $file_key			= $this->input->post("file_key");
			$file_keterangan 	= $this->input->post("file_keterangan");
			$file				= isset($_FILES["file"]["name"])? $_FILES["file"]["name"] : "" ;

			if($file != ""){
				foreach($file_key as $fkey => $fval){
					$file_allowed   = array("pdf", "png", "jpg", "jpeg", "webp");
					$file_ext       = explode(".", $file[$fkey]);
					$file_extension = strtolower(end($file_ext));
					$file_tmp       = $_FILES["file"]["tmp_name"][$fkey];

					if(in_array($file_extension, $file_allowed) === true){
						$filename = unique_file("assets/img/radiologi/", basename($file[$fkey]));
						
						move_uploaded_file($file_tmp, "assets/img/radiologi/". $filename);

						$data_insert	= [
							"noradio"			=> $nolaborat,
							"namafile"			=> $filename,
							"keteranganfile"	=> $file_keterangan[$fkey],
							"lokasifile"		=> "assets/img/radiologi/",
						];

						$query_update = $this->db->insert("tbl_dradiofile", $data_insert);

						if($query_update){
							$status		= "success";
							$message	= "File berhasil di upload";
						} else {
							$status		= "error";
							$message	= "File gagal diupload";
						}
					} else {
						$status		= "error";
						$message	= "Ekstensi file tidak valid";
					}
				}
			}

            $query_laboratorium	= $this->db->update("tbl_hradio", $data_header, array("noradio" => $nolaborat));
            $query_laboratorium = $this->M_ro2->update($data_header, $nolaborat);

            if($query_laboratorium){
                $status     = "success";
                $message    = "Berhasil disimpan";
            } else {
                $status     = "error";
                $message    = "Gagal disimpan (server)";
            }

            echo json_encode(array(
                "status"    => $status,
                "message"   => $message,
                "noradio"   => $nolaborat,
                "direct"    => $param == "" ? false : true
            ), JSON_UNESCAPED_SLASHES);
        }

        public function delete_files($id){
            if($id == ""){
                $status     = "error";
                $message    = "ID atau No Radiologi tidak ditemukan";
            } else {
                
                $file           = data_master("tbl_dradiofile", array("id" => $id))->namafile;
                $delete_file    = $this->M_ro2->delete_file($id);

                if($delete_file){
                    unlink("assets/img/radiologi/". $file);

                    $status     = "success";
                    $message    = "File berhasil dihapus";
                } else {
                    $status     = "error";
                    $message    = "Gagal menghapus file<br />Kesalahan sistem (server)";
                }

            }

            echo json_encode(array(
                "status"    => $status,
                "message"   => $message
            ), JSON_UNESCAPED_SLASHES);
        }

        // PRINT

        public function cetak_hasil($param){

            $chari      = "";
            $unit       = $this->session->userdata("unit");
            $judul      = "HASIL RADIOLOGI";
            $head		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'")->row();
            $avatar   	= $this->session->userdata('avatar_cabang');
            $source     = $this->M_ro2->data_order($param);
            $data       = $source->row();
            $catatan    = $this->M_ro2->get_expertise($param)->row();

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_addr2	= $head->alamat2;
			$comp_phone	= $head->phone;
			$comp_wa	= $head->whatsapp;
			$comp_npwp	= $head->npwp;
			$comp_image	= base_url()."assets/img_user/$avatar";
            
            if($source->num_rows() == 0){
                $this->session->set_flashdata("error", "Data tidak ditemukan<br />Cetakan gagal dibuka");
                redirect(base_url("ro2"));
            } else {

                $chari  .= "<style>
                    .table {border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto}
                    .bordered {padding:5px;border:1px solid #222}
                    .bt {padding-top:5px}
                    .bm {padding-bottom:5px}
                    .centered {text-align:center;margin:auto}
                    .bold {font-weight:bold}
                    .subtitle {font-size:12px;padding-bottom:15px !important}
                    .title {font-size:16px;margin-top:10px;margin-bottom:20px}
                    .separator {border:115px solid #222}
                </style>";

                $chari  .= '<table class="table" align="center">	
                    <thead>
                        <tr>
                            <td rowspan="4">
                                <img src="'. $comp_image .'"  width="100" height="70" />
                            </td>
                            <td>
                                <tr><td style="font-size:14px;border-bottom: none;"><b>'. $comp_name .'</b></td></tr>
                                <tr><td style="font-size:13px;">'. $comp_addr .'</td></tr>
                                <tr><td style="font-size:13px;">Telp : '. $comp_phone .'</td></tr>
                            </td>
                        </tr> 
                    </thead>
                </table>';

                $chari  .= "<hr class=\"separator\">";
                $chari  .= "<table class='table'>
                    <tbody>
                        <tr>
                            <td style='width:15%'>Nama Pasien</td>
                            <td style='width:35%'>: <b>". $data->namapas ."</b></td>
                            <td style='width:15%'>No Pemeriksaan</td>
                            <td style='width:35%'>: <b>". $data->noradio ."</b></td>
                        </tr>
                        <tr>
                            <td style='width:15%'>Umur / Kelamin</td>
                            <td style='width:35%'>: <b>". $data->umurth ." Tahun ". $data->umurbl ." Bulan ". $data->umurhr ." Hari</b> / <b>". ($data->jkel == "1" ? "Pria" : "Wanita") ."</b></td>
                            <td style='width:15%'>No Rekam Medis</td>
                            <td style='width:35%'>: <b>". $data->rekmed ."</b></td>
                        </tr>
                        <tr>
                            <td style='width:15%'>Tanggal Periksa</td>
                            <td style='width:35%'>: <b>". date("d/m/Y", strtotime($data->tglradio)) ."</b></td>
                            <td style='width:15%'>Dr Pengirim</td>
                            <td style='width:35%'>: <b>". data_master("dokter", array("koders" => $data->koders, "kodokter" => $data->drpengirim, "kopoli" => $data->asal))->nadokter ."</b></td>
                        </tr>
                        <tr>
                            <td style='width:15%'>No Foto</td>
                            <td style='width:35%'>:</td>
                            <td style='width:15%'>Keterangan Klinis</td>
                            <td style='width:35%'>:</td>
                        </tr>
                        <tr>
                            <td style='width:15%' colspan='1'>Pemeriksaan</td>
                            <td style='width:85%' colspan='3'>: <b>". ($data->rujuk == "1" ? "Radiologi Dalam" : "Dikirim ke Faskes Lain") ."</b></td>
                        </tr>
                    </tbody>
                </table>
                <hr class=\"separator\">";

                $chari  .= "<table class='table'>
                    <tbody>
                        <tr>
                            <td><b>Yth, ". data_master("tbl_setinghms", array("kodeset" => data_master("tbl_pasien", array("rekmed" => $data->rekmed))->preposisi))->keterangan ." ". $data->namapas ."</b></td>
                        </tr>
                        <tr>
                            <td>Hasil Pemeriksaan <b>". ($data->rujuk == "1" ? "Radiologi Dalam" : "Dikirim ke Faskes Lain") ."</b>:</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <table class='table'>
                    <tbody>
                        <tr>
                            <td>". nl2br($catatan->catatan) ."</td>
                        </tr>
                    </tbody>
                </table>";

                $this->M_cetak->mpdf('P','A4',$judul, $chari, $judul . '.PDF', 0, 0, 10, 2);

            }
        }

    }