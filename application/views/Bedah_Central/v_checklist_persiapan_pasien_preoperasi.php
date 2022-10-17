<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        #myBtnContainer {margin-bottom:20px}
        .filterBoxtitle {font-size:18px;font-weight:bold}
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
                            <td style="width:35%"><?= $jadwal->jkel ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%"></td>
                            <td style="width:35%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="portlet box blue">
                <div class="portlet-title" style="border-radius:0px !important">
                    <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                </div>

                <div class="portlet-body" style="border-radius:0px !important">
                    <form id="frmclpppo">
                        <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
                        <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
                        <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">

                        <div class="row form-horizontal">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="droperator" class="col-md-3">Dokter Operator</label>
                                    <div class="col-md-9">
                                        <!-- <input type="hidden" name="droperator" id="droperator" value="<?= isset($datacpo->droperator)? $datacpo->droperator : $jadwal->droperator ?>">
                                        <input type="text" class="form-control" value="<?= isset($datacpo->droperator)? data_master("tbl_dokter", array("kodokter" => $datacpo->droperator))->nadokter : data_master("tbl_dokter", array("kodokter" => $jadwal->droperator))->nadokter ?>"> -->
                                        <select type="text" id="droperator" name="droperator" class="form-control selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option value="">--- Pilih Data ---</option>
                                            <?php
                                                foreach($dokter as $dkey => $dval):
                                                    if($dval->kodokter == $datacpo->droperator):
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
                                    <label for="perawat" class="col-md-3">Perawat</label>
                                    <div class="col-md-9">
                                        <input type="text" name="perawat" class="form-control" id="perawat" value="<?= isset($datacpo->perawat)? $datacpo->perawat : "" ?>">
                                    </div>      
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-md-3">Jenis Operasi</label>
                                    <div class="col-md-9">
                                        <input type="hidden" name="kodetarif" id="kodetarif" value="<?= isset($datacpo->kodetarif)? $datacpo->kodetarif : $jadwal->kodetarif ?>">
                                        <input type="text" class="form-control" value="<?= isset($datacpo->kodetarif)? data_master("tbl_tarifrs", array("kodetarif" => $datacpo->kodetarif))->tindakan : data_master("tbl_tarifrs", array("kodetarif" => $jadwal->kodetarif))->tindakan ?>">
                                    </div>      
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="table-responsive" style="padding-bottom:0px !important;margin-bottom:0 !important">
                                    <table class="table table-bordered table-striped" style="padding-bottom:0px !important;margin-bottom:0 !important">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="font-weight:bold">No</td>
                                                <td style="font-weight:bold">Hal-Hal Yang Harus Diperhatikan</td>
                                                <td style="font-weight:bold">Ada</td>
                                                <td style="font-weight:bold">Tidak</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>Hasil Biometri</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check1" id="check1" value="1" <?= isset($datacpo->check1)? ($datacpo->check1 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check1" id="check1" value="2" <?= isset($datacpo->check1)? ($datacpo->check1 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Hasil Noncon Robo</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check2" id="check2" value="1" <?= isset($datacpo->check2)? ($datacpo->check2 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check2" id="check2" value="2" <?= isset($datacpo->check2)? ($datacpo->check2 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Hasil Retinometri</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check3" id="check3" value="1" <?= isset($datacpo->check3)? ($datacpo->check3 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check3" id="check3" value="2" <?= isset($datacpo->check3)? ($datacpo->check3 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">4</td>
                                                <td>Hasil laboratorium</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check4" id="check4" value="1" <?= isset($datacpo->check4)? ($datacpo->check4 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check4" id="check4" value="2" <?= isset($datacpo->check4)? ($datacpo->check4 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">5</td>
                                                <td>Hasil Radiologi</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check5" id="check5" value="1" <?= isset($datacpo->check5)? ($datacpo->check5 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check5" id="check5" value="2" <?= isset($datacpo->check5)? ($datacpo->check5 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">6</td>
                                                <td>Hasil Oculyer</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check6" id="check6" value="1" <?= isset($datacpo->check6)? ($datacpo->check6 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check6" id="check6" value="2" <?= isset($datacpo->check6)? ($datacpo->check6 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">7&nbsp;<small style="color:red">*</small></td>
                                                <td><p>Puasa Mulai Jam (Input Dibawah)</p><input type="time" name="jampuasa" value="<?= isset($datacpo->jampuasa)? $datacpo->jampuasa : date("H:i:s") ?>" class="form-control" style="width:20%"></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check7" id="check7" value="1" <?= isset($datacpo->check7)? ($datacpo->check7 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check7" id="check7" value="2" <?= isset($datacpo->check7)? ($datacpo->check7 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">8</td>
                                                <td>Instrukti Khusus Dari Dokter</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check8" id="check8" value="1" <?= isset($datacpo->check8)? ($datacpo->check8 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check8" id="check8" value="2" <?= isset($datacpo->check8)? ($datacpo->check8 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">9</td>
                                                <td>Lensa</td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check9" id="check9" value="1" <?= isset($datacpo->check9)? ($datacpo->check9 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check9" id="check9" value="2" <?= isset($datacpo->check9)? ($datacpo->check9 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">10</td>
                                                <td>
                                                    Bius Umum :
                                                    <small>
                                                        <br />&nbsp;&nbsp;Lab
                                                        <br />&nbsp;&nbsp;Rontgen
                                                        <br />&nbsp;&nbsp;ECG, Usia > 40 Tahun
                                                        <br />&nbsp;&nbsp;Hasil konsul Dr. Anak/Internist
                                                    </small>
                                                </td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check10" id="check10" value="1" <?= isset($datacpo->check10)? ($datacpo->check10 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check10" id="check10" value="2" <?= isset($datacpo->check10)? ($datacpo->check10 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">11&nbsp;<small style="color:red">*</small></td>
                                                <td>
                                                    Cek File : Hepatitis
                                                    <br />
                                                    <small>
                                                        Bila Hepatitis(+), Jadwal Paling Akhir<br />
                                                        <p>Peny. Lainnya (Input Dibawah)</p>
                                                    </small>
                                                    <input type="text" name="ket1" class="form-control" value="<?= isset($datacpo->ket1)? $datacpo->ket1 : "" ?>" style="width:50%">
                                                </td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check11" id="check11" value="1" <?= isset($datacpo->check11)? ($datacpo->check11 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check11" id="check11" value="2" <?= isset($datacpo->check11)? ($datacpo->check11 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">12</td>
                                                <td><p>Lain-Lain (Input Dibawah)</p>
                                                <input type="text" name="ket2" class="form-control" value="<?= isset($datacpo->ket2)? $datacpo->ket2 : "" ?>" style="width:50%"></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check12" id="check12" value="1" <?= isset($datacpo->check12)? ($datacpo->check12 == 1)? "checked" : "" : "" ?>></td>
                                                <td><input type="radio" style="padding:0px !important;display:inline !important" name="check12" id="check12" value="2" <?= isset($datacpo->check12)? ($datacpo->check12 == 2)? "checked" : "" : "" ?>></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                Catatan:
                                <ol>
                                    <li>Nomor & untuk pasien LASIK</li>
                                    <li>Bila kasus Retina, Glaukoma, Keratoplasty, Okulaplasty dan Strabismus sesuai instruksi dokter</li>
                                    <li>Apabila hasil pemeriksaan Poli / Lab / Rontgen / ECG / Konsultasi Dr. Anak / Internist kurang lengkap, agar dilengkapi terlebih dahulu</li>
                                    <li>Apabila hasil pemeriksaan tidak sesuai, lapor Dokter</li>
                                </ol>
                            </div>

                        </div>

                        <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp; <?= ($statuscpo == "undone")? "Simpan" : "Update" ?></button>
                        <!-- <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button> -->
                        <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                        
                        <br /><br />
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            <?php if($statuscpo == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakcpo/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmclpppo").serialize();

            $.ajax({
                <?php if($statuscpo == "undone"): ?>
                    url: "/Bedah_Central/save_ceklist_persiapan_pasien_preoperasi/",
                <?php else: ?>
                    url: "/Bedah_Central/save_ceklist_persiapan_pasien_preoperasi/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Cek List Persiapan Pasien Pre-operasi Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statuscpo == "undone"): ?>
                            swal({
                                html: "Cek List Persiapan Pasien Pre-operasi Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakcpo/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Cek List Persiapan Pasien Pre-operasi Berhasil Diupdate",
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