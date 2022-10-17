<?php 
  $this->load->view('template/header');
  $this->load->view('template/body');    	  
?>	
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?></span>
      - <span class="title-web">Master <small>Dokter</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">Master</a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">Dokter</a>
      </li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Daftar Dokter</div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="btn-group"></div>
          <button class="btn btn-success" onclick="add_dokter()"><i class="glyphicon glyphicon-plus"></i> Tambah Dokter</button>
        </div>
        <table id="table_dokter" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
          <thead class="breadcrumb">
            <tr>
              <th class="title-white" style="text-align: center">Cabang</th>
              <th class="title-white" style="text-align: center">Kode</th>
              <th class="title-white" style="text-align: center">Nama</th>
              <th class="title-white" style="text-align: center;" width="25%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($dokter as $d) : ?>
              <tr>
                <td><?= $d->koders; ?></td>
                <td><?= $d->kodokter; ?></td>
                <td>
                  <input type="hidden" id="kodokterx" name="kodokter" value="<?= $d->kodokter ?>">
                  <input type="hidden" id="nadokterx" name="nadokter" value="<?= $d->nadokter ?>">
                  <?= $d->nadokter; ?>
                </td>
                <td style="text-align: center;">
                  <button class="btn btn-sm btn-primary" type="button" title="Edit" onclick="get_data(<?= $d->id; ?>)"><i class="glyphicon glyphicon-edit"></i> </button>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(<?= $d->id; ?>)"><i class="glyphicon glyphicon-trash"></i> </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption" id="label_dokter_poli">Dokter Poli</div>
      </div>
      <div class="portlet-body">
        <form id="form_poli" method="POST">
        <div class="table-toolbar">
          <div class="row">
            <div class="col-md-6">
              <div class="btn-group"></div>
              <button class="btn btn-success" type="button" onclick="tambah()" id="tambah_row_poli"><i class="glyphicon glyphicon-plus"></i> Tambah Poli</button>
            </div>
            <div class="col-md-6">
              <select name="data_dokter" id="data_dokter" class="form-control select2_dokter" onchange="get_data_poli(this.value)">
                <option value=""></option>
                <?php foreach($dokter as $d) : ?>
                  <option value="<?= $d->kodokter ?>"><?= $d->nadokter; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
          <table id="table_poli" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
            <thead class="breadcrumb header-custom">
              <tr>
                <th class="title-white" style="text-align: center">Kode</th>
                <th class="title-white" style="text-align: center">Nama Poli</th>
                <th class="title-white" style="text-align: center;width:5;">Aksi</th>
              </tr>
            </thead>
            <tbody id="poli_poli">
              <tr id="row_poli1">
                <td>
                  <input type="hidden" id="kodokter_dokter" name="kodokter_dokter">
                  <select name="kodokter_poli[]" id="kodokter_poli1" class="select2_kopoli form-control" onchange="get_poli(this.value, 1)">
                    <option value=""></option>
                    <?php foreach($poli as $p) : ?>
                      <option value="<?= $p->kodepos; ?>"><?= $p->kodepos; ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <input type="text" name="nadokter_poli[]" id="nadokter_poli1" class="form-control" readonly>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" id='btnhapus1' onclick="hapusBarisIni(1)"><i class='fa fa-trash-o'></i></button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-12">
              <div class="wells">
                <button type="button" onclick="update_poli()" id="btnupdate_poli" class="btn blue"><i class="fa fa-repeat"></i> Update Poli</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>  
<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
?>

<!-- onload -->
<script>
  $("#poli_poli").hide();
  $("#tambah_row_poli").attr("disabled", true);
  $("#btnupdate_poli").attr("disabled", true);
</script>
<!-- end onload -->

