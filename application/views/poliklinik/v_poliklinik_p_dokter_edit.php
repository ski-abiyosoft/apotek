
    <?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    
    date_default_timezone_set("Asia/Jakarta");	
    ?>	

    <div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?> 
            </span>
            - 
            <span class="title-web">e-HMS <small>Poliklinik</small>
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
                <a class="title-white" href="<?php echo base_url('kasir_uangmuka');?>">
                    Poliklinik
                                            </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>							
                <a class="title-white" href="">
                    Pemeriksaan Dokter
                </a>
            </li>
        </ul>
    </div>
    </div>
<form id="form_periksa_dokter" class="form-horizontal" method="post">
    <div class="row">
    <div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i><b>Pemeriksaan Dokter</b>
        </div>
        </div>
    <div class="portlet-body">			
        <div class="row">
            <div class="col-md-6">
                    <h3 class="form-section modal-title" style="color:green" align="left"><b>EMR-RAWAT JALAN</b></h3>
            </div>
            
            <div class="col-md-5">
                    <h3 class="form-section modal-title" style="color:green" align="left"><b>HARTOYO SPOG</b></h3>
                
            </div>
        
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>No Registrasi</b></label>
                    <div class="col-md-8">
                        <input value="<?= $data_pas->noreg ?>" readonly id="noreg_dok" name="noreg_dok" class="form-control" maxlength="10" type="text">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Tgl Periksa</b></label>
                    <div class="col-md-8">
                        <input readonly id="tgl_dok" name="tgl_dok" class="form-control" type="date">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
        
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>No Rekmed</b></label>
                    <div class="col-md-8">
                        <input value="<?= $data_pas->rekmed ?>" readonly id="rekmed_dok" name="rekmed_dok" class="form-control" maxlength="100" type="text">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Tujuan</b></label>
                    <div class="col-md-8">
                        <input readonly value="<?= $data_pas->kodepos ?>" id="poli_dok" name="poli_dok" class="form-control" type="text">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
        </div>   
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Nama Pasien</b></label>
                    <div class="col-md-8">
                        <input readonly value="<?= $data_pas->namapas ?>" id="nampas_dok" name="nampas_dok"  class="form-control" maxlength="100" type="text">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:red"><b>Alergi</b></label>
                    <div class="col-md-8">
                        <textarea readonly id="alergi_dok" name="alergi_dok"  placeholder="" class="form-control"><?= $data_pas->alergi ?></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Umur </b></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="umur" name="umur" value="" readonly>	
                    </div>
                </div>
            </div>
        </div>
        
    <div class="row">
        <table  border="0" style="width:100%">
        <tr>
            <td align="center" width="5%">&nbsp;</td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>0</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>1</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>2</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>3</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>4</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>5</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>6</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>7</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>8</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>9</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="2%" style="font-size:3px black;"><b>10</b></td>
            <td align="center" width="2%">&nbsp;</td>
            <td align="center" width="5%">&nbsp;</td>
            <td width="40%">
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp; Kesimpulan Pemeriksaan Fisik</label></td>
        </tr>
        <tr>
            <td align="center" >&nbsp;</td>
            <td align="center" >&nbsp;</td>
            <td align="center" style="border-top:3px solid #23c8c0; border-right:3px solid #23c8c0 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #23c8c0; border-right:3px solid #23c8c0 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df">&nbsp;</td>
            <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #fe060e; border-right:3px solid #fe060e ">&nbsp;</td>
            <td align="center" style="border-top:3px solid #fe060e; border-right:3px solid #fe060e ">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td rowspan="3" >
                    <div class="col-md-9"><br>
                        <textarea name="simpulfisik" placeholder="" class="form-control" rows="4" ><?= $ttv->pfisik ?></textarea>
                        <span class="help-block"></span>
                    </div>
            </td>
            
        </tr>
        <tr>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Tidak Nyeri </b></td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Nyeri Ringan </b></td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Nyeri Sedang </b></td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Nyeri Sedang </b></td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Nyeri Berat</b></td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" ><b>Nyeri Sangat Berat </b></td>
            <td align="center" >&nbsp;</td>
            
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #23c8c0;" >
                <a>&#128515;</a>
            </td>
            
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #13ef9a;">
                <a>&#128578;</a>
            </td>
            
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #0f67df;">
                <a>&#128577;</a>
            </td>
            
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #e45c10;">
                <a>&#128543;</a>
            </td>
            
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #aa3d0c;">
                <a>&#128546;</a>
            </td>
            <td align="center" >&nbsp;</td>
            <td align="center" colspan="3" style="font-size: 50px; background-color: #fe060e;">
                <a>&#128534;</a> 
            </td>
            <td align="center" >&nbsp;</td>
            
        </tr>
        <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox"  id="ceknyeri1" name="ceknyeri1" onclick="c_ceknyeri(1)" value="1">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox"  id="ceknyeri2" name="ceknyeri2" onclick="c_ceknyeri(2)" value="2">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox"  id="ceknyeri3" name="ceknyeri3" onclick="c_ceknyeri(3)" value="3">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox"  id="ceknyeri4" name="ceknyeri4" onclick="c_ceknyeri(4)" value="4">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox"  id="ceknyeri5" name="ceknyeri5" onclick="c_ceknyeri(5)" value="5">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td class="rightJustified" align="center"> 
                    <input class="form-control" type="checkbox" id="ceknyeri6" name="ceknyeri6" onclick="c_ceknyeri(6)" value="6">
                </td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>

        </tr>
        </table>
    </div>

    <h3 class="form-section col-md-6" style="color:green"><b></b></h3>
    <div class="row">
    <div class="col-md-12">
    <div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i><b></b>
        </div>
        <div class="tools">
            <a href="" class="collapse">
        </a>						
        </div>
    </div>
    <div class="portlet-body">	
    <form id="frmpasien" class="form-horizontal" method="post">
    <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="" id="ases">
                    <a href="#tab1" data-toggle="tab">
                            <b>ASSESMENT AWAL</b>
                    </a>
                </li>
                <li class="active" id="sb">
                    <a href="#tab2" onclick="tab2()" data-toggle="tab">
                            <b>SOAP & BILLING</b></a>
                </li>
                <li class="" id="resep">
                    <a href="#tab3" data-toggle="tab" onclick="tab3()">
                            <b>e-RESEP</b>
                    </a>
                </li>
                <li class="" id="lab">
                    <a href="#tab4" data-toggle="tab" onclick="tab4()">
                            <b>e-LAB</b>
                    </a>
                </li>
                <li class="" id="rad">
                    <a href="#tab5" data-toggle="tab" onclick="">
                            <b>e-RAD</b>
                    </a>
                </li>
                <li class="" id="hispas">
                    <a href="#tab5" data-toggle="tab" onclick="">
                            <b>HISTORY PASIEN</b>
                    </a>
                </li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
            </div>
            <div class="tab-pane active" id="tab2">
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered " cellspacing="0" cellpadding="0" style="overflow: auto;" border="1" width="100%">
                        <tr>
                            <td align="center" style="color:green">
                            <label for=""><b>Keluhan Awal</b></label>
                            </td>
                            <td align="center" style="color:green">
                            <label for=""><b>Pemeriksaan</b></label>
                            </td>
                            <td align="center" style="color:green">
                            <label for=""><b>Diagnosa Masuk</b></label>
                            </td>
                            <td align="center" style="color:green">
                            <label for=""><b>Terapi/ Resep</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" >
                            <textarea name="kelawal" id="kelawal" cols="auto" rows="5"><?= $ttv->keluhanawal ?></textarea>
                            </td>
                            <td align="center" >
                            <textarea name="pemeriksaan" id="pemeriksaan" cols="auto" rows="5"><?= $ttv->pfisik ?></textarea>
                            </td>
                            <td align="center" >
                            <textarea name="diagmas" id="diagmas" cols="auto" rows="5"><?= $ttv->diagnosa ?></textarea>
                            </td>
                            <td align="center" >
                            <textarea name="teresep" id="teresep" cols="auto" rows="5"><?= $ttv->resep ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">&nbsp;</td>
                            <td style="border: none;">&nbsp;</td>
                            <td style="border: none;">&nbsp;</td>
                            <td style="border: none;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" style="color:green">
                                <label for=""><b>Diagnosa (Free Text)<b></label>
                            </td>
                            <td align="center" style="color:green">
                                <label for=""><b>Tindakan (Free Text)<b></label>
                            </td>
                            <td align="center" style="color:green">
                                <label for=""><b>Saran Anjuran<b></label>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                            <textarea name="diagnosa" id="diagnosa" cols="auto" rows="5"><?= $ttv->diagu ?></textarea>
                            </td>
                            <td align="center">
                            <textarea name="tindu" id="tindu" cols="auto" rows="5"><?= $ttv->tindu ?></textarea>
                            </td>
                            <td align="center" >
                            <textarea name="anjuran" id="anjuran" cols="auto" rows="5"><?= $ttv->anjuran ?></textarea>
                            </td>
                            <!-- <td style="color:green">&nbsp;</td> -->
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        </table>
                        <table id="datatable_diagnosa" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                            <h4 style="color:green"><b>Diagnosaa ICD 10 dan Procedure ICD 9 coding</b></h4>
                            <thead class="page-breadcrumb breadcrumb">
                                <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                <th class="title-white" width="30%" style="text-align: center">JENIS </th>
                                <th class="title-white" width="45%" style="text-align: center">Icd 10/ Icd 9 diagnosa</th>
                                <th class="title-white" width="20%" style="text-align: center">Utama</th>

                            </thead>

                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach($detilicd as $row){
                                ?>
                                <tr>
                                    <td align="center" >
                                        <button type='button' onclick="hapusBaris_diagnosa(<?php echo $no;?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                        </button>
                                    </td>
                                    <td>
                                        <select name="jenis_diag[]" id="jenis_diag<?php echo $no;?>" class="select2_el_jnsicd form-control input-largex" onchange="getdiag(this.value,<?= $no;?>)">
                                        <option value="<?= $row->jns;?>"><?= ($row->jns=='DG01'?'Diagnosa':'Prosedure') ?></option>
                                        </select>

                                    </td>

                                    <td>
                                        <select name="diag[]" id="diag<?php echo $no;?>" class="select2_el_icdind form-control input-largex" onchange="testing(this.value)">
                                        <option value="<?= $row->jns;?>">
                                        <?= $row->nmdiag; ?></option>
                                        </select>

                                    </td>
                                    <td>
                                        <input name="utama[]" id="utama<?php echo $no;?>" type="checkbox" class="form-control " onclick="cekutm(this.value,<?php echo $no;?>)" <?= ($row->utama==1?'checked':'') ?>>
                                        <input name="utama_hide[]" id="utama_hide<?php echo $no;?>" type="hidden" class="form-control" value="<?= $row->utama; ?>">

                                    </td>

                                </tr>
                                <?php $no++; } ?>

                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambahdiag()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Diagnosa</b> </button>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-right: none;">&nbsp;</td>
                            </tr>
                        </table>
                        <table id="datatable_poli_tindakan" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable " border="1">
                            <h4 style="color:green" ><b>Billing (Silahkan Diisi Harga Dan Tindakan Sesuai Harga Yang Tersedia   )</b></h4>
                            <thead class="page-breadcrumb breadcrumb">
                                <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                <th class="title-white" width="35%" style="text-align: center">TINDAKAN
                                </th>
                                <th class="title-white" width="20%" style="text-align: center">HARGA</th>
                                <th class="title-white" width="20%" style="text-align: center">DOKTER</th>
                                <th class="title-white" width="20%" style="text-align: center">PARAMEDIS</th>

                            </thead>

                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach($detilbilling as $row){
                                ?>
                                <tr>
                                    <td align="center" >
                                        <button type='button' onclick="hapusBaris_tindakan(<?php echo $no;?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                        </button>
                                    </td>
                                    <td>
                                        <select name="kode[]" id="kode<?php echo $no;?>"
                                            class="select2_el_poli_tindakan form-control input-largex" onchange="show_tindakan(this.value, <?php echo $no;?>)">
                                            <option value="<?= $row->kodetarif;?>">
                                            <?= $row->nm_tin; ?></option>
                                        </select>

                                    </td>

                                    <td>
                                        <input name="hrg[]" id="hrg<?php echo $no;?>" type="text" value="<?= $row->tarifdr; ?>" class="form-control rightJustified" readonly>
                                    </td>

                                    <td align="left" >
                                        <input name="dokter[]" id="dokter<?php echo $no;?>" type="text" value="<?= $row->kodokter; ?>"  class="form-control" readonly>
                                    </td>
                                    
                                    <td align="left" >
                                        <input name="paramedis[]" id="paramedis<?php echo $no;?>" type="text" value="<?= $row->koperawat; ?>"  class="form-control" readonly>
                                    </td>

                                    

                                </tr>
                                <?php $no++; } ?>

                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah_billing()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Billing</b> </button>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-right: none;">&nbsp;</td>
                            </tr>
                        </table>

                        <table id="datatable_poli_alkes" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                            <h4 style="color:green" ><b>Bahan Habis Pakai Dan Alkes Di Ruang Praktek Yang Dipakai</b></h4>
                            <thead class="page-breadcrumb breadcrumb">
                                <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                <th class="title-white" width="15%" style="text-align: center">DI BEBANKAN
                                </th>
                                <th class="title-white" width="30%" style="text-align: center">NAMA BARANG</th>
                                <th class="title-white" width="10%" style="text-align: center">SATUAN</th>
                                <th class="title-white" width="10%" style="text-align: center">QTY</th>
                                <th class="title-white" width="15%" style="text-align: center">HARGA</th>
                                <th class="title-white" width="15%" style="text-align: center">TOTAL HARGA</th>

                            </thead>

                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach($detilalkes as $row){
                                ?>
                                <tr>
                                    <td width="2%"  align="center" >
                                        <button type='button' onclick="hapusBaris_alkes(<?php echo $no;?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                        </button>                                        
                                    </td>

                                    <td width="15%">
                                        <input name="bbn[]" id="bbn<?php echo $no;?>" type="checkbox" class="form-control" onclick="cekbbn(this.value,<?= $no;?>)" <?= ($row->dibebankan==1?'checked':'') ?>>

                                        <input name="bbn_hide[]" id="bbn_hide<?php echo $no;?>" type="hidden" class="form-control" value="<?= $row->dibebankan; ?>">

                                    </td>

                                    <td width="30%">
                                        <select name="kdalkes[]" id="kdalkes<?php echo $no;?>"
                                            class="select2_el_farmasi_barang form-control input-largex" onchange="show_alkes(this.value, <?= $no;?>)">
                                            <option value="<?= $row->kodeobat;?>"><?= $row->nm_brg;?>
                                            </option>
                                        </select>

                                    </td>

                                    <td width="10%">
                                        <input name="satalkes[]" id="satalkes<?= $no;?>" value="<?= $row->satuan;?>" type="text" class="form-control" readonly>
                                    </td>
                                    <td width="10%">
                                        <input name="qtyalkes[]" id="qtyalkes<?= $no;?>" value="<?= $row->qty;?>" type="number" class="form-control " >
                                    </td>
                                    <td width="15%">
                                        <input name="hrgalkes[]" id="hrgalkes<?= $no;?>" value="<?= $row->harga;?>" type="text" class="form-control rightJustified" readonly>
                                    </td>
                                    <td width="18%">
                                        <input name="totalkes[]" id="totalkes<?= $no;?>" value="<?= $row->totalharga;?>" type="text" class="form-control rightJustified" readonly>
                                    </td>

                                </tr>
                                <?php $no++; } ?>

                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah_alkes()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Bhp/ Alkes</b> </button>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-right: none;">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab3">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">
                        <a href="<?php echo base_url()?>PendaftaranVRS/entri_igd" class="btn btn-success">
                            <i class="fa fa-plus"></i> CEK
                        </a>
                        </div>
                        <div class="btn-group pull-right" id="filter">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                        <a data-toggle="modal" href="#lupperiodeIGD">Filter Data</a>
                                </li>			
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableIGD" class="table table-striped table-bordered table-hover" cellspacing="0" style="overflow: auto; white-space: nowrap; display: inline-block;">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr>
                                        <th class="title-white text-center" width="1%">No.</th>
                                        <th class="title-white text-center" width="5%">Cab.</th>
                                        <th class="title-white text-center" width="50">User ID</th>
                                        <th class="title-white text-center" width="5%">No. Antri</th>
                                        <th class="title-white text-center">No. Reg</th>
                                        <th class="title-white text-center">No. RM</th>
                                        <th class="title-white text-center">Tgl. Masuk</th>
                                        <th class="title-white text-center">Nama Pasien</th>
                                        <th class="title-white text-center">Tujuan</th>
                                        <th class="title-white text-center">Dokter</th>
                                        <th class="title-white text-center">Jenis Bayar</th>
                                        <th class="title-white" style="text-align: center;width:10%;">No. Kartu</th>
                                        <th class="title-white text-center">Status</th>
                                        <th class="title-white text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab4">
                <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="btn-group">
                            <a href="<?php echo base_url()?>PendaftaranVRS/entri_ri" class="btn btn-success">
                                <i class="fa fa-plus"></i> CEK
                            </a>
                            </div>
                            <div class="btn-group pull-right" id="filter">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                            <a data-toggle="modal" href="#lupperiodeRI">Filter Data</a>
                                    </li>			
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="tableRI" class="table table-striped table-bordered table-hover" cellspacing="0" style="overflow: auto; white-space: nowrap; display: inline-block;">
                                <thead class="page-breadcrumb breadcrumb">
                                    <tr>
                                            <th class="title-white text-center" width="1%">No.</th>
                                            <th class="title-white text-center" width="5%">Cab.</th>
                                            <th class="title-white text-center" width="50">User ID</th>
                                            <th class="title-white text-center" width="5%">No. Antri</th>
                                            <th class="title-white text-center">No. Reg</th>
                                            <th class="title-white text-center">No. RM</th>
                                            <th class="title-white text-center">Tgl. Masuk</th>
                                            <th class="title-white text-center">Nama Pasien</th>
                                            <th class="title-white text-center">Tujuan</th>
                                            <th class="title-white text-center">Dokter</th>
                                            <th class="title-white text-center">Jenis Bayar</th>
                                            <th class="title-white" style="text-align: center;width:10%;">No. Kartu</th>
                                            <th class="title-white text-center">Status</th>
                                            <th class="title-white text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="tab-pane" id="tab5">
            </div>
        </div>
        </div>
    </div>
