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
                      <span class="title-web">Pembelian <small>Faktur</small>
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
							<a href="<?php echo base_url();?>pembelian_faktur">
                               Daftar Faktur Pembelian
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Entri Faktur
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
                                   Faktur
								</a>
							</li>
							<li class="">
								<a href="#tab2" data-toggle="tab">
                                   Info
								</a>
							</li>
							<li class="">
								<a href="#tab3" data-toggle="tab">
                                   Biaya Lainnya
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
															 <div class="input-group">
                                                              <select id="supp" name="supp" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												 
                                                            <?php
                                                              foreach($supp as $row) { ?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
                                                            <?php } ?>
            												</select>
															<span class="input-group-btn">
																	<a class="btn green" onclick="showpb()"><i class="fa fa-refresh"></i></a>
																</span>	
															 </div>	 
													        </div>
													        </div>

													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor #</label>
													        <div class="col-md-4">
															    <input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?php echo $nomor;?>" readonly>																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
												
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-4">
														        <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">No. Faktur</label>
													        <div class="col-md-4">
														        <input type="text" class="form-control input-medium" placeholder="" name="nomorfaktur" id="nomorfaktur" value="">
													        </div>

														</div>
													</div>
												
													
												</div>
												<div class="row">
												  <div class="col-md-6">
                                                         
														   
														   <div class="form-group">	
                                                           <label class="col-md-3 control-label">No. Terima</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                                 <select name="kodepb" id="kodepb" class="form-control input-medium select2me">																														    													            
																   <option value="">---Tanpa PB---</option>
																 </select>
																 <span class="input-group-btn">
																	<a class="btn blue" onclick="getpoheader();getpb();getbiaya()"><i class="fa fa-download"></i></a>
																</span>	
													          </div>
															</div>
                                                           </div>
														   
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <!--div class="form-group">	
                                                           <label class="col-md-3 control-label">No. Pesanan</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                                 <select name="kodepo" id="kodepo" class="form-control input-medium select2me">																														    													            
																   <option value="">---Tanpa PO---</option>
																 </select>
																 <span class="input-group-btn">
																	<a class="btn blue" onclick="getpoheader();getpo();getbiaya()"><i class="fa fa-download"></i></a>
																</span>	
													          </div>
															</div>
                                                           </div-->
                                                           </div>
													</div>
												</div>
												
												<div class="row">
												 <div class="col-md-12">
                                                   
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													<thead>
                                                      <tr>
                    									<th width="35%" style="text-align: center">Nama Barang</th>
														<th width="10%" style="text-align: center">Kuantitas</th>
														<th width="10%" style="text-align: center">Satuan</th>
														<th width="15%" style="text-align: center">Harga</th>
														<th width="5%" style="text-align: center"></th>
														<th width="10%" style="text-align: center">Diskon</th>
														<th width="15%" style="text-align: center">Total Harga</th>                    									
                    									
                    								</tr>
                    								<thead>
													
                    								<tbody>
													<tr>													   
                                                        <td width="35%">
														    <select name="kode[]" id="kode1" class="select2_el_barang form-control input-largex" onchange="showbarangname(this.value, 1)">
															  <option value="">--- Pilih Barang ---</option>
															</select>												
														</td>
                                                       
                                                        <td width="10%" ><input name="qty[]"    onchange="totalline(1);total()" value="1" id="qty1" type="text" class="form-control rightJustified"  ></td>
														<td width="10%" ><input name="sat[]"    id="sat1" type="text" class="form-control "  onkeypress="return tabE(this,event)"></td>
														<td width="15%" ><input name="harga[]"  onchange="totalline(1)" value="0" id="harga1" type="text" class="form-control rightJustified"  ></td>
														<td><a class="btn default" id="lupharga1" data-toggle="modal" href="#lupharga" onclick="getidharga(this.id)"><i class="fa fa-search"></i></a></td>
														<td width="5%"  ><input name="disc[]"   onchange="totalline(1)" value="0" id="disc1" type="text" class="form-control rightJustified "  ></td>
                                                        <td width="15%" ><input name="jumlah[]"  id="jumlah1"; type="text" class="form-control rightJustified" size="40%" onchange="total()"></td>
                                                       
								                      </tr>
                    								
								                    </tbody>
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
												

											
							</div>
							<!-- tab1-->
							
							<div class="tab-pane" id="tab2">	
							   <div class="row">
							       <div class="col-md-12">
								        
							            <div class="row">												    												
											<div class="col-md-6">
												 <div class="form-group">
													<label class="col-md-3 control-label">Tgl Kirim</label>
													<div class="col-md-4">
													   <div class="input-icon">
														<i class="fa fa-calendar"></i>
														<input id="tanggalkirim" name="tanggalkirim" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													   </div>
													</div>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Keterangan</label>
													<div class="col-md-4">
														<input type="text" class="form-control input-large" placeholder="" name="keterangan" id="keterangan" value="">
													</div>

												</div>
											</div>																									
									    </div>
										<div class="row">
										   <div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">PPN</label>
													        <div class="col-md-4">
														        <select name="sppn" id="sppn" class="form-control select2me input-medium" onchange="total()">
																  <option value="Y">Ya</option>
																  <option value="T">Tidak</option>
																</select>
													        </div>

														</div>
													</div>
										   <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Alamat</label>
													<div class="col-md-9">
														<textarea class="form-control" name="alamat" id="alamat" ></textarea>
													</div>
												</div>
											</div>																									
										</div>	
										<div class="row">
										   <div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Pembayaran</label>
													        <div class="col-md-4">
														        <select name="pembayaran" id="pembayaran" class="form-control select2me input-medium" onchange="total()">
																  <option value="T">Tunai</option>
																  <option value="K" selected="true">Kredit</option>
																</select>
													        </div>

														</div>
													</div>
										   
										</div>	
								   </div> 
								</div>
                                
							</div> <!--tab2-->
							
							<div class="tab-pane" id="tab3">	
							   <div class="row">
							   <div class="col-md-12">
							    
										<table id="datatable2" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
										<thead>
										  <tr>
											<th width="45%" style="text-align: center">#Kode/ Nama Biaya</th>
											<th width="15%" style="text-align: center">Jumlah</th>
											<th width="40%" style="text-align: center">Keterangan</th>																
										  </tr>
										</thead>
									
										<tbody>
										<tr>													   
											<td width="45%">
												<select name="lkode[]" class="select2_el form-control input-xlarge">
												  <option value="">--- Pilih Akun ---</option>
												</select>														
											</td>
										   
											
											<td width="15%" ><input name="ljumlah[]"  id="ljumlah1"  type="text" class="form-control rightJustified" onchange="total()" value="0"></td>
											<td width="40%" ><input name="lket[]"     id="lket1"     type="text" class="form-control "  onkeypress="return tabE(this,event)"></td>
											
										  </tr>
										
										</tbody>
										</table>
										
									</div>
								</div> 
								
                                <div class="row">	
                                  <div class="col-xs-9">
									  <div class="wells">								
								        <button type="button" onclick="tambah2()" class="btn green"><i class="fa fa-plus"></i> </button>
								        <button type="button" onclick="hapus2()" class="btn red"><i class="fa fa-trash-o"></i></button>
									  </div>
								  </div>	  
								</div>
							</div>
							<!-- tab2-->
							
						</div><!--tab-->	
						
						<div class="row">
							<div class="col-xs-8">
								<div class="wells">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									    
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
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
									<td width="40%"><strong>BIAYA LAIN</strong></td>
									<td width="1%"><strong>:</strong></td>
									<td width="59" align="right"><strong><span id="_vbiayalain"></span></strong></td>
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
  $this->load->view('template/v_report');
