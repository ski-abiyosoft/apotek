<?php 
	$this->load->view('template/headerfull');
    $this->load->view('template/bodyfull');    	  
?>	

<link href="<?php echo base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css"/>
<link href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/gritter/css/jquery.gritter.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/pages/error.css" rel="stylesheet" type="text/css"/>


<style>
 
#myInput {
     background-image: url('<?php echo base_url()?>assets/img/search-icon-blue.png'); 
    background-position: 10px 12px; 
    background-repeat: no-repeat; 
    width: 100%; 
    font-size: 14px; 
    padding: 12px 20px 12px 40px;
    border: 1px solid #ddd; 
    margin-bottom: 12px; 
}

#myTable {
    border-collapse: collapse; 
    width: 100%; 
    border: 1px solid #ddd; 
    font-size: 14px;
}

#myTable th, #myTable td {
    text-align: left;
    padding: 5px; 
}

#myTable tr {
    border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
   
    background-color: #f1f1f1;
}

input[type=text]:focus {
    width: 100%;
}


.rightJustified {
	text-align: right;
}

.total{
	font-size: 30px;
	font-weight: bold;
	color: blue;
}

.jumlahbayar{
	font-size: 30px;
	font-weight: bold;
	color: blue;
	height: 50px; 
	--width:280px;
}

.jumlahkembali{
	font-size: 30px;
	font-weight: bold;
	color: red;
	height: 50px; 
	--width:280px;
}
.kodebarang{
	font-size: 30px;
	font-weight: bold;
	color: red;
	height: 70px; 
	width:280px;
}

   
.bodycontainer { min-height: 143px; max-height: 14px; width: 100%; margin: 0; overflow-y: auto; }
.table-scrollable { margin: 0; padding: 0; }