</form>
    </div>
    </div>
    </div>
    </div>
                    
                    
                    </div>
                </form>
            </div>
                <div class="row">
                    <div class="col-md-12">		
                        <div class="form-actions">
                            <?php if($ttv->resep=='' || $ttv->anjuran=='-' ){?>
                                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>
                            <?php }else{ ?>
                                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Update</b></button>
                            <?php } ?>

                            

                            <div class="btn-group">
                                <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                            </div>
                                
                        </div>							
                    </div>															
                </div>	
                <br>
                </div>
                </div>
                </div>   
            
            </form> 
        </div>
    </div>
    <!-- </div> -->

    <?php
    $this->load->view('template/footer');  
    ?>

    <script>
            
    var idrow   = 2;
    var arr     = [1];
    var rowCount;
    $(document).ready(function() {

        var tgllahire   = '<?= $data_pas->tgllahir?>'.substring(0, 10);
        var tglmasuk    = '<?= $data_pas->tglmasuk?>'.substring(0, 10);
        var doaa        = '<?= $ttv->dead ?>';
        var kodpos      = '<?= $data_pas->kodepos ?>';
        var birthDate   = new Date(tgllahire);
        var usia        = hitung_usia(birthDate);
        if(doaa==1){
            $('[name="nadi"]').prop("readonly", true);
            $('[name="nafas"]').prop("readonly", true);
            $('[name="oksi"]').prop("readonly", true);
            $('[name="suhu"]').prop("readonly", true);
            $('[name="tekanan"]').prop("readonly", true);
            $('[name="tekanan1"]').prop("readonly", true);
        }
        $('#umur').val(usia);
        $('#tgl_dok').val(tglmasuk);
        initailizeSelect2_poli_tindakan(kodpos);

    });

    function show_tindakan(str, id) {
        var vid       = id;
        var dokter    = '<?= $data_pas->nadokter ?>';
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_tin/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // console.log(data);
                var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli)+eval(data.feemedispoli)+eval(data.bhppoli);

