<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        #myBtnContainer {margin-bottom:20px}
        .filterBoxtitle {font-size:18px;font-weight:bold;margin-bottom:10px !important;display:block}
        .filterBox {display:none}
        .filterBoxshow {display:block}
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
            <div class="caption">PERSETUJUAN TINDAKAN DOKTER</div>
        </div>
    </div>

    <form id="frmptd">
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
    
    <!-- <div class="row">
        <div class="col-sm-12">
            <div class="portlet box blue">
                <div class="portlet-title" style="border-radius:0px !important">
                    <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Data Pasien</b></div>
                </div>

                <div class="portlet-body" style="border-radius:0px !important">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4">No Rekam Medis</label>
                                        <div class="col-sm-8">
                                            <input type="" class="form-control" value="<?= $jadwal->rekmed ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4">Nama</label>
                                        <div class="col-sm-8">
                                            <input type="" name="pas2name" class="form-control" value="<?= $jadwal->namapas ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4">Jenis Kelamin</label>
                                        <div class="col-sm-8">
                                            <input type="" name="pas2jkel" class="form-control" value="<?= ($jadwal->jkel == "P")? "Pria" : "Wanita" ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4">Tanggal Lahir</label>
                                        <div class="col-sm-8">
                                            <input type="" name="pas2birth" class="form-control" value="<?= $jadwal->tgllahir ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4">Alamat</label>
                                        <div class="col-sm-8">
                                            <textarea type="" name="pas2address" class="form-control" style="resize:none"><?= $jadwal->alamat ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

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
                    <!-- <div id="myBtnContainer">
                        <button type="button" class="btn green" onclick="filterSelection('1')">Pasien</button>
                        <button type="button"  class="btn" onclick="filterSelection('2')">Wali/Keluarga</button>
                    </div> -->
                    <div id="myBtnContainer">
                        <div class="filterBox 1">
                            <div class="filterBoxtitle" style="padding-bottom:5px">Pemberian Informasi <small><i>(Education)</i></small></div>
                            <div class="table-responsive" style="padding-bottom:0px !important;margin-bottom:0 !important">
                                <table class="table table-bordered table-striped" style="padding-bottom:0px !important;margin-bottom:0 !important">
                                    <tbody>
                                        <tr>
                                            <td style="font-weight:bold" colspan="2">
                                                Tanggal & Pukul <small style="color:red"></small><br />
                                                <small><i>(Date & Time)</i></small>
                                            </td>
                                            <td colspan="2">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <input type="date" name="edudate" id="edudate" class="form-control" value="<?= isset($dataptd->edudate)? $dataptd->edudate : date("Y-m-d") ?>" required>
                                                    </div>
                                                    <div class="col-sm-2 text-center" style="padding-top:10px">&</div>
                                                    <div class="col-sm-5">
                                                        <input type="time" name="edutime" id="edutime" class="form-control" value="<?= isset($dataptd->edutime)? $dataptd->edutime : date("H:i:s") ?>" required>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold" colspan="2">
                                                Dokter Pelaksana Tindakan <small style="color:red"></small><br />
                                                <small><i>(Primary Physician)</i></small>
                                            </td>
                                            <td colspan="2">
                                                <select type="text" id="edudoc" name="edudoc" class="form-control selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                                    <option value="">--- Pilih Data ---</option>
                                                    <?php
                                                        foreach($dokter as $dkey => $dval):
                                                            if($dval->kodokter == $dataptd->edudoc):
                                                    ?>
                                                        <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                    <?php else: ?>
                                                        <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                    <?php 
                                                            endif;
                                                        endforeach; 
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold" colspan="2">
                                                Pemberi Informasi <small style="color:red"></small><br />
                                                <small><i>(Informant)</i></small>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="form-control" name="eduinformant" id="eduinformant" value="<?= isset($dataptd->eduinformant)? $dataptd->eduinformant : '' ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold" colspan="2">
                                                Penerima Informasi / Pemberi Persetujuan <small style="color:red"></small><br />
                                                <small><i>(Informed Person / Consent Trustee)</i></small>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="form-control" name="eduinformed" id="eduinformed" value="<?= isset($dataptd->eduinformed)? $dataptd->eduinformed : '' ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold;text-align:center">No</td>
                                            <td style="font-weight:bold;text-align:center">
                                                Jenis Informasi<br />
                                                <small><i>(Type of Information)</i></small>
                                            </td>
                                            <td style="font-weight:bold;text-align:center">
                                                Isi Informasi<br />
                                                <small><i>(Content)</i></small>
                                            </td>
                                            <td style="font-weight:bold;text-align:center">
                                                Tanda (<i class="fa fa-check"></i>)<br />
                                                <small><i>(Mark)</i></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">1</td>
                                            <td style="font-weight:bold">
                                                Diagnosis (WD & DD)<br />
                                                <small><i>(Working Diagnose & Differential Diagnose)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="eduwddd" id="eduwddd" value="<?= isset($dataptd->eduwddd)? $dataptd->eduwddd : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check1" id="check1" <?= isset($dataptd->eduwddd)? ($dataptd->check1 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">2</td>
                                            <td style="font-weight:bold">
                                                Dasar Diagnosis<br />
                                                <small><i>(Early Diagnose)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="edudiagnose" id="edudiagnose" value="<?= isset($dataptd->edudiagnose)? $dataptd->edudiagnose : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check2" id="check2" <?= isset($dataptd->edudiagnose)? ($dataptd->check2 == "on")? "checked" : "" : ""?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">3</td>
                                            <td style="font-weight:bold">
                                                Tindakan Kedokteran<br />
                                                <small><i>(Medical Treatment)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="edumedtreat" id="edumedtreat" value="<?= isset($dataptd->edumedtreat)? $dataptd->edumedtreat : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check3" id="check3" <?= isset($dataptd->edumedtreat)? ($dataptd->check3 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">4</td>
                                            <td style="font-weight:bold">
                                                Indikasi Tindakan<br />
                                                <small><i>(Indication)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="eduindication" id="eduindication" value="<?= isset($dataptd->eduindication)? $dataptd->eduindication : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check4" id="check4" <?= isset($dataptd->eduindication)? ($dataptd->check4 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">5</td>
                                            <td style="font-weight:bold">
                                                Jenis Anestesi<br />
                                                <small><i>(Type of Anesthesia)</i></small>
                                            </td>
                                            <td>
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutypeanes" id="edutypeanes" value="Topikal" <?= isset($dataptd->edutypeanes)? ($dataptd->edutypeanes == "Topikal")? "checked" : "" : "" ?>>&emsp;Topikal <small><i>(Topical Anethesia)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutypeanes" id="edutypeanes" value="Lokal" <?= isset($dataptd->edutypeanes)? ($dataptd->edutypeanes == "Lokal")? "checked" : "" : "" ?>>&emsp;Lokal <small><i>(Local Anethesia)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutypeanes" id="edutypeanes" value="Umum" <?= isset($dataptd->edutypeanes)? ($dataptd->edutypeanes == "Umum")? "checked" : "" : "" ?>>&emsp;Umum <small><i>(General Anethesia)</i></small></label>
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check5" id="check5" <?= isset($dataptd->check5)? ($dataptd->check5 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">6</td>
                                            <td style="font-weight:bold">
                                                Tujuan Tindakan Kedokteran<br />
                                                <small><i>(Purpose of Medical Treatment)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="edupurmedtreat" id="edupurmedtreat" value="<?= isset($dataptd->edupurmedtreat)? $dataptd->edupurmedtreat : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check6" id="check6" <?= isset($dataptd->check6)? ($dataptd->check6 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">7</td>
                                            <td style="font-weight:bold">
                                                Resiko & Komplikasi<br />
                                                <small><i>(Risk & Complication)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="eduriskcomp" id="eduriskcomp" value="<?= isset($dataptd->eduriskcomp)? $dataptd->eduriskcomp : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check7" id="check7" <?= isset($dataptd->check7)? ($dataptd->check7 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">8</td>
                                            <td style="font-weight:bold">
                                                Prognosis<br />
                                                <small><i>(Prognosis)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="eduprognosis" id="eduprognosis" value="<?= isset($dataptd->eduprognosis)? $dataptd->eduprognosis : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check8" id="check8" <?= isset($dataptd->check8)? ($dataptd->check8 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">9</td>
                                            <td style="font-weight:bold">
                                                Alternatif & Resiko<br />
                                                <small><i>(Alternative & Risk)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="edualtrisk" id="edualtrisk" value="<?= isset($dataptd->edualtrisk)? $dataptd->edualtrisk : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check9" id="check9" <?= isset($dataptd->check9)? ($dataptd->check9 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">10</td>
                                            <td style="font-weight:bold">
                                                Teknik Edukasi Yang Digunakan<br />
                                                <small><i>(Education Technique)</i></small>
                                            </td>
                                            <td>
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Alat Peraga" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Alat Peraga")? "checked" : "" : "" ?>> Alat Peraga <small><i>(Model kit)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Video" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Video")? "checked" : "" : "" ?>> Video <small><i>(Video)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Buku Panduan" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Buku Panduan")? "checked" : "" : "" ?>> Buku Panduan <small><i>(Guide Book)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Brosur" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Brosur")? "checked" : "" : "" ?>> Brosur <small><i>(Brochure)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Lisan" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Lisan")? "checked" : "" : "" ?>> Lisan <small><i>(Verbal)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edutechnique" id="edutechnique" value="Phantom" <?= isset($dataptd->edutechnique)? ($dataptd->edutechnique == "Phantom")? "checked" : "" : "" ?>> Phantom <small><i>(Phantom)</i></small></label>
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check10" id="check10" <?= isset($dataptd->check10)? ($dataptd->check10 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">11</td>
                                            <td style="font-weight:bold">
                                                Perkiraan Biaya<br />
                                                <small><i>(Estimated Cost)</i></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="eduestcost" id="eduestcost" value="<?= isset($dataptd->eduestcost)? number_format($dataptd->eduestcost, 0, '.', ',') : '' ?>">
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check11" id="check11" <?= isset($dataptd->check11)? ($dataptd->check11 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;font-weight:bold">12</td>
                                            <td style="font-weight:bold">
                                                Jenis Pembayaran<br />
                                                <small><i>(Type of Payment)</i></small>
                                            </td>
                                            <td>
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edupaymethod" id="edupaymethod" value="Pribadi" <?= isset($dataptd->edupaymethod)? ($dataptd->edupaymethod == "Pribadi")? "checked" : "" : "" ?>> Pribadi <small><i>(Self Payment)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edupaymethod" id="edupaymethod" value="Kantor" <?= isset($dataptd->edupaymethod)? ($dataptd->edupaymethod == "Kantor")? "checked" : "" : "" ?>> Kantor <small><i>(Office)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edupaymethod" id="edupaymethod" value="JKN/Jaminan" <?= isset($dataptd->edupaymethod)? ($dataptd->edupaymethod == "JKN/Jaminan")? "checked" : "" : "" ?>> JKN/Jaminan <small><i>(Convered By)</i></small></label><br />
                                                <label class="form-inline"><input type="radio" class="form-radio" name="edupaymethod" id="edupaymethod" value="Asuransi" <?= isset($dataptd->edupaymethod)? ($dataptd->edupaymethod == "Asuransi")? "checked" : "" : "" ?>> Asuransi <small><i>(Insurance)</i></small></label><br />
                                            </td>
                                            <td style="text-align:center"><input type="checkbox" class="form-checkbox" name="check12" id="check12" <?= isset($dataptd->check12)? ($dataptd->check12 == "on")? "checked" : "" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="font-weight:bold">
                                                Dengan ini menyatakn bahwa saya telah menerangkan hal-hal diatas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdikusi<br />
                                                <small><i>(Hereby I acknowledged that i have explained matters mentioned above details and providedopportunity for question and/or discussion)</i></small>
                                            </td>
                                            <td class="text-center">
                                                Tanda Tangan Dokter<br /><br /><br /><br /><br /><br /><br />
                                                <small><i>(Physician Signature)</i></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="font-weight:bold">
                                                Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana diatas yang saya beri tanda/paraf dikolom kanannya, dan telah memahaminya<br />
                                                <small><i>(Hereby I acknowledge that i have been informed matters as mentioned above that i have marked in it's right column, and fully understood)</i></small>
                                            </td>
                                            <td class="text-center">
                                                Tanda Tangan Pasien/Keluarga<br /><br /><br /><br /><br /><br /><br />
                                                <small><i>(Patient/Relative Signature)</i></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="font-weight:bold">
                                                *Bila pasien tidak kompeten atau tidak mau menerima informasi maka penerima informasi adalah wali atau keluarga terdekat<br />
                                                <small><i>If patient is competent or unwilling to be informed, the cutodain and relatives should be informed on his / her behalf</i></small>
                                            </td>
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
                            <div class="filterBoxtitle" style="padding-bottom:15px">Persetujuan Tindakan Dokter <small><i>(informed Consent)</i></small></div>
                            <div class="row form-horizontal">
                                <div class="col-md-6">
                                    <p style="font-size:16px;padding-bottom:10px"><b>Wali / Keluarga</b></p>
                                    <div class="form-group">
                                        <label for="famname" class="col-sm-4">Nama <small><i>(Name)</i></small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="famname" id="famname" value="<?= isset($dataptd->famname)? $dataptd->famname : "" ?>">
                                        </div>
                                    </diV>
                                    <div class="form-group">
                                        <label for="famgender" class="col-sm-4">Jenis Kelamin <small><i>(Gender)</i></small></label>
                                        <div class="col-sm-8">
                                            <select type="text" id="famgender" name="famgender" class="form-control">
                                                <option value="" <?= isset($dataptd->famgender)? ($dataptd->famgender == "")? "selected" : "" : ""?>>--- Pilih Data ---</option>
                                                <option value="P" <?= isset($dataptd->famgender)? ($dataptd->famgender == "P")? "selected" : "" : "" ?>>Pria (Male)</option>
                                                <option value="W" <?= isset($dataptd->famgender)? ($dataptd->famgender == "W")? "selected" : "" : "" ?>>Wanita (Female)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="famrelate" class="col-sm-4">Hubungan Keluarga <small><i>(Relationship to patient)</i></small></label>
                                        <div class="col-sm-8">
                                            <select type="text" id="famrelate" name="famrelate" class="form-control">
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($hubungank as $hkkey => $hkval):
                                                        if($hkval->keterangan == $dataptd->famrelate):
                                                ?>
                                                    <option value="<?= $hkval->keterangan ?>" selected><?= $hkval->keterangan ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $hkval->keterangan ?>"><?= $hkval->keterangan ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="famphone" class="col-sm-4">No Telepon <small><i>(Phone number)</i></small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="famphone" id="famphone" value="<?= isset($dataptd->famphone)? $dataptd->famphone : "" ?>">
                                        </div>
                                    </diV>
                                    <div class="form-group">
                                        <label for="famaddress" class="col-sm-4">Alamat <small><i>(Address)</i></small></label>
                                        <div class="col-sm-8">
                                            <textarea type="text" class="form-control" name="famaddress" id="famaddress" rows="4" style="resize:none !important"><?= isset($dataptd->famaddress)? $dataptd->famaddress : "" ?></textarea>
                                        </div>
                                    </diV>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;padding-bottom:10px"><b>Pasien / Wali / Keluarga</b></p>
                                    <div class="form-group">
                                        <label for="changeto" class="col-sm-4">Terhadap <small><i>(To)</i></small></label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" name="knowing" id="changeto">                                                
                                                <option value="1" <?= isset($dataptd->knowing)? ($dataptd->knowing == "1")? "selected" : "" : "" ?>>Saya Sendiri (Pasien)</option>
                                                <option value="2" <?= isset($dataptd->knowing)? ($dataptd->knowing == "2")? "selected" : "" : "" ?>>Wali / Keluarga</option>
                                            </select>
                                        </div>
                                    </diV>
                                    <div id="changeelement1">
                                        <?php if($statusptd == "undone"): ?>
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <input type="checkbox" id="autospill" onclick="autoSpill()">
                                            </div>
                                            <label for="autospill" class="col-sm-8">Sama Dengan Data Pasien</label>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label for="pasrekmed" class="col-sm-4">No Rekam Medik <small><i>(Medical Record Number)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="pasrekmed" class="form-control" id="ptdprekmed" value="<?= isset($dataptd->rekmed)? $dataptd->rekmed : $jadwal->rekmed ?>">
                                                Dapat dibantu dituliskan perawat <small><i>(assisted by the nurse)</i></small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasname" class="col-sm-4">Nama <small><i>(Name)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="pasname" class="form-control" id="ptdpnama" value="<?= isset($dataptd->pasname)? $dataptd->pasname : "" ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasbirth" class="col-sm-4">Tanggal Lahir <small><i>(Date of birth)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="date" name="pasbirth" class="form-control" id="ptdpdateb" value="<?= isset($dataptd->pasbirth)? $dataptd->pasbirth : "" ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasgender" class="col-sm-4">Jenis Kelamin <small><i>(Gender)</i></small></label>
                                            <div class="col-sm-8">
                                                <select type="text" name="pasgender" class="form-control" id="ptdpjkel">
                                                    <option value="" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "")? "selected" : "" : ""?>>--- Pilih Data ---</option>
                                                    <option value="P" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "P")? "selected" : "" : "" ?>>Pria (Male)</option>
                                                    <option value="W" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "W")? "selected" : "" : "" ?>>Wanita (Female)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasaddress" class="col-sm-4">Alamat <small><i>(Address)</i></small></label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control" name="pasaddress" id="ptdpalamat" rows="4" style="resize:none !important"><?= isset($dataptd->pasaddress)? $dataptd->pasaddress : "" ?></textarea>
                                            </div>
                                        </diV>
                                    </div>
                                    <div id="changeelement2">
                                        <div class="form-group">
                                            <label for="pasrekmed2" class="col-sm-4">No Rekam Medik <small><i>(Medical Record Number)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="pasrekmed2" class="form-control" id="ptdprekmed" value="<?= isset($dataptd->rekmed)? $dataptd->rekmed : $jadwal->rekmed ?>">
                                                Dapat dibantu dituliskan perawat <small><i>(assisted by the nurse)</i></small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasname2" class="col-sm-4">Nama <small><i>(Name)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="pasname2" class="form-control" id="ptdpnama" value="<?= isset($dataptd->pasname)? $dataptd->pasname : "" ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasbirth2" class="col-sm-4">Tanggal Lahir <small><i>(Date of birth)</i></small></label>
                                            <div class="col-sm-8">
                                                <input type="date" name="pasbirth2" class="form-control" id="ptdpdateb" value="<?= isset($dataptd->pasbirth)? $dataptd->pasbirth : "" ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasgender2" class="col-sm-4">Jenis Kelamin <small><i>(Gender)</i></small></label>
                                            <div class="col-sm-8">
                                                <select type="text" name="pasgender2" class="form-control" id="ptdpjkel">
                                                    <option value="" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "")? "selected" : "" : ""?>>--- Pilih Data ---</option>
                                                    <option value="P" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "P")? "selected" : "" : "" ?>>Pria (Male)</option>
                                                    <option value="W" <?= isset($dataptd->pasgender)? ($dataptd->pasgender == "W")? "selected" : "" : "" ?>>Wanita (Female)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasaddress2" class="col-sm-4">Alamat <small><i>(Address)</i></small></label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control" name="pasaddress2" id="ptdpalamat" rows="4" style="resize:none !important"><?= isset($dataptd->pasaddress)? $dataptd->pasaddress : "" ?></textarea>
                                            </div>
                                        </diV>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button type="button" class="btn default btn-md pull-left" onclick="filterSelection('1')"><i class="fa fa-angle-left fa-fw"></i>&nbsp; Sebelumnya</button>
                            <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp; <?= ($statusptd == "undone")? "Simpan" : "Update" ?></button>
                            <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                            <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

    <?php
        $this->load->view('template/footer');
        $this->load->view('template/v_report');
        $this->load->view('template/v_periode');
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
            $("#changeelement2").hide();
            <?php if($statusptd == "undone"): ?>
            $("#cetak").hide();
            autoSpill();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakptd/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#eduestcost").on("keyup", function(){
            var cost    = $(this).val();
            return $("#eduestcost").val(cost.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        });

        $("#save").on("click", function(){
            var post_form = $("#frmptd").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statusptd == "undone"): ?>
                    url: "/Bedah_Central/save_persetujuan_tindakan_dokter/",
                <?php else: ?>
                    url: "/Bedah_Central/save_persetujuan_tindakan_dokter/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Persetujuan Tindakan Dokter Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statusptd == "undone"): ?>
                            swal({
                                html: "Persetujuan Tindakan Dokter Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakptd/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Persetujuan Tindakan Dokter Berhasil Diupdate",
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

        $("#changeto").on("change", function(){
            var to  = $(this).val();

            switch(to){
                case "1" : 
                    $("#changeelement1").show();
                    $("#changeelement2").hide();
                    console.log("1");
                    break;
                case "2" :
                    $("#changeelement1").hide();
                    $("#changeelement2").show();
                    console.log("2");
                    break;
                default  : 
                    $("#changeelement1").show();
                    $("#changeelement2").show();
                    break;
            }
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

        <?php if($statusptd == "undone"): ?>
        function autoSpill(){
            var checkBox    = $("#autospill");

            if (checkBox.is(":checked")){
                $("#ptdprekmed").val("<?= $jadwal->rekmed ?>");
                $("#ptdpnama").val("<?= $jadwal->namapas ?>");
                $("#ptdpdateb").val("<?= $jadwal->tgllahir ?>");
                $("#ptdpdateb").val("<?= $jadwal->phone ?>");
                $("#ptdpjkel option[value='<?= $jadwal->jkel ?>']").prop("selected", true);
                $("#ptdpalamat").val("<?= $jadwal->alamat ?>");
            } else {
                $("#ptdprekmed").val("<?= $jadwal->rekmed ?>");
                $("#ptdpnama").val("");
                $("#ptdpdateb").val("");
                $("#ptdpdateb").val("");
                $("#ptdpjkel option[value='']").prop("selected", true);
                $("#ptdpalamat").val("");
            }
        }
        <?php endif; ?>

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>