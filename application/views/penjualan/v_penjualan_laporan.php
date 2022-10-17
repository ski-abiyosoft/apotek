<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	



				<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Penjualan <small>Laporan</small>
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
                              Penjualan
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
                         Laporan - laporan untuk transaksi Penjualan
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
																	<optgroup label="Penjualan">	
																		<option data-subtext="101" value="101">Daftar Pesanan Penjualan</option>	
                                                                        <option data-subtext="102" value="102">Daftar Pengiriman Barang</option>																			
																		<option data-subtext="103" value="103">Daftar Faktur Penjualan</option>	
																		<option data-subtext="104" value="104">Daftar Uang Muka Penjualan</option>	
																		<option data-subtext="105" value="105">Daftar Retur Penjualan</option>	
																		<option data-subtext="106" value="106">Penjualan per Pelanggan</option>	
																		<option data-subtext="107" value="107">Penjualan per Barang</option>	
																		
																	</optgroup>		
                                                                    <optgroup label="Piutang">	
																		<option data-subtext="201" value="201">Faktur Belum Lunas</option>	
                                                                        <option data-subtext="203" value="203">Umur Piutang</option>	
																		
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
													        <div class="col-md-3">
														      
															    <input id="tanggal1" name="tanggal1" class="form-control  input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>
															<label class="col-md-3 control-label">s/d</label>
															<div class="col-md-3">
														        <input id="tanggal2" name="tanggal2" class="form-control  input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	  
													        </div>



														</div>
													</div>
													
													
													
												</div>
												<div class="row">												    												
													<div class="col-md-12">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Customer</label>
													        <div class="col-md-9">
														       <select id="cust" name="cust" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih...">
																	<optgroup label="Daftar Supplier">	
																	   <option data-subtext="" value="">-- Semua --</option>
																	    <?php
																			  foreach($cust as $row) { ?>
																				<option data-subtext="" value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
																		<?php } ?> 
																		
																	</optgroup>		
                                                                    
																</select>
													        </div>



														</div>
													</div>													
													
													
												</div>
																								
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Barang</label>
													        <div class="col-md-9">
														     <select name="barang" id="barang" class="select2_el_barang form-control input-largex" onchange="showbarangname(this.value, 1)">
															  <option value="">--- Pilih Barang ---</option>
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
														      <select id="cabang" name="cabang" class="select2me bs-select form-control" data-show-subtext="true">
            												  <optgroup label="Daftar Cabang">	
															     <option data-subtext="" value="">-- Semua --</option>
																<?php
																  foreach($cabang as $row) { ?>
																	<option data-subtext="" value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
																<?php } ?>
															</optgroup>	
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
	var idlap= $('[name="idlap"]').val();				
	var tgl1 = $('[name="tanggal1"]').val();				
	var tgl2 = $('[name="tanggal2"]').val();				
	var cust = $('[name="cust"]').val();				
	var brg  = $('[name="barang"]').val();			
	var cbg  = $('[name="cabang"]').val();			
	var param= idlap+'~'+tgl1+'~'+tgl2+'~'+cust+'~'+brg+'~'+cbg;	
	
    return baseurl+'penjualan_lap/cetak/'+param;
}

</script>