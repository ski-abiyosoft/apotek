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
            <span class="title-web">LOGISTIK <small>Retur Pembelian</small>
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
                <a class="title-white" href="<?php echo base_url(); ?>pembelian_retur_log">
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
                                            <select id="supp" name="supp" class="form-control select2_el_vendor"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Retur #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" placeholder="AUTO" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal</label>
                                        <div class="col-md-3">
                                            <input id="tanggal" name="tanggal" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>
                                        <div class="col-md-1">
                                            <span class="input-group-btn">
                                                <label class="control-label"> <b> s/d </b></label>
                                            </span>                                    
                                        </div>
                                        <div class="col-md-3">
                                            <input id="tanggal2" name="tanggal2" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                            <div class="col-md-6">
                                                <input class="form-control" type="text" id="gudang1" name="gudang1" readonly>
                                                <input class="form-control" type="hidden" id="gudang" name="gudang">
                                            </div>
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
                                                <button type="button" class="btn btn-sm blue" onclick="search_bapb()" ><i class="fa fa-search"></i><b> Ambil BAPB</b></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Alasan <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" id="alasan" name="alasan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"></label>
                                        <div class="col-md-5">
                                            <div class="">
                                               <input class="form-control" type="text" name="kodepu" id="kodepu" readonly>
                                            </div>
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
                                            <tr id="retur_tr1">
                                                <td width="5%">
                                                    <button id="btnhapus1" type='button' onclick='hapusBarisIni(1)' class='btn red'><i class='fa fa-trash-o'></i></button>
                                                </td>
                                                <td width="20%">
                                                    <select name="kode[]" id="kode1" class="select2_el_farmasi_barang form-control" onchange="showbarangname(this.value, 1)" readonly>
                                                        <option value="">--- Pilih Barang ---</option>
                                                    </select>
                                                </td>
                                                <td width="10%">
                                                    <input name="qty[]" onchange="totalline(1);total();cekqty(1)" value="1" id="qty1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td width="10%">
                                                    <select name="sat[]" id="sat1" class="form-control"></select>
                                                </td>
                                                <td width="10%">
                                                    <input name="harga[]" onchange="totalline(1)" value="0" id="harga1" type="text" class="form-control rightJustified" readonly>
                                                </td>
                                                <?php if($pkp == 1) : ?>
                                                    <td width="10%">
                                                        <select name='tax[]' id='tax1' class='form-control' onchange='totalline(1); total()'>
                                                            <option value='1'>Ya</option>
                                                            <option value='0'>Tidak</option>
                                                        </select>
                                                    </td>
                                                <?php else : ?>
                                                    <td width="10%">
                                                        <select name='tax[]' id='tax1' class='form-control' onchange='totalline(1); total()'>
                                                            <option value='0'>Tidak</option>
                                                            <option value='1'>Ya</option>
                                                        </select>
                                                    </td>
                                                <?php endif; ?>
                                                <td width="10%">
                                                    <input name="disc[]" onchange="totalline(1);total();cekdisc(1)" value="0" id="disc1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td width="10%">
                                                    <input name="discrp[]" onchange="totalline(1);total();cekdiscrp(1)" value="0" id="discrp1" type="text" class="form-control rightJustified ">
                                                </td>
                                                <td width="15%">
                                                    <input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="wells">
                                <button type="button" onclick="savex()" class="btn blue"><i class="fa fa-save"></i> <b>Simpan</b></button>
                                <div class="btn-group">
                                    <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('pembelian_retur_log') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
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
                                    <?php
                                    $pjkx = $this->db->get('tbl_pajak')->result();
                                    foreach ($pjkx as $pjk) {
                                        $pj = $pjk->prosentase;
                                    }
                                    ?>
                                    <input type="hidden" value="<?= $pj; ?>" name="vatrp" id="vatrp">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal bapb -->
