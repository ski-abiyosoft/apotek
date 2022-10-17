<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KARTU STOCK
    </title>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0" width="80%">
        <thead>
            <tr>
                <td width="10%">
                    <img src="<?=base_url('/assets/img/logo.png')?>" width="100" height="100">
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td style="font-size:14px;border-bottom: none;"><b><?=$kop['namars'];?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b><?=$kop['alamat']?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>WA:<?=$kop['whatsapp'];?> Telp :<?=$kop['phone'];?></b> </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>No. NPWP <?= $kop['npwp']?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b><?=$peri1?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td colspan="2" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b><br></b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b>KARTU STOCK</b><br><br>
                </td>
            </tr>
        </thead>
    </table>
    <div class="data">

        <td>Cabang: <?=$query_cab->kota?></td><br>
        <td>Gudang: <?=$gudang->keterangan?></td><br>
        <td>Kode Barang: <?= $barang; ?></td><br>
        <td>Nama Barang: <?= $namabrg; ?></td><br>
    </div><br>

    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" border="1">
        <thead>
            <tr>
                <td align="center"><b>No.Bukti</b></td>
                <td align="center"><b>Tanggal</b></td>
                <td align="center"><b>Keterangan</b></td>
                <td align="center"><b>Rekanan</b></td>
                <td align="center"><b>Nilai Pembelian</b></td>
                <td align="center"><b>Terima</b></td>
                <td align="center"><b>Keluar</b></td>
                <td align="center"><b>Saldo Akhir</b></td>
                <td align="center"><b>Total Nilai Persediaan</b></td>
                <td align="center"><b>Nilai Persediaan</b></td>
            </tr>
        </thead>
        <tbody>
            <td style="text-align: center">SALDO</td>
            <td style="text-align: center"><?= date('d-m-y', strtotime($tanggalwal))?></td>
            <td style="text-align: center"><?='SALDO AWAL '.date('d-m-Y',strtotime($tanggalwal))?></td>
            <td style="text-align: center"></td>
            <td style="text-align: center"><?= number_format(0,0,'.',',')?></td>
            <td style="text-align: center"><?= number_format(0,0,'.',',')?></td>
            <td style="text-align: center"><?= number_format(0,0,'.',',')?></td>
            <td style="text-align: center"><?= number_format($saldo,0,'.',',')?></td>
            <td style="text-align: center"><?= number_format(0,0,'.',',')?></td>
            <td style="text-align: center"><?= number_format(0,0,'.',',')?></td>
            <?php 
                foreach($data_list as $dval):
                if($dval->terima > 0){
					$salakhir = number_format($saldo = $saldo - $dval->keluar + $dval->terima,0,'.',',');
				} else 
				if($dval->keluar > 0){
					$salakhir = number_format($saldo = $saldo + $dval->terima - $dval->keluar,0,'.',',');
				}
                $nilai = $dval->qty*$dval->harga;
            ?> <tr>
                <td align="center"><?= $dval->nomor ?></td>
                <td align="center"><?= date('d-m-Y',strtotime($dval->tanggal)) ?></td>
                <td align="center"><?= $dval->keterangan ?></td>
                <td align="center"><?= $dval->rekanan ?></td>
                <td align="center"><?= number_format($nilai,0,'.',',') ?></td>
                <td align="center"><?= number_format($dval->terima,0,'.',',') ?></td>
                <td align="center"><?= number_format($dval->keluar,0,'.',',') ?></td>
                <td align="center"><?= $salakhir ?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>