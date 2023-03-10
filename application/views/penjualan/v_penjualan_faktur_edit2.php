<?php
$this->load->view('template/header');
$this->load->view('template/body');

$datpas   = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$header->rekmed'")->row();

if ($datpas) {
     $age_date = new DateTime($datpas->tgllahir);
     $age_now = new DateTime();
     $age_interval = $age_now->diff($age_date);
} else {
     $age_interval = (object) array(
          "y"  => 0,
          "m"  => 0,
          "d"  => 0,
     );
}
?>
<div class="row">
     <div class="col-md-12">
          <h3 class="page-title">
               <span class="title-unit">
                    &nbsp;<?php echo $this->session->userdata('unit'); ?>
               </span>
               -
               <span class="title-web">APOTEK <small>Penjualan Resep</small>
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
                    <a class="title-white" href="<?php echo base_url(); ?>penjualan_faktur">
                         Daftar Faktur Penjualan
                    </a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
               </li>
               <li>
                    <a class="title-white" href="">
                         Update Faktur
                    </a>
               </li>
          </ul>
     </div>
</div>
<div class="portlet box blue">
     <div class="portlet-title">
          <div class="caption">
               <i class="fa fa-reorder"></i><b>*Data Update</b>
          </div>
     </div>
     <div class="portlet-body form">
          <form id="frmpenjualan" class="form-horizontal" method="post">
               <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Pembeli <font color="red">*</font></label>
                                        <div class="col-md-6">
                                             <select id="pembeli" name="pembeli" class="form-control select2_pembeli" onchange="getdataklinik()" disabled>
                                                  <option <?= ($header->kodepel == 'atr' ? 'selected' : '') ?> value="atr">Apotik Tanpa Resep</option>
                                                  <option <?= ($header->kodepel == 'adr' ? 'selected' : '') ?> value="adr">Apotik Dengan Resep</option>
                                                  <option <?= ($header->kodepel == 'RAJAL' ? 'selected' : '') ?> value="RAJAL">Rawat Jalan</option>
                                                  <option <?= ($header->kodepel == 'RANAP' ? 'selected' : '') ?> value="RANAP">Rawat Inap</option>
                                                  <option <?= ($header->kodepel == 'APOTIK' ? 'selected' : '') ?> value="APOTIK">Apotik</option>
                                             </select>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Resep Dari <font color="red">*</font></label>
                                        <div class="col-md-9">
                                             <input type="text" id="dokter" name="dokter" class="form-control" value="<?= $header->kodokter; ?>" <?= ($header->kodepel == 'adr' ? '' : 'readonly') ?>>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                                        <div class="col-md-6">
                                             <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" readonly disabled>
                                                  <?php $namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan; ?>
                                                  <option selected value="<?= $header->gudang; ?>"><?= $namagudang; ?></option>
                                             </select>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">No. Pembelian <font color="red">*</font></label>
                                        <div class="col-md-6">
                                             <input type="text" id="noresep" name="noresep" class="form-control" readonly value="<?= $header->resepno; ?>">
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                                        <div class="col-md-6">
                                             <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($header->tglresep)); ?>" readonly />
                                        </div>
                                        <div class="col-md-3">
                                             <input type="time" class="form-control" name="jam" id="jam" value="<?= date('H:i:s', strtotime($header->jam)); ?>" readonly>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Alamat Kirim</label>
                                        <div class="col-md-9">
                                        <?php if ($header->rekmed=='Non Member') :?>
                                             <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $header->alamat; ?>" readonly>
                                        <?php else : ?>
                                             <?php $datapasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed)); ?>
                                             <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $datapasien->alamat; ?>" readonly>
                                        <?php endif; ?>
                                        </div>
                                        <!-- <label class="col-md-3 control-label">No Handphone <font color="red">*</font></label>
                                        <div class="col-md-9">
                                             <input type="text" name="phone" id="phone" class="form-control" value="<?= $datapasien->handphone; ?>" readonly>
                                        </div> -->
                                   </div>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group"> 
                                        <label class="col-md-3 control-label">Member</label>
                                        <div class="col-md-9 input-medium">
                                             <?php if ($header->rekmed=='Non Member') :?>
                                                  <input type="text" name="pasien" id="pasien" class="form-control" value="Non Member" readonly>
                                             <?php else : ?>
                                                  <select id="pasien" name="pasien" class="form-control select2_el_pasien" onchange="getinfopasien()" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                       <?php $datapasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed)); ?>
                                                       <option value="<?= $header->rekmed; ?>">
                                                       <?= $header->rekmed . ' | ' . $datapasien->namapas; ?></option>
                                                  </select>
                                             <?php endif; ?>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">No Handphone</label>
                                        <div class="col-md-9">
                                        <?php if ($header->rekmed=='Non Member') :?>
                                             <input type="text" name="phone" id="phone" class="form-control" value="<?= $header->nohp; ?>" readonly>
                                        <?php else : ?>
                                             <?php $datapasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed)); ?>
                                             <input type="text" name="phone" id="phone" class="form-control" value="<?= $datapasien->handphone; ?>" readonly>
                                        <?php endif; ?>
                                             
                                        </div>
                                   </div>
                                   
                              </div>
                         </div>
                         <div class="row">
                              <!-- <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Umur <font color="red">*</font></label>
                                        <div class="col-md-6">
                                             <input type="text" name="umurpas" id="umurpas" class="form-control" value="<?= $age_interval->y . ' Tahun ' . $age_interval->m . ' Bulan ' . $age_interval->d . ' Hari' ?>" readonly>
                                        </div>
                                   </div>
                              </div> -->
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Pembeli</label>
                                        <div class="col-md-6">
                                             <input type="text" name="namapasien" id="namapasien" class="form-control" value="<?= $posting->namapas; ?>">
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                   <label class="col-md-3 control-label">Tgl Lahir</label>
                                   <div class="col-md-4">
                                        <div class="input-group input-small">
                                        <?php if ($header->rekmed=='Non Member') :?>
                                             <input type="date" name="tgllahir" id="tgllahir" class="form-control" onchange="tgllahirr()" value="<?= date('Y-m-d', strtotime($header->tgllahir)); ?>">
                                        <?php else : ?>
                                             <?php $datapasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed)); ?>
                                             <input type="date" name="tgllahir" id="tgllahir" class="form-control" onchange="tgllahirr()" value="<?= date('Y-m-d', strtotime($datapasien->tgllahir)); ?>" readonly>
                                        <?php endif; ?>

                                        </div>
                                   </div>
                                   <div class="col-md-5">
                                        <div class="input-group">
                                        <input id="lumur" name="lumur" type="text" class="form-control" readonly>
                                        </div>
                                   </div>
                                   </div>
                              </div>
                              
                         </div>

                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                   <label class="col-md-3 control-label">Berat Badan </label>
                                   <div class="col-md-9">
                                        <div class="input-group input-medium">

                                             <input type="number" name="bb" id="bb" class="form-control" value="<?= $header->bb; ?>" >

                                        </div>
                                   </div>
                                   

                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="col-md-3 control-label">Jenis Kelamin</label>
                                        <div class="col-md-3">
                                             <select name="jkel" id="jkel" class="form-control">
                                             <?php if ($header->rekmed=='Non Member'){ 
                                                  $jkell=$header->jkel; ?>
                                             <?php }else {?>
                                                  <?php $datapasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed)); 
                                                  $jkell=$datapasien->jkel;
                                                  ?>
                                                 
                                             <?php }; ?>
                                             
                                                  <option <?= ($jkell == 'P' ? 'selected' : '') ?> value="P">Pria</option>
                                                  <option <?= ($jkell == 'W' ? 'selected' : '') ?> value="W">Wanita</option>
                                             </select>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <br>
                         <ul class="nav nav-pills">
                              <li class="active">
                                   <a href="#tab1" data-toggle="tab">
                                        Resep
                                   </a>
                              </li>
                              <li class="">
                                   <a href="#tab2" data-toggle="tab">
                                        Racikan
                                   </a>
                              </li>
                         </ul>
                         <div class="tab-content">
                              <div class="tab-pane active" id="tab1">
                                   <div class="row">
                                        <div class="col-md-12">
                                             <!-- <div class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">     -->
                                             <table id="datatable" class="table table-hover table-striped table-bordered table-condensed table-scrollable" >
                                                          
                                                  <!-- <table id="datatable" class="table table-bordered"  > -->
                                                  <thead class="page-breadcrumb breadcrumb">
                                                       <tr>
                                                            <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                                                            <th class="title-white" width="25%" style="text-align: center">Kode Barang</th>
                                                            <!-- <th class="title-white" width="10%" style="text-align: center">Nama Barang</th> -->
                                                            <th class="title-white" width="15%" style="text-align: center">Qty Jual</th>
                                                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                                                            <th class="title-white" width="10%" style="text-align: center">Harga</th>
                                                            <th class="title-white" width="2%" style="text-align: center">PPN</th>
                                                            <th class="title-white" width="3%" style="text-align: center">Disc. %</th>
                                                            <th class="title-white" width="10%" style="text-align: center">Disc. Rp</th>
                                                            <th class="title-white" width="10%" style="text-align: center">Total Harga</th>
                                                            <th class="title-white" width="5%" style="text-align: center">Keterangan</th>
                                                            <th class="title-white" width="5%" style="text-align: center">Aturan Pakai</th>
                                                            <th class="title-white" width="5%" style="text-align: center">Expired Date</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <?php $no = 1;
                                                       foreach ($detil as $row) :  ?>
                                                            <tr id="resep_no<?= $no ?>">
                                                                 <td>
                                                                      <button type='button' onclick=hapusBarisIni(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button>
                                                                 </td>
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <!-- <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname(this.value, <?= $no; ?>);cekstok(this.value, <?= $no; ?>)"> -->
                                                                           <select name="kode[]" id="kode<?= $no; ?>" style="font-size: 12px;"  class="select2_el_farmasi_baranggud form-control" onchange="showbarangname(this.value, <?= $no; ?>)">
                                                                                <option value="<?= $row->kodebarang; ?>"><?= $row->kodebarang . ' | ' . $row->namabarang1; ?></option>
                                                                           </select>
                                                                           <input name="nama[]" id="nama<?= $no; ?>" type="hidden" class="form-control" onkeypress="return tabE(this,event)" value="<?= $row->namabarang; ?>" readonly>
                                                                      <?php else : ?>
                                                                           <select name="kode[]" id="kode<?= $no; ?>" style="font-size: 12px;"  class="select2_el_farmasi_baranggud form-control" onchange="showbarangname(this.value, <?= $no; ?>)" disabled>
                                                                                <option value="<?= $row->kodebarang; ?>"><?= $row->kodebarang . ' | ' . $row->namabarang1; ?></option>
                                                                           </select>
                                                                           <input name="nama[]" id="nama<?= $no; ?>" type="hidden" class="form-control" onkeypress="return tabE(this,event)" value="<?= $row->namabarang; ?>" readonly>
                                                                      <?php endif; ?>
                                                                 </td>
                                                                 <!-- <td>
                                                                      </td> -->
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <input name="qty[]" onchange="total();cekqty(<?= $no ?>)" value="<?= number_format($row->qty); ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified">
                                                                      <?php else : ?>
                                                                           <input name="qty[]" onchange="total();cekqty(<?= $no ?>)" value="<?= number_format($row->qty); ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                                                                      <?php endif ?>
                                                                 </td>
                                                                 <td>
                                                                      <input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control " onkeypress="return tabE(this,event)" value="<?= $row->satuan; ?>" readonly>
                                                                 </td>
                                                                 <td>
                                                                      <input name="harga[]" onchange="totalline(<?= $no; ?>);" value="<?= number_format($row->price); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                                                                 </td>
                                                                 <td>
                                                                      <input type="checkbox" <?= ($row->ppn == 1 ? 'checked' : '') ?> name="ppn[]" id="ppn<?= $no; ?>" class="form-control" onchange="totalline(<?= $no; ?>);total()" disabled>
                                                                 </td>
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <input name="disc[]" onchange="cekdisc(<?= $no; ?>)" value="<?= $row->discount; ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified ">
                                                                      <?php else : ?>
                                                                           <input name="disc[]" onchange="cekdisc(<?= $no; ?>)" value="<?= $row->discount; ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified " readonly>
                                                                      <?php endif ?>
                                                                 </td>
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <input name="disc2[]" onchange="cekdiscrp(<?= $no; ?>)" value="<?= number_format($row->discrp); ?>" id="disc2<?= $no; ?>" type="text" onkeyup="myFunction(<?= $no; ?>)" class="form-control rightJustified ">
                                                                      <?php else : ?>
                                                                           <input name="disc2[]" onchange="cekdiscrp(<?= $no; ?>)" value="<?= number_format($row->discrp); ?>" id="disc2<?= $no; ?>" type="text" onkeyup="myFunction(<?= $no; ?>)" class="form-control rightJustified " readonly>
                                                                      <?php endif ?>
                                                                 </td>
                                                                 <td>
                                                                      <input name="jumlah[]" id="jumlah<?= $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly value="<?= number_format($row->totalrp); ?>">
                                                                 </td>
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <textarea name="keterangan[]" id="keterangan<?= $no ?>" type="text" class="form-control" style="resize:none" rows="2"><?= $row->ket ?></textarea>
                                                                      <?php else : ?>
                                                                           <textarea name="keterangan[]" id="keterangan<?= $no ?>" type="text" class="form-control" style="resize:none" rows="2" reaonly><?= $row->ket ?></textarea>
                                                                      <?php endif ?>
                                                                 </td>
                                                                 <td>
                                                                      <?php if ($noedit != 1) : ?>
                                                                           <select name="aturan_pakai" id="aturan_pakai<?= $no ?>" style="font-size: 12px;" class="form-control select2_atp">
                                                                                <?php
                                                                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                                foreach ($data as $rows) { ?>
                                                                                     <option <?= ($row->atpakai == $rows->apocode ? 'selected' : '') ?> value="<?= $rows->apocode; ?>"><?= $rows->aponame; ?></option>
                                                                                <?php } ?>
                                                                           </select>
                                                                      <?php else : ?>
                                                                           <select name="aturan_pakai" id="aturan_pakai<?= $no ?>" style="font-size: 12px;" class="form-control select2_atp" disabled>
                                                                                <?php
                                                                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                                foreach ($data as $rows) { ?>
                                                                                     <option <?= ($row->atpakai == $rows->apocode ? 'selected' : '') ?> value="<?= $rows->apocode; ?>"><?= $rows->aponame; ?></option>
                                                                                <?php } ?>
                                                                           </select>
                                                                      <?php endif ?>
                                                                 </td>
                                                                 <td>
                                                                      <input name="expire[]" id="expire<?= $no; ?>" type="date" class="form-control rightJustified" style="width:90%;" value="<?= date('Y-m-d', strtotime($row->exp_date)); ?>">
                                                                 </td>
                                                            </tr>
                                                       <?php $no++;
                                                       endforeach; ?>
                                                  </tbody>
                                             </table>
                                             <!-- </div> -->
                                             <?php if ($noedit != 1) : ?>
                                                  <div class="row">
                                                       <div class="col-xs-9">
                                                            <div class="wells">
                                                                 <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                                                 <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                                            </div>
                                                       </div>
                                                  </div>
                                             <?php endif ?>
                                        </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                        <div class="col-xs-7">
                                             <div class="wells">
                                                  <?php if ($noedit != 1) : ?>
                                                       <button type="button" class="btn blue" onclick="ceksave()"><i class="fa fa-save"></i><b> Reposting</b></button>
                                                  <?php endif ?>
                                                  <a href="<?= base_url('penjualan_faktur') ?>" class="btn btn red">
                                                       <i class="fa fa-undo"></i><b> KEMBALI </b>
                                                  </a>
                                                  <h4>
                                                       <span id="error" style="display:none; color:#F00">Terjadi Kesalahan...</span>
                                                       <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                                                  </h4>
                                             </div>
                                        </div>
                                        <div class="col-xs-7 invoice-block">
                                             <div class="wells">
                                                  <table border="0">
                                                  <tr>
                                                       <td>
                                                            <button type="button" onclick="etiket();" class="btn yellow" >
                                                                 <i class="fa fa-print"></i><b> ETiket</b>
                                                            </button>
                                                            <button type="button" onclick="telaah();" class="btn yellow" >
                                                                 <i class="fa fa-print"></i><b> Telaah</b>
                                                            </button>
                                                            <button type="button" onclick="urlcetak_cr();" class="btn yellow" >
                                                                 <i class="fa fa-print"></i><b> Copy Resep</b>
                                                            </button>
                                                       </td>
                                                  </tr>
                                                  </table>
                                             </div>
                                        </div>
                                        <div class="col-xs-5 invoice-block">
                                             <div class="well">
                                                  <table id="tabeltotal">
                                                       <tr>
                                                            <td width="40%"><strong>SUB TOTAL</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vsubtotal"></span></strong>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td width="40%"><strong>DISKON</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vdiskon" data-type="currency"></span></strong>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td width="40%"><strong>DPP</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vdpp" name="_vdpp"></span></strong>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td width="40%"><strong>PPN</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vppn" name="_vppn"></span></strong>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td width="40%"><strong>TOTAL ONGKOS RACIK</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vracik" name="_vracik"></span></strong>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td width="40%"><strong>TOTAL</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59" align="right">
                                                                 <strong><span id="_vtotal"></span></strong>
                                                            </td>
                                                            <input type="hidden" id="ppn2_" name="ppn2_" value="<?= $ppn['prosentase']; ?>">
                                                       </tr>
                                                       <input type="hidden" id="tersimpan">
                                                  </table>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="tab-pane" id="tab2">
                                   <div class="row">
                                        <div class="col-md-12 form-body">
                                             <table class="table" border="0" width="100%">
                                                  <tr bgcolor="#c7f2ff">
                                                       <td width="10%" class="control-labelh rightJustified">RACIKAN KE</td>
                                                       <td width="20%">
                                                            <select id="cekracik" name="cekracik" class="form-control">
                                                                 <option value="1" selected>1</option>
                                                                 <option value="2">2</option>
                                                                 <option value="3">3</option>
                                                                 <option value="4">4</option>
                                                            </select>
                                                       </td>
                                                       <td colspan="6">&nbsp;</td>
                                                  </tr>
                                             </table>
                                        </div>
                                        <div class="col-md-12">
                                             <div class="portlet box purple" id="racik1">
                                                  <div class="portlet-title">
                                                       <div class="caption">
                                                            <span class="title-white"><b>RACIKAN KE - 1</b></span>
                                                       </div>
                                                  </div>
                                                  <div class="portlet-body form">
                                                       <div class="form-body">
                                                            <table class="table" border="0" width="100%">
                                                                 <?php if ($header_r == null) : ?>
                                                                      <tr>
                                                                           <td colspan="7">&nbsp;</td>
                                                                      </tr>
                                                                      <tr bgcolor="#c7f2ff">
                                                                           <td width="10%" class="control-labelh rightJustified">JENIS</td>
                                                                           <td width="20%" colspan="2">
                                                                                <select id="jenis_1" name="jenis_1" class="form-control">
                                                                                     <?php
                                                                                     if ($header_r) {
                                                                                          $jenisracik = $header_r->jenisracik;
                                                                                     } else {
                                                                                          $jenisracik = 'JRACIK1';
                                                                                     }
                                                                                     $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='JENISRACIK' ")->result();
                                                                                     foreach ($data as $row) { ?>
                                                                                          <option <?= ($jenisracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                     <?php } ?>
                                                                                </select>
                                                                           </td>
                                                                           <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                                                                           <td width="20%">
                                                                                <input type="text" class="form-control " name="namaracik_1" id="namaracik_1" value="" Placeholder="Nama">
                                                                           </td>
                                                                           <td> &nbsp; </td>
                                                                           <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                                                                           <td>
                                                                                <select name="carapakai" id="carapakai" class="form-control">
                                                                                     <?php if ($header_r) {
                                                                                          $carapakai = $header_r->carapakai;
                                                                                     } else {
                                                                                          $carapakai = '';
                                                                                     } ?>
                                                                                     <option value=""> --- PILIH ----</option>
                                                                                     <option <?= ($carapakai == 'DIMINUM' ? 'selected' : '') ?> value="DIMINUM"> DIMINUM </option>
                                                                                     <option <?= ($carapakai == 'DIOLES' ? 'selected' : '') ?> value="DIOLES"> DIOLES </option>
                                                                                     <option <?= ($carapakai == 'DITETES' ? 'selected' : '') ?> value="DITETES"> DITETES </option>
                                                                                </select>
                                                                           </td>
                                                                      </tr>
                                                                      <tr bgcolor="#c7f2ff">
                                                                           <td class="control-labelh rightJustified">JUMLAH</td>
                                                                           <td width="8%">
                                                                                <input type="number" class="form-control " name="jumracik_1" id="jumracik_1" value="">
                                                                           </td>
                                                                           <td width="12%">
                                                                                <select name="stajum_1" id="stajum_1" class="form-control">
                                                                                     <?php
                                                                                     if ($header_r) {
                                                                                          $kemasanracik = $header_r->kemasanracik;
                                                                                     } else {
                                                                                          $kemasanracik = 'KEMAS1';
                                                                                     }
                                                                                     $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                                                                     foreach ($data as $row) { ?>
                                                                                          <option <?= ($kemasanracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                     <?php } ?>
                                                                                </select>
                                                                           </td>
                                                                           <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                                                                           <td>
                                                                                <select name="atpakai_1" id="atpakai_1" class="form-control">
                                                                                     <?php
                                                                                     if ($header_r) {
                                                                                          $aturanpakai = $header_r->aturanpakai;
                                                                                     } else {
                                                                                          $aturanpakai = 'AP1';
                                                                                     }
                                                                                     $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                                     foreach ($data as $row) { ?>
                                                                                          <option <?= ($aturanpakai == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                     <?php } ?>
                                                                                </select>
                                                                           </td>
                                                                           <td>&nbsp;</td>
                                                                           <td class="control-labelh rightJustified" type="hidden" width="15%"></td>
                                                                           <td>&nbsp;</td>
                                                                      </tr>
                                                                      <tr>
                                                                           <td colspan="7">&nbsp;</td>
                                                                      </tr>
                                                                 <?php else : ?>
                                                                      <tr>
                                                                           <td colspan="7">&nbsp;</td>
                                                                      </tr>
                                                                      <tr bgcolor="#c7f2ff">
                                                                           <td width="10%" class="control-labelh rightJustified">JENIS</td>
                                                                           <td width="20%" colspan="2">
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <select id="jenis_1" name="jenis_1" class="form-control">
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='JENISRACIK' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->jenisracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php else : ?>
                                                                                     <select id="jenis_1" name="jenis_1" class="form-control" disabled>
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='JENISRACIK' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->jenisracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php endif ?>
                                                                           </td>
                                                                           <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                                                                           <td width="20%">
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <input type="text" class="form-control " name="namaracik_1" id="namaracik_1" value="<?= $header_r->namaracikan; ?>" Placeholder="Nama">
                                                                                <?php else : ?>
                                                                                     <input type="text" class="form-control " name="namaracik_1" id="namaracik_1" value="<?= $header_r->namaracikan; ?>" Placeholder="Nama" disabled>
                                                                                <?php endif ?>
                                                                           </td>
                                                                           <td> &nbsp; </td>
                                                                           <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                                                                           <td>
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <select name="carapakai" id="carapakai" class="form-control">
                                                                                          <option value=""> --- PILIH ----</option>
                                                                                          <option <?= ($header_r->carapakai == 'DIMINUM' ? 'selected' : '') ?> value="DIMINUM"> DIMINUM </option>
                                                                                          <option <?= ($header_r->carapakai == 'DIOLES' ? 'selected' : '') ?> value="DIOLES"> DIOLES </option>
                                                                                          <option <?= ($header_r->carapakai == 'DITETES' ? 'selected' : '') ?> value="DITETES"> DITETES </option>
                                                                                     </select>
                                                                                <?php else : ?>
                                                                                     <select name="carapakai" id="carapakai" class="form-control" disabled>
                                                                                          <option value=""> --- PILIH ----</option>
                                                                                          <option <?= ($header_r->carapakai == 'DIMINUM' ? 'selected' : '') ?> value="DIMINUM"> DIMINUM </option>
                                                                                          <option <?= ($header_r->carapakai == 'DIOLES' ? 'selected' : '') ?> value="DIOLES"> DIOLES </option>
                                                                                          <option <?= ($header_r->carapakai == 'DITETES' ? 'selected' : '') ?> value="DITETES"> DITETES </option>
                                                                                     </select>
                                                                                <?php endif ?>
                                                                           </td>
                                                                      </tr>
                                                                      <tr bgcolor="#c7f2ff">
                                                                           <td class="control-labelh rightJustified">JUMLAH</td>
                                                                           <td width="8%">
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <input type="number" class="form-control " name="jumracik_1" id="jumracik_1" value="<?= $header_r->jumlahracik; ?>">
                                                                                <?php else : ?>
                                                                                     <input type="number" class="form-control " name="jumracik_1" id="jumracik_1" value="<?= $header_r->jumlahracik; ?>" disabled>
                                                                                <?php endif ?>
                                                                           </td>
                                                                           <td width="12%">
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <select name="stajum_1" id="stajum_1" class="form-control">
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->kemasanracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php else : ?>
                                                                                     <select name="stajum_1" id="stajum_1" class="form-control" disabled>
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->kemasanracik == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php endif ?>
                                                                           </td>
                                                                           <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                                                                           <td>
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <select name="atpakai_1" id="atpakai_1" class="form-control">
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->aturanpakai == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php else : ?>
                                                                                     <select name="atpakai_1" id="atpakai_1" class="form-control" disabled>
                                                                                          <?php
                                                                                          $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                                          foreach ($data as $row) { ?>
                                                                                               <option <?= ($header_r->aturanpakai == $row->apocode ? 'selected' : '') ?> value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                                          <?php } ?>
                                                                                     </select>
                                                                                <?php endif ?>
                                                                           </td>
                                                                           <td>&nbsp;</td>
                                                                           <td class="control-labelh rightJustified" type="hidden" width="15%"></td>
                                                                           <td>&nbsp;</td>
                                                                      </tr>
                                                                      <tr>
                                                                           <td colspan="7">&nbsp;</td>
                                                                      </tr>
                                                                 <?php endif; ?>
                                                            </table>
                                                            <table id="datatableracik" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                                 <thead class="page-breadcrumb breadcrumb">
                                                                      <tr>
                                                                           <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                                                                           <th class="title-white" width="20%" style="text-align: center">Kode Obat</th>
                                                                           <th class="title-white" width="20%" style="text-align: center">Nama Obat</th>
                                                                           <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Qty Racik</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Harga Jual</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Uang R</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Total Harga</th>
                                                                           <th class="title-white" width="10%" style="text-align: center">Expired</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      <?php if ($detil_r == false) : ?>
                                                                           <tr id="racik_no<?= $no ?>">
                                                                                <td>
                                                                                     <button type='button' onclick=hapusBarisIni_1(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button>
                                                                                </td>
                                                                                <td width="20%">
                                                                                     <select name="koderacik[]" id="koderacik1" class="select2_el_farmasi_baranggud form-control input-large" onchange="showbarangnameracik(this.value, 1);">
                                                                                     </select>
                                                                                </td>
                                                                                <td>
                                                                                     <input name="namaracik[]" id="namaracik1" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly>
                                                                                </td>
                                                                                <td>
                                                                                     <input name="satracik[]" id="satracik1" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly>
                                                                                </td>
                                                                                <td>
                                                                                     <input name="qtyjualracik[]" id="qtyjualracik1" onchange="totallineracik(1);totalracik(); cekqtyr(1)" type="text" class="form-control rightJustified" value="1">
                                                                                </td>
                                                                                <td>
                                                                                     <input name="qtyracik[]" id="qtyracik1" onchange="totalracik();totalracik(); cekqtyr(1)" type="text" class="form-control rightJustified" value="1">
                                                                                </td>
                                                                                <td>
                                                                                     <input name="hargaracik[]" onchange="cekhargaracik(1);" id="hargaracik1" type="text" class="form-control rightJustified" readonly value="0">
                                                                                </td>
                                                                                <td>
                                                                                     <input name="uangracik[]" onchange="totallineracik(1);" id="uangracik1" type="text" class="form-control rightJustified" value="0">
                                                                                </td>
                                                                                <td>
                                                                                     <input name="jumlahracik[]" onchange="totallineracik(1);" id="jumlahracik1" type="text" class="form-control rightJustified" readonly value="0">
                                                                                </td>
                                                                                <td>
                                                                                     <input name="exp1[]" id="exp11" type="date" class="form-control rightJustified">
                                                                                </td>
                                                                           </tr>
                                                                      <?php else : ?>
                                                                           <?php $no = 1;
                                                                           foreach ($detil_r as $rows) :
                                                                           ?>
                                                                                <tr id="racik_no<?= $no ?>">
                                                                                     <td>
                                                                                          <button type='button' onclick=hapusBarisIni_1(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button>
                                                                                     </td>
                                                                                     <td width="20%">
                                                                                          <?php if ($noedit != 1) : ?>
                                                                                               <select name="koderacik[]" id="koderacik<?= $no; ?>" class="select2_el_farmasi_baranggud form-control input-large" onchange="showbarangnameracik(this.value, <?= $no; ?>);">
                                                                                                    <option value="<?= $rows->kodebarang; ?>">
                                                                                                         <?= $rows->kodebarang . '|' . $rows->namabarang; ?>
                                                                                                    </option>
                                                                                               </select>
                                                                                          <?php else : ?>
                                                                                               <select name="koderacik[]" id="koderacik<?= $no; ?>" class="select2_el_farmasi_baranggud form-control input-large" onchange="showbarangnameracik(this.value, <?= $no; ?>);" disabled>
                                                                                                    <option value="<?= $rows->kodebarang; ?>">
                                                                                                         <?= $rows->kodebarang . '|' . $rows->namabarang; ?>
                                                                                                    </option>
                                                                                               </select>
                                                                                          <?php endif ?>
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="namaracik[]" id="namaracik<?= $no; ?>" type="text" class="form-control " onkeypress="return tabE(this,event)" value="<?= $rows->namabarang; ?>" readonly>
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="satracik[]" id="satracik<?= $no; ?>" type="text" class="form-control " onkeypress="return tabE(this,event)" value="<?= $rows->satuan ?>" readonly>
                                                                                     </td>
                                                                                     <td>
                                                                                          <?php if ($noedit != 1) : ?>
                                                                                               <input name="qtyjualracik[]" id="qtyjualracik<?= $no; ?>" onchange="totallineracik(<?= $no; ?>);totalracik(); cekqtyr(<?= $no; ?>)" value="<?= $rows->qty ?>" type="text" class="form-control rightJustified">
                                                                                          <?php else : ?>
                                                                                               <input name="qtyjualracik[]" id="qtyjualracik<?= $no; ?>" onchange="totallineracik(<?= $no; ?>);totalracik(); cekqtyr(<?= $no; ?>)" value="<?= $rows->qty ?>" type="text" class="form-control rightJustified" readonly>
                                                                                          <?php endif ?>
                                                                                     </td>
                                                                                     <td>
                                                                                          <?php if ($noedit != 1) : ?>
                                                                                               <input name="qtyracik[]" id="qtyracik<?= $no; ?>" onchange="totalracik(); cekqtyr(<?= $no; ?>)" value="<?= $rows->qtyr ?>" type="text" class="form-control rightJustified">
                                                                                          <?php else : ?>
                                                                                               <input name="qtyracik[]" id="qtyracik<?= $no; ?>" onchange="totalracik(); cekqtyr(<?= $no; ?>)" value="<?= $rows->qtyr ?>" type="text" class="form-control rightJustified" readonly>
                                                                                          <?php endif ?>
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="hargaracik[]" onchange="cekhargaracik(<?= $no; ?>);" value="<?= number_format($rows->price) ?>" id="hargaracik<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="uangracik[]" onchange="totallineracik(<?= $no; ?>);" value="<?= number_format($rows->uangr) ?>" id="uangracik<?= $no; ?>" type="text" class="form-control rightJustified">
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="jumlahracik[]" onchange="totallineracik(<?= $no; ?>);" id="jumlahracik<?= $no; ?>" value="<?= number_format($rows->totalrp) ?>" type="text" class="form-control rightJustified" readonly>
                                                                                     </td>
                                                                                     <td>
                                                                                          <input name="exp1[]" id="exp1<?= $no; ?>" value="<?= date('Y-m-d', strtotime($rows->exp_date)) ?>" type="date" class="form-control rightJustified">
                                                                                     </td>
                                                                                </tr>
                                                                           <?php $no++;
                                                                           endforeach; ?>
                                                                      <?php endif; ?>
                                                                 </tbody>
                                                            </table>
                                                            <table class="table" border="0" width="100%">
                                                                 <?php if ($noedit != 1) : ?>
                                                                      <tr class="wells">
                                                                           <td colspan="2">
                                                                                <?php if ($detil_r == false) : ?>
                                                                                     <button type="button" onclick="tambahracik2()" class="btn green"><i class="fa fa-plus"></i></button>
                                                                                <?php else : ?>
                                                                                     <button type="button" onclick="tambahracik()" class="btn green"><i class="fa fa-plus"></i></button>
                                                                                <?php endif; ?>
                                                                                <!-- <button type="button" onclick="hapusracik()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                                                           </td>
                                                                           <td class="control-labelh leftJustified">TOTAL</td>
                                                                           <td width="6%">&nbsp;</td>
                                                                           <td width="2%">&nbsp;</td>
                                                                           <td width="15%">
                                                                                <?php if ($header_r == true) {
                                                                                     $subtotal = $header_r->subtotal;
                                                                                } else {
                                                                                     $subtotal = 0;
                                                                                } ?>
                                                                                <input type="text" class="form-control rightJustified" name="toto_1" id="toto_1" value="<?= number_format($subtotal) ?>" readonly>
                                                                           </td>
                                                                      </tr>
                                                                 <?php endif ?>
                                                                 <tr>
                                                                      <td width="30%" rowspan="6" class="control-labelh leftJustified">
                                                                           &nbsp;&nbsp;&nbsp;&nbsp;
                                                                           Resep Manual Dari Dokter
                                                                           <?php if ($header_r) {
                                                                                $resep_manual = $header_r->resep_manual;
                                                                           } else {
                                                                                $resep_manual = '';
                                                                           } ?>
                                                                           <?php if ($noedit != 1) : ?>
                                                                                <textarea type="text" class="form-control " name="resman_1" id="resman_1"><?= $resep_manual; ?></textarea><br>
                                                                           <?php else : ?>
                                                                                <textarea type="text" class="form-control " name="resman_1" id="resman_1" readonly><?= $resep_manual; ?></textarea><br>
                                                                           <?php endif ?>
                                                                           <div class="wells">
                                                                                <?php if ($noedit != 1) : ?>
                                                                                     <button id="btnsimpan" type="button" onclick="saveracik()" class="btn blue"><i class="fa fa-save"></i> <b>Posting Racik</b></button>
                                                                                <?php endif ?>
                                                                                <a href="<?= base_url('penjualan_faktur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                                <h4>
                                                                                     <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                                                                                </h4>
                                                                           </div>
                                                                      </td>
                                                                      <td rowspan="6" width="30%">&nbsp;</td>
                                                                 <tr>
                                                                      <td class="control-labelh leftJustified">DISKON</td>
                                                                      <td>
                                                                           <?php if ($racik == true) {
                                                                                $diskon = $racik->diskon;
                                                                           } else {
                                                                                $diskon = 0;
                                                                           } ?>
                                                                           <?php if ($noedit != 1) : ?>
                                                                                <input type="text" class="form-control rightJustified" name="disknom_1" id="disknom_1" value="<?= $diskon ?>" onchange="totalracik()">
                                                                           <?php else : ?>
                                                                                <input type="text" class="form-control rightJustified" name="disknom_1" id="disknom_1" value="<?= $diskon ?>" onchange="totalracik()" readonly>
                                                                           <?php endif ?>
                                                                      </td>
                                                                      <td class="control-labelh leftJustified"><b>%</b></td>
                                                                      <td>
                                                                           <?php if ($racik == true) {
                                                                                $diskonrp = $racik->diskonrp;
                                                                           } else {
                                                                                $diskonrp = 0;
                                                                           } ?>
                                                                           <input type="text" class="form-control rightJustified" name="disk_1" id="disk_1" value="<?= number_format($diskonrp) ?>" readonly>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td class="control-labelh leftJustified">
                                                                           <label for="ppn">PPN</label>
                                                                      </td>
                                                                      <td>
                                                                           <?php
                                                                           if ($header_r == true) {
                                                                                if ($header_r->ppnrp > 0) {
                                                                                     $ppnz = 1;
                                                                                } else {
                                                                                     $ppnz = 0;
                                                                                }
                                                                           ?>
                                                                                <input type="hidden" id="cekpajak" name="cekpajak" value="<?= $ppnz; ?>">
                                                                                <input class='form-control' type="checkbox" <?= ($ppnz == 1 ? 'checked' : '') ?> name="cek_ppn" id="cek_ppn" onchange="cek_ppn2()" disabled>
                                                                           <?php } ?>
                                                                      </td>
                                                                      <td>&nbsp;</td>
                                                                      <td>
                                                                           <?php if ($racik == true) {
                                                                                $ppnrp = $racik->ppnrp;
                                                                           } else {
                                                                                $ppnrp = 0;
                                                                           } ?>
                                                                           <input type="text" class="form-control rightJustified" name="ppn_1" id="ppn_1" value="<?= number_format($ppnrp) ?>" readonly>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                                                                      <td>&nbsp;</td>
                                                                      <td>&nbsp;</td>
                                                                      <td>
                                                                           <?php if ($racik == true) {
                                                                                $ongkosracik = $racik->ongkosracik;
                                                                           } else {
                                                                                $ongkosracik = 0;
                                                                           } ?>
                                                                           <input type="text" class="form-control rightJustified" name="ongra_1" id="ongra_1" value="<?= number_format($ongkosracik) ?>" onchange="totalracik()">
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td class="control-labelh leftJustified">TOTAL+PPN</td>
                                                                      <td>&nbsp;</td>
                                                                      <td>&nbsp;</td>
                                                                      <td>
                                                                           <?php if ($racik == true) {
                                                                                $totalrp = $racik->totalrp;
                                                                           } else {
                                                                                $totalrp = 0;
                                                                           } ?>
                                                                           <input type="text" class="form-control rightJustified" name="totp_1" id="totp_1" value="<?= number_format($totalrp) ?>" readonly>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                                                                      <td width="6%">
                                                                           <?php
                                                                           if ($header_r) {
                                                                                if ($header_r->cek_rm == 1) {
                                                                                     $x = 'checked';
                                                                                } else {
                                                                                     $x = '';
                                                                                }
                                                                           } else {
                                                                                $x = '';
                                                                           }
                                                                           ?>
                                                                           <input type="checkbox" <?= $x; ?> class="form-control" name="t_manual" id="t_manual" onclick="cekmanual()">
                                                                      </td>
                                                                      <td width="2%">
                                                                           &nbsp;
                                                                      </td>
                                                                      <td width="15%">

                                                                           <?php
                                                                           if ($header_r) {
                                                                                if ($header_r->cek_rm == 1) {
                                                                                     $y = '';
                                                                                } else {
                                                                                     $y = 'readonly';
                                                                                }
                                                                           } else {
                                                                                $y = 'readonly';
                                                                           }
                                                                           ?>
                                                                           <?php if ($header_r == true) {
                                                                                $subtotal = number_format($header_r->harga_manual, 0);
                                                                           } else {
                                                                                $subtotal = 0;
                                                                           } ?>
                                                                           <input type="text" class="form-control rightJustified" name="toto_11" id="toto_11" value="<?= $subtotal; ?>" <?= $y; ?> onchange="t_jual_manual()">
                                                                      </td>
                                                                 </tr>
                                                            </table>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </form>
     </div>
</div>

<!-- Modal detail -->
<div class="modal fade" id="modal-detail"  tabindex="-1"role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Daftar Resep</b></h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="text-center title-white">Nama</th>
                            <th class="text-center title-white">Aturan Pakai</th>
                            <th class="text-center title-white">Check</th>
                        </tr>
                    </thead>
                    <tbody id="daftar_resep"> </tbody>
                </table>
            </div>
            <div class="modal-footer">
               <button type="button" id="cetak_etiket" onclick="urlcetak_etiket()" class="btn btn-success"><b>
                    <i class="fa fa-print"></i> CETAK</b>
               </button>
               <button type="button" class="btn red" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</b></button>
            </div>
        </div>

    </div>
</div>
<!-- Modal detail -->

<!-- Modal detail -->
<div class="modal fade" id="modal-telaah"  tabindex="-1"role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Daftar Telaah</b></h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="text-center title-white">No</th>
                            <th class="text-center title-white">Aspek Telaah</th>
                            <th class="text-center title-white">Check</th>
                        </tr>
                    </thead>
                    <tbody id="daftar_telaah"> </tbody>
                </table>
            </div>
            <div class="modal-footer">
               <button type="button" id="cetak_etiket" onclick="urlcetak_telaah()" class="btn btn-success"><b>
                    <i class="fa fa-print"></i> CETAK</b>
               </button>
               <button type="button" class="btn red" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</b></button>
            </div>
        </div>

    </div>
</div>
<!-- Modal detail -->

<?php
$this->load->view('template/footer_tb');
?>

<!-- master -->

<script type="text/javascript">
  $(document).ready(function() {
     tgllahirr(); 
  });

</script>
<script>
     $(window).on("load", function() {
          total();
     });

     $(".select2_atp").select2();

     function t_jual_manual() {
          var x = $("#toto_11").val();
          var xx = Number(parseInt(x.replaceAll(',', '')));
          $("#toto_11").val(separateComma(xx));
          totalracik();
     }

     function cekmanual() {
          if (document.getElementById('t_manual').checked === true) {
               $('#toto_11').attr('readonly', false);
          } else {
               $('#toto_11').attr('readonly', true);
          }
          totalracik();
     }

     function etiket() {
          var noresep       = $('[name="noresep"]').val();
          $.ajax({
               url        : "<?php echo base_url(); ?>Penjualan_faktur/getctk/?noresep=" + noresep,
               type       : "GET",
               dataType   : "JSON",
               success: function(data) {
                    $('#daftar_resep').empty();
                    $.each(data, function(key, value) { 
                         
                    if(value.cetak==1){ 
                         checked = 'checked';   
                    }else{
                         checked = ''; 
                    }                

                    $('#daftar_resep').append("<tr>\
                         <td>"+value.namabarang1+"</td>\
                         <td class='text-center'>"+value.nm_atpakai+"</td>\
                         <td style='text-align: center'><input class='form-control' type='checkbox' id='kd_barang["+value.kodebarang+"]' name='kd_barang["+value.kodebarang+"]' onclick=updt_ctk('"+value.kodebarang+"'); "+checked+"></td>\
                    </tr>");
                    });
               }
          });

          $('#modal-detail').modal('show');
     }

     function telaah() {
          var noresep       = $('[name="noresep"]').val();
          $.ajax({
               url        : "<?php echo base_url(); ?>Penjualan_faktur/get_telaah/?noresep=" + noresep,
               type       : "GET",
               dataType   : "JSON",
               success: function(data) {
                    $('#daftar_telaah').empty();
                    $no = 1;
                    $.each(data, function(key, value) { 
                         
                    if(value.cek==1){ 
                         checked = 'checked';   
                    }else{
                         checked = ''; 
                    }                

                    $('#daftar_telaah').append("<tr>\
                         <td class='text-center'>"+$no+"</td>\
                         <td>"+value.aspek+"</td>\
                         <td style='text-align: center'><input class='form-control' type='checkbox' id='kd_barang["+value.kode+"]' name='kd_barang["+value.kode+"]' onclick=updt_telaah('"+value.kode+"'); "+checked+"></td>\
                    </tr>");
                    $no++;
                    });
               }
          });

          $('#modal-telaah').modal('show');
     }
     
     function updt_ctk(kd) 
     {
          var baseurl   = "<?php echo base_url() ?>";
          var noresep   = $('[name="noresep"]').val();
          if (document.getElementById('kd_barang['+kd+']').checked == true) {
               stat = 1;
          }else{
               stat = 0;

          }
          $.ajax({
               url        : "<?php echo base_url(); ?>Penjualan_faktur/updt_ctk/?kd=" + kd+ "&resep=" + noresep+ "&stat=" + stat,
               type       : "GET",
               dataType   : "JSON",
               success: function(data) {
                    
               }
          });
     }
     
     function updt_telaah(kd) 
     {
          var baseurl   = "<?php echo base_url() ?>";
          var noresep   = $('[name="noresep"]').val();
          if (document.getElementById('kd_barang['+kd+']').checked == true) {
               stat = 1;
          }else{
               stat = 0;

          }
          $.ajax({
               url        : "<?php echo base_url(); ?>Penjualan_faktur/updt_telaah/?kd=" + kd+ "&resep=" + noresep+ "&stat=" + stat,
               type       : "GET",
               dataType   : "JSON",
               success: function(data) {
                    
               }
          });
     }

     function urlcetak_etiket() 
     {
          var baseurl       = "<?php echo base_url() ?>";
          var noresep       = $('[name="noresep"]').val();
          $('#modal-detail').modal('hide');
          var ctk           = baseurl + 'penjualan_faktur/ctk_etiket/?resep=' + noresep;
          window.open(ctk,'_blank');
     }

     function urlcetak_telaah() 
     {
          var baseurl       = "<?php echo base_url() ?>";
          var noresep       = $('[name="noresep"]').val();
          $('#modal-detail').modal('hide');
          var ctk           = baseurl + 'penjualan_faktur/ctk_telaah/?resep=' + noresep;
          window.open(ctk,'_blank');
     }

     function urlcetak_cr() 
     {
          var baseurl       = "<?php echo base_url() ?>";
          var noresep       = $('[name="noresep"]').val();
          $('#modal-detail').modal('hide');
          var ctk           = baseurl + 'penjualan_faktur/ctk_cr/?resep=' + noresep;
          window.open(ctk,'_blank');
     }

     var cekppn = $('#ppn2_').val();
     var ppn = cekppn / 100;

     $('.select2_pembeli').select2();

     function getinfopasien() {
          var xhttp;
          var vid = $('#pasien').val();
          $.ajax({
               url: "<?php echo base_url(); ?>pasien/getinfopasien/?id=" + vid,
               type: "GET",
               dataType: "JSON",
               success: function(data) {
                    $('#namapasien').val(data.namapas);
                    $('#alamat').val(data.alamat);
                    $('#phone').val(data.phone);
                    var umur = hitung_usia(data.tgllahir);
                    $('#lumur').val(umur);
               }
          });
     }

     function tgllahirr() {
          var birthDate = new Date($('#tgllahir').val());
          var usia = hitung_usia(birthDate);
          $('#lumur').val(usia);
     }

     function separateComma(val) {
          var sign = 1;
          if (val < 0) {
               sign = -1;
               val = -val;
          }
          let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
          let len = num.toString().length;
          let result = '';
          let count = 1;
          for (let i = len - 1; i >= 0; i--) {
               result = num.toString()[i] + result;
               if (count % 3 === 0 && count !== 0 && i !== 0) {
                    result = ',' + result;
               }
               count++;
          }
          if (val.toString().includes('.')) {
               result = result + '.' + val.toString().split('.')[1];
          }
          return sign < 0 ? '-' + result : result;
     }

     function myFunction(id) {
          var table = document.getElementById('datatable');
          var row = table.rows[id];
          var x = row.cells[6].children[0].value.replace(/[^0-9\.]+/g, "");
          x.value = separateComma(x);
     }

     function hapusBarisIni(param) {
          $("#resep_no" + param).remove();
          total();
     }
</script>

<!-- resep -->
<script>
     var idrow = "<?= $jumdata + 1; ?>";
     var rowCount;
     var arr = [1];

     function tambah() {
          var gud = $('[name="gudang"]').val();
          var table = $("#datatable");

          table.append("<tr id='resep_no" + idrow + "'>" +
               "<td><button id='btnhapus" + idrow + "' type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
               "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_baranggud form-control input-largex' ></select> <input name='nama[]' id='nama" + idrow + "' type='hidden' class='form-control' value='' onchange='totalline(" + idrow + ")' readonly></td>" +
               // "<td></td>" +
               "<td><input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow + ");cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  ></td>" +
               "<td><input name='sat[]' id=sat" + idrow + " type='text' class='form-control' readonly></td>" +
               "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified' readonly></td>" +
               "<td><input type='checkbox' name='ppn[]'  checked  id=ppn" + idrow + " onchange='totalline(" + idrow + ")' class='form-control' disabled ></td>" +
               "<td><input name='disc[]' id=disc" + idrow + " onchange='totalline(" + idrow + ");cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
               "<td><input name='disc2[]' id=disc2" + idrow + " onkeyup='myFunction(" + idrow + ")' onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
               "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly></td>" +
               
               "<td><textarea name='keterangan[]' id='keterangan" + idrow + "' type='text' class='form-control' style='resize:none' rows='2'></textarea></td>" +
               "<td><select name='aturan_pakai[]' id=aturan_pakai" + idrow + " class='select2_atp form-control input-largex' ></select></td>"+
               
               "<td><input name='expire[]'  id=expire" + idrow + " value=''  type='date' class='form-control' style='width:90%;'></td>"+
               "</tr>");

          initailizeSelect2_farmasi_baranggud(gud);
          initailizeselect2_atp();
          idrow++;
     }

     // function tambah() {
     //      var x = document.getElementById('datatable').insertRow(idrow);
     //      var td1 = x.insertCell(0);
     //      var td2 = x.insertCell(1);
     //      var td3 = x.insertCell(2);
     //      var td4 = x.insertCell(3);
     //      var td5 = x.insertCell(4);
     //      var td6 = x.insertCell(5);
     //      var td7 = x.insertCell(6);
     //      var td8 = x.insertCell(7);
     //      var td9 = x.insertCell(8);
     //      var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ");cekstok(this.value, " + idrow + ")' class='select2_el_farmasi_baranggud form-control input-largex' ></select>";
     //      var nama = "<input name='nama[]' id='nama" + idrow + "' type='text' class='form-control' value='' onchange='totalline(" + idrow + ")' readonly>";
     //      var qty = "<input name='qty[]' id=qty" + idrow + " onchange='cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
     //      var sat = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly>";
     //      var hrg = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified' readonly>";
     //      var ppn = "<input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totalline(" + idrow + ")' class='form-control' disabled >";
     //      var disc = "<input name='disc[]' id=disc" + idrow + " onchange='cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
     //      var disc2 = "<input name='disc2[]' id=disc2" + idrow + " onkeyup='myFunction(" + idrow + ")' onchange='cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
     //      var jum = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";
     //      td1.innerHTML = akun;
     //      td2.innerHTML = nama;
     //      td3.innerHTML = qty;
     //      td4.innerHTML = sat;
     //      td5.innerHTML = hrg;
     //      td6.innerHTML = ppn;
     //      td7.innerHTML = disc;
     //      td8.innerHTML = disc2;
     //      td9.innerHTML = jum;
     //      var gud = $('[name="gudang"]').val();
     //      initailizeSelect2_farmasi_baranggud(gud);
     //      idrow++;
     // }

     function hapus() {
          if (idrow > 2) {
               var x = document.getElementById('datatable').deleteRow(idrow - 1);
               idrow--;
               total();
          }
     }

     function hapusBarisIni_1(param) {
          $("#racik_no" + param).remove();

          totalracik();
     }

     function showbarangname(str, id) {
          var xhttp;
          var vid = id;
          $('#sat' + vid).val('');
          $('#harga' + vid).val(0);
          var customer = $('#cust').val();
          $.ajax({
               url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
               type: "GET",
               dataType: "JSON",
               success: function(data) {
                    $('#sat' + vid).val(data.satuan1);
                    $('#nama' + vid).val(data.namabarang);
                    $('#harga' + vid).val(separateComma(data.hargajual));
                    totalline(vid);
               }
          });
     }

     function cekstok(str, id) {
          var gudang = $('#gudang').val();
          var xhttp;
          var vid = id;
          var kode = $("#kode" + id).val();
          var customer = $('#cust').val();
          $.ajax({
               url: "<?php echo base_url(); ?>Penjualan_faktur/cekstok?kode=" + kode + '&gudang=' + gudang,
               type: "POST",
               dataType: "JSON",
               success: function(data) {
                    if (data.status == 2) {
                         swal({
                              title: "PENJUALAN",
                              html: "<p>Barang tidak tersedia</p>",
                              type: "error",
                              confirmButtonText: "OK"
                         });
                    } else {
                         if (data <= '0') {
                              swal({
                                   title: "PENJUALAN",
                                   html: "<p>Stok barang kurang</p>",
                                   type: "error",
                                   confirmButtonText: "OK"
                              });
                              $('#kode' + vid).val('');
                              $('#qty' + vid).val('1');
                              $('#sat' + vid).val('');
                              $('#nama' + vid).val('');
                              $('#harga' + vid).val('');
                              $('#jumlah' + vid).val('');
                              totalline(vid);
                         }
                    }
               }
          });
     }

     function cekqty(id) {
          var qtyx = $('#qty' + id).val();
          var qty = Number(parseInt(qtyx.replaceAll(',', '')));
          var hargax = $('#harga' + id).val();
          var harga = Number(parseInt(hargax.replaceAll(',', '')));
          var disc = $('#disc' + id).val();
          if (disc == 0) {
               var discrpx = $('#disc2' + id).val();
               var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
               jumlah = (qty * harga) - discrp;
          } else {
               discrp = qty * harga * disc / 100;
               $('#disc2' + id).val(separateComma(discrp));
               jumlah = (qty * harga) - discrp;
          }
          $('#jumlah' + id).val(separateComma(jumlah));
          total();
     }

     function cekdisc(id) {
          var qtyx = $('#qty' + id).val();
          var qty = Number(parseInt(qtyx.replaceAll(',', '')));
          var hargax = $('#harga' + id).val();
          var harga = Number(parseInt(hargax.replaceAll(',', '')));
          var discx = $('#disc' + id).val();
          if (discx >= 100) {
               swal({
                    title: "DISKON %",
                    html: "<p>MAKSIMAL 100%</p>",
                    type: "error",
                    confirmButtonText: "OK"
               }).then((value) => {
                    $('#disc' + id).val(0);
                    var discrp = 0;
                    $('#disc2' + id).val(discrp);
                    totalx = (qty * harga) - discrp;
                    $('#jumlah' + id).val(separateComma(totalx));
                    total();
               });
          } else {
               var disc = discx;
               discrp = qty * harga * disc / 100;
               $('#disc2' + id).val(separateComma(discrp));
               var discrpx = $('#disc2' + id).val();
               var discrpxx = Number(parseInt(discrpx.replaceAll(',', '')));
               totalx = (qty * harga) - discrpxx;
               $('#jumlah' + id).val(separateComma(totalx));
               total();
          }
     }

     function cekdiscrp(id) {
          $('#disc' + id).val('0');
          var qtyx = $('#qty' + id).val();
          var qty = Number(parseInt(qtyx.replaceAll(',', '')));
          var hargax = $('#harga' + id).val();
          var harga = Number(parseInt(hargax.replaceAll(',', '')));
          var discrpx = $('#disc2' + id).val();
          var discrpxx = Number(parseInt(discrpx.replaceAll(',', '')));
          $('#disc2' + id).val(separateComma(discrpxx));
          totalx = (qty * harga) - discrpxx;
          $('#jumlah' + id).val(separateComma(totalx));
          total();
     }

     function totalline(id) {
          var hargax = $('#harga' + id).val();
          var harga = Number(parseInt(hargax.replaceAll(',', '')));
          var qtyx = $('#qty' + id).val();
          var qty = Number(parseInt(qtyx.replaceAll(',', '')));
          $('#qty' + id).val(separateComma(qty));
          var discrpx = $('#disc2' + id).val();
          var discrpxx = Number(parseInt(discrpx.replaceAll(',', '')));
          totalx = (qty * harga) - discrpxx;
          $('#jumlah' + id).val(separateComma(totalx));
          total();
     }

     function total() {
          var table       = document.getElementById('datatable');
          var rowCount    = table.rows.length;
          tjumlah         = 0;
          tdiskon         = 0;
          tppn            = 0;
          // ongkir22 = 0;
          for (var i = 1; i < rowCount; i++) {
               var row            = table.rows[i];
               jumlah             = row.cells[2].children[0].value;
               harga              = row.cells[4].children[0].value;
               diskon             = row.cells[7].children[0].value;
               var jumlah1        = Number(jumlah.replace(/[^0-9\.]+/g, ""));
               var harga1         = Number(harga.replace(/[^0-9\.]+/g, ""));
               var diskon1        = Number(diskon.replace(/[^0-9\.]+/g, ""));
               var subtot         = jumlah1 * harga1;
               tjumlah            += subtot;
               diskon             = eval(diskon1);
               tdiskon            += diskon;
               var ongkosracik    = $('#totp_1').val();
               var ppn2           = (tjumlah - tdiskon) * ppn;
               var totalracik     = Number(parseInt(ongkosracik.replaceAll(',', '')));
               if (ongkosracik == '' || ongkosracik == null || ongkosracik == 0) {
                    totalracik_1 = 0;
               } else {
                    totalracik_1 = totalracik;
               }
               // <?php if($header) : ?>
               //      <?php if ($header->ongkoskirim > 0) : ?>
               //           var ongkirx = $("#ongkir").val();
               //           var ongkir = Number(parseInt(ongkirx.replaceAll(',', '')));
               //      <?php else : ?>
               //           var ongkir = 0;
               //      <?php endif; ?>
               // <?php else : ?>
               //      var ongkir = 0;
               // <?php endif; ?>

               // ongkir22 += ongkir;
               
               // var total = tjumlah-tdiskon + ppn2;
          }
          var dpp_done    = (tjumlah - tdiskon) / (111 / 100);
          var ppn_done    = dpp_done * ppn;
          var total       = tjumlah - tdiskon;
          var totals      = total;
          if (document.getElementById('t_manual').checked == true) {
               var h_manual = 1;
               var totalx = $('#toto_11').val();
          } else {
               var totalx = $('#totp_1').val();
               var h_manual = 0;
          }
          var totalxx = Number(parseInt(totalx.replaceAll(',', '')));
          // $("#v_ongkir").val(separateComma(ongkir));
          // if(pembeli == 'KULIT' || pembeli == 'ONLINE'){
          //      document.getElementById("_vongkir").innerHTML = separateComma(ongkir.toFixed(0));
          // }
          document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
          document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
          // document.getElementById("_vracik").innerHTML = ongkosracik;
          document.getElementById("_vracik").innerHTML = separateComma(totalxx.toFixed(0));
          document.getElementById("_vdpp").innerHTML = separateComma(dpp_done.toFixed(0));
          document.getElementById("_vppn").innerHTML = separateComma(ppn_done.toFixed(0));
          document.getElementById("_vtotal").innerHTML = separateComma((totals + totalxx).toFixed(0));
          if ((totals + totalxx) > 0) {
               document.getElementById("btnsimpan").disabled = false;
          } else {
               document.getElementById("btnsimpan").disabled = true;
          }
     }
</script>

<!-- racik -->
<script>
     var idrow2 = "<?= $dracikan + 1; ?>";
     var idrow2x = 2;
     var rowCount;
     var arr = [1];

     function tambahracik() {
          var gud = $('[name="gudang"]').val();
          var table = $("#datatableracik");

          table.append("<tr id='racik_no" + idrow2 + "'>" +
               "<td><button id='btnhapus" + idrow2 + "' type='button' onclick='hapusBarisIni_1(" + idrow2 + ")' class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
               "<td><select name='koderacik[]' id='koderacik" + idrow2 + "' class='select2_el_farmasi_baranggud form-control input-largex' onchange='showbarangnameracik(this.value," + idrow2 + ")'></select></td>" +
               "<td><input name='namaracik[]' id='namaracik" + idrow2 + "' type='text' class='form-control' readonly></td>" +
               "<td><input name='satracik[]' id='satracik" + idrow2 + "' type='text' class='form-control '  onkeypress='return tabE(this,event)' readonly></td>" +
               "<td><input name='qtyjualracik[]' id='qtyjualracik" + idrow2 + "' onchange='totallineracik(" + idrow2 + ");totalracik(); cekqtyr("+idrow2+")' value='1' type='text' class='form-control rightJustified'></td>" +
               "<td><input name='qtyracik[]' id='qtyracik" + idrow2 + "' value='1' type='text' class='form-control rightJustified' onchange='cekqtyr("+idrow2+")'></td>" +
               "<td><input name='hargaracik[]' id='hargaracik" + idrow2 + "' onchange='cekhargaracik(" + idrow2 + ");' value='0'  type='text' class='form-control rightJustified'  readonly></td>" +
               "<td><input name='uangracik[]' id='uangracik" + idrow2 + "' onchange='totallineracik(" + idrow2 + ");' value='0'  type='text' class='form-control rightJustified'></td>" +
               "<td><input name='jumlahracik[]' id='jumlahracik" + idrow2 + "' value='0' type='text' class='form-control rightJustified' readonly></td>" +
               "<td><input name='exp1[]' id='exp1"+idrow2+"' type='date' class='form-control rightJustified'></td>" +
               "</tr>");
          initailizeSelect2_farmasi_baranggud(gud);
          idrow2++;
     }

     function cekqtyr(id) {
    var qtyrx = $("#qtyracik"+id).val();
    // var qtyr = Number(parseInt(qtyrx.replaceAll(',','')));
    // $("#qty_jual"+id+"_1").val(Math.ceil(qtyrx));
    var qtyjx = $("#qtyjualracik"+id+"").val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qtyracik"+id).val(qtyj);
      totallineracik(id);
    }
  }

     // function tambahracik() {
     //      var x = document.getElementById('datatableracik').insertRow(idrow2);
     //      var td1 = x.insertCell(0);
     //      var td2 = x.insertCell(1);
     //      var td3 = x.insertCell(2);
     //      var td4 = x.insertCell(3);
     //      var td5 = x.insertCell(4);
     //      var td6 = x.insertCell(5);
     //      var td7 = x.insertCell(6);
     //      var td8 = x.insertCell(7);
     //      var akun = "<select name='koderacik[]' id='koderacik" + idrow2 + "' class='select2_el_farmasi_baranggud form-control input-largex' onchange='showbarangnameracik(this.value," + idrow2 + ")'></select>";
     //      var nama = "<input name='namaracik[]' id='namaracik" + idrow2 + "' type='text' class='form-control' readonly>";
     //      var satqty = "<input name='satracik[]' id='satracik" + idrow2 + "' type='text' class='form-control '  onkeypress='return tabE(this,event)' readonly>";
     //      var qtyr = "<input name='qtyracik[]' id='qtyracik" + idrow2 + "' value='1' type='text' class='form-control rightJustified' >";
     //      var qtyj = "<input name='qtyjualracik[]' id='qtyjualracik" + idrow2 + "' onchange='totallineracik(" + idrow2 + ");totalracik()' value='1' type='text' class='form-control rightJustified'>";
     //      var hrg = "<input name='hargaracik[]' id='hargaracik" + idrow2 + "' onchange='cekhargaracik(" + idrow2 + ");' value='0'  type='text' class='form-control rightJustified'  readonly>";
     //      var uangr = "<input name='uangracik[]' id='uangracik" + idrow2 + "' onchange='totallineracik(" + idrow2 + ");' value='0'  type='text' class='form-control rightJustified' readonly>";
     //      var jum = "<input name='jumlahracik[]' id='jumlahracik" + idrow2 + "' value='0' type='text' class='form-control rightJustified' readonly>";
     //      td1.innerHTML = akun;
     //      td2.innerHTML = nama;
     //      td3.innerHTML = satqty;
     //      td4.innerHTML = qtyr;
     //      td5.innerHTML = qtyj;
     //      td6.innerHTML = hrg;
     //      td7.innerHTML = uangr;
     //      td8.innerHTML = jum;
     //      var gud = $('[name="gudang"]').val();
     //      initailizeSelect2_farmasi_baranggud(gud);
     //      idrow2++;
     // }

     function tambahracik2() {
          var gud = $('[name="gudang"]').val();
          var table = $("#datatableracik");

          table.append("<tr id='racik_tr" + idrow2x + "'>" +
               "<td><button id='btnhapus" + idrow2x + "' type='button' onclick='hapusBarisIni_1(" + idrow2x + ")' class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
               "<td><select name='koderacik[]' id='koderacik" + idrow2x + "' class='select2_el_farmasi_baranggud form-control input-largex' onchange='showbarangnameracik(this.value," + idrow2x + ")'></select></td>" +
               "<td><input name='namaracik[]' id='namaracik" + idrow2x + "' type='text' class='form-control' readonly></td>" +
               "<td><input name='satracik[]' id='satracik" + idrow2x + "' type='text' class='form-control '  onkeypress='return tabE(this,event)' readonly></td>" +
               "<td><input name='qtyjualracik[]' id='qtyjualracik" + idrow2x + "' onchange='totallineracik(" + idrow2x + ");totalracik()' value='1' type='text' class='form-control rightJustified'></td>" +
               "<td><input name='qtyracik[]' id='qtyracik" + idrow2x + "' value='1' type='text' class='form-control rightJustified' ></td>" +
               "<td><input name='hargaracik[]' id='hargaracik" + idrow2x + "' onchange='cekhargaracik(" + idrow2x + ");' value='0'  type='text' class='form-control rightJustified'  readonly></td>" +
               "<td><input name='uangracik[]' id='uangracik" + idrow2x + "' onchange='totallineracik(" + idrow2x + ");' value='0'  type='text' class='form-control rightJustified'></td>" +
               "<td><input name='jumlahracik[]' id='jumlahracik" + idrow2x + "' value='0' type='text' class='form-control rightJustified' readonly></td>" +
               "<td><input name='exp1[]' id='exp1"+idrow2x+"' value='' type='date' class='form-control rightJustified'></td>"+
               "</tr>");
          initailizeSelect2_farmasi_baranggud(gud);
          idrow2x++;
     }

     // function tambahracik2() {
     //      var x = document.getElementById('datatableracik').insertRow(idrow2x);
     //      var td1 = x.insertCell(0);
     //      var td2 = x.insertCell(1);
     //      var td3 = x.insertCell(2);
     //      var td4 = x.insertCell(3);
     //      var td5 = x.insertCell(4);
     //      var td6 = x.insertCell(5);
     //      var td7 = x.insertCell(6);
     //      var td8 = x.insertCell(7);
     //      var akun = "<select name='koderacik[]' id='koderacik" + idrow2x + "' class='select2_el_farmasi_baranggud form-control input-largex' onchange='showbarangnameracik(this.value," + idrow2x + ")'></select>";
     //      var nama = "<input name='namaracik[]' id='namaracik" + idrow2x + "' type='text' class='form-control' readonly>";
     //      var satqty = "<input name='satracik[]' id='satracik" + idrow2x + "' type='text' class='form-control '  onkeypress='return tabE(this,event)' readonly>";
     //      var qtyr = "<input name='qtyracik[]' id='qtyracik" + idrow2x + "' value='1' type='text' class='form-control rightJustified' >";
     //      var qtyj = "<input name='qtyjualracik[]' id='qtyjualracik" + idrow2x + "' onchange='totallineracik(" + idrow2x + ");totalracik()' value='1' type='text' class='form-control rightJustified'>";
     //      var hrg = "<input name='hargaracik[]' id='hargaracik" + idrow2x + "' onchange='cekhargaracik(" + idrow2x + ");' value='0'  type='text' class='form-control rightJustified'  readonly>";
     //      var uangr = "<input name='uangracik[]' id='uangracik" + idrow2x + "' onchange='totallineracik(" + idrow2x + ");' value='0'  type='text' class='form-control rightJustified' readonly>";
     //      var jum = "<input name='jumlahracik[]' id='jumlahracik" + idrow2x + "' value='0' type='text' class='form-control rightJustified' readonly>";
     //      td1.innerHTML = akun;
     //      td2.innerHTML = nama;
     //      td3.innerHTML = satqty;
     //      td4.innerHTML = qtyr;
     //      td5.innerHTML = qtyj;
     //      td6.innerHTML = hrg;
     //      td7.innerHTML = uangr;
     //      td8.innerHTML = jum;
     //      var gud = $('[name="gudang"]').val();
     //      initailizeSelect2_farmasi_baranggud(gud);
     //      idrow2x++;
     // }

     function hapusracik() {
          if (idrow2 > 2) {
               var x = document.getElementById('datatableracik').deleteRow(idrow2 - 1);
               idrow2--;
          }
     }

     function showbarangnameracik(str, id) {
          var xhttp;
          var vid = id;
          var customer = $('#cust').val();
          $('#satracik' + vid).val('');
          $('#namaracik' + vid).val('');
          $('#hargaracik' + vid).val(0);
          $.ajax({
               url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
               type: "GET",
               dataType: "JSON",
               success: function(data) {
                    $('#satracik' + vid).val(data.satuan1);
                    $('#namaracik' + vid).val(data.namabarang.trim());
                    $('#hargaracik' + vid).val(separateComma(data.hargajual));
                    $('#qtyracik' + vid).val(1);
                    $('#qtyjualracik' + vid).val(1);
                    $('#uangracik' + vid).val(0);
                    totallineracik(vid);
               }
          });
     }

     function totallineracik(id) {
          var hargax = $('#hargaracik' + id).val();
          var harga = Number(parseInt(hargax.replaceAll(',', '')));
          var qtyx = $('#qtyjualracik' + id).val();
          var qty = Number(parseInt(qtyx.replaceAll(',', '')));
          $('#qtyjualracik' + id).val(separateComma(qty));
          var uangracikx = $('#uangracik' + id).val();
          var uangracik = Number(parseInt(uangracikx.replaceAll(',', '')));
          totalx = (qty * harga) + uangracik;
          $('#uangracik' + id).val(separateComma(uangracik));
          $('#jumlahracik' + id).val(separateComma(totalx));
          totalracik();
     }

     function cek_ppn2() {
          $.ajax({
               url: '<?php echo base_url(); ?>farmasi_bapb/cekppn',
               type: "GET",
               dataType: "json",
               success: function(data) {
                    cekppn = data.prosentase;
                    cekppn2 = cekppn / 100;
                    totalracik();
               }
          });
     }

     $('#disknom_1').keyup(function() {
          totalracik();
     });

     function totalracik() {
          var table       = document.getElementById('datatableracik');
          var rowCount    = table.rows.length;
          tjumlah         = 0;
          tdiskon         = 0;
          for (var i = 1; i < rowCount; i++) {
               var row        = table.rows[i];
               jumlah         = row.cells[4].children[0].value;
               harga          = row.cells[6].children[0].value;
               uangracik      = row.cells[7].children[0].value;
               var jumlah1    = Number(jumlah.replace(/[^0-9\.]+/g, ""));
               var harga1     = Number(harga.replace(/[^0-9\.]+/g, ""));
               var uangracik1 = Number(uangracik.replace(/[^0-9\.]+/g, ""));
               var subtot     = jumlah1 * harga1 + uangracik1;
               tjumlah        += subtot;
          }
          var diskonx   = $('#disknom_1').val();
          diskon        = tjumlah * diskonx / 100;
          var cekpajak  = $('#cekpajak').val();
          if (cekpajak != 0) {
               var tppn = (tjumlah - diskon) * ppn;
          } else {
               var tppn = 0;
          }
          var dpp_done        = (tjumlah - diskon) / (111 / 100);
          var ppn_done        = dpp_done * ppn;
          $('#disk_1').val(separateComma(diskon));
          var ongkosracikx    = $('#ongra_1').val();
          var ongkosracik     = Number(parseInt(ongkosracikx.replaceAll(',', '')));
          var totalracikan    = tjumlah - diskon + ongkosracik;
          document.getElementById("toto_1").value = separateComma(tjumlah.toFixed(0));
          document.getElementById("ongra_1").value = separateComma(ongkosracik.toFixed(0));
          document.getElementById("ppn_1").value = separateComma(ppn_done.toFixed(0));
          document.getElementById("totp_1").value = separateComma(totalracikan.toFixed(0));
          document.getElementById("_vracik").innerHTML = separateComma(totalracikan.toFixed(0));
          total();
          if (totalracikan > 0) {
               document.getElementById("btnsimpan").disabled = false;
          } else {
               document.getElementById("btnsimpan").disabled = true;
          }
     }
</script>

<!-- update fungsi -->
<script>
     function ceksave() {
          swal({
               title: 'UBAH RACIKAN',
               text: "Apa ingin merubah racikan juga ?",
               type: 'info',
               showCancelButton: true,
               confirmButtonClass: 'btn btn-success',
               cancelButtonClass: 'btn btn-success',
               confirmButtonColor: '#227dff',
               cancelButtonColor: '#d33',
               confirmButtonText: 'UBAH DATA RACIKAN',
               cancelButtonText: 'TIDAK',
          }).then(function() {
               bayar();
          }, function(dismiss) {
               if (dismiss === 'cancel') {
                    saveresep();
               }
          })
     }

     function saveracik() {
          var x                       = document.getElementById('datatableracik').insertRow(idrow2);
          var rowCount                = idrow2;
          var gudang                  = $('[name="gudang"]').val();
          var jenis_racikan           = $('[name="jenis_1"]').val();
          var nama_racikan            = $('[name="namaracik_1"]').val();
          var jumlah_racikan          = $('[name="jumracik_1"]').val();
          var satuan_jumlah_racikan   = $('[name="stajum_1"]').val();
          var aturan_pakai_racikan    = $('[name="atpakai_1"]').val();
          var cara_pakai_racikan      = $('[name="carapakai"]').val();
          var resepmanual             = $('[name="resman_1"]').val();
          var nobukti                 = $('#noresep').val();

          if (document.getElementById('t_manual').checked == true) {
               var h_manual = 1;
               var totalx = $('#toto_11').val();
          } else {
               var totalx = $('#_vtotal').val();
               var h_manual = 0;
          }
          var total = Number(parseInt(totalx.replaceAll(',', '')));
          if (jenis_racikan == '') {
               swal({
                    title: "JENIS RACIKAN",
                    html: "<p>Masih kosong</p>",
                    type: "error",
                    confirmButtonText: "OK"
               });
               return;
          }
          if (nama_racikan == '') {
               swal({
                    title: "NAMA RACIKAN",
                    html: "<p>Masih kosong</p>",
                    type: "error",
                    confirmButtonText: "OK"
               });
               return;
          }
          if (jumlah_racikan == '') {
               swal({
                    title: "JUMLAH SATUAN",
                    html: "<p>Masih kosong</p>",
                    type: "error",
                    confirmButtonText: "OK"
               });
               return;
          }
          if (satuan_jumlah_racikan == '') {
               swal({
                    title: "SATUAN JUMLAH RACIKAN",
                    html: "<p>Masih kosong</p>",
                    type: "error",
                    confirmButtonText: "OK"
               });
               return;
          }
          if (aturan_pakai_racikan == '') {
               swal({
                    title: "ATURAN PAKAI",
                    html: "<p>Masih kosong</p>",
                    type: "error",
                    confirmButtonText: "OK"
               });
               return;
          }
          var subtotal = Number(parseInt($("#toto_1").val().replaceAll(',', '')));
          var diskon = Number($("#disknom_1").val());
          var diskonrp = Number(parseInt($("#disk_1").val().replaceAll(',', '')));
          var ppnrp = Number(parseInt($("#ppn_1").val().replaceAll(',', '')));
          var ongkosracik = Number(parseInt($("#ongra_1").val().replaceAll(',', '')));
          var totalrp = Number(parseInt($("#totp_1").val().replaceAll(',', '')));
          // if(ppnrp > 0){
          //      var ppn = 1;
          // } else {
          //      var ppn = 0;
          // }
          ppn = 1;
          var param_racik = '?resepno=' + nobukti + "&subtotal=" + subtotal + "&diskon=" + diskon + "&ppnrp=" + ppnrp + "&ongkosracik=" + ongkosracik + "&totalrp=" + totalrp + "&ppn=" + ppn + "&diskonrp=" + diskonrp + "&gudang=" + gudang + "&atpakai=" + aturan_pakai_racikan + "&jenisracik=" + jenis_racikan + "&namaracik=" + nama_racikan + "&jumlahracik=" + jumlah_racikan + "&stajum=" + satuan_jumlah_racikan + "&carapakai=" + cara_pakai_racikan + "&resepmanual=" + resepmanual + "&harga_manual=" + total + "&cek_rm=" + h_manual;
          // console.log(param_racik)
          if (jenis_racikan != '' && nama_racikan != '' && jumlah_racikan != '' && satuan_jumlah_racikan != '' && aturan_pakai_racikan != '') {
               $.ajax({
                    url: "<?= site_url('Penjualan_faktur/update_aporacik/') ?>" + param_racik,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                         if (data.status == 1) {
                              if ((idrow2 - 1) == 0) {
                                   idrowx = idrow2x;
                              } else {
                                   idrowx = idrow2;
                              }
                              for (i = 1; i < idrowx; i++) {
                                   var koderacik    = $('#koderacik' + i).val();
                                   var namaracik    = $('#namaracik' + i).val();
                                   var satuanracik  = $('#satracik' + i).val();
                                   var qtyracik     = $('#qtyracik' + i).val();
                                   var qtyjualracik = $('#qtyjualracik' + i).val();
                                   var hargaracikx  = $('#hargaracik' + i).val();
                                   var hargaracik   = Number(parseInt(hargaracikx.replaceAll(',', '')));
                                   var uangracikx   = $('#uangracik' + i).val();
                                   var uangracik    = Number(parseInt(uangracikx.replaceAll(',', '')));
                                   var jumlahracikx = $('#jumlahracik' + i).val();
                                   var jumlahracik  = Number(parseInt(jumlahracikx.replaceAll(',', '')));
                                   var exp          = $('#exp1' + i).val();
                                   var param        = '?resepno=' + nobukti + "&kodebarang=" + koderacik + "&namabarang=" + namaracik + "&qty=" + qtyracik + "&qtyr=" + qtyjualracik + "&satuan=" + satuanracik + "&price=" + hargaracik + "&uangr=" + uangracik + "&totalrp=" + jumlahracik + "&exp1=" + exp;
                                   // console.log(param);
                                   $.ajax({
                                        url: "<?= site_url('Penjualan_faktur/update_apodetresep/') ?>" + param,
                                        type: "POST",
                                        dataType: "JSON",
                                        // success: function(data) {
                                        // }
                                   });
                              }
                              saveresep();
                         } else {
                              swal({
                                   title: "UBAH RACIKAN",
                                   html: "Gagal dilakukan !",
                                   type: "error",
                                   confirmButtonText: "OK"
                              });
                         }
                    }
               });
          }
     }

     function saveresep() {
          var noreg   = $('[name="noreg"]').val();
          var rekmed  = $('[name="pasien"]').val();
          var dokter  = $('[name="dokter"]').val();
          var pembeli = $('[name="pembeli"]').val();
          var gudang  = $('[name="gudang"]').val();

          
          var table       = document.getElementById('datatable');
          var rowCount    = table.rows.length;          
          for (var i = 1; i < rowCount; i++) {
          var expire    = $("#expire" + i).val(); 
          if (expire == '' || expire == null) {
               swal({
               title: "Expired Date",
               html: "<p>HARUS DI isi</p>",
               type: "error",
               confirmButtonText: "OK"
               });
               return;
          }
          }

          
          if (document.getElementById('t_manual').checked == true) {
               var h_manual = 1;
               var totalx = $('#toto_11').val();
          } else {
               var totalx = $('#_vtotal').val();
               var h_manual = 0;
          }
          
          if (pembeli == 'KULIT') {
               jenispas = 1;
          } else if (pembeli == 'LOKAL') {
               jenispas = 2;
          } else if (pembeli == 'KIRIM') {
               jenispas = 3;
          } else if (pembeli == 'SPA') {
               jenispas = 4;
          } else if (pembeli == 'GIGI') {
               jenispas = 5;
          } else if (pembeli == 'ONLINE') {
               jenispas = 6;
          } else if (pembeli == 'APOTIK') {
               jenispas = 7;
          } else if (pembeli == 'RAJAL') {
               jenispas = 8;
          } else if (pembeli == 'RANAP') {
               jenispas = 9;
          } else if (pembeli == 'adr') {
               jenispas = 10;
          } else if (pembeli == 'atr') {
               jenispas = 11;
          }
          var resepno   = $('#noresep').val();
          var eresepno  = $('#noeresep').val();
          var tanggal   = $('[name="tanggal"]').val();
          var pasien    = $('[name="pasien"]').val();
          var gudang    = $('[name="gudang"]').val();
          var pembeli   = $('[name="pembeli"]').val();
          var totalx    = $('#_vtotal').text();
          var total     = Number(parseInt(totalx.replaceAll(',', '')));
          var nohp      = $('#phone').val();
          if(document.getElementById("t_manual").checked == true) {
               var racikanxx = $('#toto_11').val();
               var racikan   = Number(parseInt(racikanxx.replaceAll(',', '')));
          } else {
               var racikanxx = $('#totp_1').val();
               var racikan   = Number(parseInt(racikanxx.replaceAll(',', '')));
          }

          // console.log($('#frmpenjualan').serialize());

          $.ajax({
               url: "<?= site_url('Penjualan_faktur/update_apoposting/?total=') ?>" + total + "&racikan=" + racikan + "&resepno=" + resepno + "&gudang=" + gudang + "&rekmed=" + rekmed + "&noreg=" + noreg,
               type: "POST",
               data: $('#frmpenjualan').serialize(),
               dataType: "JSON",
               success: function(data) {
                    // console.log(data);
                    if (data.status == 1) {
                         $.ajax({
                              url: "<?= site_url('Penjualan_faktur/update_apohresep/?resepno=') ?>" + resepno + "&jenispas=" + jenispas + "&gudang=" + gudang + "&rekmed=" + rekmed + "&noreg=" + noreg + "&pembeli=" + pembeli + "&dokter=" + dokter + "&eresepno=" + eresepno,
                              type: "POST",
                              data: $('#frmpenjualan').serialize(),
                              dataType: "JSON",
                              success: function(data) {
                                   if (data.status == 1) {
                                        for (i = 1; i < idrow; i++) {
                                             var kode       = $('#kode' + i).val();
                                             var nama       = $('#nama' + i).val();
                                             var qty        = $('#qty' + i).val();
                                             var sat        = $('#sat' + i).val();
                                             var hargax     = $('#harga' + i).val();
                                             var harga      = Number(parseInt(hargax.replaceAll(',', '')));
                                             var disc       = $('#disc' + i).val();
                                             var discrpx    = $('#disc2' + i).val();
                                             var discrp     = Number(parseInt(discrpx.replaceAll(',', '')));
                                             var jumlahx    = $('#jumlah' + i).val();
                                             var jumlah     = Number(parseInt(jumlahx.replaceAll(',', '')));
                                             var aturpakai  = $("#aturan_pakai" + i).val();
                                             var keterangan = $("#keterangan" + i).val();
                                             var expire     = $("#expire" + i).val();
                                             var ppnx       = 1;
                                             var ppnrp      = (jumlah / (111 / 100)) * ppn;
                                             $.ajax({
                                                  url: "<?= site_url('Penjualan_faktur/update_apodresep/?resepno=') ?>" + resepno + "&kodebarang=" + kode + "&namabarang=" + nama + "&qty=" + qty + "&satuan=" + sat + "&discount=" + disc + "&discrp=" + discrp + "&price=" + harga + "&totalrp=" + jumlah + "&ppn=" + ppnx + "&ppnrp=" + ppnrp + "&gudang=" + gudang + "&eresepno=" + eresepno + "&aturanpakai=" + aturpakai + "&keterangan=" + keterangan + "&expire=" + expire,
                                                  type: "POST",
                                                  data: $('#frmpenjualan').serialize(),
                                                  dataType: "JSON",
                                             });
                                        }
                                        swal({
                                             title: "UBAH RESEP",
                                             html: "No. Bukti : <b>" + data.resepno + "</b>" + "<br>Tanggal : " + tanggal + "<br>Biaya Terbentuk : <b>" + totalx + "</b>" + "<br>Dengan Biaya Racikan : <b>" + racikanxx + '</b>',
                                             type: "info",
                                             confirmButtonText: "OK"
                                        }).then((value) => {
                                             location.href = "<?php echo base_url() ?>Penjualan_faktur";
                                        });
                                   } else {
                                        swal({
                                             title: "UBAH RESEP",
                                             html: "Gagal dilakukan !",
                                             type: "error",
                                             confirmButtonText: "OK"
                                        });
                                   }
                              }
                         });
                    } else {
                         swal({
                              title: "UBAH RESEP",
                              html: "Gagal dilakukan !",
                              type: "error",
                              confirmButtonText: "OK"
                         });
                    }
               }
          });
     }

     function bayar() {
          var table = document.getElementById('datatable');
          var rowCount = table.rows.length;
          var tanggal = $('[name="tanggal"]').val();
          var gudang = $('[name="gudang"]').val();
          var pembeli = $('[name="pembeli"]').val();
          var total = $('#_vtotal').text();
          if (pembeli == null || gudang == null || gudang == "" || pembeli == "" || total == "0.00" || total == "") {
               if (gudang == "" || gudang == null) {
                    swal('PENJUALAN', 'Depo belum diisi ...', '');
               }
               if (pembeli == "" || pembeli == null) {
                    swal('PENJUALAN', 'Pembeli belum diisi ...', '');
               }
               if (total == "" || total == "0.00") {
                    swal({
                         title: "PENJUALAN",
                         html: "Belum ada item barang yang dipilih ...",
                         type: "error",
                         confirmButtonText: "OK"
                    });
                    return;
               }
          } else {
               swal({
                    title: "RESEP TERBENTUK ",
                    html: "DENGAN NOMINAL <b>" + total + "</b> <br><br><p> Lanjut Ke Proses Racik...</p>",
                    type: "info",
                    confirmButtonText: "OK"
               }).then((value) => {
                    $('.nav-pills a[href="#tab2"]').tab('show');
               });
          }
     }

     function getdataregistrasi() {
          var xhttp;
          var str = $('[name=noreg]').val();
          if (str != "") {
               $.ajax({
                    url: "<?php echo base_url(); ?>kasir_konsul/getdataregistrasi/?noreg=" + str,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                         $('#pasien').html(data.rekmed);
                         $('#namapasien').val(data.namapas);
                         $('#alamat').val(data.alamat);
                         $('#phone').val(data.handphone);
                         $('#reg_poli').val(data.kodepos);
                         var selectElement = document.getElementById('pasien');
                         var opt = document.createElement('option');
                         opt.value = data.rekmed;
                         opt.innerHTML = data.rekmed;
                         selectElement.appendChild(opt);
                         var selectElement = document.getElementById('dokter');
                         var opt = document.createElement('option');
                         opt.value = data.kodokter;
                         opt.innerHTML = data.kodokter + ' | ' + data.nadokter;
                         selectElement.appendChild(opt);
                    }
               });
          }
     }

     function _urlcetak() {
          var baseurl = "<?php echo base_url() ?>";
          var nobukti = $('#noresep').val();
          return baseurl + 'penjualan_faktur/cetak/?nobukti=' + nobukti;
     }
</script>

</body>

</html>