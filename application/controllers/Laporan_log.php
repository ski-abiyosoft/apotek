<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_log extends CI_Controller
{
     public function __construct()
     {
          parent::__construct();
          $this->session->set_userdata('menuapp', '4000');
          $this->session->set_userdata('submenuapp', '4300');
          $this->load->helper('simkeu_nota');
          $this->load->model('M_laporan');
          $this->load->model('M_rs');
          $this->load->model('M_cetak');
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
               $d['akses'] = $this->M_global->cek_menu_akses($cek, 4300);
               $this->load->view('logistik/v_laporan_persediaan', $d);
          } else {
               header('location:' . base_url());
          }
     }
     public function cetak()
     {
          $cek = $this->session->userdata('level');
          $dari = $this->input->get('dari');
          $sampai = $this->input->get('sampai');
          $da = $this->input->get('da');
          $depo = $this->input->get('depo');
          $laporan = $this->input->get('laporan');
          $cabang = $this->session->userdata('unit');
          $unit = $cabang;
          if (!empty($cek)) {
               if ($laporan == 1) {
                    $judul = '01 Laporan Mutasi Logistik';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.dari, h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
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
                    $judul = '02 Laporan Stock Opname';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 3) {
                    $judul = '03 Laporan Pemakaian Logistik';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang
                         ";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
               } else if ($laporan == 4) {
                    $judul = '04 Laporan Persediaan (Detail)';
                    if ($da == 1) {
                         $x = "SELECT depocode, keterangan FROM tbl_depo";
                    } else {
                         $x = "SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$depo'";
                    }
                    $queryx = $this->db->query($x)->result();
                    $data = [
                         'judul' => $judul,
                         'queryx' => $queryx,
                    ];
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
               $pdf->setID($nama_usaha, $alamat1, $alamat2);
               $pdf->setjudul($judul . ' CABANG ' . $unit);
               $pdf->setsubjudul('Dari tgl ' . date('d-m-Y', strtotime($dari)) . ' Sampai tgl ' . date('d-m-Y', strtotime($sampai)));
               if ($laporan == 1) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                         $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                         $y = "
                         SELECT h.moveno, 
                         h.movedate, 
                         d.kodebarang, 
                         (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = d.kodebarang) AS namabarang,
                         d.satuan,
                         d.qtymove,
                         (select hpp from tbl_logbarang where kodebarang = d.kodebarang) as hpp,
                         (d.qtymove*(select hpp from tbl_logbarang where kodebarang = d.kodebarang)) AS totalhpp,
                         h.dari, 
                         h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
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
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         if ($da == 1) {
                              $y = "
                              SELECT
                              a.*
                              FROM (
                                   SELECT 
                                   kodeobat as kodebarang,
                                   (select namabarang from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as namabarang,
                                   (select satuan1 from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as sat_HNA,
                                   ((select hargabeli from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat)*hasilso) as total_HNA
                                   FROM tbl_aposesuailog
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
                                   kodeobat as kodebarang,
                                   (select namabarang from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as namabarang,
                                   (select satuan1 from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as satuan,
                                   hasilso,
                                   (hpp) as sat_HPP,
                                   (hasilso*hpp) as total_HPP,
                                   (select hargabeli from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat) as sat_HNA,
                                   ((select hargabeli from tbl_logbarang where kodebarang = tbl_aposesuailog.kodeobat)*hasilso) as total_HNA
                                   FROM tbl_aposesuailog
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
               } else if ($laporan == 3) {
                    $pdf->addpage("P", "A4");
                    $pdf->setsize("P", "A4");
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(2);
                    foreach ($queryx as $qx) {
                         $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                         if ($da == 1) {
                              $y = "Select h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$qx->gudang'";
                         } else {
                              $y = "Select h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$depo'";
                         }
                         $query = $this->db->query($y)->result();
                         $pdf->setfont('Arial', 'B', 6);
                         $pdf->Cell(30, 6, 'DARI GUDANG : ', 0, 0, 'L');
                         $pdf->Cell(30, 6, $gudang->keterangan, 0, 1, 'L');
                         $pdf->SetFillColor(0, 0, 139);
                         $pdf->settextcolor(0);
                         $pdf->Cell(5, 6, 'No', 1, 0, 'C');
                         $pdf->Cell(35, 6, 'Bukti Tr', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Tanggal', 1, 0, 'C');
                         $pdf->Cell(30, 6, 'Kode Barang', 1, 0, 'C');
                         $pdf->Cell(30, 6, 'Nama Barang', 1, 0, 'C');
                         $pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
                         $pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Hpp', 1, 0, 'C');
                         $pdf->Cell(20, 6, 'Total Hpp', 1, 0, 'C');
                         $no = 1;
                         $pdf->ln();
                         foreach ($query as $q) {
                              $pdf->Cell(5, 6, $no++, 1, 0, 'C');
                              $pdf->Cell(35, 6, $q->pakaino, 1, 0, 'L');
                              $pdf->Cell(20, 6, date('d-m-Y', strtotime($q->pakaidate)), 1, 0, 'L');
                              $pdf->Cell(30, 6, $q->kodebarang, 1, 0, 'L');
                              $pdf->Cell(30, 6, $q->namabarang, 1, 0, 'L');
                              $pdf->Cell(15, 6, $q->satuan, 1, 0, 'L');
                              $pdf->Cell(15, 6, number_format($q->qty, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, number_format($q->hpp, 2), 1, 0, 'R');
                              $pdf->Cell(20, 6, number_format($q->totalhpp, 2), 1, 0, 'R');
                              $pdf->ln();
                         }
                         $pdf->ln();
                    }
               } else if ($laporan == 4) {
                    foreach ($queryx as $qx) {
                         $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$qx->depocode'")->row();
                         if ($depo != '') {
                              $kondisi = " AND a.gudang = '$depo'";
                              $gdx = $gudang->keterangan;
                         } else {
                              $kondisi = "";
                              $gdx = 'SEMUA GUDANG';
                         }
                         $y = "SELECT 
                         a.*,
                         (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS namabarang,

                         (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS satuan,

                         IFNULL((SELECT qty_terima FROM
                              (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                              FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
                              GROUP BY c.koders,c.kodebarang,gudang
                              ORDER BY koders,kodebarang)AS terima 
                              WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                         ),0) AS pembelian,

                         IFNULL((SELECT qtymove FROM 
                              (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                              FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                              GROUP BY e.koders,e.kodebarang,ke
                              ORDER BY koders,kodebarang)AS terima 
                              WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                         ),0) AS mutasi_in,

                         IFNULL((SELECT hasilso FROM
                              (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
                              FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
                              ORDER BY koders, kodeobat
                              ) AS terima
                              WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                         ), 0) AS so,

                         (
                              (IFNULL((SELECT qty_terima FROM
                                   (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                   FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
                                   GROUP BY c.koders,c.kodebarang,gudang
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                              ),0)) +
                              (IFNULL((SELECT qtymove FROM 
                                   (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                   FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                   GROUP BY e.koders,e.kodebarang,ke
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                              ),0)) +
                              (IFNULL((SELECT hasilso FROM
                                   (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
                                   FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
                                   ORDER BY koders, kodeobat
                                   ) AS terima
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                              ), 0))
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
                              FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                              GROUP BY e.koders,e.kodebarang,dari
                              ORDER BY koders,kodebarang)AS terima 
                              WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                         ),0) AS mutasi_out,
                         0 AS bhp,

                         (
                              (IFNULL((SELECT qtyjual FROM 
                                   (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
                                   FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                   JOIN tbl_apoposting ps ON ps.resepno=b.resepno
                                   GROUP BY c.koders,c.kodebarang,b.gudang
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                              ),0)) +
                              (IFNULL((SELECT qtymove FROM 
                                   (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                   FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                   GROUP BY e.koders,e.kodebarang,dari
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                              ),0)) +
                              0
                         ) AS total_keluar,

                         (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS hpp,

                         (
                              (
                                   (
                                        (IFNULL((SELECT qty_terima FROM
                                             (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                             FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
                                             GROUP BY c.koders,c.kodebarang,gudang
                                             ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                             ),0)) +
                                        (IFNULL((SELECT qtymove FROM 
                                             (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                             FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                             GROUP BY e.koders,e.kodebarang,ke
                                             ORDER BY koders,kodebarang)AS terima 
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                                        ),0)) +
                                        (IFNULL((SELECT hasilso FROM
                                             (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
                                             FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
                                             ORDER BY koders, kodeobat
                                             ) AS terima
                                             WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                        ), 0)
                                   )
                              )
                         ) - 
                         (
                              (
                                   (IFNULL((SELECT qtyjual FROM 
                                        (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
                                        FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                        JOIN tbl_apoposting ps ON ps.resepno=b.resepno
                                        GROUP BY c.koders,c.kodebarang,b.gudang
                                        ORDER BY koders,kodebarang)AS terima 
                                        WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                   ),0)) +
                                   (IFNULL((SELECT qtymove FROM 
                                        (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                        FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                        GROUP BY e.koders,e.kodebarang,dari
                                        ORDER BY koders,kodebarang)AS terima 
                                        WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                                   ),0)) +
                                   0
                                   )
                              )
                         ) AS saldo_akhir,

                         (
                              (
                              (
                              (IFNULL((SELECT qty_terima FROM
                                   (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                                   FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
                                   GROUP BY c.koders,c.kodebarang,gudang
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                                   ),0)) +
                              (IFNULL((SELECT qtymove FROM 
                                   (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                                   FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                   GROUP BY e.koders,e.kodebarang,ke
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                              ),0)) +
                              (IFNULL((SELECT hasilso FROM
                                   (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
                                   FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
                                   ORDER BY koders, kodeobat
                                   ) AS terima
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                              ), 0))
                         ) -
                         (
                              (
                              (IFNULL((SELECT qtyjual FROM 
                                   (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
                                   FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                                   JOIN tbl_apoposting ps ON ps.resepno=b.resepno
                                   GROUP BY c.koders,c.kodebarang,b.gudang
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                              ),0)) +
                              (IFNULL((SELECT qtymove FROM 
                                   (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                                   FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                                   GROUP BY e.koders,e.kodebarang,dari
                                   ORDER BY koders,kodebarang)AS terima 
                                   WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
                              ),0)) +
                              0
                              )
                              )
                              *
                              (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang)
                         )
                         ) as total_persediaan_rp

                         FROM (
                              SELECT koders,kodebarang,gudang, tglso, saldoakhir
                              FROM tbl_apostocklog a
                              GROUP BY koders,kodebarang,gudang
                         ) a
                         WHERE a.koders = '$unit' AND a.tglso BETWEEN '$dari' AND '$sampai' $kondisi ORDER BY a.tglso ASC";
                         $query = $this->db->query($y)->result();
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
                                             <img src=\"" . base_url() . "assets/img/logo.png\"  width=\"70\" height=\"70\" />
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
                                        <td width=\"24%\" align=\"center\" colspan=\"4\"><br>Persedaan Masuk</td>
                                        <td width=\"24%\" align=\"center\" colspan=\"4\"><br>Persedaan Keluar</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Akhir</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Hpp Average</td>
                                        <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Total Persediaan</td>
                                   </tr>
                                   <tr>
                                        <td width=\"4%\" align=\"center\"><br>Pembelian</td>
                                        <td width=\"4%\" align=\"center\"><br>Mutasi In</td>
                                        <td width=\"4%\" align=\"center\"><br>So Adjustment</td>
                                        <td width=\"4%\" align=\"center\"><br>Total Masuk</td>
                                        <td width=\"4%\" align=\"center\"><br>Jual</td>
                                        <td width=\"4%\" align=\"center\"><br>Mutasi Out</td>
                                        <td width=\"4%\" align=\"center\"><br>BHP</td>
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
                              $so = number_format($q->so);
                              $total_masuk = number_format($q->total_masuk);
                              $jual = number_format($q->jual);
                              $mutasi_out = number_format($q->mutasi_out);
                              $bhp = number_format($q->bhp);
                              $total_keluar = number_format($q->total_keluar);
                              $saldo_akhir = number_format($q->saldo_akhir);
                              $hpp = number_format($q->hpp);
                              $total_persediaan_rp = number_format($q->total_persediaan_rp);

                              $chari .= "<tr>
                                        <td align=\"left\">" . $no++ . "</td>
                                        <td align=\"left\">$kodebarang</td>
                                        <td align=\"left\">$namabarang</td>
                                        <td align=\"right\">$satuan</td>
                                        <td align=\"right\">$pembelian</td>
                                        <td align=\"right\">$mutasi_in</td>
                                        <td align=\"right\">$so</td>
                                        <td align=\"right\">$total_masuk</td>
                                        <td align=\"right\">$jual</td>
                                        <td align=\"right\">$mutasi_out</td>
                                        <td align=\"right\">$bhp</td>
                                        <td align=\"right\">$total_keluar</td>
                                        <td align=\"right\">$saldo_akhir</td>
                                        <td align=\"right\">$hpp</td>
                                        <td align=\"right\">$total_persediaan_rp</td>
                                   </tr>";
                         }
                         $chari .= "</table>";
                         $data['prev'] = $chari;
                    }
                    $judul = '06 LAPORAN PERSEDIAAN BARANG';
                    echo ("<title>$judul</title>");
                    $this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
               }
               if ($laporan != 4) {
                    $pdf->Output();
               }
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
               if ($laporan == 1) {
                    $judul = '01 Laporan Mutasi Logistik';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.dari, h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai'
                         group by dari, ke
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
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
                    $this->load->view('logistik/Persediaan/v_excel_01.php', $data);
               } else if ($laporan == 2) {
                    $judul = 'Laporan Stock Opname';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
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
                    $this->load->view('logistik/Persediaan/v_excel_02.php', $data);
               } else if ($laporan == 3) {
                    $judul = '03 Laporan Pemakaian Logistik';
                    if ($da == 1) {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang
                         ";
                    } else {
                         $x = "
                         SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
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
                    $this->load->view('logistik/Persediaan/v_excel_03.php', $data);
               } else if ($laporan == 4) {
                    $judul = '04 Laporan Persediaan (Detail)';
                    if ($da == 1) {
                         $x = "SELECT depocode, keterangan FROM tbl_depo";
                    } else {
                         $x = "SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$depo'";
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
                    $this->load->view('logistik/Persediaan/v_excel_04.php', $data);
               }
          }
     }
}
