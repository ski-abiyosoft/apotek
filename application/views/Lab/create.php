<?php
    $this->load->view('template/header');
    $this->load->view('template/body');
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
                            <th class="text-center title-white">NO REG</th>
                            <th class="text-center title-white">REKMED</th>
                            <th class="text-center title-white">NAMA PASIEN</th>
                            <th class="text-center title-white">TGL MASUK</th>
                            <th class="text-center title-white">LAYANAN</th>
                            <th class="text-center title-white">TUJUAN</th>
                            <th class="text-center title-white">JENIS PASIEN</th>
                            <th class="text-center title-white">DOKTER</th>
                            <th class="text-center title-white">NO SEP</th>
                            <th class="text-center title-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listreg as $lkey => $lval){ ?>
                        <tr>
                            <td><?= $lval->no_registrasi ?></td>
                            <td><?= $lval->rekam_medis ?></td>
                            <td><?= $lval->nama_pasien ?></td>
                            <td><?= $lval->tanggal_masuk ?></td>
                            <td><?= $lval->layanan ?></td>
                            <td><?= $lval->tujuan ?></td>
                            <td><?= $lval->jenis_pasien ?></td>
                            <td><?= $lval->dokter ?></td>
                            <td><?= $lval->nosep ?></td>
                            <td><button type="button" onclick="spilldata('<?= $lval->no_registrasi ?>')"
                                    class="btn btn-success btn-xs">Pilih</button></td>
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
                    <div class="caption"><i class="fa fa-bars fa-fw"></i>&nbsp; <?= ($status == "undone")? "Form Entri" : "Form Edit" ?></div>
                </div>

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
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" placeholder="" value="<?= isset($data_header->tgllahir)? date("Y-m-d", strtotime($data_header->tgllahir)) : "" ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="umurth" class="col-sm-3 col-form-label">Umur</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="umur" name="umur">
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
                                <input type="text" class="form-control" id="orderno" name="orderno" placeholder="" required readonly>
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
                                        foreach($dataDokter as $value){
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
                                    <?php
                                    foreach ($petugas as $value) { ?>
                                        <option value="<?= $value->nokk ?>"><?= $value->namapetugas ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                </div>

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
                                    <a href="<?= "/lab/addDataPemeriksaan/". $_SERVER["QUERY_STRING"] ?>#tab4" data-toggle="tab" style="font-weight:bold"></a>
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
                                            <?php if($status == "undone"){ ?>
                                            <tr id="billing_row1">
                                                <td>
                                                    <center>
                                                        <button type="button" class="btn red" onclick="hapusBilling(1)"><i class="fa fa-trash"></i></button>
                                                    </center>
                                                    <input type="hidden" name="tindakan[0][bill_tarifrs]" id="billing_tarifrs1" value="">
                                                    <input type="hidden" name="tindakan[0][bill_tarifdr]" id="billing_tarifdr1" value="">
                                                    <input type="hidden" name="tindakan[0][bill_citorphide]" id="billing_citorphide1" value="">
                                                </td>
                                                <td>
                                                    <select type="text" class="form-control selectpicker" name="tindakan[0][bill_tindakan]" id="billing_tindakan1" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, 1)">
                                                        <option value="">--- Pilih Tindakan ---</option>
                                                        <?php foreach($listtindakan as $leval): ?>
                                                            <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="tindakan[0][bill_qty]" id="billing_qty1" onkeyup="qty(1)"></td>
                                                <td><input type="text" class="form-control" name="tindakan[0][bill_tarifrp]" id="billing_tarifrp1" readonly></td>
                                                <td><center><input type="checkbox" class="form-checkbox" name="tindakan[0][bill_cito]" id="billing_cito1" value="0" onchange="cito(1)"></center></td>
                                                <td><input type="text" class="form-control" name="tindakan[0][bill_citorp]" id="billing_citorp1" readonly></td>
                                                <td><input type="text" class="form-control" name="tindakan[0][bill_total]" id="billing_totalbiaya1"></td>
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
                                                        <input type="hidden" name="tindakan[<?= $lbkey ?>][bill_tarifrs]" id="billing_tarifrs<?= $nobill ?>" value="<?= number_format($lbval->tarifrs, 2, '.', ',') ?>">
                                                        <input type="hidden" name="tindakan[<?= $lbkey ?>][bill_tarifdr]" id="billing_tarifdr<?= $nobill ?>" value="<?= number_format($lbval->tarifdr, 2, '.', ',') ?>">
                                                        <input type="hidden" name="tindakan[<?= $lbkey ?>][bill_citorphide]" id="billing_citorphide<?= $nobill ?>" value="<?= number_format($lbval->citorp, 2, '.', ',') ?>">
                                                    </td>
                                                    <td>
                                                        <select type="text" class="form-control selectpicker" name="tindakan[<?= $lbkey ?>][bill_tindakan]" id="billing_tindakan<?= $nobill ?>" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, <?= $nobill ?>)">
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
                                                    <td><input type="text" class="form-control" name="tindakan[<?= $lbkey ?>][bill_qty]" id="billing_qty<?= $nobill ?>" onkeyup="qty(<?= $nobill ?>)" value="<?= $lbval->qty ?>"></td>
                                                    <td><input type="text" class="form-control" name="tindakan[<?= $lbkey ?>][bill_tarifrp]" id="billing_tarifrp<?= $nobill ?>" value="<?= number_format($lbval->tarifrp, 2, '.', ',') ?>" readonly></td>
                                                    <td><center><input type="checkbox" class="form-checkbox" name="tindakan[<?= $lbkey ?>][bill_cito]" id="billing_cito<?= $nobill ?>" value="<?= $lbval->jenis ?>" onchange="cito(<?= $nobill ?>)" <?= ($lbval->jenis == "1")? "checked" : "" ?>></center></td>
                                                    <td><input type="text" class="form-control" name="tindakan[<?= $lbkey ?>][bill_citorp]" id="billing_citorp<?= $nobill ?>" value="<?= number_format($lbval->citorp, 2, '.', ',') ?>" readonly></td>
                                                    <td><input type="text" class="form-control" name="tindakan[<?= $lbkey ?>][bill_total]" id="billing_totalbiaya<?= $nobill ?>" value="<?= number_format($lbval->totalrp, 2, '.', ',') ?>"></td>
                                                </tr>
                                            <?php $nobill++; } } ?>
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
                                            <?php if($status == "undone"){ ?>
                                            <tr id="bhp_row1">
                                                <td>
                                                    <center>
                                                        <button type="button" class="btn red" onclick="hapusBHP(1)"><i class="fa fa-trash"></i></button>
                                                    </center>
                                                </td>
                                                <td><center><input type="checkbox" class="form-checkbox" name="bhp[0][bhp_bill]" id="bhp_bill1" value="0" onchange="bill(1)"></center></td>
                                                <td>
                                                    <select type="text" class="form-control select2_el_alkes input-medium" name="bhp[0][bhp_barang]" id="bhp_barang1" onchange="show_bhp(this.value, 1)"></select>
                                                </td>
                                                <td><input type="text" class="form-control" name="bhp[0][bhp_satuan]" id="bhp_satuan1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[0][bhp_qty]" id="bhp_qty1" onkeyup="qtyBHP(1)"></td>
                                                <td><input type="text" class="form-control" name="bhp[0][bhp_harga]" id="bhp_harga1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[0][bhp_total]" id="bhp_totalharga1" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[0][bhp_gudang]" id="bhp_gudang1" value="LABOR"></td>
                                            </tr>
                                            <?php 
                                                } else { 
                                                    $nobhp      = 1;
                                                    $list_bhp   = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '$data_header->nolaborat'")->result();

                                                    foreach($list_billing as $lbkey => $lbval){
                                            ?>
                                            <tr id="bhp_row1">
                                                <td>
                                                    <center>
                                                        <button type="button" class="btn red" onclick="hapusBHP(<?= $nobhp ?>)"><i class="fa fa-trash"></i></button>
                                                    </center>
                                                </td>
                                                <td><center><input type="checkbox" class="form-checkbox" name="bhp[<?= $nobhp ?>][bhp_bill]" id="bhp_bill<?= $nobhp ?>" value="0" onchange="bill(<?= $nobhp ?>)"></center></td>
                                                <td>
                                                    <select type="text" class="form-control select2_el_alkes input-medium" name="bhp[<?= $nobhp ?>][bhp_barang]" id="bhp_barang<?= $nobhp ?>" onchange="show_bhp(this.value, <?= $nobhp ?>)"></select>
                                                </td>
                                                <td><input type="text" class="form-control" name="bhp[<?= $nobhp ?>][bhp_satuan]" id="bhp_satuan<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[<?= $nobhp ?>][bhp_qty]" id="bhp_qty<?= $nobhp ?>" onkeyup="qtyBHP(<?= $nobhp ?>)"></td>
                                                <td><input type="text" class="form-control" name="bhp[<?= $nobhp ?>][bhp_harga]" id="bhp_harga<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[<?= $nobhp ?>][bhp_total]" id="bhp_totalharga<?= $nobhp ?>" readonly></td>
                                                <td><input type="text" class="form-control" name="bhp[<?= $nobhp ?>][bhp_gudang]" id="bhp_gudang<?= $nobhp ?>" value="LABOR"></td>
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
                            <!-- <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                <h4 style="color:green"><b>HASIL</b></h4>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-4" style="padding-top:5px"><b>Tgl Sample Diambil</b></div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-6"><input type="date" class="form-control" name="" id="" value="<?= date("Y-m-d") ?>"></div>
                                                    <div class="col-sm-6"><input type="time" class="form-control" name="" id="" value="<?= date("H:i:s") ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-4" style="padding-top:5px"><b>Tgl Selesai Periksa</b></div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-6"><input type="date" class="form-control" name="" id="" value="<?= date("Y-m-d") ?>"></div>
                                                    <div class="col-sm-6"><input type="time" class="form-control" name="" id="" value="<?= date("H:i:s") ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-4" style="padding-top:5px"><b>Oleh Petugas</b></div>
                                            <div class="col-sm-8">
                                                <select required id="" name="" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                                    <?php foreach ($petugas as $value) { ?>
                                                        <option value="<?= $value->nokk ?>"><?= $value->namapetugas ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4" style="padding-top:5px">
                                                <table>
                                                    <tr>
                                                        <td style="width:40%"><input type="checkbox" class="form-checkbox" name="" id=""></td>
                                                        <td style="width:60%">Final Oleh</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-sm-8">
                                                <select required id="" name="" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                                    <?php foreach ($petugas as $value) { ?>
                                                        <option value="<?= $value->nokk ?>"><?= $value->namapetugas ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="tab-pane" id="tab4">
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
        <?php if($status == "undone"): ?>
            get_nolaborat();
        <?php else: ?>
            total_all();
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
        var idrowBill2      = rowcount;

        table.append('<tr id="billing_row'+ idrowBill +'">'+
            '<td>'+
                '<center>'+
                    '<button type="button" class="btn red" onclick="hapusBilling('+ idrowBill +')"><i class="fa fa-trash"></i></button>'+
                '</center>'+
                '<input type="hidden" name="tindakan['+ idrowBill2 +'][bill_tarifrs]" id="billing_tarifrs'+ idrowBill +'" value="">'+
                '<input type="hidden" name="tindakan['+ idrowBill2 +'][bill_tarifdr]" id="billing_tarifdr'+ idrowBill +'" value="">'+
                '<input type="hidden" name="tindakan['+ idrowBill2 +'][bill_citorphide]" id="billing_citorphide'+ idrowBill +'" value="">'+
            '</td>'+
            '<td>'+
                '<select type="text" class="form-control selectpicker" name="tindakan['+ idrowBill2 +'][bill_tindakan]" id="billing_tindakan'+ idrowBill +'" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, '+ idrowBill +')"><option value="">--- Pilih Tindakan ---</option><?php foreach($listtindakan as $leval): ?><option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton><?php endforeach; ?></select>'+
            '</td>'+
            '<td><input type="text" class="form-control" name="tindakan['+ idrowBill2 +'][bill_qty]" id="billing_qty'+ idrowBill +'" onkeyup="qty('+ idrowBill +')"></td>'+
            '<td><input type="text" class="form-control" name="tindakan['+ idrowBill2 +'][bill_tarifrp]" id="billing_tarifrp'+ idrowBill +'" readonly></td>'+
            '<td><center><input type="checkbox" class="form-check" name="tindakan['+ idrowBill2 +'][bill_cito]" id="billing_cito'+ idrowBill +'" value="0" onchange="cito('+ idrowBill +')"></center></td>'+
            '<td><input type="text" class="form-control" name="tindakan['+ idrowBill2 +'][bill_citorp]" id="billing_citorp'+ idrowBill +'" readonly></td>'+
            '<td><input type="text" class="form-control" name="tindakan['+ idrowBill2 +'][bill_total]" id="billing_totalbiaya'+ idrowBill +'"></td>'+
        '</tr>');

        $("#billing_tindakan"+ idrowBill).selectpicker();

        idrowBill++;
        idrowBill2++;
    }

    function tambahBHP(){
        var table       = $("#bhp_body");
        var rowcount    = $("#bhp_body tr").length;

        var idrowBHP       = rowcount+1;
        var idrowBHP2      = rowcount;

        table.append('<tr id="bhp_row'+ idrowBHP +'">'+
                '<td>'+
                    '<center>'+
                        '<button type="button" class="btn red" onclick="hapusBHP('+ idrowBHP +')"><i class="fa fa-trash"></i></button>'+
                    '</center>'+
                '</td>'+
                '<td><center><input type="checkbox" class="form-checkbox" name="bhp['+ idrowBHP2 +'][bhp_bill]" id="bhp_bill'+ idrowBHP +'" value="0" onchange="bill('+ idrowBHP +')"></center></td>'+
                '<td>'+
                    '<select type="text" class="form-control select2_el_alkes input-medium" name="bhp['+ idrowBHP2 +'][bhp_barang]" id="bhp_barang'+ idrowBHP +'" onchange="show_bhp(this.value, '+ idrowBHP +')"></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="bhp['+ idrowBHP2 +'][bhp_satuan]" id="bhp_satuan'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp['+ idrowBHP2 +'][bhp_qty]" id="bhp_qty'+ idrowBHP +'" onkeyup="qtyBHP('+ idrowBHP +')"></td>'+
                '<td><input type="text" class="form-control" name="bhp['+ idrowBHP2 +'][bhp_harga]" id="bhp_harga'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp['+ idrowBHP2 +'][bhp_total]" id="bhp_totalharga'+ idrowBHP +'" readonly></td>'+
                '<td><input type="text" class="form-control" name="bhp['+ idrowBHP2 +'][bhp_gudang]" id="bhp_gudang'+ idrowBHP +'" value="LABOR"></td>'+
            '</tr>');

        select2_el_alkes();

        idrowBHP++;
        idrowBHP2++;
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
            $("#billing_cito"+ id).val("1");
            $("#billing_citorp"+ id).val(formatCurrency1(citorp));
        } else {
            $("#billing_cito"+ id).val("0");
            $("#billing_citorp"+ id).val("");
        }
    }

    function bill(id){
        var bill    = $("#bhp_bill"+ id);

        if(bill.is(":checked")){
            $("#bhp_bill"+ id).val("1");
        } else {
            $("#bhp_bill"+ id).val("0");
        }
    }

    function get_nolaborat(){
        $.ajax({
            url: "/lab/get_last_laborat",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#nolaborat").val(data.nolab);
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

    $("#savelab").on("click", function(e){
        e.preventDefault();

        var post_form   = $("#frmlab").serialize();

        console.log(post_form);

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
    });
</script>