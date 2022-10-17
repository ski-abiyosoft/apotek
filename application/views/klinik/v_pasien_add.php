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
                      <span class="title-web">KLINIK <small>Data Pasien</small>
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
							<a class="title-white" href="<?php echo base_url('pasien');?>">
                               Daftar Pasien
                              							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a class="title-white" href="">
                               Entri Data Pasien
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>*Data Baru
					</div>
					
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpasien" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   Data Diri
								</a>
							</li>
							<li class="">
								<a href="#tab2" data-toggle="tab">
                                   Data Keluarga
								</a>
							</li>
							<!--li class="">
								<a href="#tab3" data-toggle="tab">
                                   Lainnya
								</a>
							</li-->
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
							                    <div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Cabang <font color="red">*</font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_cabang" id="cabang" name="cabang">
															   </select>
													        </div>
													        </div>

													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                           <label class="col-md-3 control-label">No. Member <font color="red">*</font></label>
													        <div class="col-md-9">
															 <div class="input-group">
                                                               <input type="text" class="form-control" name="nomember" placeholder="Otomatis" value="" readonly>															
															 </div>	 
													        </div>
													        </div>
													</div>
												</div>	
												<div class="row">
												   
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nama Pasien <font color="red">*</font></label>
													        <div class="col-md-9">
															   <div class="input-group">
															    <select class="form-control input-small" name="preposition">
																  <?php
																    foreach(setinghms('PREP') as $row){ ?>
																    <option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
																	<?php } ?>
																  
																</select>
																
															    <input type="text" class="form-control input-large" name="namapasien"  id="namapasien" value="" >																																															
															   </div>	
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Identitas <font color="red">*</font></label>
													        <div class="col-md-9">
															   <div class="input-group">
															    <select class="form-control input-small" name="identitas">
																  <option value="KTP">KTP</option> 
																  <option value="SIM">SIM</option> 
																  <option value="PASPORT">PASPORT</option> 
																  <option value="K_PELAJAR">K_PELAJAR</option> 
																  <option value="KMAHASISWA">KMAHASISWA</option> 
																  
																</select>
															    <input type="text" class="form-control input-medium" name="noidentitas"  id="noidentitas" value="" >																																															
															   </div>	
													        </div>

														</div>
													</div>
												</div>	
												
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Panggilan <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="namapanggilan" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Nama Keluarga <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="namakeluarga" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>	
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Tempat Lahir <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="tempatlahir" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Tanggal Lahir <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="date" class="form-control input-medium" id="tgllahir" name="tgllahir" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Jenis Kelamin <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control input-small" name="jeniskelamin">
																  <option value="P">Pria</option> 
																  <option value="W">Wanita</option> 
																</select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Umur <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control input-medium" id="umur" name="umur" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Status <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_statuspasien" id="status" name="status">
															   </select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Warga Negara <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control input-small" name="warganegara">
																  <option value="WNI">WNI</option> 
																  <option value="WNA">WNA</option> 
																</select>
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Agama <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_agama" id="agama" name="agama">
															   </select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Pendidikan <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_pendidikan" id="pendidikan" name="pendidikan">
															   </select>	
															 
													        </div>
													        </div>

													</div>
												</div>
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Gol. Darah <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_goldarah" id="goldarah" name="goldarah">
															   </select>															   
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Hobby <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="hobby" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Pekerjaan <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_pekerjaan" id="pekerjaan" name="pekerjaan">
																</select>																
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                        

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Alamat1 <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="alamat1" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Alamat2 <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="alamat2" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">RT/RW <font color="red"></font></label>
													         <div class="col-md-2">
															   <input type="text" class="form-control" name="rt" value="" >															
															 </div>
															 <div class="col-md-2">
															   <input type="text" class="form-control" name="rw" value="" >															
															 </div>															
															 </div>
													        

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Provinsi <font color="red"></font></label>
													        <div class="col-md-9">
															    <select class="form-control select2_el_provinsi" id="provinsi" name="provinsi">
																</select>	
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kab/Kota <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control" name="kota" id="kota">
																  
																</select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kecamatan <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control" name="kecamatan" id="kecamatan">
																 
																</select>	
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kelurahan <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control" name="kelurahan" id="kelurahan">
																  <option value="">--- Pilih ---</option> 
																  
																</select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kode Pos <font color="red"></font></label>
													        <div class="col-md-9">
															    <input type="text" class="form-control" name="kodepos" value="" >	
															 
													        </div>
													        </div>

													</div>
												</div>
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Phone <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="phone" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Handphone <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="hp" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Email <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="email" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">FB <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="fb" value="" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Twitter <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="twitter" value="" >															
															 </div>
													        </div>

													</div>
													
												</div>

											
							</div>
							<!-- tab1-->
							
							<div class="tab-pane" id="tab2">	
							   <h4>Keluarga yang dapat dihubungi dalam keadaan darurat</h4>
							   </br>
							   <div class="row">
							       
							       <div class="col-md-12">
								        
							            <div class="row">												    												
											<div class="col-md-6">
												 <div class="form-group">
													<label class="col-md-3 control-label">Nama</label>
													<div class="col-md-9">
													   <input id="namakel" name="namakel" class="form-control" type="text" value="" />
													   
													</div>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Hubungan</label>
													<div class="col-md-9">
														<input type="text" class="form-control" placeholder="" name="hubungan" id="hubungan" value="">
													</div>

												</div>
											</div>																									
									    </div>
										<div class="row">
										   <div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Jenis Kelamin</label>
													        <div class="col-md-4">
														        <select name="jkkeluarga" id="jkkeluarga" class="form-control select2me- input-medium" >
																  <option value="P">Pria</option>
																  <option value="W">Wanita</option>
																</select>
													        </div>

														</div>
													</div>
										   <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Alamat</label>
													<div class="col-md-9">
														<textarea class="form-control" name="alamatkel" id="alamatkel" ></textarea>
													</div>
												</div>
											</div>																									
										</div>	
										<div class="row">
										   <div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Pekerjaan</label>
													<div class="col-md-9">
													   <select class="form-control select2_el_pekerjaan input-large" id="pekerjaankel" name="pekerjaankel">
													   </select>
													</div>
												</div>
										   </div>
													
												
										   
										</div>	
										<div class="row">												    												
											<div class="col-md-6">
												 <div class="form-group">
													<label class="col-md-3 control-label">Phone</label>
													<div class="col-md-9">
													   <input id="phonekeluarga" name="phonekeluarga" class="form-control" type="text" value="" />
													   
													</div>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Handphone</label>
													<div class="col-md-9">
														<input type="text" class="form-control" placeholder="" name="hpkeluarga" id="hpkeluarga" value="">
													</div>

												</div>
											</div>																									
									    </div>
										
										<div class="row">												    												
											<div class="col-md-6">
												 <div class="form-group">
													<label class="col-md-3 control-label">Email</label>
													<div class="col-md-9">
													   <input id="emailkeluarga" name="emailkeluarga" class="form-control" type="text" value="" />
													   
													</div>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Facebook</label>
													<div class="col-md-9">
														<input type="text" class="form-control" placeholder="" name="fbkeluarga"  value="">
													</div>

												</div>
											</div>																									
									    </div>
								   </div> 
								</div>
                                
							</div> <!--tab2-->
							
							<div class="tab-pane" id="tab3">	
							    <div class="row">
							   
								</div>
                                <div class="row">	
                                 	  
								</div>
							</div>
							<!-- tab2-->
							
						</div><!--tab-->	
						
						
													
						<div class="row-">
						
							<div class="form-actions">
								   
                                   
									<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									   
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
									</div>
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								
							</div>
							
																			
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

