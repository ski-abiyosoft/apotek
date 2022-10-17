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
                <td align="center"><b>No</b></td>
                <td align="center"><b>Tr No</b></td>
                <td align="center"><b>Tanggal</b></td>
                <td align="center"><b>No reg/rekmed</b></td>
                <td align="center"><b>Nama Pasien</b></td>
                <td align="center"><b>Resep Dari</b></td>
                <td align="center"><b>Nama Obat</b></td>
                <td align="center"><b>Qty</b></td>
                <td align="center"><b>Satuan</b></td>
                <td align="center"><b>Disc%</b></td>
                <td align="center"><b>Harga Satuan </b></td>
                <td align="center"><b>Total </b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($query as $dt) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $dt->resepno ?></td>
                    <td><?= date('Y-m-d', strtotime($dt->tglresep)) ?></td>
                    <td>
                        <?php
                        if ($dt->noreg != '') {
                            echo $dt->noreg;
                        } else {
                            echo 'LOKAL';
                        }
                        ?>
                    </td>
                    <td><?= $dt->namapas ?></td>
                    <td><?= $dt->nadokter ?></td>
                    <td><?= $dt->namabarang ?></td>
                    <td><?= round($dt->qty, 0) ?></td>
                    <td><?= $dt->satuan ?></td>
                    <td><?= $dt->discount ?></td>
                    <td><?= round($dt->price, 0) ?></td>
                    <td><?= round($dt->totalrp, 0) ?></td>
                </tr>


            <?php } ?>
        </tbody>
    </table>
</body>

</html>