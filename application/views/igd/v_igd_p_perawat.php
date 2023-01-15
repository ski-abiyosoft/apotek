<?php 
  $this->load->view('template/header');
  $this->load->view('template/body');    
  date_default_timezone_set("Asia/Jakarta");
  $fill_perawat   = $this->session->flashdata("isi_perawat");
  if(isset($fill_perawat)){
    echo "<script>alert('$fill_perawat')</script>";
  }
?>	

<!-- header -->
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?> 
      </span>
      &nbsp;- 
      <span class="title-web">e-HMS <small>IGD</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?= site_url('dashboard');?>">Awal</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= site_url('Igd'); ?>">Instalasi Gawat Darurat</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">Pemeriksaan Perawat</a>
      </li>
    </ul>
  </div>
</div>

<!-- body -->
<form id="form_periksa_perawat" class="form-horizontal" method="post">
  <div class="row">
    <div class="col-md-12">
      <div class="portlet box blue">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-reorder"></i><b>Pemeriksaan Perawat</b>
          </div>
        </div>
        <div class="portlet-body">
          <div class="row">
            <div class="col-md-12">
              <h3 class="form-section col-md-6" style="color:green"><b>Entry Keluhan Awal Dan Tanda Vital</b></h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4">No Registrasi</label>
                <div class="col-md-8">
                  <input readonly value="<?= $data_pas->noreg ?>" id="noreg_per" name="noreg_per" class="form-control" maxlength="10" type="text">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4">Nama Pasien</label>
                <div class="col-md-8">
                  <input readonly value="<?= $data_pas->namapas ?>" id="nampas_per" name="nampas_per"  class="form-control" maxlength="100" type="text">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4">No Rekmed</label>
                <div class="col-md-8">
                  <input readonly value="<?= $data_pas->rekmed ?>" id="rekmed_per" name="rekmed_per" class="form-control" maxlength="100" type="text">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4">Tgl Lahir</label>
                <div class="col-md-8">
                  <input readonly id="tgl_l_per" name="tgl_l_per" class="form-control" type="date" value="" >
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4">Tgl Periksa</label>
                <div class="col-md-8">
                  <input readonly id="tgl_per" name="tgl_per" class="form-control" type="date">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4">Umur </label>
                <div class="col-md-8">
                  <input type="text" value=""  class="form-control" id="umur" name="umur" value="" readonly>	
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4">Poliklinik</label>
                <div class="col-md-8">
                  <select type="text" class="form-control" id="poli_per" name="poli_per" readonly>
                    <option value="<?= $data_pas->kodepos ?>"><?= data_master("tbl_namapos", array("kodepos" => $data_pas->kodepos))->namapost ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4">Jenis Kelamin</label>
                <div class="col-md-8">
                  <?php if($data_pas->jkel == 'P' || $data_pas->jkel == 'L') {
                    $jk = 'Pria';
                  } else {
                    $jk = 'Wanita';
                  } ?>
                  <input readonly id="jenkel_per" name="jenkel_per" class="form-control" type="text" value="<?= $jk; ?>">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:red">Alergi</label>
                <div class="col-md-8">
                  <textarea id="alergi_per" name="alergi_per"  placeholder="" class="form-control"><?= isset($ttv->alergi)? $ttv->alergi : $data_pas->alergi ?></textarea>
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4">Keluhan Awal (anamnese)</label>
                <div class="col-md-8">
                  <textarea id="kelawal_per" name="kelawal_per"  placeholder="" class="form-control" ><?= $ttv->keluhanawal ?></textarea>
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <h3 class="form-section col-md-6" style="color:green"><b>Tanda Vital</b></h3>
          <h3 class="form-section col-md-5" style="color:green"><b>Gizi Dan BMI</b></h3>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Nadi</label>
                <div class="col-md-5">
                  <input name="nadi" value="<?= $ttv->nadi ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">/Menit</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Berat Badan</label>
                <div class="col-md-5">
                  <input name="berat" value="<?= $ttv->beratbadan ?>" id="berat" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">Kg</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Pernafasan</label>
                <div class="col-md-5">
                  <input name="nafas" value="<?= $ttv->nafas ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">/Menit</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Tinggi Badan</label>
                <div class="col-md-5">
                  <input name="tinggi" value="<?= $ttv->tinggibadan ?>" id="tinggi" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">Cm</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">SPO2</label>
                <div class="col-md-5">
                  <input name="oksi" value="<?= $ttv->oksigen ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">%</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">BMI</label>
                <div class="col-md-5">
                  <input name="bmi" value="<?= $ttv->bmi ?>" placeholder="bmi" class="form-control" type="number" readonly>
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Suhu</label>
                <div class="col-md-5">
                  <input name="suhu" value="<?= $ttv->suhu ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label" style="color:green">Celcius</label>
              </div>
            </div>     
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green"></label>
                <div class="col-md-6">
                  <input name="bmi_result" value="<?= $ttv->bmiresult ?>" placeholder="bmi result" class="form-control" type="text" readonly>
                  <span class="help-block"></span>
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green">Tekanan Darah</label>
                <div class="col-md-3">
                  <input name="tekanan" value="<?= $ttv->tdarah ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
                <label class="control-label col-md-1" style="color:green">/</label>
                <div class="col-md-3">
                  <input name="tekanan1" value="<?= $ttv->tdarah1 ?>" placeholder="0" class="form-control" type="number">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <table border="0" style="width:100%">
            <tr>
              <td align="center" width="10%">&nbsp;</td>
              <td width="40%">
                <label class="control-label">Kesimpulan Pemeriksaan Fisik</label>
              </td>
              <td width="5%" class="rightJustified">
                <input type="checkbox" id="doa" name="doa" onclick="cekdoa()" class="form-control" <?= isset($ttv->dead)? ($ttv->dead == 1)? "checked" : "" : "" ?> >
                <input type="hidden" id="doa_hidden" name="doa_hidden">
              </td>
              <td width="45%">
                <b>DOA (Dead On Arrival)
              </td>
            </tr>
            <tr>
              <td>&nbsp; </td>
              <td colspan="3">
                <div class="form-group">
                  <div class="col-md-6"><br>
                    <textarea name="simpulfisik"  class="form-control" cols="10" rows="5" readonly><?= $ttv->pfisik ?></textarea>
                    <span class="help-block"></span>
                  </div>
                </div>
              </td>
            </tr>
          </table>	
          <div class="row">
            <div class="col-md-12">		
              <div class="form-actions">
                <?php if($status_kasir == 0){ if($ttv->nadi=='0' || $ttv->id=='-' ){?>
                  <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>
                <?php }else{ ?>
                  <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Update</b></button>
                <?php } } ?>
                <div class="btn-group">
                  <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                </div>
              </div>							
            </div>															
          </div>
        </div>
      </div>
    </div>
  </div>
