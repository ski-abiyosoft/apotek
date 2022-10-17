<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktiva Tetap yang Telah Habis</title>
</head>
<body>
    <style>
        h1{
            font-size: 16px;
            text-align: center;
            margin: 0;
        }

        .description{
            font-size: 8px;
            text-align: center;
            margin: 0;
        }

        table{
            border-collapse: collapse;
        }

        #table-laporan{
            margin-top: 10px;
        }

        th{
            font-size: 8px;
            border: 1px solid black;
            padding: 3px;
        }

        .report-data{
            font-size: 8px;
            padding: 3px;
            border: 1px solid black;
            text-align: center;
        }

        .data-name{
            width: 100px;
        }

        #table-summary{
            margin-top: 50px;
        }

        .summary-text{
            font-size:  10px;
            padding: 4px;
            border: 1px solid black;
            font-weight: bold;
        }
    </style>
    <?php setlocale(LC_ALL, 'id_ID'); ?>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size: 10px; color:#000;" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="6">
                    <img src="<?= base_url('assets/img/logo.png'); ?>"  width="70" height="70" />
                </td>
                <td colspan="20">
                    <b>
                            <tr>
                                <td style="font-size:10px;border-bottom: none;"><b><br><?= $kop->namars; ?></b></td>
                            </tr>
                            <tr>
                                <td style="font-size:9px;"><?= $kop->alamat; ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:9px;"><?= $kop->alamat2; ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:9px;">Wa : <?= $kop->whatsapp; ?>    Telp : <?= $kop->phone; ?> </td>
                            </tr>
                            <tr>
                                <td style="font-size:9px;">No. NPWP : <?= $kop->npwp; ?></td>
                            </tr>
                    </b>
                </td>
            </tr>
        </thead>
    </table>
    <hr>
    <div id="body-laporan">
        <h1>Invoice</h1>
        <table style='border-collapse:collapse;font-family: serif; font-size: 10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
            <tr rowspan='6' class='show1'>
                <td align='left'  width='5%'>Nomor</td>
                <td align='center'  width='5%'>:</td>
                <td align='left'  width='40%'><?= $data->invoiceno; ?></td>
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'><?= ucfirst($kop->detail_rs->kota); ?>, <?= local_date($data->invoicedate); ?></td>
            </tr>		
            <tr class='show1'>
                <td align='left'  width='15%'>Lampiran</td>
                <td align='center'  width='5%'>:</td>
                <td align='left'  width='30%'>1 (satu) Bendel</td>
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'>Kepada Yth.</td>
            </tr>		
            <tr class='show1'>
                <td align='left'  width='15%'>Perihal</td>
                <td align='center'  width='5%'>:</td>
                <td align='left'  width='30%'><b><u>Tagihan</u></b></td>
            
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'>Pimpinan</td>
            </tr>		
            <tr class='show1'>
                <td align='left' width='15%'></td>
                <td align='center' width='5%'></td>
                <td align='left' width='30%'></td>
            
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'><b><?= $data->cust_nama; ?></b></td>
            </tr>		
            <tr class='show1'>
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'></td>
            
                <td align='left'  width='15%'></td>
                <td align='center'  width='5%'></td>
                <td align='left'  width='30%'>D.a: <?= $kop->detail_rs->kota; ?></td>
            </tr>	
		</table>
        <p width='100%' style='font-family: serif; font-size: 10px; margin-top: 20px'>Dengan Hormat,</p>
        <div style='font-family: serif; font-size: 10px; text-align: justify; margin-bottom: 1rem;'>
            Bersama dengan surat ini kami kirimkan tagihan biaya pelayanan kesehatan karyawan <b><?= $data->cust_nama; ?></b> yang dirawat di <b><?= $kop->namars; ?></b>, unutk periode <b><?= local_date($data->dariperiode); ?></b> hingga <b><?= local_date($data->sampaiperiode); ?></b> sebanyak <b><?= count($data->patients); ?></b> kunjungan pasien <b><?= $data->jenis == 1 ? 'Rawat Jalan' : ($data->jenis == 2 ? 'Rawat Inap' : 'Rawat Jalan dan Rawat Inap'); ?></b> dengan rincian sebagai berikut:
        </div>
        <table 
            style='border-collapse:collapse;font-family: serif; font-size: 10px' 
            width='100%' 
            align='center' 
            border='1' 
            cellspacing='0' 
            cellpadding='3'>
            <tr>
                <th>NO.</th>
                <th>TANGGAL</th>
                <th>NAMA PASIEN</th>
                <th>NO. DOKUMEN</th>
                <th>NO. PESERTA</th>
                <th>BIAYA</th>
            </tr>
            <?php for ($i = 0; $i < count($data->patients); $i++) : ?>
                <tr>
                    <td class="report-data" align='center'><?= $i + 1; ?></td>
                    <td class="report-data" align='center'><?= local_date($data->patients[$i]->tglposting); ?></td>
                    <td class="report-data" align='left'><?= $data->patients[$i]->namapas; ?></td>
                    <td class="report-data" align='left'><?= $data->patients[$i]->noreg; ?></td>
                    <td class="report-data" align='left'><?= $data->patients[$i]->nopolis; ?></td>
                    <td class="report-data" align='right'>Rp <?= number_format($data->patients[$i]->jumlahhutang, 2, ',', '.'); ?></td>
                </tr>
            <?php endfor; ?>
            <tr>
                <td class="report-data" colspan='5' align='center' style='border:none;border-left: 1px solid black;'>JUMLAH PIUTANG</td>
                <td class="report-data" align='right' style='border:none;border-right: 1px solid black;'><b>Rp <?= number_format($data->jumlahrp, 2, ',', '.'); ?></b></td>
            </tr>
            <tr>
                <td class="report-data" colspan='5' align='center' style='border:none;border-left: 1px solid black;'>DISKON (<?= $data->diskon; ?> %)</td>
                <td class="report-data" align='right' style='border:none;border-bottom: 1px solid black;border-right: 1px solid black;'><b>Rp <?= number_format($data->diskonrp, 2, ',', '.'); ?></b></td>
            </tr>
            <tr>
                <td class="report-data" colspan='5' align='center' style='border:none;border-left: 1px solid black;border-bottom: 1px solid black;'>JUMLAH AKHIR</td>
                <td class="report-data" align='right' style='border:none;border-bottom: 1px solid black;border-right: 1px solid black;'><b>Rp <?= number_format($data->totalnetrp, 2, ',', '.'); ?></b></td>
            </tr>
        </table>
        <br>
        <p width='100%' style='font-family: serif; font-size: 10px;'>
            <em>Terbilang: <b><?= terbilang($data->totalnetrp); ?></b></em>
        </p>
        <p width='100%' style='font-family: serif; font-size: 10px'>
            dengan rincian, vide terlampir.
        </p>
        <div width='100%' style='font-family: serif; font-size: 10px; margin-bottom: 10px'>
            Guna melakukan realisasi pembayaran atas invoice ini, berikut kami lampirkan rekening bank yang dapat digunakan untuk keperluan transfer:
        </div>
        <div width='75%' style='font-family: serif; font-size: 10px; border:1px solid black; border-radius:5px; padding:10px; margin-bottom: 10px;'>
            BANK <?= $kop->detail_rs->ketbank; ?> (Cabang: <?= $kop->detail_rs->kota_full; ?>) atas nama: <?= $kop->detail_rs->pkpno; ?>
        </div>
        <div width='100%' style='font-family: serif; font-size: 10px; margin-bottom: 15px;'>
            Demikian surat tagihan ini kami sampaikan, atas perhatian dan kerja sama yang Bapak/Ibu berikan, kami sampaikan terima kasih.
        </div>
        <div width='100%' style='display: flex!important;justify-content: space-between!important;'>
            <div width='40%' 
                style='float:left;font-family: serif; font-size:10px; border:1px solid black; border-radius:5px;padding:10px;'>
                <i>NB:<br>
                Harap dicantumkan berita:<br>
                - Nomor surat tagihan dari RS.<br>
                - Nama Perusahaan Pengirim<br>
                Untuk Pembayaran melalui transfer
            </div>
            <div width='30%' style='float:right;font-family: serif; font-size:10px; padding:5px;text-align:center;'>
                <p style="margin: 0"><?= $kop->detail_rs->kota; ?>,<?= local_date($data->invoicedate); ?></p>
                <p style="margin-top: 4rem; margin-bottom: 0; font-weight: bold;"><?= $kop->detail_rs->pejabat1; ?></p>
                <p style="margin: 0"><?= $kop->detail_rs->jabatan1; ?></p>
            </div>
        </div>
    </div>
</body>
</html>