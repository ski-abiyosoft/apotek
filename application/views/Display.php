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

<body style="background-image: linear-gradient(#0d6efd, #0000); height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;">

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
    <div class="row justify-content-center mt-5">
      <div class="col-11">
        <div class="card shadow mb-3 border-primary p-3">
          <div class="card-body">
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
              <div class="col-3" style="color: #0a4ba5;">
                <div class="row" style="height: 450px;">
                  <div class="col">
                    <h2>Tips</h2>
                    <p style="text-align: justify;">Usahakan mendaftar via mobile JKN dan reservasi agar mendapat layanan yang cepat</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col">
                    <button type="button" class="btn btn-dark w-100" onclick="modalx()"><i class="fa fa-cogs"></i> Pengaturan</button>
                  </div>
                </div>
              </div>
              <div class="col-9">
                <div class="row">
                  <div class="col-4">
                    <div class="card shadow mb-4 text-white border-0" style="background-color: #0a3a24;">
                      <div class="card-body">
                        <div class="h4 text-center fw-bold">DAFTAR ANTRIAN</div>
                      </div>
                    </div>
                    <div style="overflow-y: scroll; overflow: auto; height: 450px; padding: 10px;">
                      <?php foreach ($dokter as $d) : ?>
                        <div class="card shadow mb-4">
                          <div class="card-body text-center">
                            <div class="h5 fw-bold"><?= $d->nadokter; ?></div>
                            <hr>
                            <?php foreach ($sql as $s) : ?>
                              <div class="h6"><?= $s->noantri; ?></div>
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
                      <?php $no = 1;
                      foreach ($dokter_show as $ds) : ?>
                        <?php $no++; ?>
                        <?php
                        if ($no == 1) {
                          $color = '#0d6efd';
                          $textcolor = 'white';
                        } else if ($no == 2) {
                          $color = '#f9cb9c;';
                          $textcolor = 'black';
                        } else if ($no == 3) {
                          $color = '#198754';
                          $textcolor = 'white';
                        } else if ($no == 4) {
                          $color = '#6c757d';
                          $textcolor = 'white';
                        } else {
                          $color = '';
                          $textcolor = '';
                        }
                        ?>
                        <div class="col-6 mb-4">
                          <div class="card shadow text-center h-100" style="background-color: <?= $color; ?>; color: <?= $textcolor; ?>;">
                            <div class="card-header h-100">
                              <div class="h3 my-auto d-flex justify-content-center"><?= $ds->nadokter; ?></div>
                            </div>
                            <div class="card-body">
                              <div class="h6">Belum Datang</div>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
        <div class="modal-body">
          <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="tutup()">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

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

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>

</html>