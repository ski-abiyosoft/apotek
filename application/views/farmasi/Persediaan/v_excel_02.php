<?php 
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=".$judul.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<?php foreach($queryx as $qx) : ?>
     <?php 
     $dari_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->dari'")->row();
     $ke_gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->ke'")->row(); ?>
     <table class="table table-striped table-bordered table-hover"style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="30%" align="left" border="0" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th align="center">Dari</th>
                    <th align="center"><?= $dari_gudang->keterangan; ?></th>
                    <th align="center">Ke</th>
                    <th align="center"><?= $ke_gudang->keterangan; ?></th>
               </tr>
          </thead>
     </table>
     <?php 
     $y = "
     SELECT
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
     ?>
     <table class="table table-striped table-borderd table-hover"style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">HPP</th>
                    <th bgcolor="#cccccc" align="center">Total HPP</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1; foreach($query as $q) : ?>
               <tr>
                    <td align="right"><?= $no++; ?></td>
                    <td><?= $q->kodebarang; ?></td>
                    <td><?= $q->namabarang; ?></td>
                    <td><?= $q->satuan; ?></td>
                    <td align="right"><?= number_format($q->qtymove, 2); ?></td>
                    <td align="right"><?= number_format($q->hpp, 2); ?></td>
                    <td align="right"><?= number_format($q->totalhpp, 2); ?></td>
               </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>