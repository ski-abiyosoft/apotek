<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  <?= $modul; ?> <small><?= $submodul; ?></small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="<?php echo base_url();?><?= $url;?>">
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
                                   <?= $submodul;?>
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
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Pemasok</label>
													        <div class="col-md-9">
                                                              <select id="supp" name="supp" class="form-control select2_el_vendor" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">            												 
                                                            
            												  </select>
													        </div>
													        </div>

													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor PO#</label>
													        <div class="col-md-4">
															    <input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?= $nomorpo;?>" readonly>																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal PO</label>
													        <div class="col-md-4">
														       
															    <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">No. Ref</label>
													        <div class="col-md-4">
														        <input type="text" class="form-control input-medium" placeholder="" name="noref" id="noref" value="">
													        </div>

														</div>
													</div>
												
													
												</div>
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal Kirim</label>
													        <div class="col-md-4">
														        <input id="tanggalkirim" name="tanggalkirim" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />													    	   
													        </div>



														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Kurs</label>
													        <div class="col-md-4">
														        <input type="text" name="kurs" class="form-control" value="">
													        </div>
															<label class="col-md-2 control-label">Rate</label>
													        <div class="col-md-3">
														        <input type="text" name="rate" class="form-control" value="0">
													        </div>

														</div>
													</div>
												
													
												</div>
												
												
												

												<div class="row">
												 <div class="col-md-12">
                                                   	
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													
                                                    <thead>
                    									<th width="35%" style="text-align: center">Nama Barang</th>
														<th width="10%" style="text-align: center">Kuantitas</th>
														<th width="10%" style="text-align: center">Satuan</th>
														<th width="15%" style="text-align: center">Harga</th>
														<th width="10%" style="text-align: center">Diskon</th>
														<th width="5%" style="text-align: center">Tax</th>
														<th width="15%" style="text-align: center">Total Harga</th>                    									
                    									
                    								</thead>
                    								
                    								<tbody>
													<tr>													   
                                                        <td width="35%">
														    <select name="kode[]" id="kode1" class="select2_el_log_barang form-control input-largex" onchange="showbarangname(this.value, 1)">
															 
															</select>												
														</td>
                                                       
                                                		<td width="10%" ><input name="qty[]"    onchange="totalline(1);total()" value="1" id="qty1" type="text" class="form-control rightJustified"  ></td>
														<td width="10%" ><input name="sat[]"    id="sat1" type="text" class="form-control "  onkeypress="return tabE(this,event)"></td>
														<td width="15%" ><input name="harga[]"  onchange="totalline(1);total()" value="0" id="harga1" type="text" class="form-control rightJustified"  ></td>
														<td width="10%"  ><input name="disc[]"   onchange="totalline(1);total()" value="0" id="disc1" type="text" class="form-control rightJustified "  ></td>
                                                        <td><input type="checkbox" name="tax[]" id="tax1" class="form-control" onchange="totalline(1);total()"></td>
														
														<td width="20%" ><input name="jumlah[]"  id="jumlah1" type="text" class="form-control rightJustified" size="40%"></td>
                                                       
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
                                  <div class="col-xs-9">
									
								  </div>	  
								</div>
							</div>
							<!-- tab2-->
							
						</div><!--tab-->	
						
						<div class="row">
							<div class="col-xs-9">
								<div class="wells">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
                                       
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
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
	 
	var akun="<select name='kode[]' id=kode"+idrow+" onchange='showbarangname(this.value,"+idrow+")' class='select2_el_log_barang form-control' >";
	td1.innerHTML=akun;
	td2.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
	td3.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
	td4.innerHTML="<input name='harga[]'  id=harga"+idrow+" onchange='totalline("+idrow+") value='0'  type='text' class='form-control rightJustified'>";
	td5.innerHTML="<input name='disc[]'   id=disc"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified'  >";
	td6.innerHTML="<input type='checkbox' name='tax[]'    id=tax"+idrow+" onchange='totalline("+idrow+")' class='form-control'>";													
	td7.innerHTML="<input name='jumlah[]' id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%'>";
	
	initailizeSelect2_log_barang();
    idrow++;
}


function showbarangname(str, id) {   
  
  var xhttp;     
  var vid = id;
   $.ajax({
        url : "<?php echo base_url();?>logistik_po/getinfobarang/?kode="+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
		    $('#sat'+vid).val(data.satuan1);
			$('#harga'+vid).val(formatCurrency1(data.hargabeli));
			totalline(vid);
		}
	});	
  
  
}

function save()
{	   
    var tanggal   = $('[name="tanggal"]').val(); 
    var nomor     = $('[name="nomorbukti"]').val(); 	
	var total     = $('#_vtotal').text(); 	
	
	if(nomor=="" || total=="0.00" || total==""){
	  swal('PURCHASE ORDER','Data Belum Lengkap/Belum ada transaksi ...','');   	
	} else {
			
	$.ajax({				
		url:"<?php echo site_url('logistik_po/ajax_add')?>",				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		success:function(data){        				
		  swal({
					  title: "PURCHASE ORDER",
					  html: "<p> No. Bukti   : <b>"+nomor+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>logistik_po";
		         });							
	
		},
		error:function(data){
			swal('PESANAN PEMBELIAN','Data gagal disimpan ...','');   	
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
   tppn    = 0;
   for(var i=1; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[1].children[0].value;
	harga       = row.cells[3].children[0].value;    
	diskon      = row.cells[4].children[0].value; 

    
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
	var harga1  = Number(harga.replace(/[^0-9\.]+/g,""));
	var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1*harga1);
	
	diskon      = eval((diskon1/100)*jumlah1*harga1);
	
   	tdiskon  = tdiskon + diskon;
	
	if(document.getElementById('tax'+i).checked==true){
		tppn = tppn + (eval(jumlah1*harga1)*0.1);
	} 
	
		  
    
   } 
  
   //tppn = 0;
   document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);
   document.getElementById("_vdiskon").innerHTML=formatCurrency1(tdiskon);
   document.getElementById("_vppn").innerHTML=formatCurrency1(tppn);
   document.getElementById("_vtotal").innerHTML=formatCurrency1(tjumlah-tdiskon+tppn);
 

  }
  
  function totalline(id)
  {      
   var table = document.getElementById('datatable');
   var row = table.rows[id];        
   var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g,"")); 
   jumlah      = row.cells[1].children[0].value*harga;    
   diskon      = (row.cells[4].children[0].value/100)* jumlah;
   
   tot         = jumlah - diskon;
   
   
   if(document.getElementById('tax'+id).checked==true){	  
	  tot = tot*1.1;
   } 
    
   row.cells[6].children[0].value= formatCurrency1(tot);   
   total();
   
   
   
  }
  

</script>

							
</body>
</html>

