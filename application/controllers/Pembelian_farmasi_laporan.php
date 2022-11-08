<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_farmasi_laporan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->model('M_template_cetak');
		$this->load->model('M_Lap_Farm');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3104');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang'] = $this->db->get('tbl_namers')->result();

			$this->load->view('farmasi/v_farm_pemb_lap', $d);
		} else {
			header('location:' . base_url());
		}
	}


	function ctk_101($cek = '', $thnn = '')
	{

		// $cek    	  = '1';
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$vendor       = $this->input->get('vendor');
		$cek          = $this->input->get('cekk');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td colspan=\"2\" rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"18\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"14\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"14\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>01 LAPORAN FARMASI PEMBELIAN DETAIL</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"14\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No.</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Bapb</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Tanggal</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No.Invoice/SJ</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Kode Barang</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Nama Barang</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Qty</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Satuan</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Harga sat</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Total</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Discount</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Vat</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Total Net</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Po No</b> </td>
            </tr>
            
		</thead>";

			$sql =
				"SELECT
		a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, 
		c.namabarang,b.qty_terima,b.satuan,b.price,(b.totalrp + b.vatrp) as totalnet,b.discountrp,b.vat, b.vatrp As vatrp1, b.po_no, a.diskontotal,
		(b.qty_terima * b.price) as totalrp
		FROM tbl_baranghterima a
		JOIN tbl_barangdterima b ON b.terima_no = a.terima_no
		JOIN tbl_barang c ON c.kodebarang = b.kodebarang
		JOIN tbl_vendor d ON d.vendor_id = a.vendor_id 
		WHERE a.vendor_id='$vendor' and a.koders='$unit'
		and a.terima_date between '$tgl1' and '$tgl2'
		ORDER BY a.terima_date, a.terima_no
		";


			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$terima_no    = 0;
			$terima_date  = 0;
			$invoice_no   = 0;
			$sj_no        = 0;
			$kodebarang   = 0;
			$namabarang   = 0;
			$qty_terima   = 0;
			$satuan       = 0;
			$price        = 0;
			$totalrp      = 0;
			$discount     = 0;
			$vat          = 0;
			$vatrp1        = 0;
			$po_no        = 0;




			foreach ($query1->result() as $row) {
				$lcno          = $lcno + 1;
				$c_terima_no   = $row->terima_no;
				$c_terima_date = $row->terima_date;
				$c_invoice_no  = $row->invoice_no;
				$c_sj_no       = $row->sj_no;
				$c_kodebarang  = $row->kodebarang;
				$c_namabarang  = $row->namabarang;
				$c_qty_terima  = $row->qty_terima;
				$c_satuan      = $row->satuan;
				$c_price       = number_format($row->price, 2);
				$c_totalrp     = number_format($row->totalrp, 2);
				$c_discount    = $row->discountrp;
				$c_vat         = $row->vat;
				$c_vatrp1      = $row->vatrp1;
				$c_po_no       = $row->po_no;
				$c_diskontotal       = $row->diskontotal;
				$c_totalnet       = number_format($row->totalnet, 2);


				$chari .= "<tr>
				<td align=\"center\">$lcno  </td>
				<td align=\"left\">$c_terima_no </td>
				<td align=\"left\">$c_terima_date </td>
				<td align=\"left\">$c_invoice_no / $c_sj_no</td>
				<td align=\"left\">$c_kodebarang </td>
				<td align=\"left\">$c_namabarang </td>
				<td align=\"right\">$c_qty_terima </td>
				<td align=\"left\">$c_satuan </td>
				<td align=\"right\">$c_price </td>
				<td align=\"right\">$c_totalrp </td>
				<td align=\"right\">$c_discount </td>
				<td align=\"right\">$c_vatrp1 </td>
				<td align=\"right\">$c_totalnet </td>
				<td align=\"left\">$c_po_no </td>
				</tr>";
			}


			// $jum_nil    += $nilai;



			// $jum2_nil        = angka_rp($jum_nil,0);

			// $chari .= "<tr>
			// 		<td bgcolor=\"#cccccc\" align=\"center\"><b>GRAND TOTAL </b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jumlah</b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
			// 		</tr>";
			// $chari .= "
			// 		<tr>
			// 			<td style=\"border:0\" colspan=\"3\" align=\"center\">&nbsp;</td>
			// 		</tr>";

			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN FARMASI PEMBELIAN DETAIL';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN FARMASI PEMBELIAN DETAIL</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 2);
					break;

				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}


	function ctk_106($cek = '', $thnn = '')
	{

		// $cek    	  = '1';
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$vendor       = $this->input->get('vendor');
		$cek          = $this->input->get('cekk');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td colspan=\"2\" rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"18\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>LAPORAN FARMASI PEMBELIAN DETAIL</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>No</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>koders</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>due_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_id</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_name</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>invoice_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>term</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>sj_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>gudang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kurs</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kursrate</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>proses</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>closed</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>diskontotal</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>pilih</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>lunas</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>luar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ppn</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>jenisbeli</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tgltukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>rbayar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>materai</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ongkir</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>bkemasan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kodebarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>namabarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>qty_terima</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>price</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discount</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discountrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vat</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp1</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>totalrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>counter</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>oldqty</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>po_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>exp_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>batno</b></td>
            </tr>
            
		</thead>";

			$sql = $query =
				"SELECT
		a.koders, a.terima_no, a.terima_date, a.due_date, a.vendor_id, d.vendor_name, a.invoice_no, a.term, a.sj_no, a.gudang, a.kurs, a.kursrate, a.proses, a.closed, a.diskontotal, a.pilih, a.lunas, a.luar, a.ppn, a.vatrp, a.jenisbeli, a.tgltukar, a.tukar, a.rbayar, a.materai, a.ongkir, a.bkemasan, b.kodebarang, c.namabarang, b.qty_terima, b.satuan, b.price, b.discount, b.discountrp, b.vat, b.vatrp As vatrp1, b.totalrp, b.counter, b.oldqty, b.po_no, b.exp_date, b.batno
		FROM tbl_baranghterima a
		JOIN tbl_barangdterima b ON b.terima_no = a.terima_no
		JOIN tbl_barang c ON c.kodebarang = b.kodebarang
		JOIN tbl_vendor d ON d.vendor_id = a.vendor_id 
		WHERE a.vendor_id='$vendor' and a.koders='$unit'
		ORDER BY a.terima_date, a.terima_no
		LIMIT 15
		";


			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$koders      = 0;
			$terima_no   = 0;
			$terima_date = 0;
			$due_date    = 0;
			$vendor_id   = 0;
			$vendor_name = 0;
			$invoice_no  = 0;
			$term        = 0;
			$sj_no       = 0;
			$gudang      = 0;
			$kurs        = 0;
			$kursrate    = 0;
			$proses      = 0;
			$closed      = 0;
			$diskontotal = 0;
			$pilih       = 0;
			$lunas       = 0;
			$luar        = 0;
			$ppn         = 0;
			$vatrp       = 0;
			$jenisbeli   = 0;
			$tgltukar    = 0;
			$tukar       = 0;
			$rbayar      = 0;
			$materai     = 0;
			$ongkir      = 0;
			$bkemasan    = 0;
			$kodebarang  = 0;
			$namabarang  = 0;
			$qty_terima  = 0;
			$satuan      = 0;
			$price       = 0;
			$discount    = 0;
			$discountrp  = 0;
			$vat         = 0;
			$vatrp1      = 0;
			$totalrp     = 0;
			$counter     = 0;
			$oldqty      = 0;
			$po_no       = 0;
			$exp_date    = 0;
			$batno       = 0;




			foreach ($query1->result() as $row) {
				$lcno           = $lcno + 1;
				$c_koders       = $row->koders;
				$c_terima_no    = $row->terima_no;
				$c_terima_date  = $row->terima_date;
				$c_due_date     = $row->due_date;
				$c_vendor_id    = $row->vendor_id;
				$c_vendor_name  = $row->vendor_name;
				$c_invoice_no   = $row->invoice_no;
				$c_term         = $row->term;
				$c_sj_no        = $row->sj_no;
				$c_gudang       = $row->gudang;
				$c_kurs         = $row->kurs;
				$c_kursrate     = $row->kursrate;
				$c_proses       = $row->proses;
				$c_closed       = $row->closed;
				$c_diskontotal  = $row->diskontotal;
				$c_pilih        = $row->pilih;
				$c_lunas        = $row->lunas;
				$c_luar         = $row->luar;
				$c_ppn          = $row->ppn;
				$c_vatrp        = $row->vatrp;
				$c_jenisbeli    = $row->jenisbeli;
				$c_tgltukar     = $row->tgltukar;
				$c_tukar        = $row->tukar;
				$c_rbayar       = $row->rbayar;
				$c_materai      = $row->materai;
				$c_ongkir       = $row->ongkir;
				$c_bkemasan     = $row->bkemasan;
				$c_kodebarang   = $row->kodebarang;
				$c_namabarang   = $row->namabarang;
				$c_qty_terima   = $row->qty_terima;
				$c_satuan       = $row->satuan;
				$c_price        = $row->price;
				$c_discount     = $row->discount;
				$c_discountrp   = $row->discountrp;
				$c_vat          = $row->vat;
				$c_vatrp1       = $row->vatrp1;
				$c_totalrp      = $row->totalrp;
				$c_counter      = $row->counter;
				$c_oldqty       = $row->oldqty;
				$c_po_no        = $row->po_no;
				$c_exp_date     = $row->exp_date;
				$c_batno        = $row->batno;


				$chari .= "<tr>
				<td align=\"center\">$lcno  </td>
				<td align=\"left\">$c_koders  </td>
				<td align=\"left\">$c_terima_no  </td>
				<td align=\"left\">$c_terima_date  </td>
				<td align=\"left\">$c_due_date  </td>
				<td align=\"left\">$c_vendor_id  </td>
				<td align=\"left\">$c_vendor_name  </td>
				<td align=\"left\">$c_invoice_no  </td>
				<td align=\"left\">$c_term  </td>
				<td align=\"left\">$c_sj_no  </td>
				<td align=\"left\">$c_gudang  </td>
				<td align=\"left\">$c_kurs  </td>
				<td align=\"left\">$c_kursrate  </td>
				<td align=\"left\">$c_proses  </td>
				<td align=\"left\">$c_closed  </td>
				<td align=\"left\">$c_diskontotal  </td>
				<td align=\"left\">$c_pilih  </td>
				<td align=\"left\">$c_lunas  </td>
				<td align=\"left\">$c_luar  </td>
				<td align=\"left\">$c_ppn  </td>
				<td align=\"left\">$c_vatrp  </td>
				<td align=\"left\">$c_jenisbeli  </td>
				<td align=\"left\">$c_tgltukar  </td>
				<td align=\"left\">$c_tukar  </td>
				<td align=\"left\">$c_rbayar  </td>
				<td align=\"left\">$c_materai  </td>
				<td align=\"left\">$c_ongkir  </td>
				<td align=\"left\">$c_bkemasan  </td>
				<td align=\"left\">$c_kodebarang  </td>
				<td align=\"left\">$c_namabarang  </td>
				<td align=\"left\">$c_qty_terima  </td>
				<td align=\"left\">$c_satuan  </td>
				<td align=\"left\">$c_price  </td>
				<td align=\"left\">$c_discount  </td>
				<td align=\"left\">$c_discountrp  </td>
				<td align=\"left\">$c_vat  </td>
				<td align=\"left\">$c_vatrp1  </td>
				<td align=\"left\">$c_totalrp  </td>
				<td align=\"left\">$c_counter  </td>
				<td align=\"left\">$c_oldqty  </td>
				<td align=\"left\">$c_po_no  </td>
				<td align=\"left\">$c_exp_date  </td>
				<td align=\"left\">$c_batno  </td>
				</tr>";
			}


			// $jum_nil    += $nilai;



			// $jum2_nil        = angka_rp($jum_nil,0);

			// $chari .= "<tr>
			// 		<td bgcolor=\"#cccccc\" align=\"center\"><b>GRAND TOTAL </b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jumlah</b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
			// 		</tr>";
			// $chari .= "
			// 		<tr>
			// 			<td style=\"border:0\" colspan=\"3\" align=\"center\">&nbsp;</td>
			// 		</tr>";

			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN FARMASI PEMBELIAN DETAIL';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN FARMASI PEMBELIAN DETAIL</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 2);
					break;

				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	function ctk_107($cek = '', $thnn = '')
	{

		// $cek    	  = '1';
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$vendor       = $this->input->get('vendor');
		$cek          = $this->input->get('cekk');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td colspan=\"2\" rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"18\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>LAPORAN FARMASI PEMBELIAN DETAIL</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>No</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>koders</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>due_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_id</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_name</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>invoice_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>term</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>sj_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>gudang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kurs</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kursrate</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>proses</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>closed</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>diskontotal</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>pilih</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>lunas</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>luar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ppn</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>jenisbeli</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tgltukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>rbayar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>materai</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ongkir</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>bkemasan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kodebarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>namabarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>qty_terima</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>price</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discount</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discountrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vat</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp1</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>totalrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>counter</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>oldqty</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>po_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>exp_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>batno</b></td>
            </tr>
            
		</thead>";

			$sql = $query =
				"SELECT
		a.koders, a.terima_no, a.terima_date, a.due_date, a.vendor_id, d.vendor_name, a.invoice_no, a.term, a.sj_no, a.gudang, a.kurs, a.kursrate, a.proses, a.closed, a.diskontotal, a.pilih, a.lunas, a.luar, a.ppn, a.vatrp, a.jenisbeli, a.tgltukar, a.tukar, a.rbayar, a.materai, a.ongkir, a.bkemasan, b.kodebarang, c.namabarang, b.qty_terima, b.satuan, b.price, b.discount, b.discountrp, b.vat, b.vatrp As vatrp1, b.totalrp, b.counter, b.oldqty, b.po_no, b.exp_date, b.batno
		FROM tbl_baranghterima a
		JOIN tbl_barangdterima b ON b.terima_no = a.terima_no
		JOIN tbl_barang c ON c.kodebarang = b.kodebarang
		JOIN tbl_vendor d ON d.vendor_id = a.vendor_id 
		WHERE a.vendor_id='$vendor' and a.koders='$unit'
		ORDER BY a.terima_date, a.terima_no
		LIMIT 15
		";


			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$koders      = 0;
			$terima_no   = 0;
			$terima_date = 0;
			$due_date    = 0;
			$vendor_id   = 0;
			$vendor_name = 0;
			$invoice_no  = 0;
			$term        = 0;
			$sj_no       = 0;
			$gudang      = 0;
			$kurs        = 0;
			$kursrate    = 0;
			$proses      = 0;
			$closed      = 0;
			$diskontotal = 0;
			$pilih       = 0;
			$lunas       = 0;
			$luar        = 0;
			$ppn         = 0;
			$vatrp       = 0;
			$jenisbeli   = 0;
			$tgltukar    = 0;
			$tukar       = 0;
			$rbayar      = 0;
			$materai     = 0;
			$ongkir      = 0;
			$bkemasan    = 0;
			$kodebarang  = 0;
			$namabarang  = 0;
			$qty_terima  = 0;
			$satuan      = 0;
			$price       = 0;
			$discount    = 0;
			$discountrp  = 0;
			$vat         = 0;
			$vatrp1      = 0;
			$totalrp     = 0;
			$counter     = 0;
			$oldqty      = 0;
			$po_no       = 0;
			$exp_date    = 0;
			$batno       = 0;




			foreach ($query1->result() as $row) {
				$lcno           = $lcno + 1;
				$c_koders       = $row->koders;
				$c_terima_no    = $row->terima_no;
				$c_terima_date  = $row->terima_date;
				$c_due_date     = $row->due_date;
				$c_vendor_id    = $row->vendor_id;
				$c_vendor_name  = $row->vendor_name;
				$c_invoice_no   = $row->invoice_no;
				$c_term         = $row->term;
				$c_sj_no        = $row->sj_no;
				$c_gudang       = $row->gudang;
				$c_kurs         = $row->kurs;
				$c_kursrate     = $row->kursrate;
				$c_proses       = $row->proses;
				$c_closed       = $row->closed;
				$c_diskontotal  = $row->diskontotal;
				$c_pilih        = $row->pilih;
				$c_lunas        = $row->lunas;
				$c_luar         = $row->luar;
				$c_ppn          = $row->ppn;
				$c_vatrp        = $row->vatrp;
				$c_jenisbeli    = $row->jenisbeli;
				$c_tgltukar     = $row->tgltukar;
				$c_tukar        = $row->tukar;
				$c_rbayar       = $row->rbayar;
				$c_materai      = $row->materai;
				$c_ongkir       = $row->ongkir;
				$c_bkemasan     = $row->bkemasan;
				$c_kodebarang   = $row->kodebarang;
				$c_namabarang   = $row->namabarang;
				$c_qty_terima   = $row->qty_terima;
				$c_satuan       = $row->satuan;
				$c_price        = $row->price;
				$c_discount     = $row->discount;
				$c_discountrp   = $row->discountrp;
				$c_vat          = $row->vat;
				$c_vatrp1       = $row->vatrp1;
				$c_totalrp      = $row->totalrp;
				$c_counter      = $row->counter;
				$c_oldqty       = $row->oldqty;
				$c_po_no        = $row->po_no;
				$c_exp_date     = $row->exp_date;
				$c_batno        = $row->batno;


				$chari .= "<tr>
				<td align=\"center\">$lcno  </td>
				<td align=\"left\">$c_koders  </td>
				<td align=\"left\">$c_terima_no  </td>
				<td align=\"left\">$c_terima_date  </td>
				<td align=\"left\">$c_due_date  </td>
				<td align=\"left\">$c_vendor_id  </td>
				<td align=\"left\">$c_vendor_name  </td>
				<td align=\"left\">$c_invoice_no  </td>
				<td align=\"left\">$c_term  </td>
				<td align=\"left\">$c_sj_no  </td>
				<td align=\"left\">$c_gudang  </td>
				<td align=\"left\">$c_kurs  </td>
				<td align=\"left\">$c_kursrate  </td>
				<td align=\"left\">$c_proses  </td>
				<td align=\"left\">$c_closed  </td>
				<td align=\"left\">$c_diskontotal  </td>
				<td align=\"left\">$c_pilih  </td>
				<td align=\"left\">$c_lunas  </td>
				<td align=\"left\">$c_luar  </td>
				<td align=\"left\">$c_ppn  </td>
				<td align=\"left\">$c_vatrp  </td>
				<td align=\"left\">$c_jenisbeli  </td>
				<td align=\"left\">$c_tgltukar  </td>
				<td align=\"left\">$c_tukar  </td>
				<td align=\"left\">$c_rbayar  </td>
				<td align=\"left\">$c_materai  </td>
				<td align=\"left\">$c_ongkir  </td>
				<td align=\"left\">$c_bkemasan  </td>
				<td align=\"left\">$c_kodebarang  </td>
				<td align=\"left\">$c_namabarang  </td>
				<td align=\"left\">$c_qty_terima  </td>
				<td align=\"left\">$c_satuan  </td>
				<td align=\"left\">$c_price  </td>
				<td align=\"left\">$c_discount  </td>
				<td align=\"left\">$c_discountrp  </td>
				<td align=\"left\">$c_vat  </td>
				<td align=\"left\">$c_vatrp1  </td>
				<td align=\"left\">$c_totalrp  </td>
				<td align=\"left\">$c_counter  </td>
				<td align=\"left\">$c_oldqty  </td>
				<td align=\"left\">$c_po_no  </td>
				<td align=\"left\">$c_exp_date  </td>
				<td align=\"left\">$c_batno  </td>
				</tr>";
			}


			// $jum_nil    += $nilai;



			// $jum2_nil        = angka_rp($jum_nil,0);

			// $chari .= "<tr>
			// 		<td bgcolor=\"#cccccc\" align=\"center\"><b>GRAND TOTAL </b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jumlah</b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
			// 		</tr>";
			// $chari .= "
			// 		<tr>
			// 			<td style=\"border:0\" colspan=\"3\" align=\"center\">&nbsp;</td>
			// 		</tr>";

			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN FARMASI PEMBELIAN DETAIL';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN FARMASI PEMBELIAN DETAIL</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 2);
					break;

				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	function ctk_108($cek = '', $thnn = '')
	{

		// $cek    	  = '1';
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$vendor       = $this->input->get('vendor');
		$cek          = $this->input->get('cekk');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td colspan=\"2\" rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"18\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>LAPORAN FARMASI PEMBELIAN DETAIL</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>No</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>koders</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>terima_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>due_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_id</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vendor_name</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>invoice_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>term</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>sj_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>gudang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kurs</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kursrate</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>proses</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>closed</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>diskontotal</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>pilih</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>lunas</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>luar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ppn</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>jenisbeli</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tgltukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>tukar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>rbayar</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>materai</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>ongkir</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>bkemasan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>kodebarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>namabarang</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>qty_terima</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>price</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discount</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>discountrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vat</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>vatrp1</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>totalrp</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>counter</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>oldqty</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>po_no</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>exp_date</b></td>
			<td bgcolor=\"#cccccc\" align=\"center\"><b>batno</b></td>
            </tr>
            
		</thead>";

			$sql = $query =
				"SELECT
		a.koders, a.terima_no, a.terima_date, a.due_date, a.vendor_id, d.vendor_name, a.invoice_no, a.term, a.sj_no, a.gudang, a.kurs, a.kursrate, a.proses, a.closed, a.diskontotal, a.pilih, a.lunas, a.luar, a.ppn, a.vatrp, a.jenisbeli, a.tgltukar, a.tukar, a.rbayar, a.materai, a.ongkir, a.bkemasan, b.kodebarang, c.namabarang, b.qty_terima, b.satuan, b.price, b.discount, b.discountrp, b.vat, b.vatrp As vatrp1, b.totalrp, b.counter, b.oldqty, b.po_no, b.exp_date, b.batno
		FROM tbl_baranghterima a
		JOIN tbl_barangdterima b ON b.terima_no = a.terima_no
		JOIN tbl_barang c ON c.kodebarang = b.kodebarang
		JOIN tbl_vendor d ON d.vendor_id = a.vendor_id 
		WHERE a.vendor_id='$vendor' and a.koders='$unit'
		ORDER BY a.terima_date, a.terima_no
		LIMIT 15
		";


			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$koders      = 0;
			$terima_no   = 0;
			$terima_date = 0;
			$due_date    = 0;
			$vendor_id   = 0;
			$vendor_name = 0;
			$invoice_no  = 0;
			$term        = 0;
			$sj_no       = 0;
			$gudang      = 0;
			$kurs        = 0;
			$kursrate    = 0;
			$proses      = 0;
			$closed      = 0;
			$diskontotal = 0;
			$pilih       = 0;
			$lunas       = 0;
			$luar        = 0;
			$ppn         = 0;
			$vatrp       = 0;
			$jenisbeli   = 0;
			$tgltukar    = 0;
			$tukar       = 0;
			$rbayar      = 0;
			$materai     = 0;
			$ongkir      = 0;
			$bkemasan    = 0;
			$kodebarang  = 0;
			$namabarang  = 0;
			$qty_terima  = 0;
			$satuan      = 0;
			$price       = 0;
			$discount    = 0;
			$discountrp  = 0;
			$vat         = 0;
			$vatrp1      = 0;
			$totalrp     = 0;
			$counter     = 0;
			$oldqty      = 0;
			$po_no       = 0;
			$exp_date    = 0;
			$batno       = 0;




			foreach ($query1->result() as $row) {
				$lcno           = $lcno + 1;
				$c_koders       = $row->koders;
				$c_terima_no    = $row->terima_no;
				$c_terima_date  = $row->terima_date;
				$c_due_date     = $row->due_date;
				$c_vendor_id    = $row->vendor_id;
				$c_vendor_name  = $row->vendor_name;
				$c_invoice_no   = $row->invoice_no;
				$c_term         = $row->term;
				$c_sj_no        = $row->sj_no;
				$c_gudang       = $row->gudang;
				$c_kurs         = $row->kurs;
				$c_kursrate     = $row->kursrate;
				$c_proses       = $row->proses;
				$c_closed       = $row->closed;
				$c_diskontotal  = $row->diskontotal;
				$c_pilih        = $row->pilih;
				$c_lunas        = $row->lunas;
				$c_luar         = $row->luar;
				$c_ppn          = $row->ppn;
				$c_vatrp        = $row->vatrp;
				$c_jenisbeli    = $row->jenisbeli;
				$c_tgltukar     = $row->tgltukar;
				$c_tukar        = $row->tukar;
				$c_rbayar       = $row->rbayar;
				$c_materai      = $row->materai;
				$c_ongkir       = $row->ongkir;
				$c_bkemasan     = $row->bkemasan;
				$c_kodebarang   = $row->kodebarang;
				$c_namabarang   = $row->namabarang;
				$c_qty_terima   = $row->qty_terima;
				$c_satuan       = $row->satuan;
				$c_price        = $row->price;
				$c_discount     = $row->discount;
				$c_discountrp   = $row->discountrp;
				$c_vat          = $row->vat;
				$c_vatrp1       = $row->vatrp1;
				$c_totalrp      = $row->totalrp;
				$c_counter      = $row->counter;
				$c_oldqty       = $row->oldqty;
				$c_po_no        = $row->po_no;
				$c_exp_date     = $row->exp_date;
				$c_batno        = $row->batno;


				$chari .= "<tr>
				<td align=\"center\">$lcno  </td>
				<td align=\"left\">$c_koders  </td>
				<td align=\"left\">$c_terima_no  </td>
				<td align=\"left\">$c_terima_date  </td>
				<td align=\"left\">$c_due_date  </td>
				<td align=\"left\">$c_vendor_id  </td>
				<td align=\"left\">$c_vendor_name  </td>
				<td align=\"left\">$c_invoice_no  </td>
				<td align=\"left\">$c_term  </td>
				<td align=\"left\">$c_sj_no  </td>
				<td align=\"left\">$c_gudang  </td>
				<td align=\"left\">$c_kurs  </td>
				<td align=\"left\">$c_kursrate  </td>
				<td align=\"left\">$c_proses  </td>
				<td align=\"left\">$c_closed  </td>
				<td align=\"left\">$c_diskontotal  </td>
				<td align=\"left\">$c_pilih  </td>
				<td align=\"left\">$c_lunas  </td>
				<td align=\"left\">$c_luar  </td>
				<td align=\"left\">$c_ppn  </td>
				<td align=\"left\">$c_vatrp  </td>
				<td align=\"left\">$c_jenisbeli  </td>
				<td align=\"left\">$c_tgltukar  </td>
				<td align=\"left\">$c_tukar  </td>
				<td align=\"left\">$c_rbayar  </td>
				<td align=\"left\">$c_materai  </td>
				<td align=\"left\">$c_ongkir  </td>
				<td align=\"left\">$c_bkemasan  </td>
				<td align=\"left\">$c_kodebarang  </td>
				<td align=\"left\">$c_namabarang  </td>
				<td align=\"left\">$c_qty_terima  </td>
				<td align=\"left\">$c_satuan  </td>
				<td align=\"left\">$c_price  </td>
				<td align=\"left\">$c_discount  </td>
				<td align=\"left\">$c_discountrp  </td>
				<td align=\"left\">$c_vat  </td>
				<td align=\"left\">$c_vatrp1  </td>
				<td align=\"left\">$c_totalrp  </td>
				<td align=\"left\">$c_counter  </td>
				<td align=\"left\">$c_oldqty  </td>
				<td align=\"left\">$c_po_no  </td>
				<td align=\"left\">$c_exp_date  </td>
				<td align=\"left\">$c_batno  </td>
				</tr>";
			}


			// $jum_nil    += $nilai;



			// $jum2_nil        = angka_rp($jum_nil,0);

			// $chari .= "<tr>
			// 		<td bgcolor=\"#cccccc\" align=\"center\"><b>GRAND TOTAL </b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jumlah</b></td>
			// 		<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
			// 		</tr>";
			// $chari .= "
			// 		<tr>
			// 			<td style=\"border:0\" colspan=\"3\" align=\"center\">&nbsp;</td>
			// 		</tr>";

			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN FARMASI PEMBELIAN DETAIL';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN FARMASI PEMBELIAN DETAIL</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 2);
					break;

				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	function ctk_109($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor       = $this->input->get('vendor');

		$query = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE h.vendor_id = '$vendor' AND b.koders = '$unit' AND h.terima_date BETWEEN '$tgl1' AND '$tgl2'")->result();


		$vendorx = $this->db->query("SELECT c.vendor_name FROM tbl_barang AS a JOIN tbl_barangdterima AS b ON a.kodebarang = b.kodebarang JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id='$vendor' and b.koders = '$unit' limit 1 ")->result();
		// and a.terima_date between '$tgl1' and '$tgl2'

		$suplier = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id ='$vendor'")->row();


		// $Ratarata = $this->db->query();

		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data'         => $query,
			// 'rataRata'     => $rataRata,
			'suplier'	   => $suplier,
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm3', $data);
	}

	function ctk_110($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');
		$chari   	  = '';

		// $query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, 
		// (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_baranghterima AS a 
		// JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id
		// JOIN tbl_barangdterima AS c ON a.terima_no = c.terima_no
		// WHERE b.vendor_id ='$vendor' AND a.koders = '$unit' 
		// and a.terima_date between '$tgl1' and '$tgl2' group by a.terima_no")->result();

		$query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_baranghterima AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_barangdterima AS c ON a.terima_no = c.terima_no WHERE b.vendor_id ='$vendor' AND a.koders = '$unit' AND a.terima_date BETWEEN '$tgl1' AND '$tgl2' group by a.terima_no")->result();

		$data = [
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm2', $data);


		$chari .= "</table>";
		$data['prev'] = $chari;
		$judul        = 'REKAP
		PENERIMAAN BARANG BY INVOICE';

		switch ($cek) {
			case 0;
				echo ("<title>REKAP
				PENERIMAAN BARANG BY INVOICE</title>");
				echo ($chari);
				break;

			case 1;
				$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'REKAP
				PENERIMAAN_BARANG_BY_INVOICE.PDF', 10, 10, 10, 2);
				break;

			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('app/master_cetak', $data);
				break;
		}
	}
	function ctk_111($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');

		$query = $this->db->query("SELECT a.vatrp, a.materai, a.terima_date, b.discountrp, b.qty_terima , 
		b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, 
		c.vendor_name , c.vendor_id FROM tbl_baranghterima as a
		JOIN tbl_barangdterima AS b ON a.terima_no = b.terima_no
		JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id
		WHERE a.vendor_id ='$vendor' AND a.koders = '$unit' and a.terima_date between '$tgl1' and '$tgl2'")->result();

		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
		];
		$this->load->view('LaporanFarmasi/v_lap_farm4', $data);
	}
	function ctk_112($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');

		$query = $this->db->query("SELECT b.kodebarang , (select namabarang from tbl_barang where kodebarang = b.kodebarang) as namabarang , h.vendor_id, b.satuan , b.qty_terima , (b.totalrp / b.qty_terima ) AS ratarata, b.koders, h.vendor_id , (select vendor_name from tbl_vendor where vendor_id=h.vendor_id) as vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no JOIN tbl_vendor AS c ON h.vendor_id = c.vendor_id WHERE h.vendor_id = '$vendor' AND b.koders = '$unit' and h.terima_date between '$tgl1' and '$tgl2'")->result();
		// var_dump($query);die;
		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm5', $data);
	}
	function ctk_113($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');

		$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price , a.terima_date, 
		b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang , d.namabarang FROM tbl_baranghterima AS a 
		JOIN  tbl_barangdterima AS b ON a.terima_no = b.terima_no
		JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id
		JOIN tbl_barang AS d ON b.kodebarang = d.kodebarang
		WHERE a.vendor_id = '$vendor' AND  a.koders = '$unit' and 
		a.terima_date between '$tgl1' and '$tgl2';
		")->result();
		$suplier = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id ='$vendor'")->row();


		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
			'suplier' => $suplier,
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm6', $data);
	}
	function ctk_114($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');

		$query = $this->db->query("SELECT 
					a.retur_no, 
					a.retur_date, 
					a.terima_no, 
					b.kodebarang, 
					(SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, 
					b.qty_retur, 
					b.satuan, 
					b.price, 
					b.totalrp, 
					(SELECT po_no FROM tbl_barangdterima WHERE terima_no = a.terima_no AND kodebarang = b.kodebarang) AS po_no
					FROM tbl_baranghreturbeli a 
					JOIN tbl_barangdreturbeli b ON a.retur_no=b.retur_no 
					WHERE a.koders = '$unit' AND a.retur_date BETWEEN '$tgl1' AND '$tgl2' GROUP BY b.kodebarang")->result();



		// 	$query = $this->db->query("SELECT a.terima_no, a.koders, a.terima_date , b.qty_terima, b.satuan, b.price,
		// 	d.vendor_id , d.vendor_name , e.kodebarang , e.namabarang FROM tbl_baranghterima AS a 
		//    JOIN tbl_barangdterima AS b ON a.terima_no = b.terima_no
		//    JOIN tbl_vendor AS d ON a.vendor_id = d.vendor_id
		//    JOIN tbl_barang AS e ON b.kodebarang = e.kodebarang
		//    WHERE a.vendor_id = '$vendor' AND a.koders = '$unit'
		// 	  ")->result();

		$suplier = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id ='$vendor'")->row();

		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
			'suplier' => $suplier
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm7', $data);
	}
	function ctk_115($cek = '', $thnn = '')
	{
		$profile = $this->M_global->_LoadProfileLap();
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$unit = $this->session->userdata('unit');
		$vendor = $this->input->get('vendor');

		$query = $this->db->query("SELECT 
			(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghpo.vendor_id) AS rekanan,
			tbl_baranghpo.ref_no AS no_faktur,
			0 AS obat,
			0 AS alkes_rutin,
			0 AS alk_investasi,
			0 AS bahan_kimia,
			0 AS gas_medik,
			0 AS pemeliharaan,
			0 AS sewa,
			0 AS pelengkapan,
			0 AS materai,
			tbl_barangdpo.vatrp AS lain2,
			tbl_barangdpo.total AS jumlah
		FROM tbl_baranghpo
		JOIN tbl_barangdpo ON tbl_baranghpo.po_no=tbl_barangdpo.po_no
		WHERE vendor_id = '$vendor' AND tglpo BETWEEN '$tgl1' AND '$tgl2'

		UNION ALL

		SELECT
			(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghterima.vendor_id) AS rekanan,
			invoice_no AS no_faktur,
			0 AS obat,
			0 AS alkes_rutin,
			0 AS alk_investasi,
			0 AS bahan_kimia,
			0 AS gas_medik,
			0 AS pemeliharaan,
			0 AS sewa,
			0 AS pelengkapan,
			materai,
			tbl_barangdterima.vatrp AS lain2,
			tbl_barangdterima.totalrp AS jumlah
		FROM tbl_baranghterima
		JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
		WHERE vendor_id = '$vendor' AND terima_date BETWEEN '$tgl1' AND '$tgl2'

		UNION ALL

		SELECT
			(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghreturbeli.vendor_id) AS rekanan,
			invoice_no AS no_faktur,
			0 AS obat,
			0 AS alkes_rutin,
			0 AS alk_investasi,
			0 AS bahan_kimia,
			0 AS gas_medik,
			0 AS pemeliharaan,
			0 AS sewa,
			0 AS pelengkapan,
			0 AS materai,
			tbl_barangdreturbeli.taxrp AS lain2,
			tbl_barangdreturbeli.totalrp AS jumlah
		FROM tbl_baranghreturbeli
		JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no
		WHERE vendor_id = '$vendor' AND retur_date BETWEEN '$tgl1' AND '$tgl2'")->result();
		// $query = $this->db->query("SELECT a.materai , a.koders, b.vendor_id , b.vendor_name 
		// FROM tbl_baranghterima AS a
		// JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id 
		// WHERE b.vendor_id = '$vendor' AND a.koders = '$unit'
		// ")->result();

		$data = [
			'chari' => '',
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'idlp'         => $this->input->get('idlap'),
			'tgl1'         => $this->input->get('tgl1'),
			'tgl2'         => $this->input->get('tgl2'),
			'vendor'       => $this->input->get('vendor'),
			'cek'          => $this->input->get('cekk'),
			'_tgl1'        => date('Y-m-d', strtotime($tgl1)),
			'_tgl2'        => date('Y-m-d', strtotime($tgl2)),
			'_peri'        => 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)),
			'kop' => $this->M_cetak->kop($unit),
			'data' => $query,
		];
		// var_dump($data['data']);die;

		$this->load->view('LaporanFarmasi/v_lap_farm8', $data);
	}
	public function cetak()
	{
		$cek = $this->session->userdata('level');
		$idlap = $this->input->get('idlap');
		$cabang = $this->input->get('cabang');
		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');
		$vendorx = $this->input->get('vendor');
		$unit = $cabang;
		if (!empty($cek)) {
			if ($idlap == 101) {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' and";
				} else {
					$vendor = '';
				}
				$judul = '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)';
				$query = $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_baranghterima a JOIN tbl_barangdterima b ON b.terima_no = a.terima_no JOIN tbl_barang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE $vendor a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result();
			} else if ($idlap == '102') {
				if ($vendorx != '') {
					$vendor = "b.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)';
				$query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_baranghterima AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_barangdterima AS c ON a.terima_no = c.terima_no WHERE $vendor a.koders = '$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
			} else if ($idlap == '103') {
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
					$vendorr = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
					$vendorr = '';
				}
				$judul = '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM';
				$query = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2'")->result();
				$vendorx = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by h.vendor_id")->result();
			} else if ($idlap == '104') {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL';
				$query = $this->db->query("SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_baranghterima as a JOIN tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE $vendor a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
			} else if ($idlap == '105') {
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)';
				$query = $this->db->query("SELECT b.kodebarang , (select namabarang from tbl_barang where kodebarang = b.kodebarang) as namabarang , h.vendor_id, b.satuan , b.qty_terima , (b.totalrp / b.qty_terima ) AS ratarata, b.koders, h.vendor_id , (select vendor_name from tbl_vendor where vendor_id=h.vendor_id) as vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no JOIN tbl_vendor AS c ON h.vendor_id = c.vendor_id WHERE $vendor b.koders = '$unit' and h.terima_date between '$tanggal1' and '$tanggal2'")->result();
			} else if ($idlap == '106') {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '06 LAPORAN STATUS ORDER PEMBELIAN';
				$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_baranghterima AS a JOIN  tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_barang AS d ON b.kodebarang = d.kodebarang WHERE $vendor  a.koders = '$unit'  and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
			} else if ($idlap == '107') {
				$judul = '07 LAPORAN RETURN PEMBELIAN';
				$query = $this->db->query("SELECT 
					a.retur_no, 
					a.retur_date, 
					a.terima_no, 
					b.kodebarang, 
					(SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, 
					b.qty_retur, 
					b.satuan, 
					b.price, 
					b.totalrp, 
					(SELECT po_no FROM tbl_barangdterima WHERE terima_no = a.terima_no AND kodebarang = b.kodebarang) AS po_no
					FROM tbl_baranghreturbeli a 
					JOIN tbl_barangdreturbeli b ON a.retur_no=b.retur_no 
					WHERE a.koders = '$unit' AND a.retur_date BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY b.kodebarang")->result();
			} else if ($idlap == '108') {
				if ($vendorx != '') {
					$vendor = "vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '08 LAPORAN HUTANG GUDANG FARMASI';
				$query = $this->db->query("SELECT 
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghpo.vendor_id) AS rekanan,
						tbl_baranghpo.ref_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdpo.vatrp AS lain2,
						tbl_barangdpo.total AS jumlah
					FROM tbl_baranghpo
					JOIN tbl_barangdpo ON tbl_baranghpo.po_no=tbl_barangdpo.po_no
					WHERE $vendor tglpo BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghterima.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						materai,
						tbl_barangdterima.vatrp AS lain2,
						tbl_barangdterima.totalrp AS jumlah
					FROM tbl_baranghterima
					JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
					WHERE $vendor terima_date BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghreturbeli.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdreturbeli.taxrp AS lain2,
						tbl_barangdreturbeli.totalrp AS jumlah
					FROM tbl_baranghreturbeli
					JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no
					WHERE $vendor retur_date BETWEEN '$tanggal1' AND '$tanggal2'")->result();
				// $query = $this->db->query("SELECT a.materai , a.koders, b.vendor_id , b.vendor_name FROM tbl_baranghterima AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id WHERE b.vendor_id = '$vendor' AND a.koders = '$unit'")->result();
			}


			$profile = $this->M_global->_LoadProfileLap();
			$unit = $this->session->userdata('unit');
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1 = $profile->alamat;
			$alamat2 = $profile->kota;
			$pdf = new simkeu_nota();

			// header perusahaan
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			// header judul
			$pdf->setjudul($judul . ' CABANG ' . $unit);
			// header sub judul
			$pdf->setsubjudul('');

			if ($idlap == '101') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$judul = array('No', 'Bapb', 'Tanggal', 'No Invoice/SJ', 'Kode Barang', 'Nama Barang', 'Qty', 'Satuan', 'Harga Sat', 'Total', 'Diskon', 'Vat', 'Total Net', 'Po No');
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(5, 6, 'No', 1, 0, 'C');
				$pdf->Cell(25, 6, 'No BAPB', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Tanggal', 1, 0, 'C');
				$pdf->Cell(19, 6, 'No Invoice/SJ', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Kode Barang', 1, 0, 'C');
				$pdf->Cell(36, 6, 'Nama Barang', 1, 0, 'C');
				$pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
				$pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Harga Set', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Total', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Diskon', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Vat', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Total Net', 1, 0, 'C');
				$pdf->Cell(30, 6, 'Po No', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$no = 1;
				$qty = 0;
				$harga = 0;
				$total = 0;
				$diskon = 0;
				$vat = 0;
				$total_net = 0;
				foreach ($query as $q) {
					$pdf->Cell(5, 6, $no++, 1, 0, 'C');
					$pdf->Cell(25, 6, $q->terima_no, 1, 0, 'C');
					$pdf->Cell(17, 6, date('d-m-Y', strtotime($q->terima_date)), 1, 0, 'C');
					$pdf->Cell(19, 6, $q->sj_no, 1, 0, 'C');
					$pdf->Cell(19, 6, $q->kodebarang, 1, 0, 'C');
					$pdf->Cell(36, 6, $q->namabarang, 1, 0, 'L');
					$pdf->Cell(15, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
					$pdf->Cell(15, 6, $q->satuan, 1, 0, 'C');
					$pdf->Cell(19, 6, number_format($q->price, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($q->totalrp, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($q->discountrp, 2), 1, 0, 'R');
					$materai = $q->materai;
					$totalnet = $q->totalnet + $q->materai;
					$pdf->Cell(19, 6, number_format($q->vatrp1, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($totalnet, 2), 1, 0, 'R');
					$pdf->Cell(30, 6, $q->po_no, 1, 0, 'L');
					$pdf->ln();
					$qty += $q->qty_terima;
					$diskon += $q->discountrp;
					$vat += $q->vatrp1;
					$total_net += $totalnet;
					$harga += $q->price;
					$total += $q->totalrp;
				}
				$pdf->Cell(121, 6, 'TOTAL', 1, 0, 'C');
				$pdf->Cell(15, 6, number_format($qty, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, '', 1, 0, 'R');
				$pdf->Cell(19, 6, number_format($harga, 2), 1, 0, 'R');
				$pdf->Cell(19, 6, number_format($total, 2), 1, 0, 'R');
				$pdf->Cell(19, 6, number_format($diskon, 2), 1, 0, 'R');
				$pdf->Cell(19, 6, number_format($vat, 2), 1, 0, 'R');
				$pdf->Cell(19, 6, number_format($total_net, 2), 1, 0, 'R');
				$pdf->Cell(30, 6, '', 1, 0, 'R');
			} else if ($idlap == '102') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(5, 6, 'No', 1, 0, 'C');
				$pdf->Cell(50.4, 6, 'Supplier', 1, 0, 'C');
				$pdf->Cell(40.2, 6, 'No BAPB', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Tanggal', 1, 0, 'C');
				$pdf->Cell(28.2, 6, 'No Invoice/SJ', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Total', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Diskon', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Vat', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Materai', 1, 0, 'C');
				$pdf->Cell(25.2, 6, 'Total Net', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$no = 1;
				$Discount = 0;
				$vatRp = 0;
				$materai = 0;
				$total = 0;
				$totalrp = 0;
				foreach ($query as $q) {
					$pdf->Cell(5, 6, $no++, 1, 0, 'C');
					$pdf->Cell(50.4, 6, $q->vendor_name, 1, 0, 'L');
					$pdf->Cell(40.2, 6, $q->terima_no, 1, 0, 'L');
					$pdf->Cell(25.2, 6, date('d-m-Y', strtotime($q->terima_date)), 1, 0, 'L');
					$pdf->Cell(28.2, 6, $q->sj_no, 1, 0, 'L');
					$pdf->Cell(25.2, 6, number_format($q->totalrp, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($q->diskontotal, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($q->vatrp, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($q->materai, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($q->totalnet, 2), 1, 0, 'R');
					$pdf->ln();
				}
			} else if (($idlap == '103')) {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vendorx as $v) {
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(8, 6, 'No', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(35.3, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(25.3, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Harga Rata-rata', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Total', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$pdf->Cell(189.8, 6, 'SUPPLIER : ' . $v->vendor_name, 1, 0, 'L');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$total_qty = 0;
					$totalSemua = 0;
					foreach ($query as $q) {
						$pdf->Cell(8, 6, $no++, 1, 0, 'C');
						$pdf->Cell(30.3, 6, $q->kodebarang, 1, 0, 'C');
						$pdf->Cell(35.3, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(25.3, 6, $q->satuan, 1, 0, 'C');
						$pdf->Cell(30.3, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(30.3, 6, number_format($q->ratarata, 2), 1, 0, 'R');
						$total = $q->ratarata * $q->qty_terima;
						$pdf->Cell(30.3, 6, number_format($total, 2), 1, 0, 'R');
						$pdf->ln();
						$total_qty += ($q->qty_terima);
						$totalSemua += ($total);
					}
					$pdf->Cell(98.9, 6, 'SUBTOTAL SUPPLIER', 1, 0, 'L');
					$pdf->Cell(30.3, 6, number_format($total_qty, 2), 1, 0, 'R');
					$pdf->Cell(30.3, 6, '', 1, 0, 'R');
					$pdf->Cell(30.3, 6, number_format($totalSemua, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '104') {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(8, 6, 'No', 1, 0, 'C');
				$pdf->Cell(48.44, 6, 'Supplier', 1, 0, 'C');
				$pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
				$pdf->Cell(25.86, 6, 'Total', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Diskon', 1, 0, 'C');
				$pdf->Cell(25.86, 6, 'Vat Rp', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Materai', 1, 0, 'C');
				$pdf->Cell(25.86, 6, 'Total Net', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 5);
				$no = 1;
				$qty = 0;
				$diskon = 0;
				$vat = 0;
				$materai = 0;
				$total = 0;
				foreach ($query as $q) {
					$pdf->Cell(8, 6, $no++, 1, 0, 'C');
					$pdf->Cell(48.44, 6, $q->vendor_name, 1, 0, 'L');
					$pdf->Cell(15, 6, number_format($q->qty_terima, 0), 1, 0, 'R');
					$pdf->Cell(25.86, 6, number_format($q->totalrp, 0), 1, 0, 'R');
					$pdf->Cell(20, 6, number_format($q->discountrp, 0), 1, 0, 'R');
					$pdf->Cell(25.86, 6, number_format($q->vatrp, 0), 1, 0, 'R');
					$pdf->Cell(20, 6, number_format($q->materai, 0), 1, 0, 'R');
					$pdf->Cell(25.86, 6, number_format($q->totalrp, 0), 1, 0, 'R');
					$pdf->ln();
					$qty += ($q->qty_terima);
					$diskon += ($q->discountrp);
					$vat += ($q->vatrp);
					$materai += ($q->materai);
					$total += ($q->totalrp);
				}
				$pdf->Cell(71.44, 6, 'TOTAL', 1, 0, 'C');
				$pdf->Cell(25.86, 6, number_format($qty, 0), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($diskon, 0), 1, 0, 'R');
				$pdf->Cell(25.86, 6, number_format($vat, 0), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($materai, 0), 1, 0, 'R');
				$pdf->Cell(25.86, 6, number_format($total, 0), 1, 0, 'R');
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->setfont('Arial', 'B', 10);
				$pdf->Cell(190, 6, 'HOSPITAL MANAGEMENT SIMTEM', 0, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 8);
				$pdf->Cell(190, 6, 'Tanggal Cetak : ' . date('d-m-Y'), 0, 0, 'C');
				$pdf->ln();
				$pdf->ln();
				$pdf->setfont('Arial', '', 10);
				$pdf->Cell(63.33, 6, 'Diketahui Oleh :', 0, 0, 'C');
				$pdf->Cell(63.33, 6, 'Diserahkan Oleh :', 0, 0, 'C');
				$pdf->Cell(63.33, 6, 'Dibuat Oleh :', 0, 0, 'C');
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->Cell(63.33, 6, '', 0, 0, 'C');
				$pdf->Cell(63.33, 6, '', 0, 0, 'C');
				$pdf->Cell(63.33, 6, 'Haryanto', 0, 0, 'C');
				$pdf->ln();
			} else if ($idlap == '105') {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(10, 6, 'No', 1, 0, 'C');
				$pdf->Cell(30, 6, 'Kode Barang', 1, 0, 'C');
				$pdf->Cell(50, 6, 'Nama Barang', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Satuan', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
				$pdf->Cell(30, 6, 'Harga Rata-rata', 1, 0, 'C');
				$pdf->Cell(30, 6, 'Total', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$no = 1;
				$qty = 0;
				$ratarata = 0;
				$tot = 0;
				foreach ($query as $q) {
					$pdf->Cell(10, 6, $no++, 1, 0, 'C');
					$pdf->Cell(30, 6, $q->kodebarang, 1, 0, 'L');
					$pdf->Cell(50, 6, $q->namabarang, 1, 0, 'L');
					$pdf->Cell(20, 6, $q->satuan, 1, 0, 'L');
					$pdf->Cell(20, 6, number_format($q->qty_terima, 0), 1, 0, 'R');
					$pdf->Cell(30, 6, number_format($q->ratarata, 0), 1, 0, 'R');
					$total = $q->qty_terima * $q->ratarata;
					$pdf->Cell(30, 6, number_format($total, 0), 1, 0, 'R');
					$pdf->ln();
					$qty += $q->qty_terima;
					$ratarata += $q->ratarata;
					$tot += $total;
				}
				$pdf->Cell(110, 6, 'TOTAL', 1, 0, 'C');
				$pdf->Cell(20, 6, number_format($qty, 0), 1, 0, 'R');
				$pdf->Cell(30, 6, number_format($ratarata, 0), 1, 0, 'R');
				$pdf->Cell(30, 6, number_format($tot, 0), 1, 0, 'R');
			} else if ($idlap == '106') {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(29, 6, 'PO No', 1, 0, 'C');
				$pdf->Cell(23, 6, 'Tanggal', 1, 0, 'C');
				$pdf->Cell(29, 6, 'Kode Barang', 1, 0, 'C');
				$pdf->Cell(37, 6, 'Nama Barang', 1, 0, 'C');
				$pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Satuan', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Harga Set', 1, 0, 'C');
				$pdf->Cell(19, 6, 'Total', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$total_akhir = 0;
				foreach ($query as $q) {
					$pdf->Cell(29, 6, $q->po_no, 1, 0, 'L');
					$pdf->Cell(23, 6, date('d-m-Y', strtotime($q->terima_date)), 1, 0, 'C');
					$pdf->Cell(29, 6, $q->kodebarang, 1, 0, 'L');
					$pdf->Cell(37, 6, $q->namabarang, 1, 0, 'L');
					$pdf->Cell(15, 6, number_format($q->qty_terima, 0), 1, 0, 'R');
					$pdf->Cell(19, 6, $q->satuan, 1, 0, 'L');
					$pdf->Cell(19, 6, number_format($q->price, 0), 1, 0, 'R');
					$total = $q->price * $q->qty_terima;
					$pdf->Cell(19, 6, number_format($total, 0), 1, 0, 'R');
					$pdf->ln();
				}
			} else if ($idlap == '107') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(40, 6, 'Retur No', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Tanggal', 1, 0, 'C');
				$pdf->Cell(40, 6, 'No BAPB', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Kode Barang', 1, 0, 'C');
				$pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Satuan', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Harga Set', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Total', 1, 0, 'C');
				$pdf->Cell(40, 6, 'PO No', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$no = 1;
				$total_akhir = 0;
				foreach ($query as $q) {
					$pdf->Cell(40, 6, $q->retur_no, 1, 0, 'L');
					$pdf->Cell(20, 6, date('d-m-Y', strtotime($q->retur_date)), 1, 0, 'C');
					$pdf->Cell(40, 6, $q->terima_no, 1, 0, 'L');
					$pdf->Cell(20, 6, $q->kodebarang, 1, 0, 'L');
					$pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
					$pdf->Cell(20, 6, number_format($q->qty_retur, 2), 1, 0, 'R');
					$pdf->Cell(20, 6, $q->satuan, 1, 0, 'L');
					$pdf->Cell(20, 6, number_format($q->price, 2), 1, 0, 'R');
					// $total = $q->qty_terima*$q->price;
					$pdf->Cell(20, 6, number_format($q->totalrp, 2), 1, 0, 'R');
					$pdf->Cell(40, 6, $q->po_no, 1, 0, 'L');
					$pdf->ln();
					$total_akhir += $q->totalrp;
				}
				$pdf->Cell(220, 6, 'Total', 1, 0, 'C');
				$pdf->Cell(20, 6, number_format($total_akhir, 2), 1, 0, 'R');
				$pdf->Cell(40, 6, '', 1, 0, 'C');
				$pdf->ln();
			} else if ($idlap == '108') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$pdf->setfont('Arial', 'B', 6);
				$pdf->Cell(50, 6, 'Rekanan', 1, 0, 'C');
				$pdf->Cell(40, 6, 'No Faktur', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Obat', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Alkes Rutin', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Alkes Inves', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Bahan Kimia', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Gas Medik', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Pemeliharaan', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Sewa', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Pelengkap', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Lain-lain', 1, 0, 'C');
				$pdf->Cell(17, 6, 'Materai', 1, 0, 'C');
				$pdf->Cell(20, 6, 'Jumlah', 1, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$no = 1;
				$obat = 0;
				$alkes_rutin = 0;
				$alk_investasi = 0;
				$bahan_kimia = 0;
				$gas_medik = 0;
				$pemeliharaan = 0;
				$sewa = 0;
				$pelengkapan = 0;
				$lain2 = 0;
				$materai = 0;
				$jumlah = 0;
				foreach ($query as $q) {
					$pdf->Cell(50, 6, $q->rekanan, 1, 0, 'L');
					$pdf->Cell(40, 6, $q->no_faktur, 1, 0, 'L');
					$pdf->Cell(17, 6, number_format($q->obat, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->alkes_rutin, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->alk_investasi, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->bahan_kimia, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->gas_medik, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->pemeliharaan, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->sewa, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->pelengkapan, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->lain2, 0), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($q->materai, 0), 1, 0, 'R');
					$pdf->Cell(20, 6, number_format($q->jumlah, 0), 1, 0, 'R');
					$pdf->ln();
					// $obat += $q->obat;
					// $alkes_rutin += $q->alkes_rutin;
					// $alk_investasi += $q->alk_investasi;
					// $bahan_kimia += $q->bahan_kimia;
					// $gas_medik += $q->gas_medik;
					// $pemeliharaan += $q->pemeliharaan;
					// $sewa += $q->sewa;
					// $pelengkapan += $q->pelengkapan;
					// $lain2 += $q->lain2;
					// $materai += $q->materai;
					// $jumlah += $q->jumlah;
				}
				// $pdf->Cell(90, 6, 'Total', 1, 0, 'C');
				// $pdf->Cell(17, 6, number_format($obat, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($alkes_rutin, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($alk_investasi, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($bahan_kimia, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($gas_medik, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($pemeliharaan, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($sewa, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($pelengkapan, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($lain2, 2), 1, 0, 'R');
				// $pdf->Cell(17, 6, number_format($materai, 2), 1, 0, 'R');
				// $pdf->Cell(20, 6, number_format($jumlah, 2), 1, 0, 'R');
				// $pdf->ln();
			}

			$pdf->Output();
		}
	}

	public function cetak2(){
		$cekpdf   = $this->input->get('pdf');;
		$cek      = $this->session->userdata('level');
		$idlap    = $this->input->get('idlap');
		$unit     = $this->input->get('cabang');
		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');
		$vendorx  = $this->input->get('vendor');
		$body     = '';
		$date     = "Dari Tgl : ".date("d-m-Y", strtotime($tanggal1))." S/D ".date("d-m-Y", strtotime($tanggal2));
		$profile  = data_master('tbl_namers', array('koders' => $unit));
		$kota     = $profile->kota;
		if (!empty($cek)) {
			if($idlap == 101){
				$position = 'L';
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' and";
				} else {
					$vendor = '';
				}
				$judul = '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)';
				$query = $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_baranghterima a JOIN tbl_barangdterima b ON b.terima_no = a.terima_no JOIN tbl_barang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE $vendor a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. BAPB</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">PO No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Invoice / SJ</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga set</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Diskon</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Vat Rp</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total Net</td>
											</tr>
										</thead>";
				$no = 1;
				$tqty = 0;
				$thargaset = 0;
				$ttotal = 0;
				$tdiskon = 0;
				$tvatrp = 0;
				$ttotalnet = 0;
				foreach($query as $q) {
				if($q->po_no == ''){
					$po_no = '-';
				} else {
					$po_no = $q->po_no;
				}
				$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->terima_no</td>
												<td>$po_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td>$q->sj_no</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . number_format($q->qty_terima) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->price) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->totalrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->discountrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->vatrp1) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->totalnet) . "</td>
											</tr>
										</tbody>";
					$tqty += $q->qty_terima;
					$thargaset += $q->price;
					$ttotal += $q->totalrp;
					$tdiskon += $q->discountrp;
					$tvatrp += $q->vatrp1;
					$ttotalnet += $q->totalnet;
				};
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"8\">TOTAL</td>
											<td style=\"text-align: right;\">".number_format($tqty)."</td>
											<td style=\"text-align: right;\">".number_format($thargaset)."</td>
											<td style=\"text-align: right;\">".number_format($ttotal)."</td>
											<td style=\"text-align: right;\">".number_format($tdiskon)."</td>
											<td style=\"text-align: right;\">".number_format($tvatrp)."</td>
											<td style=\"text-align: right;\">".number_format($ttotalnet)."</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
			} else if($idlap == 102){
				$position = 'L';
				if ($vendorx != '') {
					$vendor = "b.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)';
				$query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_baranghterima AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_barangdterima AS c ON a.terima_no = c.terima_no WHERE $vendor a.koders = '$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Supplier</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. BAPB</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Invoice / SJ</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Diskon</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Vat Rp</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Materai</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total Net</td>
											</tr>
										</thead>";
				$no = 1;
				$ttotalrp = 0;
				$tdiskontotal = 0;
				$tvatrp = 0;
				$tmaterai = 0;
				$ttotalnet = 0;
				foreach ($query as $q) {
					$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->vendor_name</td>
												<td>$q->terima_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td>$q->sj_no</td>
												<td style=\"text-align: right;\">" . number_format($q->totalrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->diskontotal) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->vatrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->materai) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->totalnet) . "</td>
											</tr>
										</tbody>";
					$ttotalrp += $q->totalrp;
					$tdiskontotal += $q->diskontotal;
					$tvatrp += $q->vatrp;
					$tmaterai += $q->materai;
					$ttotalnet += $q->totalnet;
				};
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"5\">TOTAL</td>
											<td style=\"text-align: right;\">" . number_format($ttotalrp) . "</td>
											<td style=\"text-align: right;\">" . number_format($tdiskontotal) . "</td>
											<td style=\"text-align: right;\">" . number_format($tvatrp) . "</td>
											<td style=\"text-align: right;\">" . number_format($tmaterai) . "</td>
											<td style=\"text-align: right;\">" . number_format($ttotalnet) . "</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
											<td> &nbsp; </td>
										</tr> 
                  </table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"20%\" align=\"center\" border=\"0\">
										<tr>
											<td style=\"text-align:center;\"><i>HOSPITAL MANAGEMENT SYSTEM</i></td>
										</tr> 
										<tr>
											<td style=\"text-align:center;\">$kota ," . date("d-m-Y") . "</td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center;\" width=\"33%\">Diketahui oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Diserahkan oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Dibuat oleh,</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">2</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">HARYANTO</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">Kepala Apoteker</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">PENANGGUNG JAWAB ADMINISTRASI</td>
										</tr> 
									</table>";
			} else if($idlap == 103){
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
					$vendorr = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
					$vendorr = '';
				}
				$judul = '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM';
				$vendorx = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, h.vendor_id, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by h.vendor_id")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga rata-rata</td>
												<td width=\"15%\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>
										</thead>";
				$totalsemua = 0;
				foreach($vendorx as $v) {
				$body .= 		"<tbody>
											<tr>
												<td colspan=\"7\">SUPPLIER : $v->vendor_name</td>
											</tr>";
					$no = 1;
					$tqty_terima = 0;
					$tratarata = 0;
					$ttotal = 0;
					$query = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2' AND vendor_id = '$v->vendor_id'")->result();
					foreach($query as $q){
						$total = $q->ratarata * $q->qty_terima;
						$body .= 			"<tr>
														<td style=\"text-align: right;\">" . $no++ . "</td>
														<td>$q->kodebarang</td>
														<td>$q->namabarang</td>
														<td>$q->satuan</td>
														<td style=\"text-align: right;\">" . number_format($q->qty_terima) . "</td>
														<td style=\"text-align: right;\">" . number_format($q->ratarata) . "</td>
														<td width=\"15%\" style=\"text-align: right;\">" . number_format($total) . "</td>
													</tr>";
						$tqty_terima += $q->qty_terima;
						$tratarata += $q->ratarata;
						$ttotal += $total;
					}
					$body .= 				"<tr>
														<td colspan=\"4\">SUBTOTAL SUPLIER</td>
														<td style=\"text-align: right; font-weight: bold; color:red;\">".number_format($tqty_terima)."</td>
														<td style=\"text-align: right; font-weight: bold; color:red;\">".number_format($tratarata). "</td>
														<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">".number_format($ttotal)."</td>
													</tr>";
					$totalsemua += $ttotal;
				}
				$body .=	"</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
											<td> &nbsp; </td>
										</tr> 
                  </table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\"> 
                    <tr>
											<td colspan=\"6\">TOTAL</td>
											<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . number_format($totalsemua) . "</td>
										</tr> 
                  </table>";
			} else if($idlap == 104){
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL';
				$query = $this->db->query("SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id, a.terima_date FROM tbl_baranghterima as a JOIN tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE $vendor a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Supplier</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Diskon</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Vat Rp</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Materai</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total Net</td>
											</tr>
										</thead>";
				$no = 1;
				$tqty_terima = 0;
				$ttotalrp = 0;
				$ttotalrp2 = 0;
				$tdiscountrp = 0;
				$tvatrp = 0;
				$tmaterai = 0;
				$ttotalrp = 0;
				$sql = $this->db->get_where("tbl_pajak", ["kodetax"=>"PPN"])->row();
				$pajak = $sql->prosentase / 100;
				foreach ($query as $q) {
					$vatrp = ($q->totalrp * $pajak);
					$totalrp = ($q->totalrp + $q->discountrp);
					$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->vendor_name</td>
												<td>".date("d-m-Y", strtotime($q->terima_date))."</td>
												<td style=\"text-align: right;\">" . number_format($q->qty_terima) . "</td>
												<td style=\"text-align: right;\">" . number_format($totalrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->discountrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($vatrp) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->materai) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->totalrp + $q->vatrp) . "</td>
											</tr>
										</tbody>";
					$tqty_terima += $q->qty_terima;
					$ttotalrp += $totalrp;
					$tdiscountrp += $q->discountrp;
					$tvatrp += $vatrp;
					$tmaterai += $q->materai;
					$ttotalrp2 += ($q->totalrp + $q->vatrp);
				}
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
											<td style=\"text-align: right;\">" . number_format($tqty_terima) . "</td>
											<td style=\"text-align: right;\">" . number_format($ttotalrp) . "</td>
											<td style=\"text-align: right;\">" . number_format($tdiscountrp) . "</td>
											<td style=\"text-align: right;\">" . number_format($tvatrp) . "</td>
											<td style=\"text-align: right;\">" . number_format($tmaterai) . "</td>
											<td style=\"text-align: right;\">" . number_format($ttotalrp2) . "</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td style=\"text-align:center;\"><i>HOSPITAL MANAGEMENT SYSTEM</i></td>
										</tr> 
										<tr>
											<td style=\"text-align:center;\">$kota ," . date("d-m-Y") . "</td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center;\" width=\"33%\">Diketahui oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Diserahkan oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Dibuat oleh,</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">2</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">HARYANTO</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">Kepala Apoteker</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">PENANGGUNG JAWAB ADMINISTRASI</td>
										</tr> 
									</table>";
			} else if ($idlap == 105){
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)';
				$query = $this->db->query("SELECT b.kodebarang , (select namabarang from tbl_barang where kodebarang = b.kodebarang) as namabarang , h.vendor_id, b.satuan , sum(b.qty_terima) as qty_terima, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, h.vendor_id , (select vendor_name from tbl_vendor where vendor_id=h.vendor_id) as vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no JOIN tbl_vendor AS c ON h.vendor_id = c.vendor_id WHERE $vendor b.koders = '$unit' and h.terima_date between '$tanggal1' and '$tanggal2' GROUP BY b.kodebarang")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga rata-rata</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>
										</thead>";
				$no = 1;
				$tqty_terima = 0;
				$tratarata = 0;
				$ttotal = 0;
				foreach($query as $q){
					$total = $q->qty_terima * $q->ratarata;
					$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . number_format($q->qty_terima) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->ratarata) . "</td>
												<td style=\"text-align: right;\">" . number_format($total) . "</td>
											</tr>
										</tbody>";
					$tqty_terima += $q->qty_terima;
					$tratarata += $q->ratarata;
					$ttotal += $total;
				}
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"4\">TOTAL</td>
											<td style=\"text-align: right;\">" . number_format($tqty_terima) . "</td>
											<td style=\"text-align: right;\">" . number_format($tratarata) . "</td>
											<td style=\"text-align: right;\">" . number_format($ttotal) . "</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
			} else if ($idlap == 106){
				$position = 'P';
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '06 LAPORAN STATUS ORDER PEMBELIAN';
				$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_baranghterima AS a JOIN  tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_barang AS d ON b.kodebarang = d.kodebarang WHERE $vendor  a.koders = '$unit'  and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">PO No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga sat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>
										</thead>";
				$no = 1;
				$tqty_terima = 0;
				$tprice = 0;
				$ttotal = 0;
				foreach($query as $q){
					$total = $q->price * $q->qty_terima;
					if($q->po_no == ''){
						$po_no = '-';
					} else {
						$po_no = $q->po_no;
					}
					$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$po_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . number_format($q->qty_terima) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->price) . "</td>
												<td style=\"text-align: right;\">" . number_format($total) . "</td>
											</tr>
										</tbody>";
					$tqty_terima += $q->qty_terima;
					$tprice += $q->price;
					$ttotal += $total;
				}
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"6\">TOTAL</td>
											<td style=\"text-align: right;\">" . number_format($tqty_terima) . "</td>
											<td style=\"text-align: right;\">" . number_format($tprice) . "</td>
											<td style=\"text-align: right;\">" . number_format($ttotal) . "</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
			} else if ($idlap == 107){
				$position = 'L';
				$judul = '07 LAPORAN RETURN PEMBELIAN';
				$vendor = $this->db->query("SELECT 
					a.vendor_id,
					v.vendor_name
					FROM tbl_baranghreturbeli a 
					JOIN tbl_barangdreturbeli b ON a.retur_no=b.retur_no 
					JOIN tbl_vendor v ON a.vendor_id=v.vendor_id
					WHERE a.koders = '$unit' AND a.retur_date BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY a.vendor_id")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Retur No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. BAPB</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">PO No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga sat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>
										</thead>";
				foreach($vendor as $v){
					$body .= 		"<tbody>
											<tr>
												<td colspan=\"7\">SUPPLIER : $v->vendor_name</td>
											</tr>";
					$query = $this->db->query("SELECT 
					a.retur_no, 
					a.retur_date, 
					a.terima_no, 
					b.kodebarang, 
					(SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, 
					b.qty_retur, 
					b.satuan, 
					b.price, 
					a.vendor_id,
					b.totalrp, 
					(SELECT po_no FROM tbl_barangdterima WHERE terima_no = a.terima_no AND kodebarang = b.kodebarang) AS po_no
					FROM tbl_baranghreturbeli a 
					JOIN tbl_barangdreturbeli b ON a.retur_no=b.retur_no 
					WHERE a.koders = '$unit' AND a.retur_date BETWEEN '$tanggal1' AND '$tanggal2' AND a.vendor_id='$v->vendor_id'")->result();
					$no = 1;
					$tqty_retur = 0;
					$tprice = 0;
					$ttotalrp = 0;
					$totalsemua = 0;
					foreach($query as $q){
						if($q->po_no == ''){
							$po_no = '-';
						} else {
							$po_no = $q->po_no;
						}
						$body .= 			"<tr>
														<td style=\"text-align: right;\">" . $no++ . "</td>
														<td>$q->retur_no</td>
														<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->retur_date)) . "</td>
														<td>$q->terima_no</td>
														<td>$po_no</td>
														<td>$q->kodebarang</td>
														<td>$q->namabarang</td>
														<td>$q->satuan</td>
														<td style=\"text-align: right;\">" . number_format($q->qty_retur) . "</td>
														<td style=\"text-align: right;\">" . number_format($q->price) . "</td>
														<td width=\"15%\" style=\"text-align: right;\">" . number_format($q->totalrp) . "</td>
													</tr>";
					$tqty_retur += $q->qty_retur;
					$tprice += $q->price;
					$ttotalrp += $q->totalrp;
					}
					$body .= 				"<tr>
														<td colspan=\"8\">SUBTOTAL</td>
														<td style=\"text-align: right; font-weight: bold; color:red;\">" . number_format($tqty_retur) . "</td>
														<td style=\"text-align: right; font-weight: bold; color:red;\">" . number_format($tprice) . "</td>
														<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . number_format($ttotalrp) . "</td>
													</tr>";
					$totalsemua += $ttotalrp;
				}
				$body .=	"</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
											<td> &nbsp; </td>
										</tr> 
                  </table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\"> 
                    <tr>
											<td colspan=\"6\">TOTAL</td>
											<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . number_format($totalsemua) . "</td>
										</tr> 
                  </table>";
			} else if ($idlap == 108) {
				$position = 'L';
				if ($vendorx != '') {
					$vendor = "vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$judul = '08 LAPORAN HUTANG GUDANG FARMASI';
				$query = $this->db->query("SELECT 
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghpo.vendor_id) AS rekanan,
						tbl_baranghpo.ref_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdpo.vatrp AS lain2,
						tbl_barangdpo.total AS jumlah
					FROM tbl_baranghpo
					JOIN tbl_barangdpo ON tbl_baranghpo.po_no=tbl_barangdpo.po_no
					WHERE $vendor tglpo BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghterima.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						materai,
						tbl_barangdterima.vatrp AS lain2,
						tbl_barangdterima.totalrp AS jumlah
					FROM tbl_baranghterima
					JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
					WHERE $vendor terima_date BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghreturbeli.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdreturbeli.taxrp AS lain2,
						tbl_barangdreturbeli.totalrp AS jumlah
					FROM tbl_baranghreturbeli
					JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no
					WHERE $vendor retur_date BETWEEN '$tanggal1' AND '$tanggal2'")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                    <thead>
											<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Rekanan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Faktur</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Obat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alkes Rutin</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alk. Investasi</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Bahan Kimia</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Gas Medik</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pemeliharaan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Sewa</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pelengkap</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Lain-lain</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Materai</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
											</tr>
										</thead>";
				$no = 1;
				$tobat = 0;
				$talkes_rutin = 0;
				$talk_investasi = 0;
				$tbahan_kimia = 0;
				$tgas_medik = 0;
				$tpemeliharaan = 0;
				$tsewa = 0;
				$tpelengkapan = 0;
				$tlain2 = 0;
				$tmaterai = 0;
				$tjumlah = 0;
				foreach($query as $q){
					$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->rekanan</td>
												<td>$q->no_faktur</td>
												<td style=\"text-align: right;\">" . number_format($q->obat) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->alkes_rutin) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->alk_investasi) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->bahan_kimia) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->gas_medik) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->pemeliharaan) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->sewa) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->pelengkapan) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->lain2) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->materai) . "</td>
												<td style=\"text-align: right;\">" . number_format($q->jumlah) . "</td>
											</tr>
										</tbody>";
					$tobat += $q->obat;
					$talkes_rutin += $q->alkes_rutin;
					$talk_investasi += $q->alk_investasi;
					$tbahan_kimia += $q->bahan_kimia;
					$tgas_medik += $q->gas_medik;
					$tpemeliharaan += $q->pemeliharaan;
					$tsewa += $q->sewa;
					$tpelengkapan += $q->pelengkapan;
					$tlain2 += $q->lain2;
					$tmaterai += $q->materai;
					$tjumlah += $q->jumlah;
				}
				$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
											<td style=\"text-align: right;\">" . number_format($tobat) . "</td>
											<td style=\"text-align: right;\">" . number_format($talkes_rutin) . "</td>
											<td style=\"text-align: right;\">" . number_format($talk_investasi) . "</td>
											<td style=\"text-align: right;\">" . number_format($tbahan_kimia) . "</td>
											<td style=\"text-align: right;\">" . number_format($tgas_medik) . "</td>
											<td style=\"text-align: right;\">" . number_format($tpemeliharaan) . "</td>
											<td style=\"text-align: right;\">" . number_format($tsewa) . "</td>
											<td style=\"text-align: right;\">" . number_format($tpelengkapan) . "</td>
											<td style=\"text-align: right;\">" . number_format($tlain2) . "</td>
											<td style=\"text-align: right;\">" . number_format($tmaterai) . "</td>
											<td style=\"text-align: right;\">" . number_format($tjumlah) . "</td>
										</tr>
									</tfoot>";
				$body .=	"</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Petugas,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Mengetahui,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Disetujui,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Diterima oleh,</td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">HARYANTO</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Sie Farmasi)</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Ka. Bid. Farmasi)</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Keuangan)</td>
										</tr> 
									</table>";
			}
			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function excel()
	{
		$cek = $this->session->userdata('level');
		$idlap = $this->input->get('idlap');
		// $unit = $this->input->get('cabang');
		$unit = $this->session->userdata('unit');
		$tanggal1 = $this->input->get('tgl1');
		$tanggal2 = $this->input->get('tgl2');
		$vendorx = $this->input->get('vendor');
		// $unit = $cabang;
		// echo json_encode($tanggal1.' dan '.$tanggal2);
		if (!empty($cek)) {

			if ($idlap == 101) {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' and";
				} else {
					$vendor = '';
				}
				$data = [
					'judul' => '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)',
					'query' => $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_baranghterima a JOIN tbl_barangdterima b ON b.terima_no = a.terima_no JOIN tbl_barang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE $vendor a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result(),
				];
				$this->load->view('LaporanFarmasi/v_excel_101', $data);
			} else if ($idlap == '102') {
				if ($vendorx != '') {
					$vendor = "b.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$data = [
					'judul' => '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)',
					'query' => $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_baranghterima AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_barangdterima AS c ON a.terima_no = c.terima_no WHERE $vendor a.koders = '$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result(),
				];
				$this->load->view('LaporanFarmasi/v_excel2', $data);
			} else if ($idlap == '103') {
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
					$vendorr = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
					$vendorr = '';
				}
				$supplier = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by h.vendor_id")->result();
				$query = $this->db->query("SELECT b.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no WHERE $vendor b.koders = '$unit' AND h.terima_date BETWEEN '$tanggal1' AND '$tanggal2'")->result();
				$data = [
					'query' => $query,
					'judul'  => '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM',
					'suplier' =>  $supplier,

				];
				$this->load->view('LaporanFarmasi/v_excel_103', $data);
			} else if ($idlap == '104') {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$data = [
					'query' =>  $this->db->query("SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_baranghterima as a JOIN tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE $vendor a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result(),
					'judul'  => '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL',
				];
				$this->load->view('LaporanFarmasi/v_excel_104', $data);
			} else if ($idlap == '105') {
				if ($vendorx != '') {
					$vendor = "h.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$data = [
					'query' => $this->db->query("SELECT b.kodebarang , (select namabarang from tbl_barang where kodebarang = b.kodebarang) as namabarang , h.vendor_id, b.satuan , b.qty_terima , (b.totalrp / b.qty_terima ) AS ratarata, b.koders, h.vendor_id , (select vendor_name from tbl_vendor where vendor_id=h.vendor_id) as vendor_name FROM tbl_barangdterima AS b JOIN tbl_baranghterima h ON b.terima_no = h.terima_no JOIN tbl_vendor AS c ON h.vendor_id = c.vendor_id WHERE $vendor b.koders = '$unit' and h.terima_date between '$tanggal1' and '$tanggal2'")->result(),
					'judul'  => '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)',
				];
				$this->load->view('LaporanFarmasi/v_excel5', $data);
			} else if ($idlap == '106') {
				if ($vendorx != '') {
					$vendor = "a.vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$data = [
					'query'  => $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_baranghterima AS a JOIN  tbl_barangdterima AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_barang AS d ON b.kodebarang = d.kodebarang WHERE $vendor  a.koders = '$unit'  and a.terima_date between '$tanggal1' and '$tanggal2'")->result(),
					'judul'  => '06 LAPORAN STATUS ORDER PEMBELIAN',
				];
				$this->load->view('LaporanFarmasi/v_excel_106', $data);
			} else if ($idlap == '107') {
				$data = [
					'query' =>  $this->db->query("SELECT 
					a.retur_no, 
					a.retur_date, 
					a.terima_no, 
					b.kodebarang, 
					(SELECT namabarang FROM tbl_barang WHERE kodebarang = b.kodebarang) AS namabarang, 
					b.qty_retur, 
					b.satuan, 
					b.price, 
					b.totalrp, 
					(SELECT po_no FROM tbl_barangdterima WHERE terima_no = a.terima_no AND kodebarang = b.kodebarang) AS po_no
					FROM tbl_baranghreturbeli a 
					JOIN tbl_barangdreturbeli b ON a.retur_no=b.retur_no 
					WHERE a.koders = '$unit' AND a.retur_date BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY b.kodebarang")->result(),
					'judul' => '07 LAPORAN RETUR PEMBELIAN BARANG',
				];
				$this->load->view('LaporanFarmasi/v_excel7', $data);
			} else if ($idlap == '108') {
				if ($vendorx != '') {
					$vendor = "vendor_id='$vendorx' AND";
				} else {
					$vendor = '';
				}
				$data = [
					'query' => $this->db->query("SELECT 
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghpo.vendor_id) AS rekanan,
						tbl_baranghpo.ref_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdpo.vatrp AS lain2,
						tbl_barangdpo.total AS jumlah
					FROM tbl_baranghpo
					JOIN tbl_barangdpo ON tbl_baranghpo.po_no=tbl_barangdpo.po_no
					WHERE $vendor tglpo BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghterima.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						materai,
						tbl_barangdterima.vatrp AS lain2,
						tbl_barangdterima.totalrp AS jumlah
					FROM tbl_baranghterima
					JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
					WHERE $vendor terima_date BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_baranghreturbeli.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_barangdreturbeli.taxrp AS lain2,
						tbl_barangdreturbeli.totalrp AS jumlah
					FROM tbl_baranghreturbeli
					JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no
					WHERE $vendor retur_date BETWEEN '$tanggal1' AND '$tanggal2'")->result(),
					'judul' => '08 LAPORAN HUTANG FARMASI',
				];
				$this->load->view('LaporanFarmasi/v_excel8', $data);
			}
		}
	}
	public function convert_excel($type, $fn, $dl)
	{
		// var elt = document.getElementById('table');
		// var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
		// return dl ?
		// 	XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		// 	XLSX.writeFile(wb, fn || ('Jurnal-Report.' + (type || 'xlsx')));
	}


	public function export()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['master_bank'] = $this->db->get("tbl_namers");
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');

			$this->load->view('master/bank/v_master_bank_exp', $d);
		} else {

			header('location:' . base_url());
		}
	}
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */