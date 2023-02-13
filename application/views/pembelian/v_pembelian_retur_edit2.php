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
      &nbsp;-
      <span class="title-web">APOTEK <small>Retur Pembelian</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url(); ?>dashboard">
          Awal
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?php echo base_url(); ?>pembelian_retur">
          Daftar Retur Pembelian
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Entri Retur
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i>*Data Baru
    </div>
  </div>
  <div class="portlet-body form">
    <form id="frmpembelian" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab1" data-toggle="tab">
                  Retur
                </a>
            </li>
            <li class="">
                <a href="#tab2" data-toggle="tab">
                  Info
                </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Pemasok</label>
                    <div class="col-md-6">
                      <select id="supp" name="supp" class="form-control select2_el_vendor" data-placeholder="Pilih..." onchange="showpo()">
                        <?php
                        if ($header->vendor_id) {
                          $data = data_master("tbl_vendor", array('vendor_id' => $header->vendor_id))->vendor_name;
                        }
                        ?>
                        <option value="<?= $header->vendor_id; ?>"><?= $data; ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">No. Retur #</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?= $header->retur_no; ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">No. BAPB</label>
                    <div class="col-md-5">
                      <div class="input-group">
                        <input type="text" name="kodepu" id="kodepu" class="form-control" style="width: 100%;" value="<?= $header->terima_no; ?>" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Gudang</label>
                    <div class="col-md-6">
                      <?php $sql = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$header->gudang'")->row(); ?>
                      <input class="form-control" type="text" id="gudang1" name="gudang1" value="<?= $sql->keterangan; ?>" readonly>
                      <input class="form-control" type="hidden" id="gudang" name="gudang" value="<?= $header->gudang; ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Tanggal</label>
                    <div class="col-md-4">
                      <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?= date('Y-m-d', strtotime($header->retur_date)); ?>" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label class="col-md-3 control-label">Alasan <span class="text-danger">*</span></label>
                      <div class="col-md-6">
                          <input class="form-control" type="text" id="alasan" name="alasan" value="<?= $header->alasan; ?>">
                      </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                    <thead class="page-breadcrumb breadcrumb">
                      <tr>
                        <th style="color:white; text-align:center" width="5%">Hapus</th>
                        <th style="color:white; text-align:center" width="20%">Nama Barang</th>
                        <th style="color:white; text-align:center" width="10%">Kuantitas</th>
                        <th style="color:white; text-align:center" width="10%">Satuan</th>
                        <th style="color:white; text-align:center" width="10%">Harga</th>
                        <th style="color:white; text-align:center" width="10%">Tax</th>
                        <th style="color:white; text-align:center" width="10%">Diskon</th>
                        <th style="color:white; text-align:center" width="10%">Disc Rp</th>
                        <th style="color:white; text-align:center" width="15%">Total</th>
                      </tr>
                    <thead>
                    <tbody>
                      <?php $no = 1; foreach($detil as $d) : ?>
                      <tr id="retur_tr<?= $no; ?>">
                        <td width="5%">
                          <button id="btnhapus<?= $no; ?>" type='button' onclick='hapusBarisIni(<?= $no; ?>)' class='btn red'><i class='fa fa-trash-o'></i></button>
                        </td>
                        <td width="20%">
                          <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_barang form-control" onchange="showbarangname(this.value, <?= $no; ?>)" readonly>
                            <option value="<?= $d->kodebarang; ?>"><?= $d->namabarang; ?></option>
                          </select>
                        </td>
                        <td width="10%">
                          <input name="qty[]" onchange="totalline(<?= $no; ?>);total();" value="<?= number_format($d->qty_retur); ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified">
                        </td>
                        <td width="10%">
                          <input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control" value="<?= $d->satuan; ?>" readonly>
                        </td>
                        <td width="10%">
                          <input name="harga[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($d->price); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                        </td>
                        <td width="10%">
                          <?php if($d->tax == 1) { $cektax = 'selected'; } else { $cektax = ''; } ?>
                          <select name='tax[]' id='tax<?= $no; ?>' class='form-control' onchange='totalline(<?= $no; ?>); total()'>
                            <option value='0' <?= $cektax; ?>>Tidak</option>
                            <option value='1' <?= $cektax; ?>>Ya</option>
                          </select>
                        </td>
                        <td width="10%">
                          <input name="disc[]" onchange="totalline(<?= $no; ?>);total();cekdisc(<?= $no; ?>)" value="<?= number_format($d->discount); ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified">
                        </td>
                        <td width="10%">
                          <input name="discrp[]" onchange="totalline(<?= $no; ?>);total();cekdiscrp(<?= $no; ?>)" value="<?= number_format($d->discountrp); ?>" id="discrp<?= $no; ?>" type="text" class="form-control rightJustified ">
                        </td>
                        <td width="15%">
                          <input name="jumlah[]" id="jumlah<?= $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                        </td>
                      </tr>
                      <?php $no++; endforeach; ?>
                    </tbody>
                  </table>
                  <input type="hidden" id="pajak" name="pajak" value="<?= $pajak; ?>">
                  <div class="row">
                    <div class="col-xs-9">
                      <div class="wells">
                        <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab2">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" id="form_ppn">
                        <label class="col-md-3 control-label">PPN</label>
                        <div class="col-md-4">
                          <select name="sppn" id="sppn" class="form-control select2me input-small" onchange="total()">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="col-md-3 control-label">Keterangan</label>
                        <div class="col-md-4">
                          <textarea row="3" class="form-control input-xlarge" placeholder="" name="keterangan" id="keterangan" maxlength="100"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="wells">
                <button type="button" onclick="save()" class="btn blue" id="btnsimpan"><i class="fa fa-save"></i> <b>Simpan</b></button>
                <div class="btn-group">
                  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></button>
                </div>
                <div class="btn-group">
                  <a class="btn red" href="<?= site_url('pembelian_retur') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                </div>
                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
              </div>
            </div>
            <div class="col-xs-4 invoice-block">
              <div class="well">
                <table border="0">
                  <tr>
                    <td width="40%"><strong>SUB TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right" id="cobaaja"><strong><span id="_vsubtotal"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>DISKON</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="10%"><strong>PPN</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="20" align="right"><strong><span id="_vppn"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                    <input type="hidden" class="form-control input-medium" name="_vtotalx" id="_vtotalx">
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
$this->load->view('template/footer');
?>


