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
      <span class="title-web">Farmasi <small>Permohonan Barang Ke Gudang</small>
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
        <a class="title-white" href="<?php echo base_url(); ?>farmasi_pbb">
          Daftar Permohonan
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Entri Permohonan
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="portlet box blue" style="margin-bottom:30px !important">
  <div class="portlet-title">
    <div class="caption"><i class="fa fa-reorder"></i> Data Baru</div>
  </div>

  <div class="portlet-body form">
    <form id="frmpermohonan" class="form-horizontal" method="post"
      style="padding-bottom:0px !important;margin-bottom:0 !important">
      <div class="form-body" style="padding-bottom:0px !important;margin-bottom:0 !important">
        <div class="tabbable tabbable-custom tabbable-full-width"
          style="padding-bottom:0px !important;margin-bottom:0 !important">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-4 control-label">Tanggal <small style="color:red">*</small></label>
                <div class="col-md-4">
                  <input id="tanggal" name="tanggal" class="form-control" type="date"
                    value="<?php echo date('Y-m-d'); ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label">Gudang Asal <small style="color:red">*</small></label>
                <div class="col-md-8">
                  <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo"
                    data-placeholder="Pilih..." onkeypress="return tabE(this,event)"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label">Gudang Tujuan <small style="color:red">*</small></label>
                <div class="col-md-8">
                  <select id="gudang_tujuan" name="gudang_tujuan" class="form-control select2_el_farmasi_depo"
                    data-placeholder="Pilih..." onchange="getgudang()" onkeypress="return tabE(this,event)"></select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Pemohonan No.</label>
                <div class="col-md-6">
                  <div class="input-group">
                    <input type="hidden" name="hidenomorbukti" id="hidenomorbukti">
                    <input type="text" name="nomorbukti" id="nomorbukti" class="form-control" readonly>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Keterangan <small style="color:red">*</small></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ket">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12" style="padding:20px">
          <table id="datatable" class="table table-striped table-condensed table-scrollable">
            <thead>
              <tr>
                <th style="text-align: center;width:28%">Kode/Nama Barang</th>
                <th style="text-align: center;width:10%">Kuantitas</th>
                <th style="text-align: center;width:10%">Satuan</th>
                <th style="text-align: center;width:16%">Harga</th>
                <th style="text-align: center;width:16%">Total</th>
                <th style="text-align: center;width:16%">Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><select name="kode[]" id="kode1" class="select2_el_farmasi_baranggud form-control" onchange="getbarang(1)"></select></td>
                <td><input name="qty[]" id="qty1" type="text" class="form-control" value="1" min="1" onchange="total_1(1);"></td>
                <td><input name="sat[]" id="sat1" type="text" class="form-control"></td>
                <td><input name="harga[]" id="harga1" type="text" class="form-control" readonly></td>
                <td><input name="total[]" id="total1" type="text" class="form-control" readonly></td>
                <td><input name="note[]" id="note1" type="text" class="form-control"></td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td>
                  <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i></button>
                  <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
                </td>
                <td colspan="2">&nbsp;</td>
                <td style="text-align:right"><span style="padding-top:8px;display:block">TOTAL</span>
                </td>
                <td><input type="text" class="form-control" name="vtotal" id="vtotal" readonly></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div style="padding:20px;padding-top:0px">
        <button type="button" class="btn blue" style="font-weight:bold" id="save_pembayaran" onclick="save()">
          <i class="fa fa-save fa-fw"></i>&nbsp; Simpan
        </button>

        <a class="btn red" href="<?php echo base_url('Farmasi_pbb')?>">
        <i class="fa fa-undo"></i><b> KEMBALI </b></a>
      </div>
    </form>
  </div>
</div>

<?php
$this->load->view('template/footer');
$this->load->view('template/footer_all');
?>

<script>
$(window).on("load", function() {
  sequentPenjualan();
  initailizeSelect2_farmasi_baranggud(null);
});

var idrow = 2;
var rowCount;
var arr = [1];

