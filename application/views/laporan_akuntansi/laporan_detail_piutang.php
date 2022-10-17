<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Detail Piutang</title>
</head>

<body>
    <style>
        h1 {
            font-size: 14px;
            text-align: center;
            margin: 0;
        }

        #description {
            font-size: 10px;
            text-align: center;
            margin: 0;
        }

        table {
            border-collapse: collapse;
        }

        .table-laporan {
            margin-top: 10px;
        }

        th {
            font-size: 10px;
            border: 1px solid black;
            padding: 3px;
        }

        .report-data {
            font-size: 8px;
            padding: 3px;
            border: 1px solid black;
            text-align: center;
        }

        .data-name {
            width: 80px;
        }

        #table-summary {
            margin-top: 50px;
        }

        .summary-text {
            font-size: 12px;
            padding: 4px;
            border: 1px solid black;
            font-weight: bold;
        }

        .vendor {
            font-size: 12px;
            background-color: lightcyan;
            padding: 4px;
            font-weight: bold;
        }

        .align-left {
            text-align: left;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="6">
                    <img src="<?= base_url('assets/img/logo.png'); ?>" width="70" height="70" />
                </td>
                <td colspan="20">
                    <b>
            <tr>
                <td style="font-size:10px;border-bottom: none;"><b><br><?= $kop['namars']; ?></b></td>
            </tr>
            <tr>
                <td style="font-size:9px;"><?= $kop['alamat']; ?></td>
            </tr>
            <tr>
                <td style="font-size:9px;"><?= $kop['alamat2']; ?></td>
            </tr>
            <tr>
                <td style="font-size:9px;">Wa : <?= $kop['whatsapp']; ?> Telp : <?= $kop['phone']; ?> </td>
            </tr>
            <tr>
                <td style="font-size:9px;">No. NPWP : <?= $kop['npwp']; ?></td>
            </tr>
            </b>
            </td>
            </tr>
        </thead>
    </table>
    <hr>
    <div id="body-laporan">
        <h1>Laporan Detail Piutang</h1>
        <p id="description">Dari <?= date('d F Y', strtotime($input->fromdate)); ?> s/d tanggal: <?= date('d F Y', strtotime($input->todate)); ?></p>
        <p id="description">Untuk Sumber <?= $input->jenis; ?></p>
        <?php foreach ($data as $vendor) : ?>
            <p class="vendor"><?= $vendor->cust_id; ?> - <?= $vendor->cust_nama; ?></p>
            <table class="table-laporan">
                <thead>
                    <tr>
                        <th>No Faktur</th>
                        <th>No Reg</th>
                        <th>Rekmed</th>
                        <th>No Doc</th>
                        <th class="data-name">Nama Pasien</th>
                        <th>Penjamin</th>
                        <th>Jenis</th>
                        <th>Tgl Posting</th>
                        <th>Jatuh Tempo</th>
                        <th>Total Tagihan</th>
                        <th>Inacbg</th>
                        <th>Total Bayar</th>
                        <th>Saldo Piutang</th>
                        <th>No Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="report-data text-bold" colspan="12">Saldo Awal</td>
                        <td class="report-data align-right text-bold">Rp. <?= number_format($vendor->starting_balance ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data"> - </td>
                    </tr>
                    <?php if (count($vendor->transactions) > 0) : ?>
                        <?php foreach ($vendor->transactions as $transaction) : ?>
                            <tr>
                                <td class="report-data"><?= $transaction->fakturno != '' ? $transaction->noreg : '-'; ?></td>
                                <td class="report-data"><?= $transaction->noreg != '' ? $transaction->noreg : '-'; ?></td>
                                <td class="report-data"><?= $transaction->rekmed != '' ? $transaction->rekmed : '-'; ?></td>
                                <td class="report-data"><?= $transaction->dokument != 0 ? $transaction->dokument : 'No Doc.'; ?></td>
                                <td class="report-data" style="text-align: left;"><?= $transaction->namapas; ?></td>
                                <td class="report-data"><?= $transaction->cust_id; ?></td>
                                <td class="report-data"><?= $transaction->asal; ?></td>
                                <td class="report-data"><?= date('d-M-Y', strtotime($transaction->tglposting)); ?></td>
                                <td class="report-data"><?= date('d-M-Y', strtotime($transaction->tgljatuhtempo)); ?></td>
                                <td class="report-data" style="text-align: right;">Rp <?= number_format($transaction->jumlahhutang, 2, ',', '.'); ?></td>
                                <td class="report-data" style="text-align: right;">Rp <?= number_format($transaction->inacbg, 2, ',', '.'); ?></td>
                                <td class="report-data" style="text-align: right;">Rp <?= number_format($transaction->jumlahbayar, 2, ',', '.'); ?></td>
                                <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->new_balance = ($vendor->new_balance ?? $vendor->starting_balance ?? 0) - $transaction->jumlahbayar + $transaction->jumlahhutang + $transaction->inacbg, 2, ',', '.'); ?></td>
                                <td class="report-data"><?= $transaction->invoiceno != '' ? $transaction->invoiceno : '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class="report-data" colspan="12">Tidak ada transaksi selama periode ini.</td>
                            <td class="report-data"> - </td>
                            <td class="report-data"> - </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="report-data text-bold" colspan="12">Saldo Akhir</td>
                        <td class="report-data align-right text-bold">Rp. <?= number_format($vendor->new_balance ?? $vendor->starting_balance ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data"> - </td>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</body>

</html>