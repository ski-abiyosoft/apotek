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
                      <span class="title-web">Pembelian <small>Laporan</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="../home.php">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                              Kas & Bank
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
                         Laporan - laporan untuk transaksi Kas & Bank
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
																	<optgroup label="Keuangan">	
																		<option data-subtext="101" value="101">Daftar Saldo Kas</option>	
																		<option data-subtext="102" value="102">Daftar Penerimaan </option>																			
																		<option data-subtext="103" value="103">Daftar Pengeluaran</option>	
																		<option data-subtext="104" value="104">Daftar Transfer</option>	
																	</optgroup>		
                                                                    																
																</select>
													        </div>



														</div>
													</div>													
													
													
												</div>
											    <div class="row">												    												
													<div class="col-md-12">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Mulai</label>
													        <div class="col-md-2">
														       
															    <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" placeholder="" />
													    	   
													        </div>
															<label class="col-md-2 control-label">s/d</label>
															<div class="col-md-2">
														        <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>"/>
													    	   
													        </div>



														</div>
													</div>
													
													
													
												</div>
												
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Kas & Bank</label>
													        <div class="col-md-9">
														      <select name="kasbank" id="kasbank" class="select2_el_kasbank form-control" >
															  </select>																												    
													       </div>
                                                           </div>
													</div>
														
													
												</div>													
												
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Cabang</label>
													        <div class="col-md-9">
														      <select name="cabang" id="cabang" class="select2_el_cabang form-control" >
																<option value="<?= $this->session->userdata("unit") ?>" selected><?= $cabang->namars ?></option>
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
   $this->load->view('template/v_report');
?>


<script>

function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var idlap= $('[name="idlap"]').val();				
	var tgl1 = $('[name="tanggal1"]').val();				
	var tgl2 = $('[name="tanggal2"]').val();				
	var bank = $('[name="bank"]').val();			
	var cbg  = $('[name="cabang"]').val();			
	var param= idlap+'~'+tgl1+'~'+tgl2+'~'+bank+'~'+cbg;	
	
    return baseurl+'keuangan_laporan/cetak/'+param;
}
	
</script>