?>

<script>


var idrow  = 2;
var idrow2 = 2;

function tambah(){
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
	var td4=x.insertCell(3);
	var td5=x.insertCell(4);
	var td6=x.insertCell(5);
	var td7=x.insertCell(6);
	 
	var akun="<select name='kode[]' id=kode"+idrow+" onchange='showbarangname(this.value,"+idrow+")' class='select2_el_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
	td1.innerHTML=akun;
	td2.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
	td3.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
	td4.innerHTML="<input name='harga[]'  id=harga"+idrow+" onchange='totalline("+idrow+") value='0'  type='text' class='form-control rightJustified'>";
	td5.innerHTML="<a class='btn default' id=lupharga"+idrow+" data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a>";													
	td6.innerHTML="<input name='disc[]'   id=disc"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified'  >";
	td7.innerHTML="<input name='jumlah[]' id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%'>";
	
	initailizeSelect2_barang();
    idrow++;
}

function tambah2(){
     var x=document.getElementById('datatable2').insertRow(idrow2);
     var td1=x.insertCell(0);
     var td2=x.insertCell(1);
     var td3=x.insertCell(2);
	 
	 var akun="<select name='lkode[]' class='select2_el form-control' ><option value=''>--- Pilih Akun ---</option></select>";
	
	 td1.innerHTML=akun;
	 td2.innerHTML="<input name='ljumlah[]' id=ljumlah"+idrow2+" onchange='totalline("+idrow2+")' value='0'  type='text' class='form-control rightJustified'  >";
	 td3.innerHTML="<input name='lket[]'    id=lket"+idrow2+" type='text' class='form-control' >";
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getbarang/"+str, true);  
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getakun/"+str, true);  
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getharga/"+str, true);  
  xhttp.send();
}

