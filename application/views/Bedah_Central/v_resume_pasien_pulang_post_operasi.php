    <?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        .sub-title {font-size:18px;font-weight:bold;margin-bottom:10px !important;display:block}
        .chb1{
            -ms-transform: scale(2); /* IE */
            -moz-transform: scale(2); /* FF */
            -webkit-transform: scale(2); /* Safari and Chrome */
            -o-transform: scale(2); /* Opera */
            transform: scale(2);
            padding: 50px;
        }
        .chb1 {margin:auto 35px auto 35px !important}
        .chbw {width:15%;text-align:center;margin:auto}
        /* .chb1 .last {margin-right:0px !important} */
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

    <form id="frmrpppo">
        <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
        <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
        <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">

        <div class="row form-horizontal" style="margin-bottom:20px">
            <label class="col-sm-2" style="margin-top:5px">Jenis Operasi</label>
            <input type="hidden" name="kodetarif" value="<?= $jadwal->kodetarif ?>">
            <div class="col-sm-6">
                <input type="text" class="form-control" value="<?= $jenisop ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">
                        <div class="sub-title">I. KEADAAN UMUM PASIEN SAAT PINDAH</div>
                        <div class="table-responsive" style="margin-bottom:20px">
                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td style="width:25%;font-weight:bold">Tingkat Kesadaran</td>
                                        <td colspan="2">
                                            <label class="checkbox-inline"><input type="checkbox" name="tingkatkesadaran" value="1" class="chb" <?= isset($datarpo->tingkatkesadaran)? ($datarpo->tingkatkesadaran == 1)? "checked" : "" : "" ?>>&nbsp; Compos Mentis</label>&emsp;
                                            <label class="checkbox-inline"><input type="checkbox" name="tingkatkesadaran" value="2" class="chb" <?= isset($datarpo->tingkatkesadaran)? ($datarpo->tingkatkesadaran == 2)? "checked" : "" : "" ?>>&nbsp; Apatis</label>&emsp;
                                            <label class="checkbox-inline"><input type="checkbox" name="tingkatkesadaran" value="3" class="chb" <?= isset($datarpo->tingkatkesadaran)? ($datarpo->tingkatkesadaran == 3)? "checked" : "" : "" ?>><input type="text" class="form-control" name="tingkatkesadaranket" style="float:left;display:inline-block;height:25px" placeholder="lainnya.." value="<?= isset($datarpo->tingkatkesadaranket)? $datarpo->tingkatkesadaranket : "" ?>"></label>&emsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3" style="width:25%;font-weight:bold">Obeservasi Terakhir</td>
                                        <td>Tekanan Darah</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tekanandarah" value="<?= isset($datarpo->tekanandarah)? $datarpo->tekanandarah : "" ?>">
                                                <span class="input-group-addon">mmhg</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nadi</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nadi" value="<?= isset($datarpo->nadi)? $datarpo->nadi : "" ?>">
                                                <span class="input-group-addon">x/menit</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pernapasan</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="pernapasan" value="<?= isset($datarpo->pernapasan)? $datarpo->pernapasan : "" ?>">
                                                <span class="input-group-addon">x/menit</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:25%;font-weight:bold">Skala Nyeri&nbsp; <small style="color:red">*</small></td>
                                        <td colspan="2">
                                            <center>
                                                <img src="<?= base_url(). '/assets/img/skalanyeri.png' ?>" width="95%">
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2" class="text-center" rowspan="1">
                                            <br />
                                            <center>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="1" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 1)? "checked" : "" : "" ?>></label>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="2" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 2)? "checked" : "" : "" ?>></label>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="3" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 3)? "checked" : "" : "" ?>></label>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="4" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 4)? "checked" : "" : "" ?>></label>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="5" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 5)? "checked" : "" : "" ?>></label>
                                                <label class="checkbox-inline chbw"><input type="checkbox" class="chb1" name="skalanyeri" value="6" <?= isset($datarpo->skalanyeri)? ($datarpo->skalanyeri == 6)? "checked" : "" : "" ?>></label>
                                            </center>
                                            <br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="sub-title">II. PENDIDIKAN PASIEN DIRUMAH</div>
                        <div class="table-responsive" style="margin-bottom:20px">
                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td style="font-weight:bold;width:25%">Aktivitas</td>
                                        <td><label class="checkbox-inline"><input type="checkbox" class="chb2" name="aktivitas" id="" value="1" <?= isset($datarpo->aktivitas)? ($datarpo->aktivitas == 1)? "checked" : "" : "" ?>>&nbsp; Terbatas</label></td>
                                        <td>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb2" name="aktivitas" id="" value="2" <?= isset($datarpo->aktivitas)? ($datarpo->aktivitas == 2)? "checked" : "" : "" ?>>&nbsp; Terbatas, Jelaskan : <input type="text" class="form-control" name="aktivitasket" style="float:left;display:inline-block;height:25px" value="<?= isset($datarpo->aktivitasket)? $datarpo->aktivitasket : "" ?>"></label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb2" name="aktivitas" id="" value="3" <?= isset($datarpo->aktivitas)? ($datarpo->aktivitas == 3)? "checked" : "" : "" ?>>&nbsp; Posisi Wajah Menghadap Bawah/Atas</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb2" name="aktivitas" id="" value="4" <?= isset($datarpo->aktivitas)? ($datarpo->aktivitas == 4)? "checked" : "" : "" ?>>&nbsp; Posisi Kelapal Miring Ke Kanan/Kiri</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb2" name="aktivitas" id="" value="5" <?= isset($datarpo->aktivitas)? ($datarpo->aktivitas == 5)? "checked" : "" : "" ?>>&nbsp; Posisi Kepala 45°/90°</label><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold;width:25%">Pendidikan Kesehatan</td>
                                        <td>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb3" name="pendidikankes" id="" value="1" <?= isset($datarpo->pendidikankes)? ($datarpo->pendidikankes == 1)? "checked" : "" : "" ?>>&nbsp; Kompres Dingin</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb3" name="pendidikankes" id="" value="2" <?= isset($datarpo->pendidikankes)? ($datarpo->pendidikankes == 2)? "checked" : "" : "" ?>>&nbsp; Ganti Perban</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb3" name="pendidikankes" id="" value="3" <?= isset($datarpo->pendidikankes)? ($datarpo->pendidikankes == 3)? "checked" : "" : "" ?>>&nbsp; Membersihkan Mata</label>
                                        </td>
                                        <td>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb5" name="pendidikankes2" id="" value="1" <?= isset($datarpo->pendidikankes2)? ($datarpo->pendidikankes2 == 1)? "checked" : "" : "" ?>>&nbsp; Menggunakan Obat Tetes</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb5" name="pendidikankes2" id="" value="2" <?= isset($datarpo->pendidikankes2)? ($datarpo->pendidikankes2 == 2)? "checked" : "" : "" ?>>&nbsp; Menggunakan Obat Salep</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb5" name="pendidikankes2" id="" value="3" <?= isset($datarpo->pendidikankes2)? ($datarpo->pendidikankes2 == 3)? "checked" : "" : "" ?>>&nbsp; Menggunakan Obat Oral</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold;width:25%">Terapi Diet</td>
                                        <td colspan="2">
                                            <label class="checkbox-inline"><input type="checkbox" class="chb4" name="terapidiet" id="" value="1" <?= isset($datarpo->terapidiet)? ($datarpo->terapidiet == 1)? "checked" : "" : "" ?>>&nbsp; Normal</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb4" name="terapidiet" id="" value="2" <?= isset($datarpo->terapidiet)? ($datarpo->terapidiet == 2)? "checked" : "" : "" ?>>&nbsp; Lain-Lain, Jelaskan : <input type="text" class="form-control" name="terapidietket" style="float:left;display:inline-block;height:25px" value="<?= isset($datarpo->terapidietket)? $datarpo->terapidietket : "" ?>"></label><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold;width:25%">Instruksi Kontrol</td>
                                        <td colspan="2">
                                            <b>Dengan Dokter</b><br />
                                            <p>
                                                <select type="text" id="dokter" name="dokter" class="form-control" style="height:30px;padding:0px;width:50%">
                                                    <option value="">--- Pilih Data ---</option>
                                                    <?php 
                                                        foreach($dokter as $dkey => $dval): 
                                                            if($dval->kodokter == $datarpo->dokter):
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

                                            <b>Tanggal</b><br />
                                            <p>
                                                <input type="datetime" class="form-control" name="tanggal" value="<?= isset($datarpo->tanggal)? $datarpo->tanggal : date("Y-m-d H:i:s") ?>" style="height:30px;padding:0px;width:50%">
                                            </p>

                                            <b>No Pendaftaran</b><br />
                                            <p>
                                                <input type="text" class="form-control" name="nopendaftaran" value="<?= isset($datarpo->nopendaftaran)? $datarpo->nopendaftaran : $jadwal->nojadwal ?>" style="height:30px;padding:0px;width:50%">
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="sub-title">III. EVALUASI PENDIDIKAN KEPADA PASIEN DAN KELUARGA</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td style="font-weight:bold;width:33.3333333333%">Evaluasi Pemahaman</td>
                                        <td style="font-weight:bold;width:33.3333333333%">Hambatan Pada Pasien</td>
                                        <td style="font-weight:bold;width:33.3333333333%">Intervensi Hambatan</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Pemaham verbal</p>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb6" name="evaluasipemahaman" id="" value="1" <?= isset($datarpo->evaluasipemahaman)? ($datarpo->evaluasipemahaman == 1)? "checked" : "" : "" ?>>&nbsp; Mengerti</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb6" name="evaluasipemahaman" id="" value="2" <?= isset($datarpo->evaluasipemahaman)? ($datarpo->evaluasipemahaman == 2)? "checked" : "" : "" ?>>&nbsp; Perlu Diulang</label><br />
                                        </td>
                                        <td>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb7" name="hambatanpadapasien" id="" value="1" <?= isset($datarpo->hambatanpadapasien)? ($datarpo->hambatanpadapasien == 1)? "checked" : "" : "" ?>>&nbsp; Tidak Ada</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb7" name="hambatanpadapasien" id="" value="2" <?= isset($datarpo->hambatanpadapasien)? ($datarpo->hambatanpadapasien == 2)? "checked" : "" : "" ?>>&nbsp; Penglihatan Teranggu</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb7" name="hambatanpadapasien" id="" value="3" <?= isset($datarpo->hambatanpadapasien)? ($datarpo->hambatanpadapasien == 3)? "checked" : "" : "" ?>>&nbsp; Pendengaran</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb7" name="hambatanpadapasien" id="" value="4" <?= isset($datarpo->hambatanpadapasien)? ($datarpo->hambatanpadapasien == 4)? "checked" : "" : "" ?>>&nbsp; Bahasa</label><br />
                                            <p><label class="checkbox-inline"><input type="checkbox" class="chb7" name="hambatanpadapasien" id="" value="5" <?= isset($datarpo->hambatanpadapasien)? ($datarpo->hambatanpadapasien == 5)? "checked" : "" : "" ?>>&nbsp; Lainnya :</label></p>
                                            <input type="text" class="form-control" name="hambatanpadapasienket" style="height:30px;padding:0px" value="<?= isset($datarpo->hambatanpadapasienket)? $datarpo->hambatanpadapasienket : "" ?>">
                                        </td>
                                        <td>
                                            <label class="checkbox-inline"><input type="checkbox" class="chb8" name="intervensihambatan" id="" value="1" <?= isset($datarpo->intervensihambatan)? ($datarpo->intervensihambatan == 1)? "checked" : "" : "" ?>>&nbsp; Pendidikan Diberikan Kepada keluarga</label><br />
                                            Pendidikan Diberikan Melalui :</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb8" name="intervensihambatan" id="" value="2" <?= isset($datarpo->intervensihambatan)? ($datarpo->intervensihambatan == 2)? "checked" : "" : "" ?>>&nbsp; Lisan</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb8" name="intervensihambatan" id="" value="3" <?= isset($datarpo->intervensihambatan)? ($datarpo->intervensihambatan == 3)? "checked" : "" : "" ?>>&nbsp; Brosur</label><br />
                                            <label class="checkbox-inline"><input type="checkbox" class="chb8" name="intervensihambatan" id="" value="4" <?= isset($datarpo->intervensihambatan)? ($datarpo->intervensihambatan == 4)? "checked" : "" : "" ?>>&nbsp; Demonstrasi</label>
                                            <p><label class="checkbox-inline"><input type="checkbox" class="chb8" name="intervensihambatan" id="" value="5" <?= isset($datarpo->intervensihambatan)? ($datarpo->intervensihambatan == 5)? "checked" : "" : "" ?>>&nbsp; Lainnya :</label></p>
                                            <input type="text" class="form-control" name="intervensihambatanket" style="height:30px;padding:0px" value="<?= isset($datarpo->intervensihambatanket)? $datarpo->intervensihambatanket : "" ?>">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp;  <?= ($statusrpo == "undone")? "Simpan" : "Update" ?></button>
                <button type="button" class="btn yellow btn-md pull-right" id="ctk" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
            </div>
        </div>
        <br />
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
            <?php if($statusrpo == "undone"): ?>
            $("#ctk").hide();
            <?php else: ?>
            $("#ctk").attr("onclick", "window.open('/Bedah_Central/cetakrpo/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmrpppo").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statusrpo == "undone"): ?>
                    url: "/Bedah_Central/save_resume_pasien_pulang_post_operasi/",
                <?php else: ?>
                    url: "/Bedah_Central/save_resume_pasien_pulang_post_operasi/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Resume Pasien Pulang Post Operasi Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statusrpo == "undone"): ?>
                            swal({
                                html: "Resume Pasien Pulang Post Operasi Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakrpo/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Resume Pasien Pulang Post Operasi Berhasil Diupdate",
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

        $(".chb").change(function() {
            $(".chb").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb1").change(function() {
            $(".chb1").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb2").change(function() {
            $(".chb2").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb3").change(function() {
            $(".chb3").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb4").change(function() {
            $(".chb4").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb5").change(function() {
            $(".chb5").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb6").change(function() {
            $(".chb6").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb7").change(function() {
            $(".chb7").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb8").change(function() {
            $(".chb8").prop('checked', false);
            $(this).prop('checked', true);
        });

        $(".chb9").change(function() {
            $(".chb9").prop('checked', false);
            $(this).prop('checked', true);
        });

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>