<div class="modal fade" role="dialog" id="list_bapb" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#c50c0c;color:#fff">
                <h4><b>Daftar No BAPB</b></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">                            
                            <th style="text-align: center">No BPAB/No Transaksi</th>
                            <th style="text-align: center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="daftar_bapb"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn red" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal bapb -->

<?php
$this->load->view('template/footer');
?>

<script>
    $("#form_ppn").hide();
    var idrow = 2;
    var rowCount;
    var arr = [1];

    function tambah() {
        var table = $("#datatable");
        if('<?= $pkp ?>' == '1') {
            table.append("<tr id='retur_tr" + idrow + "'>" +
                "<td><button id='btnhapus" + idrow + "' type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
                "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select></td>" +
                "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'></td>" +
                "<td><select name='sat[]' id='sat" + idrow + "' class='form-control'></select></td>" +
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
                "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'></td>" +
                "<td><select name='sat[]' id='sat" + idrow + "' class='form-control'></select></td>" +
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

    function cekqty(id) {
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number((hargax.replaceAll(',', '')));
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var discrp = qty * harga * disc / 100;
        var jumlah = qty * harga - discrp;
        $('#discrp' + id).val(separateComma(discrp.toFixed(2)));
        $('#qty').val(qty.toFixed(0));
        $('#qty' + id).val(separateComma(qty.toFixed(0)));
        $('#jumlah' + id).val(separateComma(jumlah.toFixed(2)));
        total();
    }

    function cekdisc(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number((hargax.replaceAll(',', '')));
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        if (disc < 1) {
            $("#discrp" + id).val(separateComma((0).toFixed(2)));
        } else {
            var discrp = qty * harga * disc / 100;
            $("#discrp" + id).val(separateComma(discrp.toFixed(2)));
        }
        totalline(id);
    }


    function cekdiscrp(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number((hargax.replaceAll(',', '')));
        var discrpx = $("#discrp" + id).val();
        var discrp = Number((discrpx.replaceAll(',', '')));
        $("#disc" + id).val(0);
        $("#discrp" + id).val(separateComma(discrp.toFixed(2)));
        subtotal = qty * harga;
        tot = subtotal - discrp;
        $('#jumlah' + id).val(separateComma(tot.toFixed(2)));
        total();
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
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur_log/getbarang/" + str, true);
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
                document.getElementById("jumdata").value = this.jumdata;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur_log/getbarangname/" + str, true);
        xhttp.send();
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

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?php echo base_url(); ?>pembelian_retur_log/getinfobarang/" + str,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                var qty = $('#qty' + id).val();
                $('#harga' + id).val(separateComma(data.hargabeli));
                var harga = data.hargabeli;
                var jumlah = Number(parseInt(qty.replaceAll(',', ''))) * data.hargabeli;
                $('#jumlah' + id).val(separateComma(jumlah));
                totalline(id);
                $.ajax({
                    url: "<?php echo base_url(); ?>logistik_bapb/getinfobarang_sat/" + str,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data)
                        var opt = data;
                        var satuan = $("#sat"+vid);
                        satuan.empty();
                        $(opt).each(function() {
                            $.ajax({
                            url: "<?php echo base_url(); ?>logistik_bapb/getinfobarang_sat2/" + this.satuan,
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                var option = $("<option/>");
                                option.html(data.aponame);
                                option.val(data.apocode);
                                satuan.append(option);
                                if($('#kodepu').val() != null){
                                    $("#sat"+vid).val(data.apocode).change();
                                }
                            }
                            })
                        });
                    }
                });
            }
        });
    }

    function getidharga(id) {
        return;
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

    function savex() {
        var noform    = $("#nomorbukti").val();
        var tanggal   = $("#tanggal").val();
        var gudang    = $("#gudang").val();
        var total     = $('#_vtotalx').val();
        var totalz    = Number(total.replaceAll(",",""));
        var alasan    = $("#alasan").val();
        if (totalz < 1 || gudang == "" || alasan == '') {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?= site_url() ?>pembelian_retur_log/save_one',
                data: $('#frmpembelian').serialize(),
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    var no = data.nomor;
                    var bapb = data.bapb;
                    //rincian
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    var pj = parseInt($("#vatrp").val()) / 100;
                    totvatrp = 0;
                    diskontotal = 0;
                    for (i = 1; i < rowCount; i++) {
                        var row = table.rows[i];
                        kode = row.cells[1].children[0].value;
                        qtyx = row.cells[2].children[0].value;
                        var qty = Number(qtyx.replace(/[^0-9\.]+/g, ""));
                        sat = row.cells[3].children[0].value;
                        hargax = row.cells[4].children[0].value;
                        var harga = Number(hargax.replace(/[^0-9\.]+/g, ""));
                        discx = row.cells[6].children[0].value;
                        var disc = Number(discx.replace(/[^0-9\.]+/g, ""));
                        discrpx = row.cells[7].children[0].value;
                        var discrp = Number(discrpx.replace(/[^0-9\.]+/g, ""));
                        vat = row.cells[5].children[0].value;
                        var jumlahx = row.cells[8].children[0].value;
                        var jumlah = Number(jumlahx.replace(/[^0-9\.]+/g, ""));
                        if (vat == 1) {
                            var vatrp = jumlah * pj;
                        } else {
                            var vatrp = 0;
                        }
                        $.ajax({
                            url: '<?= site_url() ?>pembelian_retur_log/save_multi/?kode=' + kode + '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&disc=' + disc + '&discrp=' + discrp + '&tax=' + vat + '&jumlah=' + jumlah + '&taxrp=' + vatrp + '&retur_no=' + no + "&bapb=" + bapb,
                            data: $('#frmpembelian').serialize(),
                            type: 'POST',
                            dataType: 'JSON',
                            success: function(data) {
                                console.log(data);
                            }
                        });
                    }
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + data.nomor + "</b> </p>" + "Tanggal :  " +
                            tanggal + '<br> Total : ' + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>pembelian_retur_log";
                    });
                }
            });
        }
    }

    function save() {
        var noform = $('[name="nomorbukti"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var gudang = $('[name="gudang"]').val();
        var total = $('#_vtotal').text();
        if (noform == "" || total == "" || total == "0.00" || gudang == "") {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('pembelian_retur_log/save/1') ?>',
                data: $('#frmpembelian').serialize(),
                type: 'POST',
                success: function(data) {
                    data1 = JSON.parse(data);
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + data1.nomor + "</b> </p>" +
                            "Tanggal :  " + tanggal + '<br> Total : ' + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>pembelian_retur_log";
                    });
                },
                error: function(data) {
                    swal('RETUR PEMBELIAN', 'Data gagal disimpan ...', '');
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

    function hapusBarisIni(param) {
        $("#retur_tr" + param).remove();
        total();
    }

    function total_before() {
        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;
        tjumlah = 0;
        tdiskon = 0;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[7].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            tjumlah = tjumlah + eval(jumlah1 * harga1);
            diskon = eval((diskon1 / 100) * jumlah1 * harga1);
            tdiskon = tdiskon + diskon;
        }
        var cppn = document.getElementById("sppn").value;
        if (cppn == "T") {
            tppn = (tjumlah - tdiskon) * 11 / 100;
        } else {
            tppn = 0;
        }
        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
        document.getElementById("_vppn").innerHTML = separateComma(tppn);
        document.getElementById("_vtotal").innerHTML = separateComma(tjumlah - tdiskon + tppn);
        $('[name="_vtotalx"]').val(tjumlah - tdiskon + tppn);
    }

    var cekppn2 = '<?php echo $cekppn2; ?>';

    function total() {
        var tmateraix = $("#materai").val();
        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));
        if (xtotal >= '5000000') {
            $('#materai').val('10000').change();
        }
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
        var tmaterai = Number(tmateraix);
        if('<?= $pkp ?>' == '1'){
            var abc = Number(tjumlah - tdiskon);
        } else {
            var abc = Number(tjumlah - tdiskon + tppn);
        }
        if (tmaterai == 10000) {
            var tmattotal = abc + tmaterai;
        } else {
            var tmattotal = abc;
        }
        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(2));
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(2));
        document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(2));
        document.getElementById("_vtotal").innerHTML = separateComma(tmattotal.toFixed(2));
        $('[name="_vtotalx"]').val(tjumlah - tdiskon + tppn);
        $('[name="_vppn"]').val(tppn);
    }

    function totalline(id) {
        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            var qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
            var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
            var discrp = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
            jumlah = qty * harga;
            tot = jumlah - discrp;
            row.cells[8].children[0].value = separateComma(tot.toFixed(2));
            total();
        }
    }

    function showpo() {
        var xhttp;
        var str = $('[name="supp"]').val();

        if (str == "") {
            document.getElementById("kodepu").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("kodepu").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur_log/getlistpo/" + str, true);
        xhttp.send();
    }

    function search_bapb() {
        var supp        = $('#supp').val();
        var tanggal     = $('#tanggal').val();
        var tanggal2    = $('#tanggal2').val();
        if(supp=='' || supp == null) {
            swal({
                title   : "Pemasok",
                html    : "Wajib di Pilih",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }
        if(tanggal=='' || tanggal == null) {
            swal({
                title   : "Tanggal Awal",
                html    : "Wajib di Pilih",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }
        if(tanggal2=='' || tanggal2 == null) {
            swal({
                title   : "Tanggal Awal",
                html    : "Wajib di Pilih",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }
        $.ajax({
            url         : "<?php echo site_url('pembelian_retur_log/get_bapb?vendor=')?>"+supp+"&startdate="+tanggal+"&enddate="+tanggal2,
            type        : "GET",
            dataType    : "JSON",
            success: function(data)
            {                           
                $('#daftar_bapb').empty();
                $.each(data, function( key, value ) {
                    $('#daftar_bapb').append(
                        "<tr>\
                            <td style='text-align: center'>"+value.terima_no+"</td>\
                            <td style='text-align: center'><button type='button' onclick='getpoheader("+'"'+value.terima_no+'"'+''+");getpo("+'"'+value.terima_no+'"'+''+");' class='btn btn-success btn-xs'>Pilih</button></td>\
                    </tr>");
                });
                $('#list_bapb').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Data dari Ajax Error, Hubungi Konsultan');
            }
        });
    }

    function getpo(str) {
        var xhttp;
        if (str == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
            $('[id=harga1]').val('');
            $('[id=disc1]').val('');
            $('[id=discrp1]').val('');
            totalline(1);
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>pembelian_retur_log/getpo/" + str,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    for (i = 0; i <= data.length - 1; i++) {
                    }
                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah();
                        }
                        x = i + 1;
                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i].namabarang);
                        $('#kode' + x).append(option).trigger('change');
                        document.getElementById("kode" + x).value = data[i].kodebarang;
                        document.getElementById("qty" + x).value = separateComma(Number(data[i].qty_terima));
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = separateComma(data[i].price);
                        document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("discrp" + x).value = separateComma(Number(data[i].discountrp));
                        if (data[i].vat == 1) {
                            document.getElementById("tax" + x).checked = true;
                        }
                        totalline(x);
                    }

                }
            });
        }
        $('#list_bapb').modal('hide');
    }

    function getpoheader(str) {
        var xhttp;
        if (str == "") {} else {
            $.ajax({
                url: "<?php echo base_url(); ?>pembelian_retur_log/getpoheader/" + str,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="kodepu"]').val(str);
                    $('[name="sppn"]').val(data.sppn);
                    $('#gudang1').val(data.nm_gud);
                    $('#gudang').val(data.gudang);
                    $('#tanggal').val(data.terima_date1);
                }
            });
        }
    }

    window.onload = function() {
        document.getElementById('nomorbukti').focus();
    };
</script>


</body>

</html>