<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?= $this->session->userdata("unit") ?>
            </span>
            -
            <span class="title-web">Logistik <small>Permohonan Barang Ke Gudang</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home" style="color:white;"></i>&nbsp;<a href="<?php echo base_url();?>dashboard"
                    class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:white;"></i></li>
            <li><a href="/permohonan_log" class="title-white">Permohonan</a>&nbsp;<i class="fa fa-angle-right"
                    style="color:white;"></i></li>
            <li><a href="#" class="title-white">Entri Permohonan</a></li>
        </ul>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i> Data Baru
        </div>
    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post"
            style="padding-bottom:0px !important;margin-bottom:0 !important">
            <div class="form-body" style="padding-bottom:0px !important;margin-bottom:0 !important">
                <div class="tabbable tabbable-custom tabbable-full-width"
                    style="padding-bottom:0px !important;margin-bottom:0 !important">
                    <!-- <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
									<i class="fa fa-file"></i> 
									Permohonan Barang
								</a>
							</li>
							li class="">
								<a href="#tab2" data-toggle="tab">                                   
									<i class="fa fa-info-circle"></i>
									Info
								</a>
							</li
						</ul> -->
                    <!-- <div class="tab-content">
							<div class="tab-pane active" id="tab1">		 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tanggal</label>
                                <div class="col-md-4">
                                    <input id="tanggal" name="tanggal" class="form-control input-medium" type="date"
                                        value="<?php echo date('Y-m-d');?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pemohonan No.</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="hidden" name="hidenomorbukti" value="<?= $nomor ?>">
                                        <input type="text" placeholder="Otomatis" name="nomorbukti" class="form-control"
                                            value="AUTO" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Gudang Asal</label>
                                <div class="col-md-9">
                                    <select id="gudang_asal" name="gudang_asal"
                                        class="form-control select2_el_logistik_depo" data-placeholder="Pilih..."
                                        onkeypress="return tabE(this,event)"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Keterangan</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="ket">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Gudang Tujuan</label>
                                <div class="col-md-9">
                                    <select id="gudang_tujuan" name="gudang_tujuan"
                                        class="form-control select2_el_logistik_depo" data-placeholder="Pilih..."
                                        onchange="getgudang()" onkeypress="return tabE(this,event)"></select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="padding:20px">
                            <table id="datatable" class="table table-bordered table-condensed table-scrollable">
                                <thead>
                                    <tr>
                                        <th width="30%" style="text-align: center">Kode/Nama Barang</th>
                                        <th width="10%" style="text-align: center">Kuantitas</th>
                                        <th width="10%" style="text-align: center">Satuan</th>
                                        <th width="10%" style="text-align: center">Harga</th>
                                        <th width="15%" style="text-align: center">Total</th>
                                        <th width="20%" style="text-align: center">Keterangan</th>
                                    </tr>
                                    <thead>
                                    <tbody>
                                        <tr>
                                            <td width="30%">
                                                <select name="kode[]" id="kode1"
                                                    class="select2_el_log_baranggud form-control"
                                                    onchange="showbarangname(this.value, 1)"></select>
                                            </td>
                                            <td width="10%"><input name="qty[]" onchange="totalline(1)" value="1"
                                                    id="qty1" type="text" class="form-control rightJustified"></td>
                                            <td width="10%"><input name="sat[]" id="sat1" type="text"
                                                    class="form-control " onkeypress="return tabE(this,event)" readonly></td>
                                            <td width="10%"><input name="harga[]" onchange="totalline(1)" id="harga1"
                                                    type="text" class="form-control rightJustified"
                                                    onkeypress="return tabE(this,event)" readonly></td>
                                            <td width="15%"><input name="total[]" onchange="totalline(1)" id="total1"
                                                    type="text" class="form-control rightJustified"
                                                    onkeypress="return tabE(this,event)" readonly></td>
                                            <td width="10%"><input name="note[]" id="note1" type="text"
                                                    class="form-control " onkeypress="return tabE(this,event)"></td>
                                        </tr>
                                    </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:right">TOTAL</td>
                                        <td><input type="text" class="form-control rightJustified" id="vtotal" readonly>
                                        </td>
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
                    <!-- </div> -->
                    <!-- tab1-->
                    <!-- <div class="tab-pane" id="tab2">	
								<div class="row">
									
								</div>
								
							</div> -->
                    <!-- tab2-->
                    <!-- </div> -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="well">
                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>

                                <div class="btn-group">
                                    <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>
                                <a type="button" class="btn red" href="<?= site_url('Permohonan_log'); ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
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
    initailizeSelect2_log_baranggud(null);
});

