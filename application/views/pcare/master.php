<?php

    $this->load->view("template/header");
    $this->load->view("template/body");

?>

<style>
    .filterDiv {display:none}
    .show {display:block}
    .master-title {display:block;position:relative;font-size:18px;font-weight:bold;margin-bottom:20px}
    .master-title-count {position:absolute;right:0}
    .table-responsive, .table {margin-bottom:0px !important;padding-bottom:0px !important}
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            &nbsp;-&nbsp;
            <span class="title-web">P-Care <small>Master</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home title-white"></i>&nbsp; 
                <a href="<?php echo base_url()?>dashboard" class="title-white">Awal</a>&nbsp;
                <i class="fa fa-angle-right title-white"></i></li>
            <li>
                <a href="#" class="title-white">P-Care</a>&nbsp;
                <i class="fa fa-angle-right title-white"></i>&nbsp;
            </li>
            <li>
                <a href="#" class="title-white">Master</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue" style="margin-bottom:60px !important">

            <div class="portlet-title" style="border-radius:0px !important">
                <div class="caption">
                    <i class="fa fa-reorder"></i><b>P-CARE Master</b>
                </div>
            </div>
            
            <div class="portlet-body" style="border-radius:0px !important;padding:40px 0px 40px 0px">
                <div class="row">
                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-4">
                        <form id="" class="form-horizontal form-bordered1">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <select type="text" class="selectpicker" id="data" data-live-search="true" data-width="100%" data-title="- Pilih Data Master -">
                                                <?php foreach($list_master as $lmk => $lmv): ?>
                                                    <option value="<?= $lmk ?>"><?= $lmv ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <center><button type="button" class="btn green" id="show_data">Tampilkan Tabel</button></center>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">&nbsp;</div>
                </div>
            </div>

            <div class="portlet-body" style="border-radius:0px !important;border-top:1px solid #ddd;padding:20px">
                <div class="filterDiv 00">
                    <div class="alert alert-info" style="margin:0px">
                        <center>Pilih data diatas untuk ditampilkan</center>
                    </div>
                </div>
                <div class="filterDiv 01">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($diagnosa->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Diagnosa
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Diagnosa</th>
                                    <th>Nama Diagnosa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($diagnosa->result() as $d): ?>
                                    <tr>
                                        <td><?= $d->kdDiag ?></td>
                                        <td><?= $d->nmDiag ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 02">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($dokter->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Dokter
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Dokter</th>
                                    <th>Nama Dokter</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dokter->result() as $d): ?>
                                    <tr>
                                        <td><?= $d->kdDokter ?></td>
                                        <td><?= $d->nmDokter ?></td>
                                        <td><?= $d->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 03">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($faskes->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Faskes
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode PPK</th>
                                    <th>Nama PPK</th>
                                    <th>Alamat</th>
                                    <th>Telpon</th>
                                    <th>Kelas</th>
                                    <th>Kapasitas</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($faskes->result() as $f): ?>
                                    <tr>
                                        <td><?= $f->kdppk ?></td>
                                        <td><?= $f->nmppk ?></td>
                                        <td><?= $f->alamatPpk ?></td>
                                        <td><?= $f->telpPpk ?></td>
                                        <td><?= $f->kelas ?></td>
                                        <td><?= $f->kapasitas ?></td>
                                        <td><?= $f->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 04">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($kdtkp->num_rows, 0, ',', '.') ?> Data</span>
                        # Data KDTKP
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode TKP</th>
                                    <th>Nama TKP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($kdtkp->result() as $k): ?>
                                    <tr>
                                        <td><?= $k->kdTkp ?></td>
                                        <td><?= $k->nmTKP ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 05">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($kesadaran->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Kesadaran
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Kesadaran</th>
                                    <th>Nama Kesadaran</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($kesadaran->result() as $k): ?>
                                    <tr>
                                        <td><?= $k->kdSadar ?></td>
                                        <td><?= $k->nmSadar ?></td>
                                        <td><?= $k->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 06">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($obat->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Obat
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Obat</th>
                                    <th>Nama Obat</th>
                                    <th>Ketersediaan</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($obat->result() as $o): ?>
                                    <tr>
                                        <td><?= $o->kdObat ?></td>
                                        <td><?= $o->nmObat ?></td>
                                        <td><?= number_format($o->sedia, 0, ',', '.') ?></td>
                                        <td><?= $o->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 07">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($poli->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Poli
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Poli</th>
                                    <th>Nama Poli</th>
                                    <th style="text-align:center">Kunjungan Sakit</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($poli->result() as $p): ?>
                                    <tr>
                                        <td><?= $p->kdPoli ?></td>
                                        <td><?= $p->nmPoli ?></td>
                                        <td style="text-align:center"><?= $p->poliSakit == "1"? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td><?= $p->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 08">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($provider->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Provider
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Provider</th>
                                    <th>Nama Provider</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($provider->result() as $p): ?>
                                    <tr>
                                        <td><?= $p->kdProvider ?></td>
                                        <td><?= $p->nmProvider ?></td>
                                        <td><?= $p->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 09">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($sarana->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Sarana
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Sarana</th>
                                    <th>Nama Sarana</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sarana->result() as $s): ?>
                                    <tr>
                                        <td><?= $s->kdSarana ?></td>
                                        <td><?= $s->nmSarana ?></td>
                                        <td><?= $s->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 10">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($spesialis->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Spesialis
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Spesialis</th>
                                    <th>Nama Spesialis</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($spesialis->result() as $s): ?>
                                    <tr>
                                        <td><?= $s->kdSpesialis ?></td>
                                        <td><?= $s->nmSpesialis ?></td>
                                        <td><?= $s->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 11">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($subspesialis->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Sub Spesialis
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Sub Spesialis</th>
                                    <th>Nama Sub Spesialis</th>
                                    <th>Poli Rujuk</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($subspesialis->result() as $s): ?>
                                    <tr>
                                        <td><?= $s->kdSubSpesialis ?></td>
                                        <td><?= $s->nmSubSpesialis ?></td>
                                        <td><?= $s->kdPoliRujuk ?></td>
                                        <td><?= $s->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 12">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($statuspulang->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Status Pulang
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode Status</th>
                                    <th>Nama Status</th>
                                    <th style="text-align:center">Rawat Jalan</th>
                                    <th style="text-align:center">Rawat Inap</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($statuspulang->result() as $s): ?>
                                    <tr>
                                        <td><?= $s->kdStatusPulang ?></td>
                                        <td><?= $s->nmStatusPulang ?></td>
                                        <td style="text-align:center"><?= $s->rawatInap == "1" ? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td style="text-align:center"><?= $s->rawatJalan == "1" ? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td><?= $s->kodeRs ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="filterDiv 13">
                    <div class="master-title">
                        <span class="master-title-count"><?= number_format($tindakan->num_rows, 0, ',', '.') ?> Data</span>
                        # Data Tindakan
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="page-breadcrumb breadcrumb">
                                <tr class="title-white">
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Maks. Tarif</th>
                                    <th style="text-align:center">RJ Tingkat Pertama</th>
                                    <th style="text-align:center">RI Tingkat Pertama</th>
                                    <th style="text-align:center">Promotif</th>
                                    <th>Kode RS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tindakan->result() as $t): ?>
                                    <tr>
                                        <td><?= $t->kdTindakan ?></td>
                                        <td><?= $t->nmTindakan ?></td>
                                        <td>Rp <?= number_format($t->maxTarif, 0, ',', ',') ?></td>
                                        <td style="text-align:center"><?= $t->rjtp == "1" ? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td style="text-align:center"><?= $t->ritp == "1" ? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td style="text-align:center"><?= $t->promotif == "1" ? "<i class='fa fa-check-circle' style='color:green'></i>" : "<i class='fa fa-times-circle' style='color:red'></i>" ?></td>
                                        <td><?= $t->kodeRs ?></td>
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

