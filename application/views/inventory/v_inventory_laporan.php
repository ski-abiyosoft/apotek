<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


				<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Inventory <small>Laporan</small>
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
                              Inventory
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
                         Laporan - laporan untuk transaksi Inventory
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
																	<optgroup label="Inventory">	
																		<option data-subtext="101" value="101">Daftar Barang</option>	
                                                                        <option data-subtext="102" value="102">Kartu Stok Persediaan</option>																			
																		<option data-subtext="103" value="103">Umur Persediaan</option>	
																	
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
														        <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													
													
												</div>
												
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Kelompok Barang</label>
													        <div class="col-md-9">
														      <select id="kaateg" name="kateg" class="select2me bs-select form-control" data-show-subtext="true">
            												  <optgroup label="Daftar Kelompok Barang">	
															     <option data-subtext="" value="">-- Semua --</option>
																<?php
																  foreach($kateg as $row) { ?>
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
												
												<div class="row">												    												
													<div class="col-md-12">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Gudang</label>
													        <div class="col-md-9">
														        <select id="gudang" name="gudang" class="form-control  select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												    <option value="">--- Pilih Gudang ---</option>
                                                            <?php
															  $gudang = $this->db->query("select * from inv_gudang")->result();
                                                              foreach($gudang as $row) { ?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
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

$('#idlap').change(function(){
	if($(this).val()==102){
		swal('','Kode Barang harus dipilih terlebih dahulu ...','');
		$('#barang').focus();
	}
});

function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var idlap= $('[name="idlap"]').val();				
	var tgl1 = $('[name="tanggal1"]').val();				
	var tgl2 = $('[name="tanggal2"]').val();				
	var brg  = $('[name="barang"]').val();			
	var cbg  = $('[name="cabang"]').val();			
	var kat  = $('[name="kateg"]').val();
	var gud  = $('[name="gudang"]').val();
	var param= idlap+'~'+tgl1+'~'+tgl2+'~'+kat+'~'+brg+'~'+cbg+'~'+gud;	
    return baseurl+'inventory_lap/cetak/'+param;
	
}

	
</script>