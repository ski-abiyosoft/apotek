<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN HUTANG FARMASI</title>
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
                    <b>08 LAPORAN HUTANG FARMASI</b>
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
                <td bgcolor="#cccccc" align="center"><b>Rekanan </b></td>
                <td bgcolor="#cccccc" align="center"><b>No. Faktur </b></td>
                <td bgcolor="#cccccc" align="center"><b>Obat </b></td>
                <td bgcolor="#cccccc" align="center"><b>Alkes Rutin</b></td>
                <td bgcolor="#cccccc" align="center"><b>Alk. Investasi</b></td>
                <td bgcolor="#cccccc" align="center"><b>Bahan kimia</b></td>
                <td bgcolor="#cccccc" align="center"><b>Gas Medik</b></td>
                <td bgcolor="#cccccc" align="center"><b>Pemeliharaan</b></td>
                <td bgcolor="#cccccc" align="center"><b>Sewa</b></td>
                <td bgcolor="#cccccc" align="center"><b>Pelengkap</b></td>
                <td bgcolor="#cccccc" align="center"><b>Lain2</b></td>
                <td bgcolor="#cccccc" align="center"><b>Materai </b></td>
                <td bgcolor="#cccccc" align="center"><b>Jumlah </b></td>
            </tr>
        </thead>
        <tbody>
            <?php
             if($data != ''){
             $nomor = 1;
            $obat = 0;
            $alkes_rutin = 0;
            $alk_investasi = 0;
            $bahan_kimia = 0;
            $gas_medik = 0;
            $pemeliharaan = 0;
            $sewa = 0;
            $pelengkapan = 0;
            $lain2 = 0;
            $materai = 0;
            $jumlah = 0;
             foreach ($data as $dt) {   ?>
            <tr>
                <td align="center"><?= $nomor++?></td>
                <td align="left"><?= $dt->rekanan; ?></td>
                <td align="left"><?= $dt->no_faktur; ?></td>
                <td align="right"><?= number_format($dt->obat,2); ?></td>
                <td align="right"><?= number_format($dt->alkes_rutin, 2); ?></td>
                <td align="right"><?= number_format($dt->alk_investasi, 2); ?></td>
                <td align="right"><?= number_format($dt->bahan_kimia, 2); ?></td>
                <td align="right"><?= number_format($dt->gas_medik, 2); ?></td>
                <td align="right"><?= number_format($dt->pemeliharaan, 2); ?></td>
                <td align="right"><?= number_format($dt->sewa, 2); ?></td>
                <td align="right"><?= number_format($dt->pelengkapan, 2); ?></td>
                <td align="right"><?= number_format($dt->lain2, 2); ?></td>
                <td align="right"><?= number_format($dt->materai, 2); ?></td>
                <td align="right"><?= number_format($dt->jumlah, 2); ?></td>


            </tr>

            <?php 
                $obat += $dt->obat;
                $alkes_rutin += $dt->alkes_rutin;
                $alk_investasi += $dt->alk_investasi;
                $bahan_kimia += $dt->bahan_kimia;
                $gas_medik += $dt->gas_medik;
                $pemeliharaan += $dt->pemeliharaan;
                $sewa += $dt->sewa;
                $pelengkapan += $dt->pelengkapan;
                $lain2 += $dt->lain2;
                $materai += $dt->materai;
                $jumlah += $dt->jumlah;
        } }?>
            <tr>
                <td align="center" colspan="3">TOTAL</td>
                <td align="right"><?= number_format($obat,2); ?></td>
                <td align="right"><?= number_format($alkes_rutin,2); ?></td>
                <td align="right"><?= number_format($alk_investasi,2); ?></td>
                <td align="right"><?= number_format($bahan_kimia,2); ?></td>
                <td align="right"><?= number_format($gas_medik,2); ?></td>
                <td align="right"><?= number_format($pemeliharaan,2); ?></td>
                <td align="right"><?= number_format($sewa,2); ?></td>
                <td align="right"><?= number_format($pelengkapan,2); ?></td>
                <td align="right"><?= number_format($lain2,2); ?></td>
                <td align="right"><?= number_format($materai,2); ?></td>
                <td align="right"><?= number_format($jumlah,2); ?></td>
            </tr>

        </tbody>
    </table>
</body>

</html>