<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($queryx as $qx) : ?>
     <?php $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row(); ?>
     <table border="0" width="100%" class="mt-5">
          <tr>
               <td align="center"><b>DEPO / GUDANG <?= $gudang->keterangan; ?></b></td>
          </tr>
     </table>
     <?php
     if ($da == 1) {
          $y = "Select h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$qx->gudang'";
     } else {
          $y = "Select h.pakaino, h.pakaidate, d.kodebarang, (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, d.satuan, d.qty, (select hpp from tbl_logbarang where kodebarang=d.kodebarang) as hpp, (d.qty * (SELECT hpp FROM tbl_logbarang WHERE kodebarang=d.kodebarang)) as totalhpp from tbl_pakaihlog h inner join tbl_pakaidlog d on h.pakaino=d.pakaino where h.koders = '$unit' and pakaidate between '$dari' and '$sampai' and h.gudang = '$depo'";
     }
     $query = $this->db->query($y)->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <td bgcolor="#cccccc" align="center">Bukti TR</td>
                    <td bgcolor="#cccccc" align="center">Tanggal</td>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <td bgcolor="#cccccc" align="center">HPP</td>
                    <td bgcolor="#cccccc" align="center">Total HPP</td>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->pakaino; ?></td>
                         <td><?= date('d-m-Y', strtotime($q->pakaidate)); ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->qty, 0); ?></td>
                         <td><?= round($q->hpp, 0); ?></td>
                         <td><?= round($q->totalhpp, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
     <hr>
<?php endforeach; ?>