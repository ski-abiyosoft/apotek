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
						<span class="title-web"> Kasir <small>Pembayaran Obat</small> </span>
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
							<a class="title-white" href="<?php echo base_url();?>kasir_obat">
                               Daftar Pembayaran Obat
                              							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a class="title-white" href="">
                               Pembayaran
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>Edit Pembayaran Obat
					</div>
					
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmkonsul" class="form-horizontal" method="post">
				    <div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabsx nav-pills">
							
							<li class="active">
								<a href="#tab2" data-toggle="tab">
                                   Pembayaran
								</a>
							</li>
							
						</ul>
						<div class="tab-content">
							
							<div class="tab-pane active" id="tab2">	
							   <!--form id="frmbayar" class="form-horizontal" method="post"-->
							   <!-- <div class="row"> -->
								   <!-- harimas begin -->
							   <div class="col-md-12">
							   <div class="portlet box blue">

									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i><b>KWITANSI</b>
										</div>
									</div>
									<input type="hidden" name="rekmed" id="rekmed" value="<?= $kasir->rekmed;?>">
									<input type="hidden" name="noreg" id="noreg" value="<?= $kasir->noreg;?>">
									<div class="portlet-body">
										<table class="table" >
											<tr>
																						  
												<td>NO. KWITANSI</td>												  
												<td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="<?= $kasir->nokwitansi;?>" readonly></td>
											</tr>
											<tr style="border-top:none;">
												<td>NO. RESEP</td>												  
												<td><input type="text" class="form-control" name="noresep" id="noresep" value="<?= $resep['resepno'];?>" readonly></td>
											</tr>
											<tr>
												<td>FAKTUR PAJAK</td>	  
												<td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="<?= $kasir->pajak;?>"></td>
											</tr>
											<tr style="border-top:none;">
												<td>TGL & JAM BAYAR</td>												  
												<td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i', strtotime(substr($kasir->tglbayar, 0, 10).''.$kasir->jambayar ));?>"></td>
											</tr>
											<tr>	
												<td>PRO</td>												  
												<td><input type="text" class="form-control" name="namapasien" id="namapasien" value="<?= $kasir->namapasien;?>" ></td>
																								
											</tr>
											<tr>	
												<td>TOTAL RESEP RP</td>												  
												<td><input type="text" class="form-control rightJustified" name="reseprp" id="reseprp" value="<?= $kasir->totalresep;?>" readonly></td>
											</tr>
											<tr>
												<td>UANG RESEP RP</td>
												<td>
												<input type="text" class="form-control rightJustified" name="uangr" id="uangr" value="<?= $uangr ?>" readonly>
												</td>
											</tr>											
											
										</table>
									</div>
								</div>

								<!-- end harimas -->
									 <!-- <div class="col-md-4">
									   <div class="portlet ">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="label label-info">KWITANSI</span>												
											</div>																						
										</div>
										<input type="hidden" name="rekmed" id="rekmed" value="<?= $kasir->rekmed;?>">
										<input type="hidden" name="noreg" id="noreg" value="<?= $kasir->noreg;?>">
										<div class="portlet-body form">									
										    <div class="form-body">
											 	
											  <table class="table">
											    <tr>
												  <td>NO. KWITANSI</td>												  
												  <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="<?= $kasir->nokwitansi;?>" readonly></td>
												  
												</tr>
												 <tr>
												  <td>NO. RESEP</td>												  
												  <td><input type="text" class="form-control" name="noresep" id="noresep" value="<?= $resep->resepno;?>" readonly></td>
												  
												</tr>
												<tr>
												  <td>FAKTUR PAJAK</td>												  
												  <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="<?= $kasir->pajak;?>"></td>
												  
												</tr>
												<tr>
												  <td>TGL & JAM BAYAR</td>												  
												  <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i', strtotime($kasir->tglbayar));?>"></td>
												  
												</tr>
												<tr>
												  <td>PRO</td>												  
												  <td><input type="text" class="form-control" name="namapasien" id="namapasien" value="<?= $kasir->namapasien;?>" ></td>
												  
												</tr>
												
												<tr>
												  <td>TOTAL RESEP RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="reseprp" id="reseprp" value="<?= $kasir->totalresep;?>" readonly></td>
												  
												</tr>
												
											  </table>
											   
											</div>
										 
										</div>
										</div>
									</div> -->
									<!-- <div id="tabpromo" class="portlet box blue">

										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i><b>PROMO</b>
											</div>
										</div>		

										<div class="portlet-body">		
											<table class="table table-hoverx table-striped table-bordered table-condensed table-scrollable" width="100%" border="0">
												<div class="col-md-12">
													<div class="form-group">
													<thead class="breadcrumb">
														<tr>														
															<td class="breadcrumb title-white" align="center" width="10%" halign="justify"><b>No</b></td>
															<td class="breadcrumb title-white" align="center" width="30%" halign="justify"><b>Promo</b></td>
															<td class="breadcrumb title-white" align="center" width="30%" halign="justify"><b>Hadiah</b></td>
															<td class="breadcrumb title-white" align="center" width="30%" halign="justify"><b>Qty</b></td>

														</tr>
														
													</thead>
														<tr>
															<td align="center"> 1 </td>			  
															<td align="center">
																<select name="promo1" class="form-control select2_el_promo input-medium">
																<option value="">--- Pilih Promo1 ---</option>
																</select>
															</td>

															<td align="center">
																<select name="hadiah1" class="form-control select2_el_hadiah input-medium">
																<option value="">--- Pilih Hadiah1 ---</option>
																</select>
															</td>													  
															<td align="center">
																<input type="text" class="form-control input-small rightJustified" name="qtyhadiah1" id="qtyhadiah1" value="0">
															</td>
															
															
														</tr>

														<tr>
															<td align="center"> 2 </td>			  
															<td align="center">
																<select name="promo2" class="form-control select2_el_promo input-medium">
																<option value="">--- Pilih Promo2 ---</option>
																</select>
															</td>

															<td align="center">
																<select name="hadiah2" class="form-control select2_el_hadiah input-medium">
																<option value="">--- Pilih Hadiah2 ---</option>
																</select>
															</td>													  
															<td align="center">
																<input type="text" class="form-control input-small rightJustified" name="qtyhadiah2" id="qtyhadiah2" value="0">
															</td>
															
															
														</tr>

														<tr>
															<td align="center"> 3 </td>			  
															<td align="center">
																<select name="promo[]" class="form-control select2_el_promo input-medium">
																<option value="">--- Pilih Promo1 ---</option>
																</select>
															</td>

															<td align="center">
																<select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
																<option value="">--- Pilih Hadiah1 ---</option>
																</select>
															</td>												  
															<td align="center"><input type="text" class="form-control rightJustified input-small" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>
															
															
														</tr>

														<tr>
															<td align="center"> 4 </td>			  
															<td align="center">
																<select name="promo[]" class="form-control select2_el_promo input-medium">
																<option value="">--- Pilih Promo1 ---</option>
																</select>
															</td>

															<td align="center">
																<select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
																<option value="">--- Pilih Hadiah1 ---</option>
																</select>
															</td>												  
															<td align="center"><input type="text" class="form-control rightJustified input-small" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>
															
															
														</tr>

														<tr>
															<td align="center"> 5 </td>			  
															<td align="center">
																<select name="promo[]" class="form-control select2_el_promo input-medium">
																<option value="">--- Pilih Promo1 ---</option>
																</select>
															</td>

															<td align="center">
																<select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
																<option value="">--- Pilih Hadiah1 ---</option>
																</select>
															</td>												  
															<td align="center"><input type="text" class="form-control rightJustified input-small" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>
															
															
														</tr>
														
														
													</div>
												</div>
											</table>
										</div>
									</div>  -->
									<!-- <div class="col-md-2">
									</div>
									
									<div class="col-md-4">
									   <div class="portlet ">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="label label-success">PROMO</span>												
											</div>
											
											
										</div>
										
										<div class="portlet-body form">									
										    <div class="form-body">
											   <table class="table" width="100%" border="0">
													<div class="col-md-6">
													<div class="form-group">
														<tr>
														<td width="20%" style="border-bottom:none;background: #0855d5;"  class="title-white"><b>&nbsp;ADA PROMO ?</b></td>

														<td width="10%" style="font-weight:bold;border-top:none;" align="center" >&nbsp;ADA
														</td>

														<td width="10%" style="font-weight:bold;border-top:none;" align="center">TIDAK
														</td>
														</tr>
														<tr>
														<td >&nbsp;
														</td>
														<td style="font-weight:bold;border-top:none;">
															<input type="checkbox" name="adapromo" class="form-control cekpromo" value=1
															onclick="cekpro(1)" id="adapromo">
														</td>

														<td style="font-weight:bold;border-top:none;" >
															<input type="checkbox" checked="checked" name="adapromo" class="form-control cekpromo"
															onclick="cekpro(2)" value=0 id="tidakadapromo">
														</td>
														<td width="80%" style="border-top:none;"></td>
														</tr>

													</div>
													</div>
												</table>
											</div>
										 
										</div>
										</div>
									</div>
								 </div> -->
								 <div class="row">
									<div class="col-md-12">
									   <div class="portlet box red">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="title-white">PENGURANGAN</span>												
											</div>
											
											
										</div>
										
										<div class="portlet-body form">																			  
											<div class="form-body">
											   <table class="table">
											    <tr>
												  
												  <td>DISKON TOTAL %</td>												  												  
												  <td><input type="text" class="form-control rightJustified" name="diskonpr" id="diskonpr" value="<?= $kasir->diskon;?>" onchange="total_net()"></td>
												  <td><input type="text" class="form-control rightJustified" name="diskonrp" id="diskonrp" value="<?= $kasir->diskonrp;?>" onchange="total_net()"></td>												  												  
												  
												</tr>
												
												<tr>
												  <td>UANG MUKA</td>												  
												  <td><input type="text" class="form-control rightJustified" name="uangmuka" id="uangmuka" value="<?= $kasir->umuka;?>" onchange="total_net()"></td>
												  <td> <input type="button" class="btn btn-info" value="CEK DP" onclick="getuangmuka()"></td>
												  
												</tr>
												<tr>
												  <td>REFUND RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="refund" id="refund" value="<?= $kasir->refund;?>" onchange="total_net()"></td>
												  
												</tr>
												<tr>
												  <td>VOUCHER CODE</td>												  
												  <td><input type="text" class="form-control" name="vouchercode" id="vouchercode" value="<?= $kasir->novoucher1;?>"></td>
												  <td><input type="text" class="form-control rightJustified" name="voucherrp" id="voucherrp" placeholder="Voucher RP" value="<?= $kasir->voucherrp1;?>" onchange="total_net()"></td>
												  
												</tr>
												<tr>
												  <td>TOTAL NET RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="totalnet" id="totalnet" value="<?= $kasir->totalsemua + $uangr;?>" readonly></td>
												  
												</tr>
												
												
											  </table>
											</div>
										 
										</div>
										</div>
									</div>
									

								</div>

                                <div class="row">								
									<div class="col-md-12">
									   <div class="portlet box yellow">
										<div class="portlet-title">
											<div class="caption">
                                                <div class="caption">
                                                    <i class="fa fa-reorder"></i><b> PEMBAYARAN</b>
                                                </div>
											</div>
										</div>
										<div class="portlet-body form">
                                            										
											<div class="form-body">
												<span class="label label-info"><b>ELECTRONIC (DEBET/CREDIT/TRANFER/EMONEY)</b></span> 

										        <div class="row">
												 <div class="col-md-12">
                                                   	<table id="datatable_pembayaran" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													<thead  class="breadcrumb">
                    									<th class="title-white" width="20%" style="text-align: center">Bank/Provier</th>
														<th class="title-white" width="10%" style="text-align: center">Pay Type</th>
														<th class="title-white" width="15%" style="text-align: center">No. Kartu/Member</th>
														<th class="title-white" width="10%" style="text-align: center">Tr Valid No</th>
														<th class="title-white" width="15%" style="text-align: center">Nilai Transaksi</th>
														<th class="title-white" width="10%" style="text-align: center">Adm %</th>
														<th class="title-white" width="20%" style="text-align: center">Grand Total</th>                    									
                    									
                    								</thead>
													
                    								<tbody>
														<?php if($kredit) : ?>
														<?php $no = 1; foreach($kredit as $k) : ?>
														<tr>
															<td width="20%">
																<select name="bayar_bank[]" id="bayar_bank<?= $no; ?>"
																class="select2_el_kasbankedc  form-control input-large">
																<option value="<?= $k->bankcode; ?>"><?= $k->namabank; ?></option>
																</select>
															</td>
															<td width="10%">
																<select name="bayar_tipe[]" id="bayar_tipe<?= $no; ?>" class="form-control select2">
																<option <?= ($k->cardtype == '1' ? 'selected' : '') ?> value="1">DEBIT</option>
																<option <?= ($k->cardtype == '2' ? 'selected' : '') ?> value="2">CREDIT CARD
																</option>
																<option <?= ($k->cardtype == '3' ? 'selected' : '') ?> value="3">TRANFER</option>
																<option <?= ($k->cardtype == '4' ? 'selected' : '') ?> value="4">ONLINE</option>
																<option <?= ($k->cardtype == '5' ? 'selected' : '') ?> value="5">QRIS</option>
																</select>
															</td>
															<td width="15%"><input name="bayar_nokartu[]" id="bayar_nokartu<?= $no; ?>"
																value="<?= $k->nocard; ?>" class="form-control"></td>
															<td width="10%"><input name="bayar_trvalid[]" id="bayar_trvalid<?= $no; ?>"
																onchange="totalline_bayar(1)" value="<?= $k->nootorisasi; ?>" type="text"
																class="form-control rightJustified"></td>
															<td width="15%"><input name="bayar_nilai[]" id="bayar_nilai<?= $no; ?>"
																onchange="totalline_bayar(1)" value="<?= number_format($k->jumlahbayar); ?>"
																type="text" class="form-control rightJustified "></td>
															<td width="10%"><input name="bayar_adm[]" id="bayar_adm<?= $no; ?>"
																onchange="totalline_bayar(1)" value="<?= number_format($k->admpersen); ?>"
																type="text" class="form-control rightJustified "></td>
															<td width="20%"><input name="bayar_total[]" id="bayar_total<?= $no; ?>" type="text"
																value="<?= number_format($k->totalcardrp); ?>" class="form-control rightJustified"
																readonly></td>

														</tr>
														<?php $no++; endforeach; ?>
														<?php else : ?>
													
														<tr>													   
															<td width="20%">
																<select name="bayar_bank[]" id="bayar_bank1" class="select2_el_kasbankedc form-control input-largex">															 
																</select>												
															</td>
															<td width="10%">
																<select name="bayar_tipe[]" id="bayar_tipe1" class="form-control select2">
																<option value="1">DEBIT</option>	
																<option value="2">CREDIT CARD</option>
																<option value="3">TRANFER</option>
																<option value="4">ONLINE</option>
																<option value="5">QRIS</option>
																</select>												
															</td>
															<td width="15%"><input name="bayar_nokartu[]" class="form-control"></td>								
															<td width="10%"><input name="bayar_trvalid[]"  onchange="totalline_bayar(1)" value="0"  type="text" class="form-control rightJustified"  ></td>
															<td width="15%"><input name="bayar_nilai[]"   onchange="totalline_bayar(1)" value="0"  type="text" class="form-control rightJustified "  ></td>
															<td width="10%"><input name="bayar_adm[]"   onchange="totalline_bayar(1)" value="0"  type="text" class="form-control rightJustified "  ></td>
															<td width="20%"><input name="bayar_total[]"  type="text" class="form-control rightJustified" readonly></td>
														
														</tr>
														
														<?php endif; ?>
                    								
								                    </tbody>
													  
													</table>
													
								                   </div>
												</div>
												
												<div class="row">
												 <div class="col-md-12">
												  	<button type="button" onclick="tambah_bayar()" class="btn green"><i class="fa fa-plus"></i></button>
													<button type="button" onclick="hapus_bayar()"  class="btn red"><i class="fa fa-trash-o"></i></button>													
												 </div>
												</div> 	   
											</div>
										 
										</div>
										</div>
									</div>
								</div>	
								
								<div class="row">
								  <div class="col-md-9">
								   <table class="table">
											  
												<tr>
												  <td>TOTAL ELECTRONIC RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="totalelectronicrp" id="totalelectronicrp" value="<?= $kasir->bayarcard;?>" readonly onchange="total_net()"></td>
												  
												</tr>
												<tr>
												  <td>TOTAL TUNAI RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="totaltunairp" id="totaltunairp" readonly value="<?= number_format($kasir->bayarcash, 2, '.', ',') ?>" onchange="total_net()" ></td>
												  
												</tr>
												<tr>
												  <td>UANG PASIEN RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="uangpasienrp" id="uangpasienrp" value="0" onchange="total_net()" readonly></td>
												  
												</tr>
												<tr>
													<td>KEMBALI RP</td>												  
													<td><input type="text" class="form-control total rightJustified" name="kembalirp" id="kembalirp" value="<?= number_format($kasir->kembali, 2, '.', ',') ?>" readonly></td>
													<td style="border-top:none;">
														<span id="pertanyaan">Auto ke uang muka</span></td>
													<td style="border-top:none;" width="10%">
														<input type="checkbox" id="uangmukakembaliya" name="kembaliuang" <?= ($kasir->kembalikeuangmuka == 1 ? 'checked' : '') ?> value="1"><span id="textya"></span>
													</td>
													<td style="border-top:none;" width="10%">
														<input type="checkbox" id="uangmukakembalitidak" name="kembaliuang" <?= ($kasir->kembalikeuangmuka == 0 ? 'checked' : '') ?> value="0"><span id="texttidak">Kembali ke pasien</span>
													</td>
												  
												</tr>
												<tr>
													<td>SUDAH TERIMA DARI</td>
													<td><input type="text" class="form-control total rightJustified" name="terimadari" id="terimadari"
														value="<?= $kasir->dibayaroleh ?>" readonly></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>

												<tr>
													<td>No. Hp</td>
													<td>
														<?php
														$pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$kasir->rekmed'")->row();
														if($pasien) { $nohp = $pasien->handphone; } else { $nohp = "-"; }
														?>
														<input type="text" class="form-control total leftJustified" name="hpno" id="hpno" value="<?= $nohp; ?>" readonly>
													</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>

												</tr>
											  </table>
										  </div>	  
										</div>	  
									
									
									<div class="row">
							  <div class="col-xs-12">
								<!--div class="wells"-->	
								<div class="form-actions">
								
								   
									<!--button id="btnsimpan_bayar" type="button" onclick="save_bayar()" class="btn blue"><i class="fa fa-save"></i> UPDATE</button-->
									<a class="btn yellow print_laporan" onclick="javascript:window.open(_urlcetak(1),'_blank');" ><i class="fa fa-print"></i> CETAK KWITANSI</a>
									<!-- <a class="btn yellow print_laporan" id="btncetak_jaminan" onclick="javascript:window.open(_urlcetak(2),'_blank');" ><i class="fa fa-print"></i> CETAK JAMINAN</a> -->
                                       									
									<a href="<?= base_url('kasir_obat')?>" class="btn btn red">TUTUP</a>
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								</div>															
							</div>
							</div>
							
									 <!--/form-->

                                </div>
								 </form>
							</div> <!--tab2-->
							
							
							<!-- tab2-->
							
						</div><!--tab-->	
						
											
													
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
  $this->load->view('template/v_periode'); 