</form> 

<?php
  $this->load->view('template/footer');  
?>

<!-- master js -->
<script>
  $(document).ready(function() {
    var tgllahire   = '<?= $data_pas->tgllahir?>'.substring(0, 10);
    var tglmasuk    = '<?= $data_pas->tglmasuk?>'.substring(0, 10);
    var doaa         = '<?= $ttv->dead?>';
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
    $('#tgl_l_per').val(tgllahire);
    $('#tgl_per').val(tglmasuk);
  });

  $('#berat, #tinggi').keyup(function(){  
    var vberat    = $('#berat').val();
    var vtinggi   = $('#tinggi').val();
    if(vberat== null){
      var vberat=0;
    }else{
      var vberat=vberat;
    }

    if(vtinggi== null){
      var vtinggi=0;
    }else{
      var vtinggi=vtinggi;
    }
    
    var tbb   = eval(vtinggi)/100;
    var bmi   = vberat/(tbb*tbb);   

    if(bmi>0){
      bmi2=Math.ceil(bmi);
    }else{
      bmi2=0;
    }
      
    if(bmi>1 && bmi<18.5){
      bmi_res='Under Weight';
    }else if(bmi>18.5 && bmi<=25){
      bmi_res='Normal Weight';
    }else if(bmi>25 && bmi<=30){
      bmi_res='Over Weight';
    }else if(bmi>30 && bmi<1000){
      bmi_res='Obese';
    }else{
      bmi_res='';
    }

    $('[name="bmi"]').val(bmi2);
    $('[name="bmi_result"]').val(bmi_res);  
  });

  function cekdoa() {
    var cekdoa2 = $('#doa').is(':checked');
    if(cekdoa2===true){            
      $('[name="nadi"]').prop("readonly", true);
      $('[name="nafas"]').prop("readonly", true);
      $('[name="oksi"]').prop("readonly", true);
      $('[name="suhu"]').prop("readonly", true);
      $('[name="tekanan"]').prop("readonly", true);
      $('[name="tekanan1"]').prop("readonly", true);
      $('[name="nadi"]').val(0);
      $('[name="nafas"]').val(0);
      $('[name="oksi"]').val(0);
      $('[name="suhu"]').val(0);
      $('[name="tekanan"]').val(0);
      $('[name="tekanan1"]').val(0);
      $('[name="doa_hidden"]').val(1);
    }else{
      $('[name="nadi"]').prop("readonly", false);
      $('[name="nafas"]').prop("readonly", false);
      $('[name="oksi"]').prop("readonly", false);
      $('[name="suhu"]').prop("readonly", false);
      $('[name="tekanan"]').prop("readonly", false);
      $('[name="tekanan1"]').prop("readonly", false);
      $('[name="nadi"]').val('');
      $('[name="nafas"]').val('');
      $('[name="oksi"]').val('');
      $('[name="suhu"]').val('');
      $('[name="tekanan"]').val('');
      $('[name="tekanan1"]').val('');
      $('[name="doa_hidden"]').val(0);
    }
  }

  function back() {
    var thiloc = window.location;
    window.close(thiloc);
  }
