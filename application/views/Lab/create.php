<?php
    $this->load->view('template/header');
    $this->load->view('template/body');

    $elab   = $this->input->get("elab");
    if(isset($elab)){
        $noelab         = $this->input->get("noelab");
        $cabang         = $this->session->userdata("unit");

        $helab          = $this->db->query("SELECT * FROM tbl_orderperiksa WHERE orderno = '$noelab' AND koders = '$cabang'")->row();
        $delab          = $this->db->query("SELECT * FROM tbl_elab WHERE notr = '$noelab'")->result();
        $pasrsp         = $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '$helab->noreg'")->row();
        $rekmed         = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$helab->noreg' AND koders = '$cabang'")->row();

        if($pasrsp){
            $age_date     = new DateTime($pasrsp->tgllahir);
            $age_now      = new DateTime();
            $age_interval = $age_now->diff($age_date);
        } else {
            $age_interval = (object) array(
                "y"  => 0,
                "m"  => 0,
                "d"  => 0,
            );
        }

        $umur			    = $age_interval->y .' Tahun '. $age_interval->m .' Bulan '. $age_interval->d .' Hari';

        if(!isset($noelab)){
            redirect("/lab");
        }
    }
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<style>
    .toolbar {float:left}
    .modal {text-align:center;padding:0!important}
    .modal:before {content:'';display:inline-block;height:100%;vertical-align:middle;margin-right: -4px;}
    .modal-dialog {display:inline-block;text-align:left;vertical-align:middle}
    @media screen and (max-width:720px){.modal-content {width:100%;margin:auto}}
</style>

