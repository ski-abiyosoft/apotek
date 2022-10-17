<?php
$this->load->view('template/header');
$this->load->view('template/body');
// var_dump( $tindakan );
// die();
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

?>

<style>

</style>

<!-- Modal Billing Add -->
<div class="modal fade" id="tesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Tambah Billing</h4>
            </div>
            <form action="<?= base_url() ?>ro/saveBilling" method="POST">
                <input id="billing-add-tarif_rs" type="hidden" value="0" class="form-control" name="tarif_rs" placeholder="" required>
                <input id="billing-add-tarif_dr" type="hidden" value="0" class="form-control" name="tarif_dr" placeholder="" required>
                <input id="billing-add-noradio" type="hidden" value="<?= $row->noradio ?>" class="form-control" name="noradio" placeholder="" required>
                <input id="billing-add-id" type="hidden" value="<?= $row->id ?>" class="form-control" name="id" placeholder="" required>

                <div class="modal-body" style="padding-right: 30px;padding-left: 30px;">
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Tindakan</label>
                        <div class="col-sm-9">
                            <select id="billing-add-tindakan_id" required name="tindakan_id" class="selectpicker" data-live-search="true" data-source="add" data-width="100%" onkeypress="return tabE(this,event)" required>
                                <option value="" selected disabled>-- Pilih Data --</option>
                                <?php
                                foreach ($tindakan as $value) {
                                ?>

                                    <option value="<?= $value->kodetarif ?>"><?= $value->tindakan ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-3 col-form-label">QTY</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="billing-add-qty" value="1" name="qty" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tarifrp" class="col-sm-3 col-form-label">Tarif Rp</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-add-tarifrp" type="text" class="form-control" name="tarifrp" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Cito</label>
                        <div class="col-sm-9">
                            <input type="checkbox" value="1" name="cito" class="form-check-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="citorp" class="col-sm-3 col-form-label">Cito Rp</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-add-citorp" type="text" value="0" class="form-control" name="citorp" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Total Biaya</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-add-total_biaya" type="text" class="form-control" name="total_biaya" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                        Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Billing Edit -->
<div class="modal fade" id="tesModalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Billing</h4>
            </div>
            <form action="<?= base_url() ?>ro/updateBilling" method="POST">
                <input id="billing-edit-tarif_rs" type="hidden" value="" class="form-control" name="tarif_rs" placeholder="" required>
                <input id="billing-edit-tarif_dr" type="hidden" value="" class="form-control" name="tarif_dr" placeholder="" required>
                <input id="billing-edit-noradio" type="hidden" value="<?= $row->noradio ?>" class="form-control" name="noradio" placeholder="" required>
                <input id="billing-edit-id" type="hidden" value="<?= $row->id ?>" class="form-control" name="id" placeholder="" required>
                <input id="billing-edit-id-billing" type="hidden" class="form-control" name="id_billing" placeholder="" required>

                <div class="modal-body" style="padding-right: 30px;padding-left: 30px;">
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Tindakan</label>
                        <div class="col-sm-9">
                            <select id="billing-edit-tindakan_id" required name="tindakan_id" class="selectpicker" data-live-search="true" data-source="edit" data-width="100%" onkeypress="return tabE(this,event)" required>
                                <option value="" selected disabled>-- Pilih Data --</option>
                                <?php
                                foreach ($tindakan as $value) { ?>
                                    <option value="<?= $value->kodetarif ?>"><?= $value->tindakan ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-3 col-form-label">QTY</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="billing-edit-qty" value="" name="qty" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tarifrp" class="col-sm-3 col-form-label">Tarif Rp</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-edit-tarifrp" type="text" class="form-control" name="tarifrp" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Cito</label>
                        <div class="col-sm-9">
                            <input type="checkbox" value="1" name="cito" id="cito" class="form-check-inpu">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="citorp" class="col-sm-3 col-form-label">Cito Rp</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-edit-citorp" type="text" value="" class="form-control" name="citorp" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Total Biaya</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="billing-edit-total_biaya" type="text" class="form-control" name="total_biaya" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                        Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bhp Modal Add -->
<div class="modal fade" id="bhpModalAdd">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Simpan BHP</h4>
            </div>
            <form action="<?= base_url() ?>ro/saveBhp" method="POST">

                <input id="bhp-koders" type="hidden" value="<?= $this->session->userdata('unit') ?>" class="form-control" name="koders" placeholder="" required>
                <input id="bhp-nolaborat" type="hidden" value="<?= $row->noradio ?>" class="form-control" name="notr" placeholder="" required>
                <input id="id" type="hidden" value="<?= $row->id ?>" class="form-control" name="id" placeholder="" required>

                <div class="modal-body" style="padding-right: 30px;padding-left: 30px;">

                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Bill</label>
                        <div class="col-sm-9">
                            <input type="checkbox" name="bill" id="bhp-bill" class="form-check-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namabarang" class="col-sm-3 col-form-label">Nama Barang</label>
                        <div class="col-sm-9">
                            <select id="bhp-nama-barang" required name="kodeobat" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" required>
                                <option value="" selected disabled>-- Pilih Data --</option>
                                <?php
                                foreach ($barang as $value) { ?>
                                    <option value="<?= $value->kodebarang ?>" data-satuan="<?= $value->satuan1 ?>" data-harga="<?= $value->hargajual ?>"><?= $value->namabarang ?> -
                                        <?= $value->satuan1 ?> - <?= $value->hargajual ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tarifrp" class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                            <!-- <div class="input-group"> -->
                            <!-- <span class="input-group-addon">Rp</span> -->
                            <input id="bhp-satuan" type="text" class="form-control" name="satuan" placeholder="" readonly required>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-9">
                            <input type="number" value="1" name="qty" id="bhp-qty" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="citorp" class="col-sm-3 col-form-label">Harga</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="bhp-harga" type="text" value="" class="form-control" name="harga" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Total Harga</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="bhp-total-harga" type="text" class="form-control" name="totalharga" readonly placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Lokasi Barang</label>
                        <div class="col-sm-9">
                            <!-- <div class="input-group">
                                <span class="input-group-addon">Rp</span> -->
                            <input id="bhp-lokasi-barang" type="text" class="form-control" name="gudang" placeholder="" required>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                        Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bhp Modal Edit -->
<div class="modal fade" id="bhpModalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit BHP</h4>
            </div>
            <form action="<?= base_url() ?>lab/updateBhp" method="POST">

                <input id="bhp-edit-koders" type="hidden" value="<?= $this->session->userdata('unit') ?>" class="form-control" name="koders" placeholder="" required>
                <input id="bhp-edit-nolaborat" type="hidden" value="<?= $row->noradio ?>" class="form-control" name="notr" placeholder="" required>
                <input id="bhp-edit-id" type="hidden" class="form-control" name="id" placeholder="" required>
                <input id="bhp-edit-id-pemeriksaan" type="hidden" class="form-control" name="id_pemeriksaan" placeholder="" required>

                <div class="modal-body" style="padding-right: 30px;padding-left: 30px;">

                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Bill</label>
                        <div class="col-sm-9">
                            <input type="checkbox" name="bill" id="bhp-edit-bill" class="form-check-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namabarang" class="col-sm-3 col-form-label">Nama Barang</label>
                        <div class="col-sm-9">
                            <select id="bhp-edit-nama-barang" required name="kodeobat" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" required>
                                <option value="" disabled>-- Pilih Data --</option>
                                <?php
                                foreach ($barang as $value) { ?>
                                    <option value="<?= $value->kodebarang ?>" data-satuan="<?= $value->satuan1 ?>" data-harga="<?= $value->hargajual ?>"><?= $value->namabarang ?> -
                                        <?= $value->satuan1 ?> - <?= $value->hargajual ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tarifrp" class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                            <!-- <div class="input-group"> -->
                            <!-- <span class="input-group-addon">Rp</span> -->
                            <input id="bhp-edit-satuan" type="text" class="form-control" name="satuan" placeholder="" readonly required>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-9">
                            <input type="number" value="1" name="qty" id="bhp-edit-qty" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="citorp" class="col-sm-3 col-form-label">Harga</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="bhp-edit-harga" type="text" value="" class="form-control" name="harga" placeholder="" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Total Harga</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="bhp-edit-total-harga" type="text" class="form-control" readonly name="totalharga" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namapas" class="col-sm-3 col-form-label">Lokasi Barang</label>
                        <div class="col-sm-9">
                            <!-- <div class="input-group">
                                <span class="input-group-addon">Rp</span> -->
                            <input id="bhp-edit-lokasi-barang" type="text" class="form-control" name="gudang" placeholder="" required>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                        Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


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
            <form action="<?= base_url() ?>ro/updateDataPemeriksaan" method="POST" enctype="multipart/form-data">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">ENTRY PEMERIKSAAN dan BILLING Radiologi</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="noradio" class="col-sm-3 col-form-label">No Pemeriksaan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="noradio" value="<?= $row->noradio ?>" id="noradio" placeholder="" readonly>
                                <input type="hidden" class="form-control" name="id" value="<?= $row->id ?>" id="id" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tglradio" class="col-sm-3 col-form-label">Tanggal Periksa</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tglradio" name="tglradio" value="<?= str_replace(' 00:00:00', '', $row->tglradio) ?>" placeholder="" format="YYYY-MM-DD" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="noreg" class="col-sm-3 col-form-label">No Registrasi</label>
                            <div class="col-sm-5">
                                <select id="noreg" name="noreg" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" required>
                                    <option value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($noReg as $value) { ?>
                                        <?php if ($row->noreg == $value->noreg) { ?>
                                            <option value="<?php echo $value->noreg ?>" selected><?php echo $value->noreg ?>
                                            </option>
                                        <?php } else { ?>
                                            <option value="<?= $value->noreg ?>"><?= $value->noreg ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rekmed" name="rekmed" value="<?= $row->rekmed ?>" placeholder="No RM" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="namapas" class="col-sm-3 col-form-label">Nama Pasien</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="namapas" name="namapas" value="<?= $row->namapas ?>" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" value="<?= str_replace(' 00:00:00', '', $row->tgllahir) ?>" placeholder="" format="YYYY-MM-DD" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="umurth" class="col-sm-3 col-form-label">Umur</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurth" name="umurth" value="<?= $row->umurth ?>" placeholder="Tahun" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurbl" name="umurbl" value="<?= $row->umurbl ?>" placeholder="Bulan" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" id="umurhr" name="umurhr" value="<?= $row->umurhr ?>" placeholder="Hari" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Kalamin</label>
                            <div class="col-sm-2 " style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="1" name="jkel" id="jenis_kelamin1" placeholder="" required <?php echo $row->jkel == '1' ? 'checked' : 'null' ?>> <label for="jenis_kelamin1" style="margin-left: 20px;">Pria</label>
                            </div>
                            <div class="col-sm-2" style="margin-top: -10px !important;">
                                <input type="radio" class="form-check-input" value="2" name="jkel" id="jenis_kelamin2" placeholder="" required <?php echo $row->jkel == '2' ? 'checked' : 'null' ?>> <label for="jenis_kelamin2" style="margin-left: 20px;">Wanita</label>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="orderno" class="col-sm-3 col-form-label">No Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="orderno" value="<?= $row->orderno ?>" name="orderno" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pasien</label>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="1" id="jpas1" name="jpas" placeholder="" <?php echo $row->jpas == '1' ? 'checked' : 'null' ?>> <label for="jpas1" style="margin-left: 20px;">Pasien RS</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input required type="radio" class="form-check-input" value="2" id="jpas2" name="jpas" placeholder="" <?php echo $row->jpas == '2' ? 'checked' : 'null' ?>> <label for="jpas2" style="margin-left: 20px;">Pasien Luar</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Pemeriksaan</label>
                            <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="jenisperiksa1" name="jenisperiksa" placeholder="" <?php echo $row->jenisperiksa == '1' ? 'checked' : 'null' ?>> <label for="jenisperiksa1" style="margin-left: 20px;">Rujukan Dokter</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="jenisperiksa2" name="jenisperiksa" placeholder="" <?php echo $row->jenisperiksa == '2' ? 'checked' : 'null' ?>> <label for="jenisperiksa2" style="margin-left: 20px;">Permintaan Sendiri</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Pemeriksaan</label>
                            <div class="col-sm-3 " style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="1" id="rujuk1" name="rujuk" placeholder="" <?php echo $row->rujuk == '1' ? 'checked' : 'null' ?>> <label for="rujuk1" style="margin-left: 20px;">Lab Dalam</label>
                            </div>
                            <div class="col-sm-3" style="margin-top: -10px !important;">
                                <input type="radio" required class="form-check-input" value="2" id="rujuk2" name="rujuk" placeholder="" <?php echo $row->rujuk == '2' ? 'checked' : 'null' ?>> <label for="rujuk2" style="margin-left: 20px;">Dikirim Ke Faskes Lain</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diagnosa" class="col-sm-3 col-form-label">Diagnosa</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control" id="diagnosa" name="diagnosa" value="<?= $row->diagnosa ?>" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="drperiksa" class="col-sm-3 col-form-label">Dokter Pengirim</label>
                            <div class="col-sm-9">
                                <select required id="drperiksa" name="drperiksa" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                    <option value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($dataDokter as $value) { ?>
                                        <?php if ($row->drpengirim == $value->kodokter) { ?>
                                            <option value="<?php echo $value->kodokter ?>" selected>
                                                <?php echo $value->nadokter ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $value->kodokter ?>"><?= $value->nadokter ?></option>
                                        <?php } ?>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="drpengirim" class="col-sm-3 col-form-label">Dokter Laborat</label>
                            <div class="col-sm-9">
                                <select required id="drpengirim" name="drpengirim" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                    <option value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($dataDokter as $value) { ?>

                                        <?php if ($row->drpengirim == $value->kodokter) { ?>
                                            <option value="<?php echo $value->kodokter ?>" selected>
                                                <?php echo $value->nadokter ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $value->kodokter ?>"><?= $value->nadokter ?></option>
                                        <?php } ?>
                                    <?php }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Petugas Lab</label>
                            <div class="col-sm-9">
                                <select required id="kodepetugas" name="kodepetugas" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                    <option value="" selected disabled>-- Pilih Data --</option>
                                    <?php
                                    foreach ($petugas as $value) { ?>
                                        <?php if ($row->kodepetugas == $value->nokk) { ?>
                                            <option value="<?php echo $value->nokk ?>" selected>
                                                <?php echo $value->namapetugas ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $value->nokk ?>"><?= $value->namapetugas ?></option>
                                        <?php } ?>

                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-5">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Billing</a></li>
                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">BHP</a></li>
                            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Hasil</a></li>
                            <!-- <li role="presentation"><a href="#settings" aria-controls="settings" role="tab"
                                    data-toggle="tab">Hasil Text</a></li> -->
                        </ul>
                        <div class="tab-content">
                            <!-- Tba billing -->
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="button" style="margin-bottom: 10px;" data-toggle="modal" data-target="#tesModal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                        Billing</button>
                                    <table class="table table-bordered" id="dynamic_field">
                                        <thead bgcolor="#00c11e">
                                            <tr style="color: white;">
                                                <th scope="col" style="width: 90px;">Aksi</th>
                                                <th scope="col">Tindakan</th>
                                                <th scope="col" style="width: 100px;">Qty</th>
                                                <th scope="col" style="width: 120px;">Tarif RP</th>
                                                <th scope="col" style="width: 100px;">Cito</th>
                                                <th scope="col" style="width: 120px;">Cito Rp</th>
                                                <th scope="col" style="width: 150px;">Total Biaya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $unit = $this->session->userdata("unit");
                                            $dataBilling = $this->db->query("SELECT tbl_dradio.*,daftar_tarif_nonbedah.tindakan,daftar_tarif_nonbedah.koders from tbl_dradio
                                            join daftar_tarif_nonbedah on daftar_tarif_nonbedah.kodetarif = tbl_dradio.kodetarif
                                            where noradio='$row->noradio' and daftar_tarif_nonbedah.koders ='$unit'")->result();
                                            ?>
                                            <?php foreach ($dataBilling as $value) {

                                            ?>
                                                <tr>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm update_data" 
                                                        data-jenis="<?= $value->jenis ?>" 
                                                        data-tarifrs="<?= $value->tarifrs ?>" 
                                                        data-tarifdr="<?= $value->tarifdr ?>" 
                                                        data-qty="<?= $value->qty ?>" 
                                                        data-cito_rp="<?= $value->cito_rp ?>" 
                                                        data-total_biaya="<?= $value->total_biaya ?>" 
                                                        data-id_billing="<?= $value->id ?>" 
                                                        data-kode_tarif="<?= $value->kodetarif ?>" 
                                                        data-toggle="modal" 
                                                        data-target="#tesModalEdit" id="btn-billing-edit">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="<?= base_url() ?>ro/delDataBilling/<?= $value->id ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </td>
                                                    <td><?= $value->tindakan ?></td>
                                                    <td><?= $value->qty ?></td>
                                                    <td><?= $value->tarifrs +  $value->tarifdr ?></td>
                                                    <?php if ($value->jenis == 1) { ?>
                                                        <td> <input type="checkbox" value="" name="" class="form-check-inpu" checked disabled style="color: blue;"></td>
                                                    <?php } else { ?>
                                                        <td> <input type="checkbox" value="" name="" class="form-check-inpu" disabled></td>
                                                    <?php } ?>

                                                    <td><?= rupiah($value->cito_rp) ?></td>
                                                    <td><?= rupiah($value->total_biaya) ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <th colspan="6" style="text-align: right;">Total Billing</th>
                                            <?php $grand_total = $this->db->query("SELECT SUM(total_biaya) AS grand_total FROM tbl_dradio where noradio='$row->noradio'")->row();
                                            ?>
                                            <th><?= $grand_total->grand_total ?></th>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                            <!-- Tab BHP -->
                            <div role="tabpanel" class="tab-pane" id="profile">
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="button" style="margin-bottom: 10px;" data-toggle="modal" data-target="#bhpModalAdd"><i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                        Bhp</button>
                                    <table class="table table-bordered">
                                        <thead bgcolor="#00c11e">
                                            <tr style="color: white;">
                                                <th scope="col">Aksi</th>
                                                <th scope="col">Bill</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Satuan</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Harga</th>
                                                <th scope="col">Total Harga</th>
                                                <th scope="col">Lokasi Barang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $unit = $this->session->userdata("unit");
                                            $dataBilling = $this->db->query("SELECT * from tbl_alkestransaksi
                                            where notr='$row->noradio' ")->result();
                                            ?>
                                            <?php foreach ($dataBilling as $value) {
                                                $icon = ($value->dibebankan) ? "check" : "times";

                                                $kode_obat = $value->kodeobat;
                                                $barang_filter = array_filter($barang, function ($obj) use ($kode_obat) {
                                                    if ($obj->kodebarang === $kode_obat) {
                                                        return true;
                                                    }
                                                    return false;
                                                });

                                                $barang_filter = [...$barang_filter];
                                                $nama_barang = (count($barang_filter)) ? $barang_filter[0]->namabarang : '-';
                                            ?>
                                                <tr>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm update_data_bhp" 
                                                        data-kode_barang="<?= $value->kodeobat ?>" 
                                                        data-satuan="<?= $value->satuan ?>" 
                                                        data-qty="<?= $value->qty ?>" 
                                                        data-harga="<?= $value->harga ?>" 
                                                        data-total_harga="<?= $value->totalharga ?>" 
                                                        data-gudang="<?= $value->gudang ?>" 
                                                        data-id="<?= $value->id ?>" 
                                                        data-id_pemeriksaan="<?= $row->id ?>" 
                                                        data-dibebankan="<?= $value->dibebankan ?>" 
                                                        data-toggle="modal" 
                                                        data-target="#bhpModalEdit">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="<?= base_url() ?>ro/delDataBhp/<?= $value->id ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </td>
                                                    <td align="center"><i class="icon fa fa-<?= $icon ?>"></i></tdc>
                                                    <td><?= $nama_barang ?></td>
                                                    <td><?= $value->satuan ?></td>
                                                    <td><?= $value->qty ?></td>
                                                    <td><?= rupiah($value->harga) ?> </td>
                                                    <td><?= rupiah($value->totalharga) ?></td>
                                                    <td><?= $value->gudang  ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Tab Hasil -->
                            <div role="tabpanel" class="tab-pane" id="messages">
                                <?php
                                $unit = $this->session->userdata("unit");
                                $dataHradio = $this->db->query("SELECT * from tbl_hradio where noradio='$row->noradio'")->row();
                                $rilis = ($dataHradio->keluar) ? true : false;
                                ?>
                                    <input type="hidden" value="<?= $row->id ?>" name="id_pemeriksaan">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Tgl jam foto diambil</label>
                                            <div class="d-flex">
                                                <input type="hidden" name="noradio" value="<?= $row->noradio ?>">
                                                <input type="date" name="tanggal_foto_diambil" min="2018-06-07" class="form-control" value="<?= str_replace(' 00:00:00', '', $dataHradio->tglambil) ?>" id="hasil-tanggal-sampel-diambil" placeholder="" format="YYYY-MM-DD">
                                                <input type="time" name="jam_foto_diambil" class="form-control" value="<?= trim($dataHradio->jamambil) ?>" id="hasil-jam-sampel-diambil" placeholder="" step="1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Tanggal jam Selesai dibaca</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control" min="2018-06-07" value="<?= str_replace(' 00:00:00', '', $dataHradio->tglselesai) ?>" name="tanggal_selesai_periksa" id="hasil-jam-selesai-periksa" placeholder="">
                                                <input type="time" name="jam_selesai_periksa" class="form-control" value="<?= trim($dataHradio->jamselesai) ?>" id="hasil-jam-selesai-periksa" placeholder="" step="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Oleh Petugas</label>
                                            <select name="oleh_petugas" class="form-control" id="hasil-oleh-petugas" placeholder="">
                                                <option value selected disabled>--Pilih salah Satu--</option>
                                                <?php
                                                foreach ($petugas as $value) :
                                                ?>
                                                    <option value="<?= $value->nokk ?>" <?= ($dataHradio->kodepetugas == $value->nokk) ? 'selected' : '' ?>> <?= $value->namapetugas ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group m-0">
                                            <label class="form-check-label d-flex  " for="exampleCheck1">
                                                <input type="checkbox" class="form-check-input" id="hasil-final-oleh" name="dibaca_oleh" checked=<?= $rilis ?>>
                                                Dibaca Oleh
                                            </label>
                                            <select name="kode_pemeriksaan" class="form-control" id="hasil-kode-pemeriksaan" placeholder="">
                                                <option value selected disabled>--Pilih salah Satu--</option>
                                                <?php
                                                foreach ($petugas as $value) :
                                                ?>
                                                    <option value="<?= $value->nokk ?>" <?= ($dataHradio->drperiksa == $value->nokk) ? 'selected' : '' ?>> <?= $value->namapetugas ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="divided col-lg-12 mt-4"></div>

                                    <?php
                                    $tbl_notes = $this->db->query("SELECT * from tbl_expertise where noradio='$row->noradio'")->row();
                                    if (isset($tbl_notes->catatan)) {
                                        $dataCatatan =  $tbl_notes->catatan;
                                    } else {
                                        $dataCatatan = '';
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4>Hasil / EXPERTISED</h4>
                                            <textarea type="text" name="catatan_hasil" rows="5" class="form-control"> <?= $dataCatatan ?></textarea>
                                            <div>
                                                <button type="submit" id="button_simpan_hasil" class="btn btn-primary" style="margin-top:10px;margin-left:auto">
                                                <i class="fa fa-plus" aria-hidden="true"></i> 
                                                    Simpan Hasil
                                                </button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="col-lg-6 mt-sm-2 mt-md-2">
                                            <div class="d-flex justify-content-between mt-2" style="margin-top:8px">

                                                <h6>File hasil pengambilan foto</h6>
                                                <button type="button" class="btn btn-primary" style="margin-bottom: 10px;" onclick="addBerkasHasil()"><i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                                    Berkas
                                                </button>
                                            </div>
                                            <table class="table table-bordered" id="dynamic_field">
                                                <thead bgcolor="#00c11e">
                                                    <tr style="color: white;">
                                                        <th scope="col">Keterangan</th>
                                                        <th scope="col" style="width: 100px;">File Hasil</th>
                                                        <th scope="col" style="width: 120px;">View</th>
                                                        <th scope="col" style="width: 100px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                
                                                <?php 
                                                    $hasil_radio_file = $this->db->query("SELECT * FROM tbl_dradiofile WHERE noradio='$row->noradio'")->result();
                                                ?>
                                                <?php if ( count($hasil_radio_file) > 0) { ?>
                                                    <tbody id="berkas-hasil">
                                                        <?php foreach ($hasil_radio_file as $radio_file) :?>
                                                            <tr id="berkas-<?= $radio_file->id ?>">
                                                                <td><textarea class="form-control" name="keterangan_berkas-<?= $radio_file->id ?>" type="text" resizable><?= $radio_file->keteranganfile ?></textarea></td>
                                                                <td>
                                                                    <input type="file" onchange="loadfile(event, id)" name="file_berkas-<?= $radio_file->id ?>" value="<?= base_url($radio_file->lokasifile)  ?>">
                                                                    <input type="hidden" name="old_file-<?= $radio_file->id ?>" value="<?= $radio_file->namafile ?>">
                                                                </td>
                                                                <td><a target="__blank" href="<?= base_url($radio_file->lokasifile) ?>" id="view_berkas-<?= $radio_file->id  ?>">lihat file</a></td>
                                                                <td><button type="button" class="btn btn-danger" onclick="hapusBerkasAjax(`<?= $radio_file->id ?>`, `<?= $radio_file->noradio ?>`)" ><i class="fa fa-trash"></i></button></td>
                                                            </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                <?php } else {?>
                                                    <tbody id="berkas-hasil">
                                                    </tbody>
                                                <?php } ?>
                                               
                                            </table>
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
                $('#rekmed').val(data.rekmed)
                $('#namapas').val(data.namapas)
            }
        });
    })
</script>

<script>
    $('#billing-add-tindakan_id, #billing-edit-tindakan_id').change(function() {
        let source = 'billing-' + $(this).data('source') + '-';

        let kodetarif = $(this).val()
        var citorp = $('#' + source + 'citorp').val().replace('.00', '');
        let qty = $('#' + source + 'qty').val()

        $('#' + source + 'tarifrp').val('Loading...')
        $('#' + source + 'total_biaya').val('Loading...')
        $.ajax({
            url: '<?php echo site_url('lab/get_daftar_tarif_nonbedah ') ?>',
            type: 'POST',
            dataType: "JSON",
            data: {
                kodetarif: kodetarif
            },
            success: function(res) {
                let data;
                try {
                    data = JSON.parse(res)
                } catch (e) {
                    data = res
                }
                var jml = qty * (parseFloat(data.tarifrspoli) + parseFloat(data.tarifdrpoli))
                $('#' + source + 'tarifrp').val(parseFloat(jml))

                if (citorp) {
                    var citorp = citorp
                } else {
                    var citorp = 0
                }

                if (jml) {
                    var jml = jml
                } else {
                    var jml = 0
                }


                $('#' + source + 'total_biaya').val(parseFloat(jml) + parseFloat(citorp))
                $('#' + source + 'tarif_rs').val(parseFloat(data.tarifrspoli))
                $('#' + source + 'tarif_dr').val(parseFloat(data.tarifdrpoli))
            }
        });
    });


    $('#billing-add-citorp, #billing-add-qty').on('keyup mouseup', function() {
        var citorp = $('#billing-add-citorp').val()
        var jml = $('#billing-add-tarifrp').val()
        let qty = $('#billing-add-qty').val();


        if (citorp) {
            var citorp = citorp
        } else {
            var citorp = 0
        }

        if (jml) {
            var jml = jml
        } else {
            var jml = 0
        }


        let total_biaya = (qty * parseFloat(jml)) + parseFloat(citorp);
        $('#billing-add-total_biaya').val(total_biaya)
    });

    $('#billing-edit-citorp, #billing-edit-qty').on('keyup mouseup', function() {
        var citorp = $('#billing-edit-citorp').val()
        var jml = $('#billing-edit-tarifrp').val()
        let qty = $('#billing-edit-qty').val();

        if (citorp) {
            var citorp = citorp
        } else {
            var citorp = 0
        }

        if (jml) {
            var jml = jml
        } else {
            var jml = 0
        }

        let total_biaya = (qty * parseFloat(jml)) + parseFloat(citorp);
        $('#billing-edit-total_biaya').val(total_biaya)
    });
</script>

<script>
    $('.update_data').click(function() {
        var jenis = $(this).data('jenis');
        var qty = $(this).data('qty');
        var cito_rp = $(this).data('cito_rp');
        var total_biaya = $(this).data('total_biaya');
        var tarifrs = $(this).data('tarifrs');
        var tarifdr = $(this).data('tarifdr');
        var kode_tarif = $(this).data('kode_tarif');
        var id_billing = $(this).data('id_billing');
        var tarifrp = tarifrs + tarifdr;

        if (jenis == 1) {
            $('#tesModalEdit #billing-edit-cito').prop("checked", true);
        } else {
            $('#tesModalEdit #billing-edit-cito').prop("checked", false);
        }

        $('#tesModalEdit #billing-edit-qty').val(qty);
        $('#tesModalEdit #billing-edit-id-billing').val(id_billing);
        $('#tesModalEdit #billing-edit-citorp').val(cito_rp);
        $('#tesModalEdit #billing-edit-total_biaya').val(total_biaya);
        $('#tesModalEdit #billing-edit-tarif_rs').val(tarifrs);
        $('#tesModalEdit #billing-edit-tarif_dr').val(tarifdr);
        $('#tesModalEdit #billing-edit-tarifrp').val(tarifrp);
        $('#tesModalEdit #billing-edit-tindakan_id').val(kode_tarif).change();
    });

    $('.update_data_bhp').click(function() {
        var id = $(this).data('id');
        var id_pemeriksaan = $(this).data('id_pemeriksaan');
        var kode_barang = $(this).data('kode_barang');
        var satuan = $(this).data('satuan');
        var qty = $(this).data('qty');
        var harga = $(this).data('harga');
        var total_harga = $(this).data('total_harga');
        var gudang = $(this).data('gudang');
        var dibebankan = $(this).data('dibebankan');

        let bill = (dibebankan) ? true : false;

        $('#bhpModalEdit #bhp-edit-bill').prop("checked", bill);
        $('#bhpModalEdit #bhp-edit-nama-barang option[value="' + kode_barang + '"]').attr("selected",
            "selected").change();
        $('#bhpModalEdit #bhp-edit-nama-barang').val(kode_barang);
        $('#bhpModalEdit #bhp-edit-satuan').val(satuan);
        $('#bhpModalEdit #bhp-edit-qty').val(qty);
        $('#bhpModalEdit #bhp-edit-harga').val(harga);
        $('#bhpModalEdit #bhp-edit-total-harga').val(total_harga);
        $('#bhpModalEdit #bhp-edit-lokasi-barang').val(gudang);
        $('#bhpModalEdit #bhp-edit-id').val(id);
        $('#bhpModalEdit #bhp-edit-id-pemeriksaan').val(id_pemeriksaan);

    });

    //when change nama_barang in bhp tab
    $('#bhp-nama-barang').on("change", function() {
        let satuan = $(this).find('option:selected').data('satuan');
        let harga = $(this).find('option:selected').data('harga');
        $('#bhp-satuan').val(satuan);
        $('#bhp-harga').val(harga);
        $('#bhp-qty').prop('disabled', false);
        triggerTotalHarga();
    })

    $('#bhp-edit-nama-barang').on("change", function() {
        let satuan = $(this).find('option:selected').data('satuan');
        let harga = $(this).find('option:selected').data('harga');
        $('#bhp-edit-satuan').val(satuan);
        $('#bhp-edit-harga').val(harga);
        $('#bhp-edit-qty').prop('disabled', false);
        $('#bhp-edit-qty').val(1);
        triggerTotalHargaEdit();
    })

    $('#bhp-qty').on('keyup', function() {
        triggerTotalHarga();
    });

    $('#qty').on('keyup', function() {
        triggerTotalHargaBilling();
    });

    $('#bhp-edit-qty').on('keyup', function() {
        triggerTotalHargaEdit();
    });

    function triggerTotalHarga() {
        let total_harga = $('#bhp-qty').val() * $('#bhp-harga').val();
        $('#bhp-total-harga').val(total_harga);
    }

    function triggerTotalHargaBilling() {
        let total_harga = ($('#qty').val() * $('#tarifrp').val()) + $('#citorp').val();
        console.log({
            total_harga
        });
        $('#total_harga').val(total_harga);
    }

    function triggerTotalHargaEdit() {
        let total_harga = $('#bhp-edit-qty').val() * $('#bhp-edit-harga').val();
        $('#bhp-edit-total-harga').val(total_harga);
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

    function hapusBerkas(unique_id) {
        $('#berkas-' + unique_id).remove();
    }

    function hapusBerkasAjax(id, no_laborat) {
        let base_url = <?php echo "'" . site_url('/') . "'" ?>;
        $.ajax({
            type: 'post',
            url : `${base_url}ro/hapusRadiofile/${id}/${no_laborat}`,
            success: function(result) {
                window.location.reload();
            }
        });
    }

    function randomInteger(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>