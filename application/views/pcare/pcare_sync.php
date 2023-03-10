<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	

<style>
    .font-bold {
        font-weight: bold;
    }
    h2 {
        margin: 0;
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .mb-6 {
        margin-bottom: 3rem;
    }

    .text-center {
        text-align: center;
    }

    .text-lg {
        font-size: 2rem;
    }

    .flex {
        display: flex;
    }

    .flex-col {
        flex-direction: column;
    }

    .items-center {
        align-items: center;
    }

    .justify-center {
        justify-content: center;
    }

    .gap-3 {
        gap: 1.5rem;
    }

    .modal-container {
        inset: 0;
        position: fixed;
        background-color: rgba(210, 208, 198, .5);
        z-index: 999999999999999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 30vh;
        max-width: 50vw;
        background-color: #FFFFFF;
        padding: 30px;
        border-radius: 20px;
        border: 3px solid skyblue;
    }

    .icons {
        font-size: 60px;
        padding: 30px;
        color: red;
    }

    .flex-col-center {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    progress::-webkit-progress-bar {
        background-color: white;
        border: 2px solid gray;
        border-radius: 10px;
    }

    progress::-webkit-progress-value {
        background-color: #bada55;
        border-radius: 10px;
    }

    progress::-moz-progress-bar {
        background-color: #bada55;
    }

    progress {
        border-radius: 20px; 
        width: 100%; 
        background-color: white;  
        height: 20px;
    }
</style>

<div class="row flex-col-center">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            &nbsp;- 
            <span class="title-web">P-Care Bridging System <small>Sinkronisasi Database</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url()?>dashboard">
                    Awal
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    P-Care Bridging System
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Sinkronisasi Database
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-12 mb-6">
        <h3 class="text-center"><strong>Sinkronisasi Database BPJS P-Care</strong></h2>
        <form id="sync-form">
            <div class="col-md-12 flex flex-col items-center">
                <div class="col-md-9 mb-3">
                    <label class="col-md-3 control-label" for="item">Item Sinkronisasi:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="item" id="item">
                            <option value="1">1. Dokter dan Tenaga Medis</option>
                            <option value="2">2. Kesadaran</option>
                            <option value="3">3. Poliklinik</option>
                            <option value="4">4. Status Pulang</option>
                            <option value="5">5. Obat</option>
                            <option value="6">6. Tindakan</option>
                            <option value="7">7. Provider Rayonisasi</option>
                            <option value="8">8. Spesialis</option>
                            <option value="9">9. Subspesialis</option>
                            <option value="10">10. Sarana</option>
                            <option value="11">11. Sarana Khusus</option>
                            <option value="12">12. Diagnosa (Opsional)</option>
                            <option value="13">13. Peserta (Opsional)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 mb-3 hidden" id="param1-wrapper">
                    <label class="col-md-3 control-label" for="param1">Parameter Pencarian:</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" id="param1" name="param1">
                    </div>
                </div>
                <div class="col-md-9 mb-3 hidden" id="param2-wrapper">
                    <label class="col-md-3 control-label" for="param2">Jenis Tindakan:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="param2" id="param2">
                            <option value="10">1. Rawat Jalan (RJTP)</option>
                            <option value="20">2. Rawat Inap (RITP)</option>
                            <option value="50">3. Tindakan Promotif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 mb-3 hidden" id="param3-wrapper">
                    <label class="col-md-3 control-label" for="param3">Jenis Perawatan:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="param3" id="param3">
                            <option value="rj">1. Rawat Jalan</option>
                            <option value="ri">2. Rawat Inap</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 mb-3 hidden" id="param4-wrapper">
                    <label class="col-md-3 control-label" for="param4">Pilih Spesialis:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="param4" id="param4">
                            <?php foreach ($spesialis as $value): ?>
                                <option value="<?= $value->id ?>"><?= $value->text ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 mb-3 hidden" id="param5-wrapper">
                    <label class="col-md-3 control-label" for="param5">Pilih Jenis Identitas:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="param5" id="param5">
                            <option value="noka">Kartu BPJS</option>
                            <option value="nik">KTP/Kartu Keluarga</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 flex flex-col items-center">
                    <button type="button" class="btn btn-danger" id="sync" type="submit"><i class="fa fa-refresh"></i> Mulai Sinkronisasi</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <div class="col-md-12" id="console" style="border: 1px solid red; height: 20vh; margin: 0 auto; overflow: auto;" data-totalJobs="0"></div>
    </div>

    <!-- Obat Modal -->
    <div class="modal fade" id="obat-modal" tabindex="-1" role="dialog" aria-labelledby="obat-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="sync-obat"><strong>Sinkronisasi Master Obat</strong></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="master-obat-sync">
                    <table class="table" id="obat-table">
                        <thead>
                            <tr class="breadcrumb">
                                <th class="text-center title-white">Kode Obat</th>
                                <th class="text-center title-white">Nama Obat</th>
                                <th class="text-center title-white">Master Obat</th>
                            </tr>
                        </thead>
                        <tbody id="obat-body"></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sync_obat()">Save changes</button>
            </div>
            </div>
        </div>
    </div>
    <!-- End Obat Modal -->

    <!-- Tindakan Modal -->
    <div class="modal fade" id="tindakan-modal" role="dialog" aria-labelledby="tindakan-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="sync-tindakan"><strong>Sinkronisasi Master Obat</strong></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="master-tindakan-sync">
                    <table class="table" id="tindakan-table">
                        <thead>
                            <tr class="breadcrumb">
                                <th class="text-center title-white">Kode Tidakan</th>
                                <th class="text-center title-white">Nama Tindakan</th>
                                <th class="text-center title-white">Master Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="tindakan-body"></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sync_tindakan()">Save changes</button>
            </div>
            </div>
        </div>
    </div>
    <!-- End Tindakan Modal -->

    <!-- Dokter Modal -->
    <div class="modal fade" id="dokter-modal" role="dialog" aria-labelledby="dokter-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="sync-tindakan"><strong>Sinkronisasi Master DOkter</strong></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="master-dokter-sync">
                    <table class="table" id="dokter-table">
                        <thead>
                            <tr class="breadcrumb">
                                <th class="text-center title-white">Kode Dokter</th>
                                <th class="text-center title-white">Nama Dokter</th>
                                <th class="text-center title-white">Master Dokter</th>
                            </tr>
                        </thead>
                        <tbody id="dokter-body"></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sync_dokter()">Save changes</button>
            </div>
            </div>
        </div>
    </div>
    <!-- End Dokter Modal -->
</div>

<?php 
    $this->load->view('template/footer_tb');
?>

<script>
    var all_obat     = JSON.parse(`<?= json_encode($all_obat) ?>`)
    var all_tindakan = JSON.parse(`<?= json_encode($all_tindakan) ?>`)
    var obat         = JSON.parse(`<?= json_encode($obat) ?>`)
    var tindakan     = JSON.parse(`<?= json_encode($tindakan) ?>`)
    var all_dokter   = JSON.parse(`<?= json_encode($all_dokter) ?>`)
    var dokter       = JSON.parse(`<?= json_encode($dokter) ?>`)

    $(document).ready(() => {
        var syncItem = $("#item").val()
        show_param(syncItem)

        $("#item").change(function (){
            var val = $("#item").val()

            show_param(val)
        })
    })

    function show_param (item_value) {
        $("#param1-wrapper").addClass("hidden")
        $("#param2-wrapper").addClass("hidden")
        $("#param3-wrapper").addClass("hidden")
        $("#param4-wrapper").addClass("hidden")
        $("#param5-wrapper").addClass("hidden")

        if (item_value == 4) {
            $("#param3-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 5 || item_value == 12) {
            $("#param1-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 6) {
            $("#param2-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 9) {
            $("#param4-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 13) {
            $("#param1-wrapper").removeClass("hidden")
            $("#param5-wrapper").removeClass("hidden")
            return
        }
    }

    document.getElementById("sync").addEventListener("click", function () {
        var itemVal = $("#item").val()
        var baseUrl = "<?= base_url() ?>"
        var ajaxUrl = ""

        switch (itemVal) {
            case "1":
                ajaxUrl = baseUrl + "pcare_sync/dokter"
                $.ajax({
                    url: ajaxUrl,
                    success: (data) => {
                        if (data) {
                            $("#console").empty()
                            $("#console").append(`<p>${data.count} data ditemukan.</p>`)
                            $("#dokter-table").dataTable().fnDestroy()
                            $("#dokter-body").empty()

                            data.list.forEach((item) => {
                                $("#dokter-body").append(
                                    /*html*/
                                    `<tr>
                                        <td class="text-center">
                                            ${item.kdDokter}
                                        </td>
                                        <td class="text-center">${item.nmDokter}</td>
                                        <td class="text-center">
                                            <select id="${item.kdDokter}" class="form-control master_dokter" name="master_dokter[${item.kdDokter}]" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>`
                                )
                            })

                            $(".master_dokter").select2({
                                data: all_dokter,
                                placeholder: "Pilih master dokter",
                                dropdownParent: $("#dokter-modal")
                            })

                            dokter.forEach((item) => {
                                var selectEl = $(`#${item.pcare_kdDokter}`)

                                if (selectEl) {
                                    selectEl.val(item.kodokter)
                                    selectEl.trigger("change")
                                }
                            })

                            $("#obat-table").dataTable({
                                bLengthChange: false,
                                iDisplayLength: 5,
                                aoColumns: [
                                    {bSearchable: true},
                                    {bSearchable: true},
                                    {bSearchable: false},
                                ]
                            })

                            $("#dokter-modal").modal("show")
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data!"
                            })
                        }
                    }
                })

                return
                break;
            case "2":
                ajaxUrl = baseUrl + "pcare_sync/kesadaran"
                break;
            case "3":
                ajaxUrl = baseUrl + "pcare_sync/poliklinik"
                break;
            case "4":
                ajaxUrl = baseUrl + "pcare_sync/status_pulang/" + $("#param3").val()
                break;
            case "5":
                ajaxUrl = baseUrl + "pcare_sync/obat/" + $("#param1").val()
                $.ajax({
                    url: ajaxUrl,
                    success: (data) => {
                        if (data) {
                            $("#console").empty()
                            $("#console").append(`<p>${data.count} data ditemukan.</p>`)
                            $("#obat-table").dataTable().fnDestroy()
                            $("#obat-body").empty()

                            data.list.forEach((item) => {
                                $("#obat-body").append(
                                    /*html*/
                                    `<tr>
                                        <td class="text-center">
                                            ${item.kdObat}
                                        </td>
                                        <td class="text-center">${item.nmObat}</td>
                                        <td class="text-center">
                                            <select id="${item.kdObat}" class="form-control master_code" name="master_code[${item.kdObat}]" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>`
                                )
                            })

                            $(".master_code").select2({
                                data: all_obat,
                                placeholder: "Pilih master obat",
                                dropdownParent: $("#obat-modal")
                            })

                            obat.forEach((item) => {
                                var selectEl = $(`#${item.pcare_kdObat}`)

                                if (selectEl) {
                                    selectEl.val(item.kodebarang)
                                    selectEl.trigger("change")
                                }
                            })

                            $("#obat-table").dataTable({
                                bLengthChange: false,
                                iDisplayLength: 5,
                                aoColumns: [
                                    {bSearchable: true},
                                    {bSearchable: true},
                                    {bSearchable: false},
                                ]
                            })

                            $("#obat-modal").modal("show")
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data!"
                            })
                        }
                    }
                })
                return
                break;
            case "6":
                ajaxUrl = baseUrl + "pcare_sync/tindakan/" + $("#param2").val()
                $.ajax({
                    url: ajaxUrl,
                    success: (data) => {
                        if (data) {
                            $("#console").empty()
                            $("#console").append(`<p>${data.count} data telah disinkronisasi.</p>`)
                            $("#tindakan-table").dataTable().fnDestroy()
                            $("#tindakan-body").empty()

                            data.list.forEach((item) => {
                                $("#tindakan-body").append(
                                    /*html*/
                                    `<tr>
                                        <td class="text-center">
                                            ${item.kdTindakan}
                                        </td>
                                        <td class="text-center">${item.nmTindakan}</td>
                                        <td class="text-center">
                                            <select id="${item.kdTindakan}" class="form-control master_tindakan" name="master_tindakan[${item.kdTindakan}]" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>`
                                )
                            })

                            $(".master_tindakan").select2({
                                data: all_tindakan,
                                placeholder: "Pilih master tindakan",
                                dropdownParent: $("#tindakan-modal")
                            })

                            tindakan.forEach((item) => {
                                var selectEl = $(`#${item.pcare_kdTindakan}`)

                                if (selectEl) {
                                    selectEl.val(item.kodetarif)
                                    selectEl.trigger("change")
                                }
                            })

                            $("#tindakan-table").dataTable({
                                bLengthChange: false,
                                iDisplayLength: 5,
                                aoColumns: [
                                    {bSearchable: true},
                                    {bSearchable: true},
                                    {bSearchable: false},
                                ]
                            })

                            $("#tindakan-modal").modal("show")
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data!"
                            })
                        }
                    }
                })
                return
                break;
            case "7":
                ajaxUrl = baseUrl + "pcare_sync/provider/"
                break;
            case "8":
                ajaxUrl = baseUrl + "pcare_sync/spesialis/"
                break;
            case "9":
                ajaxUrl = baseUrl + "pcare_sync/subspesialis/" + $("#param4").val()
                break;
            case "10":
                ajaxUrl = baseUrl + "pcare_sync/sarana"
                break;
            case "11":
                ajaxUrl = baseUrl + "pcare_sync/khusus"
                break;
            case "12":
                ajaxUrl = baseUrl + "pcare_sync/diagnosa/" + $("#param1").val()
                break;
            case "13":
                ajaxUrl = baseUrl + "pcare_sync/peserta"
                $.ajax({
                    url: ajaxUrl,
                    type: "POST",
                    dataType: "json",
                    data: { "jenis_kartu": $("#param5").val(), "search_term": $("#param1").val() },
                    success: (data) => {
                        if (data) {
                            $("#console").empty()
                            $("#console").append(`<p>Data ditemukan!</p>`)
                            
                            for (const property in data) {
                                $("#console").append(`<p>${property}: ${data[property]}</p>`)
                            }
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data!"
                            })
                        }
                    }
                })
                return;
                break;
        }

        $.ajax({
            url: ajaxUrl,
            success: (data) => {
                if (data) {
                    swal({
                        type: 'success',
                        title: "Success!",
                        html: "Sinkronisasi berhasil!",
                        timer: 2000
                    })

                    $("#console").empty()
                    $("#console").append(`<p>${data.count} data telah disinkronisasi.</p>`)
                }else {
                    swal({
                        type: "error",
                        title: "No Data",
                        html: "Tidak ditemukan data!"
                    })
                }
            }
        })
    })

    function sync_obat () {
        $.ajax({
            url: `<?= base_url("pcare_sync/update_master_obat") ?>`,
            type: "POST",
            dataType: "json",
            data: $("#master-obat-sync").serialize(),
            success: (data) => {
                if (data.status) {
                    swal({
                        type: "success",
                        title: "Berhasil",
                        html: "Data berhasil disimpan"
                    })
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                swal({
                    type: "error",
                    title: "Gagal",
                    html: `${textStatus} - ${errorThrown}`
                })
            }
        })
    }

    function sync_tindakan () {
        $.ajax({
            url: `<?= base_url("pcare_sync/update_master_tindakan") ?>`,
            type: "POST",
            dataType: "json",
            data: $("#master-tindakan-sync").serialize(),
            success: (data) => {
                if (data.status) {
                    swal({
                        type: "success",
                        title: "Berhasil",
                        html: "Data berhasil disimpan"
                    })
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                swal({
                    type: "error",
                    title: "Gagal",
                    html: `${textStatus} - ${errorThrown}`
                })
            }
        })
    }

    function sync_dokter () {
        $.ajax({
            url: `<?= base_url("pcare_sync/update_master_dokter") ?>`,
            type: "POST",
            dataType: "json",
            data: $("#master-dokter-sync").serialize(),
            success: (data) => {
                if (data.status) {
                    swal({
                        type: "success",
                        title: "Berhasil",
                        html: "Data berhasil disimpan"
                    })
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                swal({
                    type: "error",
                    title: "Gagal",
                    html: `${textStatus} - ${errorThrown}`
                })
            }
        })
    }

    function sync_process (primaryKey, totalData) {
        $.ajax({
            url: `<?= base_url("auto_jurnal/sync/") ?>${primaryKey}`,
            success: (data) => {
                var total = parseInt(document.getElementById("console").dataset.totaljobs)

                $("#console").append(`<p>Sinkronisasi ${total+1}/${totalData}.</p>`)
                document.getElementById("console").dataset.totaljobs = total+1
                document.getElementById("progress-bar").value = total+1
            },
            error: (jqXHR, textStatus, errorThrown) => {
                var total = parseInt(document.getElementById("console").dataset.totaljobs)

                $("#console").append(`<p>Sinkronisasi gagal, nomor bukti sudah terdaftar.</p>`)
                document.getElementById("console").dataset.totaljobs = total+1
                document.getElementById("progress-bar").value = total+1
            }
        })
    }
</script>


<?php  
    $this->load->view('template/v_report');
?>