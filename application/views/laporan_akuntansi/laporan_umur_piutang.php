<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Umur Piutang</title>
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
        <h1>Laporan Umur Piutang</h1>
        <p id="description">Per <?= strftime('%e %B %Y', strtotime($input->todate)); ?></p>
        <p id="description">
            Untuk Sumber <?= $input->jenis; ?>
            (<?= $input->jenis == 'RAJAL' ? 'Rawat Jalan' : ($input->jenis == 'INAP' ? 'Rawat Inap' : ($input->jenis == 'JUAL' ? 'Penjualan' : 'Semua Jenis')); ?>)
        </p>
        <table class="table-laporan" style="display: block; margin: 0 auto; margin-top: 20px">
            <thead>
                <tr>
                    <th rowspan="2">Penjamin</th>
                    <th colspan="6">Rincian Umur Piutang</th>
                    <th rowspan="2">Total Piutang</th>
                </tr>
                <tr>
                    <th>Belum ditagihkan</th>
                    <th>Belum jatuh tempo</th>
                    <th>Kurang dari 30 hari</th>
                    <th>Antara 30 s/d 60 hari</th>
                    <th>Antara 60 s/d 90 hari</th>
                    <th>Lebih dari 90 hari</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data->vendor_list as $vendor) : ?>
                    <tr>
                        <td class="report-data" style="text-align: left;"><?= $vendor->cust_id; ?> | <?= $vendor->cust_nama; ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->unbilled ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->unexpired ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->less_than_30_days ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->between_30_and_60_days ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->between_60_and_90_days ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->more_than_90_days ?? 0, 2, ',', '.'); ?></td>
                        <td class="report-data" style="text-align: right;">Rp <?= number_format($vendor->total_ar ?? 0, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="report-data"><strong>Jumlah</strong></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_unbilled ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_unexpired ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_less_than_30_days ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_between_30_and_60_days ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_between_60_and_90_days ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_more_than_90_days ?? 0, 2, ',', '.'); ?></td>
                    <td class="report-data" style="text-align: right; font-weight: bold;">Rp <?= number_format($data->total_ar ?? 0, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>