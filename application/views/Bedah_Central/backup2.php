<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>


<link href="<?php echo base_url('assets/css/font_css.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-select2.min.css')?>">
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

.modal-body a {
    border: none;
    text-decoration: none;
    color: black;
}

.reglist {
    display: none
}

#table th,
#tbl th {
    color: #fff
}

tab .page-breadcrumb i,
tab .page-breadcrumb a {
    color: #fff !important
}
</style>
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web"><?=$menu;?> <small> <?= $title;?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    <?=$menu;?> </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>Bedah_Central">
                    <?=$title;?>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    INSTALASI BEDAH CENTRAL
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-barcodex"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>

                            <div class="desc">
                                PERMINTAAN OPERASI
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-printx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>
                            <div class="desc">
                                Dijadwalkan Operasi
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="fa fa-shopping-cartx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>
                            <div class="desc">
                                Selesai Operasi
                            </div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <br>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                            <div class="keterangan">
                                <h5> <b>DAFTAR PERMINTAAN OPERASI</b> </h5>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
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
                        </div>
                    </div>
                </div>
            </div>


            <br>
            <table class="table table-striped table-hover table-bordered" id="table" name="table">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center">Buat Jadwal</th>
                        <th style="text-align: center">No Reg</th>
                        <th style="text-align: center">Rekmed</th>
                        <th style="text-align: center">Nama Pasien</th>
                        <th style="text-align: center">Tanggal Rencana</th>
                        <th style="text-align: center">Asal Pasien</th>
                        <th style="text-align: center">Dokter Pemohon</th>
                        <th style="text-align: center">Dokter Operator</th>
                        <th style="text-align: center">Jenis Pasien</th>
                        <th style="text-align: center">Tindakan</th>
                        <th style="text-align: center">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <td><button type="button">Jadwalkan</button></td>

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
            <div class="col-md-12">
                <div class="row">
                    <div class="pull-left">
                        <div class="keterangan">
                            <h5> <b>STATUS DAN JADWAL OPERASI</b> </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom:20px">
            <div class="row">
                <div class="btn-group pull-right">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a data-toggle="modal" href="#hperiode">Filter Data</a>
                        </li>
                    </ul>
                </div>
                <div class="jadwal">
                    <button type="button" data-toggle="modal" data-target=".Jadwalkan" class="btn green">
                        <b>Buat Jadwal</b>
                    </button>
                </div>
            </div>
        </div>

        <table class="table table-striped table-hover table-bordered" id="tbl" name="table"
            style="overflow: auto; white-space: nowrap; display: inline-block;cellspacing:0;" width="100%">
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
                        for($i = 1; $i <= 20; $i++){
                            echo "
                            <tr>
                                <td>123213213213213</td>
                                <td>12321321321321321</td>
                                <td>12321321321213</td>
                                <td>1231321312321</td>
                                <td>12321312321213</td>
                                <td>12312321321</td>
                                <td>12312321321</td>
                                <td>12321312312213</td>
                                <td>1321321321213</td>
                                <td>12321321321</td>
                                <td>213123213</td>
                            </tr>
                            ";
                        }
                    ?>
                <?php
                        if($getData != ''){
                        $no = 1;
                        foreach ($getData as $dt){ ?>
                <tr>
                    <td><button type="button" class="btn grey" data-toggle="modal"
                            data-target="#<?= $dt->nojadwal ?>">Task</button>
                    </td>
                    <td><button type="button" class="btn grey">Billing</button></td>
                    <td><?=$dt->nojadwal; ?></td>
                    <?php
                        if($dt->status_operasi =='0'){ ?>
                    <td> Selesai</td>
                    <?php }else{ ?>
                    <td>Belum Selesai</td>
                    <?php }?>

                    <td><?=$dt->noreg;?></td>
                    <td><?=$dt->rekmed;?></td>
                    <td><?=$dt->namapas;?></td>
                    <td><?=$dt->tgloperasi?></td>
                    <!-- diambil dari tbl_tarifh.tindakan -->
                    <td><?=$dt->tindakan; ?></td>
                    <td><?=$dt->namapost;?> <?=$dt->namaruang;?></td>
                    <!-- diambil dari tbl_dokter.nadokter -->
                    <td><?=$dt->nadokter;?></td>
                    <?php  } } ?>
                </tr>
            </tbody>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
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
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Sampai Tanggal:</label>
                            <div class="col-md-6">
                                <input id="tglakhir" name="tglakhir" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
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


