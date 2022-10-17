<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<br>
<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
     <thead>
          <tr>
               <td bgcolor="#cccccc" align="center"><b>PO NO</b></td>
               <td bgcolor="#cccccc" align="center"><b>Tanggal</b></td>
               <td bgcolor="#cccccc" align="center"><b>Kode Barang </b></td>
               <td bgcolor="#cccccc" align="center"><b>Nama Barang</b></td>
               <td bgcolor="#cccccc" align="center"><b>Qty</b></td>
               <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
               <td bgcolor="#cccccc" align="center"><b>Harga Set</b></td>
               <td bgcolor="#cccccc" align="center"><b>Total</b></td>
          </tr>
     </thead>
     <tbody>
          <?php
          if ($query != '') {
               $nomor = 0;
               $TOTAL_SEMUA = 0;
               foreach ($query as $dt) {   ?>
                    <tr>
                         <td align="center"><?= $dt->po_no ?></td>
                         <td align="center"><?= date("Y-m-d", strtotime($dt->terima_date)) ?></td>
                         <td align="center"><?= $dt->kodebarang ?></td>
                         <td align="center"><?= $dt->namabarang ?></td>
                         <td align="right"><?= round($dt->qty_terima, 0) ?></td>
                         <td align="center"><?= $dt->satuan ?></td>
                         <td align="right"><?= round($dt->price, 0) ?></td>
                         <td align="right"><?= round($dt->price * $dt->qty_terima, 0) ?></td>
                    </tr>
          <?php
                    $TOTAL_SEMUA += ($dt->price * $dt->qty_terima);
               }
          }
          ?>
          <!-- <tr>
               <td align="center" colspan="7">Sub Total :</td>
               <td align="right"><?= round($TOTAL_SEMUA, 0); ?></td>
          </tr> -->
     </tbody>
</table>