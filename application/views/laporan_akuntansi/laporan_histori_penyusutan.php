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

        h2{
            font-size: 9px;
            text-align: left;
            padding: 3px;
            background-color: cyan;
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

        td{
            padding: 3px;
            font-size: 8px;
        }

        .barcode-wrapper{
            width: 120px;
        }

        .barcode-text{
            font-size: 8px;
            text-align: center;
            margin-top: -1px;
            width: 120px;
        }

        .property{
            width: 75px;
        }

        .value{
            width: 150px;
        }

        .report-data{
            font-size: 8px;
            padding: 3px;
            border: 1px solid black;
            text-align: center;
        }

        .table-full{
            width: 100%;
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
        <h1>Laporan Histori Penyusutan Aktiva Tetap</h1>
        <p id="description">s/d tanggal: <?= date('d F Y'); ?></p>
        <?php foreach ($data->fa_groups as $row) : ?>
            <h2><?= $row->groupname; ?></h2>
            <?php if (count($row->fa_list) > 0): ?>
                <?php foreach ($row->fa_list as $fa) : ?>
                    <div class="fa-wrapper">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="property">Kode Asset</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->kodefix; ?></td>
                                    <td class="property">Nama Asset</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->namafix; ?></td>
                                </tr>
                                <tr>
                                    <td class="property">Nomor Serial</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->serialno; ?></td>
                                    <td class="property">Tgl. Pembelian</td>
                                    <td>:</td>
                                    <td class="value"><?= date('d-M-Y', strtotime($fa->tglaku)); ?></td>
                                </tr>
                                <tr>
                                    <td class="property">Tgl. Pakai</td>
                                    <td>:</td>
                                    <td class="value"><?= date('d-M-Y', strtotime($fa->tglpakai)); ?></td>
                                    <td class="property">Tgl. Kalibrasi</td>
                                    <td>:</td>
                                    <td class="value"><?= date('d-M-Y', strtotime($fa->tglkalibrasi)); ?></td>
                                </tr>
                                <tr>
                                    <td class="property">Depr. Per Tahun</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->fixrate; ?> %</td>
                                    <td class="property">Umur Ekonomis</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->tahunsusut; ?> (<?= ($fa->tahunsusut * 12); ?> bulan)</td>
                                </tr>
                                <tr>
                                    <td class="property">Metode</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->metode; ?></td>
                                    <td class="property">Lokasi</td>
                                    <td>:</td>
                                    <td class="value"><?= $fa->lokasi; ?></td>
                                </tr>
                                <tr>
                                    <td class="property">Nilai Pembelian</td>
                                    <td>:</td>
                                    <td class="value"><?= 'Rp. ' . number_format($fa->nilaiaktiva, 2, ',', '.'); ?></td>
                                    <td class="property">Total Penyusutan</td>
                                    <td>:</td>
                                    <td class="value"><?= 'Rp. ' . number_format($fa->depreciation->depreciation_total ?? 0, 2, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td class="property">Nilai Buku</td>
                                    <td>:</td>
                                    <td class="value"><?= 'Rp. ' . number_format($fa->nilaiaktiva - ($fa->depreciation->depreciation_total ?? 0), 2, ',', '.'); ?></td>
                                    <td class="property">Barcode</td>
                                    <td>:</td>
                                    <td style="text-align: center;">
                                        <barcode code="<?= $fa->serialno; ?>" type="C128A" size="0.5" />
                                        <?= $fa->serialno; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table-full">
                            <thead>
                                <tr>
                                    <th>
                                        No.
                                    </th>
                                    <th>
                                        Bulan
                                    </th>
                                    <th>
                                        Tarif
                                    </th>
                                    <th>
                                        Nilai Penyusutan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($fa->depreciation)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach($fa->depreciation->depreciation_list as $depreciation): ?>
                                        <tr>
                                            <td class="report-data"><?= $no++; ?></td>
                                            <td class="report-data"><?= date('d-M-Y', strtotime($depreciation->tglsusut)); ?></td>
                                            <td class="report-data"><?= $depreciation->prosensusut; ?> %</td>
                                            <td class="report-data"><?= 'Rp. ' . number_format($depreciation->susutrp, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="report-data" colspan="4">Belum ada penyusutan</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="report-data" colspan="3"><strong>Subtotal</strong></td>
                                    <td class="report-data"><strong><?= 'Rp. ' . number_format($fa->depreciation->depreciation_total ?? 0, 2, ',', '.'); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-size: 8px; text-align: center;">Kelompok ini tidak memiliki daftar aktiva.</p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
