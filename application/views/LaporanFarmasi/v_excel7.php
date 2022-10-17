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
            <td bgcolor="#cccccc" align="center"><b>Retur No</b></td>
            <td bgcolor="#cccccc" align="center"><b>Tanggal</b></td>
            <td bgcolor="#cccccc" align="center"><b>No. Bapb</b></td>
            <td bgcolor="#cccccc" align="center"><b>Kode BRG </b></td>
            <td bgcolor="#cccccc" align="center"><b>Nama Barang</b></td>
            <td bgcolor="#cccccc" align="center"><b>Qty</b></td>
            <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
            <td bgcolor="#cccccc" align="center"><b>Harga Satuan</b></td>
            <td bgcolor="#cccccc" align="center"><b>Total</b></td>
            <td bgcolor="#cccccc" align="center"><b>PO NO</b></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($query != '') {
            $nomor = 1;
            $qty = 0;
            $total = 0;
            foreach ($query as $dt) {   ?>

                <tr>
                    <td align="center"><?= $dt->retur_no ?></td>
                    <td align="center"><?= date("Y-m-d", strtotime($dt->retur_date)) ?></td>
                    <td align="center"><?= $dt->terima_no ?></td>
                    <td align="center"><?= $dt->kodebarang ?></td>
                    <td align="center"><?= $dt->namabarang ?></td>
                    <td align="center"><?= $dt->qty_retur ?></td>
                    <td align="center"><?= $dt->satuan ?></td>
                    <td align="center"><?= round($dt->price, 0) ?></td>
                    <td align="center"><?= round($dt->totalrp, 0) ?></td>
                    <td align="center"><?= $dt->po_no ?></td>
                </tr>

        <?php
                $qty += ($dt->qty_retur);
                $total += ($dt->price * $dt->qty_retur);
            }
        } ?>
        <tr>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><b>Sub Total :</b></td>
            <td align="center"><b><?= $qty ?></b></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><b><?= round($total, 0) ?></b></td>
        </tr>
    </tbody>
</table><br>
<?php
$tgl = date("d-m-Y");
?>
<td>
    <h5 align="center">Yogyakarta, <?= $tgl ?></h5>
</td>