?>

<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>

<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>


<script>


var TableEditable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }


            var oTable = $('#keuangan-keluar-list').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,

                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data : ",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "Sebelumnya",
                        "sNext": "Berikutnya"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': true,
                        'aTargets': [0]
                    },
					
                ]
                    });

                jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-keluar-list_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '','','',
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Batal</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            function deleteRow ( oTable, nRow)
            {
                if (confirm("Hapus Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = row_id.substring(4,30);
                    
                    $.ajax( {
                        dataType: 'html',
                        type: "POST",
                        url: "<?php echo base_url(); ?>penjualan_faktur/hapus/"+mydata,	
                        cache: false,
                        data: mydata,
                        success: function () {
                            oTable.fnDeleteRow( nRow );
                            oTable.fnDraw();
                        },
                        error: function() {},
                        complete: function() {}
                    } );

                }
            }

            $('#keuangan-keluar-list a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


            $('#keuangan-keluar-list a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-keluar-list a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Simpan") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

}();


var idrow  = 2;
var idrow2 = 2;


function tambah_bayar(){
     var x=document.getElementById('datatable_pembayaran').insertRow(idrow2);
     var td1=x.insertCell(0);
     var td2=x.insertCell(1);
     var td3=x.insertCell(2);
	 var td4=x.insertCell(3);
	 var td5=x.insertCell(4);
	 var td6=x.insertCell(5);
	 var td7=x.insertCell(6);
	 
	 var akun="<select name='lkode[]' id=lkode"+idrow2+" class='select2_el_kasbank form-control' ><option value=''>--- Pilih Akun ---</option></select>";
	 td1.innerHTML=akun;
	 td2.innerHTML=
	    	"<select name='bayar_tipe[]' id='bayar_tipe"+idrow2+" class='form-control select2'>"+
			"<option value='1'>DEBIT</option>"+	
			"<option value='2'>CREDIT CARD</option>"+
			"<option value='3'>TRANFER</option>"+
			"</select>";
			
	 td3.innerHTML="<input name='bayar_nokartu[]' class='form-control'>";
	 td4.innerHTML="<input name='bayar_trvalid[]'  onchange='totalline_bayar("+idrow2+")' value='0' type='text' class='form-control rightJustified'>";
	 td5.innerHTML="<input name='bayar_nilai[]'   onchange='totalline_bayar("+idrow2+")' value='0' type='text' class='form-control rightJustified'>";
	 td6.innerHTML="<input name='bayar_adm[]'   onchange='totalline_bayar("+idrow2+")' value='0' type='text' class='form-control rightJustified'>";
	 td7.innerHTML="<input name='bayar_total[]'  type='text' class='form-control rightJustified' readonly>";
	 initailizeSelect2_kasbank();
     idrow2++;
}


function showharga(str) {
  var xhttp;
  if (str == "") {
    document.getElementById("dafhargabeli").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("dafhargabeli").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_faktur/getharga/"+str, true);  
  xhttp.send();
}


function showbarangname(str, id) {   
  var xhttp;     
  var vid = id;
  $('#sat'+vid).val('');
  $('#harga'+vid).val(0);
   $.ajax({
        url : "<?php echo base_url();?>kasir_konsul/getinfotindakan/?kode="+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {						
			//$('#sat'+vid).val(data.satuan);
			$('#harga'+vid).val(formatCurrency1(data.harga));
			totalline(vid);
		}
	});	
  
}

function save_bayar()
{	        
    var nomor     = $('[name="noresep"]').val(); 	
	var total     = $('#totalnet').val(); 	
	
	if(nomor=="" || total=="0.00" || total==""){
	  swal('','Data Belum Lengkap / Belum ada pembayaran ...','');   	
	}  else {
	$.ajax({				
		url:'<?php echo site_url('kasir_obat/ajax_add_bayar')?>',				
		data:$('#frmkonsul').serialize(),				
		type:'POST',
		dataType: 'json',
		
		success:function(data){ 
		  document.getElementById("btnsimpan_bayar").disabled=true;
		  //document.getElementById("tersimpan_bayar").value="OK";
		  
		 swal({
					  title: "KWITANSI PEMBAYARAN",
					  html: "<p> No. Bukti   : <b>"+data.nomor+"</b> </p>",					  
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
						    location.reload();
		         });				
	
		},
		error:function(data){
			swal('','Data gagal disimpan ...','');	
					
		}
		});
	}	
}	



  function hapus_bayar(){
		if(idrow2>2){
			var x=document.getElementById('datatable_pembayaran').deleteRow(idrow2-1);
			idrow2--;
			total();
		}
	}
	

  function totalline_bayar(id)
  {
   
   var table = document.getElementById('datatable_pembayaran');
   var row = table.rows[id];      
   var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g,""));   
   var adm   = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g,""));   
   rpadm     = (row.cells[5].children[0].value/100)* harga;
   tot         = harga + rpadm;
   row.cells[6].children[0].value= formatCurrency1(tot);   
   total_bayar();
   
  }
  
  function total_bayar()
  {
    
   var table = document.getElementById('datatable_pembayaran');
   var rowCount = table.rows.length;

   tjumlah = 0;
   
   for(var i=1; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[6].children[0].value;
	var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
	
   	tjumlah  = tjumlah  + eval(jumlah1);	  
    
   } 
   
   document.getElementById("totalelectronicrp").value=formatCurrency1(tjumlah);
   total_net(); 
   

  }
  
  
