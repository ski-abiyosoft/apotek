<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
?>
<div class="row">
  <div class="col-md-12">
      <h3 class="page-title">
      <span class="title-unit">
            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
      </span>
      - 
      <span class="title-web">MASTER <small>Tarif</small>
      </h3>
      <ul class="page-breadcrumb breadcrumb">

            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">
                Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">
                Master
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">
                Tarif
                </a>
            </li>
      </ul>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Daftar Master Tarif</div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <button class="btn btn-success" onclick="add_new()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
        </div>
        <table id="table_mtarif" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
          <thead class="breadcrumb header-custom">
            <tr>
              <th style="text-align: center; color: white;">Kode Tarif</th>
              <th style="text-align: center; color: white;">Tidakan</th>
              <th style="text-align: center; color: white;">Poli</th>
              <th style="text-align: center; color: white;">Akun Pendapatan</th>
              <th style="text-align: center; color: white;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($master_tarif as $mt) : ?>
              <tr>
                <td><?= $mt->kodetarif; ?></td>
                <td><?= $mt->tindakan; ?></td>
                <td><?= $mt->kodepos; ?></td>
                <td><?= $mt->akun; ?></td>
                <td style="text-align: center;">
                  <input type="hidden" id="id_data" name="id_data" value="<?= $mt->id; ?>">
                  <a class="btn btn-sm btn-primary" type="button" title="Edit" href="<?= site_url('Master_tarif/ubah_hal/').$mt->id; ?>"><i class="glyphicon glyphicon-edit"></i> </a>
                  <!-- <button class="btn btn-sm btn-primary" type="button" title="Edit" onclick="get_data(<?= $mt->id; ?>)"><i class="glyphicon glyphicon-edit"></i> </button> -->
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('<?= $mt->id; ?>','<?= $mt->tindakan; ?>','<?= $mt->kodetarif; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
                  <!-- <a class="btn btn-sm btn-warning" href="<?= site_url('Master_tarif/edit_data_bhp/'). $mt->id;?>" title="BHP" ><i class="glyphicon glyphicon-edit"></i> BHP </a> -->
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- modal tambah -->
<div class="modal" tabindex="-1" role="dialog" id="tambah_master">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="form_tambah">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Kode Tarif</label>
              <div class="col-md-8">
                <input type="text" name="kode" id="kode" readonly class="form-control" placeholder="AUTO">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Unit</label>
              <div class="col-md-8">
                <select name="poli" id="poli" class="form-control select2_unit">
                  <option value=""></option>
                  <?php foreach ($poli as $row) { ?>
                      <option value="<?= $row->kodepos; ?>"><?= $row->namapost; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Tindakan / Layanan</label>
              <div class="col-md-8">
                <input type="text" name="tindakan" id="tindakan" class="form-control" placeholder="Nama Tindakan...">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Akun Pendapatan</label>
              <div class="col-md-8">
                <select name="akunpendapatan" id="akunpendapatan" class="form-control select2_pendapatan">
                  <option value=""></option>
                  <?php foreach($pendapatan as $s) : ?>
                      <option value="<?= $s->id; ?>"><?= $s->text; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4"></label>
              <div class="col-md-8" style="display: flex; gap: 5px;">
                  <input name="tidakaktif" id="tidakaktif" type="checkbox">
                  <label for="tidakaktif"> Tidak dipakai </label>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="tabbable tabbable-custom tabbable-full-width">
                <ul class="nav nav-tabs">
                  <li class="active" id="tab_detail">
                    <a href="#tab1" data-toggle="tab">Detail Tarif</a>
                  </li>
                  <li class="" id="tab_costbhp">
                    <a href="#tab2" data-toggle="tab">Cost & BHP</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab1">
                    <table id="detail_tarif" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                      <thead class="breadcrumb header-custom">
                        <tr>
                          <th style="text-align: center; color: white;" width="5%">Hapus</th>
                          <th style="text-align: center; color: white;" width="10%">Cabang</th>
                          <th style="text-align: center; color: white;" width="10%">Kelompok Tarif</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa RS/Klinik</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa Dokter</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa Perawat</th>
                          <th style="text-align: center; color: white;" width="15%">BHP</th>
                          <th style="text-align: center; color: white;" width="15%">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="row_detail_tarif1">
                          <td>
                              <button type='button' onclick=hapusBarisIni(1) class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td>
                            <select name='cabang[]' id='cabang1' class='select2_cabang form-control input-largex'>
                              <option value=""></option>
                              <?php foreach($cabang as $c) : ?>
                                <option value="<?= $c->id; ?>"><?= $c->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <select name='keltarif[]' id='keltarif1' class='select2_tarif form-control input-largex'>
                              <option value=""></option>
                              <?php foreach($tarif as $t) : ?>
                                <option value="<?= $t->id; ?>"><?= $t->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input name='jasars[]' id='jasars1' onkeyup='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='jasadr[]' id='jasadr1' onkeyup='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='jasaperawat[]' id='jasaperawat1' onkeyup='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='bhp[]' id='bhp1' onkeyup='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='total[]' id='total1' readonly value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab2">
                    <table id="cost_bhp" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                      <thead class="breadcrumb header-custom">
                        <tr>
                          <th style="text-align: center; color: white;" width="5%">Hapus</th>
                          <th style="text-align: center; color: white;" width="35%">Kode Barang</th>
                          <th style="text-align: center; color: white;" width="10%">Qty</th>
                          <th style="text-align: center; color: white;" width="10%">Satuan</th>
                          <th style="text-align: center; color: white;" width="20%">Harga</th>
                          <th style="text-align: center; color: white;" width="20%">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="row_cost_bhp1">
                          <td>
                              <button type='button' onclick=hapusBarisBarang(1) class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td>
                            <select name='kodebarang[]' id='kodebarang1' class='select2_barang form-control input-largex' onchange="showbarangname(this.value, 1)">
                              <option value=""></option>
                              <?php foreach($barang as $b) : ?>
                                <option value="<?= $b->id; ?>"><?= $b->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input name='qty[]' id='qty1' onkeyup='totalline_barang(1)' value="1" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='satuan[]' id='satuan1' readonly type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='harga[]' id='harga1' onkeyup='totalline_barang(1)' value="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='jumlah[]' id='jumlah1' readonly value="0" type='text' class='form-control rightJustified'>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-8">
                        <div class="wells">
                          <button type="button" onclick="tambah_barang()" class="btn green"><i class="fa fa-plus"></i> </button>
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <div class="well">
                          <table>
                            <tr>
                              <td width="40%"><strong>Total</strong></td>
                              <td width="1%"><strong>:</strong></td>
                              <td width="59%" align="right"><strong><span id="_vtotal"></span></strong></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-sm" onclick="save()">Simpan</button>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end modal tambah -->

<!-- modal ubah -->
<div class="modal" tabindex="-1" role="dialog" id="ubah_master">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="form_ubah">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Kode Tarif</label>
              <div class="col-md-8">
                <input type="text" name="lupkode" id="lupkode" readonly class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Unit</label>
              <div class="col-md-8">
                <select name="luppoli" id="luppoli" class="form-control select2_lup_unit">
                  <option value=""></option>
                  <?php foreach ($poli as $row) { ?>
                      <option value="<?= $row->kodepos; ?>"><?= $row->namapost; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Tindakan / Layanan</label>
              <div class="col-md-8">
                <input type="text" name="luptindakan" id="luptindakan" class="form-control" placeholder="Nama Tindakan...">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4">Akun Pendapatan</label>
              <div class="col-md-8">
                <select name="lupakunpendapatan" id="lupakunpendapatan" class="form-control select2_lup_pendapatan">
                  <option value=""></option>
                  <?php foreach($pendapatan as $s) : ?>
                      <option value="<?= $s->id; ?>"><?= $s->text; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="control-label col-md-4"></label>
              <div class="col-md-8" style="display: flex; gap: 5px;">
                  <input name="luptidakaktif" id="luptidakaktif" type="checkbox">
                  <label for="tidakaktif"> Tidak dipakai </label>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="tabbable tabbable-custom tabbable-full-width">
                <ul class="nav nav-tabs">
                  <li class="active" id="luptab_detail">
                    <a href="#luptab1" data-toggle="tab">Detail Tarif</a>
                  </li>
                  <li class="" id="luptab_costbhp">
                    <a href="#luptab2" data-toggle="tab">Cost & BHP</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="luptab1">
                    <input type="hidden" name="jum_detail" id="jum_detail">
                    <table id="lupdetail_tarif" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                      <thead class="breadcrumb header-custom">
                        <tr>
                          <th style="text-align: center; color: white;" width="5%">Hapus</th>
                          <th style="text-align: center; color: white;" width="10%">Cabang</th>
                          <th style="text-align: center; color: white;" width="10%">Kelompok Tarif</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa RS/Klinik</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa Dokter</th>
                          <th style="text-align: center; color: white;" width="15%">Jasa Perawat</th>
                          <th style="text-align: center; color: white;" width="15%">BHP</th>
                          <th style="text-align: center; color: white;" width="15%">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="luprow_detail_tarif1">
                          <td>
                              <button type='button' onclick=hapusBarisIni_ubah(1) class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td>
                            <select name='lupcabang[]' id='lupcabang1' class='select2_lupcabang form-control input-largex'>
                              <option value=""></option>
                              <?php foreach($cabang as $c) : ?>
                                <option value="<?= $c->id; ?>"><?= $c->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <select name='lupkeltarif[]' id='lupkeltarif1' class='select2_luptarif form-control input-largex'>
                              <option value=""></option>
                              <?php foreach($tarif as $t) : ?>
                                <option value="<?= $t->id; ?>"><?= $t->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input name='lupjasars[]' id='lupjasars1' onkeyup='luptotallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupjasadr[]' id='lupjasadr1' onkeyup='luptotallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupjasaperawat[]' id='lupjasaperawat1' onkeyup='luptotallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupbhp[]' id='lupbhp1' onkeyup='luptotallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='luptotal[]' id='luptotal1' readonly value="0" min="0" type='text' class='form-control rightJustified'>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="luptambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="luptab2">
                    <table id="lupcost_bhp" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                      <thead class="breadcrumb header-custom">
                        <tr>
                          <th style="text-align: center; color: white;" width="5%">Hapus</th>
                          <th style="text-align: center; color: white;" width="35%">Kode Barang</th>
                          <th style="text-align: center; color: white;" width="10%">Qty</th>
                          <th style="text-align: center; color: white;" width="10%">Satuan</th>
                          <th style="text-align: center; color: white;" width="20%">Harga</th>
                          <th style="text-align: center; color: white;" width="20%">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="luprow_cost_bhp1">
                          <td>
                              <button type='button' onclick=hapusBarisIni_ubahbarang(1) class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td>
                            <select name='lupkodebarang[]' id='lupkodebarang1' class='select2_lupbarang form-control input-largex' onchange="lupshowbarangname(this.value, 1)">
                              <option value=""></option>
                              <?php foreach($barang as $b) : ?>
                                <option value="<?= $b->id; ?>"><?= $b->text; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input name='lupqty[]' id='lupqty1' onkeyup='totalline_lupbarang(1)' value="1" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupsatuan[]' id='lupsatuan1' readonly type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupharga[]' id='lupharga1' onkeyup='totalline_lupbarang(1)' value="0" type='text' class='form-control rightJustified'>
                          </td>
                          <td>
                            <input name='lupjumlah[]' id='lupjumlah1' readonly value="0" type='text' class='form-control rightJustified'>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-8">
                        <div class="wells">
                          <button type="button" onclick="luptambah_barang()" class="btn green"><i class="fa fa-plus"></i> </button>
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <div class="well">
                          <table>
                            <tr>
                              <td width="40%"><strong>Total</strong></td>
                              <td width="1%"><strong>:</strong></td>
                              <td width="59%" align="right"><strong><span id="_lupvtotal"></span></strong></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-sm" onclick="update()">Ubah</button>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end modal ubah -->

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
?>

<!-- datatable -->
<script>
  $("#table_mtarif").DataTable({
    "processing": true,
    "responsive":true,
    "scrollCollapse": false,
    "paging":true,
    "oLanguage": {
        "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
        "sInfoEmpty": "",
        "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
        "sSearch": "Pencarian Data : ",
        "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
        "sLengthMenu": "_MENU_ Baris",
        "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
        "oPaginate": {
        "sPrevious": "Sebelumnya",
        "sNext": "Berikutnya"
        }
    },		
    "aLengthMenu": [
        [5, 15, 20, -1],
        [5, 15, 20, "Semua"]
    ],
  });
</script>
<!-- end datatable -->

<!-- master curency -->
<script>
  function separateComma(val) {
    var sign = 1;
    if (val < 0) {
      sign = -1;
      val = -val;
    }
    let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
    let len = num.toString().length;
    let result = '';
    let count = 1;
    for (let i = len - 1; i >= 0; i--) {
      result = num.toString()[i] + result;
      if (count % 3 === 0 && count !== 0 && i !== 0) {
        result = ',' + result;
      }
      count++;
    }
    if (val.toString().includes('.')) {
      result = result + '.' + val.toString().split('.')[1];
    }
    return sign < 0 ? '-' + result : result;
  }
</script>
<!-- end master curency -->

<!-- select2 -->
<script>
  $(".select2_pendapatan").select2({
    placeholder: "Pilih Akun Pendapatan",
    width: '100%',
    dropdownParent: $("#tambah_master")
  });
  $(".select2_unit").select2({
    placeholder: "Pilih Unit",
    width: '100%',
    dropdownParent: $("#tambah_master")
  });
  $(".select2_cabang").select2({
    placeholder: "Pilih Cabang",
    width: '100%',
    dropdownParent: $("#tambah_master")
  });
  $(".select2_tarif").select2({
    placeholder: "Pilih Tarif",
    width: '100%',
    dropdownParent: $("#tambah_master")
  });


  $(".select2_barang").select2({
    placeholder: "Pilih Barang",
    width: '100%',
    dropdownParent: $("#tambah_master")
  });
</script>
<!-- end select2 -->

<!-- function -->
<script>
  function add_new(){
    $("#tambah_master").modal("show", 200);
  }

  var idrow = 2;
  var idrow2 = 2;
  var rowCount;
  var arr = [1];

  function tambah() {
    var table = document.getElementById('detail_tarif');
    rowCount = table.rows.length;
    arr.push(idrow);

    var x = document.getElementById('detail_tarif').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    td1.innerHTML = "<tr id='row_detail_tarif"+idrow+"'><td id='kolom" + idrow + "'><button type='button' onclick='hapusBarisIni(" + idrow + ");' id=btnhapus" + idrow +" class='btn red'><i class='fa fa-trash-o'></td>"
    td2.innerHTML = "<td><select name='cabang[]' id='cabang"+idrow+"' class='select2_cabang form-control input-largex'><option value=''></option><?php foreach($cabang as $c) : ?><option value='<?= $c->id; ?>'><?= $c->text; ?></option><?php endforeach; ?></select></td>";
    td3.innerHTML = "<td><select name='keltarif[]' id='keltarif"+idrow+"' class='select2_tarif form-control input-largex'><option value=''></option><?php foreach($tarif as $t) : ?><option value='<?= $t->id; ?>'><?= $t->text; ?></option><?php endforeach; ?></select></td>";
    td4.innerHTML = "<td><input name='jasars[]' id='jasars"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td5.innerHTML = "<td><input name='jasadr[]' id='jasadr"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td6.innerHTML = "<td><input name='jasaperawat[]' id='jasaperawat"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td7.innerHTML = "<td><input name='bhp[]' id='bhp"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td8.innerHTML = "<td><input name='total[]' id='total"+idrow+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>";
    idrow++;
    $(".select2_pendapatan").select2({
      placeholder: "Pilih Akun Pendapatan",
      width: '100%',
      dropdownParent: $("#tambah_master")
    });
    $(".select2_unit").select2({
      placeholder: "Pilih Unit",
      width: '100%',
      dropdownParent: $("#tambah_master")
    });
    $(".select2_cabang").select2({
      placeholder: "Pilih Cabang",
      width: '100%',
      dropdownParent: $("#tambah_master")
    });
    $(".select2_tarif").select2({
      placeholder: "Pilih Tarif",
      width: '100%',
      dropdownParent: $("#tambah_master")
    });
  }

  function hapusBarisIni(param) {
    var x = document.getElementById('detail_tarif').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
  }

  function tambah_barang() {
    var table = document.getElementById('cost_bhp');
    rowCount = table.rows.length;
    arr.push(idrow2);

    var x = document.getElementById('cost_bhp').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    td1.innerHTML = "<tr id='row_cost_bhp"+idrow2+"'><td id='kolom" + idrow2 + "'><td id='kolom" + idrow2 + "'><button type='button' onclick='hapusBarisBarang(" + idrow2 + ");' id=btnhapus" + idrow2 +" class='btn red'><i class='fa fa-trash-o'></td>"
    td2.innerHTML = "<td><select name='kodebarang[]' id='kodebarang"+idrow2+"' class='select2_barang form-control input-largex' onchange='showbarangname(this.value, "+idrow2+")'><option value=''></option><?php foreach($barang as $b) : ?><option value='<?= $b->id; ?>'><?= $b->text; ?></option><?php endforeach; ?></select></td>";
    td3.innerHTML = "<td><input name='qty[]' id='qty"+idrow2+"' onkeyup='totalline_barang("+idrow2+")' value='1' type='text' class='form-control rightJustified'></td>";
    td4.innerHTML = "<td><input name='satuan[]' id='satuan"+idrow2+"' readonly type='text' class='form-control rightJustified'></td>";
    td5.innerHTML = "<td><input name='harga[]' id='harga"+idrow2+"' onkeyup='totalline_barang("+idrow2+")' value='1' type='text' class='form-control rightJustified'></td>";
    td6.innerHTML = "<td><input name='jumlah[]' id='jumlah"+idrow2+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>";
    idrow2++;
    $(".select2_barang").select2({
      placeholder: "Pilih Barang",
      width: '100%',
      dropdownParent: $("#tambah_master")
    });
  }

  function hapusBarisBarang(param) {
    var x = document.getElementById('cost_bhp').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
    total_barang();
  }

  function showbarangname(str, id) {
    var xhttp;
    $('#satuan' + id).val('');
    $('#harga' + id).val(0);
    $.ajax({
      url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#kode' + id).val(data.kodebarang);
        $('#satuan' + id).val(data.satuan1);
        $('#harga' + id).val(separateComma(data.hargabeli));
        totalline_barang(id);
      }
    });
  }

  function totalline_barang(param){
    var qtyx = $("#qty"+param).val();
    var hargax = $("#harga"+param).val();
    var qty = Number(parseInt(qtyx.replaceAll(',','')));
    var harga = Number(parseInt(hargax.replaceAll(',','')));
    var total = qty * harga;
    $("#jumlah"+param).val(separateComma(total));
    total_barang();
  }

  function total_barang(){
    var table = document.getElementById('cost_bhp');
    var rowCount = table.rows.length;
    tjumlah = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      jumlah = row.cells[2].children[0].value;
      harga = row.cells[4].children[0].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
      tjumlah = tjumlah + eval(jumlah1 * harga1);
    }
    document.getElementById("_vtotal").innerHTML = separateComma(tjumlah);
  }
</script>
<!-- end function -->

<!-- aritmatika -->
<script>
  function totallineTarif(param){
    var jasarsx = $("#jasars"+param).val();
    jasars = Number(parseInt(jasarsx.replaceAll(',','')));
    var jasadrx = $("#jasadr"+param).val();
    jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
    var jasaperawatx = $("#jasaperawat"+param).val();
    jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
    var bhpx = $("#bhp"+param).val();
    bhp = Number(parseInt(bhpx.replaceAll(',','')));
    var total = jasars + jasadr + jasaperawat + bhp;
    $("#jasars"+param).val(separateComma(jasars));
    $("#jasadr"+param).val(separateComma(jasadr));
    $("#jasaperawat"+param).val(separateComma(jasaperawat));
    $("#bhp"+param).val(separateComma(bhp));
    $("#total"+param).val(separateComma(total));
  }
</script>
<!-- end aritmatika -->

<!-- cek data validation -->
<script>
  function validation(poli, tindakan, akunpendapatan){
    if(poli == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "UNIT",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(tindakan == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "TINDAKAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(akunpendapatan == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "AKUN PENDAPATAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
  }

  function validation_detail(cabang, keltarif, jasars, jasadr, jasaperawat, bhp, total){
    if(cabang == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "CABANG",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(keltarif == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "KELOMPOK TARIF",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(jasars == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "JASA RS",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(jasadr == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "JASA DOKTER",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(jasaperawat == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "JASA PERAWAT",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(bhp == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "BHP",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(total == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "TOTAL",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
  }

  function validation_barang(kodebarang, qty, satuan, harga, jumlah){
    if(kodebarang == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "KODE BARANG",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(qty == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "JUMLAH",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(satuan == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "SATUAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(harga == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "HARGA",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
    if(jumlah == ''){
      $("#tambah_master").modal('hide');
      swal({
        title: "TOTAL",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#tambah_master").modal('show');
      });
    }
  }
</script>
<!-- end cek data validation -->

<!-- function save -->
<script>
  function save(){
    var poli = $("#poli").val();
    var tindakan = $("#tindakan").val();
    var akunpendapatan = $("#akunpendapatan").val();
    if(document.getElementById("tidakaktif").checked == true){
      var tidakaktif = 1;
    } else {
      var tidakaktif = 0;
    }
    if(poli != '' && tindakan != '' && akunpendapatan != ''){
      $.ajax({
        url: "<?= site_url('Master_tarif/simpan_tarif?tidakaktif='); ?>"+tidakaktif,
        data: $('#form_tambah').serialize(),
        type: "POST",
        dataType: "JSON",
        success: function(data){
          if(data.status == 1){
            // data detail
            var cabang = $('[name="cabang"]').val();
            var keltarif = $('[name="keltarif"]').val();
            var jasars = $('[name="jasars"]').val();
            var jasadr = $('[name="jasadr"]').val();
            var jasaperawat = $('[name="jasaperawat"]').val();
            var bhp = $('[name="bhp"]').val();
            var total = $('[name="total"]').val();
            var kodetarif = data.kodetarif;
            if(cabang != '' && keltarif != '' && jasars != '' && jasadr != '' && jasaperawat != '' && bhp != '' && total != ''){
              var table = document.getElementById('detail_tarif');
              var rowCount = table.rows.length;
              for (i = 1; i < rowCount; i++) {
                var cabang = $("#cabang"+i).val();
                var keltarif = $("#keltarif"+i).val();
                var jasarsx = $("#jasars"+i).val();
                var jasars = Number(parseInt(jasarsx.replaceAll(',','')));
                var jasadrx = $("#jasadr"+i).val();
                var jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
                var jasaperawatx = $("#jasaperawat"+i).val();
                var jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
                var bhpx = $("#bhp"+i).val();
                var bhp = Number(parseInt(bhpx.replaceAll(',','')));
                var totalx = $("#total"+i).val();
                var total = Number(parseInt(totalx.replaceAll(',','')));
                var param = "?cabang="+cabang+"&keltarif="+keltarif+"&jasars="+jasars+"&jasadr="+jasadr+"&jasaperawat="+jasaperawat+"&bhp="+bhp+"&total="+total+"&kodetarif="+kodetarif;
                $.ajax({
                  url: "<?= site_url('Master_tarif/simpan_tarif_detail') ?>"+param,
                  type: "POST",
                  dataType: "JSON",
                });
              }
            } else {
              validation_detail(cabang, keltarif, jasars, jasadr, jasaperawat, bhp, total);
            }
            // data barang
            var kodebarang = $('[name="kodebarang"]').val();
            var qty = $('[name="qty"]').val();
            var satuan = $('[name="satuan"]').val();
            var harga = $('[name="harga"]').val();
            var jumlah = $('[name="jumlah"]').val();
            var kodetarif = data.kodetarif;
            if(kodebarang != '' && qty != '' && satuan != '' && harga != '' && jumlah != ''){
              var table = document.getElementById('cost_bhp');
              var rowCount = table.rows.length;
              for (i = 1; i < rowCount; i++) {
                var kodebarang = $("#kodebarang"+i).val();
                var qtyx = $("#qty"+i).val();
                var qty = Number(parseInt(qtyx.replaceAll(',','')));
                var satuan = $("#satuan"+i).val();
                var hargax = $("#harga"+i).val();
                var harga = Number(parseInt(hargax.replaceAll(',','')));
                var jumlahx = $("#jumlah"+i).val();
                var jumlah = Number(parseInt(jumlahx.replaceAll(',','')));
                var param_barang = "?kodebarang="+kodebarang+"&qty="+qty+"&satuan="+satuan+"&harga="+harga+"&jumlah="+jumlah+"&kodetarif="+kodetarif;
                $.ajax({
                  url: "<?= site_url('Master_tarif/simpan_tarif_barang') ?>"+param_barang,
                  type: "POST",
                  dataType: "JSON",
                });
              }
            } else {
              validation_barang(kodebarang, qty, satuan, harga, jumlah);
            }
            swal({
              title: "TAMBAH TARIF",
              html: "Berhasil dilakukan !",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_tarif";
            });
          } else {
            swal({
              title: "TAMBAH TARIF",
              html: "Gagal dilakukan !",
              type: "error",
              confirmButtonText: "OK"
            });
          }
        }
      })
    } else {
      validation(poli, tindakan, akunpendapatan);
    }
  }
</script>
<!-- end function save -->

<!-- ubah ====================================================================================================================================================== -->
<!-- select2 -->
<script>
  $(".select2_lup_pendapatan").select2({
    placeholder: "Pilih Akun Pendapatan",
    width: '100%',
    dropdownParent: $("#ubah_master")
  });
  $(".select2_lup_unit").select2({
    placeholder: "Pilih Unit",
    width: '100%',
    dropdownParent: $("#ubah_master")
  });
  $(".select2_lupcabang").select2({
    placeholder: "Pilih Cabang",
    width: '100%',
    dropdownParent: $("#ubah_master")
  });
  $(".select2_luptarif").select2({
    placeholder: "Pilih Tarif",
    width: '100%',
    dropdownParent: $("#ubah_master")
  });


  $(".select2_lupbarang").select2({
    placeholder: "Pilih Barang",
    width: '100%',
    dropdownParent: $("#ubah_master")
  });

  
</script>
<!-- end select2 -->

<!-- function ubah -->
<script>
  var lupidrowx = $('#jum_detail').val();
  var lupidrow = 2;
  var lupidrow2 = 2;
  var luprowCount;
  var luparr = [1];
  var table;
  function luptambah() {
    var table = document.getElementById('lupdetail_tarif');
    luprowCount = table.rows.length;
    arr.push(lupidrow);

    var x = document.getElementById('lupdetail_tarif').insertRow(luprowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    td1.innerHTML = "<tr id='luprow_detail_tarif"+lupidrow+"'><td id='kolom" + lupidrow + "'><button type='button' onclick='hapusBarisIni_ubah(" + lupidrow + ");' id=btnhapus" + lupidrow +" class='btn red'><i class='fa fa-trash-o'></td>"
    td2.innerHTML = "<td><select name='lupcabang[]' id='lupcabang"+lupidrow+"' class='select2_lupcabang form-control input-largex'><option value=''></option><?php foreach($cabang as $c) : ?><option value='<?= $c->id; ?>'><?= $c->text; ?></option><?php endforeach; ?></select></td>";
    td3.innerHTML = "<td><select name='lupkeltarif[]' id='lupkeltarif"+lupidrow+"' class='select2_luptarif form-control input-largex'><option value=''></option><?php foreach($tarif as $t) : ?><option value='<?= $t->id; ?>'><?= $t->text; ?></option><?php endforeach; ?></select></td>";
    td4.innerHTML = "<td><input name='lupjasars[]' id='lupjasars"+lupidrow+"' onkeyup='luptotallineTarif("+lupidrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td5.innerHTML = "<td><input name='lupjasadr[]' id='lupjasadr"+lupidrow+"' onkeyup='luptotallineTarif("+lupidrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td6.innerHTML = "<td><input name='lupjasaperawat[]' id='lupjasaperawat"+lupidrow+"' onkeyup='luptotallineTarif("+lupidrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td7.innerHTML = "<td><input name='lupbhp[]' id='lupbhp"+lupidrow+"' onkeyup='luptotallineTarif("+lupidrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>";
    td8.innerHTML = "<td><input name='luptotal[]' id='luptotal"+lupidrow+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>";
    lupidrow++;
    $(".select2_lup_pendapatan").select2({
      placeholder: "Pilih Akun Pendapatan",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });
    $(".select2_lup_unit").select2({
      placeholder: "Pilih Unit",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });
    $(".select2_lupcabang").select2({
      placeholder: "Pilih Cabang",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });
    $(".select2_luptarif").select2({
      placeholder: "Pilih Tarif",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });


    $(".select2_lupbarang").select2({
      placeholder: "Pilih Barang",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });
  }
  
  function hapusBarisIni_ubah(param) {
    if (lupidrow > 2) {
      var x = document.getElementById('lupdetail_tarif').deleteRow(param);
      lupidrow--;
    }
  }

  function luptambah_barang() {
    var table = document.getElementById('lupcost_bhp');
    luprowCount = table.rows.length;
    luparr.push(lupidrow2);

    var x = document.getElementById('lupcost_bhp').insertRow(luprowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    td1.innerHTML = "<tr id='luprow_cost_bhp"+lupidrow2+"'><td id='kolom" + lupidrow2 + "'><td id='kolom" + lupidrow2 + "'><button type='button' onclick='hapusBarisIni_ubahbarang(" + lupidrow2 + ");' id=btnhapus" + lupidrow2 +" class='btn red'><i class='fa fa-trash-o'></td>"
    td2.innerHTML = "<td><select name='lupkodebarang[]' id='lupkodebarang"+lupidrow2+"' class='select2_lupbarang form-control input-largex' onchange='lupshowbarangname(this.value, "+lupidrow2+")'><option value=''></option><?php foreach($barang as $b) : ?><option value='<?= $b->id; ?>'><?= $b->text; ?></option><?php endforeach; ?></select></td>";
    td3.innerHTML = "<td><input name='lupqty[]' id='lupqty"+lupidrow2+"' onkeyup='totalline_lupbarang("+lupidrow2+")' value='1' type='text' class='form-control rightJustified'></td>";
    td4.innerHTML = "<td><input name='lupsatuan[]' id='lupsatuan"+lupidrow2+"' readonly type='text' class='form-control rightJustified'></td>";
    td5.innerHTML = "<td><input name='lupharga[]' id='lupharga"+lupidrow2+"' onkeyup='totalline_lupbarang("+lupidrow2+")' value='1' type='text' class='form-control rightJustified'></td>";
    td6.innerHTML = "<td><input name='lupjumlah[]' id='lupjumlah"+lupidrow2+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>";
    lupidrow2++;
    $(".select2_lupbarang").select2({
      placeholder: "Pilih Barang",
      width: '100%',
      dropdownParent: $("#ubah_master")
    });
  }

  function hapusBarisIni_ubahbarang(param) {
    if (lupidrow2 > 2) {
      var x = document.getElementById('lupcost_bhp').deleteRow(param);
      lupidrow2--;
      total_ubahbarang();
    }
  }
</script>
<!-- end function ubah -->

<!-- master get data -->
<script>
  function get_data(id){
    if(id == ''){
      hapusBarisIni_ubah();
    } else {
      $.ajax({
        url: "<?= site_url('Master_tarif/get_data_master?id=') ?>"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          // show modal
          $('#ubah_master').modal('show');
          // header
          $("#jum_detail").val(data[3]);
          $("#lupkode").val(data[0].kodetarif);
          $("#luppoli").val(data[0].kodepos).change();
          $("#luptindakan").val(data[0].tindakan);
          $("#lupakunpendapatan").val(data[0].accountno).change();
          if(data[0].tidakaktif == 1){
            document.getElementById("luptidakaktif").checked=true;
          } else {
            document.getElementById("luptidakaktif").checked=false;
          }
          // detail
          for (i = 0; i <= data[1].length - 1; i++) {
            hapusBarisIni_ubah(i+1);
          }
          for (i = 0; i <= data[1].length - 1; i++) {
            if (i > 0) {
              luptambah();
            }
            x = i + 1;
            var cabang = $("<option selected></option>").val(data[1][i].koders).text(data[1][i].namacabang);
            var keltarif = $("<option selected></option>").val(data[1][i].cust_id).text(data[1][i].keltarif);
            $('#lupcabang' + x).append(cabang).trigger('change');
            $('#lupkeltarif' + x).append(keltarif).trigger('change');
            document.getElementById("lupjasars" + x).value = separateComma(data[1][i].tarifrspoli);
            document.getElementById("lupjasadr" + x).value = separateComma(data[1][i].tarifdrpoli);
            document.getElementById("lupjasaperawat" + x).value = separateComma(data[1][i].feemedispoli);
            document.getElementById("lupbhp" + x).value = separateComma(data[1][i].obatpoli);
            total_detail();
          }
          // barang
          for (i = 0; i <= data[2].length - 1; i++) {
            hapusBarisIni_ubahbarang(i+1);
          }
          for (i = 0; i <= data[2].length - 1; i++) {
            if (i > 0) {
              luptambah_barang();
            }
            x = i + 1;
  
            var kodebarang = $("<option selected></option>").val(data[2][i].kodeobat).text(data[2][i].namabarang);
            $('#lupkodebarang' + x).append(kodebarang).trigger('change');
            document.getElementById("lupqty" + x).value = separateComma(data[2][i].qty);
            document.getElementById("lupsatuan" + x).value = separateComma(data[2][i].satuan);
            document.getElementById("lupharga" + x).value = separateComma(data[2][i].harga);
            document.getElementById("lupjumlah" + x).value = separateComma(data[2][i].totalharga);
            total_ubahbarang();
          }
        }
      });
    }
  }

  function reload_table() {
    table.ajax.reload(null,false);
  }

  function delete_data(id,tindakan,kdtr) {
    swal({
      title               : "Akan Menghapus Data ini ?",
      html                : "Kode : "+id+"<br>Nama : "+tindakan,
      type                : "question",
      showCancelButton    : true,
      confirmButtonText   : "Ya, hapus",
      confirmButtonClass  : "btn btn-sm btn-success",
      cancelButtonText    : "Batal",
      cancelButtonClass   : "btn btn-sm btn-success"
    }).then(function () {
          // ajax delete data to database
          $.ajax({
              url: "<?php echo site_url('master_tarif/ajax_delete') ?>/" + id+'/'+kdtr,
              type: "POST",
              dataType: "JSON",
              success: function(data) {
                  //if success reload ajax table

                  $('#modal_form').modal('hide');
                  if(data.status == '1') {
                    swal({
                      title   : "DATA",
                      html    : "<p> Berhasil Terhapus</p>",
                      type    : "success",
                      confirmButtonText: "OK" 
                    }).then((value) => {
                      location.href = "<?php echo base_url() ?>Master_tarif";
                    });	
                  } else if(data.status == '2') {
                    swal({
                      title   : "DATA",
                      html    : "<p> Sudah di Pakai, Tidak Boleh di Hapus !!</p>",
                      type    : "error",
                      confirmButtonText: "OK" 
                    }).then((value) => {
                      location.href = "<?php echo base_url() ?>Master_tarif";
                    });
                  } else {
                    swal({
                      title   : "Data Gagal Hapus ...",
                      html    : "Cek kembali",
                      type    : "error",
                      confirmButtonText: "OK" 
                    });
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  alert('Error deleting data');
              }
          });
    });
  }

  function total_detail(id){
    var table = document.getElementById('lupdetail_tarif');
    var rowCount = table.rows.length;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      rs = row.cells[3].children[0].value;
      dr = row.cells[4].children[0].value;
      perawat = row.cells[5].children[0].value;
      bhp = row.cells[6].children[0].value;
      var rs1 = Number(rs.replace(/[^0-9\.]+/g, ""));
      var dr1 = Number(dr.replace(/[^0-9\.]+/g, ""));
      var perawat1 = Number(perawat.replace(/[^0-9\.]+/g, ""));
      var bhp1 = Number(bhp.replace(/[^0-9\.]+/g, ""));
      tjumlah = eval(rs1 + dr1 + perawat1 + bhp1);
      row.cells[7].children[0].value = separateComma(tjumlah);
    }
  }

  function lupshowbarangname(str, id) {
    var xhttp;
    $('#lupsatuan' + id).val('');
    $('#lupharga' + id).val(0);
    $.ajax({
      url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#lupkode' + id).val(data.kodebarang);
        $('#lupsatuan' + id).val(data.satuan1);
        $('#lupharga' + id).val(separateComma(data.hargabeli));
        totalline_lupbarang(id);
      }
    });
  }

  function totalline_lupbarang(param){
    var qtyx = $("#lupqty"+param).val();
    var hargax = $("#lupharga"+param).val();
    var qty = Number(parseInt(qtyx.replaceAll(',','')));
    var harga = Number(parseInt(hargax.replaceAll(',','')));
    var total = qty * harga;
    $("#lupjumlah"+param).val(separateComma(total));
    total_ubahbarang();
  }

  function total_ubahbarang(){
    var table = document.getElementById('lupcost_bhp');
    var rowCount = table.rows.length;
    tjumlah = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      jumlah = row.cells[2].children[0].value;
      harga = row.cells[4].children[0].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
      tjumlah = tjumlah + eval(jumlah1 * harga1);
    }
    document.getElementById("_lupvtotal").innerHTML = separateComma(tjumlah);
  }

  function luptotallineTarif(param){
    var jasarsx = $("#lupjasars"+param).val();
    jasars = Number(parseInt(jasarsx.replaceAll(',','')));
    var jasadrx = $("#lupjasadr"+param).val();
    jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
    var jasaperawatx = $("#lupjasaperawat"+param).val();
    jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
    var bhpx = $("#lupbhp"+param).val();
    bhp = Number(parseInt(bhpx.replaceAll(',','')));
    var total = jasars + jasadr + jasaperawat + bhp;
    $("#lupjasars"+param).val(separateComma(jasars));
    $("#lupjasadr"+param).val(separateComma(jasadr));
    $("#lupjasaperawat"+param).val(separateComma(jasaperawat));
    $("#lupbhp"+param).val(separateComma(bhp));
    $("#luptotal"+param).val(separateComma(total));
  }
</script>
<!-- end master get data -->

<!-- function update -->
<script>
  function update(){
    var kodetarif = $("#lupkode").val();
    var poli = $("#luppoli").val();
    var tindakan = $("#luptindakan").val();
    var akunpendapatan = $("#lupakunpendapatan").val();
    if(document.getElementById("luptidakaktif").checked == true){
      var tidakaktif = 1;
    } else {
      var tidakaktif = 0;
    }
    swal({
      title: 'UBAH DATA',
      html: "Yakin ingin mengubah data ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ubah',
      cancelButtonText: 'Belum'
    }).then(function() {
      if(poli != '' && tindakan != '' && akunpendapatan != ''){
        $.ajax({
          url: "<?= site_url('Master_tarif/ubah_tarif?luptidakaktif='); ?>"+tidakaktif,
          data: $('#form_ubah').serialize(),
          type: "POST",
          dataType: "JSON",
          success: function(data){
            if(data.status == 1){
              // data detail
              var cabang = $('[name="cabang"]').val();
              var keltarif = data.kodetarif;
              var jasars = $('[name="jasars"]').val();
              var jasadr = $('[name="jasadr"]').val();
              var jasaperawat = $('[name="jasaperawat"]').val();
              var bhp = $('[name="bhp"]').val();
              var total = $('[name="total"]').val();
              var kodetarif = data.kodetarif;
              if(cabang != '' && keltarif != '' && jasars != '' && jasadr != '' && jasaperawat != '' && bhp != '' && total != ''){
                var table = document.getElementById('lupdetail_tarif');
                var rowCount = table.rows.length;
                for (i = 1; i < rowCount; i++) {
                  var cabang = $("#lupcabang"+i).val();
                  var keltarif = $("#lupkeltarif"+i).val();
                  var jasarsx = $("#lupjasars"+i).val();
                  var jasars = Number(parseInt(jasarsx.replaceAll(',','')));
                  var jasadrx = $("#lupjasadr"+i).val();
                  var jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
                  var jasaperawatx = $("#lupjasaperawat"+i).val();
                  var jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
                  var bhpx = $("#lupbhp"+i).val();
                  var bhp = Number(parseInt(bhpx.replaceAll(',','')));
                  var totalx = $("#luptotal"+i).val();
                  var total = Number(parseInt(totalx.replaceAll(',','')));
                  var param = "?cabang="+cabang+"&keltarif="+keltarif+"&jasars="+jasars+"&jasadr="+jasadr+"&jasaperawat="+jasaperawat+"&bhp="+bhp+"&total="+total+"&kodetarif="+kodetarif;
                  $.ajax({
                    url: "<?= site_url('Master_tarif/simpan_tarif_detail') ?>"+param,
                    type: "POST",
                    dataType: "JSON",
                  });
                }
              } else {
                validation_detail(cabang, keltarif, jasars, jasadr, jasaperawat, bhp, total);
              }
              // data barang
              var kodebarang = $('[name="kodebarang"]').val();
              var qty = $('[name="qty"]').val();
              var satuan = $('[name="satuan"]').val();
              var harga = $('[name="harga"]').val();
              var jumlah = $('[name="jumlah"]').val();
              var kodetarif = data.kodetarif;
              if(kodebarang != '' && qty != '' && satuan != '' && harga != '' && jumlah != ''){
                var table = document.getElementById('lupcost_bhp');
                var rowCount = table.rows.length;
                for (i = 1; i < rowCount; i++) {
                  var kodebarang = $("#lupkodebarang"+i).val();
                  var qtyx = $("#lupqty"+i).val();
                  var qty = Number(parseInt(qtyx.replaceAll(',','')));
                  var satuan = $("#lupsatuan"+i).val();
                  var hargax = $("#lupharga"+i).val();
                  var harga = Number(parseInt(hargax.replaceAll(',','')));
                  var jumlahx = $("#lupjumlah"+i).val();
                  var jumlah = Number(parseInt(jumlahx.replaceAll(',','')));
                  var param_barang = "?kodebarang="+kodebarang+"&qty="+qty+"&satuan="+satuan+"&harga="+harga+"&jumlah="+jumlah+"&kodetarif="+kodetarif;
                  $.ajax({
                    url: "<?= site_url('Master_tarif/simpan_tarif_barang') ?>"+param_barang,
                    type: "POST",
                    dataType: "JSON",
                  });
                }
              } else {
                validation_barang(kodebarang, qty, satuan, harga, jumlah);
              }
              swal({
                title: "UPDATE TARIF",
                html: "Berhasil dilakukan !",
                type: "success",
                confirmButtonText: "OK"
              }).then((value) => {
                location.href = "<?php echo base_url() ?>Master_tarif";
              });
            } else {
              swal({
                title: "UPDATE TARIF",
                html: "Gagal dilakukan !",
                type: "error",
                confirmButtonText: "OK"
              });
            }
          }
        })
      } else {
        validation(poli, tindakan, akunpendapatan);
      }
    });
  }
</script>
<!-- end function update -->