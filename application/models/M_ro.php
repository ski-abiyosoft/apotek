<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ro extends CI_Model
{
    public $view_order_rad = 'view_order_rad';
    public $pasien_daftar = 'pasien_daftar';
    public $tbl_dokter = 'tbl_dokter';
    public $tbl_petugas = 'tbl_petugas';
    public $tbl_hradio = 'tbl_hradio';
    public $id = 'orderno';
    public $order = 'DESC';
    public $daftar_tarif_nonbedah  = 'daftar_tarif_nonbedah' ;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all()
    {
        $this->db->group_by('orderno');
        $this->db->limit(1);
        // $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->view_order_rad)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_hradio)->row();
    }

    function insert($data)
    {
        $this->db->insert($this->tbl_hradio, $data);
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->tbl_hradio, $data);
    }

    function noReg()
    {
        $this->db->select('noreg,rekmed,tglmasuk,namapas,nadokter,namapost,namaruang');
        // $this->db->order_by('noreg', $this->order);
        $this->db->limit(10); 
        return $this->db->get($this->pasien_daftar)->result();
    }

    function dataDokter()
    {
        $this->db->select('nadokter,kodokter');
        return $this->db->get($this->tbl_dokter)->result();
    }

    function dataPetugas()
    {
        $this->db->select('nokk,namapetugas');
        return $this->db->get($this->tbl_petugas)->result();
    }


    function generatekode()
    {

        $this->db->select('*', false);
        $this->db->order_by("id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get($this->tbl_hradio);
        

        // CEK JIKA DATA ADA
        if ($query->num_rows() <> 0) {
            $data       = $query->row();
            $cek  = substr($data->noradio, -11);
            $kodeReq  = sprintf("%011s", intval($cek) + 1);
            $data = $query->row();
        } else {
            $kodeReq  = 00000000001;
        }

        $lastKode = str_pad($kodeReq, 4, "0", STR_PAD_LEFT);

        $newKode  = $this->session->userdata("unit") . '' . date('Y') . '' . $kodeReq;

        return $newKode;
    }

    public function get_tindakan(){
        $unit = $this->session->userdata("unit");
        $this->db->where('kodepos', 'RADIO');
        $this->db->where('koders', $unit); 
        return $this->db->get($this->daftar_tarif_nonbedah)->result();
    }
}
