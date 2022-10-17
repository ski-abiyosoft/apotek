<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
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
					<!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->
					
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
										<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
									<?php } ?>
									</select>
									<span class="input-group-btn">
											<a class="btn green" onclick="showpo()"><i class="fa fa-refresh"></i></a>
										</span>	
									 </div>	 
									</div>
									</div>

							</div>
								
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">No. Faktur #</label>
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
							
							
							
						</div>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">No. SO</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <div id="listpo"></div>																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Jumlah SO</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="jumlahso" id="jumlahso" value="0" readonly>
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
										 <input type="text" class="form-control input-medium" placeholder="" name="uangmuka" id="uangmuka" value="0" >																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Keterangan</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="keterangan" id="keterangan" value="" >
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
										<option value="<?php echo $row->kodeakun;?>"><?php echo $row->namaakun;?></option>
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
										<input type="text" class="form-control input-medium" placeholder="" name="nokartu" id="nokartu" value="" >
									</div>

								</div>
							</div>
						</div>
					    <div class="form-actions">
							<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-plus"></i> Data Baru</button>

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
    var tanggal   = $('[name="tanggal"]').val(); 
    var noform   = $('[name="nomorbukti"]').val();    	
    var kodeso   = $('[name="kodeso"]').val();    

	if(kodeso==""){
	  swal('PENJUALAN','Data Belum Lengkap/Belum ada transaksi ...','');   	
	}  else {
		    
	$.ajax({				
		url:'<?php echo site_url('penjualan_uangmuka/save/1')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  swal({
					  title: "UANG MUKA PENJUALAN",
					  html: "<p> No. Bukti   : <b>"+noform+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>penjualan_uangmuka";
		         });
		},
		error:function(data){
			swal('FAKTUR PENJUALAN','Data gagal disimpan ...','');		
					
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

	
window.onload = function(){
        document.getElementById('nomorbukti').focus();
};


</script>



	
</body>
</html> 
