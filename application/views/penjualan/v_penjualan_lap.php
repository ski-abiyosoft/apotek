<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


<link href="<?php echo base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>




			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Penjualan <small>Laporan</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url()?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                              Penjualan
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                              Laporan
							</a>
						</li>
					</ul>
				</div>
			</div>
			
			
											
			<div class="tab-pane" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
													<i class="fa fa-angle-double-right"></i> Daftar Pesanan Penjualan
												</a>
												<span class="after">
												</span>
											</li>											
											<li>
												<a data-toggle="tab" href="#tab_3-3">
													<i class="fa fa-angle-double-right"></i> Daftar Pengiriman Barang
												</a>
											</li>											
											<li>
												<a data-toggle="tab" href="#tab_5-5">
													<i class="fa fa-angle-double-right"></i> Daftar Faktur Penjualan
												</a>
											</li>											
											<li>
												<a data-toggle="tab" href="#tab_7-7">
													<i class="fa fa-angle-double-right"></i> Uang Muka Penjualan
												</a>
											</li>
											
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Daftar Penerimaan 
												</a>
											</li>											
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Daftar Retur Penjualan
												</a>
											</li>											
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Penjualan per Pelanggan
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Penjualan per Barang
												</a>
											</li>											
											
											
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Faktur Belum Lunas
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Umur Piutang
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Buku Besar Pembantu Hutang
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Histori Hutang
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Rincian Umur Hutang
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Rincian Buku Pembantu Hutang
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Pesanan per Barang (Belum Proses)
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_9-9">
													<i class="fa fa-angle-double-right"></i> Pembayaran Faktur
												</a>
											</li>
											
										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
											<div id="tab_1-1" class="tab-pane active">
												<form name="frmlap1" id="frmlap1" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                   
                                                    <tr>
                                                        <td  class="success">Nomor Jurnal</td>
                                                        <td class="warning">
                                                            <input type="text" id="nobukti" name="nobukti" class="form-control input-medium" />
            										    </td>
                                                    </tr>
                                                    <tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit1" name="unit1" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">            												
                                                            <?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
            										
            										<tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">

            											<div class="row form-group">
													<div class="col-md-4">

														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal11" name="tanggal11" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai" size="16"/>
														</div>
													</div>
													<div class="col-md-4">

														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal12" name="tanggal12" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan" size="16"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>
            										

                                                 </table>
													<div class="margiv-top-10">
																												
														<a class="btn green print_laporan" href="#report" id="1" data-toggle="modal">Cetak Laporan</a>                                                        
														<a href="javascript:_urlExport1()" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>
											</div>
											
											<div id="tab_2-2" class="tab-pane">
											    <form name="frmlap2" id="frmlap2" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                    <tr>
                                                      <td class="success">Kode Akun</td>
                                                      <td class="warning">
                                                      <select id="akun2" name="akun2" class="bs-select select2me form-control " data-show-subtext="true" data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
                                                            <?php
																foreach($akuninduk->result()as $row)
																{ ?>
																	<optgroup label="<?php echo $row->namaakun;?>">	
																	<?php
																	  foreach($akundetil->result()as $rowd)
																      { 
																	     if($rowd->akuninduk==$row->kodeakun){
																	  ?>
                                                                        <option data-subtext="<?php echo $rowd->namaakun;?>" value="<?php echo $rowd->kodeakun;?>"><?php echo $rowd->kodeakun.' - '.$rowd->namaakun;?></option>																																		  
																		 <?php 
																		 }
																	  }	 ?> 
                                                                      
																    </optgroup>
																  <?php	
																} 
																
            												?>
            													
                                                            
            												</select>
            										  </td>

            										</tr>
            										
            										<tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit2" name="unit2" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">            											
                                                            <?php
															  foreach($unit->result()as $row){
            												 
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>

                                                    <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">


            											<div class="row form-group">
													<div class="col-md-4">
														<!--label class="control-label">Mulai:</label-->
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal21" name="tanggal21" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														<!--label class="control-label">Sampai Dengan:</label-->
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal22" name="tanggal22" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>

                                                 
                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="2" data-toggle="modal">Cetak Laporan</a>
														<a href="#" onclick="javascript:window.open(_urlExport2());" class="btn red">
                                                           Export Ke Excel
														</a>
													</div>
												</form>

											</div>
											
									<div id="tab_3-3" class="tab-pane">
									   <form name="frmlap3" id="frmlap3" role="form" action="#">
                                                  <table class="table table-bordered1 table-striped1">
                                                    <tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit3" name="unit3" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
                                                 	<tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<!--label class="control-label">Mulai:</label-->
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal31" name="tanggal31" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														<!--label class="control-label">Sampai Dengan:</label-->
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal32" name="tanggal32" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>


                                                 </table>
													<div class="margiv-top-10">
														<a class="btn green print_laporan" href="#report" id="3" data-toggle="modal">Cetak Laporan</a>      
														<a href="#" onclick="javascript:window.open(_urlExport3());" class="btn red" target="_blank">
                                                           Export Ke Excel
														</a>
													</div>
												</form>
									
                                    </div>
                                    
                                    <div id="tab_4-4" class="tab-pane">
                                       <form name="frmlap4" id="frmlap4" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
										            <tr>
                                                      <td class="success">Format Laporan</td>
                                                      <td class="warning">
                                                      <select id="format4" name="format4" class="form-control input-sm select2me input-small " data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
                                                            <?php

            												  foreach($formatn->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode;?></option>
                                                            <?php } ?>
            												</select>															
            										  </td>
            										</tr>
													
                                                  
            										<tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit4" name="unit4" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															  foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
                                              <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal41" name="tanggal41" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal42" name="tanggal42" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>

                                                   <tr>
                                                      <td  class="success">Catatan LK</td>
                                                      <td class="warning">
                                                    		<input type="checkbox" name="cbcatatanlk4" id="cbcatatanlk4" value="Y"/>
            										  </td>
            										</tr>
                                           </table>
                                           <div class="margiv-top-10">
                                             <a class="btn green print_laporan" href="#report" id="4" data-toggle="modal">Cetak Laporan</a>   
                                             <a href="#" onclick="javascript:window.open(_urlExport4());" class="btn red">
                                                Export Ke Excel
                                             </a>
											 
                                           </div>
                                      </form>

                                    </div>
                                    
                                    <div id="tab_5-5" class="tab-pane">
                                       <form name="frmlap5" id="frmlap5" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
										      <tr>
                                                      <td class="success">Format Laporan</td>
                                                      <td class="warning">
                                                      <select id="format5" name="format5" class="form-control input-sm select2me input-small " data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
                                                            <?php

            												  foreach($formatn->result()as $row){
            												  
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>
            										</tr>
                                              <tr>
                                                      <td  class="success">Tipe Laporan</td>
                                                      <td class="warning">
                                                    		<select id="jenis5" name="jenis5" class="form-control select2me input-medium"  data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
            												<option value="1">Rekapitulasi</option>            												
															<option value="5">Rincian - Cabang</option>
            												</select>
            										  </td>
            										</tr>
            										
            										<tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit5" name="unit5" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															  foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
            										
                                              <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal51" name="tanggal51" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal52" name="tanggal52" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>

                                                   <tr>
                                                      <td  class="success">Penjelasan</td>
                                                      <td class="warning">
                                                    		<input type="checkbox" id="cbcatatanlk5" />
            										  </td>
            										</tr>
                                           </table>
                                           <div class="margiv-top-10">
                                             <a href="#" onclick="javascript:window.open(_urlcetak5(),'_blank');" class="btn green">
                                               Cetak Laporan
                                             </a>
                                             <a href="#" onclick="javascript:window.open(_urlExport5());" class="btn red">
                                                Export Ke Excel
                                             </a>
                                           </div>
                                      </form>

                                    </div>
                                    

                                    <div id="tab_6-6" class="tab-pane">
                                       <form name="frmlap6" id="frmlap6" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
                                               <tr>
                                                      <td  class="success">Metode</td>
                                                      <td class="warning">
                                                    		<select id="jenis6" name="jenis6" class="form-control select2me input-medium"  data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
            												<option value="1">Langsung </option>
            												<option value="2">Tidak Langsung</option>
            												<option value="3">Tidak Langsung Rinci Laba Rugi</option>
            												</select>
            										  </td>
            										</tr>
            										
            										<tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit6" name="unit6" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
            										
                                              <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal61" name="tanggal61" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal62" name="tanggal62" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>


                                           </table>
                                           <div class="margiv-top-10">
                                             <a href="#" onclick="javascript:window.open(_urlcetak6(),'_blank');" class="btn green">
                                               Cetak Laporan
                                             </a>
                                             <a href="#" onclick="javascript:window.open(_urlExport6());" class="btn red">
                                                Export Ke Excel
                                             </a>
                                           </div>
                                      </form>

                                    </div>
                                    
                                    <div id="tab_7-7" class="tab-pane">
                                       <form name="frmlap7" id="frmlap7" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
                                              <tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit7" name="unit7" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
                                              <tr>
											  <tr>
                                                      <td class="success">Jenis</td>
                                                      <td class="warning">
                                                      <select id="jenisar" name="jenisar" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<option value="1">LIQUIDITY</option>
															<option value="2">LEVERAGE</option> 
															<option value="3">ACTIVITY</option>                                                            
															<option value="4">PROFITABILITY</option>                                                            	
            										  </select>
            										  </td>

            										</tr>
                                              <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal71" name="tanggal71" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
														
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal72" name="tanggal72" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>


                                           </table>
                                           <div class="margiv-top-10">
                                             <a href="#" onclick="javascript:window.open(_urlcetak7(),'_blank');" class="btn green">
                                               Cetak Laporan
                                             </a>
											 
											 											
                                             <a href="#" onclick="javascript:window.open(_urlExport7());" class="btn red">
                                                Export Ke Excel
                                             </a>
											 
                                           </div>
										   
										   
                                      </form>

                                    </div>
                                    
                                    <div id="tab_8-8" class="tab-pane">
                                       <form name="frmlap8" id="frmlap8" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
                                              <tr>
                                                      <td  class="success">Tipe Laporan</td>
                                                      <td class="warning">
                                                    		<select id="jenis8" name="jenis8" class="form-control select2me input-medium"  data-placeholder="Pilih...">
            												<option value="NONE">&nbsp</option>
            												<option value="1">Rincian</option>
            												
            												
            												</select>
            										  </td>
            										</tr>
            										
            										<tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit8" name="unit8" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															  foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
                                              <tr>
                                                       <td  class="success">Tahun</td>
                                                        <td class="warning">
                                                            <input type="text" id="tahun8" name="tahun8" class="form-control input-small" value="<?php echo $_tahun;?>"/>
            										    </td>
            										</tr>


                                           </table>
                                           <div class="margiv-top-10">
                                             <a href="#" onclick="javascript:window.open(_urlcetak8(),'_blank');" class="btn green">
                                               Cetak Laporan
                                             </a>
                                             <a href="#" onclick="javascript:window.open(_urlExport8());" class="btn red">
                                                Export Ke Excel
                                             </a>
                                           </div>
                                      </form>

                                    </div>
                                    
                                    <div id="tab_9-9" class="tab-pane">
                                       <form name="frmlap9" id="frmlap9" role="form" action="#">
                                           <table class="table table-bordered1 table-striped1">
                                              <tr>
                                                      <td class="success">Cabang</td>
                                                      <td class="warning">
                                                      <select id="unit9" name="unit9" class="form-control input-sm select2me input-large " data-placeholder="Pilih...">
            												<?php
															 foreach($unit->result()as $row){
            												?>
            													<option value="<?php echo $row->kode;?>"><?php echo $row->kode.' - '.$row->nama;?></option>
                                                            <?php } ?>
            												</select>
            										  </td>

            										</tr>
                                              <tr>
                                                       <td class="success">Periode Data</td>
                                                       <td class="warning">
                                                       <div class="row form-group">
													<div class="col-md-4">
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal91" name="tanggal91" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
														</div>
													</div>
													<div class="col-md-4">
													
														<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input id="tanggal92" name="tanggal92" class="form-control date-picker input-medium" size="16" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
														</div>
													</div>
												</div>
                                                       </td>
            										</tr>


                                           </table>
                                           <div class="margiv-top-10">
                                             <a href="#" onclick="javascript:window.open(_urlcetak9(),'_blank');" class="btn green">
                                               Cetak Laporan
                                             </a>
                                             <a href="#" onclick="javascript:window.open(_urlExport9());" class="btn red">
                                                Export Ke Excel
                                             </a>
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

<script src="<?php echo base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/clockface/js/clockface.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-dropdowns.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-pickers.js"></script>

<script>
    
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('BUKU BESAR');
		if(this.id==1)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal11').value+'~'+document.getElementById('tanggal12').value+'~'+document.getElementById('unit1').value+
		'~'+document.getElementById('nobukti').value;
		} else
		if(this.id==2)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal21').value+'~'+document.getElementById('tanggal22').value+'~'+document.getElementById('unit2').value+
		'~'+document.getElementById('akun2').value;
		} else
		if(this.id==3)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal31').value+'~'+document.getElementById('tanggal32').value+'~'+document.getElementById('unit3').value;
		} else
		if(this.id==4)		
		{	
		var param = this.id+'~'+document.getElementById('tanggal41').value+'~'+document.getElementById('tanggal42').value+'~'+document.getElementById('unit4').value+
		'~'+document.getElementById('format4').value+'~'+document.getElementById('cbcatatanlk4').checked;
		}	
		
		
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>akuntansi_laporan/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	 jQuery(document).ready(function() {
		ComponentsDropdowns.init(); 
        ComponentsPickers.init();
		
        });
	
	
	
</script>

