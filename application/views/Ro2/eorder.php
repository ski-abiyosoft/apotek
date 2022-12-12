<?php
    $this->load->view("template/header");
    $this->load->view("template/body");
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<style>
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
            &nbsp;<span class="title-web"><?= $modul; ?> <small> <?= $title; ?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url(); ?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i>&nbsp;</li>
            <li><a href="#" class="title-white"><?= $modul; ?>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></a></li>
            <li><a href="#" class="title-white"><?= $submodul; ?></a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">

            <!-- TITLE -->
            <div class="portlet-title">
                <div class="caption"><?= strtoupper($title) ?></div>
            </div>

        </div>
    </div>
</div>

<form id="frmradio" enctype="multipart/form-data">
    <input type="hidden" name="asal" id="asal">
    <input type="hidden" name="kelas" id="kelas">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">

                <!-- CONTENT START -->

                <div class="portlet-title">
                    <div class="caption">Form Entri</div>
                </div>

                <!-- HEADER -->
                <div class="portlet-body" style="padding:20px !important;border-radius:0px !important">
                    <div class="form-group row">
                        <div class="col-sm-2"><label for="noradio" style="padding-top:5px">No Pemeriksaan</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control" name="noradio" id="noradio" readonly></div>
                        <div class="col-sm-2"><label for="orderno" style="padding-top:5px">No Order</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control" name="orderno" id="orderno" value="<?= $orderno ?>" readonly></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="tglperiksa" style="padding-top:5px">Tanggal Periksa</label></div>
                        <div class="col-sm-4"><input type="date" class="form-control" name="tglperiksa" id="tglperiksa" value="<?= date("Y-m-d") ?>"></div>
                        <div class="col-sm-2"><label style="padding-top:5px">Jenis Pasien</label></div>
                        <div class="col-sm-4">
                            <table style="width:100%">
                                <tr>
                                    <td style="text-align:left !important"><input type="radio" name="jenispas" id="jenispas1" style="padding:0px !important;margin:0px !important;line-height:none !important" value="1"></td>
                                    <td style="text-align:left !important">Pasien RS</td>
                                    <td style="text-align:left !important"><input type="radio" name="jenispas" id="jenispas2" style="padding:0px !important;margin:0px !important;line-height:none !important" value="2"></td>
                                    <td style="text-align:left !important">Pasien Luar</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="noreg" style="padding-top:5px">No Registrasi</label></div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input id="noreg" type="text" class="form-control" name="noreg" placeholder="No registrasi" value="" readonly>
                                <div class="input-group-btn">
                                    <button class="btn green" type="button" data-toggle="modal" data-target="#noregister"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="rekmed" name="rekmed" placeholder="No rekam medis" readonly>
                        </div>
                        <div class="col-sm-2"><label style="padding-top:5px">Jenis Pemeriksaan</label></div>
                        <div class="col-sm-4">
                            <table style="width:100%">
                                <tr>
                                    <td style="text-align:left !important"><input type="radio" name="jenisperiksa" id="jenisperiksa1" style="padding:0px !important;margin:0px !important;line-height:none !important" value="1"></td>
                                    <td style="text-align:left !important">Rujukan Dokter</td>
                                    <td style="text-align:left !important"><input type="radio" name="jenisperiksa" id="jenisperiksa2" style="padding:0px !important;margin:0px !important;line-height:none !important" value="2"></td>
                                    <td style="text-align:left !important">Permintaan Sendiri</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="namapas" style="padding-top:5px">Nama Pasien</label></div>
                        <div class="col-sm-4">
                            <input id="namapas" type="text" class="form-control" name="namapas" readonly>
                        </div>
                        <div class="col-sm-2"><label for="pemeriksaan" style="padding-top:5px">Pemeriksaan</label></div>
                        <div class="col-sm-4">
                            <table style="width:100%">
                                <tr>
                                    <td style="text-align:left !important"><input type="radio" name="pemeriksaan" id="pemeriksaan1" style="padding:0px !important;margin:0px !important;line-height:none !important" value="1"></td>
                                    <td style="text-align:left !important">Radiologi Dalam</td>
                                    <td style="text-align:left !important"><input type="radio" name="pemeriksaan" id="pemeriksaan2" style="padding:0px !important;margin:0px !important;line-height:none !important" value="2"></td>
                                    <td style="text-align:left !important">Kirim ke Faskes Lain</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="tgllahir" style="padding-top:5px">Tanggal Lahir</label></div>
                        <div class="col-sm-4"><input type="date" class="form-control" name="tgllahir" id="tgllahir"></div>
                        <div class="col-sm-2"><label for="diagnosa" style="padding-top:5px">Diagnosa</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control" name="diagnosa" id="diagnosa"></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="umur" style="padding-top:5px">Umur</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control" name="umur" id="umur" placeholder="Cth : 20 Tahun 12 Bulan 31 Hari"></div>
                        <div class="col-sm-2"><label for="drpengirim" style="padding-top:5px">Dokter Pengirim</label></div>
                        <div class="col-sm-4">
                            <select type="text" class="selectpicker" data-title="- Pilih Dokter -" data-live-search="true" data-width="100%" name="drpengirim" id="drpengirim">
                                <?php
                                    foreach($dokter1 as $dr1){
                                        if($dr1->kodokter == $eorder->kodokter){
                                            echo "<option value='$eorder->kodokter' selected>". data_master("dokter", array("kodokter" => $eorder->kodokter, "koders" => $eorder->koders, "kopoli" => $eorder->asal))->nadokter ."</option>";
                                        } else {
                                            echo "<option value='$dr1->kodokter'>$dr1->nadokter</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"><label for="jeniskelamin" style="padding-top:5px">Jenis kelamin</label></div>
                        <div class="col-sm-4">
                            <table style="width:100%">
                                <tr>
                                    <td style="text-align:left !important"><input type="radio" name="jeniskelamin" id="jeniskelamin1" style="padding:0px !important;margin:0px !important;line-height:none !important" value="1"></td>
                                    <td style="text-align:left !important">Pria</td>
                                    <td style="text-align:left !important"><input type="radio" name="jeniskelamin" id="jeniskelamin2" style="padding:0px !important;margin:0px !important;line-height:none !important" value="2"></td>
                                    <td style="text-align:left !important">Wanita</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-2"><label for="drlaborat" style="padding-top:5px">Dokter Laborat</label></div>
                        <div class="col-sm-4">
                            <select type="text" class="selectpicker" data-title="- Pilih Dokter -" data-live-search="true" data-width="100%" name="drlaborat" id="drlaborat">
                                <?php
                                    foreach($dokter_rad as $dr2){
                                        echo "<option value='$dr2->kodokter'>$dr2->nadokter</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-4">&nbsp;</div>
                        <div class="col-sm-2"><label for="petugas" style="padding-top:5px">Petugas Lab</label></div>
                        <div class="col-sm-4">
                            <select type="text" class="selectpicker" data-title="- Pilih Petugas -" data-live-search="true" data-width="100%" name="petugas" id="petugas">
                                <?php
                                    foreach($petugas as $ptg){
                                        echo "<option value='$ptg->nokk'>$ptg->namapetugas</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TAB -->
                <div class="portlet-body" style="padding:20px !important;margin-bottom:0px !important;border-radius:0px !important">
                    <!-- TAB INDIKATOR -->
                    <div class="form-body">
                        <div class="tabbable tabbable-custom tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active" id="billing">
                                    <a href="<?= "/ro2/eorder/". $orderno ."/". $_SERVER["QUERY_STRING"] ?>#tab1" data-toggle="tab" style="font-weight:bold">BILLING</a>
                                </li>
                                <li class="" id="bhp">
                                    <a href="<?= "/ro2/eorder/". $orderno ."/". $_SERVER["QUERY_STRING"] ?>#tab2" data-toggle="tab" style="font-weight:bold">BHP</a>
                                </li>
                                <li class="" id="hasil">
                                    <a href="<?= "/ro2/eorder/". $orderno ."/". $_SERVER["QUERY_STRING"] ?>#tab3" data-toggle="tab" style="font-weight:bold">HASIL</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- TAB CONTENT -->
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
                                            <?php $no = 1; foreach($detail_order as $do){ ?>
                                                <tr id="billing_row<?= $no ?>">
                                                    <td>
                                                        <center>
                                                            <button type="button" class="btn red" onclick="hapusBilling(<?= $no ?>)"><i class="fa fa-trash"></i></button>
                                                        </center>
                                                        <input type="hidden" name="billing_tarifrs[]" id="billing_tarifrs<?= $no ?>" value="<?= $do->tarifrs ?>">
                                                        <input type="hidden" name="billing_tarifdr[]" id="billing_tarifdr<?= $no ?>" value="<?= $do->tarifdr ?>">
                                                        <input type="hidden" id="billing_citorphide<?= $no ?>" value="">
                                                    </td>
                                                    <td>
                                                        <select type="text" class="form-control selectpicker" name="billing_tindakan[]" id="billing_tindakan<?= $no ?>" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan(this.value, <?= $no ?>)">
                                                            <option value="">--- Pilih Tindakan ---</option>
                                                            <?php foreach($listtindakan as $leval): ?>
                                                                <?php if($leval->kodeid == $do->kodetarif): ?>
                                                                    <option value="<?= $do->kodetarif ?>" selected>[ <?= $do->kodetarif ?> ] - [ <?= $do->tindakan ?> ]</option>
                                                                <?php else: ?>
                                                                    <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="billing_qty[]" id="billing_qty<?= $no ?>" value="1" onkeyup="qty(<?= $no ?>)"></td>
                                                    <td><input type="text" class="form-control" name="billing_tarifrp[]" id="billing_tarifrp<?= $no ?>" value="<?= number_format($do->tarifrs + $do->tarifdr, 2, '.', ',') ?>" readonly></td>
                                                    <td>
                                                        <center><input type="checkbox" class="form-checkbox" id="billing_cito<?= $no ?>" onchange="cito(<?= $no ?>)"></center>
                                                        <input type="hidden" name="billing_cito[]" id="billing_citohide<?= $no ?>" value="0">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="billing_citorp[]" id="billing_citorp<?= $no ?>" readonly></td>
                                                    <td><input type="text" class="form-control" name="billing_totalbiaya[]" id="billing_totalbiaya<?= $no ?>" value="<?= number_format($do->tarifrs + $do->tarifdr, 2, '.', ',') ?>"></td>
                                                </tr>
                                            <?php $no++; } ?>
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
                                <h4 style="color:green"><b>BILLING</b></h4>
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
                                        <tbody id="bhp_body"></tbody>
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
                            <div class="alert alert-danger" style="margin:0px 20px 0px 20px !important;text-align:center;font-weight:bold">Isi billing terlebih dahulu</div>
                            <!-- <div class="portlet-body" style="padding:0px 10px 0px 10px !important">
                                <h4 style="color:green"><b>HASIL</b></h4>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-6 form-horizontal">
                                        <div class="row">
                                            <div class="col-sm-4">Tgl Foto Diambil</div>
                                            <div class="col-sm-4"><input type="date" class="form-control" name="tglfoto" id="tglfoto"></div>
                                            <div class="col-sm-4"><input type="time" class="form-control" name="jamfoto" id="jamfoto"></div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4">Tgl Selesai Dibaca</div>
                                            <div class="col-sm-4"><input type="date" class="form-control" name="tglselesai" id="tglselesai"></div>
                                            <div class="col-sm-4"><input type="time" class="form-control" name="jamselesai" id="jamselesai"></div>
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
                                                            echo '<option value="'. $value->nokk .'">'. $value->namapetugas .'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="checkbox-inline"><input type="checkbox" name="rilis"  id="rilis">&emsp;Final Oleh</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select required id="kodepemeriksa" name="kodepemeriksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" >
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
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="table-layout: fixed; width: 100%">
                                                <thead class="page-breadcrumb breadcrumb title-white">
                                                    <tr>
                                                        <th style="width:70%">File</th>
                                                        <th style="width:15%">Lihat</th>
                                                        <th style="width:15%">Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="file_body"></tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn green" onclick="tambahFile()"><i class="fa fa-plus"></i>&nbsp; Tambah Berkas</button>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="alert alert-danger" style="text-align:center">
                                            <b>Simpan terlebih dahulu gambar yang baru ditambahkan sebelum melihat gambar</b>
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="expertise" class="form-label"><b>Hasil / Expertised</b></label>
                                            <textarea type="text" class="form-control" name="expertise" id="expertise" rows="5" style="resize:none !Important"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-actions" style="margin-top:0px !important;border-radius:0px !important">
                            <button type="button" class="btn blue" id="saveradio"><i class="fa fa-save"></i><b> Simpan</b></button>
                            <div class="btn-group">
                                <a class="btn red" onclick="location.href='/ro2'" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

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
        get_noradio();
        spilldata("<?= $eorder->noreg ?>");
        total_all();
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
                $("#asal").val(data.kodepos);
                $("#kelas").val(data.kelas);
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
                '<td><input type="text" class="form-control" name="bhp_gudang[]" id="bhp_gudang'+ idrowBHP +'" value="RADIO"></td>'+
            '</tr>');

        select2_el_alkes("RADIO");

        idrowBHP++;
    }

    function tambahFile(){
        var table       = $("#file_body");
        var rowcount    = $("#file_body tr").length;

        var idrowFile   = rowcount+1;

        table.append('<tr id="file_row'+ idrowFile +'">'+
                '<td><input type="hidden" name="file_key[]" value="'+ idrowFile +'"><input type="file" class="form-control" name="file[]" onchange="fileBerkas('+ idrowFile +')" id="file'+ idrowFile +'"></td>'+
                '<td></td>'+
                '<td><button type="button" class="btn red" onclick="hapusFile('+ idrowFile +')"><i class="fa fa-trash"></i></button></td>'+
            '</tr>');

            idrowFile++;
    }

    function hapusBilling(id){
        $("#billing_row"+ id).remove();
        total_all();
    }

    function hapusBHP(id){
        $("#bhp_row"+ id).remove();
        total_all_bhp();
    }

    function hapusFile(id){
        $("#file_row"+ id).remove();
    }

    function show_tindakan(str, id){
        $.ajax({
            url: "/ro2/get_tindakan/"+ str,
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
            url: "/ro2/get_barang/"+ str,
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

    function error_alert(message){
        return swal({
            title: "RADIOLOGI",
            html: message,
            type: "error",
            confirmButtonText: "Tutup", 
            confirmButtonColor: "red"
        });
    }

    function loadfile(event, id) {
        let element = $(`#view_berkas-${id}`);
        element.text('lihat file');
        element.attr('href', URL.createObjectURL(event.target.files[0]))
    }

    function fileBerkas(param){
        var file    = $("#file"+param).val();
        var fakepath1   = file.split("C:").join("");
        var fakepath2   = fakepath1.split("fakepath").join("");
        var fakepath3   = fakepath2.split("\\").join("");
        var final_file  = fakepath3;
        
        $("#file_name"+param).val(final_file);
    }

    function get_noradio(){
        $.ajax({
            url: "/ro2/get_last_radio",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                // $("#nolaborathide").val("");
                $("#noradio").val("Memuat no pemeriksaan...");
            },
            success: function(data){
                // $("#nolaborathide").val(data.nolab);
                $("#noradio").val(data.noradio);
            },
            error: function(jqXHR, textStatus, errorThrown){
                swal({
                    title: "RADIOLOGI",
                    html: "gagal membuat no radiologi",
                    type: "error",
                    confirmButtonText: "Tutup", 
                    confirmButtonColor: "red"
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    $(document).ready(function(){
        // $("#frmradio").on("submit", function(e){
        $("#saveradio").on("click", function(e){
            e.preventDefault();

            // var post_form   = $(this).serialize();
            var post_form  = new FormData($("#frmradio")[0]);

            console.log(post_form);

            var noreg       = $("#noreg").val();
            var rekmed      = $("#rekmed").val();
            var namapas     = $("#namapas").val();
            var tgllahir    = $("#tgllahir").val();
            var umur        = $("#umur").val();
            var jkel        = $("[name='jeniskelamin']").val();

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
                    url: "/ro2/save",
                    data: post_form,
                    type: "POST",
                    dataType: "JSON",
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                    success: function(data){
                        if(data.status == "success"){
                            swal({
                                title: "RADIOLOGI",
                                html: "<p style='padding:0px 0px 5px 0px'>No Radio :<br /><b>"+ data.noradio +"</b></p>"+ data.message,
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green",
                                allowOutsideClick: false
                            }).then(() => {
                                location.href='/ro2';
                            });
                        } else 
                        if(data.status == "error"){
                            swal({
                                title: "RADIOLOGI",
                                html: "<p style='padding:0px 0px 5px 0px'>No Radio :<br /><b>"+ data.noradio +"</b></p>"+ data.message,
                                type: "error",
                                confirmButtonText: "Tutup",
                                confirmButtonColor: "red",
                                allowOutsideClick: false
                            }).then(() => {
                                location.href='/ro2/eorder/'+ data.noradio;
                            });
                        } else {
                            swal({
                                title: "RADIOLOGI",
                                html: "<p>Gagal menyimpan data (server) > (client)</p>"+ textStatus,
                                type: "error",
                                confirmButtonText: "Tutup", 
                                confirmButtonColor: "red"
                            }).then(() => {
                                location.href='/ro2/eorder/'+ data.noradio;
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        swal({
                            title: "RADIOLOGI",
                            html: "<p>Gagal menyimpan data (client)</p>"+ textStatus,
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
    });

</script>