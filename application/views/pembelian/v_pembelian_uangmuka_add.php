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
                      <span class="title-web">Pembelian <small>Uang Muka Pembelian</small>
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
							<a href="<?php echo base_url();?>pembelian_uangmuka">
                               Daftar Uang Muka Pembelian
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
				
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpembelian" class="form-horizontal" method="post">
					<div class="form-body">
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
											<a class="btn green" onclick="showpo()"><i class="fa fa-refresh"></i></a>
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
								   <label class="col-md-3 control-label">No. PO</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <select id="kodepo" name="kodepo" class="form-control input-medium select2me">
										   <option value="">---Tanpa PO---</option>
										 </select>
										 <span class="input-group-btn">
											<a class="btn green" onclick="getpoheader();"><i class="fa fa-refresh"></i></a>
										</span>	
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Jumlah PO</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="jumlahpo" id="jumlahpo" value="0" readonly>
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
									<label class="col-md-3 control-label">Rek. Tujuan</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="norek" id="norek" value="" >
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
  $this->load->view('template/v_report');
?>

<script>



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
	var tanggal  = $('[name="tanggal"]').val(); 
    if(nofaktur==""){
		swal('PEMBELIAN','Nomor Faktur belum diisi ...',''); 
	} else {
	$.ajax({				
		url:'<?php echo site_url('pembelian_uangmuka/save/1')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		success:function(data){ 
		 swal({
					  title: "PEMBELIAN",
					  html: "<p> No. Faktur   : <b>"+noform+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>pembelian_uangmuka";
		         });					
	
		},
		error:function(data){
			swal('PEMBELIAN','Data gagal disimpan ...',''); 
					
		}
		});
	}	
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_uangmuka/getlistpo/"+str, true);  
  xhttp.send();
}

function getpo() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  alert(str);
  if(str==""){
	$('[id=jumlahpo]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_uangmuka/getpo/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
			$('[name=jumlahpo]').val(data.dpp);
		}
	});	    
  }	
}

function getpoheader() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  if(str==""){	
  //$('[name="jumlahpo"]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_uangmuka/getpoheader/"+str,
        type: "GET",
        dataType: "JSON",
		 
        success: function(data)
        {		            	                   
             total = parseInt(data.dpp)+parseInt(data.ppn)-parseInt(data.diskon)+parseInt(data.biayalain);		
			 $('[name="jumlahpo"]').val(total);
		}
	});	    
  }	
}

	
window.onload = function(){
        document.getElementById('nomorbukti').focus();
};

$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('PEMBELIAN');
		
		var param= $('[name="nomorbukti"]').val();
				
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>pembelian_uangmuka/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});	
	
</script>



	
</body>
</html> 
