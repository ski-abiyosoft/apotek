
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
                               Entri Uang Muka
							</a>
						</li>
					</ul>
				</div>
			</div>
			<form id="frmdaftar" class="form-horizontal" method="post">
			<div class="row">
			  
			  <div class="col-md-6">
			   <div class="portlet box blue">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>Pembayaran Uang Muka</b>
					</div>
					</div>
				
				<div class="portlet-body">									
				 
				 	<div class="row">	
					    
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">No. Kwitansi <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="nokwitansi" placeholder="Otomatis" value="" readonly>																							 
								</div>
								</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Tanggal Bayar <font color="red"></font></label>
								<div class="col-md-8">
								 <div class="input-group">
								   <input type="date" class="form-control input-medium" name="tanggal" placeholder="Otomatis" value="<?= date('Y-m-d');?>" >															
								   <input type="time" class="form-control input-small" name="jam" placeholder="Otomatis" value="<?= date('H:i:s');?>" >															
								 </div>	 
								</div>
								</div>
						</div>
													
					   <div class="col-md-12">
							 <div class="form-group">
							   <label class="col-md-4 control-label">No. Reg <font color="red"></font></label>
								<div class="col-md-8">
								   <select class="form-control select2_el_registrasi" id="noreg" name="noreg" onchange="getdataregistrasi()">
								   </select>
								   
								 
								</div>
								</div>

						</div>
						
					   <div class="col-md-12">
							 <div class="form-group">
							   <label class="col-md-4 control-label">Rek. Medis <font color="red"></font></label>
								<div class="col-md-8">
								   <select class="form-control select2_el_pasien" id="pasien" name="pasien">
								   </select>
								   
								 
								</div>
								</div>

						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" data-type="currency" placeholder="0" class="total form-control rightJustified" name="jumlah" id="jumlah" placeholder="" readonly="" value="">																							 
								</div>
								</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Untuk Keperluan Bayar <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="total form-control" name="ketbayar" id="ketbayar" placeholder="" value="">																							 
								</div>
								</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
								<div class="col-md-8">
								   <select readonly name="" id="pembayaran" class="form-control">
											<option value="0">Cash</option>
											<option value="1">DEBIT</option>
											<option value="2">CREDIT CARD</option>
											<option value="3">TRANFER</option>
											<option value="4">ONLINE</option>
										</select> 								   
								</div>
								</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							    <label class="col-md-4 control-label">Dibayar Oleh <font color="red"></font></label>
								<div class="col-md-8">
								   <input type="text" class="form-control" name="dibayaroleh" placeholder="" value="">																							 
								</div>
							</div>
						</div>
					</div>								
				</div>
			   </div>
			  </div> 
			  <!-- cash -->
			<div class="col-md-6">
			   <div class="portlet box green" id="vpembayarancash">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>PEMBAYARAN CASH</b>
					</div>
					</div>
					<div class="portlet-body">									
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
									<div class="col-md-8">
										<input type="text" class="total form-control" readonly value="Cash">
										<input type="text" hidden="" value="1" name="pembayaran_pilih_cash">
									<!-- <select name="pembayaran_pilih_cash" id="" class="form-control">
										<option value="1" selected="">Cash</option>
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
								<label class="col-md-4 control-label">Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="totalcash" name="totalcash" placeholder="0" value="">																							 
									</div>
									</div>
							</div>
							
							<!-- <div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Adm <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
									</div>
									</div>
							</div> -->
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotalcash" id="grandtotalcash" placeholder="" value="0" readonly>																							 
									</div>
									</div>
							</div>
						</div>
				</div>
			  </div> 
			</div>

			<!-- credit card -->
			<div class="col-md-6">
			   <div class="portlet box green" id="vpembayarancreditcard">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>PEMBAYARAN CREDIT CARD</b>
					</div>
					</div>
					<div class="portlet-body">									
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
									<div class="col-md-8">
										<input type="text" class="total form-control" readonly value="Credit">
										<input type="text" hidden="" value="2" name="pembayaran_pilih_credit">
									<!-- <select name="pembayaran_pilih_credit" id="pembayaran_pilih_credit" class="form-control">
										<option value="1">Cash</option>
										<option value="2" selected="">Credit Card</option>
										<option value="3">Debet Card</option>
										<option value="4">Transfer</option>
										<option value="5">Online</option>
									</select> 								    -->
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(16 Digit) Nomor Kartu <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nokartu_credit" onchange="validasiCreditCard()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(6 Digit) Approval Code <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nootorisasi_credit" onchange="validasiNootorisasi_credit()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="totalcreditcard" name="totalcreditcard" placeholder="0" value="">																							 
									</div>
									</div>
							</div>
							
							<!-- <div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Adm <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
									</div>
									</div>
							</div> -->
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotalcredit" id="grandtotalcredit" placeholder="" value="0" readonly>																							 
									</div>
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">Bank <font color="red"></font></label>
									<div class="col-md-8">
									<select class="form-control select2_el_kasbankedc" id="bank" name="bank_credit">
										
									</select>	 
									</div>
								</div>
							</div>
						</div>
				</div>
			  </div> 
			</div>

			<!-- debet card -->
			<div class="col-md-6">
			   <div class="portlet box green" id="vpembayarandebetcard">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>PEMBAYARAN DEBET CARD</b>
					</div>
					</div>
					<div class="portlet-body">									
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
									<div class="col-md-8">
										<input type="text" class="total form-control" readonly value="Debet">
										<input type="text" hidden="" value="3" name="pembayaran_pilih_debet">
									<!-- <select name="pembayaran_pilih_debet" id="pembayaran_pilih_debet" class="form-control">
										<option value="1">Cash</option>
										<option value="2">Credit Card</option>
										<option value="3" selected="">Debet Card</option>
										<option value="4">Transfer</option>
										<option value="5">Online</option>
									</select> 								    -->
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(16 Digit) Nomor Kartu <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nokartu_debet" onchange="validasiDebetCard()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(6 Digit) Approval Code <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nootorisasi_debet" onchange="validasiNootorisasi_debet()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="totaldebet" name="totaldebet" placeholder="0" value="">																							 
									</div>
									</div>
							</div>
							
							<!-- <div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Adm <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
									</div>
									</div>
							</div> -->
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotaldebet" id="grandtotaldebet" placeholder="" value="0" readonly>																							 
									</div>
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">Bank <font color="red"></font></label>
									<div class="col-md-8">
									<select class="form-control select2_el_kasbankedc" id="bank" name="bank_debet">
										
									</select>	 
									</div>
								</div>
							</div>
						</div>
				</div>
			  </div> 
			</div>

			<!-- transfer -->
			<div class="col-md-6">
			   <div class="portlet box green" id="vpembayarantransfer">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>PEMBAYARAN TRANSFER</b>
					</div>
					</div>
					<div class="portlet-body">									
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
									<div class="col-md-8">
										<input type="text" class="total form-control" readonly value="Transfer">
										<input type="text" hidden="" value="4" name="pembayaran_pilih_transfer">
									<!-- <select name="pembayaran_pilih_transfer" id="pembayaran_pilih_transfer" class="form-control">
										<option value="1">Cash</option>
										<option value="2">Credit Card</option>
										<option value="3">Debet Card</option>
										<option value="4" selected="">Transfer</option>
										<option value="5">Online</option>
									</select> 								    -->
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(16 Digit) Nomor Kartu <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nokartu_transfer" onchange="validasiTransferCard()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(6 Digit) Approval Code <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nootorisasi_transfer" onchange="validasiNootorisasi_transfer()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="totaltransfer" name="totaltransfer" placeholder="0" value="">																							 
									</div>
									</div>
							</div>
							
							<!-- <div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Adm <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
									</div>
									</div>
							</div> -->
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotaltransfer" id="grandtotaltransfer" placeholder="" value="0" readonly>																							 
									</div>
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">Bank <font color="red"></font></label>
									<div class="col-md-8">
									<select class="form-control select2_el_kasbankedc" id="bank" name="bank_transfer">
										
									</select>	 
									</div>
								</div>
							</div>
						</div>
				</div>
			  </div> 
			</div>

			<!-- online -->
			<div class="col-md-6">
			   <div class="portlet box green" id="vpembayaranonline">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i><b>PEMBAYARAN ONLINE</b>
					</div>
					</div>
					<div class="portlet-body">									
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Cara Pembayaran <font color="red"></font></label>
									<div class="col-md-8">
										<input type="text" class="total form-control" readonly value="Online">
										<input type="text" hidden="" value="5" name="pembayaran_pilih_online">
									<!-- <select name="pembayaran_pilih_online" id="pembayaran_pilih_online" class="form-control">
										<option value="1">Cash</option>
										<option value="2">Credit Card</option>
										<option value="3" >Debet Card</option>
										<option value="4">Transfer</option>
										<option value="5" selected="">Online</option>
									</select> 								    -->
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(16 Digit) Nomor Kartu <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nokartu_online" onchange="validasiOnlineCard()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">(6 Digit)Approval Code <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="nootorisasi_online" onchange="validasiNootorisasi_transfer()" placeholder="" value="">																							 
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="totalonline" name="totalonline" placeholder="0" value="">																							 
									</div>
									</div>
							</div>
							
							<!-- <div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Adm <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" id="adm" name="adm" placeholder="" value="0">																							 
									</div>
									</div>
							</div> -->
							
							<div class="col-md-12">
								<div class="form-group">
								<label class="col-md-4 control-label">Grand Total <font color="red"></font></label>
									<div class="col-md-8">
									<input type="text" data-type="currency" class="total form-control rightJustified" name="grandtotalonline" id="grandtotalonline" placeholder="" value="0" readonly>																							 
									</div>
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-4 control-label">Bank <font color="red"></font></label>
									<div class="col-md-8">
									<select class="form-control select2_el_kasbankedc" id="bank" name="bank_online">
										
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
						<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>
						   
						<div class="btn-group">
						  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i><b> Data Baru</b></button>                																							
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
<!-- </div> -->

