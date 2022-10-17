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
                      <span class="title-web">Master <small>Konfigurasi Aplikasi</small>
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
							<a href="">
								Konfigurasi Aplikasi
							</a>

						</li>

					</ul>
				</div>
			</div>

			<div class="row profile">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom tabbable-full-width">
						<ul class="nav nav-tabs">
                       <li class="active">
							<!--a href="#tab_1_1" data-toggle="tab">
                                   Beranda
							</a-->
							</li>
							<li class="active">
								<a href="#tab_1_3" data-toggle="tab">
                                   Konfigurasi Aplikasi
								</a>
							</li>

						</ul>
						<div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
								<div class="row">
									
								</div>
							</div>
							<div class="tab-pane" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
													<i class="fa fa-angle-double-right"></i> Identitas Perusahaan
												</a>
												<span class="after">
												</span>
											</li>
											
											<li>
												<a data-toggle="tab" href="#tab_3-3">
													<i class="fa fa-angle-double-right"></i> Periode Data
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_4-4">
													<i class="fa fa-angle-double-right"></i> Akun Referensi
												</a>
											</li>
											<!--li>
												<a data-toggle="tab" href="#tab_5-5">
													<i class="fa fa-angle-double-right"></i> Nomor Urut Transaksi
												</a>
											</li-->

										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
											<div id="tab_1-1" class="tab-pane active">
												<form id="frmidusaha" role="form" action="#">
												    <div class="form-group">
														<label class="control-label">Cabang </label>
														
													</div>
													<div class="form-group">
														<label class="control-label">Nama </label>
														<input type="text" placeholder="" class="form-control" value="" id="_nama" name="_nama"/>
													</div>
													<div class="form-group">
														<label class="control-label">Nomor Telepon</label>
														<input type="text" placeholder="" class="form-control" value="" id="_telpon" name="_telpon"/>
													</div>
													
													

													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanid">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error1" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success1" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												</form>
											</div>
											


											<div id="tab_3-3" class="tab-pane">
                                                	<form id="frmperiode" role="form" action="#">
													<div class="form-group">
														<label class="control-label">Tahun </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $this->M_global->_periodetahun();?>" id="_tahun" name="_tahun" maxlength="4"/>
													</div>
													<div class="form-group">
														<label class="control-label">Bulan</label>
                                                        <select id="_bulan" name="_bulan" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih...">
                                                         <option value="NONE">&nbsp</option>
                                                         <?php
                                                           for($i=1;$i<=12;$i++)
                                                           {
                                                             if($this->M_global->_periodebulan()==$i)
                                                             { ?>
                                                               <option value="<?php echo $i;?>" selected='true'><?php echo $this->M_global->_namabulan($this->M_global->_periodebulan());?></option>
                                                             <?php
                                                             } else
                                                             {
                                                             ?>
                                                               <option value="<?php echo $i;?>"><?php echo $this->M_global->_namabulan($i);?></option>
                                                             <?php
                                                             }
                                                           }

                                                         ?>

            											</select>
														
														
													</div>

													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanperiode">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error3" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success3" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												    </form>
											</div>
                                            <div id="tab_4-4" class="tab-pane">
                                                	<form id="frmakun" role="form" action="#">
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun L/R Berjalan </label>
															<select name="_akunlrberjalan" id="_akunlrberjalan" class="select2_el form-control input-xlarge" >
															 <option value="<?= $akunlrberjalan;?>"><?= $akunlrberjalan;?></option>
														   </select>
															
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun L/R Lalu </label>
															<select class="select2_el form-control input-xlarge" name="_akunlrlalu" id="_akunlrlalu">
																<option value="<?= $akunlrlalu;?>"><?= $akunlrlalu;?></option> 														  
															</select>
														</div>
													</div>
													
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun Persediaan Transit </label>
															<select class="select2_el input-xlarge form-control " name="_akunpersediaantransit" id="_akunpersediaantransit">
															  <option value="<?= $akunpersediaantransit;?>"><?= $akunpersediaantransit;?></option> 														    
															</select>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun Persediaan  </label>
															<select class="select2_el form-control  input-xlarge" name="_akunpersediaan" id="_akunpersediaan">
															  <option value="<?= $akunpersediaan;?>"><?= $akunpersediaan;?></option> 
															</select>
														</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Biaya Kerugian Lain </label>
														<select class="form-control select2_el input-xlarge" name="_akunbiayakerugianlain" id="_akunbiayakerugianlain">
														  <option value="<?= $akunbiayakerugianlain;?>"><?= $akunbiayakerugianlain;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Pendapatan Lain </label>
														<select class="form-control select2_el input-xlarge" name="_akunpendapatanlain" id="_akunpendapatanlain">
														  <option value="<?= $akunpendapatanlain;?>"><?= $akunpendapatanlain;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Penjualan </label>
														<select class="form-control select2_el input-xlarge" name="_akunpenjualan" id="_akunpenjualan">
														  <option value="<?= $akunpenjualan;?>"><?= $akunpenjualan;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun PPN </label>
														<select class="form-control select2_el input-xlarge" name="_akunppn" id="_akunppn">
														  <option value="<?= $akunppn;?>"><?= $akunppn;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Ongkir </label>
														<select class="form-control select2_el input-xlarge" name="_akunongkir" id="_akunongkir">
														   <option value="<?= $akunongkir;?>"><?= $akunongkir;?></option> 
														 													  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Uang Muka </label>
														<select class="form-control select2_el input-xlarge" name="_akunuangmuka" id="_akunuangmuka">
														    <option value="<?= $akunuangmuka;?>"><?= $akunuangmuka;?></option> 
														 													  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun HPP </label>
														<select class="form-control select2_el input-xlarge" name="_akunhpp" id="_akunhpp">
														    <option value="<?= $akunhpp;?>"><?= $akunhpp;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Kas </label>
														<select class="form-control select2_el input-xlarge" name="_akunkas" id="_akunkas">
														   <option value="<?= $akunkas;?>"><?= $akunkas;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Hutang </label>
														<select class="form-control select2_el input-xlarge" name="_akunhutang" id="_akunhutang">
														  <option value="<?= $akunhutang;?>"><?= $akunhutang;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun UM Jual </label>
														<select class="form-control select2_el input-xlarge" name="_akunuangmukajual" id="_akunuangmukajual">
														   <option value="<?= $akunuangmukajual;?>"><?= $akunuangmukajual;?></option> 
														 													  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Piutang </label>
														<select class="form-control select2_el input-xlarge" name="_akunpiutang" id="_akunpiutang">
														  <option value="<?= $akunpiutang;?>"><?= $akunpiutang;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Ongkir Jual </label>
														<select class="form-control select2_el input-xlarge" name="_akunongkirjual" id="_akunongkirjual">
														 <option value="<?= $akunongkirjual;?>"><?= $akunongkirjual;?></option> 
														 												  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Retur Jual </label>
														
														<select class="form-control select2_el input-xlarge" name="_akunretjual" id="_akunretjual">
														  <option value="<?= $akunongkirjual;?>"><?= $akunongkirjual;?></option> 
														 														  
														</select>
													</div>
													</div>
													<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label">Akun Hutang Belum Ditagih </label>
														<select class="form-control select2_el input-xlarge" name="_akunhutangbeli" id="_akunhutangbeli">
														   <option value="<?= $akunhutangbeli;?>"><?= $akunhutangbeli;?></option> 
														 														  
														</select>
													</div>
													</div>
													
													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanakun">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error4" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success4" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												    </form>
											</div>
											
											<div id="tab_5-5" class="tab-pane">
                                                	<form id="frmnomor" role="form" action="#">
													<div class="form-group">
														<label class="control-label">Jurnal Umum</label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor1;?>" id="_nomor1" name="_nomor1"/>
													</div>
													<div class="form-group">
														<label class="control-label">Penerimaan Kas </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor2;?>" id="_nomor2" name="_nomor2"/>
													</div>
													<div class="form-group">
														<label class="control-label">Pengeluaran Kas </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor3;?>" id="_nomor3" name="_nomor3"/>
													</div>
                                                	
													<div class="form-group">
														<label class="control-label">Transfer Kas/Bank </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor5;?>" id="_nomor5" name="_nomor5"/>
													</div>
													<div class="form-group">
														<label class="control-label">Penerimaan Bank </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor6;?>" id="_nomor6" name="_nomor6"/>
													</div>
													<div class="form-group">
														<label class="control-label">Pengeluaran Bank </label>
														<input type="text" placeholder="" class="form-control input-small" value="<?php echo $nomor7;?>" id="_nomor7" name="_nomor7"/>
													</div>
													

													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpannomor">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error4" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success4" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												    </form>
											</div>
											
											

										</div>
									</div>
								</div>
							</div>
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

$('#btnsimpanid').on("click", function(){
		   
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/1')?>',				
        		data:$('#frmidusaha').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		
		$('#btnsimpanbos').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/2')?>',				
        		data:$('#frmbos').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		
		$('#btnsimpanperiode').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/3')?>',				
        		data:$('#frmperiode').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		$('#btnsimpanakun').on("click", function(){
		   
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/4')?>',				
        		data:$('#frmakun').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		$('#btnsimpannomor').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/5')?>',				
        		data:$('#frmnomor').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	

</script>
</body>
</html>