.modal-bodyx {
    max-height:200px; 
    overflow-y: auto;
}
</style>



			
            <div class="portlet box- blue-">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>POS
					</div>
				    <div class="tools">											
						<a href="<?php echo base_url();?>dashboard" class="remove"></a>
					</div>
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpenjualan" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabsx  nav-pills">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   Penjualan
								</a>
							</li>
							<li class="">
								<a href="#tab2" data-toggle="tab">
                                   Pembayaran
								</a>
							</li>
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
													        <div class="col-md-4">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-mediums" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													    	   </div>
													        </div>														
														</div>																												
													</div>
													
												    
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor # <font color="red">*</font></label>
													        <div class="col-md-4">
															    <input type="text" class="form-control input-mediums" name="nomorbukti"  id="nomorbukti" value="<?php echo $nomor;?>" readonly>																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
												
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kode Barang <font color="red">*</font></label>
													        <div class="col-md-6">
															 <div class="input-group">
                                                                
															    <input name="kodebarang"  id="kodebarang"  maxlength="12" onchange="getitem()" style="color:blue;"  type="text" class="form-control" >																																																
																<span class="input-group-btn">
																	<a class="btn green" id="0" data-toggle="modal" href="#lupbarang" onclick="getid(this.id)"><i class="fa fa-search"></i></a>
																</span>						                
																<span class="input-group-btn">
																	<a class="btn blue" onclick="getitem()"><i class="fa fa-download"></i></a>
																</span>	
															 </div>	 
													        </div>
													        </div>

													</div>
													
				
													<!--div class="col-md-6" >
														<div class="form-group " >
                                                            <label class="col-md-3 control-label "><font color="red">***</font> <b>TOTAL</b> <font color="red">***</font></label>
													        <div class="col-md-4" id="pulsate-regular" style="padding:5px;" >
															   <input  style="font-size:16px;" type="text"  name="vtotal"  id="vtotal" value="0" class="form-control rightJustified" readonly>																																														
															
													        </div>

														</div>
													</div-->
													
													
												</div>
												
												
												
												<div class="row">
												 <div class="col-md-12">
                                                   	<div class="table-responsive">
							                    	<table class="table table-bordereds table-condensed">
								                    <thead>
                                                      <tr>
													    <th width="5%" style="text-align: center">No</th>
                    									<th width="10%" style="text-align: center">Kode #</th>
                    									<th width="30%" style="text-align: center">Nama Barang</th>
														<th width="10%" style="text-align: center">Kuantitas</th>
														<th width="10%" style="text-align: center">Satuan</th>
														<th width="15%" style="text-align: center">Harga</th>
														<th width="5%" style="text-align: center">Diskon</th>
														<th width="10%" style="text-align: center">Total Harga</th>   
														<th width="3%" style="text-align: center">&nbsp</th>   
                    									
                    								</tr>
                    								<thead>
													</table>
													

													<div class="bodycontainer scrollable">
													<table id="datatable" class="table table-hoverx table-striped table-bordered table-condensed table-scrollables">
													
													
                    								<tbody>
													<tr>
                                                        <td width="5%"  ><input name="nourut[]"  id="nourut0"  maxlength="30" type="text" class="form-control center" readonly></td>																																																																												
                                                        <td width="10%" ><input name="kode[]"  id="kode0"  maxlength="30" style="color:blue;"  type="text" class="form-control" readonly></td>																																																																
											            <td width="30%" ><input name="nama[]"    id="namabarang0" type="text" class="form-control "  onkeypress="return tabE(this,event)" readonly></td>
														<td width="10%" ><input name="qty[]" onchange="totalline(0);total()" value="1" id="qty0" type="text" class="form-control spinner-input rightJustified" ></td>
														<td width="10%" ><input name="sat[]"    id="sat0" type="text" class="form-control "  onkeypress="return tabE(this,event)" readonly></td>
														<td width="15%" ><input name="harga[]"  onchange="totalline(0)" value="0" id="harga0" type="text" class="form-control rightJustified"  ></td>														
														<td width="5%"  ><input name="disc[]"   onchange="totalline(0)" value="0" id="disc0" type="text" class="form-control rightJustified "  ></td>
                                                        <td width="10%" ><input name="jumlah[]"  id="jumlah0"; type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
                                                        <td width="3%"></td>
								                      </tr>
                    								
								                    </tbody>
													</table>
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
										   <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Pembayaran</label>
													<div class="col-md-4">
														<select name="pembayaran" id="pembayaran" class="form-control select2me input-mediums" onchange="total()">
														  <option value="T">Tunai</option>
														  <option value="K" selected="true">Kredit</option>
														</select>
													</div>

												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">No. Kartu</label>
													<div class="col-md-4">
													   <input name="kokartu"  id="nokartu"   style="color:blue;"  type="text" class="form-control input-mediums" >																																																
														
													</div>

												</div>
											</div>
										   
										</div>	
										<div class="row">
										   
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Jumlah Bayar</label>
													
													<div class="col-md-9">
													   <div class="input-group">	
													   <input name="jumlahbayar"  id="jumlahbayar"   onfocus="this.select()" onmouseup="return false" onchange="total();getterbilang()" style="color:blue;"  type="text" class="form-control input-medium rightJustified jumlahbayar" value="0" >																																																
													    </div>	
														<p class="help-block"><span style='color:green;' id="terbilang_bayar">...</span></p>
													</div>
                                                    
													
												</div>
											</div>
											
											
										
										   
										</div>	
										<div class="row">
										   
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Jumlah Kembali</label>
													<div class="col-md-9">
													  <div class="input-group">
													   <input name="vkembali"  id="vkembali"   type="text" class="form-control input-medium rightJustified jumlahkembali" readonly>																																																
													   </div>	
														<p class="help-block"><span style='color:green;' id="terbilang_kembali">...</span></p>	
													</div>
                                                   
												</div>
											</div>
											
										
										   
										</div>	
										<!--div class="row">
										    <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label"></label>
													   <div id="kotakkembalis" class="col-lg-4 col-md-4 col-sm-2 col-xs-4">
														<div class="dashboard-stat green">
															<div class="visual">
															
															</div>
															<div class="details">
																<div class="number">
																	<span id="vkembali"></span>
																</div>
																<div class="desc">
																	Kembali
																</div>
															</div>
														
														</div>
													</div>
														
													
												</div>
											</div>
											
										</div-->
								   </div> 
								</div>
                                <hr>
							</div> <!--tab2-->
							
							
							
						</div><!--tab-->	
						<div class="row">
							<div class="col-xs-6">
								<div class="wells">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									<a class="btn yellow print_laporan" href="#report" data-toggle="modal"><i class="fa fa-print"></i> Cetak</a> 
                                    <button type="button" onclick="bayar()" class="btn red"><i class="fa fa-save"></i> Bayar</button>   
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
									</div>
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								</div>															
							</div>
							
							<div class="col-lg-2 col-md-3s col-sm-2 col-xs-12x">
								<div class="dashboard-stat blue">
									<div class="visual">
										
									</div>
									<div class="details">
										<div class="number">
											 <span id="vsubtotal"></span>
										</div>
										<div class="desc">
											 Sub Total
										</div>
									</div>
									
								</div>
							</div>
							
							<div id="kotakdiskon" class="col-lg-2 col-md-3s col-sm-2 col-xs-12x">
								<div class="dashboard-stat yellow">
									<div class="visual">
										<!--i class="fa fa-shopping-cart"></i-->
									</div>
									<div class="details">
										<div class="number">
											 <span id="vdiskon"></span>
										</div>
										<div class="desc">
											 Diskon
										</div>
									</div>
									
								</div>
							</div>
							<div class="col-lg-2 col-md-3s col-sm-2 col-xs-12x" >
								<div class="dashboard-stat green " >
									<div class="visual" >
										
									</div>
									<div class="details" >
										<div class="number">
											 <span id="vtotal"></span>
										</div>
										<div class="desc" >
											 <span id="totitem"></span>TOTAL
										</div>
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

