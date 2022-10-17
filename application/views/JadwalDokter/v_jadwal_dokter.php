<!-- load header & body -->
<?php  
 $this->load->view('template/header');
 $this->load->view('template/body');
?>

<!-- sub header -->
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span>
      -
      <span class="title-web">KLINIK <small>Pendaftaran - Jadwal Dokter</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url();?>home">
          Awal
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">
          Klinik
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">
          Jadwal Dokter
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- body -->
<div class="row">
  <div class="col-md-12">
    <div class="h3">JADWAL PRAKTEK DOKTER</div>
    <form id="form-modal" method="POST">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="koders">Cabang</label>
                <input type="text" name="koders" class="form-control" value="<?= $koders; ?>" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="unit">Unit</label>
                <select name="unit" id="unit" class="form-control select2_el_poli" onChange="update()" style="width:100%;">
                  <option value="">-- Pilih --</option>
                  <?php foreach($namapos as $pos): ?>
                  <option value="<?= $pos->kodepos;?>"><?= $pos->namapost;?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="nadokter">Dokter</label>
                <select name="kodokter" class="form-control" id="kodokter">
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary" id="proses" type="button" onclick="saveas()" style="margin-top: 23.5px;">Proses</button>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <div class="h4">INFORMASI DOKTER</div>
        <div id="info">
        <div class="row">
          <div class="col-md-6">
            <img src="https://www.hollywoodprimaryschool.co.uk/wp-content/uploads/male-placeholder.jpg" width="100%" alt="gambar" class="immg-rounded">
          </div>
          <div class="col-md-6">
            <div class="h5" id="nadokter"></div>
            <div class="h5" id="nosip"></div>
            <div class="h5" id="hp"></div>
          </div>
        </div>
        <div class="row" style="margin-top:20px;">
          <div class="col-md-12">
            <div class="form-group">
              <label for="catatan">Catatan dari dokter: </label>
              <textarea name="catatan" class="form-control" rows="10" style="border-color:#e0e0d1;"></textarea>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class="col-md-9" style="margin-bottom:50px;margin-top:10px;">
        <div id="calendar" class="abc"></div>
      </div>
    </div>
  </div>
</div>

<!-- modal praktek -->
<div class="modal fade" id="praktekmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">JADWAL PRAKTEK</h4>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <label for="tanggal">Tanggal</label>
                </div>
                <div class="col-md-8">
                  <input type="date" name="tanggal" id="tanggal" class="form-control" readonly>
                </div>
              </div>
              <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <label for="tanggal" style="margin-top:5px;">Jenis</label>
                  <input name="jeniss" id="jeniss" type="hidden">
                </div>
                <div class="col-md-8">
                  <span>
                    <a name="jenis" data-value="1" id="praktek" class="btn" onclick="select(1)" style="width:32.5%;">
                      Praktek
                      <input type="hidden" value="1" name="praktek" class="form-control" required>
                    </a>
                  </span>
                  <span>
                    <a name="jenis" data-value="2" id="cuti" class="btn" onclick="select(2)" style="width:32.5%;">
                      Cuti
                      <input type="hidden" value="2" name="cuti" class="form-control">
                    </a>
                  </span>
                  <span>
                    <a name="jenis" data-value="3" id="libur" class="btn" onclick="select(3)" style="width:32.5%;">
                      Libur
                      <input type="hidden" value="3" name="libur" class="form-control">
                    </a>
                  </span>
                </div>
              </div>
              <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <label for="shift" style="margin-top:5px;">Shift</label>
                </div>
                <div class="col-md-8">
                  <select name="shift" id="shift" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                  </select>
                </div>
              </div>
              <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <label for="dari" style="margin-top:5px;">Jam Praktek</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-5">
                      <input type="time" name="dari" id="dari" class="form-control">
                    </div>
                    <div class="col-md-2">
                      <label for="sampai" style="margin-top:5px;">Sampai</label>
                    </div>
                    <div class="col-md-5">
                      <input type="time" name="sampai" id="sampai" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <label for="quota" style="margin-top:5px;">Quota</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-8">
                      <input type="number" name="quota" id="quota" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <label for="pasien" style="margin-top:5px;">Pasien</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="saveas2()" id="save" class="btn btn-primary">Simpan</button>
          <button class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$("#info").hide();
var b;