<?php
  $this->load->view('template/footer');  
?>

<script>

function validasiNootorisasi_credit(){
    var noCredirCard=document.forms["frmdaftar"]["nootorisasi_credit"].value;
    var number=/^[0-9]+$/;
      
    if (!noCredirCard.match(number)){
	  swal('','Nomor Kartu harus angka !','');
	  $('[name="nootorisasi_credit"]').val('')
      return false;
    };
      
    if (noCredirCard.length!=6){
		$('[name="nootorisasi_credit"]').val('')
	  swal('','Nomor Approval Code Credit Harus 6 Digit !','');
      return false;
    };
	var no = $('[name="nootorisasi_credit"]').val();	

	// $.ajax({		
	// 	url : "<?php echo site_url('kasir_uangmuka/cariNotorisasi/')?>/" + no,						
	// 	type:'POST',
	// 	dataType: 'json',
	// 	success:function(data){ 

	// 	});
}

function validasiNootorisasi_debet(){
    var noDebetCard=document.forms["frmdaftar"]["nootorisasi_debet"].value;
    var number=/^[0-9]+$/;
      
    if (!noDebetCard.match(number)){
	  swal('','Nomor Kartu harus angka !','');
	  $('[name="nootorisasi_debet"]').val('')
      return false;
    };
      
    if (noDebetCard.length!=6){
		$('[name="nootorisasi_debet"]').val('')
	  swal('','Nomor Approval Code Debet Harus 6 Digit !','');
      return false;
    };
	var no = $('[name="nootorisasi_debet"]').val();	
}
//transfer
function validasiNootorisasi_transfer(){
    var noTransfer=document.forms["frmdaftar"]["nootorisasi_transfer"].value;
    var number=/^[0-9]+$/;
      
    if (!noTransfer.match(number)){
	  swal('','Nomor Kartu harus angka !','');
	  $('[name="nootorisasi_transfer"]').val('')
      return false;
    };
      
    if (noTransfer.length!=6){
		$('[name="nootorisasi_transfer"]').val('')
	  swal('','Nomor Approval Code Transfer Harus 6 Digit !','');
      return false;
    };
	var no = $('[name="nootorisasi_transfer"]').val();	
}
// Online
function validasiNootorisasi_transfer(){
    var noOnline=document.forms["frmdaftar"]["nootorisasi_online"].value;
    var number=/^[0-9]+$/;
      
    if (!noOnline.match(number)){
	  swal('','Nomor Kartu harus angka !','');
	  $('[name="nootorisasi_online"]').val('')
      return false;
    };
      
    if (noOnline.length!=6){
		$('[name="nootorisasi_online"]').val('')
	  swal('','Nomor Approval Code Online Harus 6 Digit !','');
      return false;
    };
	var no = $('[name="nootorisasi_online"]').val();	
}

