<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>
<style>
<link rel="stylesheet"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">.button button {
    /* border: none; */
    padding: 5px;
}

.caption h5 {
    color: black;
}

.portlet-body h5 {
    margin: 20px;
}

.portlet-body h4 {
    margin: 30px;
}

#tab1 h5 {
    color: black;
    font-weight: 700;
}

.form-group a {
    text-decoration: none;
    color: white;
}

select {
    color: black;
}
</style>
<!-- <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet"> -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<div class="card">
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
                    <a href="<?php echo base_url();?>LaporanBedah">
                        <?=$title;?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box">
                <div class="portlet-title">
                    <div class="caption">
                        <h5><b>CEKLIST KESELAMATAN PASIEN</b></h5>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <form action="" method="post">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Nama Pasien: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="namaPas" name="namaPas"
                                            value="<?=$pasien->namapas?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?php
                            $tglLahir = $pasien->tgllahir;
                            $tgllahirr = date("Y-m-d", strtotime($tglLahir));
                            ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tanggal Lahir: </label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" id="tglLahir" name="tglLahir"
                                            value="<?=$tgllahirr ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="button">
                                            <button type="reset" class="btn-danger" data-dismiss='modal'><i
                                                    class="fa fa-close"></i>
                                                <b><a href="<?=site_url('Bedah_Central/index')?>">CLOSE</a></b>
                                                <!-- <button type="button">CLOSE</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No. RM: </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="noRM" name="noRM"
                                        value="<?=$pasien->rekmed; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Umur: </label>
                                <div class="col-md-9">
                                    <input id="luptgllahir" name="luptgllahir" placeholder="Tanggal Lahir"
                                        class="form-control" type="hidden">
                                    <input id="lupumur" name="lupumur" placeholder="" class="form-control" type="text"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" value="<?=$nojadwal?>" id="nojadwal" name="nojadwal">

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="button">
                                        <button type="button" onclick="simpan()" class="btn blue"><i
                                                class="fa fa-save"></i> <b>SIMPAN</b>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="c">
                                <label class="col-md-3 control-label">Dr Bedah: </label>
                                <div class="col-md-9">
                                    <input type="text" name="drBedah" id="drBedah" class="form-control"
                                        value="<?=$pasien->nadokter?>">
                                </div>
                                <!-- <select name="drBedah" id="drBedah" class="form-control " style="width:100%;">
                                    <option value="">-- Pilih Data --</option>

                                    <?php foreach($pasien as $p) : ?>
                                        <option value="<?= $p->nadokter; ?>"><?= $p->nadokter; ?></option>
                                    <?php endforeach; ?>


                                </select> -->
                                <!-- <div class="col-md-9">
                                    <input type="text" class="form-control" id="drBedah" name="drBedah"
                                        value="<?=$pasien->nadokter; ?>">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nama Tindakan: </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="tindakan" name="tindakan"
                                        value="<?= $pasien->tindakan;?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="button">
                                        <button type="submit" value="cetak" class="btn btn-warning"> <i
                                                class="fa fa-print"></i>
                                            <b>CETAK</b> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <form id="frmpasien" class="form-horizontal" method="post">
                    <div class="form-body">
                        <div class="tabbable tabbable-custom tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active" id="reservasi">
                                    <a href="#tab1" data-toggle="tab">
                                        Sig In
                                    </a>
                                </li>
                                <li class="" id="rj">
                                    <a href="#tab2" onclick="tab2()" data-toggle="tab">
                                        Time Out
                                    </a>
                                </li>
                                <li class="" id="igd">
                                    <a href="#tab3" data-toggle="tab" onclick="tab3()">
                                        Sign Out
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="portlet-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-6 control-label"> <b>THE SIGN IN (PUKUL) :</b>
                                                </label>
                                                <di class="col-md-6">
                                                    <input type="text" class="form-control" id="sigin" name="sigin">
                                                </di>
                                            </div>
                                            <h5><b>Sebelum Induki dan Ansetasi Oleh Perawat dan
                                                    Dokter Anestasi</b></h5>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="label"> <b>1. Pasien Telah
                                                                dikonfirmasi atau verifikasi</b> </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <label for="label"> <b>Identitas dan Gelang
                                                                                Pasien
                                                                            </b>
                                                                        </label>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="identitas"
                                                                                id="option1">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="identitas"
                                                                                id="option2">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Belum</label></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label for="label"> <b>Lokasi Operasi
                                                                            </b>
                                                                        </label>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="lokasi"
                                                                                id="option3">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="lokasi"
                                                                                id="option4">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Belum</label></td>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <label for="label"> <b>Prosedur
                                                                            </b>
                                                                        </label>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="prosedur1"
                                                                                id="option5">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="prosedur1"
                                                                                id="option6">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Belum</label></td>
                                                                    </td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label for="label"><b>Informed Consent
                                                                                (SIO)</b></label>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="SIO" id="option7">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="SIO" id="option8">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Belum</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>2. Lokasi Operasi
                                                                                    Sudah Diberi
                                                                                    Tanda</b> </label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="operasi"
                                                                                id="option9">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="operasi"
                                                                                id="option10">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Belum</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>3. Mesin Dan Obat
                                                                                    Anestasi Sudah
                                                                                    Dicek Lengkap
                                                                                </b>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="obat"
                                                                                id="option11">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Sudah</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="obat"
                                                                                id="option12">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label for="label"
                                                                            id="option100">Belum</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>4. Puse Oximetsi
                                                                                    Sudah
                                                                                    Terpasang
                                                                                    dan Berfungsi
                                                                                </b>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="oximetsi"
                                                                                id="option13">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="oximetsi"
                                                                                id="option14">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>5. Apakah Pasien
                                                                                    Mempunyai
                                                                                    Riwayat Alergi
                                                                                </b>
                                                                            </label>
                                                                        </div>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="riwayat"
                                                                                id="option15">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="riwayat"
                                                                                id="option16">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>6. Kesulitan Bernafas
                                                                                    / Resiko
                                                                                    Aspirasi </b>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="kesulitan"
                                                                                id="option17">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="kesulitan"
                                                                                id="option18">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b
                                                                                    style="margin:0 15px">Dan
                                                                                    Menggunakan Peralatan
                                                                                    /Bantuan
                                                                                </b>
                                                                            </label><br>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="peralatan"
                                                                                id="option19">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="peralatan"
                                                                                id="option20">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"><b>7. Resiko Kehilangan
                                                                                    Darah >=500
                                                                                    (7ml/Kg/BB pada Anak)
                                                                                </b></label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="resiko"
                                                                                id="option21">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="resiko"
                                                                                id="option22">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <label for="label"> <b>8. Dua Akses Intra
                                                                                    Vena atau Vena
                                                                                    Sentral</b> </label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="vena"
                                                                                id="option23">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Ya</label></td>
                                                                    <td align="center" width="10%">
                                                                        <label for="label">
                                                                            <input type="radio" name="vena"
                                                                                id="option24">
                                                                        </label>
                                                                    </td>
                                                                    <td style="margin:0 10px"><label
                                                                            for="label">Tidak</label></td>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-6 control-label"> <b>THE TIME OUT (PUKUL) :</b>
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="sigin" name="sigin">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <h5><b>Sebelum Insisi Kulit Oleh Perawat, Dr Bedah, Dr Aneatasi</b></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="label"> <b>Review Tim Anestesi: Apakah ada hal khusus yang
                                                    diperhatikan pada pasien?</b> </label>
                                            <textarea name="review" id="review"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <h5><b>1. Konfirmasi Seluruh Anggota Team Memperkenalkan
                                                                Nama
                                                                Dan Peran
                                                                Masing Masing</b></h5>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="konfirmasi" id="option25">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="konfirmasi" id="option26">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <h5><b>2. Dokter Bedah, Anasthesi dan Perawat Melakukan Konfirmasi Secara
                                                    Verbal</b></h5>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <h5><b style="margin:0 20px">-Nama Pasien</b><br>
                                                        </h5>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="namaPas" id="option27">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="namaPas" id="option28">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5><b style="margin:0 20px">-Prosedur</b><br></h5>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="Prosedur2" id="option29">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="Prosedur2" id="option30">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5><b style="margin:0 20px">-Lokasi Insisi Akan Dibuat</b><br>
                                                        </h5>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="lokasi" id="option31">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="lokasi" id="option32">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                    </td>
                                                </tr>
                                            </table>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="label"><b>Jika diperlukan CCV kapan dipasang</b></label><br>
                                            <textarea name="ccv" id="ccv"></textarea><br>
                                            <label for="label"> <b>Review Tim Perawat: Apakah Peralatan sudah steril,
                                                    adakah
                                                    alat alat yang perlu diperhatikan khusus atau dalam masalah</b>
                                            </label>
                                            <textarea name="perawat" id="perawat"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <h5><b>3. Antibioatik Profilaksis Sudah Diberikan 30 Menit Sebelumnya</b>
                                            </h5>
                                            <label for="label"><b style="margin:0 20px">-Nama Antibiotik yang akan
                                                    diberikan</b><br>
                                            </label>
                                            <input type="text" name="anti" id="anti" style="margin:0 20px">
                                            <label for="label"><b style="margin:0 20px">-Dosis Antibiotik yang akan
                                                    diberikan</b><br>
                                            </label>
                                            <input type="text" name="anti" id="anti" style="margin:0 20px">
                                        </div>
                                        <div class="col-md-6">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <label for="label"><b>5. Apakah Foto RO, CT Scan dan MRI Sudah
                                                                Dipasang?</b>
                                                        </label>
                                                    </td><br>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="FO" id="option33">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label"><b>Sudah</b></label>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="FO" id="option34">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label"><b>Belum</b></label>
                                                    </td><br>
                                                    </td><br>
                                                </tr>
                                            </table>
                                            <label for="hal"><b>Hal Yang Harus Diperhatikan</b></label><br>
                                            <textarea name="FO" id="FO"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <h5><b>4. Antisipasi Kejadian Kritis</b>
                                                <label for="label"><b>Review dokter bedah yang
                                                        akan dilakukan
                                                        kritis atau Kejadian tidak diharapkan, lamanya operasi,
                                                        antisipasi kehilangan darah
                                                    </b><br>
                                                </label>
                                                <textarea name="review" id="review"></textarea>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab3">
                                    <div class="portlet-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-6 control-label"> <b>THE SIGN OUT (PUKUL) :</b>
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="sigin" name="sigin">
                                                </div>
                                            </div>
                                        </div><br>

                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <h5><b>Sebelum pasien meninggalkan OK Oleh Perawat dokter bedah , Dokter
                                                        Anestesi</b></h5>
                                                <h5><b>1. Perawat Konfirmasi Secara Verbal Dengan Tim</b></h5>

                                                <table>
                                                    <tr>
                                                        <td>
                                                            <h5><b style="margin:0 20px">-Nama prosedur tindakan yang
                                                                    telah dicatat</b><br>
                                                            </h5>
                                                        </td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="prosedur" id="option35">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="prosedur" id="option36">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5><b style="margin:0 20px">-Instrumen, Kasa, Jarum telah
                                                                    dihitung dengan
                                                                    benar</b><br>
                                                            </h5>
                                                        </td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="instrumen" id="option37">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="instrumen" id="option38">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5><b style="margin:0 20px">-Specimen telah diberi
                                                                    label(termasuk nama pasien
                                                                    dan bocal jaringan/specimen
                                                                </b></h5>
                                                        </td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="specimen" id="option39">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                        <td align="center" width="10%">
                                                            <label for="label">
                                                                <input type="radio" name="specimen" id="option40">
                                                            </label>
                                                        </td>
                                                        <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                $tgl = $pasien->tgloperasi;
                                                $tglTindakan = date("Y-m-d", strtotime($tgl));
                                                ?>
                                                <label for="label"><b>Tanggal Tindakan </b></label>
                                                <input type="text" name="tindakan" id="tindakan" width="200px"
                                                    style="margin:0 5px" value="<?=$tglTindakan; ?>"><br>
                                                <label for="label"><b>Perawat Sirkuler</b></label>
                                                <input type="text" name="perawat" id="perawat" style="margin:0 10px"
                                                    value="<?=$pasien->asdrsirkule?>"><br>
                                                <label for="label"><b>Dr. Bedah</b></label>
                                                <input type="text" name="bedah" id="bedah" style="margin:0 55px"
                                                    value="<?=$pasien->nadokter?>"><br>
                                                <label for="label"><b>Dr. Anestasi</b></label>
                                                <input type="text" name="anestesi" id="anestesi" style="margin:0 40px"
                                                    value="<?=$pasien->dranestasi?>">
                                            </div>
                                        </div>

                                        </h5>
                                        <div class="col-md-6">
                                            <h5><b>2. Dokter Bedah, Dokter Anesthesi, Perawat melakukan review masalah
                                                    apa
                                                    yang perlu diperhatikan untuk penyembuhan dan managemen pasien
                                                    selanjutnya</b><br>
                                                <textarea name="dokter" id="dokter"></textarea>
                                            </h5>
                                            <h5><b>3. Hal yang perlu diperhatikan</b></h5>
                                            <textarea name="hal" id="hal" style="margin:0 20px"></textarea>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <h5><b>4. Thoroact Pack</b></h5>
                                                    </td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="thoroact" id="option41">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Sudah</label></td>
                                                    <td align="center" width="10%">
                                                        <label for="label">
                                                            <input type="radio" name="thoroact" id="option42">
                                                        </label>
                                                    </td>
                                                    <td style="margin:0 10px"><label for="label">Belum</label></td>
                                                    </td>
                                                </tr>
                                            </table>

                                            <!-- disini radio button -->
                                            <label for="label" style="margin:0 20px"><b>Ya, Keterangan</b></label>
                                            <input type="text" id="keterangan" name="keterangan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function simpan() {
    var nojadwal = document.getElementById('nojadwal').value;

    url = "<?php echo site_url('LaporanBedah/simpan/')?>" + nojadwal;
    $.ajax({
        url: url,
        type: "POST",
        data: ($('#frmpasien').serialize()),
        dataType: "JSON",
        success: function(data) {
            if (data.status == 0) {
                swal({
                    title: "DATA PASIEN",
                    html: "Data berhasil tersimpan",
                    type: "success",
                    confirmButtonText: "OK"
                });
            } else if (data.status == 1) {
                swal({
                    title: "DATA PASIEN",
                    text: "Ingin mengubah data ini?",
                    icon: "warning",
                    buttons: true,
                    buttons: false,
                    dangerMode: true,
                }).then((value) => {
                    swal({
                        title: "DATA PASIEN",
                        html: "Data berhasil diubah",
                        type: "success",
                        confirmButtonText: "OK"
                    });
                });
            }
        }
    });
}
</script>


