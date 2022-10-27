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
            <span class="title-web">Hutang <small>Manual</small>
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
                <a href="<?php echo base_url();?>hutang">
                    Daftar Hutang
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Entri Hutang
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
                                Hutang
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No. Tukar Faktur<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="nomortukarfaktur" name="nomortukarfaktur"
                                                class="form-control rightJustified" placeholder=""
                                                value="<?php echo $nomortukarfaktur;?>" readonly>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No. Transaksi<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="nomorbukti" name="nomorbukti"
                                                class="form-control rightJustified" value="<?php echo $nomor;?>"
                                                readonly>

                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No. Faktur / Rekanan<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="nomorfaktur" name="nomorfaktur"
                                                class="form-control rightJustified" placeholder="" value="">
                                        </div>

                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Jenis Faktur<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <select id="jenis_faktur" name="jenis_faktur"
                                                class="form-control select2_el_jenisfaktur" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Supplier<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <select id="supplier" name="supplier" class="form-control select2_el_vendor"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Akun Biaya Langsung<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <select id="acbiaya" name="acbiaya"
                                                class="form-control select2_el_akunbiaya" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Invoice<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_invoice" name="tanggal_invoice"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Ambil<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_ambil" name="tanggal_ambil"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Jatuh Tempo<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="jatuh_tempo" name="jatuh_tempo"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Rencana Bayar<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_rencana_bayar" name="tanggal_rencana_bayar"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Jumlah Tagihan<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="jumlah_tagihan" name="jumlah_tagihan"
                                                data-type="currency" onkeyup="cekPpn(this.value);"
                                                class="form-control rightJustified">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Materai<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="materai" name="materai" data-type="currency"
                                                class="form-control rightJustified">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Keterangan<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="keterangan" name="keterangan"
                                                class="form-control rightJustified" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">DPP<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="dpp" name="dpp" class="form-control rightJustified"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Biaya Lain<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="biayalain" name="biayalain" data-type="currency"
                                                class="form-control rightJustified">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">PPN
                                            (<?php echo (int)$prosentase_ppn; ?>%)<font color="red">*</font></label>
                                        <div class="col-md-3">
                                            <select id="jenis_ppn" name="jenis_ppn" class="form-control"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <option value=''>Jenis PPn...</option>
                                                <option value='exclude'>Exclude</option>
                                                <option value='include'>Include</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="hidden" id="ppn" name="ppn" class="form-control rightJustified"
                                                value="<?php echo $prosentase_ppn; ?>">
                                            <input type="text" id="ppnrp" name="ppnrp"
                                                class="form-control rightJustified" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">PPH23<font color="red">*</font></label>
                                    <span class="d-flex flex-row">
                                        <div class="col-md-6">
                                            <input type="text" id="pph" name="pph" data-type="currency"
                                                class="form-control rightJustified">
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Tagihan + PPn<font color="red">*</font></label>
                                    <span class="d-flex flex-row">
                                        <div class="col-md-6">
                                            <input type="text" id="tagihan_plus_ppn" name="tagihan_plus_ppn"
                                                class="form-control rightJustified" readonly>
                                        </div>
                                    </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>



            </div>
            <!--tab-->

            <div class="row form-actions">
                <div class="col-xs-8">
                    <div class="wells">
                        <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                            Simpan</button>
                        <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i
                                class="fa fa-pencil-square-o"></i> Data Baru</button>
                        <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i
                                class="fa fa-undo"></i> Kembali</button>
                        <!-- <button type="button" class="btn yellow" onclick=""><i class="fa fa-print"></i> Cetak</button> -->
                        <input type="hidden" id="id" name="id" class="form-control rightJustified">

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
  $this->load->view('template/v_report');
?>

<script>
function isCharacterALetter(char) {
    return (/[a-zA-Z]/).test(char)
}

$(document).ready(function() {
    $('#jenis_ppn').change(function() {
        hitungPpn($('#jumlah_tagihan').val());
    });
});

$("input[data-type='currency']").on({
    blur: function() {
        var val = this.value.replaceAll(',', '').split('.');
        this.value = currencyFormat(val[0]);
    }
});

function currencyFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function cekPpn(jml_tagihan) {
    hitungPpn(jml_tagihan);
}