function validasiCreditCard(){
    var noCredirCard=document.forms["frmdaftar"]["nokartu_credit"].value;
    var number=/^[0-9]+$/;
      
    if (!noCredirCard.match(number)){
	  swal('','Nomor Kartu harus angka !','');
	  $('[name="nokartu_credit"]').val('')
      return false;
    };
      
    if (noCredirCard.length!=16){
		$('[name="nokartu_credit"]').val('')
	  swal('','Nomor Kartu Credit Harus 16 Digit !','');
      return false;
    };
}
function validasiDebetCard(){
    var noDebetCard=document.forms["frmdaftar"]["nokartu_debet"].value;
    var number=/^[0-9]+$/;
      
    if (!noDebetCard.match(number)){
	  swal('','Nomor Kartu Debet harus angka !','');
	  $('[name="nokartu_debet"]').val('')
      return false;
    };
      
    if (noDebetCard.length!=16){
	  swal('','Nomor Kartu Debet Harus 16 Digit !','');
	  $('[name="nokartu_debet"]').val('')
      return false;
    };
}
function validasiTransferCard(){
    var noTransferCard=document.forms["frmdaftar"]["nokartu_transfer"].value;
    var number=/^[0-9]+$/;
      
    if (!noTransferCard.match(number)){
	  swal('','Nomor Kartu Transfer harus angka !','');
	  $('[name="nokartu_transfer"]').val('')
      return false;
    };
      
    if (noTransferCard.length!=16){
	  swal('','Nomor Kartu Transfer Harus 16 Digit !','');
	  $('[name="nokartu_transfer"]').val('')
      return false;
    };
}
function validasiOnlineCard(){
    var noOnlineCard=document.forms["frmdaftar"]["nokartu_online"].value;
    var number=/^[0-9]+$/;
      
    if (!noOnlineCard.match(number)){
	  swal('','Nomor Kartu Online harus angka !','');
	  $('[name="nokartu_online"]').val('')
      return false;
    };
      
    if (noOnlineCard.length!=16){
	  swal('','Nomor Kartu Online Harus 16 Digit !','');
	  $('[name="nokartu_online"]').val('')
      return false;
    };
}

