<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
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

			<div class="row">
				<div class="col-md-12">
                    <h3 class="page-title">
					Keuangan <small>Laporan</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url()?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                              Keuangan
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                              Laporan
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="tab-pane" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
													<i class="fa fa-angle-double-right"></i> Saldo Kas/Bank
												</a>
												<span class="after">
												</span>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_2-2">
													<i class="fa fa-angle-double-right"></i> Mutasi Kas Harian
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_3-3">
													<i class="fa fa-angle-double-right"></i> Penerimaan Kas/Bank
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_4-4">
													<i class="fa fa-angle-double-right"></i> Pengeluaran Kas/Bank
												</a>
											</li>
																						

										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
										
										     <div id="tab_1-1" class="tab-pane active">
												<form name="frmlap1" id="frmlap1" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                    <tr>
                                                      <td class="success">Unit Kerja</td>
                                                      <td class="warning">
                                                      <select id="unit1" name="unit1" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">            												
                                                            <?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            											
            										  </td>

            										</tr>
            										<tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">

            											<div class="row form-group">
													<div class="col-md-3">

														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal11" name="tanggal11" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai" size="16"/>
														</div>
													</div>
													<div class="col-md-3">

														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal12" name="tanggal12" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan" size="16"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>


                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="1" data-toggle="modal">Cetak Laporan</a> 
														<a href="javascript:_urlExport1()" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>
											</div>
										

											
											<div id="tab_2-2" class="tab-pane">
											    <form name="frmlap2" id="frmlap2" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
												    <tr>
                                                      <td class="success">Unit Kerja</td>
                                                      <td class="warning">
                                                      <select id="unit2" name="unit2" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">            												
                                                            <?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>            												
            										  </td>

            										</tr>
                                                    <tr>
                                                      <td class="success">Bank</td>
                                                      <td class="warning">
                                                      <select id="bank2" name="bank2" class="form-control input-sm select2me input-medium " data-placeholder="Pilih...">
            												 <?php
															 foreach($bank->result()as $row){
            												?>
            													<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_kode.' - '.$row->bank_nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>

                                                    <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">

            											<div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal21" name="tanggal21" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal22" name="tanggal22" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
            									       </td>
            										</tr>


                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="2" data-toggle="modal">Cetak Laporan</a> 
														<a href="#" onclick="javascript:_urlExport2();" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>

											</div>
											
									<div id="tab_3-3" class="tab-pane">
									   <form name="frmlap3" id="frmlap3" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                    <tr>
                                                      <td  class="success">Bank</td>
                                                      <td class="warning">

                                                      <select id="bank3" name="bank3" class="form-control input-sm select2me input-medium " data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
                                                           <?php
															 foreach($bank->result()as $row){
            												?>
            													<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_kode.' - '.$row->bank_nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>
            										</tr>
            										<tr>
                                                      <td class="success">Unit Kerja</td>
                                                      <td class="warning">
                                                      <select id="unit3" name="unit3" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												 <?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>   
            										  </td>

            										</tr>

            										<tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">

            											<div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal31" name="tanggal31" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal32" name="tanggal32" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
            									       </td>
            										</tr>


                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="3" data-toggle="modal">Cetak Laporan</a> 
														<a href="#" onclick="javascript:_urlExport3();" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>
									
                                    </div>
                                    
                                    <div id="tab_4-4" class="tab-pane">
									   <form name="frmlap4" id="frmlap4" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                    <tr>

                                                      <td  class="success">Bank</td>
                                                      <td class="warning">

                                                      <select id="bank4" name="bank4" class="form-control input-sm select2me input-medium " data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
                                                           <?php
															 foreach($bank->result()as $row){
            												?>
            													<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_kode.' - '.$row->bank_nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>
            										</tr>
            										
            										<tr>
                                                      <td class="success">Unit Kerja</td>
                                                      <td class="warning">
                                                      <select id="unit4" name="unit4" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												 <?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>   
            										  </td>

            										</tr>
													
													 <td class="success">Bidang/Sub-Bidang</td>
                                                      <td class="warning">
                                                      <select id="bidang4" name="bidang4" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">            												
													        <option value='NONE'>&nbsp</option>                                                            
															<?php
															 foreach($bidang->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>

            										<tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">

            											<div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal41" name="tanggal41" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal42" name="tanggal42" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
            									       </td>
            										</tr>


                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="4" data-toggle="modal">Cetak Laporan</a> 
														<a href="#" onclick="javascript:_urlExport4();" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>

                                    </div>
                                   </div>
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

<script>
    
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('KAS/BANK');
		if(this.id==1)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal11').value+'~'+document.getElementById('tanggal12').value+'~'+document.getElementById('unit1').value;
		} else
		if(this.id==2)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal21').value+'~'+document.getElementById('tanggal22').value+'~'+document.getElementById('unit2').value+
		'~'+document.getElementById('bank2').value;
		} else
		if(this.id==3)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal31').value+'~'+document.getElementById('tanggal32').value+'~'+document.getElementById('unit3').value+
		'~'+document.getElementById('bank3').value;
		} else
		if(this.id==4)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal41').value+'~'+document.getElementById('tanggal42').value+'~'+document.getElementById('unit4').value+
		'~'+document.getElementById('bank4').value+'~'+document.getElementById('bidang4').checked;
		}
		
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>keuangan_laporan/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	 jQuery(document).ready(function() {
        ComponentsPickers.init();
        });
	
	
	
</script>