<?php
  $this->load->view('template/footero');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
  $this->load->view('template/footer_tb');  

?>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script>
// < script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" >
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#uniform-option1').removeClass('radio');
    $('#uniform-option2').removeClass('radio');
    $('#uniform-option3').removeClass('radio');
    $('#uniform-option4').removeClass('radio');
    $('#uniform-option5').removeClass('radio');
    $('#uniform-option6').removeClass('radio');
    $('#uniform-option7').removeClass('radio');
    $('#uniform-option8').removeClass('radio');
    $('#uniform-option9').removeClass('radio');
    $('#uniform-option10').removeClass('radio');
    $('#uniform-option11').removeClass('radio');
    $('#uniform-option13').removeClass('radio');
    $('#uniform-option14').removeClass('radio');
    $('#uniform-option15').removeClass('radio');
    $('#uniform-option16').removeClass('radio');
    $('#uniform-option17').removeClass('radio');
    $('#uniform-option18').removeClass('radio');
    $('#uniform-option19').removeClass('radio');
    $('#uniform-option20').removeClass('radio');
    $('#uniform-option21').removeClass('radio');
    $('#uniform-option22').removeClass('radio');
    $('#uniform-option23').removeClass('radio');
    $('#uniform-option24').removeClass('radio');
    $('#uniform-option25').removeClass('radio');
    $('#uniform-option26').removeClass('radio');
    $('#uniform-option27').removeClass('radio');
    $('#uniform-option28').removeClass('radio');
    $('#uniform-option29').removeClass('radio');
    $('#uniform-option30').removeClass('radio');
    $('#uniform-option31').removeClass('radio');
    $('#uniform-option32').removeClass('radio');
    $('#uniform-option33').removeClass('radio');
    $('#uniform-option34').removeClass('radio');
    $('#uniform-option35').removeClass('radio');
    $('#uniform-option36').removeClass('radio');
    $('#uniform-option37').removeClass('radio');
    $('#uniform-option38').removeClass('radio');
    $('#uniform-option39').removeClass('radio');
    $('#uniform-option40').removeClass('radio');
    $('#uniform-option41').removeClass('radio');
    $('#uniform-option42').removeClass('radio');
    $('#uniform-option100').removeClass('radio');
});

$(window).on('load', function() {
    var tglLahir = $('#tglLahir').val();
    var birthDate = new Date(tglLahir);
    var usia = hitung_usia(birthDate);
    $('#lupumur').val(usia);
});
// $('window').on('load', function() {
//     var birthDate = new Date(this.value);
//     var usia = hitung_usia(birthDate);
//     $('#tglLahir').val(usia);
// });
$('#luptgllahir').on('change', function() {
    var birthDate = new Date(this.value);
    var usia = hitung_usia(birthDate);
    $('#lupumur').val(usia);
});

$('#tgllahir').on('change', function() {
    var birthDate = new Date(this.value);
    var usia = hitung_usia(birthDate);
    $('#umur').val(usia);
});
</script>