function hitungPpn(jml_tagihan) {
    var val = jml_tagihan.replaceAll(',', '').split('.');
    jml_tagihan = val[0];
    var jumlah_tagihan = jml_tagihan == '' || isCharacterALetter(jml_tagihan) ? 0 : jml_tagihan;
    var jenis_ppn = $('#jenis_ppn').val();
    var dpp = $('#dpp').val();
    var prosentase_ppn = '<?php echo $prosentase_ppn; ?>';
    var ppn, ppnrp;

    if (jenis_ppn == 'exclude') {
        dpp = parseInt(jumlah_tagihan);
        ppn = Math.ceil(parseInt(prosentase_ppn) * parseInt(dpp) / 100);
        ppnrp = parseInt(ppn) + parseInt(dpp);

        $('#dpp').val(dpp);
        $('#ppnrp').val(ppn);
        $('#tagihan_plus_ppn').val(ppnrp);

        $('#dpp').val(currencyFormat(dpp));
        $('#ppnrp').val(currencyFormat(ppn));
        $('#tagihan_plus_ppn').val(currencyFormat(ppnrp));
    } else if (jenis_ppn == 'include') {
        dpp = Math.ceil(parseInt(jumlah_tagihan) / (1 + (parseInt(prosentase_ppn) / 100)));

        ppn = parseInt(jumlah_tagihan) - dpp;
        ppnrp = parseInt(ppn) + parseInt(dpp);

        $('#dpp').val(dpp);
        $('#ppnrp').val(ppn);
        $('#tagihan_plus_ppn').val(ppnrp);

        $('#dpp').val(currencyFormat(dpp));
        $('#ppnrp').val(currencyFormat(ppn));
        $('#tagihan_plus_ppn').val(currencyFormat(ppnrp));
    } else {
        $('#dpp').val(0);
        $('#ppnrp').val(0);
        $('#tagihan_plus_ppn').val(0);
    }
}


var idrow = 2;
var idrow2 = 2;

function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ")' class='select2_el_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified'>";
    td5.innerHTML = "<a class='btn default' id=lupharga" + idrow +
        " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a>";
    td6.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td7.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%'>";

    initailizeSelect2_barang();
    idrow++;
}

function tambah2() {
    var x = document.getElementById('datatable2').insertRow(idrow2);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);

    var akun =
        "<select name='lkode[]' class='select2_el form-control' ><option value=''>--- Pilih Akun ---</option></select>";

    td1.innerHTML = akun;
    td2.innerHTML = "<input name='ljumlah[]' id=ljumlah" + idrow2 + " onchange='totalline(" + idrow2 +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='lket[]'    id=lket" + idrow2 + " type='text' class='form-control' >";
    initailizeSelect2();

    idrow2++;
}


function showbarang(str) {
    var xhttp;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getbarang/" + str, true);
    xhttp.send();
}

function showakun(str) {
    var xhttp;
    if (str == "") {
        document.getElementById("daftarakun").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("daftarakun").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getakun/" + str, true);
    xhttp.send();
}

function showharga(str) {
    var xhttp;
    if (str == "") {
        document.getElementById("dafhargabeli").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("dafhargabeli").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getharga/" + str, true);
    xhttp.send();
}

function showbarangname1(str, id) {
    var xhttp;
    if (str == "") {
        document.getElementById("nama" + id).value = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("nama" + id).value = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getbarangname/" + str, true);
    xhttp.send();
}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>pembelian_pesanan/getinfobarang/" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan);
            $('#harga' + vid).val(data.hargabeli);
            totalline(vid);
        }
    });


}

function showakunname(str, id) {
    var xhttp;
    var vid = id.substring(5);
    $.ajax({
        url: "<?php echo base_url();?>pembelian_pesanan/getinfoakun/" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#lnama' + vid).val(data.namaakun);
        }
    });


}

function getidharga(id) {
    var vid = id.substring(8);
    document.getElementById("nopilihharga").value = vid;
    var supp = document.getElementById("supp").value;
    var item = document.getElementById("kode" + vid).value;
    var param = supp + '~' + item;
    showharga(param);
}


function post_harga(v1, v2) {
    id = document.getElementById("nopilihharga").value;
    document.getElementById("sat" + id).value = v2;
    document.getElementById("harga" + id).value = v1;
    totalline(id);
}


