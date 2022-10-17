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
                      <span class="title-web">KLINIK <small>Laporan Pendaftaran</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="../home.php">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                              Pendaftaran
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
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
                         Laporan - laporan untuk Pendaftaran 
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
														       <select id="idlap" name="idlap" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih..." onchange="cetak_pasien()">
																	<optgroup label="Keuangan">	
																		<option data-subtext="101" value="101">01. LAPORAN PENDAFTARAN PASIEN (URUT BERDASARKAN UNIT)</option>	
																		<option data-subtext="102" value="102">02. REKAP PENDAFTARAN PASIEN BERDASARKAN UNIT  </option>																			
																		<option data-subtext="103" value="103">03. GRAFIK KUNJUNGAN PASIEN</option>	
																		<option data-subtext="104" value="104">04. CETAK DATA PASIEN</option>	
																		<option data-subtext="104" value="105">05. LAPORAN PASIEN PER DOKTER PRAKTEK</option>	
																		<option data-subtext="104" value="106">06. LAPORAN REKAP PASIEN</option>	
																		
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
														       
															    <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" placeholder="" />
													    	   
													        </div>
															<label class="col-md-2 control-label">s/d</label>
															<div class="col-md-3">
														        <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>"/>
													    	   
													        </div>



														</div>
													</div>
													
													
													
												</div>
												
												<div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Dokter</label>
													        <div class="col-md-9">
														      <select name="dokter" id="dokter" class="select2_el_dokter form-control" >
															  </select>																												    
													       </div>
                                                           </div>
													</div>
														
													
												</div>	
                                                <div class="row">
												    <div class="col-md-12">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Unit</label>
													        <div class="col-md-9">
														      <select name="unit" id="unit" class="select2_el_poli form-control" >
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
														      <select name="cabang" id="cabang" class="select2_el_cabang form-control" >
																<?php $cbg = $this->db->query("select koders, namars from tbl_namers where koders= '".$this->session->userdata('unit')."' order by namars")->result();
																	foreach($cbg as $row){ 
																		$selected = ($row->koders==$this->session->userdata('unit')?'selected':'');
																	?>
																	<option <?= $selected;?> value="<?= $row->koders;?>"><?= $row->namars;?></option>
																<?php } ?>
															  </select>																													    
													            
													     	</div>
                                                           </div>
													</div>
														
													
												</div>	

												<div class="row" id="urut">
													<div class="col-md-12">
														<div class="form-group">
															<label class="col-md-3 control-label">Urut</label>
															<div class="col-md-3">
																<select name="rekam_medis" id="rekam_medis" class="form-control">
																	<option value="">-- Pilih --</option>
																	<option value="1">Rekam Medis</option>
																	<option value="2">Nama Pasien</option>
																</select>
															</div>
															<div class="col-md-2">
																<select name="baru_lama" id="baru_lama" class="form-control">
																	<option value="">-- Pilih --</option>
																	<option value="1">Baru</option>
																	<option value="2">Lama</option>
																	<option value="3">Baru + Lama</option>
																</select>
															</div>
															<div class="col-md-4">
																<div class="row">
																	<div class="col-md-1" style="margin-top:-5px;">
																		<div class="form-group">
																			<input type="checkbox" id="pasien_batal" name="pasien_batal" value="1" class="form-control">
																		</div>
																	</div>
																	<div class="col-md-10" style="margin-top:6px;">
																		 Munculkan Pasien Batal
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												
												
                                                
											<div class="row">
												<div class="col-md-offset-3 col-md-9">
												    <a class="btn btn-sm red print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" ><i title="PDF" class="glyphicon glyphicon-print"></i> <b>PDF</b></a>
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
   $this->load->view('template/v_report');
?>


<script>
	$('#urut').hide();
	
	function cetak_pasien(){
	var kondisi = $('#idlap').val();
	if(kondisi == 104){
		$('#urut').show('200');
	} else if(kondisi != 104){
		$('#urut').hide('200');
	}
}

var cab = '<?php echo $this->session->userdata('unit'); ?>';
console.log(cab);
$('#cabang').val(cab).change();

function _urlcetak()
{
	var rekmed   = $('#rekam_medis').val();
	var bl       = $('#baru_lama').val();
	var pb1      = $('#pasien_batal').is(':checked');
	if(pb1 == true){
		pb          = 1;
	} else if(pb1 == false){
		pb          = 0;
	}
	var baseurl  = "<?php echo base_url()?>";
	var idlap    = $('[name="idlap"]').val();
	var tgl1     = $('[name="tanggal1"]').val();
	var tgl2     = $('[name="tanggal2"]').val();
	var dokter   = $('[name="dokter"]').val();
	var unit     = $('[name="unit"]').val();
	var cbg      = $('[name="cabang"]').val();
	var param= '?idlap='+idlap+'&tgl1='+tgl1+'&tgl2='+tgl2+'&dokter='+dokter+'&cabang='+cbg+'&unit='+unit+'&rekmed='+rekmed+'&bl='+bl+'&pb='+pb;
	
    return baseurl+'pendaftaran_laporan/cetak/'+param;
}
	
</script>