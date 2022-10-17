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
                <td></td>
                <td></td>
                <td colspan="2" align="center">Penjualan</td>
                <td colspan="2" align="center">Harga Pokok Pembelian</td>
                <td colspan="3" align="center">Analisa rugi laba </td>
            </tr>
            <!-- <td>hh</td> -->
            <tr>
                <td align="center">Kode</td>
                <td align="center">Nama Barang</td>
                <td align="center">Satuan</td>
                <td align="center">Qty</td>
                <td align="center">Harga Jual</td>
                <td align="center">Total Net Rp</td>
                <td align="center">Harga Pokok</td>
                <td align="center">Total Hpp</td>
                <td align="center">PPN Rp</td>
                <td align="center">Rugi Laba</td>
                <td align="center">%</td>
            </tr>

        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($query as $dt) { ?>
                <tr>
                    <td><?= $dt->kodebarang ?></td>
                    <td><?= $dt->namabarang ?></td>
                    <td><?= $dt->satuan ?></td>
                    <td><?= round($dt->qty, 0) ?></td>
                    <td><?= round($dt->harga_jual, 0) ?></td>
                    <td><?= round($dt->totalnet, 0) ?></td>
                    <td><?= round($dt->harga_pokok, 0) ?></td>
                    <td><?= round($dt->total_hpp, 0) ?></td>
                    <td><?= round($dt->ppnrp, 0) ?></td>
                    <td><?= round($dt->rugi_laba, 0) ?></td>
                    <td><?= round($dt->persen, 2) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>