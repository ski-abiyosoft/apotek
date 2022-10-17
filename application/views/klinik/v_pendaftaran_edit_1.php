

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">KLINIK <small>Pendaftaran</small>
					</h3>
                   
				</div>
			</div>
			<form id="frmdaftar" class="form-horizontal" method="post">
			<input type="hidden" name="q_id" id="q_id" value="<?= $id; ?>">
			<div class="row">
			  
			  <div class="col-md-6">
			   <div class="portlet box blue">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>REGISTRASI
					</div>
					</div>
				
				<div class="portlet-body">									
				 
				 	<div class="row">	
					    
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-3 control-label">No. Reg <font color="red"></font></label>
								<div class="col-md-9">
								   <input type="text" class="form-control" id="nodaftar" name="nodaftar" placeholder="Otomatis" value="<?= $data->noreg;?>" readonly>																							 
								</div>
								</div>
						</div>
													
					  
					  <div class="col-md-12">
							 <div class="form-group">
							   <label class="col-md-3 control-label">
							   <a href="#" onclick="editpasien()"> Pasien </a>
							   <font color="red">*</font></label>
								<div class="col-md-9">
								 <div class="input-group">
								   <select class="form-control select2_el_pasien" onchange="getinfopasien()" id="pasien" name="pasien">
								     <?php if($data->rekmed){ ?>
									   <option value="<?= $data->rekmed;?>"><?= $data->rekmed.' | '.$data->namapas;?></option>
									 <?php } ?>
								   </select>
								   <input type="hidden" id="idpasien">
								   <span class="input-group-btn">
									 <a class="btn red" onclick="add_pasien()"><i class="fa fa-plus"></i></a>
								   </span>	
								  </div>
								</div>
								</div>

						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							   <label class="col-md-3 control-label">Tanggal Masuk <font color="red">*</font></label>
								<div class="col-md-9">
								 <div class="input-group">
								   <input type="date" class="form-control input-medium" name="tanggal" placeholder="Otomatis" value="<?= date('Y-m-d', strtotime($data->tglmasuk));?>" >															
								   <input type="time" class="form-control input-small" name="jam" placeholder="Otomatis" value="<?= date('H:i:s', strtotime($data->jam));?>" >															
								 </div>	 
								</div>
								</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							   
							</div>
						</div>
						
					</div>								
					  
					  
					
				 
				</div>
			   </div>
			  </div> 
			  
			  <div class="col-md-6">
			   <div class="portlet box green">
				   <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>TUJUAN DOKTER
					</div>
					
					</div>
				
				<div class="portlet-body">									
				  <div class="row">
						<div class="col-md-12">
					  	 <div class="form-group">
						    <label class="col-md-3 control-label">Poli <font color="red">*</font></label>
							<div class="col-md-9">
							   <select class="form-control select2_el_poli" id="poli" name="poli">
							     <?php if($data->kodepos){ 
								   $vpoli = data_master('tbl_namapos', array('kodepos' => $data->kodepos));
								      ?>
									   <option value="<?= $data->kodepos;?>"><?= $data->kodepos.' | '.$vpoli->namapost;?></option>
								 <?php } ?>
							   </select>
							</div>
						 </div>
                        </div> 
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 control-label">Dokter <font color="red">*</font></label>
								<div class="col-md-9">
								   <select class="form-control select2me" id="dokter" name="dokter">
								     <?php if($data->kodokter){ 
									    $vdokter = data_master('tbl_dokter', array('kodokter' => $data->kodokter));
									    ?>
									   <option value="<?= $data->kodokter;?>"><?= $data->kodokter.' | '.$vdokter->nadokter;?></option>
									 <?php } ?>
								   </select>	 
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 control-label">Ruang <font color="red">*</font></label>
								<div class="col-md-5">
								    <select class="form-control select2me" id="ruang" name="ruang">
								    <option value="">--- Pilih ---</option>
								    <?php $poli = $this->db->get("tbl_ruangpoli")->result();
									foreach($poli as $row){ 
									  $selected = ($row->koderuang==$data->kelas?'selected':'');
									?>
									
									<option <?= $selected; ?> value="<?= $row->koderuang;?>"><?= $row->namaruang;?></option>
									<?php } ?>
								   </select>
								</div>
								<label class="col-md-2 control-label">Antri No <font color="red"></font></label>
								<div class="col-md-2">
								   <input type="text" class="form-control" name="noantri" id="noantri" value="<?= $data->antrino;?>">	 	 
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 control-label">Pengirim <font color="red">*</font></label>
								<div class="col-md-9">
								   <select class="form-control select2_el_dokter" id="pengirim" name="pengirim">
								   </select>	 
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Pasien <font color="red">*</font></label>
								<div class="col-md-9">
								   <select class="form-control select2me" id="jenispasien" name="jenispasien">
								    <option value="">--- Pilih ---</option>
								    <?php $jenis = $this->db->get_where("tbl_setinghms", array("lset" => 'JPAS'))->result();
									foreach($jenis as $row){ 
									$selected = ($row->kodeset==$data->jenispas?'selected':'');
									?>
									<option <?= $selected;?> value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
									<?php } ?>
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
						<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
						   
						<a href="<?php echo base_url()?>pendaftaran/entri" class="btn btn-success">
						<i class="fa fa-edit"></i>
						Data Baru
						</a>
							
					</div>							
				</div>															
			</div>
			</br>			
			
		 </form> 
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer_tb');  
?>

<script>


function save()
{	        
    var tanggal   = $('[name="tanggal"]').val(); 
    var pasien    = $('[name="pasien"]').val(); 	
	var poli      = $('[name="poli"]').val(); 	
	var dokter    = $('[name="dokter"]').val(); 	
	var ruang     = $('[name="ruang"]').val(); 	
	var jenispasien= $('[name="jenispasien"]').val(); 	
	var noantri    = $('[name="noantri"]').val(); 	
	
	if(pasien=="" || pasien==null){
	  swal('PENDAFTARAN','Data Belum Lengkap ...','');   	
	}  else {
	$.ajax({				
		url:"<?php echo site_url('pendaftaran/ajax_update')?>",				
		data:$('#frmdaftar').serialize(),				
		type:'POST',
		dataType: 'json',
		
		success:function(data){ 
		  swal(data.noreg,'Pendaftaran berhasil ...','');	
		  document.getElementById("btnsimpan").disabled=true;
		},
		error:function(data){
			swal('PENDAFTARAN','Data gagal disimpan ...','');	
					
		}
		});
	}	
}	

   

</script>

	
