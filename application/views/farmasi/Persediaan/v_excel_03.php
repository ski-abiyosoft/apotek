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
     <table class="table table-striped table-bordered table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="30%" align="left" border="0" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th align="center">Tanggal</th>
                    <th align="center"><?= date('d-m-Y', strtotime($qx->tglproduksi)); ?></th>
               </tr>
          </thead>
     </table>
     <?php
     $y = "
SELECT d.prdno, 
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
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <td bgcolor="#cccccc" align="center">No TR</td>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Jumlah</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <td bgcolor="#cccccc" align="center">HPP</td>
                    <td bgcolor="#cccccc" align="center">TotalHPP</td>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->prdno; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= number_format($q->qty, 2); ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= number_format($q->hpp, 2); ?></td>
                         <td><?= number_format($q->totalharga, 2); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
     <hr>
<?php endforeach; ?>