<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_acc_keluar extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->session->set_userdata('menuapp', '100');
		$this->session->set_userdata('submenuapp', '104');		
		
	}

	public function index()
	{
	   $cek = $this->session->userdata('level');		
	   if(!empty($cek))
	   {	
		$unit = $this->session->userdata('unit');	
		
		//if(!empty($unit))
		{
		   $query =
			  "select a.keluar_register, a.keluar_tanggal,  a.keluar_uraian, sum(b.keluard_jumlah) as jumlah,
			  a.keluar_status, a.keluar_acc1, keluar_acc1_user, a.keluar_acc2, keluar_acc2_user, keluar_userentry, keluar_cekgirotanggal
				from
				   tr_pengeluaran a,
				   tr_pengeluarand b
				where
				   a.keluar_register=b.keluard_register and
				   a.keluar_acc2 is null and
				   a.keluar_kodepasar = '$unit'
				group by
				   a.keluar_tanggal, a.keluar_nomor, a.keluar_uraian, a.keluar_status
				order by a.keluar_nomor desc";  
		} 
				
		$d['keu'] = $this->db->query($query)->result();		
		$this->load->view('keuangan/v_keuangan_acc_keluar',$d);
	   } else
	   {
		   header('location:'.base_url());
	   }		   
	}
		
		
	
	
	
	public function approv($kode)
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{						
	              $userid  = $this->session->userdata('username');
                  $tanggal = date('Y-m-d');	
				  
				  $query = "select keluar_acc1_user, keluar_nomor from tr_pengeluaran where keluar_register = '$kode'";
				  $data  = $this->db->query($query)->result();
				  foreach($data as $row){
					$nomor_bukti = $row->keluar_nomor;
                    $acc_user    = $row->keluar_acc1_user;					
				  }
				  
				  if (empty($acc_user)||$acc_user=="")
				  {
					$this->db->query("update tr_pengeluaran set keluar_status=2, keluar_acc1 = '$tanggal', keluar_acc1_user='$userid' where keluar_register= '$kode'");					
				  } else
				  {
					$this->db->query("update tr_pengeluaran set keluar_status=3, keluar_acc2 = '$tanggal', keluar_acc2_user='$userid' where keluar_register= '$kode'");
					
					$query = "select keluar_uraian, keluar_kasbank, keluar_tanggal, keluar_kodepasar, keluar_nomor, keluar_cekgirotanggal from tr_pengeluaran where keluar_register='$kode'";
					$data  = $this->db->query($query)->result();
					foreach($data as $row){
						$keterangan = $row->keluar_uraian;
						$kasbank    = $row->keluar_kasbank;
						$tanggal    = $row->keluar_cekgirotanggal;
						$unit       = $row->keluar_kodepasar;
						$nobukti    = $row->keluar_nomor;
					}
					$tanggal = date('Y-m-d',strtotime($tanggal));
					$thn = date('y',strtotime($tanggal));
					$bln = date('m',strtotime($tanggal));		
					
					$nojurnal     = $this->M_global->nomor_jurnal($unit,'JK',$thn,$bln);  
					$akunkasbank  = $this->M_global->_akunbank($kasbank);
										
					
					$data = array(
						'nojurnal' => $nojurnal,
						'novoucher' => $nobukti,
						'tanggal' => $tanggal,
						'noref' => $kode,
						'keterangan' => $keterangan,
						'jenis' => 'JK',
						'kodepasar' => $unit,				
					);
					$this->M_global->input_data($data,"tr_jurnalh");
					

					//debet
					$query = "select sum(keluard_jumlah) as jumlah, count(*) as baris from tr_pengeluarand where keluard_register='$kode'";
					$data  = $this->db->query($query)->result();
					foreach($data as $row){
					  $jumlah = $row->jumlah;
					  $baris  = $row->baris;
					}
					$baris++;
					
					$query = "insert into tr_jurnald(nojurnal, novoucher, nourut, kodeakun, debet, kredit, tanggal, keterangan)
							  select '$nojurnal','$nomor_bukti',keluard_nourut, keluard_akun, keluard_jumlah, 0,  '$tanggal',keluard_uraian from tr_pengeluarand where keluard_register = '$kode'";
					$this->db->query($query);
					
					$query = "insert into tr_jurnald(nojurnal, novoucher, nourut, kodeakun, debet, kredit, tanggal, keterangan)
							 values('$nojurnal','$nomor_bukti',$baris, '$akunkasbank', 0, '$jumlah', '$tanggal','$keterangan')";
					$this->db->query($query);
					
					
					$this->M_global->update_idjurnal();	
                  }					
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
}

/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */