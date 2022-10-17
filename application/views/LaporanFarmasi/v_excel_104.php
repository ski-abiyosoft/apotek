<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
     <thead>
          <tr>
               <td bgcolor="#cccccc" align="center" width="2%"><b>No</b></td>
               <td bgcolor="#cccccc" align="center"><b>Suplier</b></td>
               <td bgcolor="#cccccc" align="center"><b>Qty</b></td>
               <td bgcolor="#cccccc" align="center"><b>Total </b></td>
               <td bgcolor="#cccccc" align="center"><b>Discount</b></td>
               <td bgcolor="#cccccc" align="center"><b>Vat Rp</b></td>
               <td bgcolor="#cccccc" align="center"><b>Materai</b></td>
               <td bgcolor="#cccccc" align="center"><b>Total Net</b></td>
          </tr>
     </thead>
     <tbody>
          <?php
          if ($query != '') {
               $nomor = 1;
               $QTY = 0;
               $DISKON = 0;
               $VAT = 0;
               $MATERAI = 0;
               $TOTAL = 0;
               foreach ($query as $dt) {   ?>

                    <tr>
                         <td align="center"><?= $nomor++ ?></td>
                         <td align="center"><?= $dt->vendor_name ?></td>
                         <td align="right"><?= $dt->qty_terima ?></td>
                         <td align="right"><?= $dt->totalrp ?></td>
                         <td align="right"><?= $dt->discountrp ?></td>
                         <td align="right"><?= $dt->vatrp ?></td>
                         <td align="right"><?= $dt->materai ?></td>
                         <td align="right"><?= $dt->totalrp ?></td>
                    </tr>

          <?php
                    $QTY += ($dt->qty_terima);
                    $DISKON += ($dt->discountrp);
                    $VAT += ($dt->vatrp);
                    $MATERAI += ($dt->materai);
                    $TOTAL += ($dt->totalrp);
               }
          } ?>
          <tr>
               <td></td>
               <td></td>
               <td align="right"><?= $QTY ?></td>
               <td align="right"><?= round($TOTAL, 0) ?></td>
               <td align="right"><?= round($DISKON, 0) ?></td>
               <td align="right"><?= round($VAT, 0) ?></td>
               <td align="right"><?= round($MATERAI, 0) ?></td>
               <td align="right"><?= round($TOTAL, 0) ?></td>

          </tr>
     </tbody>
</table>
<?php
$tgl = date("Y-m-d");

?>
<h3 align="center">HOSPITAL MANAGEMENT SIMTEM</h3>
<h5 align="center">Tanggal Cetak: <?= $tgl ?></h5>

<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">

     <td><b>Diketahui Oleh,</b></td>
     <td><b>Diserahkan Oleh,</b></td>
     <td><b>Dibuat Oleh</b></td>
     <tr>
          <td><br><br><br><br><br><br></td>
          <td><br><br><br><br><br><br></td>
          <td><br><br><br><br><br><br></td>
     </tr>
     <tr>
          <td><b>Diketahui Oleh,</b></td>
          <td></td>
          <td><b>Haryanto</b></td>
     </tr>
</table>