;               $('#hrg' + vid).val(formatCurrency1(totalharga));
                $('#dokter' + vid).val(dokter);
                $('#paramedis' + vid).val(dokter);
            }
        });
    }

    function show_alkes(str, id) {
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_alkes/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // console.log(data);
                $('#satalkes' + vid).val(data.satuan1);
                $('#hrgalkes' + vid).val(formatCurrency1(data.hargabeli));
                $('#qtyalkes' + vid).val(1);
                $('#totalkes' + vid).val(formatCurrency1(1*data.hargabeli));
            }
        });
    }

    function c_ceknyeri(cekjns)
    {   
        if(cekjns==1){
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==2){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==3){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==4){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==5){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==6){
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri1').prop('checked', false);
        }else{
            swal({
                title: "Perasaan Nyeri",
                html: "<p>HARUS DI PILIH !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
            return;
        } 
        
    }
    
    function cekbbn(str, id)
    {   
        var vid   = id;
        
        var cekbbn2 = $('#bbn' + vid).is(':checked');
        if(cekbbn2===true){
            $('#bbn_hide' + vid).val(1);
        }else{
            $('#bbn_hide' + vid).val(0);
        }
        
    }

    function cekutm(str, id)
    {   
        var vid   = id;
        
        var cekutm2 = $('#utama' + vid).is(':checked');
        if(cekutm2===true){
            $('#utama_hide' + vid).val(1);
        }else{
            $('#utama_hide' + vid).val(0);
        }
        
    }

    function getdiag(cekjns,rows)
    {   
        if(cekjns=='DG01'){
            var sab= 'ICD10_1998';
        }else if(cekjns=='DG02'){
            var sab= 'ICD9CM_2005';
        }else{
            var sab= '';
        }
        initailizeSelect2_icdind(sab);
        
    }

    function tambahdiag() 
    {

        var table = document.getElementById('datatable_diagnosa');
        rowCount = table.rows.length;
        arr.push(idrow);

        var x   = document.getElementById('datatable_diagnosa').insertRow(rowCount);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        // &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        var button    = "<button type='button' onclick=hapusBaris_diagnosa("+idrow+") class='btn red  justify'><i class='fa fa-trash-o'></i> </button>";

        var jnsdiag   = "<select name='jenis_diag[]' id=jenis_diag"+idrow+" class='select2_el_jnsicd form-control input-largex' onchange='getdiag(this.value,"+idrow+")'> </select>";

        var diagg     = "<select name='diag[]' id=diag"+idrow+" class='select2_el_icdind form-control input-largex' onchange='testing(this.value,"+idrow+")'> </select>";

        var utama     = "<input name='utama[]' id=utama"+idrow+" type='checkbox' class='form-control' onclick='cekutm(this.value,"+idrow+")'>"+
        " <input name='utama_hide[]' id='utama_hide"+idrow+"' type='hidden' class='form-control'>";


        td1.innerHTML   = button;
        td2.innerHTML   = jnsdiag;
        td3.innerHTML   = diagg;
        td4.innerHTML   = utama;
        
        // initailizeSelect2_farmasi_barang();
        initailizeSelect2_jnsicd();
        idrow++;

    }

    function hapusBaris_diagnosa(param){
        var x = document.getElementById('datatable_diagnosa').deleteRow(arr.indexOf(param)+1);
        arr.splice(arr.indexOf(param),1);

        rowCount--;
        console.log('rowCount ' + rowCount);
        total();
    }

    function tambah_billing() 
    {

        var kodpos    = '<?= $data_pas->kodepos ?>';
        var table     = document.getElementById('datatable_poli_tindakan');
        rowCount      = table.rows.length;
        arr.push(idrow);

        var x   = document.getElementById('datatable_poli_tindakan').insertRow(rowCount);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
                                        
        var button    = "<button type='button' onclick=hapusBaris_tindakan("+idrow+") class='btn red'><i class='fa fa-trash-o'></i> </button></td>";

        var tind      = "<select name='kode[]' id=kode"+idrow+" class='select2_el_poli_tindakan form-control input-largex' onchange='show_tindakan(this.value, "+idrow+")'> </select>";

        var hrgt      = "<input name='hrg[]' id=hrg"+idrow+" class='form-control rightJustified' readonly>";

        var dokter    = "<input name='dokter[]' id=dokter"+idrow+" type='text' class='form-control' readonly>";

        var param     = "<input name='paramedis[]' id=paramedis"+idrow+" type='text' class='form-control' readonly>";

        td1.innerHTML   = button;
        td2.innerHTML   = tind;
        td3.innerHTML   = hrgt;
        td4.innerHTML   = dokter;
        td5.innerHTML   = param;

        initailizeSelect2_poli_tindakan(kodpos);
        idrow++;

    }

    function hapusBaris_tindakan(param)
    {
        var x = document.getElementById('datatable_poli_tindakan').deleteRow(arr.indexOf(param)+1);
        arr.splice(arr.indexOf(param),1);

        rowCount--;
        console.log('rowCount ' + rowCount);
        total();
    }

    function tambah_alkes() 
    {

        var table     = document.getElementById('datatable_poli_alkes');
        rowCount      = table.rows.length;
        arr.push(idrow);

        var x   = document.getElementById('datatable_poli_alkes').insertRow(rowCount);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);
        
        var button    = "<button type='button' onclick=hapusBaris_alkes("+idrow+") class='btn red'><i class='fa fa-trash-o'></i> </button>";

        var bbna      = "<input name='bbn[]' id=bbn"+idrow+" type='checkbox' class='form-control' onclick='cekbbn(this.value,"+idrow+")'> <input name='bbn_hide[]' value='1' id='bbn_hide"+idrow+"' type='hidden' class='form-control'>";

        var nmal      = "<select name='kdalkes[]' id=kdalkes"+idrow+" class='select2_el_farmasi_barang form-control input-largex' onchange='show_alkes(this.value, "+idrow+")'> </select>";

        var satal    = "<input name='satalkes[]' id=satalkes"+idrow+" type='text' class='form-control' readonly>";

        var qtya     = "<input name='qtyalkes[]' id=qtyalkes"+idrow+" type='number' class='form-control ' >";

        var hrga     = "<input name='hrgalkes[]' id=hrgalkes"+idrow+" type='text' class='form-control rightJustified' readonly>";

        var tota     = "<input name='totalkes[]' id=totalkes"+idrow+" type='text' class='form-control rightJustified' readonly>";

        td1.innerHTML   = button;
        td2.innerHTML   = bbna;
        td3.innerHTML   = nmal;
        td4.innerHTML   = satal;
        td5.innerHTML   = qtya;
        td6.innerHTML   = hrga;
        td7.innerHTML   = tota;

        initailizeSelect2_farmasi_barang()
        idrow++;

    }

    function hapusBaris_alkes(param)
    {
        var x = document.getElementById('datatable_poli_alkes').deleteRow(arr.indexOf(param)+1);
        arr.splice(arr.indexOf(param),1);

        rowCount--;
        console.log('rowCount ' + rowCount);
        total();
    }

    function testing(diagn)
    {   
        
        // alert(diagn);
        
    }

    function back()
    {
        var thiloc = window.location;
        window.close(thiloc);
    }

    function save()
    {	    
        var cek           = '<?= $statuspu ?>';
        var v_noreg_dok   = $('[name="noreg_dok"]').val();
        var v_rekmed_dok  = $('[name="rekmed_dok"]').val();
        var v_kelawal     = $('[name="kelawal"]').val();
        var v_pemeriksaan = $('[name="pemeriksaan"]').val();
        var v_diagmas     = $('[name="diagmas"]').val();
        var v_teresep     = $('[name="teresep"]').val();
        var v_diagnosa    = $('[name="diagnosa"]').val();
        var v_tindu       = $('[name="tindu"]').val();
        var v_anjuran     = $('[name="anjuran"]').val();
        var v_namapass    = $('[name="nampas_dok"]').val();

        if (v_kelawal=='' || v_kelawal== null){
        swal({
                title: "KELUHAN AWAL",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_pemeriksaan=='' || v_pemeriksaan== null){
        swal({
                title: "PEMERIKSAAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_diagmas=='' || v_diagmas== null){
        swal({
                title: "DIAGNOSA MASUK",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_teresep=='' || v_teresep== null){
        swal({
                title: "TERAPI RESEP",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_diagnosa=='' || v_diagnosa== null){
        swal({
                title: "DIAGNOSA",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_tindu=='' || v_tindu== null){
        swal({
                title: "TINDAKAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",  
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_anjuran=='' || v_anjuran== null){
        swal({
                title: "SARAN ANJURAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        
                
        $.ajax({			
            url: "<?php echo base_url(); ?>poliklinik/ajax_add_dokter/0",
            data:$('#form_periksa_dokter').serialize(),				
            type:'POST',
            dataType : "json",
            success:function(data){ 
            // data1 = JSON.parse(data);
            // alert(data1.status);
                if(data.status=='0'){   
                    swal({
                        title: "DATA PEMERIKSAAN DOKTER",
                        html: 
                            "<p> Nama   : <b>"+v_namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil di Perbarui...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                                location.reload();
                    });	
                }else{
                    swal({
                        title: "DATA PEMERIKSAAN DOKTER",
                        html: 
                            "<p> Nama   : <b>"+v_namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil Tersimpan...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                                location.href = "<?php echo base_url()?>poliklinik/pemeriksaan_dokter/?noreg="+v_noreg_dok+"&rekmed="+v_rekmed_dok+"";
                    });	
                }			
                                    
        
            },
            error:function(data){
                swal('EMR','Data gagal disimpan ...','');   	
            }
        });
    }	

    </script>




    </body>
    </html> 