var idrow = 2;

function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);

    var akun = '<select name="kode[]" id="kode' + idrow +
        '" class="select2_el_log_baranggud form-control" onchange="showbarangname(this.value, ' + idrow +
        ')"></select>';
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id='qty" + idrow + "' onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id='sat" + idrow + "' type='text' class='form-control'  readonly>";
    td4.innerHTML = "<input name='harga[]'  id='harga" + idrow + "' onchange='totalline(" + idrow +
        ")' onkeyup='format_num(this.value)' type='text' class='form-control'  readonly>";
    td5.innerHTML = "<input name='total[]'    id='total" + idrow + "' onchange='totalline(" + idrow +
        ")' type='text' class='form-control rightJustified'  readonly>";
    td6.innerHTML = "<input name='note[]'    id='note" + idrow + "' type='text' class='form-control' >";
    total();
    idrow++;
    initailizeSelect2_log_baranggud($("#gudang_tujuan").val());
}

function showbarangname(str, id) {
    var xhttp;
    var qty = $("#qty" + id).val();
    var gudang = $("#gudang_tujuan").val();
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>permohonan_log/getinfobarang/?str=" + str+"&gudang_asal="+gudang,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            // console.log(data);
            // if(data.saldoakhir == null){
            //     var saldo = '0,00';
            // } else {
            //     var saldo = data.saldoakhir;
            // }
            // var saldo1 = Number(parseInt(saldo.replaceAll(',','')));
            // if(saldo1 < qty){
            //     swal({
            //         title: "KUANTITAS",
            //         html: "Melebihi saldo akhir<br>Saldo akhir saat ini : "+separateComma(saldo),
            //         type: "info",
            //         confirmButtonText: "OK"
            //     }).then((value) => {
            //         $("#kode"+id).empty();
            //         $("#sat"+id).val('');
            //         $("#harga"+id).val('');
            //         $("#total"+id).val('');
            //         totalline(vid);
            //     });
            // } else {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(separateComma(Number(data.hargabelippn)));
                totalline(vid);
            // }
        }
    });
}

function getgudang() {
    // console.log($("#gudang_tujuan").val());
    initailizeSelect2_log_baranggud($("#gudang_tujuan").val());
}


function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var gudang_tujuan = $('[name="gudang_tujuan"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || gudang_tujuan == "" || total == "" || total == "0.00") {
        swal('MUTASI GUDANG', 'gudang belum diisi ...', '');
    } else {
        // console.log($('#frmpenjualan').serialize());      
        $.ajax({
            url: '<?php echo site_url('permohonan_log/save/1')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "PERMOHONAN GUDANG",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal + "</b> </p>" + "Total: " + " " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>permohonan_log";
                });
            },
            error: function(data) {
                swal('PERMOHONAN GUDANG', 'Data gagal disimpan ...', '');
            }
        });
    }
}

function hapus() {
    if (idrow > 2) {
        var x = document.getElementById('datatable').deleteRow(idrow - 1);
        idrow--;
        total();
    }
}

function showpo() {
    var xhttp;
    var str = $('[name="cust"]').val();

    if (str == "") {
        document.getElementById("kodeso").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("kodeso").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pengiriman/getlistpo/" + str, true);
    xhttp.send();
}

function getpo() {
    var xhttp;
    var str = $('[name=kodeso]').val();
    if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=qty1]').val('');
        $('[id=sat1]').val('');
    } else {
        $.ajax({
            url: "<?php echo base_url();?>penjualan_pengiriman/getpo/" + str,
            type: "GET",
            dataType: "JSON",

            success: function(data) {
                for (i = 0; i <= data.length - 1; i++) {
                    hapus();
                }

                for (i = 0; i <= data.length - 1; i++) {
                    if (i > 0) {
                        tambah();
                    }

                    x = i + 1;

                    var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i]
                        .namabarang);
                    $('#kode' + x).append(option).trigger('change');

                    document.getElementById("qty" + x).value = data[i].sisa;
                    document.getElementById("sat" + x).value = data[i].satuan;
                }




            }
        });
    }
}

// window.onload = function(){
//         document.getElementById('nomorbukti').focus();
// };

function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;

    row.cells[4].children[0].value = separateComma(jumlah);
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

    tjumlah = 0;
    for (var i = 1; i < rowCount - 1; i++) {
        var row = table.rows[i];

        ztotal = row.cells[4].children[0].value;

        var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1);

    }

    document.getElementById("vtotal").value = separateComma(tjumlah);
}
</script>



</body>

</html>