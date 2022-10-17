<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Logistik <small>Pemakaian Barang</small>
        </h3>

        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home" style="color:#fff"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
                <i class="fa fa-angle-right" style="color:#fff"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url();?>logistik_keluar">Daftar Pemakaian Barang</a>
                <i class="fa fa-angle-right" style="color:#fff"></i>
            </li>
            <li>
                <a class="title-white" href="">Entri Pemakaian</a>
            </li>
        </ul>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder fa-fw"></i>&nbsp;Data Baru
        </div>
    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">

                <br />

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-4">
                                <input id="tanggal" name="tanggal" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pemakaian No.</label>
                            <div class="col-md-6">
                                <input type="hidden" name="hidenomorbukti" value="<?= $nomor ?>">
                                <input type="text" placeholder="Otomatis" name="nomorbukti" class="form-control"
                                    value="Auto" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gudang</label>
                            <div class="col-md-9">
                                <!-- <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo"
                                    onchange="getgudang()" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)"></select> -->


                                <select id="gudang_asal" name="gudang_asal"
                                    class="form-control select2_el_logistik_depo" data-placeholder="Pilih..."
                                    onchange="getgudang()" onkeypress="return tabE(this,event)"></select>

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

                <br />

                <!-- List Item -->
                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable"
                            class="table table-hoverx table-stripedx table-bordered table-condensed table-scrollable">
                            <thead>
                                <tr style="padding:10px 0px 10px 0px">
                                    <th style="text-align: center;width:50%">Kode/Nama Barang</th>
                                    <th style="text-align: center;width:10%">Kuantitas</th>
                                    <th style="text-align: center;width:10%">Satuan</th>
                                    <th style="text-align: center;width:16%">Harga</th>
                                    <th style="text-align: center;width:14%">Total</th>
                                </tr>
                                <thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="kode[]" id="kode1"
                                                class="select2_el_log_baranggud form-control"
                                                onchange="showbarangname(this.value, 1)"></select>
                                        </td>
                                        <td><input name="qty[]" onchange="totalin(1)" onkeyup="totalin(1)" id="qty1"
                                                type="text" class="form-control rightJustified"></td>
                                        <td><input name="sat[]" id="sat1" type="text" class="form-control "
                                                onkeypress="return tabE(this,event)"></td>
                                        <td><input name="harga[]" id="harga1" type="text"
                                                class="form-control rightJustified"
                                                onkeypress="return tabE(this,event)"></td>
                                        <td><input name="total[]" id="total1" type="text"
                                                class="form-control rightJustified sumtot"
                                                onkeypress="return tabE(this,event)"></td>
                                    </tr>
                                </tbody>
                                <!-- <tfoot>
								<tr>
									<td colspan="4" style="text-align:right">TOTAL</td>
									<td><input type="text" class="form-control rightJustified" id="vtotal" readonly></td>
									<td colspan="2"></td>
								</tr>
							</tfoot> -->
                        </table>

                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-1"><span
                                    style="display:block;padding:5px 0px 0px 0px;font-size:20px">Total</span></div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="vtotal" readonly>
                            </div>
                        </div>

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

                <br />

                <div class="row">
                    <div class="col-xs-12">
                        <div style="padding:20px 0px 0px 0px">
                            <button type="button" onclick="save()" class="btn blue" id="savebtn"><i
                                    class="fa fa-save"></i>&nbsp; Simpan</button>
                            <button type="reset" class="btn green"><i class="fa fa-edit"></i>&nbsp; Reset</button>
                            <button type="button" class="btn red" onclick="location.href='/logistik_keluar'"><i
                                    class="fa fa-times fa-fw"></i>&nbsp;Tutup</button>
                            <!-- <div class="btn-group">
								<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
							</div>
							<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								 -->
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
$(window).on("load", function() {
    initailizeSelect2_log_baranggud(null);
})

var idrow = 2;

function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ")' class='select2_el_log_baranggud form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id='qty" + idrow + "' onchange='totalin(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id='sat" + idrow + " type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id='harga" + idrow + " type='text' class='form-control rightJustified' >";
    td5.innerHTML = "<input name='total[]'    id='total" + idrow +
        " type='text' class='form-control rightJustified sumtot' >";
    total();
    idrow++;
    initailizeSelect2_log_baranggud($("#gudang_asal").val());

}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>permohonan_log/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hargabeli));
            // totalline(vid);
            checkstock($("#gudang_asal").val(), data.kodebarang);
        }
    });
}

function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || total == "" || total == "0.00") {
        swal('PEMAKAIAN LOGISTIK', 'gudang belum diisi ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('logistik_keluar/save/1')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "PEMAKAIAN LOGISTIK",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal,
                    "</b> </p>" + "Total : " + " " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>logistik_keluar";
                });

            },
            error: function(data) {
                swal('PEMAKAIAN LOGISTIK', 'Data gagal disimpan ...', '');
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

function getgudang() {
    console.log($("#gudang_asal").val());
    initailizeSelect2_log_baranggud($("#gudang_asal").val());
}

function totalin(id) {
    var qtyline = $("#qty" + id).val();
    var hargaline = $("#harga" + id).val();

    var totalline = eval(hargaline.replace(/[^0-9\.]+/g, "")) * eval(qtyline);

    var totalactual = $("#total" + id).val(formatCurrency1(totalline));

    var totalrp = 0;
    $("tr .sumtot").each(function(index, value) {
        currentRow = parseInt(Number($(this).val().replace(/[^0-9\.]+/g, "")));
        totalrp += currentRow
    });

    $("#vtotal").val(formatCurrency1(totalrp));
}

function checkstock(param1, param2) {
    $.ajax({
        url: "/logistik_keluar/checkstock/?kode=" + param2 + "&gudang=" + param1,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(param1 + " - " + param2);
            if (data.status == 0) {
                swal({
                    title: "Kesalahan",
                    html: "Stock tidak cukup<br />Barang tidak dapat digunakan",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("#savebtn").prop("disabled", true);
            } else
            if (data.stock == 0) {
                swal({
                    title: "Kesalahan",
                    html: "Stock tidak cukup<br />Barang tidak dapat digunakan",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("#savebtn").prop("disabled", true);
            } else {
                $("#savebtn").prop("disabled", false);
            }
        }
    });
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
//         url : "<?php echo base_url();?>penjualan_pengiriman/getpo/"+str,
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

// window.onload = function(){
//         document.getElementById('nomorbukti').focus();
// };

// function totalline(id){      
//    var table = document.getElementById('datatable');
//    var row = table.rows[id];        
//    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g,"")); 
//    jumlah      = row.cells[1].children[0].value*harga;    

//    row.cells[4].children[0].value= formatCurrency1(jumlah);   
//    total();  
// }

// function total(){

//    var table = document.getElementById('datatable');
//    var rowCount = table.rows.length;

//    tjumlah = 0;
//    for(var i=1; i<rowCount-1; i++) {
//     var row = table.rows[i];

// 	ztotal      = row.cells[4].children[0].value;

//     var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g,""));

//    	tjumlah  = tjumlah  + eval(jumlah1);

//    } 

//    document.getElementById("vtotal").value=formatCurrency1(tjumlah);
// }
</script>

</body>

</html>