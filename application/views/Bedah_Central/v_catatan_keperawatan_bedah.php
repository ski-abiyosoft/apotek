    <?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        .sub-title {font-size:14px;font-weight:bold;display:block}
        #myBtnContainer {margin-bottom:20px}
        .filterBoxtitle {font-size:18px;font-weight:bold;margin-bottom:10px !important;display:block}
        .filterBox {display:none}
        .filterBoxshow {display:block}
    </style>

    <?= $item_cpo ?>

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">&nbsp;<?= $unit ?>&nbsp;</span>&nbsp;-
                &nbsp;<span class="title-web"><?=$menu;?> <small> <?= $submenu;?></small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard"
                        class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="/<?= $link ?>" class="title-white"><?=$menu;?> </a></a>&nbsp;<i class="fa fa-angle-right"
                        style="color:#fff"></i></li>
                <li><a href="#" class="title-white"><?=$submenu;?> </a></a></li>
            </ul>
        </div>
    </div>

    <div class="portlet">
        <div class="portlet-title">
            <div class="caption"><?= strtoupper($submenu) ?></div>
        </div>
    </div>

    <div class="row" style="margin-bottom:20px">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered" style="width:100%;margin:auto">
                    <tbody>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">No Rekam Medis</td>
                            <td style="width:35%"><?= $jadwal->rekmed ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Tanggal Lahir</td>
                            <td style="width:35%"><?= $jadwal->tgllahir ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Nama</td>
                            <td style="width:35%"><?= $jadwal->namapas ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Alamat</td>
                            <td style="width:35%"><?= $jadwal->alamat ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Jenis Kelamin</td>
                            <td style="width:35%"><?= ($jadwal->jkel == "W")? "Wanita" : "Pria" ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%"></td>
                            <td style="width:35%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="frmckb">   
        <?php $no = $no1 = $no2 = $no3 = $no4 = $no5 = $no6 = 1; ?>
        <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
        <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
        <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">

        <div class="row form-horizontal" style="margin-bottom:20px">
            <label class="col-sm-2" style="margin-top:5px">Tanggal operasi</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="tgloperasi" value="<?= date("Y-m-d", strtotime($jadwal->tgloperasi)) ?>" readonly>
            </div>
        </div>
        <div class="row form-horizontal" style="margin-bottom:20px">
            <label class="col-sm-2" style="margin-top:5px">Nama Dokter Bedah</label>
            <div class="col-sm-6">
                <select type="text" id="droperator" name="droperator" class="form-control selectpicker" data-live-search="true"
                    data-width="100%" onkeypress="return tabE(this,event)"
                    style='color:#222 !important'>
                    <option value="">--- Pilih Data ---</option>
                    <?php
                        foreach($dokter as $dkey => $dval):
                            if($statusckb == "done"){
                                $droperatorhead = $datackb->droperator;
                            } else {
                                $droperatorhead = $jadwal->droperator;
                            }
                            if($dval->kodokter == $droperatorhead):
                    ?>
                        <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                    <?php else: ?>
                        <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                    <?php 
                            endif;
                        endforeach; 
                    ?>
                </select>
            </div>
        </div>
        <div class="row form-horizontal" style="margin-bottom:20px">
            <label class="col-sm-2" style="margin-top:5px">Nama Dokter Anestesi</label>
            <div class="col-sm-6">
                <select type="text" id="dranestesi" name="dranestesi" class="form-control selectpicker" data-live-search="true"
                    data-width="100%" onkeypress="return tabE(this,event)"
                    style='color:#222 !important'>
                    <option value="">--- Pilih Data ---</option>
                    <?php
                        foreach($dokter as $dkey => $dval):
                            if($statusckb == "done"){
                                $dranestesi = $datackb->dranestesi;
                            } else {
                                $dranestesi = $jadwal->dranestasi;
                            }
                            if($dval->kodokter == $dranestesi):
                    ?>
                        <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                    <?php else: ?>
                        <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                    <?php 
                            endif;
                        endforeach; 
                    ?>
                </select>
            </div>
        </div>
        <div class="row form-horizontal" style="margin-bottom:20px">
            <label class="col-sm-2" style="margin-top:5px">Jenis Anestesi</label>
            <div class="col-sm-6">
                <select type="text" class="form-control" name="jenis_anestesi">
                    <option>--- Pilih Data ---</option>
                    <option value="Umum" <?= isset($datackb->jenis_anestesi)? ($datackb->jenis_anestesi == "Umum")? "selected" : "" : ($jenisan == "Umum")? "selected" : "" ?>>Umum</option>
                    <option value="Lokal" <?= isset($datackb->jenis_anestesi)? ($datackb->jenis_anestesi == "Lokal")? "selected" : "" : ($jenisan == "Lokal")? "selected" : "" ?>>Lokal</option>
                    <option value="Topikal" <?= isset($datackb->jenis_anestesi)? ($datackb->jenis_anestesi == "Topikal")? "selected" : "" : ($jenisan == "Topikal")? "selected" : "" ?>>Topikal</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">
                        <div id="myBtnContainer">
                            <div class="filterBox 1">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="2" style="width:50%"><b class="sub-title">Aktivitas</b></td>
                                                <td class="text-center" colspan="2" style="width:50%"><b class="sub-title">Pelaksanaan</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"><b class="sub-title">PRE OPERASI<br />A. PERSIAPAN DOKUMEN</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:55%">Cek List Persiapan Pasien Pre-Operasi dari Poliklinik</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen1" value="1" <?= isset($datackb->persiapandokumen1)? ($datackb->persiapandokumen1 == "1")? "checked" : "" : ($item_cpo == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen1" value="2" <?= isset($datackb->persiapandokumen1)? ($datackb->persiapandokumen1 == "2")? "checked" : "" : ($item_cpo == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:55%">Persetujuan Tindakan Dokter (Pemberian Informasi)</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen2" value="1" <?= isset($datackb->persiapandokumen2)? ($datackb->persiapandokumen2 == "1")? "checked" : "" : ($item_ptd == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen2" value="2" <?= isset($datackb->persiapandokumen2)? ($datackb->persiapandokumen2 == "2")? "checked" : "" : ($item_ptd == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:95%" colspan="3">Untuk Katarak</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Biometri</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen4" value="1" <?= isset($datackb->persiapandokumen4)? ($datackb->persiapandokumen4 == "1")? "checked" : "" : ($biometri == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen4" value="2" <?= isset($datackb->persiapandokumen4)? ($datackb->persiapandokumen4 == "2")? "checked" : "" : ($biometri == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Sel Endothel</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen5" value="1" <?= isset($datackb->persiapandokumen5)? ($datackb->persiapandokumen5 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen5" value="2" <?= isset($datackb->persiapandokumen5)? ($datackb->persiapandokumen5 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Retinometri</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen6" value="1" <?= isset($datackb->persiapandokumen6)? ($datackb->persiapandokumen6 == "1")? "checked" : "" : ($retinomt == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen6" value="2" <?= isset($datackb->persiapandokumen6)? ($datackb->persiapandokumen6 == "2")? "checked" : "" : ($retinomt == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:55%">Hasil Laboratorium</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen7" value="1" <?= isset($datackb->persiapandokumen7)? ($datackb->persiapandokumen7 == "1")? "checked" : "" : ($laborato == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen7" value="2" <?= isset($datackb->persiapandokumen7)? ($datackb->persiapandokumen7 == "2")? "checked" : "" : ($laborato == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:95%" colspan="3">Untuk Bius Umum dan Hasil</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Rontgen</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen9" value="1" <?= isset($datackb->persiapandokumen9)? ($datackb->persiapandokumen9 == "1")? "checked" : "" : ($biusumum == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen9" value="2" <?= isset($datackb->persiapandokumen9)? ($datackb->persiapandokumen9 == "2")? "checked" : "" : ($biusumum == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- EKG</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen10" value="1" <?= isset($datackb->persiapandokumen10)? ($datackb->persiapandokumen10 == "1")? "checked" : "" : ($biusumum == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen10" value="2" <?= isset($datackb->persiapandokumen10)? ($datackb->persiapandokumen10 == "2")? "checked" : "" : ($biusumum == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Hasil Konsul Dokter Anak/Penyakit Dalam</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen11" value="1" <?= isset($datackb->persiapandokumen11)? ($datackb->persiapandokumen11 == "1")? "checked" : "" : ($biusumum == "1")? "checked" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen11" value="2" <?= isset($datackb->persiapandokumen11)? ($datackb->persiapandokumen11 == "2")? "checked" : "" : ($biusumum == "0")? "checked" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no++ ?></td>
                                                <td style="width:55%">Persetujuan Tindakan Dokter</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen12" value="1" <?= isset($datackb->persiapandokumen12)? ($datackb->persiapandokumen12 == "1")? "checked" : "" : ($item_ptd == "1")? "checked" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapandokumen12" value="2" <?= isset($datackb->persiapandokumen12)? ($datackb->persiapandokumen12 == "2")? "checked" : "" : ($item_ptd == "0")? "checked" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4"><b class="sub-title">B. PERSIAPAN PASIEN</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Melepaskan Perhiasan, Cuci Muka dan Ganti Pakaian</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien1" value="1" <?= isset($datackb->persiapanpasien1)? ($datackb->persiapanpasien1 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien1" value="2" <?= isset($datackb->persiapanpasien1)? ($datackb->persiapanpasien1 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Memasang Gelang Identitas Pasien</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien2" value="1" <?= isset($datackb->persiapanpasien2)? ($datackb->persiapanpasien2 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien2" value="2" <?= isset($datackb->persiapanpasien2)? ($datackb->persiapanpasien2 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Cek Peradangan Disekitar Mata dan Muka</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien3" value="1" <?= isset($datackb->persiapanpasien3)? ($datackb->persiapanpasien3 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien3" value="2" <?= isset($datackb->persiapanpasien3)? ($datackb->persiapanpasien3 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tanda Mata Yang Akan Dioperasi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien4" value="1" <?= isset($datackb->persiapanpasien4)? ($datackb->persiapanpasien4 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien4" value="2" <?= isset($datackb->persiapanpasien4)? ($datackb->persiapanpasien4 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tanda-Tanda Vital :</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien5" value="1" <?= isset($datackb->persiapanpasien5)? ($datackb->persiapanpasien5 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien5" value="2" <?= isset($datackb->persiapanpasien5)? ($datackb->persiapanpasien5 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Tekanan Darah</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="tekanandarah1" value="<?= isset($datackb->tekanandarah1)? $datackb->tekanandarah1 : "" ?>">
                                                        <span class="input-group-addon">mmHg</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Nadi</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="nadi1" value="<?= isset($datackb->nadi1)? $datackb->nadi1 : "" ?>">
                                                        <span class="input-group-addon">x/menit</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Suhu</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="suhu1" value="<?= isset($datackb->suhu1)? $datackb->suhu1 : "" ?>">
                                                        <span class="input-group-addon">°C</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Pernapasan</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="pernapasan1" value="<?= isset($datackb->pernapasan1)? $datackb->pernapasan1 : "" ?>">
                                                        <span class="input-group-addon">x/menit</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Skala Nyeri</td>
                                                <td style="width:40%" colspan="2">
                                                    <input id="msg" type="text" class="form-control" name="skalanyeri1" value="<?= isset($datackb->skalanyeri1)? $datackb->skalanyeri1 : "" ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Riwayat Alergi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien6" value="1" <?= isset($datackb->persiapanpasien6)? ($datackb->persiapanpasien6 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien6" value="2" <?= isset($datackb->persiapanpasien6)? ($datackb->persiapanpasien6 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Sebutkan Bila Ada Riwayat Alergi</td>
                                                <td style="width:40%" colspan="2">
                                                    <input id="msg" type="text" class="form-control" name="riwayatalergi" value="<?= isset($datackb->riwayatalergi)? $datackb->riwayatalergi : "" ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:95%" colspan="3">Riwayat Penyakit</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- DM</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien7" value="1" <?= isset($datackb->persiapanpasien7)? ($datackb->persiapanpasien7 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien7" value="2" <?= isset($datackb->persiapanpasien7)? ($datackb->persiapanpasien7 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Hypertensi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien8" value="1" <?= isset($datackb->persiapanpasien8)? ($datackb->persiapanpasien8 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien8" value="2" <?= isset($datackb->persiapanpasien8)? ($datackb->persiapanpasien8 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Jantung</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien9" value="1" <?= isset($datackb->persiapanpasien9)? ($datackb->persiapanpasien9 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien9" value="2" <?= isset($datackb->persiapanpasien9)? ($datackb->persiapanpasien9 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Hepatitis</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien10" value="1" <?= isset($datackb->persiapanpasien10)? ($datackb->persiapanpasien10 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien10" value="2" <?= isset($datackb->persiapanpasien10)? ($datackb->persiapanpasien10 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Lainnya</td>
                                                <td style="width:40%" colspan="2">
                                                    <input id="msg" type="text" class="form-control" name="riwayatpenyakit" value="<?= isset($datackb->riwayatpenyakit)? $datackb->riwayatpenyakit : "" ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tetes Pantocain</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien11" value="1" <?= isset($datackb->persiapanpasien11)? ($datackb->persiapanpasien11 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien11" value="2" <?= isset($datackb->persiapanpasien11)? ($datackb->persiapanpasien11 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:95%" colspan="3">Tetes Antibiotik Setiap 15 Menit (Untuk Operasi Intra Okular)</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 0' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik01" value="<?= isset($datackb->tetesantibiotik01)? $datackb->tetesantibiotik01 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien12" value="1" <?= isset($datackb->persiapanpasien12)? ($datackb->persiapanpasien12 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien12" value="2" <?= isset($datackb->persiapanpasien12)? ($datackb->persiapanpasien12 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 15' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik151" value="<?= isset($datackb->tetesantibiotik01)? $datackb->tetesantibiotik01 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien13" value="1" <?= isset($datackb->persiapanpasien13)? ($datackb->persiapanpasien13 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien13" value="2" <?= isset($datackb->persiapanpasien13)? ($datackb->persiapanpasien13 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 30' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik301" value="<?= isset($datackb->tetesantibiotik301)? $datackb->tetesantibiotik301 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien14" value="1" <?= isset($datackb->persiapanpasien14)? ($datackb->persiapanpasien14 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien14" value="2" <?= isset($datackb->persiapanpasien14)? ($datackb->persiapanpasien14 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 45' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik451" value="<?= isset($datackb->tetesantibiotik451)? $datackb->tetesantibiotik451 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien15" value="1" <?= isset($datackb->persiapanpasien15)? ($datackb->persiapanpasien15 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien15" value="2" <?= isset($datackb->persiapanpasien15)? ($datackb->persiapanpasien15 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 60' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik601" value="<?= isset($datackb->tetesantibiotik601)? $datackb->tetesantibiotik601 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien16" value="1" <?= isset($datackb->persiapanpasien16)? ($datackb->persiapanpasien16 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien16" value="2" <?= isset($datackb->persiapanpasien16)? ($datackb->persiapanpasien16 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Cukur Bulu Mata (Untuk Operasi Retina & Karatoplasty)</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien17" value="1" <?= isset($datackb->persiapanpasien17)? ($datackb->persiapanpasien17 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien17" value="2" <?= isset($datackb->persiapanpasien17)? ($datackb->persiapanpasien17 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tetes Mydriacyl 1% ke 1" Diberikan Jam&emsp;<input type="time" class="form-control" name="mydriacyl11" value="<?= isset($datackb->mydriacyl11)? $datackb->mydriacyl11 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien18" value="1" <?= isset($datackb->persiapanpasien18)? ($datackb->persiapanpasien18 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien18" value="2" <?= isset($datackb->persiapanpasien18)? ($datackb->persiapanpasien18 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tetes Mydriacyl 1% ke 2" Diberikan Jam&emsp;<input type="time" class="form-control" name="mydriacyl21" value="<?= isset($datackb->mydriacyl21)? $datackb->mydriacyl21 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien19" value="1" <?= isset($datackb->persiapanpasien19)? ($datackb->persiapanpasien19 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien19" value="2" <?= isset($datackb->persiapanpasien19)? ($datackb->persiapanpasien19 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tetes Mydriacyl 1% ke 3" Diberikan Jam&emsp;<input type="time" class="form-control" name="mydriacyl31" value="<?= isset($datackb->mydriacyl31)? $datackb->mydriacyl31 : date("H:i:s") ?>" style="padding:0px;height:20px;width:30%;display:inline-block"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien20" value="1" <?= isset($datackb->persiapanpasien20)? ($datackb->persiapanpasien20 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien20" value="2" <?= isset($datackb->persiapanpasien20)? ($datackb->persiapanpasien20 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Tetes Xyfocaine 2%</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien21" value="1" <?= isset($datackb->persiapanpasien21)? ($datackb->persiapanpasien21 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien21" value="2" <?= isset($datackb->persiapanpasien21)? ($datackb->persiapanpasien21 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">Desinfeksi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien22" value="1" <?= isset($datackb->persiapanpasien22)? ($datackb->persiapanpasien22 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien22" value="2" <?= isset($datackb->persiapanpasien22)? ($datackb->persiapanpasien22 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no1++ ?></td>
                                                <td style="width:55%">
                                                    <p>Intruksi Khusus</p>
                                                    <textarea style="resize:none;width:50%" class="form-control" name="persiapanpasien_intruksi" placeholder="Kosongkan bila tidak ada"><?= isset($datackb->persiapanpasien_intruksi)? $datackb->persiapanpasien_intruksi : "" ?></textarea>
                                                </td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien23" value="1" <?= isset($datackb->persiapanpasien23)? ($datackb->persiapanpasien23 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="persiapanpasien23" value="2" <?= isset($datackb->persiapanpasien23)? ($datackb->persiapanpasien23 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4"><b class="sub-title">C. PERSIAPAN TAMABHAN UNTUK ANESTESI LOKAL</b></td>
                                            </tr>
                                            <td style="width:5%" class="text-center"><?= $no2++ ?></td>
                                                <td style="width:55%">
                                                    <p>Suntik Anestesi Oleh Dokter & Jam Berapa</p>
                                                    <p>
                                                        <select type="text" id="dranestesi1" name="dranestesi1" class="form-control selectpicker" data-live-search="true"
                                                            data-width="50%" onkeypress="return tabE(this,event)"
                                                            style='color:#222 !important'>
                                                            <option value="">--- Pilih Data ---</option>
                                                            <?php
                                                                foreach($dokter as $dkey => $dval):
                                                                    if($statusckb == "done"){
                                                                        $droperatorhead = $datackb->dranestesi1;
                                                                    } else {
                                                                        $droperatorhead = $jadwal->dranestasi;
                                                                    }
                                                                    if($dval->kodokter == $droperatorhead):
                                                            ?>
                                                                <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                            <?php else: ?>
                                                                <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                            <?php 
                                                                    endif;
                                                                endforeach; 
                                                            ?>
                                                        </select>
                                                    </p>
                                                    <input type="time" class="form-control" name="jamsuntikanestesi" value="<?= isset($datackb->jamsuntikanestesi)? $datackb->jamsuntikanestesi : date("H:i:s") ?>" style="width:50%">
                                                </td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesilokal1" value="1" <?= isset($datackb->anestesilokal1)? ($datackb->anestesilokal1 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesilokal1" value="2" <?= isset($datackb->anestesilokal1)? ($datackb->anestesilokal1 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no2++ ?></td>
                                                <td style="width:55%">Check Hasil Suntikan Anestesi Lokal</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesilokal2" value="1" <?= isset($datackb->anestesilokal2)? ($datackb->anestesilokal2 == "1")? "checked" : "" : "" ?>>&nbsp; Baik</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesilokal2" value="2" <?= isset($datackb->anestesilokal2)? ($datackb->anestesilokal2 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <button type="button" class="btn green btn-md pull-right" onclick="filterSelection('2')">Selanjutnya &nbsp;<i class="fa fa-angle-right fa-fw"></i></button>
                                <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                                <br />
                            </div>

                            <div class="filterBox 2">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="2" style="width:50%"><b class="sub-title">Aktivitas</b></td>
                                                <td class="text-center" colspan="2" style="width:50%"><b class="sub-title">Pelaksanaan</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"><b class="sub-title">D. PERSIAPAN TAMBAHAN UNTUK ANESTESI UMUM</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no3++ ?></td>
                                                <td style="width:55%">Timbangan Berat Badan&emsp;<input type="text" class="form-control" name="berat_badan" value="<?= isset($datackb->berat_badan)? $datackb->berat_badan : "" ?>" style="padding:0px;height:20px;width:30%;display:inline-block" placeholder="Kg"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum1" value="1" <?= isset($datackb->anestesiumum1)? ($datackb->anestesiumum1 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum1" value="2" <?= isset($datackb->anestesiumum1)? ($datackb->anestesiumum1 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no3++ ?></td>
                                                <td style="width:55%">Gigi Palsu</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum2" value="1" <?= isset($datackb->anestesiumum2)? ($datackb->anestesiumum2 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum2" value="2" <?= isset($datackb->anestesiumum2)? ($datackb->anestesiumum2 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:55%">- Bila Ada Dilepas</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum3" value="1" <?= isset($datackb->anestesiumum3)? ($datackb->anestesiumum3 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum3" value="2" <?= isset($datackb->anestesiumum3)? ($datackb->anestesiumum3 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no3++ ?></td>
                                                <td style="width:55%">Melepaskan Pakaian Dalam Atas</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum4" value="1" <?= isset($datackb->anestesiumum4)? ($datackb->anestesiumum4 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum4" value="2" <?= isset($datackb->anestesiumum4)? ($datackb->anestesiumum4 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no3++ ?></td>
                                                <td style="width:95%" colspan="3">Puasa Mulai Jam&emsp;<input type="time" class="form-control" name="jampuasa" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->jampuasa)? $datackb->jampuasa : date("H:i:s") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no3++ ?></td>
                                                <td style="width:55%">Cat Kuku</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum5" value="1" <?= isset($datackb->anestesiumum5)? ($datackb->anestesiumum5 == "1")? "checked" : "" : "" ?>>&nbsp; Ada</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum5" value="2" <?= isset($datackb->anestesiumum5)? ($datackb->anestesiumum5 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:55%">- Bila Ada Dibersihkan</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum6" value="1" <?= isset($datackb->anestesiumum6)? ($datackb->anestesiumum6 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="anestesiumum6" value="2" <?= isset($datackb->anestesiumum6)? ($datackb->anestesiumum6 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4"><b class="sub-title">E. PERSIAPAN TAMBAHAN BILA MENGGUNAKAN IOL</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no4++ ?></td>
                                                <td style="width:55%">Dokter Menentukan Jenis Power IOL</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="iol1" value="1" <?= isset($datackb->iol1)? ($datackb->iol1 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="iol1" value="2" <?= isset($datackb->iol1)? ($datackb->iol1 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no4++ ?></td>
                                                <td style="width:55%">Dokter Memastikan Kembali Sisi Mata Yang Akan Dioperasi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="iol2" value="1" <?= isset($datackb->iol2)? ($datackb->iol2 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="iol2" value="2" <?= isset($datackb->iol2)? ($datackb->iol2 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4"><b class="sub-title">F. INTRA OPERASI</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Menyambut Pasien</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi1" value="1" <?= isset($datackb->intraoperasi1)? ($datackb->intraoperasi1 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi1" value="2" <?= isset($datackb->intraoperasi1)? ($datackb->intraoperasi1 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Menganjurkan Pasien Untuk Rileks dan Mengikuti Saran Perawat/Dokter</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi2" value="1" <?= isset($datackb->intraoperasi2)? ($datackb->intraoperasi2 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi2" value="2" <?= isset($datackb->intraoperasi2)? ($datackb->intraoperasi2 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Monitor dan Oksigen Terpasang</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi3" value="1" <?= isset($datackb->intraoperasi3)? ($datackb->intraoperasi3 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi3" value="2" <?= isset($datackb->intraoperasi3)? ($datackb->intraoperasi3 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Melakukan Sign In</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi4" value="1" <?= isset($datackb->intraoperasi4)? ($datackb->intraoperasi4 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi4" value="2" <?= isset($datackb->intraoperasi4)? ($datackb->intraoperasi4 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Tetes Pantocain</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi6" value="1" <?= isset($datackb->intraoperasi6)? ($datackb->intraoperasi6 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi6" value="2" <?= isset($datackb->intraoperasi6)? ($datackb->intraoperasi6 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Tetes Antibiotik (Intra Okular)</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi7" value="1" <?= isset($datackb->intraoperasi7)? ($datackb->intraoperasi7 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi7" value="2" <?= isset($datackb->intraoperasi7)? ($datackb->intraoperasi7 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Disenfeksi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi8" value="1" <?= isset($datackb->intraoperasi8)? ($datackb->intraoperasi8 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi8" value="2" <?= isset($datackb->intraoperasi8)? ($datackb->intraoperasi8 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Untuk Endopthalmitis Kultur Sudah Disiapkan</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi10" value="1" <?= isset($datackb->intraoperasi10)? ($datackb->intraoperasi10 == "1")? "checked" : "" : "" ?>>&nbsp; Ya</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi10" value="2" <?= isset($datackb->intraoperasi10)? ($datackb->intraoperasi10 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Melakukan Timeout</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi12" value="1" <?= isset($datackb->intraoperasi12)? ($datackb->intraoperasi12 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi12" value="2" <?= isset($datackb->intraoperasi12)? ($datackb->intraoperasi12 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Mengukur Tanda-Tanda Vital</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi13" value="1" <?= isset($datackb->intraoperasi13)? ($datackb->intraoperasi13 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi13" value="2" <?= isset($datackb->intraoperasi13)? ($datackb->intraoperasi13 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:55%">- Nadi</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="nadi2" value="<?= isset($datackb->nadi2)? $datackb->nadi2 : "" ?>">
                                                        <span class="input-group-addon">x/menit</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:55%">- SpO2</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="spo2" value="<?= isset($datackb->spo2)? $datackb->spo2 : "" ?>">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Saat Operasi Luka Dijahit Apabila</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi14" value="1" <?= isset($datackb->intraoperasi14)? ($datackb->intraoperasi14 == "1")? "checked" : "" : "" ?>>&nbsp; Ya</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi14" value="2" <?= isset($datackb->intraoperasi14)? ($datackb->intraoperasi14 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:95%" colspan="3">- One Eye</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:95%" colspan="3">- Grade IV-V</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:95%" colspan="3">- Ruptur Capsul Posterior</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:95%" colspan="3">- Umur > 80 Tahun</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Melakukan Sign Out</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi15" value="1" <?= isset($datackb->intraoperasi15)? ($datackb->intraoperasi15 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi15" value="2" <?= isset($datackb->intraoperasi15)? ($datackb->intraoperasi15 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Menghitung Jumlah Instrumen/Kassa/Jarum</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi16" value="1" <?= isset($datackb->intraoperasi16)? ($datackb->intraoperasi16 == "1")? "checked" : "" : "" ?>>&nbsp; Lengkap</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi16" value="2" <?= isset($datackb->intraoperasi16)? ($datackb->intraoperasi16 == "2")? "checked" : "" : "" ?>>&nbsp; Tidak</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no5++ ?></td>
                                                <td style="width:55%">Dokter Menginformasikan Jam Kontrol Kepada Perawat Atau Pasien</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi17" value="1" <?= isset($datackb->intraoperasi17)? ($datackb->intraoperasi17 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="intraoperasi17" value="2" <?= isset($datackb->intraoperasi17)? ($datackb->intraoperasi17 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4"><b class="sub-title">G. POST OPERASI</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Menganjurkan Pasien Beristirahat dan Memberikan Posisi Nyaman</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi1" value="1" <?= isset($datackb->postoperasi1)? ($datackb->postoperasi1 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi1" value="2" <?= isset($datackb->postoperasi1)? ($datackb->postoperasi1 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Memberikan Minum/Snack</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi2" value="1" <?= isset($datackb->postoperasi2)? ($datackb->postoperasi2 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi2" value="2" <?= isset($datackb->postoperasi2)? ($datackb->postoperasi2 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Tanda-Tanda Vital</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi4" value="1" <?= isset($datackb->postoperasi4)? ($datackb->postoperasi4 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi4" value="2" <?= isset($datackb->postoperasi4)? ($datackb->postoperasi4 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Tekanan Darah</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="tekanandarah2" value="<?= isset($datackb->tekanandarah2)? $datackb->tekanandarah2 : "" ?>">
                                                        <span class="input-group-addon">mmHg</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Nadi</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="nadi3" value="<?= isset($datackb->nadi3)? $datackb->nadi3 : "" ?>">
                                                        <span class="input-group-addon">x/menit</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Suhu</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="suhu2" value="<?= isset($datackb->suhu2)? $datackb->suhu2 : "" ?>">
                                                        <span class="input-group-addon">°C</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- Pernapasan</td>
                                                <td style="width:40%" colspan="2">
                                                    <div class="input-group">
                                                        <input id="msg" type="text" class="form-control" name="pernapasan2" value="<?= isset($datackb->pernapasan2)? $datackb->pernapasan2 : "" ?>">
                                                        <span class="input-group-addon">x/menit</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Skala Nyeri</td>
                                                <td style="width:40%" colspan="2">
                                                    <input id="msg" type="text" class="form-control" name="skalanyeri2" value="<?= isset($datackb->skalanyeri2)? $datackb->skalanyeri2 : "" ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:95%" colspan="3">Tetes Antibiotik Setiap 15 Menit (Untuk Operasi Intra Okular)</td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 0' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik02" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->tetesantibiotik02)? $datackb->tetesantibiotik02 : date("H:i:s") ?>"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi5" value="1" <?= isset($datackb->postoperasi5)? ($datackb->postoperasi5 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi5" value="2" <?= isset($datackb->postoperasi5)? ($datackb->postoperasi5 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 15' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik152" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->tetesantibiotik152)? $datackb->tetesantibiotik152 : date("H:i:s") ?>"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi6" value="1" <?= isset($datackb->postoperasi6)? ($datackb->postoperasi6 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi6" value="2" <?= isset($datackb->postoperasi6)? ($datackb->postoperasi6 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 30' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik302" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->tetesantibiotik302)? $datackb->tetesantibiotik302 : date("H:i:s") ?>"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi7" value="1" <?= isset($datackb->postoperasi7)? ($datackb->postoperasi7 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi7" value="2" <?= isset($datackb->postoperasi7)? ($datackb->postoperasi7 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 45' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik452" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->tetesantibiotik452)? $datackb->tetesantibiotik452 : date("H:i:s") ?>"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi8" value="1" <?= isset($datackb->postoperasi8)? ($datackb->postoperasi8 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi8" value="2" <?= isset($datackb->postoperasi8)? ($datackb->postoperasi8 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"></td>
                                                <td style="width:55%">- 60' Diberikan Jam&emsp;<input type="time" class="form-control" name="tetesantibiotik602" style="padding:0px;height:20px;width:30%;display:inline-block" value="<?= isset($datackb->tetesantibiotik602)? $datackb->tetesantibiotik602 : date("H:i:s") ?>"></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi9" value="1" <?= isset($datackb->postoperasi9)? ($datackb->postoperasi9 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi9" value="2" <?= isset($datackb->postoperasi9)? ($datackb->postoperasi9 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Keluarga Pasien Diberitahu Bila Operasi Sudah Selesai</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi10" value="1" <?= isset($datackb->postoperasi10)? ($datackb->postoperasi10 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi10" value="2" <?= isset($datackb->postoperasi10)? ($datackb->postoperasi10 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Melepas Tanda Pada Mata Yang Dioperasi</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi11" value="1" <?= isset($datackb->postoperasi11)? ($datackb->postoperasi11 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi11" value="2" <?= isset($datackb->postoperasi11)? ($datackb->postoperasi11 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%" class="text-center"><?= $no6++ ?></td>
                                                <td style="width:55%">Memberikan Penyuluhan dan Perawatan Dirumah, Posisi Tidur, Obat, Kontrol, Berikutnya dan Hal-Hal Yang Harus Diwaspadai</td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi12" value="1" <?= isset($datackb->postoperasi12)? ($datackb->postoperasi12 == "1")? "checked" : "" : "" ?>>&nbsp; Sudah</label></td>
                                                <td style="width:20%"><label class="checkbox-inline"><input type="checkbox" name="postoperasi12" value="2" <?= isset($datackb->postoperasi12)? ($datackb->postoperasi12 == "2")? "checked" : "" : "" ?>>&nbsp; Belum</label></td>
                                            </tr>
                                            <tr>
                                                <td style="width:5%"></td>
                                                <td style="width:55%">Catatan</td>
                                                <td style="width:40%" colspan="2">
                                                    <textarea type="text" class="form-control" name="catatan" style="resize:none"><?= isset($datackb->catatan)? $datackb->catatan : "" ?></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <button type="button" class="btn default btn-md pull-left" onclick="filterSelection('1')"><i class="fa fa-angle-left fa-fw"></i>&nbsp; Sebelumnya</button>
                                <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp; <?= ($statusckb == "undone")? "Simpan" : "Update" ?></button>
                                <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                                <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
    </form>

    <?php
        $this->load->view('template/footer');
    ?>

    <!-- <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script> -->
    <!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

    <script>
        $(window).on("load", function(){
            <?php if($statusckb == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakckp/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmckb").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statusckb == "undone"): ?>
                    url: "/Bedah_Central/save_catatan_keperawatan_bedah/",
                <?php else: ?>
                    url: "/Bedah_Central/save_catatan_keperawatan_bedah/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Catatan Keperawatan Bedah Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statusckb == "undone"): ?>
                            swal({
                                html: "Catatan Keperawatan Bedah Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakckp/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Catatan Keperawatan Bedah Berhasil Diupdate",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            });
                        <?php endif; ?>
                    }
                },
                error: function(data, jqXHR, textStatus, errorThrown) {
                    swal({
                        html: textStatus,
                        type: "error",
                        confirmButtonText: "Close",
                        confirmButtonColor: "red"
                    });
                }
            })
        });

        filterSelection("1")
        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("filterBox");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                w3RemoveClass(x[i], "filterBoxshow");
                if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "filterBoxshow");
            }
        }

        function w3AddClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {
                    element.className += " " + arr2[i];
                }
            }
        }

        function w3RemoveClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++){
                while (arr1.indexOf(arr2[i]) > -1){
                    arr1.splice(arr1.indexOf(arr2[i]), 1);
                }
            }
            element.className = arr1.join(" ");
        }

        var btnContainer = document.getElementById("myBtnContainer");
        var btns = btnContainer.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                // var current = document.getElementsByClassName("btn green");
                // current[0].className = current[0].className.replace(" green", "");
                // this.className += " green";
            });
        }

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>