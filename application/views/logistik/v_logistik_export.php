<?php 
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=".$judul.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<table border="1">
    <thead>
        <tr>
            <th style="text-align: center">Cabang</th>
            <th style="text-align: center">Kode Barang</th>
            <th style="text-align: center">Nama Barang</th>
            <th style="text-align: center">Saldo Awal</th>
            <th style="text-align: center">Sesuai</th>
            <th style="text-align: center">Terima</th>
            <th style="text-align: center">Keluar</th>
            <th style="text-align: center">Hasil SO</th>
            <th style="text-align: center">Saldo Akhir</th>
            <th style="text-align: center">Satuan</th>
            <th style="text-align: center">Gudang</th>
        </tr>
    </thead>
    <tbody>
        <?php
	   foreach($data as $row) { ?>
        <tr>
            <td>
                <center><?php echo $row->koders;?></center>
            </td>
            <td align="center"><?php echo $row->kodebarang;?></td>
            <td align="center"><?php echo $row->namabarang;?></td>
            <td align="center"><?php echo number_format($row->saldoawal);?></td>
            <td align="center"><?php echo number_format($row->sesuai);?></td>
            <td align="center"><?php echo number_format($row->terima);?></td>
            <td align="center"><?php echo number_format($row->keluar);?></td>
            <td align="center"><?php echo number_format($row->hasilso);?></td>
            <td align="center"><?php echo number_format($row->saldoakhir);?></td>
            <td><?php echo $row->satuan1;?></td>
            <td><?php echo $row->gudang;?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>