function tambah(gudang) {
  var v = document.getElementById('datatable').insertRow(idrow);
  var tdv1 = v.insertCell(0);
  var tdv2 = v.insertCell(1);
  var tdv3 = v.insertCell(2);
  var tdv4 = v.insertCell(3);
  var tdv5 = v.insertCell(4);
  var tdv6 = v.insertCell(5);
  tdv1.innerHTML = '<select name="kode[]" id="kode' + idrow + '" class="select2_el_farmasi_baranggud form-control" onchange="getbarang(' + idrow + ')"></select>';
  tdv2.innerHTML = '<input name="qty[]" id="qty' + idrow + '" value="1" min="1" type="text" class="form-control" onchange="total_1(' + idrow + ');">';
  tdv3.innerHTML = '<input name="sat[]" id="sat' + idrow + '" type="text" class="form-control">';
  tdv4.innerHTML = '<input name="harga[]" id="harga' + idrow + '" type="text" class="form-control" readonly>';
  tdv5.innerHTML = '<input name="total[]" id="total' + idrow + '" type="text" class="form-control" readonly>';
  tdv6.innerHTML = '<input name="note[]" id="note' + idrow + '" type="text" class="form-control">';
  var gud = $("#gudang_tujuan").val();
  initailizeSelect2_farmasi_baranggud(gud);
  idrow++;
}

function sequentPenjualan() {
  $.ajax({
    url: "/farmasi_pbb/get_last_number/NO_MOHON",
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      $("#nomorbukti").val("AUTO");
      $("#hidenomorbukti").val(data.lastno);
    },
    error: function(data, xhr, ajaxOptions, thrownError) {
      alert("error get no tr");
    }
  });
}

function secondPenjualan() {
  $.ajax({
    url: "/farmasi_pbb/get_recent_number/NO_MOHON",
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      $("#nomorbukti").val(data.notr);
      $("#hidenomorbukti").val(data.notr);
    },
    error: function(data, xhr, ajaxOptions, thrownError) {
      alert("error get no tr");
    }
  });
}

function getbarang(param) {
  var kodebarang = $("#kode" + param).val();
  var qty = $("#qty" + param).val();
  var gud = $("#gudang_tujuan").val();
  $.ajax({
    url: "/farmasi_po/getinfobarang/?kode=" + kodebarang + '&gudang=' + gud,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      // if (data.saldoakhir < qty) {
      //   swal({
      //     title: "KUANTITAS",
      //     html: "Melebihi saldo akhir<br>Saldo akhir saat ini : " + separateComma(data.saldoakhir),
      //     type: "info",
      //     confirmButtonText: "OK"
      //   }).then((value) => {
      //     $("#kode" + param).empty();
      //   });
      // } else {
        total = Number(parseInt(qty.replaceAll(',', ''))) * data.hargajual;
        $("#sat" + param).val(data.satuan1);
        $("#harga" + param).val(separateComma(data.hargajual));
        $("#total" + param).val(separateComma(total));
        total_1(param);
      // }
    }
  });
}

