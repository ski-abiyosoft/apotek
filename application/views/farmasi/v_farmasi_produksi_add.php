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
            <span class="title-web">Farmasi <small>Produksi</small>
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
                <a class="title-white" href="<?php echo base_url();?>farmasi_produksi">
                    Daftar Produksi
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Data
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>Data Baru
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                <i class="fa fa-file"></i>
                                Produksi Barang
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">                                   
								   <i class="fa fa-info-circle"></i>
								   Info
								</a>
							</li-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Lokasi Produksi</label>
                                        <div class="col-md-9">
                                            <select id="gudang_asal" name="gudang_asal"
                                                class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..."
                                                onchange="setkodebarang()" onkeypress="return tabE(this,event)">
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kode Barang</label>
                                        <div class="col-md-9">
                                            <select id="kodebarang" name="kodebarang"
                                                class="form-control select2_el_farmasi_baranggud"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                            </select>

                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Produksi</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" placeholder="Otomatis" name="nomorbukti"
                                                    class="form-control" value="<?= $nomor;?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Qty Jadi</label>
                                        <div class="col-md-2">
                                            <input id="qtyjadi" name="qtyjadi"
                                                class="form-control input-small rightJustified" type="text" value="0" />
                                        </div>
                                        <label class="col-md-3 control-label">HNA</label>
                                        <div class="col-md-2">
                                            <input id="hna" name="hna" class="form-control input-small rightJustified"
                                                type="text" value="0" />

                                        </div>
                                    </div>
                                </div>




                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tgl. Produksi</label>
                                        <div class="col-md-4">
                                            <input id="tanggal" name="tanggal" class="form-control input-medium "
                                                type="date" value="<?php echo date('Y-m-d');?>" />

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Harga Jual Jadi</label>
                                        <div class="col-md-2">
                                            <input id="hargajualjadi" name="hargajualjadi"
                                                class="form-control input-small rightJustified" onkeyup="total()"
                                                type="text" value="0" />

                                        </div>
                                        <label class="col-md-3 control-label">HPP</label>
                                        <div class="col-md-2">
                                            <input id="hpp" name="hpp" class="form-control input-small rightJustified"
                                                type="text" value="0" />

                                        </div>
                                    </div>
                                </div>

                                <!--<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">PPN</label>
													        <div class="col-md-2">
														        <input id="ppn" name="ppn" class="form-control input-small rightJustified" type="text" value="0" />
													    	   
													        </div>
															<label class="col-md-3 control-label">Margin</label>
													        <div class="col-md-2">
														        <input id="margin" name="margin" class="form-control input-small rightJustified" type="text" value="0" onchange="total()"/>
													    	   
													        </div>
														</div>
													</div>-->






                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                    </div>
                                </div>

                                <!-- HRG JUAL QTY -->






                            </div>




                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead>
                                            <tr>
                                                <th width="30%" style="text-align: center">Kode/Nama Bahan</th>
                                                <th width="10%" style="text-align: center">Kuantitas</th>
                                                <th width="10%" style="text-align: center">Satuan</th>
                                                <th width="10%" style="text-align: center">Harga</th>
                                                <th width="15%" style="text-align: center">Total</th>
                                                <th width="10%" style="text-align: center">Expire</th>
                                                <th width="20%" style="text-align: center">Keterangan</th>

                                            </tr>
                                            <thead>

                                            <tbody>
                                                <tr>
                                                    <td width="30%">
                                                        <select name="kode[]" id="kode1"
                                                            class="select2_el_farmasi_baranggud form-control"
                                                            onchange="showbarangname(this.value, 1)">

                                                        </select>
                                                    </td>

                                                    <td width="10%"><input name="qty[]" onchange="totalline(1)"
                                                            value="1" id="qty1" type="text"
                                                            class="form-control rightJustified"></td>
                                                    <td width="10%"><input name="sat[]" id="sat1" type="text"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="10%"><input name="harga[]" onchange="totalline(1)"
                                                            id="harga1" type="text" class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="15%"><input name="total[]" onchange="totalline(1)"
                                                            id="total1" type="text" class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="10%"><input name="expire[]" id="expire1" type="date"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="10%"><input name="note[]" id="note1" type="text"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                </tr>

                                            </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align:right">TOTAL</td>
                                                <td><input type="text" class="form-control rightJustified" id="vtotal"
                                                        readonly></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i
                                                        class="fa fa-plus"></i> </button>
                                                <button type="button" onclick="hapus()" class="btn red"><i
                                                        class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>


                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">
                            <div class="row">

                            </div>

                        </div>
                        <!-- tab2-->

                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="well">


                                <button type="button" id="save" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>
                                <!-- <button type="button" onclick="save()" class="btn blue" id="save"><i
                                        class="fa fa-save"></i> Simpan</button> -->
                                <div class="btn-group">
                                    <button type="button" class="btn green"
                                        onclick="this.form.reset();location.reload();"><i
                                            class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('Farmasi_produksi/')?>"><i
                                            class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                        id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
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
</div>
<?php
  $this->load->view('template/footer'); 
