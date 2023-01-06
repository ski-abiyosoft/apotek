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
            <span class="title-web"><?= $modul; ?> <small><small><?= $submodul; ?></small>
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
                <a class="title-white" href="<?php echo base_url(); ?><?= $url; ?>">
                    Daftar <?= $submodul; ?>
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
                                <?= $submodul; ?>
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">
                                   Biaya Lain-Lain
								</a>
							</li-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <!-- <div class="col-md-6">
														<div class="form-group">
															<label class="col-md-3 control-label">Nama Vendor
																<font color="red">*</font>
															</label>
															<div class="col-md-9">
																	<input type="text" class="" id="wil" name="wil" />
																	
															</div>

														</div>
														<div class="form-group">
															<div class="col-md-9">
																<input type="text" class="form-control" id="nm_wil" name="nm_wil" readonly />
															</div>
														</div>
														
													</div> -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Pemasok
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="supp" name="supp" class="form-control select2_el_vendor" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang / Depo
                                        </label>
                                        <div class="col-md-9">
                                            <!-- <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" onchange="cekbarang(1)"> -->
                                            <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal PO</label>
                                        <div class="col-md-4">

                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Ref
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" name="noref" id="noref" value="">
                                        </div>
                                        <label class="col-md-2 control-label">Inter PO</label>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="ipo" class="form-control" id="ipo">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal Kirim</label>
                                        <div class="col-md-4">
                                            <input id="tanggalkirim" name="tanggalkirim" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kurs</label>
                                        <div class="col-md-4">
                                            <select name="kurs" id="kurs" class="form-control">
                                                <option value="IDR">IDR</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </div>
                                        <label class="col-md-2 control-label">Rate</label>
                                        <div class="col-md-3">
                                            <input type="text" name="rate" data-type="currency" class="form-control" value="0">
                                        </div>

                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Dikirim Via</label>
                                        <div class="col-md-5">
                                            <select name="dikirimvia" id="dikirimvia" class="form-control">
                                                <option value="Call">Call</option>
                                                <option value="Reff PBF">Reff PBF</option>
                                                <option value="WA">WA</option>
                                                <option value="SMS">SMS</option>
                                                <option value="Email">Email</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor PO #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?= $nomorpo; ?>" readonly>
                                        </div>

                                    </div>
                                </div>


                            </div>




                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">

                                        <thead class="breadcrumb">
                                            <th class="title-white" width="5%" style="text-align: center">DEL</th>
                                            <th class="title-white" width="35%" style="text-align: center">Nama Barang +
                                                Qty Stock All Depo/Unit</th>
                                            <th class="title-white" width="15%" style="text-align: center">Kuantitas
                                            </th>
                                            <th class="title-white" width="15%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="15%" style="text-align: center">Harga</th>
                                            <!-- <th class="title-white" width="10%" style="text-align: center" hidden="true">Diskon</th>
														<th class="title-white" width="5%" style="text-align: center" hidden="true">Tax</th> -->
                                            <th class="title-white" width="15%" style="text-align: center">Total Harga
                                            </th>

                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button type='button' onclick=hapusBarisIni(1) class='btn red'><i class='fa fa-trash-o'>
                                                </td>
                                                <td>
                                                    <!-- <select name="kode[]" id="kode1" onclick="cekbarangx(1)" class="form-control input-largex" onchange="showbarangname(this.value, 1)"></select> -->
                                                    <select name="kode[]" id="kode1" class=" form-control input-largex" onchange="showbarangname(this.value, 1)"></select>
                                                </td>

                                                <!-- <td>
														    <select name="kode1" id="kode1" class="" style="width:300px;" onchange="showbarangname(this.value, 1)">
															 
															</select>												
														</td> -->

                                                <td>
                                                    <input name="qty[]" onchange="totalline(1);total()" value="1" id="qty1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td>
                                                    <input name="sat[]" id="sat1" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly>
                                                </td>
                                                <td>
                                                    <input name="harga[]" onchange="totalline(1);total();cekharga(1)" value="0" id="harga1" type="text" class="form-control rightJustified">
                                                </td>
                                                <!-- <td  >
															<input name="disc[]"   onchange="totalline(1);total()" value="0" id="disc1" type="text" class="form-control rightJustified "  >
														</td>
                                                        <td>
															<input type="hidden" type="checkbox" name="tax[]" id="tax1" class="form-control" onchange="totalline(1);total()">
														</td> -->

                                                <td>
                                                    <input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" readonly>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                                <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                            </div>
                                        </div>


                                    </div>


                                </div>
                            </div>



                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">

                            <div class="row">
                                <div class="col-xs-9">

                                </div>
                            </div>
                        </div>
                        <!-- tab2-->

                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-9">
                            <div class="wells">


                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                    <b>Simpan</b></button>

                                <div class="btn-group">
                                    <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>

                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('farmasi_po') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-3 invoice-block">
                            <div class="well">
                                <table id="tabeltotal">
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
$this->load->view('template/currency');
?>