function getgudang() {
  var gud = $("#gudang_tujuan").val();
  console.log(gud);
  initailizeSelect2_farmasi_baranggud(gud);
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

function total_1(param) {
  var table = document.getElementById('datatable');
  // var row = table.rows[arr.indexOf(param) + 1];
  // var qty = Number(row.cells[1].children[0].value.replace(/[^0-9\.]+/g, ""));
  // var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
  var qtyx = $("#qty"+param).val();
  var qty = Number(parseInt(qtyx.replaceAll(',','')));
  var hargax = $("#harga"+param).val();
  var harga = Number(parseInt(hargax.replaceAll(',','')));
  var tot = qty * harga;
  // row.cells[4].children[0].value = separateComma(tot);
  $("#total"+param).val(separateComma(tot));
  total_2();
}

function total_2(){
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

function hapus() {
  if (idrow > 2) {
    var x = document.getElementById('datatable').deleteRow(idrow - 1);
    idrow--;
    total_2();
  }

  // var total = 0;
  // $("tr .sumsubtot").each(function(index, value) {
  //   currentRow = parseInt(Number($(this).val().replace(/[^0-9\.]+/g, "")));
  //   total += currentRow
  // });
  // $("#vtotal").val(separateComma(total));
}

function save() {
  var gudang_asal   = $('[name="gudang_asal"]').val();
  var gudang_tujuan = $('[name="gudang_tujuan"]').val();
  var ket           = $('[name="ket"]').val();
  var total         = $('#vtotal').val();
  var tanggal       = $('[name="tanggal"]').val();
  if (gudang_asal == "" || gudang_tujuan == "" || ket == "" || total == "" || total == "0.00") {
    swal({
      title : "PERMOHONAN GUDANG",
      html  : "Belum Lengkap !",
      type  : "warning",
      confirmButtonText: "OK"
    });
    return;
  } else {
    $.ajax({
      url   : "/farmasi_pbb/save/1",
      data  : $('#frmpermohonan').serialize(),
      type  : 'POST',
      success: function(data) {
        swal({
          title : "PERMOHONAN GUDANG",
          html  : "<p> No. Mutasi   : <b>" + data + "</b> </p>" + "Tanggal :  " + tanggal + "</b> </p>" + "Total" + " " + total,
          type  : "info",
          confirmButtonText: "OK"
        }).then((value) => {
          location.href = "<?php echo base_url() ?>farmasi_pbb";
        });
      },
      error: function(data) {
        swal('PERMOHONAN GUDANG', 'Data gagal disimpan ...', '');
      }
    });
  }
}
</script>
<!-- -->
<!-- <div class="row">
	<div class="col-md-12">
		<h3 class="page-title">
		<span class="title-unit">
				&nbsp;<?php echo $this->session->userdata('unit'); ?> 
			</span>
			- 
			<span class="title-web">Farmasi <small>Permohonan Barang Ke Gudang</small>
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
				<a class="title-white" href="<?php echo base_url(); ?>farmasi_pbb">
					Daftar Permohonan
											</a>
				<i style="color:white;" class="fa fa-angle-right"></i>
			</li>
			<li>							
				<a class="title-white" href="">
					Entri Permohonan
				</a>
			</li>
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
		<form id="frmpenjualan" class="form-horizontal" method="post">
		<div class="form-body">
			<div class="tabbable tabbable-custom tabbable-full-width">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#tab1" data-toggle="tab">
						<i class="fa fa-file"></i> 
						Permohonan Barang
					</a>
				</li> -->
<!--li class="">
					<a href="#tab2" data-toggle="tab">                                   
						<i class="fa fa-info-circle"></i>
						Info
					</a>
				</li-->
<!-- </ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">		
									
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
												<label class="col-md-3 control-label">Pemohonan No.</label>
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
													<select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
													</select>	
													
												</div>



											</div>
										</div>
										<div class="col-md-6">
												
										</div>
										
									
										
									</div>
									
									<div class="row">												    												
										<div class="col-md-6">
												<div class="form-group">
												<label class="col-md-3 control-label">Gudang Tujuan</label>
												<div class="col-md-9">
													<select id="gudang_tujuan" name="gudang_tujuan" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
													</select>	
													
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
										<div class="col-md-12">
										
										<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
										<thead>
											<tr>
											<th width="25%" style="text-align: center">Kode/Nama Barang</th>
											<th width="10%" style="text-align: center">Kuantitas</th>
											<th width="10%" style="text-align: center">Satuan</th>
											<th width="15%" style="text-align: center">Harga</th>
											<th width="15%" style="text-align: center">Total</th>
											<th width="20%" style="text-align: center">Keterangan</th>
											
										</tr>
										<thead>
										
										<tbody>
										<tr>		 -->
<!-- <td width="5%">
												<button type='button' onclick="hapusBarisIni(1)"class='btn red'><i class='fa fa-trash-o'></i></button>
											</td>											    -->
<!-- <td width="25%">
												<select name="kode[]" id="kode1" onchange="showbarangname(this.value, 1)" class="select2_el_farmasi_baranggud form-control" onkeypress="return tabE(this,event)"></select>
											</td>
											<td width="10%" ><input name="qty[]" onchange="totalline(1)" value="1" id="qty1" type="text" class="form-control rightJustified"  ></td>
											<td width="10%" ><input name="sat[]" id="sat1" type="text" class="form-control "></td>
											<td width="15%" ><input name="harga[]" id="harga1" type="text" class="form-control rightJustified"></td>
											<td width="15%" ><input name="total[]" id="total1" type="text" class="form-control rightJustified"></td>
											<td width="10%" ><input name="note[]" id="note1" type="text" class="form-control "></td>
											</tr>
										
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4" style="text-align:right">TOTAL</td>
												<td><input type="text" class="form-control rightJustified" id="vtotal" readonly></td>
												<td colspan="2"></td>
											</tr>
										</tfoot>
										</table>
										
										<div class="row">
											<div class="col-xs-9">
												<div class="wells">
													<button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
													<button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
												</div>															
											</div>
											
																							
										</div>

										
										</div>
									</div>
									

								
				</div> -->
<!-- tab1-->

<!-- <div class="tab-pane" id="tab2">	
					<div class="row">
						
					</div>
					
				</div> -->
<!-- tab2-->

<!-- </div>tab	 -->

<!-- <div class="row">
				<div class="col-xs-12">
					<div class="well">		
						
						
						<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							
						<div class="btn-group">
							<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
						</div>
						<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
					</div>															
				</div>
				
															
			</div>
										
										
			</div>	
		</div>  
		
		
		</form>
	</div>
</div>-->

<script>
// $(window).on("load", function(){
// 	var gud = "FARMASI";
// 	initailizeSelect2_farmasi_baranggud(gud);
// })

// var idrow  = 2;
// // var rowCount;
// // var arr = [1];

// function tambah(){
// 	// var table      = document.getElementById('datatable');
// 	// rowCount   = table.rows.length;
// 	// arr.push(idrow);

//     var x=document.getElementById('datatable').insertRow(idrow);
//     // var td1=x.insertCell(0);
//     var td1=x.insertCell(0);
//     var td2=x.insertCell(1);
// 	var td3=x.insertCell(2);
// 	var td4=x.insertCell(3);
// 	var td5=x.insertCell(4);
// 	var td6=x.insertCell(5);


// 	var akun="<select name='kode[]' id='kode"+ idrow +"' onchange='showbarangname(this.value, "+ idrow +")' class='select2_el_farmasi_baranggud form-control' onkeypress='return tabE(this,event)'></select>";
// 	td1.innerHTML=akun;
// 	// td1.innerHTML="<td><button type='button' onclick=hapusBarisIni("+idrow+") class='btn red'><i class='fa fa-trash-o'></i></button></td>";
// 	td2.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
// 	td3.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
// 	td4.innerHTML="<input name='harga[]'  id=harga"+idrow+" type='text' class='form-control' >";
// 	td5.innerHTML="<input name='total[]'    id=total"+idrow+" type='text' class='form-control rightJustified' >";
// 	td6.innerHTML="<input name='note[]'    id=note"+idrow+" type='text' class='form-control' >";
// 	var gud    = $('[name="gudang_asal"]').val(); 
// 	initailizeSelect2_farmasi_baranggud(gud);
// 	total();
//     idrow++;
// }

// function hapusBarisIni(param){
// 		// alert(param);
// 		// var x=document.getElementById('datatable').deleteRow(param);
// 		// idrow = param;
// 		// total();

// 		console.log(param);
// 		console.log(arr.indexOf(param))
// 		var x = document.getElementById('datatable').deleteRow(arr.indexOf(param)+1);
// 		arr.splice(arr.indexOf(param),1);

// 		rowCount--;
// 		console.log('rowCount ' + rowCount);
// 		total();
// 	}

// function showbarangname(str, id) {   
// //   var xhttp; 
//    $.ajax({
//         url : "<?php echo base_url(); ?>farmasi_po/getinfobarang/"+str,
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){						
// 			$('#sat'+id).val(data.satuan1);
// 			// $('#harga'+id).val(separateComma(data.hargajual));
// 			totalline(id);
// 		}
// 	});	


// }



// function save(){	      
//     var gudang_asal   = $('[name="gudang_asal"]').val(); 
// 	var gudang_tujuan   = $('[name="gudang_tujuan"]').val(); 
// 	var total   = $('#vtotal').val(); 
// 	var tanggal  = $('[name="tanggal"]').val(); 
// 	if(gudang_asal=="" || gudang_tujuan=="" || total=="" || total=="0.00"){
// 		swal('MUTASI GUDANG','gudang belum diisi ...',''); 
// 	} else {      
// 		$.ajax({				
// 			url:'<?php echo site_url('farmasi_pbb/save/1') ?>',				
// 			data:$('#formpermohonan').serialize(),				
// 			type:'POST',
// 			success:function(data){        		
// 			swal({
// 						title: "PERMOHONAN GUDANG",
// 						html: "<p> No. Mutasi   : <b>"+data+"</b> </p>"+ 
// 						"Tanggal :  " + tanggal,
// 						type: "info",
// 						confirmButtonText: "OK" 
// 						}).then((value) => {
// 								location.href = "<?php echo base_url() ?>farmasi_pbb";
// 					});								

// 			},
// 			error:function(data){
// 				swal('PERMOHONAN GUDANG','Data gagal disimpan ...',''); 
// 			}
// 		});
// 	}		
// }	

// function hapus(){
// 	if(idrow>2){
// 		var x=document.getElementById('datatable').deleteRow(idrow-1);
// 		idrow--;
// 	}
// }

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

// function totalline(id)
//   {      
//    var table = document.getElementById('datatable');
//    var row = table.rows[id];        
//    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g,"")); 
//    jumlah      = row.cells[1].children[0].value*harga;    

//    row.cells[3].children[0].value= separateComma(jumlah);   
//    total();  
// }

// function total()
//   {

//    var table = document.getElementById('datatable');
//    var rowCount = table.rows.length;

//    tjumlah = 0;
//    for(var i=1; i<rowCount-1; i++) 
//    {
//     var row = table.rows[i];

// 	ztotal      = row.cells[4].children[0].value;

//     var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g,""));

//    	tjumlah  = tjumlah  + eval(jumlah1);

//    } 

//    document.getElementById("vtotal").value=separateComma(tjumlah);


//   }
</script>
</body>

</html>