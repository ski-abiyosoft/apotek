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
      <span class="title-web">Farmasi <small>Penjualan Ke Cabang</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="<?php echo base_url(); ?>dashboard">
          Awal
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="<?php echo base_url(); ?>penjualan_cabang">
          Daftar Resep
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="">
          Entri Resep
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
    <form id="frmpenjualan" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab1" data-toggle="tab">
                Resep
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Cabang <font color="red">*</font></label>
                    <div class="col-md-9">
                      <input type="hidden" id="cabang" name="cabang" value="<?= $header->rekmed; ?>">
                      <select id="cabangd" name="cabangd" class="form-control select2_el_cabang_all" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>
                        <?php
                        if ($header->rekmed) {
                          $namars = data_master('tbl_namers', array('koders' => $header->rekmed))->namars; ?>
                          <option value="<?= $header->rekmed; ?>"><?= $namars; ?></option>
                        <?php }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                    <div class="col-md-6">
                      <input type="hidden" value="<?= $header->gudang; ?>" name="gudang" id="gudang">
                      <select id="gudangd" name="gudangd" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>
                        <?php
                        if ($header->gudang) {
                          $namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;
                        ?>
                          <option value="<?= $header->gudang; ?>"><?= $namagudang; ?></option>
                        <?php }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">No. Resep <font color="red">*</font>
                    </label>
                    <div class="col-md-6">
                      <input type="hidden" name="noresep" id="noresep" value="<?= $header->resepno; ?>" class="form-control">
                      <input type="text" name="noresepx" id="noresepx" value="<?= $header->resepno; ?>" class="form-control" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">PO Cabang <font color="red">*</font></label>
                    <div class="col-md-6">
                      <input type="text" name="po_cabang" id="po_cabang" class="form-control" readonly value="<?= $header->pono; ?>">
                    </div>
                    <div class="col-md-3">
                      <button style="width:100%;" type="button" class="btn btn-secondary" id="btn_po_cabang" disabled onclick="lookup()">LOOKUP</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Jenis <font color="red">*</font></label>
                    <div class="col-md-6">
                      <select name="pembeli" id="pembeli" class="form-control" onchange="getdataklinik()">
                        <option <?= ($posting->kodepel == 'FARMASI' ? 'selected' : '') ?> value="FARMASI">Farmasi</option>
                        <option <?= ($posting->kodepel == 'UMUM' ? 'selected' : '') ?> value="UMUM">Umum</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                    <div class="col-md-6">
                      <input type="hidden" name="tanggal" id="tanggal" value="<?php echo date('Y-m-d', strtotime($header->tglresep)); ?>">
                      <input id="tanggald" name="tanggald" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($header->tglresep)); ?>" readonly />
                    </div>
                    <div class="col-md-3">
                      <input type="time" class="form-control" name="jam" id="jam" value="<?= date('H:i:s', strtotime($header->jam)); ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Pembeli <font color="red">*</font>
                    </label>
                    <div class="col-md-9">
                      <input type="hidden" name="cust_id" id="cust_id" value="<?= $header->cust_id; ?>">
                      <select name="cust_idx" id="cust_idx" class="form-control select2_el_penjamin" disabled>
                        <?php $sql = $this->db->get_where('tbl_penjamin', ['cust_id' => $header->cust_id])->row_array(); ?>
                        <option value="<?= $sql['cust_id']; ?>"><?= $sql['cust_nama']; ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">VAT <font color="red">*</font></label>
                    <div class="col-md-1">
                      <input type="checkbox" name="vat" class="form-control" <?= ($header->pajak == 1 ? 'checked' : '') ?> disabled>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Nama Pembeli <font color="red">*</font>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="namapasien" id="namapasien" class="form-control" value="<?= $posting->namapas; ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Faktur Pajak <font color="red">*</font>
                    </label>
                    <div class="col-md-9">
                      <input type="text" name="fakturpajak" id="fakturpajak" class="form-control" value="<?= $header->fakturpajak; ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                    <thead>
                      <th width="20%" style="text-align: center">Nama Barang</th>
                      <th width="7%" style="text-align: center">Qty</th>
                      <th width="5%" style="text-align: center">Satuan</th>
                      <th width="10%" style="text-align: center">Harga</th>
                      <th width="5%" style="text-align: center">Disc %</th>
                      <th width="10%" style="text-align: center">Disc Rp</th>
                      <th width="5%" style="text-align: center">PPN</th>
                      <th width="10%" style="text-align: center">Total Harga</th>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      foreach ($detil as $row) {
                      ?>
                        <tr id="cabang<?= $no; ?>">
                          <td width="20%">
                            <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_barang_cbg form-control input-large" onchange="showbarangname(this.value, <?= $no; ?>)">
                              <option value="<?= $row->kodebarang; ?>">
                                <?= '[kode : ' . $row->kodebarang . '] - [nama : ' . $row->namabarang . '] - [satuan : ' . $row->satuan . ']'; ?></option>
                            </select>
                          </td>
                          <td width="7%">
                            <?php
                            if ($row->discount == 0) {
                              $cek = 'totalline(' . $no . ')';
                            } else {
                              $cek = 'totalline_x(' . $no . ')';
                            } ?>
                            <input name="qty[]" onchange="<?= $cek; ?>;total()" value="<?= number_format($row->qty, 0); ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified">
                          </td>
                          <td width="5%">
                            <input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control " value="<?= $row->satuan; ?>" onkeypress="return tabE(this,event)" readonly>
                          </td>
                          <td width="10%">
                            <input name="harga[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($row->price); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                          </td>
                          <td width="5%">
                            <input name="disc[]" onchange="totalline_x(<?= $no; ?>)" value="<?= $row->discount; ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified ">
                          </td>
                          <td width="10%">
                            <input name="discrp[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($row->discrp, 0); ?>" id="discrp<?= $no; ?>" type="text" class="form-control rightJustified">
                          </td>
                          <td>
                            <input type="checkbox" name="ppn[]" id="ppn<?= $no; ?>" class="form-control" checked disabled>
                          </td>
                          <td width="7%">
                            <input name="jumlah[]" value="<?= number_format($row->totalrp); ?>" id="jumlah<?= $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                          </td>
                        </tr>
                      <?php $no++;
                      } ?>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-xs-9">
                      <div class="wells">
                        <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                        <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab2">
              <div class="row">
                <div class="col-md-12">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="wells">
                <button id="btnupdate" type="button" onclick="update()" class="btn blue"><i class="fa fa-refresh"></i> Update</button>
                <div class="btn-group">
                  <a href="<?= site_url('Penjualan_cabang/entri'); ?>" type="button" class="btn green"><i class="fa fa-pencil-square-o"></i> Data Baru</a>
                </div>
                <div class="btn-group">
                  <a class="btn red" href="<?php echo base_url('Penjualan_cabang/') ?>">
                    <i class="fa fa-undo"></i><b> KEMBALI </b>
                  </a>
                </div>
                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
              </div>
            </div>
            <div class="col-xs-4 invoice-block">
              <div class="well">
                <table>
                  <tr>
                    <td width="40%"><strong>SUB TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>DISKON</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>DPP</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vdpp"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>PPN</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vppn"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
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

  var idrow = <?= $jumdata + 1; ?>;
  var rowCount;
  var arr = [1];

  function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    td1.innerHTML = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang_cbg form-control' ></select>";
    td2.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline_x(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]' id=sat" + idrow + " type='text' class='form-control' readonly>";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified' readonly>";
    td5.innerHTML = "<input name='disc[]' id=disc" + idrow + " onchange='totalline_x(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
    td6.innerHTML = "<input name='discrp[]' id=discrp" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'>";
    td7.innerHTML = "<input type='checkbox' name='ppn[]' id=ppn" + idrow + " checked class='form-control' disabled>";
    td8.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";
    initailizeSelect2_farmasi_barang_cbg();
    idrow++;
  }

  function hapus() {
    if (idrow > 2) {
      var x = document.getElementById('datatable').deleteRow(idrow - 1);
      idrow--;
      total();
    }
  }

  function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $('#sat' + vid).val('');
    $('#harga' + vid).val(0);
    var gudang = $('[name="gudang"]').val();
    var customer = $('#cust_id').val();
    $.ajax({
      url: "<?= site_url('Penjualan_faktur/gethargapenjamin?cust_id=') ?>" + customer,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        var hargapenjamin = Number(data.harga);
        $.ajax({
          url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang_cbg/?kode=" + str + "&gudang=" + gudang,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            var persen = Number(data.hargabelippn) * 5 / 100;
            var total = Number(data.hargabelippn) + persen + hargapenjamin;
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(separateComma(total));
            totalline(vid);
          }
        });
      }
    });
  }

  function totalline(id) {
    var discrpx = $("#discrp" + id).val();
    var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
    var qtyx = $("#qty" + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var hargax = $("#harga" + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var jumlah = qty * harga - discrp;
    $("#jumlah" + id).val(separateComma(jumlah.toFixed(0)));
    $("#qty" + id).val(separateComma(qty));
    $("#discrp" + id).val(separateComma(discrp.toFixed(0)));
    $("#disc" + id).val(separateComma(0));
    total();
  }

  function totalline_x(id) {
    var qtyx = $("#qty" + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var hargax = $("#harga" + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var discx = $("#disc" + id).val();
    var disc = Number(parseInt(discx.replaceAll(',', '')));
    var discrp = (qty * harga) * disc / 100;
    var jumlah = qty * harga - discrp;
    $("#jumlah" + id).val(separateComma(jumlah.toFixed(0)));
    $("#qty" + id).val(separateComma(qty));
    $("#discrp" + id).val(separateComma(discrp.toFixed(0)));
    total();
  }

  function totalline_y(id) {
    var qtyx = $("#qty" + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var hargax = $("#harga" + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var discrpx = $("#discrp" + id).val();
    var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
    var jumlah = qty * harga - discrp;
    $("#jumlah" + id).val(separateComma(jumlah));
    $("#qty" + id).val(separateComma(qty));
    $("#discrp" + id).val(separateComma(discrp));
    total();
  }
</script>

<!-- load -->
<script>
  $(document).ready(function() {
    total();
  });

  function total() {
    var ppn = <?= $ppn + 5 / 100; ?>;
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;
    tembal = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var qtyx = $("#qty" + i).val();
      var qty = Number(parseInt(qtyx.replaceAll(',', '')));
      var hargax = $("#harga" + i).val();
      var harga = Number(parseInt(hargax.replaceAll(',', '')));
      var discrpx = $("#discrp" + i).val();
      var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
      // var jumlahx = $("#jumlah"+i).val();
      // var jumlah = Number(parseInt(jumlahx.replaceAll(',','')));
      var jml = qty * harga;
      var pajak = jml * ppn;
      tjumlah += jml;
      tdiskon += discrp;
      tppn += pajak;
    }
    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
    document.getElementById("_vdpp").innerHTML = separateComma(tjumlah.toFixed(0));
    // document.getElementById("_vembalase").innerHTML = separateComma(discrp1);
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma((tjumlah - tdiskon + tppn).toFixed(0));
  }
</script>

<!-- update -->
<script>
  function update() {
    $("#btnupdate").attr("disabled", true);
    var resepno = $("#noresep").val();
    var jenis = $("#pembeli").val();
    var pembeli = $("#namapasien").val();
    var total = $("#_vtotal").text();
    var diskon = $('#_vdiskon').text();
    var ppn = $('#_vppn').text();
<<<<<<< HEAD
    var tanggal = $('#tanggald').val();
=======
    var tanggal = $('[name="tanggal"]').val();
>>>>>>> development
    if (jenis == '' || jenis == null) {
      swal({
        title: "JENIS",
        html: "Tidak boleh kosong",
        type: "warning",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        return;
      });
    }
    if (pembeli == '' || pembeli == null) {
      swal({
        title: "Pembeli",
        html: "Tidak boleh kosong",
        type: "warning",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        return;
      });
    }
    if (jenis != '' || jenis != null && pembeli != '' || pembeli != null) {
      swal({
        title: 'UPDATE DATA',
        html: 'Yakin Ingin Update No. Resep : ' + resepno,
        type: 'question',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-success',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, update',
        cancelButtonText: 'Tidak'
      }).then(function() {
        $.ajax({
          url: "<?php echo site_url('Penjualan_cabang/update_data?totalnet=') ?>" + Number(parseInt(total.replaceAll(',', ''))) + "&diskon=" + Number(parseInt(diskon.replaceAll(',', ''))) + "&ppn=" + Number(parseInt(ppn.replaceAll(',', ''))) + "&resepno=" + Number(parseInt(resepno.replaceAll(',', ''))),
          data: $('#frmpenjualan').serialize(),
          type: 'POST',
          dataType: "json",
          success: function(data) {
            swal({
              title: "PENJUALAN KE CABANG",
              html: "<p> No. Bukti   : <b>" + data + "</b> </p>" + "Tanggal :  " + tanggal + "</b> </p>" + "Total:" + " " + total,
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              $("#btnupdate").attr("disabled", false);
              $("#btnupdate").text("Update");
              location.href = "<?php echo base_url() ?>penjualan_cabang";
            });
          },
          error: function(data) {
            $("#btnupdate").attr("disabled", false);
            $("#btnupdate").text("Update");
            swal('PENJUALAN', 'Data gagal diupdate ...', '');
          }
        });
      });
    }
  }
</script>