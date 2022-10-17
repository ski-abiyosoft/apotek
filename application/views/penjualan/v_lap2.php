<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $judul ?>

    </title>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td colspan="2" align="center">Tunai</td>
                <td colspan="2" align="center">Lokal</td>
                <td colspan="2" align="center">Kirim</td>
                <td colspan="2" align="center">Spa</td>
                <td colspan="2" align="center">Apotik</td>
                <td colspan="2" align="center">Jumlah Total</td>
            </tr>
            <tr>
                <td align="center">Kode</td>
                <td align="center">Nama Barang</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>
                <td align="center">QTY</td>
                <td align="center">RP</td>

            </tr>
            <tr>
                <td></td>
                <td></td>
                <!-- <td align="center">Qty</td>
                <td align="center">Rp</td> -->
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($query as $dt) { ?>
                <tr>
                    <td><?= $dt->kodebarang ?></td>
                    <td><?= $dt->namabarang ?></td>
                    <td><?= round($dt->qty_tunai, 0) ?></td>
                    <td><?= round($dt->rp_tunai, 0) ?></td>
                    <td><?= round($dt->qty_lokal, 0) ?></td>
                    <td><?= round($dt->rp_lokal, 0) ?></td>
                    <td><?= round($dt->qty_kirim, 0) ?></td>
                    <td><?= round($dt->rp_kirim, 0) ?></td>
                    <td><?= round($dt->qty_spa, 0) ?></td>
                    <td><?= round($dt->rp_spa, 0) ?></td>
                    <td><?= round($dt->qty_apotik, 0) ?></td>
                    <td><?= round($dt->rp_apotik, 0) ?></td>
                    <td><?= round($dt->jualtotal_qty, 0) ?></td>
                    <td><?= round($dt->jualtotal_rp, 0) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>