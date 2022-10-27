<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');
    date_default_timezone_set("Asia/Jakarta");		
?>	

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">KLINIK <small>Electronic Medical Report</small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						
					</ul>
				</div>
			</div>
            <div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>Electronic Medical Report
					</div>
					
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmkonsul" class="form-horizontal" method="post">
				    <div class="form-body">
					
					  <div class="row">
							<div class="col-md-6">
								<div class="note note-info">
								    <div class="form-group">
									<label class="col-md-3 control-label">Rekmed <font color="red">*</font></label>
									<div class="col-md-9">
									  <select class="form-control select2_el_pasien" id="rekmed" name="rekmed" onchange="getdataemr()">
									  </select>
									</div>
									</div>
									
									
									
									
								
								 </div>	
							</div>													
							<div class="col-md-6">
								<div class="note note-success">
									<!--div class="form-group">
									  <label class="col-md-3 control-label">No. MR <font color="red"></font></label>
									  <div class="col-md-9">
										<input type="text" value="" class="form-control" id="pasien" name="pasien" readonly>
									  </div>
									</div-->
									
									<div class="form-group">
									  <label class="col-md-3 control-label">Nama Pasien <font color="red"></font></label>
									  <div class="col-md-9">
										<input type="text" value="" class="form-control" id="namapasien" name="namapasien" readonly>
									  </div>
									</div>
									
									
								 </div>	
							</div>													
					  </div>	
						
                      <div class="row">
							
							<div class="col-xs-6 invoice-block">
							  </br>
							  <span class="label label-success">REKAM MEDIS HISTORY</span>
							  <table id="datatable_emr" class="table  table-condensed table-scrollable">
								<thead  class="breadcrumb">
									<th width="20%" style="text-align: center">Tanggal</th>
									<th width="30%" style="text-align: center">Cabang</th>									
									<th width="40%" style="text-align: center">Dokter</th>
                                    <th width="10%">&nbsp;</th>									
								</thead>													
								<tbody>													
								</tbody>								 
							  </table>
							</div>
							
							<div class="col-xs-6 invoice-block">
							  </br>
							  <!--span class="label label-info">DETAIL</span-->
							  
							  <div class="tabbable tabbable-custom tabbable-full-width">
								<ul class="nav nav-tabs">
									<li class="active">
										<a href="#tab1" data-toggle="tab">
										   Konsultasi 
										</a>
									</li>
									<li class="">
										<a href="#tab2" data-toggle="tab">
										   Tindakan
										</a>
									</li>
									
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab1">		
										<div class="col-md-12">
												<div class="form-group">
												  <label class="col-md-3 control-label">Keluhan Awal <font color="red"></font></label>
												  <div class="col-md-9">
													<textarea class="form-control" id="keluhanawal" name="keluhanawal" readonly></textarea>
												  </div>
												</div>
												
												<div class="form-group">
												  <label class="col-md-3 control-label">Diagnosa <font color="red"></font></label>
												  <div class="col-md-9">
													<textarea class="form-control" id="diagnosa" name="diagnosa" readonly></textarea>
												  </div>
												</div>
												<div class="form-group">
												  <label class="col-md-3 control-label">Pemeriksaan <font color="red"></font></label>
												  <div class="col-md-9">
													<textarea class="form-control" id="pemeriksaan" name="pemeriksaan" readonly></textarea>
												  </div>
												</div>
												<div class="form-group">
												  <label class="col-md-3 control-label">Obat/Resep <font color="red"></font></label>
												  <div class="col-md-9">
													<textarea class="form-control" id="obat" name="obat" readonly></textarea>
												  </div>
												</div>
												<div class="form-group">
												  <label class="col-md-3 control-label">Keterangan <font color="red"></font></label>
												  <div class="col-md-9">
													<textarea class="form-control" id="keterangan" name="keterangan" readonly></textarea>
												  </div>
												</div>
												
												
											
							</div>				
									</div>
									
									<div class="tab-pane" id="tab2">	
									  

									</div>
										
							   </div> 
									
									
									
									
								</div><!--tab-->  
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
  $this->load->view('template/footer_tb');  
?>

<script>


function getdataklinik() { 
  var xhttp;      
  var str = $('[name=reg_klinik]').val();
  if(str==""){
	
  }  else  {
	initailizeSelect2_register(str);

  }	
}
  
function getdataemr() { 
  var xhttp;      
  var str = $('[name=rekmed]').val();
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>emr/getdataemr/?rekmed="+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		      
		  //$('#pasien').val(data.rekmedpas);
		  $('#namapasien').val(data.namapas);
		  getdatakonsul();		  
		}
	});	    
  }	
}

  

function getdatakonsul() { 
  var xhttp;      
  var str = $('[name=rekmed]').val();
  $('#datatable_emr tbody').empty();  
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>emr/getdatakonsul/?rekmed="+str,
        type: "GET",        
        success: function(data)
        {		      		  
          $('#datatable_emr tbody').append(data);
		}
	});	    
  }	
}

  
function getdetil( tgl ) { 
  var xhttp;      
  var str = $('[name=rekmed]').val();
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>emr/getdataemr_detil/?rekmed="+str+'&tanggal='+tgl,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		      
		  
		  $('#keluhanawal').val(data.keluhanawal);
		  $('#diagnosa').val(data.diagnosa);
		  $('#pemeriksaan').val(data.pfisik);
		  $('#obat').val(data.resep);
		  $('#keterangan').val(data.anjuran);
		  
		}
	});	    
  }	
}
  
window.onload = function(){
     	//document.getElementById('btnsimpan').disabled=true;
		document.getElementById('tersimpan').value="";
};

	
</script>


	
</body>
</html> 