var calendar = $('#calendar').fullCalendar({
  editable: true,
  header: {
    left: 'prev',
    center: 'title',
    right: 'next',
  },
  droppable: true, 
  events: "<?php echo base_url('Jadwal_dokter/read'); ?>",
  selectable: true,
  selectHelper: true,
  select: function(start, allDay, data) {
    var start = moment(start, 'DD.MM.YYYY').format('YYYY-MM-DD');
    var kodokter  = $('[name="kodokter"]').val();
    $('#tanggal').val(start);
    if(kodokter != null){
      ondblclick = function() {modal()};
      return false;
    }
    $("#info").hide("500");
  },
  editable:true,
  eventClick:function(event)
  {
    var id = event.id;
    $.ajax({
      url:"<?php echo base_url('Jadwal_dokter/info'); ?>",
      type:"POST",
      data:{id:id},
      dataType:'JSON',
      success:function(data)
      {
        var nadokter = $('#nadokter');
        document.getElementById("nadokter").innerHTML = data.nadokter;
        if(data.nosip == null){
          var nosip = '-';
        } else {
          var nosip = data.nosip;
        }
        document.getElementById("nosip").innerHTML = nosip;
        if(data.hp == null){
          var hp = '-';
        } else {
          var hp = data.hp;
        }
        document.getElementById("hp").innerHTML = hp;
        $("#info").show("500");
      }
    })
  },
});

function modal(){
  $("#info").hide("500");
  $('#praktekmodal').modal('show');
  $('#praktek').removeClass("btn-primary");
  $('#cuti').removeClass("btn-primary");
  $('#libur').removeClass("btn-primary");
  $('#shift').attr('disabled', true);
  $('#dari').attr('readonly', true);
  $('#sampai').attr('readonly', true);
  $('#quota').attr('readonly', true);
}

function update(){
  var select = document.getElementById('unit');
  $.ajax({
    url: "<?= site_url('Jadwal_dokter/get_dokter');?>",
    type: "POST",
    data: ($('#form-modal').serialize()),
    dataType: "JSON",
    success:function(data){
      var opt=data;
      var nadokter = $("#kodokter");
      nadokter.empty();
      $(opt).each(function () {
        var option = $("<option/>");
        option.html(this.nadokter);
        option.val(this.kodokter);
        nadokter.append(option);
      });
    }
  });
}

function saveas(){
  var koders    = $('[name="koders"]').val();
  var unit      = $('[name="unit"]').val();
  var kodokter  = $('[name="kodokter"]').val();
  $.ajax({
    url:'<?php echo site_url('Jadwal_dokter/tambah_data')?>',		
    data:($('#form-modal').serialize()),
    type: "POST",
    dataType: "JSON",
    success:function(data){ 
      $("#proses").attr('disabled', true);
    }
  });
  document.getElementById('proses').innerHTML = 'Sedang melakukan proses...';
  swal({
    title: "Jadwal Dokter",
    html: "Silahkan pilih tanggal dokter di kalender, dengan klik 2 kali pada tanggal kalender",
    type: "success",
    confirmButtonText: "OK" 
  });
}

