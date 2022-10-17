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
                <td align="center">No</td>
                <td align="center">No Resep</td>
                <td align="center">Nama </td>
                <td align="center">Rekmed</td>
                <td align="center">Kode Obat</td>
                <td align="center">Nama Obat</td>
                <td align="center">Satuan</td>
                <td align="center">Qty</td>
                <td align="center"> Total</td>
                <td align="center"> Total HNA</td>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($query as $dt) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $dt->resepno ?></td>
                    <td><?= $dt->namapas ?></td>
                    <td><?= $dt->rekmed ?></td>
                    <td><?= $dt->kodebarang ?></td>
                    <td><?= $dt->namabarang ?></td>
                    <td><?= $dt->satuan ?> </td>
                    <td><?= round($dt->qty, 0) ?></td>
                    <td><?= round($dt->totalrp, 0) ?></td>
                    <td><?= round($dt->hna, 0) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>