<?php

    $this->load->view("template/footer_all");
    $this->load->view("template/footer_tb");

?>

<script>
    $(document).ready(function(){
        filterSelection("00");

        $(".table").dataTable({
            ordering: true,
            oLanguage: {
                sSearch: "<i class='fa fa-search fa-fw'></i>&emsp;",
                sSearchPlaceholder: "Cari...",
                sLengthMenu: "Tampilkan _MENU_ Baris",
                sEmptyTable: "<center><span class='text-danger'><i class='fa fa-times'></i>&nbsp; Tidak ada data</span></center>",
                sInfo: "data (_START_ - _END_ baris dipilih) dari <b>_MAX_ total</b> data",
                sInfoEmpty: "",
                sInfoFiltered: "",
                sZeroRecords: "Hasil pencarian tidak ditemukan",
                oPaginate: {
                    sPrevious: "Sebelumnya",
                    sNext: "Berikutnya"
                }
            },

            aLengthMenu: [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"]
            ],
        });
    });

    $("#show_data").on("click", function(){
        var no_master = $("#data").val();
        filterSelection(no_master);
    });
    
    function filterSelection(c) {
    var x, i;
    x = document.getElementsByClassName("filterDiv");
        if (c == "all") c = "";
        for (i = 0; i < x.length; i++) {
            w3RemoveClass(x[i], "show");
            if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
        }
    }

    function w3AddClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
    }
    }

    function w3RemoveClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        while (arr1.indexOf(arr2[i]) > -1) {
        arr1.splice(arr1.indexOf(arr2[i]), 1);     
        }
    }
    element.className = arr1.join(" ");
    }
</script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>