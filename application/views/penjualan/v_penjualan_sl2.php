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
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>






			<!--div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Penjualan <small>Entri Penjualan</small>
					</h3>
                   
				</div>
			</div-->
            <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Penjualan
										</div>
										
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmsales1" action="#" class="form-horizontal" method="post">
											<div class="form-body">
												<!--h4 class="form-section">Deskripsi</h4-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">SI No.</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control input-small" value="<?php echo $nosi;?>" name="slno" readonly>
													        </div>

														</div>
													</div>
												</div>
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-6">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-small" type="text" value="<?php echo date('d-m-Y')?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													    	   </div>
													        </div>
													        


														</div>
													</div>
												</div>
												
												<div class="row">												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Kode Cust</label>
													        <div class="col-md-6">
															  <div class="input-group">														        
                                                              <select name="cust" class="form-control input-large select2me"  onchange="getcustomer(this.value)">            											
															  <option value="">-- Pilih ---</option>
                                                              <?php 
									                            foreach($cust  as $row){?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
																<span class="input-group-btn">
																	<a class="btn green" id="0" data-toggle="modal" href="#form_customer" ><i class="fa fa-plus"></i>   Add Cust</a>
																	
																</span>
																
														   										                
														      </div> 
                                                              
													        </div>

														</div>
													</div>
												</div>
	   											<div class="row">
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Nama</label>
													        <div class="col-md-9">
														        <input type="text" class="form-control"  name="nama" disabled>
													        </div>

														</div>
													</div>													
												</div>
												<div class="row">
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Alamat 1</label>
													        <div class="col-md-9">
														        <input type="text" class="form-control"  name="alamat1"  disabled>
													        </div>

														</div>
													</div>													
												</div>
												<div class="row">
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Alamat 2</label>
													        <div class="col-md-9">
														        <input type="text" class="form-control"  name="alamat2" disabled>
													        </div>

														</div>
													</div>													
												</div>
												<div class="row">
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">HP</label>
													        <div class="col-md-9">
														        <input type="text" class="form-control"  name="hp" disabled>
													        </div>

														</div>
													</div>													
												</div>
												
												
												
                    						<div class="form-actions">
	                                            <div class="col-md-6">
												<div class="form-group">
												<label class="col-md-3 control-label"></label>
												<button  type="button" onclick="#" class="btn blue">Next</button>                                                
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

<script>
 
function getcustomer(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>penjualan_sl/getcustomer/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
            $('[name="nama"]').val(data.nama);						
			$('[name="alamat1"]').val(data.alamat1);						
			$('[name="alamat2"]').val(data.alamat2);						
			$('[name="hp"]').val(data.hp);						
			
		}
	});	    
}

jQuery(document).ready(function() {   
   ComponentsPickers.init(); 
   
});


</script>


<div class="modal fade" id="form_customer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Data Customer</h4>				
			</div>
			<div class="modal-body">										 
			  <form id="customer" action="#" class="form-horizontal" method="post">
					<div class="form-body">
						
						<div class="row">												
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-6 control-label">Kode Cust</label>
									<div class="col-md-6">
                                         <input type="text" class="form-control"  name="newkode" >									 													
									</div> 
									  
									</div>

								</div>
							</div>
						
						<div class="row">
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-6 control-label">Nama</label>
									<div class="col-md-6">
										<input type="text" class="form-control input-large"  name="newnama" >
									</div>

								</div>
							</div>													
						</div>
						<div class="row">
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-6 control-label">Alamat 1</label>
									<div class="col-md-6">
										<input type="text" class="form-control input-large"  name="newalamat1"  >
									</div>

								</div>
							</div>													
						</div>
						<div class="row">
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-6 control-label">Alamat 2</label>
									<div class="col-md-6">
										<input type="text" class="form-control input-large"  name="newalamat2" >
									</div>

								</div>
							</div>													
						</div>
						<div class="row">
						    <div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-6">HP</label>
								<div class="col-md-6">
									<input name="newhp" placeholder="" class="form-control input-large"  type="text">
									
								</div>
							</div>
						
							
						</div>
						
						
					   </div>	
					</div>
					
					
				</form>
			</div>   
			<div class="modal-footer">
			  <p align="center">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			  </p>	
            </div>		            
		</div>									
	</div>								
</div>
														
							



</body>
</html>
