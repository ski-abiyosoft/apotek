
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
                        <span class="title-web">Kas/Bank <small>Penerimaan Kas</small>
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
                                Daftar Penerimaan Kas/Bank
                            </a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                                Entri Penerimaan Kas/Bank
							</a>
						</li>
					</ul>
				</div>
			</div>              
            <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Form Entri Penerimaan
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
                                                            <label class="col-md-3 control-label">Nomor Transaksi<font color="red">*</font></label>
													        <div class="col-md-6">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nomor;?>" onkeypress="return tabE(this,event)" readonly>
													        </div>

														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal<font color="red">*</font></label>
													        <div class="col-md-6">
															    <!-- <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/> -->
																<input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="date" value="<?php echo date('Y-m-d');?>"/>
													        </div>
														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Kas/Bank<font color="red">*</font></label>
													        <div class="col-md-9">
                                                              <select id="kasbank" name="kasbank" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												
            												   </select>
													        </div>

														</div>
													</div>
													
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">POS<font color="red">*</font></label>
													        <div class="col-md-9">
                                                              <select id="pos" name="pos" class="form-control select2_el_pos" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												  
            												</select>
													        </div>

														</div>
													</div>

												</div>
												
												<div class="row">
													<!--/span-->
                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Keterangan<font color="red">*</font></label>
													        <div class="col-md-9">
														        <textarea class="form-control" rows="2" id="keterangan" name="keterangan" onkeypress="return tabE(this,event)"></textarea>
													        </div>

														</div>
													</div>

                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Jumlah<font color="red">*</font></label>
													        <div class="col-md-9">
														        <input class="form-control" rows="2" id="jumlah" name="jumlah" value="" data-type="currency"/>
													        </div>

														</div>
													</div>
												</div>												

											<div class="form-actions">
												<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
												<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-edit"></i> Data Baru</button>
												<button type="button" class="btn red" onclick="javascript:history.go(-1)"><i class="fa fa-undo"></i> Kembali</button>

											</div>
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>

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

jQuery(document).ready(function() {
 
   ComponentsPickers.init();
});


$("input[data-type='currency']").on({
    blur: function() { 
		var val = this.value.replaceAll(',','').split('.');
		this.value = currencyFormat(val[0]);
    }
});

function currencyFormat (num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function save()
{	          
    var tanggal  	= $('[name="tanggal"]').val();
	var kasbank    	= $('#kasbank').val();
	var pos     	= $('#pos').val();
	var keterangan	= $('#keterangan').val();
	var jumlah      = $('#jumlah').val();

	if(kasbank=="" || pos=="" || keterangan=="" || jumlah ==""){
		swal('','Maaf, mohon isi data Anda dengan lengkap!','')  
	} else {  
		if(kasbank == pos){
			swal('','Maaf, mohon cek dari dan ke Kas/Bank dengan benar!','')  
		} else {			
			console.log($('#frmkeuangan').serialize());

			$.ajax({				
				url:'<?php echo site_url('keuangan_masuk/penerimaan_simpan/1')?>',				
				data:$('#frmkeuangan').serialize(),				
				type:'POST',
				success:function(data){        		
					swal({
								title: "PENERIMAAN KAS/BANK",
								html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
								"<p>Tanggal :  " + moment(tanggal).format('DD/MM/YYYY') + "</p>" +
								"<p><b>Rp " + jumlah + "</b></p>",
								type: "info",
								confirmButtonText: "OK" 
								}).then((value) => {
										location.href = "<?php echo base_url()?>keuangan_masuk";
					});								
				
				},
				error:function(data){
					$("#error").show().fadeOut(5000);
				}
			});
		}
	}	
}	
        
window.onload = function(){
        document.getElementById('nomorbukti').focus();
};



</script>

<!-- <script src="https://momentjs.com/downloads/moment-with-locales.min.js" type="text/javascript"></script> -->
</body>
</html>