function showbarangname1(str,id) {
  var xhttp;
  if (str == "") {
    document.getElementById("nama"+id).value = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("nama"+id).value = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getbarangname/"+str, true);  
  xhttp.send();
}

function showbarangname(str, id) {   
  var xhttp; 
  var vid = id;
   $.ajax({
        url : "<?php echo base_url();?>pembelian_pesanan/getinfobarang/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
			$('#sat'+vid).val(data.satuan);
			$('#harga'+vid).val(data.hargabeli);
			totalline(vid);
		}
	});	
  
  
}

function showakunname(str, id) {   
  var xhttp; 
  var vid = id.substring(5);
   $.ajax({
        url : "<?php echo base_url();?>pembelian_pesanan/getinfoakun/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
			$('#lnama'+vid).val(data.namaakun);
		}
	});	
  
  
}

function getidharga(id)
{
	var vid = id.substring(8);
	document.getElementById("nopilihharga").value = vid;
	var supp = document.getElementById("supp").value;
	var item = document.getElementById("kode"+vid).value;
	var param= supp+'~'+item;
	showharga(param);
}


function post_harga(v1, v2)
  {	
	 id=document.getElementById("nopilihharga").value;
	 document.getElementById("sat"+id).value = v2;	 
	 document.getElementById("harga"+id).value = v1;	      
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

function save()
{	        
    var noform   = $('[name="nomorbukti"]').val();    
	var nofaktur = $('[name="nomorfaktur"]').val(); 
	
	var tanggal   = $('[name="tanggal"]').val(); 
    var total     = $('#_vtotal').text(); 
	
    if(nofaktur=="" || total=="" || total=="0.00"){
		 swal('FAKTUR PEMBELIAN','Data Belum Lengkap/Belum ada transaksi ...',''); 
	} else {
	$.ajax({				
		url:'<?php echo site_url('pembelian_faktur/save/1')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  swal({
					  title: "FAKTUR PEMBELIAN",
					  html: "<p> No. Faktur   : <b>"+nofaktur+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>pembelian_faktur";
		         });							
					
	
		},
		error:function(data){
			swal('FAKTUR PEMBELIAN','Data gagal disimpan ...','');   	
								
					
		}
		});
	}	
}	


	function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
			total();
		}
	}

  function hapus2(){
		if(idrow2>2){
			var x=document.getElementById('datatable2').deleteRow(idrow2-1);
			idrow2--;
			total();
		}
	}
	
  function total()
  {
    
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tjumlah = 0;
   tdiskon = 0; 
   
   for(var i=1; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[1].children[0].value;
	harga       = row.cells[3].children[0].value;    
	diskon      = row.cells[5].children[0].value;    
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
	var harga1  = Number(harga.replace(/[^0-9\.]+/g,""));
	var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1*harga1);
	
	diskon      = eval((diskon1/100)*jumlah1*harga1);
	
   	tdiskon  = tdiskon + diskon;
		  
    
   } 

   var table2 = document.getElementById('datatable2');
   var rowCount2 = table2.rows.length;

   tbiaya = 0;
   
   for(var i=1; i<rowCount2; i++) 
   {
    var row = table2.rows[i];
    
	biaya      = row.cells[1].children[0].value;
    var biaya1 = Number(biaya.replace(/[^0-9\.]+/g,""));
	
   	tbiaya  = tbiaya  + eval(biaya1);
   } 
   
   var cppn =  document.getElementById("sppn").value;
   if(cppn=="Y"){
	  tppn = (tjumlah-tdiskon)*0.1; 
   } else {
	  tppn = 0; 
   }
      
   
   document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);
   document.getElementById("_vdiskon").innerHTML=formatCurrency1(tdiskon);
   document.getElementById("_vbiayalain").innerHTML=formatCurrency1(tbiaya);
   document.getElementById("_vppn").innerHTML=formatCurrency1(tppn);
   document.getElementById("_vtotal").innerHTML=formatCurrency1(tjumlah-tdiskon+tbiaya+tppn);


  }
  
  function totalline(id)
  {
   	  
   var table = document.getElementById('datatable');
   var row = table.rows[id];       
   jumlah      = row.cells[1].children[0].value*row.cells[3].children[0].value;    
   diskon      = (row.cells[5].children[0].value/100)* jumlah;
   tot         = jumlah - diskon;
   row.cells[6].children[0].value= tot;   
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_faktur/getlistpo/"+str, true);  
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_faktur/getlistpb/"+str, true);  
  xhttp.send();
}

