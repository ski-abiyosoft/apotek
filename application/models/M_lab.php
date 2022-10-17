<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_lab extends CI_Model{

    public $view_order_lab = 'view_order_lab';
    public $pasien_daftar = 'pasien_daftar';
    public $c = 'tbl_dokter';
    public $tbl_petugas = 'tbl_petugas';
    public $tbl_hlab = 'tbl_hlab';
    public $tbl_dhasillabnew = 'tbl_dhasillabnew';
    public $tbl_hlabnotes = "tbl_hlabnotes";
    public $tbl_dhasilfile = "tbl_dhasilfile";
    public $id = 'orderno';
    public $order = 'DESC';
    public $daftar_tarif_nonbedah  = 'daftar_tarif_nonbedah' ;

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_list_eorder($date = ""){
        $unit   = $this->session->userdata("unit");

        if($date != ""){
            $query_res  = $this->db->query("SELECT 
                a.koders AS koders,
                a.orderno AS orderno, 
                a.noreg AS noreg,
                a.rekmed AS rekmed,
                a.tglorder AS tglorder,
                a.jamorder AS jamorder,
                a.kodokter AS kodokter,
                a.ordernote AS ordernote,
                a.asal AS asal,
                a.lab AS lab,
                b.namapas AS namapas,
                b.preposisi AS preposisi,
                c.nadokter AS nadokter,
                a.labonproses AS labonproses,
                a.labok AS labok
                FROM tbl_orderperiksa AS a
                LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed
                JOIN tbl_dokter AS c ON a.kodokter = c.kodokter
                WHERE a.lab = 1 
                AND a.labok = 0 
                AND a.tglorder BETWEEN '$date->start' AND '$date->end' 
                AND a.koders = '$unit'
                AND c.koders = '$unit'");
        } else {
            $query_res  = $this->db->query("SELECT 
                a.koders AS koders,
                a.orderno AS orderno, 
                a.noreg AS noreg,
                a.rekmed AS rekmed,
                a.tglorder AS tglorder,
                a.jamorder AS jamorder,
                a.kodokter AS kodokter,
                a.ordernote AS ordernote,
                a.asal AS asal,
                a.lab AS lab,
                b.namapas AS namapas,
                b.preposisi AS preposisi,
                c.nadokter AS nadokter,
                a.labonproses AS labonproses,
                a.labok AS labok
                FROM tbl_orderperiksa AS a
                LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed
                JOIN tbl_dokter AS c ON a.kodokter = c.kodokter
                WHERE a.lab = 1 
                AND a.labok = 0 
                AND a.koders = '$unit'
                AND c.koders = '$unit'");
        }

        return $query_res;
    }

    function get_list_order($date = ""){
        $unit   = $this->session->userdata("unit");
        $dnow   = date("Y-m-d");

        if($date != ""){
            $query_res  = $this->db->query("SELECT * FROM tbl_hlab WHERE tgllab BETWEEN '$date->start' AND '$date->end'");
        } else {
            $query_res  = $this->db->query("SELECT * FROM tbl_hlab WHERE tgllab LIKE '%$dnow%'");
        }

        return $query_res;
    }

    function get_all(){
        $this->db->group_by('orderno');
        // $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->view_order_lab)->result();
    }

    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_hlab)->row();
    }

    function insert($data){
        $this->db->insert($this->tbl_hlab, $data);
    }

    function update($id, $data){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_hlab, $data);
    }

    function insertDhasillabnew( $data ){
        $this->db->insert($this->tbl_dhasillabnew, $data);
    }

    function updateDhasillabnew(  $id, $data ){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_dhasillabnew, $data);
    }

    function getDhasillabnewByNolab( $nolaborat ){
        $this->db->select('*');
        $this->db->where('nolaborat', $nolaborat );
        return $this->db->get($this->tbl_dhasillabnew );

    }

    // function noReg(){
    //     $this->db->select('noreg,rekmed,tglmasuk,namapas,nadokter,namapost,namaruang');
    //     // $this->db->order_by('noreg', $this->order);
    //     $this->db->limit(10); 
    //     return $this->db->get($this->pasien_daftar)->result();
    // }

    function dataDokter(){
        $unit   = $this->session->userdata("unit");
        $dokter = $this->db->query("SELECT * FROM dokter WHERE koders = '$unit' AND kopoli = 'LABOR'")->result();

        return $dokter;
    }

    function dataPetugas(){
        $petugas = $this->db->query("SELECT * FROM tbl_petugas WHERE kodepos = 'LABOR'")->result();

        return $petugas;
    }

    function generatekode(){

        $this->db->select('*', false);
        $this->db->order_by("id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get($this->tbl_hlab);
        

        // CEK JIKA DATA ADA
        if ($query->num_rows() <> 0) {
            $data       = $query->row();
            $cek  = substr($data->nolaborat, -11);
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
        $this->db->where('kodepos', 'LABOR');
        $this->db->where('koders', $unit); 
        return $this->db->get($this->daftar_tarif_nonbedah)->result();
    }

    public function getHlabByNolaborat( $nolaborat ){    
        $this->db->select('*');
        $this->db->where('nolaborat', $nolaborat );
        return $this->db->get($this->tbl_hlab );
    }

    public function getHlabNotesByNolaborat( $nolaborat ){    
        $this->db->select('*');
        $this->db->where('nolaborat', $nolaborat );
        return $this->db->get($this->tbl_hlabnotes );
    }

    public function getDhasilFileByNolaborat( $nolaborat ){    
        $this->db->select('*');
        $this->db->where('nolaborat', $nolaborat );
        return $this->db->get($this->tbl_dhasilfile );
    }

    function insertHlabNotes( $data ){
        $this->db->insert($this->tbl_hlabnotes, $data);
    }

    function updateHlabNotes(  $id, $data ){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_hlabnotes, $data);
    }

    function insertDhasilFile( $data ){
        $this->db->insert($this->tbl_dhasilfile, $data);
    }

    function updateDhasilFile(  $id, $data ){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_dhasilfile, $data);
    }

}
