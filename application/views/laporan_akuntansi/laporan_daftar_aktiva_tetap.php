<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Aktiva Tetap</title>
</head>
<body>
    <style>
        h1{
            font-size: 10px;
            text-align: center;
            margin: 0;
        }

        #description{
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
            font-size: 12px;
            padding: 4px;
            border: 1px solid black;
            font-weight: bold;
        }
    </style>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="6">
                    <img src="<?= base_url('assets/img/logo.png'); ?>"  width="70" height="70" />
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
                                <td style="font-size:9px;">Wa : <?= $kop['whatsapp']; ?>    Telp : <?= $kop['phone']; ?> </td>
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
        <h1>Laporan Daftar Aktiva Tetap</h1>
        <p id="description">s/d tanggal: <?= date('d F Y'); ?></p>
        <table id="table-laporan">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Asset</th>
                    <th>Tgl. Beli</th>
                    <th>Nilai Pembelian</th>
                    <th>Penyusutan</th>
                    <th>Nilai Buku</th>
                    <th>Lokasi</th>
                    <th>Tanggal Kalibrasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data->fa_groups as $row) : ?>
                    <tr>
                        <td class="report-data" colspan="8" style="font-size: 10px; background-color: lightcyan; text-align: left;"><strong><?= $row->groupname; ?></strong></td>
                    </tr>
                    <?php if (count($row->fa_list) > 0): ?>
                        <?php foreach ($row->fa_list as $fa): ?>
                            <tr>
                                <td class="report-data"><?= $fa->kodefix; ?></td>
                                <td class="report-data data-name" style="text-align: left;"><?= $fa->namafix; ?></td>
                                <td class="report-data"><?= date('d-M-Y', strtotime($fa->tglaku)); ?></td>
                                <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($fa->nilaiaktiva, 2, ',', '.'); ?></td>
                                <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($fa->depreciation->depreciation_total ?? 0, 2, ',', '.'); ?></td>
                                <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($fa->nilaiaktiva - ($fa->depreciation->depreciation_total ?? 0), 2, ',', '.'); ?></td>
                                <td class="report-data"><?= $fa->lokasi; ?></td>
                                <td class="report-data"><?= date('d-M-Y', strtotime($fa->tglkalibrasi)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="report-data" colspan="3"><strong>Sub total</strong></td>
                            <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($row->group_fa_total, 2, ',', '.'); ?></td>
                            <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($row->group_depreciation_total, 2, ',', '.'); ?></td>
                            <td class="report-data" style="text-align: right;"><?= 'Rp. ' . number_format($row->group_bv_total, 2, ',', '.'); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="report-data" colspan="8" style="text-align: left;">Kelompok ini tidak memiliki aktiva tetap.</td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table id="table-summary">
            <tbody>
                <tr>
                    <td class="summary-text">Total Aktiva Tetap yang Dimiliki</td>
                    <td class="summary-text">:</td>
                    <td class="summary-text" style="text-align: right;"><?= 'Rp. ' . number_format($data->fa_total, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class="summary-text">Total Akumulasi Penyusutan</td>
                    <td class="summary-text">:</td>
                    <td class="summary-text" style="text-align: right;"><?= 'Rp. ' . number_format($data->fa_depreciation_total, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class="summary-text">Nilai Buku Ativa Tetap yang dimiliki</td>
                    <td class="summary-text">:</td>
                    <td class="summary-text" style="text-align: right;"><?= 'Rp. ' . number_format($data->fa_bv_total, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>