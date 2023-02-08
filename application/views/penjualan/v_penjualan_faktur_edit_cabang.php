<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span>
      -
      <span class="title-web">APOTEK <small>Penjualan Ke Cabang</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="<?php echo base_url(); ?>dashboard">
          Awal
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="<?php echo base_url(); ?>penjualan_cabang">
          Daftar Resep
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="">
          Entri Resep
        </a>
      </li>
    </ul>
  </div>
</div>
<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i>*Data Baru
    </div>


  </div>

  <div class="portlet-body form">
    <form id="frmpenjualan" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab1" data-toggle="tab">
                Resep
              </a>
            </li>
            <!--li class="">
								<a href="#tab2" data-toggle="tab">
                                   Info
								</a>
							</li-->

          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                    <div class="col-md-6">
                      <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                        <?php
                        if ($header->gudang) {
                          $namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;
                        ?>
                          <option value="<?= $header->gudang; ?>"><?= $namagudang; ?></option>
                        <?php }
                        ?>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Cabang <font color="red">*</font></label>
                    <div class="col-md-9">
                      <select id="cabang" name="cabang" class="form-control select2_el_cabang_all" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                        <?php
                        if ($header->rekmed) {
                          $namars = data_master('tbl_namers', array('koders' => $header->rekmed))->namars; ?>
                          <option value="<?= $header->rekmed; ?>"><?= $namars; ?></option>
                        <?php }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">No. Resep <font color="red">*</font>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="noresep" id="noresep" value="<?= $header->resepno; ?>" class="form-control" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">PO Cabang <font color="red">*</font>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="po_cabang" id="po_cabang" class="form-control" readonly value="<?= $header->pono; ?>">
                    </div>
                    <div class="col-md-3">
                      <button style="width:100%;" type="button" class="btn btn-secondary" id="btn_po_cabang" disabled onclick="lookup()">LOOKUP</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Jenis <font color="red">*</font></label>
                    <div class="col-md-6">
                      <select name="pembeli" class="form-control" onchange="getdataklinik()">
                        <option <?= ($posting->kodepel == 'FARMASI' ? 'selected' : '') ?> value="FARMASI">Farmasi
                        </option>
                        <option <?= ($posting->kodepel == 'UMUM' ? 'selected' : '') ?> value="UMUM">
                          Umum</option>

                      </select>

                    </div>



                  </div>
                </div>

                <div class="col-md-6">

                  <div class="form-group">
                    <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                    <div class="col-md-6">
                      <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($header->tglresep)); ?>" />

                    </div>
                    <!--label class="col-md-1 control-label">Jam<font color="red">*</font></label-->
                    <div class="col-md-3">
                      <input type="time" class="form-control" name="jam" id="jam" value="<?= date('H:i:s', strtotime($header->jam)); ?>">

                    </div>

                  </div>
                </div>


              </div>



              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Pembeli <font color="red">*</font>
                    </label>
                    <div class="col-md-9">
                      <select name="cust_id" id="cust_id" class="form-control select2_el_penjamin">
                        <?php $sql = $this->db->get_where('tbl_penjamin', ['cust_id' => $header->cust_id])->row_array(); ?>
                        <option value="<?= $sql['cust_id']; ?>"><?= $sql['cust_nama']; ?></option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">VAT <font color="red">*</font></label>
                    <div class="col-md-1">
                      <input type="checkbox" name="vat" class="form-control" <?= ($header->pajak == 1 ? 'checked' : '') ?>>

                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Nama Pembeli <font color="red">*</font>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="namapasien" id="namapasien" class="form-control" value="<?= $posting->namapas; ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Faktur Pajak <font color="red">*</font>
                    </label>
                    <div class="col-md-9">
                      <input type="text" name="fakturpajak" id="fakturpajak" class="form-control" value="<?= $header->fakturpajak; ?>">
                    </div>


                  </div>
                </div>
              </div>



              <div class="row">
                <div class="col-md-12">

                  <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                    <thead>
                      <th width="5%" style="text-align: center">Hapus</th>
                      <th width="20%" style="text-align: center">Nama Barang</th>
                      <th width="7%" style="text-align: center">Qty</th>
                      <th width="5%" style="text-align: center">Satuan</th>
                      <th width="10%" style="text-align: center">Harga</th>
                      <th width="5%" style="text-align: center">Disc %</th>
                      <th width="10%" style="text-align: center">Uang Embalase</th>
                      <th width="5%" style="text-align: center">PPN</th>
                      <th width="7%" style="text-align: center">Total Harga</th>
                      <!-- <th width="7%" style="text-align: center">Tgl Kadaluarsa</th>
                                            <th width="7%" style="text-align: center">Aturan Pakai</th>
                                            <th width="20%" style="text-align: center">No. Rak</th> -->
                    </thead>

                    <tbody>
                      <?php
                      $no = 1;
                      foreach ($detil as $row) {
                      ?>
                        <tr id="cabang<?= $no; ?>">
                          <td width="5%">
                            <button type='button' onclick=hapusBarisIni(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button>
                          </td>
                          <?php
                          $ceking = $this->db->get_where('tbl_barangstock', ['gudang' => $header->gudang])->num_rows();
                          if ($ceking > 0) {
                            $brg = 'class = "select2_el_farmasi_barangdata form-control input-largex"';
                          } else {
                            $brg = 'class = "select2_el_log_baranggud form-control input-largex"';
                          }
                          ?>
                          <input type="hidden" id="cekingz" value="<?= $ceking; ?>">
                          <td width="20%">
                            <select name="kode[]" id="kode<?= $no; ?>" <?= $brg; ?> onchange="showbarangname(this.value, <?= $no; ?>)">
                              <option value="<?= $row->kodebarang; ?>">
                                <?= $row->kodebarang . ' | ' . $row->namabarang; ?></option>
                            </select>
                          </td>

                          <td width="7%"><input name="qty[]" onchange="totalline(<?= $no; ?>);total()" value="<?= $row->qty; ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified"></td>
                          <td width="5%"><input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control " value="<?= $row->satuan; ?>" onkeypress="return tabE(this,event)"></td>
                          <td width="10%"><input name="harga[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($row->price); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified"></td>
                          <td width="5%"><input name="disc[]" onchange="totalline(<?= $no; ?>)" value="<?= $row->discount; ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified "></td>
                          <td width="10%"><input name="embalase[]" onchange="totalline(<?= $no; ?>)" value="<?= $row->ongkosracik; ?>" id="embalase<?= $no; ?>" type="text" class="form-control rightJustified "></td>
                          <td><input type="checkbox" name="ppn[]" id="ppn<?= $no; ?>" class="form-control" <?= ($row->ppn == 1 ? 'checked' : '') ?> onchange="totalline(<?= $no; ?>);total()"></td>

                          <td width="7%"><input name="jumlah[]" value="<?= number_format($row->totalrp); ?>" id="jumlah<?= $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()"></td>
                          <!-- <td width="7%"><input name="expire[]" value="<?= $row->exp_date; ?>"
                                                        id="expire<?= $no; ?>" type="text" class="form-control  "></td>
                                                <td width="10%"><input name="aturan[]" value="<?= $row->atpakai; ?>"
                                                        id="aturan<?= $no; ?>" type="text" class="form-control  "></td>
                                                <td width="20%"><input name="norak[]" value="<?= $row->rakno; ?>"
                                                        id="norak<?= $no; ?>" type="text" class="form-control  "></td> -->


                        </tr>
                      <?php $no++;
                      } ?>
                    </tbody>
                  </table>

                  <div class="row">
                    <div class="col-xs-9">
                      <div class="wells">
                        <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                        <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                      </div>
                    </div>


                  </div>


                </div>
              </div>



            </div>
            <!-- tab1-->

            <div class="tab-pane" id="tab2">
              <div class="row">
                <div class="col-md-12">




                </div>
              </div>

            </div>
            <!--tab2-->


          </div>
          <!--tab-->

          <div class="row">
            <div class="col-xs-8">
              <div class="wells">


                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
                <a target="_blank" type="button" href="<?php echo base_url() ?>penjualan_cabang/cetakcab/?id=<?php echo $row->resepno; ?>" class="btn yellow"><i class="fa fa-print"></i> Cetak Faktur</a>
                <a target="_blank" type="button" href="<?php echo base_url() ?>penjualan_cabang/cetakjalan/?id=<?php echo $row->resepno; ?>" class="btn yellow"><i class="fa fa-print"></i> Cetak Surat Jalan</a>

                <div class="btn-group">
                  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>
                </div>
                <div class="btn-group">
                  <a class="btn red" href="<?php echo base_url('Penjualan_cabang/') ?>">
                    <i class="fa fa-undo"></i><b> KEMBALI </b>
                  </a>
                </div>
                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
              </div>
            </div>

            <div class="col-xs-4 invoice-block">
              <div class="well">
                <table>
                  <tr>
                    <td width="40%"><strong>SUB TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>JASA/EMBAL/KAPSUL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vembalase"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>DISKON</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                  </tr>
                  <tr>
                    <td width="40%"><strong>PPN</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vppn"></span></strong></td>
                  </tr>

                  <tr>
                    <td width="40%"><strong>TOTAL</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                  </tr>
                  <input type="hidden" id="tersimpan">
                </table>
              </div>
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

<!-- <div class="modal fade" role="dialog" id="modal-lookup" aria-labelledby="modal-lookup">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="portlet-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                        <ul class="nav nav-tabs">
                            <li class="active" id="farmasi">
                                <a href="#tab1_farmasi" data-toggle="tab">
                                    PO FARMASI
                                </a>
                            </li>
                            <li class="" id="logistik">
                                <a href="#tab2_logistik" data-toggle="tab">
                                    PO LOGISTIK UMUM
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1_farmasi">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="datatable_farmasi" class="table table-hover table-striped table-bordered table-condensed table-scrollable" width="100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <td width="1%">No.</td>
                                                            <td>PO No.</td>
                                                            <td>Tanggal PO</td>
                                                            <td>PO Cabang</td>
                                                            <td>Aksi</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1;
                                                        foreach ($baranghpo as $farmasi) : ?>
                                                            <tr>
                                                                <td><?= $no++; ?></td>
                                                                <td><?= $farmasi->po_no; ?></td>
                                                                <td><?= $farmasi->tglpo; ?></td>
                                                                <td><?= $farmasi->koders; ?></td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-success" type="button" id="selecter" data-po_no="<?= $farmasi->po_no; ?>">Pilih</button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2_logistik">
                                <div class="portlet-body">
                                    <div class="table-responsive">
                                        <table id="datatable_logistik" class="table table-hover table-striped table-bordered table-condensed table-scrollable" width="100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <td width="1%">No.</td>
                                                    <td>PO No.</td>
                                                    <td>Tanggal PO</td>
                                                    <td>PO Cabang</td>
                                                    <td>Aksi</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($apohpolog as $logistik) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $logistik->po_no; ?></td>
                                                        <td><?= $logistik->po_date; ?></td>
                                                        <td><?= $logistik->koders; ?></td>
                                                        <td class="text-center">
                                                            <button class="btn btn-success" type="button" id="selecter_l" data-po_no="<?= $logistik->po_no; ?>">Pilih</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div> -->

<?php
$this->load->view('template/footer');
?>

<!-- <script>
    $(document).ready(function() {
        $(document).on('click', '#selecter', function() {
            var po_no = $(this).data('po_no');
            $('#po_cabang').val(po_no);
            $('#modal-lookup').modal('hide');
            getdatapo(po_no);
        });
        $(document).on('click', '#selecter_l', function() {
            var po_no = $(this).data('po_no');
            $('#po_cabang').val(po_no);
            $('#modal-lookup').modal('hide');
            getdatapo_l(po_no);
        });
    });

    function getdatapo(str) {

        var vtotal = $('#_vtotal').text();
        var xhttp;
        //var str = $('[name=kodepu]').val();
        if (str == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
            $('[id=harga1]').val('');
            $('[id=disc1]').val('');
            totalline(1);
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>Penjualan_cabang/getpo/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    for (i = 0; i <= data.length - 1; i++) {
                        hapus();
                    }

                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah();
                        }

                        x = i + 1;

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');

                        if (data[i].vat == 1) {
                            document.getElementById("tax" + x).checked = true;
                        }
                        document.getElementById("qty" + x).value = data[i].qty_po
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = data[i].price_po;
                        document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("disc" + x).value = 0;

                    }

                }
            });
        }
    }

    function getdatapo_l(str) {

        var vtotal = $('#_vtotal').text();
        var xhttp;
        //var str = $('[name=kodepu]').val();
        if (str == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
            $('[id=harga1]').val('');
            $('[id=disc1]').val('');
            totalline(1);
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>Penjualan_cabang/getpo_l/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    for (i = 0; i <= data.length - 1; i++) {
                        hapus();
                    }

                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah();
                        }

                        x = i + 1;

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');

                        if (data[i].vat == 1) {
                            document.getElementById("tax" + x).checked = true;
                        }
                        document.getElementById("qty" + x).value = data[i].qty_po
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = data[i].price_po;
                        document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("disc" + x).value = 0;

                    }

                }
            });
        }
    }

    $('.select2_filter_koders').select2({
        dropdownParent: $("#modal-lookup .modal-body"),
    });
    var table_farmasi = $('#datatable_farmasi').DataTable({		
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }],
        "lengthMenu": [
            [5, 20, 50, -1],
            [5, 20, 50, 'semua']
        ],
        "oLanguage": {
            "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
            "sInfoEmpty": "",
            "sInfoFiltered": " - Dipilih dari _MAX_ data",
            "sSearch": "Pencarian Data : ",
            "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "sLengthMenu": "_MENU_ Baris",
            "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
            "oPaginate": {
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
            }
        },
        "scrollCollapse": false,
        "paging": true,
        "responsive": true,
    });
    var table_logistik = $('#datatable_logistik').DataTable({
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }],
        "lengthMenu": [
            [5, 20, 50, -1],
            [5, 20, 50, 'semua']
        ],
        "oLanguage": {
            "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
            "sInfoEmpty": "",
            "sInfoFiltered": " - Dipilih dari _MAX_ data",
            "sSearch": "Pencarian Data : ",
            "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "sLengthMenu": "_MENU_ Baris",
            "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
            "oPaginate": {
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
            }
        },
        "scrollCollapse": false,
        "paging": true,
        "responsive": true,
    });
