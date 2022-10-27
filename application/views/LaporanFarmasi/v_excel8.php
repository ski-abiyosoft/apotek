<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=" . $judul . ".xls");
// header("Pragma: no-cache");
// header("Expires: 0");
?>

<table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1" cellspacing="0" cellpadding="0">
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
        if ($query != '') {
            $nomor = 1;
            $materai = 0;
            foreach ($query as $dt) {   ?>

                <tr>
                    <td align="center"><?= $nomor++ ?></td>
                    <td align="center"><?= $dt->rekanan; ?></td>
                    <td align="center"><?= $dt->no_faktur; ?></td>
                    <td align="center"><?= round($dt->obat, 0); ?></td>
                    <td align="center"><?= round($dt->alkes_rutin, 0); ?></td>
                    <td align="center"><?= round($dt->alk_investasi, 0); ?></td>
                    <td align="center"><?= round($dt->bahan_kimia, 0); ?></td>
                    <td align="center"><?= round($dt->gas_medik, 0); ?></td>
                    <td align="center"><?= round($dt->pemeliharaan, 0); ?></td>
                    <td align="center"><?= round($dt->sewa, 0); ?></td>
                    <td align="center"><?= round($dt->pelengkapan, 0); ?></td>
                    <td align="center"><?= round($dt->lain2, 0); ?></td>
                    <td align="center"><?= round($dt->materai, 0) ?></td>
                    <td align="center"><?= round($dt->jumlah, 0) ?></td>
                    <td align="center"></td>


                </tr>

        <?php
                // $materai += ($dt->materai);
            }
        } ?>
        <!-- <tr>
            <td align="center"></td>
            <td align="center">TOTAL</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><?= round($materai, 0) ?></td>
            <td align="center"></td>
        </tr> -->

    </tbody>
</table>