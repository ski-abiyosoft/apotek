<?php
$this->load->view('template/header');
$this->load->view('template/body');

?>


<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Kasir <small>Pembayaran Obat</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url(); ?>kasir_obat">
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
            <i class="fa fa-reorder"></i>Pembayaran Obat
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmkonsul" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabsx nav-pills">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Daftar Resep
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Pembayaran
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-toolbar">




                                        <div class="btn-group pull-right">
                                            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                    <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                                        <thead>
                                            <tr class="page-breadcrumb breadcrumb">
                                                <th class="title-white" width="25%" style="text-align: center">No. Resep
                                                </th>
                                                <th class="title-white" width="15%" style="text-align: center">Tanggal
                                                </th>
                                                <!-- <th class="title-white" width="15%" style="text-align: center">No. Reg</th> -->
                                                <th class="title-white" width="15%" style="text-align: center">Rekmed
                                                </th>
                                                <th class="title-white" width="5%" style="text-align: center">Nama
                                                    Pembeli/Pasien</th>
                                                <th class="title-white" width="10%" style="text-align: center">Total Rp
                                                </th>
                                                <th class="title-white" width="15%" style="text-align: center">No.
                                                    Kwitansi</th>
                                                <th class="title-white" width="45%" style="text-align: center">Aksi</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            <?php

                                            $nomor = 1;

                                            foreach ($resep as $row) {

                                            ?>

                                                <tr class="show1" id="row_<?php echo $row->resepno; ?>">
                                                    <td align="center"><?php echo $row->resepno; ?></td>
                                                    <td align="center"><?php echo date('d-m-Y', strtotime($row->tglresep)); ?>
                                                    </td>
                                                    <!-- <td align="center"><?php echo $row->noreg; ?></td> -->
                                                    <td align="center"><?php echo $row->rekmed; ?></td>
                                                    <td align="center"><?php echo $row->namapas; ?></td>
                                                    <td align="right"><?php echo number_format($row->poscredit, 0, ',', '.'); ?>
                                                    </td>
                                                    <td><?php echo $row->nokwitansi; ?></td>

                                                    <td style="text-align: center">
                                                        <a class="btn btn yellow" href="javascript:void(0)" title="Bayar" onclick="pembayaran('<?= $row->resepno; ?>','<?= $row->namapas; ?>','<?= number_format($row->poscredit, 0, '.', ','); ?>','<?= $row->rekmed; ?>','<?= $row->noreg; ?>','<?= $row->hp; ?>')"><i class="glyphicon glyphicon-money"></i>Bayar </a>
                                                        <a class="btn btn blue" href="<?php echo base_url() ?>penjualan_faktur/edit/<?php echo $row->resepno; ?>">Resep</a>
                                                    </td>


                                                </tr>
                                            <?php
                                                $nomor++;
                                            } ?>


                                        </tbody>


                                    </table>



                                </div>
                            </div>







                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">
                            <!--form id="frmbayar" class="form-horizontal" method="post"-->
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <div class="caption">
                                                    <i class="fa fa-reorder"></i><b> KWITANSI</b>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rekmed" id="rekmed">
                                        <input type="hidden" name="noreg" id="noreg">
                                        <div class="portlet-body form">
                                            <div class="form-body">

                                                <table class="table">
                                                    <tr>
                                                        <td>NO. KWITANSI</td>
                                                        <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="otomatis" readonly></td>

                                                    </tr>
                                                    <tr>
                                                        <td>NO. RESEP</td>
                                                        <td><input type="text" class="form-control" name="noresep" id="noresep" value="" readonly></td>

                                                    </tr>
                                                    <tr>
                                                        <td>FAKTUR PAJAK</td>
                                                        <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value=""></td>

                                                    </tr>
                                                    <tr>
                                                        <td>TGL & JAM BAYAR</td>
                                                        <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i'); ?>"></td>

                                                    </tr>
                                                    <tr>
                                                        <td>PRO</td>
                                                        <td><input type="text" class="form-control" name="namapasien" id="namapasien" value=""></td>

                                                    </tr>

                                                    <tr>
                                                        <td>TOTAL RESEP RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="reseprp" id="reseprp" value="0" readonly></td>

                                                    </tr>

                                                </table>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                </div>

                                <!-- <div class="col-md-4">
									   <div class="portlet ">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="label label-success">PROMO</span>												
											</div>
											
											
										</div>
										
										<div class="portlet-body form">									
										    <div class="form-body">
											   <table class="table">
											   <tr>
													<td>Ada Promo ?
														<input type="checkbox" name="adapromo" class="cekpromo" value=1 id="adapromo"> Ada											  
														<input type="checkbox" name="adapromo" class="cekpromo" value=0 id="tidakadapromo"> Tidak
													</td>
												</tr>
												<tr>
												  <td>PROMO 1</td>												  
												  <td>
												     <select name="promo1" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo1 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 1</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah1" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah1 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah1" id="qtyhadiah1" value="0"></td>
												  
												</tr>
												<tr>
												  <td>PROMO 2</td>												  
												  <td>
												     <select name="promo2" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo2 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 2</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah2" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah2 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah2" id="qtyhadiah2" value="0"></td>
												  
												</tr>

												<tr>
												  <td>PROMO 3</td>												  
												  <td>
												     <select name="promo[]" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo3 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 3</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah3 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah3" value="0"></td>
												  
												</tr>

												<tr>
												  <td>PROMO 4</td>												  
												  <td>
												     <select name="promo[]" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo4 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 4</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah4 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah4" value="0"></td>
												  
												</tr>

												<tr>
												  <td>PROMO 5</td>												  
												  <td>
												     <select name="promo[]" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo4 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 5</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah5 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah5" value="0"></td>
												  
												</tr>
												
											  </table>
											</div>
										 
										</div>
										</div>
									</div> -->
                            </div>
                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-md-12">
                                    <table border="0" width="70%">
                                        <tr>
                                            <td width="15%"><b>Jenis Bayar</b></td>
                                            <td width="5%" style="text-align:center;">
                                                <input type="radio" id="j_umum" checked name="j_jaminan" class="form-control" onclick="cek_umum()">
                                            </td>
                                            <td width="15%">UMUM</td>
                                            <td width="5%" style="text-align:center;">
                                                <input type="radio" id="j_jaminan" name="j_jaminan" class="form-control" onclick="cek_jaminan()">
                                            </td>
                                            <td width="15%">JAMINAN</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 20px;" id="show_jaminan">
                                <div class="col-md-12" style="border: 1px solid black;border-collapse: collapse;">
                                    <h5><b>Jaminan</b></h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_jaminan" class="col-md-5 control-label">No Kartu <span class="text-danger">*</span></label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="no_jaminan" id="no_jaminan" placeholder="No Kartu Penjamin">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Penjamin</label>
                                                <div class="col-md-7">
                                                    <input type="hidden" id="vpenjaminx">
                                                    <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin" name="vpenjamin" onchange="getjaminan1(this.value)">
                                                        <option value="">--- Pilih ---</option>
                                                        <?php $penjamin = $this->db->get("tbl_penjamin")->result();
                                                        foreach ($penjamin as $row) { ?>
                                                            <option value="<?= $row->cust_id; ?>"><?= $row->cust_nama; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Tercover Rp</label>
                                                <div class="col-md-7">
                                                    <input id="tercover_rp" name="tercover_rp" class="form-control input-medium" type="text" style="text-align: right" value="0" onchange="terkofer()" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">COB</label>
                                                <div class="col-md-7">
                                                    <input type="hidden" id="vpenjamin2x">
                                                    <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin2" name="vpenjamin2" onchange="getjaminan2(this.value)">
                                                        <option value="">--- Pilih ---</option>
                                                        <?php $penjamin = $this->db->get_where("tbl_penjamin", array("cust_id" => 'BPJS'))->result();
                                                        foreach ($penjamin as $row) {
                                                            $selected = ($row->cust_id == $data->cust_nama ? 'selected' : '');
                                                        ?>
                                                            <option <?= $selected; ?> value="<?= $row->cust_id; ?>"><?= $row->cust_nama; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Tercover Rp</label>
                                                <div class="col-md-7">
                                                    <input id="tercover_rp2" name="tercover_rp2" class="form-control input-medium" type="text" style="text-align: right" value="0" onchange="terkofer()" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                        <td><input type="text" class="form-control rightJustified" name="diskonpr" id="diskonpr" value="0" onchange="total_net()"></td>
                                                        <td><input type="text" class="form-control rightJustified" name="diskonrp" id="diskonrp" value="0" onchange="total_net()"></td>

                                                    </tr>

                                                    <!-- <tr>
												  <td>UANG MUKA</td>												  
												  <td><input type="text" class="form-control rightJustified" name="uangmuka" id="uangmuka" value="0" onchange="total_net()"></td>
												  <td> <input type="button" class="btn btn-info" value="CEK DP" onclick="getuangmuka()"></td>
												  
												</tr> -->

                                                    <tr>
                                                        <td>TERSEDIA UANG MUKA</td>
                                                        <td><input type="text" readonly class="form-control rightJustified" name="uangmuka" id="uangmuka" value="0" onchange="total_net()"></td>
                                                        <td> <input type="button" class="btn btn-info" value="CEK DP" onclick="getuangmuka()"></td>

                                                    </tr>

                                                    <tr>
                                                        <td>PAKAI UANG MUKA</td>
                                                        <td><input type="text" class="form-control rightJustified" name="uangmukapakai" id="uangmukapakai" value="0" onchange="total_net()"></td>

                                                    </tr>

                                                    <tr>
                                                        <td>NO RETUR</td>
                                                        <td><input type="text" readonly class="form-control rightJustified" name="noretur" id="noretur"></td>

                                                    </tr>

                                                    <tr>
                                                        <td>TERSEDIA RETUR</td>
                                                        <td><input type="text" readonly class="form-control rightJustified" name="retur" id="retur" value="0" onchange="total_net()"></td>
                                                        <td> <input type="button" class="btn btn-info" value="CEK RETUR" onclick="getretur()"></td>

                                                    </tr>

                                                    <tr>
                                                        <td>REFUND RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="refund" id="refund" value="0" onchange="total_net()"></td>

                                                    </tr>
                                                    <tr bgcolor="#f6f5ff">
                                                        <td>VOUCHER SOURCE</td>
                                                        <td>
                                                            <select name="vouchersource" id="vouchersource" class="form-control select2_el_vouchersource input-medium"></select>
                                                        </td>
                                                        <td class="vouchercode">&nbsp;Block nominal dibawah ini untuk
                                                            mendapatkan nilainya</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#f6f5ff" class="vouchercode">
                                                        <td>VOUCHER CODE 1</td>
                                                        <td><select type="text" class="form-control" name="vouchercode1" id="vouchercode1" value="" onchange="nominal_voucher('1')"></select></td>
                                                        <td><input type="text" class="form-control vrp rightJustified" name="voucherrp1" id="voucherrp1" placeholder="Voucher RP" value="0" onfocus="total_net()"></td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#f6f5ff" class="vouchercode">
                                                        <td>VOUCHER CODE 2</td>
                                                        <td><select type="text" class="form-control" name="vouchercode2" id="vouchercode2" value="" onchange="nominal_voucher('2')"></select></td>
                                                        <td><input type="text" class="form-control vrp rightJustified" name="voucherrp2" id="voucherrp2" placeholder="Voucher RP" value="0" onfocus="total_net()"></td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#f6f5ff" class="vouchercode">
                                                        <td>VOUCHER CODE 3</td>
                                                        <td><select type="text" class="form-control" name="vouchercode3" id="vouchercode3" value="" onchange="nominal_voucher('3')"></select></td>
                                                        <td><input type="text" class="form-control vrp rightJustified" name="voucherrp3" id="voucherrp3" placeholder="Voucher RP" value="0" onfocus="total_net()"></td>
                                                        <td>&nbsp;</td>
                                                    </tr>

                                                    <tr>
                                                        <td>TOTAL NET RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="totalnet" id="totalnet" value="0" readonly></td>

                                                    </tr>


                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="histori_form">
                                    <div class="portlet ">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-reorder"></i>
                                                <span class="label label-success">HISTORIES UANG MUKA</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="table-responsive">
                                                    <table id="datatable_dp" class="table">
                                                        <thead class="breadcrumb">
                                                            <th class="title-white" width="5%" style="text-align: left">
                                                                Pilih</th>
                                                            <th class="title-white" width="20%" style="text-align: left">No. Kwitansi</th>
                                                            <th class="title-white" width="15%" style="text-align: left">Tanggal</th>
                                                            <th class="title-white" width="15%" style="text-align: right">Uang Muka (Rp)</th>
                                                            <th class="title-white" width="15%" style="text-align: right">Dipakai (Rp)</th>
                                                            <th class="title-white" width="15%" style="text-align: right">Sisa (Rp)</th>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet box blue">
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
                                                        <table width="100%" id="datatable_pembayaran" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                            <thead class="breadcrumb">
                                                                <th class="title-white" width="20%" style="text-align: center">Bank/Provier</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Pay Type</th>
                                                                <th class="title-white" width="15%" style="text-align: center">No. Kartu/Member</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Approval Code</th>
                                                                <th class="title-white" width="15%" style="text-align: center">Nilai Transaksi</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Adm %</th>
                                                                <th class="title-white" width="20%" style="text-align: center">Grand Total</th>

                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <select name="bayar_bank[]" id="bayar_bank1" class="form-control select2_el_kasbankedc input-large">
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="bayar_tipe[]" id="bayar_tipe1" class="form-control">
                                                                            <option value="1">DEBIT</option>
                                                                            <option value="2">CREDIT CARD</option>
                                                                            <option value="3">TRANFER</option>
                                                                            <option value="4">ONLINE</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="bayar_nokartu[]" class="form-control" type='text' maxlength='16'></td>
                                                                    <td><input name="bayar_trvalid[]" onchange="totalline_bayar(1)" value="0" type="text" class="form-control rightJustified" maxlength="6"></td>
                                                                    <td><input name="bayar_nilai[]" id="bayar_nilai1" onchange="totalline_bayar(1)" value="0" type="text" class="form-control rightJustified "></td>
                                                                    <td><input name="bayar_adm[]" onchange="totalline_bayar(1)" value="0" type="text" readonly="" class="form-control rightJustified "></td>
                                                                    <td><input name="bayar_total[]" type="text" class="form-control rightJustified" readonly></td>

                                                                </tr>

                                                            </tbody>

                                                        </table>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" onclick="tambah_bayar()" class="btn green"><i class="fa fa-plus"></i></button>
                                                        <button type="button" onclick="hapus_bayar()" class="btn red"><i class="fa fa-trash-o"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" border="0" width="100%">

                                        <tr>
                                            <td>TOTAL ELECTRONIC RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="totalelectronicrp" id="totalelectronicrp" value="0" readonly onchange="total_net()"></td>

                                        </tr>

                                        <tr>
                                            <td>SELISIH</td>
                                            <td><input type="text" class="form-control rightJustified" name="selisihrp" id="selisihrp" value="0" onchange="total_net()"></td>

                                        </tr>

                                        <tr>
                                            <td>TOTAL TUNAI RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="totaltunairp" id="totaltunairp" value="0" onchange="total_net()"></td>

                                        </tr>
                                        <!-- <tr>
												  <td>UANG PASIEN RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="uangpasienrp" id="uangpasienrp" value="0" onchange="total_net()" ></td>
												  
												</tr> -->
                                        <tr>
                                            <td>KEMBALI RP</td>
                                            <td><input type="text" class="form-control total rightJustified" name="kembalirp" id="kembalirp" value="0" readonly></td>
                                            <!-- <td style="border-top:none;"><span id="pertanyaan">Auto ke uang muka</span></td> -->
                                            <td style="border-top:none;" width="10%"><input type="hidden" id="uangmukakembaliya" name="kembaliuang" checked value="1"><span id="textya"></span></td>
                                            <td style="border-top:none;" width="10%"><input type="checkbox" checked id="uangmukakembalitidak" name="kembaliuang" value="0"><span id="texttidak">Kembali ke
                                                    pasien</span></td>

                                        </tr>
                                        <tr>
                                            <td>SUDAH TERIMA DARI</td>
                                            <td><input type="text" class="form-control total rightJustified" name="terimadari" id="terimadari" value=""></td>

                                        </tr>

                                        <tr>
                                            <td>No. Hp</td>
                                            <td>
                                                <input type="text" class="form-control total leftJustified" name="hpno" id="hpno" value="" readonly>

                                            </td>
                                            <td style="border-top:none;">

                                                <input type="checkbox" id="reg_cekhp" name="reg_cekhp" value="1" class="form-control">
                                            </td>

                                        </tr>
                                    </table>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <!--div class="wells"-->
                                    <div class="form-actions">


                                        <button id="btnsimpan_bayar" type="button" onclick="save_bayar()" class="btn blue"><i class="fa fa-save"></i> UPDATE</button>
                                        <button id="btncetak_bayar" type="button" onclick="javascript:window.open(_urlcetak(1),'_blank');" class="btn yellow"><i class="fa fa-save"></i> CETAK KWITANSI</button>

                                        <button id="btncetak_jaminan" type="button" onclick="javascript:window.open(_urlcetak(2),'_blank');" class="btn yellow"><i class="fa fa-save"></i> CETAK JAMINAN</button>

                                        <a href="<?= base_url('kasir_obat') ?>" class="btn btn red">TUTUP</a>
                                        <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan...
                                            </span> <span id="success" style="display:none; color:#0C0">Data sudah
                                                disimpan...</span></h4>
                                    </div>
                                </div>
                            </div>

                            <!--/form-->

                        </div>
        </form>
    </div>
    <!--tab2-->


    <!-- tab2-->

</div>
<!--tab-->



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

<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js') ?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js') ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>


<script>
    
    // $("#j_umum").attr("disabled", true);
    // $("#j_jaminan").attr("disabled", true);
    function getjaminan1(param) {
        $.ajax({
            url: "<?= site_url('Kasir_obat/getjaminan1/') ?>" + param,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                $("#vpenjaminx").val(data.cust_nama);
            }
        });
    }

    function getjaminan2(param) {
        $.ajax({
            url: "<?= site_url('Kasir_obat/getjaminan2/') ?>" + param,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                $("#vpenjamin2x").val(data.cust_nama);
            }
        });
    }
    $("#btncetak_jaminan").hide();

    function cek_umum() {
        if (document.getElementById('j_umum').checked == true) {
            $("#show_jaminan").hide();
            $("#tercover_rp").val(0);
            $("#tercover_rp2").val(0);
            total_net();
            $("#btncetak_jaminan").hide();
        }
    }

    function cek_jaminan() {
        if (document.getElementById('j_jaminan').checked == true) {
            $("#show_jaminan").show();
            $("#btncetak_jaminan").show();
        }
    }

    function terkofer() {
        var tercover = $("#tercover_rp").val();
        var tercover2 = $("#tercover_rp2").val();
        var totalrpx = $("#reseprp").val();
        var totalrp = Number(parseInt(totalrpx.replaceAll(',', '')));
        var trp = Number(parseInt(tercover.replaceAll(',', '')));
        var trp2 = Number(parseInt(tercover2.replaceAll(',', '')));
        total_cover = trp + trp2;
        hasil = totalrp - total_cover;
        $("#tercover_rp").val(formatCurrency1(trp));
        $("#tercover_rp2").val(formatCurrency1(trp2));
        $("#totalnet").val(formatCurrency1(hasil));
        // console.log(hasil)
        total_net();
    }

    $('#histori_form').hide();

    $(document).ready(function() {
        $("#show_jaminan").hide();
    });

    $(window).on("load", function() {
        $(".vouchercode").hide();
    });

    function nominal_voucher(param) {
        var voucher_nominal;
        var selected_voucher = $("[name='vouchersource']").val();
        var novoucher = $("#vouchercode" + param).val();
        var vrpcount = $("#vrp").length;
        $.ajax({
            url: "/kasir_obat/check_voucher/",
            type: "POST",
            dataType: "JSON",
            data: {
                voucher: novoucher,
                cust: selected_voucher
            },
            success: function(data) {
                if (data.status == 0) {
                    $("#voucherrp" + param).val("Tidak Ditemukan");
                } else {
                    final_vnominal = data.nominal.split(".").join(",");
                    $("#voucherrp" + param).val(final_vnominal + ".00");
                }
            }
        });
    }

    $("#vouchersource").on("select2:select", function(e) {
        e.preventDefault();
        var sourceval = $(this).val();
        $.ajax({
            url: "/kasir_obat/check_group_voucher/" + sourceval,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 0) {
                    $(".vouchercode").hide();
                    swal({
                        title: "Kesalahan",
                        html: "Voucher Tidak tersedia Untuk Group Ini",
                        type: "error",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        $("#vouchersource").empty().trigger('change');
                    });
                } else {
                    $(".vouchercode").show();
                    $("#vouchercode1").html("<option>--- Pilih Voucher Atau Ketik ---</option>");
                    $("#vouchercode2").html("<option>--- Pilih Voucher Atau Ketik ---</option>");
                    $("#vouchercode3").html("<option>--- Pilih Voucher Atau Ketik  ---</option>");
                    $.each(data, function(key, value) {
                        $("#vouchercode1").append("<option value='" + value.novoucher + "'>" +
                            value.novoucher + "</option>");
                        $("#vouchercode2").append("<option value='" + value.novoucher + "'>" +
                            value.novoucher + "</option>");
                        $("#vouchercode3").append("<option value='" + value.novoucher + "'>" +
                            value.novoucher + "</option>");
                    });
                }
            }
        });
    });

    var TableEditable = function() {

        return {

            //main function to initiate the module
            init: function() {
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

                jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass(
                    "form-control input-medium input-inline"); // modify table search input
                jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass(
                    "form-control input-small"); // modify table per page dropdown
                jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
                    showSearchInput: false //hide search box with special css class
                }); // initialize select2 dropdown

                var nEditing = null;

                $('#keuangan-keluar-list_new').click(function(e) {
                    e.preventDefault();
                    var aiNew = oTable.fnAddData(['', '', '', '', '',
                        '<a class="edit" href="">Edit</a>',
                        '<a class="cancel" data-mode="new" href="">Batal</a>'
                    ]);
                    var nRow = oTable.fnGetNodes(aiNew[0]);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                });

                function deleteRow(oTable, nRow) {
                    if (confirm("Hapus Data Ini?")) {

                        var row_id = nRow.id;
                        var mydata = row_id.substring(4, 30);

                        $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "<?php echo base_url(); ?>penjualan_faktur/hapus/" + mydata,
                            cache: false,
                            data: mydata,
                            success: function() {
                                oTable.fnDeleteRow(nRow);
                                oTable.fnDraw();
                            },
                            error: function() {},
                            complete: function() {}
                        });

                    }
                }

                $('#keuangan-keluar-list a.delete').live('click', function(e) {
                    e.preventDefault();

                    var nRow = $(this).parents('tr')[0];
                    if (nRow) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


                $('#keuangan-keluar-list a.cancel').live('click', function(e) {
                    e.preventDefault();
                    if ($(this).attr("data-mode") == "new") {
                        var nRow = $(this).parents('tr')[0];
                        oTable.fnDeleteRow(nRow);
                    } else {
                        restoreRow(oTable, nEditing);
                        nEditing = null;
                    }
                });

                $('#keuangan-keluar-list a.edit').live('click', function(e) {
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


    var idrow = 2;
    var idrow2 = 2;


    function tambah_bayar() {
        var x = document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);

        var akun = "<select name='bayar_bank[]' id=bayar_bank" + idrow2 +
            " class='select2_el_kasbankedc form-control' ><option value=''>--- Pilih Akun ---</option></select>";
        td1.innerHTML = akun;
        td2.innerHTML =
            "<select name='bayar_tipe[]' id='bayar_tipe" + idrow2 + "' class='form-control'>" +
            "<option value='1'>DEBIT</option>" +
            "<option value='2'>CREDIT CARD</option>" +
            "<option value='3'>TRANFER</option>" +
            "<option value='4'>ONLINE</option>" +
            "</select>";
        td3.innerHTML = "<input name='bayar_nokartu[]' class='form-control' type='text' maxlength='16'>";
        td4.innerHTML = "<input name='bayar_trvalid[]' onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified' maxlength='6'>";
        td5.innerHTML = "<input id='bayar_nilai" + idrow2 + "' name='bayar_nilai[]' onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified'>";
        td6.innerHTML = "<input name='bayar_adm[]' onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified readonly'>";
        td7.innerHTML = "<input name='bayar_total[]' type='text' class='form-control rightJustified' readonly>";
        initailizeSelect2_kasbankedc();
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
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_faktur/getharga/" + str, true);
        xhttp.send();
    }


    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $('#sat' + vid).val('');
        $('#harga' + vid).val(0);
        $.ajax({
            url: "<?php echo base_url(); ?>kasir_konsul/getinfotindakan/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                //$('#sat'+vid).val(data.satuan);
                $('#harga' + vid).val(formatCurrency1(data.harga));
                totalline(vid);
            }
        });

    }

    function save_bayar() {
        var reseprp = $('#reseprp').val();
        var voucherrp1 = $('#voucherrp1').val();
        var voucherrp2 = $('#voucherrp2').val();
        var voucherrp3 = $('#voucherrp3').val();
        if (voucherrp1 != '' | voucherrp1 != null) {
            if (voucherrp1 == 0) {
                var vc1 = '';
            } else {
                var vc1 = '<br>VC1 : ' + voucherrp1;
            }
        } else {
            var vc1 = '';
        }
        if (voucherrp2 != '' | voucherrp2 != null) {
            if (voucherrp2 == 0) {
                var vc2 = '';
            } else {
                var vc2 = '<br>VC2 : ' + voucherrp2;
            }
        } else {
            var vc2 = '';
        }
        if (voucherrp3 != '' | voucherrp3 != null) {
            if (voucherrp3 == 0) {
                var vc3 = '';
            } else {
                var vc3 = '<br>VC3 : ' + voucherrp3;
            }
        } else {
            var vc3 = '';
        }
        var nomor = $('[name="noresep"]').val();
        var total = $('#totalnet').val();
        var totalrpx = $("#reseprp").val();
        var totalrp = Number(parseInt(totalrpx.replaceAll(',', '')));

        var abc;
        var table_bank = document.getElementById('datatable_pembayaran');
        var rowCount = table_bank.rows.length;
        for (var i = 1; i < rowCount; i++) {
            var row = table_bank.rows[i];
            tipe = row.cells[1].children[0].value;
            bayarnyax = row.cells[4].children[0].value;
            bayarnya = bayarnyax.replace(/[^0-9\.]+/g, "");
            if (tipe == 1) {
                $1 = 'DC';
            } else if (tipe == 2) {
                $1 = 'CC';
            } else if (tipe == 3) {
                $1 = 'TR';
            } else {
                $1 = 'OL';
            }
            if (bayarnya == '' || bayarnya == null || bayarnya == 0) {
                abc = "<br>" + $1 + " : .....";
            } else {
                abc = "<br>" + $1 + " : <b>" + bayarnyax + "</b>";
            }
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        }

        // cek pakai uang muka apakah lebih dari tersedia uang muka
        var uangmukapakaix = $('#uangmukapakai').val();
        var uangmukapakai = Number(parseInt(uangmukapakaix.replaceAll(',', '')));
        var uangmuka = $('#uangmuka').val();
        var nuangmuka = Number(uangmuka.replace(/[^0-9\.]+/g, ""));

        if (document.getElementById("j_umum").checked == true && document.getElementById("j_jaminan").checked == false) {
            jaminan = 0;
        } else if (document.getElementById("j_jaminan").checked == true && document.getElementById("j_umum").checked == false) {
            jaminan = 1;
        }
        var vpenjaminx = $("#vpenjaminx").val();
        var vpenjamin2x = $("#vpenjamin2x").val();
        var tercover = $("#tercover_rp").val();
        var tercoverx = Number(parseInt(tercover.replaceAll(',', '')));
        var tercover2 = $("#tercover_rp2").val();
        var trp = Number(parseInt(tercover.replaceAll(',', '')));
        var trp2 = Number(parseInt(tercover2.replaceAll(',', '')));
        total_cover = trp + trp2;
        hasil = totalrp - total_cover;

        var totalnetnyax = $("#totalnet").val();
        var totalnetnya = Number(parseInt(totalnetnyax.replaceAll(',', '')));

        var terimadari = $('#terimadari').val();
        var nohp = $('#hpno').val();
        var cekhp = $('#reg_cekhp').is(':checked');

        if (nohp == '') {
            swal({
                title: "No Hp Masih Kosong",
                html: "<p> No.Hp : <b>" + nohp + "</b> </p>" +
                    "CEK LAGI",
                type: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        if (!cekhp) {
            swal({
                title: "Cek Kembali Nomor Hp Pasien",
                html: "<p> No.Hp : <b>" + nohp + "</b> </p>" +
                    "Jika Sudah Benar Lakukan <br>CHECKLIST <br>di Samping Kolom No Hp",
                type: "info",
                confirmButtonText: "OK"
            });
            return;
        }

        if (uangmukapakaix > 0) {
            um = "<br>DP : " + formatCurrency1(uangmukapakaix);
        } else {
            um = "";
        }


        if (nomor == "" || terimadari == "" || uangmukapakai > nuangmuka) {
            if (uangmukapakai > nuangmuka) {
                swal({
                    title: "Beban DP",
                    html: "Tidak Sesuai dengan Uang Muka .!!!",
                    type: "error",
                    confirmButtonText: "OK"
                });
            } else if (terimadari == "") {
                swal({
                    title: "Terima dari ",
                    html: "Tidak Boleh Kosong .!!!",
                    type: "error",
                    confirmButtonText: "OK"
                });
            } else {
                swal('', 'Data Belum Lengkap / Belum ada pembayaran ...', '');
            }

        } else {
            $.ajax({
                url: '<?php echo site_url('kasir_obat/ajax_add_bayar?jaminan=') ?>' + jaminan + '&hasil=' + hasil,
                data: $('#frmkonsul').serialize(),
                type: 'POST',
                dataType: 'json',

                success: function(data) {
                    document.getElementById("btnsimpan_bayar").disabled = true;
                    //document.getElementById("tersimpan_bayar").value="OK";

                    if (jaminan == 1) {
                        alertjx = "<br>" + vpenjaminx + " : <b>" + tercover + "</b>";
                        if (trp2 > 0) {
                            cobnya = "<br>" + vpenjamin2x + " : <b>" + tercover2 + "</b>";
                        } else {
                            cobnya = "";
                        }
                        alertj = alertjx + cobnya;
                    } else {
                        alertj = "";
                    }
                    // var totalnx = Number(data.total);

                    swal({
                        title: "KWITANSI PEMBAYARAN",
                        html: "<p>No. Bukti : <b>" + data.nomor + "</b></p><br> Biaya Terbentuk Rp : <b>" + reseprp + "</b>" + um + "<br>Resep Racik <b>" + formatCurrency1(totalnetnya) + "</b>" + abc + vc1 + vc2 + vc3 + alertj,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        //location.reload();
                        document.getElementById("btncetak_bayar").disabled = false;
                        document.getElementById("btncetak_jaminan").disabled = false;
                        $('#nokwitansi').val(data.nomor);
                    });

                },
                error: function(data) {
                    swal('', 'Data gagal disimpan ...', '');

                }
            });
        }
    }



    function hapus_bayar() {
        if (idrow2 > 2) {
            var x = document.getElementById('datatable_pembayaran').deleteRow(idrow2 - 1);
            idrow2--;
            total();
        }
    }


    function totalline_bayar(id) {

        var bayar_nilai = $('#bayar_nilai' + id).val();
        // var bayar_nilaix = Number(parseInt(bayar_nilai.replaceAll(',','')));
        $("#bayar_nilai" + id).val(formatCurrency1(bayar_nilai));
        // console.log(bayar_nilai)
        var table = document.getElementById('datatable_pembayaran');
        var row = table.rows[id];
        var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
        var adm = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
        rpadm = (row.cells[5].children[0].value / 100) * harga;
        tot = harga + rpadm;
        row.cells[6].children[0].value = formatCurrency1(tot);
        total_bayar();

    }

    function total_bayar() {

        var table = document.getElementById('datatable_pembayaran');
        var rowCount = table.rows.length;

        tjumlah = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[6].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1);

        }

        document.getElementById("totalelectronicrp").value = formatCurrency1(tjumlah);
        total_net();


        var table = document.getElementById('datatable_dp');
        var rowCount = table.rows.length;

        tjumlah = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[5].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));

            if (row.cells[0].children[0].checked == true) {
                tjumlah = tjumlah + eval(jumlah1);
            }

        }
        $('#uangmuka').val(formatCurrency1(tjumlah));
        total_net(); //saya nambah ini


    }


    function getdataklinik() {
        var xhttp;
        var str = $('[name=reg_klinik]').val();
        if (str == "") {

        } else {
            initailizeSelect2_register(str);

        }
    }

    function getdataregistrasi() {
        var xhttp;
        var str = $('[name=noreg]').val();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>kasir_konsul/getdataregistrasi/?noreg=" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
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

    function getretur() {
        var xhttp;
        // var str = $('[name="rekmed"]').val();
        var str = $('[name=noresep]').val();
        // console.log(str);
        if (str == "") {

        } else {
            $.ajax({
                // url: "<?php echo base_url(); ?>kasir_obat/getdataretur/?noreg=" + str.trim(),
                url: "<?php echo base_url(); ?>kasir_obat/getdataretur1/?noresep=" + str,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    $('#retur').val(formatCurrency1(data.totalrp));
                    $('#noretur').val(data.returno);
                    total_net();
                    total_bayar();
                }
            });
            // total_net();
            // total_bayar();
        }
    }

    //   saya ganti
    // function getuangmuka() { 
    //   var xhttp;      
    //   var str = $('[name=rekmed]').val();
    //   if(str==""){

    //   }  else  {
    // 	$.ajax({
    //         url : "<?php echo base_url(); ?>kasir_konsul/getuangmuka/?noreg="+str,
    //         type: "GET",
    //         dataType: "JSON",

    //         success: function(data)
    //         {		     
    //           $('#uangmuka').val(formatCurrency1(data.totalsemua));  	
    //           total_net();
    // 		}
    // 	});	    
    //   }	
    // }

    function getuangmuka() {
        $('#histori_form').show('200');
        var xhttp;
        var str = $('[name=rekmed]').val();
        $('#datatable_dp tbody').empty();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>kasir_konsul/getdatadp/?rekmed=" + str.trim(),
                type: "GET",
                //dataType: "JSON",		
                success: function(data) {
                    $('#datatable_dp tbody').append(data);
                    total_bayar();
                    total_net();
                }
            });
        }
    }



    function pembayaran(nomor, namapas, reseprp, rekmed, noreg, hp) {
        $('#noresep').val(nomor);
        $('#namapasien').val(namapas);
        $('#reseprp').val(reseprp);
        $('#rekmed').val(rekmed);
        $('#noreg').val(noreg);
        $('#hpno').val(hp);
        $('.nav-pills a[href="#tab2"]').tab('show');
        total_net();
        $.ajax({
            url: "<?= site_url('Kasir_obat/get_pas/') ?>" + rekmed,
            dataType: "JSON",
            type: "POST",
            success: function(data) {
                if (data.nocard != '' || data.nocard != null || data.nocard != 'null') {
                    $("#no_jaminan").val(data.nocard);
                } else {
                    $("#no_jaminan").val('');
                }
            }
        });
    }

    function total_net() {
        var retur = $('#retur').val();
        var vretur = Number(retur.replace(/[^0-9\.]+/g, ""));

        var selisih = $('#selisihrp').val();

        var resep = $('#reseprp').val();

        var vresep = Number(resep.replace(/[^0-9\.]+/g, ""));

        var diskonpr = $('#diskonpr').val();
        var vdiskon = (diskonpr / 100) * vresep;
        $('#diskonrp').val(formatCurrency1(vdiskon));
        var totalsemua = vresep;
        var diskonrp = $('#diskonrp').val();
        var uangmukarp = $('#uangmuka').val();
        var uangmukapakai = $('#uangmukapakai').val();
        var refundrp = $('#refund').val();
        var voucherrp1 = $('#voucherrp1').val();
        var voucherrp2 = $('#voucherrp2').val();
        var voucherrp3 = $('#voucherrp3').val();

        var vdiskonrp = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
        var vuangmukarp = Number(uangmukarp.replace(/[^0-9\.]+/g, ""));
        var vuangmukapakai = Number(uangmukapakai.replace(/[^0-9\.]+/g, ""));
        var vrefundrp = Number(refundrp.replace(/[^0-9\.]+/g, ""));
        var vvoucherrp1 = Number(voucherrp1.replace(/[^0-9\.]+/g, ""));
        var vvoucherrp2 = Number(voucherrp2.replace(/[^0-9\.]+/g, ""));
        var vvoucherrp3 = Number(voucherrp3.replace(/[^0-9\.]+/g, ""));

        var tercover = $("#tercover_rp").val();
        var tercover2 = $("#tercover_rp2").val();
        var totalrpx = $("#reseprp").val();
        var totalrp = Number(parseInt(totalrpx.replaceAll(',', '')));
        var trp = Number(parseInt(tercover.replaceAll(',', '')));
        var trp2 = Number(parseInt(tercover2.replaceAll(',', '')));
        total_cover = trp + trp2;
        hasil = totalrp - total_cover;

        // var totalnet = eval(totalsemua) - eval(vdiskonrp) - eval(vuangmukapakai) - eval(vrefundrp) - eval(vretur) - selisih - eval(vvoucherrp1) - eval(vvoucherrp2) - eval(vvoucherrp3);
        var totalnet = eval(hasil) - eval(vdiskonrp) - eval(vuangmukapakai) - eval(vrefundrp) - eval(vretur) - selisih - eval(vvoucherrp1) - eval(vvoucherrp2) - eval(vvoucherrp3);

        $('#totalnet').val(formatCurrency1(totalnet));

        var bayarcredit = $('#totalelectronicrp').val();
        var bayartunai = $('#totaltunairp').val();
        $('#totaltunairp').val(formatCurrency1(bayartunai));
        var uangpasienrp = $('#uangpasienrp').val();

        var vbayarcredit = Number(bayarcredit.replace(/[^0-9\.]+/g, ""));
        var vbayartunai = Number(bayartunai.replace(/[^0-9\.]+/g, ""));
        // var vuangpasien = Number(uangpasienrp.replace(/[^0-9\.]+/g,"")); 	  

        var kembali = (eval(vbayarcredit) + eval(vbayartunai)) - eval(totalnet);
        $('#kembalirp').val(formatCurrency1(kembali));
        $('#uangmukapakai').val(formatCurrency1(uangmukapakai));

        if (vuangmukarp > 0 || vbayartunai != 0 || vbayarcredit != 0 || vretur != 0) {
            // document.getElementById('pertanyaan').style.visibility = 'visible';
            document.getElementById('textya').style.visibility = 'visible';
            document.getElementById('uangmukakembaliya').style.visibility = 'visible';

            if (vbayartunai != 0 || vbayarcredit != 0) {
                document.getElementById('uangmukakembalitidak').style.visibility = 'visible';
                document.getElementById('texttidak').style.visibility = 'visible';
            } else {
                document.getElementById('uangmukakembalitidak').style.visibility = 'hidden';
                document.getElementById('texttidak').style.visibility = 'hidden';
            }

        } else {
            // document.getElementById('pertanyaan').style.visibility = 'hidden';
            document.getElementById('textya').style.visibility = 'hidden';
            document.getElementById('uangmukakembaliya').style.visibility = 'hidden';
            document.getElementById('uangmukakembalitidak').style.visibility = 'hidden';
            document.getElementById('texttidak').style.visibility = 'hidden';
        }



    }

    function _urlcetak(param) {
        var baseurl = "<?php echo base_url() ?>";
        var nokwitansi = $('[name="nokwitansi"]').val();
        var noresep = $('[name="noresep"]').val();
        if (param == 1) {
            return baseurl + 'kasir_obat/cetak/?kwitansi=' + nokwitansi + '&resep=' + noresep;
        } else {
            return baseurl + 'kasir_obat/cetak_jaminan/?kwitansi=' + nokwitansi + '&resep=' + noresep;
        }
    }

    function filterdata() {
        var tgl1 = document.getElementById("tanggal1").value;
        var tgl2 = document.getElementById("tanggal2").value;
        var str = '2~' + tgl1 + '~' + tgl2;
        location.href = "<?php echo base_url(); ?>kasir_obat/filter/" + str;
    }


    jQuery(document).ready(function() {
        TableEditable.init();
        document.getElementById("btncetak_bayar").disabled = true;
        document.getElementById("btncetak_jaminan").disabled = true;

        // tambahan dari saya
        // document.getElementById('pertanyaan').style.visibility = 'hidden';
        document.getElementById('uangmukakembaliya').style.visibility = 'hidden';
        document.getElementById('textya').style.visibility = 'hidden';

        document.getElementById('uangmukakembalitidak').style.visibility = 'hidden';
        document.getElementById('texttidak').style.visibility = 'hidden';
    });

    $('.cekpromo').on('change', function() {
        $('.cekpromo').not(this).prop('checked', false);
    });

    $("input[name='kembaliuang']").on('change', function() {
        $("input[name='kembaliuang']").not(this).prop('checked', false);
    });
</script>



</body>

</html>