
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	<link href="<?= base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?= base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?= base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?= base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?= base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css"/>
	<style>
        .toolbar {
            float: left;
        }

        /* * {font-family: 'Dot Matrix';} */

        
    </style>
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?= $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">APOTEK <small>Penjualan Resep</small>
					</h3>
                         <ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?= base_url();?>dashboard">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="">
                               Farmasi
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="<?= base_url();?>penjualan_faktur">
                               Data Penjualan
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
                        <!-- <div class="portlet-title">
							<div class="caption">Daftar E-RESEP</div>
						</div>
						<div class="portlet-body">
                            <div class="btn-group pull-right" style="margin-bottom:15px">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li>											
                                        <a data-toggle="modal" href="#eresepperiode">Ganti Periode Data</a>										
                                    </li>										
                                </ul>
                            </div>
							<table class="table table-striped table-hover table-bordered" id="datatable-eresep">
                                <thead>
                                    <tr class="page-breadcrumb breadcrumb title-white">
                                        <th>Proses</th>
                                        <th>Cabang</th>
                                        <th>Tgl Eresep</th>
                                        <th>No Eresep</th>
                                        <th>No Reg</th>
                                        <th>Rekmed</th>
                                        <th>Nama Pasien</th>
                                        <th>Resep Dokter</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($eresep as $rkey => $rval){ ?>
                                        <tr>
                                            <?php 
                                            $cek =  $this->session->userdata('user_level'); 
                                            if($cek==0){?> 
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-xs" >proses</button>
                                                </td>
                                            <?php }else{ ?>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-xs" onclick="window.open('/penjualan_faktur/entri/?eresep=true&noresep=<?= $rval->orderno ?>', '_blank')">proses</button>
                                                </td>
                                            <?php } ?>
                                            <td class="text-center"><?= $rval->koders ?></td>
                                            <td><?= date("d-m-Y", strtotime($rval->tglorder)) ?></td>
                                            <td><?= $rval->orderno ?></td>
                                            <td><?= $rval->noreg ?></td>
                                            <td><?= $rval->rekmed ?></td>
                                            <td><?= $rval->namapas ?></td>
                                            <td><?= $rval->kodepos == "" ? "-" : data_master("dokter", array("kodokter" => $rval->kodokter, "koders" => $rval->koders, "kopoli" => $rval->kodepos))->nadokter ?></td>
                                            <td><?= $rval->keterangan ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
							</table>
						</div>
                        
                        <hr /> -->

						<div class="portlet-title">
							<div class="caption">
								Daftar  Faktur Penjualan 
								<span><b>
								<?php 
								//    echo $periode;?></b>
                                </span>
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								
								<div class="btn-group">
                                    <?php if($akses->uadd){?>
                                        <?php 
                                        $cek =  $this->session->userdata('user_level'); 
                                        if($cek==0){?> 
                                        <?php }else{ ?>

                                            <a href="<?= base_url()?>penjualan_faktur/entri/" class="btn btn-success">
                                            <i class="fa fa-plus"></i>
                                            <b>Transaksi Baru</b>
                                            </a>

                                        <?php } ?>

                                    <?php } ?>	
								</div>	
								
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
							<div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list" width="100%" style="overflow-x:auto;">
                            <thead>
                                     <tr class="page-breadcrumb breadcrumb">
                                         <th class="title-white" style="text-align: center">Cab.</th>
										 <th class="title-white" style="text-align: center" width="150">User ID</th>
									     <th class="title-white" style="text-align: center">No. Resep</th>
										 <!-- <th class="title-white" style="text-align: center">No. Register</th> -->
										 <th class="title-white" style="text-align: center">Rekmed</th>
										 <th class="title-white" style="text-align: center">Nama Pasien</th>
										 <th class="title-white" style="text-align: center">Tanggal</th>
										 <th class="title-white" style="text-align: center">Jumlah Rp</th>
										 <!-- <th class="title-white" style="text-align: center">No. Kwitansi</th> -->
                      <th class="title-white" style="text-align: center">Status</th>
                      <th class="title-white" style="text-align: center">Action</th>
										 <!-- <th>&nbsp;</th>
                                         <th>&nbsp;</th>                                          -->
                    </tr>
                    </thead>

                  
                    <tbody>
                    <?php
                    
                      $nomor = 1;
                      foreach ($keu as $row)
                      {   
									     
									     ?>

                      <tr class="show1" id="row_<?= $row->resepno;?>">
									     <td align="center"><?= $row->koders;?></td>	
									     <td align="center"><?= $row->username;?></td>										 
                        <td align="center"><?= $row->resepno;?></td>										 
										 <!-- <td align="center"><?= $row->noreg;?></td>										  -->
										 <td align="center"><b><?= $row->rekmed;?></b></td>										 
										 <td align="center"><?= $row->namapas;?></td>										 
                        <td align="center"><?= date('d-m-Y',strtotime($row->tglresep));?></td>										 
                        <td align="right"><?= number_format($row->poscredit,0,',','.');?></td>
										 <!-- <td><?= $row->nokwitansi;?></td> -->
                                         
                        <td style="text-align: center"><?php
                              if ($row->keluar=='0')
                              { ?>
										           <span class="label label-sm label-warning">
											          Belum Lunas
										           </span>
										           <?php
                              }else
                              if ($row->keluar=='1')
                              { ?>
                                <span class="label label-sm label-success">
                                  Lunas
										           </span>

										           <?php
												 } ?> 
                                                 
                                         </td>
										
                                        <td style="text-align: center">
                                            <?php
                                            if ($row->rekmed=='Non Member' || $row->rekmed=='NON MEMBER')
                                                { ?>
                                                <a class="btn btn-sm btn-success" href="<?= base_url()?>kasir_obat/entri" style="margin-bottom: 10px;">
                                                <i class="fa fa-money"></i></a>
                                            <?php }else{  ?>
                                              <?php
                                                $cek = $this->db->get_where("tbl_hset_farma", ["koders" => $this->session->userdata("unit")])->row();
                                                $cek2 = $this->db->get_where("tbl_apohresep", ["resepno" => $row->resepno])->row();
                                                if($cek) {
                                                  if($cek2->kodepel == "adr") {
                                                    $cek3 = $this->db->get_where("tbl_apodresep", ["resepno" => $row->resepno])->num_rows();
                                                    $cek4 = $this->db->get_where("tbl_aporacik", ["resepno" => $row->resepno])->num_rows();
                                                    if($cek4 > 0) {
                                                      $uangracik = $cek->uang_racik * $cek4;
                                                    } else {
                                                      $uangracik = 0;
                                                    }
                                                    $uangr = ($cek->uang_r * $cek3) + $uangracik;
                                                  } else {
                                                    $uangr = $cek->uang_r;
                                                  }
                                                } else {
                                                  $uangr = 0;
                                                }
                                              ?>
                                                <a class="btn btn-sm btn-success" onclick="bayar('<?= $row->resepno;?>', '<?= $uangr;?>')" style="margin-bottom: 10px;">
                                                <i class="fa fa-money"></i></a>
                                            <?php } ?>

                                            
                                            <?php
                                               if ($row->keluar=='0')
                                                 { ?>
											 <a class="btn btn-sm btn-primary" href="<?= base_url()?>penjualan_faktur/edit/<?= $row->resepno;?>" style="margin-bottom: 10px;">
                                             <i class="glyphicon glyphicon-edit" title="Edit"></i></a>
                                            <?php }?>
                                            <?php
                                            if ($row->keluar=='1')
                                                { ?>
											 <a class="btn btn-sm btn-info" href="<?= base_url()?>penjualan_faktur/edit/<?= $row->resepno;?>/1" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-eye-open"></i></a>

                                             <a class="btn btn-sm btn-warning" onclick="_urlcetak('<?= $row->resepno;?>')" title="Cetak" style="margin-bottom: 10px;">
                                                <i class="glyphicon glyphicon-print"></i></a>
                                            <?php }?>
                                           <?php
                                            if ($row->keluar=='0')
                                                { ?>
                                                <!-- <a class="btn btn-sm btn-danger" href="<?= base_url()?>penjualan_faktur/delete/<?= $row->resepno;?>/1" title="Delete"> -->
                                                <a class="btn btn-sm btn-danger" onclick="cekhapus('<?= $row->resepno;?>')" title="Delete" style="margin-bottom: 10px;">
                                                <i class="glyphicon glyphicon-remove"></i></a>

                                                <a class="btn btn-sm btn-warning" onclick="_urlcetak('<?= $row->resepno;?>')" title="Cetak" style="margin-bottom: 10px;">
                                                <i class="glyphicon glyphicon-print"></i></a>
                                            <?php }?>
                                        </td>
                                         
                                     </tr>
                                     <?php
                                          $nomor++;
                                     } ?>


                                     </tbody>
                                  
							</table>
              </div>
						</div>
                        <br /><br />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Pasien</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">

                        <div class="row">
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">No Kwitansi <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <input type="text" id="nokwi_kasir" name="nokwi_kasir" class="form-control" value="Auto" readonly>
                                    <input type="hidden" id="noress" name="noress" class="form-control" value="Auto" readonly>
                                </div>

                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Identitas <font color="red">*</font></label>
                                <div class="col-md-2">
                                  <select name="lupidentitas" id="lupidentitas" class="form-control input-small" readonly>
                                      <option value="-">-- Pilih --</option>
                                      <option value="KTP">KTP</option>
                                      <option value="SIM">SIM</option>
                                      <option value="PASPORT">PASPORT</option>
                                      <option value="K_PELAJAR">K_PELAJAR</option>
                                      <option value="KMAHASISWA">KMAHASISWA</option>
                                  </select>

                                </div>
                                <div class="col-md-7">
                                  <input type="text" placeholder="No Identitas" name="lupnoidentitas" id="lupnoidentitas" class="form-control" readonly>
                                </div>

                              </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Tgl Transaksi<font color="red">*</font></label>
                                <div class="col-md-9">
                                  <input id="tgltr" name="tgltr" type="date" class="form-control input-large" readonly>
                                </div>

                              </div>
                            </div>
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">Nama Pasien <font color="red">*</font></label>
                                  <div class="col-md-2">
                                    <select class="form-control input-small" name="luppreposition" id="luppreposition" disabled>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        foreach (setinghms('PREP') as $row) { ?>
                                            <option value="<?= $row->kodeset; ?>"><?= $row->keterangan; ?></option>
                                        <?php } ?>
                                    </select>
                                  </div>
                                  <div class="col-md-7">
                                        <input name="lupnamapasien" required="required" id="lupnamapasien" placeholder="Nama Pasien"  class="form-control" type="text" readonly>
                                  </div>
                              </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Penjamin <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <!-- <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin" readonly>
                                    <option value="">--- Pilih ---</option>
                                    <?php $penjamin = $this->db->get("tbl_penjamin")->result();
                                    foreach($penjamin as $row){ 
                                    $selected = ($row->cust_id==$data->cust_nama?'selected':'');
                                    ?>
                                    <option <?= $selected;?> value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
                                    <option value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
                                    <?php } ?>
                                  </select> -->
                                  <input class="form-control" type="hidden" id="vpenjamin" name="vpenjamin" readonly>
                                  <input class="form-control" type="text" id="vpenjaminb" name="vpenjaminb" readonly>
                                </div>
                              </div>
                            </div>
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Handphone <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <div class="input-group input-medium">
                                    <!-- <span class="input-group-btn">
                                      <a class="form-control">+62</a>
                                    </span> -->
                                    <input name="luphp" id="luphp" placeholder="Dimulai Tanpa 0" class="form-control" maxlength="" type="text" value="+62" readonly>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">No Member
                                    <font color="red">*</font>
                                  </label>
                                  <div class="col-md-9">
                                      <input name="rekmed" id="rekmed" placeholder="Auto" class="form-control" type="text" readonly>
                                      <input name="resepno" id="resepno" placeholder="Auto" class="form-control" type="hidden" readonly>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>
                            
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Jenis Kelamin <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <div class="input-group input-medium">
                                    <select name="jkelp" id="jkelp" class="form-control" disabled>
                                      <option value="P">Pria</option>
                                      <option value="W">Wanita</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Total Resep <font color="red">*</font></label>
                                <div class="col-md-3">
                                  <input type="hidden"  id="total_resep"  name="total_resep" class="form-control" readonly>
                                  <input style="text-align: right;"  id="total_resepb" name="total_resepb" type="text" class="form-control " readonly>
                                </div>
                                <label class="control-label col-md-3">Uang Resep <font color="red">*</font></label>
                                <div class="col-md-3">
                                  <input style="text-align: right;"  id="uangr" name="uangr" type="hidden" class="form-control " readonly>
                                  <input style="text-align: right;"  id="uangrr" name="uangrr" type="text" class="form-control " readonly>
                                </div>
                              </div>
                            </div>
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Tgl Lahir <font color="red">*</font></label>
                                <div class="col-md-4">
                                  <input id="tgllahirp" name="tgllahirp" type="date" onchange="tgllahirpp()" class="form-control input-medium" readonly>
                                </div>
                                <div class="col-md-5">
                                    <input id="lumurp" name="lumurp" type="text" class="form-control" readonly>
                                </div>

                              </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Total Semua <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <input style="text-align: right;"  type="hidden"  id="total_semua"  name="total_semua" class="form-control" readonly>
                                  <input style="text-align: right;"  id="total_semuab" name="total_semuab" type="text" class="form-control" readonly>
                                </div>
                              </div>
                            </div>
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">Alamat <font color="red">*</font></label>
                                  <div class="col-md-9">
                                      <input name="lupalamat" id="lupalamat" placeholder="Alamat Pasien" class="form-control" type="text" readonly>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Nilai Apotek <font color="red">*</font></label>
                                <div class="col-md-5">
                                  <input style="text-align: right;"  type="number"  id="nil_aptk"  name="nil_aptk" class="form-control" >
                                </div>
                                <div class="col-md-4">
                                  <input style="text-align: right;"  id="nilap" name="nilap" type="text" class="form-control" readonly>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label col-md-3">Jumlah Klaim <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <input style="text-align: right;"  type="hidden"  id="juklaim"  name="juklaim" class="form-control" readonly>
                                  <input style="text-align: right;"  id="juklaimb" name="juklaimb" type="text" class="form-control" readonly>
                                </div>
                            </div>
                          </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btncetak_bayar" onclick="urlcetak_kasir()" class="btn btn-warning"><b>
                    <i class="fa fa-print"></i> Cetak Kasir</b>
                </button>
                <button type="button" id="btncetak_jamin" onclick="urlcetak_jamin()" class="btn btn-warning"><b>
                    <i class="fa fa-print"></i> Cetak Penjamin</b>
                </button>
                <button type="button" id="btnsimpan_bayar" onclick="save_bayar()" class="btn btn-success"><b>
                    <i class="fa fa-money"></i> PROSES PENJAMIN</b>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Batal</b>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>
	

<script src="<?= base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<!-- <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script> -->
<!-- <script src="<?= base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>" type="text/javascript"></script> -->
<script src="<?= base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript" ></script>
<script src="<?= base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?= base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>
<script src="<?= base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>" type="text/javascript"></script>

<script>

window.onload = function(event) {
      $('[name="nil_aptk"]').on("keyup", function(){
            var uangr           = $('[name="uangr"]').val();
            var cekkk           = $('[name="nil_aptk"]').val();
            var cek2            = formatCurrency1(eval(cekkk));
            $('[name="nilap"]').val('Rp. '+(cek2));
            
            var nil_aptk        = $('#nil_aptk').val();
            var total_resep     = $('#total_resep').val();
            
            var vnil_aptk       = Number(nil_aptk.replace(/[^0-9\.]+/g, ""));
            var vtotal_resep    = Number(total_resep.replace(/[^0-9\.]+/g, ""));
            var totalnet        = eval(vtotal_resep) - eval(vnil_aptk) + eval(uangr) ;
            var totalnet2       = formatCurrency1(totalnet);

            
            $('#juklaim').val(totalnet);
            $('[name="juklaimb"]').val('Rp. '+totalnet2);

      });      
};
function tgllahirpp() {
    var birthDate = new Date($('#tgllahirp').val());
    var usia = hitung_usia(birthDate);
    $('#lumurp').val(usia);
}
function currencyFormat (num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function tot_klaim() 
{
  var nil_aptk    = $('#nil_aptk').val();
  var nilap       = $('#nilap').val();
  var total_resep = $('#total_resep').val();
  var juklaim     = $('#juklaim').val();

  var vnil_aptk   = Number(nil_aptk.replace(/[^0-9\.]+/g, ""));
  var vjuklaim    = Number(juklaim.replace(/[^0-9\.]+/g, ""));
  var totalnet    = eval(vnil_aptk) - eval(vjuklaim) ;

//   $('#total_resep').val(formatCurrency1(totalnet));
  $('#juklaim').val(formatCurrency1(totalnet));


//   document.getElementById('totaltunairp2').style.visibility = 'hidden';
//   document.getElementById('selisihrp2').style.visibility = 'hidden';
//   cekbutton();


}

function save_bayar() {

    
    var resepno     = $('[name="resepno"]').val();
    $.ajax({
    url         : '<?php echo site_url('penjualan_faktur/ajax_add_bayar') ?>',
      data        : $('#form').serialize(),
    // data        : {resepno : resepno},
    type        : 'POST',
    dataType    : 'json',

      success: function(data) {
        document.getElementById("btnsimpan_bayar").disabled = true;

        swal({
          title: "KWITANSI PEMBAYARAN",
          html : "BERHASIL TERSIMPAN",
          html: "<p> No. Bukti   : <b>" + data.nomor + "</b> </p>" + "<p>Biaya Terbentuk Rp " + formatCurrency1(data.total).split(".00").join(""),
          type: "info",
          confirmButtonText: "OK"
        }).then((value) => {
          //location.reload();
        //   document.getElementById("btncetak_bayar").disabled = false;
        //   $('#nokwitansi').val(data.nomor); 
        
            location.href = "<?= base_url()?>penjualan_faktur";
        });

      },
      error: function(data) {
        // $("#btnsimpan_bayar").attr("disabled", false);
        swal('', 'Data gagal disimpan ...', '');

      }
    });
}

function urlcetak_kasir() 
{
    var baseurl       = "<?php echo base_url() ?>";
    var nokwitansi    = $('[name="nokwi_kasir"]').val();
    var noresep       = $('[name="noress"]').val();
    var ctk           = baseurl + 'kasir_obat/cetak/?kwitansi=' + nokwitansi + '&resep=' + noresep;
    window.open(ctk,'_blank');
}

function urlcetak_jamin() 
{

  swal({
      title              : 'CETAK',
      html               : "<p>PILIH FORMAT</p>",
      type               : 'question',
      showCancelButton   : true,
      confirmButtonClass : 'btn btn-success',
      cancelButtonClass  : 'btn btn-danger',
      confirmButtonText  : 'FORMAT NOTA',
      cancelButtonText   : 'FORMAT PDF'
  }).then(function() {
      var baseurl       = "<?php echo base_url() ?>";
      var nokwitansi    = $('[name="nokwi_kasir"]').val();
      var noresep       = $('[name="noress"]').val();
      var ctk           = baseurl + 'kasir_obat/cetak_jaminan_nota/?kwitansi=' + nokwitansi + '&resep=' + noresep;
      window.open(ctk,'_blank');
  },function(dismiss) {
      var baseurl       = "<?php echo base_url() ?>";
      var nokwitansi    = $('[name="nokwi_kasir"]').val();
      var noresep       = $('[name="noress"]').val();
      var ctk           = baseurl + 'kasir_obat/cetak_jaminan/?kwitansi=' + nokwitansi + '&resep=' + noresep;
      window.open(ctk,'_blank');
  });
  

}

function bayar(resepno, uangr) {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('.form-group').removeClass('has-error'); // clear error class
      $('.help-block').empty(); // clear error string
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('PEMBAYAR DENGAN MEMBER'); // Set Title to Bootstrap modal title
        //Ajax Load data from ajax
        $.ajax({
            url         : "<?php echo site_url('penjualan_faktur/bayar/') ?>" + resepno,
            type        : "GET",
            dataType    : "JSON",
            success: function(data) {

                if(data.nokwitansi2=='' || data.nokwitansi2 == null){
                    document.getElementById("btnsimpan_bayar").disabled = false;
                    document.getElementById("btncetak_bayar").disabled = true;
                    document.getElementById("btncetak_jamin").disabled = true;
                    $('[name="nokwi_kasir"]').val('Auto');
                    $('[name="noress"]').val('');


                }else{
                    document.getElementById("btnsimpan_bayar").disabled = true;
                    document.getElementById("btncetak_bayar").disabled = false;
                    document.getElementById("btncetak_jamin").disabled = false;
                    $('[name="nokwi_kasir"]').val(data.nokwitansi2);
                    $('[name="noress"]').val(data.resepno2);

                } 
                
                if(data.nokwitansi2==''){
                  
                  $('[name="nil_aptk"]').val(0);  
                  $('[name="nilap"]').val(0);  
                  $('[name="juklaim"]').val(eval(data.poscredit) + eval(uangr));  
                  $('[name="juklaimb"]').val(formatCurrency1(eval(data.poscredit) + eval(uangr))); 
                }else{

                  
                  $total_klaim = eval(data.jumlahhutang);
                  $('[name="juklaim"]').val($total_klaim);  
                  $('[name="juklaimb"]').val(formatCurrency1($total_klaim));  
                  $total_nilap = Number(data.bayarcash)+Number(data.bayarcard);
                  $('[name="nil_aptk"]').val($total_nilap);  
                  $('[name="nilap"]').val(formatCurrency1($total_nilap));  
                }
                $('[name="id"]').val(data.id);
                $('[name="rekmed"]').val(data.rekmed2);
                $('[name="lupnamapasien"]').val(data.namapas2);
                $('[name="vpenjamin"]').val(data.penjamin);
                $('[name="vpenjaminb"]').val(data.nm_penjamin);
                // $('#vpenjamin option[value="' + data.penjamin + '"]').prop('selected', true);
                $('#jkelp option[value="' + data.jkel + '"]').prop('selected', true);
                $('#luppreposition option[value="' + data.preposisi + '"]').prop('selected', true);
                $('#lupidentitas option[value="' + data.idpas + '"]').prop('selected', true);
                $('[name="resepno"]').val(data.resepno2);
                $('[name="no_bpjs"]').val(data.nocard);
                $('[name="luphp"]').val(data.nohp);
                $('[name="lupnoidentitas"]').val(Number(data.noidentitas));
                $('[name="lupalamat"]').val(data.alamat);
                $('[name="tgllahirp"]').val(data.tanggallahir);  
                $('[name="tgltr"]').val(data.tglresep1);  
                $totalres = formatCurrency1(data.poscredit);
                $total_semua = formatCurrency1(eval(data.poscredit) + eval(uangr));
                $uangr = formatCurrency1(uangr);
                $('[name="total_resep"]').val(data.poscredit);  
                $('[name="total_resepb"]').val($totalres);  
                console.log(eval(data.poscredit) + eval(uangr))
                $('[name="total_semua"]').val(eval(data.poscredit) + eval(uangr));  
                $('[name="total_semuab"]').val($total_semua);  
                $('[name="uangr"]').val(uangr);  
                $('[name="uangrr"]').val($uangr);  
                $('[name="juklaim"]').val(eval(data.poscredit) + eval(uangr));  
                tgllahirpp();
                tgllahirpp();
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('PROSES BAYAR'); // Set title to Bootstrap modal title

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
  }

function cekhapus(noresep)
{
  swal({
  title: "Hapus Resep : <b>"+noresep+"</b>  ?",
  text: "",
  type: 'info',
  showCancelButton: true,
  confirmButtonClass: 'btn btn-success',
  cancelButtonClass: 'btn btn-success',
  confirmButtonColor: '#227dff',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Ya',     
  cancelButtonText: 'TIDAK',     
  }).then(function () {
        $.ajax( {
            dataType: 'html',
            type: "POST",
            url: "<?= base_url(); ?>penjualan_faktur/delete/"+noresep,	
            cache: false,
            success: function (data) {
                swal({
                    title: "Resep Berhasil Di Hapus",
                    html: "<b>"+noresep+"</b> <br> ",
                    type: "success",
                    confirmButtonText: "OK" 
                }).then((value) => {
        // bayar();
                    // var table = $('#keuangan-keluar-list').dataTable();
                    // table.ajax.reload();
        location.href = "<?= base_url()?>penjualan_faktur";
                    return;
                });
            },
                error:function(data){
                swal('RESEP','Data gagal di hapus ...','');	
                        
            }
        } );
  }, function (dismiss) {
    if (dismiss === 'cancel') {
      return;
    }
  });
}

function _urlcetak(nobukti)
{

  swal({
      title              : 'CETAK',
      html               : "<p>PILIH FORMAT</p>",
      type               : 'question',
      showCancelButton   : true,
      confirmButtonClass : 'btn btn-success',
      cancelButtonClass  : 'btn btn-danger',
      confirmButtonText  : 'FORMAT NOTA',
      cancelButtonText   : 'FORMAT PDF'
  }).then(function() {
      var baseurl   = "<?= base_url()?>";
      var ctk       = baseurl+'penjualan_faktur/cetak2_nota/?nobukti='+nobukti;
      window.open(ctk,'_blank');
  },function(dismiss) {
      var baseurl   = "<?= base_url()?>";
      var ctk       = baseurl+'penjualan_faktur/cetak2/?nobukti='+nobukti;
      window.open(ctk,'_blank');
  });
}
	
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
                        url: "<?= base_url(); ?>penjualan_faktur/hapus/"+mydata,	
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

$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('penjualan');
		
		var param=this.id;				
				
		$("#simkeureport").html('<iframe src="<?= base_url();?>penjualan_faktur/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});

        $("#datatable-eresep").DataTable({
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
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
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ]
            },
        });

        $('#datatable-eresep_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#datatable-eresep_wrapper .dataTables_length select').addClass("form-control input-small  input-inline"); // modify table per page dropdown
        $('#datatable-eresep_wrapper .dataTables_length').attr("style", "float:left;margin-top:50px");
	});
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = '2~'+tgl1+'~'+tgl2; 
	location.href = "<?= base_url();?>penjualan_faktur/filter/"+str;
}

function filterdataEresep(){
    var tgl1 = document.getElementById("tanggale1").value;
	var tgl2 = document.getElementById("tanggale2").value;
    var str         = tgl1 +"~"+ tgl2;
    location.href   = "<?= base_url();?>penjualan_faktur/?filter-eresep="+str;
}

jQuery(document).ready(function() {       
   TableEditable.init();
//    ComponentsPickers.init();
  
});
</script>
