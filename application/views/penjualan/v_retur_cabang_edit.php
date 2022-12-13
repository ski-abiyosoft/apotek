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
      <span class="title-web">Penjualan <small><small>Retur Cabang</small>
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
        <a class="title-white" href="<?php echo base_url(); ?>Retur_jual_cabang">
          Daftar Retur Penjualan Cabang
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Edit Retur Cabang
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i>*Edit Data
    </div>


  </div>

  <div class="portlet-body form">
    <form id="frmPenjualan" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab1" data-toggle="tab">
                Retur Cabang
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">No. Resep</label>
                    <div class="col-md-6">
                      <input type="text" name="kwiobat" id="kwiobat" value="<?= $header->resepno; ?>" readonly class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Dari Cabang</label>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="hidden" id="rekmed" name="rekmed" value="<?= $header->rekmed; ?>" class="form-control input-large" readonly></input>
                        <input type="text" id="rekmeda" name="rekmeda" value="<?= $namars->namars; ?>" class="form-control input-large" readonly></input>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">No. Retur</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?= $header->returno; ?>" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Gudang</label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="hidden" class="form-control input-medium" name="gudang" id="gudang" value="<?= $header->gudang; ?>" readonly>
                      <input type="text" class="form-control input-medium" value="<?= $gudang->keterangan; ?>" name="gudanga" id="gudanga" value="" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal</label>
                  <div class="col-md-4">
                    <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?= date('Y-m-d'); ?>" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Pembeli</label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control input-medium" name="cust_idx" id="cust_idx" readonly value="<?= $cust->cust_nama; ?>">
                      <input type="hidden" class="form-control input-medium" name="cust" id="cust" readonly value="<?= $cust->cust_id; ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Jenis</label>
                  <div class="col-md-4">
                    <input id="jenis" name="jenis" class="form-control input-medium" type="text" readonly value="<?= $hresep->kodepel; ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Alasan</label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control input-medium" name="alasan" id="alasan" value="<?= $header->alasan; ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Nama Pembeli</label>
                  <div class="col-md-4">
                    <input id="nama_pembeli" name="nama_pembeli" class="form-control input-medium" type="text" readonly value="<?= $hresep->pro; ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table id="datatable" class="table table-hover table-striped table-bordered table-condensed table-scrollable">
                  <thead class="breadcrumb">
                    <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                    <th class="title-white" width="15%" style="text-align: center">Kode Barang</th>
                    <th class="title-white" width="25%" style="text-align: center">Nama Barang</th>
                    <th class="title-white" width="5%" style="text-align: center">Kuantitas</th>
                    <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                    <th class="title-white" width="10%" style="text-align: center">Harga</th>
                    <th class="title-white" width="5%" style="text-align: center">PPN</th>
                    <th class="title-white" width="5%" style="text-align: center">Diskon (%)</th>
                    <th class="title-white" width="10%" style="text-align: center">Diskon (Rp)</th>
                    <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                  </thead>
                  <tbody>
                    <?php $no = 1;
                    foreach ($detail as $key => $value) : ?>
                      <tr>
                        <td width="5%" style='text-align: center;'>
                          <!-- <?php if ($no == 1) : ?>
                            <button style='text-align: center;' type="button" class="btn btn-danger btn-sm btn-circle" disabled><i class="fa fa-trash"></i></button>
                          <?php else : ?>
                            <button style='text-align: center;' type="button" class="btn btn-danger btn-sm btn-circle" id="hapus<?= $no; ?>" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
                          <?php endif; ?> -->
                          <input type="checkbox" name="cek[]" id="cek<?= $no; ?>" class="form-control" onclick="total_retur();">
                        </td>
                        <td width="15%">
                          <input type="text" name="kodex[]" id="kodex<?= $no; ?>" class="form-control" value="<?= $value->kodebarang; ?>" readonly>
                        </td>
                        <td width="25%">
                          <input name="namabarang[]" value="<?= $value->namabarang ?>" id="namabarang<?= $no ?>" type="text" class="form-control" readonly>
                        </td>
                        <td width="5%">
                          <input name="qty[]" onchange="totalline(<?= $no ?>);total();" value="<?= number_format($value->qtyretur); ?>" id="qty<?= $no ?>" type="text" class="form-control rightJustified">
                        </td>
                        <td width="10%">
                          <input name="sat[]" id="sat<?= $no ?>" type="text" class="form-control" value="<?= $value->satuan1 ?>" readonly>
                        </td>
                        <td width="10%">
                          <input name="harga[]" onchange="totalline(<?= $no ?>)" value="<?= number_format($value->price) ?>" id="harga<?= $no ?>" type="text" class="form-control rightJustified" readonly>
                        </td>
                        <td width="5%">
                          <input class="form-control" type="checkbox" checked disabled id="ppn<?= $no ?>" name="ppn[]">
                        </td>
                        <td width="5%">
                          <input class="form-control" type="text" id="disc<?= $no ?>" name="disc[]" value="<?= $value->discount; ?>" onchange="totalline_x(<?= $no; ?>);">
                        </td>
                        <td width="10%">
                          <input name="discrp[]" onchange="totalline(<?= $no ?>);total()" value="<?= number_format($value->discountrp) ?>" id="discrp<?= $no ?>" type="text" class="form-control rightJustified ">
                        </td>
                        <td width="15%">
                          <input name="jumlah[]" id="jumlah<?= $no ?>" type="text" value="<?= number_format($value->totalrp) ?>" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                        </td>
                      </tr>
                    <?php $no++;
                    endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-9">
                <div class="wells">
                  <!-- <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button> -->
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
              <button id="btnsimpan" type="button" onclick="savex()" class="btn blue">
                <i class="fa fa-save"></i> <b>Proses</b>
              </button>
              <div class="btn-group">
                <a class="btn red" href="<?php echo base_url('Retur_jual_cabang/') ?>"><i class="fa fa-undo"></i><b> KEMBALI</b></a>
              </div>
              <div class="btn-group"></div>
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
    </form>
  </div>