function getpo() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  if(str==""){
	hapus();
	$('[id=kode1]').val('');
	$('[id=qty1]').val('');
	$('[id=sat1]').val('');
	$('[id=harga1]').val('');
	$('[id=disc1]').val('');
	totalline(1);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_faktur/getpo/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		                
		    for(i=0; i <= data.length-1; i++){	
			hapus();
			}
			
            for(i=0; i <= data.length-1; i++){		
			  if(i>0){
		       tambah();
			  }
			  
			  x = i+1;
			  
			  var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i].namabarang);
              $('#kode'+x).append(option).trigger('change');
			  
			  document.getElementById("qty"+x).value=data[i].qtyorder;		    
			  document.getElementById("sat"+x).value=data[i].satuan;	
			  document.getElementById("harga"+x).value=data[i].hargabeli;
			  document.getElementById("disc"+x).value=data[i].disc;
			 
			  
			}
			
		}
	});	    
  }	
}

function getpb() { 
  var xhttp;      
  var str = $('[name=kodepb]').val();
  if(str==""){
	hapus();
	$('[id=kode1]').val('');
	$('[id=qty1]').val('');
	$('[id=sat1]').val('');
	$('[id=harga1]').val('');
	$('[id=disc1]').val('');
	totalline(1);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_faktur/getpb/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
		    for(i=0; i <= data.length-1; i++){	
			hapus();
			}
			
            for(i=0; i <= data.length-1; i++){		
			  if(i>0){
		       tambah();
			  }
			  
			  x = i+1;
			  
			  var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i].namabarang);
              $('#kode'+x).append(option).trigger('change');
			  
			  document.getElementById("qty"+x).value=data[i].qtyterima;		    
			  document.getElementById("sat"+x).value=data[i].satuan;	
			  document.getElementById("harga"+x).value=0;
			  document.getElementById("disc"+x).value=0;
			 
			}
			
		}
	});	    
  }	
}

function getpoheader() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  if(str==""){	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_faktur/getpoheader/"+str,
        type: "GET",
        dataType: "JSON",
		 
        success: function(data)
        {		            	                        
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
  if(str==""){
	hapus();
	$('[id=lkode0]').val('');
	$('[id=lnama0]').val('');
	$('[id=ljumlah0]').val('');
	$('[id=lket0]').val('');
	totalline(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_faktur/getbiaya/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
		    for(i=0; i <= data.length-1; i++){	
			hapus2();
			}
			
            for(i=0; i <= data.length-1; i++){		
			  if(i>0){
		       tambah2();
			  }
			  document.getElementById("lkode"+i).value=data[i].kodeakun;
			  document.getElementById("lnama"+i).value=data[i].namaakun;		    
              document.getElementById("ljumlah"+i).value=data[i].jumlah;		    
			  document.getElementById("lket"+i).value=data[i].keterangan;				  
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