<!-- datatable -->
<script>
  $("#table_dokter").DataTable({
    "processing": true,
    "responsive":true,
    "scrollCollapse": false,
    "paging":true,
    "oLanguage": {
        "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
        "sInfoEmpty": "",
        "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
        "sSearch": "Cari : ",
        "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
        "sLengthMenu": "_MENU_ Baris",
        "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
        "oPaginate": {
        "sPrevious": "Sebelumnya",
        "sNext": "Berikutnya"
        }
    },		
    "aLengthMenu": [
        [5, 15, 20, -1],
        [5, 15, 20, "Semua"]
    ],
  });

  var idrow = 2;
  var rowCount;
  var arr = [1];

  function hapusBarisIni(param) {
    if (idrow > 2) {
      var x = document.getElementById('table_poli').deleteRow(param);
      idrow--;
    }
  }

  function tambah() {
    var table = document.getElementById('table_poli');
    rowCount = table.rows.length;
    arr.push(idrow);

    var x = document.getElementById('table_poli').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    td1.innerHTML = "<tr id='row_poli"+idrow+"'><td><select name='kodokter_poli[]' id='kodokter_poli"+idrow+"' class='select2_kopoli form-control input-largex' onchange='get_poli(this.value, "+idrow+")'><option value=''></option><?php foreach($poli as $p) : ?><option value='<?= $p->kodepos; ?>'><?= $p->kodepos; ?></option><?php endforeach; ?></select></td>";
    td2.innerHTML = "<td><input type='text' name='nadokter_poli[]' id='nadokter_poli"+idrow+"' class='form-control' readonly></td>";
    td3.innerHTML = "<td'><button type='button' onclick='hapusBarisIni(" + idrow + ");' id=btnhapus" + idrow +" class='btn red'><i class='fa fa-trash-o'></td></tr>"
    idrow++;
    $(".select2_kopoli").select2({
      placeholder: "Pilih Poli",
      width: '100%',
    });
  }

  function get_poli(str, id) {
    var xhttp;
    $('#nadokter_poli' + id).val('');
    $.ajax({
      url: "<?php echo base_url(); ?>Master_dokter/master_poli/?kodepos=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#nadokter_poli' + id).val(data.namapost);
      }
    });
  }

  function hapusAllRow(){
    if (idrow > 2) {
      var x = document.getElementById('table_poli').deleteRow(idrow - 1);
      idrow--;
    }
  }

  function get_data_poli(kodokter){
    $("#kodokter_dokter").val(kodokter);
    if(kodokter == ''){
      hapusBarisIni();
      $('[name="kodokter_poli"]').val('');
    } else {
      $.ajax({
        url: "<?= site_url('Master_dokter/get_dokter_poli?kodokter=') ?>"+kodokter,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          // console.log(data)
          if(data.status == 0){
            $("#poli_poli").hide();
            swal({
              title: "DATA POLI",
              html: "Tidak memiliki poli",
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_dokter/index2";
            });
          } else {
            $("#poli_poli").show("200");
            $("#label_dokter_poli").text(data[1].nadokter);
            $("#tambah_row_poli").attr("disabled", false);
            $("#btnupdate_poli").attr("disabled", false);
            for (i = 0; i <= data[0].length - 1; i++) {
              hapusBarisIni(i+1);
            }
            for (i = 0; i <= data[0].length - 1; i++) {
              if (i > 0) {
                tambah();
              }
              x = i + 1;
              var kodokter_poli = $("<option selected></option>").val(data[0][i].kopoli).text(data[0][i].namapost);
              $('#kodokter_poli' + x).append(kodokter_poli).trigger('change');
              document.getElementById("nadokter_poli" + x).value = data[0][i].namapost;
            }
          }
        }
      });
    }
  }

  function update_poli(){
    var kodokter = $("#data_dokter").val();
    $.ajax({
      url: "<?= site_url('Master_dokter/hapus_dlama?kodokter=') ?>"+kodokter,
      type: "POST",
      dataType: "JSON",
      success: function(data){
        if(data.status == 1){
          var table = document.getElementById('table_poli');
          var rowCount = table.rows.length;
          for (i = 1; i < rowCount; i++) {
            var kopoli = $("#kodokter_poli"+i).val();
            var param = "?kopoli="+kopoli+"&kodokter="+kodokter;
            $.ajax({
              url: "<?= site_url('Master_dokter/update_drpoli') ?>"+param,
              type: "POST",
              dataType: "JSON",
            });
          }
          swal({
            title: "UPDATE POLI",
            html: "Berhasil dilakukan !!",
            type: "success",
            confirmButtonText: "OK"
          }).then((value) => {
            location.href = "<?php echo base_url() ?>Master_dokter/index2";
          });
        } else {
          swal({
            title: "UPDATE POLI",
            html: "Gagal dilakukan !!",
            type: "error",
            confirmButtonText: "OK"
          }).then((value) => {
            location.href = "<?php echo base_url() ?>Master_dokter/index2";
          });
        }
      }
    });
  }