<div class="modal fade" id="Task" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Persetujuan Tindakan</h5>
                <h5>Form Serah Terima Pasien Pre Operasi</h5>
                <h5>Form Persiapan Operasi</h5>
                <h5>Site Marking</h5>
                <h5>Cekist Pasien Safety</h5>
            </div>
            <div class="modal-body">

                <a href="<?=site_url('LaporanBedah/Laporan/'.$dt->nojadwal )?>">
                    <h5 style="color:black;">Laporan Pra Bedah</h5>
                </a>
                <h5>Persetujuan Anestasi</h5>
                <h5>Laporan Anestasi</h5>
                <h5>Laporan Operasi</h5>
            </div>
        </div>

    </div>
</div>

<div class="modal fade Jadwalkan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <!-- <img src="" alt="" > -->
                <h5><b>Jadwal Bedah Central</b></h5>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom:15px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">No Urut</label>
                            <div class="col-md-9">
                                <input type="hidden" id="hideno_urut" name="hideno_urut">
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
                                    data-target=".noRegist"><i class="fa fa-search"></i></button>
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
                                    data-width="auto" onkeypress="return tabE(this,event)"
                                    style='color:#222 !important'>
                                    <!-- <option style='color:#222 !important'>-- Pilih Data --</option> -->
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
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
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
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
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
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sirkuler</label>
                            <div class="col-md-10">
                                <select id="sirkuler" name="sirkuler" class="selectpicker" data-live-search="true"
                                    data-width="100%" onkeypress="return tabE(this,event)" style='color:#222'>
                                    <option>-- Pilih Data --</option>
                                    <?php
                                        foreach($dokter as $val){
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Anastesi</label>
                            <div class="col-md-10">
                                <select id="anastesi" name="anastesi" class="selectpicker" data-live-search="true"
                                    data-width="100%" onkeypress="return tabE(this,event)"
                                    style='color:#222 !important'>
                                    <option style='color:#222 !important'>-- Pilih Data --</option>
                                    <?php
                                        foreach($dokter as $val){
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Penata Anastesi</label>
                            <div class="col-md-10">
                                <select id="penata_anastesi" name="penata_anastesi" class="selectpicker"
                                    data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)"
                                    style='color:#222 !important'>
                                    <option style='color:#222 !important'>-- Pilih Data --</option>
                                    <?php
                                        foreach($dokter as $val){
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Dr Pengirim Int</label>
                            <div class="col-md-10">
                                <select id="drpengirim_int" name="drpengirim_int" class="selectpicker"
                                    data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)"
                                    style='color:#222 !important'>
                                    <option style='color:#222 !important'>-- Pilih Data --</option>
                                    <?php
                                        foreach($dokter as $val){
                                            echo "<option value='$val->kodekter'>$val->nadokter</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Dr Pengirim Ext</label>
                            <div class="col-md-10">
                                <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Jenis Operasi</label>
                            <div class="col-md-10">
                                <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Jenis</label>
                            <div class="col-md-10">
                                <label class="radio-inline"><input type="radio" name="jenis" checked>Terencana</label>
                                <label class="radio-inline"><input type="radio" name="jenis">Cito</label>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Jenis Pembiusan</label>
                            <div class="col-md-10">
                                <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Ruang Ok</label>
                            <div class="col-md-10">
                                <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div><br /><br />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Keterangan</label>
                            <div class="col-md-10">
                                <textarea type="text" class="form-control" name="" id=""
                                    style="resize:none !important"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn blue"><i class="fa fa-save"></i> <b>Update</b>
                            </button>
                            <button type="submit" value="cetak" class="btn btn-warning"> <i class="fa fa-print"></i>
                                <b>Cetak Persetujuan Tindakan</b> </button>
                            <button type="button" class="btn red" data-dismiss='modal'><i class="fa fa-undo"></i><b>
                                    Kembali
                                </b></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</div>

<!-- Modal Regist -->
<div class="modal fade noRegist" role="dialog" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Daftar No Registrasi</b></h5>
            </div>
            <div class="modal-body">
                <input class="form-control" id="noReginput" type="text"
                    placeholder="Cari no registrasi/no rekam medis/nama pasien..">
                <br />
                <p>Menampilkan <?= number_format($totalreg, 0, ',', '.') ?> baris</p>
                <table class="table table-bordered table-striped" style="margin:auto !important">
                    <thead>
                        <tr>
                            <th>No Registrasi</th>
                            <th>No Rekam Medis</th>
                            <th>Nama Pasien</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="noRegtable">
                        <?php foreach($noreg as $nokey => $noval){ ?>
                        <tr class='reglist'>
                            <td><?= $noval->noreg ?></td>
                            <td><?= $noval->rekmed ?></td>
                            <td><?= $noval->namapas ?></td>
                            <td><button class='btn btn-success btn-xs' type='button'
                                    onclick="spilldata('<?= $noval->noreg ?>')">pilih</button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br />
                <button class="btn blue" type="button" id="loadMore"><i class="fa fa-refresh fa-fw"></i>&nbsp;Muat
                    Lebih</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn red" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>

<?php
//   $this->load->view('template/footero');
  $this->load->view('template/footer');
//   $this->load->view('template/v_report');
//   $this->load->view('template/v_periode'); 
?>
<!-- 
<style>
.bootstrap-select .dropdown-toggle:focus {
    outline: none;
    outline: none;
    outline-offset: none;
}
</style> -->

<!-- <script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script> -->

<!-- <script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>"
    type="text/javascript"></script> -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript"></script>

<script>
$(window).on("load", function() {
    lastnourut();
});

function currencyFormat(num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$(document).ready(function() {
    $("#noReginput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var totalrow = $(".reglist").length;
        if (value == "") {
            $(".reglist").slice(10, totalrow).hide();
        } else {
            $("#noRegtable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
    });

    $(".reglist").slice(0, 10).show();
    $("#loadMore").on("click", function(e) {
        e.preventDefault();
        $(".reglist:hidden").slice(0, 10).slideDown();
        if ($(".reglist:hidden").length == 0) {
            $("#loadMore").hide();
        }
    });

    $('#tbl').DataTable({});
    // $('#table').DataTable({});


    //datepicker
    // $('.datepicker').datepicker({
    //     autoclose: true,
    //     format: "yyyy-mm-dd",
    //     todayHighlight: true,
    //     orientation: "top auto",
    //     todayBtn: true,
    //     todayHighlight: true,
    // });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});

var TableEditable = function() {
    return {

        //main function to initiate the module
        init: function() {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }


            var oTable = $('#table').dataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                // set the initial value
                "iDisplayLength": 5,

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
                    }
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],

                "columnDefs": [{
                        // "targets": [-1], //last column
                        // "orderable": false,
                        "defaultContent": "Tidak Ada Data",
                        "targets": _all,
                    },


                    {
                        "targets": [8], //last column
                        "orderable": true, //set not orderable

                        "className": "text-right",
                        render: function(data, type, row) {
                            return '<b>' + display(row[8]) + '</b>';

                        }
                    },


                ],
                "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {

                    var iTotal = 0;
                    var iTotal1 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][7] * 1;
                        iTotal1 += aaData[i][8] * 1;
                    }


                    var iTot = 0;
                    var iTot1 = 0;
                    for (var i = iStart; i < iEnd; i++) {

                        var x = aaData[aiDisplay[i]][7];
                        var y = Number(x.replace(/[^0-9\.]+/g, ""));
                        iTot += y;

                        var x1 = aaData[aiDisplay[i]][8];
                        var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        iTot1 += y2;
                    }

                    // var nCells = nRow.getElementsByTagName('td');
                    // nCells[1].innerHTML = currencyFormat(iTot);
                    // nCells[2].innerHTML = currencyFormat(iTot1);
                }
            });

            jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass(
                "form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass(
                "form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
                showSearchInput: false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-keluar-list_new').click(function(e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '', '', '',
                    '<a class="edit" href="">Edit</a>',
                    '<a class="cancel" data-mode="new" href="">Batal</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });
            $('#keuangan-keluar-list a.cancel').live('click', function(e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-keluar-list a.edit').live('click', function(e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Simpan") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };
}();

$(document).ready(function() {
    $('.print_laporan').on("click", function() {
        $('.modal-title').text('PEMBELIAN');

        var param = this.id;

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>hutang/cetak/' +
            param +
            '" frameborder="no" width="100%" height="420"></iframe>');
    });
});

function filterdata() {
    var tgloperasi = document.getElementById("tgloperasi").value;
    var tglakhir = document.getElementById("tglakhir").value;
    var str = '?tgloperasi=' + tgloperasi + '&tglakhir=' + tglakhir;
    location.href = "<?php echo base_url();?>Bedah_Central/filter_data" + str;
}

jQuery(document).ready(function() {
    TableEditable.init();
    //    ComponentsPickers.init();

});

function lastnourut() {
    $.ajax({
        url: "/Bedah_Central/get_last_number/",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $("#hideno_urut").val(data.lastno);
            $("#no_urut").prop("readonly", true);
            // $("#no_urut").val(data.lastno);
        },
        error: function(data, xhr, ajaxOptions, thrownError) {
            alert("error get no tr");
        }
    });
}

function spilldata(param) {
    $.ajax({
        url: "/pasien_global/get/noreg/" + param,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $("#no_antrian").val(data.antrino)
            $("#noReg").val(data.no_registrasi)
            $("#rekMedis").val(data.rekam_medis)
            $("#namaPas").val(data.nama_pasien)
            $("#dari").val(data.tujuan)
            $("#kelas").val(data.kelas);
            $('.noRegist').modal('hide');
        },
        error: function(data, xhr, ajaxOptions, thrownError) {
            alert("error get no registration");
        }
    });
}

$("#operator").on("change", function() {
    console.log($(this).val());
})
</script>