<script>


function save()
{	        
    var nomor     = $('[name="cabang"]').val(); 	
	var nama      = $('[name="namapasien"]').val(); 	
	var noidentitas     = $('[name="noidentitas"]').val();
	
	if(nomor=="" || nama=="" || noidentitas==""){
	  swal('','Data Belum Lengkap ...','');   	
	}  else {
	$.ajax({				
		url:'<?php echo site_url('pasien/ajax_add_pasien')?>',				
		data:$('#frmpasien').serialize(),				
		type:'POST',
		
		success:function(data){ 		
		 swal('','Tambah Data berhasil ...','');	
		},
		error:function(data){
			swal('','Data gagal disimpan ...','');	
					
		}
		});
	}	
}	

$('#tgllahir').on('change', function() {		
	var birthDate = new Date(this.value);		
	var usia = hitung_usia(birthDate);
	$('#umur').val(usia);
});

function getprovinsi( kode ) {     
   $.ajax({
        url : "<?php echo base_url();?>app/namaprovinsi/?kode="+kode,
        type: "GET",
        success: function(data)
        {				
            var selectElement = document.getElementById('provinsi');
			var opt = document.createElement('option');
			opt.value = kode;
			opt.innerHTML = data;
			selectElement.appendChild(opt);			
		}
	});	    
}

function getkota( kode ) {     
   $.ajax({
        url : "<?php echo base_url();?>app/namakota/?kode="+kode,
        type: "GET",
        success: function(data)
        {				
            var selectElement = document.getElementById('kota');
			var opt = document.createElement('option');
			opt.value = kode;
			opt.innerHTML = data;
			selectElement.appendChild(opt);			
		}
	});	    
}

function getkecamatan( kode ) {     
   $.ajax({
        url : "<?php echo base_url();?>app/namakecamatan/?kode="+kode,
        type: "GET",
        success: function(data)
        {				
            var selectElement = document.getElementById('kecamatan');
			var opt = document.createElement('option');
			opt.value = kode;
			opt.innerHTML = data;
			selectElement.appendChild(opt);			
		}
	});	    
}

$('#noidentitas').on('change', function() {		
	var noktp = this.value;		
	var prov  = noktp.substring(0,2);
	var kota  = noktp.substring(0,4);
	var kec   = noktp.substring(0,6);
	
	getprovinsi(prov);
	getkota(kota);
	getkecamatan(kec);
	
			
});

   	
</script>
	
</body>
</html> 