</script>

<!-- simpan -->
<script>
  function save() {	    
    var v_nadi          = $('[name="nadi"]').val();
    var v_berat         = $('[name="berat"]').val();
    var v_nafas         = $('[name="nafas"]').val();
    var v_tinggi        = $('[name="tinggi"]').val();
    var v_oksi          = $('[name="oksi"]').val();
    var v_bmi           = $('[name="bmi"]').val();
    var v_bmi_result    = $('[name="bmi_result"]').val();
    var v_suhu          = $('[name="suhu"]').val();
    var v_tekanan       = $('[name="tekanan"]').val();
    var v_tekanan1      = $('[name="tekanan1"]').val();
    var v_doa           = $('[name="doa"]').is(':checked');
    var v_simpulfisik   = $('[name="simpulfisik"]').val();
    var namapass        = $('[name="nampas_per"]').val();
    var doa_hidden      = $('[name="doa_hidden"]').val();


    if (v_nadi=='' || v_nadi== null){
      swal({
        title: "NADI",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_berat=='' || v_berat== null){
      swal({
        title: "BERAT BADAN",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_nafas=='' || v_nafas== null){
      swal({
        title: "PERNAFASAN",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_tinggi=='' || v_tinggi== null){
      swal({
        title: "TINGGI BADAN",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_oksi=='' || v_oksi== null){
      swal({
        title: "SPO2",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_bmi=='' || v_bmi== null){
      swal({
        title: "BMI",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_bmi_result=='' || v_bmi_result== null){
      swal({
        title: "BMI RESULT",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_suhu=='' || v_suhu== null){
      swal({
        title: "SUHU",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_tekanan=='' || v_tekanan== null){
      swal({
        title: "TEKANAN DARAH ATAS",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_tekanan1=='' || v_tekanan1== null){
      swal({
        title: "TEKANAN DARAH BAWAH",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    } 
    if (v_simpulfisik=='' || v_simpulfisik== null){
      swal({
        title: "KESIMPULAN FISIK",
        html: "<p>HARUS DI ISI !</p>",
        type: "error",
        confirmButtonText: "OK" 
      });    
      return;
    }
    
    $.ajax({				
        url:"<?php echo site_url('Igd/ajax_add_per/1')?>",				
        data:$('#form_periksa_perawat').serialize(),				
        type:'POST',
        dataType : "JSON",
        success:function(data){
          if(data.status=='1'){   
            swal({
              title: "DATA PEMERIKSAAN PERAWAT",
              html: 
                "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                "<br>Berhasil di Perbarui...",
              type: "success",
              confirmButtonText: "OK" 
            }).then((value) => {
              location.reload();
            });	
          }else{
            swal({
              title: "DATA PEMERIKSAAN PERAWAT",
              html: 
                "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                "<br>Berhasil Tersimpan...",
              type: "success",
              confirmButtonText: "OK" 
            }).then((value) => {
              location.reload();
            });	
        }
      }, error:function(data){
        swal('EMR','Data gagal disimpan ...','');   	
      }
    });
  }
</script>