function getnobukti() {
    var xhttp;

    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("nomorbukti").value = this.responseText;
        }
    };

    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getnobukti", true);
    xhttp.send();
}

function save() {
    var nomortukarfaktur = $('[name="nomortukarfaktur"]').val();
    var nomorbukti = $('[name="nomorbukti"]').val();
    var nomorfaktur = $('#nomorfaktur').val();
    var jenis_faktur = $('#jenis_faktur').val();
    var supplier = $('#supplier').val();
    var acbiaya = $('#acbiaya').val();
    var tanggal_invoice = $('#tanggal_invoice').val();
    var tanggal_ambil = $('#tanggal_ambil').val();
    var jatuh_tempo = $('#jatuh_tempo').val();
    var tanggal_rencana_bayar = $('#tanggal_rencana_bayar').val();
    var jumlah_tagihan = $('#jumlah_tagihan').val();
    var materai = $('#materai').val();
    var keterangan = $('#keterangan').val();
    var dpp = $('#dpp').val();
    var biayalain = $('#biayalain').val();
    var jenis_ppn = $('#jenis_ppn').val();
    var ppn = $('#ppn').val();
    var pph = $('#pph').val();
    var tagihan_plus_ppn = $('#tagihan_plus_ppn').val();
    

    // console.log($('#frmpembelian').serialize());
    if (nomortukarfaktur == "" ||
        nomorbukti == "" ||
        nomorfaktur == "" ||
        jenis_faktur == "" || jenis_faktur == null ||
        supplier == "" || supplier == null ||
        acbiaya == "" || acbiaya == null ||
        tanggal_invoice == "" ||
        tanggal_ambil == "" ||
        jatuh_tempo == "" ||
        tanggal_rencana_bayar == "" ||
        jumlah_tagihan == "" ||
        materai == "" ||
        keterangan == "" ||
        dpp == "" ||
        biayalain == "" ||
        jenis_ppn == "" || jenis_ppn == null ||
        ppn == "" ||
        pph == ""
    ) {
        swal('ENTRI HUTANG', 'Data Belum Lengkap...', '');
    } else {

        $.ajax({
            url: '<?php echo site_url('hutang/save/1')?>',
            data: $('#frmpembelian').serialize(),
            type: 'POST',

            success: function(data) {
                swal({
                    title: "ENTRI HUTANG",
                    html: "<p> No. Transaksi   : <b>" + data + "</b> </p>" +
                        // "Tanggal :  " + moment(tanggal_invoice).format('DD/MM/YYYY'),
                         "<p>Tanggal :  " + moment(tanggal_invoice).format('DD/MM/YYYY') + "</p>" +
                            "<p><b>Rp " + (tagihan_plus_ppn) + "</b></p>",
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>hutang";
                });


            },
            error: function(data) {
                swal('ENTRI HUTANG GAGAL', 'Data gagal disimpan ...', '');
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

function hapus2() {
    if (idrow2 > 2) {
        var x = document.getElementById('datatable2').deleteRow(idrow2 - 1);
        idrow2--;
        total();
    }
}

function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;

    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[1].children[0].value;
        harga = row.cells[3].children[0].value;
        diskon = row.cells[5].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1 * harga1);

        diskon = eval((diskon1 / 100) * jumlah1 * harga1);

        tdiskon = tdiskon + diskon;


    }

    var table2 = document.getElementById('datatable2');
    var rowCount2 = table2.rows.length;

    tbiaya = 0;

    for (var i = 1; i < rowCount2; i++) {
        var row = table2.rows[i];

        biaya = row.cells[1].children[0].value;
        var biaya1 = Number(biaya.replace(/[^0-9\.]+/g, ""));

        tbiaya = tbiaya + eval(biaya1);
    }

    var cppn = document.getElementById("sppn").value;
    if (cppn == "Y") {
        tppn = (tjumlah - tdiskon) * 0.1;
    } else {
        tppn = 0;
    }


    document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tjumlah);
    document.getElementById("_vdiskon").innerHTML = formatCurrency1(tdiskon);
    document.getElementById("_vbiayalain").innerHTML = formatCurrency1(tbiaya);
    document.getElementById("_vppn").innerHTML = formatCurrency1(tppn);
    document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah - tdiskon + tbiaya + tppn);


}