<!-- -- Tambahan easyui -- -->
<script type="text/javascript">
    var idrow = 2;
    var rowCount;
    var arr = [1];
    // cekbarang(1);
    convert_currency2();

    function tambah() {
        var table = document.getElementById('datatable');
        rowCount = table.rows.length;
        arr.push(idrow);

        var x = document.getElementById('datatable').insertRow(rowCount);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var button = "<button type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'>"
        // var td6=x.insertCell(5);
        // var td7=x.insertCell(6);
        // cekbarang(idrow);
        // alert(idrow);
        td1.innerHTML = button;
        var akun = "<select name='kode[]' id=kode" + idrow + " class='select2_el_farmasi_barangdata form-control input-largex' onchange='showbarangname(this.value, " + idrow + ")'></select>";
        td2.innerHTML = akun;

        td3.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1'  type='text' class='form-control rightJustified' >";

        td4.innerHTML = "<input name='sat[]' id=sat" + idrow + " type='text' class='form-control'  readonly>";

        td5.innerHTML = "<input name='harga[]' id=harga" + idrow + " onchange='totalline(" + idrow + ");cekharga("+idrow+")' value='0'  type='text' class='form-control rightJustified'>";

        /*td5.innerHTML="<input name='disc[]' id=disc"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified'  >";
        
        td6.innerHTML="<input type='checkbox' name='tax[]' id=tax"+idrow+" onchange='totalline("+idrow+")' class='form-control'>";
        */

        td6.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";


        // initailizeSelect2_farmasi_barang2();
        // initailizeSelect2_farmasi_baranggud(gud);
        initailizeSelect2_farmasi_barangdata();
        // cekbarang(idrow);
        idrow++;

        convert_currency2();
    }

    function changeharga(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        totalline(id);
        $("#harga" + id).val(separateComma(harga));
    }


    // function listbarang(str) {
    // 	// alert(str); 
    // 	var option = "<option value=''>--- Pilih Poli ---</option>"
    //     $('#kode1').empty();	
    // 	$.ajax({
    // 		url: '<?php echo base_url() ?>farmasi_po/getlistbarang/'+str,
    // 		type: 'GET',
    // 		dataType: 'json',
    // 		success:function(data){				
    // 			if(data.message == "Success"){						
    // 				$.each(data.data, function(index, val) {
    // 					option += "<option value='"+val.text+"'>"+val.text+"</option>";
    // 				});

    // 				$('#kode1').html(option);
    // 			}
    // 		}
    // 	});	


    // }

    function showbarangname(str, id) {

        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?php echo base_url(); ?>farmasi_po/getinfobarang/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#kode' + id).val(data.kodebarang);
                $('#sat' + id).val(data.satuan1);
                $('#harga' + id).val(formatCurrency1(data.hargabeli));
                totalline(id);
            }
        });


    }

    function save() {
        var tanggal = $('[name="tanggal"]').val();
        var noref = $('#noref').val();
        var supp = $('#supp').val();
        var tanggal = $('[name="tanggal"]').val();
        var nomor = $('[name="nomorbukti"]').val();
        var total = $('#_vtotal').text();
        var gud = $('#gudang').val();
        if (document.getElementById('ipo').checked == true) {
            ipo = 1;
        } else {
            ipo = 0;
        }


        if (supp == '' || supp == null) {
            swal({
                title: "Nama Vendor",
                html: " Tidak Boleh Kosong .!!!",
                type: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        // if (gud == '' || gud == null) {
        //     swal({
        //         title: "GUDANG",
        //         html: " Harus Di Pilih .!!!",
        //         type: "error",
        //         confirmButtonText: "OK"
        //     });
        //     return;
        // }

        if (noref == '' || noref == null) {
            swal({
                title: "No Ref",
                html: " Tidak Boleh Kosong .!!!",
                type: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        if (nomor == "" || total == "0.00" || total == "") {
            swal('PURCHASE ORDER', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            swal({
                title: 'HARGA',
                html: "Apakah Harga Sudah Sesuai ?",
                type: 'question',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-success',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function() {
                $.ajax({
                    url: "<?php echo site_url('farmasi_po/ajax_add/1?ipo=') ?>" + ipo,
                    data: $('#frmpembelian').serialize(),
                    type: 'POST',
                    success: function(data) {
                        // console.log(data)
                        data1 = JSON.parse(data);
                        swal({
                            title: "PURCHASE ORDER",
                            html: "<p> No. Bukti   : <b>" + data1.nomor + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <br><b>" + total + "</b>",
                            type: "info",
                            confirmButtonText: "OK"
                        }).then((value) => {
                            location.href = "<?php echo base_url() ?>farmasi_po";
                        });
    
                    },
                    error: function(data) {
                        swal('PESANAN PEMBELIAN', 'Data gagal disimpan ...', '');
                    }
                });
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
        var x = document.getElementById('datatable').deleteRow(arr.indexOf(param) + 1);
        arr.splice(arr.indexOf(param), 1);

        rowCount--;
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
        tdiskon = 0;
        tppn = 0;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[5].children[0].value;


            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1 * harga1);

            // diskon      = eval((diskon1/100)*jumlah1*harga1);

            // tdiskon  = tdiskon + diskon;

            // if(document.getElementById('tax'+i).checked==true){
            // 	tppn = tppn + (eval(jumlah1*harga1)*0.1);
            // } 



        }
        // document.getElementById("_vdiskon").innerHTML = formatCurrency1(tdiskon);
        //tppn = 0;
        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
        document.getElementById("_vppn").innerHTML = separateComma(tppn);
        document.getElementById("_vtotal").innerHTML = separateComma(tjumlah - tdiskon + tppn);


    }

    function totalline(id) {
        var table = document.getElementById('datatable');
        var row = table.rows[arr.indexOf(id) + 1];
        var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
        jumlah = row.cells[2].children[0].value * harga;
        //    diskon       = (row.cells[4].children[0].value/100)* jumlah;
        //    tot          = jumlah;


        //    if(document.getElementById('tax'+id).checked==true){	  
        // 	  tot = tot*1.1;
        //    } 

        row.cells[5].children[0].value = formatCurrency1(jumlah);
        total();



    }
</script>


</body>

</html>