<?php

defined("BASEPATH") or die("No direct script access allowed");

class Pembuatan_voucher extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("M_pembuatan_voucher", "M_global", "M_penjamin"));
        $this->session->set_userdata('menuapp', '4000');
        $this->session->set_userdata('submenuapp', '4306');
        $this->load->helper(array("app_global", "simkeu_nota1", "simkeu_nota"));
    }

    public function index()
    {
        $cek = $this->session->userdata('level');
        $unit = $this->session->userdata('unit');
        $period = date("Y") . "-" . date("m") . "-";
        $bulan          = $this->M_global->_periodebulan();
        $nbulan         = $this->M_global->_namabulan($bulan);
        $data["periode"] = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
        $data["listv"] = $this->M_pembuatan_voucher->voucher_list($unit, $period)->result();
        if (!empty($cek)) {
            $this->load->view('logistik/v_pembuatan_voucher', $data);
        } else {
            header('location:' . base_url());
        }
    }

    public function entri()
    {
        $cek = $this->session->userdata('level');
        $data["cabang"] = $this->M_global->getcabang_selected($this->session->userdata('unit'))->row();
        $data["penjamin"] = $this->M_penjamin->get_penjamin();
        $data["unit"] = $this->session->userdata('unit');
        if (!empty($cek)) {
            $this->load->view("logistik/v_pembuatan_voucher_add", $data);
        } else {
            header('location:' . base_url());
        }
    }

    public function edit($param)
    {
        $cek = $this->session->userdata('level');
        $check_param = $this->db->query("SELECT * FROM tbl_voch WHERE nokir = '$param'");
        if ($check_param->num_rows() == 0) {
            redirect(base_url("/pembuatan_voucher"));
        } else {
            $data["cabang"]         = $this->M_global->getcabang_selected($this->session->userdata('unit'))->row();
            $data["penjamin"]       = $this->M_penjamin->get_penjamin();
            $data["unit"]           = $this->session->userdata('unit');
            $data["get_voucher"]    = $check_param->row();
            $data["listv"]          = $this->M_pembuatan_voucher->get_byid($param)->result();
            if (!empty($cek)) {
                $this->load->view("logistik/v_pembuatan_voucher_edit", $data);
            } else {
                header("location:" . base_url());
            }
        }
    }

    public function filter($param)
    {
        $cek                = $this->session->userdata('level');
        $unit               = $this->session->userdata('unit');

        $period_length      = explode("~", $param);
        $dari_Periode       = date("Y-m-d", strtotime($period_length[0]));
        $ke_periode         = date("Y-m-d", strtotime($period_length[1]));
        $bulan_dari_priode  = date("n", strtotime($period_length[0]));
        $bulan              = $this->M_global->_periodebulan();

        $tahun1             =  explode("-", $period_length[0]);
        $tahun2             =  explode("-", $period_length[1]);

        $data["listv"]      = $this->M_pembuatan_voucher->filter($unit, $dari_Periode, $ke_periode)->result();

        $bulan              = $this->M_global->_periodebulan();
        $dari_bulan           = $this->M_global->_namabulan($bulan_dari_priode);
        $ke_bulan           = $this->M_global->_namabulan($bulan);
        $data["periode"]    = 'Periode ' . $dari_bulan . '-' . $tahun1[0] . ' s/d ' . $ke_bulan . '-' . $tahun2[0];
        if (!empty($cek)) {
            $this->load->view("logistik/v_pembuatan_voucher", $data);
        } else {
            header("location:" . base_url());
        }
    }

    public function get_last_number()
    {
        $unit = $this->session->userdata("unit");
        echo json_encode(array(
            "nokir" => temp_urut_transaksi("URUT_VOC", $unit, 19)
        ));
    }

    public function get_recent_number()
    {
        $unit = $this->session->userdata("unit");
        echo json_encode(array(
            "nokir" => recent_urut_transaksi("URUT_VOC", $unit, 19)
        ));
    }

    public function save()
    {
        // $this->M_pembuatan_voucher->save();

        $userid = $this->session->userdata("username");

        // $cabangvoc = $this->input->post("cabang");
        // $nokir = $this->input->post("hidenokirim");

        $koders     = $this->session->userdata("unit");
        $cabangvoc  = $this->input->post("cabang");
        $nokirim    = urut_transaksi("URUT_VOC", 19);
        $cust_id    = $this->input->post("jenisvouc");
        $ket        = $this->input->post("keterangan");
        $tglkirim   = $this->input->post("tanggal");

        $novoucher  = $this->input->post("novoucher");
        $nominal    = $this->input->post("nominal");

        $data = array(
            'koders' => $koders,
            'cabangvoc' => $cabangvoc,
            'nokir' => $nokirim,
            'ket' => $ket,
            'tglkirim' => $tglkirim,
            // 'ongkos' => 0,
            'cust_id' => $cust_id,
            'user_id' => $userid,
        );

        // print_r($data);
        // $this->db->insert('tbl_voch', $data);
        // if ($this->db->affected_rows() > 0) {
        //     for ($i = 0; $i < count($novoucher); $i++) {
        //         $data2 = array(
        //             'koders' => $koders,
        //             'nokir' => $nokirim,
        //             'novoucher' => $novoucher[$i],
        //             'tglkirim' => $tglkirim,
        //             'cabangvoc' => $cabangvoc,
        //             'nominal' => str_replace(',', '', $nominal[$i]),
        //         );
        //         $this->db->insert('tbl_vocd', $data2);
        //     }

        //     if ($this->db->affected_rows() > 0) {
        //         echo json_encode(array("status" => true));
        //     } else echo json_encode(array("status" => false));
        // } else echo json_encode(array("status" => false));

        $this->db->insert("tbl_voch", $data);
        for ($i = 0; $i < count($novoucher); $i++) {
            $cek = $this->db->query("SELECT * FROM tbl_vocd WHERE novoucher = '$novoucher[$i]'")->num_rows();
        }
        if ($cek > 0) {
            echo json_encode(['status' => 2]);
        } else {
            for ($i = 0; $i < count($novoucher); $i++) {
                $data2 = array(
                    'koders' => $koders,
                    'nokir' => $nokirim,
                    'novoucher' => $novoucher[$i],
                    'tglkirim' => $tglkirim,
                    'cabangvoc' => $cabangvoc,
                    'nominal' => str_replace(',', '', $nominal[$i]),
                );
                $this->db->insert('tbl_vocd', $data2);
            }
            echo json_encode(['status' => 1]);
        }
    }

    public function update()
    {
        $novoucher_arr    = array();
        $cabangvoc        = $this->input->post("cabang");
        $nokir            = $this->input->post("nokirim");
        $ket              = $this->input->post("keterangan");
        $tglkirim         = $this->input->post("tanggal");
        $cust_id          = $this->input->post("jenisvouc");
        $novoucher        = $this->input->post("novoucher");
        $nominal          = $this->input->post("nominal");

        foreach ($novoucher as $nvc) {
            $novoucher_arr[] = $nvc;
        }

        // $this->db->query("DELETE FROM tbl_vocd WHERE nokir = '$nokir'");
        $this->db->query("UPDATE tbl_voch SET cabangvoc = '$cabangvoc', ket = '$ket', tglkirim = '$tglkirim', cust_id = '$cust_id' WHERE nokir = '$nokir'");

        foreach ($novoucher_arr as $nvakey => $nvaval) {
            // $sql = $this->db->query("SELECT*FROM tbl_vocd where novoucher in ('$nvaval')");
            // $asg = $this->db->query($sql);

            // if(!$asg) {
            $this->db->query("INSERT INTO tbl_vocd (koders,nokir,novoucher,tglkirim,cabangvoc,nominal) VALUES ('$cabangvoc','$nokir','" . $nvaval . "','$tglkirim','$cabangvoc','" . $nominal[$nvakey] . "')");
            // }
        }
    }

    public function hapus($param)
    {
        $unit = $this->session->userdata("unit");

        $query = $this->db->query("SELECT * FROM tbl_voch WHERE nokir = '$param'");
        if ($query->num_rows() == 0) {
            echo json_encode(array("status" => 0));
        } else {
            $this->db->query("DELETE FROM tbl_vocd WHERE nokir = '$param'");
            $this->db->query("DELETE FROM tbl_voch WHERE nokir = '$param'");
            echo json_encode(array("status" => 1));
        }
    }

    public function check_hapus($param)
    {
        $check_it = $this->db->query("SELECT * FROM tbl_vocd WHERE nokir = '$param'")->row();
        if ($check_it->terjual > 0 || $check_it->terpakai > 0) {
            echo json_encode(array("status" => 0));
        } else {
            echo json_encode(array("status" => 1));
        }
    }

    public function hapus_voucher($novoucher)
    {
        $unit = $this->session->userdata("unit");
        $rand = rand(100000, 999999);
        $check_voucher = $this->db->query("SELECT * FROM tbl_vocd WHERE novoucher = '$novoucher'")->row();
        if ($check_voucher->terjual > 0 || $check_voucher->terpakai > 0) {
            echo json_encode(array("status" => 0));
        } else {
            $this->db->query("DELETE FROM tbl_vocd WHERE novoucher = '$novoucher'");
            // $this->db->query("DELETE FROM tbl_vocd WHERE novoucher = '$novoucher'");
            echo json_encode(array("status" => 1));
        }
    }

    public function cetak()
    {
        $no_kirim = $this->input->get("no_kirim");
        $check_voucher = $this->db->query("SELECT * FROM tbl_voch WHERE nokir = '$no_kirim'");
        if ($check_voucher->num_rows() == 0) {
            $this->session->set_flashdata("not_found", "Voucher Tidak Ditemukan");
            redirect("/pembuatan_voucher/");
        } else {
            $unit = $this->session->userdata('unit');
            $profile = data_master('tbl_namers', array('koders' => $unit));
            $nama_usaha = $profile->namars;
            $alamat1  = $profile->alamat;
            $alamat2  = $profile->kota;

            $query_header = $check_voucher->row();
            $query_lvoucher = $this->db->query("SELECT * FROM tbl_vocd WHERE nokir = '$no_kirim'")->result();

            /*$queryh = "select * from tbl_apohresep where resepno = '$param'";
                $queryd = "select * from tbl_apodresep where resepno = '$param'";
                $queryb = "select * from tbl_apoposting  where resepno = '$param'";

                $detil  = $this->db->query($queryd)->result();
                $header = $this->db->query($queryh)->row();
                $posting  = $this->db->query($queryb)->row();
                */
            $pdf = new simkeu_nota();

            $pdf->setID($nama_usaha, $alamat1, $alamat2);
            $pdf->setjudul('Distribusi Voucher');
            $pdf->setsubjudul('');
            $pdf->addpage("P", "A4");
            $pdf->setsize("P", "A4");
            $pdf->SetWidths(array(70, 30, 90));

            $border = array('', '', '');
            $size   = array('', '', '');
            $pdf->setfont('Arial', 'B', 18);
            $pdf->SetAligns(array('C', 'C', 'C'));
            $align = array('L', 'C', 'L');
            $style = array('U', '', '');
            $size  = array('12', '', '18');
            $max   = array(5, 5, 20);

            $judul = array('', '');
            $fc     = array('0', '0');
            $hc     = array('30', '30');
            $pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);
            $pdf->ln(0);

            $pdf->setfont('Arial', 'B', 10);
            $pdf->SetWidths(array(20, 5, 90, 30, 5, 40));
            $border = array('', '', '', '', '', '');
            $fc     = array('0', '0', '0', '0', '0', '0');

            $pdf->SetFillColor(230, 230, 230);
            $pdf->setfont('Arial', '', 9);
            $pdf->FancyRow(array('', '', '', 'No', ':', $query_header->nokir), $fc, $border);
            $pdf->FancyRow(array('Catatan', ':', $query_header->ket, 'Tanggal', ':', date("Y-m-d", strtotime($query_header->tglkirim))), $fc, $border);
            $pdf->ln(5);

            /*
                $pdf->SetFillColor(230,230,230);			
                $pdf->setfont('Arial','',9);
                $pdf->FancyRow(array('No. Resep',':',$header->resepno,'Tanggal Resep',':',date('d M Y',strtotime($header->tglresep)),'Alamat Kirim:'), $fc, $border);
                $pdf->FancyRow(array('Nama Pasien',':',$posting->namapas,'No. Registrasi',':',$header->noreg), $fc, $border);
                $pdf->FancyRow(array('No. Member',':',$header->rekmed,'Alamat',':',$data_pasien->alamat), $fc, $border);
                $pdf->ln(10);
                */

            $pdf->SetWidths(array(10, 130, 50));
            $border = array('LTBR', 'LTBR', 'LTBR');
            $align  = array('C', 'L', 'L');
            $pdf->setfont('Arial', 'B', 9);
            $pdf->SetAligns(array('L', 'L', 'R'));
            //$pdf->SetFillColor(0,0,139);
            //$pdf->settextcolor(255,255,255);
            $fc = array('0', '0', '0');
            $judul = array('NO', 'NO VOUCHER', 'NOMINAL');
            $pdf->FancyRow2(8, $judul, $fc, $border, $align);
            $border = array('', '', '');
            $pdf->setfont('Arial', '', 9);
            $tot = 0;
            $subtot = 0;
            $tdisc  = 0;
            $border = array('', '', '', '', '');
            $border = array('LTBR', 'LTBR', 'LTBR', 'LTBR');
            $align  = array('C', 'L', 'L');
            $fc = array('0', '0', '0');
            $pdf->SetFillColor(0, 0, 139);
            $pdf->settextcolor(0);
            $no = 1;
            foreach ($query_lvoucher as $listv) {
                $pdf->FancyRow(array(
                    $no,
                    $listv->novoucher,
                    number_format($listv->nominal, 0, ',', '.')
                ), $fc, $border, $align);
                $no++;
            }
            /*
                foreach($detil as $db)
                {
                $totdisc += $db->discrp;
                $tot += $db->totalrp; 
                $totsub = $tot + $totdisc;	
                $pdf->FancyRow(array(
                $no,
                $db->namabarang,
                $header->kodepel,
                number_format($db->qty, 0, ',', '.'),
                number_format($db->price, 0, ',', '.'),
                number_format($db->discrp, 0, ',', '.'),
                number_format($db->totalrp, 0, ',', '.')),$fc, $border, $align);
                
                $no++;
                }
                */
            $pdf->ln(15);

            $pdf->SetWidths(array(47, 48, 48, 47));
            $align  = array('C', 'C', 'C', 'C');
            $pdf->setfont('Arial', '', 9);
            $pdf->SetAligns(array('C', 'C', 'C', 'C'));
            $fc = array('0', '0', '0', '0');
            $pdf->FancyRow(array('', '', ' ', 'Cabang , ' . date("d -m Y")), 0, 0, $align);
            $pdf->ln(5);
            $pdf->FancyRow(array('Pemohon', 'keuangan', 'Penanggung Jawab', 'Pembukuan'), 0, 0, $align);
            $pdf->ln(15);
            $pdf->FancyRow(array('(.........................)', '(.........................)', '(.........................)', '(.........................)'), 0, 0, $align);
            $pdf->ln(15);

            //$pdf->SetWidths(array(190));
            //$pdf->SetFont('Arial','',9);
            //$pdf->SetAligns(array('L'));
            //$align  = array('L');
            //$pdf->FancyRow(array('Cetak '.date('d-m-Y').' Jam : '.date('H:i:s')),0,0, $align);
            // $pdf->FancyRow(array($user.' '.$header->jam),0,$border, $align);

            $pdf->SetTitle($no_kirim);
            $pdf->AliasNbPages();
            $pdf->output($no_kirim . '.PDF', 'I');
        }
    }
}
