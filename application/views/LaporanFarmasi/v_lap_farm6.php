<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN SATUS ORDER PEMBELIAN</title>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0" width="80%">
        <thead>
            <tr>
                <td width="10%">
                    <img src="<?=base_url('/assets/img/logo.png')?>" width="100" height="100">
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td style="font-size:14px;border-bottom: none;"><b><?=$kop['namars'];?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b><?=$kop['alamat']?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>WA:<?=$kop['whatsapp'];?> Telp :<?=$kop['phone'];?></b> </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>No. NPWP <?= $kop['npwp']?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td colspan="2" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b><br></b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b>06 LAPORAN SATUS ORDER PEMBELIAN</b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center;font-size:15px;border-top: none;"><?=$_peri?></td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="1" cellspacing="0" cellpadding="0">
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
             if($data != ''){
              $nomor = 0;
              $TOTAL_SEMUA = 0;
            //   $TGL = $data->terima_date;
            //   $TANGGAL = date("Y-m-d", strtotime($TGL));
    
             foreach ($data as $dt) {   ?>

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
            // $TOTAL_SEMUA += ($dt->price * $dt->qty_terima);
            // $TGL = $dt->terima_date;
            // $TANGGAL = date("Y-m-d", strtotime($TGL));
        } }?>

            <!-- <tr>
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

            </tr> -->
        </tbody>
    </table>
</body>

</html>