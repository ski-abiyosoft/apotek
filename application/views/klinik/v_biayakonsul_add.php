<?php
$this->load->view('template/header');
$this->load->view('template/body');

?>
<style>
  .table-sc {
    max-height: 100px;
    overflow: auto;
    display: inline-block;
  }
</style>
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span>
      -
      <span class="title-web"> Kasir <small>Pembayaran Jasa Konsultasi dan Resep</small></span>

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
        <a class="title-white" href="<?php echo base_url(); ?>kasir_konsul">
          Daftar Pembayaran
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Entri Tindakan & Pembayaran
        </a>
      </li>
    </ul>
  </div>
</div>
<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i><b>Pembayaran Konsultasi dan Tindakan</b>
    </div>


  </div>

  <div class="portlet-body form">
    <form id="frmkonsul" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <ul class="nav nav-tabsx nav-pills">
            <li class="active">
              <a href="#tab1" data-toggle="tab">
                Entry Tindakan
              </a>
            </li>
            <li class="">
              <a href="#tab2" data-toggle="tab">
                Pembayaran
              </a>
            </li>
            <!--li class="">
								<a href="#tab3" data-toggle="tab">
                                   Farmasi
								</a>
							</li-->
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-6">
                  <div class="note note-info">
                    <div class="form-group">
                      <label class="col-md-3 control-label">No. Registrasi <font color="red">*</font></label>
                      <div class="col-md-9">
                        <select class="form-control select2_el_registrasi" id="noreg" name="noreg" onchange="getdataregistrasi()">
                        </select>
                      </div>
                    </div>
                    <!-- <div class="form-group">
                      <label class="col-md-3 control-label">Klinik <font color="red">*</font></label>
                      <div class="col-md-9">
                        <select class="form-control select2_el_poli" id="reg_klinik" name="reg_klinik" onchange="getdataklinik()">
                          <option value='KULIT' selected="">UNIT KULIT</option>
                        </select>
                      </div>
                    </div> -->
                    <input type="hidden" id="kodokterpilih">
                    <input type="hidden" id="nadokterpilih">

                    
                    <div class="form-group">
                      <label class="col-md-3 control-label">Klinik <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="text" id="reg_klinik" name="reg_klinik" readonly class="form-control">
                      </div>
                    </div>
                    <!-- <div class="form-group">
                      <label class="col-md-3 control-label">No. Registrasi <font color="red">*</font></label>
                      <div class="col-md-9">
                        <select class="form-control select2_el_registrasi" id="noreg" name="noreg" onchange="getdataregistrasi()">
                        </select>
                      </div>
                    </div> -->

                    <div class="form-group">
                      <label class="col-md-3 control-label">Tanggal & Jam <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="datetime-local" value="<?= date('Y-m-d\TH:m'); ?>" class="form-control" id="reg_tanggal" name="reg_tanggal" readonly>

                      </div>
                    </div>

                  </div>
                </div>
                <div class="col-md-6">
                  <div class="note note-success">
                    <div class="form-group">
                      <label class="col-md-3 control-label">No. MR <font color="red"></font></label>
                      <div class="col-md-9">
                        <input type="text" value="" class="form-control" id="pasien" name="pasien" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-3 control-label">Nama Pasien <font color="red"></font></label>
                      <div class="col-md-9">
                        <input type="text" value="" class="form-control" id="reg_namapasien" name="reg_namapasien" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-3 control-label">Poli/Unit <font color="red"></font></label>
                      <div class="col-md-9">
                        <input type="text" value="" class="form-control" id="reg_poli" name="reg_poli" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-3 control-label">No.Hp <font color="red"></font></label>

                      <div class="col-md-7">
                        <input type="text" value="" class="form-control" id="reg_hp" name="reg_hp" readonly>
                      </div>

                      <div class="col-md-2">
                        <input type="checkbox" id="reg_cekhp" name="reg_cekhp" value="1" class="form-control">
                      </div>

                    </div>

                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <span class="label label-info"><b>KONSULTASI DAN TINDAKAN</b></span>
                  <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                    <thead class="page-breadcrumb breadcrumb">
                      <th class="title-white" width="5%" style="text-align: center">Delete</th>
                      <th class="title-white" width="20%" style="text-align: center">Tindakan</th>
                      <th class="title-white" width="15%" style="text-align: center">Dokter</th>
                      <th class="title-white" width="15%" style="text-align: center">Perawat</th>
                      <th class="title-white" width="15%" style="text-align: center">Total Jasa (Rp)</th>
                      <th class="title-white" width="5%" style="text-align: center">Diskon %</th>
                      <th class="title-white" width="10%" style="text-align: center">Diskon (Rp)</th>
                      <th class="title-white" width="20%" style="text-align: center">Total Net (Rp)</th>

                    </thead>

                    <tbody>
                      <tr>
                        <td>
                          <button type='button' onclick=hapusBarisIni(1) class='btn red'><i class='fa fa-trash-o'>
                        </td>
                        <td width="20%">
                          <select name="kode[]" id="kode1" class="select2_el_tarif_tindakan form-control input-largex" onchange="showbarangname(this.value, 1)">
                          </select>
                        </td>
                        <td width="10%">
                          <select name="dokter[]" id="dokter1" class="select2_el_dokter form-control input-largex">
                          </select>
                        </td>
                        <td width="10%">
                          <select name="perawat[]" id="perawat1" class="select2_el_perawat form-control input-largex">
                          </select>
                        </td>

                        <td width="10%">
                          <input name="harga[]" onchange="totalline(1)" value="0" id="harga1" type="text" class="form-control rightJustified" readonly>
                          <input name="feemedis[]" value="0" id="feemedis1" type="hidden" class="form-control rightJustified" readonly>
                          <input name="bhp[]" value="0" id="bhp1" type="hidden" class="form-control rightJustified" readonly>
                          <input name="tarifrs[]" value="0" id="tarifrs1" type="hidden" class="form-control rightJustified" readonly>
                          <input name="tarifdr[]" value="0" id="tarifdr1" type="hidden" class="form-control rightJustified" readonly>
                        </td>
                        <td width="7%"><input name="disc1[]" onchange="totalline(1)" value="0" id="disc11" type="text" class="form-control rightJustified "></td>
                        <td width="10%"><input name="disc2[]" onchange="totalline(1)" value="0" id="disc21" type="text" class="form-control rightJustified "></td>
                        <td width="20%"><input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>

                      </tr>

                    </tbody>

                  </table>

                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i></button>
                  <!-- <button type="button" onclick="hapus()"  class="btn red"><i class="fa fa-trash-o"></i></button>													 -->
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4 invoice-block">
                  </br>
                  <span class="label label-info"><b>ADM PASIEN BARU</b></span>
                  <table id="adm" class="table  table-condensed table-scrollable">
                    <thead class="breadcrumb">
                      <th class="title-white" width="25%" style="text-align: center">Pasien</th>
                      <th class="title-white" width="15%" style="text-align: center">Total (Rp)</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input align="left" type="text" class="form-control" value="ADM PASIEN BARU" readonly="">
                        </td>
                        <td><input type="text" class="form-control rightJustified" name="admrp1" id="admrp1" value="0" onchange="total_net()" readonly=""></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4 invoice-block">
                  </br>
                  <span class="label label-info"><b>RESEP YANG SDH DITEBUS</b></span>
                  <table id="datatable_resep" class="table  table-condensed table-scrollable">
                    <thead class="breadcrumb">
                      <th class="title-white" width="25%" style="text-align: center">Nomor Resep</th>
                      <th class="title-white" width="15%" style="text-align: center">Nilai Resep (Rp)</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="col-xs-4 invoice-block pull-right">
                  <div class="well">
                    <table>
                      <tr>
                        <td width="40%"><strong>TOTAL ADM</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vsubadm"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL KONSUL</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL /R</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vtotalresep"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL DISK /R</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vtotalresepdisc"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL DISK TIN/KON</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vtotaldiskon"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>GRAND TOTAL</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vtotal2"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                      </tr>
                      <input type="hidden" id="tersimpan">
                    </table>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <!--div class="wells"-->
                  <div class="form-actions">


                    <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                      <b>PROSES</b></button>
                    <button id="btnbayar" type="button" onclick="bayar()" class="btn yellow"><i class="fa fa-money"></i>
                      <b>BAYAR</b></button>

                    <div class="btn-group">
                      <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>TAMBAH</b></button>
                    </div>
                    <a href="<?= base_url('kasir_konsul') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI
                      </b></a>
                    <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                  </div>
                </div>
              </div>

            </div>
            <!-- tab1-->

            <div class="tab-pane" id="tab2">
              <!-- harimas -->
              <div class="col-md-12">
                <div class="portlet box blue">

                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-reorder"></i><b>KWITANSI</b>
                    </div>
                  </div>
                  <div class="portlet-body">
                    <table class="table">
                      <div class="col-md-12">
                        <div class="form-group ">
                          <tr>
                            <td>&nbsp;</td>
                            <td>NO. KWITANSI</td>
                            <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="otomatis" readonly></td>

                            <td>FAKTUR PAJAK</td>
                            <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="">
                            </td>
                            <td>&nbsp;</td>

                          </tr>
                          <tr style="border-top:none;">
                            <td>&nbsp;</td>
                            <td>TGL & JAM BAYAR</td>
                            <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i'); ?>"></td>
                            <td>ADM PASIEN BARU RP</td>
                            <td><input type="text" class="form-control rightJustified" name="admrp" id="admrp" value="0" onchange="total_net()" readonly=""></td>
                            <td>&nbsp;</td>

                          </tr>
                          <tr style="border-top:none;">
                            <td>&nbsp;</td>
                            <td>KONSUL/TINDAKAN RP</td>
                            <td><input type="text" class="form-control rightJustified" name="tindakanrp" id="tindakanrp" value="0" onchange="total_net()" readonly></td>

                            <td>RESEP RP</td>
                            <td><input type="text" class="form-control rightJustified" name="reseprp_1" id="reseprp_1" value="0" onchange="total_net()" readonly></td>
                            <td>&nbsp;</td>

                          </tr>
                          <tr style="border-top:none;">
                            <td>&nbsp;</td>
                            <td>LABORATORIUM  RP</td>
                            <td><input type="text" class="form-control rightJustified" name="totalrp_lab" id="totalrp_lab" value="0" readonly></td>
                            <td>RADIOLOGI RP</td>
                            <td><input type="text" class="form-control rightJustified" name="radiorp" id="radiorp" value="0" onchange="total_net()" readonly></td>
                            <td>&nbsp;</td>

                          </tr>

                          <tr style="border-top:none;">
                            <td>&nbsp;</td>
                            <td>PENUNJANG  RP</td>
                            <td><input type="text" class="form-control rightJustified" name="penunjangrp" id="penunjangrp" value="0" readonly></td>
                            <td>BEDAH RP</td>
                            <td><input type="text" class="form-control rightJustified" name="bedahrp" id="bedahrp" value="0" onchange="total_net()" readonly></td>
                            <td>&nbsp;</td>

                          </tr>

                          <tr style="border-top:none;">
                            <td>&nbsp;</td>
                            <td>TOTAL TRANSAKSI</td>
                            <td><input type="text" class="form-control total rightJustified" name="totalrp_transaksi" id="totalrp_transaksi" value="0" readonly></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                          </tr>

                        </div>
                      </div>
                    </table>
                  </div>
                </div>
                <div class="portlet-body">
                  <table class="table" width="100%" border="0">
                    <div class="col-md-7">
                      <div class="form-group">
                        <tr>
                          <td bgcolor="grey" class="title-white" width="12%"><b>&nbsp;ADA PROMO ?</b></td>
                          <!-- <td>&nbsp;</td> -->
                        </tr>
                        <tr>
                          <td style="font-weight:bold;" width="10%" valign="justify" halign="justify">&nbsp;ADA
                            <input type="checkbox" name="adapromo" class="form-control cekpromo" value=1 onclick="cekpro(1)" id="adapromo">
                          </td>
                          <td style="font-weight:bold;" width="10%" valign="justify" halign="justify">TIDAK
                            <input type="checkbox" checked="checked" name="adapromo" class="form-control cekpromo" onclick="cekpro(2)" value=0 id="tidakadapromo">
                          </td>
                          <td width="80%"></td>
                        </tr>
                      </div>
                    </div>
                  </table>
                </div>
                <hr>
                <div class="row" style="margin-bottom: 20px;">
                  <div class="col-md-12">
                    <table border="0" width="70%">
                      <tr>
                        <td width="15%"><b>Jenis Bayar</b></td>
                        <td width="5%" style="text-align:center;">
                          <input type="radio" id="j_umum" name="j_jaminan" class="form-control" onclick="cek_umum()">
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
                            <label class="col-md-5 control-label">Penjamin</label>
                            <div class="col-md-7">
                                <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                  <option value="">--- Pilih ---</option>
                                  <?php $penjamin = $this->db->get("tbl_penjamin")->result(); foreach($penjamin as $row){ ?>
                                  <option value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-5 control-label">Tercover Rp</label>
                            <div class="col-md-7">
                                <input id="tercover_rp" name="tercover_rp" class="form-control input-medium" type="text" style="text-align: right" value="0" onchange="terkofer()"/>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-5 control-label">COB</label>
                            <div class="col-md-7">
                                <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin2" name="vpenjamin2">
                                  <option value="">--- Pilih ---</option>
                                  <?php $penjamin = $this->db->get_where("tbl_penjamin", array("cust_id" => 'BPJS'))->result();
                                  foreach($penjamin as $row){ 
                                  $selected = ($row->cust_id==$data->cust_nama?'selected':'');
                                  ?>
                                  <option <?= $selected;?> value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-5 control-label">Tercover Rp</label>
                            <div class="col-md-7">
                                <input id="tercover_rp2" name="tercover_rp2" class="form-control input-medium" type="text" style="text-align: right" value="0"  onchange="terkofer()"/>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="tabpromo" class="portlet box blue">

                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-reorder"></i><b>PROMO</b>
                    </div>
                  </div>

                  <div class="portlet-body">
                    <table class="table table-hover table-striped table-bordered table-condensed table-scrollable" width="100%" border="0">
                    <thead class="breadcrumb">
                          <tr >

                            <th class="title-white" style="text-align: center" width="5%" halign="justify"><b>No</b></th>
                            <th class="title-white" style="text-align: center" width="20%" halign="justify"><b>Promo</b>
                            </th>
                            <th class="title-white" style="text-align: center" width="20%" halign="justify"><b>Hadiah</b>
                            </th>
                            <th class="title-white" style="text-align: center" width="15%" halign="justify"><b>Qty</b>
                            </th>
                      </thead>
                          </tr>
                          <tr>
                            <td align="center"> 1 </td>
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
                            <td align="center">
                              <input type="text" class="form-control rightJustified input-small" name="qtyhadiah[]" id="qtyhadiah1" value="0">
                            </td>


                          </tr>

                          <tr>
                            <td align="center"> 2 </td>
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

                    </table>
                  </div>
                </div>
              </div>


              <!-- end harimas -->
              <div class="row">
                <div class="col-md-12">
                  <div class="portlet  box blue">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-reorder"></i>
                        <span class="title-white"><b>PENGURANGAN</b></span>
                      </div>


                    </div>

                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" width="100%">
                          <tr bgcolor="#f6f5ff">

                            <td>DISKON TIN/KON</td>
                            <td><input type="text" class="form-control rightJustified" name="diskonrp" id="diskonrp" value="0" onchange="total_net()" readonly></td>
                            <!-- Diskon Resep -->

                            <td>DISKON RESEP</td>
                            <td><input type="text" class="form-control rightJustified" name="diskonresep" id="diskonresep" value="0" onchange="total_net()" readonly></td>

                          </tr>
                          <tr bgcolor="#f6f5ff">
                            <td>REFUND RP</td>
                            <td><input type="text" class="form-control rightJustified" name="refund" id="refund" value="0" onchange="total_net()"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr bgcolor="#f6f5ff">
                            <td>VOUCHER SOURCE</td>
                            <td>
                              <select name="vouchersource" id="vouchersource" class="form-control select2_el_vouchersource input-medium"></select>
                            </td>
                            <td class="vouchercode">&nbsp;Block nominal dibawah ini untuk mendapatkan nilainya</td>
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
                            <td>&nbsp;</td>
                          </tr>

                          <tr bgcolor="#f6f5ff">
                            <td>TERSEDIA UANG MUKA</td>
                            <td><input type="text" readonly class="form-control rightJustified" name="uangmuka" id="uangmuka" value="0" onchange="total_net()"></td>
                            <td> <input type="button" class="btn btn-info" value="CEK DP" onclick="getuangmuka()"></td>
                            <td>&nbsp;</td>

                          </tr>
                          <tr bgcolor="#f6f5ff">
                            <td>UANG MUKA YANG DIGUNAKAN</td>
                            <td><input type="text" class="form-control rightJustified" name="uangmukapakai" id="uangmukapakai" value="0" onchange="total_net()"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                          </tr>

                          <tr bgcolor="#f6f5ff">
                            <td>TOTAL NET RP</td>
                            <td><input type="text" class="form-control rightJustified" name="totalnet" id="totalnet" value="0" readonly></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                          </tr>


                        </table>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div id="hisum" class="portlet box blue">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-reorder"></i>
                        <span><b>HISTORIES UANG MUKA</b></span>
                      </div>


                    </div>

                    <div class="portlet-body form">
                      <div class="form-body">
                        <div class="table-responsive">
                          <table id="datatable_dp" class="table">
                            <thead class="breadcrumb">
                              <th class="title-white" width="5%" style="text-align: center">Pilih</th>
                              <th class="title-white" width="20%" style="text-align: center">No.Kwitansi</th>
                              <th class="title-white" width="15%" style="text-align: center">Tanggal</th>
                              <th class="title-white" width="15%" style="text-align: right">U.Muka (Rp)</th>
                              <th class="title-white" width="15%" style="text-align: right">Dipakai (Rp)</th>
                              <th class="title-white" width="15%" style="text-align: center">Sisa (Rp)</th>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>


                <div class="col-md-12">
                  <div class="portlet ">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-reorder"></i>
                        <span class="label label-warning title-white">PEMBAYARAN</span>
                      </div>


                    </div>
                    <span class="label label-info title-white">ELECTRONIC (DEBET/CREDIT/TRANFER/EMONEY)</span>
                    <div class="portlet-body form">

                      <div class="form-body">
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
                                  <td><input name="bayar_nokartu[]" class="form-control" type="text" onchange="mink(1);" id="kartu1" maxlength="16" min="16"></td>
                                  <td><input name="bayar_trvalid[]" onchange="totalline_bayar(1);mina(1);" id="aprov1" value="0" type="text" maxlength="6" min="6" class="form-control rightJustified" maxlength="20"></td>
                                  <td><input name="bayar_nilai[]" id="bayar_nilai1" onchange="totalline_bayar(1);" value="0" type="text" maxlength="20" class="form-control rightJustified "></td>
                                  <td><input name="bayar_adm[]" onchange="totalline_bayar(1)" value="0" type="text" readonly="" class="form-control rightJustified "></td>
                                  <td><input name="bayar_total[]" id="bayar_total1" type="text" class="form-control rightJustified" readonly></td>

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
                <div class="col-md-9">
                  <table class="table">

                    <tr>
                      <td>TOTAL ELECTRONIC RP</td>
                      <td><input type="text" class="form-control rightJustified" name="totalelectronicrp" id="totalelectronicrp" value="0" readonly></td>

                    </tr>
                    <tr>
                      <td>SELISIH</td>
                      <td><input type="text" class="form-control rightJustified" name="selisihrp" id="selisihrp" value="0" onchange="total_net()"></td>

                    </tr>
                    <tr>
                      <td>TOTAL TUNAI RP</td>
                      <td><input type="text" class="form-control rightJustified" name="totaltunairp" id="totaltunairp" value="0" onchange="total_net()"></td>

                    </tr>
                    <!--tr>
												  <td>UANG PASIEN RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="uangpasienrp" id="uangpasienrp" value="0" onchange="total_net()"></td>
												  
												</tr-->
                    <tr>
                      <td>KEMBALI RP</td>
                      <td><input type="text" class="form-control total rightJustified" name="kembalirp" id="kembalirp" value="0" readonly></td>
                      <td><span id="pertanyaan">Auto ke uang muka</span></td>
                      <td width="10%"><input type="checkbox" id="uangmukakembaliya" name="kembaliuang" checked value="1"><span id="textya"></span></td>
                      <td width="10%"><input type="checkbox" id="uangmukakembalitidak" name="kembaliuang" value="0"><span id="texttidak">Kembali ke pasien</span></td>
                    </tr>
                    <tr>
                      <td>SUDAH TERIMA DARI <font color="red">*</font></td>
                      <td><input type="text" class="form-control total rightJustified" name="terimadari" id="terimadari" value=""></td>

                    </tr>
                  </table>
                </div>
              </div>


              <div class="row">
                <div class="col-xs-12">
                  <!--div class="wells"-->
                  <div class="form-actions">


                    <button id="btnsimpan_bayar" type="button" onclick="save_bayar()" class="btn blue"><i class="fa fa-save"></i> <b>PROSES</b></button>

                    <button id="btncetak_bayar" type="button" onclick="javascript:_urlcetak();" class="btn yellow">
                      <i class="fa fa-print"></i> <b>CETAK KWITANSI</b></button>

                    <!-- script original -->
                    <!-- <button id="btncetak_bayar2" type="button" onclick="javascript:cekisiobat();" class="btn yellow"><i class="fa fa-print"></i> <b>CETAK KWITANSI OBAT</b></button> -->

                    <!-- husain change -->
                    <button type="button" id="btncetak_bayar2" class="btn yellow print_laporan" onclick="javascript:cekpenjamin();"><i class="fa fa-print"></i> <b>CETAK DOKUMEN JAMINAN</b></button>
                    <!-- end husain -->
                    <a href="<?= base_url('kasir_konsul') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI
                      </b></a>
                    <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                  </div>
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
$this->load->view('template/footer_tb');
?>

<script>
  $("#btnbayar").hide();
  $("#j_umum").attr("disabled", true);
  $("#j_jaminan").attr("disabled", true);
  function cekpenjamin() {
      var baseurl = "<?php echo base_url() ?>";
      var nokwitansi = $('[name="nokwitansi"]').val();
      var noreg = $('[name="noreg"]').val();
      var baseurl = "<?php echo base_url() ?>";
      var var1 = baseurl + 'kasir_konsul/cetak_jaminan/?kwitansi=' + nokwitansi + '&noreg=' + noreg;
      window.open(var1, '_blank');
  }
  $("#show_jaminan").hide();
  function cek_umum(){
    if(document.getElementById('j_umum').checked == true){
      $("#show_jaminan").hide();
    }
  }
  function cek_jaminan(){
    if(document.getElementById('j_jaminan').checked == true){
      $("#show_jaminan").show();
    }
  }
  function terkofer() {
    var tercover = $("#tercover_rp").val();
    var tercover2 = $("#tercover_rp2").val();
    var totalrpx = $("#totalrp_transaksi").val();
    var totalrp = Number(parseInt(totalrpx.replaceAll(',','')));
    var trp = Number(parseInt(tercover.replaceAll(',','')));
    var trp2 = Number(parseInt(tercover2.replaceAll(',','')));
    total_cover = trp + trp2;
    hasil = totalrp - total_cover;
    $("#tercover_rp").val(formatCurrency1(trp));
    $("#tercover_rp2").val(formatCurrency1(trp2));
    $("#totalnet").val(formatCurrency1(hasil));
    // console.log(hasil)
    total_net();
  }
</script>

<script>
  var idrow = 2;
  var idrow2 = 2;
  var rowCount;
  var arr = [1];

  $(window).on("load", function() {
    $("#frmkonsul").trigger("reset");
    $(".vouchercode").hide();
    var poli = $("#reg_klinik").val();
    initailizeSelect2_register(poli);
  });

  $('#tabpromo').hide();
  $('#hisum').hide();

  function cekpro(param) {
    if (param == 1) {
      $('#tabpromo').show("1000");
    } else if (param == 2) {
      $('#tabpromo').hide("1000");
    }
  }

  function nominal_voucher(param) {
    var voucher_nominal;
    var selected_voucher = $("[name='vouchersource']").val();
    var novoucher = $("#vouchercode" + param).val();
    var vrpcount = $("#vrp").length;
    $.ajax({
      url: "/kasir_konsul/check_voucher/",
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
      url: "/kasir_konsul/check_group_voucher/" + sourceval,
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
            $("#vouchercode1").append("<option value='" + value.novoucher + "'>" + value.novoucher +
              "</option>");
            $("#vouchercode2").append("<option value='" + value.novoucher + "'>" + value.novoucher +
              "</option>");
            $("#vouchercode3").append("<option value='" + value.novoucher + "'>" + value.novoucher +
              "</option>");
          });
        }
      }
    });
  });

  function tambah() {
    // console.log(idrow);
    var table = document.getElementById('datatable');
    rowCount = table.rows.length;
    arr.push(idrow);

    var x = document.getElementById('datatable').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
      ")' class='select2_el_tarif_tindakan form-control' ></select>";
    var dokter = "<select name='dokter[]' id=dokter" + idrow + " class='select2_el_dokter form-control' ></select>";
    var perawat = "<select name='perawat[]' id=perawat" + idrow + " class='select2_el_perawat form-control' ></select>";
    var button = "<button type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'>"
    // td1.innerHTML=akun;
    // td2.innerHTML=dokter;
    // td3.innerHTML=perawat;
    // td4.innerHTML="<input name='harga[]'  id=harga"+idrow+" onchange='totalline("+idrow+") value='0'  type='text' class='form-control rightJustified' readonly>";
    // td5.innerHTML="<input name='disc1[]'   id=disc1"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified'  >";
    // td6.innerHTML="<input name='disc2[]'   id=disc2"+idrow+" onchange='totalline("+idrow+")' value='0'  type='text' class='form-control rightJustified' >";
    // td7.innerHTML="<input name='jumlah[]' id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%' readonly>";

    td1.innerHTML = button;
    td2.innerHTML = akun;
    td3.innerHTML = dokter;
    td4.innerHTML = perawat;
    td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified' readonly><input name='feemedis[]'  id=feemedis" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='bhp[]'  id=bhp" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='tarifrs[]'  id=tarifrs" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='tarifdr[]'  id=tarifdr" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly>";

    td6.innerHTML = "<input name='disc1[]'   id=disc1" + idrow + " onchange='totalline(" + idrow +
      ")' value='0'  type='text' class='form-control rightJustified'  >";

    td7.innerHTML = "<input name='disc2[]' id=disc2" + idrow + " onchange='totalline(" + idrow +
      ")' value='0' type='text' class='form-control rightJustified' >";
    td8.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
      " type='text' class='form-control rightJustified' size='40%' readonly>";

    poli = $('#reg_klinik').val();
    initailizeSelect2_tarif_tindakan(poli);
    initailizeSelect2_dokter();
    initailizeSelect2_perawat();

    var selectElement = document.getElementById('dokter' + idrow);
    var opt = document.createElement('option');
    opt.value = $('#kodokterpilih').val();
    opt.innerHTML = $('#kodokterpilih').val() + ' | ' + $('#nadokterpilih').val();
    selectElement.appendChild(opt);
    // $('#dokter'+idrow).val($('#kodokterpilih').val);
    idrow++;
  }

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
    td3.innerHTML = "<input name='bayar_nokartu[]' id='kartu" + idrow + "' class='form-control' type='text' maxlength='16' minlength='16' onchange='mink(" + idrow + ")'>";
    td4.innerHTML = "<input name='bayar_trvalid[]' id='aprov" + idrow + "' onchange='totalline_bayar(" + idrow2 +
      ")' value='0' type='text' class='form-control rightJustified' maxlength='6' minlength='6' onchange='mina(" + idrow + ")'>";
    td5.innerHTML = "<input name='bayar_nilai[]' id='bayar_nilai" + idrow2 + "' onchange='totalline_bayar(" + idrow2 +
      ")' value='0' type='text' class='form-control rightJustified'>";
    td6.innerHTML = "<input name='bayar_adm[]' onchange='totalline_bayar(" + idrow2 +
      ")' value='0' type='text' class='form-control rightJustified'>";
    td7.innerHTML = "<input name='bayar_total[]' id='bayar_total" + idrow2 + "' type='text' class='form-control rightJustified' readonly>";
    initailizeSelect2_kasbankedc();
    idrow2++;
  }

  function mink(id) {
    var kartu = document.getElementById('kartu' + id).value;
    if (kartu.length < 16) {
      swal({
        title: "NO KARTU",
        html: "Harus berisi 16 digit",
        type: "warning",
        confirmButtonText: "OK"
      }).then((value) => {
        $('#kartu' + id).focus();
      });
    }
  }

  function mina(id) {
    var aprov = document.getElementById('aprov' + id).value;
    if (aprov.length < 6) {
      swal({
        title: "APROVAL CODE",
        html: "Harus berisi 6 digit",
        type: "warning",
        confirmButtonText: "OK"
      }).then((value) => {
        $('#aprov' + id).focus();
      });
    }
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

  // $('#kode1').on('change', function() {
  //   var value = this.value;
  //   alert(value);
  // });

  function showbarangname(str, id) {
    // console.log(test);
    var xhttp;
    var vid = id;
    $('#sat' + vid).val('');
    $('#harga' + vid).val(0);
    $('#feemedis' + vid).val(0);
    $('#bhp' + vid).val(0);
    $('#jumlah' + vid).val(0);
    $.ajax({
      url: "<?php echo base_url(); ?>kasir_konsul/getinfotindakan/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        console.log(data)
        var feemedis = Number(parseInt((data.feemedispoli).replaceAll(',','')));
        var bhp = Number(parseInt((data.bhp).replaceAll(',','')));
        var tarifrs = Number(parseInt((data.tarifrspoli).replaceAll(',','')));
        var tarifdr = Number(parseInt((data.tarifdrpoli).replaceAll(',','')));
        $('#harga' + vid).val(formatCurrency1(data.totaljasa));
        $('#feemedis' + vid).val(formatCurrency1(feemedis));
        $('#bhp' + vid).val(formatCurrency1(bhp));
        $('#tarifrs' + vid).val(formatCurrency1(tarifrs));
        $('#tarifdr' + vid).val(formatCurrency1(tarifdr));
        totalline(id);
      }
    });

  }

  function save() {
    //var tanggal   = $('[name="tanggal"]').val(); 
    var poli = $('[name="reg_klinik"]').val();
    var nomor = $('[name="noreg"]').val();
    var nohp = $('#reg_hp').val();
    var total = $('#_vtotal2').text();
    var cekhp = $('#reg_cekhp').is(':checked');
    var tresepx = $('#_vtotalresep').text();

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

    if (cekhp === false) {
      swal({
        title: "Apakah Nomor HP Pasien",
        html: "<p> <br><b>" + nohp +
          "<br></b><br>Sudah Sesuai ???  <br>Checklist Di samping No.HP Jika Sudah Sesuai</p>",
        type: "question",
        confirmButtonText: "OK"
      });

      return;
    }


    if (nomor == "" || total == "0.00" || total == "" || nohp == '') {
      swal({
        title: "Data Belum Lengkap / Belum ada transaksi ...",
        type: "info",
        confirmButtonText: "OK"
      }).then((value) => {
        return;
      });
    } else {
      $.ajax({
        url: '<?php echo site_url('kasir_konsul/ajax_add') ?>',
        data: $('#frmkonsul').serialize(),
        type: 'POST',

        success: function(data) {
          document.getElementById("btnsimpan").disabled = true;
          document.getElementById("tersimpan").value = "OK";

          swal({
            title: "BIAYA SUDAH TERBENTUK",
            html: "<p>Akan Di Proses Dengan Nilai Nominal <br><b>" + total + "</b></p><br><p>Dengan Biaya Resep Racik <b>" + tresepx + "</b>",
            type: "info",
            confirmButtonText: "OK"
          }).then((value) => {
            bayar();
          });

        },
        error: function(data) {
          swal('', 'Data gagal disimpan ...', '');

        }
      });
    }
  }

  function currencyFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function save_bayar() {
    // husain add
    var kembalirp   = angka($('[name="kembalirp"]').val());
    
    if(document.getElementById("j_umum").checked == true && document.getElementById("j_jaminan").checked == false){
      jaminan = 0;
    } else if(document.getElementById("j_jaminan").checked == true && document.getElementById("j_umum").checked == false){
      jaminan = 1;
    }
    var tercover    = $("#tercover_rp").val();
    var tercoverx   = Number(parseInt(tercover.replaceAll(',','')));
    var tercover2   = $("#tercover_rp2").val();
    var totalrpx    = $("#totalrp_transaksi").val();
    var totalrp     = Number(parseInt(totalrpx.replaceAll(',','')));
    var trp         = Number(parseInt(tercover.replaceAll(',','')));
    var trp2        = Number(parseInt(tercover2.replaceAll(',','')));
    total_cover     = trp + trp2;
    hasil           = totalrp - total_cover;
    // end husain
    //cek promo
    var voucherrp1  = $('#voucherrp1').val();
    var voucherrp2  = $('#voucherrp2').val();
    var voucherrp3  = $('#voucherrp3').val();
    if (voucherrp1 != '' | voucherrp1 != null) {
      var vc1 = '<br>VC1 : ' + voucherrp1;
    } else {
      var vc1 = '';
    }
    if (voucherrp2 != '' | voucherrp2 != null) {
      var vc2 = '<br>VC2 : ' + voucherrp2;
    } else {
      var vc2 = '';
    }
    if (voucherrp3 != '' | voucherrp3 != null) {
      var vc3 = '<br>VC3 : ' + voucherrp3;
    } else {
      var vc3 = '';
    }
    var tresepx       = $('#_vtotalresep').text();
    var statuspromo   = '';
    var ada           = $('#adapromo').is(':checked');
    var tidak         = $('#tidakadapromo').is(':checked');
    var totalnetx     = $('#totalnet').val();
    var totaltunairp  = $('#totaltunairp').val();
    var totalnet      = parseInt(totalnetx.replaceAll(',', ''));
    if (ada) {
      var statuspromo = 'ada';
    } else if (tidak) {
      var statuspromo = 'tidak';
    }
    //cek kembalian
    var statuskembalian   = "";
    var kembali_ya        = $('#uangmukakembaliya').is(':checked');
    var kembali_tidak     = $('#uangmukakembalitidak').is(':checked');
    if (kembali_ya) {
      var statuskembalian = 'kembali';
    } else if (kembali_tidak) {
      var statuskembalian = 'tidak';
    }
    // cek pakai uang muka apakah lebih dari tersedia uang muka
    var uangmukapakai   = $('#uangmukapakai').val();
    var uangmukapakaix  = parseInt(uangmukapakai.replaceAll(',', ''));
    var uangmuka        = $('#uangmuka').val();
    var nuangmuka       = Number(uangmuka.replace(/[^0-9\.]+/g, ""));
    var nomor           = $('[name="noreg"]').val();
    var total           = $('#totalnet').val();
    var terimadari      = $('#terimadari').val();
    
    if (kembalirp<0){
      swal({
        title: "PEMBAYARAN",
        html: "<p>MASIH BELUM LUNAS, Silahkan Lunasi Terlebih Dahulu...</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 

    if (nomor == "" || terimadari == "" || statuspromo == "" || statuskembalian == "" || uangmukapakai > nuangmuka) {
      if (statuspromo == "") {
        var title   = "Status Promo Belum dipilih";
        var html    = " Tidak Boleh Kosong .!!!";
      } else if (total == "0" || total == "") {
        var title = "Belum ada pembayaran";
        var html = " Tidak Boleh Kosong .!!!";
      } else if (terimadari == "") {
        var title = "Terima dari ";
        var html = " Tidak Boleh Kosong .!!!";
      } else if (statuskembalian == "") {
        var title = "Status Kembalian Belum dipilih ";
        var html = " Tidak Boleh Kosong .!!!";
      } else if (uangmukapakai > nuangmuka) {
        var title = "Beban DP";
        var html = "Tidak Sesuai dengan Uang Muka .!!!";
      }
      swal({
        title: title,
        html: html,
        type: "error",
        confirmButtonText: "OK"
      });

      //   swal('','Data Belum Lengkap / Belum ada pembayaran ...','');   	
    } else {
      if (totalnet == 0 || totalnet == '0') {
        if(jaminan == 1){
          if(tercoverx == 0){
            swal({
              title: "BIAYA JAMINAN",
              html: "Harus diisi",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          } else {
            $.ajax({
              url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
              data: $('#frmkonsul').serialize(),
              type: 'POST',
              dataType: 'json',
    
              success: function(data) {
                if(data.status == 1){
                  document.getElementById("btnsimpan_bayar").disabled = true;
                  //document.getElementById("tersimpan_bayar").value="OK";
                  swal({
                    title: "KWITANSI PEMBAYARAN",
                    html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + formatCurrency1(uangmukapakaix) + "<br>Dengan Biaya Resep Racik <b>" + tresepx + "</b>" + vc1 + vc2 + vc3,
                    // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                    type: "info",
                    confirmButtonText: "OK"
                  }).then((value) => {
                    //location.reload();
                    document.getElementById("btncetak_bayar").disabled = false;
                    document.getElementById("btncetak_bayar2").disabled = false;
                    $('#nokwitansi').val(data.nomor);
                  });
                } else {
                  swal('', 'Data gagal disimpan ...', '');
                }
              }
            });
          }
        } else {
          $.ajax({
            url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
            data: $('#frmkonsul').serialize(),
            type: 'POST',
            dataType: 'json',
  
            success: function(data) {
              if(data.status == 1){
                document.getElementById("btnsimpan_bayar").disabled = true;
                //document.getElementById("tersimpan_bayar").value="OK";
                swal({
                  title: "KWITANSI PEMBAYARAN",
                  html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + formatCurrency1(uangmukapakaix) + "<br>Dengan Biaya Resep Racik <b>" + tresepx + "</b>" + vc1 + vc2 + vc3,
                  // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                  type: "info",
                  confirmButtonText: "OK"
                }).then((value) => {
                  //location.reload();
                  document.getElementById("btncetak_bayar").disabled = false;
                  document.getElementById("btncetak_bayar2").disabled = false;
                  $('#nokwitansi').val(data.nomor);
                });
              } else {
                swal('', 'Data gagal disimpan ...', '');
              }
            }
          });
        }
      } else if (uangmukapakaix == 0 || uangmukapakaix == '0') {
        var x = document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);
        var abc = '';
        for (i = 1; i < idrow2; i++) {
          var tipe = document.getElementById("bayar_tipe" + i).value;
          var total = document.getElementById("bayar_total" + i).value;
          if (tipe == 1) {
            $1 = 'DC';
          } else if (tipe == 2) {
            $1 = 'CC';
          } else if (tipe == 3) {
            $1 = 'TR';
          } else {
            $1 = 'OL';
          }
          if (total == '' || total == null || total == 0) {
            abc += "<br>" + $1 + " : .....";
          } else {
            abc += "<br>" + $1 + " : " + total;
          }
        }
        if(jaminan == 1){
          if(tercoverx == 0){
            swal({
              title: "BIAYA JAMINAN",
              html: "Harus diisi",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          } else {
            $.ajax({
              url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
              data: $('#frmkonsul').serialize(),
              type: 'POST',
              dataType: 'json',
    
              success: function(data) {
                if(data.status == 1){
                  document.getElementById("btnsimpan_bayar").disabled = true;
                  //document.getElementById("tersimpan_bayar").value="OK";
                  swal({
                    title: "KWITANSI PEMBAYARAN",
                    html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br>" + abc + "<br> CS : " + totaltunairp + vc1 + vc2 + vc3,
                    // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                    type: "info",
                    confirmButtonText: "OK"
                  }).then((value) => {
                    //location.reload();
                    document.getElementById("btncetak_bayar").disabled = false;
                    document.getElementById("btncetak_bayar2").disabled = false;
                    $('#nokwitansi').val(data.nomor);
                  });
                } else {
                  swal('', 'Data gagal disimpan ...', '');
                }
              }
            });
          }
        } else {
          $.ajax({
            url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
            data: $('#frmkonsul').serialize(),
            type: 'POST',
            dataType: 'json',
  
            success: function(data) {
              if(data.status == 1){
                document.getElementById("btnsimpan_bayar").disabled = true;
                //document.getElementById("tersimpan_bayar").value="OK";
                swal({
                  title: "KWITANSI PEMBAYARAN",
                  html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br>" + abc + "<br> CS : " + totaltunairp + vc1 + vc2 + vc3,
                  // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                  type: "info",
                  confirmButtonText: "OK"
                }).then((value) => {
                  //location.reload();
                  document.getElementById("btncetak_bayar").disabled = false;
                  document.getElementById("btncetak_bayar2").disabled = false;
                  $('#nokwitansi').val(data.nomor);
                });
              } else {
                swal('', 'Data gagal disimpan ...', '');
              }
            }
          });
        }
      } else if (totaltunairp == 0 || totaltunairp == '0') {
        var x = document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);
        var abc = '';
        for (i = 1; i < idrow2; i++) {
          var tipe = document.getElementById("bayar_tipe" + i).value;
          var total = document.getElementById("bayar_total" + i).value;
          if (tipe == 1) {
            $1 = 'DC';
          } else if (tipe == 2) {
            $1 = 'CC';
          } else if (tipe == 3) {
            $1 = 'TR';
          } else {
            $1 = 'OL';
          }
          if (total == '' || total == null || total == 0) {
            abc += "<br>" + $1 + " : .....";
          } else {
            abc += "<br>" + $1 + " : " + total;
          }
        }
        if(jaminan == 1){
          if(tercoverx == 0){
            swal({
              title: "BIAYA JAMINAN",
              html: "Harus diisi",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          } else {
            $.ajax({
              url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
              data: $('#frmkonsul').serialize(),
              type: 'POST',
              dataType: 'json',
    
              success: function(data) {
                if(data.status == 1){
                  document.getElementById("btnsimpan_bayar").disabled = true;
                  //document.getElementById("tersimpan_bayar").value="OK";
                  swal({
                    title: "KWITANSI PEMBAYARAN",
                    html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + currencyFormat(Number(uangmukapakai).toFixed(2)) + "<br>" + abc + vc1 + vc2 + vc3,
                    // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                    type: "info",
                    confirmButtonText: "OK"
                  }).then((value) => {
                    //location.reload();
                    document.getElementById("btncetak_bayar").disabled = false;
                    document.getElementById("btncetak_bayar2").disabled = false;
                    $('#nokwitansi').val(data.nomor);
                  });
                } else {
                  swal('', 'Data gagal disimpan ...', '');
                }
              }
            });
          }
        } else {
          $.ajax({
            url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
            data: $('#frmkonsul').serialize(),
            type: 'POST',
            dataType: 'json',
  
            success: function(data) {
              if(data.status == 1){
                document.getElementById("btnsimpan_bayar").disabled = true;
                //document.getElementById("tersimpan_bayar").value="OK";
                swal({
                  title: "KWITANSI PEMBAYARAN",
                  html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + currencyFormat(Number(uangmukapakai).toFixed(2)) + "<br>" + abc + vc1 + vc2 + vc3,
                  // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                  type: "info",
                  confirmButtonText: "OK"
                }).then((value) => {
                  //location.reload();
                  document.getElementById("btncetak_bayar").disabled = false;
                  document.getElementById("btncetak_bayar2").disabled = false;
                  $('#nokwitansi').val(data.nomor);
                });
              } else {
                swal('', 'Data gagal disimpan ...', '');
              }
            }
          });
        }
      } else {
        var x = document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);
        var abc = '';
        for (i = 1; i < idrow2; i++) {
          var tipe = document.getElementById("bayar_tipe" + i).value;
          var total = document.getElementById("bayar_total" + i).value;
          if (tipe == 1) {
            $1 = 'DC';
          } else if (tipe == 2) {
            $1 = 'CC';
          } else if (tipe == 3) {
            $1 = 'TR';
          } else {
            $1 = 'OL';
          }
          if (total == '' || total == null || total == 0) {
            abc += "<br>" + $1 + " : .....";
          } else {
            abc += "<br>" + $1 + " : " + total;
          }
        }
        if(jaminan == 1){
          if(tercoverx == 0){
            swal({
              title: "BIAYA JAMINAN",
              html: "Harus diisi",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          } else {
            $.ajax({
              url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
              data: $('#frmkonsul').serialize(),
              type: 'POST',
              dataType: 'json',
    
              success: function(data) {
                if(data.status == 1){
                  document.getElementById("btnsimpan_bayar").disabled = true;
                  //document.getElementById("tersimpan_bayar").value="OK";
                  swal({
                    title: "KWITANSI PEMBAYARAN",
                    html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + currencyFormat(Number(uangmukapakai).toFixed(2)) + "<br>" + abc + "<br> CS : " + totaltunairp + vc1 + vc2 + vc3,
                    // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                    type: "info",
                    confirmButtonText: "OK"
                  }).then((value) => {
                    //location.reload();
                    document.getElementById("btncetak_bayar").disabled = false;
                    document.getElementById("btncetak_bayar2").disabled = false;
                    $('#nokwitansi').val(data.nomor);
                  });
                } else {
                  swal('', 'Data gagal disimpan ...', '');
                }
              }
            });
          }
        } else {
          $.ajax({
            url: '<?php echo site_url('kasir_konsul/ajax_add_bayar?jaminan=') ?>'+jaminan+'&hasil='+hasil,
            data: $('#frmkonsul').serialize(),
            type: 'POST',
            dataType: 'json',
  
            success: function(data) {
              if(data.status == 1){
                document.getElementById("btnsimpan_bayar").disabled = true;
                //document.getElementById("tersimpan_bayar").value="OK";
                swal({
                  title: "KWITANSI PEMBAYARAN",
                  html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p><br> DP : " + currencyFormat(Number(uangmukapakai).toFixed(2)) + "<br>" + abc + "<br> CS : " + totaltunairp + vc1 + vc2 + vc3,
                  // html: "<p> No. Bukti : <b>" + data.nomor + "</b> </p>",
                  type: "info",
                  confirmButtonText: "OK"
                }).then((value) => {
                  //location.reload();
                  document.getElementById("btncetak_bayar").disabled = false;
                  document.getElementById("btncetak_bayar2").disabled = false;
                  $('#nokwitansi').val(data.nomor);
                });
              } else {
                swal('', 'Data gagal disimpan ...', '');
              }
            }
          });
        }
      }
    }
  }


  function hapusBarisIni(param) {
    // console.log(param);
    // console.log(param);
    // console.log(arr.indexOf(param))
    var x = document.getElementById('datatable').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);

    rowCount--;
    // console.log('rowCount ' + rowCount);
    total();
  }

  function hapus() {
    if (idrow > 2) {
      var x = document.getElementById('datatable').deleteRow(idrow - 1);
      idrow--;
      total();
    }
  }

  function hapus_bayar() {
    if (idrow2 > 2) {
      var x = document.getElementById('datatable_pembayaran').deleteRow(idrow2 - 1);
      idrow2--;
      total();
    }
  }



  function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;
    tperawatan = 0;

    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];

      jumlah = row.cells[7].children[0].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));

      tjumlah = tjumlah + eval(jumlah1);

      diskon = row.cells[6].children[0].value;
      var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

      tdiskon = tdiskon + eval(diskon1);

      perawatan = row.cells[4].children[0].value;
      var perawatan1 = Number(perawatan.replace(/[^0-9\.]+/g, ""));

      tperawatan = tperawatan + eval(perawatan1 - diskon1);

    }
    console.log(tperawatan);

    var table_resep = document.getElementById('datatable_resep');
    var rowCount_resep = table_resep.rows.length;

    tjumlah_resep = 0;
    tjumlah_resep_disc = 0;

    for (var i = 1; i < rowCount_resep; i++) {
      var row = table_resep.rows[i];

      jumlah = row.cells[1].children[0].value;
      jumlahdisc = row.cells[1].children[1].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var jumlah1disc = Number(jumlahdisc.replace(/[^0-9\.]+/g, ""));
      tjumlah_resep = tjumlah_resep + eval(jumlah1);
      tjumlah_resep_disc = tjumlah_resep_disc + eval(jumlah1disc);

    }

    tresep = tjumlah_resep;
    var vadmrp = $('#admrp').val(); //saya nambah ini
    var _vsubadm = Number(vadmrp.replace(/[^0-9\.]+/g, ""));
    document.getElementById("_vsubadm").innerHTML = formatCurrency1(vadmrp); //saya nambah ini
    //    document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);saya ganti
    document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tperawatan);
    document.getElementById("tindakanrp").value = formatCurrency1(tperawatan);
    document.getElementById("_vtotalresep").innerHTML = formatCurrency1(tresep);
    document.getElementById("_vtotalresepdisc").innerHTML = formatCurrency1(tjumlah_resep_disc);
    document.getElementById("_vtotaldiskon").innerHTML = formatCurrency1(tdiskon);
    document.getElementById("_vtotal2").innerHTML = formatCurrency1(tjumlah + tresep + _vsubadm);
    $("#totalrp_transaksi").val(formatCurrency1(tjumlah + tresep + _vsubadm));
    $("#totalnet").val(formatCurrency1(tjumlah + tresep + _vsubadm));
    $("#reseprp_1").val(formatCurrency1(tresep));
    var _vtota = tjumlah + tresep;
    //    document.getElementById("_vtotal").innerHTML=formatCurrency1(tperawatan);

    if (tjumlah > 0) {
      document.getElementById("btnsimpan").disabled = false;
    } else {
      document.getElementById("btnsimpan").disabled = true;
    }

  }

  function totalline(id) {

    var table = document.getElementById('datatable');
    // console.log(table);
    // var row       = table.rows[id];
    var row = table.rows[arr.indexOf(id) + 1];
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    var vdiskon = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));

    if (eval(vdiskon) > 0) {
      diskon = (row.cells[5].children[0].value / 100) * harga;
      row.cells[6].children[0].value = formatCurrency1(diskon);
      tot = harga - diskon;
    } else {
      var diskon = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
      tot = harga - diskon;
    }

    // console.log(row.cells[4].children[0].value);

    row.cells[7].children[0].value = formatCurrency1(tot);
    total();

  }

  function totalline_bayar(id) {

    var table = document.getElementById('datatable_pembayaran');
    var row = table.rows[id];
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    var adm = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    rpadm = (row.cells[5].children[0].value / 100) * harga;
    tot = harga + rpadm;
    row.cells[6].children[0].value = formatCurrency1(tot);
    total_bayar2();
    total_net();
    var bayarx = $('#bayar_nilai' + id).val();
    var bayar = Number(bayarx);
    $('#bayar_nilai' + id).val(formatCurrency1(bayar));

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

    //uangmuka

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

    document.getElementById("uangmuka").value = formatCurrency1(tjumlah);
    total_net(); //saya nambah ini


  }

  function total_bayar2() {

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

    //uangmuka

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

    //    document.getElementById("uangmuka").value=formatCurrency1(tjumlah);
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
          // console.log(data);
          if(data.jenispas == 'PAS1'){
            document.getElementById('j_umum').checked = true;
            $("#show_jaminan").hide();
            $("#btncetak_bayar2").hide();
          } else{
            document.getElementById('j_jaminan').checked = true;
            $("#show_jaminan").show();
          }
          $('#pasien').val(data.rekmed);
          $('#reg_klinik').val(data.kodepos);
          $('#reg_namapasien').val(data.namapas);
          $('#reg_poli').val(data.kodepos);
          $('#reg_hp').val(data.handphone);
          $('#pasien_bayar').val(data.rekmed);
          $('#noreg_bayar').val(str);
          initailizeSelect2_tarif_tindakan(data.kodepos);

          var opt = data;
          var penjamin = $("#vpenjamin");
          penjamin.empty();
          $(opt).each(function() {
            var option = $("<option/>");
            option.html(this.penjamin);
            option.val(this.cust_id);
            penjamin.append(option);
          });
        }
      });
      var vtotal = $('#_vsubtotal').text();
      var xtotal = parseInt(vtotal.replaceAll(',', ''));
      var xhttp;
      if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=dokter1]').val('');
        $('[id=perawat1]').val('');
        $('[id=harga1]').val('');
        $('[id=disc1]').val('');
        $('[id=disc21]').val('');
        $('[id=jumlah1]').val('');
        totalline(1);
      } else {
        $.ajax({
          url: "<?php echo base_url(); ?>kasir_konsul/getdataregistrasi2/?noreg=" + str,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            for (i = 0; i <= data.length-1; i++) {
              hapus();
            }
            for (i = 0; i <= data.length-1; i++) {
              if (i > 0) {
                tambah();
              }
              console.log(data)
              x = i + 1;
              var option = $("<option selected></option>").val(data[i].kodetarif).text(data[i].tindakan);
              var option2 = $("<option selected></option>").val(data[i].kodokter).text(data[i].nadokter);
              var option3 = $("<option selected></option>").val(data[i].koperawat).text(data[i].naperawat);
              tarifdr = data[i].tarifdr;
              tarifrs = data[i].tarifrs;
              $('#kode' + x).append(option).trigger('change');
              $('#dokter' + x).append(option2).trigger('change');
              $('#perawat' + x).append(option3).trigger('change');
              var tarifdr = Number(parseInt(tarifdr));
              var tarifrs = Number(parseInt(tarifrs));
              var hrg = tarifdr + tarifrs;
              $("#harga" + x).val(formatCurrency1(hrg));
            }
          }
        });
      }
      getdataresep();
      var cek_totalx = $("#_vtotal2").text();
      var cek_total = Number(parseInt(cek_totalx.replaceAll(',','')));
    }
    poli = $('#reg_klinik').val();
    initailizeSelect2_tarif_tindakan(poli);
  }


  function getuangmuka() {
    var xhttp;
    $('#hisum').show("1000");
    var str = $('[name=pasien]').val();
    $('#datatable_dp tbody').empty();
    console.log(str)
    if (str == "") {

    } else {
      $.ajax({
        url: "<?php echo base_url(); ?>kasir_konsul/getdatadp/?rekmed=" + str.trim(),
        type: "GET",
        //dataType: "JSON",		
        success: function(data) {
          // concole.log(data)
          $('#datatable_dp tbody').append(data);
          total_bayar();
          total_net();
        }
      });
    }
  }


  function getdataresep() {
    var xhttp;
    var str = $('[name=noreg]').val();
    $('#datatable_resep tbody').empty();
    if (str == "") {

    } else {
      $.ajax({
        url: "<?php echo base_url(); ?>kasir_konsul/getdataresep/?noreg=" + str,
        type: "GET",
        success: function(data) {
          $('#datatable_resep tbody').append(data);
        }
      });
    }
  }


  function bayar() {
    var totaldiskon = document.getElementById("_vtotaldiskon").innerHTML;
    var tindakan = document.getElementById("_vsubtotal").innerHTML;
    var resep = document.getElementById("_vtotalresep").innerHTML;
    var total = document.getElementById("_vtotal").inneHTML;
    var totalresepdisc = document.getElementById("_vtotalresepdisc").innerHTML;
    $('#diskonrp').val(totaldiskon);
    $('#tindakanrp').val(tindakan);
    $('#reseprp_1').val(resep);
    $('#totalrp').val(total);
    $('#diskonresep').val(totalresepdisc);
    total_net();
    $('.nav-pills a[href="#tab2"]').tab('show');

  }

  function admrp(koders, rekmed, idtr) {
    $.ajax({
      url: "<?php echo base_url(); ?>kasir_konsul/getinfotadm/?rekmed=" + rekmed,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        var adm = data.adm;
        // console.log(data.adm);
        if (idtr > 264573 && adm == 0) {
          if (koders == 'DIY') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'DTI') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'KGD') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'KBJ') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'BSD') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'DPK') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'CBR') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'BKS') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'MDN') {
            $('#admrp').val(formatCurrency1(25000));
            $('#admrp1').val(formatCurrency1(25000));
          } else if (koders == 'SMG') {
            $('#admrp').val(formatCurrency1(15000));
            $('#admrp1').val(formatCurrency1(15000));
          } else if (koders == 'SBY') {
            $('#admrp').val(formatCurrency1(10000));
            $('#admrp1').val(formatCurrency1(10000));
          } else if (koders == 'SKA') {
            $('#admrp').val(formatCurrency1(20000));
            $('#admrp1').val(formatCurrency1(20000));
          } else if (koders == 'CRB') {
            $('#admrp').val(formatCurrency1(20000));
            $('#admrp1').val(formatCurrency1(20000));
          } else if (koders == 'MKS') {
            $('#admrp').val(formatCurrency1(20000));
            $('#admrp1').val(formatCurrency1(20000));
          } else {
            $('#admrp').val(formatCurrency1(10000));
          }
        } else {
          $('#admrp').val(0);
        }
      }
    });
  }

  function total_net() {
    var adm = $('#admrp').val();
    var tindakan = $('#tindakanrp').val();
    var resep = $('#reseprp_1').val();

    var vadm = Number(adm.replace(/[^0-9\.]+/g, ""));
    var vtindakan = Number(tindakan.replace(/[^0-9\.]+/g, ""));
    var vresep = Number(resep.replace(/[^0-9\.]+/g, ""));

    var vtotalrp = eval(vadm) + eval(vtindakan) + eval(vresep);
    $('#totalrp').val(formatCurrency1(vtotalrp));

    // var totalrp = $('#totalrp').val();
    // var totalsemua = Number(totalrp.replace(/[^0-9\.]+/g, ""));
    // var totalrp_transaksix = $('#totalrp').val();
    var totalrp_transaksix = $('#totalrp_transaksi').val();
    var totalsemua = Number(totalrp_transaksix.replace(/[^0-9\.]+/g, ""));
    var diskonrp = $('#diskonrp').val();
    var diskonresep = $('#diskonresep').val();
    var uangmukarp = $('#uangmuka').val();
    var uangmukapakai = $('#uangmukapakai').val();
    $('#uangmukapakai').val(formatCurrency1(uangmukapakai));
    var refundrp = $('#refund').val();
    var voucherrp1 = $('#voucherrp1').val();
    var voucherrp2 = $('#voucherrp2').val();
    var voucherrp3 = $('#voucherrp3').val();

    var vdiskonrp = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
    var vdiskonresep = Number(diskonresep.replace(/[^0-9\.]+/g, ""));
    var vuangmukarp = Number(uangmukarp.replace(/[^0-9\.]+/g, ""));
    var vuangmukapakai = Number(uangmukapakai.replace(/[^0-9\.]+/g, ""));
    var vrefundrp = Number(refundrp.replace(/[^0-9\.]+/g, ""));

    var vvoucherrp1 = Number(voucherrp1.replace(/[^0-9\.]+/g, ""));
    var vvoucherrp2 = Number(voucherrp2.replace(/[^0-9\.]+/g, ""));
    var vvoucherrp3 = Number(voucherrp3.replace(/[^0-9\.]+/g, ""));

    var countv = eval(vvoucherrp1) + eval(vvoucherrp2) + eval(vvoucherrp3);
    // console.log(countv);

    // var totalnet = eval(totalsemua) - eval(vdiskonrp) - eval(vuangmukapakai) - eval(vrefundrp) - eval(vvoucherrp1) - eval(
    //   vvoucherrp2) - eval(vvoucherrp3) - eval(vdiskonresep);

    // husain tambahan
    var tercover = $("#tercover_rp").val();
    var tercover2 = $("#tercover_rp2").val();
    var totalrpx = $("#totalrp_transaksi").val();
    var totalrp = Number(parseInt(totalrpx.replaceAll(',','')));
    var trp = Number(parseInt(tercover.replaceAll(',','')));
    var trp2 = Number(parseInt(tercover2.replaceAll(',','')));
    total_cover = trp + trp2;
    hasil = totalrp - total_cover;
    // end husain

    // var totalnet = eval(totalsemua) - eval(vdiskonrp) - eval(vuangmukapakai) - eval(vrefundrp) - eval(vvoucherrp1) - eval(vvoucherrp2) - eval(vvoucherrp3);
    // husain change
    var totalnet = eval(hasil) - eval(vuangmukapakai) - eval(vrefundrp) - eval(vvoucherrp1) - eval(vvoucherrp2) - eval(vvoucherrp3);
    // end husain
    var bayarcredit = $('#totalelectronicrp').val();
    var bayartunai = $('#totaltunairp').val();
    $('#totaltunairp').val(formatCurrency1(bayartunai));
    var selisihrp = $('#selisihrp').val();

    var vbayarcredit = Number(bayarcredit.replace(/[^0-9\.]+/g, ""));
    var vbayartunai = Number(bayartunai.replace(/[^0-9\.]+/g, ""));
    var vselisihrp = Number(selisihrp.replace(/[^0-9\.]+/g, ""));

    $('#totalnet').val(formatCurrency1(totalnet));
    var kembali = (eval(vbayarcredit) + eval(vbayartunai) + eval(vselisihrp)) - eval(totalnet);
    $('#kembalirp').val(formatCurrency1(kembali));

    if (vuangmukarp > 0 || vbayartunai != 0 || vbayarcredit != 0) {
      document.getElementById('pertanyaan').style.visibility = 'visible';
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
      document.getElementById('pertanyaan').style.visibility = 'hidden';
      document.getElementById('textya').style.visibility = 'hidden';
      document.getElementById('uangmukakembaliya').style.visibility = 'hidden';
      document.getElementById('uangmukakembalitidak').style.visibility = 'hidden';
      document.getElementById('texttidak').style.visibility = 'hidden';
    }
  }

  function cekisiobat() {
    var nokwitansi = $('[name="nokwitansi"]').val();
    var noreg = $('[name="noreg"]').val();

    $.ajax({
      url: "/kasir_konsul/cek_isiobat/",
      type: "POST",
      dataType: "JSON",
      data: {
        kwi: nokwitansi,
        regg: noreg
      },
      success: function(data) {
        if (data.status == 0) {
          swal({
            title: "KWITANSI OBAT",
            html: "<p>Untuk Kwitansi <br><b>" + nokwitansi + "</b><br> Tidak Ada obat </p>",
            type: "info",
            confirmButtonText: "OK"
          });
        } else {
          _urlcetak2();
        }
      }
    });
  }

  function _urlcetak() {
    var baseurl = "<?php echo base_url() ?>";
    var nokwitansi = $('[name="nokwitansi"]').val();
    var noreg = $('[name="noreg"]').val();

    // script original
    // var var1 = baseurl + 'kasir_konsul/cetak/?kwitansi=' + nokwitansi + '&noreg=' + noreg;

    // husain change
    var var1 = baseurl + 'kasir_konsul/cetak_kwitansi/?kwitansi=' + nokwitansi + '&noreg=' + noreg;
    // end husain
    window.open(var1, '_blank');


  }

  function _urlcetak2() {
    var baseurl = "<?php echo base_url() ?>";
    var nokwitansi = $('[name="nokwitansi"]').val();
    var noreg = $('[name="noreg"]').val();

    var var2 = baseurl + 'kasir_konsul/cetak2/?kwitansi=' + nokwitansi + '&noreg=' + noreg;
    window.open(var2, '_blank');

  }


  window.onload = function() {
    //document.getElementById('btnsimpan').disabled=true;
    document.getElementById('tersimpan').value = "";
    document.getElementById("btncetak_bayar").disabled = true;
    document.getElementById("btncetak_bayar2").disabled = true;
    // tambahan dari saya
    document.getElementById('pertanyaan').style.visibility = 'hidden';
    document.getElementById('uangmukakembaliya').style.visibility = 'hidden';
    document.getElementById('textya').style.visibility = 'hidden';

    document.getElementById('uangmukakembalitidak').style.visibility = 'hidden';
    document.getElementById('texttidak').style.visibility = 'hidden';
  };

  $('.cekpromo').on('change', function() {
    $('.cekpromo').not(this).prop('checked', false);
  });

  $("input[name='kembaliuang']").on('change', function() {
    $("input[name='kembaliuang']").not(this).prop('checked', false);
  });
</script>



</body>

</html>