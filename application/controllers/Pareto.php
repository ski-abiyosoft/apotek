<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pareto extends CI_Controller {
	public function __construct() {
		parent::__construct();
    $this->load->model("M_pareto");
    $this->load->model("M_template_cetak");
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3105');
	}

  public function index() {
    $cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');
		$koders   = $this->input->get('cabang');
		$tanggal  = $this->input->get('tanggal');
		if(!empty($cek)) {
			$id = 30;
			$week = 7;
      if($koders == null || $koders == '') {
        if($tanggal == null || $tanggal == '') {
          $w_cabang   = "";
          $w_tanggal  = 'hr.tglresep';
        } else {
          $w_cabang   = "";
          $w_tanggal  = "'".$tanggal."'";
        }
      } else {
        if($tanggal == null || $tanggal == '') {
          $w_cabang   = " AND r.koders = '$koders'";
          $w_tanggal  = 'hr.tglresep';
        } else {
          $w_cabang   = " AND r.koders = '$koders'";
          $w_tanggal  = "'".$tanggal."'";
        }
      }
      $pareto = $this->db->query("SELECT r.koders, r.kodebarang, r.namabarang, r.satuan, ROUND(SUM(r.qty)) AS qty_jual, ROUND(SUM(bs.saldoakhir)) AS sisa_stock, 
      ROUND(SUM(r.qty) / 30) AS pareto, ROUND((SUM(r.qty) / 30) * 7) AS min_stock,
      ROUND(((SUM(r.qty) / 30) * 7) - (SUM(bs.saldoakhir))) AS yhdb
      FROM (
        SELECT hr.koders, dr.kodebarang, dr.namabarang, dr.satuan, dr.qty
        FROM tbl_apodresep dr
        JOIN tbl_apohresep hr ON dr.resepno = hr.resepno
        WHERE DATE($w_tanggal) - 30
        
        UNION ALL
        
        SELECT hr.koders, der.kodebarang, der.namabarang, der.satuan, der.qty
        FROM tbl_apodetresep der
        JOIN tbl_apohresep hr ON der.resepno = hr.resepno
        WHERE DATE($w_tanggal) - 30
      ) AS r
      JOIN tbl_barangstock bs ON bs.kodebarang = r.kodebarang
      WHERE r.kodebarang IN (SELECT kodebarang FROM tbl_barang)
      $w_cabang
      GROUP BY r.kodebarang")->result();
      $pareto2 = $this->db->query("SELECT p.*, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = p.vendor_id) AS vendor_name FROM tbl_pareto p WHERE status < 3")->result();
      $data = [
        'rs'      => $this->db->get("tbl_namers")->result(),
				'pareto'  => $pareto,
				'pareto2' => $pareto2,
        'koders'  => $koders,
        'tanggal' => $tanggal,
      ];
      $this->load->view('template/header');
      $this->load->view('template/body');
			$this->load->view('pembelian/v_pareto', $data);
      $this->load->view('template/footer_tb');
		} else {
			header('location:'.base_url());
		}
  }

  public function get_data($vendor) {
    $data   = $this->db->get_where("tbl_pareto", ["vendor_id" => $vendor, "status" => 1])->row();
    $barang = $this->db->get_where("tbl_barang", ["kodebarang" => $data->kodebarang])->row();
    $cek_data = [
      'kodebarang'  => $data->kodebarang,
      'namabarang'  => $data->namabarang,
      'qty'         => $data->qty_rencana,
      'satuan'      => $data->satuan,
      'harga'       => $barang->hargabeli,
    ];
    if($cek_data) {
      echo json_encode($cek_data);
    } else {
      echo json_encode(["status" => 0]);
    }
  }

  public function pareto($kodebarang) {
    $kode_pareto    = $this->M_pareto->kode();
    $barang         = $this->db->get_where("tbl_barang", ["kodebarang" => $kodebarang])->row();
    $namabarang     = $barang->namabarang;
    $satuan         = $barang->satuan1;
    $vendor_id      = $this->input->get("vendor");
    $qty_jual       = (int)round($this->input->get("qty_jual"));
    $sisa_stok      = (int)round($this->input->get("sisa_stok"));
    $min_stok       = (int)round($this->input->get("min_stok"));
    $yhdb           = (int)round($this->input->get("yhdb"));
    $tanggal        = date("Y-m-d");
    $data = [
      'kode_pareto' => $kode_pareto,
      'kodebarang'  => $kodebarang,
      'namabarang'  => $namabarang,
      'satuan'      => $satuan,
      'saldo'       => $sisa_stok,
      'qty_rencana' => $yhdb,
      'tanggal'     => $tanggal,
      'vendor_id'   => $vendor_id,
      'status'      => 1,
    ];
    // echo json_encode($data);
    $cek = $this->db->insert("tbl_pareto", $data);
    if($cek) {
      echo json_encode(["status" => 1]);
    } else {
      echo json_encode(["status" => 0]);
    }
  }

  public function ajax_list($param) {
    $cek_user   = $this->session->userdata('user_level');
		$dat        = explode("~", $param);
    $cabang     = $this->input->get("cabang");
		if ($dat[0] == 1) {
			$bulan   = $this->M_global->_periodebulan();
			$tahun   = $this->M_global->_periodetahun();
			$list    = $this->M_pareto->get_datatables(1, $bulan, $tahun, $cabang);
		} else {
			$bulan   = date('Y-m-d', strtotime($dat[1]));
			$tahun   = date('Y-m-d', strtotime($dat[2]));
			$list    = $this->M_pareto->get_datatables(2, $bulan, $tahun, $cabang);
		}
		$data   = [];
		$no     = $_POST['start'];
    $tgl1    = date("Y-m-d");
    $tgl2    = date('Y-m-d', strtotime('-30 days', strtotime($tgl1)));
    foreach ($list as $unit) {
			$no++;
			$row   = [];
			$row[] = $unit->kodebarang;
			$row[] = $unit->namabarang;
			$row[] = $unit->satuan;
			$row[] = "<div class='text-right'>".number_format($unit->qty)."</div>";
      if($cabang == null || $cabang == "") {
        $where_cabang = "";
      } else {
        $where_cabang = " AND koders = '$cabang'";
      }
      $salalkhir = $this->db->query("SELECT SUM(saldoakhir) as saldo FROM tbl_barangstock WHERE kodebarang = '$unit->kodebarang' AND koders = '$unit->koders' GROUP BY kodebarang AND lasttr LIKE '%$tgl2%' AND kodebarang IN (SELECT kodebarang FROM tbl_barang) $where_cabang")->row();
			$row[] = "<div class='text-right'>".number_format($salalkhir->saldo)."</div>";
      $barang = $this->db->get_where("tbl_barang", ["kodebarang" => $unit->kodebarang])->row();
      $cek_stock = $unit->qty / 30;
			$row[] = "<div class='text-right'>".number_format($barang->minstock)."</div>";
			$row[] = "<div class='text-right'>".number_format($cek_stock)."</div>";
			$row[] = "abc";
			$row[] = '<div class="text-center">
        <button class="btn btn-primary" type="button" onclick="pilih(' . "'" . $unit->kodebarang . "'" . ')"><i class="fa fa-check"></i> Pilih</button>
      </div>';
      $data[] = $row;
    }
    $output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_pareto->count_all($dat[0], $bulan, $tahun, $cabang),
			"recordsFiltered" => $this->M_pareto->count_filtered($dat[0], $bulan, $tahun, $cabang),
			"data" => $data,
		);
		echo json_encode($output);
  }

  public function cetak($cekpdf) {
		$cabang   = $this->session->userdata('unit');
		$body     = "";
		$date     = "Tanggal Cetak : " . date("d-m-Y");
		$profile  = $this->db->get_where("tbl_namers", ["koders" => $cabang])->row();
		$kota     = $profile->kota;
		$position = 'L';
    $judul    = 'QTY BARANG YANG HARUS DIBELI';
    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">
    <thead>
      <tr>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">NO</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">KODE BARANG</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">NAMA BARANG</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">SATUAN</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">SALDO</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">QTY RENCANA</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">TANGGAL</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">VENDOR</td>
        <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">STATUS</td>
      </tr>
    </thead>";
    $query = $this->db->get("tbl_pareto")->result();
    $no = 1;
    foreach($query as $q) {
      $vendor = $this->db->get_where("tbl_vendor", ["vendor_id" => $q->vendor_id])->row();
      if($cekpdf <= 1) {
        $saldo          = number_format($q->saldo);
        $qty_rencana    = number_format($q->qty_rencana);
      } else {
        $saldo          = ceil($q->saldo);
        $qty_rencana    = ceil($q->qty_rencana);
      }
      if($q->status == 1) {
        $status = "DIAJUKAN";
        $color = "blue";
      } else {
        $color = "green";
        $status = "DIORDER";
      }
      $body .= "<tbody>
        <tr>
          <td style=\"text-align: right;\">$no</td>
          <td>$q->kodebarang</td>
          <td>$q->namabarang</td>
          <td>$q->satuan</td>
          <td style=\"text-align: right;\">$saldo</td>
          <td style=\"text-align: right;\">$qty_rencana</td>
          <td style=\"text-align: center;\">".date("d-m-Y", strtotime($q->tanggal))."</td>
          <td>".$vendor->vendor_name."</td>
          <td style=\"text-align: center; background-color: $color; color: white; font-weight: bold;\">".$status."</td>
        </tr>
      </tbody>";
      $no++;
    }
    $body .= "</table>";
    $this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
  }

  public function batal($id) {
    $this->db->query("DELETE FROM tbl_pareto WHERE id = '$id'");
    echo json_encode(["status" => 1]);
  }
}