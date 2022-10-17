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
        if ($query != '') {
            $nomor = 1;
            $Discount = 0;
            $vatRp = 0;
            $materai = 0;
            $total = 0;
            foreach ($query as $dt) {   ?>

                <tr>
                    <td align="center"><?= $nomor++ ?></td>
                    <td align="center"><?= $dt->vendor_name ?></td>
                    <td align="center"><?= $dt->terima_no ?></td>
                    <td align="center"><?= $dt->terima_date ?></td>
                    <td align="center"><?= $dt->sj_no ?></td>
                    <td align="center"><?= round($dt->totalrp) ?></td>
                    <td align="center"><?= round($dt->diskontotal) ?></td>
                    <td align="center"><?= $dt->vatrp ?></td>
                    <td align="center"><?= $dt->materai ?></td>
                    <td align="center"><?= round($dt->totalnet) ?></td>
                </tr>

        <?php
                // $Discount += ($dt->diskontotal);
                // $vatRp += ($dt->vat);
                // $materai += ($dt->materai);
                // $total += ($dt->totalrp);
            }
        } ?>
        <!-- <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center"><?= number_format($total, 0); ?></td>
            <td align="center"><?= number_format($Discount, 0); ?></td>
            <td align="center"><?= number_format($vatRp, 0); ?></td>
            <td align="center"><?= number_format($materai, 0); ?></td>
            <td align="center"><?= number_format($total, 0); ?></td>
        </tr> -->
    </tbody>
</table>