function totalline(id) {

    var table = document.getElementById('datatable');
    var row = table.rows[id];
    jumlah = row.cells[1].children[0].value * row.cells[3].children[0].value;
    diskon = (row.cells[5].children[0].value / 100) * jumlah;
    tot = jumlah - diskon;
    row.cells[6].children[0].value = tot;
    total();

}


function showpo() {
    var xhttp;
    var str = $('[name="supp"]').val();

    if (str == "") {
        document.getElementById("kodepo").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("kodepo").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_faktur/getlistpo/" + str, true);
    xhttp.send();
}

function showpb() {
    var xhttp;
    var str = $('[name="supp"]').val();

    if (str == "") {
        document.getElementById("kodepb").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("kodepb").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_faktur/getlistpb/" + str, true);
    xhttp.send();
}

function getpo() {
    var xhttp;
    var str = $('[name=kodepo]').val();
    if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=qty1]').val('');
        $('[id=sat1]').val('');
        $('[id=harga1]').val('');
        $('[id=disc1]').val('');
        totalline(1);
    } else {
        $.ajax({
            url: "<?php echo base_url();?>pembelian_faktur/getpo/" + str,
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

                    document.getElementById("qty" + x).value = data[i].qtyorder;
                    document.getElementById("sat" + x).value = data[i].satuan;
                    document.getElementById("harga" + x).value = data[i].hargabeli;
                    document.getElementById("disc" + x).value = data[i].disc;


                }

            }
        });
    }
}

function getpb() {
    var xhttp;
    var str = $('[name=kodepb]').val();
    if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=qty1]').val('');
        $('[id=sat1]').val('');
        $('[id=harga1]').val('');
        $('[id=disc1]').val('');
        totalline(1);
    } else {
        $.ajax({
            url: "<?php echo base_url();?>pembelian_faktur/getpb/" + str,
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

                    document.getElementById("qty" + x).value = data[i].qtyterima;
                    document.getElementById("sat" + x).value = data[i].satuan;
                    document.getElementById("harga" + x).value = 0;
                    document.getElementById("disc" + x).value = 0;

                }

            }
        });
    }
}

function getpoheader() {
    var xhttp;
    var str = $('[name=kodepo]').val();
    if (str == "") {} else {
        $.ajax({
            url: "<?php echo base_url();?>pembelian_faktur/getpoheader/" + str,
            type: "GET",
            dataType: "JSON",

            success: function(data) {
                $('[name="sppn"]').val(data.sppn);
                $('[name="keterangan"]').val(data.ket);
                $('[name="alamat"]').val(data.alamat1);
            }
        });
    }
}

function getbiaya() {
    var xhttp;
    var str = $('[name=kodepo]').val();
    if (str == "") {
        hapus();
        $('[id=lkode0]').val('');
        $('[id=lnama0]').val('');
        $('[id=ljumlah0]').val('');
        $('[id=lket0]').val('');
        totalline(0);
    } else {
        $.ajax({
            url: "<?php echo base_url();?>pembelian_faktur/getbiaya/" + str,
            type: "GET",
            dataType: "JSON",

            success: function(data) {
                for (i = 0; i <= data.length - 1; i++) {
                    hapus2();
                }

                for (i = 0; i <= data.length - 1; i++) {
                    if (i > 0) {
                        tambah2();
                    }
                    document.getElementById("lkode" + i).value = data[i].kodeakun;
                    document.getElementById("lnama" + i).value = data[i].namaakun;
                    document.getElementById("ljumlah" + i).value = data[i].jumlah;
                    document.getElementById("lket" + i).value = data[i].keterangan;
                    totalline(i);
                }




            }
        });
    }
}
</script>


<div class="modal fade" id="lupharga" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="nopilihharga">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Daftar Harga Pembelian</h4>
                    <h5><strong><span id="namabarangharga"></span></strong></h5>
                </div>
                <div class="modal-body">
                    <div id="dafhargabeli"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btntutup" class="btn red" data-dismiss="modal">Tutup</button>
                </div>
        </div>
    </div>
</div>



</body>

</html>