function saveas2(){
  var tanggal   = $('[name="tanggal"]').val();
  var shift     = $('[name="shift"]').val();
  var dari      = $('[name="dari"]').val();
  var sampai    = $('[name="sampai"]').val();
  var quota     = $('[name="quota"]').val();
  var jenis     = b;
  if(document.getElementById('jeniss').value == '' || document.getElementById('jeniss').value == null){
    $('#praktekmodal').modal('hide');
    swal({
      title: "Jadwal Dokter",
      html: "Silahkan pilih jenis terlebih dahulu",
      type: "error",
      confirmButtonText: "OK" 
    }).then((value) => {
        $('#praktekmodal').modal('show');
    });
  } else if(document.getElementById('jeniss').value == 1) {
    var shift = document.getElementById('shift').value;
    var dari = document.getElementById('dari').value;
    var sampai = document.getElementById('sampai').value;
    var quota = document.getElementById('quota').value;
    if (shift == ''){
      $('#praktekmodal').modal('hide');
      swal({
        title: "Shift",
        html: " Tidak Boleh Kosong .!!!",
        type: "error",
        confirmButtonText: "OK" 
        }).then((value) => {
          $('#praktekmodal').modal('show');
      });
      return;
    }
    if (dari == ''){
      $('#praktekmodal').modal('hide');
      swal({
        title: "Jam mulai",
        html: " Tidak Boleh Kosong .!!!",
        type: "error",
        confirmButtonText: "OK" 
        }).then((value) => {
          $('#praktekmodal').modal('show');
      });
      return;
    }
    if (sampai == ''){
      $('#praktekmodal').modal('hide');
      swal({
        title: "Jam selesai",
        html: " Tidak Boleh Kosong .!!!",
        type: "error",
        confirmButtonText: "OK" 
        }).then((value) => {
          $('#praktekmodal').modal('show');
      });
      return;
    }
    if (quota == ''){
      $('#praktekmodal').modal('hide');
      swal({
        title: "Quota Pasien",
        html: " Tidak Boleh Kosong .!!!",
        type: "error",
        confirmButtonText: "OK" 
        }).then((value) => {
          $('#praktekmodal').modal('show');
      });
      return;
    }
    $.ajax({
      url:'<?php echo site_url('Jadwal_dokter/tambah_data')?>',	
      data:($('#form-modal').serialize()),		
      type:'POST',
      dataType: "JSON",
      success:function(data){
        if(data.status == 0){
          swal({
            title: "Jadwal Dokter",
            html: "Data berhasil tersimpan",
            type: "success",
            confirmButtonText: "OK" 
            }).then((value) => {
              location.href = "<?php echo base_url()?>Jadwal_dokter";
          });
        } else if(data.status == 1) {
          swal({
            title: "Jadwal Dokter",
            html: "Data gagal tersimpan, silahkan cek ulang",
            type: "error",
            confirmButtonText: "OK" 
            }).then((value) => {
              console.log(data);
              location.href = "<?php echo base_url()?>Jadwal_dokter";
          });
        } else {
          swal({
            title: "Jadwal Dokter",
            text: "Ingin mengubah data ini?",
            icon: "warning",
            buttons: true,
            buttons: false,
            dangerMode: true,
          }).then((value) => {
            swal({
              title: "Jadwal Dokter",
              html: "Data berhasil diubah",
              type: "success",
              confirmButtonText: "OK" 
              }).then((value) => {
                location.href = "<?php echo base_url()?>Jadwal_dokter";
            });
          }); 
        }
      }
    });
    $('#praktekmodal').modal('hide');
  } else if(document.getElementById('jeniss').value == 2 || document.getElementById('jeniss').value == 3){
    $.ajax({
      url:'<?php echo site_url('Jadwal_dokter/tambah_data')?>',	
      data:($('#form-modal').serialize()),		
      type:'POST',
      dataType: "JSON",
      success:function(data){
        if(data.status == 0){
          $('#praktekmodal').modal('hide');
          swal({
            title: "Jadwal Dokter",
            html: "Data berhasil tersimpan",
            type: "success",
            confirmButtonText: "OK" 
            }).then((value) => {
              location.href = "<?php echo base_url()?>Jadwal_dokter";
          });
        }
      }
    });
  }
}

function select(a) {
  if(a == 1){
    $('#praktek').addClass("btn-primary");
    $('#libur').removeClass("btn-primary");
    $('#cuti').removeClass("btn-primary");
    b = 1;
    $('#jeniss').val(1);
    $('#shift').attr('disabled', false);
    $('#dari').attr('readonly', false);
    $('#sampai').attr('readonly', false);
    $('#quota').attr('readonly', false);
  } else if(a == 2){
    $('#cuti').addClass("btn-primary");
    $('#praktek').removeClass("btn-primary");
    $('#libur').removeClass("btn-primary");
    b = 2;
    $('#jeniss').val(2);
    $('#shift').attr('disabled', true);
    $('#dari').attr('readonly', true);
    $('#sampai').attr('readonly', true);
    $('#quota').attr('readonly', true);
  } else if(a== 3) {
    $('#libur').addClass("btn-primary");
    $('#praktek').removeClass("btn-primary");
    $('#cuti').removeClass("btn-primary");
    b = 3;
    $('#jeniss').val(3);
    $('#shift').attr('disabled', true);
    $('#dari').attr('readonly', true);
    $('#sampai').attr('readonly', true);
    $('#quota').attr('readonly', true);
  } else if(document.getElementById('praktek').value == '' || document.getElementById('praktek').value == null || document.getElementById('cuti').value == '' || document.getElementById('cuti').value == null || document.getElementById('libur').value == '' || document.getElementById('libur').value == null) {
    swal({
      title: "Jadwal Dokter",
      html: "Silahkan pilih jenis terlebih dahulu",
      type: "success",
      confirmButtonText: "OK" 
    }).then((value) => {
        $('#praktekmodal').modal('show');
    });
  }
}
</script>

<!-- load footer -->
<?php 
  $this->load->view('template/footer_tb');
?>