<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?> 
      </span>
      &nbsp;-&nbsp;<span class="title-web">FARMASI&nbsp;<small>Pembelian</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">FARMASI</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">Pareto Stok</a>
      </li>
    </ul>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption" style="color: green; font-weight: bold;">DAFTAR OBAT YANG HARUS DIBELI KEMBALI</div>
      </div>
      <div class="portlet-body">
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-md-4">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-4">Lokasi Klinik</label>
                <div class="col-md-8">
                  <select name="cabang" id="cabang" class="form-control select2_all" data-placeholder="Pilih Cabang...">
                    <option value="">Pilih Cabang...</option>
                    <?php foreach($rs as $rs) : ?>
                      <?php if(isset($koders)) { $cek = 'selected'; } else { $cek = ''; } ?>
                      <option value="<?= $rs->koders; ?>"><?= $rs->namars; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-4">Per Tanggal</label>
                <div class="col-md-8">
                  <?php if(isset($tanggal)) { $cek_tgl = date('Y-m-d', strtotime($tanggal)); } else { $cek_tgl = date('Y-m-d'); } ?>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $cek_tgl; ?>">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 text-right">
            <button type="button" class="btn green" onclick="ubah_data()"><i class="fa fa-refresh"></i> Proses</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <form method="POST" id="form-pareto">
                <table id="table-pareto" class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center" style="background-color: green; color: white;">Kode Obat</th>
                      <th class="text-center" style="background-color: green; color: white;">Nama Obat</th>
                      <th class="text-center" style="background-color: green; color: white;">Satuan</th>
                      <th class="text-center" style="background-color: green; color: white;">Qty Jual</th>
                      <th class="text-center" style="background-color: green; color: white;">Sisa Stock</th>
                      <th class="text-center" style="background-color: green; color: white;">Minimum Stock</th>
                      <th class="text-center" style="background-color: green; color: white;">Yang Harus Dibeli</th>
                      <th class="text-center" style="background-color: green; color: white;">Riwayat Pembelian</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($pareto as $p) : ?>
                      <?php if($p->yhdb > 0) : ?>
                      <tr>
                        <td>
                          <?= $p->kodebarang; ?>
                          <input type="hidden" name="kodebarang" id="kodebarang" value="<?= $p->kodebarang; ?>">
                        </td>
                        <td>
                          <?= $p->namabarang; ?>
                          <input type="hidden" name="namabarang" id="namabarang" value="<?= $p->namabarang; ?>">
                        </td>
                        <td>
                          <?= $p->satuan; ?>
                          <input type="hidden" name="satuan" id="satuan" value="<?= $p->satuan; ?>">
                        </td>
                        <td class="text-right">
                          <?= number_format($p->qty_jual); ?>
                          <input type="hidden" name="qty_jual" id="qty_jual" value="<?= $p->qty_jual; ?>">
                        </td>
                        <td class="text-right">
                          <?= number_format($p->sisa_stock); ?>
                          <input type="hidden" name="sisa_stok" id="sisa_stok" value="<?= $p->sisa_stock; ?>">
                        </td>
                        <td class="text-right">
                          <?= number_format($p->min_stock); ?>
                          <input type="hidden" name="min_stok" id="min_stok" value="<?= $p->min_stock; ?>">
                        </td>
                        <td class="text-right">
                          <?= number_format($p->yhdb); ?>
                          <input type="hidden" name="yhdb" id="yhdb" value="<?= $p->yhdb; ?>">
                        </td>
                          <?php $sql = $this->db->query("SELECT d.kodebarang, h.terima_date, h.terima_no, h.koders, h.vendor_id, (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = h.vendor_id) AS vendor_name,
                          ROUND(SUM(d.qty_terima)) AS qty, ROUND(SUM(d.price)) AS price,
                          ROUND(SUM(d.discountrp)) AS discountrp, ROUND(SUM(d.totalrp)) AS totalrp
                          FROM tbl_baranghterima h
                          JOIN tbl_barangdterima d ON d.terima_no = h.terima_no
                          WHERE d.kodebarang = '$p->kodebarang'
                          GROUP BY d.kodebarang, h.terima_no LIMIT 5")->result(); ?>
                          <td>
                            <?php foreach($sql as $s) : ?>
                              <div class="row">
                                <div class="col-md-9">
                                  <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $s->vendor_id; ?>">
                                  <?= date('d-m-Y', strtotime($s->terima_date)); ?><br>
                                  Vendor : <?= $s->vendor_name; ?><br>
                                  Harga : <b class="text-primary"><?= number_format($s->price); ?></b>, 
                                  Diskon : <b class="text-primary"><?= number_format($s->discountrp); ?>,</b>
                                  Harga Net : <b class="text-primary"><?= number_format($s->totalrp); ?></b>
                                </div>
                                <div class="col-md-2">
                                  <button type="button" class="btn btn-primary" onclick="pilih('<?= $p->kodebarang; ?>', '<?= $s->vendor_id; ?>', '<?= $p->qty_jual; ?>', '<?= $p->sisa_stock; ?>', '<?= $p->min_stock; ?>', '<?= $p->yhdb; ?>')">Pilih</button>
                                </div>
                              </div>
                              <hr>
                            <?php endforeach; ?>
                          </td>
                      </tr>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="table-rencana" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="background-color: green; color: white;">Kode Obat</th>
                    <th class="text-center" style="background-color: green; color: white;">Nama Obat</th>
                    <th class="text-center" style="background-color: green; color: white;">Satuan</th>
                    <th class="text-center" style="background-color: green; color: white;">Saldo</th>
                    <th class="text-center" style="background-color: green; color: white;">Qty Rencana</th>
                    <th class="text-center" style="background-color: green; color: white;">Tanggal</th>
                    <th class="text-center" style="background-color: green; color: white;">Vendor</th>
                    <th class="text-center" style="background-color: green; color: white;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($pareto2 as $p2) : ?>
                    <tr>
                      <td><?= $p2->kodebarang; ?></td>
                      <td><?= $p2->namabarang; ?></td>
                      <td><?= $p2->satuan; ?></td>
                      <td class="text-right"><?= number_format($p2->saldo); ?></td>
                      <td class="text-right"><?= number_format($p2->qty_rencana); ?></td>
                      <td><?= date('d-m-Y', strtotime($p2->tanggal)); ?></td>
                      <td><?= $p2->vendor_name; ?></td>
                      <td class="text-center">
                        <?php if($p2->status == 1) { ?>
                          <button type="button" class="btn blue" disabled>DIAJUKAN</button>
                          <button type="button" class="btn red" onclick="batal(<?= $p2->id; ?>)">Batal</button>
                        <?php } else { ?>
                          <button type="button" class="btn green" disabled>DIORDER</button>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-2 text-center">
            <button type="button" style="width: 100%;" class="btn btn-warning" onclick="_urlcetak(1)"><i class="fa fa-print"></i> Cetak</button>
          </div>
          <div class="col-md-2 text-center">
            <button type="button" style="width: 100%;" class="btn btn-success" onclick="_urlcetak(2)"><i class="fa fa-download"></i> Excel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

  var table_pareto;

  $(document).ready(function() {
    $(".select2_all").select2({
      allowClear: true,
    });
    
    table_pareto = $("#table-pareto").DataTable({
      destroy: true,
      "processing": true,
      "responsive": true,
      "scrollCollapse": false,
      "paging": true,
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
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      }, ],
    });

    var table_rencana = $("#table-rencana").DataTable({
      destroy: true,
      "processing": true,
      "responsive": true,
      "scrollCollapse": false,
      "paging": true,
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
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      }, ],
    });
  });

  function ubah_data() {
    var koders = $("#cabang").val();
    var tanggal = $("#tanggal").val();
    location.href = "<?= site_url('Pareto?cabang='); ?>" + koders + "&tanggal=" + tanggal;
  }

  function pilih(kode, vendor, jual, sisa, min, yhdb) {
    $.ajax({
      url: "<?= site_url('Pareto/pareto/'); ?>"+kode+"?vendor="+vendor+"&qty_jual="+jual+"&min_stok="+min+"&sisa_stok="+sisa+"&yhdb="+yhdb,
      type: "POST",
      data: ($('#form-pareto').serialize()),
      dataType: "JSON",
      success: function(data) {
        // console.log(data)
        if(data.status == 1) {
          location.href = '<?= site_url("Pareto"); ?>';
        }
      }
    });
  }

  function _urlcetak(param) {
    if(param == 1) {
      var cekpdf = 1;
    } else {
      var cekpdf = 2;
    }

    url = "<?= site_url('Pareto/cetak/') ?>" + cekpdf;
    window.open(url, '');
  }

  function batal(id) {
    swal({
        title: 'PARETO',
        html: "Data ini akan dibatalkan ?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        confirmButtonText: 'Ya, Batal',
        cancelButtonText: 'Batal'
    }).then(function() {
      $.ajax({
        url: "<?= site_url('Pareto/batal/'); ?>"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
              swal(
                  '',
                  'Data sudah dibatalkan...',
                  ''
              ).then(function() {
                location.href = '<?= site_url("Pareto"); ?>';
              });
          } else {
              swal(
                  '',
                  'Data gagal dibatalkan.',
                  ''
              )
          }
        }
      });
    });
  }
</script>