<?php 
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=".$judul.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1"
    cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <td style="border:0" align="center"><br> </td>
        </tr>
        <tr>
            <td bgcolor="#cccccc" align="center"><b>PO NO</b></td>
            <td bgcolor="#cccccc" align="center"><b>Tanggal</b></td>
            <td bgcolor="#cccccc" align="center"><b>Kode Barang </b></td>
            <td bgcolor="#cccccc" align="center"><b>Nama Barang</b></td>
            <td bgcolor="#cccccc" align="center"><b>Qty</b></td>
            <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
            <td bgcolor="#cccccc" align="center"><b>Qty Terima</b></td>
            <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
            <td bgcolor="#cccccc" align="center"><b>Harga Set</b></td>
            <td bgcolor="#cccccc" align="center"><b>Total</b></td>
        </tr>
        <tr>
            <td><b>Suplier: <?=$suplier->vendor_name;?></b></td>

        </tr>
    </thead>
    <tbody>

        <?php
             if($query != ''){
              $nomor = 0;
              $TOTAL_SEMUA = 0;
            //   $TGL = $query->terima_date;
            //   $TANGGAL = date("Y-m-d", strtotime($TGL));
    
             foreach ($query as $dt) {   ?>

        <tr>

            <td align="center"><?=$dt->po_no?></td>
            <td align="center"><?=date("Y-m-d", strtotime($dt->terima_date))?></td>
            <td align="center"><?=$dt->kodebarang?></td>
            <td align="center"><?=$dt->namabarang?></td>
            <td align="center"><?=$dt->qty_terima?></td>
            <td align="center"><?=$dt->satuan?></td>
            <td align="center"><?=$dt->qty_terima?></td>
            <td align="center"><?=$dt->satuan?></td>
            <td align="center"><?=number_format($dt->price,0)?></td>
            <td align="center"><?=number_format($dt->price * $dt->qty_terima,0)?></td>

        </tr>

        <?php 
            $TOTAL_SEMUA += ($dt->price * $dt->qty_terima);
            // $TGL = $dt->terima_date;
            // $TANGGAL = date("Y-m-d", strtotime($TGL));
        } }?>

        <tr>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center">Sub Total :</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><?= number_format($TOTAL_SEMUA,0); ?></td>

        </tr>
    </tbody>
</table>