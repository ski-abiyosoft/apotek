<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>
<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<style>
    .portlet-body a {
        color: black;
        text-decoration: none
    }

    .dropdown-menu pull-right a {
        color: black;
    }

    .keterangan h5 {
        color: green;
    }

    .jadwal button {
        color: black;
        background-color: #00FF00;
    }

    .modal-body h5 {
        border: none;
        /* text-decoration: none; */
        color: black;
    }

    .modal-body a:hover {
        color: blue;
    }

    #table th,
    #tbl th {
        color: #fff
    }

    tab .page-breadcrumb i,
    tab .page-breadcrumb a {
        color: #fff !important
    }

    #taskslist a {display:block}
    #taskslist .btn {width:100%;margin:auto auto 5px auto}
</style>

<!-- Modal Filter Data -->
<div class="modal fade" id="hperiode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <span id="nopilih">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Data</h4>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tanggal Operasi:</label>
                            <div class="col-md-6">
                                <input id="tgloperasi" name="tgloperasi" class="form-control input-medium" type="date"
                                    value="<?= isset($_GET["tgloperasi"])? $_GET["tgloperasi"] : date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Sampai Tanggal:</label>
                            <div class="col-md-6">
                                <input id="tglakhir" name="tglakhir" class="form-control input-medium" type="date"
                                    value="<?= isset($_GET["tglakhir"])? $_GET["tglakhir"] : date('Y-m-d');?>" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p align="center">
                        <button type="button" id="btnfilter" class="btn green" onclick="filterdata()"
                            data-dismiss="modal">Buka Data</button>
                    </p>
                </div>
        </div>
    </div>
</div>
<!-- Modal Filter Data -->