?>
<script>
$(window).on("load", function() {
    // var gud = "FARMASI";
    // initailizeSelect2_farmasi_baranggud(gud);
    initailizeSelect2_farmasi_baranggud(null);
})

function setkodebarang() {
    var gudang = $("#gudang_asal").val();
    initailizeSelect2_farmasi_baranggud(gudang);
};

var idrow = 2;

function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var akun = "<select name='kode[]' id='kode" + idrow + "' onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_baranggud form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " type='text' class='form-control rightJustified' >";
    td5.innerHTML = "<input name='total[]'    id=total" + idrow + " type='text' class='form-control rightJustified' >";
    td6.innerHTML = "<input name='expire[]'    id=expire" + idrow + " type='date' class='form-control' >";
    td7.innerHTML = "<input name='note[]'    id=note" + idrow + " type='text' class='form-control' >";
    var gud = $('[name="gudang_asal"]').val();
    initailizeSelect2_farmasi_baranggud(gud);
    total();
    idrow++;
}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>farmasi_produksi/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hpp));
            totalline(vid);
            checkstock($('[name="gudang_asal"]').val(), data.kodebarang);
        }
    });
}

// function save() {
$("#save").on("click", function() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    var barang = $('[name="kodebarang"]').val();
    if (gudang_asal == "" || total == "" || total == "0.00" || gudang_asal == null || barang == null) {
        swal('PRODUKSI BARANG', 'data belum lengkap ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('farmasi_produksi/save/1')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "PRODUKSI BARANG",
                    html: "<p> No. Produksi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal + "</b> </p>" + " Total :" + " " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>farmasi_produksi";
                });
            }, error: function(data) {
                swal('PRODUKSI BARANG', 'Data gagal disimpan ...', '');
            }
        });
    }
});

function hapus() {
    if (idrow > 2) {
        var x = document.getElementById('datatable').deleteRow(idrow - 1);
        idrow--;
    }
}

function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;
    row.cells[4].children[0].value = formatCurrency1(jumlah);
    total();
}


function separateComma(val) {
    // remove sign if negative
    var sign = 1;
    if (val < 0) {
        sign = -1;
        val = -val;
    }
    // trim the number decimal point if it exists
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
    // add number after decimal point
    if (val.toString().includes('.')) {
        result = result + '.' + val.toString().split('.')[1];
    }
    // return result with - sign if negative
    return sign < 0 ? '-' + result : result;
}


function total() {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    var hargajualj = $("#hargajualjadi").val();
    var ppn = <?= str_replace(".00", "", $ppn->prosentase) ?> / 100;
    tjumlah = 0;
    thna = 0;
    thpp = 0;
    for (var i = 1; i < rowCount - 1; i++) {
        var row = table.rows[i];
        ztotal = row.cells[4].children[0].value;
        zharga = row.cells[3].children[0].value;
        // var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g, ""));
        // var harga1 = Number(zharga.replace(/[^0-9\.]+/g, ""));
        var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(zharga.replace(/[^0-9\.]+/g, ""));
        tjumlah = tjumlah + eval(jumlah1);
        thna = thna + (eval(harga1) * ppn);
        thpp = thpp + eval(harga1);
    }
    // var margin = document.getElementById("margin").value;
    // var ppn = tjumlah*0.1;
    // var hnappn = eval(tjumlah)+eval(ppn);
    var hnappn = eval(thpp) + (eval(thpp) * ppn);
    // var hargajual = eval(10/100)*eval(hnappn);
    // document.getElementById("hargajualjadi").value=formatCurrency1(hargajual);
    // document.getElementById("ppn").value=formatCurrency1(ppn);
    document.getElementById("vtotal").value = separateComma(tjumlah);
    // document.getElementById("hna").value=separateComma(thna);
    document.getElementById("hna").value = separateComma(hnappn);
    document.getElementById("hpp").value = separateComma(thpp);
}

function checkstock(param1, param2) {
    $.ajax({
        url: "/farmasi_produksi/checkstock/?kode=" + param2 + "&gudang=" + param1,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            if (data.status == 0) {
                swal({
                    title: "Kesalahan",
                    html: "stock kosong atau kurang dari 1",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("[id='save']").prop("disabled", true);
            } else if (data.status == 2) {
                swal({
                    title: "Kesalahan",
                    html: "stock kosong atau kurang dari 1",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("[id='save']").prop("disabled", true);
            } else if (data.stock == 0) {
                swal({
                    title: "Kesalahan 2",
                    html: "Stock Tidak Cukup",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("[id='save']").prop("disabled", true);
            } else {
                $("[id='save']").prop("disabled", false);
            }
        }
    });
}
</script>

</body>

</html>