</script>
<!-- end datatable -->

<!-- modal add dokter -->
<div class="modal fade" id="modal_tambah_dokter" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Data Dokter</h3>
      </div>
      <div class="modal-body form">
        <form id="form_tambah_dokter" class="form-horizontal" method = "post">
          <input type="hidden" value="" name="id"/> 
          <input type="hidden" class="form-control" id="statusicd" name="statusicd"/>
          <div class="form-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Kode Dokter</label>
                  <div class="col-md-5">
                    <input name="kodokter" id="kodokter" class="form-control input-small" maxlength="100" type="text" readonly placeholder="AUTO">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Alamat</label>
                  <div class="col-md-5">
                    <input name="alamat" id="alamat" placeholder="Alamat" class="form-control" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Nama Dokter</label>
                  <div class="col-md-5">
                    <input name="namadokter" id="namadokter" placeholder="Nama Dokter" class="form-control" maxlength="100" type="text" autofocus>
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Tanggal Masuk</label>
                  <div class="col-md-5">
                    <input name="tglmasuk" id="tglmasuk" value = "<?php echo date('Y-m-d') ?>" placeholder="tglmasuk" class="form-control" type="date" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">NIK / Identitas</label>
                  <div class="col-md-5">
                    <input name="noidentitas" id="noidentitas" placeholder="NIK" class="form-control" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Status</label>
                  <div class="col-md-5">
                    <select name="status" id="status" placeholder="Status" class="form-control">
                        <option value="">Status</option>
                        <option value="ON">ON</option>
                        <option value="OFF">OFF</option>
                    </select>
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5  ">SIP</label>
                  <div class="col-md-5">
                    <input name="sip" id="sip" placeholder="SIP" class="form-control" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>                                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">Tanggal Berhenti</label>
                  <div class="col-md-5">
                    <input name="tglberhenti" id="tglberhenti" placeholder="tglberhenti" class="form-control" type="date" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>		                                
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">NPWP</label>
                  <div class="col-md-5">
                    <input name="npwp" id="npwp" placeholder="NPWP" class="form-control" maxlength="100" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>		                    
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-5">No HP</label>
                  <div class="col-md-5">
                    <input name="nohp" id="nohp" placeholder="No HP" class="form-control" maxlength="100" type="number">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>	
            </div>        
            <div class="portlet box blue">
              <div class="portlet-title"><div class="caption">
                <i class="fa fa-reorder"></i>Data
              </div>
            </div>
            <div class="portlet-body form">									
              <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width" id="modal-tabs">
                  <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="">
                        <a class="nav-link " id="unit-tab" data-toggle="tab" href="#unitpraktek" role="tab" aria-controls="home" aria-selected="true">Unit Praktek</a>
                      </li>
                      <li class="active">
                        <a class="nav-link active" id="lokasi-tab" data-toggle="tab" href="#lokasipraktek" role="tab" aria-controls="profile" aria-selected="false">Lokasi Praktek / Cabang</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane" id="unitpraktek" role="tabpanel" aria-labelledby="unit-tab">
                        <table id="table_unit_praktek" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                          <h4 style="color:green"><b>UNIT PRAKTEK</b></h4>
                          <thead class="page-breadcrumb breadcrumb">
                            <tr>
                              <th class="title-white" width="5%" style="text-align: center">Delete</th>
                              <th class="title-white" style="text-align: center">UNIT PRAKTEK </th>
                            </tr>
                          </thead>
                          <tbody id="unit_row">
                            <tr class="unit-form-input" id="row_unit_praktek1">
                              <td align="center" >
                                <button type='button' onclick="hapusBaris_unit(1)" class='btn red hapus-unit'><i class='fa fa-trash-o'></i></button>
                              </td>
                              <td>
                                <select name='status_unit[]' id='status_unit1' class='select2_unit form-control'>
                                  <?php foreach($poli as $row) { ?>
                                    <option value='<?= $row->kodepos;?>'><?= $row->namapost;?></option>
                                  <?php } ?>
                                </select>
                              </td>
                            </tr>
                          </tbody>                
                        </table>
                        <table>
                          <tr>
                            <td>
                              <div class="row">
                                <div class="col-xs-9">
                                  <div class="wells">
                                    <button type="button" onclick="tambah_unit()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Unit</b> </button>
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
                      <div class="tab-pane active" id="lokasipraktek" role="tabpanel" aria-labelledby="lokasi-tab">
                        <table id="table_lokasi_praktek" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                          <h4 style="color:green"><b>LOKASI PRAKTEK</b></h4>
                          <thead class="page-breadcrumb breadcrumb">
                            <tr>
                              <th class="title-white" width="5%" style="text-align: center">Delete</th>
                              <th class="title-white" style="text-align: center">LOKASI PRAKTEK </th>
                            </tr>
                          </thead>
                          <tbody id="lokasi_row">
                            <tr class="lokasi-form-input" id="row_lokasi_praktek1">
                              <td align="center">
                                <button type='button' onclick="hapusBaris_lokasi(1)" class='btn red hapus-lokasi'><i class='fa fa-trash-o'></i></button>
                              </td>
                              <td>
                                <select name='status_lokasi[]' id='status_lokasi1' placeholder='Status' class='select2_lokasi form-control'>
                                    <?php foreach($namers as $row) { ?>
                                    <option value='<?= $row->koders;?>'><?= $row->namars;?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                              </td>
                            </tr>
                          </tbody>                
                        </table>
                        <table>
                          <tr>
                            <td>
                              <div class="row">
                                <div class="col-xs-9">
                                  <div class="wells">
                                    <button type="button" onclick="tambah_lokasi()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Lokasi</b> </button>
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
                      <div class="row">
                        <div class="col-xs-8"></div>
                      </div>
                    </div>	
                  </div>  
                </div>                    
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><b>Simpan</b></button>
        <button type="button" id="btnUpdate" onclick="update()" class="btn btn-warning"><b>Update</b></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal add dokter -->

<!-- table in modal -->
<script>
  var idrow3 = 2;
  var rowCount3;
  var arr3 = [1];

  function hapusBaris_unit(param) {
    if (idrow3 > 2) {
      var x = document.getElementById('table_unit_praktek').deleteRow(param);
      idrow3--;
    }
  }

  function tambah_unit() {
    var table = document.getElementById('table_unit_praktek');
    rowCount3 = table.rows.length;
    arr3.push(idrow3);

    var x = document.getElementById('table_unit_praktek').insertRow(rowCount3);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    td1.innerHTML = "<tr id='row_unit_praktek"+idrow3+"'><td'><button type='button' onclick='hapusBaris_unit("+idrow3+")' class='btn red hapus-unit'><i class='fa fa-trash-o'></i></button></td>"
    td2.innerHTML = "<td><select name='status_unit[]' id='status_unit"+idrow3+"' class='select2_unit form-control'><?php foreach($poli as $row) { ?><option value='<?= $row->kodepos;?>'><?= $row->namapost;?></option><?php } ?></select></td></tr>";
    idrow3++;
    $(".select2_unit").select2({
      placeholder: "Pilih Unit",
      width: '100%',
      dropdownParent: $("#modal_tambah_dokter")
    });
  }

  var idrow4 = 2;
  var rowCount4;
  var arr4 = [1];

  function hapusBaris_lokasi(param) {
    if (idrow4 > 2) {
      var x = document.getElementById('table_lokasi_praktek').deleteRow(param);
      idrow4--;
    }
  }

  function tambah_lokasi() {
    var table = document.getElementById('table_lokasi_praktek');
    rowCount4 = table.rows.length;
    arr4.push(idrow4);

    var x = document.getElementById('table_lokasi_praktek').insertRow(rowCount4);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    td1.innerHTML = "<tr id='row_lokasi_praktek"+idrow4+"'><td'><button type='button' onclick='hapusBaris_lokasi("+idrow4+")' class='btn red hapus-unit'><i class='fa fa-trash-o'></i></button></td>"
    td2.innerHTML = "<td><select name='status_lokasi[]' id='status_lokasi"+idrow4+"' placeholder='Status' class='select2_lokasi form-control'><?php foreach($namers as $row) { ?><option value='<?= $row->koders;?>'><?= $row->namars;?></option><?php } ?></select></td></tr>";
    idrow4++;
    $(".select2_lokasi").select2({
      placeholder: "Pilih Lokasi",
      width: '100%',
      dropdownParent: $("#modal_tambah_dokter")
    });
  }
</script>
<!-- end table in modal -->

<!-- validation -->
<script>
  function validation_dokter(namadokter, alamat, noidentitas, sip, npwp, nohp, status){
    if(status == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "STATUS",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(namadokter == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "NAMA DOKTER",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(alamat == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "ALAMAT",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(noidentitas == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "NO IDENTITAS / NIK",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(sip == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "NO SIP",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(npwp == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "NO NPWP",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
    if(nohp == ''){
      $("#modal_tambah_dokter").modal('hide');
      swal({
        title: "NO HP",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_tambah_dokter").modal('show');
      });
    }
  }
</script>
<!-- end validation -->

<!-- function save -->
<script>
  function save(){
    var namadokter = $("#namadokter").val();
    var alamat = $("#alamat").val();
    var tglmasuk = $("#tglmasuk").val();
    var noidentitas = $("#noidentitas").val();
    var status = $("#status").val();
    var sip = $("#sip").val();
    var tglberhenti = $("#tglberhenti").val();
    var npwp = $("#npwp").val();
    var nohp = $("#nohp").val();
    if(namadokter != '' && alamat != '' && noidentitas != '' && sip != '' && npwp != '' && nohp != '' && status != ''){
      var param = "?namadokter="+namadokter+"&alamat="+alamat+"&noidentitas="+noidentitas+"&sip="+sip+"&npwp="+npwp+"&nohp="+nohp+"&status="+status+"&tglmasuk="+tglmasuk+"&tglberhenti="+tglberhenti;
      $.ajax({
        url: "<?= site_url('Master_dokter/tambah_dokter') ?>"+param,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          if(data.status == 1){
            var table = document.getElementById('table_unit_praktek');
            var rowCount3 = table.rows.length;
            for (i = 1; i < rowCount3; i++) {
              var status_unit = $("#status_unit"+i).val();
              var param = "?status_unit="+status_unit+"&kodokter="+data.kodokter;
              $.ajax({
                url: "<?= site_url('Master_dokter/simpan_drpoli') ?>"+param,
                type: "POST",
                dataType: "JSON",
              });
            }
            var table2 = document.getElementById('table_lokasi_praktek');
            var rowCount4 = table2.rows.length;
            for (i = 1; i < rowCount4; i++) {
              var status_unit = $("#status_lokasi"+i).val();
              var param = "?status_lokasi="+status_unit+"&kodokter="+data.kodokter;
              $.ajax({
                url: "<?= site_url('Master_dokter/simpan_doktercabang') ?>"+param,
                type: "POST",
                dataType: "JSON",
              });
            }
            swal({
              title: "TAMBAH DOKTER",
              html: "Berhasil dilakukan !",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_dokter/index2";
            });
          } else {
            swal({
              title: "TAMBAH DOKTER",
              html: "Gagal dilakukan !!",
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_dokter/index2";
            });
          }
        }
      });
    } else {
      validation_dokter(namadokter, alamat, noidentitas, sip, npwp, nohp, status);
    }
  }
</script>
<!-- end function save -->

<!-- master select2 -->
<script>
  $(".select2_unit").select2({
    placeholder: "Pilih Unit",
    width: '100%',
    dropdownParent: $("#modal_tambah_dokter")
  });
  $(".select2_lokasi").select2({
    placeholder: "Pilih Lokasi",
    width: '100%',
    dropdownParent: $("#modal_tambah_dokter")
  });
  $(".select2_kopoli").select2({
    placeholder: "Pilih Poli",
    width: '100%',
  });
  $(".select2_dokter").select2({
    placeholder: "Cari Dokter",
    width: '100%',
  });
</script>
<!-- end master select2 -->

<!-- tambah dokter ============================================================================================================================================================================================= -->
<script>
  function add_dokter(){
    $("#modal_tambah_dokter").modal("show");
    $("#btnUpdate").hide();
  }
</script>
<!-- end tambah dokter ============================================================================================================================================================================================= -->

<!-- hapus dokter ============================================================================================================================================================================================= -->
<script>
  function delete_data(id){
    swal({
      title: 'HAPUS DATA',
      html: "Yakin ingin menghapus data ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Belum'
    }).then(function() {
      $.ajax({
        url: "<?= site_url('Master_dokter/hapus_dokter/?id=') ?>"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          if(data.status == 1){
            swal({
              title: "HAPUS DOKTER",
              html: "Berhasil dilakukan !",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_dokter/index2";
            });
          } else {
            swal({
              title: "HAPUS DOKTER",
              html: "Gagal dilakukan !",
              type: "error",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Master_dokter/index2";
            });
          }
        }
      })
    });
  }
</script>
<!-- end hapus dokter ============================================================================================================================================================================================= -->

<!-- data update dokter ============================================================================================================================================================================================= -->
<!-- get data update -->
<script>
  function get_data(id){
    $.ajax({
      url: "<?= site_url('Master_dokter/update_data_dokter?id=') ?>"+id,
      type: "POST",
      dataType: "JSON",
      success: function(data){
        $("#modal_tambah_dokter").modal("show");
        $("#btnSave").hide();
        $("#kodokter").val(data[0].kodokter);
        if(data[0].alamat == null || data[0].alamat == '' || data[0].alamat == 'NULL'){
          alamat = '';
        } else {
          alamat = data[0].alamat;
        }
        if(data[0].nik == null || data[0].nik == '' || data[0].nik == 'NULL'){
          nik = '';
        } else {
          nik = data[0].nik;
        }
        if(data[0].nosip == null || data[0].nosip == '' || data[0].nosip == 'NULL'){
          nosip = '';
        } else {
          nosip = data[0].nosip;
        }
        if(data[0].npwp == null || data[0].npwp == '' || data[0].npwp == 'NULL'){
          npwp = '';
        } else {
          npwp = data[0].npwp;
        }
        if(data[0].hp == null || data[0].hp == '' || data[0].hp == 'NULL'){
          hp = '';
        } else {
          hp = data[0].hp;
        }
        $("#alamat").val(alamat);
        $("#namadokter").val(data[0].nadokter);
        $("#tglmasuk").val(data[0].tglmasuk);
        $("#noidentitas").val(nik);
        $("#status").val(data[0].status);
        $("#sip").val(nosip);
        $("#tglberhenti").val(data[0].tglberhenti);
        $("#npwp").val(npwp);
        $("#nohp").val(hp);
        // unit
        for (i = 0; i <= data[1].length - 1; i++) {
          hapusBaris_unit(i+1);
        }
        for (i = 0; i <= data[1].length - 1; i++) {
          if (i > 0) {
            tambah_unit();
          }
          x = i + 1;

          var status_unit = $("<option selected></option>").val(data[1][i].kopoli).text(data[1][i].namapoli);
          $('#status_unit' + x).append(status_unit).trigger('change');
        }
        // lokasi
        for (i = 0; i <= data[2].length - 1; i++) {
          hapusBaris_lokasi(i+1);
        }
        for (i = 0; i <= data[2].length - 1; i++) {
          if (i > 0) {
            tambah_lokasi();
          }
          x = i + 1;

          var status_lokasi = $("<option selected></option>").val(data[2][i].koders).text(data[2][i].namars);
          $('#status_lokasi' + x).append(status_lokasi).trigger('change');
        }
      }
    });
  }
</script>
<!-- end get data update -->

<!-- function save -->
<script>
  function update(){
    var kodokter = $("#kodokter").val();
    var namadokter = $("#namadokter").val();
    var alamat = $("#alamat").val();
    var tglmasuk = $("#tglmasuk").val();
    var noidentitas = $("#noidentitas").val();
    var status = $("#status").val();
    var sip = $("#sip").val();
    var tglberhenti = $("#tglberhenti").val();
    var npwp = $("#npwp").val();
    var nohp = $("#nohp").val();
    swal({
      title: 'UBAH DATA DOKTER',
      html: "Yakin ingin mengubah data ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ubah',
      cancelButtonText: 'Belum'
    }).then(function() {
      if(namadokter != '' && alamat != '' && noidentitas != '' && sip != '' && npwp != '' && nohp != '' && status != ''){
        var param = "?kodokter="+kodokter+"&namadokter="+namadokter+"&alamat="+alamat+"&noidentitas="+noidentitas+"&sip="+sip+"&npwp="+npwp+"&nohp="+nohp+"&status="+status+"&tglmasuk="+tglmasuk+"&tglberhenti="+tglberhenti;
        $.ajax({
          url: "<?= site_url('Master_dokter/update_dokter') ?>"+param,
          type: "POST",
          dataType: "JSON",
          success: function(data){
            if(data.status == 1){
              var table = document.getElementById('table_unit_praktek');
              var rowCount3 = table.rows.length;
              for (i = 1; i < rowCount3; i++) {
                var status_unit = $("#status_unit"+i).val();
                var param = "?status_unit="+status_unit+"&kodokter="+data.kodokter;
                $.ajax({
                  url: "<?= site_url('Master_dokter/simpan_drpoli') ?>"+param,
                  type: "POST",
                  dataType: "JSON",
                });
              }
              var table2 = document.getElementById('table_lokasi_praktek');
              var rowCount4 = table2.rows.length;
              for (i = 1; i < rowCount4; i++) {
                var status_unit = $("#status_lokasi"+i).val();
                var param = "?status_lokasi="+status_unit+"&kodokter="+data.kodokter;
                $.ajax({
                  url: "<?= site_url('Master_dokter/simpan_doktercabang') ?>"+param,
                  type: "POST",
                  dataType: "JSON",
                });
              }
              swal({
                title: "UBAH DOKTER",
                html: "Berhasil dilakukan !",
                type: "success",
                confirmButtonText: "OK"
              }).then((value) => {
                location.href = "<?php echo base_url() ?>Master_dokter/index2";
              });
            } else {
              swal({
                title: "UBAH DOKTER",
                html: "Gagal dilakukan !!",
                type: "info",
                confirmButtonText: "OK"
              }).then((value) => {
                location.href = "<?php echo base_url() ?>Master_dokter/index2";
              });
            }
          }
        });
      } else {
        validation_dokter(namadokter, alamat, noidentitas, sip, npwp, nohp, status);
      }
    });
  }
</script>
<!-- end function save -->

<!-- end data update dokter ============================================================================================================================================================================================= -->