<script src="<?php echo base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/clockface/js/clockface.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-dropdowns.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-pickers.js"></script>
<script src="<?php echo base_url('assets/scripts/core/app.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/ui-general.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/gritter/js/jquery.gritter.js');?>" type="text/javascript" ></script>

<script>


var idrow  = 0;
var idrow2 = 1;

function tambah(){
	var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
	var td4=x.insertCell(3);
	var td5=x.insertCell(4);
	var td6=x.insertCell(5);
	var td7=x.insertCell(6);
    var td8=x.insertCell(7);
	var td9=x.insertCell(8);
	
	td1.innerHTML="<input name='nourut[]' id=nourut"+idrow+" type='text' class='form-control'  readonly>";
	td2.innerHTML="<input name='kode[]'   id=kode"+idrow+" type='text' class='form-control'  readonly>";
	td3.innerHTML="<input name='nama[]'   id=namabarang"+idrow+" type='text' class='form-control'  readonly>";
	td4.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
	td5.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
	td6.innerHTML="<input name='harga[]'  id=harga"+idrow+" onchange='totalline("+idrow+") value='0'  type='text' class='form-control rightJustified'>";	
	td7.innerHTML="<input name='disc[]'   id=disc"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified'  >";
	td8.innerHTML="<input name='jumlah[]' id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%'>";
	td9.innerHTML="<button type='button' onclick='deleteRow(this)' class='btn red'><i class='fa fa-trash-o'></i></button>";
	
    //idrow++;
	
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
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pos/getbarang/"+str, true);  
  xhttp.send();
}



function showbarangname(str, id) {   
  var xhttp; 
  var vid = id.substring(4);
   $.ajax({
        url : "<?php echo base_url();?>penjualan_pos/getinfobarang/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
			$('#namabarang'+vid).val(data.namabarang);
			$('#sat'+vid).val(data.satuan);
			$('#harga'+vid).val(data.hargabeliakhir);
			totalline(vid);
		}
	});	
  
  
}

function post_value(v1)
  {	
     $('#kodebarang').val(v1);
	 getitem();
	 $('#kodebarang').focus();
	 $('#kodebarang').val('');
	 
  }
  
function bayar()
{
	$('.nav-pills a[href="#tab2"]').tab('show');
	var total = $('#vtotal').text();
	$('#jumlahbayar').val(total);
	$('#jumlahbayar').focus();
	getterbilang();
	
	
}
  
function save()
{	        
    var noform   = $('[name="nomorbukti"]').val();    
    if(noform==""){
		$.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Nomor Faktur untuk formulir '+noform+' masih kosong ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'gritter-red',
					});
	} else {
	$.ajax({				
		url:'<?php echo site_url('penjualan_pos/save/1')?>',				
		data:$('#frmpenjualan').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  $.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Faktur dengan nomor '+noform+' BERHASIL disimpan ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color success',
					});					
	
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
			
			$.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Faktur dengan nomor '+noform+' GAGAL disimpan, periksa kembali data yang dimasukan dan lakukan SIMPAN ulang ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color warning',
					});
								
					
		}
		});
	}	
}	


