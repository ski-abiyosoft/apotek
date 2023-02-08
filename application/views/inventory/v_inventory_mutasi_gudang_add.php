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
            <span class="title-web">APOTEK <small>Mutasi Barang Antar Gudang</small>
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
                <a class="title-white" href="<?php echo base_url(); ?>inventory_mutasi_gudang">
                    Daftar Mutasi
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Mutasi Antar Gudang
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-4">
                                <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                            </div>



                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Transfer No.</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" placeholder="Otomatis" name="nomorbukti" class="form-control" value="<?= $nomor; ?>" readonly>
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
                                <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo" onchange="getpermohonan()" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                </select>
                                <!-- <select type="text" name="gudang_asal" id="gudang_asal" class="form-control"
                                    readonly></select> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. Permohonan</label>
                            <div class="col-md-9">
                                <select id="nomohon" name="nomohon" class="form-control select2_el_farmasi_permohonan" data-placeholder="Pilih..." onchange="getgud()" onkeypress="return tabE(this,event)">
                                </select>
                            </div>
                        </div>
                    </div>



                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gudang Tujuan</label>
                            <div class="col-md-9">
                                <select id="gudang_tujuan" name="gudang_tujuan" class="form-control select2_el_farmasi_depo" onchange="getpermohonan()" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                </select>
                                <!-- <select type="text" name="gudang_tujuan" id="gudang_tujuan" class="form-control"
                                    readonly></select> -->
                            </div>



                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="ket" id="ket">

                            </div>



                        </div>
                    </div>



                </div>

                <div class="row">
                    <div class="col-md-12">

                        <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                            <thead>
                                <tr>
                                    <th width="30%" style="text-align: center">Kode/Nama Barang</th>
                                    <th width="10%" style="text-align: center">Kuantitas</th>
                                    <th width="10%" style="text-align: center">Satuan</th>
                                    <th width="10%" style="text-align: center">Harga</th>
                                    <th width="15%" style="text-align: center">Total</th>
                                    <th width="10%" style="text-align: center">Tgl. Kadaluarsa</th>
                                    <th width="20%" style="text-align: center">Keterangan</th>
                                </tr>
                                <thead>
                                <tbody>
                                    <tr>
                                        <td width="30%">
                                            <select name="kode[]" id="kode1" class="select2_el_farmasi_baranggud form-control kodeb" onchange="showbarangname(this.value, 1); cekqty(1)"></select>
                                            <input type='hidden' name='hidekode[]' id='hidekode1' onchange='showbarangname(this.value,"1"); cekqty(1)' value=''>
                                        </td>
                                        <td width="10%"><input name="qty[]" onchange="totalline(1); cekqty(1)" value="1" id="qty1" type="text" class="form-control rightJustified"></td>
                                        <td width="10%"><input name="sat[]" id="sat1" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly></td>
                                        <td width="10%"><input name="harga[]" onchange="totalline(1)" id="harga1" type="text" class="form-control rightJustified" onkeypress="return tabE(this,event)" readonly></td>
                                        <td width="15%"><input name="total[]" onchange="totalline(1)" id="total1" type="text" class="form-control rightJustified" onkeypress="return tabE(this,event)" readonly></td>
                                        <td width="10%"><input name="expire[]" id="expire1" type="date" class="form-control " onkeypress="return tabE(this,event)"></td>
                                        <td width="10%"><input name="note[]" id="note1" type="text" class="form-control " onkeypress="return tabE(this,event)"></td>
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
                                    <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                    <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                </div>
                            </div>


                        </div>


                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well">


                            <button type="button" onclick="savex()" class="btn blue" id="savec"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" onclick="location.href='/inventory_mutasi_gudang'" class="btn red"><i class="fa fa-times"></i> Tutup</button>
                            <!-- <div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
									</div> -->
                            <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
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
        initailizeSelect2_farmasi_baranggud(null);
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
        var td7 = x.insertCell(6);

        var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
            "); cekqty(" + idrow + ")' class='select2_el_farmasi_baranggud form-control' ></select><input type='hidden' name='hidekode[]' id='hidekode" +
            idrow + "' onchange='showbarangname(this.value," + idrow + ")' value=''>";
        td1.innerHTML = akun;
        td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
            "); cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
        td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly>";
        td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " type='text' class='form-control rightJustified' readonly>";
        td5.innerHTML = "<input name='total[]'    id=total" + idrow +
            " type='text' class='form-control rightJustified' readonly>";
        td6.innerHTML = "<input name='expire[]'    id=expire" + idrow + " type='date' class='form-control' >";
        td7.innerHTML = "<input name='note[]'    id=note" + idrow + " type='text' class='form-control' >";
        var gud = $('[name="gudang_asal"]').val();
        initailizeSelect2_farmasi_baranggud(gud);
        total();
        idrow++;
    }

    function cekqty(id) {
        var kode = $('#kode' + id).val();
        var qty = $('#qty' + id).val();
        var gudang = $('[name="gudang_asal"]').val();
        $.ajax({
            url: '<?= site_url() ?>inventory_mutasi_gudang/cekqty/?kode=' + kode + '&qty=' + qty + '&gudang=' + gudang,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                if (qty > Number(parseInt(data.saldoakhir.replaceAll(',', '')))) {
                    swal({
                        title: "KUANTITAS",
                        html: "Tidak boleh lebih besar dari saldo akhir",
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        $('#kode' + id).empty();
                        $('#sat' + id).val('');
                        $('#harga' + id).val('');
                        var vtotalx = $('#vtotal').val();
                        var vtotal = Number(parseInt(vtotalx.replaceAll(',', '')));
                        var totalx = $('#total' + id).val();
                        var total = Number(parseInt(totalx.replaceAll(',', '')));
                        var newtotal = vtotal - total;
                        $('#vtotal').val(newtotal);
                        $('#total' + id).val('');
                    });
                }
            }
        });
    }

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?php echo base_url(); ?>farmasi_po/getinfobarang/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(formatCurrency1(data.hargajual));
                totalline(vid);
            }
        });
    }

    function getpermohonan() {
        initailizeSelect2_farmasi_permohonan($("#gudang_asal").val(), $("#gudang_tujuan").val());
        initailizeSelect2_farmasi_baranggud($("#gudang_asal").val());
    }

    function getgud() {
        var nomor = $("#nomohon").val();
        $.ajax({
            url: "<?php echo base_url(); ?>inventory_mutasi_gudang/getgudang/" + nomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $("#gudang_asal").html("<option value='" + data.keid + "'>" + data.ke + "</option>");
                $("#gudang_tujuan").html("<option value='" + data.dariid + "'>" + data.dari + "</option>");
                $("#ket").val(data.keterangan).prop("readonly", true);
                initailizeSelect2_farmasi_baranggud($("#gudang_asal").val());
                getpermohonandetil(data.keid);
            }
        });
    }

    function getpermohonandetil(param) {
        var nomor = $('#nomohon').val();
        if (nomor == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
            $('[id=harga1]').val('');
            $('[id=total1]').val('');
            totalline(1);
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>inventory_mutasi_gudang/getpermohonan/" + nomor,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    for (i = 0; i <= data.length - 1; i++) {
                        hapus(data.lengt);
                    }

                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah(data.lengt);
                        }

                        x = i + 1;

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');
                        $("#hidekode" + x).val(data[i].kodebarang);
                        $("#qty" + x).val(data[i].qtymohon.split(".00").join(""));
                        $("#sat" + x).val(data[i].satuan).prop("readonly", true);
                        $("#harga" + x).val(data[i].harga).prop("readonly", true);
                        $("#total" + x).val(data[i].totalharga).prop("readonly", true);

                        checkstock(param, data[i].kodebarang);
                    }

                }
            });
        }
    }

    function savex() {
        var gudang_asal = $('[name="gudang_asal"]').val();
        var gudang_tujuan = $('[name="gudang_tujuan"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var kodeb = $(".kodeb").length;
        var ttl = $('#vtotal').val();
        if (gudang_asal == "" || gudang_tujuan == "" || total == "" || total == "0.00") {
            swal('MUTASI GUDANG', 'gudang belum diisi ...', '');
        } else {
            $.ajax({
                url: "<?= site_url('inventory_mutasi_gudang/save_one') ?>",
                data: $('#frmpenjualan').serialize(),
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 1) {
                        var table = document.getElementById('datatable');
                        rowCount = table.rows.length;
                        for (i = 1; i < rowCount - 1; i++) {
                            var kode = $('#kode' + i).val();
                            var qty = $('#qty' + i).val();
                            var sat = $('#sat' + i).val();
                            var hargax = $('#harga' + i).val();
                            var harga = Number(parseInt(hargax.replaceAll(',', '')));
                            var totalx = $('#total' + i).val();
                            var total = Number(parseInt(totalx.replaceAll(',', '')));
                            var expire = $('#expire' + i).val();
                            var note = $('#note' + i).val();
                            // console.log('kode : '+kode+', qty : '+qty+', sat : '+sat+', harga : '+harga+', total : '+total+', expire : '+expire+', note : '+note)
                            $.ajax({
                                url: "<?= site_url('inventory_mutasi_gudang/save_multi/?kode='); ?>" + kode + '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&total=' + total + '&expire=' + expire + '&note=' + note + '&nomorbukti=' + data.nomorbukti + '&keterangan=' + data.keterangan,
                                data: $('#frmpenjualan').serialize(),
                                type: 'POST',
                                dataType: 'JSON',
                            });
                        }
                        swal({
                            title: "MUTASI GUDANG",
                            html: "No. Mutasi : " + data.nomorbukti + "</b> </p>Tanggal : " + tanggal + "</b> </p>Total : " + ttl,
                            type: "info",
                            confirmButtonText: "OK"
                        }).then((value) => {
                            location.href = "<?php echo base_url() ?>inventory_mutasi_gudang";
                        });
                    }
                }
            });
        }
    }

    function save() {
        var gudang_asal = $('[name="gudang_asal"]').val();
        var gudang_tujuan = $('[name="gudang_tujuan"]').val();
        var total = $('#vtotal').val();
        var tanggal = $('[name="tanggal"]').val();
        var kodeb = $(".kodeb").length;
        if (gudang_asal == "" || gudang_tujuan == "" || total == "" || total == "0.00") {
            swal('MUTASI GUDANG', 'gudang belum diisi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('inventory_mutasi_gudang/save/1') ?>',
                data: $('#frmpenjualan').serialize(),
                type: 'POST',
                success: function(data) {
                    swal({
                        title: "MUTASI GUDANG",
                        html: "No. Mutasi   : " + data + "</b> </p>" +
                            "Tanggal :  " + tanggal + "</b> </p>" + "Total:" + " " + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>inventory_mutasi_gudang";
                    });
                },
                error: function(data) {
                    swal('MUTASI GUDANG', 'Data gagal disimpan ...', '');
                }
            });
        }
    }

    function hapus() {
        if (idrow > 2) {
            var x = document.getElementById('datatable').deleteRow(idrow - 1);
            idrow--;
        }
    }




    // function showpo() {
    //   var xhttp;
    //   var str = $('[name="cust"]').val(); 

    //   if (str == "") {
    //     document.getElementById("kodeso").innerHTML = "";
    //     return;
    //   }
    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("kodeso").innerHTML = this.responseText;
    //     }
    //   };
    //   xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pengiriman/getlistpo/"+str, true);  
    //   xhttp.send();
    // }

    // function getpo() { 
    //   var xhttp;      
    //   var str = $('[name=kodeso]').val();
    //   if(str==""){
    // 	hapus();
    // 	$('[id=kode1]').val('');
    // 	$('[id=qty1]').val('');
    // 	$('[id=sat1]').val('');
    //   }  else  {
    // 	$.ajax({
    //         url : "<?php echo base_url(); ?>penjualan_pengiriman/getpo/"+str,
    //         type: "GET",
    //         dataType: "JSON",

    //         success: function(data)
    //         {		            
    // 		    for(i=0; i <= data.length-1; i++){	
    // 			hapus();
    // 			}

    //             for(i=0; i <= data.length-1; i++){		
    // 			  if(i>0){
    // 		       tambah();
    // 			  }

    // 			  x = i+1;

    // 			  var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i].namabarang);
    //               $('#kode'+x).append(option).trigger('change');

    // 			  document.getElementById("qty"+x).value=data[i].sisa;		    
    // 			  document.getElementById("sat"+x).value=data[i].satuan;		    
    // 			}




    // 		}
    // 	});	    
    //   }	
    // }

    /*window.onload = function(){
            document.getElementById('nomorbukti').focus();
    };*/


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

        tjumlah = 0;
        for (var i = 1; i < rowCount - 1; i++) {
            var row = table.rows[i];

            ztotal = row.cells[4].children[0].value;

            var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1);

        }

        document.getElementById("vtotal").value = separateComma(tjumlah);


    }

    function checkstock(param1, param2) {
        $.ajax({
            url: "/farmasi_pbb/checkstock/?kode=" + param2 + "&gudang=" + param1,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 0) {
                    swal({
                        title: "Kesalahan 1",
                        html: "Terdapat Barang Yang Kosong",
                        type: "error",
                        confirmButtonText: "Ok"
                    }).then((value) => {
                        $("#savec").prop("disabled", true).attr("onclick", "savex()");
                    });
                } else
                if (data.stock == 0) {
                    swal({
                        title: "Kesalahan 2",
                        html: "Stock Tidak Cukup",
                        type: "error",
                        confirmButtonText: "Ok"
                    }).then((value) => {
                        $("#savec").prop("disabled", true).attr("onclick", "savex()");
                    });
                } else {
                    $("#savec").prop("disabled", false).attr("onclick", "savex()");
                }
            }
        });
    }
</script>



</body>

</html>