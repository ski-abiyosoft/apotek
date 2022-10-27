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
            <span class="title-web">Logistik <small>Retur Pembelian</small>
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
                <a href="<?php echo base_url();?>pembelian_retur_log">
                    Daftar Retur Pembelian
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
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
        <!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->

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
                                            <select id="supp" name="supp" class="form-control  select2_el_vendor"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <!-- onchange="showpo()"> -->
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Retur #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti"
                                                id="nomorbukti" value="<?php echo $nomor;?>" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. BAPB</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select name="kodepu" id="kodepu"
                                                    class="form-control input-medium select2me"
                                                    onchange="getpoheader();getpo()">
                                                    <option value="">---Tanpa BAPB---</option>
                                                </select>
                                                <!-- <span class="input-group-btn">
																	<a class="btn green" onclick="getpoheader();getpo()"><i class="fa fa-refresh"></i></a>
																</span>	 -->

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
                                            <input id="tanggal" name="tanggal" class="form-control input-medium"
                                                type="date" value="<?php echo date('Y-m-d');?>" />

                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                        <div class="col-md-6">
                                            <input id="gudang" name="gudang" class="form-control" readonly>
                                            <input class="form-control" type="hidden" id="gudang1" name="gudang1">

                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr>
                                                <th class="title-white" width="35%" style="text-align: center">Nama
                                                    Barang</th>
                                                <th class="title-white" width="10%" style="text-align: center">Kuantitas
                                                </th>
                                                <th class="title-white" width="10%" style="text-align: center">Satuan
                                                </th>
                                                <th class="title-white" width="15%" style="text-align: center">Harga
                                                </th>
                                                <th class="title-white" width="5%" style="text-align: center"></th>
                                                <th class="title-white" width="10%" style="text-align: center">Diskon
                                                    (%)</th>
                                                <th class="title-white" width="15%" style="text-align: center">Total
                                                    Harga</th>

                                            </tr>
                                            <thead>

                                            <tbody>
                                                <tr>
                                                    <td width="35%">
                                                        <select name="kode[]" id="kode1"
                                                            class="select2_el_log_barang form-control"
                                                            onchange="showbarangname(this.value, 1)">
                                                            <option value="">--- Pilih Barang ---</option>
                                                        </select>

                                                        <!-- <select name="kode[]" id="kode1"
                                                            class="form-control input-largex"
                                                            onchange="showbarangname(this.value, 1)">

                                                        </select> -->
                                                    </td>
                                                    <!-- <td  >
															<input name="disc[]"   onchange="totalline(1);total()" value="0" id="disc1" type="text" class="form-control rightJustified "  >
														</td>
                                                        <td>
															<input type="hidden" type="checkbox" name="tax[]" id="tax1" class="form-control" onchange="totalline(1);total()">
														</td> -->

                                                    <td>
                                                        <input name="jumlah[]" id="jumlah1" type="text"
                                                            class="form-control rightJustified" size="40%" readonly>
                                                    </td>
                                                    <td width="10%"><input name="qty[]" onchange="totalline(1);total()"
                                                            value="1" id="qty1" type="text"
                                                            class="form-control rightJustified"></td>
                                                    <td width="10%"><input name="sat[]" id="sat1" type="text"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="15%"><input name="harga[]" onchange="totalline(1)"
                                                            value="0" id="harga1" type="text"
                                                            class="form-control rightJustified"></td>
                                                    <td><a class="btn default" id="lupharga1" data-toggle="modal"
                                                            href="#lupharga" onclick="getidharga(this.id)"><i
                                                                class="fa fa-search"></i></a></td>
                                                    <td width="5%"><input name="disc[]" onchange="totalline(1)"
                                                            value="0" id="disc1" type="text"
                                                            class="form-control rightJustified "></td>
                                                    <td width="15%"><input name="jumlah[]" id="jumlah1" ; type="text"
                                                            class="form-control rightJustified" size="40%"
                                                            onchange="total()"></td>

                                                </tr>

                                            </tbody>
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
                                <div class="col-md-12">
                                    <div class="row">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">PPN</label>
                                                <div class="col-md-4">
                                                    <select name="sppn" id="sppn"
                                                        class="form-control select2me input-small" onchange="total()">
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
                                                    <textarea row="3" class="form-control input-xlarge" placeholder=""
                                                        name="keterangan" id="keterangan" maxlength="100"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                        <!--tab2-->



                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-9">
                            <div class="wells">


                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>

                                <div class="btn-group">
                                    <button type="button" class="btn green"
                                        onclick="this.form.reset();location.reload();"><i
                                            class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('Pembelian_retur_log/')?>"><i
                                            class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                        id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-3 invoice-block">
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
    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ")' class='select2_el_log_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly >";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified' readonly>";
    td5.innerHTML = "<a class='btn default' id=lupharga" + idrow +
        " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a> ";
    td6.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td7.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%'>";
    initailizeSelect2_log_barang();
    idrow++;


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
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur_log/getbarangname/" + str, true);
        xhttp.send();
    }

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?php echo base_url();?>pembelian_retur_log/getinfobarang/" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(data.hargabeli);
                totalline(vid);
            }
        });


    }


    // function showbarangname(str, id) {

    //     var xhttp;
    //     var vid = id;
    //     $.ajax({
    //         url: "<?php echo base_url();?>logistik_po/getinfobarang/?kode=" + str,
    //         type: "GET",
    //         dataType: "JSON",
    //         success: function(data) {
    //             $('#sat' + vid).val(data.satuan1);
    //             $('#harga' + vid).val(formatCurrency1(data.hargabeli));
    //             totalline(vid);
    //         }
    //     });


    // }


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
        var noform = $('[name="nomorbukti"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var total = $('#_vtotal').text();
        if (noform == "" || total == "" || total == "0.00") {
            // if (noform == "") {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('pembelian_retur_log/save/1')?>',
                data: $('#frmpembelian').serialize(),
                type: 'POST',

                success: function(data) {
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + noform + "</b> </p>" +
                            "Tanggal :  " + tanggal + "Total:" + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url()?>pembelian_retur_log";
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

        var cppn = document.getElementById("sppn").value;
        if (cppn == "Y") {
            tppn = (tjumlah - tdiskon) * 0.1;
        } else {
            tppn = 0;
        }


        document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tjumlah);
        document.getElementById("_vdiskon").innerHTML = formatCurrency1(tdiskon);
        document.getElementById("_vppn").innerHTML = formatCurrency1(tppn);
        document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah - tdiskon + tppn);


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



    // function showpo() {
    //     var xhttp;
    //     var str = $('[name="supp"]').val();

    //     if (str == "") {
    //         document.getElementById("kodepu").innerHTML = "";
    //         return;
    //     }
    //     xhttp = new XMLHttpRequest();
    //     xhttp.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //             document.getElementById("kodepu").innerHTML = this.responseText;
    //         }
    //     };
    //     xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur_log/getlistpo/" + str, true);
    //     xhttp.send();
    // }

    function getpo() {
        var xhttp;
        var str = $('[name=kodepu]').val();
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
                url: "<?php echo base_url();?>pembelian_retur_log/getpo/" + str,
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

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');

                        document.getElementById("qty" + x).value = data[i].qty_terima;
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = data[i].price;
                        document.getElementById("disc" + x).value = data[i].discount;

                    }

                }
            });
        }
    }

    function getpoheader() {
        var xhttp;
        var str = $('[name=kodepu]').val();
        if (str == "") {} else {
            $.ajax({
                url: "<?php echo base_url();?>pembelian_retur_log/getpoheader/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    $('[name="sppn"]').val(data.sppn);
                    $('#gudang').val(data.nm_gud);
                    $('#gudang1').val(data.gudang);
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