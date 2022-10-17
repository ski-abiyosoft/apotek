<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
	foreach($header as $rowh){};
?>	

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Penjualan <small>Pengiriman Pesanan</small>
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
							<a href="<?php echo base_url();?>penjualan_pengiriman">
                               Daftar Pengiriman Pesanan
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Pengiriman Pesanan
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>Edit Data
					</div>
					<!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpenjualan" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   <i class="fa fa-file"></i> 
								   Pengiriman
								</a>
							</li>
							<li class="">
								<a href="#tab2" data-toggle="tab">                                   
								   <i class="fa fa-info-circle"></i>
								   Info
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Kirim Ke</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                              <select id="cust" name="cust" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												 
                                                            <?php
                                                              foreach($cust as $row) { ?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
                                                            <?php } ?>
            												</select>																														    
													           
													        </div>
															</div>
                                                           </div>
													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor #</label>
													        <div class="col-md-4">
															    <input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?php echo $rowh->kodekirim;?>">																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
											
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-4">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y', strtotime($rowh->tglkirim));?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													    	   </div>
													        </div>
														</div>
													</div>
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">No. Pesanan</label>
													        <div class="col-md-6">
															  <div class="input-group">
     															  <input type="text" class="form-control input-medium" id="kodeso" name="kodeso" value="<?php echo $rowh->kodeso;?>">	    
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
                    									<th width="50%" style="text-align: center">Kode/Nama Barang</th>
                    									<th width="20%" style="text-align: center">Kuantitas</th>
														<th width="30%" style="text-align: center">Satuan</th>
														
                    								</tr>
                    								<thead>
													
                    								<tbody>
													<?php
													$no=1;
													foreach($detil as $row){?>
													<tr>													   
                                                        <td width="50%">
															<select name="kode[]" id="kode<?=$no;?>" class="select2_el_barang form-control" onchange="showbarangname(this.value, <?= $no;?>)">
															  <option value="<?= $row->kodeitem;?>"><?= $row->namabarang;?></option>
															</select>												
														</td>	
                                                       
                                                        <td width="30%" ><input name="qty[]"    value="<?php echo $row->qtykirim;?>" id="qty<?php echo $no;?>" type="text" class="form-control rightJustified"  ></td>
														<td width="20%" ><input name="sat[]"    value="<?php echo $row->satuan;?>"id="sat<?php echo $no;?>" type="text" class="form-control "  onkeypress="return tabE(this,event)"></td>
														
								                      </tr>
                    								<?php $no++; } ?>
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
											   <label class="col-md-3 control-label">Gudang</label>
												<div class="col-md-6">
												  <div class="input-group">
												  <select id="gudang" name="gudang" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
												 
												<?php
												  foreach($gudang as $row) { ?>
													<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
												<?php } ?>
												</select>																														    
													
												</div>
												</div>
											   </div>
										      </div>	
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Keterangan</label>
													<div class="col-md-4">
														<input type="text" class="form-control input-large" placeholder="" name="keterangan" id="keterangan" value="<?php echo $rowh->keterangan;?>">
													</div>

												</div>
											</div>																									
									    </div>
										<div class="row">
										   <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Alamat</label>
													<div class="col-md-9">
														<textarea class="form-control" name="alamat" id="alamat" ><?php echo $rowh->alamat;?></textarea>
													</div>
												</div>
											</div>	
                                            										
										</div>									
								   </div> 
								</div>
                                
							</div>
							<!-- tab2-->
							
						</div><!--tab-->	
						
						<div class="row">
							<div class="col-xs-12">
								<div class="well">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									<a class="btn yellow print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" ><i class="fa fa-print"></i> Cetak</a>
                                       
									<div class="btn-group">
										<a href="<?php echo base_url()?>penjualan_pengiriman/entri" class="btn btn-success">
										<i class="fa fa-plus"></i>
										Data Baru
										</a>
									</div>	
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
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

var idrow  = <?php echo $jumdata1+1;?>;

function tambah(){
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
	
	var akun="<select name='kode[]' id=kode"+idrow+" onchange='showbarangname(this.value,"+idrow+")' class='select2_el_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
	td1.innerHTML=akun;
	td2.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
	td3.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
	initailizeSelect2_barang();
    idrow++;
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
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pengiriman/getharga/"+str, true);  
  xhttp.send();
}


function showbarangname(str, id) {   
  var xhttp; 
  var vid = id;
   $.ajax({
        url : "<?php echo base_url();?>penjualan_pengiriman/getinfobarang/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
			$('#sat'+vid).val(data.satuan);			
		}
	});	
  
  
}


function save()
{	      
    var noform   = $('[name="nomorbukti"]').val(); 
	var tanggal  = $('[name="tanggal"]').val(); 
	if(noform==""){
		swal('PENJUALAN','Nomor surat jalan / no. pengiriman belum diisi ...',''); 
	} else {     
	$.ajax({				
		url:'<?php echo site_url('penjualan_pengiriman/save/2')?>',				
		data:$('#frmpenjualan').serialize(),				
		type:'POST',
		success:function(data){        		
		swal({
					  title: "PENJUALAN",
					  html: "<p> No. Surat Jalan   : <b>"+noform+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>penjualan_pengiriman";
		         });									
	
		},
		error:function(data){
			swal('PENJUALAN','Data gagal disimpan ...',''); 
		}
		});
	}	
}	

	function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
		}
	}


function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var param= $('[name="nomorbukti"]').val();				
    return baseurl+'penjualan_pengiriman/cetak/'+param;
}
  

window.onload = function(){
        document.getElementById('nomorbukti').focus();
};


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

