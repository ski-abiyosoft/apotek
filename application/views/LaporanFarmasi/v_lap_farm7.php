<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN RETUR PEMBELIAN BARANG</title>
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
                    <b>07 LAPORAN RETUR PEMBELIAN BARANG</b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center;font-size:15px;border-top: none;"><?=$_peri?></td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="1" cellspacing="0" cellpadding="0">
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
            <tr>
                <td><b>SUPLIER : <?=$suplier->vendor_name?></b></td>

            </tr>
            <?php
             if($data != ''){
             $nomor = 1;
             $qty = 0;
             $total = 0;
             foreach ($data as $dt) {   ?>

            <tr>
                <td align="left"><?= $dt->retur_no; ?></td>
                <td align="left"><?=date("Y-m-d", strtotime($dt->retur_date))?></td>
                <td align="left"><?=$dt->terima_no?></td>
                <td align="left"><?=$dt->kodebarang?></td>
                <td align="left"><?=$dt->namabarang?></td>
                <td align="right"><?=$dt->qty_retur?></td>
                <td align="left"><?=$dt->satuan?></td>
                <td align="right"><?= number_format($dt->price, 2)?></td>
                <td align="right"><?= number_format($dt->totalrp, 2)?></td>
                <td align="left"><?= $dt->po_no?></td>
                <td></td>
            </tr>

            <?php 
            $qty += ($dt->qty_retur);
            $total +=($dt->totalrp);
        } }?>
            <tr>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"><b>Sub Total :</b></td>
                <td align="right"><b><?=$qty?></b></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="right"><b><?=number_format($total,2)?></b></td>
            </tr>
        </tbody>
    </table><br>
    <?php
    $tgl = date("d-m-Y");
    ?>
    <td>
        <h5 align="center" margin="20px">Yogyakarta, <?=$tgl?></h5>
    </td>

    <!-- <td>
        <h5 align="left">Dibuat Oleh</h5>
    </td>
    <td>
        <h5 align="center">Disetujui</h5>
    </td>
    <td>
        <h5 align="right">Diterima oleh</h5>
    </td> -->


</body>

</html>