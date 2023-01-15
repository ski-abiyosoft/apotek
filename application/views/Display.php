<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->config->item('nama_app'); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="<?= base_url(); ?>assets/img/hms.ico" rel="shortcut icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
</head>

<!-- background-image: linear-gradient(#0000, #0d6efd);  -->

<body style="height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;">

  <!-- responsive -->
  <style>
    /* For mobile phones: */
    [class*="col-"] {
      width: 100%;
    }

    @media only screen and (min-width: 768px) {

      /* For desktop: */
      .col-1 {
        width: 8.33%;
      }

      .col-2 {
        width: 16.66%;
      }

      .col-3 {
        width: 25%;
      }

      .col-4 {
        width: 33.33%;
      }

      .col-5 {
        width: 41.66%;
      }

      .col-6 {
        width: 50%;
      }

      .col-7 {
        width: 58.33%;
      }

      .col-8 {
        width: 66.66%;
      }

      .col-9 {
        width: 75%;
      }

      .col-10 {
        width: 83.33%;
      }

      .col-11 {
        width: 91.66%;
      }

      .col-12 {
        width: 100%;
      }
    }

    .btn-circle {
      width: 30px;
      height: 30px;
      padding: 6px 0px;
      border-radius: 15px;
      text-align: center;
      font-size: 12px;
      line-height: 1.42857;
    }
  </style>

  <main style="overflow: hidden; height: 100%;" id="bodyx" class="p-3">
    <div class="row mb-1" style="color: #0a4ba5;">
      <div class="col-4 text-center">
        <img src="<?= base_url('assets/img_user/abiyosoft.png'); ?>" width="70%">
      </div>
      <div class="col-5 h2 text-center my-auto fw-bold">
        <span id="tampil_nama_display"><b>Nama Display</b></span>
      </div>
      <div class="col-3 text-center my-auto">
        <input type="hidden" name="id_display" id="id_display">
        <input type="hidden" name="kodokterp1" id="kodokterp1">
        <input type="hidden" name="kodokterp2" id="kodokterp2">
        <input type="hidden" name="kodokterp3" id="kodokterp3">
        <input type="hidden" name="kodokterp4" id="kodokterp4">
        <input type="hidden" name="noreg1" id="noreg1">
        <input type="hidden" name="noreg2" id="noreg2">
        <input type="hidden" name="noreg3" id="noreg3">
        <input type="hidden" name="noreg4" id="noreg4">
        <div class="h6"><?= $tgl; ?>, <span id="jam"></span></div>
      </div>
    </div>
    <div class="row mb-1">
      <div class="col-12">
        <div class="row">
          <div class="col-4 mb-4">
            <div class="card shadow">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="h4 text-center fw-bold" style="color: black;">PENGUMUMAN</div>
                  </div>
                </div>
                <hr>
                <div class="row mb-4" style="margin-top: 25px;">
                  <div class="col">
                    <video controls autoplay style="width: 100%; height: auto;" id="videonya" loop="true">
                      <source src="<?= base_url('assets/video/'); ?>Splash.mp4" type="video/mp4">
                    </video>
                  </div>
                </div>
                <div class="row" style="margin-top: 50px;">
                  <div class="col text-center">
                    <img src="<?= base_url('assets/img_user/qr_abi3.png'); ?>" width="200px">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-8">
            <div class="card shadow">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="h4 text-center fw-bold text-primary">NOMOR ANTRIAN</div>
                    <hr>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-6 mb-2">
                    <div class="card h-100 border-primary">
                      <div class="card-header text-center h-100 bg-primary text-white">
                        <div class="h4" id="nadokter1">Nadokter</div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-5 my-auto">
                            <div id="noantrian_body1" class="text-primary"></div>
                          </div>
                          <div class="col-7 my-auto text-center">
                            <div class="card border-primary">
                              <div class="card-body text-center" style="font-size: 40px; color: #0d6efd;">
                                <div class="fw-bold" id="antrinonya1">-</div>
                                <span style="font-size: 14px;"><b>SEDANG DILAYANI</b></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 mb-2">
                    <div class="card h-100" style="border-color: #f9cb9c;">
                      <div class="card-header h-100 text-center" style="background-color: #f9cb9c; color: white;">
                        <div class="h4" id="nadokter2">Nadokter</div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-5 my-auto">
                            <div id="noantrian_body2"></div>
                          </div>
                          <div class="col-7 my-auto text-center">
                            <div class="card" style="border-color: #f9cb9c;">
                              <div class="card-body text-center" style="font-size: 40px; color: #f9cb9c;">
                                <div class="fw-bold" id="antrinonya2">-</div>
                                <span style="font-size: 14px;"><b>SEDANG DILAYANI</b></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="card h-100" style="border-color: #198754;">
                      <div class="card-header h-100 text-center" style="background-color: #198754; color: white;">
                        <div class="h4" id="nadokter3">Nadokter</div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-5 my-auto">
                            <div id="noantrian_body3"></div>
                          </div>
                          <div class="col-7 my-auto text-center">
                            <div class="card" style="border-color: #198754;">
                              <div class="card-body text-center" style="font-size: 40px; color: #198754;">
                                <div class="fw-bold" id="antrinonya3">-</div>
                                <span style="font-size: 14px;"><b>SEDANG DILAYANI</b></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card h-100" style="border-color: #6c757d;">
                      <div class="card-header text-center h-100" style="background-color: #6c757d; color: white;">
                        <div class="h4" id="nadokter4">Nadokter</div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-5 my-auto">
                            <div id="noantrian_body4"></div>
                          </div>
                          <div class="col-7 my-auto text-center">
                            <div class="card" style="border-color: #6c757d;">
                              <div class="card-body" style="font-size: 40px; color: #6c757d;">
                                <div class="fw-bold" id="antrinonya4">-</div>
                                <span style="font-size: 14px;"><b>SEDANG DILAYANI</b></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive" style="margin-top: -5px;">
      <table border="0" width="100%">
        <tr>
          <td width="33%">
            <button type="button" class="btn text-white my-auto" style="width: 100%; background-color: black;" onclick="modalx()"><i class="fa fa-cogs"></i> Pengaturan</button>
          </td>
          <td width="67%">
            <?php $cbg = $this->db->get_where("tbl_namers", ["koders" => $cabang])->row(); ?>
            <marquee class="h3 fw-bold my-auto" style="color: #0a4ba5;">(<?= $cabang; ?>) - <?= $cbg->namars; ?></marquee>
          </td>
        </tr>
      </table>
    </div>
  </main>

  <div class="modal" tabindex="-1" id="modal-config">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pengaturan Display</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutup()"></button>
        </div>
        <form method="POST" id="form-dokter">
          <div class="modal-body">
            <div class="table-responsive">
              <table id="datatable" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="5%">Hapus</th>
                    <th width="30%">Display</th>
                    <th width="15%">Dokter 1</th>
                    <th width="15%">Dokter 2</th>
                    <th width="15%">Dokter 3</th>
                    <th width="15%">Dokter 4</th>
                    <th width="5%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach($master_display as $md) : ?>
                  <tr id="dokter_tr<?= $no; ?>">
                    <td>
                      <?php if($no == 1) { $dsb = "disabled"; } else { $dsb = ''; } ?>
                      <button type='button' class='btn btn-danger' onclick='hapusbaris(<?= $no; ?>, <?= $md->id; ?>)' <?= $dsb; ?>><i class='fa fa-trash'></i></button>
                    </td>
                    <td>
                      <input type='text' name='nama_display[]' id='nama_display<?= $no; ?>' class='form-control' value="<?= $md->nama_display; ?>">
                    </td>
                    <td>
                      <select name='dokter1_[]' id='dokter1_<?= $no; ?>' class='form-control'>
                        <option value=''>Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <?php if($dk->kodokter == $md->kodokter1) { $d1 = 'selected'; } else { $d1 = ''; } ?>
                          <option value='<?= $dk->kodokter; ?>' <?= $d1; ?>><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name='dokter2_[]' id='dokter2_<?= $no; ?>' class='form-control'>
                        <option value=''>Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <?php if($dk->kodokter == $md->kodokter2) { $d2 = 'selected'; } else { $d2 = ''; } ?>
                          <option value='<?= $dk->kodokter; ?>' <?= $d2; ?>><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name='dokter3_[]' id='dokter3_<?= $no; ?>' class='form-control'>
                        <option value=''>Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <?php if($dk->kodokter == $md->kodokter3) { $d3 = 'selected'; } else { $d3 = ''; } ?>
                          <option value='<?= $dk->kodokter; ?>' <?= $d3; ?>><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name='dokter4_[]' id='dokter4_<?= $no; ?>' class='form-control'>
                        <option value=''>Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <?php if($dk->kodokter == $md->kodokter4) { $d4 = 'selected'; } else { $d4 = ''; } ?>
                          <option value='<?= $dk->kodokter; ?>' <?= $d4; ?>><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td class="text-center">
                      <button type='button' class='btn btn-success' onclick='pilih(<?= $no; ?>)'><i class='fa-regular fa-circle-check'></i></button>
                    </td>
                  </tr>
                  <?php $no++; endforeach; ?>
                </tbody>
              </table>
            </div>
            <button type="button" onclick="tambah()" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Display</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- modal -->
  <script type="text/javascript">
    function modalx() {
      $("#bodyx").css("filter", "blur(10px)");
      $("#modal-config").show();
    }

    function tutup() {
      $("#bodyx").css("filter", "none");
      $("#modal-config").hide();
      var vid = document.getElementById("videonya");
      vid.play();
    }
    
    var idrow = <?= $jum_display + 1; ?>;
    var rowCount;
    var arr = [1];

    function tambah() {
      var table = $("#datatable");

      table.append("<tr id='dokter_tr" + idrow + "'>" +
        "<td><button type='button' class='btn btn-danger' onclick='hapusbaris("+idrow+")'><i class='fa fa-trash'></i></button></td>" +
        "<td><input type='text' name='nama_display[]' id='nama_display"+idrow+"' class='form-control'></td>" +
        "<td><select name='dokter1_[]' id='dokter1_"+idrow+"' class='form-control'><option value=''>Pilih...</option><?php foreach ($dkr as $dk) : ?><option value='<?= $dk->kodokter; ?>'><?= $dk->nadokter; ?></option><?php endforeach; ?></select></td>" +
        "<td><select name='dokter2_[]' id='dokter2_"+idrow+"' class='form-control'><option value=''>Pilih...</option><?php foreach ($dkr as $dk) : ?><option value='<?= $dk->kodokter; ?>'><?= $dk->nadokter; ?></option><?php endforeach; ?></select></td>" +
        "<td><select name='dokter3_[]' id='dokter3_"+idrow+"' class='form-control'><option value=''>Pilih...</option><?php foreach ($dkr as $dk) : ?><option value='<?= $dk->kodokter; ?>'><?= $dk->nadokter; ?></option><?php endforeach; ?></select></td>" +
        "<td><select name='dokter4_[]' id='dokter4_"+idrow+"' class='form-control'><option value=''>Pilih...</option><?php foreach ($dkr as $dk) : ?><option value='<?= $dk->kodokter; ?>'><?= $dk->nadokter; ?></option><?php endforeach; ?></select></td>" +
        "<td><button type='button' class='btn btn-success' onclick='pilih("+idrow+")'><i class='fa-regular fa-circle-check'></i></button></td>" +
        "</tr>");
      idrow++;
    }

    function hapusbaris(param, id_display) {
      $("#dokter_tr" + param).remove();
      $.ajax({
        url: "<?= site_url('Display/hapusbaris/'); ?>"+id_display,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          if(data.status == 1){
            location.href = "<?= site_url('Display') ?>";
          } else {
            location.href = "<?= site_url('Display') ?>";
          }
        }
      });
    }
  </script>


  <!-- realtime -->
  <script>
    function pilih(param){
      var vid = document.getElementById("videonya");
      vid.play();
      $("#id_display").val(param);
      var nama = $("#nama_display"+param).val();
      var kodokter1 = $("#dokter1_"+param).val();
      var kodokter2 = $("#dokter2_"+param).val();
      var kodokter3 = $("#dokter3_"+param).val();
      var kodokter4 = $("#dokter4_"+param).val();
      var cek = "?nama="+nama+"&kodokter1="+kodokter1+"&kodokter2="+kodokter2+"&kodokter3="+kodokter3+"&kodokter4="+kodokter4;
      getnomor(cek);
    }

    function getnomor(cek){
      var id = $("#id_display").val();
      $.ajax({
        url: "<?= site_url('Display/set_dokter/'); ?>"+id+cek,
        data: $('#form-dokter').serialize(),
        type: "POST",
        dataType: "JSON",
        success: function(data){
          tutup();
          $("#tampil_nama_display").text(data.nama_display);
          $("#tampil_nama_display").css("font-weight", "bold");
          $("#nadokter1").text(data.nadokter1);
          $("#nadokter2").text(data.nadokter2);
          $("#nadokter3").text(data.nadokter3);
          $("#nadokter4").text(data.nadokter4);
          $("#kodokterp1").val(data.kodokter1);
          $("#kodokterp2").val(data.kodokter2);
          $("#kodokterp3").val(data.kodokter3);
          $("#kodokterp4").val(data.kodokter4);
          no_sekarang();
          get_noantri();
          get_audio();
          set_audio();
          setInterval(no_sekarang, 1000);
          setInterval(get_noantri, 1000);
          setInterval(get_audio, 1000);
          setTimeout(set_audio, 1000);
        }
      });
    }

    function get_noantri() {
      var kodokter1 = $("#kodokterp1").val();
      var kodokter2 = $("#kodokterp2").val();
      var kodokter3 = $("#kodokterp3").val();
      var kodokter4 = $("#kodokterp4").val();

      // kolom dokter 1
      var param1 = kodokter1;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("noantrian_body1").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "<?php echo base_url(); ?>Display/noantrian1/" + param1, true);
      xhttp.send();

      // kolom dokter 2
      var param2 = kodokter2;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("noantrian_body2").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "<?php echo base_url(); ?>Display/noantrian2/" + param2, true);
      xhttp.send();

      // kolom dokter 3
      var param3 = kodokter3;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("noantrian_body3").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "<?php echo base_url(); ?>Display/noantrian3/" + param3, true);
      xhttp.send();

      // kolom dokter 4
      var param4 = kodokter4;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("noantrian_body4").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "<?php echo base_url(); ?>Display/noantrian4/" + param4, true);
      xhttp.send();
    };

    function no_sekarang() {
      var id = $("#id_display").val();
      $.ajax({
        url: "<?= site_url('Poliklinik/cekpanggil/'); ?>" + id,
        dataType: "JSON",
        data: {},
        success: function(x) {
          // console.log(x)
          document.getElementById('antrinonya1').innerHTML = x.antri1;
          document.getElementById('antrinonya2').innerHTML = x.antri2;
          document.getElementById('antrinonya3').innerHTML = x.antri3;
          document.getElementById('antrinonya4').innerHTML = x.antri4;
          $("#noreg1").val(x.noreg1);
          $("#noreg2").val(x.noreg2);
          $("#noreg3").val(x.noreg3);
          $("#noreg4").val(x.noreg4);
          var param = "?noreg1="+x.noreg1+"&noreg2="+x.noreg2+"&noreg3="+x.noreg3+"&noreg4="+x.noreg4;
          $.ajax({
            url: "<?= site_url('Display/set_id/'); ?>"+id+param,
            type: "POST",
            dataType: "JSON",
          });
        }
      });
    }

    function set_audio() {
      var id = $("#id_display").val();
      $.ajax({
        url: "<?= site_url('Poliklinik/ubahpanggil/'); ?>" + id,
        dataType: "JSON",
        data: {},
        // success: function(x) {
        //   console.log(x)
        // }
      });
    }
  </script>

  <!-- master and jam -->
  <script>
    modalx();
    jam();

    function jam() {
      var e = document.getElementById('jam'),
      d = new Date(), h, m, s;
      h = d.getHours();
      m = set(d.getMinutes());
      s = set(d.getSeconds());
      e.innerHTML = h +':'+ m +':'+ s;
      setTimeout('jam()', 1000);
    }
    
    function set(e) {
      e = e < 10 ? '0'+ e : e;
      return e;
    }
  </script>

  <!-- panggil -->
  <script>
    function get_audio() {
      var id = $("#id_display").val();
      var noreg1 = $("#noreg1").val();
      var noreg2 = $("#noreg2").val();
      var noreg3 = $("#noreg3").val();
      var noreg4 = $("#noreg4").val();
      var param = "?noreg1="+noreg1+"&noreg2="+noreg2+"&noreg3="+noreg3+"&noreg4="+noreg4+"&id="+id;
      $.ajax({
        url: "<?= site_url('Display/panggil/'); ?>"+param,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          console.log(data);
          kodepos = data.kodepos;
          antrino1 = data.antrino1;
          antrino = data.antrino;
          sebut = data.sebut;
          noreg = data.noreg;
          if(data.status == 1){
            playAudio(kodepos, antrino1, antrino, sebut, noreg);
          } else {
            matikan_audio(noreg);
          }
        }
      });
    }

    function matikan_audio(noreg){
      $.ajax({
        url: "<?= site_url('Display/matikan/'); ?>"+noreg,
        type: "POST",
        dataType: "JSON",
        // success: function(data){
          // console.log(data)
        // }
      });
    }

    function alltrim(kata) {
      b = (kata.split(' ').join(''));
      c = (b.replace(/\s/g, ""));
      return c
    }

    function playAudio(kodepos = "Z", noantri1 = "Z", antriangka = "", noantri = "satu", noreg) {
      var bel = new Audio('audio/bell_long.wav');
      var bel2 = new Audio('audio/nomor_antrian.wav');
      var belh = new Audio('audio/' + noantri1 + '.wav');
      var bel4 = new Audio('audio/belas.wav');
      var bel5 = new Audio('audio/puluh.wav');
      var bel7 = new Audio('audio/ratus.wav');
      var to = new Audio('audio/silakan_menuju_ke.wav');
      var poli = new Audio('audio/poli.wav');

      if (kodepos == 'pumum') {
        var cek_pol = new Audio('audio/umum.wav');
      } else if (kodepos == 'pgigi') {
        var cek_pol = new Audio('audio/gigi.wav');
      } else if (kodepos == 'bidan') {
        var cek_pol = new Audio('audio/ibu_dan_anak.wav');
      } else if (kodepos == 'farmasi') {
        var cek_pol = new Audio('audio/customer_service.wav');
      } else if (kodepos == 'ugd') {
        var cek_pol = new Audio('audio/customer_service.wav');
      } else {
        var cek_pol = new Audio('audio/umum.wav');
      }
      totalwaktu = 0;

      setTimeout(function() {
        bel.pause();
        bel.currentTime = 0;
        bel.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1500;
      setTimeout(function() {
        bel2.pause();
        bel2.currentTime = 0;
        bel2.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1500;
      setTimeout(function() {
        belh.pause();
        belh.currentTime = 0;
        belh.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1000;
      if (antriangka <= 11 || antriangka == 100) {
        var bel3 = new Audio('audio/' + noantri + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1500;
      } else if (antriangka > 11 && antriangka < 20) {
        noantri2 = alltrim(noantri.split("belas").join(""));
        var bel3 = new Audio('audio/' + noantri2 + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel4.pause();
          bel4.currentTime = 0;
          bel4.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      } else if (antriangka == 20 || antriangka == 30 || antriangka == 40 || antriangka == 50 || antriangka == 60 || antriangka == 70 || antriangka == 80 || antriangka == 90) {
        noantri2 = alltrim(noantri.split("puluh").join(""));
        var bel3 = new Audio('audio/' + noantri2 + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        // belas
        setTimeout(function() {
          bel5.pause();
          bel5.currentTime = 0;
          bel5.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      } else if (antriangka > 20 && antriangka < 100 && antriangka != 20 && antriangka != 30 && antriangka != 40 && antriangka != 50 && antriangka != 60 && antriangka != 70 && antriangka != 80 && antriangka != 90) {
        noantri2 = alltrim(noantri.split("puluh").join("-"));
        $dat = noantri2.split("-");
        $bel3 = $dat[0];
        $bel6 = $dat[1];
        var bel3 = new Audio('audio/' + $bel3 + '.wav');
        var bel6 = new Audio('audio/' + $bel6 + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        // puluh
        setTimeout(function() {
          bel5.pause();
          bel5.currentTime = 0;
          bel5.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel6.pause();
          bel6.currentTime = 0;
          bel6.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      } else if (antriangka <= 110 || antriangka == 111) {
        noantri2 = alltrim(noantri.split("seratus").join(""));
        var bel3 = new Audio('audio/seratus.wav');
        var bel4 = new Audio('audio/' + noantri2 + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel4.pause();
          bel4.currentTime = 0;
          bel4.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      } else if (antriangka > 111 && antriangka < 120) {
        noantri2 = alltrim(noantri.split("seratus").join(""));
        noantri3 = alltrim(noantri2.split("belas").join(""));
        var bel3 = new Audio('audio/seratus.wav');
        var bel11 = new Audio('audio/' + noantri3 + '.wav');
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel11.pause();
          bel11.currentTime = 0;
          bel11.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel4.pause();
          bel4.currentTime = 0;
          bel4.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      } else if (antriangka > 120 && antriangka < 200) {
        noantri2 = alltrim(noantri.split("seratus").join(""));
        noantri3 = alltrim(noantri2.split("puluh").join("-"));
        $dat = noantri3.split("-");
        $bel8 = $dat[0];
        $bel9 = $dat[1];

        var bel3 = new Audio('audio/seratus.wav');
        var bel8 = new Audio('audio/' + $bel8 + '.wav');
        var bel9 = new Audio('audio/' + $bel9 + '.wav');

        // seratus
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;

        setTimeout(function() {
          bel8.pause();
          bel8.currentTime = 0;
          bel8.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        // puluh
        setTimeout(function() {
          bel5.pause();
          bel5.currentTime = 0;
          bel5.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel9.pause();
          bel9.currentTime = 0;
          bel9.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;

      } else if (antriangka > 200) {

        noantri2 = alltrim(noantri.split("ratus").join("-"));
        noantri3 = alltrim(noantri2.split("puluh").join("-"));
        $dat = noantri3.split("-");
        $bel3 = $dat[0];
        $bel8 = $dat[1];
        $bel9 = $dat[2];

        var bel3 = new Audio('audio/' + $bel3 + '.wav');
        var bel8 = new Audio('audio/' + $bel8 + '.wav');
        var bel9 = new Audio('audio/' + $bel9 + '.wav');

        // seratus
        setTimeout(function() {
          bel3.pause();
          bel3.currentTime = 0;
          bel3.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        // seratus
        setTimeout(function() {
          bel7.pause();
          bel7.currentTime = 0;
          bel7.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;

        setTimeout(function() {
          bel8.pause();
          bel8.currentTime = 0;
          bel8.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        // puluh
        setTimeout(function() {
          bel5.pause();
          bel5.currentTime = 0;
          bel5.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
        setTimeout(function() {
          bel9.pause();
          bel9.currentTime = 0;
          bel9.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;
      }
      setTimeout(function() {
        to.pause();
        to.currentTime = 0;
        to.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1700;

      setTimeout(function() {
        poli.pause();
        poli.currentTime = 0;
        poli.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1000;

      setTimeout(function() {
        cek_pol.pause();
        cek_pol.currentTime = 0;
        cek_pol.play();
      }, totalwaktu);
      totalwaktu = totalwaktu + 1500;

      return matikan_audio(noreg);
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>

</html>