<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
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
        if ($query != '') {
            $nomor = 1;
            $QTY = 0;
            $rataRata = 0;
            $totalSemua = 0;
            foreach ($query as $dt) {   ?>

                <tr>
                    <td align="center"><?= $nomor++ ?></td>
                    <td align="center"><?= $dt->kodebarang ?></td>
                    <td align="center"><?= $dt->satuan ?></td>
                    <td align="center"><?= $dt->qty_terima ?></td>
                    <td align="center"><?= round($dt->ratarata, 0) ?></td>
                    <td align="center"><?= round($dt->qty_terima * $dt->ratarata, 0) ?></td>

                </tr>

        <?php
                $QTY += ($dt->qty_terima);
                $rataRata += ($dt->ratarata);
                $totalSemua += ($dt->qty_terima * $dt->ratarata);
            }
        } ?>
        <tr>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><?= $QTY ?></td>
            <td align="center"><?= round($rataRata, 0) ?></td>
            <td align="center"><?= round($totalSemua, 0) ?></td>
        </tr>
    </tbody>
</table>