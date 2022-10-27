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
                      <span class="title-web">Finance <small>Pembayaran Hutang</small>
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
							<a href="<?php echo base_url();?>pembelian_bayar">
                               Daftar Pembayaran Hutang
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Entri Pembayaran
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
                                   Pembayaran
								</a>
							</li>							
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Vendor</label>
													        <div class="col-md-6">
															 <div class="input-group">
                                                              <select id="supp" name="supp" class="form-control select2_el_vendor" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												  </select>
															<span class="input-group-btn">
																	<a class="btn blue" onclick="getfaktur()"><i class="fa fa-download"></i></a>
																</span>	
															 </div>	 
													        </div>
													        </div>

													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">No. Bukti #</label>
													        <div class="col-md-4">
															    <input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?php echo $nomor;?>" readonly>																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
												
												<div class="row">	
                                                    <div class="col-md-6">
														 <div class="form-group">	
														   <label class="col-md-3 control-label">Kas/Bank</label>
															<div class="col-md-6">
																 <select id="kasbank" name="kasbank" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
															
															     </select>																														    													            
															
															</div>
														   </div>
													</div>												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tgl Bayar</label>
													        <div class="col-md-4">
														        <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													
												</div>
												
												<div class="row">	
                                                    <div class="col-md-6">
														 <div class="form-group">	
														   <label class="col-md-3 control-label">Metode Bayar</label>
															<div class="col-md-3">
															  <div class="input-group">
																 <select id="metodebayar" name="metodebayar" class="form-control input-small select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
															      <option value="T">Tunai</option>
																  <option value="C">Cek/Giro</option>
																  <option value="F">Transfer Bank</option>
															     </select>																														    													            
															  </div>
															</div>
															<div class="col-md-3">
															  <div class="input-group">
																 <input type="text" class="form-control input-medium" name="nomorcek"  id="nomorcek" value="" >																																																																												    													            
															  </div>
															</div>
														   </div>
													</div>												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal Cek</label>
													        <div class="col-md-4">
														        <input id="tanggalcek" name="tanggalcek" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													
												</div>
												<div class="row">
												  <div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Nilai Pembayaran</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                                 <input type="text" class="form-control input-medium rightJustified" name="jumlahbayar"  id="jumlahbayar" value="0" readonly>	
																 <span class="input-group-btn">
																	<a class="btn green" onclick="total()"><i class="fa fa-refresh"></i></a>
															     </span>	
													          </div>
															  
															</div>
                                                           </div>
													</div>
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Keterangan</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                                 <input type="text" class="form-control input-large" name="keterangan"  id="keterangan" value="" >	
																 
													          </div>
															  
															</div>
                                                           </div>
													</div>
												</div>
												
												<div class="row">
												 <div class="col-md-12">
                                                   	
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													  <thead>
                                                      <tr>
                    									<th width="12%" style="text-align: center">No. Faktur</th>
                    									<th width="10%" style="text-align: center">Tgl. Faktur</th>
														<th width="10%" style="text-align: center">Total Faktur</th>
														<th width="10%" style="text-align: center">Terhutang</th>
														<th width="10%" style="text-align: center">Uang Muka</th>
														<th width="10%" style="text-align: center">Bayar</th>
														<th width="5%" style="text-align: center">Diskon</th>
														<th width="15%" style="text-align: center">Pembayaran</th>                    									
                    									
                    								</tr>
                    								<thead>
													
                    								<tbody>
													<tr>													   
                                                        
                                                        <td width="12%" ><input name="faktur[]"    id="faktur1" type="text" class="form-control "  onkeypress="return tabE(this,event)" readonly></td>
                                                        <td width="10%" ><input name="tglfaktur[]"    id="tglfaktur1" type="text" class="form-control "  onkeypress="return tabE(this,event)" readonly></td>
														<td width="10%" ><input name="totfaktur[]"    id="totfaktur1" type="text" class="form-control rightJustified"  readonly></td>
														<td width="10%" ><input name="hutang[]"    id="hutang1" type="text" class="form-control rightJustified"  readonly></td>
														<td width="10%" ><input name="uangmuka[]"    id="uangmuka1" type="text" class="form-control rightJustified"  readonly></td>
														<td width="10%" ><input name="bayar[]"  onchange="totalline(1)" value="0" id="bayar1" type="text" class="form-control rightJustified" onclick="formatCurrency(this)"></td>														
														<td width="5%"  ><input name="disc[]"   onchange="totalline(1)" value="0" id="disc1" type="text" class="form-control rightJustified "  onclick="formatCurrency(this)"></td>
                                                        <td width="15%" ><input name="jumlah[]"  id="jumlah1"; type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
                                                       
								                      </tr>
                    								
								                    </tbody>
													
													
													</table>
													
								                    </div>
								                   </div>
												</div>
												

											
							</div>
							<!-- tab1-->
							
							
							
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


