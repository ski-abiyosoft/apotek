<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


				<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Payroll <small>Laporan</small>
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
							<a href="#">
                              Payroll
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                              Laporan
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

					<div class="note note-success">
						<p>
                         Laporan - laporan untuk Payroll
                         <br>
						</p>
					</div>

					<br>

					<div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Parameter Laporan
										</div>

									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmlaporan" class="form-horizontal form-bordered1" method="post"  >
											<div class="form-body">
											    <div class="row">												    												
													<div class="col-md-12">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Nama Laporan</label>
													        <div class="col-md-9">
														       <select id="idlap" name="idlap" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih...">
																		<option data-subtext="101" value="101">Daftar Gaji Karyawan</option>	
                                                                        <option data-subtext="102" value="102">Slip Gaji Karyawan</option>																			
																		<option data-subtext="103" value="103">Rekap Gaji Karyawan</option>	
																	
																																	
																</select>
													        </div>



														</div>
													</div>													
													
													
												</div>
											    <div class="row">												    												
													<div class="col-md-12">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Periode</label>
													        <div class="col-md-3">
														        <select id="bulan" name="bulan" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih...">
																 <option value="NONE">&nbsp;</option>
																 <?php
																   $bulan = date('n');
																   $tahun = date('Y');
																   for($i=1;$i<=12;$i++)
																   { ?>
																	 <option value="<?php echo $i;?>" <?php if($bulan==$i){ echo "selected=true";}?>><?php echo $this->M_global->_namabulan($i);?></option>
																   <?php 
																   }
																 
																 ?>

																</select>
															   
													        </div>
															<div class="col-md-2">
																  <input maxlength="4" type="text" size="3" name="tahun" id="tahun" class="form-control" value="<?php echo $tahun;?>" />
															</div>



														</div>
													</div>
													
													
													
												</div>
												
																								
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Karyawan</label>
													        <div class="col-md-9">
														      <select name="karyawan" id="karyawan" class="form-control input-largex select2me" onchange="showbarangname(this.value, 1)">
															  <option value="">--- Pilih Karyawan ---</option>
															  <?php
																  foreach($karyawan as $row) { ?>
																	<option data-subtext="" value="<?php echo $row->id;?>"><?php echo $row->nama;?></option>
																<?php } ?>
															 </select>																														    
													            
													     	</div>
                                                           </div>
													</div>
														
													
												</div>	
												
												
												
												
                                                
											<div class="form-actions fluid">
												<div class="col-md-offset-3 col-md-9">
												   <a class="btn green print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" >Tampilkan</a>
												
                                                    <br />
                                                    <h4>
													<div class="err" id="resultss"></div>
													</h4>
													
													<div >
													  <img id="proses" src="<?php echo base_url();?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden"/>
													</div>
												</div>
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
 
?>

<script>

function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var idlap = $('[name="idlap"]').val();				
	var bulan = $('[name="bulan"]').val();				
	var tahun = $('[name="tahun"]').val();				
	var karyawan = $('[name="karyawan"]').val();			
	var param= 'idlap='+idlap+'&bulan='+bulan+'&tahun='+tahun+'&karyawan='+karyawan;	
    return baseurl+'hrd_laporan/cetak/?'+param;
	
}

	
</script>