
<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Keuangan<small>Transfer Kas/Bank </small>
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
							<a href="<?php echo base_url();?>keuangan_transfer">
                               Daftar Transfer Kas/Bank
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url();?>keuangan_transfer/entri">
                               Entri Transfer Kas/Bank
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Form Entri
										</div>
										
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmkeuangan" class="form-horizontal" method="post">
											<div class="form-body">
												<h4 class="form-section">Deskripsi</h4>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor Bukti</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nomor;?>" onkeypress="return tabE(this,event)" readonly>
													        </div>

														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-6">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y',strtotime($tanggal));?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													    	   </div>
													        </div>
													        


														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Dari Kas/Bank</label>
													        <div class="col-md-9">
                                                               <select id="sumber" name="sumber" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												     <?php 
																  if($sumber){ 
																     $data = data_master('tbl_coa', array('accountno' => $sumber));
																     ?>
																	 <option value="<?= $data->accountno;?>"><?= $data->acname;?></option> 
																  <?php }
																 
																 ?>
																 
																 
            												   </select>
													        </div>

														</div>
													</div>
													
                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Keterangan</label>
													        <div class="col-md-9">
														        <textarea class="form-control" rows="2" id="keterangan" name="keterangan"  onkeypress="return tabE(this,event)"><?php echo $uraian;?></textarea>
													        </div>

														</div>
													</div>

												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Ke Kas/Bank</label>
													        <div class="col-md-9">
                                                              <select id="tujuan" name="tujuan" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												    <?php 
																  if($tujuan){ 
																     $data = data_master('tbl_coa', array('accountno' => $tujuan));
																     ?>
																	 <option value="<?= $data->accountno;?>"><?= $data->acname;?></option> 
																  <?php }
																 
																 ?>
            												  </select>
													        </div>

														</div>
													</div>

                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Nilai Transfer</label>
													        <div class="col-md-9">
														        <input class="form-control" rows="2" id="jumlah" name="jumlah" onClick="formatCurrency(this)" value="<?php echo $jumlah;?>"/>
													        </div>

														</div>
													</div>

												</div>



												

											<div class="form-actions">
												<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
												<a href="<?php echo base_url()?>keuangan_transfer/entri" class="btn green"><i class="fa fa-new"></i> Data Baru</a>

											</div>
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>
                                            <input type="hidden" name="nomor" value="<?php echo $nomor;?>" />
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
	var total     = $('#jumlah').val();
	var sumber    = $('#sumber').val();
	var tujuan    = $('#tujuan').val();
	
	if(total==0.00 || total=="" || sumber=="" || tujuan =="" ){
		 swal('','Data belum lengkap...','')
                        
	} else {  
	$.ajax({				
		url:'<?php echo site_url('keuangan_transfer/transfer_save/2')?>',				
		data:$('#frmkeuangan').serialize(),				
		type:'POST',
		success:function(data){        		
		swal({
					  title: "MUTASI KAS/BANK",
					  html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>keuangan_transfer";
		  });								
	
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
		}
		});
	}	
}	
   
</script>
</body>
</html>