function formatCurrency1(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	//return (((sign)?'':'-') + '' + num + '.' + cents);
	return (((sign)?'':'-') + '' + num);
	}

  function formatCurrency(fieldObj)
    {

        if (isNaN(fieldObj.value)) { return false; }
        fieldObj.value =formatCurrency1(fieldObj.value);
        return true;

    }

	function deleteRow(r) {
		var i = r.parentNode.parentNode.rowIndex;
		document.getElementById("datatable").deleteRow(i);
		idrow--;
		total();
	}

	
  function total()
  {
    
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tjumlah = 0;
   tdiskon = 0; 
   titem  = 0;
   for(var i=0; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[3].children[0].value;
	harga       = row.cells[5].children[0].value;    
	diskon      = row.cells[6].children[0].value;    
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
	var harga1  = Number(harga.replace(/[^0-9\.]+/g,""));
	var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1*harga1);
	
	diskon      = eval((diskon1/100)*jumlah1*harga1);
	
   	tdiskon  = tdiskon + diskon;
	titem++;
		  
    
   } 
   var vkode = $('#kode0').val();
   if(vkode==""){
	   titem = 0;
	   $('#totitem').text('');
   } else {
	   var isi = "("+titem+" item) ";
	   $('#totitem').text(isi);
   }
  
   /*  
   if(tdiskon>0){ 
      $('#kotakdiskon').show();
   } else {
	   $('#kotakdiskon').hide();
   }	  
   */
 
   jumbayar = document.getElementById("jumlahbayar").value;
   document.getElementById("vsubtotal").innerHTML=formatCurrency1(tjumlah);
   document.getElementById("vdiskon").innerHTML=formatCurrency1(tdiskon);
   document.getElementById("vtotal").innerHTML=formatCurrency1(tjumlah-tdiskon);
   var jumbayarf = Number(jumbayar.replace(/[^0-9\.]+/g,""));
   if(jumbayar!=0){
     document.getElementById("vkembali").value=formatCurrency1(jumbayarf-tjumlah);
	 $('#kotakkembali').show();
   } else {
	 document.getElementById("vkembali").value=0;  
	 $('#kotakkembali').hide();  
   }
   
   var kembali = jumbayar-tjumlah;
   if(kembali>0){ 
      getterbilang2(kembali);
   }

  }
  
  function totalline(id)
  {
   	  
   var table = document.getElementById('datatable');
   var row = table.rows[id];       
   var harga   = row.cells[5].children[0].value;
   var harga1  = Number(harga.replace(/[^0-9\.]+/g,""));
   jumlah      = row.cells[3].children[0].value*harga1;    
   diskon      = (row.cells[6].children[0].value/100)* jumlah;
   tot         = jumlah - diskon;
   row.cells[7].children[0].value= formatCurrency1(tot);   
   total();
   
  }
  
   
  
function tabE(obj,e){
      var e=(typeof event!='undefined')?window.event:e;// IE : Moz

      if(e.keyCode==13){
         var ele = document.forms[0].elements;

      for(var i=0;i<ele.length;i++){
          var q=(i==ele.length-1)?0:i+1;// if last element : if any other
      if(obj==ele[i]){ele[q].focus();break}
    }
    return false;
    }
    }

	
function getterbilang() {
  var xhttp;
  
  var str0 = $('#jumlahbayar').val();
  var str = Number(str0.replace(/[^0-9\.]+/g,""));
  if (str0 == "0") {
	$('#terbilang_bayar').text('...');
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       $('#terbilang_bayar').text(this.responseText);
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pos/getterbilang/"+str, true);  
  xhttp.send();
}

function getterbilang2(str) {
  var xhttp;
  if (str == "") {
	$('#terbilang_kembali').text('');
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       $('#terbilang_kembali').text(this.responseText);
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pos/getterbilang/"+str, true);  
  xhttp.send();
}

function getitem() { 
  var xhttp;      
  var str = $('[name=kodebarang]').val();
  var jumlah = 1;
  if(idrow>0){
  tambah();
  }
  if(str==""){
	Alert('Kode masih kosong ');
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>penjualan_pos/getinfobarang/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		
		  document.getElementById("nourut"+idrow).value=idrow+1;
		  document.getElementById("kode"+idrow).value=data.kodeitem;
		  document.getElementById("namabarang"+idrow).value=data.namabarang;		    
		  document.getElementById("qty"+idrow).value=jumlah;		    
		  document.getElementById("sat"+idrow).value=data.satuan;	
		  document.getElementById("harga"+idrow).value=formatCurrency1(data.hargajual1);
		  document.getElementById("disc"+idrow).value=data.disc1;
		  totalline(idrow);			  			
		  idrow++;		  
		  document.getElementById("kodebarang").focus();
		  document.getElementById("kodebarang").value="";
			  
		},
		error:function(data){
		  hapus();						
					
		}
	});	    
  }
}


window.onload = function(){
        document.getElementById('kodebarang').focus();
		document.getElementById('vsubtotal').innerHTML=0;
		document.getElementById('vdiskon').innerHTML=0;
		document.getElementById('vtotal').innerHTML=0;
		document.getElementById('vkembali').innerHTML=0;
		total();
		
		
};

$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('PENJUALAN');
		
		var param= $('[name="nomorbukti"]').val();
		
				
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>penjualan_pos/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});

jQuery(document).ready(function() {   
   
   //
  
   //App.init();
   ComponentsPickers.init();
   UIGeneral.init();
   
   
   
   
});
	
</script>


<div class="modal fade" id="lupbarang" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<span id="nopilih">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Daftar Barang</h4>
				<input type="text" id="myInput" onkeyup="showbarang(this.value)" placeholder="Masukan Kode/ Nama Barang ...">
			</div>
			<div class="modal-body">										 
			  <div id="txtHint"></div>
			</div>   
			<div class="modal-footer">	                                        
				<button type="button" id="btntutup" class="btn red" data-dismiss="modal">Tutup</button>																			
			</div>											
		</div>									
	</div>								
</div>


	
</body>
</html> 
