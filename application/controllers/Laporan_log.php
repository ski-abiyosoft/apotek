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
          $this->load->model('M_template_cetak');
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
     
     public function cetak(){
          $cek        = $this->session->userdata('level');
          $dari       = $this->input->get('dari');
          $sampai     = $this->input->get('sampai');
          $da         = $this->input->get('da');
          $depo       = $this->input->get('depo');
          $laporan    = $this->input->get('laporan');
          $cabang     = $this->session->userdata('unit');
          $cekpdf     = $this->input->get('pdf');
          $unit       = $cabang;
          $body       = '';
          $date       = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
          $profile    = data_master('tbl_namers', array('koders' => $unit));
          $kota       = $profile->kota;
          $position   = 'L';
          if($laporan == 1){
               $judul = '01 Laporan Mutasi Logistik';
               if ($da == 1) {
                    $x = "SELECT
                         h.dari, h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate >= '$dari' AND h.movedate <= '$sampai'
                         group by dari, ke";
               } else {
                    $x = "SELECT
                         h.dari, 
                         h.ke
                         FROM tbl_apohmovelog h
                         JOIN tbl_apodmovelog d ON h.moveno= d.moveno
                         WHERE h.koders = '$unit' AND h.movedate >= '$dari' AND h.movedate <= '$sampai' and h.dari = '$depo'
                         GROUP BY dari, ke";
               }
               $queryx = $this->db->query($x)->result();
               foreach($queryx as $qx){
                    $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
                    $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row();
                    $y = "SELECT h.moveno, 
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
                         WHERE h.koders = '$unit' AND h.movedate BETWEEN '$dari' AND '$sampai' and h.dari = '$qx->dari' and h.ke = '$qx->ke'";
                    $query = $this->db->query($y)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                    $body .= "<tr>
                                   <td colspan=\"4\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">Dari : $dari_gudang->keterangan</td>
                                   <td colspan=\"5\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">Ke : $ke_gudang->keterangan</td>
                              </tr>";
                    $body .= "<tr>
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
                    $no = 1;
                    foreach($query as $q){
                         if($cekpdf == 1){
                              $qtymove = number_format($q->qtymove);
                              $hpp = number_format($q->hpp);
                              $totalhpp = number_format($q->totalhpp);
                         } else {
                              $qtymove = round($q->qtymove);
                              $hpp = round($q->hpp);
                              $totalhpp = round($q->totalhpp);
                         }
                         $body .= "<tr>
                                   <td style=\"text-align: center;\">". $no++."</td>
                                   <td style=\"text-align: left;\">$q->moveno</td>
                                   <td style=\"text-align: left;\">". date('d-m-Y', strtotime($q->movedate))."</td>
                                   <td style=\"text-align: left;\">$q->kodebarang</td>
                                   <td style=\"text-align: left;\">$q->namabarang</td>
                                   <td style=\"text-align: left;\">$q->satuan</td>
                                   <td style=\"text-align: right;\">".$qtymove."</td>
                                   <td style=\"text-align: right;\">".$hpp."</td>
                                   <td style=\"text-align: right;\">".$totalhpp."</td>
                              </tr>";
                    }
                    $body .= "<tr>
                                   <td colspan=\"9\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">&nbsp;</td>
                              </tr>";
                    $body .= "</table>";
               }
          } else if($laporan == 2){
               $position = 'L';
               $judul = '02 Laporan Stock Opname';
               if ($da == 1) {
                    $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang";
               } else {
                    $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang";
               }
               $queryx = $this->db->query($x)->result();
               foreach($queryx as $qx){
                    $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                    if ($da == 1) {
                         $y = "SELECT
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
                              ) AS a";
                    } else {
                         $y = "SELECT
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
                              ) AS a";
                    }
                    $query = $this->db->query($y)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                    $body .= "<tr>
                                   <td colspan=\"7\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">Dari Gudang : $gudang->keterangan</td>
                              </tr>";
                    $body .= "<tr>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">No</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Kode Barang</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Nama Barang</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Satuan</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" rowspan=\"2\">Qty Adjustment</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" colspan=\"2\">HPP</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" colspan=\"2\">HNA</td>
                              </tr>
                              <tr>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
                                   <td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
                              </tr>";
                    $no = 1;
                    foreach($query as $q){
                         if($cekpdf == 1){
                              $hasilso = number_format($q->hasilso);
                              $sat_hpp = number_format($q->sat_HPP);
                              $total_hpp = number_format($q->total_HPP);
                              $sat_hna = number_format($q->sat_HNA);
                              $total_hna = number_format($q->total_HNA);
                         } else {
                              $hasilso = round($q->hasilso);
                              $sat_hpp = round($q->sat_HPP);
                              $total_hpp = round($q->total_HPP);
                              $sat_hna = round($q->sat_HNA);
                              $total_hna = round($q->total_HNA);
                         }
                         $body .= "<tr>
                                   <td style=\"text-align: center;\">".$no++."</td>
                                   <td style=\"text-align: left;\">$q->kodebarang</td>
                                   <td style=\"text-align: left;\">$q->namabarang</td>
                                   <td style=\"text-align: left;\">$q->satuan</td>
                                   <td style=\"text-align: right;\">".$hasilso."</td>
                                   <td style=\"text-align: right;\">".$sat_hpp."</td>
                                   <td style=\"text-align: right;\">".$total_hpp."</td>
                                   <td style=\"text-align: right;\">".$sat_hna."</td>
                                   <td style=\"text-align: right;\">".$total_hna."</td>
                              </tr>";
                    }
                    $body .= "</table>";
               }
          } else if($laporan == 3){
               $judul = '03 Laporan Pemakaian Logistik';
               if ($da == 1) {
                    $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai'
                         group by gudang";
               } else {
                    $x = "SELECT
                         h.gudang
                         FROM tbl_aposesuailog h
                         WHERE h.koders = '$unit' AND h.tglso BETWEEN '$dari' AND '$sampai' and h.gudang = '$depo'
                         group by gudang";
               }
               $queryx = $this->db->query($x)->result();
               foreach($queryx as $qx){
                    $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row();
                    if ($da == 1) {
                         $y = "SELECT h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$qx->gudang'";
                    } else {
                         $y = "SELECT h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$depo'";
                    }
                    $query = $this->db->query($y)->result();
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                    $body .= "<tr>
                                   <td colspan=\"7\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">Dari Gudang : $gudang->keterangan</td>
                              </tr>";
                    $body .= "<tr>
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
                    $no = 1;
                    foreach($query as $q){
                         if ($cekpdf == 1) {
                              $qty = number_format($q->qty);
                              $hpp = number_format($q->hpp);
                              $totalhpp = number_format($q->totalhpp);
                         } else {
                              $qty = round($q->qty);
                              $hpp = round($q->hpp);
                              $totalhpp = round($q->totalhpp);
                         }
                         $body .= "<tr>
                                   <td style=\"text-align: center;\">" . $no++ . "</td>
                                   <td style=\"text-align: left;\">$q->pakaino</td>
                                   <td style=\"text-align: left;\">" . date('d-m-Y', strtotime($q->pakaidate)) . "</td>
                                   <td style=\"text-align: left;\">$q->kodebarang</td>
                                   <td style=\"text-align: left;\">$q->namabarang</td>
                                   <td style=\"text-align: left;\">$q->satuan</td>
                                   <td style=\"text-align: right;\">" . $qty . "</td>
                                   <td style=\"text-align: right;\">" . $hpp . "</td>
                                   <td style=\"text-align: right;\">" . $totalhpp . "</td>
                              </tr>";
                    }
                    $body .= "<tr>
                                   <td colspan=\"9\" style=\"border-top: none; border-bottom: none; border-left: none; border-right: none;\">&nbsp;</td>
                              </tr>";
                    $body .= "</table>";
               }
          } else if ($laporan == 4) {
               $judul = '04 Laporan Persediaan (Detail)';
               if ($da == 1) {
                    $x = "SELECT depocode, keterangan FROM tbl_depo";
               } else {
                    $x = "SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$depo'";
               }
               $queryx = $this->db->query($x)->result();
               foreach($queryx as $qx){
                    $gudangy = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$qx->depocode'")->row();
                    if ($depo != '') {
                         $gudang = $depo;
                         $gdx = $gudangy->keterangan;
                         $y =
                              "SELECT *, (total_masuk - total_keluar) AS saldo, hpp, 
                                   ((total_masuk - total_keluar) * hpp) AS total 
                              FROM ( 
                                   SELECT p.*, (pembelian + move_in + so + retur_beli) AS total_masuk, 
                                        (jual + mutasi_out + retur_jual) AS total_keluar 
                                   FROM ( 
                                        SELECT a.kodebarang, (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS namabarang, 
                                             (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS satuan, 
                                             (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS hpp, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dt.kodebarang, SUM(dt.qty_terima) AS qty, ht.gudang, ht.koders 
                                                            FROM tbl_apodterimalog dt 
                                                            JOIN tbl_apohterimalog ht ON dt.terima_no = ht.terima_no 
                                                            WHERE ht.gudang = '$gudang' AND ht.koders = '$unit' 
                                                            AND ht.terima_date BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dt.kodebarang 
                                                       ) AS beli 
                                                       WHERE beli.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS pembelian, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.dari, hm.koders 
                                                            FROM tbl_apodmovelog dm 
                                                            JOIN tbl_apohmovelog hm ON dm.moveno = hm.moveno 
                                                            WHERE hm.dari = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.movedate BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS move_i 
                                                       WHERE move_i.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS move_in,  
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT kodeobat AS kodebarang, SUM(hasilso) AS qty, gudang, koders 
                                                            FROM tbl_aposesuailog 
                                                            WHERE gudang = '$gudang' AND koders = '$unit' 
                                                            AND tglso BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY kodeobat 
                                                       ) AS so_ 
                                                       WHERE so_.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS so, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dr.kodebarang, SUM(dr.qty_retur) AS qty, hr.gudang, hr.koders 
                                                            FROM tbl_apodreturbelilog dr 
                                                            JOIN tbl_apohreturbelilog hr ON dr.retur_no = hr.retur_no 
                                                            WHERE hr.gudang = '$gudang' AND hr.koders = '$unit' 
                                                            AND hr.retur_date BETWEEN  '$dari' AND '$sampai' GROUP BY dr.kodebarang 
                                                       ) AS ret 
                                                       WHERE ret.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS retur_beli, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT SUM(qty) AS qty FROM ( 
                                                            SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                                                            FROM tbl_apodresep d 
                                                            JOIN tbl_apohresep h ON d.resepno = h.resepno 
                                                            WHERE h.gudang = '$gudang' AND h.koders = '$unit' 
                                                            AND h.tglresep BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY d.kodebarang 
                                                            
                                                            UNION ALL 
                                                            
                                                            SELECT d.kodebarang, SUM(d.qtyr) AS qty, h.gudang, h.koders 
                                                            FROM tbl_apodetresep d 
                                                            JOIN tbl_apohresep h ON d.resepno = h.resepno 
                                                            WHERE h.gudang = '$gudang' AND h.koders = '$unit' 
                                                            AND h.tglresep BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY d.kodebarang 
                                                       ) xx 
                                                       WHERE xx.kodebarang=a.kodebarang 
                                                       GROUP BY xx.kodebarang 
                                                  ) ,
                                             0) AS jual, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.ke, hm.koders 
                                                            FROM tbl_apodmovelog dm 
                                                            JOIN tbl_apohmovelog hm ON dm.moveno = hm.moveno 
                                                            WHERE hm.ke = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.movedate BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS move_o 
                                                       WHERE move_o.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS mutasi_out, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtyretur) AS qty, hm.gudang, hm.koders 
                                                            FROM tbl_apodreturjual dm 
                                                            JOIN tbl_apohreturjual hm ON dm.returno = hm.returno 
                                                            WHERE hm.gudang = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.tglretur BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS retur_j 
                                                       WHERE retur_j.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS retur_jual
                                             FROM tbl_apostocklog a 
                                             WHERE a.gudang = '$gudang' 
                                             AND a.koders = '$unit' 
                                        ) p 
                                   ) z
                         ";
                    } else {
                         $gudang = $qx->depocode;
                         $gdx = '-';
                         $y =
                              "SELECT *, (total_masuk - total_keluar) AS saldo, hpp, 
                                   ((total_masuk - total_keluar) * hpp) AS total 
                              FROM ( 
                                   SELECT p.*, (pembelian + move_in + so + retur_beli) AS total_masuk, 
                                        (jual + mutasi_out + retur_jual) AS total_keluar 
                                   FROM ( 
                                        SELECT a.kodebarang, (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS namabarang, 
                                             (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS satuan, 
                                             (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS hpp, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dt.kodebarang, SUM(dt.qty_terima) AS qty, ht.gudang, ht.koders 
                                                            FROM tbl_apodterimalog dt 
                                                            JOIN tbl_apohterimalog ht ON dt.terima_no = ht.terima_no 
                                                            WHERE ht.gudang = '$gudang' AND ht.koders = '$unit' 
                                                            AND ht.terima_date BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dt.kodebarang 
                                                       ) AS beli 
                                                       WHERE beli.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS pembelian, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.dari, hm.koders 
                                                            FROM tbl_apodmovelog dm 
                                                            JOIN tbl_apohmovelog hm ON dm.moveno = hm.moveno 
                                                            WHERE hm.dari = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.movedate BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS move_i 
                                                       WHERE move_i.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS move_in,  
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT kodeobat AS kodebarang, SUM(hasilso) AS qty, gudang, koders 
                                                            FROM tbl_aposesuailog 
                                                            WHERE gudang = '$gudang' AND koders = '$unit' 
                                                            AND tglso BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY kodeobat 
                                                       ) AS so_ 
                                                       WHERE so_.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS so, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dr.kodebarang, SUM(dr.qty_retur) AS qty, hr.gudang, hr.koders 
                                                            FROM tbl_apodreturbelilog dr 
                                                            JOIN tbl_apohreturbelilog hr ON dr.retur_no = hr.retur_no 
                                                            WHERE hr.gudang = '$gudang' AND hr.koders = '$unit' 
                                                            AND hr.retur_date BETWEEN  '$dari' AND '$sampai' GROUP BY dr.kodebarang 
                                                       ) AS ret 
                                                       WHERE ret.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS retur_beli, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT SUM(qty) AS qty FROM ( 
                                                            SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                                                            FROM tbl_apodresep d 
                                                            JOIN tbl_apohresep h ON d.resepno = h.resepno 
                                                            WHERE h.gudang = '$gudang' AND h.koders = '$unit' 
                                                            AND h.tglresep BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY d.kodebarang 
                                                            
                                                            UNION ALL 
                                                            
                                                            SELECT d.kodebarang, SUM(d.qtyr) AS qty, h.gudang, h.koders 
                                                            FROM tbl_apodetresep d 
                                                            JOIN tbl_apohresep h ON d.resepno = h.resepno 
                                                            WHERE h.gudang = '$gudang' AND h.koders = '$unit' 
                                                            AND h.tglresep BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY d.kodebarang 
                                                       ) xx 
                                                       WHERE xx.kodebarang=a.kodebarang 
                                                       GROUP BY xx.kodebarang 
                                                  ) ,
                                             0) AS jual, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.ke, hm.koders 
                                                            FROM tbl_apodmovelog dm 
                                                            JOIN tbl_apohmovelog hm ON dm.moveno = hm.moveno 
                                                            WHERE hm.ke = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.movedate BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS move_o 
                                                       WHERE move_o.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS mutasi_out, 
                                             IFNULL( 
                                                  ( 
                                                       SELECT qty FROM ( 
                                                            SELECT dm.kodebarang, SUM(dm.qtyretur) AS qty, hm.gudang, hm.koders 
                                                            FROM tbl_apodreturjual dm 
                                                            JOIN tbl_apohreturjual hm ON dm.returno = hm.returno 
                                                            WHERE hm.gudang = '$gudang' AND hm.koders = '$unit' 
                                                            AND hm.tglretur BETWEEN  '$dari' AND '$sampai' 
                                                            GROUP BY dm.kodebarang 
                                                       ) AS retur_j 
                                                       WHERE retur_j.kodebarang=a.kodebarang 
                                                  ) ,
                                             0) AS retur_jual
                                             FROM tbl_apostocklog a 
                                             WHERE a.gudang = '$gudang' 
                                             AND a.koders = '$unit' 
                                        ) p 
                                   ) z
                         ";
                    }
                    $query = $this->db->query($y)->result();

                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
                    $body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
                                   <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
                                   <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . $gdx . "</td>
                                   <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Tanggal</td>
                                   <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
                                   <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . date('d-m-Y', strtotime($dari)) . ' / ' . date('d-m-Y', strtotime($sampai)) . "</td>
                              </tr>
                         </table>";
                    $body .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td style=\"border:0\" align=\"center\"><br></td>
                              </tr>
                              <tr>
                                   <td width=\"3%\" align=\"center\" rowspan=\"2\"><br>No</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
                                   <td width=\"24%\" align=\"center\" colspan=\"5\"><br>Persedaan Masuk</td>
                                   <td width=\"24%\" align=\"center\" colspan=\"5\"><br>Persedaan Keluar</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Akhir</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Hpp Average</td>
                                   <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Total Persediaan</td>
                              </tr>
                              <tr>
                                   <td width=\"4%\" align=\"center\"><br>Pembelian</td>
                                   <td width=\"4%\" align=\"center\"><br>Retur Pembelian</td>
                                   <td width=\"4%\" align=\"center\"><br>Mutasi In</td>
                                   <td width=\"4%\" align=\"center\"><br>So Adjustment</td>
                                   <td width=\"4%\" align=\"center\"><br>Total Masuk</td>
                                   <td width=\"4%\" align=\"center\"><br>Jual</td>
                                   <td width=\"4%\" align=\"center\"><br>Retur Jual</td>
                                   <td width=\"4%\" align=\"center\"><br>Mutasi Out</td>
                                   <td width=\"4%\" align=\"center\"><br>BHP</td>
                                   <td width=\"4%\" align=\"center\"><br>Total Keluar</td>
                              </tr>";
                    $no = 1;
                    foreach ($query as $q) {
                         $kodebarang = $q->kodebarang;
                         $namabarang = $q->namabarang;
                         $satuan = $q->satuan;
                         if($cekpdf == 1){
                              $pembelian = number_format($q->pembelian);
                              $pembelianr = number_format($q->retur_beli);
                              $mutasi_in = number_format($q->move_in);
                              $so = number_format($q->so);
                              $total_masuk = number_format($q->total_masuk);
                              $jual = number_format($q->jual);
                              $jualr = number_format($q->retur_jual);
                              $mutasi_out = number_format($q->mutasi_out);
                              $bhp = number_format(0);
                              $total_keluar = number_format($q->total_keluar + $bhp);
                              $saldo_akhir = number_format($q->saldo);
                              $hpp = number_format($q->hpp);
                              $total_persediaan_rp = number_format($q->total);
                         } else {
                              $pembelian = round($q->pembelian);
                              $pembelianr = round($q->retur_beli);
                              $mutasi_in = round($q->move_in);
                              $so = round($q->so);
                              $total_masuk = round($q->total_masuk);
                              $jual = round($q->jual);
                              $jualr = round($q->retur_jual);
                              $mutasi_out = round($q->mutasi_out);
                              $bhp = 0;
                              $total_keluar = round($q->total_keluar + $bhp);
                              $saldo_akhir = round($q->saldo);
                              $hpp = round($q->hpp);
                              $total_persediaan_rp = round($q->total);
                         }
                         $body .= "<tr>
                                        <td align=\"left\">" . $no++ . "</td>
                                        <td align=\"left\">$kodebarang</td>
                                        <td align=\"left\">$namabarang</td>
                                        <td align=\"right\">$satuan</td>
                                        <td align=\"right\">$pembelian</td>
                                        <td align=\"right\">$pembelianr</td>
                                        <td align=\"right\">$mutasi_in</td>
                                        <td align=\"right\">$so</td>
                                        <td align=\"right\">$total_masuk</td>
                                        <td align=\"right\">$jual</td>
                                        <td align=\"right\">$jualr</td>
                                        <td align=\"right\">$mutasi_out</td>
                                        <td align=\"right\">$bhp</td>
                                        <td align=\"right\">$total_keluar</td>
                                        <td align=\"right\">$saldo_akhir</td>
                                        <td align=\"right\">$hpp</td>
                                        <td align=\"right\">$total_persediaan_rp</td>
                                   </tr>";
                    }
                    $body .= "</table>";
               }
          }
          $this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
     }

     public function laporan4() {
          $cek           = $this->session->userdata('level');
          $dari          = $this->input->get('dari');
          $sampai        = $this->input->get('sampai');
          // $da            = $this->input->get('da');
          $gudang        = $this->input->get('depo');
          $laporan       = $this->input->get('laporan');
          $cabang        = $this->session->userdata('unit');
          $cekpdf        = $this->input->get('pdf');
          $unit          = $cabang;
          $chari         = '';
          $date          = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
          $kop           = $this->M_cetak->kop($unit);
          $namars        = $kop['namars'];
          $alamat        = $kop['alamat'];
          $alamat2       = $kop['alamat2'];
          $kota          = $kop['kota'];
          $phone         = $kop['phone'];
          $whatsapp      = $kop['whatsapp'];
          $npwp          = $kop['npwp'];
          $chari         = '';
          $position      = 'L';
          // $date          = date("Y-m-d");
          $judul         = '04 Laporan Persediaan Barang';
          $data1    = $this->db->query("SELECT * FROM tbl_apostocklog WHERE koders = '$unit' AND gudang = '$gudang' AND kodebarang IN (SELECT kodebarang FROM tbl_logbarang)")->result();
          $data_gudang   = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();
          $chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
               <tr>
                    <td width=\"8%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
                    <td width=\"2%\" style=\"text-align:center;border-bottom: none;\">:</td>
                    <td width=\"40%\" style=\"text-align:left;border-bottom: none;\">" . $data_gudang->keterangan . "</td>
                    <td width=\"15%\" style=\"text-align:left;border-bottom: none;\">&nbsp;</td>
                    <td width=\"5%\" style=\"text-align:center;border-bottom: none;\">&nbsp;</td>
                    <td width=\"30%\" style=\"text-align:left;border-bottom: none;\">&nbsp;</td>
               </tr>
          </table>";
          $chari .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
               <tr>
                    <td style=\"border:0\" align=\"center\"><br></td>
               </tr>
               <tr>
                    <td width=\"3%\" align=\"center\" rowspan=\"2\"><br>No</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Awal</td>
                    <td width=\"24%\" align=\"center\" colspan=\"4\"><br>Persediaan Masuk</td>
                    <td width=\"24%\" align=\"center\" colspan=\"5\"><br>Persediaan Keluar</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>SO</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Saldo Akhir</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Hpp Average</td>
                    <td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Total Persediaan</td>
               </tr>
               <tr>
                    <td width=\"4%\" align=\"center\"><br>Pembelian</td>
                    <td width=\"4%\" align=\"center\"><br>Mutasi In</td>
                    <td width=\"4%\" align=\"center\"><br>Retur Jual</td>
                    <td width=\"4%\" align=\"center\"><br>Total Masuk</td>
                    <td width=\"4%\" align=\"center\"><br>Jual</td>
                    <td width=\"4%\" align=\"center\"><br>Mutasi Out</td>
                    <td width=\"4%\" align=\"center\"><br>Retur Beli</td>
                    <td width=\"4%\" align=\"center\"><br>Pakai Log</td>
                    <td width=\"4%\" align=\"center\"><br>Total Keluar</td>
               </tr>";
          $no = 1;
          foreach($data1 as $d1) {
               // JEDA
               $beli1 = $this->db->query('SELECT IF(sum(d.qty_terima) > 0, sum(d.qty_terima), 0) as qty FROM tbl_apohterimalog h JOIN tbl_apodterimalog d ON h.terima_no = d.terima_no WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.terima_date < "'.$dari.'"')->row();
               $mutasi_in1 = $this->db->query('SELECT IF(sum(d.qtymove) > 0, sum(d.qtymove), 0) as qty FROM tbl_apohmovelog h JOIN tbl_apodmovelog d ON d.moveno = h.moveno WHERE h.koders = "'.$d1->koders.'" AND h.dari = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.movedate < "'.$dari.'"')->row();
               $retur_jual1 = $this->db->query('SELECT IF(sum(d.qtyretur) > 0, sum(d.qtyretur), 0) as qty FROM tbl_apohreturjual h JOIN tbl_apodreturjual d ON d.returno = h.returno WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.tglretur < "'.$dari.'"')->row();
               $jual1 = $this->db->query('SELECT IF(qty > 0, qty, 0) as qty FROM (
                         SELECT SUM(qty) AS qty, kodebarang, gudang, koders
                         FROM (
                              SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                              FROM tbl_apodresep d
                              JOIN tbl_apohresep h ON d.resepno = h.resepno
                              WHERE h.gudang = "'.$d1->gudang.'" AND h.koders = "'.$d1->koders.'"
                              AND h.tglresep < "'.$dari.'"
                              AND d.kodebarang = "'.$d1->kodebarang.'"
                              
                              UNION ALL 
                              
                              SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                              FROM tbl_apodetresep d
                              JOIN tbl_apohresep h ON d.resepno = h.resepno
                              WHERE h.gudang = "'.$d1->gudang.'" AND h.koders = "'.$d1->koders.'"
                              AND h.tglresep < "'.$dari.'"
                              AND d.kodebarang = "'.$d1->kodebarang.'"
                         ) AS j
               ) beli')->row();
               $mutasi_out1 = $this->db->query('SELECT IF(sum(d.qtymove) > 0, sum(d.qtymove), 0) as qty FROM tbl_apohmovelog h JOIN tbl_apodmovelog d ON d.moveno = h.moveno WHERE h.koders = "'.$d1->koders.'" AND h.ke = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.movedate < "'.$dari.'"')->row();
               $retur_beli1 = $this->db->query('SELECT IF(sum(d.qty_retur) > 0, sum(d.qty_retur), 0) as qty FROM tbl_apohreturbelilog h JOIN tbl_apodreturbelilog d ON d.retur_no = h.retur_no WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.retur_date < "'.$dari.'"')->row();
               $pakai_log1 = $this->db->query('SELECT IF(SUM(d.qty) > 0, SUM(d.qty), 0) as qty FROM tbl_pakaihlog h JOIN tbl_pakaidlog d ON h.pakaino = d.pakaino WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.pakaidate < "'.$dari.'"')->row();

               $total_masuk1 = ($beli1->qty + $mutasi_in1->qty + $retur_jual1->qty);
               $total_keluar1 = ($jual1->qty + $mutasi_out1->qty + $retur_beli1->qty + $pakai_log1->qty);
               $total1 = $total_masuk1 - $total_keluar1;

               // SALDO SAAT INI
               $barang = $this->db->query('SELECT * FROM tbl_logbarang WHERE kodebarang = "'.$d1->kodebarang.'"')->row();
               $beli = $this->db->query('SELECT IF(sum(d.qty_terima) > 0, sum(d.qty_terima), 0) as qty FROM tbl_apohterimalog h JOIN tbl_apodterimalog d ON h.terima_no = d.terima_no WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.terima_date >= "'.$dari.'" AND h.terima_date <= "'.$sampai.'"')->row();
               $mutasi_in = $this->db->query('SELECT IF(sum(d.qtymove) > 0, sum(d.qtymove), 0) as qty FROM tbl_apohmovelog h JOIN tbl_apodmovelog d ON d.moveno = h.moveno WHERE h.koders = "'.$d1->koders.'" AND h.dari = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.movedate >= "'.$dari.'" AND h.movedate <= "'.$sampai.'"')->row();
               $retur_jual = $this->db->query('SELECT IF(sum(d.qtyretur) > 0, sum(d.qtyretur), 0) as qty FROM tbl_apohreturjual h JOIN tbl_apodreturjual d ON d.returno = h.returno WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.tglretur >= "'.$dari.'" AND h.tglretur <= "'.$sampai.'"')->row();
               $jual = $this->db->query('SELECT IF(qty > 0, qty, 0) as qty FROM (
                         SELECT SUM(qty) AS qty, kodebarang, gudang, koders
                         FROM (
                              SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                              FROM tbl_apodresep d
                              JOIN tbl_apohresep h ON d.resepno = h.resepno
                              WHERE h.gudang = "'.$d1->gudang.'" AND h.koders = "'.$d1->koders.'"
                              AND h.tglresep >= "'.$dari.'" AND h.tglresep <= "'.$sampai.'"
                              AND d.kodebarang = "'.$d1->kodebarang.'"
                              
                              UNION ALL 
                              
                              SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
                              FROM tbl_apodetresep d
                              JOIN tbl_apohresep h ON d.resepno = h.resepno
                              WHERE h.gudang = "'.$d1->gudang.'" AND h.koders = "'.$d1->koders.'"
                              AND h.tglresep >= "'.$dari.'" AND h.tglresep <= "'.$sampai.'"
                              AND d.kodebarang = "'.$d1->kodebarang.'"
                         ) AS j
               ) beli')->row();
               $mutasi_out    = $this->db->query('SELECT IF(sum(d.qtymove) > 0, sum(d.qtymove), 0) as qty FROM tbl_apohmovelog h JOIN tbl_apodmovelog d ON d.moveno = h.moveno WHERE h.koders = "'.$d1->koders.'" AND h.ke = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.movedate >= "'.$dari.'" AND h.movedate <= "'.$sampai.'"')->row();
               $retur_beli    = $this->db->query('SELECT IF(sum(d.qty_retur) > 0, sum(d.qty_retur), 0) as qty FROM tbl_apohreturbelilog h JOIN tbl_apodreturbelilog d ON d.retur_no = h.retur_no WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.retur_date >= "'.$dari.'" AND h.retur_date <= "'.$sampai.'"')->row();
               $pakai_log     = $this->db->query('SELECT IF(SUM(d.qty) > 0, SUM(d.qty), 0) as qty FROM tbl_pakaihlog h JOIN tbl_pakaidlog d ON h.pakaino = d.pakaino WHERE h.koders = "'.$d1->koders.'" AND h.gudang = "'.$d1->gudang.'" AND d.kodebarang = "'.$d1->kodebarang.'" AND h.pakaidate >= "'.$dari.'" AND h.pakaidate <= "'.$sampai.'"')->row();

               $total_masuk   = ($beli->qty + $mutasi_in->qty + $retur_jual->qty);
               $total_keluar  = ($jual->qty + $mutasi_out->qty + $retur_beli->qty + $pakai_log->qty);
               $total         = $total_masuk - $total_keluar;

               if($cekpdf == 1){
                    $total1_        = number_format($total1);
                    $beli_          = number_format($beli->qty);
                    $mutasi_in_     = number_format($mutasi_in->qty);
                    $retur_jual_    = number_format($retur_jual->qty);
                    $total_masuk_   = number_format($total_masuk);
                    $jual_          = number_format($jual->qty);
                    $mutasi_out_    = number_format($mutasi_out->qty);
                    $retur_beli_    = number_format($retur_beli->qty);
                    $pakai_log_     = number_format($pakai_log->qty);
                    $total_keluar_  = ceil($total_keluar);
                    $so_            = number_format($d1->saldoakhir-($total+$total1));
                    $saldoakhir_    = number_format($d1->saldoakhir);
                    if($barang) {
                         $barang_hpp_   = number_format($barang->hpp);
                         $total_hpp_    = number_format($barang->hpp * $d1->saldoakhir);
                    } else {
                         $barang_hpp_   = number_format(0);
                         $total_hpp_    = number_format(0);
                    }
               } else {
                    $total1_        = ceil($total1);
                    $beli_          = ceil($beli->qty);
                    $mutasi_in_     = ceil($mutasi_in->qty);
                    $retur_jual_    = ceil($retur_jual->qty);
                    $total_masuk_   = ceil($total_masuk);
                    $jual_          = ceil($jual->qty);
                    $mutasi_out_    = ceil($mutasi_out->qty);
                    $retur_beli_    = ceil($retur_beli->qty);
                    $pakai_log_     = ceil($pakai_log->qty);
                    $total_keluar_  = ceil($total_keluar);
                    $so_            = ceil($d1->saldoakhir-($total+$total1));
                    $saldoakhir_    = ceil($d1->saldoakhir);
                    if($barang) {
                         $barang_hpp_   = ceil($barang->hpp);
                         $total_hpp_    = ceil($barang->hpp * $d1->saldoakhir);
                    } else {
                         $barang_hpp_   = ceil(0);
                         $total_hpp_    = ceil(0);
                    }
               }

               if($barang){
                    $namabarang = $barang->namabarang;
                    $satuan1 = $barang->satuan1;
               } else {
                    $namabarang = "TIDAK ADA BARANG";
                    $satuan1 = "TIDAK ADA";
               }

               $chari .= "<tr>
                    <td>$no</td>
                    <td>$d1->kodebarang</td>
                    <td>$namabarang</td>
                    <td>$satuan1</td>
                    <td style=\"text-align: right;\">$total1_</td>
                    <td style=\"text-align: right;\">$beli_</td>
                    <td style=\"text-align: right;\">$mutasi_in_</td>
                    <td style=\"text-align: right;\">$retur_jual_</td>
                    <td style=\"text-align: right;\">$total_masuk_</td>
                    <td style=\"text-align: right;\">$jual_</td>
                    <td style=\"text-align: right;\">$mutasi_out_</td>
                    <td style=\"text-align: right;\">$retur_beli_</td>
                    <td style=\"text-align: right;\">$pakai_log_</td>
                    <td style=\"text-align: right;\">$total_keluar_</td>
                    <td style=\"text-align: right;\">$so_</td>
                    <td style=\"text-align: right;\">$saldoakhir_</td>
                    <td style=\"text-align: right;\">$barang_hpp_</td>
                    <td style=\"text-align: right;\">$total_hpp_</td>
               </tr>";
               $no++;
          }
          $chari .= "</table>";
          $this->M_template_cetak->template($judul, $chari, $position, $date, $cekpdf);
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
