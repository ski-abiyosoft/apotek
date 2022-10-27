<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAP PENERIMAAN BARANG PER SUPLIER</title>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="0" cellspacing="0" cellpadding="0" width="80%">
        <thead>
            <tr>
                <td width="10%">
                    <img src="<?= base_url('/assets/img/logo.png') ?>" width="100" height="100">
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td style="font-size:14px;border-bottom: none;"><b><?= $kop['namars']; ?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b><?= $kop['alamat'] ?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>WA:<?= $kop['whatsapp']; ?> Telp :<?= $kop['phone']; ?></b> </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>No. NPWP <?= $kop['npwp'] ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td colspan="2" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b><br></b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;"><b>04 REKAP
                        PENERIMAAN BARANG PER SUPLIER</b></td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center;font-size:15px;border-top: none;"><?= $_peri ?></td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td style="border:0" align="center"><br> </td>
            </tr>
            <tr>
                <td bgcolor="#cccccc" align="center"><b>No</b></td>
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
            if ($data != '') {
                $nomor = 1;
                $QTY = 0;
                $DISKON = 0;
                $VAT = 0;
                $MATERAI = 0;
                $TOTAL = 0;
                foreach ($data as $dt) {   ?>

                    <tr>
                        <td align="center"><?= $nomor++ ?></td>
                        <td align="center"><?= $dt->vendor_name ?></td>
                        <td align="center"><?= number_format($dt->qty_terima, 2) ?></td>
                        <td align="center"><?= number_format($dt->totalrp, 2) ?></td>
                        <td align="center"><?= number_format($dt->discountrp, 2) ?></td>
                        <td align="center"><?= number_format($dt->vatrp, 2) ?></td>
                        <td align="center"><?= number_format($dt->materai, 2) ?></td>
                        <td align="center"><?= number_format($dt->totalrp, 2) ?></td>
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
                <td align="center"><?= $QTY ?></td>
                <td align="center"><?= number_format($TOTAL, 0) ?></td>
                <td align="center"><?= number_format($DISKON, 0) ?></td>
                <td align="center"><?= number_format($VAT, 0) ?></td>
                <td align="center"><?= number_format($MATERAI, 0) ?></td>
                <td align="center"><?= number_format($TOTAL, 0) ?></td>

            </tr>
        </tbody>
    </table>
    <?php
    $tgl = date("Y-m-d");

    ?>
    <td>
        <h5>Tanggal Cetak: <?= $tgl ?></h5>
    </td>
    <td>
        <h5>HOSPITAL MANAGEMENT SIMTEM</h5>
    </td>
    <td align="right">

    </td>

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
</body>

</html>