<!-- Modal Jadwal -->
<div class="modal fade Jadwalkan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <!-- <img src="" alt="" > -->
                <h5><b>Jadwal Bedah Central</b></h5>
            </div>
            <form id="frmjdwal">
                <input type="hidden" name="userbuat" value="<?= $this->session->userdata("username") ?>">
                <input type="hidden" name="tglbuat" value="<?= date("Y-m-d") ?>">
                <div class="modal-body">
                    <div class="row" style="margin-bottom:15px">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No Urut</label>
                                <div class="col-md-9">
                                    <input type="hidden" id="hideno_urut" name="hideno_urut" value="">
                                    <input type="text" class="form-control" id="no_urut" name="no_urut" value="AUTO">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No Antrian</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="no_antrian" name="no_antrian">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tanggal Bedah</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" id="tgl_bedah" name="tgl_bedah"
                                        value="<?= date("Y-m-d") ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Jam</label>
                                <div class="col-md-9">
                                    <input type="time" class="form-control" id="jam" name="jam"
                                        value="<?= date("H:i:s") ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No Registrasi</label>
                                <div class="col-md-7">
                                    <!-- <select style='color:black;' id="noReg" name="noReg" class="selectpicker"
                                            data-live-search="true" data-placeholder="Pilih..."
                                            onkeypress="return tabE(this,event)" onchange="spilldata()">
                                            <option>-- Pilih Data --</option>
                                            <?php
                                                foreach($noreg as $val){
                                                    echo "<option value='$val->noreg'>$val->noreg | $val->namapas</option>";
                                                }
                                            ?>
                                        </select> -->
                                    <input type="text" class="form-control input-sm" name="noReg" id="noReg"
                                        value="-- Cari Data --" readonly>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn green btn-sm" style="width:100%;margin:auto" data-toggle="modal"
                                        data-target=".noRegist" type="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Rek Medis</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="rekMedis" name="rekMedis" value="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-bottom:30px">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Pasien</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="namaPas" name="namaPas">
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Dari</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="dari" name="dari">
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Kelas</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="kelas" name="kelas">
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Diagnosa</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="diagsona" name="diagsona" value="">
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Operator</label>
                                <div class="col-md-10">
                                    <select id="operator" name="operator" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Asisten I</label>
                                <div class="col-md-10">
                                    <select id="asisten1" name="asisten1" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Asisten II</label>
                                <div class="col-md-10">
                                    <select id="asisten2" name="asisten2" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Instrumen</label>
                                <div class="col-md-10">
                                    <select id="instrumen" name="instrumen" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Sirkuler</label>
                                <div class="col-md-10">
                                    <select id="sirkuler" name="sirkuler" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Anestesi</label>
                                <div class="col-md-10">
                                    <select id="anestasi" name="anestasi" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Penata Anestesi</label>
                                <div class="col-md-10">
                                    <select id="asanestasi" name="asanestasi" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Dr Pengirim Int</label>
                                <div class="col-md-10">
                                    <select id="drpengirimint" name="drpengirimint" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Dr Pengirim Ext</label>
                                <div class="col-md-10">
                                    <select id="drpengirimext" name="drpengirimext" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($dokter2 as $val){
                                                    echo "<option value='$val->kodokter' style='color:#222 !important'>$val->nadokter</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jenis Operasi</label>
                                <div class="col-md-10">
                                    <select id="jenis_operasi" name="jenis_operasi" class="selectpicker"
                                        data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($tarifrs as $val){
                                                    echo "<option value='$val->kodetarif' style='color:#222 !important'>$val->ket</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jenis</label>
                                <div class="col-md-10">
                                    <label class="radio-inline" style="display:inline-block !important"><input style="display:inline-block !important" type="radio" name="jenis" value="1" checked>Terencana</label>
                                    <label class="radio-inline" style="display:inline-block !important"><input style="display:inline-block !important" type="radio" name="jenis" value="2">Cito</label>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jenis Pembiusan</label>
                                <div class="col-md-10">
                                    <select id="jenisbius" name="jenisbius" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                            <?php
                                                foreach($bius as $val){
                                                    echo "<option value='$val->kodeset' style='color:#222 !important'>$val->keterangan</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Ruang Ok</label>
                                <div class="col-md-10">
                                    <select id="ruangok" name="ruangok" class="selectpicker" data-live-search="true"
                                        data-width="100%" onkeypress="return tabE(this,event)"
                                        style='color:#222 !important'>
                                        <option style='color:#222 !important'>-- Pilih Data --</option>
                                        <?php
                                                foreach($ruangok as $val){
                                                    echo "<option value='$val->koderuang' style='color:#222 !important'>$val->namaruang</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-10">
                                    <textarea type="text" class="form-control" name="uraian" id="uraian"
                                        style="resize:none !important"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn blue title-white" id="save"><i class="fa fa-save"></i> <b>Update</b></button>
                                <button type="button" class="btn btn-warning" id="cetak"> <i class="fa fa-print"></i> <b>Cetak Persetujuan Tindakan</b></button>
                                <button type="button" class="btn red" data-dismiss='modal'><i class="fa fa-times"></i> <b>Tutup</b></button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
<!-- Modal Jadwal -->

<!-- Modal Regist -->
<div class="modal fade noRegist" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:100%;margin:auto">

        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Daftar No Registrasi</b></h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
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
                <button type="button" class="btn red" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- Modal Regist -->

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?= $this->session->userdata("unit"); ?>
            </span>&nbsp;
            -
            &nbsp;<span class="title-web"><?=$menu;?> <small> <?= $title;?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard"
                    class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
            <li><a href="#" class="title-white"><?=$menu;?> </a></a>&nbsp;<i class="fa fa-angle-right"
                    style="color:#fff"></i></li>
            <li><a href="#" class="title-white"><?=$title;?> </a></a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">INSTALASI BEDAH CENTRAL</div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-barcodex"></i></div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>
                            <div class="desc" style="font-weight:bold">PERMINTAAN OPERASI</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                <?= $totaljop ?>
                            </div>
                            <div class="desc" style="font-weight:bold">DIJADWALKAN OPERASI</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat blue">
                        <div class="visual"><i class="fa fa-shopping-cartx"></i></div>
                        <div class="details">
                            <div class="number">
                                <?= $totalsop ?>
                            </div>
                            <div class="desc" style="font-weight:bold">SELESAI OPERASI</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="portlet-body">
            <div class="table-toolbar">
                <h5 style="display:inline-block;color:green"><b>DAFTAR PERMINTAAN OPERASI</b> </h5>
                <div class="btn-group pull-right">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i
                            class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu pull-right">
                        <li><a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a></li>
                    </ul>
                </div>
            </div>

            <table class="table table-striped table-hover table-bordered" id="table" name="table"
                style="overflow: auto; white-space: nowrap; display: inline-block;" cellspacing="0"; width="100%">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center" class="title-white">Buat Jadwal</th>
                        <th style="text-align: center" class="title-white">No Reg</th>
                        <th style="text-align: center" class="title-white">Rekmed</th>
                        <th style="text-align: center" class="title-white">Nama Pasien</th>
                        <th style="text-align: center" class="title-white">Tanggal Rencana</th>
                        <th style="text-align: center" class="title-white">Asal Pasien</th>
                        <th style="text-align: center" class="title-white">Dokter Pemohon</th>
                        <th style="text-align: center" class="title-white">Dokter Operator</th>
                        <th style="text-align: center" class="title-white">Jenis Pasien</th>
                        <th style="text-align: center" class="title-white">Tindakan</th>
                        <th style="text-align: center" class="title-white">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <?php
                    if($cekData != ''){
                    $no = 1;
                    foreach ($cekData as $dt){ ?>
                    <tr>
                        <td><button type="button">Jadwalkan</button></td>
                        <td><?=$dt->noreg; ?></td>
                        <td><?=$dt->rekmed;?></td>
                        <td><?=$dt->userbuat;?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?=$dt->droperator;?></td>
                        <td><?=$dt->jenispas?></td>
                        <td></td>
                        <td></td>

                        <?php  } } ?> -->
                    </tr>

                </tbody>
                <tfoot>
                    <!-- <td colspan="7" style="text-align:right">Total:</td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td colspan="1"></td> -->
                </tfoot>

            </table>

            <br />

            <div class="table-toolbar" style="margin-top:10px">
                <button type="button" data-toggle="modal" data-target=".Jadwalkan" class="btn green">
                    <b>Buat Jadwal</b>
                </button>
                <div class="col-md-12" style="margin-bottom:20px">
                    <div class="row">
                        <div class="btn-group pull-right">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i
                                    class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a data-toggle="modal" href="#hperiode">Filter Data</a>
                                </li>
                            </ul>
                        </div>
                        <div class="jadwal">
                            <h5 style="display:inline-block;color:green"><b>STATUS DAN JADWAL OPERASI</b> </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div style="overflow-x:auto !important"> -->
            <table class="table table-striped table-hover table-bordered" id="tbl" name="table"
                style="overflow: auto; white-space: nowrap; display: inline-block;" cellspacing="0" width="100%">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center">Task</th>
                        <th style="text-align: center">Billing</th>
                        <th style="text-align: center">No Jadwal</th>
                        <th style="text-align: center">Status Operasi</th>
                        <th style="text-align: center">No reg</th>
                        <th style="text-align: center">Rekmed</th>
                        <th style="text-align: center">Nama Pasien</th>
                        <th style="text-align: center">Tanggal Operasi</th>
                        <th style="text-align: center">Tindakan</th>
                        <th style="text-align: center">Asal Pasien</th>
                        <th style="text-align: center">Dokter Operator</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        if($getData != ''){
                        $no = 1;
                        foreach ($getData as $dt){ ?>
                    <tr>
                        <td>
                            <button type="button" class="btn grey btn-xs" data-toggle="modal" data-target="#taskmodal" onclick="showTasks('<?= $dt->nojadwal ?>')">Task</button>
                            <!-- <button type="button" class="btn grey btn-xs">Task</button> -->
                        </td>
                        <td><button type="button" class="btn grey btn-xs" onclick="window.open('/Bedah_Central/billing/<?= $dt->nojadwal ?>', '_blank')">Billing</button></td>
                        <td><?=$dt->nojadwal; ?></td>

                        <?php if($dt->status_operasi =='0'){ ?>
                            <td>Belum Selesai</td>
                        <?php }else{ ?>
                            <td>Selesai</td>
                        <?php }?>

                        <td><?=$dt->noreg;?></td>
                        <td><?=$dt->rekmed;?></td>
                        <td><?=$dt->namapasien;?></td>
                        <td><?=$dt->tgloperasi?></td>
                        <!-- diambil dari tbl_tarifh.tindakan -->
                        <td><?=$dt->tindakan; ?></td>
                        <td><?=$dt->namapost;?> <?=$dt->namaruang;?></td>
                        <!-- diambil dari tbl_dokter.nadokter -->
                        <td><?=$dt->nadokter;?></td>
                        <?php  } } ?>
                    </tr>
                </tbody>
                <tfoot>
                    <!-- <td colspan="7" style="text-align:right">Total:</td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td colspan="1"></td> -->
                </tfoot>

            </table>
            <!-- </div> -->
        </div>
    </div>
</div>
<br /><br />
<!-- Modal Menu -->
<div class="modal fade" id="taskmodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content taskslist">
            <div class="modal-header" id="taskhead"></div>
            <div class="modal-body" id="taskslist"></div>
        </div>
    </div>
</div>
<!-- Modal Menu -->

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
        $(window).on("load", function() {
            $("#cetak").hide();

            $.ajax({
                url: "/pasien_global/list",
                type: "GET",
                dataType: "JSON",
                success: function(data) {console.log("%c get 'Data Pasien' successfully", "color:#2bd687")},
                error: function(data, xhr, ajaxOptions, thrownError) {
                    // alert("error get pasien");
                }
            });

            lastnourut();
        });

        $(document).ready(function() {
            $("#table").DataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],
                info: false,
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
                        [5, 15, 20, -1],
                        [5, 15, 20, "Semua"]
                    ]
                }
            });

            $("#tbl").DataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],
                "oLanguage": {
                    "sEmptyTable": "Tida ada data",
                    "sInfoEmpty": "Tida ada data",
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
                        [5, 15, 20, -1],
                        [5, 15, 20, "Semua"]
                    ]
                }
            });

            $("#tbl2").DataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ],
                info: false,
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
                        [5, 15, 20, -1],
                        [5, 15, 20, "Semua"]
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
        });

        $("#save").on("click", function(){
            var post_form = $("#frmjdwal").serialize();

            $.ajax({
                url: "/Bedah_Central/save",
                type: "POST",
                data: post_form,
                success: function(data) {
                    $('.Jadwalkan').modal('hide');
                    $('.noRegist').modal('hide');
                    swal({
                        html: "Jadwal Bedah Berhasil Dibuat",
                        type: "success",
                        confirmButtonText: "OK",
                        confirmButtonColor: "green",
                    });
                    $("#save").hide();
                    $("#cetak").show();
                    window.location.reload();
                },
                error: function(data, jqXHR, textStatus, errorThrown) {
                    swal({
                        html: textStatus,
                        type: "error",
                        confirmButtonText: "Close",
                        confirmButtonColor: "red",
                    });
                }
            });
        });

        function lastnourut(){
            $.ajax({
                url: "/Bedah_Central/get_last_number/",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $("#hideno_urut").val(data.lastno);
                    $("#no_urut").prop("readonly", true);
                },
                error: function(data, xhr, ajaxOptions, thrownError) {
                    alert("error get no tr");
                }
            });
        }

        function spilldata(param){
            $.ajax({
                url: "/pasien_global/get/noreg/" + param,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $("#no_antrian").val(data.antrino)
                    $("#noReg").val(data.no_registrasi)
                    $("#rekMedis").val(data.rekam_medis)
                    $("#namaPas").val(data.namapas)
                    $("#dari").val(data.tujuan)
                    $("#kelas").val(data.kelas);
                    $('.noRegist').modal('hide');
                },
                error: function(data, xhr, ajaxOptions, thrownError) {
                    alert("error get no registration");
                }
            });
        }

        function showTasks(param){
            $.ajax({
                url: "/Bedah_Central/modaltrigger/"+param,
                type: "GET",
                dataType: "html",
                beforeSend: function(data) {
                    $('#taskslist').html("<div class='modal-body' id='taskslist'>sedang memuat data..</div>");    
                },
                success: function(data){
                    $(".taskslist").html(data);
                    // $("#taskhead").html("<table style='overflow-x:auto;border-collapse: collapse;border-spacing:0;width:100%;border:1px solid #fff'>"+
                    // "<tr><td style='padding:5px'>No Jadwal</td><td>:</td><td> <b>"+ data.nojadwal +"</b></td></tr>"+
                    // "<tr><td style='padding:5px'>Tanggal Operasi</td><td>:</td><td> <b>"+ data.tgloperasi.split(" 00:00:00").join("") +"</b></td></tr>"+
                    // "<tr><td style='padding:5px'>Nama Pasien</td><td>:</td><td> <b>"+ data.namapas +"</b></td></tr>"+
                    // "<tr><td style='padding:5px'>No Rekmed</td><td>:</td><td> <b>"+ data.rekmed +"</b></td></tr>"+
                    // "<tr><td style='padding:5px'>No Regist</td><td>:</td><td> <b>"+ data.noreg +"</b></td></tr>"+
                    // "</table>");
                    // $("#taskslist").html("<a href='/Bedah_Central/persetujuan_umum/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Persetujuan Umum</button></a>"+
                    // "<a href='/Bedah_Central/persetujuan_tindakan_dokter/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Persetujuan Tindakan Dokter</button></a>"+
                    // "<a href='/Bedah_Central/checklist_persiapan_pasien_preoperasi/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Cek List Persiapan Pre-Operasi</button></a>"+
                    // "<a href='/Bedah_Central/prosedur_safety_checklist/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Prosedur Safety Checklist</button></a>"+
                    // "<a href='/Bedah_Central/laporan_anestasi/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Laporan Anestasi</button></a>"+
                    // "<a href='/Bedah_Central/laporan_operasi/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Laporan Operasi</button></a>"+
                    // "<a href='/Bedah_Central/resume_pasien_pulang_post_operasi/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Resume Pasien Pulang Post Operasi</button></a>"+
                    // "<a href='/Bedah_Central/catatan_keperawatan_bedah/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Catatan Keperawatan Bedah</button></a>"+
                    // "<a href='/Bedah_Central/cataract_surgery/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Cataract Surgery</button></a>"+
                    // "<a href='/LaporanBedah/Laporan/"+ data.nojadwal +"' target='_blank'><button type='button' class='btn green btn-sm'>Laporan Pra Bedah</button></a>");
                },
                error: function(data, xhr, ajaxOptions, thrownError) {
                    alert("error get modal");
                }
            });
        }

        function filterdata() {
            var tgl1 = document.getElementById("tgloperasi").value;
            var tgl2 = document.getElementById("tglakhir").value;
            var str = '?tgloperasi=' + tgl1 + '&tglakhir=' + tgl2;
            location.href = "<?php echo base_url();?>Bedah_Central/filter_data/" + str;

        }
    </script>