</div>

<?php
$this->load->view('template/footer');
?>
<script>
  var pajak = <?= $pajak + 5 / 100 ?>;

  function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var qty = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    var harga = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var disc = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
    var discrp = Number(row.cells[8].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = qty * harga;
    if (disc != 0) {
      diskon = jumlah * (disc / 100);
    } else {
      diskon = discrp;
    }
    if (discrp == 0) {
      row.cells[7].children[0].value = 0;
      row.cells[8].children[0].value = 0;
    } else {
      row.cells[8].children[0].value = separateComma(diskon);
    }
    tot = jumlah - diskon;
    row.cells[9].children[0].value = separateComma(tot);
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
    $("#jumlah" + id).val(separateComma(jumlah));
    $("#qty" + id).val(separateComma(qty));
    $("#discrp" + id).val(separateComma(discrp));
    total();
  }

  function total() {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    tdiskon = 0;
    ttot = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      jumlah1 = row.cells[3].children[0].value;
      hargax = row.cells[5].children[0].value;
      harga1 = Number(hargax.replace(/[^0-9\.]+/g, ""));
      diskon = Number(row.cells[8].children[0].value.replace(/[^0-9\.]+/g, ""));
      tot = Number(row.cells[9].children[0].value.replace(/[^0-9\.]+/g, ""));
      row.cells[9].children[0].value = separateComma(tot);
      tdiskon += diskon;
      ttot += tot;
    }
    // alert(tdiskon)
    tppn = 0;
    var tot = ttot + tdiskon + tppn;
    document.getElementById("_vsubtotal").innerHTML = tot;
    document.getElementById("_vdiskon").innerHTML = separateComma(0);
    document.getElementById("_vppn").innerHTML = separateComma(tppn);
    document.getElementById("_vtotal").innerHTML = separateComma(ttot - tdiskon);
    if (tot > 0) {
      document.getElementById("btnsimpan").disabled = false;
    } else {
      document.getElementById("btnsimpan").disabled = true;
    }
    total_retur(tppn);
  }

  function total_retur(tppn) {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    subtotal = 0;
    tdiskon = 0;
    ppn = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      qty = row.cells[3].children[0].value;
      hargax = row.cells[5].children[0].value;
      jumlah = row.cells[9].children[0].value;
      diskon = row.cells[8].children[0].value;
      var harga = Number(hargax.replace(/[^0-9\.]+/g, ""));
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
      if (document.getElementById("cek" + i).checked == true) {
        tjumlah = tjumlah + eval(jumlah1);
        tdiskon = tdiskon + eval(diskon1);
        subtotal = subtotal + eval(qty * harga);
        ppn += eval((jumlah1) * pajak);
      }
    }
    var tot = tjumlah + tdiskon + tppn;
    tjumlah2 = (tjumlah - tdiskon) / 1.11;
    tppn2 = (tjumlah2) * pajak;
    var ttl = subtotal - tdiskon;
    var new_tjumlah = separateComma(Number(subtotal).toFixed(0));
    var new_tdiskon = separateComma(Number(tdiskon).toFixed(0));
    var new_tppn2 = separateComma(Number(ppn).toFixed(0));
    var new_ttl = separateComma(Number(ttl).toFixed(0));
    document.getElementById("_vsubtotal").innerHTML = new_tjumlah;
    document.getElementById("_vdiskon").innerHTML = new_tdiskon;
    document.getElementById("_vppn").innerHTML = new_tppn2;
    document.getElementById("_vtotal").innerHTML = new_ttl;
    if (tjumlah > 0) {
      document.getElementById("btnsimpan").disabled = false;
    } else {
      document.getElementById("btnsimpan").disabled = true;
    }
  }

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

  function savex() {
    var resepno = $('[name="kwiobat"]').val();
    var gudang = $('[name="gudang"]').val();
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    var loop = rowCount - 1;
    var noform = $('[name="cust"]').val();
    var rekmed = $('[name="rekmed"]').val();
    var tanggal = $('[name="tanggal"]').val();
    var gudang = $('[name="gudang"]').val();
    var alasan = $('[name="alasan"]').val();
    var total = $('#_vtotal').text();
    var totalx = Number(parseInt(total.replaceAll(',', '')));
    var ppn = $('#_vppn').text();
    // var cektgll = cektgl;
    // var date_now = date_n;
    // var cekuserr = cekuser;
    if (totalx == "" || total == 0 || alasan == '') {
      swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    } else {
      // console.log(resepno)
      $.ajax({
        url: "<?= site_url('Retur_jual_cabang/update_one/?vtotal=') ?>" + totalx,
        data: $('#frmPenjualan').serialize(),
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
          if (data.status == 1) {
            var no = data.nomor;
            for (var i = 1; i < rowCount; i++) {
              var row = table.rows[i];
              if (document.getElementById("cek"+i).checked == true) {
                var kodebarang = row.cells[1].children[0].value;
                var qtyx = row.cells[3].children[0].value;
                var satuanx = row.cells[4].children[0].value;
                var hargax = row.cells[5].children[0].value;
                var dsc1x = row.cells[7].children[0].value;
                var dscx = row.cells[8].children[0].value;
                var jumlahx = row.cells[9].children[0].value;
                var qty = parseInt(qtyx.replaceAll(',', ''));
                var harga = parseInt(hargax.replaceAll(',', ''));
                var dsc1 = parseInt(dsc1x.replaceAll(',', ''));
                var dsc = parseInt(dscx.replaceAll(',', ''));
                var jumlah = parseInt(jumlahx.replaceAll(',', ''));
                $.ajax({
                  url: '<?= site_url(); ?>Retur_jual_cabang/save_multi/?kodebarang=' + kodebarang + '&qty=' + qty + '&harga=' + harga + '&resepno=' + resepno + '&loop=' + loop + '&dsc=' + dsc + '&dsc1=' + dsc1 + '&jumlah=' + jumlah + '&satuan=' + satuanx + '&ppn=' + Number(parseInt(ppn.replaceAll(',', ''))) + '&nobukti=' + no,
                  data: $('#frmPenjualan').serialize(),
                  type: 'POST',
                  dataType: 'JSON',
                  success: function(data) {
                    if (data.status == 1) {
                      swal({
                        title: "RETUR PENJUALAN",
                        html: "<p> No. Retur   : <b>" + data.nomor + "</b> </p>" + "Tanggal :  " + tanggal + "</b> </p>" + "Total Biaya" + " " + total,
                        type: "info",
                        confirmButtonText: "OK"
                      }).then((value) => {
                        location.href = "<?php echo base_url() ?>Retur_jual_cabang";
                      });
                    }
                  }
                });
              }
            }
            swal({
              title: "RETUR PENJUALAN",
              html: "<p> No. Retur   : <b>" + data.nomor + "</b> </p>" + "Tanggal :  " + tanggal + "</b> </p>" + "Total Biaya" + " " + total,
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Retur_jual_cabang";
            });
          } else {
            swal({
              title: "RETUR PENJUALAN",
              html: "Gagal dilakukan",
              type: "error",
              confirmButtonText: "OK"
            });
          }
        }
      });
    }
  }
</script>