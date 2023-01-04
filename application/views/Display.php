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

  <main style="height: 100%; overflow: hidden;" id="bodyx">
    <div class="row">
      <div class="col p-4">
        <div class="row">
          <div class="col-12">
            <div class="row justify-content-center" style="color: #0a4ba5;">
              <div class="col-3 text-center">
                <img src="<?= base_url('assets/img_user/abiyosoft.png'); ?>" width="100%">
              </div>
              <div class="col-6 h1 text-center my-auto">
                ANTRIAN DOKTER
              </div>
              <div class="col-3 h3 text-center my-auto">
                <?= $tgl; ?>
              </div>
            </div>
            <hr class="text-primary">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-4">
                    <div class="card shadow mb-4 text-white border-0" style="background-color: #0a3a24;">
                      <div class="card-body">
                        <div class="h4 text-center fw-bold">DAFTAR ANTRIAN</div>
                      </div>
                    </div>
                    <div style="overflow-y: scroll; overflow: auto; height: 425px; padding: 10px; margin-bottom: 20px;">
                      <?php foreach ($dokter as $d) : ?>
                        <div class="card shadow mb-4">
                          <div class="card-body text-center">
                            <div class="h5 fw-bold"><?= $d->nadokter; ?></div>
                            <hr>
                            <?php foreach ($sql as $s) : ?>
                              <?php $sql2 = $this->db->get_where("tbl_pasien", ["rekmed" => $s->rekmed])->row(); ?>
                              <?php if ($sql2) : ?>
                                <div style="font-size: 14px;"><?= "<b>" . $s->noantri . "</b> - " . $sql2->namapas; ?></div>
                              <?php else : ?>
                                <div style="font-size: 14px;"><b>-</b> - 0</div>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="col-8">
                    <div class="row">
                      <div class="col">
                        <div class="card shadow mb-4 text-white border-0" style="background-color: #094cb0;">
                          <div class="card-body">
                            <div class="h4 text-center fw-bold">SEDANG DILAYANI</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 mb-4">
                        <div class="card shadow text-center h-100" style="background-color: #0d6efd; color: white;">
                          <div class="card-header h-100">
                            <div class="h4">Nadokter</div>
                          </div>
                          <div class="card-body">
                            <div class="h6" id="antrinonya">Belum Datang</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 mb-4">
                        <div class="card shadow text-center h-100" style="background-color: #f9cb9c; color: black;">
                          <div class="card-header h-100">
                            <div class="h4">Nadokter</div>
                          </div>
                          <div class="card-body">
                            <div class="h6" id="antrinonya">Belum Datang</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 mb-4">
                        <div class="card shadow text-center h-100" style="background-color: #198754; color: white;">
                          <div class="card-header h-100">
                            <div class="h4">Nadokter</div>
                          </div>
                          <div class="card-body">
                            <div class="h6" id="antrinonya">Belum Datang</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 mb-4">
                        <div class="card shadow text-center h-100" style="background-color: #6c757d; color: white;">
                          <div class="card-header h-100">
                            <div class="h4">Nadokter</div>
                          </div>
                          <div class="card-body">
                            <div class="h6" id="antrinonya">Belum Datang</div>
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
        <div class="row">
          <div class="col-4 p-3">
            <button type="button" class="btn btn-dark text-white" style="width: 100%;" onclick="modalx()"><i class="fa fa-cogs"></i> Pengaturan</button>
          </div>
          <div class="col-8 p-3">
            <?php $cbg = $this->db->get_where("tbl_namers", ["koders" => $cabang])->row(); ?>
            <marquee class="h3 fw-bold" style="color: #0a4ba5;">(<?= $cabang; ?>) - <?= $cbg->namars; ?></marquee>
          </div>
        </div>
      </div>
    </div>
  </main>

  <div class="modal" tabindex="-1" id="modal-config">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pengaturan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutup()"></button>
        </div>
        <form method="POST" id="form-dokter">
          <div class="modal-body">
            <div class="table-responsive">
              <table id="datatable" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>Dokter 1</th>
                    <th>Dokter 2</th>
                    <th>Dokter 3</th>
                    <th>Dokter 4</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <button type="button" class="btn btn-success">Pilih</button>
                    </td>
                    <td>
                      <select name="dokter1" id="dokter1" class="form-control">
                        <option value="">Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <option value="<?= $dk->kodokter; ?>"><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name="dokter2" id="dokter2" class="form-control">
                        <option value="">Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <option value="<?= $dk->kodokter; ?>"><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name="dokter3" id="dokter3" class="form-control">
                        <option value="">Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <option value="<?= $dk->kodokter; ?>"><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <select name="dokter4" id="dokter4" class="form-control">
                        <option value="">Pilih...</option>
                        <?php foreach ($dkr as $dk) : ?>
                          <option value="<?= $dk->kodokter; ?>"><?= $dk->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="tutup()">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="save()"><i class="fa fa-save"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function save() {
      var dokter1 = $("#dokter1").val();
      var dokter2 = $("#dokter2").val();
      var dokter3 = $("#dokter3").val();
      var dokter4 = $("#dokter4").val();
      var param = "?dokter1=" + dokter1 + "&dokter2=" + dokter2 + "&dokter3=" + dokter3 + "&dokter4=" + dokter4;
      $.ajax({
        url: "<?= site_url('Display/set_dokter'); ?>"+param,
        type: "POST",
        dataType: "JSON",
      });
    }
  </script>

  <script>
    function modalx() {
      $("#bodyx").css("filter", "blur(10px)");
      $("#modal-config").show();
    }

    function tutup() {
      $("#bodyx").css("filter", "none");
      $("#modal-config").hide();
    }
  </script>

  <script>
    setInterval(function() {
      $.ajax({
        url: "<?= site_url('Poliklinik/cekpanggil/'); ?>",
        dataType: "JSON",
        data: {},
        success: function(x) {
          document.getElementById('antrinonya').innerHTML = x;
        },
        error: function(x) {
          document.getElementById('antrinonya').innerHTML = 90;
        }
      });
    }, 2000);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>

</html>