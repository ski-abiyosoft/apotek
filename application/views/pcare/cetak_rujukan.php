<style>
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/barcodes/JsBarcode.code39.min.js"></script>

<?php $kr = explode("-", $rujukan->nmKR); ?>

<div>
    <div id="header" style="padding: 10px">
        <table>
            <tbody>
                <tr>
                    <td style="width: 60%"><img src="<?= base_url("assets/img/BPJS_Kesehatan_logo.svg") ?>" alt="Logo BPJS Kesehatan" style="width: 200px"></td>
                    <td style="padding-left: 30px; font-size: 11px;">
                        <p style="font-weight: bold;">Kedeputian Wilayah </p>
                        <p style="font-weight: bold;">Wilayah </span></p>
                    </td>
                    <td style="padding-left: 30px; font-size: 11px;">
                        <p><span><?= $kr[0] ?></span></p>
                        <p><span><?= $kr[1] ?></span></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h3 style="text-align: center; padding: 5px 0; font-size: 20px;">Surat Rujukan FKTP</h3>
        <div style="margin: 0 auto; width: 720px; border: 2px solid black; 12px; padding: 10px;">
            <div style="margin: 0 auto; width: 640px; border: 2px solid black; padding: 20px 30px;">
                <table>
                    <tbody>
                        <tr>
                            <td style="font-size: 16px; width: 20%">No. Rujukan</td>
                            <td>:</td>
                            <td><?= $rujukan->noRujukan ?></td>
                            <td style="width: 50%"></td>
                        </tr>
                        <tr>
                            <td>FKTP</td>
                            <td>:</td>
                            <td><?= "$info_ppk->namers ($info_ppk->koders)" ?></td>
                            <td style="width: 50%">
                                <barcode code="<?= $rujukan->noRujukan ?>" type="C39" height="0.9" text="0" />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right: 60px;">Kabupaten / Kota</td>
                            <td>:</td>
                            <td>KOTA DENPASAR (0233)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding: 10px 40px;">
                <table style="font-size: 10px;">
                    <tbody>
                        <tr>
                            <td style="padding-right: 60px">Kepada Yth. TS DOKTER</td>
                            <td>:</td>
                            <td><?= $rujukan->nmPoli ?></td>
                        </tr>
                        <tr>
                            <td style="padding-right: 30px;">Di</td>
                            <td>:</td>
                            <td><?= $rujukan->nmPpk ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p style="padding: 0 40px; font-size: 10px;">Mohon pemeriksaan dan penanganan lebih lanjut pasien:</p>
            <div style="padding-left: 40px; width: 300px; float: left;">
                <table class="content-table2" style="font-size: 10px;">
                    <tbody>
                        <tr>
                            <td style="width: 100px">Nama</td>
                            <td>:</td>
                            <td><?= $rujukan->nmPst ?></td>
                        </tr>
                        <tr>
                            <td>No. Kartu BPJS</td>
                            <td>:</td>
                            <td><?= $rujukan->nokaPst ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: center;">Diagnosa</td>
                            <td style="vertical-align: center;">:</td>
                            <td><?= "$rujukan->nmDiag1 ($rujukan->kdDiag1)" ?></td>
                        </tr>
                        <?php if (isset($rujukan->kdDiag2)): ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><?= "$rujukan->nmDiag2 ($rujukan->kdDiag2)" ?></td>
                            </tr>
                        <?php endif ?>
                        <?php if (isset($rujukan->kdDiag3)): ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><?= "$rujukan->nmDiag3 ($rujukan->kdDiag3)" ?></td>
                            </tr>
                        <?php endif ?>
                        <tr>
                            <td>Telah Diberikan</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="width: 300px; float: right;">
                <table class="content-table2" style="font-size: 10px; width: 100%;">
                    <tbody>
                        <tr>
                            <td>Umur :</td>
                            <td><?= floatval(hitung_umur($rujukan->tglLahir, "tahun")) ?></td>
                            <td>Tahun :</td>
                            <td colspan="2"> <?= local_date($rujukan->tglLahir, "dd-MMM-yyyy") ?></td>
                        </tr>
                        <tr>
                            <td>Status :</td>
                            <td style="padding: 2px 5px; border: 1px solid black; text-align: center;"><?= $rujukan->pisa ?></td>
                            <td><?= $rujukan->ketPisa ?></td>
                            <td style="border: 1px solid black; text-align: center;"><?= $rujukan->sex ?></td>
                            <td>(L/P)</td>
                        </tr>
                        <tr>
                            <td>Catatan:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="float: none; clear: both;"></div>
            <div style="padding-left: 40px; margin-top: 20px;">
                <p style="font-size: 10px;">Atas bantuannya, diucapkan terima kasih.</p>
            </div>
            <div style="width: 400px; float: left;">
                <table style="font-size: 10px; width: 400px;">
                    <tbody>
                        <tr>
                            <td style="width: 150px">Tgl. Rencana Berkunjung</td>
                            <td>:</td>
                            <td><?= local_date($rujukan->tglEstRujuk, "dd-MMM-yyy") ?></td>
                        </tr>
                        <tr>
                            <td>Jadwal Praktik</td>
                            <td>:</td>
                            <td><?= $rujukan->jadwal ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Surat rujukan berlaku 1[satu] kali kunjungan, berlaku sampai dengan: <?= local_date($rujukan->tglAkhirRujuk, "dd-MMM-yyyy") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="width: 250px; float: right; margin-top: -30px">
                <table style="font-size: 10px; width: 250px;">
                    <tbody>
                        <tr>
                            <td style="text-align: center">Teman Sejawat,</td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><?= local_date(date("Y-m-d"), "dd MMMM yyyy") ?></td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 30px">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><?= $rujukan->nmDokter ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="float: none; clear: both;"></div>
        </div>
    </div>
    <div>
        <div style="margin: -1px auto; width: 720px; border: 2px solid black; padding: 10px;">
            <h3 style="text-align: center; padding: 10px 0; text-decoration: underline; font-size: 14px;">SURAT RUJUKAN BALIK</h3>
            <div style="padding: 0 40px; margin-bottom: 20px">
                <table style="font-size: 10px;">
                    <tbody>
                        <tr>
                            <td style="padding-right: 60px">Teman Sejawat Yth.</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 30px;">Mohon kontrol selanjutnya penderita:</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding: 0 60px;">
                <table style="font-size: 10px;">
                    <tbody>
                        <tr>
                            <td style="padding: 5px 0">Nama</td>
                            <td>:</td>
                            <td><?= $rujukan->nmPst ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0">Diagnosa</td>
                            <td>:</td>
                            <td><?= str_repeat(".", 80) ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0">Terapi</td>
                            <td>:</td>
                            <td><?= str_repeat(".", 80) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding: 0 40px;">
                <p style="font-size: 10px">Tindak lanjut yang dianjurkan</p>
            </div>
            <div style="padding: 0 60px; width: 600px">
                <table style="font-size: 10px; width: 100%; border-spacing: 4px;">
                    <tbody>
                        <tr>
                            <td style="width: 5%; padding: 10px 10px; border: 1px solid black;"></td>
                            <td style="width: 45%; vertical-align: middle;">
                                Pengobatan dengan obat-obatan: 
                            </td>
                            <td style="width: 5%; padding: 5px 10px; border: 1px solid black;"></td>
                            <td style="width: 45%; vertical-align: middle">
                                Perlu rawat inap
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 5%;"></td>
                            <td><?= str_repeat(".", 90) ?></td>
                            
                        </tr>
                        <tr>
                            <td style="width: 5%; padding: 10px 10px; border: 1px solid black;"></td>
                            <td style="width: 45%; vertical-align: middle;">
                                Kontrol kembali ke RS tanggal: <?= str_repeat(".", 35) ?> 
                            </td>
                            <td style="width: 5%; padding: 10px 10px; border: 1px solid black;"></td>
                            <td style="width: 45%; vertical-align: middle;">
                                Konsultasi Selesai
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 5%; padding: 10px 10px; border: 1px solid black;"></td>
                            <td style="width: 45%; vertical-align: middle;">
                                Lain-lain: <?= str_repeat(".", 73) ?> 
                            </td>
                            <td colspan="2">
                                <?= str_repeat(".", 30) ?>, Tgl. <?= str_repeat(".", 50) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="width: 300px; float: left;">
                
            </div>
            <div style="width: 300px; float: right;">
                <p style="text-align: center; margin-bottom: 50px; font-size: 10px">Dokter RS</p>
                <p style="text-align: center; font-size: 10px">(<?= str_repeat(".", 50) ?>)</p>
            </div>
            <div style="float: none; clear: both;">
                <p style="font-size: 8px; text-align: right;">Dicetak: <?= local_date(date("Y-m-d"), "dd MMMM yyyy") ?> - Abiyosoft P-Care Bridging System</p>
            </div>
        </div>
    </div>
</div>

<script>
    JsBarcode("#barcode", "<?= $rujukan->noRujukan ?>", {
        displayValue: false,
        width: 0.78,
        height: 30
    })
</script>