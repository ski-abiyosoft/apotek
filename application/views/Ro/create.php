<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<div class="container-fluid">
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

    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url() ?>ro/simpanDataPemeriksaan" method="POST">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">ENTRY PEMERIKSAAN dan BILLING RADIOLOGI</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Pemeriksaan</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="noradio" value="<?= $kodenoradio ?>" id="inputEmail3" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tglradio" class="col-sm-3 col-form-label">Tanggal Periksa</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tglradio" name="tglradio" placeholder="" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">No Registrasi</label>
                            <div class="col-sm-5">
                                <select id="noreg" name="noreg" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)"  required>
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($noReg as $value) { ?>
                                        <option value="<?= $value->noreg ?>"><?= $value->noreg ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rekmed" name="rekmed" placeholder="No RM" readonly>
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
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="umurth" class="col-sm-3 col-form-label">Umur</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurth" name="umurth" placeholder="Tahun" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurbl" name="umurbl" placeholder="Bulan" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurhr" name="umurhr" placeholder="Hari" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Kalamin</label>
                            <div class="col-sm-2 " style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="1" name="jkel" id="jenis_kelamin1" placeholder="" required> <label for="jenis_kelamin1" style="margin-left: 20px;">Pria</label>
                            </div>
                            <div class="col-sm-2" style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="2" name="jkel" id="jenis_kelamin2" placeholder="" required> <label for="jenis_kelamin2" style="margin-left: 20px;" >Wanita</label>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="orderno" class="col-sm-3 col-form-label">No Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="orderno" name="orderno" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pasien</label>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="1" id="jpas1" name="jpas" placeholder=""> <label for="jpas1" style="margin-left: 20px;">Pasien RS</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="2" id="jpas2" name="jpas" placeholder=""> <label for="jpas2" style="margin-left: 20px;">Pasien Luar</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pemeriksaan</label>
                            <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="jenisperiksa1" name="jenisperiksa" placeholder=""> <label for="jenisperiksa1" style="margin-left: 20px;">Rujukan Dokter</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="jenisperiksa2" name="jenisperiksa" placeholder=""> <label for="jenisperiksa2" style="margin-left: 20px;">Permintaan Sendiri</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Pemeriksaan</label>
                            <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="rujuk1" name="rujuk" placeholder=""> <label for="rujuk1" style="margin-left: 20px;">Lab Dalam</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="rujuk2" name="rujuk" placeholder=""> <label for="rujuk2" style="margin-left: 20px;">Dikirim Ke Faskes Lain</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diagnosa" class="col-sm-3 col-form-label">Diagnosa</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control" id="diagnosa" name="diagnosa" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="drperiksa" class="col-sm-3 col-form-label">Dokter Pengirim</label>
                            <div class="col-sm-9">
                                <select required  id="drperiksa" name="drperiksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
                                    <option  value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($dataDokter as $value) { ?>
                                        <option value="<?= $value->kodokter ?>"><?= $value->nadokter ?></option>
                                    <?php }
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
                                    foreach ($dataDokter as $value) { ?>
                                        <option value="<?= $value->kodokter ?>"><?= $value->nadokter ?></option>
                                    <?php }
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
                                    foreach ($petugas as $value) { ?>
                                        <option value="<?= $value->nokk ?>"><?= $value->namapetugas ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" style="float: right;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
                        <a href="<?= base_url() ?>ro" class="btn btn-warning" style="float: right;margin-right:4px !important"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </form>





        </div>
    </div>
</div>



<?php
$this->load->view('template/footer');

?>

<script>
    $(document).ready(function() {
        $('#dataTableEx').DataTable();
    });
</script>

<script>
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
    })
</script>