<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunjungan - Pcare Testing Center</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <h1 class="font-bold text-3xl text-center">P-Care Testing Center - Abiyosoft</h1>
    <div class="container">
        <div class="alert alert-danger">
            <p>
                <strong><i class="fa fa-exclamation-triangle fa-fw"></i>&nbsp; Perlu Diketahui</strong>
            </p>
            <br />
            <p>Sebelum melakukan bridging, lakukan pemeriksaan perawat dan dokter delebih dahulu</p>
        </div>
        <h4>
            <strong>P-Care Bridging System - Abiyosoft | KUNJUNGAN</strong>
        </h4>
        <hr stle="margin-bottom: 1rem;" />
        <form name="pcare_form" id="pcare_form">
            <input type="hidden" name="kdProviderPelayanan" id="kdProviderPelayanan" value="<?= $kdppk->koders ?>">
            <fieldset name="data_diri">
                <p style="font-weight: bold; font-size: 16px;">Data Diri Pasien</p>
                <hr stle="margin-bottom: 1rem;" />
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">Faskes</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="hidden" class="form-control" name="kodeRs" id="kodeRs" value="<?= $kdppk->koders ?>" readonly>
                        <input type="text" class="form-control" value="<?= "$kdppk->koders | $kdppk->namers" ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">No. Urut Registrasi</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" name="noReg" id="noReg" value="<?= $noreg ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">No. Kartu BPJS</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" name="noKartu" id="noKartu" value="<?= $data_peserta->noKartu ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">Nama Peserta</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="namaPeserta" value="<?= $data_peserta->nama ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">Status Peserta</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="status" value="<?= $data_peserta->ketAktif ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">Tanggal Lahir</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="tglLahir" value="<?= local_date($data_peserta->tglLahir, "dd MMMM YYYY") ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">Jenis Kelamin</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <?php $sex = $data_peserta->sex == "L" ? "Laki-Laki" : "Perempuan" ?>
                        <input type="text" class="form-control" id="sex" value="<?= "$sex ($data_peserta->sex)" ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">PPK Umum</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="ppkUmum" value="<?= "$kdppk->koders | $kdppk->namers" ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">No. HP</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="noHp" value="<?= $data_peserta->noHP ?>" readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 10px 3fr;">
                    <label class="form-label">No. Rekmed.</label>
                    <span>:</span>
                    <div class="" style="margin:0px !important">
                        <input type="text" class="form-control" id="rekmed" value="<?= $local_regist_data->rekmed ?>" readonly>
                    </div>
                </div>
            </fieldset>
            <fieldset name="hasil_pemeriksaan">
                <p style="font-weight: bold; font-size: 16px;">Hasil Pemeriksaan</p>
                <hr stle="margin-bottom: 1rem;" />
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="tglDaftar">Tanggal Kunjungan</label>
                    <input 
                        class="form-control" 
                        name="tglDaftar" 
                        id="tglDaftar" 
                        type="date" 
                        value="<?= isset($pcare_visit_data->tglDaftar) ? $pcare_visit_data->tglDaftar : date("Y-m-d") ?>"
                        <?= isset($pcare_visit_data->tglDaftar) ? "readonly" : "" ?>
                        >
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="tglPulang">Tanggal Pulang</label>
                    <input 
                        class="form-control" 
                        name="tglPulang" 
                        id="tglPulang" 
                        type="date" 
                        value="<?= isset($pcare_visit_data->tglPulang) ? $pcare_visit_data->tglPulang : date("Y-m-d") ?>"
                        <?= isset($pcare_visit_data->tglPulang) ? "readonly" : "" ?>
                        >
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="noKunjungan">Nomor Kunjungan</label>
                    <input class="form-control" name="noKunjungan" id="noKunjungan" type="text" value="<?= isset($pcare_visit_data) ? $pcare_visit_data->noKunjungan : "Otomatis" ?>" readonly>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="noUrut">Nomor P-Care Antri</label>
                    <input class="form-control" name="noUrut" id="noUrut" type="text" value="<?= $pcare_regist_data->noUrut ?>" readonly>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label">Jenis Kunjungan</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr;">
                        <label for="sakit" style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="kunjSakit" id="sakit" value="1"
                                style="transform: scale(1.3);" <?= $pcare_regist_data->kunjSakit == 1 ? "checked" : "" ?> >
                            Kunjungan Sakit
                        </label>
                        <label for="sehat" style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="kunjSakit" id="sehat" value="0"
                                style="transform: scale(1.3);" <?= $pcare_regist_data->kunjSakit == 0 ? "checked" : "" ?> >
                            Kunjungan Sehat
                        </label>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label">Perawatan</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr;">
                        <label for="rawat_jalan" style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="kdTkp" id="rawat_jalan" value="10"
                                style="transform: scale(1.3);" <?= $pcare_regist_data->kdTkp == 10 ? "checked" : "" ?> >
                            Rawat jalan
                        </label>
                        <label for="rawat_inap" style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="kdTkp" id="rawat_inap" value="20"
                                style="transform: scale(1.3);" <?= $pcare_regist_data->kdTkp == 20 ? "checked" : "" ?> >
                            Rawat Inap
                        </label>
                        <label for="promotif_preventif" style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="kdTkp" id="promotif_preventif" value="50"
                                style="transform: scale(1.3);" <?= $pcare_regist_data->kdTkp == 50 ? "checked" : "" ?> >
                            Promotif Preventif
                        </label>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="pcare_poli_dok">Poli Tujuan</label>
                    <div>
                        <select type="text" class="form-control" name="kdPoli" id="kdPoli"></select>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="keluhan">Keluhan Awal</label>
                    <textarea name="keluhan" id="keluhan" class="form-control" rows="3"><?= $pcare_regist_data->keluhan ?></textarea>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDiag1">Diagnosa utama</label>
                    <div style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="kdDiag1" 
                            id="kdDiag1" 
                            value="<?= isset($diagnosa[0]) ? trim($diagnosa[0]->code) : "" ?>"
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="<?= isset($diagnosa[0]) ? trim($diagnosa[0]->str) : "" ?>"
                            readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDiag2">Diagnosa 2 (jika ada)</label>
                    <div style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="kdDiag2" 
                            id="kdDiag2" 
                            value="<?= isset($diagnosa[1]) ? trim($diagnosa[1]->code) : "" ?>"
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="<?= isset($diagnosa[1]) ? trim($diagnosa[1]->str) : "" ?>"
                            readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDiag3">Diagnosa 3 (jika ada)</label>
                    <div style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="kdDiag3" 
                            id="kdDiag3" 
                            value="<?= isset($diagnosa[3]) ? trim($diagnosa[3]->code) : "" ?>"
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="<?= isset($diagnosa[3]) ? trim($diagnosa[3]->str) : "" ?>"
                            readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="terapi">Terapi Obat</label>
                    <textarea class="form-control" name="terapi" id="terapi" rows="2" placeholder="Terapi obat free text ..."><?= isset($pcare_visit_data->terapi) ? $pcare_visit_data->terapi : 0 ?></textarea>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="terapinon">Terapi Non Obat</label>
                    <textarea class="form-control" name="terapinon" id="terapinon" rows="2" placeholder="Terapi non-obat free text ..."><?= isset($pcare_visit_data->terapinon) ? $pcare_visit_data->terapinon : 0 ?></textarea>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdSadar">Kesadaran</label>
                    <select class="form-control" name="kdSadar" id="kdSadar">
                        <?php foreach ($kesadaran as $ks): ?>
                            <option value="<?= $ks->kdSadar ?>"><?= $ks->nmSadar ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="tinggiBadan">Tinggi Badan</label>
                    <div style="display: grid; grid-template-columns: 3fr 1fr 3fr; gap: 20px;">
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="tinggiBadan" id="tinggiBadan" type="number" value="<?= $local_mr->tinggibadan ?>">
                            cm
                        </div>
                        <label class="form-label" for="beratBadan">Berat Badan</label>
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="beratBadan" id="beratBadan" type="number" value="<?= $local_mr->beratbadan ?>">
                            Kg
                        </div>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="lingkarPerut">Lingkar Perut</label>
                    <div style="display: grid; grid-template-columns: 3fr 1fr 3fr; gap: 20px;">
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="lingkarPerut" id="lingkarPerut" type="number" value="<?= isset($pcare_visit_data->lingkarPerut) ? $pcare_visit_data->lingkarPerut : 0 ?>">
                            cm
                        </div>
                        <label class="form-label" for="imt">IMT</label>
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="imt" id="imt" type="number" readonly>
                            Kg/m2
                        </div>
                    </div>
                </div>
                <p style="font-weight: bold; font-size: 16px;">Tekanan Darah</p>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="sistole">Sistole</label>
                    <div style="display: grid; grid-template-columns: 3fr 1fr 3fr; gap: 20px;">
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="sistole" id="sistole" type="number" max="250" min="40"
                                placeholder="40-250" value="<?= $local_mr->tdarah ?>">
                            mm/Hg
                        </div>
                        <label class="form-label" for="diastole">Diastole</label>
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="diastole" id="diastole" type="number" max="180" min="30"
                                placeholder="30-180" value="<?= $local_mr->tdarah1 ?>">
                            mm/Hg
                        </div>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="respRate">Respiratory Rate</label>
                    <div style="display: grid; grid-template-columns: 3fr 1fr 3fr; gap: 20px;">
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="respRate" id="respRate" type="number" max="50" min="10"
                                placeholder="10-50" value="<?= $local_mr->nafas ?>">
                            /menit
                        </div>
                        <label class="form-label" for="heartRate">Heart Rate</label>
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" name="heartRate" id="heartRate" type="number" max="160" min="30"
                                placeholder="30-160" value="<?= $local_mr->nadi ?>">
                            bpm
                        </div>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="terapi">Pemeriksaan Fisik Lain (Jika ada)</label>
                    <textarea class="form-control" name="pemFisikLain" id="pemFisikLain" rows="2" placeholder="Free text ..."></textarea>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDokter">Tenaga Medis</label>
                    <select class="form-control" name="kdDokter" id="kdDokter">
                        <?php foreach ($dokter as $dk): ?>
                            <option value="<?= $dk->kdDokter ?>"><?= $dk->nmDokter ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="pcare_pelayan_non_kapitas">Pelayan Non-Kapitas</label>
                    <select class="form-control" id="pcare_pelayan_non_kapitas">
                        <?php foreach ($dokter as $dk): ?>
                            <option value="<?= $dk->kdDokter ?>"><?= $dk->nmDokter ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdStatusPulang">Status Pulang</label>
                    <select class="form-control" name="kdStatusPulang" id="kdStatusPulang"></select>
                </div>
            </fieldset>
            <fieldset class="d-none" name="rujuk-lanjut" id="rujuk-lanjut">
                <h4>
                    <strong>Rujukan Lanjut</strong>
                </h4>
                <hr stle="margin-bottom: 1rem;" />
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutTglEstimasiRujuk">Tgl. Rencana Rujuk</label>
                    <div id="icd_result">
                        <input 
                            type="date" 
                            class="form-control" 
                            name="rujukLanjutTglEstimasiRujuk" 
                            id="rujukLanjutTglEstimasiRujuk" 
                            readonly>
                    </div>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutKdPpk">Faskes Rujukan</label>
                    <div  style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutKdPpk" 
                            id="rujukLanjutKdPpk" 
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutNmPpk" 
                            id="rujukLanjutNmPpk" 
                            readonly>
                    </div>
                </div>
                <div class="mb-3" id="sub-spesialis-input" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutSubSpesialisKdSubSpesialis1">Sub-Spesialis</label>
                    <div  style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutSubSpesialisKdSubSpesialis1" 
                            id="rujukLanjutSubSpesialisKdSubSpesialis1" 
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutSubSpesialisNmSubSpesialis1" 
                            id="rujukLanjutSubSpesialisNmSubSpesialis1" 
                            readonly>
                    </div>
                </div>
                <div class="mb-3" id="sarana-input" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutSubSpesialisKdSarana">Sarana</label>
                    <div  style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutSubSpesialisKdSarana" 
                            id="rujukLanjutSubSpesialisKdSarana" 
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutSubSpesialisNmSarana" 
                            id="rujukLanjutSubSpesialisNmSarana" 
                            readonly>
                    </div>
                </div>
                <div class="mb-3 d-none attr-khusus" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutKhususKdKhusus">Khusus</label>
                    <div  style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutKhususKdKhusus" 
                            id="rujukLanjutKhususKdKhusus" 
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="rujukLanjutKhususNmKhusus" 
                            id="rujukLanjutKhususNmKhusus" 
                            readonly>
                    </div>
                </div>
                <div class="mb-3 d-none attr-khusus" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="rujukLanjutKhususCatatan">Catatan Rujukan Khusus</label>
                    <textarea class="form-control" name="rujukLanjutKhususCatatan" id="rujukLanjutKhususCatatan" rows="2" placeholder="Free text ..."></textarea>
                </div>
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDokter">TACC</label>
                    <div style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;">
                        <select class="form-control" name="kdTacc" id="kdTacc"></select>
                        <select class="form-control" name="alasanTacc" id="alasanTacc1"></select>
                        <select class="d-none" name="alasanTacc" id="alasanTacc2"></select>
                    </div>
                </div>
            </fieldset>
            <!-- <fieldset name="rujuk-lanjut" id="rujuk-horizontal">
                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                    <label class="form-label" for="kdDiag1">Diagnosa utama</label>
                    <div style="display: grid; grid-template-columns: 1fr 3fr;  gap: 10px;" id="icd_result">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="kdDiag1" 
                            id="kdDiag1" 
                            value="<?= isset($diagnosa[0]) ? trim($diagnosa[0]->code) : "" ?>"
                            readonly>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="<?= isset($diagnosa[0]) ? trim($diagnosa[0]->str) : "" ?>"
                            readonly>
                    </div>
                </div>
            </fieldset> -->
        </form>
        <div class="modal fade" id="form-rujukan" tabindex="-1" role="dialog" aria-labelledby="FormPencarianRujukan" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalTitle"><strong>Form Pencarian Rujukan</strong></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                <label class="form-label" for="tglEstRujuk">Tanggal Estimasi Rujuk</label>
                                <input type="date" class="form-control" name="tglEstRujuk" id="tglEstRujuk"
                                    value="<?= date("Y-m-d") ?>">
                            </div>
                            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                <label class="form-label" for="">Termasuk Penyakit Khusus?</label>
                                <select type="text" class="form-control" id="sarana_khusus">
                                    <option value="1">Ya</option>
                                    <option value="0" selected>Tidak</option>
                                </select>
                            </div>
                            <div class="mb-3 d-none" id="rujukan_khusus">
                                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                    <label class="form-label" for="khusus">Sarana Khusus</label>
                                    <select type="text" class="form-control" id="khusus" name="khusus">
                                        <?php foreach ($khusus as $khu): ?>
                                            <option value="<?= $khu->kdKhusus ?>"><?= $khu->nmKhusus ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="alert alert-warning d-none" role="alert" id="tha_hem">
                                    <h5 class="alert-heading"><strong>Rujukan Thalasemia/Hemofilia</strong></h5>
                                    <p style="font-size: 12px; margin-bottom: 5px;">Anda memilih rujukan Thalasemia/Hemofilia, pastikan Anda telah memilih sub-spesialis dengan benar. Adapun sub-spesialis yang bisa dipilih untuk rujukan Thalasemia/Hemofilia ada adalah:</p>
                                    <ol style="font-size: 12px">
                                        <li>PENYAKIT DALAM</li>
                                        <li>HEMATOLOGI - ONKOLOGI MEDIK</li>
                                        <li>ANAK</li>
                                        <li>ANAK HEMATOLOGI ONKOLOGI</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="mb-3 d-none" style="padding:15px;border:1px solid #ddd;border-radius:5px;margin-bottom:15px;"
                                id="rujukan_subspesialis">
                                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                    <label class="form-label spesialis" for="spesialis">Spesialis</label>
                                    <select type="text" class="form-control" name="spesialis" id="spesialis">
                                        <?php foreach ($spesialis as $sp): ?>
                                            <option value="<?= $sp->kdSpesialis ?>"><?= $sp->nmSpesialis ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                    <label class="form-label" for="sub_spesialis">Sub Spesialis</label>
                                    <select type="text" class="form-control" name="sub_spesialis" id="sub_spesialis"></select>
                                </div>
                                <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                                    <label class="form-label" for="sarana_rujuk">Sarana</label>
                                    <select type="text" class="form-control" name="sarana_rujuk" id="sarana_rujuk">
                                        <?php foreach ($sarana as $srn): ?>
                                            <option value="<?= $srn->kdSarana ?>"><?= $srn->nmSarana ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="title-white text-center">Aksi</th>
                                        <th class="title-white text-center">Kode PPK</th>
                                        <th class="title-white text-center">Nama PPK</th>
                                        <th class="title-white text-center">Alamat</th>
                                        <th class="title-white text-center">Jadwal</th>
                                        <th class="title-white text-center">Jarak</th>
                                        <th class="title-white text-center">Jml. Rujukan</th>
                                        <th class="title-white text-center">Kapasistas</th>
                                    </tr>
                                </thead>
                                <tbody id="daftar-ppk">
                                    <tr>
                                        <td colspan="8">Belum ada data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="ambil-rujukan"><i class="fa fa-refresh"></i> Ambil Data Rujukan</button>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div
            style="display: flex; gap: 20px; align-items: center; justify-content: space-between; max-width: 80%; margin: 10px auto;">
            <button class="btn btn-success" type="button" onclick="save_pcare()">
                <i class="fa fa-floppy-o"></i> Simpan
            </button>
            <button class="btn btn-danger" type="button">
                <i class="fa fa-undo"></i> Batal
            </button>
            <a id="link-obat" target="_blank" class="btn btn-success" type="button">
                <i class="fa fa-plus"></i> Tambah Obat
            </a>
            <a id="link-tindakan" target="_blank" class="btn btn-info" type="button">
                <i class="fa fa-plus"></i> Tambah Tindakan
            </a>
            <button class="btn btn-warning" type="button">
                <i class="fa fa-book"></i> Riwayat
            </button>
            <a href="<?= isset($pcare_visit_data->noKunjungan) ? base_url("test/cetak_rujukan/{$pcare_visit_data->noKunjungan}") : "#" ?>" target="_blank" class="btn btn-info" type="button">
                <i class="fa fa-hand-o-right"></i> Rujukan
            </a>
        </div>
        <p style="font-weight: bold; font-size: 16px; margin-top: 20px;">Hasil Bridging</p>
        <hr stle="margin-bottom: 1rem;" />
        <div style="width: 80%; margin: 10px auto; padding: 10px; border: 1px solid black;" id="bridging_result">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var poli            = <?= json_encode($poli) ?>;
        var visit_data      = <?= json_encode($pcare_visit_data ?? (object) []) ?>;
        var statusPulang    = <?= json_encode($status_pulang) ?>;
        var pcareRegistData = <?= json_encode($pcare_regist_data) ?>;
        var subSpesialis    = <?= json_encode($subspesialis) ?>;
        var sarana          = <?= json_encode($sarana) ?>;
        var khusus          = <?= json_encode($khusus) ?>;
        var icd_all         = <?= json_encode($icd_all) ?>;
        var daftarRujukan, tacc;

        $(document).ready(() => {
            if (visit_data.noKunjungan) {
                $("#link-obat").attr("href", "<?= base_url("test/obat_kunjungan") ?>/" + $("#noKunjungan").val())
                $("#link-tindakan").attr("href", "<?= base_url("test/tindakan_kunjungan") ?>/" + $("#noKunjungan").val())
            }
            // Get TACC
            $.ajax({
                url: "<?= base_url("api/pcare") ?>" + "/get_tacc",
                dataType: "json",
                success: (data) => {
                    tacc = data
                    $("#kdTacc").empty()

                    data.forEach((el) => {
                        $("#kdTacc").append(/*html*/`<option value="${el.kdTacc}">${el.kdTacc} - ${el.nmTacc}</option>`)
                    })

                    document.getElementById("kdTacc").addEventListener("change", function () {
                        var selectedTacc = tacc.find((el) => {
                            return el.kdTacc == this.value
                        })
                        $("#alasanTacc1").empty()

                        if (selectedTacc.alasanTacc) {
                            if (Array.isArray(selectedTacc.alasanTacc)) {
                                selectedTacc.alasanTacc.forEach((el) => {
                                    $("#alasanTacc1").append(/*html*/`<option value="${el}">${el}</option>`)
                                })
                                $("#alasanTacc2").addClass("d-none")
                                $("#alasanTacc1").removeClass("d-none")
                                $("#alasanTacc2").select2("destroy")
                                $("#alasanTacc2").empty()
                            }else {
                                $("#alasanTacc1").addClass("d-none")
                                $("#alasanTacc2").removeClass("d-none")
                                $("#alasanTacc2").select2({
                                    data: icd_all
                                })
                            }
                        }
                    })
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.log(textStatus)
                }
            })
            // Enable popover
            $(function () {
                $('[data-toggle="popover"]').popover()
            })

            // Melakukan filter poliklinik berdasarkan data pendaftaran
            var filteredPoli = poli.filter((el) => {
                return el.poliSakit == $("input[name='kunjSakit']").val()
            })

            $("#kdPoli").empty()
            filteredPoli.forEach((el) => {
                $("#kdPoli").append(/*html*/`<option value="${el.kdPoli}" ${el.kdPoli == pcareRegistData.kdPoli ? "selected" : ""} >${el.nmPoli}</option>`)
            })
            // End filter poli

            // Melakukan filter status pulang berdasarkan data pendaftaran
            var filteredStatus = statusPulang.filter((el) => {
                if ($("input[name='kdTkp']").val() == "10") {
                    return el.rawatJalan == 1
                }

                if ($("input[name='kdTkp']").val() == "20") {
                    return el.rawatInap == 1
                }
            })

            $("#kdStatusPulang").empty()
            filteredStatus.forEach((el) => {
                $("#kdStatusPulang").append(/*html*/`<option value="${el.kdStatusPulang}">${el.nmStatusPulang}</option>`)
            })
            // End filter status pulang

            // Kalkulasi Indeks Masa Tubuh
            calculate_imt()

            // Menambahkan event listener saat tinggi badan / berat badan berubah
            document.getElementById("tinggiBadan").addEventListener("change", () => {
                calculate_imt()
            })

            document.getElementById("beratBadan").addEventListener("change", () => {
                calculate_imt()
            })

            // Menambahkan event listener saat jenis kunjungan berubah
            document.getElementsByName("kunjSakit").forEach((elm) => {
                elm.addEventListener("change", function () {
                    var filtered = poli.filter((el) => {
                        return el.poliSakit == this.value
                    })

                    $("#kdPoli").empty()
                    filtered.forEach((el) => {
                        $("#kdPoli").append(/*html*/`<option value="${el.kdPoli}">${el.nmPoli}</option>`)
                    })
                })
            })
            
            // Menambahkan event listener saat kdTkp berubah
            document.getElementsByName("kdTkp").forEach((elm) => {
                elm.addEventListener("change", function () {
                    var filteredStatus = statusPulang.filter((el) => {
                        if (this.value == "10") {
                            return el.rawatJalan == 1
                        }

                        if (this.value == "20") {
                            return el.rawatInap == 1
                        }
                    })

                    $("#kdStatusPulang").empty()
                    filteredStatus.forEach((el) => {
                        $("#kdStatusPulang").append(/*html*/`<option value="${el.kdStatusPulang}">${el.nmStatusPulang}</option>`)
                    })
                })
            })

            // Menambahkan event listener saat status pulang berubah
            document.getElementById("kdStatusPulang").addEventListener("change", function () {
                $("#rujuk-lanjut").addClass("d-none")
                if (this.value == 4) {
                    prepare_form_rujukan()
                    $("#rujuk-lanjut").removeClass("d-none")
                }
            })

            // Menambakan listener untuk tombol ambil rujukan
            document.getElementById("ambil-rujukan").addEventListener("click", function () {
                get_rujukan()
            })
        })

        function calculate_imt () {
            var tinggiBadan = $("#tinggiBadan").val()
            var beratBadan  = $("#beratBadan").val()
            var imt = parseFloat(beratBadan) / Math.pow((parseFloat(tinggiBadan)/100), 2)

            $("#imt").val(imt.toFixed(2))
        }

        function prepare_form_rujukan (value) {
            $('#form-rujukan').modal('show')

            // Munculkan input rujukan subspesialis
            document.getElementById("rujukan_subspesialis").classList.remove("d-none")
            
            if ($("#sarana_khusus").val() == 1) {
                document.getElementById("rujukan_khusus").classList.remove("d-none")
                document.getElementById("rujukan_subspesialis").classList.add("d-none")

                // Inisialiasi form Thalasemia dan Hemofilia
                document.getElementById("tha_hem").classList.add("d-none")
                if ($("#khusus").val() == "THA" || $("#khusus").val() == "HEM") {
                    document.getElementById("tha_hem").classList.remove("d-none")
                    document.getElementById("rujukan_subspesialis").classList.remove("d-none")
                }
            }

            // Inisialisasi subspesialis
            var filteredSubSpesialis = subSpesialis.filter((el) => {
                return el.kdSpesialis == $("#spesialis").val()
            })

            $("#sub_spesialis").empty()
            filteredSubSpesialis.forEach((elm) => {
                $("#sub_spesialis").append(/*html*/`<option value="${elm.kdSubSpesialis}">${elm.nmSubSpesialis}</option>`)
            })

            // Menambahkan event listener saat sarana khusus berubah
            document.getElementById("sarana_khusus").addEventListener("change", function () {
                document.getElementById("rujukan_khusus").classList.add("d-none")
                document.getElementById("rujukan_subspesialis").classList.remove("d-none")
                if (this.value == 1) {
                    document.getElementById("rujukan_khusus").classList.remove("d-none")
                    document.getElementById("rujukan_subspesialis").classList.add("d-none")

                    // Inisialiasi form Thalasemia dan Hemofilia
                    document.getElementById("tha_hem").classList.add("d-none")
                    if ($("#khusus").val() == "THA" || $("#khusus").val() == "HEM") {
                        document.getElementById("tha_hem").classList.remove("d-none")
                        document.getElementById("rujukan_subspesialis").classList.remove("d-none")
                    }
                }
            })

            // Menambahkan event listener saat user memilih Thalasemia dan Hemofilia
            document.getElementById("khusus").addEventListener("change", function () {
                document.getElementById("tha_hem").classList.add("d-none")
                document.getElementById("rujukan_subspesialis").classList.add("d-none")
                if (this.value == "THA" || this.value == "HEM") {
                    document.getElementById("tha_hem").classList.remove("d-none")
                    document.getElementById("rujukan_subspesialis").classList.remove("d-none")
                }
            })

            // Menambahkan event listener saat user memilih kode spesialis
            document.getElementById("spesialis").addEventListener("change", function () {
                var filteredSubSpesialis = subSpesialis.filter((el) => {
                    return el.kdSpesialis == this.value
                })

                $("#sub_spesialis").empty()
                filteredSubSpesialis.forEach((elm) => {
                    $("#sub_spesialis").append(/*html*/`<option value="${elm.kdSubSpesialis}">${elm.nmSubSpesialis}</option>`)
                })
            })
        }

        function get_rujukan () {
            var url = "<?= base_url("api/pcare") ?>" + "/get_rujukan_subspesialis";

            if ($("#rujukan_khusus").val() == 1) url = "<?= base_url("api/pcare") ?>" + "/get_rujukan_khusus";

            $.ajax({
                url: url,
                dataType: "json",
                data: {
                    subspesialis: $("#sub_spesialis").val(),
                    kdKhusus: $("#khusus").val(),
                    sarana: $("#sarana_rujuk").val(),
                    tglEstRujuk: $("#tglEstRujuk").val(),
                    noKartu: $("#noKartu").val()
                },
                success: (data) => {
                    $("#daftar-ppk").empty()

                    if (data) {
                        daftarRujukan = data.list
                        data.list.forEach((el) => {
                        $("#daftar-ppk").append(/*html*/`<tr>
                                <td>
                                    <button class="btn btn-info" onclick="pilihPpk('${el.kdppk}')">Pilih</button>
                                </td>
                                <td>
                                    ${el.kdppk}
                                </td>
                                <td>
                                    ${el.nmppk}
                                </td>
                                <td>
                                    ${el.alamatPpk}
                                </td>
                                <td>
                                    ${el.jadwal}
                                </td>
                                <td>
                                    ${((el.distance)/1000).toFixed(2)} km
                                </td>
                                <td>
                                    ${el.jmlRujuk}
                                </td>
                                <td>
                                    ${el.kapasitas}
                                </td>
                            </tr>`)
                        })
                    }else {
                        $("#daftar-ppk").append(/*html*/`<tr>
                                <td colspan="8">
                                    Data yang diminta tidak ditemukan
                                </td>
                            </tr>`)
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.log(textStatus)
                }
            })
        }

        function pilihPpk(kdppk) {
            // Close modal
            $("#form-rujukan").modal("hide")

            // Append the selected item into main form

            // Mempersiapkan data rujukan

            // Cek termasuk rujukan khusus atau tidak
            var isKhusus = $("#sarana_khusus").val()

            // Mencari PPK Rujukan
            var ppk = daftarRujukan.find((el) => {
                return el.kdppk == kdppk
            })

            // Mencari info subspesialis
            var selectedSubSpesialis = subSpesialis.find((el) => {
                return el.kdSubSpesialis == $("#sub_spesialis").val()
            })

            // Mencari info sarana
            var selectedSarana = sarana.find((el) => {
                return el.kdSarana == $("#sarana_rujuk").val()
            })

            // Set info khusus null
            var selectedKhusus   = null;

            // Mengisi input estimasi rujuk
            $("#rujukLanjutTglEstimasiRujuk").val($("#tglEstRujuk").val())

            // Mengisi input kode PPK & nama PPK
            $("#rujukLanjutKdPpk").val(ppk.kdppk)
            $("#rujukLanjutNmPpk").val(`${ppk.nmppk} - ${ppk.alamatPpk} - ${ppk.telpPpk}`)

            // Mengisi input subspesialis
            $("#rujukLanjutSubSpesialisKdSubSpesialis1").val(selectedSubSpesialis.kdSubSpesialis)
            $("#rujukLanjutSubSpesialisNmSubSpesialis1").val(selectedSubSpesialis.nmSubSpesialis)

            // Mengisi input sarana
            $("#rujukLanjutSubSpesialisKdSarana").val(selectedSarana.kdSarana)
            $("#rujukLanjutSubSpesialisNmSarana").val(selectedSarana.nmSarana)

            // Jika rujukan khusus
            if (isKhusus == 1) {
                // Mencari info khusus
                selectedKhusus = khusus.find((el) => {
                    return el.kdKhusus == $("#khusus").val()
                })
                
                // Mengisi input untuk rujukan khusus
                $("#rujukLanjutKhususKdKhusus").val(selectedKhusus.kdKhusus)
                $("#rujukLanjutKhususNmKhusus").val(selectedKhusus.nmKhusus)

                // Rujukan khsusu tidak memerlukan sarana
                $("#rujukLanjutSubSpesialisKdSarana").val("")
                $("#rujukLanjutSubSpesialisNmSarana").val("")
                $("#sarana-input").addClass("d-none")

                // Tampilkan input untuk rujukan khusus
                $(".attr-khusus").removeClass("d-none")
            }else {
                // Jika bukan rujukan khusus maka fungsi berakhir
                return
            }

            // Jika rujukan khusus bukan Thalasemia atau Hemofilia, tidak memerlukan subspesialis
            if (selectedKhusus.kdKhusus != "THA" || selectedKhusus.kdKhusus != "HEM") {
                $("#rujukLanjutSubSpesialisKdSubSpesialis1").val("")
                $("#rujukLanjutSubSpesialisNmSubSpesialis1").val("")
                $("#sub-spesialis-input").addClass("d-none")
            }
        }

        function save_pcare() {
            // console.log($("#pcare_form").serialize())
            $.ajax({
                url: "<?= base_url("api/pcare/create_kunjungan") ?>",
                type: "post",
                dataType: "json",
                data: $("#pcare_form").serialize(),
                success: (data) => {
                    console.log(data)
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.log(jqXHR.responseJSON)
                }
            })
        }
    </script>
</body>

</html>