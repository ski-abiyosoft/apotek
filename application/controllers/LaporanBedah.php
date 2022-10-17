<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class LaporanBedah extends CI_Controller{
    public function Laporan($id){
		$cek = $this->session->userdata('level');
		$unit= $this->session->userdata('unit');

        $query = $this->db->query("SELECT b.namapas, a.rekmed, a.tgloperasi, a.nojadwal, a.dranestasi, a.asdrsirkule , e.anestesi, 
        c.nadokter, b.tgllahir, d.tindakan, e.nojadwal FROM tbl_hbedahjadwal AS a
        LEFT JOIN tbl_pasien AS b ON b.rekmed = a.rekmed
        LEFT JOIN tbl_dokter AS c ON c.kodokter = a.droperator
        LEFT JOIN tbl_tarifh AS d ON a.kodetarif = d.kodetarif
        LEFT JOIN tbl_hbedahpra AS e ON a.nojadwal = e.nojadwal
        WHERE a.nojadwal = '$id'")->row();

        // $dokter = $this->db->query("SELECT * FROM tbl_dokter");
        // var_dump($dokter);die;
        
        $data=[
			'title'				=> 'Laporan Bedah',
			'menu'				=> 'Bedah Central',
            'pasien'            => $query,
            'nojadwal'          => $id,
        ];
		
        $this->load->view('LaporanBedah/v_laporanBedah', $data);
    }
    public function simpan($id){
        
        $cek = $this->db->get_where('tbl_hbedahpra', ['nojadwal' => $id])->row_array();
        if($cek){
            $data=[
                // "nojadwal"      => $this->input->post("nojadwal"),
                "namapasi"		=> $this->input->post("namaPas"),
                // "tgllahir"		=> $this->input->post("tglLahir"),
                // "rekmed"		=> $this->input->post("noRM"),
                // "umur"		=> $this->input->post("lupumur"),
                // "nadokter"		=> $this->input->post("drBedah"),
                // "tindakan"		=> $this->input->post("tindakan"),
            ];
            $where= [
                'nojadwal' => $id,
            ];
        } else {
            $dataUpdate = $this->db->insert('tbl_hbedahpra', $where);
        }
        
        if($dataUpdate){
            echo json_encode(array("status" => 1));
        }else{
            echo json_encode(array("status" => 0));
        }
        redirect('/Bedah_Central');
        
    }
    public function update($id){
        $query = $this->db->query("SELECT b.namapas, a.rekmed, a.tgloperasi, a.nojadwal, a.dranestasi, a.asdrsirkule, 
        c.nadokter, b.tgllahir, d.tindakan, e.nojadwal FROM tbl_hbedahjadwal AS a
        LEFT JOIN tbl_pasien AS b ON b.rekmed = a.rekmed
        LEFT JOIN tbl_dokter AS c ON c.kodokter = a.droperator
        LEFT JOIN tbl_tarifh AS d ON a.kodetarif = d.kodetarif
        LEFT JOIN tbl_hbedahpra AS e ON a.nojadwal = e.nojadwal");
        // var_dump($query);die;
        $unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
        $username	= $this->session->userdata("username");

        $data=[
            "nojadwal"      => $this->input->post("nojadwal"),
            "namapas"		=> $this->input->post("namaPas"),
            "tgllahir"		=> $this->input->post("tglLahir"),
            "rekmed"		=> $this->input->post("noRM"),
            // "umur"		=> $this->input->post("lupumur"),
            "nadokter"		=> $this->input->post("drBedah"),
            "tindakan"		=> $this->input->post("tindakan"),
        ];

        $where= [
            'nojadwal' => $id,
        ];
        
        // $this->db->where('nojadwal', $data['nojadwal']);
        
        $dataUpdate = $this->db->update($data, $where);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Data changed successfully');
        }
        redirect('LaporanBedah/Laporan');
    }
    public function update_cek()
    {
        $namapas =  $this->input->post("namaPas");
        $tgllahir =  $this->input->post("tglLahir");
        $rekmed =  $this->input->post("noRM");
        $nadokter =  $this->input->post("drBedah");
        $tindakan =  $this->input->post("tindakan");
        $where= [
            'nojadwal' => $id,
        ];
        $data=[
        $this->db->update('tbl_pasien', $namapas),
        $this->db->update('tbl_pasien', $tgllahir),
        $this->db->update('tbl_hbedahjadwal', $rekmed),
        $this->db->update('tbl_dokter', $nadokter),
        $this->db->update('tbl_tarifh', $tindakan)
        ];

       
        if($data){
            echo json_encode(array("status" => 1));
        }else{
            echo json_encode(array("status" => 0));
        }
        redirect('/Bedah_Central');
    }
}