</script> -->

<script>
  // function lookup(){
  //     $('#modal-lookup').modal('show');
  //     $('.modal-title').text('PO CABANG');
  // }

  var idrow = "<?= $jumdata + 1; ?>";
  var idrow2 = "<?= $jumdata + 1; ?>";

  // function tambah() {
  //   var x = document.getElementById('datatable').insertRow(idrow);
  //   var td1 = x.insertCell(0);
  //   var td2 = x.insertCell(1);
  //   var td3 = x.insertCell(2);
  //   var td4 = x.insertCell(3);
  //   var td5 = x.insertCell(4);
  //   var td6 = x.insertCell(5);
  //   var td7 = x.insertCell(6);
  //   var td8 = x.insertCell(7);
  //   var td9 = x.insertCell(8);
  //   var td10 = x.insertCell(9);
  //   var td11 = x.insertCell(10);
  //   var td12 = x.insertCell(11);

  //   var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
  //     ")' class='select2_el_farmasi_barang form-control' ></select>";
  //   td1.innerHTML = akun;
  //   td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
  //     ")' value='1'  type='text' class='form-control rightJustified'  >";
  //   td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
  //   td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
  //     ") value='0'  type='text' class='form-control rightJustified'>";
  //   td5.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
  //     ")' value='0'  type='text' class='form-control rightJustified'  >";
  //   td6.innerHTML = "<input name='embalase[]'   id=embalase" + idrow + " onchange='totalline(" + idrow +
  //     ")' value='0'  type='text' class='form-control'  >";
  //   td7.innerHTML = "<input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totalline(" + idrow +
  //     ")' class='form-control'>";
  //   td8.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
  //     " type='text' class='form-control rightJustified' size='40%'>";
  //   td9.innerHTML = "<input name='expire[]'   id=expire" + idrow + " onchange='totalline(" + idrow +
  //     ")' type='date' class='form-control'  >";
  //   td10.innerHTML = "<input name='aturan[]'   id=aturan" + idrow + " value=''  type='text' class='form-control'  >";
  //   td11.innerHTML = "<input name='norak[]'   id=norak" + idrow + " value=''  type='text' class='form-control'  >";

  //   initailizeSelect2_farmasi_barang();
  //   idrow++;
  // }

  function tambah() {
    var cekingx = $("#cekingz").val();
    console.log(cekingx)
    if (cekingx > 0) {
      var kds = 'class="select2_el_farmasi_barangdata form-control input-largex"';
    } else {
      var kds = 'class = "select2_el_log_baranggud form-control input-largex"';
    }
    var table = $("#datatable");

    table.append("<tr id='cabang" + idrow + "'>" +
      "<td><button type='button' onclick=hapusBarisIni(" + idrow +
      ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
      "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
      ")' " + kds +
      "></select></td>" +
      "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
      ")' value='1'  type='text' class='form-control rightJustified'></td>" +
      "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control'></td>" +
      "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
      ") value='0'  type='text' class='form-control rightJustified'></td>" +
      "<td><input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
      ")' value='0'  type='text' class='form-control rightJustified'></td>" +
      "<td><input name='embalase[]'   id=embalase" + idrow + " onchange='totalline(" + idrow +
      ")' value='0.00'  type='text' class='form-control rightJustified'></td>" +
      "<td><input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totalline(" + idrow +
      ")' class='form-control'></td>" +
      "<td><input name='jumlah[]' value='0' id=jumlah" + idrow +
      " type='text' class='form-control rightJustified' size='40%'></td>" +
      "</tr>");
    initailizeSelect2_farmasi_barangdata();
    initailizeSelect2_log_baranggud();
    idrow++;
  }

  function hapusBarisIni(param) {
    $("#cabang" + param).remove();
    total();
  }

  function tambah2() {
    var x = document.getElementById('datatable2').insertRow(idrow2);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);

    var akun = "<select name='lkode[]' id=lkode" + idrow2 +
      " class='select2_el form-control' ><option value=''>--- Pilih Akun ---</option></select>";

    td1.innerHTML = akun;
    td2.innerHTML = "<input name='ljumlah[]' id=ljumlah" + idrow2 + " onchange='totalline(" + idrow2 +
      ")' value='0'  onchange='total()' type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='lket[]'    id=lket" + idrow2 + " type='text' class='form-control' >";
    initailizeSelect2();
    idrow2++;
  }

  function showbarang(str) {
    var xhttp;
    var cust = $('[name="cust"]').val();
    var str = str + '~' + cust;
    if (str == "") {
      document.getElementById("txtHint").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>penjualan_faktur/getbarang/" + str, true);
    xhttp.send();
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
        //$('#namabarang'+vid).val(data.namabarang);
        $('#sat' + vid).val(data.satuan1);
        $('#harga' + vid).val(formatCurrency1(data.hargajual));
        totalline(vid);
      }
    });

  }


  function save() {
    var tanggal = $('[name="tanggal"]').val();
    var gudang = $('[name="gudang"]').val();
    var pembeli = $('[name="pembeli"]').val();
    var cabang = $('[name="cabang"]').val();
    var total = $('#_vtotal').text();

    if (cabang == "" || gudang == "" || pembeli == "" || total == "0.00" || total == "") {
      swal('PENJUALAN KE CABANG', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    } else {
      $.ajax({
        url: '<?php echo site_url('penjualan_cabang/save/2') ?>',
        data: $('#frmpenjualan').serialize(),
        type: 'POST',

        success: function(data) {
          //document.getElementById("btnsimpan").disabled=true;
          document.getElementById("tersimpan").value = "OK";

          swal({
            title: "PENJUALAN KE CABANG",
            html: "<p> No. Bukti   : <b>" + data + "</b> </p>" +
              "Tanggal :  " + tanggal + "<br>" + "Total:" + total,
            type: "info",
            confirmButtonText: "OK"
          }).then((value) => {
            location.href = "<?php echo base_url() ?>penjualan_cabang";
          });

        },
        error: function(data) {
          swal('PENJUALAN', 'Data gagal disimpan ...', '');

        }
      });
    }
  }


  function hapus() {
    if (idrow > 2) {
      var x = document.getElementById('datatable').deleteRow(idrow - 1);
      idrow--;
      total();
    }
  }

  function separateComma(val) {
    // remove sign if negative
    var sign = 1;
    if (val < 0) {
      sign = -1;
      val = -val;
    }
    // trim the number decimal point if it exists
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

    // add number after decimal point
    if (val.toString().includes('.')) {
      result = result + '.' + val.toString().split('.')[1];
    }
    // return result with - sign if negative
    return sign < 0 ? '-' + result : result;
  }

  function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;
    tembal = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];

      jumlah = row.cells[2].children[0].value;
      harga = row.cells[4].children[0].value;
      diskon = row.cells[5].children[0].value;
      embal = row.cells[6].children[0].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
      var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
      var embal1 = Number(embal.replace(/[^0-9\.]+/g, ""));

      tjumlah = tjumlah + eval(jumlah1 * harga1);
      tembal = tembal + eval(embal1);
      diskon = eval((diskon1 / 100) * jumlah1 * harga1);

      tdiskon = tdiskon + diskon;



      if (document.getElementById('ppn' + i).checked == true) {
        tppn = tppn + (eval(jumlah1 * harga1) * 0.1);
      }
    }

    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
    document.getElementById("_vembalase").innerHTML = separateComma(tembal);
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
    document.getElementById("_vppn").innerHTML = separateComma(tppn);
    document.getElementById("_vtotal").innerHTML = separateComma(tjumlah - tdiskon + tppn + tembal);

    if (tjumlah > 0) {
      document.getElementById("btnsimpan").disabled = false;
    } else {
      document.getElementById("btnsimpan").disabled = true;
    }

  }

  function totalline(id) {

    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[2].children[0].value * harga;
    diskon = (row.cells[5].children[0].value / 100) * jumlah;

    tot = jumlah - diskon + eval(row.cells[6].children[0].value);


    if (document.getElementById('ppn' + id).checked == true) {
      tot = tot * 1.1;
    }

    row.cells[8].children[0].value = formatCurrency1(tot);
    total();

  }

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


      }
    });


  }


  function getdataklinik() {
    var xhttp;
    var str = $('[name=pembeli]').val();

    if (str == "") {

    } else {
      initailizeSelect2_register(str);

    }
  }

  window.onload = function() {
    const noresep = document.getElementById('noresep');
    noresep.focus();
    //document.getElementById('btncetak').disabled=true;
    document.getElementById('btnsimpan').disabled = true;
    document.getElementById('tersimpan').value = "";
  };

  total();
</script>


</body>

</html>