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
            <div class="caption">PERSETUJUAN UMUM</div>
        </div>
    </div>

    <div class="row">

        <!-- Kolom Pasien -->
        <div class="col-sm-6">
            <div class="portlet box blue">
                <div class="portlet-title" style="border-radius:0px !important">
                    <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Data Pasien</b></div>
                </div>

                <div class="portlet-body" style="border-radius:0px !important">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="col-sm-4">No Rekam Medis</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" value="<?= $jadwal->rekmed ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4">Nama</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" value="<?= $jadwal->namapas ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4">Jenis Kelamin</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" value="<?= ($jadwal->jkel == "P")? "Pria" : "Wanita" ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4">Tanggal Lahir</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" value="<?= $jadwal->tgllahir ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4">Alamat</label>
                                <div class="col-sm-8">
                                    <textarea type="" class="form-control" style="resize:none"><?= $jadwal->alamat ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Persetujuan -->
        <form id="frmpu">
            <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
            <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
            <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">
            <div class="col-sm-6">
                <div class="portlet box green">
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">
                        <div id="myBtnContainer">
                            <button type="button" class="btn green" onclick="filterSelection('1')">Pasien</button>
                            <button type="button"  class="btn" onclick="filterSelection('2')">Wali/Keluarga</button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="filterBox 1">
                                    <div class="row form-horizontal">
                                        <div class="col-md-12">
                                            <?php if($statuspu == "undone"): ?>
                                            <div class="form-group">
                                                <div class="col-sm-4">
                                                    <input type="checkbox" id="autospill" onclick="autoSpill()">
                                                </div>
                                                <label for="autospill" class="col-sm-8">Sama Dengan Data Pasien</label>
                                            </div>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label for="fpudnama" class="col-sm-4">Nama</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="fpudnama" name="fpudnama" class="form-control" value="<?= isset($datapu->nama_p)? ($datapu->nama_p)? $datapu->nama_p : "" : "" ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fpudttl" class="col-sm-4">Tanggal Lahir</label>
                                                <div class="col-sm-8">
                                                    <input type="date" id="fpudttl" name="fpudttl" class="form-control" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fpudalamat" class="col-sm-4">Alamat</label>
                                                <div class="col-sm-8">
                                                    <textarea type="text" id="fpudalamat" name="fpudalamat" class="form-control" style="resize:none"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fpudtlp" class="col-sm-4">No Telepon</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="fpudtlp" name="fpudtlp" class="form-control" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filterBox 2">
                                    <div class="row form-horizontal">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="fpuwnama" class="col-sm-4">Nama</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="fpuwnama" name="fpuwnama" class="form-control" value="<?= isset($datapu->nama_w)? ($datapu->nama_w)? $datapu->nama_w : "" : "" ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fpuwjk" class="col-sm-4">Jenis Kelamin</label>
                                                <div class="col-sm-8">
                                                    <select type="text" id="fpuwjk" name="fpuwjk" class="form-control" value="">
                                                        <option value="">--- Pilih Data ---</option>
                                                        <option value="P" <?= isset($datapu->jkel_w)? ($datapu->jkel_w == "P")? "selected" : "" : "" ?>>Pria</option>
                                                        <option value="W" <?= isset($datapu->jkel_w)? ($datapu->jkel_w == "W")? "selected" : "" : "" ?>>Wanita</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fpuwhub" class="col-sm-4">Hubungan Keluarga</label>
                                                <div class="col-sm-8">
                                                    <select type="text" id="fpuwhub" name="fpuwhub" class="form-control" value="">
                                                        <option value="">--- Pilih Data ---</option>
                                                        <?php
                                                            foreach($hubungank as $hkkey => $hkval):
                                                                if($dval->kodokter == $dataptd->edudoc):
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
                                                <label for="fpuwalamat" class="col-sm-4">Alamat</label>
                                                <div class="col-sm-8">
                                                    <textarea type="text" id="fpuwalamat" name="fpuwalamat" class="form-control" style="resize:none"><?= isset($datapu->alamat_w)? ($datapu->alamat_w)? $datapu->alamat_w : "" : "" ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-actions">
                    <button type="button" id="save" class="btn blue pull-right"><i class="fa fa-save"></i> <b><?= ($statuspu == "done")? "Update" : "Simpan" ?></b></button>
                    <button type="button" id="cetak" class="btn yellow pull-right" style="margin-right:15px"><i class="fa fa-print"></i> <b>Cetak</b></button>
                    <button type="button" onclick="back()" class="btn red pull-right" style="margin-right:15px"><i class="fa fa-times"></i> <b>Tutup</b></button>
                </div>															
            </div>
        </form>

    </div>

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
            autoSpill();
            <?php if($statuspu == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakpu/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmpu").serialize();

            $.ajax({
                <?php if($statuspu == "undone"): ?>
                url: "/Bedah_Central/save_persetujuan/",
                <?php else: ?>
                url: "/Bedah_Central/save_persetujuan/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Persetujuan Umum Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statuspu == "undone"): ?>
                            swal({
                                html: "Persetujuan Umum Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakpu/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Persetujuan Umum Berhasil Diupdate",
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
                var current = document.getElementsByClassName("btn green");
                current[0].className = current[0].className.replace(" green", "");
                this.className += " green";
            });
        }

        function autoSpill(){
            var checkBox    = $("#autospill");

            if (checkBox.is(":checked")){
                $("#fpudnama").val("<?= $jadwal->namapas ?>").prop("readonly", false);
                $("#fpudttl").val("<?= $jadwal->tgllahir ?>").prop("readonly", false);
                $("#fpudalamat").val("<?= $jadwal->alamat ?>").prop("readonly", false);
                $("#fpudtlp").val("<?= $jadwal->phone ?>").prop("readonly", false);
            } else {
                $("#fpudnama").val("<?= isset($datapu->nama_p)? ($datapu->nama_p)? $datapu->nama_p : '' : '' ?>").prop("readonly", false);
                $("#fpudttl").val("<?= isset($datapu->tgllahir_p)? ($datapu->tgllahir_p)? $datapu->tgllahir_p : '' : '' ?>").prop("readonly", false);
                $("#fpudalamat").val("<?= isset($datapu->alamat_p)? ($datapu->alamat_p)? $datapu->alamat_p : '' : '' ?>").prop("readonly", false);
                $("#fpudtlp").val("<?= isset($datapu->phone_p)? ($datapu->phone_p)? $datapu->phone_p : '' : '' ?>").prop("readonly", false);
            }
        }

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>