<!-- master -->
<script>
  var pajak1 = $("#pajak").val();
  var cekppn2 = Number(pajak1.replace(/[^0-9\.]+/g, ""));

  $( document ).ready(function() {
      totalline(<?= $jumdata1; ?>);
  });

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

  function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
      url: "<?php echo base_url(); ?>pembelian_retur/getinfobarang/" + str,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        var qty = $('#qty' + id).val();
        $('#sat' + id).val(data.satuan1);
        $('#harga' + id).val(separateComma(data.hargabeli));
        var harga = data.hargabeli;
        var jumlah = Number(parseInt(qty.replaceAll(',', ''))) * data.hargabeli;
        $('#jumlah' + id).val(separateComma(jumlah));
        totalline(id);
      }
    });
  }
</script>

<!-- aritmatika -->
<script>
  function cekdisc(id) {
    var qtyx = $("#qty" + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var hargax = $("#harga" + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var discx = $("#disc" + id).val();
    var disc = Number(parseInt(discx.replaceAll(',', '')));
    if (disc < 1) {
      $("#discrp" + id).val(separateComma(0));
    } else {
      var discrp = qty * harga * disc / 100;
      $("#discrp" + id).val(separateComma(discrp.toFixed(0)));
    }
    totalline(id);
  }

  function cekdiscrp(id) {
    var qtyx = $("#qty" + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var hargax = $("#harga" + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var discrpx = $("#discrp" + id).val();
    var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
    $("#disc" + id).val(0);
    $("#discrp" + id).val(separateComma(discrp));
    tot = qty * harga - discrp;
    $('#jumlah' + id).val(separateComma(tot));
    totalline(id);
  }

  function totalline(id){
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
      var discrp = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
      jumlah = qty * harga;
      tot = jumlah - discrp;
      row.cells[8].children[0].value = separateComma(tot);
      total();
    }
  }
  
  function total() {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;
    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];
        jumlah = row.cells[2].children[0].value;
        harga = row.cells[4].children[0].value;
        diskon = row.cells[6].children[0].value;
        diskonrp = row.cells[7].children[0].value;
        subtotal = row.cells[8].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
        var diskon2 = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
        var subtotal1 = Number(subtotal.replace(/[^0-9\.]+/g, ""));
        tjumlah = tjumlah + eval(jumlah1 * harga1);
        diskon = eval((diskon1 / 100) * jumlah1 * harga1);
        tdiskon = tdiskon + diskon2;
        if (row.cells[5].children[0].value == 1) {
          tppn = tppn + (eval((jumlah1 * harga1 - diskon2))) * cekppn2;
        }
    }
    // var abc = Number(tjumlah - tdiskon + tppn);
    if('<?= $pkp ?>' == '1') {
      var abc = tjumlah - tdiskon;
    } else {
      var abc = tjumlah - tdiskon + tppn;
    }
    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma(abc.toFixed(0));
    $('[name="_vtotalx"]').val(abc);
  }
</script>

<!-- row tabel -->
<script>
  var idrow = <?= $jumdata1 + 1; ?>;
  var rowCount;
  var arr = [1];

  function tambah() {
    var table = $("#datatable");
    if('<?= $pkp ?>' == '1') {
      table.append("<tr id='retur_tr" + idrow + "'>" +
          "<td><button id='btnhapus" + idrow + "' type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
          "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select></td>" +
          "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();' value='1'  type='text' class='form-control rightJustified'></td>" +
          "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly></td>" +
          "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly></td>" +
          "<td><select name='tax[]' id='tax" + idrow + "' class='form-control' onchange='totalline(" + idrow + "); total()'><option value='1'>Ya</option><option value='0'>Tidak</option></select></td>" +
          "<td><input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
          "<td><input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
          "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' readonly></td>" +
          "</tr>");
    } else {
      table.append("<tr id='retur_tr" + idrow + "'>" +
          "<td><button id='btnhapus" + idrow + "' type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
          "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select></td>" +
          "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();' value='1'  type='text' class='form-control rightJustified'></td>" +
          "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly></td>" +
          "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly></td>" +
          "<td><select name='tax[]' id='tax" + idrow + "' class='form-control' onchange='totalline(" + idrow + "); total()'><option value='0'>Tidak</option><option value='1'>Ya</option></select></td>" +
          "<td><input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
          "<td><input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
          "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' readonly></td>" +
          "</tr>");
    }
    initailizeSelect2_farmasi_barang();
    idrow++;
  }

  function hapusBarisIni(param) {
    $("#retur_tr" + param).remove();
    totalline(param);
  }
</script>

<!-- simpan -->
<script>
  function save() {
    $("#btnsimpan").attr("disabled", true);
    var noretur = $('#nomorbukti').val();
    var tanggal = $('#tanggal').val();
    var total = $('#_vtotal').text();
    var alasan = $('#alasan').text();
    var pajak = cekppn2;
    $.ajax({
      url: '<?= site_url("Pembelian_retur/update_one/?retur_no=") ?>' + noretur,
      data: $('#frmpembelian').serialize(),
      type: 'POST',
      dataType: 'JSON',
      success: function(data) {
        if(data.status == 1){
          var nomor = data.nomor;
          var table = document.getElementById('datatable');
          var rowCount = table.rows.length;
          var totvatrp = 0;
          var diskontotal = 0;
          for (i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            kode = row.cells[1].children[0].value;
            qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
            satuan = row.cells[3].children[0].value;
            harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
            tax = row.cells[5].children[0].value;
            if(tax == 1){
              taxrp = qty * harga * cekppn2;
            } else {
              taxrp = 0;
            }
            disc = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
            discrp = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
            jumlah = Number(row.cells[8].children[0].value.replace(/[^0-9\.]+/g, ""));
            var param = "?kode=" + kode + '&qty=' + qty + '&sat=' + satuan + '&harga=' + harga + '&disc=' + disc + '&discrp=' + discrp + '&tax=' + tax + '&jumlah=' + jumlah + '&taxrp=' + taxrp + '&retur_no=' + nomor + "&pajak=" + pajak;
            $.ajax({
              url: '<?= site_url() ?>Pembelian_retur/update_multi/'+param,
              data: $('#frmpembelian').serialize(),
              type: 'POST',
              dataType: 'JSON',
              success: function(data) {
                console.log(data);
              }
            });
          }
          $("#btnsimpan").attr("disabled", false);
          swal({
            title: "PEMBELIAN RETUR",
            html: "<p> No. Retur : <b>" + noretur + "</b></p>" + "Tanggal : " + tanggal + "<br> Total : " + total,
            type: "success",
            confirmButtonText: "OK"
          }).then((value) => {
            location.href = "<?= site_url('Pembelian_retur') ?>";
          });
        } else {
          $("#btnsimpan").attr("disabled", false);
          swal({
            title: "UBAH PEMBELIAN RETUR",
            html: "Gagal dilakukan",
            type: "error",
            confirmButtonText: "OK"
          });
        }
      }
    });
  }
</script>