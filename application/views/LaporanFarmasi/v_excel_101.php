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
            <td bgcolor="#cccccc" align="center"><b>Bapb No</b></td>
            <td bgcolor="#cccccc" align="center"><b>Tanggal</b></td>
            <td bgcolor="#cccccc" align="center"><b>No. Invoice/SJ</b></td>
            <td bgcolor="#cccccc" align="center"><b>Kode Barang</b></td>
            <td bgcolor="#cccccc" align="center"><b>Nama Barang</b></td>
            <td bgcolor="#cccccc" align="center"><b>Qty</b></td>
            <td bgcolor="#cccccc" align="center"><b>Satuan</b></td>
            <td bgcolor="#cccccc" align="center"><b>Harga Sat</b></td>
            <td bgcolor="#cccccc" align="center"><b>Total</b></td>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($query as $q) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $q->terima_no; ?></td>
                <td><?= $q->terima_date; ?></td>
                <td><?= $q->sj_no; ?></td>
                <td><?= $q->kodebarang; ?></td>
                <td><?= $q->namabarang; ?></td>
                <td><?= round($q->qty_terima); ?></td>
                <td><?= $q->satuan; ?></td>
                <td><?= round($q->price); ?></td>
                <td><?= round($q->totalrp); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>