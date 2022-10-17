
			
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>*Edit Data
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
															     <?php 
																   if($data->koders){ 
																    $cabang = data_master('tbl_namers', array('koders' => $data->koders))->namars;
																   ?>
															       <option value="<?= $data->koders;?>"><?= $cabang;?></option>
																   <?php } ?>  
															   </select>
													        </div>
													        </div>

													</div>
														
													<div class="col-md-6">
														<div class="form-group">
                                                           <label class="col-md-3 control-label">No. Member <font color="red">*</font></label>
													        <div class="col-md-9">
															 <div class="input-group">
                                                               <input type="text" class="form-control" name="nomember" placeholder="Otomatis" value="<?= $data->rekmed;?>" readonly>															
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
																  <option value="TN">TN</option> 
																  <option value="NY">NY</option> 
																  <option value="AN">AN</option> 
																  <option value="BY">BY</option> 
																  <option value="HJ">HJ</option> 
																  <option value="HJH">HJH</option> 
																  <option value="NN">NN</option> 
																  <option value="MS">MS</option> 
																  <option value="MR">MR</option> 
																  <option value="MRS">MRS</option> 
																  <option value="SDR">SDR</option> 
																  
																</select>
															    <input type="text" class="form-control input-medium" name="namapasien"  id="namapasien" value="<?= $data->namapas;?>" >																																															
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
															    <input type="text" class="form-control input-medium" name="noidentitas"  id="noidentitas" value="<?= $data->noidentitas;?>" >																																															
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
															   <input type="text" class="form-control" name="namapanggilan" value="<?= $data->namapanggilan;?>" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Nama Keluarga <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="namakeluarga" value="<?= $data->namakeluarga;?>" >															
															 
													        </div>
													        </div>

													</div>
												</div>	
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Tempat Lahir <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="tempatlahir" value="<?= $data->tempatlahir;?>" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Tanggal Lahir <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="date" class="form-control input-medium" name="tgllahir" value="<?= date('Y-m-d',strtotime($data->tgllahir));?>" >															
															 
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
																  <option <?= ($data->jkel=='P'?'selected':'')?> value="P">Pria</option> 
																  <option <?= ($data->jkel=='W'?'selected':'')?> value="W">Wanita</option> 
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
															   <select class="form-control " name="status">
																  <option value="BELUM MENIKAH">BELUM MENIKAH</option> 
																  <option value="MENIKAH">MENIKAH</option> 
																  <option value="DUDA">DUDA</option> 
																  <option value="JANDA">JANDA</option> 
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
															     <?php 
																   if($data->agama){ 
																    $agama = data_master('tbl_setinghms', array('kodeset' => $data->agama))->keterangan;
																   ?>
															       <option value="<?= $data->agama;?>"><?= $agama;?></option>
																   <?php } ?> 
															   </select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Pendidikan <font color="red"></font></label>
													        <div class="col-md-9">
															   <select class="form-control select2_el_pendidikan" id="pendidikan" name="pendidikan">
															    <?php 
																   if($data->pendidikan){ 
																    $pendidikan = data_master('tbl_setinghms', array('kodeset' => $data->pendidikan))->keterangan;
																   ?>
															       <option value="<?= $data->pendidikan;?>"><?= $pendidikan;?></option>
																   <?php } ?> 
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
															   <input type="text" class="form-control" name="goldarah" value="<?= $data->goldarah;?>" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Hobby <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="hobby" value="<?= $data->hoby;?>" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Pekerjaan <font color="red"></font></label>
													        <div class="col-md-9">
															    <select class="form-control select2_el_pekerjaan" id="pekerjaan" name="pekerjaan" value="<?= $data->pekerjaan;?>">
																<?php 
																   if($data->pekerjaan){ 
																    $pekerjaan = data_master('tbl_setinghms', array('kodeset' => $data->pekerjaan))->keterangan;
																   ?>
															       <option value="<?= $data->pekerjaan;?>"><?= $pekerjaan;?></option>
																   <?php } ?> 
																
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
															   <input type="text" class="form-control" name="alamat1" value="<?= $data->alamat;?>" >															
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
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="rtrw" value="" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Provinsi <font color="red"></font></label>
													        <div class="col-md-9">
															    <select class="form-control select2_el_provinsi" id="provinsi" name="provinsi">
																   <?php 
																   if($data->propinsi){ 
																    $propinsi = data_master('tbl_propinsi', array('kodeprop' => $data->propinsi))->namaprop;
																   ?>
															       <option value="<?= $data->propinsi;?>"><?= $propinsi;?></option>
																   <?php } ?> 
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
																   <?php 
																   if($data->kabupaten){ 
																    $kabupaten = data_master('tbl_kabupaten', array('kodekab' => $data->kabupaten))->namakab;
																   ?>
															       <option value="<?= $data->kabupaten;?>"><?= $kabupaten;?></option>
																   <?php } ?> 
																  
																</select>															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Kecamatan <font color="red"></font></label>
													        <div class="col-md-9">
															    <select class="form-control" name="kecamatan" id="kecamatan">
																   <?php 
																   if($data->kecamatan){ 
																    $kecamatan = data_master('tbl_kecamatan', array('kodekec' => $data->kecamatan))->namakec;
																   ?>
															       <option value="<?= $data->kecamatan;?>"><?= $kecamatan;?></option>
																   <?php } ?> 
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
																  <?php 
																   if($data->kelurahan){ 
																    $kelurahan = data_master('tbl_desa', array('kodedesa' => $data->kelurahan))->namadesa;
																   ?>
															       <option value="<?= $data->kelurahan;?>"><?= $kelurahan;?></option>
																   <?php } ?> 
																  
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
															   <input type="text" class="form-control" name="phone" value="<?= $data->phone;?>" >															
															 </div>
													        </div>

													</div>
													<div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Handphone <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="hp" value="<?= $data->handphone;?>" >															
															 
													        </div>
													        </div>

													</div>
												</div>
												
												<div class="row">
												    <div class="col-md-6">
                                                         <div class="form-group">
                                                           <label class="col-md-3 control-label">Email <font color="red"></font></label>
													        <div class="col-md-9">
															   <input type="text" class="form-control" name="email" value="<?= $data->email;?>" >															
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
																  <option value="Pria">Pria</option>
																  <option value="Wanita">Wanita</option>
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
								   
                                    <input type="hidden" name="idpasien" value="<?= $id;?>">
									<button id="btnsimpan" type="button" onclick="save_()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									   
									
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								
							</div>
							
																			
						</div>							
					  </div>	
					</div>  
					
					
				   </form>
				</div>
            </div>
		


<script>


function save_()
{	            
    var nomor     = $('[name="nomember"]').val(); 	
	
	if(nomor==""){
	  swal('','Data Belum Lengkap ...','');   	
	}  else {
	$.ajax({				
		url:'<?php echo site_url('pasien/ajax_update')?>',				
		data:$('#frmpasien').serialize(),				
		type:'POST',
		
		success:function(data){ 		
		 alert('update berhasil...');
		},
		error:function(data){
			swal('','Data gagal disimpan ...','');	
					
		}
		});
	}	
}	

   	
</script>

