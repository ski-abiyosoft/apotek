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
            <span class="title-web">Farmasi <small>Retur Penjualan</small>
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
                <a class="title-white" href="<?php echo base_url(); ?>penjualan_retur">
                    Daftar Retur Penjualan
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
            <i class="fa fa-reorder"></i>*Transaksi Baru
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmPenjualan" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Retur
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">
                                   Info
								</a>
							</li-->

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <!-- <div class="col-md-6">
                                                        <div class="form-group">
                                                           <label class="col-md-3 control-label">No Kwitansi Obat</label>
													        <div class="col-md-6">
																<div class="input-group">
                                                              		<select id="cust" name="cust" class="form-control select2_el_resep" data-placeholder="Pilih..." onkeypress="return tabE(this,event)"></select>
															<span class="input-group-btn">
																	<a class="btn green" onclick="showpo()"><i class="fa fa-refresh"></i></a>
																</span>	
															 </div>	 
													        </div>
													    </div>
													</div> -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Resep</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select id="kwiobat" name="kwiobat" class="form-control select2_el_resep input-large" data-placeholder="Pilih..." onchange="getinfopasien(); getinfopasienresep()"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Rek Med</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" id="rekmed" name="rekmed" class="form-control input-large" readonly></input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No. Retur #</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control input-medium" placeholder="Auto" name="nomorbukti" id="nomorbukti" value="" readonly>

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
                                            <input type="text" class="form-control input-medium" placeholder="Auto" name="gudang" id="gudang" value="" readonly>
                                            <!-- <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo input-large" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
														 
														
														  </select>																														     -->

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
                                        <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                    </div>



                                </div>
                            </div>
                            <!-- <div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">No. Resep</label>
													        <div class="col-md-6">
														        <div class="input-group">
                                                                 <div id="listpo"></div>																														    													            
													          </div>
													        </div>

														</div>
													</div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Alasan</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-medium" name="alasan" id="alasan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">

                                <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                    <thead class="breadcrumb">
                                        <th class="title-white" width="5%" style="text-align: center">Check</th>
                                        <th class="title-white" width="5%" style="text-align: center">Kode Barang</th>
                                        <th class="title-white" width="35%" style="text-align: center">Nama Barang</th>
                                        <th class="title-white" width="5%" style="text-align: center">Kuantitas</th>
                                        <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                                        <th class="title-white" width="10%" style="text-align: center">Harga</th>
                                        <th class="title-white" width="5%" style="text-align: center"></th>
                                        <th class="title-white" width="10%" style="text-align: center">Diskon (Rp)</th>
                                        <th class="title-white" width="15%" style="text-align: center">Total Harga</th>

                                    </thead>

                                    <tbody>
                                        <!-- <tr>													   
                                                       <td width="35%">
														    <select name="kode[]" id="kode1" class="select2_el_farmasi_barang form-control input-largex" onchange="showbarangname(this.value, 1)">
															</select>												
														</td>
                                                       
                                                        <td width="30%" ><input name="nama[]"    id="namabarang0" type="text" class="form-control "  onkeypress="return tabE(this,event)" readonly></td>
														<td width="10%" ><input name="qty[]"    onchange="totalline(1);total()" value="1" id="qty1" type="text" class="form-control rightJustified"  ></td>
														<td width="10%" ><input name="sat[]"    id="sat1" type="text" class="form-control "  onkeypress="return tabE(this,event)"></td>
														<td width="15%" ><input name="harga[]"  onchange="totalline(1)" value="0" id="harga1" type="text" class="form-control rightJustified"  readonly></td>
														<td><a class="btn default" id="lupharga1" data-toggle="modal" href="#lupharga" onclick="getidharga(this.id)"><i class="fa fa-search"></i></a></td>
														<td width="10%"  ><input name="disc[]"   onchange="totalline(1)" value="0" id="disc1" type="text" class="form-control rightJustified "  ></td>
                                                        <td width="20%" ><input name="jumlah[]"  id="jumlah1"; type="text" class="form-control rightJustified" size="40%" onchange="total()"></td>
                                                       
								                      </tr> -->

                                    </tbody>
                                </table>
                                <!-- saya hilangkan -->
                                <!-- <div class="row">
														<div class="col-xs-9">
															<div class="wells">
																<button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
												                <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
															</div>															
														</div>
														
																										
													</div> -->


                            </div>
                        </div>



                    </div>
                    <!-- tab1-->

                    <div class="tab-pane" id="tab2">
                        <div class="row">
                            <div class="col-md-12">






                            </div>
                        </div>

                    </div>
                    <!--tab2-->



                </div>
                <!--tab-->

                <div class="row">
                    <div class="col-xs-8">
                        <div class="wells">


                            <button id="btnsimpan" type="button" onclick="savex()" class="btn blue">
                                <i class="fa fa-save"></i> <b>Proses</b>
                            </button>
                            <div class="btn-group">
                                <a class="btn red" href="<?php echo base_url('penjualan_retur/') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                            </div>


                            <div class="btn-group">
                                <!-- <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							 -->
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
    cektgl = 0;
    let date_n = "<?= date('Y-m-d') ?>";
    var cekuser = "<?= $this->session->userdata('user_level'); ?>";

    function tambah() {
        var x = document.getElementById('datatable').insertRow(idrow);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);
        var akun = "<select name='kode[]' id='kode" + idrow + "' onchange='showbarangname(this.value," + idrow +
            ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
        td1.innerHTML = akun;
        td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
            ");total();changeqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
        td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly>";
        td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
            ") value='0'  type='text' class='form-control rightJustified' readonly>";
        td5.innerHTML = "<a class='btn default' id=lupharga" + idrow +
            " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a>";
        td6.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
            ")' value='0'  type='text' class='form-control rightJustified'  >";
        td7.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
            " type='text' class='form-control rightJustified' size='40%'>";
        initailizeSelect2_farmasi_barang();
        idrow++;
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
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getbarang/" + str, true);
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
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getharga/" + str, true);
        xhttp.send();
    }

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $('#sat' + vid).val('');
        $('#harga' + vid).val(0);
        var customer = $('#cust').val();
        $.ajax({
            url: "<?php echo base_url(); ?>penjualan_retur/getinfobarang/?kode=" + str + "&cust=" + customer,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(formatCurrency1(data.hargajual));
                totalline(vid);
            }
        });

    }


    function post_harga(v1, v2) {
        id = document.getElementById("nopilihharga").value;
        document.getElementById("sat" + id).value = v2;
        document.getElementById("harga" + id).value = v1;
        totalline(id);
    }


    function getidharga(id) {
        var vid = id.substring(8);
        document.getElementById("nopilihharga").value = vid;
        var supp = document.getElementById("cust").value;
        var item = document.getElementById("kode" + vid).value;
        var param = supp + '~' + item;
        showharga(param);
    }


    function getnobukti() {
        var xhttp;

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nomorbukti").value = this.responseText;
            }
        };

        xhttp.open("GET", "<?php echo base_url(); ?>Penjualan_pesanan/getnobukti", true);
        xhttp.send();
    }

    // function save() {
    //     var noform = $('[name="cust"]').val();
    //     var tanggal = $('[name="tanggal"]').val();
    //     var gudang = $('[name="gudang"]').val();
    //     var total = $('#_vtotal').text();
    //     var cektgll = cektgl;
    //     var date_now = date_n;
    //     var cekuserr = cekuser;

    //     if (cekuserr == 3 || cekuserr == 2) {

    //         if (cektgll != date_now) {
    //             swal({
    //                 title: "GAGAL..",
    //                 html: "<p>TANGGAL RETUR HARUS SESUAI DENGAN TANGGAL TRANSAKSI</p>",
    //                 type: "error",
    //                 confirmButtonText: "OK"
    //             });
    //             return;
    //         }

    //     }


    //     // if(noform=="" || total=="" || total=="0.00" || gudang=="" || gudang==null){saya ganti
    //     if (total == "" || total == "0.00") {
    //         swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    //     } else {
    //         $.ajax({
    //             url: '<?php echo site_url('penjualan_retur/save/1') ?>',
    //             data: $('#frmPenjualan').serialize(),
    //             type: 'POST',

    //             success: function(data) {
    //                 document.getElementById("btnsimpan").disabled = true;
    //                 swal({
    //                     title: "RETUR PENJUALAN",
    //                     html: "<p> No. Retur   : <b>" + data + "</b> </p>" +
    //                         "Tanggal :  " + tanggal + "</b> </p>" +
    //                         "Total Biaya" + " " + total,
    //                     type: "info",
    //                     confirmButtonText: "OK"
    //                 }).then((value) => {
    //                     location.href = "<?php echo base_url() ?>penjualan_retur";
    //                 });

    //             },
    //             error: function(data) {
    //                 swal('RETUR PENJUALAN', 'Data gagal disimpan ...', '');


    //             }
    //         });
    //     }
    // }


    function savex() {
        var resepno = document.getElementById('kwiobat').value;
        var gudang = document.getElementById('gudang').value;

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;
        var loop = rowCount - 1;


        var noform = $('[name="cust"]').val();
        var rekmed = $('[name="rekmed"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var gudang = $('[name="gudang"]').val();
        var total = $('#_vtotal').text();
        var totalx = Number(parseInt(total.replaceAll(',', '')));
        var ppn = $('#_vppn').text();
        var cektgll = cektgl;
        var date_now = date_n;
        var cekuserr = cekuser;
        if (cekuserr == 3 || cekuserr == 2) {
            if (cektgll != date_now) {
                swal({
                    title: "GAGAL..",
                    html: "<p>TANGGAL RETUR HARUS SESUAI DENGAN TANGGAL TRANSAKSI</p>",
                    type: "error",
                    confirmButtonText: "OK"
                });
                return;
            }
        }

        if (total == "" || total == "0.00") {
            swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                // url: '<?= site_url(); ?>Penjualan_retur/save_one',
                url: "<?php echo site_url('Penjualan_retur/save_one/?vtotal=') ?>" + totalx,
                data: $('#frmPenjualan').serialize(),
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 1) {
                        var no = data.nomor;
                        for (var i = 1; i < rowCount; i++) {
                            var row = table.rows[i];
                            var cek = row.cells[0].children[0].checked;
                            if (cek == true) {
                                var kodebarang = row.cells[1].children[0].value;
                                var qtyx = row.cells[3].children[0].value;
                                var satuanx = row.cells[4].children[0].value;
                                var hargax = row.cells[5].children[0].value;
                                var dscx = row.cells[7].children[0].value;
                                var jumlahx = row.cells[8].children[0].value;
                                var qty = parseInt(qtyx.replaceAll(',', ''));
                                var harga = parseInt(hargax.replaceAll(',', ''));
                                var dsc = parseInt(dscx.replaceAll(',', ''));
                                var jumlah = parseInt(jumlahx.replaceAll(',', ''));
                                $.ajax({
                                    url: '<?= site_url(); ?>Penjualan_retur/save_multi/?kodebarang=' + kodebarang + '&qty=' + qty + '&harga=' + harga + '&resepno=' + resepno + '&loop=' + loop + '&dsc=' + dsc + '&jumlah=' + jumlah + '&satuan=' + satuanx + '&ppn=' + Number(parseInt(ppn.replaceAll(',', ''))) + '&nobukti=' + no,
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
                                                location.href = "<?php echo base_url() ?>penjualan_retur";
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
                            location.href = "<?php echo base_url() ?>penjualan_retur";
                        });
                    }
                }
            });
        }
    }

    // function save() {

    //     var resepno = document.getElementById('kwiobat').value;
    //     var gudang = document.getElementById('gudang').value;

    //     var table = document.getElementById('datatable');
    //     var rowCount = table.rows.length;
    //     var loop = rowCount - 1;


    //     var noform = $('[name="cust"]').val();
    //     var rekmed = $('[name="rekmed"]').val();
    //     var tanggal = $('[name="tanggal"]').val();
    //     var gudang = $('[name="gudang"]').val();
    //     var total = $('#_vtotal').text();
    //     var cektgll = cektgl;
    //     var date_now = date_n;
    //     var cekuserr = cekuser;

    //     if (cekuserr == 3 || cekuserr == 2) {

    //         if (cektgll != date_now) {
    //             swal({
    //                 title: "GAGAL..",
    //                 html: "<p>TANGGAL RETUR HARUS SESUAI DENGAN TANGGAL TRANSAKSI</p>",
    //                 type: "error",
    //                 confirmButtonText: "OK"
    //             });
    //             return;
    //         }

    //     }

    //     if (total == "" || total == "0.00") {
    //         swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    //     } else {
    //         for (var i = 1; i < rowCount; i++) {
    //             var row = table.rows[i];
    //             var cek = row.cells[0].children[0].checked;
    //             if (cek == true) {
    //                 var kodebarang = row.cells[1].children[0].value;
    //                 var qtyx = row.cells[3].children[0].value;
    //                 var satuanx = row.cells[4].children[0].value;
    //                 var hargax = row.cells[5].children[0].value;
    //                 var dscx = row.cells[7].children[0].value;
    //                 var jumlahx = row.cells[8].children[0].value;

    //                 var qty = parseInt(qtyx.replaceAll(',', ''));
    //                 var harga = parseInt(hargax.replaceAll(',', ''));
    //                 var dsc = parseInt(dscx.replaceAll(',', ''));
    //                 var jumlah = parseInt(jumlahx.replaceAll(',', ''));
    //                 $.ajax({
    //                     url: '<?php echo site_url('penjualan_retur/save/1?kodebarang=') ?>' + kodebarang + '&qty=' +
    //                         qty + '&harga=' + harga + '&resepno=' + resepno + '&loop=' + loop + '&dsc=' + dsc +
    //                         '&jumlah=' + jumlah + '&satuan=' + satuanx,
    //                     data: $('#frmPenjualan').serialize(),
    //                     type: 'POST',

    //                     success: function(data) {
    //                         console.log(data);
    //                         swal({
    //                             title: "RETUR PENJUALAN",
    //                             html: "<p> No. Retur   : <b>" + data + "</b> </p>" + "Tanggal :  " +
    //                                 tanggal + "</b> </p>" + "Total Biaya" + " " + total,
    //                             type: "info",
    //                             confirmButtonText: "OK"
    //                         }).then((value) => {
    //                             location.href = "<?php echo base_url() ?>penjualan_retur";
    //                         });

    //                     },
    //                     error: function(data) {
    //                         swal('RETUR PENJUALAN', 'Data gagal disimpan ...', '');
    //                     }
    //                 });
    //             }
    //         }
    //     }
    // }

    function cek() {
        console.log('HAIAIAIAIA');
    }

    function hapus() {
        if (idrow > 2) {
            var x = document.getElementById('datatable').deleteRow(idrow - 1);
            idrow--;
            total();
        }
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

    function changeqty(id) {
        var qty = $("#qty" + id).val();
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var kode = $("#kode" + id).val();
        var gudang = $('[name="gudang"]').val()
        var resepno = $('[name="kwiobat"]').val()
        $.ajax({
            url: "<?= site_url('Penjualan_retur/data_awal/?kode=') ?>" + kode + "&gudang=" + gudang + "&resepno=" + resepno,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var qty_awal = Number(data.qty).toFixed(0);
                var disc_awal = Number(data.discrp).toFixed(0);
                harga_peritem = disc_awal / qty_awal;
                disc_new = qty * harga_peritem;
                // console.log(harga_peritem)
                $("#disc" + id).val(separateComma(disc_new.toFixed(0)));
            }
        });
        total();
    }

    function total() {

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        tdiskon = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah1 = row.cells[3].children[0].value;
            harga1 = row.cells[5].children[0].value;
            diskon = row.cells[7].children[0].value;
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            row.cells[8].children[0].value = separateComma((jumlah1 * harga1 - diskon1).toFixed(0));

            tjumlah = tjumlah + eval(jumlah1 * harga1);
            diskon = eval(jumlah1 * harga1 - diskon1);
            tdiskon = tdiskon + diskon;
        }

        tppn = 0;

        var tot = tjumlah + tdiskon + tppn;
        // var ttl = tjumlah + tppn;
        // var new_tjumlah = separateComma(Number(tjumlah).toFixed(0));

        document.getElementById("_vsubtotal").innerHTML = tot;
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
        document.getElementById("_vppn").innerHTML = separateComma(tppn);
        document.getElementById("_vtotal").innerHTML = separateComma(tjumlah - tdiskon);

        if (tot > 0) {
            document.getElementById("btnsimpan").disabled = false;
            //  document.getElementById("btncetak").disabled=false;
        } else {
            document.getElementById("btnsimpan").disabled = true;
            //  document.getElementById("btncetak").disabled=true;
        }
        total_retur();
    }

    function totalline(id) {
        var table = document.getElementById('datatable');
        var row = table.rows[id];
        var hrg = $('#harga' + id).val();
        var harga = Number(parseInt(hrg.replaceAll(',', '')));
        var qty = $('#qty' + id).val();
        var disc = $('#disc' + id).val();
        jumlah = Number(parseInt(qty.replaceAll(',', ''))) * harga;
        //    diskon      = (row.cells[7].children[0].value/100)* jumlah;
        diskon = Number(parseInt(disc.replaceAll(',', '')));
        tot = jumlah - diskon;
        //    alert(tot);
        row.cells[8].children[0].value = separateComma(tot.toFixed(0));
        total();

    }

    $(document).ready(function() {
        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        for (var i = 1; i < rowCount; i++) {

            separateComma($('#qty' + i).val().toFixed(0));
            separateComma($('#harga' + i).val().toFixed(0));
            separateComma($('#jumlah' + i).val().toFixed(0));
            row.cells[8].children[0].value = separateComma((jumlah1 * harga1 - diskon1).toFixed(0));
        }
    });

    function showpo() {
        var xhttp;
        var str = $('[name="cust"]').val();

        if (str == "") {
            document.getElementById("listpo").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                document.getElementById("listpo").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getlistpo/" + str, true);
        xhttp.send();
    }

    function getpo() {
        var xhttp;
        var str = $('[name=kodesi]').val();
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
                url: "<?php echo base_url(); ?>penjualan_retur/getpo/" + str,
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

                        document.getElementById("qty" + x).value = data[i].qty;
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = formatCurrency1(data[i].price);
                        document.getElementById("disc" + x).value = data[i].discount;

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
                url: "<?php echo base_url(); ?>penjualan_retur/getpoheader/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    $('[name="sppn"]').val(data.sppn);
                }
            });
        }
    }

    function getinfopasien() {
        var xhttp;
        var str = $('[name=kwiobat]').val();
        $('#datatable tbody').empty();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>penjualan_retur/getdataresep/?noresep=" + str.trim(),
                type: "GET",
                //dataType: "JSON",		
                success: function(data) {
                    // console.log(data.price);
                    $('#datatable tbody').append(data);
                    // $("#harga1").val(formatCurrency1(Number(data.price)));
                    // totalline(1);
                    // total_bayar();
                    // total_net();
                }
            });
        }
    }


    function getinfopasienresep() {
        var xhttp;
        var str = $('[name=kwiobat]').val();
        $('#datatable tbody').empty();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>penjualan_retur/getdataresep2/?noresep=" + str.trim(),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#gudang').val(data.gudang);
                    $('#rekmed').val(data.rekmed);
                    $('#resepno').val(data.resepno);
                    cektgl = data.tgll;
                }
            });
        }
    }

    // function getuangmuka() { 
    //   var xhttp;      
    //   var str = $('[name=pasien]').val();
    //   $('#datatable_dp tbody').empty();  
    //   if(str==""){

    //   }  else  {	
    // 	$.ajax({
    //         url : "<?php echo base_url(); ?>kasir_konsul/getdatadp/?rekmed="+str.trim(),
    //         type: "GET",
    //         //dataType: "JSON",		
    //         success: function(data)
    //         {		               
    // 		  $('#datatable_dp tbody').append(data);
    // 		  total_bayar();
    // 		  total_net();
    // 		}
    // 	});	    
    //   }	  
    // }

    // Helper function:
    function upTo(el, tagName) {
        tagName = tagName.toLowerCase();

        while (el && el.parentNode) {
            el = el.parentNode;
            if (el.tagName && el.tagName.toLowerCase() == tagName) {
                return el;
            }
        }
        return null;
    }

    function deleteRow(el) {
        var row = upTo(el, 'tr')
        if (row) row.parentNode.removeChild(row);
    }

    function total_retur() {

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        subtotal = 0;
        tdiskon = 0;
        ppn = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            qty = row.cells[3].children[0].value;
            harga = row.cells[5].children[0].value;
            jumlah = row.cells[8].children[0].value;
            diskon = row.cells[7].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

            if (row.cells[0].children[0].checked == true) {
                tjumlah = tjumlah + eval(jumlah1);
                tdiskon = tdiskon + eval(diskon1);
                subtotal = subtotal + eval(qty * harga);
                ppn = ppn + eval(jumlah1) * 11 / 100;
            }

        }


        var tot = tjumlah + tdiskon + tppn;
        tjumlah2 = (tjumlah - tdiskon) / 1.11;
        tppn2 = (tjumlah2) * 11 / 100;

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
            //  document.getElementById("btncetak").disabled=false;
        } else {
            document.getElementById("btnsimpan").disabled = true;
            //  document.getElementById("btncetak").disabled=true;
        }
    }




    window.onload = function() {
        document.getElementById('nomorbukti').focus();
        total();
    };
</script>


</body>

</html>