function getdataklinik() { 
  var xhttp;      
  var str = $('[name=reg_klinik]').val();
  if(str==""){
	
  }  else  {
	initailizeSelect2_register(str);

  }	
}
  
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
          //$('#kodepoli').val(data.kodepos);
		  $('#pasien').val(data.rekmed);
		  $('#reg_namapasien').val(data.namapas);
		  $('#reg_poli').val(data.kodepos);
		  $('#pasien_bayar').val(data.rekmed);
		  $('#noreg_bayar').val(str);
          initailizeSelect2_tarif_tindakan(data.kodepos);
		  getdataresep();		  
		}
	});	    
  }	
}

  
function getuangmuka() { 
  var xhttp;      
  var str = $('[name=rekmed]').val();
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>kasir_konsul/getuangmuka/?noreg="+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		     
          $('#uangmuka').val(formatCurrency1(data.totalsemua));  	
          total_net();
		}
	});	    
  }	
}


function pembayaran( nomor, namapas, reseprp, rekmed, noreg, hp, uangr){	
	$('#noresep').val(nomor);
	$('#namapasien').val(namapas);
	$('#reseprp').val(reseprp);
	$('#rekmed').val(rekmed);
	$('#noreg').val(noreg);
	$('.nav-pills a[href="#tab2"]').tab('show'); 
    total_net();    
}

