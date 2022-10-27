<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_persediaan extends CI_Controller
{
     public function __construct()
     {
          parent::__construct();
          $this->session->set_userdata('menuapp', '3000');
          $this->session->set_userdata('submenuapp', '3308');
          $this->load->helper('simkeu_nota');
          $this->load->model('M_laporan');
          $this->load->model('M_rs');
          $this->load->model('M_cetak');
          $this->load->model('M_template_cetak');
     }
     public function index()
     {
          $cek = $this->session->userdata('level');
          $unit = $this->session->userdata('unit');
          if (!empty($cek)) {
               $d['startdate'] = null;
               $d['enddate'] = null;
               $d['jenis_kunjungan'] = 'frekuensi';
               $d['keu'] = array();
               $d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);
               $this->load->view('farmasi/v_laporan_persediaan', $d);
          } else {
               header('location:' . base_url());
          }
     }
     public function cetak()
     {
          $cek        = $this->session->userdata('level');
          $cekpdf     = $this->input->get('pdf');
          $dari       = $this->input->get('dari');
          $sampai     = $this->input->get('sampai');
          $da         = $this->input->get('da');
          $depo       = $this->input->get('depo');
          $laporan    = $this->input->get('laporan');
          $keperluan  = $this->input->get('keperluan');
          $cabang     = $this->session->userdata('unit');
          $unit       = $cabang;
          if (!empty($cek)) {
               if ($laporan == 1) {
                    $judul = '01 Laporan Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 2) {
                    $judul = '02 Laporan Rekap Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 3) {
                    $judul = '03 Laporan Produksi Farmasi';
                    if ($da == 1) {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang,
                         (select satuan from tbl_barang where kodebarang = h.kodebarang) as satuan,
                         (select hpp from tbl_barang where kodebarang = h.kodebarang) as hpp,
                         ((select hpp from tbl_barang where kodebarang = h.kodebarang) * h.qtyjadi) as totalhpp
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai'
                         group by h.prdno
                         ";
                    } else {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang,
                         (select satuan from tbl_barang where kodebarang = h.kodebarang) as satuan,
                         (select hpp from tbl_barang where kodebarang = h.kodebarang) as hpp,
                         ((select hpp from tbl_barang where kodebarang = h.kodebarang) * h.qtyjadi) as totalhpp
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by h.prdno
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 4) {
                    $judul = '04 Laporan Stock Opname';
                    if ($da == 1) {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 5) {
                    $judul = '05 Laporan Permusnahan Barang';
                    $query = $this->db->query()->result();
                    $data = [
                         'judul' => $judul,
                         'query' => $query,
                    ];
               } else if ($laporan == 6) {
                    if ($keperluan == 1) {
                         $judul = '06 Laporan Persediaan';
                         if ($da == 1) {
                              $x = "SELECT depocode FROM tbl_depo";
                         } else {
                              $x = "SELECT depocode FROM tbl_depo WHERE depocode = '$depo'";
                         }
                         $queryx = $this->db->query($x)->result();
                         $data = [
                              'judul' => $judul,
                              'queryx' => $queryx,
                         ];
                    } else {
                         $judul = '06 Laporan Persediaan';
                         if ($da == 1) {
                              $x = "SELECT depocode FROM tbl_depo";
                         } else {
                              $x = "SELECT depocode FROM tbl_depo WHERE depocode = '$depo'";
                         }
                         $queryx = $this->db->query($x)->result();
                         $data = [
                              'judul' => $judul,
                              'queryx' => $queryx,
                         ];
                    }
               }
               $profile       = $this->M_global->_LoadProfileLap();
               $unit          = $this->session->userdata('unit');
               $avatar        = $this->session->userdata('avatar_cabang');
               $nama_usaha    = $profile->nama_usaha;
               $alamat1       = $profile->alamat1;
               $alamat2       = $profile->alamat2;
               $profile       = data_master('tbl_namers', array('koders' => $unit));
               $nama_usaha    = $profile->namars;
               $alamat1       = $profile->alamat;
               $alamat2       = $profile->kota;
               $pdf           = new simkeu_nota();
               $pdf->setID($nama_usaha, $alamat1, $alamat2);
               $pdf->setjudul($judul . ' CABANG ' . $unit);
               $pdf->setsubjudul('Dari tgl ' . date('d-m-Y', strtotime($dari)) . ' Sampai tgl ' . date('d-m-Y', strtotime($sampai)));
               if ($laporan == 1) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         // rumus pak har
                         // select h.moveno,
                         // movedate,
                         // d.kodebarang,
                         // namabarang,
                         // d.satuan,
                         // d.qtymove,
                         // d.hpp,
                         // d.hpp*qtymove as totalhpp 
                         // from tbl_apohmove h
                         // inner join tbl_apodmove d on h.moveno=d.moveno
                         // inner join tbl_barang 
                         // WHERE h.koders = '$unit' AND h.movedate >= '$dari' AND h.movedate <= '$sampai' and h.dari = '$qx->dari' and h.ke = '$qx->ke'
                         $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                         $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                         $y = "SELECT h.moveno, 
                         h.movedate, 
                         d.kodebarang, 
                         (SELECT namabarang FROM tbl_barang WHERE kodebarang = d.kodebarang) AS namabarang,
                         d.satuan,
                         d.qtymove,
                         (select hpp from tbl_barang where kodebarang = d.kodebarang) as hpp,
                         (d.qtymove*(select hpp from tbl_barang where kodebarang = d.kodebarang)) AS totalhpp,
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$qx->dari' and h.ke = '$qx->ke'
                         ";
                         $query = $this->db->query($y)->result();
                         $pdf->setfont('Arial', 'B', 6);
                         $pdf->Cell(10, 6, 'Dari', 0, 0, 'L');
                         $pdf->Cell(30, 6, $dari_gudang->keterangan, 0, 0, 'L');
                         $pdf->Cell(10, 6, 'Ke', 0, 0, 'L');
                         $pdf->Cell(30, 6, $ke_gudang->keterangan, 0, 1, 'L');
                         $pdf->SetFillColor(0, 0, 139);
                         $pdf->settextcolor(0);
                         $pdf->Cell(5, 6, 'No', 1, 0, 'C');
                         $pdf->Cell(35, 6, 'Bukti Tr', 1, 0, 'C');
                         $pdf->Cell(15, 6, 'Tanggal', 1, 0, 'C');
                         $pdf->Cell(30, 6, 'Kode Barang', 1, 0, 'C');
                         $pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
                         $pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
                         $pdf->Cell(10, 6, 'Qty', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'HPP', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Total HPP', 1, 0, 'C');
                         $pdf->ln();
                         $no = 1;
                         foreach ($query as $q) {
                              $pdf->Cell(5, 6, $no++, 1, 0, 'C');
                              $pdf->Cell(35, 6, $q->moveno, 1, 0, 'L');
                              $pdf->Cell(15, 6, date('d-m-Y', strtotime($q->movedate)), 1, 0, 'L');
                              $pdf->Cell(30, 6, $q->kodebarang, 1, 0, 'L');
                              $pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
                              $pdf->Cell(15, 6, $q->satuan, 1, 0, 'L');
                              $pdf->Cell(10, 6, number_format($q->qtymove, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, number_format($q->hpp, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, number_format($q->totalhpp, 2), 1, 0, 'R');
                              $pdf->ln();
                         }
                    }
               } else if ($laporan == 2) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         // rumus pak har
                         // SELECT
                         // tbl_apodmove.kodebarang,
                         // namabarang,
                         // tbl_apodmove.satuan,
                         // tbl_apodmove.qtymove,
                         // tbl_apodmove.hpp,
                         // tbl_apodmove.hpp*qtymove AS totalhpp 
                         // FROM tbl_apohmove 
                         // INNER JOIN tbl_apodmove
                         // INNER JOIN tbl_barang 
                         // WHERE tbl_apohmove.koders = 'ska' 
                         // AND tbl_apohmove.movedate >= '2022-07-01' AND tbl_apohmove.movedate <= '2022-07-11'
                         $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                         $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                         $y = "SELECT
                         tbl_apodmove.kodebarang,
                         (SELECT namabarang FROM tbl_barang WHERE kodebarang = tbl_apodmove.kodebarang) AS namabarang,
                         tbl_apodmove.satuan,
                         tbl_apodmove.qtymove,
                         tbl_apodmove.hpp,
                         tbl_apodmove.hpp*qtymove AS totalhpp 
                         FROM tbl_apohmove 
                         JOIN tbl_apodmove ON tbl_apohmove.moveno = tbl_apodmove.moveno
                         WHERE tbl_apohmove.koders = '$unit' 
                         AND tbl_apohmove.movedate between '$dari' AND '$sampai' and tbl_apohmove.dari = '$qx->dari' and tbl_apohmove.ke = '$qx->ke'
                         ";
                         $query = $this->db->query($y)->result();
                         $pdf->setfont('Arial', 'B', 6);
                         $pdf->Cell(10, 6, 'Dari', 0, 0, 'L');
                         $pdf->Cell(30, 6, $dari_gudang->keterangan, 0, 0, 'L');
                         $pdf->Cell(10, 6, 'Ke', 0, 0, 'L');
                         $pdf->Cell(30, 6, $ke_gudang->keterangan, 0, 1, 'L');
                         $pdf->SetFillColor(0, 0, 139);
                         $pdf->settextcolor(0);
                         $pdf->Cell(5, 6, 'No', 1, 0, 'C');
                         $pdf->Cell(40, 6, 'Kode Barang', 1, 0, 'C');
                         $pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
                         $pdf->Cell(25, 6, 'Satuan', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
                         $pdf->Cell(30, 6, 'HPP', 1, 0, 'C');
                         $pdf->Cell(30, 6, 'Total HPP', 1, 0, 'C');
                         $pdf->ln();
                         $no = 1;
                         foreach ($query as $q) {
                              $pdf->Cell(5, 6, $no++, 1, 0, 'C');
                              $pdf->Cell(40, 6, $q->kodebarang, 1, 0, 'L');
                              $pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
                              $pdf->Cell(25, 6, $q->satuan, 1, 0, 'L');
                              $pdf->Cell(20, 6, number_format($q->qtymove, 2), 1, 0, 'R');
                              $pdf->Cell(30, 6, number_format($q->hpp, 2), 1, 0, 'R');
                              $pdf->Cell(30, 6, number_format($q->totalhpp, 2), 1, 0, 'R');
                              $pdf->ln();
                         }
                    }
               } else if ($laporan == 3) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         $y = "SELECT d.prdno, 
                         d.kodebarang, 
                         (select namabarang from tbl_barang where kodebarang = d.kodebarang) as namabarang,
                         d.qty, 
                         d.satuan,
                         d.hpp,
                         d.totalharga
                         FROM tbl_apodproduksi d
                         WHERE d.prdno = '$qx->prdno'
                         ";
                         $query = $this->db->query($y)->result();
                         $pdf->setfont('Arial', 'B', 12);
                         $pdf->Cell(190, 6, 'DEPO/GUDANG ' . $gudang->keterangan, 0, 1, 'C');
                         $pdf->setfont('Arial', 'B', 6);
                         $pdf->SetFillColor(0, 0, 139);
                         $pdf->settextcolor(0);
                         $pdf->Cell(5, 6, 'No', 1, 0, 'C');
                         $pdf->Cell(45, 6, 'No Tr / Keterangan', 1, 0, 'C');
                         $pdf->Cell(25, 6, 'Kode Barang', 1, 0, 'C');
                         $pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
                         $pdf->Cell(15, 6, 'Jumlah', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Satuan', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'HPP', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Total HPP', 1, 0, 'C');
                         $pdf->ln();
                         $pdf->Cell(50, 6, date('d-m-Y', strtotime($qx->tglproduksi)) . ' / Barang Jadi', 1, 0, 'C');
                         $pdf->Cell(25, 6, $qx->kodebarang, 1, 0, 'L');
                         $pdf->Cell(40, 6,  $qx->namabarang, 1, 0, 'L');
                         $pdf->Cell(15, 6,  number_format($qx->qtyjadi, 2), 1, 0, 'R');
                         $pdf->Cell(20, 6,  $qx->satuan, 1, 0, 'L');
                         $pdf->Cell(20, 6,  number_format($qx->hpp, 2), 1, 0, 'R');
                         $pdf->Cell(20, 6,  number_format($qx->totalhpp, 2), 1, 0, 'R');
                         $pdf->ln();
                         $no = 1;
                         foreach ($query as $q) {
                              $pdf->Cell(5, 6, $no++, 1, 0, 'C');
                              $pdf->Cell(45, 6, $q->prdno . ' / Bahan Baku', 1, 0, 'L');
                              $pdf->Cell(25, 6, $q->kodebarang, 1, 0, 'L');
                              $pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
                              $pdf->Cell(15, 6, number_format($q->qty, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, $q->satuan, 1, 0, 'L');
                              $pdf->Cell(20, 6, number_format($q->hpp, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, number_format($q->totalharga, 2), 1, 0, 'R');
                              $pdf->ln();
                         }
                         $pdf->ln();
                    }
               } else if ($laporan == 4) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         if ($da == 1) {
                              $y = "
                              SELECT
                              a.*
                              FROM (
                                   SELECT 
                                   kodebarang,
                                   (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang,
                                   (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as sat_HNA,
                                   ((select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang)*hasilso) as total_HNA
                                   FROM tbl_aposesuai
                                   WHERE koders = '$unit' and tglso between '$dari' and '$sampai' and gudang = '$qx->gudang'
                                   ORDER BY kodebarang
                              ) AS a
                              ";
                         } else {
                              $y = "
                              SELECT
                              a.*
                              FROM (
                                   SELECT 
                                   kodebarang,
                                   (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang,
                                   (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as sat_HNA,
                                   ((select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang)*hasilso) as total_HNA
                                   FROM tbl_aposesuai
                                   WHERE koders = '$unit' and gudang = '$depo' and tglso between '$dari' and '$sampai'
                                   ORDER BY kodebarang
                              ) AS a
                              ";
                         }
                         $query = $this->db->query($y)->result();
                         $pdf->setfont('Arial', 'B', 6);
                         $pdf->Cell(30, 6, 'DARI GUDANG : ', 0, 0, 'L');
                         $pdf->Cell(30, 6, $gudang->keterangan, 0, 1, 'L');
                         $pdf->SetFillColor(0, 0, 139);
                         $pdf->settextcolor(0);
                         $pdf->Cell(5, 6, '', 'TLR', 0, 'C');
                         $pdf->Cell(38, 6, '', 'TLR', 0, 'C');
                         $pdf->Cell(40, 6, '', 'TLR', 0, 'C');
                         $pdf->Cell(15, 6, '', 'TLR', 0, 'C');
                         $pdf->Cell(20, 6, '', 'TLR', 0, 'C');
                         $pdf->Cell(36, 6, 'HPP', 1, 0, 'C');
                         $pdf->Cell(36, 6, 'HNA', 1, 1, 'C');
                         $pdf->Cell(5, 6, 'No', 'BLR', 0, 'C');
                         $pdf->Cell(38, 6, 'Kode Barang', 'BLR', 0, 'C');
                         $pdf->Cell(40, 6, 'Nama Barang', 'BLR', 0, 'C');
                         $pdf->Cell(15, 6, 'Satuan', 'BLR', 0, 'C');
                         $pdf->Cell(20, 6, 'Qty Adjustment', 'BLR', 0, 'C');
                         $pdf->Cell(18, 6, 'Sat', 1, 0, 'C');
                         $pdf->Cell(18, 6, 'Total', 1, 0, 'C');
                         $pdf->Cell(18, 6, 'Sat', 1, 0, 'C');
                         $pdf->Cell(18, 6, 'Total', 1, 0, 'C');
                         $pdf->ln();
                         $no = 1;
                         foreach ($query as $q) {
                              $pdf->Cell(5, 6, $no++, 1, 0, 'C');
                              $pdf->Cell(38, 6, $q->kodebarang, 1, 0, 'L');
                              $pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
                              $pdf->Cell(15, 6, $q->satuan, 1, 0, 'L');
                              $pdf->Cell(20, 6, number_format($q->hasilso, 2), 1, 0, 'R');
                              $pdf->Cell(18, 6, number_format($q->sat_HPP, 2), 1, 0, 'R');
                              $pdf->Cell(18, 6, number_format($q->total_HPP, 2), 1, 0, 'R');
                              $pdf->Cell(18, 6, number_format($q->sat_HNA, 2), 1, 0, 'R');
                              $pdf->Cell(18, 6, number_format($q->total_HNA, 2), 1, 0, 'R');
                              $pdf->ln();
                         }
                         $pdf->ln();
                    }
               } else if ($laporan == 5) {
               } else if ($laporan == 6) {
                    foreach ($queryx as $qx) {
                         $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$qx->depocode'")->row();
                         if ($depo != '') {
                              $kondisi = " AND p.gudang = '$depo'";
                              $gdx = $gudang->keterangan;
                         } else {
                              $kondisi = "";
                              $gdx = 'SEMUA GUDANG';
                         }
                         $y = "SELECT*FROM(
                              SELECT 
                                        a.*,
     
                                        (select namabarang from tbl_barang where kodebarang = a.kodebarang) as namabarang,
     
                                        (select satuan1 from tbl_barang where kodebarang = a.kodebarang) as satuan,
     
                                        IFNULL((select qty_terima from 
                                                  (select c.koders,c.kodebarang, sum(c.qty_terima)qty_terima,gudang 
                                                  from tbl_baranghterima b join tbl_barangdterima c on b.terima_no = c.terima_no
                                                  group by c.koders,c.kodebarang,gudang
                                                  order by koders,kodebarang)as terima 
                                             where terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) as pembelian,
     
                                        IFNULL((SELECT qtymove FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                                  FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                  GROUP BY e.koders,e.kodebarang,ke
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                                        ),0) AS mutasi_in,
     
                                        IFNULL((SELECT qtyjadi FROM 
                                                  (SELECT koders,kodebarang, SUM(qtyjadi) qtyjadi,gudang 
                                                  FROM tbl_apohproduksi d
                                                  GROUP BY koders,kodebarang,gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS produksi,
     
                                        IFNULL((select hasilso from
                                                  (select koders, kodebarang, sum(sesuai)hasilso, gudang
                                                  from tbl_aposesuai group by koders,kodebarang,gudang
                                                  order by koders, kodebarang
                                                  ) as terima
                                             where terima.kodebarang=a.kodebarang and terima.koders=a.koders and terima.gudang=a.gudang
                                        ), 0) as so,
     
                                        IFNULL((SELECT qty_retur FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qty_retur,gudang 
                                                  FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
                                                  GROUP BY e.koders,e.kodebarang,gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS retur_beli,
     
                                        (
                                             (IFNULL((SELECT qty_terima FROM 
                                                       (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                                       FROM tbl_baranghterima b JOIN tbl_barangdterima c ON b.terima_no = c.terima_no
                                                       GROUP BY c.koders,c.kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT qtymove FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                                       FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                       GROUP BY e.koders,e.kodebarang,ke
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT qtyjadi FROM 
                                                       (SELECT koders,kodebarang, SUM(qtyjadi)qtyjadi,gudang 
                                                       FROM tbl_apohproduksi d
                                                       GROUP BY koders,kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT hasilso FROM
                                                       (SELECT koders, kodebarang, SUM(sesuai)hasilso, gudang
                                                       FROM tbl_aposesuai GROUP BY koders,kodebarang,gudang
                                                       ORDER BY koders, kodebarang
                                                       ) AS terima
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ), 0))
                                             +
                                             (IFNULL((SELECT qty_retur FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qty_retur,gudang 
                                                       FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
                                                       GROUP BY e.koders,e.kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                        ) AS total_masuk,
     
                                        IFNULL((SELECT qtyjual FROM 
                                                  (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
                                                  FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                                  JOIN tbl_apoposting ps ON ps.resepno=b.resepno
                                                  GROUP BY c.koders,c.kodebarang,b.gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS jual,
     
                                        IFNULL((SELECT qtymove FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                                  FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                  GROUP BY e.koders,e.kodebarang,dari
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                                        ),0) AS mutasi_out,
     
                                        IFNULL((SELECT qtyretur FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qtyretur,gudang 
                                                  FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
                                                  GROUP BY e.koders,e.kodebarang,gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS retur_jual,
     
                                        IFNULL((SELECT qty FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                  FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
                                                  GROUP BY e.koders,e.kodebarang,gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS produksi_out, 
     
                                        0 as bhp,
     
                                        IFNULL((SELECT qty FROM 
                                                  (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                  FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
                                                  GROUP BY e.koders,e.kodebarang,gudang
                                                  ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ),0) AS expired,
                                        (
                                             (IFNULL((SELECT qtyjual FROM 
                                                       (SELECT c.koders,c.kodebarang, SUM(c.qty)qtyjual,b.gudang 
                                                       FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno 
                                                       GROUP BY c.koders,c.kodebarang,b.gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT qtymove FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                                       FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                       GROUP BY e.koders,e.kodebarang,dari
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT qtyretur FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qtyretur,gudang 
                                                       FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
                                                       GROUP BY e.koders,e.kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                             +
                                             (IFNULL((SELECT qty FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                       FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
                                                       GROUP BY e.koders,e.kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                             +
                                             0
                                             +
                                             (IFNULL((SELECT qty FROM 
                                                       (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                       FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
                                                       GROUP BY e.koders,e.kodebarang,gudang
                                                       ORDER BY koders,kodebarang)AS terima 
                                                  WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0))
                                        ) as total_keluar,
     
                                        (select hpp from tbl_barang where kodebarang = a.kodebarang) as hpp,
     
                                        (
                                             (
                                                  (
                                                       (
                                                            (IFNULL((SELECT qty_terima FROM 
                                                                      (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                                                      FROM tbl_baranghterima b JOIN tbl_barangdterima c ON b.terima_no = c.terima_no
                                                                      GROUP BY c.koders,c.kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT qtymove FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                                                      FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                                      GROUP BY e.koders,e.kodebarang,ke
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT qtyjadi FROM 
                                                                      (SELECT koders,kodebarang, SUM(qtyjadi) qtyjadi,gudang 
                                                                      FROM tbl_apohproduksi d
                                                                      GROUP BY koders,kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT hasilso FROM
                                                                      (SELECT koders, kodebarang, SUM(sesuai)hasilso, gudang
                                                                      FROM tbl_aposesuai GROUP BY koders,kodebarang,gudang
                                                                      ORDER BY koders, kodebarang
                                                                      ) AS terima
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ), 0))
                                                            +
                                                            (IFNULL((SELECT qty_retur FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qty_retur,gudang 
                                                                      FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
                                                                      GROUP BY e.koders,e.kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                       )
                                                       -
                                                       (
                                                            (IFNULL((SELECT qtyjual FROM 
                                                                      (SELECT c.koders,c.kodebarang, SUM(c.qty)qtyjual,b.gudang 
                                                                      FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                                                      GROUP BY c.koders,c.kodebarang,b.gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT qtymove FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                                                      FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                                      GROUP BY e.koders,e.kodebarang,dari
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT qtyretur FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qtyretur,gudang 
                                                                      FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
                                                                      GROUP BY e.koders,e.kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                            +
                                                            (IFNULL((SELECT qty FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                                      FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
                                                                      GROUP BY e.koders,e.kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                            +
                                                            0
                                                            +
                                                            (IFNULL((SELECT qty FROM 
                                                                      (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                                      FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
                                                                      GROUP BY e.koders,e.kodebarang,gudang
                                                                      ORDER BY koders,kodebarang)AS terima 
                                                                 WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                            ),0))
                                                       )
                                                  )
                                             )
                                             *
                                             (SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang)
                                        ) as total_persediaan_rp,
                                        (
                                             (
                                                  (
                                                       (IFNULL((SELECT qty_terima FROM 
                                                                 (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                                                 FROM tbl_baranghterima b JOIN tbl_barangdterima c ON b.terima_no = c.terima_no
                                                                 GROUP BY c.koders,c.kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT qtymove FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                                                 FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                                 GROUP BY e.koders,e.kodebarang,ke
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT qtyjadi FROM 
                                                                 (SELECT koders,kodebarang, SUM(qtyjadi)qtyjadi,gudang 
                                                                 FROM tbl_apohproduksi d
                                                                 GROUP BY koders,kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT hasilso FROM
                                                                 (SELECT koders, kodebarang, SUM(sesuai)hasilso, gudang
                                                                 FROM tbl_aposesuai GROUP BY koders,kodebarang,gudang
                                                                 ORDER BY koders, kodebarang
                                                                 ) AS terima
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ), 0))
                                                       +
                                                       (IFNULL((SELECT qty_retur FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qty_retur,gudang 
                                                                 FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
                                                                 GROUP BY e.koders,e.kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                  )
                                                  -
                                                  (
                                                       (IFNULL((SELECT qtyjual FROM 
                                                                 (SELECT c.koders,c.kodebarang, SUM(c.qty)qtyjual,b.gudang 
                                                                 FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                                                 GROUP BY c.koders,c.kodebarang,b.gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT qtymove FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                                                 FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                                                                 GROUP BY e.koders,e.kodebarang,dari
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT qtyretur FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qtyretur,gudang 
                                                                 FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
                                                                 GROUP BY e.koders,e.kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                       +
                                                       (IFNULL((SELECT qty FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                                 FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
                                                                 GROUP BY e.koders,e.kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                       +
                                                       0
                                                       +
                                                       (IFNULL((SELECT qty FROM 
                                                                 (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                                                                 FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
                                                                 GROUP BY e.koders,e.kodebarang,gudang
                                                                 ORDER BY koders,kodebarang)AS terima 
                                                            WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                                       ),0))
                                                  )
                                             )
                                        ) as salakhir
     
                                   FROM
                                        (
                                             select koders,kodebarang,gudang, tglso, saldoakhir
                                             from tbl_barangstock a
                                             group by koders,kodebarang,gudang
                                        ) a

                                   )p
                                   WHERE p.koders = '$unit' $kondisi and p.tglso between '$dari' and '$sampai' and namabarang is not null
                                   ";
                         $query = $this->db->query($y)->result();
                    }
                    
                    $kop       = $this->M_cetak->kop($unit);
                    $namars    = $kop['namars'];
                    $alamat    = $kop['alamat'];
                    $alamat2   = $kop['alamat2'];
                    $kota      = $kop['kota'];
                    $phone     = $kop['phone'];
                    $whatsapp  = $kop['whatsapp'];
                    $npwp      = $kop['npwp'];
                    $chari  = '';
                    $chari .= "
                    <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                         <thead>
                              <tr>
                                   <td rowspan=\"6\" align=\"center\">
                                        <img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
                                   </td>
                                   <td colspan=\"20\">
                                        <b>
                                             <tr>
                                                  <td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat2</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">No. NPWP : $npwp</td>
                                             </tr>
                                        </b>
                                   </td>
                              </tr>
                         </table>";
                    $chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td> &nbsp; </td>
                                   </tr> 
                              </table>";
                    $chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
                    $chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
                    $chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
                    $chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
                                   <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
                                   <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . $gdx . "</td>
                                   <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Tanggal</td>
                                   <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
                                   <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . date('d-m-Y', strtotime($dari)) . ' / ' . date('d-m-Y', strtotime($sampai)) . "</td>
                              </tr>
                         </table>";
                    if ($keperluan == 1) {
                         $chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                   <tr>
                                        <td style=\"border:0\" align=\"center\"><br></td>
                                   </tr>
                                   <tr>
                                        <td width=\"3%\" align=\"center\" rowspan=\"2\"><br>No</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Akhir</td>
                                        <td width=\"24%\" align=\"center\" colspan=\"2\"><br>Fisik</td>
                                        <td width=\"24%\" align=\"center\" colspan=\"2\"><br>Kartu Stock</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Keterangan</td>
                                   </tr>
                                   <tr>
                                        <td width=\"4%\" align=\"center\"><br>" . $unit . "</td>
                                        <td width=\"4%\" align=\"center\"><br>RGN</td>
                                        <td width=\"4%\" align=\"center\"><br>" . $unit . "</td>
                                        <td width=\"4%\" align=\"center\"><br>RGN</td>
                                   </tr>
                              </thead>";
                         $no = 1;
                         foreach ($query as $q) {
                              $namabarang = $q->namabarang;
                              $satuan = $q->satuan;
                              $salakhir = number_format($q->salakhir);

                              $chari .= "<tr>
                                        <td align=\"center\">" . $no++ . "</td>
                                        <td align=\"left\">$namabarang</td>
                                        <td align=\"right\">$satuan</td>
                                        <td align=\"right\">$salakhir</td>
                                        <td align=\"right\"></td>
                                        <td align=\"right\"></td>
                                        <td align=\"right\"></td>
                                        <td align=\"right\"></td>
                                        <td align=\"right\"></td>
                                   </tr>";
                         }
                    } else {
                         $chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                   <tr>
                                        <td style=\"border:0\" align=\"center\"><br></td>
                                   </tr>
                                   <tr>
                                        <td width=\"3%\" align=\"center\" rowspan=\"2\"><br>No</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
                                        <td width=\"24%\" align=\"center\" colspan=\"6\"><br>Persedaan Masuk</td>
                                        <td width=\"24%\" align=\"center\" colspan=\"7\"><br>Persedaan Keluar</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Akhir</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Hpp Average</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Total Persediaan</td>
                                   </tr>
                                   <tr>
                                        <td width=\"4%\" align=\"center\"><br>Pembelian</td>
                                        <td width=\"4%\" align=\"center\"><br>Mutasi In</td>
                                        <td width=\"4%\" align=\"center\"><br>Produksi</td>
                                        <td width=\"4%\" align=\"center\"><br>So Adjustment</td>
                                        <td width=\"4%\" align=\"center\"><br>Retur Beli</td>
                                        <td width=\"4%\" align=\"center\"><br>Total Masuk</td>
                                        <td width=\"4%\" align=\"center\"><br>Jual</td>
                                        <td width=\"4%\" align=\"center\"><br>Mutasi Out</td>
                                        <td width=\"4%\" align=\"center\"><br>Retur Jual</td>
                                        <td width=\"4%\" align=\"center\"><br>Bahan Produksi</td>
                                        <td width=\"4%\" align=\"center\"><br>BHP</td>
                                        <td width=\"4%\" align=\"center\"><br>Barang Expired</td>
                                        <td width=\"4%\" align=\"center\"><br>Total Keluar</td>
                                   </tr>
                              </thead>";

                         $no = 1;
                         foreach ($query as $q) {
                              $kodebarang = $q->kodebarang;
                              $namabarang = $q->namabarang;
                              $satuan = $q->satuan;
                              $pembelian = number_format($q->pembelian);
                              $mutasi_in = number_format($q->mutasi_in);
                              $produksi = number_format($q->produksi);
                              $so = number_format($q->so);
                              $retur_beli = number_format($q->retur_beli);
                              $total_masuk = number_format($q->total_masuk);
                              $jual = number_format($q->jual);
                              $mutasi_out = number_format($q->mutasi_out);
                              $retur_jual = number_format($q->retur_jual);
                              $produksi_out = number_format($q->produksi_out);
                              $bhp = number_format($q->bhp);
                              $expired = number_format($q->expired);
                              $total_keluar = number_format($q->total_keluar);
                              $salakhir = number_format($q->salakhir);
                              $hpp = number_format($q->hpp);
                              $total_persediaan_rp = number_format($q->total_persediaan_rp);

                              $chari .= "<tr>
                                        <td align=\"center\">" . $no++ . "</td>
                                        <td align=\"left\">$kodebarang</td>
                                        <td align=\"left\">$namabarang</td>
                                        <td align=\"right\">$satuan</td>
                                        <td align=\"right\">$pembelian</td>
                                        <td align=\"right\">$mutasi_in</td>
                                        <td align=\"right\">$produksi</td>
                                        <td align=\"right\">$so</td>
                                        <td align=\"right\">$retur_beli</td>
                                        <td align=\"right\">$total_masuk</td>
                                        <td align=\"right\">$jual</td>
                                        <td align=\"right\">$mutasi_out</td>
                                        <td align=\"right\">$retur_jual</td>
                                        <td align=\"right\">$produksi_out</td>
                                        <td align=\"right\">$bhp</td>
                                        <td align=\"right\">$expired</td>
                                        <td align=\"right\">$total_keluar</td>
                                        <td align=\"right\">$salakhir</td>
                                        <td align=\"right\">$hpp</td>
                                        <td align=\"right\">$total_persediaan_rp</td>
                                   </tr>";
                         }
                    }
                    $chari .= "</table>";
                    $data['prev'] = $chari;
                    $judul = '06 LAPORAN PERSEDIAAN BARANG';
                    echo ("<title>$judul</title>");
                    // $this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
                    switch ($cekpdf) {
                         case 0;
                              echo ("<title>REKAP PENJUALAN HARIAN</title>");
                              echo ($chari);
                              break;
     
                         case 1;
                              $this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
                              break;
                         case 2;
                              header("Cache-Control: no-cache, no-store, must-revalidate");
                              header("Content-Type: application/vnd-ms-excel");
                              header("Content-Disposition: attachment; filename= $judul.xls");
                              $this->load->view('app/master_cetak', $data);
                              break;
                    }
               }
               if ($laporan != 6) {
                    $pdf->Output();
               }
          }
     }

     public function cetak2()
     {
          $cek        = $this->session->userdata('level');
          $cekpdf     = $this->input->get('pdf');
          $dari       = $this->input->get('dari');
          $sampai     = $this->input->get('sampai');
          $da         = $this->input->get('da');
          $depo       = $this->input->get('depo');
          $laporan    = $this->input->get('laporan');
          $keperluan  = $this->input->get('keperluan');
          $cabang     = $this->session->userdata('unit');
          $unit       = $cabang;
          $body       = '';
          $date       = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
          $profile    = data_master('tbl_namers', array('koders' => $unit));
          $kota       = $profile->kota;
          $position   = 'P';
          if (!empty($cek)) {
               if($laporan == 1){
                    $judul = '01 Laporan Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
                    foreach($queryx as $qx){
                         $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                         $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                         $body .=  "<tbody>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">$dari_gudang->keterangan<b style=\"color:red;\"> >> </b>$ke_gudang->keterangan</td>
                                        </tr>";
                         $query = $this->db->query("SELECT h.moveno, h.movedate, d.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = d.kodebarang) AS namabarang, d.satuan, d.qtymove, (select hpp from tbl_barang where kodebarang = d.kodebarang) as hpp, (d.qtymove*(select hpp from tbl_barang where kodebarang = d.kodebarang)) AS totalhpp, h.dari, h.ke FROM tbl_apohmove h JOIN tbl_apodmove d ON h.moveno= d.moveno WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$qx->dari' and h.ke = '$qx->ke'")->result();
                         $no = 1;
                         foreach($query as $q){
                              $body .=  "<tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Bukti Tr.</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">HPP</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total HPP</td>
                                        </tr>";
                              $body .=  "<tr>
                                             <td style=\"text-align: right;\">" . $no++ . "</td>
                                             <td>$q->moveno</td>
                                             <td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->movedate)) . "</td>
                                             <td>$q->kodebarang</td>
                                             <td>$q->namabarang</td>
                                             <td>$q->satuan</td>
                                             <td style=\"text-align: right;\">" . number_format($q->qtymove) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->hpp) . "</td>
                                             <td width=\"15%\" style=\"text-align: right;\">" . number_format($q->totalhpp) . "</td>
                                        </tr>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">&nbsp;</td>
                                        </tr>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">&nbsp;</td>
                                        </tr>";
                         }
                    }
                    $body .=       "</tbody>
                              </table>";
               } else if($laporan == 2){
                    $judul = '02 Laporan Rekap Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
                    foreach ($queryx as $qx) {
                         $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                         $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                         $body .=  "<tbody>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">$dari_gudang->keterangan<b style=\"color:red;\"> >> </b>$ke_gudang->keterangan</td>
                                        </tr>";
                         $query = $this->db->query("SELECT tbl_apodmove.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = tbl_apodmove.kodebarang) AS namabarang, tbl_apodmove.satuan, tbl_apodmove.qtymove, tbl_apodmove.hpp, tbl_apodmove.hpp*qtymove AS totalhpp 
                         FROM tbl_apohmove JOIN tbl_apodmove ON tbl_apohmove.moveno = tbl_apodmove.moveno WHERE tbl_apohmove.koders = '$unit' AND tbl_apohmove.movedate between '$dari' AND '$sampai' and tbl_apohmove.dari = '$qx->dari' and tbl_apohmove.ke = '$qx->ke'")->result();
                         $no = 1;
                         foreach ($query as $q) {
                              $body .=  "<tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">HPP</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total HPP</td>
                                        </tr>";
                              $body .=  "<tr>
                                             <td style=\"text-align: right;\">" . $no++ . "</td>
                                             <td>$q->kodebarang</td>
                                             <td>$q->namabarang</td>
                                             <td>$q->satuan</td>
                                             <td style=\"text-align: right;\">" . number_format($q->qtymove) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->hpp) . "</td>
                                             <td width=\"15%\" style=\"text-align: right;\">" . number_format($q->totalhpp) . "</td>
                                        </tr>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">&nbsp;</td>
                                        </tr>
                                        <tr>
                                             <td colspan=\"12\" style=\"border-top:none; border-bottom:none; border-left:none; border-right:none;\">&nbsp;</td>
                                        </tr>";
                         }
                    }
                    $body .=       "</tbody>
                              </table>";
               } else if($laporan == 3){
                    $position = "L";
                    $judul = '03 Laporan Produksi Farmasi';
                    if ($da == 1) {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang,
                         (select satuan from tbl_barang where kodebarang = h.kodebarang) as satuan,
                         (select hpp from tbl_barang where kodebarang = h.kodebarang) as hpp,
                         ((select hpp from tbl_barang where kodebarang = h.kodebarang) * h.qtyjadi) as totalhpp
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai'
                         group by h.prdno
                         ";
                    } else {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang,
                         (select satuan from tbl_barang where kodebarang = h.kodebarang) as satuan,
                         (select hpp from tbl_barang where kodebarang = h.kodebarang) as hpp,
                         ((select hpp from tbl_barang where kodebarang = h.kodebarang) * h.qtyjadi) as totalhpp
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by h.prdno
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                                   <thead>
                                        <tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Tr / Keterangan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">HPP</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total HPP</td>
                                        </tr>
                                   </thead>";
                    foreach($queryx as $qx){
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         $body .=  "<tbody>
                                        <tr>
                                             <td colspan=\"8\" style=\"text-align:center\"> DEPO / GUDANG : <b>".strtoupper($gudang->keterangan). "</b></td>
                                        </tr>
                                        <tr>
                                             <td colspan=\"2\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($qx->tglproduksi)) . " / Barang Jadi</td>
                                             <td>$qx->kodebarang</td>
                                             <td>$qx->namabarang</td>
                                             <td>$qx->satuan</td>
                                             <td style=\"text-align: right;\">" . number_format($qx->qtyjadi) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($qx->hpp) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($qx->totalhpp) . "</td>
                                        </tr>";
                         $query = $this->db->query("SELECT d.prdno, d.kodebarang, (select namabarang from tbl_barang where kodebarang = d.kodebarang) as namabarang, d.qty, d.satuan, d.hpp, d.totalharga FROM tbl_apodproduksi d WHERE d.prdno = '$qx->prdno'")->result();
                         $no = 1;
                         foreach($query as $q){
                              $body .=  "<tr>
                                             <td style=\"text-align: right;\">" . $no++ . "</td>
                                             <td>$q->prdno</td>
                                             <td>$q->kodebarang</td>
                                             <td>$q->namabarang</td>
                                             <td>$q->satuan</td>
                                             <td style=\"text-align: right;\">" . number_format($q->qty) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->hpp) . "</td>
                                             <td width=\"15%\" style=\"text-align: right;\">" . number_format($q->totalharga) . "</td>
                                        </tr>
                                   </tbody>";
                         }
                    }
                    $body .=  "</table>";
               } else if($laporan == 4){
                    $position = "L";
                    $judul = '04 Laporan Stock Opname';
                    if ($da == 1) {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    foreach($queryx as $qx){
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td>GUDANG : $gudang->keterangan</td>
                                   </tr> 
                              </table>";
                         if ($da == 1) {
                              $y = " SELECT
                              a.*
                              FROM (
                                   SELECT 
                                   kodebarang,
                                   (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang,
                                   (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as sat_HNA,
                                   ((select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang)*hasilso) as total_HNA
                                   FROM tbl_aposesuai
                                   WHERE koders = '$unit' and tglso between '$dari' and '$sampai' and gudang = '$qx->gudang'
                                   ORDER BY kodebarang
                              ) AS a
                              ";
                         } else {
                              $y = "SELECT
                              a.*
                              FROM (
                                   SELECT 
                                   kodebarang,
                                   (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang,
                                   (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as sat_HNA,
                                   ((select hargabeli from tbl_barang where kodebarang = tbl_aposesuai.kodebarang)*hasilso) as total_HNA
                                   FROM tbl_aposesuai
                                   WHERE koders = '$unit' and gudang = '$depo' and tglso between '$dari' and '$sampai'
                                   ORDER BY kodebarang
                              ) AS a
                              ";
                         }
                         $query = $this->db->query($y)->result();
                         $body .=  "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                                   <thead>
                                        <tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">No</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Kode Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Nama Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Satuan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Qty Adjusment</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" colspan=\"2\">HPP</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" colspan=\"2\">HNA</td>
                                        </tr>
                                        <tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Sat</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Sat</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
                                        </tr>
                                   </thead>";
                         $no = 1;
                         $thasilso = 0;
                         $tsat_HPP = 0;
                         $ttotal_HPP = 0;
                         $tsat_HNA = 0;
                         $ttotal_HNA = 0;
                         foreach($query as $q){
                              $body .=  "<tbody>
                                        <tr>
                                             <td style=\"text-align: right;\">" . $no++ . "</td>
                                             <td>$q->kodebarang</td>
                                             <td>$q->namabarang</td>
                                             <td>$q->satuan</td>
                                             <td style=\"text-align: right;\">" . number_format($q->hasilso) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->sat_HPP) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->total_HPP) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->sat_HNA) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->total_HNA) . "</td>
                                        </tr>
                                   </tbody>";
                              $thasilso += $q->hasilso;
                              $tsat_HPP += $q->sat_HPP;
                              $ttotal_HPP += $q->total_HPP;
                              $tsat_HNA += $q->sat_HNA;
                              $ttotal_HNA += $q->total_HNA;
                         }
                         $body .=  "<tfoot>
                                        <tr>
                                             <td style=\"text-align: center;\" colspan=\"4\">TOTAL</td>
                                             <td style=\"text-align: right;\">" . number_format($thasilso) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($tsat_HPP) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($ttotal_HPP) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($tsat_HNA) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($ttotal_HNA) . "</td>
                                        </tr>
                                   </tfoot>";
                         $body .=  "</table>";
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                        <tr>
                                             <td> &nbsp; </td>
                                        </tr> 
                                   </table>";
                    }
               } else if($laporan == 5){
                    $judul = "05 BERITA ACARA PEMUSNAHAN BARANG";
                    if ($da == 1) {
                         $cekheadergudang = $this->db->query("SELECT * FROM tbl_apohex WHERE tgl_ed BETWEEN '$dari' AND '$sampai' AND koders = '$unit'")->result();
                         foreach($cekheadergudang as $chg){
                              $gudangx = $this->db->get_where("tbl_depo", ['depocode' => $chg->gudang])->result();
                              foreach($gudangx as $gx){
                                   $gudang = $gx->keterangan;
                                   $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                             <tr>
                                                  <td style=\"text-align:left;\" width=\"10%\">NO</td>
                                                  <td style=\"text-align:center;\" width=\"10%\">:</td>
                                                  <td style=\"text-align:left;\" width=\"30%\">$chg->ed_no</td>
                                                  <td style=\"text-align:left;\" width=\"10%\">LOKASI</td>
                                                  <td style=\"text-align:center;\" width=\"10%\">:</td>
                                                  <td style=\"text-align:left;\" width=\"30%\">$gudang</td>
                                             </tr> 
                                             <tr>
                                                  <td style=\"text-align:left;\" width=\"10%\">TANGGAL</td>
                                                  <td style=\"text-align:center;\" width=\"10%\">:</td>
                                                  <td style=\"text-align:left;\" width=\"30%\">" . date("d-m-Y", strtotime($chg->tgl_ed)) . "</td>
                                                  <td style=\"text-align:left;\" width=\"10%\">KETERANGAN</td>
                                                  <td style=\"text-align:center;\" width=\"10%\">:</td>
                                                  <td style=\"text-align:left;\" width=\"30%\">$chg->keterangan</td>
                                             </tr> 
                                        </table>";
                                   $query = $this->db->query("SELECT h.ed_no, h.username, h.approve_1, h.approve_2, h.approve_3, h.tgl_ed, h.gudang, h.keterangan, (SELECT namabarang FROM tbl_barang WHERE kodebarang = d.kodebarang) AS namabarang, d.qty, d.exp_date, d.satuan, d.hpp, d.totalrp, d.rakno FROM tbl_apohex h JOIN tbl_apodex d ON h.ed_no=d.ed_no WHERE h.ed_no = '$chg->ed_no'")->result();
                                   $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                             <tr>
                                                  <td> &nbsp; </td>
                                             </tr> 
                                        </table>";
                                   $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                                             <thead>
                                                  <tr>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"25%\">Nama Barang</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Satuan</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tgl Expire</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"20%\">Keterangan</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Qty</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">HPP</td>
                                                       <td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">Total Harga</td>
                                                  </tr>
                                             </thead>";
                                   $no = 1;
                                   $tqty = 0;
                                   $thpp = 0;
                                   $ttotalrp = 0;
                                   foreach($query as $q){
                                        $aju = $this->db->get_where("userlogin", ['uidlogin' => $q->username])->row();
                                        $ap1 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_1])->row();
                                        $ap2 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_2])->row();
                                        $ap3 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_3])->row();
                                        $body .=  "<tbody>
                                                  <tr>
                                                       <td style=\"text-align: right;\">" . $no++ . "</td>
                                                       <td>$q->namabarang</td>
                                                       <td>$q->satuan</td>
                                                       <td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->exp_date)) . "</td>
                                                       <td>$q->rakno</td>
                                                       <td style=\"text-align: right;\">" . number_format($q->qty) . "</td>
                                                       <td style=\"text-align: right;\">" . number_format($q->hpp) . "</td>
                                                       <td style=\"text-align: right;\">" . number_format($q->totalrp) . "</td>
                                                  </tr>
                                             </tbody>";
                                        $tqty += $q->qty;
                                        $thpp += $q->hpp;
                                        $ttotalrp += $q->totalrp;
                                   }
                                   $body .=  "<tfoot>
                                                  <tr>
                                                       <td style=\"text-align: center;\" colspan=\"5\">TOTAL</td>
                                                       <td style=\"text-align: right;\">" . number_format($tqty) . "</td>
                                                       <td style=\"text-align: right;\">" . number_format($thpp) . "</td>
                                                       <td style=\"text-align: right;\">" . number_format($ttotalrp) . "</td>
                                                  </tr>
                                             </tfoot>";
                                   $body .=  "</table>";
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
                                   $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                             <tr>
                                                  <td style=\"text-align:center\">$kota, " . date("d-m-Y") . "</td>
                                             </tr>
                                        </table>";
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
                                   $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                             <tr>
                                                  <td style=\"text-align: center;\">DIAJUKAN,</td>
                                                  <td style=\"text-align: center;\">DISETUJUI 1,</td>
                                                  <td style=\"text-align: center;\">DISETUJUI 2,</td>
                                                  <td style=\"text-align: center;\">DISETUJUI 3,</td>
                                             </tr> 
                                             <tr>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                             </tr> 
                                             <tr>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                             </tr> 
                                             <tr>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                                  <td style=\"text-align: center;\">&nbsp;</td>
                                             </tr> 
                                             <tr>
                                                  <td style=\"text-align: center; font-size: 12px;\">$aju->username</td>
                                                  <td style=\"text-align: center; font-size: 12px;\">$ap1->username</td>
                                                  <td style=\"text-align: center; font-size: 12px;\">$ap2->username</td>
                                                  <td style=\"text-align: center; font-size: 12px;\">$ap3->username</td>
                                             </tr> 
                                        </table>";
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
                              }
                         }
                    } else {
                         $x = "SELECT h.ed_no, h.username, h.approve_1, h.approve_2, h.approve_3, h.tgl_ed, h.gudang, h.keterangan, (SELECT namabarang FROM tbl_barang WHERE kodebarang = d.kodebarang) AS namabarang, d.qty, d.exp_date, d.satuan, d.hpp, d.totalrp, d.rakno FROM tbl_apohex h JOIN tbl_apodex d ON h.ed_no=d.ed_no WHERE h.koders = '$unit' AND h.tgl_ed BETWEEN '$dari' AND '$sampai' OR h.gudang='$depo'";
                         $query = $this->db->query($x)->result();
                         $gudangx = $this->db->get_where("tbl_depo", ['depocode' => $depo])->row();
                         $gudang = $gudangx->keterangan;
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td style=\"text-align:left;\" width=\"10%\">NO</td>
                                        <td style=\"text-align:center;\" width=\"10%\">:</td>
                                        <td style=\"text-align:left;\" width=\"30%\">-</td>
                                        <td style=\"text-align:left;\" width=\"10%\">LOKASI</td>
                                        <td style=\"text-align:center;\" width=\"10%\">:</td>
                                        <td style=\"text-align:left;\" width=\"30%\">$gudang</td>
                                   </tr> 
                                   <tr>
                                        <td style=\"text-align:left;\" width=\"10%\">TANGGAL</td>
                                        <td style=\"text-align:center;\" width=\"10%\">:</td>
                                        <td style=\"text-align:left;\" width=\"30%\">" . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai)) . "</td>
                                        <td style=\"text-align:left;\" width=\"10%\">KETERANGAN</td>
                                        <td style=\"text-align:center;\" width=\"10%\">:</td>
                                        <td style=\"text-align:left;\" width=\"30%\">-</td>
                                   </tr> 
                              </table>";
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td> &nbsp; </td>
                                   </tr> 
                              </table>";
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                                   <thead>
                                        <tr>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Tgl Expire</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Keterangan</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">HPP</td>
                                             <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total Harga</td>
                                        </tr>
                                   </thead>";
                         $no = 1;
                         $tqty = 0;
                         $thpp = 0;
                         $ttotalrp = 0;
                         foreach ($query as $q) {
                              $aju = $this->db->get_where("userlogin", ['uidlogin' => $q->username])->row();
                              $ap1 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_1])->row();
                              $ap2 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_2])->row();
                              $ap3 = $this->db->get_where("userlogin", ['uidlogin' => $q->approve_3])->row();
                              $body .=  "<tbody>
                                        <tr>
                                             <td style=\"text-align: right;\">" . $no++ . "</td>
                                             <td>$q->namabarang</td>
                                             <td>$q->satuan</td>
                                             <td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->exp_date)) . "</td>
                                             <td>$q->rakno</td>
                                             <td style=\"text-align: right;\">" . number_format($q->qty) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->hpp) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($q->totalrp) . "</td>
                                        </tr>
                                   </tbody>";
                              $tqty += $q->qty;
                              $thpp += $q->hpp;
                              $ttotalrp += $q->totalrp;
                         }
                         $body .=       "<tfoot>
                                        <tr>
                                             <td style=\"text-align: center;\" colspan=\"5\">TOTAL</td>
                                             <td style=\"text-align: right;\">" . number_format($tqty) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($thpp) . "</td>
                                             <td style=\"text-align: right;\">" . number_format($ttotalrp) . "</td>
                                        </tr>
                                   </tfoot>";
                         $body .=  "</table>";
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
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td style=\"text-align:center\">$kota, " . date("d-m-Y") . "</td>
                                   </tr>
                              </table>";
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
                         $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td style=\"text-align: center;\">DIAJUKAN,</td>
                                        <td style=\"text-align: center;\">DISETUJUI 1,</td>
                                        <td style=\"text-align: center;\">DISETUJUI 2,</td>
                                        <td style=\"text-align: center;\">DISETUJUI 3,</td>
                                   </tr> 
                                   <tr>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                   </tr> 
                                   <tr>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                   </tr> 
                                   <tr>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                        <td style=\"text-align: center;\">&nbsp;</td>
                                   </tr> 
                                   <tr>
                                        <td style=\"text-align: center;\">$aju->username</td>
                                        <td style=\"text-align: center;\">$ap1->username</td>
                                        <td style=\"text-align: center;\">$ap2->username</td>
                                        <td style=\"text-align: center;\">$ap3->username</td>
                                   </tr> 
                              </table>";
                    }
               } else if($laporan == 6){
                    $position = "L";
                    if ($keperluan == 1) {
                         $judul = '06 Laporan Persediaan';
                         if ($da == 1) {
                              $x = "SELECT depocode FROM tbl_depo";
                         } else {
                              $x = "SELECT depocode FROM tbl_depo WHERE depocode = '$depo'";
                         }
                         $queryx = $this->db->query($x)->result();
                    } else {
                         $judul = '06 Laporan Persediaan';
                         if ($da == 1) {
                              $x = "SELECT depocode FROM tbl_depo";
                         } else {
                              $x = "SELECT depocode FROM tbl_depo WHERE depocode = '$depo'";
                         }
                         $queryx = $this->db->query($x)->result();
                    }
                    foreach($queryx as $qx){
                         if($depo != ""){
                              $kondisi = "gudang = '$depo'";
                         } else {
                              $kondisi = "gudang = '$qx->depocode'";
                         }
                         $y = "SELECT *, (total_masuk - total_keluar) AS saldo, hpp, ((total_masuk - total_keluar) * hpp) AS total FROM (
                                   SELECT p.*, (pembelian + move_in + produksi_jadi + so + retur_beli) AS total_masuk, (jual + mutasi_out + retur_jual + produksi_bahan + bhp + expire) AS total_keluar FROM (
                                        SELECT a.kodebarang, (SELECT namabarang FROM tbl_barang WHERE kodebarang = a.kodebarang) AS namabarang, (SELECT satuan1 FROM tbl_barang WHERE kodebarang = a.kodebarang) AS satuan, (SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang) AS hpp,
                                             IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dt.kodebarang, SUM(dt.qty_terima) AS qty, ht.gudang, ht.koders
                                        FROM tbl_barangdterima dt 
                                        JOIN tbl_baranghterima ht ON dt.terima_no = ht.terima_no
                                        WHERE ht.$kondisi AND ht.koders = '$unit' AND ht.terima_date BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dt.kodebarang
                                        ) AS beli
                                        WHERE beli.kodebarang=a.kodebarang
                                   )
                                   ,0) AS pembelian,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.dari, hm.koders
                                        FROM tbl_apodmove dm 
                                        JOIN tbl_apohmove hm ON dm.moveno = hm.moveno
                                        WHERE hm.dari = '$qx->depocode' AND hm.koders = '$unit' AND hm.movedate BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dm.kodebarang
                                        ) AS move_i
                                        WHERE move_i.kodebarang=a.kodebarang
                                   )
                                   ,0) AS move_in,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT kodebarang, SUM(qtyjadi) AS qty, gudang, koders
                                        FROM tbl_apohproduksi
                                        WHERE $kondisi AND koders = '$unit' AND tglproduksi BETWEEN '$dari' AND '$sampai'
                                        GROUP BY kodebarang
                                        ) AS prod_jadi
                                        WHERE prod_jadi.kodebarang=a.kodebarang
                                   )
                                   ,0) AS produksi_jadi,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT kodebarang, SUM(sesuai) AS qty, gudang, koders
                                        FROM tbl_aposesuai
                                        WHERE $kondisi AND koders = '$unit' AND tglso BETWEEN '$dari' AND '$sampai'
                                        GROUP BY kodebarang
                                        ) AS so_
                                        WHERE so_.kodebarang=a.kodebarang
                                   )
                                   ,0) AS so,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dr.kodebarang, SUM(dr.qty_retur) AS qty, hr.gudang, hr.koders
                                        FROM tbl_barangdreturbeli dr
                                        JOIN tbl_baranghreturbeli hr ON dr.retur_no = hr.retur_no
                                        WHERE hr.$kondisi AND hr.koders = '$unit' AND hr.retur_date BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dr.kodebarang
                                        ) AS ret
                                        WHERE ret.kodebarang=a.kodebarang
                                   )
                                   ,0) AS retur_beli,
                                   IFNULL(
                                   (
                                        SELECT SUM(qty) AS qty FROM
                                        (
                                        SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders
                                        FROM tbl_apodresep d
                                        JOIN tbl_apohresep h ON d.resepno = h.resepno
                                        WHERE h.$kondisi AND h.koders = '$unit' AND h.tglresep BETWEEN '$dari' AND '$sampai'
                                        GROUP BY d.kodebarang
                                        UNION ALL
                                        SELECT d.kodebarang, SUM(d.qtyr) AS qty, h.gudang, h.koders
                                        FROM tbl_apodetresep d
                                        JOIN tbl_apohresep h ON d.resepno = h.resepno
                                        WHERE h.$kondisi AND h.koders = '$unit' AND h.tglresep BETWEEN '$dari' AND '$sampai'
                                        GROUP BY d.kodebarang
                                        ) xx 
                                        WHERE xx.kodebarang=a.kodebarang
                                        GROUP BY xx.kodebarang
                                   )
                                   ,0) AS jual,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.ke, hm.koders
                                        FROM tbl_apodmove dm 
                                        JOIN tbl_apohmove hm ON dm.moveno = hm.moveno
                                        WHERE hm.ke = '$qx->depocode' AND hm.koders = '$unit' AND hm.movedate BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dm.kodebarang
                                        ) AS move_o
                                        WHERE move_o.kodebarang=a.kodebarang
                                   )
                                   ,0) AS mutasi_out,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dm.kodebarang, SUM(dm.qtyretur) AS qty, hm.gudang, hm.koders
                                        FROM tbl_apodreturjual dm 
                                        JOIN tbl_apohreturjual hm ON dm.returno = hm.returno
                                        WHERE hm.$kondisi AND hm.koders = '$unit' AND hm.tglretur BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dm.kodebarang
                                        ) AS retur_j
                                        WHERE retur_j.kodebarang=a.kodebarang
                                   )
                                   ,0) AS retur_jual,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT tbl_apodproduksi.kodebarang, SUM(tbl_apodproduksi.qty) AS qty, tbl_apohproduksi.gudang, tbl_apodproduksi.koders
                                        FROM tbl_apodproduksi
                                        JOIN tbl_apohproduksi ON tbl_apohproduksi.prdno = tbl_apodproduksi.prdno
                                        WHERE tbl_apohproduksi.$kondisi AND tbl_apodproduksi.koders = '$unit' AND tbl_apohproduksi.tglproduksi BETWEEN '$dari' AND '$sampai'
                                        GROUP BY kodebarang
                                        ) AS prod_jadi
                                        WHERE prod_jadi.kodebarang=a.kodebarang
                                   )
                                   ,0) AS produksi_bahan,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT tbl_apodpakai.kodeobat AS kodebarang, SUM(tbl_apodpakai.qty) AS qty, tbl_apohpakai.gudang, tbl_apohpakai.koders
                                        FROM tbl_apodpakai
                                        JOIN tbl_apohpakai ON tbl_apohpakai.nobhp = tbl_apodpakai.nobhp
                                        WHERE tbl_apohpakai.$kondisi AND tbl_apohpakai.koders = '$unit' AND tbl_apohpakai.tglbhp BETWEEN '$dari' AND '$sampai'
                                        GROUP BY kodebarang
                                        ) AS prod_jadi
                                        WHERE prod_jadi.kodebarang=a.kodebarang
                                   )
                                   ,0) AS bhp,
                                   IFNULL(
                                   (
                                        SELECT qty FROM
                                        (
                                        SELECT dm.kodebarang, SUM(dm.qty) AS qty, hm.gudang, hm.koders
                                        FROM tbl_apodex dm 
                                        JOIN tbl_apohex hm ON dm.ed_no = hm.ed_no
                                        WHERE hm.$kondisi AND hm.koders = '$unit' AND hm.tgl_ed BETWEEN '$dari' AND '$sampai'
                                        GROUP BY dm.kodebarang
                                        ) AS expi
                                        WHERE expi.kodebarang=a.kodebarang
                                   )
                                   ,0) AS expire
                                        FROM tbl_barangstock a
                                        WHERE a.$kondisi AND a.koders = '$unit'
                                   ) p
                              ) z
                         ";
                         $query = $this->db->query($y)->result();
                         $queryx = $this->db->query($y)->num_rows();
                         if($queryx > 0){
                              $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                             <tr>
                                                  <td> &nbsp; </td>
                                             </tr> 
                                   </table>";
                              $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$qx->depocode'")->row();
                              $body .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                                   <tr>
                                        <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
                                        <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>";

                              $body .= "<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . $gudang->keterangan . "</td>";

                              $body .= "<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Tanggal</td>
                                        <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
                                        <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . date('d-m-Y', strtotime($dari)) . ' / ' . date('d-m-Y', strtotime($sampai)) . "</td>
                                   </tr>
                              </table>";
                              if ($keperluan == 1) {
                                   $body .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                                   <thead>
                                        <tr>
                                             <td style=\"border:0\" align=\"center\"><br></td>
                                        </tr>
                                        <tr>
                                             <td width=\"3%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\"><br><b>No</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\"><br><b>Nama Barang/<b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\"><br><b>Satuan</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\"><br><b>Saldo Akhir</b></td>
                                             <td width=\"24%\" bgcolor=\"#cccccc\" align=\"center\" colspan=\"2\"><br><b>Fisik</b></td>
                                             <td width=\"24%\" bgcolor=\"#cccccc\" align=\"center\" colspan=\"2\"><br><b>Kartu Stock</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\"><br><b>Keterangan</b></td>
                                        </tr>
                                        <tr>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\"><br><b>" . $unit . "</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\"><br><b>RGN</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\"><br><b>" . $unit . "</b></td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\"><br><b>RGN</b></td>
                                        </tr>
                                   </thead>";
                                   $no = 1;
                                   foreach ($query as $q) {
                                        $namabarang = $q->namabarang;
                                        $satuan = $q->satuan;
                                        $salakhir = number_format($q->salakhir);
     
                                        $body .= "<tr>
                                             <td align=\"center\">" . $no++ . "</td>
                                             <td align=\"left\">$namabarang</td>
                                             <td align=\"right\">$satuan</td>
                                             <td align=\"right\">$salakhir</td>
                                             <td align=\"right\"></td>
                                             <td align=\"right\"></td>
                                             <td align=\"right\"></td>
                                             <td align=\"right\"></td>
                                             <td align=\"right\"></td>
                                        </tr>";
                                   }
                              } else {
                                   $body .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                                   <thead>
                                        <tr>
                                             <td style=\"border:0\" align=\"center\"><br></td>
                                        </tr>
                                        <tr>
                                             <td width=\"3%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">No</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Kode Barang</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Nama Barang</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Satuan</td>
                                             <td width=\"24%\" bgcolor=\"#cccccc\" align=\"center\" colspan=\"6\">Persedaan Masuk</td>
                                             <td width=\"24%\" bgcolor=\"#cccccc\" align=\"center\" colspan=\"7\">Persedaan Keluar</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Saldo Akhir</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Hpp Average</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\" rowspan=\"2\">Total Persediaan</td>
                                        </tr>
                                        <tr>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Pembelian</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Mutasi In</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Produksi</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">So Adjustment</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Retur Beli</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Total Masuk</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Jual</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Mutasi Out</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Retur Jual</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Bahan Produksi</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">BHP</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Barang Expired</td>
                                             <td width=\"4%\" bgcolor=\"#cccccc\" align=\"center\">Total Keluar</td>
                                        </tr>
                                   </thead>";
     
                                   $no = 1;
                                   foreach ($query as $q) {
                                        $kodebarang             = $q->kodebarang;
                                        $namabarang             = $q->namabarang;
                                        $satuan                 = $q->satuan;
                                        $pembelian              = number_format($q->pembelian);
                                        $mutasi_in              = number_format($q->move_in);
                                        $produksi               = number_format($q->produksi_jadi);
                                        $so                     = number_format($q->so);
                                        $retur_beli             = number_format($q->retur_beli);
                                        $total_masuk            = number_format($q->total_masuk);
                                        $jual                   = number_format($q->jual);
                                        $mutasi_out             = number_format($q->mutasi_out);
                                        $retur_jual             = number_format($q->retur_jual);
                                        $produksi_out           = number_format($q->produksi_bahan);
                                        $bhp                    = number_format($q->bhp);
                                        $expired                = number_format($q->expire);
                                        $total_keluar           = number_format($q->total_keluar);
                                        $salakhir               = number_format($q->saldo);
                                        $hpp                    = number_format($q->hpp);
                                        $total_persediaan_rp    = number_format($q->total);
     
                                        $body .= "<tr>
                                             <td align=\"center\">" . $no++ . "</td>
                                             <td align=\"left\">$kodebarang</td>
                                             <td align=\"left\">$namabarang</td>
                                             <td align=\"right\">$satuan</td>
                                             <td align=\"right\">$pembelian</td>
                                             <td align=\"right\">$mutasi_in</td>
                                             <td align=\"right\">$produksi</td>
                                             <td align=\"right\">$so</td>
                                             <td align=\"right\">$retur_beli</td>
                                             <td align=\"right\">$total_masuk</td>
                                             <td align=\"right\">$jual</td>
                                             <td align=\"right\">$mutasi_out</td>
                                             <td align=\"right\">$retur_jual</td>
                                             <td align=\"right\">$produksi_out</td>
                                             <td align=\"right\">$bhp</td>
                                             <td align=\"right\">$expired</td>
                                             <td align=\"right\">$total_keluar</td>
                                             <td align=\"right\">$salakhir</td>
                                             <td align=\"right\">$hpp</td>
                                             <td align=\"right\">$total_persediaan_rp</td>
                                        </tr>";
                                   }
                              }
                              $body .= "</table>";
                         }
                    }
               }
               $this->M_template_cetak->template($judul, $body, $position, $date,$cekpdf);
          } else {
               header('location:' . base_url());
          }
     }

     public function excel()
     {
          $cek = $this->session->userdata('level');
          $dari = $this->input->get('dari');
          $sampai = $this->input->get('sampai');
          $da = $this->input->get('da');
          $depo = $this->input->get('depo');
          $laporan = $this->input->get('laporan');
          $unit = $this->session->userdata('unit');
          if (!empty($cek)) {
               if ($laporan == '1') {
                    $judul = '01 Laporan Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                         'unit' => $unit,
                         'dari' => $dari,
                         'sampai' => $sampai,
                    ];
                    $this->load->view('farmasi/Persediaan/v_excel_01.php', $data);
               } else if ($laporan == '2') {
                    $judul = '02 Laporan Rekap Mutasi Barang';
                    if ($da == 1) {
                         $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmove h
                         JOIN tbl_apodmove d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                         'unit' => $unit,
                         'dari' => $dari,
                         'sampai' => $sampai,
                    ];
                    $this->load->view('farmasi/Persediaan/v_excel_02.php', $data);
               } else if ($laporan == '3') {
                    $judul = 'Laporan Produksi Farmasi';
                    if ($da == 1) {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai'
                         group by h.prdno
                         ";
                    } else {
                         $x = "SELECT
                         h.*, (select namabarang from tbl_barang where kodebarang = h.kodebarang) as namabarang
                         FROM tbl_apohproduksi h
                         JOIN tbl_apodproduksi d ON h.prdno= d.prdno
                         WHERE h.koders = '$unit' AND h.tglproduksi BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by h.prdno
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                         'unit' => $unit,
                         'depo' => $depo,
                         'dari' => $dari,
                         'sampai' => $sampai,
                    ];
                    $this->load->view('farmasi/Persediaan/v_excel_03.php', $data);
               } else if ($laporan == '4') {
                    $judul = 'Laporan Stock Opname';
                    if ($da == 1) {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                         'unit' => $unit,
                         'da' => $da,
                         'depo' => $depo,
                         'dari' => $dari,
                         'sampai' => $sampai,
                    ];
                    $this->load->view('farmasi/Persediaan/v_excel_04.php', $data);
               } else if ($laporan == '5') {
               } else if ($laporan == '6') {
                    $judul = 'Laporan Persediaan';
                    if ($da == 1) {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuai h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                         'unit' => $unit,
                         'depo' => $depo,
                         'dari' => $dari,
                         'sampai' => $sampai,
                    ];
                    $this->load->view('farmasi/Persediaan/v_excel_06.php', $data);
               }
          }
     }
}
