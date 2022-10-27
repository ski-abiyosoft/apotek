<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($queryx as $qx) : ?>
     <?php $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row(); ?>
     <table class="table table-striped table-bordered table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="30%" align="left" border="0" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th align="center">Dari</th>
                    <th align="center"><?= $gudang->keterangan; ?></th>
               </tr>
          </thead>
     </table>
     <?php
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
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" rowspan="2" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Satuan</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Qty Adjustment</th>
                    <td bgcolor="#cccccc" align="center" colspan="2">HPP</td>
                    <td bgcolor="#cccccc" align="center" colspan="2">HNA</td>
               </tr>
               <tr>
                    <th bgcolor="#cccccc" align="center">Sat</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
                    <th bgcolor="#cccccc" align="center">Sat</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->hasilso, 0); ?></td>
                         <td><?= round($q->sat_HPP, 0); ?></td>
                         <td><?= round($q->total_HPP, 0); ?></td>
                         <td><?= round($q->sat_HNA, 0); ?></td>
                         <td><?= round($q->total_HNA, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>