function total_net(){
	var resep = $('#reseprp').val();
	
	var vresep = Number(resep.replace(/[^0-9\.]+/g,"")); 	  
	
	var diskonpr = $('#diskonpr').val();
	var vdiskon  = (diskonpr/100)*vresep;
	$('#diskonrp').val(formatCurrency1(vdiskon));
	var totalsemua = vresep; 	  
	var diskonrp = $('#diskonrp').val();
	var uangmukarp = $('#uangmuka').val();
	var refundrp = $('#refund').val();
	var voucherrp = $('#voucherrp').val();
	
	var vdiskonrp = Number(diskonrp.replace(/[^0-9\.]+/g,"")); 	  
	var vuangmukarp = Number(uangmukarp.replace(/[^0-9\.]+/g,"")); 	  
	var vrefundrp = Number(refundrp.replace(/[^0-9\.]+/g,"")); 	  
	var vvoucherrp = Number(voucherrp.replace(/[^0-9\.]+/g,"")); 	  
	
	var totalnet = eval(totalsemua) - eval(vdiskonrp) - eval(vuangmukarp) - eval(vrefundrp) - eval(vvoucherrp);
	
	$('#totalnet').val(formatCurrency1(totalnet));

    var bayarcredit = $('#totalelectronicrp').val();	
	var bayartunai  = $('#totaltunairp').val();	
	var uangpasienrp = $('#uangpasienrp').val();	
	
	var vbayarcredit = Number(bayarcredit.replace(/[^0-9\.]+/g,"")); 	  
	var vbayartunai = Number(bayartunai.replace(/[^0-9\.]+/g,"")); 	  
	var vuangpasien = Number(uangpasienrp.replace(/[^0-9\.]+/g,"")); 	  
	
	var kembali = (eval(vbayarcredit)+eval(vbayartunai)+eval(vuangpasien))-eval(totalnet);
	$('#kembalirp').val(formatCurrency1(kembali));
	
	
	
}	
  
  
function _urlcetak(param)
{
	var baseurl = "<?php echo base_url()?>";
	var nokwitansi= $('[name="nokwitansi"]').val();				
	var noresep= $('[name="noresep"]').val();				
	if(param == 1){
    return baseurl+'kasir_obat/cetak/?kwitansi='+nokwitansi+'&resep='+noresep;
	} else {
    return baseurl+'kasir_obat/cetak_jaminan/?kwitansi='+nokwitansi+'&resep='+noresep;
	}
}

	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = '2~'+tgl1+'~'+tgl2; 
	location.href = "<?php echo base_url();?>kasir_obat/filter/"+str;
}	
	

jQuery(document).ready(function() {       
   TableEditable.init();
   
});
	

$("input[name='kembaliuang']").on('change', function() {
	$("input[name='kembaliuang']").not(this).prop('checked', false);
});
</script>


	
</body>
</html> 
