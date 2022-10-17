<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($suplier as $s) : ?>
     <table>
          <thead>
               <tr>
                    <td>Supplier</td>
                    <td> : </td>
                    <td><?= $s->vendor_name; ?></td>
               </tr>
          </thead>
     </table>
     <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
          <thead>
               <tr>
                    <td bgcolor="#cccccc" align="center" width="2%"><b>No</b></td>
                    <td bgcolor="#cccccc" align="center"><b>Kode Barang</b></td>
                    <td bgcolor="#cccccc" align="center"><b>Nama Barang</b></td>
                    <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
                    <td bgcolor="#cccccc" align="center"><b>QTY</b></td>
                    <td bgcolor="#cccccc" align="center"><b>Harga Rata Rata</b></td>
                    <td bgcolor="#cccccc" align="center"><b>Total</b></td>
               </tr>
          </thead>
          <tbody>
               <?php
               if ($query != '') {
                    $nomor = 1;
                    $total_qty = 0;
                    $total_ratarata = 0;
                    $totalSemua = 0;
                    foreach ($query as $dt) {   ?>
                         <tr>
                              <td align="center"><?= $nomor++ ?></td>
                              <td align="center"><?= $dt->kodebarang ?></td>
                              <td align="center"><?= $dt->namabarang ?></td>
                              <td align="center"><?= $dt->satuan ?></td>
                              <td align="right"><?= number_format($dt->qty_terima, 2) ?></td>
                              <td align="right"><?= number_format($dt->ratarata, 2) ?></td>
                              <td align="right"><?= number_format($dt->qty_terima * $dt->ratarata, 2) ?></td>
                         </tr>

               <?php
                         $total_qty += ($dt->qty_terima);
                         $total_ratarata += ($dt->ratarata);
                         $totalSemua += ($dt->qty_terima * $dt->ratarata);
                    }
               } ?>
               <tr>
                    <td align="center" colspan="4"><b>TOTAL</b></td>
                    <td align="right"><?= number_format($total_qty, 2); ?></td>
                    <td align="right"><?= number_format($total_ratarata, 2); ?></td>
                    <td align="right"><?= number_format($totalSemua, 2); ?></td>
               </tr>
          </tbody>
     </table>
     <br>
     <br>
     <br>
<?php endforeach; ?>