var idrow  = 2;

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
	 	
	td1.innerHTML="<input name='faktur[]'      id=faktur"+idrow+" type='text' class='form-control'  readonly>";
	td2.innerHTML="<input name='tglfaktur[]'   id=tglfaktur"+idrow+" type='text' class='form-control'  readonly>";
	td3.innerHTML="<input name='totfaktur[]'   id=totfaktur"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  readonly>";
	td4.innerHTML="<input name='hutang[]'      id=hutang"+idrow+" type='text' class='form-control rightJustified' readonly >";
	td5.innerHTML="<input name='uangmuka[]'    id=uangmuka"+idrow+" type='text' class='form-control rightJustified' readonly >";
	td6.innerHTML="<input name='bayar[]'       id=bayar"+idrow+" onchange='totalline("+idrow+")'  type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";	
	td7.innerHTML="<input name='disc[]'        id=disc"+idrow+" onchange='totalline("+idrow+")' type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";
	td8.innerHTML="<input name='jumlah[]'      id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%' readonly>";
	
    idrow++;
}



 function post_harga(v1, v2)
  {	
	 id=document.getElementById("nopilihharga").value;
	 document.getElementById("sat"+id).value = v2;	 
	 document.getElementById("harga"+id).value = v1;	      
	 totalline(id);
  } 
  

function getid(id)
{
	document.getElementById("nopilih").value = id;
}

function getidakun(id)
{
	document.getElementById("nopilihakun").value = id;
}


function getidharga(id)
{
	var vid = id.substring(8);
	document.getElementById("nopilihharga").value = vid;
	var supp = document.getElementById("supp").value;
	var item = document.getElementById("kode"+vid).value;
	var param= supp+'~'+item;
	document.getElementById("namabarangharga").innerHTML=document.getElementById("namabarang"+vid).value;
	showharga(param);
}



function save()
{	        
    var noform   = $('[name="nomorbukti"]').val();    
	var tanggal  = $('[name="tanggal"]').val();    
	var jumlahbayar = $('[name="jumlahbayar"]').val(); 
    if(jumlahbayar=="0" || jumlahbayar=="0.00"){
		swal('FINANCE-Pembayaran Hutang','Jumlah pembayaran belum diisi ...',''); 
	} else {
	$.ajax({				
		url:'<?php echo site_url('pembelian_bayar/save/1')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		 success:function(data){ 
		  swal({
					  title: "FINANCE-Pembayaran Hutang",
					  html: "<p> No. Bukti   : <b>"+noform+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>pembelian_bayar";
		         });				
	
		},
		error:function(data){
			swal('FINANCE-Pembayaran Hutang','Data gagal disimpan ...',''); 
								
					
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
	
  function total()
  {
    
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tjumlah = 0;
   tdiskon = 0; 
   
   for(var i=1; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[5].children[0].value;	
	diskon      = row.cells[6].children[0].value;    
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));	
	var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1);			
   	tdiskon  = tdiskon  + eval(diskon1);			
   } 

   document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);
   document.getElementById("_vdiskon").innerHTML=formatCurrency1(tdiskon);   
   document.getElementById("_vtotal").innerHTML=formatCurrency1(tjumlah-tdiskon);
   document.getElementById("jumlahbayar").value=formatCurrency1(tjumlah-tdiskon);
  }
  
  function totalline(id)
  {
   	  
   var table = document.getElementById('datatable');
   var row = table.rows[id];       
   uangmuka    = row.cells[4].children[0].value;    
   jumlah      = row.cells[5].children[0].value;    
   diskon      = row.cells[6].children[0].value;
   
   hutang      = row.cells[3].children[0].value;    
   
   var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
   var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));   
   var hutang1 = Number(hutang.replace(/[^0-9\.]+/g,""));
   var uangmuka1 = Number(uangmuka.replace(/[^0-9\.]+/g,""));   
   
   if((jumlah1+uangmuka1)>hutang1){
	  swal('PEMBELIAN','Jumlah pembayaran melebihi Hutang ...','');  
	  row.cells[5].children[0].value = 0; 
   }
   
   
   tot         = eval(jumlah1) - eval(diskon1);
   row.cells[7].children[0].value= formatCurrency1(tot);   
   total();
   
  }
  
   
  
function showfaktur() {
  var xhttp;
  var str = $('[name="supp"]').val(); 
  
  if (str == "") {
    document.getElementById("listpo").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("listpo").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_bayar/getlistfaktur/"+str, true);  
  xhttp.send();
}

function cekbayar(id){
  var faktur = $('').val();
  var bayar  = $('').val();
  
  
}

function getfaktur() { 
  var xhttp;      
  var str = $('[name=supp]').val();
  hapus();							
	$('[id=faktur1]').val('');
	$('[id=tglfaktur1]').val('');
	$('[id=totfaktur1]').val('');
	$('[id=hutang1]').val('');
	$('[id=uangmuka1]').val('');
	$('[id=bayar1]').val('');
	$('[id=disc1]').val('');	
	totalline(1);
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_bayar/getfaktur/"+str,
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
			  document.getElementById("faktur"+x).value=data[i].kodepu;
			  document.getElementById("tglfaktur"+x).value=data[i].tglpu;		    
			  document.getElementById("totfaktur"+x).value=formatCurrency1(data[i].total);		    
              document.getElementById("hutang"+x).value=formatCurrency1(data[i].hutang);		    
			  document.getElementById("uangmuka"+x).value=formatCurrency1(data[i].uangmuka);		    
			  document.getElementById("bayar"+x).value=0;				  
			  document.getElementById("disc"+x).value=0;
			  totalline(x);
			}
			
		}
	});	    
  }	
}


window.onload = function(){
        document.getElementById('nomorbukti').focus();
};


</script>



			
	
</body>
</html> 