function save()
{	        
	var tanggal                = $('[name="tanggal"]').val();
	var pasien                 = $('[name="pasien"]').val();
	var pembayaran             = $('[name="pembayaran"]').val();
	var jumlah                 = $('[name="jumlah"]').val();
	var ketbayar               = $('[name="ketbayar"]').val();
	var dibayaroleh            = $('[name="dibayaroleh"]').val();

	var grantotalcredit        = $('[name="grandtotalcredit"]').val();
	// credit card
	var nokartu_credit         = $('[name="nokartu_credit"]').val();
	var nootorisasi_credit     = $('[name="nootorisasi_credit"]').val();
	var bank_credit            = $('[name="bank_credit"]').val();
	var totalcreditcard        = $('[name="totalcreditcard"]').val();
	//debet card
	var nokartu_debet          = $('[name="nokartu_debet"]').val();
	var nootorisasi_debet      = $('[name="nootorisasi_debet"]').val();
	var bank_debet             = $('[name="bank_debet"]').val();
	var totaldebet             = $('[name="totaldebet"]').val();
	// transfer
	var nokartu_transfer       = $('[name="nokartu_transfer"]').val();
	var nootorisasi_transfer   = $('[name="nootorisasi_transfer"]').val();
	var totaltransfer          = $('[name="totaltransfer"]').val();
	var bank_transfer          = $('[name="bank_transfer"]').val();
	// Online
	var nokartu_online         = $('[name="nokartu_online"]').val();
	var nootorisasi_online     = $('[name="nootorisasi_online"]').val();
	var totalonline            = $('[name="totalonline"]').val();
	var bank_online            = $('[name="bank_online"]').val();
	var jumlahtot              = $('[name="jumlah"]').val();

	if (nokartu_credit != "") {
		  if (nootorisasi_credit == "") {
			swal({
              title: "Approval Code Credit card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (bank_credit == null) {
			swal({
              title: "Bank Credit card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (totalcreditcard == "") {
			swal({
              title: "Total Credit card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
	  }

	  if (nokartu_debet != "") {
		  if (nootorisasi_debet == "") {
			swal({
              title: "Approval Code Debet card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (bank_debet == null) {
			swal({
              title: "Bank Debet card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (totaldebet == "") {
			swal({
              title: "Total Debet card",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
	  }

	  if (nokartu_transfer != "") {
		  if (nootorisasi_transfer == "") {
			swal({
              title: "Approval Transfer",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (bank_transfer == null) {
			swal({
              title: "Cara Bayar Transfer",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (totaltransfer == "") {
			swal({
              title: "Total Transfer",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
	  }

	  if (nokartu_online != "") {
		  if (nootorisasi_online == "") {
			swal({
              title: "Approval Online",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (bank_online == null) {
			swal({
              title: "Cara Bayar Online",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
		  if (totalonline == "") {
			swal({
              title: "Total Online",
              html: " Tidak Boleh Kosong .!!!",
              type: "error",
              confirmButtonText: "OK" 
         	});
			 return false;
		  }
	  }

	

	if(pasien=="" || pasien==null || pembayaran=="" || jumlah=="" || jumlah==0 || tanggal=="" || ketbayar =="" || dibayaroleh == "" ){
	  swal('','Data Belum Lengkap....','');
	}  else {
	$.ajax({				
		url:"<?php echo site_url('kasir_uangmuka/ajax_add')?>",				
		data:$('#frmdaftar').serialize(),				
		type:'POST',
		dataType: 'json',
		success:function(data){ 
		  
		 swal({
					  title: "UANG MUKA",
					  html: "<p> Kwitansi   : <b>"+data.nomor+"</b> </p>"+ 
					  "Tanggal :  " + tanggal+
					  "<br><br>Biaya Terbentuk <br><b>"+jumlahtot+"</b>",
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


// $('#vpembayarancash').hide();
$('#vpembayarancreditcard').hide();
$('#vpembayarandebetcard').hide();
$('#vpembayarantransfer').hide();
$('#vpembayaranonline').hide();

$('#pembayaran').change(function(){
	var pembayaran = $(this).val();
	
	if(pembayaran == 2 ){ //credit card
		$('#vpembayarancash').hide()
	  	$('#vpembayarancreditcard').show();
	  	$('#vpembayarandebetcard').hide();
	  	$('#vpembayarantransfer').hide();
	  	$('#vpembayaranonline').hide();
	//   $('#pembayaran_pilih').val(pembayaran);
	} else if(pembayaran == 3){
		$('#vpembayarancash').hide()
		$('#vpembayarancreditcard').hide();
		$('#vpembayarandebetcard').show();
		$('#vpembayarantransfer').hide();
		$('#vpembayaranonline').hide();
	} else if(pembayaran == 4){
		$('#vpembayarancash').hide()
		$('#vpembayarancreditcard').hide();
		$('#vpembayarandebetcard').hide();
		$('#vpembayarantransfer').show();
		$('#vpembayaranonline').hide();
	} else if(pembayaran == 5){
		$('#vpembayarancash').hide()
		$('#vpembayarancreditcard').hide();
		$('#vpembayarandebetcard').hide();
		$('#vpembayarantransfer').hide();
		$('#vpembayaranonline').show();

	} else {
		$('#vpembayarancash').show()
	  	$('#vpembayaran').hide();
	  	$('#vpembayarancreditcard').hide();
	  	$('#vpembayarandebetcard').hide();
		$('#vpembayarantransfer').hide();
	  	$('#vpembayaranonline').hide();
	}
});

$('#total, #adm, #totaldebet, #totalcreditcard, #totaltransfer, #totalonline, #totalcash').keyup(function(){
	// var total = $('#total').val();
	var totalcash = $('#totalcash').val();
	var totalcreditcard = $('#totalcreditcard').val();
	var totaldebet = $('#totaldebet').val();
	var totaltransfer = $('#totaltransfer').val();
	var totalonline = $('#totalonline').val();
	// var adm   = $('#adm').val();
	// var vtotal = Number(total.replace(/[^0-9\.]+/g,""));
	var vtotalcash = Number(totalcash.replace(/[^0-9\.]+/g,""));
	var vtotalcreditcard = Number(totalcreditcard.replace(/[^0-9\.]+/g,""));
	var vtotaldebet = Number(totaldebet.replace(/[^0-9\.]+/g,""));
	var vtotaltransfer = Number(totaltransfer.replace(/[^0-9\.]+/g,""));
	var vtotalonline = Number(totalonline.replace(/[^0-9\.]+/g,""));
	
	var gtotal= eval(vtotalcreditcard) + eval(vtotaldebet) + eval(vtotaltransfer) + eval(vtotalonline) + eval(vtotalcash);
	// var gtotal= eval(vtotal)
	
	$('#grandtotalcash').val(formatCurrency1(vtotalcash));
	$('#grandtotalcredit').val(formatCurrency1(vtotalcreditcard));
	$('#grandtotaldebet').val(formatCurrency1(vtotaldebet));
	$('#grandtotaltransfer').val(formatCurrency1(vtotaltransfer));
	$('#grandtotalonline').val(formatCurrency1(vtotalonline));
	$('#jumlah').val(formatCurrency1(gtotal));
	
});

function getdataregistrasi() { 
  var xhttp;      
  var str = $('[name=noreg]').val();
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>kasir_konsul/getdataregistrasi/?noreg="+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		      
          $('#pasien').val(data.rekmed);
		  var selectElement = document.getElementById('pasien');
		  var opt = document.createElement('option');
		  opt.value = data.rekmed;
		  opt.innerHTML = data.rekmed;
		  selectElement.appendChild(opt);		  
		}
	});	    
  }	
}
	
</script>



	
</body>
</html> 
