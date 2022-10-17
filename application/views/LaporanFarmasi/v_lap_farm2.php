<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAP PENERIMAAN BARANG BY INVOICE</title>
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
                    <b>02 REKAP PENERIMAAN BARANG BY INVOICE</b>
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
                <td bgcolor="#cccccc" align="center"><b>Suplier</b></td>
                <td bgcolor="#cccccc" align="center"><b>Bapb No</b></td>
                <td bgcolor="#cccccc" align="center"><b>Tanggal</b></td>
                <td bgcolor="#cccccc" align="center"><b>No. Invoice/SJ</b></td>
                <td bgcolor="#cccccc" align="center"><b>Total</b></td>
                <td bgcolor="#cccccc" align="center"><b>Discount</b></td>
                <td bgcolor="#cccccc" align="center"><b>Vat</b></td>
                <td bgcolor="#cccccc" align="center"><b>Materai</b></td>
                <td bgcolor="#cccccc" align="center"><b>Total Net</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
             if($data != ''){
             $nomor = 1;
             $Discount = 0;
             $vatRp = 0;
             $materai = 0;
             $total = 0;
             $totalnet = 0;
             foreach ($data as $dt) {   ?>

            <tr>
                <td align="center"><?= $nomor++?></td>
                <td align="left"><?= $dt->vendor_name?></td>
                <td align="left"><?=$dt->terima_no?></td>
                <td align="left"><?=date('d-m-Y', strtotime($dt->terima_date))?></td>
                <td align="left"><?=$dt->sj_no?></td>
                <td align="right"><?= number_format($dt->totalrp,2)?></td>
                <td align="right"><?= number_format($dt->diskontotal,2)?></td>
                <td align="right"><?= number_format($dt->vatrp,2)?></td>
                <td align="right"><?= number_format($dt->materai,2)?></td>
                <td align="right"><?= number_format($dt->totalnet,2)?></td>
            </tr>

            <?php 
        // $Discount += ($dt->diskontotal);
        // $vatRp += ($dt->vatrp);
        // $materai += ($dt->materai);
        // $total += ($dt->totalrp);
        // $totalnet += ($dt->totalnet);
        } }?>
            <!-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right"><?=number_format($total,2);?></td>
                <td align="right"><?=number_format($Discount,2);?></td>
                <td align="right"><?=number_format($vatRp,2);?></td>
                <td align="right"><?=number_format($materai,2);?></td>
                <td align="right"><?=number_format($totalnet,2);?></td>
            </tr> -->
        </tbody>
    </table>
</body>

</html>