<!-- Modal No Register -->
<div id="noregister" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width:100%;margin:auto;height:100vh;overflow:auto;position:relative">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight:bold">Daftar Pasien</h4>
            </div>
            <div class="modal-body">
            <table class="table table-bordered table-striped" id="tblnoreg" style="margin:auto !important">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="text-center title-white"></th>
                            <th class="text-center title-white">NO REG</th>
                            <th class="text-center title-white">REKMED</th>
                            <th class="text-center title-white">NAMA PASIEN</th>
                            <th class="text-center title-white">TGL MASUK</th>
                            <th class="text-center title-white">LAYANAN</th>
                            <th class="text-center title-white">TUJUAN</th>
                            <th class="text-center title-white">JENIS PASIEN</th>
                            <th class="text-center title-white">DOKTER</th>
                            <th class="text-center title-white">NO SEP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listreg as $lkey => $lval){ ?>
                        <tr>
                            <td><button type="button" onclick="spilldata('<?= $lval->no_registrasi ?>')" class="btn btn-success btn-xs">Pilih</button></td>
                            <td><?= $lval->no_registrasi ?></td>
                            <td><?= $lval->rekam_medis ?></td>
                            <td><?= $lval->nama_pasien ?></td>
                            <td><?= $lval->tanggal_masuk ?></td>
                            <td><?= $lval->layanan ?></td>
                            <td><?= $lval->tujuan ?></td>
                            <td><?= $lval->jenis_pasien ?></td>
                            <td><?= $lval->dokter ?></td>
                            <td><?= $lval->nosep ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?= $this->session->userdata("unit"); ?>
                </span>&nbsp;
                -
                &nbsp;<span class="title-web"><?= $menu; ?> <small> <?= $title; ?></small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url(); ?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white"><?= $menu; ?> </a></a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white"><?= $title; ?> </a></a></li>
            </ul>
        </div>
    </div>

    <h3 style="color:green;margin:0 !important"><b>PEMERIKSAAN DAN BILLING LABORATORIUM</b></h3>

    <hr />

<form id="frmlab">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">

                <!-- CONTENT START -->

                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-bars fa-fw"></i>&nbsp; <?= ($status == "save")? "Form Entri" : "Form Edit" ?></div>
                </div>
                
                <?php if(isset($elab)): ?>
                <input type="hidden" name="asal" value="<?= $helab->asal ?>">
                <div class="portlet-body" style="padding:20px !important;border-radius:0px !important">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Pemeriksaan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nolaborat" readonly>
                                <input type="hidden" name="nolaborat" id="nolaborathide" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllab" class="col-sm-3 col-form-label">Tanggal Periksa</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgllab" name="tgllab" placeholder="" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Registrasi</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input id="noreg" type="text" class="form-control" name="noreg" placeholder="No registrasi" value="<?= $helab->noreg ?>" readonly>
                                    <div class="input-group-btn">
                                        <button class="btn green" type="button" data-toggle="modal" data-target="#noregister"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rekmed" name="rekmed" placeholder="No rekam medis" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="namapas" class="col-sm-3 col-form-label">Nama Pasien</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="namapas" name="namapas" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" placeholder="" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="umurth" class="col-sm-3 col-form-label">Umur</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="umur" name="umur" readonly>
                            </div>
                            <!-- <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurth" name="umurth" placeholder="Tahun" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurbl" name="umurbl" placeholder="Bulan" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurhr" name="umurhr" placeholder="Hari" required>
                            </div> -->
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Kalamin</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jkel" id="jeniskelamin1" required></td>
                                    <td style="width:45%">Pria</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jkel" id="jeniskelamin2" required></td>
                                    <td style="width:45%">Wanita</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-2 " style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="1" name="jkel" id="jenis_kelamin1" placeholder="" required> <label for="jenis_kelamin1" style="margin-left: 20px;">Pria</label>
                            </div>
                            <div class="col-sm-2" style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="2" name="jkel" id="jenis_kelamin2" placeholder="" required> <label for="jenis_kelamin2" style="margin-left: 20px;" >Wanita</label>
                            </div> -->
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="orderno" class="col-sm-3 col-form-label">No Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="orderno" name="orderno" placeholder="" value="<?= $noelab ?>" required readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pasien</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jpas" required></td>
                                    <td style="width:45%">Pasien RS</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jpas" required></td>
                                    <td style="width:45%">Pasien Luar</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="1" id="jpas1" name="jpas" placeholder=""> <label for="jpas1" style="margin-left: 20px;">Pasien RS</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="2" id="jpas2" name="jpas" placeholder=""> <label for="jpas2" style="margin-left: 20px;">Pasien Luar</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pemeriksaan</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jenisperiksa" required></td>
                                    <td style="width:45%">Rujukan Dokter</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jenisperiksa" required></td>
                                    <td style="width:45%">Permintaan Sendiri</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="jenisperiksa1" name="jenisperiksa" placeholder=""> <label for="jenisperiksa1" style="margin-left: 20px;">Rujukan Dokter</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="jenisperiksa2" name="jenisperiksa" placeholder=""> <label for="jenisperiksa2" style="margin-left: 20px;">Permintaan Sendiri</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Pemeriksaan</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="rujuk" required></td>
                                    <td style="width:45%">Lab Dalam</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="rujuk" required></td>
                                    <td style="width:45%">Dikirim Ke Faskes Lain</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="rujuk1" name="rujuk" placeholder=""> <label for="rujuk1" style="margin-left: 20px;">Lab Dalam</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="rujuk2" name="rujuk" placeholder=""> <label for="rujuk2" style="margin-left: 20px;">Dikirim Ke Faskes Lain</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="diagnosa" class="col-sm-3 col-form-label">Diagnosa</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control selectpicker" id="diagnosa" name="diagnosa" placeholder="" value="<?= $rekmed->diagnosa ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="drperiksa" class="col-sm-3 col-form-label">Dokter Pengirim</label>
                            <div class="col-sm-9">
                                <select required  id="drperiksa" name="drperiksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        $unit   = $this->session->userdata("unit");
                                        $data_dokter    = $this->db->query("SELECT * FROM dokter WHERE koders = '$unit' AND kopoli = '$rekmed->kodepos'")->result();
                                        foreach($data_dokter as $value){
                                            if($value->kodokter == $rekmed->kodokter){
                                                echo '<option value="'. $rekmed->kodokter .'" selected>'. data_master("dokter", array("kodokter" => $rekmed->kodokter, "koders" => $unit, "kopoli" => $rekmed->kodepos))->nadokter .'</option>';
                                            } else {
                                                echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="drpengirim" class="col-sm-3 col-form-label">Dokter Laborat</label>
                            <div class="col-sm-9">
                                <select required id="drpengirim" name="drpengirim" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        foreach($dataDokter as $value){
                                            echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                        }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Petugas Lab</label>
                            <div class="col-sm-9">
                                <select required id="kodepetugas" name="kodepetugas" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        foreach($petugas as $value){
                                            echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                </div>
                <?php else: ?>
                <input type="hidden" name="asal" value="<?= isset($data_header->asal)? $data_header->asal : ""  ?>">
                <div class="portlet-body" style="padding:20px !important;border-radius:0px !important">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Pemeriksaan</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="nolaborat" id="nolaborat" value="<?= isset($data_header->nolaborat)? $data_header->nolaborat : "" ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllab" class="col-sm-3 col-form-label">Tanggal Periksa</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgllab" name="tgllab" placeholder="" value="<?= isset($data_header->tgllab)? date("Y-m-d", strtotime($data_header->tgllab)) : date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Registrasi</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input id="noreg" type="text" class="form-control" name="noreg" placeholder="No registrasi" value="<?= isset($data_header->noreg)? $data_header->noreg : "" ?>" readonly>
                                    <div class="input-group-btn">
                                        <button class="btn green" type="button" data-toggle="modal" data-target="#noregister"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rekmed" name="rekmed" placeholder="No rekam medis" value="<?= isset($data_header->rekmed)? $data_header->rekmed : "" ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="namapas" class="col-sm-3 col-form-label">Nama Pasien</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="namapas" name="namapas" placeholder="" value="<?= isset($data_header->namapas)? $data_header->namapas : "" ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" placeholder="" value="<?= isset($data_header->tgllahir)? date("Y-m-d", strtotime($data_header->tgllahir)) : "" ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="umurth" class="col-sm-3 col-form-label">Umur</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="umur" name="umur" readonly>
                            </div>
                            <!-- <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurth" name="umurth" placeholder="Tahun" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurbl" name="umurbl" placeholder="Bulan" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurhr" name="umurhr" placeholder="Hari" required>
                            </div> -->
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Kalamin</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jkel" id="jeniskelamin1" <?= isset($data_header->jkel)? ($data_header->jkel == "1")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Pria</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jkel" id="jeniskelamin2" <?= isset($data_header->jkel)? ($data_header->jkel == "2")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Wanita</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-2 " style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="1" name="jkel" id="jenis_kelamin1" placeholder="" required> <label for="jenis_kelamin1" style="margin-left: 20px;">Pria</label>
                            </div>
                            <div class="col-sm-2" style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="2" name="jkel" id="jenis_kelamin2" placeholder="" required> <label for="jenis_kelamin2" style="margin-left: 20px;" >Wanita</label>
                            </div> -->
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="orderno" class="col-sm-3 col-form-label">No Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="orderno" name="orderno" placeholder="" value="<?= isset($data_header->orderno)? $data_header->orderno : "" ?>" required readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pasien</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jpas" <?= isset($data_header->jpas)? ($data_header->jpas == "1")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Pasien RS</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jpas" <?= isset($data_header->jpas)? ($data_header->jpas == "2")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Pasien Luar</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="1" id="jpas1" name="jpas" placeholder=""> <label for="jpas1" style="margin-left: 20px;">Pasien RS</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="2" id="jpas2" name="jpas" placeholder=""> <label for="jpas2" style="margin-left: 20px;">Pasien Luar</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pemeriksaan</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="jenisperiksa" <?= isset($data_header->jenisperiksa)? ($data_header->jenisperiksa == "1")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Rujukan Dokter</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="jenisperiksa" <?= isset($data_header->jenisperiksa)? ($data_header->jenisperiksa == "2")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Permintaan Sendiri</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="jenisperiksa1" name="jenisperiksa" placeholder=""> <label for="jenisperiksa1" style="margin-left: 20px;">Rujukan Dokter</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="jenisperiksa2" name="jenisperiksa" placeholder=""> <label for="jenisperiksa2" style="margin-left: 20px;">Permintaan Sendiri</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Pemeriksaan</label>
                            <table class="col-sm-7">
                                <tr>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="1" name="rujuk" <?= isset($data_header->rujuk)? ($data_header->rujuk == "1")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Lab Dalam</td>
                                    <td style="width:5%;padding-left:10px"><input type="radio" value="2" name="rujuk" <?= isset($data_header->rujuk)? ($data_header->rujuk == "2")? "checked" : "" : "" ?> required></td>
                                    <td style="width:45%">Dikirim Ke Faskes Lain</td>
                                </tr>
                            </table>
                            <!-- <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="rujuk1" name="rujuk" placeholder=""> <label for="rujuk1" style="margin-left: 20px;">Lab Dalam</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="rujuk2" name="rujuk" placeholder=""> <label for="rujuk2" style="margin-left: 20px;">Dikirim Ke Faskes Lain</label>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="diagnosa" class="col-sm-3 col-form-label">Diagnosa</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control selectpicker" id="diagnosa" name="diagnosa" placeholder="" value="<?= isset($data_header->diagnosa)? $data_header->diagnosa : "" ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="drperiksa" class="col-sm-3 col-form-label">Dokter Pengirim</label>
                            <div class="col-sm-9">
                                <select required  id="drperiksa" name="drperiksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        $unit   = $this->session->userdata("unit");
                                        $data_dokter    = $this->db->query("SELECT * FROM dokter WHERE koders = '$unit' GROUP BY kodokter")->result();
                                        foreach($data_dokter as $value){
                                            if(isset($data_header->drperiksa)){
                                                if($value->kodokter == $data_header->drperiksa){
                                                    echo '<option value="'. $data_header->drperiksa .'" selected>'. data_master("dokter", array("kodokter" => $data_header->drperiksa, "koders" => $this->session->userdata("unit")))->nadokter .'</option>';
                                                } else {
                                                    echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                                }
                                            } else {
                                                echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="drpengirim" class="col-sm-3 col-form-label">Dokter Laborat</label>
                            <div class="col-sm-9">
                                <select required id="drpengirim" name="drpengirim" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        foreach($dataDokter as $value){
                                            if(isset($data_header->drpengirim)){
                                                if($value->kodokter == $data_header->drpengirim){
                                                    echo '<option value="'. $data_header->drpengirim .'" selected>'. data_master("dokter", array("kodokter" => $data_header->drperiksa, "koders" => $this->session->userdata("unit")))->nadokter .'</option>';
                                                } else {
                                                    echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                                }
                                            } else {
                                                echo '<option value="'. $value->kodokter .'">'. $value->nadokter .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Petugas Lab</label>
                            <div class="col-sm-9">
                                <select required id="kodepetugas" name="kodepetugas" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                        foreach($petugas as $value){
                                            if(isset($data_header->kodepetugas)){
                                                if($value->nokk == $data_header->kodepetugas){
                                                    echo '<option value="'. $data_header->kodepetugas .'" selected>'. data_master("tbl_petugas", array("nokk" => $data_header->kodepetugas, "kodepos" => "LABOR"))->namapetugas .'</option>';
                                                } else {
                                                    echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                                }
                                            } else {
                                                echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                </div>
                <?php endif; ?>

                <div class="portlet-body" style="padding:20px !important;margin-bottom:0px !important;border-radius:0px !important">
                    <div class="form-body">
                        <div class="tabbable tabbable-custom tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active" id="billing">
                                    <a href="<?= "/lab/addDataPemeriksaan/". $_SERVER["QUERY_STRING"] ?>#tab1" data-toggle="tab" style="font-weight:bold">BILLING</a>
                                </li>
                                <li class="" id="bhp">
                                    <a href="<?= "/lab/addDataPemeriksaan/". $_SERVER["QUERY_STRING"] ?>#tab2" data-toggle="tab" style="font-weight:bold">BHP</a>
                                </li>
                                <li class="" id="hasil">
                                    <a href="<?= "/lab/addDataPemeriksaan/". $_SERVER["QUERY_STRING"] ?>#tab3" data-toggle="tab" style="font-weight:bold">HASIL</a>
                                </li>
                                <!-- <li class="" id="">
                                    <a href="<?= "/lab/addDataPemeriksaan/". $_SERVER["QUERY_STRING"] ?>#tab4" data-toggle="tab" style="font-weight:bold">Test Hasil</a>
                                </li> -->
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                <h4 style="color:green"><b>BILLING</b></h4>
                                <hr />
                                <div class="table-repsonsive">
                                    <table class="table table-bordered table-striped" id="billing_table" style="width:100%">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr class="title-white">
                                                <th style="width:5%">Delete</th>
                                                <th style="width:20%">Tindakan</th>
                                                <th style="width:15%">Qty</th>
                                                <th style="width:15%">Tarif Rp</th>
                                                <th style="width:5%">Cito</th>
                                                <th style="width:15%">Cito Rp</th>
                                                <th style="width:20%">Total Biaya</th>
                                            </tr>
                                        </thead>
                                        <tbody id="billing_body">
                                            <?php 
                                                if(isset($elab)): 

                                                $noelab = 1;
                                                foreach($delab as $dkey => $dval):
                                            ?>
                                                <tr id="billing_row1">
                                                    <td>
                                                        <center>
                                                            <button type="button" class="btn red" onclick="hapusBilling(<?= $noelab ?>)"><i class="fa fa-trash"></i></button>
                                                        </center>
                                                        <input type="hidden" name="billing_tarifrs[]" id="billing_tarifrs<?= $noelab ?>" value="<?= $dval->tarif ?>">
                                                        <input type="hidden" name="billing_tarifdr[]" id="billing_tarifdr<?= $noelab ?>" value="<?= $dval->tarif ?>">
                                                        <input type="hidden" id="billing_citorphide<?= $noelab ?>" value="">
                                                    </td>
                                                    <td>
                                                        <select type="text" class="form-control selectpicker" name="billing_tindakan[]" id="billing_tindakan<?= $noelab ?>" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, <?= $noelab ?>)">
                                                            <option value="">--- Pilih Tindakan ---</option>
                                                            <?php foreach($listtindakan as $leval): ?>
                                                                <?php if($leval->kodeid == $dval->kodetarif): ?>
                                                                    <option value="<?= $dval->kodetarif ?>" selected>[ <?= $dval->kodetarif ?> ] - [ <?= $dval->tindakan ?> ]</option>
                                                                <?php else: ?>
                                                                    <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="billing_qty[]" id="billing_qty<?= $noelab ?>" value="1" onkeyup="qty(<?= $noelab ?>)"></td>
                                                    <td><input type="text" class="form-control" name="billing_tarifrp[]" id="billing_tarifrp<?= $noelab ?>" value="<?= number_format($dval->tarifrs + $dval->tarifdr, 2, '.', ',') ?>" readonly></td>
                                                    <td>
                                                        <center><input type="checkbox" class="form-checkbox" id="billing_cito<?= $noelab ?>" onchange="cito(<?= $noelab ?>)"></center>
                                                        <input type="hidden" name="billing_cito[]" id="billing_citohide<?= $noelab ?>" value="0">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="billing_citorp[]" id="billing_citorp<?= $noelab ?>" readonly></td>
                                                    <td><input type="text" class="form-control" name="billing_totalbiaya[]" id="billing_totalbiaya<?= $noelab ?>" value="<?= number_format($dval->tarifrs + $dval->tarifdr, 2, '.', ',') ?>"></td>
                                                </tr>
                                            <?php $noelab++; endforeach; else: ?>
                                                <?php if($status == "save"){ ?>
                                                    <tr id="billing_row1">
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn red" onclick="hapusBilling(1)"><i class="fa fa-trash"></i></button>
                                                            </center>
                                                            <input type="hidden" name="billing_tarifrs[]" id="billing_tarifrs1" value="">
                                                            <input type="hidden" name="billing_tarifdr[]" id="billing_tarifdr1" value="">
                                                            <input type="hidden" id="billing_citorphide1" value="">
                                                        </td>
                                                        <td>
                                                            <select type="text" class="form-control selectpicker" name="billing_tindakan[]" id="billing_tindakan1" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, 1)">
                                                                <option value="">--- Pilih Tindakan ---</option>
                                                                <?php foreach($listtindakan as $leval): ?>
                                                                    <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control" name="billing_qty[]" id="billing_qty1" value="1" onkeyup="qty(1)"></td>
                                                        <td><input type="text" class="form-control" name="billing_tarifrp[]" id="billing_tarifrp1" readonly></td>
                                                        <td>
                                                            <center><input type="checkbox" class="form-checkbox" id="billing_cito1" onchange="cito(1)"></center>
                                                            <input type="hidden" name="billing_cito[]" id="billing_citohide1" value="0">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="billing_citorp[]" id="billing_citorp1" readonly></td>
                                                        <td><input type="text" class="form-control" name="billing_totalbiaya[]" id="billing_totalbiaya1"></td>
                                                    </tr>
                                                <?php 
                                                    } else {
                                                        $nobill = 1;
                                                        $list_billing   = $this->db->query("SELECT * FROM tbl_dlab WHERE nolaborat = '$data_header->nolaborat'")->result();

                                                        foreach($list_billing as $lbkey => $lbval){
                                                ?>
                                                    <tr id="billing_row<?= $nobill ?>">
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn red" onclick="hapusBilling(<?= $nobill ?>)"><i class="fa fa-trash"></i></button>
                                                            </center>
                                                            <input type="hidden" name="billing_tarifrs[]" id="billing_tarifrs<?= $nobill ?>" value="<?= number_format($lbval->tarifrs, 2, '.', ',') ?>">
                                                            <input type="hidden" name="billing_tarifdr[]" id="billing_tarifdr<?= $nobill ?>" value="<?= number_format($lbval->tarifdr, 2, '.', ',') ?>">
                                                            <input type="hidden" id="billing_citorphide<?= $nobill ?>" value="<?= number_format($lbval->citorp, 2, '.', ',') ?>">
                                                        </td>
                                                        <td>
                                                            <select type="text" class="form-control selectpicker" name="billing_tindakan[]" id="billing_tindakan<?= $nobill ?>" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, <?= $nobill ?>)">
                                                                <option value="">--- Pilih Tindakan ---</option>
                                                                <?php foreach($listtindakan as $leval): ?>
                                                                    <?php if($leval->kodeid == $lbval->kodetarif): ?>
                                                                        <option value="<?= $lbval->kodetarif ?>" selected>
                                                                            [<?= $lbval->kodetarif?>] - [<?= data_master("daftar_tarif_nonbedah", array("kodetarif" => $lbval->kodetarif, "kodepos" => "LABOR"))->tindakan ?>]
                                                                        </opiton>
                                                                    <?php else: ?>
                                                                        <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control" name="billing_qty[]" id="billing_qty<?= $nobill ?>" onkeyup="qty(<?= $nobill ?>)" value="<?= $lbval->qty ?>"></td>
                                                        <td><input type="text" class="form-control" name="billing_tarifrp[]" id="billing_tarifrp<?= $nobill ?>" value="<?= number_format($lbval->tarifrp, 2, '.', ',') ?>" readonly></td>
                                                        <td>
                                                            <center><input type="checkbox" class="form-checkbox" id="billing_cito<?= $nobill ?>" value="<?= $lbval->jenis ?>" onchange="cito(<?= $nobill ?>)" <?= ($lbval->jenis == "1")? "checked" : "" ?>></center>
                                                            <input type="hidden" name="billing_cito[]" id="billing_citohide<?= $nobill ?>" value="<?= $lbval->jenis ?>">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="billing_citorp[]" id="billing_citorp<?= $nobill ?>" value="<?= number_format($lbval->citorp, 2, '.', ',') ?>" readonly></td>
                                                        <td><input type="text" class="form-control" name="billing_totalbiaya[]" id="billing_totalbiaya<?= $nobill ?>" value="<?= number_format($lbval->totalrp, 2, '.', ',') ?>"></td>
                                                    </tr>
                                                <?php $nobill++; } } ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <br />
                                    <div style="position:relative">
                                        <div class="form-inline" style="position:absolute;right:0">
                                            <label for="grand_total"><b>Total Billing</b></label>&emsp;
                                            <input type="text" class="form-control" id="grand_total" readonly>
                                        </div>
                                        <button class="btn green" type="button" onclick="tambahBilling()"><i class="fa fa-plus fa-fw"></i>&nbsp; Tambah Tindakan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                <h4 style="color:green"><b>BHP</b></h4>
                                <hr />
                                <div class="table-repsonsive">
                                    <table class="table table-bordered table-striped" id="bhp_table" style="width:100%">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr class="title-white">
                                                <th style="width:5%">Delete</th>
                                                <th style="width:5%">Bill</th>
                                                <th style="width:20%">Nama Barang</th>
                                                <th style="width:10%">Satuan</th>
                                                <th style="width:10%">Qty</th>
                                                <th style="width:17%">Harga</th>
                                                <th style="width:17%">Total Harga</th>
                                                <th style="width:16%">Lokasi Barang</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bhp_body">
                                            <?php if($status == "save"){ ?>
                                            <tr id="bhp_row1">
                                                <td>
                                                    <center>
                                                        <button type="button" class="btn red" onclick="hapusBHP(1)"><i class="fa fa-trash"></i></button>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><input type="checkbox" class="form-checkbox" id="bhp_bill1" onchange="bill(1)"></center>
                                                    <input type="hidden" name="bhp_bill[]" id="bhp_billhide1" value="0">
                                                </td>
                                                <td>
                                                    <select type="text" class="form-control select2_el_alkes input-medium" name="bhp_barang[]" id="bhp_barang1" onchange="show_bhp(this.value, 1)"></select>
                                                </td>
                                                <td><input type="text" class="form-control" name="bhp_satuan[]" id="bhp_satuan1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_qty[]" id="bhp_qty1" onkeyup="qtyBHP(1)" value="1"></td>
                                                <td><input type="text" class="form-control" name="bhp_harga[]" id="bhp_harga1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_total[]" id="bhp_totalharga1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_gudang[]" id="bhp_gudang1" value="LABOR"></td>
                                            </tr>
                                            <?php 
                                                } else { 
                                                    $nobhp      = 1;
                                                    $list_bhp   = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '$data_header->nolaborat'")->result();

                                                    foreach($list_bhp as $lbkey => $lbval){
                                            ?>
                                            <tr id="bhp_row1">
                                                <td>
                                                    <center>
                                                        <button type="button" class="btn red" onclick="hapusBHP(<?= $nobhp ?>)"><i class="fa fa-trash"></i></button>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><input type="checkbox" class="form-checkbox" id="bhp_bill<?= $nobhp ?>" onchange="bill(<?= $nobhp ?>)" <?= ($lbval->dibebankan == "1")? "checked" : "" ?>></center>
                                                    <input type="hidden" name="bhp_bill[]" id="bhp_billhide<?= $nobhp ?>" value="<?= $lbval->dibebankan ?>">
                                                </td>
                                                <td>
                                                    <select type="text" class="form-control select2_el_alkes input-medium" name="bhp_bhp_barang[]" id="bhp_barang<?= $nobhp ?>" onchange="show_bhp(this.value, <?= $nobhp ?>)">
                                                        <option value="<?= $lbval->kodeobat ?>" selected>
                                                            <?php $kodeobat = data_master("tbl_barang", array("kodebarang" => $lbval->kodeobat)); ?>
                                                            [ <?= $kodeobat->kodebarang ?> ] - [ <?= $kodeobat->namabarang ?> ]
                                                        </opiton>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="bhp_satuan[]" value="<?= $lbval->satuan ?>" id="bhp_satuan<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_qty[]" value="<?= number_format($lbval->qty, 0 ,',' ,'.') ?>" id="bhp_qty<?= $nobhp ?>" onkeyup="qtyBHP(<?= $nobhp ?>)"></td>
                                                <td><input type="text" class="form-control" name="bhp_harga[]" value="<?= number_format($lbval->harga, 2 ,'.', ',') ?>" id="bhp_harga<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_total[]" value="<?= number_format($lbval->totalharga, 2 ,'.', ',') ?>" id="bhp_totalharga<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp_gudang[]" value="<?= $lbval->gudang ?>" id="bhp_gudang<?= $nobhp ?>" value="LABOR"></td>
                                            </tr>
                                            <?php $nobhp++; } } ?>
                                        </tbody>
                                    </table>
                                    <br />
                                    <div style="position:relative">
                                        <div class="form-inline" style="position:absolute;right:0">
                                            <label for="grand_total_bhp"><b>Total</b></label>&emsp;
                                            <input type="text" class="form-control" id="grand_total_bhp" readonly>
                                        </div>
                                        <button class="btn green" type="button" onclick="tambahBHP()"><i class="fa fa-plus fa-fw"></i>&nbsp; Tambah Barang</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <?php if($status == "save"): ?>
                                <div class="alert alert-danger text-center">
                                    <b><?= strtoupper("Isi Billing Terlebih Dahulu") ?></b>
                                </div>
                            <?php else: ?>
                                <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                    <h4 style="color:green"><b>HASIL</b></h4>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-6 form-horizontal">
                                            <div class="row">
                                                <div class="col-sm-4">Tgl Sampel Diambil</div>
                                                <div class="col-sm-4"><input type="date" class="form-control" name="tglsampel" id="tglsampel" value="<?= str_replace(" 00:00:00", "", $data_header->tglsampel) ?>"></div>
                                                <div class="col-sm-4"><input type="time" class="form-control" name="jamsampel" id="jamsampel" value="<?= $data_header->jamsampel ?>"></div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-sm-4">Tgl Selesai Periksa</div>
                                                <div class="col-sm-4"><input type="date" class="form-control" name="tglselesai" id="tglselesai" value="<?= str_replace(" 00:00:00", "", $data_header->tglselesai) ?>"></div>
                                                <div class="col-sm-4"><input type="time" class="form-control" name="jamselesai" id="jamselesai" value="<?= $data_header->jamselesai ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 form-horizontal">
                                            <div class="row">
                                                <div class="col-sm-4">Oleh Petugas</div>
                                                <div class="col-sm-8">
                                                    <select required id="sampeloleh" name="sampeloleh" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                                        <option  value="" selected disabled>-- Pilih Data --</option>
                                                        <?php
                                                            foreach($petugas as $value){
                                                                if($value->nokk == $data_header->sampeloleh){
                                                                    echo '<option value="'. $data_header->sampeloleh .'" selected>'. data_master("tbl_petugas", array("nokk" => $data_header->sampeloleh, "kodepos" => "LABOR"))->namapetugas .'</option>';
                                                                } else {
                                                                    echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="checkbox-inline"><input type="checkbox" name="rilis"  id="rilis" <?= isset($data_header->rilis)? ($data_header->rilis == 1)? "checked" : "" : "" ?>>&emsp;Final Oleh</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select required id="kodepemeriksa" name="kodepemeriksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                                        <option  value="" selected disabled>-- Pilih Data --</option>
                                                        <?php
                                                            foreach($petugas as $value){
                                                                if($value->nokk == $data_header->kodepemeriksa){
                                                                    echo '<option value="'. $data_header->kodepemeriksa .'" selected>'. data_master("tbl_petugas", array("nokk" => $data_header->kodepemeriksa, "kodepos" => "LABOR"))->namapetugas .'</option>';
                                                                } else {
                                                                    echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <!-- <button type="button" class="btn green" id="proses_hasil">Proses hasil</button> -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width:100%">
                                            <thead class="page-breadcrumb breadcrumb">
                                                <tr class="title-white">
                                                    <th style="width:30%">Pemeriksaan</th>
                                                    <th style="width:16.6666666667%">Hasil</th>
                                                    <th style="width:16.6666666667%">Satuan</th>
                                                    <th style="width:16.6666666667%">Nilai Rujukan</th>
                                                    <th style="width:30%">Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data_hasil as $dh_key => $dh_val): ?>
                                                    <tr>
                                                        <td><?= $dh_val->pemeriksaan ?></td>
                                                        <td>
                                                            <input type="number" class="form-control" name="hasil_c[]" value="<?= $dh_val->hasilc ?>">
                                                        </td>
                                                        <td><?= $dh_val->satuan ?></td>
                                                        <td><?= $dh_val->normalc ?></td>
                                                        <td>
                                                            <textarea type="text" class="form-control" name="hasil_catatan[]" rows="2" style="resize:none"><?= $dh_val->keterangan ?></textarea>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane" id="tab4">
                            <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                <h4 style="color:green"><b>HASIL</b></h4>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-6 form-horizontal">
                                        <div class="row">
                                            <div class="col-sm-4">Tgl Sampel Diambil</div>
                                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_diambil" id="tgl_diambil" value="<?= date("Y-m-d") ?>"></div>
                                            <div class="col-sm-4"><input type="time" class="form-control" name="jam_diambil" id="jam_diambil" value="<?= date("H:i:s") ?>"></div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4">Tgl Selesai Periksa</div>
                                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_selesai" id="tgl_selesai" value="<?= date("Y-m-d") ?>"></div>
                                            <div class="col-sm-4"><input type="time" class="form-control" name="jam_selesai" id="jam_selesai" value="<?= date("H:i:s") ?>"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-horizontal">
                                        <div class="row">
                                            <div class="col-sm-4">Oleh Petugas</div>
                                            <div class="col-sm-8"><input type="text" class="form-control" name="petugas_pemeriksa" id="petugas_pemeriksa"></div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4">
                                            <label class="checkbox-inline"><input type="checkbox" name="">&emsp;Final Oleh</label>
                                            </div>
                                            <div class="col-sm-8"><input type="text" class="form-control" name="final_pemeriksa" id="final_pemeriksa"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <!-- <button type="button" class="btn green" id="proses_hasil">Proses hasil</button> -->
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="width:100%">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr class="title-white">
                                                <th style="width:30%">Pemeriksaan</th>
                                                <th style="width:16.6666666667%">Hasil</th>
                                                <th style="width:16.6666666667%">Satuan</th>
                                                <th style="width:16.6666666667%">Nilai Rujukan</th>
                                                <th style="width:30%">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>String</td>
                                                <td>
                                                    <input type="number" class="form-control" name="hasil_c[]">
                                                </td>
                                                <td>String</td>
                                                <td>String</td>
                                                <td>
                                                    <textarea type="text" class="form-control" name="hasil_catatan[]" rows="2" style="resize:none"></textarea>
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
                    <div class="col-sm-12">
                        <div class="form-actions" style="margin-top:0px !important;border-radius:0px !important">
                            <button type="button" class="btn blue" id="savelab"><i class="fa fa-save"></i><b> Simpan</b></button>
                            <div class="btn-group">
                                <a class="btn red" onclick="location.href='/lab/'" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENT END -->

            </div>
        </div>
    </div>
</form>

<br /><br /><br />

<?php
$this->load->view('template/footer');
?>

<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

<script>
    $(window).on("load", function(){
        select2_el_alkes();
        <?php if(isset($elab)): ?>
            spilldata('<?= $helab->noreg ?>');
            total_all();
        <?php endif; ?>
        
        <?php if($status == "save"): ?>
            get_nolaborat();
        <?php else: ?>
            total_all();
            total_all_bhp();
            $("#umur").val(hitung_usia('<?= date("Y-m-d", strtotime($data_header->tgllahir)) ?>'));
        <?php endif; ?>
        // billing_tindakan1
    });
    $(document).ready(function(){
        $('#tblnoreg').DataTable({
            "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sEmptyTable": "Tidak ada data",
                "sInfoEmpty": "Tidak ada data",
                "sInfoFiltered": " - Dipilih dari _MAX_ data",
                "sSearch": "Pencarian Data : ",
                "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu": "_MENU_ Baris",
                "sZeroRecords": "Tida ada data",
                "oPaginate": {
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
                },
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "Semua"]
                ]
            },
            initComplete: function () {
                    this.api()
                        .columns(4)
                        .every(function () {
                            var column = this;
                            var select = $('<select style="border:1px solid #fff;background:#00c11e;color:#fff;margin:auto"><option value="">LAYANAN</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append('<option value="' + d + '" style="color:#fff !important">' + d + '</option>');
                                });
                        });
                },
        });

        $('#tblnoreg_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#tblnoreg_wrapper .dataTables_length select').addClass("form-control input-small  input-inline"); // modify table per page dropdown
        $('#tblnoreg_wrapper .dataTables_length').attr("style", "float:left");
    });

    $('#noreg').change(function() {
        let noreg = $(this).val()
        $('#rekmed').val('Loading...')
        $('#namapas').val('Loading...')
        $.ajax({
            url: '<?php echo site_url('lab/pasien_daftar') ?>',
            type: 'POST',
            dataType: "JSON",
            data: {
                noreg: noreg
            },
            success: function(res) {
                let data;
                try {
                    data = JSON.parse(res)
                } catch (e) {
                    data = res
                }

                console.log({ data })
                $('#rekmed').val(data.rekmed)
                $('#namapas').val(data.namapas)
            }
        });
    });

    function spilldata(param){
        $.ajax({
            url: "/pasien_global/get/noreg/" + param,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $("#noreg").val(data.no_registrasi);
                $("#rekmed").val(data.rekam_medis);
                $("#namapas").val(data.namapas);
                if(data.jkel == "P"){
                    $("#jeniskelamin1").prop("checked", true);
                } else
                if(data.jkel == "W"){
                    $("#jeniskelamin2").prop("checked", true);
                }
                $("#tgllahir").val(data.tanggallahir);
                $("#umur").val(hitung_usia(data.tanggallahir));
                $('#noregister').modal('hide');
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                alert("error get no registration");
            }
        });
    }

    function tambahBilling(){
        var table       = $("#billing_body");
        var rowcount    = $("#billing_body tr").length;

        var idrowBill       = rowcount+1;

        table.append('<tr id="billing_row'+ idrowBill +'">'+
            '<td>'+
                '<center>'+
                    '<button type="button" class="btn red" onclick="hapusBilling('+ idrowBill +')"><i class="fa fa-trash"></i></button>'+
                '</center>'+
                '<input type="hidden" name="billing_tarifrs[]" id="billing_tarifrs'+ idrowBill +'" value="">'+
                '<input type="hidden" name="billing_tarifdr[]" id="billing_tarifdr'+ idrowBill +'" value="">'+
                '<input type="hidden" id="billing_citorphide'+ idrowBill +'" value="">'+
            '</td>'+
            '<td>'+
                "<select type='text' class='form-control selectpicker' name='billing_tindakan[]' id='billing_tindakan"+ idrowBill +"' data-live-search='true' data-width='100%' onkeypress='return tabE(this,event)' onchange='show_tindakan(this.value, "+ idrowBill +")'><option value=''>--- Pilih Tindakan ---</option><?php foreach($listtindakan as $leval): ?><option value='<?= $leval->kodeid ?>'><?= $leval->text ?></opiton><?php endforeach; ?></select>"+
            '</td>'+
            '<td><input type="text" class="form-control" name="billing_qty[]" id="billing_qty'+ idrowBill +'" value="1" onkeyup="qty('+ idrowBill +')"></td>'+
            '<td><input type="text" class="form-control" name="billing_tarifrp[]" id="billing_tarifrp'+ idrowBill +'" readonly></td>'+
            '<td><center><input type="checkbox" class="form-check" id="billing_cito'+ idrowBill +'" onchange="cito('+ idrowBill +')"></center><input type="hidden" name="billing_cito[]" id="billing_citohide'+ idrowBill +'" value="0"></td>'+
            '<td><input type="text" class="form-control" name="billing_citorp[]" id="billing_citorp'+ idrowBill +'" readonly></td>'+
            '<td><input type="text" class="form-control" name="billing_totalbiaya[]" id="billing_totalbiaya'+ idrowBill +'"></td>'+
        '</tr>');

        $("#billing_tindakan"+ idrowBill).selectpicker();
        
        idrowBill++;
    }

    function tambahBHP(){
        var table       = $("#bhp_body");
        var rowcount    = $("#bhp_body tr").length;

        var idrowBHP       = rowcount+1;

        table.append('<tr id="bhp_row'+ idrowBHP +'">'+
                '<td>'+
                    '<center>'+
                        '<button type="button" class="btn red" onclick="hapusBHP('+ idrowBHP +')"><i class="fa fa-trash"></i></button>'+
                    '</center>'+
                '</td>'+
                '<td><center><input type="checkbox" class="form-checkbox" id="bhp_bill'+ idrowBHP +'" onchange="bill('+ idrowBHP +')"></center><input type="hidden" name="bhp_bill[]" id="bhp_billhide'+ idrowBHP +'" value="0"></td>'+
                '<td>'+
                    '<select type="text" class="form-control select2_el_alkes input-medium" name="bhp_barang[]" id="bhp_barang'+ idrowBHP +'" onchange="show_bhp(this.value, '+ idrowBHP +')"></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="bhp_satuan[]" id="bhp_satuan'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp_qty[]" id="bhp_qty'+ idrowBHP +'" onkeyup="qtyBHP('+ idrowBHP +')" value="1"></td>'+
                '<td><input type="text" class="form-control" name="bhp_harga[]" id="bhp_harga'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp_total[]" id="bhp_totalharga'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp_gudang[]" id="bhp_gudang'+ idrowBHP +'" value="LABOR"></td>'+
            '</tr>');

        select2_el_alkes();

        idrowBHP++;
    }

    function hapusBilling(id){
        $("#billing_row"+ id).remove();
        total_all();
    }

    function hapusBHP(id){
        $("#bhp_row"+ id).remove();
        total_all_bhp();
    }

    function show_tindakan(str, id){
        $.ajax({
            url: "/lab/get_tindakan/"+ str,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                var tarifrp = eval(data.tarifrspoli) + eval(data.tarifdrpoli);

                $("#billing_tarifrs"+ id).val(formatCurrency1(data.tarifrspoli));
                $("#billing_tarifdr"+ id).val(formatCurrency1(data.tarifdrpoli));
                $("#billing_citorphide"+ id).val(formatCurrency1(data.cito));
                $("#billing_tarifrp"+ id).val(formatCurrency1(tarifrp));
                qty(id);
            },
            error: function(){
                swal({
                    title: "BILLING TINDAKAN",
                    html: "gagal memuat data tindakan ini",
                    type: "error",
                    confirmButtonText: "Tutup" 
                });
            }
        });
    }

    function show_bhp(str, id){
        $.ajax({
            url: "/lab/get_barang/"+ str,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#bhp_satuan"+ id).val(data.satuan1);
                $("#bhp_harga"+ id).val(formatCurrency1(data.hargajual));
                qtyBHP(id);
            },
            error: function(){
                swal({
                    title: "BHP/ALKES",
                    html: "gagal memuat data tindakan ini",
                    type: "error",
                    confirmButtonText: "Tutup" 
                });
            }
        });
    }

    function qty(id){
        var qtyval  = $("#billing_qty"+ id).val();
        var hrgval  = numeric_restruct($("#billing_tarifrp"+ id).val());
        var citorp  = ($("#billing_citorp"+ id).val() == "")? "0,00" : $("#billing_citorp"+ id).val();
        var ctrval  = numeric_restruct(citorp);

        if(citorp == "0,00" || citorp == "0.00"){
            var total   = eval(hrgval)*eval(qtyval);
        } else {
            var total   = eval(hrgval)*eval(qtyval)*eval(ctrval);
        }

        $("#billing_totalbiaya"+ id).val(formatCurrency1(total));

        total_all();
    }

    function qtyBHP(id){
        var qtyval  = $("#bhp_qty"+ id).val();
        var hrgval  = numeric_restruct($("#bhp_harga"+ id).val());

        var total   = eval(hrgval)*eval(qtyval);

        $("#bhp_totalharga"+ id).val(formatCurrency1(total));

        total_all_bhp();
    }

    function total_all(){
        var table = document.getElementById('billing_table');
        var total_row   = table.rows.length;
        var grand_total = 0;

        for(i = 1; i < total_row; i++){
            var row = table.rows[i];
            grand_total += Number(parseInt(row.cells[6].children[0].value.replaceAll(',', '')));
        }

        $("#grand_total").val(formatCurrency1(grand_total));
    }

    function total_all_bhp(){
        var table = document.getElementById('bhp_table');
        var total_row   = table.rows.length;
        var grand_total = 0;

        for(i = 1; i < total_row; i++){
            var row = table.rows[i];
            grand_total += Number(parseInt(row.cells[6].children[0].value.replaceAll(',', '')));
        }

        $("#grand_total_bhp").val(formatCurrency1(grand_total));
    }

    function cito(id){
        var cito    = $("#billing_cito"+ id);
        var citorp  = $("#billing_citorphide"+ id).val();

        if(cito.is(":checked")){
            $("#billing_citohide"+ id).val("1");
            $("#billing_citorp"+ id).val(formatCurrency1(citorp));
        } else {
            $("#billing_citohide"+ id).val("0");
            $("#billing_citorp"+ id).val("");
        }
    }

    function bill(id){
        var bill    = $("#bhp_bill"+ id);

        if(bill.is(":checked")){
            $("#bhp_billhide"+ id).val("1");
        } else {
            $("#bhp_billhide"+ id).val("0");
        }
    }

    function get_nolaborat(){
        $.ajax({
            url: "/lab/get_last_laborat",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                $("#nolaborathide").val("");
                $("#nolaborat").val("Memuat no pemeriksaan...");
            },
            success: function(data){
                $("#nolaborathide").val(data.nolab);
                $("#nolaborat").val("AUTO");
            },
            error: function(jqXHR, textStatus, errorThrown){
                swal({
                    title: "LABORATORIUM",
                    html: "gagal membuat no pemeriksaan",
                    type: "error",
                    confirmButtonText: "Tutup", 
                    confirmButtonColor: "red"
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function error_alert(message){
        return swal({
            title: "LABORATORIUM",
            html: message,
            type: "error",
            confirmButtonText: "Tutup", 
            confirmButtonColor: "red"
        });
    }

    $("#savelab").on("click", function(e){
        e.preventDefault();

        var post_form   = $("#frmlab").serialize();

        console.log(post_form);

        var noreg       = $("#noreg").val();
        var rekmed      = $("#rekmed").val();
        var namapas     = $("#namapas").val();
        var tgllahir    = $("#tgllahir").val();
        var umur        = $("#umur").val();
        var jkel        = $("[name='jkel']").val();

        if(noreg == "" || noreg == null){
            error_alert("No Registrasi Masih Kosong");
        } else 
        if(rekmed == "" || rekmed == null){
            error_alert("No Rekam Medis Masih Kosong");
        } else 
        if(namapas == "" || namapas == null){
            error_alert("Nama Pasien Masih Kosong");
        } else 
        if(tgllahir == "" || tgllahir == null){
            error_alert("Tanggal Lahir Masih Kosong");
        } else 
        if(umur == "" || umur == null){
            error_alert("Umur Masih Kosong");
        } else 
        if(jkel == "" || jkel == null || jkel == 0){
            error_alert("Jenis kelamin Masih Kosong");
        } else {
        

            $.ajax({
                url: "/lab/simpanDataPemeriksaan/<?= ($status != "save")? "update" : "save" ?>",
                data: post_form,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                    if(data.status == "success"){
                        swal({
                            title: "LABORATORIUM",
                            html: "<p style='padding:0px 0px 5px 0px'>No Laboratorium :<br /><b>"+ data.nolab +"</b></p>Berhasil disimpan",
                            type: "success",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "green",
                            allowOutsideClick: false
                        }).then(() => {
                            location.href='/lab/addDataPemeriksaan/'+ data.nolab;
                        });
                    } else 
                    if(data.status == "failed"){
                        swal({
                            title: "LABORATORIUM",
                            html: "gagal melakukan simpan",
                            type: "error",
                            confirmButtonText: "Tutup",
                            confirmButtonColor: "red",
                            allowOutsideClick: false
                        }).then(() => {
                            location.href='/lab/addDataPemeriksaan/';
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    swal({
                        title: "LABORATORIUM",
                        html: "<p>gagal melakukan simpan</p>"+ textStatus,
                        type: "error",
                        confirmButtonText: "Tutup", 
                        confirmButtonColor: "red"
                    }).then(() => {
                        location.reload();
                    });
                }
            });

        }
    });
</script>

<script>
    $('#hasil-btn-proses-hasil').on('click', getListPemeriksaan);

    function getListPemeriksaan() {
        let jenis_kelamin = $("[name='jkel']").val();
        let nolaborat = $('#nolaborat').val();
        let data = {
            jenis_kelamin,
            nolaborat
        };
        let request = getRequest("lab/get_pemeriksaan", "POST", data);

        request.then((res) => {
            console.log({
                res
            });
            let data = res.data;
            let results = '';

            data.forEach(item => {
                results += "<tr>"+
                    "<td>"+ item.pemeriksaan +"</td>"+
                    "<td>"+
                        "<input type='text' name='hasilc[]' class='form-control' value='"+ item.hasilc +"'>"+
                        "<input type='hidden' name='kodeperiksa[]' value='"+ item.kodeperiksa +"'>"+
                        "<input type='hidden' name='kodelab[]' value='"+ item.kodelab +"'>"+
                    "</td>"+
                    "<td>"+ item.satuan +"</td>"+
                    "<td>"+ item.normalc +"</td>"+
                    "<td><input type='text' name='keterangan[]' class='form-control' value='"+ item.keterangan +"'></td>"+
                "</tr>";
            });

            $('#hasil-list-pemeriksaan').html(results);

        });
    }

    /**@
     * 
     */
    async function getRequest(path = "", method = "GET", data = {}) {
        let base_url = <?php echo "'" . site_url('/') . "'" ?>;
        return await $.ajax({
            url: base_url + path,
            type: method,
            dataType: "JSON",
            data: data,
            success: function(res) {
                let data;
                try {
                    data = JSON.parse(res)
                } catch (e) {
                    data = res
                }

                return data;
            }
        });
    }


    function addBerkasHasil() {
        let unique_id = randomInteger(1, 6000);
        let input_berkas = `<tr id="berkas-` + unique_id + `">
            <td><textarea class="form-control" name="keterangan_berkas-${unique_id}" type="text" resizable></textarea></td>
            <td>
                <input type="file" onchange="loadfile(event, ${unique_id})" name="file_berkas-${unique_id}">
                <input type="hidden" name="old_file-${unique_id}">
            </td>
            <td><a target="__blank" id="view_berkas-${unique_id}">-</a></td>
            <td><button type="button" class="btn btn-danger" onclick="hapusBerkas(` + unique_id + `)" ><i class="fa fa-trash"></i></button></td>
        </tr>`
        $('#berkas-hasil').append(input_berkas);
    }

    function loadfile(event, id) {
        let element = $(`#view_berkas-${id}`);
        element.text('lihat file');
        element.attr('href', URL.createObjectURL(event.target.files[0]))
    }

    function randomInteger(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function hapusBerkas(unique_id) {
        $('#berkas-' + unique_id).remove();
    }

    function hapusBerkasAjax(id, no_laborat) {
        let base_url = <?php echo "'" . site_url('/') . "'" ?>;
        $.ajax({
            type: 'post',
            url : `${base_url}lab/hapuslabfile/${id}/${no_laborat}`,
            success: function(result) {
                window.location.reload();
            }
        });
    }
</script>