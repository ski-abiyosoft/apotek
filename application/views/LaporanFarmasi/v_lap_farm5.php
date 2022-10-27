<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAP PENERIMAAN BARANG PER ITEM</title>
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
                    <b>05 REKAP PENERIMAAN BARANG PER ITEM</b>
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
                <td bgcolor="#cccccc" align="center"><b>No</b></td>
                <td bgcolor="#cccccc" align="center"><b>Kode Barang</b></td>
                <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
                <td bgcolor="#cccccc" align="center"><b>Qty </b></td>
                <td bgcolor="#cccccc" align="center"><b>Harga Rata Rata</b></td>
                <td bgcolor="#cccccc" align="center"><b>Total</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
             if($data != ''){
             $nomor = 1;
             $QTY =0;
             $rataRata = 0;
             $totalSemua = 0;
             foreach ($data as $dt) {   ?>

            <tr>
                <td align="center"><?= $nomor++ ?></td>
                <td align="center"><?= $dt->kodebarang?></td>
                <td align="center"><?= $dt->satuan?></td>
                <td align="center"><?= $dt->qty_terima?></td>
                <td align="center"><?=number_format($dt->ratarata,0)?></td>
                <td align="center"><?=number_format($dt->qty_terima * $dt->ratarata,0)?></td>

            </tr>

            <?php 
            $QTY += ($dt->qty_terima);
            $rataRata += ($dt->ratarata);
            $totalSemua += ($dt->qty_terima * $dt->ratarata);
        } }?>
            <tr>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"><?=$QTY?></td>
                <td align="center"><?=number_format($rataRata,0)?></td>
                <td align="center"><?=number_format($totalSemua,0)?></td>
            </tr>
        </tbody>
    </table>
</body>

</html>