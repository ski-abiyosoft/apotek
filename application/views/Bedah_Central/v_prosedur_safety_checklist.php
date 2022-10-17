<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        .tab-pane {padding:20px;border-left:1px solid #ddd;border-right:1px solid #ddd;border-bottom:1px solid #ddd;border-radius:0px;border-bottom-left-radius:5px;border-bottom-right-radius:5px}
        .tab-pane-title {font-weight:bold;font-size:18px;margin-bottom:20px}
    </style>

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

    <form id="frmpsc">
        <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
        <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
        <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">
        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">

                        <div class="row form-horizontal">
                            <div class="col-sm-12">

                            <div class="form-group">
                                <label for="droperator" class="col-sm-3">Nama Dokter</label>
                                <div class="col-sm-6">
                                    <!-- <input type="hidden" name="droperator" id="droperator" value="<?= isset($datapsc->droperator)? $datapsc->droperator : $kodokter ?>">
                                    <input type="text" class="form-control"value="<?= isset($datapsc->droperator)? data_master("tbl_dokter", array("kodokter" => $datapsc->droperator))->nadokter : $dokter ?>"> -->
                                    <select type="text" id="droperator" name="droperator" class="form-control selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option value="">--- Pilih Data ---</option>
                                            <?php
                                                foreach($listdokter as $dkey => $dval):
                                                    if($dval->kodokter == $datapsc->droperator):
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
                            <div class="form-group">
                                <label for="tanggal" class="col-sm-3">Tanggal</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= isset($datapsc->droperator)? $datapsc->tanggal : date("Y-m-d") ?>">
                                </div>
                            </div>

                            <br />

                            <ul class="nav nav-tabs nav-justified" style="margin:0">
                                <li class="active">
                                    <a href="#tab1" data-toggle="tab" style="color:#222">Sig In</a>
                                </li>
                                <li >
                                    <a href="#tab2" onclick="tab2()" data-toggle="tab" style="color:#222">Time Out</a>
                                </li>
                                <li >
                                    <a href="#tab3" data-toggle="tab" onclick="tab3()" style="color:#222">Sign Out</a>
                                </li>
                            </ul>

                            <div class="tab-content" style="margin:0 0 20px 0">
                                <div class="tab-pane active" id="tab1">
                                    <div class="tab-pane-title">Sebelum Induksi Anestesi</div>

                                    <div class="table-repsonsive">
                                        <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                            <tbody>
                                                <tr>
                                                    <td class="text-center" style="width:10%"><i class="fa fa-angle-down"></i></td>
                                                    <td class="text-center" style="width:40%">Perawat Verifikasi</td>
                                                    <td class="text-center" style="width:40%">Anestesi</td>
                                                    <td class="text-center" style="width:10%"><i class="fa fa-angle-down"></i></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check1)? ($datapsc->signin_check1 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Nama Pasien</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check2)? ($datapsc->signin_check2 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check3" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check3)? ($datapsc->signin_check3 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Tanggal Lahir & Alamat</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check4" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check4)? ($datapsc->signin_check4 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check5" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check5)? ($datapsc->signin_check5 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Prosedur Operasi</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check6" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check6)? ($datapsc->signin_check6 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check7" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check7)? ($datapsc->signin_check7 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Sisi Mata Yang Akan Dioperasi</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check8" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check8)? ($datapsc->signin_check8 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check9" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check9)? ($datapsc->signin_check9 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Jenis Anestesi</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check10" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check10)? ($datapsc->signin_check10 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check11" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check11)? ($datapsc->signin_check11 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Surat Persetujuan Operasi Sudah Ditandatangani dan Sesuai</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check12" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check12)? ($datapsc->signin_check12 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><input type="checkbox" name="signin_check13" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check13)? ($datapsc->signin_check13 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                    <td colspan="2">Apakah Sisi Yang Dioperasi Sudah Ditandai?</td>
                                                    <td class="text-center"><input type="checkbox" name="signin_check14" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check14)? ($datapsc->signin_check14 == "on")? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check15" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check15)? ($datapsc->signin_check15 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check15" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check15)? ($datapsc->signin_check15 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                    <td colspan="2">Alergi</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check16" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check16)? ($datapsc->signin_check16 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check16" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check16)? ($datapsc->signin_check16 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check17" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check17)? ($datapsc->signin_check17 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check17" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check17)? ($datapsc->signin_check17 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                    <td colspan="2">Apakah Ada Resiko Kehilangan Darah > 500cc (&cc/kg bb Anak)</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check18" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check18)? ($datapsc->signin_check18 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check18" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check18)? ($datapsc->signin_check18 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check19" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check19)? ($datapsc->signin_check19 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check19" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check19)? ($datapsc->signin_check19 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                    <td colspan="2">Memastikan Apakah Ada Darah Penganti</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check20" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check20)? ($datapsc->signin_check20 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check20" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check20)? ($datapsc->signin_check20 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check21" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check21)? ($datapsc->signin_check21 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check21" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check21)? ($datapsc->signin_check21 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                    <td colspan="2">Apakah Foto Rontgen/CT Scan Perlu Dipajang</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check22" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check22)? ($datapsc->signin_check22 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check22" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check22)? ($datapsc->signin_check22 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check23" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check23)? ($datapsc->signin_check23 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check23" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check23)? ($datapsc->signin_check15 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                    <td colspan="2">Apakah Pasien Sudah Dipuasakan</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check24" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check24)? ($datapsc->signin_check24 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check24" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check24)? ($datapsc->signin_check24 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"></td>
                                                    <td colspan="2">Apakah Mesin Anestesi dan Obat-Obatan Sudah Di Cek Komplit ?</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check25" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check25)? ($datapsc->signin_check25 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check25" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check25)? ($datapsc->signin_check25 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"></td>
                                                    <td colspan="2">Apakah Ada Hal Yang Spesifik Berhubungan Dengan Anestesi</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check26" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check26)? ($datapsc->signin_check26 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check26" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check26)? ($datapsc->signin_check26 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"></td>
                                                    <td colspan="2">Jika Ya Apakah Alat/Asisten Sudah Tersedia ?</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="signin_check27" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check27)? ($datapsc->signin_check27 == 1)? "checked" : "" : "" ?>><span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signin_check27" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signin_check27)? ($datapsc->signin_check27 == 2)? "checked" : "" : "" ?>><span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="tab-pane-title">Sebelum Pembedahan Dimulai</div>

                                    <div class="table-repsonsive">
                                        <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah semua anggota tim sudah memperkenalkan dirinya dan apa pseranannya ?</b></p>
                                                        <input type="checkbox" name="timeout_check1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check1)? ($datapsc->timeout_check1 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Sudah Dilakukan</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Operator, Anestesi dan Perawat Sirkuler mencek Pasien dan memastikan secara verbal :</b></p>
                                                        <input type="checkbox" name="timeout_check2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check2)? ($datapsc->timeout_check2 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Siapa Nama Pasien ?</span><br />
                                                        <input type="checkbox" name="timeout_check3" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check3)? ($datapsc->timeout_check3 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tanggal Lahir & Alamat</span><br />
                                                        <input type="checkbox" name="timeout_check4" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check4)? ($datapsc->timeout_check4 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Apa Prosedur Operasi</span><br />
                                                        <input type="checkbox" name="timeout_check5" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check5)? ($datapsc->timeout_check5 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Sisi Mata Yang Akan Dioperasi ?</span><br />
                                                        <input type="checkbox" name="timeout_check6" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check6)? ($datapsc->timeout_check6 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Informasi Lain :</span><br /><br />
                                                        <input type="text" class="form-control" name="timeout_text1" id="" value="<?= isset($datapsc->timeout_text1)? $datapsc->timeout_text1 : "" ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Alergi ?</b></p>
                                                        <input type="checkbox" name="timeout_check7" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check7)? ($datapsc->timeout_check7 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">ya</span><br />
                                                        <input type="checkbox" name="timeout_check7" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check7)? ($datapsc->timeout_check7 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah mata yang tidak dioperasi sudah dipasang DOP ?</b></p>
                                                        <input type="checkbox" name="timeout_check8" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check8)? ($datapsc->timeout_check8 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="timeout_check8" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check8)? ($datapsc->timeout_check8 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak Dipasang</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Opertor memastikan : <br />jenis dan power IOL/implant lainnya</b></p>
                                                        <input type="checkbox" name="timeout_check9" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check9)? ($datapsc->timeout_check9 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Digunakan</span><br /><br />
                                                        <p><b>Sebutkan IOL yang dipilih dan target yang diinginkan dan implant lainnya sudah tersedia di kamar operasi.</b></p>
                                                        <input type="checkbox" name="timeout_check10" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check10)? ($datapsc->timeout_check10 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak Digunakan</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Operator memastikan :</b><br />Apakah ada alat khusus yang dibutuhkan :</p>
                                                        <input type="checkbox" name="timeout_check11" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check11)? ($datapsc->timeout_check11 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="timeout_check11" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check11)? ($datapsc->timeout_check11 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span><br /><br />
                                                        <p><b>Apakah ada langkah yang tidak rutin dilakukan  untuk diketahui oleh tim OK :</b></p>
                                                        <input type="checkbox" name="timeout_check12" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check12)? ($datapsc->timeout_check12 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="timeout_check12" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check12)? ($datapsc->timeout_check12 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Perawat OK :</b></p>
                                                        <input type="checkbox" name="timeout_check16" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check16)? ($datapsc->timeout_check16 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Apakah sterilitas sudah dipastikan (termasuk hasil indikator) ?</span><br />
                                                        <input type="checkbox" name="timeout_check17" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check17)? ($datapsc->timeout_check17 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Apakah instrumen sudah lengkap atau ada hal yang spesifik ?</span><br />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah hal dibawah ini dibutuhkan untuk mengurangi resiko infeksi pembedahan</b></p>
                                                        <input type="checkbox" name="timeout_check13" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check13)? ($datapsc->timeout_check13 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Antibiotik profilaksis dalam 60 menit terakhir</span><br />
                                                        <input type="checkbox" name="timeout_check13" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check13)? ($datapsc->timeout_check13 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Kendali hiperglikemia</span><br /><br />
                                                        <input type="checkbox" name="timeout_check14" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check14)? ($datapsc->timeout_check14 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="timeout_check14" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check14)? ($datapsc->timeout_check14 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak Dilakukan</span>
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Doa Bersama</b></p>
                                                        <input type="checkbox" name="timeout_check15" style="display:inline-block !important;float:left" <?= isset($datapsc->timeout_check15)? ($datapsc->timeout_check15 == "on")? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya/Sudah Dilakukan</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab3">
                                    <div class="tab-pane-title">Sebelum Tim Meninggalkan Kamar Operasi</div>

                                    <div class="table-repsonsive">
                                        <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Operator / Perawat OK secara verbal</b></p>
                                                        <p><b>Apakah nama prosedur sudah tercatat dalam status ?</b></p>
                                                        <input type="checkbox" name="signout_check1" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check1)? ($datapsc->signout_check1 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signout_check1" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check1)? ($datapsc->signout_check1 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah ada spesimen ?</b></p>
                                                        <input type="checkbox" name="signout_check2" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check2)? ($datapsc->signout_check2 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signout_check2" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check2)? ($datapsc->signout_check2 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span><br /><br />
                                                        <p><b>Jika ada, apakah spesimen sudah diberi label (termasuk nama pasien, tanggal dan nama dokter) ?</b></p>
                                                        <input type="checkbox" name="signout_check3" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check3)? ($datapsc->signout_check3 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signout_check3" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check3)? ($datapsc->signout_check3 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah ada malasah alat dan sudah diidentifikasi untuk dilaporkan ke kepala OK ?</b></p>
                                                        <input type="checkbox" name="signout_check4" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check4)? ($datapsc->signout_check4 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signout_check4" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check4)? ($datapsc->signout_check4 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span><br /><br />
                                                        <b>Jika ya, buat laporan terperinci terpisah dan pastikan alat tersebut dikirim untuk diperbaiki</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Operator dan Dr Anestesi</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Apakah ada instruksi khusus sehubungan dengan perawatan pasien diruang pulih dan / atau diruang rawat ?</b></p>
                                                        <input type="checkbox" name="signout_check5" value="1" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check5)? ($datapsc->signout_check5 == 1)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Ya</span><br />
                                                        <input type="checkbox" name="signout_check5" value="2" style="display:inline-block !important;float:left" <?= isset($datapsc->signout_check5)? ($datapsc->signout_check5 == 2)? "checked" : "" : "" ?>>&emsp;<span style="display:inline-block">Tidak</span><br /><br />
                                                        <p><b>Jika ya :</b></p>
                                                        <textarea type="text" class="form-control" name="signout_text1" id="" rows="3" style="resize:none"><?= isset($datapsc->signout_text1)? $datapsc->signout_text1 : "" ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><b>Hal-hal penting lainnya ?</b></p>
                                                        <textarea type="text" class="form-control" name="signout_text2" id="" rows="5" style="resize:none"><?= isset($datapsc->signout_text2)? $datapsc->signout_text2 : "" ?></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-1">&nbsp;</div>
                                <div class="col-sm-2 text-center">
                                    <p>Perawat Verifikasi</p><br />
                                    <input type="text" name="jam1" class="form-control" id="jam1" placeholder="jam" value="<?= isset($datapsc->jam1)? $datapsc->jam1 : "" ?>">
                                </div>
                                <div class="col-sm-2 text-center">
                                    <p>Anestesi</p><br />
                                    <input type="text" name="jam2" class="form-control" id="jam2" placeholder="jam" value="<?= isset($datapsc->jam2)? $datapsc->jam2 : "" ?>">
                                </div>
                                <div class="col-sm-2 text-center">
                                    <p>Perawat Sirkuler</p><br />
                                    <input type="text" name="jam3" class="form-control" id="jam3" placeholder="jam" value="<?= isset($datapsc->jam3)? $datapsc->jam3 : "" ?>">
                                </div>
                                <div class="col-sm-2 text-center">
                                    <p>Perawat Asisten</p><br />
                                    <input type="text" name="jam4" class="form-control" id="jam4" placeholder="jam" value="<?= isset($datapsc->jam4)? $datapsc->jam4 : "" ?>">
                                </div>
                                <div class="col-sm-2 text-center">
                                    <p>Operator</p><br />
                                    <input type="text" name="jam5" class="form-control" id="jam5" placeholder="jam" value="<?= isset($datapsc->jam5)? $datapsc->jam5 : "" ?>">
                                </div>
                                <div class="col-sm-1">&nbsp;</div>
                            </div>

                            </div>
                        </div>

                        <br />

                        <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp; <?= ($statuspsc == "done")? "Update" : "Simpan" ?></button>
                        <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                        <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                        
                        <br /><br />
                    </div>

                </div>
            </div>
        </div>
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
            <?php if($statuspsc == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakpsc/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmpsc").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statuspsc == "undone"): ?>
                    url: "/Bedah_Central/save_prosedur_safety_checklist/",
                <?php else: ?>
                    url: "/Bedah_Central/save_prosedur_safety_checklist/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Prosedur Safety Checklist Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statuspsc == "undone"): ?>
                            swal({
                                html: "Prosedur Safety Checklist Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakpsc/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Prosedur Safety Checklist Berhasil Diupdate",
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

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>