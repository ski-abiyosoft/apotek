
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
                      <span class="title-web">KLINIK <small>Kasir - Uang Muka</small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="<?php echo base_url('kasir_uangmuka');?>">
                               Uang Muka
                              							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a class="title-white" href="">
                               Edit Uang Muka
							</a>
						</li>
					</ul>
				</div>
			</div>
			<form id="frmdaftar" class="form-horizontal" method="post">
			<input type="hidden" name="q_id" value="<?= $id;?>">
			<div class="row">
			  <!-- salah konsep -->
			  <?php foreach ($datas as $key => $data) { ?>
			  <div class="col-md-6">
			   <div class="portlet box yellow">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>Edit Pembayaran Uang Muka
					</div>
					</div>
				
				<div class="portlet-body">									
				 
				 	<div class="row">	
					    
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">No. Kwitansi <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="nokwitansi" placeholder="Otomatis" value="<?= $data->nokwitansi;?>" readonly>																							 
								</div>
								</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Tanggal Bayar <font color="red"></font></label>
								<div class="col-md-8">
								 <div class="input-group">
								   <input type="date" class="form-control input-medium" name="tanggal" placeholder="Otomatis" value="<?= date('Y-m-d', strtotime($data->tglbayar));?>" >															
								   <input type="time" class="form-control input-small" name="jam" placeholder="Otomatis" value="<?= date('H:i:s',strtotime($data->jambayar));?>" >															
								 </div>	 
								</div>
								</div>
						</div>
												
					  <div class="col-md-12">
							 <div class="form-group">
							   <label class="col-md-4 control-label">No. Reg <font color="red"></font></label>
								<div class="col-md-8">
								   <select class="form-control select2_el_registrasi" id="noreg" name="noreg">
								    <?php
									 if($data->noreg){ ?>
									   <option value="<?= $data->noreg;?>" > <?= $data->noreg;?></option>
									 <?php }
									?>
								   </select>
								   
								 
								</div>
								</div>

						</div>
					  <div class="col-md-12">
							 <div class="form-group">
							   <label class="col-md-4 control-label">Rek. Medis <font color="red"></font></label>
								<div class="col-md-8">
								   <select class="form-control select2_el_pasien" id="pasien" name="pasien">
								    <?php
									 if($data->rekmed){ ?>
									   <option value="<?= $data->rekmed;?>" > <?= $data->rekmed.' | '.$data->namapas;?></option>
									 <?php }
									?>
								   </select>
								   
								 
								</div>
								</div>

						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Jumlah <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" data-type="currency" class="total form-control rightJustified" name="jumlah" id="jumlah" placeholder="" value="<?= $data->uangmuka;?>">																							 
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Untuk Keperluan Bayar <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="total form-control" name="ketbayar" id="ketbayar" placeholder="" value="<?= $data->lainket;?>">																							 
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Sistem Pembayaran <font color="red"></font></label>
								<div class="col-md-8">
									<?php if ($data->jenisbayar == 1) {
										$jenisbayar = "Cash";
									}elseif ($data->jenisbayar == 2) {
										$jenisbayar = "Credit Card";
									}elseif ($data->jenisbayar == 3) {
										$jenisbayar = "Debet Card";
									}elseif ($data->jenisbayar == 4) {
										$jenisbayar = "Transfer";
									}elseif ($data->jenisbayar == 5) {
										$jenisbayar = "Online";
									} ?>
								<input type="text" class="total form-control" name="pembayaran" id="pembayaran" placeholder="" value="<?= $jenisbayar ?>">
								   <!-- <select name="pembayaran" id="pembayaran" class="form-control">
								     <option value="1">Cash</option>
									 <option value="2">Credit Card</option>
									 <option value="3">Debet Card</option>
									 <option value="4">Transfer</option>
									 <option value="5">Online</option>
                                   </select> 								    -->
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							    <label class="col-md-4 control-label">Dibayar Oleh <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="dibayaroleh" placeholder="" value="<?= $data->dibayaroleh;?>">																							 
								</div>
							</div>
						</div>
						
						
						
						
						
						
					</div>								
					  
					  
					
				 
				</div>
			   </div>
			  </div> 
			  <?php } ?>			  
			  
			  <div class="col-md-6">
			   <div class="portlet box green" id="vpembayaran">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>PEMBAYARAN
					</div>
					</div>
				
				<div class="portlet-body">									
				  <div class="row">
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Sistem Pembayaran <font color="red"></font></label>
								<div class="col-md-8">
								   <select disabled name="pembayaran_pilih" id="pembayaran_pilih" class="form-control">
								     <option value="1">Cash</option>
									 <option value="2">Credit Card</option>
									 <option value="3">Debet Card</option>
									 <option value="4">Transfer</option>
									 <option value="5">Online</option>
                                   </select> 								   
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							    <label class="col-md-4 control-label">Nomor Kartu <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="nokartu" placeholder="" value="">																							 
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							    <label class="col-md-4 control-label">No. Otorisasi <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="nootorisasi" placeholder="" value="">																							 
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Total <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" data-type="currency" class="total form-control rightJustified" id="total" name="total" placeholder="" value="0">																							 
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Adm <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotal" id="grandtotal" placeholder="" value="0" readonly>																							 
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-4 control-label">Bank <font color="red"></font></label>
								<div class="col-md-8">
								   <select class="form-control select2_el_kasbankedc" id="bank" name="bank">
								    
								   </select>	 
								</div>
							</div>
						</div>
										
					  
				 
				    </div>
			   </div>
			  </div> 
			  
			</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">		
					<div class="form-actions">
						<?php if($level<3){?>
						<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
						<?php } ?>

						<div class="btn-group">
						  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
						</div>

						<div class="btn-group">
							<a class="btn red" href="<?php echo base_url('kasir_uangmuka')?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
						</div>
							
					</div>							
				</div>															
			</div>
			</br>			
			

          </br>
		  
		 </form> 
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
    var pasien    = $('[name="pasien"]').val(); 	
	var pembayaran= $('[name="pembayaran"]').val(); 	
	var jumlah    = $('[name="jumlah"]').val(); 	
	
	if(pasien=="" || pasien==null || pembayaran=="" || jumlah=="" || jumlah==0 || tanggal==""){
	  swal('','Data Belum Lengkap....','');
	}  else {
	$.ajax({				
		url:"<?php echo site_url('kasir_uangmuka/ajax_update')?>",				
		data:$('#frmdaftar').serialize(),				
		type:'POST',
		dataType: 'json',
		success:function(data){ 
		  
		 swal({
					  title: "UANG MUKA",
					  html: "<p> Kwitansi   : <b>"+data.nomor+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>kasir_uangmuka";
		         });				
	     
		  
		},
		error:function(data){
			swal('','Gagal simpan data','');					
		}
		});
	}	
}	


$('#vpembayaran').hide();

$('#pembayaran').change(function(){
	var pembayaran = $(this).val();
	
	if(pembayaran !=1 ){
	  $('#vpembayaran').show();	
	  $('#pembayaran_pilih').val(pembayaran);
	} else {
	  $('#vpembayaran').hide();
      
	}
});

$('#total, #adm').keyup(function(){
	var total = $('#total').val();
	var adm   = $('#adm').val();
	var vtotal = Number(total.replace(/[^0-9\.]+/g,""));
	var vadm = Number(adm.replace(/[^0-9\.]+/g,""));
	
	var gtotal= eval(vtotal)+eval(vadm);
	
	$('#grandtotal').val(formatCurrency1(gtotal));
	$('#jumlah').val(formatCurrency1(gtotal));
	
});
	
</script>



	
</body>
</html> 
