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

    .row{
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

<div class="row">
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
                    SInkronisasi Database
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
                            <option value="8">8. Spesialis dan Sarana Khusus</option>
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
                <div class="col-md-9 flex flex-col items-center">
                    <button type="button" class="btn btn-danger" id="sync" type="submit"><i class="fa fa-refresh"></i> Mulai Sinkronisasi</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <div class="col-md-12" id="console" style="border: 1px solid red; height: 20vh; margin: 0 auto; overflow: auto;" data-totalJobs="0"></div>
    </div>
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
                    <table class="table">
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
</div>

<?php 
    $this->load->view('template/footer_tb');
?>

<script>
    var all_obat     = JSON.parse(`<?= json_encode($all_obat) ?>`)
    var all_tindakan = JSON.parse(`<?= json_encode($all_tindakan) ?>`)
    var obat         = JSON.parse(`<?= json_encode($obat) ?>`)
    var tindakan     = JSON.parse(`<?= json_encode($tindakan) ?>`)

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

        if (item_value == 4) {
            $("#param3-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 5) {
            $("#param1-wrapper").removeClass("hidden")
            return
        }

        if (item_value == 6) {
            $("#param2-wrapper").removeClass("hidden")
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
                            $("#console").append(`<p>${data.count} data telah disinkronisasi.</p>`)
                            $("#tindakan-body").empty()

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
                            $("#obat-modal").modal("show")
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data selama periode penarikan!"
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
                            $("#console").append(`<p>${data.count} data telah disinkronisasi.</p>`)
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
                            $(document).on('select2:close', '.my-select2', function (e) {
                                var evt = "scroll.select2"
                                $(e.target).parents().off(evt)
                                $(window).off(evt)
                            })
                            $("#tindakan-modal").modal("show")
                            $("#tindakan-table").dataTable()
                        }else {
                            swal({
                                type: "error",
                                title: "No Data",
                                html: "Tidak ditemukan data selama periode penarikan!"
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

                    $("#console").append(`<p>${data.count} data telah disinkronisasi.</p>`)
                }else {
                    swal({
                        type: "error",
                        title: "No Data",
                        html: "Tidak ditemukan data selama periode penarikan!"
                    })
                }
            }
        })
    })

    function sync_obat () {
        $.ajax({
            url: `<?= base_url("pcare_sync/update_master") ?>`,
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
    $this->load->view('template/footer');
    $this->load->view('template/v_report');
?>