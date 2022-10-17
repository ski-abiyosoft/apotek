<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    
    foreach($header as $rowh){};	
?>	

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Penjualan <small>Uang Muka Penjualan</small>
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
							<a href="<?php echo base_url();?>penjualan_uangmuka">
                               Daftar Uang Muka Penjualan
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Data
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
				
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpembelian" class="form-horizontal" method="post">
					<div class="form-body">
					   <div class="row">
							<div class="col-md-6">
								 <div class="form-group">
								   <label class="col-md-3 control-label">Pelanggan</label>
									<div class="col-md-6">
									 <div class="input-group">
									  <select id="cust" name="cust" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
									 
									<?php
									  foreach($cust as $row) { ?>
										<option value="<?php echo $row->kode;?>" <?php if($rowh->kodecust==$row->kode) { echo "selected=true";}?>><?php echo $row->nama;?></option>
									<?php } ?>
									</select>
									
									 </div>	 
									</div>
									</div>

							</div>
								
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">No. Faktur #</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?php echo $rowh->kodeum;?>" readonly>																																															
										
									</div>

								</div>
							</div>
						</div>	
						
						
						<div class="row">												    												
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-3 control-label">Tanggal</label>
									<div class="col-md-4">
									   <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d',strtotime($rowh->tglum));?>" />
									   
									</div>



								</div>
							</div>
							
							
							
						</div>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">No. SO</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <input type="text" name="kodeso" value="<?php echo $rowh->kodeso;?>" class="form-control input-medium" />																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Jumlah SO</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="jumlahso" id="jumlahso" value="<?php echo $rowh->jumlahso;?>" readonly>
									</div>

								</div>
							</div>
						</div>
					    <h4><b><i>Pembayaran</i></b></h4>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">Uang Muka</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <input type="text" class="form-control input-medium rightJustified" placeholder="" name="uangmuka" id="uangmuka" value="<?php echo $rowh->jumlahum;?>" >																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Keterangan</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="keterangan" id="keterangan" value="<?php echo $rowh->keterangan;?>" >
									</div>

								</div>
							</div>
						</div>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">Kas/Bank</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <select id="kasbank" name="kasbank" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
									<?php
									  foreach($bank as $row) { ?>
										<option value="<?php echo $row->kodeakun;?>" <?php if($rowh->kodebank==$row->kodeakun) { echo "selected=true";}?>><?php echo $row->namaakun;?></option>
									<?php } ?>
									</select>																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">No. Kartu</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="nokartu" id="nokartu" value="<?php echo $rowh->nomorkartu;?>" >
									</div>

								</div>
							</div>
						</div>
					    <div class="form-actions">
							<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							<a class="btn yellow print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" ><i class="fa fa-print"></i> Cetak</a>
							<div class="btn-group">
								<a href="<?php echo base_url()?>penjualan_uangmuka/entri" class="btn btn-success">
								<i class="fa fa-plus"></i>
								Data Baru
								</a>
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

function save()
{	        
    var noform   = $('[name="nomorbukti"]').val();    	
    var kodeso   = $('[name="kodeso"]').val();    	
    if(kodeso==""){
		$.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Nomor SO untuk formulir '+noform+' masih kosong ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'gritter-red',
					});
	} else {
	$.ajax({				
		url:'<?php echo site_url('penjualan_uangmuka/save/2')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  $.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Uang Muka dengan nomor '+noform+' BERHASIL disimpan ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color success',
					});					
	
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
			
			$.gritter.add({
						title: '<b><?php echo 'Penjualan';?></b>',
						text: 'Uang Muka dengan nomor '+noform+' GAGAL disimpan, periksa kembali data yang dimasukan dan lakukan SIMPAN ulang ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color warning',
					});
								
					
		}
		});
	}	
}	

	
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
    document.getElementById("listpo").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_uangmuka/getlistpo/"+str, true);  
  xhttp.send();
}

function getpo() { 
  var xhttp;      
  var str = $('[name=kodeso]').val();
  if(str==""){
	$('[id=jumlahso]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>penjualan_uangmuka/getpo/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
			$('[name=jumlahso]').val(data.totalso);
		}
	});	    
  }	
}

function getpoheader() { 
  var xhttp;      
  var str = $('[name=kodeso]').val();
  if(str==""){	
  //$('[name="jumlahpo"]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>penjualan_uangmuka/getpoheader/"+str,
        type: "GET",
        dataType: "JSON",
		 
        success: function(data)
        {		            	                   
             total = data.totalso;		
			 $('[name="jumlahso"]').val(total);
		}
	});	    
  }	
}

function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var param= $('[name="nomorbukti"]').val();				
    return baseurl+'penjualan_uangmuka/cetak/'+param;
}

</script>



	
</body>
</html> 
