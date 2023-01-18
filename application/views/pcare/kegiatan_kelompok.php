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
                    Kegiatan Kelompok
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-12 mb-6">
        <h2 class="mb-3" style="color: #0a428a;"><strong>BPJS P-Care Kegiatan Kelompok</strong></h2>
        <div class="mb-3" style="padding: 30px; ">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="kegiatan_kelompok_list-tab" data-toggle="tab" href="#kegiatan_kelompok_list" role="tab" aria-controls="kegiatan_kelompok_list" aria-selected="true"><strong>Daftar Kegiatan Kelompok</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="detail_peserta-tab" data-toggle="tab" href="#detail_peserta" role="tab" aria-controls="detail_peserta" aria-selected="false"><strong>Detail Peserta Kegiatan Kelompok</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="klub_prolanis-tab" data-toggle="tab" href="#klub_prolanis" role="tab" aria-controls="klub_prolanis" aria-selected="false"><strong>Daftar Klub Prolanis</strong></a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active in" id="kegiatan_kelompok_list" role="tabpanel" aria-labelledby="kegiatan_kelompok_list-tab">
                    <div style="margin-bottom: 10px; width: 100%">
                        <h3 class="mb-3 text-center"><strong>Daftar Kegiatan Kelompok</strong></h3>
                        <button class="btn blue" type="button" onclick="showKelompokForm()"><i class="fa fa-plus"></i> <strong>Tambah Kegiatan Kelompok</strong></button>
                    </div>
                    <div class="table-responsive" style="width: 100%">
                        <table class="table" id="kegiatan-table">
                            <thead>
                                <tr class="breadcrumb">
                                    <th class="title-white text-center">No.</th>
                                    <th class="title-white text-center">Edu. Id</th>
                                    <th class="title-white text-center">Club Id</th>
                                    <th class="title-white text-center">Tgl. Pelayanan</th>
                                    <th class="title-white text-center">Pembicara</th>
                                    <th class="title-white text-center">Lokasi</th>
                                    <th class="title-white text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="detail_peserta" role="tabpanel" aria-labelledby="detail_peserta-tab">
                    <div style="margin-bottom: 10px; width: 100%">
                        <h3 class="mb-3 text-center"><strong>Daftar Peserta Kegiatan Kelompok</strong></h3>
                    </div>
                    <div class="table-responsive" style="width: 100%">
                        <table class="table" id="peserta-kegiatan-table">
                            <thead>
                                <tr class="breadcrumb">
                                    <th class="title-white text-center">No.</th>
                                    <th class="title-white text-center">No. Kartu</th>
                                    <th class="title-white text-center">Nama Peserta</th>
                                    <th class="title-white text-center">Tgl. Lahir</th>
                                    <th class="title-white text-center">Usia</th>
                                    <th class="title-white text-center">Pekerjaan</th>
                                    <th class="title-white text-center">No. HP</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="klub_prolanis" role="tabpanel" aria-labelledby="klub_prolanis-tab" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="col-md-6" style="display: flex; flex-direction: column; align-items: center;">
                        <h3 class="mb-3 text-center"><strong>Sinkronisasi Daftar Klub Prolanis</strong></h3>
                        <form class="col-md-12 mb-3" style="display: flex; flex-direction: column; align-items: center;">
                            <select class="form-control mb-3" name="kdProgram" id="kdProgram">
                                <?php foreach ($kelompok as $value): ?>
                                    <option value="<?= $value->kdProgram ?>"><?= $value->nmProgram ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn red" type="button" onclick="syncProlanis()"><i class="fa fa-refresh"></i> Sinkronisasi Data</button>
                        </form>
                    </div>
                    <div class="table-responsive" style="width: 100%">
                        <table class="table" id="club-prolanis-table">
                            <thead>
                                <tr class="breadcrumb">
                                    <th class="title-white text-center">No.</th>
                                    <th class="title-white text-center">Club Id</th>
                                    <th class="title-white text-center">Kelompok</th>
                                    <th class="title-white text-center">Nama Club</th>
                                    <th class="title-white text-center">Alamat</th>
                                    <th class="title-white text-center">Tanggal Mulai</th>
                                    <th class="title-white text-center">Tanggal Akhir</th>
                                    <th class="title-white text-center">Contact Person</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Kegiatan Modal -->
    <div class="modal fade" id="kelompok-modal" role="dialog" aria-labelledby="kelompok-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="sync-tindakan"><strong>Tambah Kegiatan Kelompok</strong></h3>
                </div>
            <div class="modal-body">
                <form id="kelompok-add" style="padding: 10px; display: flex; flex-direction: column; gap: 3px;">
                    <div class="form-group mb-3">
                        <label class="col-md-3" class="form-label" for="club_id">Pilih Klub Prolanis:</label>
                        <div class="col-md-9">
                            <select class="form-control" name="clubId" id="club_id">
                                <?php foreach ($club_prolanis as $value): ?>
                                    <option value="<?= $value->clubId ?>"><?= $value->clubId ?> - <?= $value->nama ?> (<?= $value->kdProgram ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="tglPelayanan">Tanggal Pelayanan:</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" name="tglPelayanan" id="tglPelayanan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="kdKelompok">Kode Kelompok:</label>
                        <div class="col-md-9">
                            <select class="form-control" name="kdKelompok" id="kdKelompok">
                                <?php foreach ($kelompok as $value): ?>
                                    <option value="<?= $value->kdProgram ?>"><?= $value->kdProgram ?> - <?= $value->nmProgram ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="kdKegiatan">Kode Kegiatan:</label>
                        <div class="col-md-9">
                            <select class="form-control" name="kdKegiatan" id="kdKegiatan">
                                <?php foreach ($kegiatan as $value): ?>
                                    <option value="<?= $value->kdProgram ?>"><?= $value->kdProgram ?> - <?= $value->nmProgram ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="materi">Meteri yang Disampaikan:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="materi" id="materi" cols="30" rows="3" placeholder="Materi yang disampaikan..."></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="pembicara">Pembicara:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="pembicara" id="pembicara" placeholder="Pembicara...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="lokasi">Lokasi:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="keterangan">Keterangan:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="3" placeholder="Keterangan lain..."></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3" class="form-label" for="biaya">Biaya (Rp):</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control currency" name="biaya" id="biaya" placeholder="Biaya...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="addKelompok()">Simpan</button>
            </div>
            </div>
        </div>
    </div>
    <!-- End Add Kegiatan Modal -->
</div>

<?php 
    $this->load->view('template/footer_tb');
?>

<script>
    moment.locale("id")
    function syncProlanis () {
        var kdProgram = $("#kdProgram").val()
        var i = 1;

        $.ajax({
            url: '<?= base_url("pcare_sync/club_prolanis") ?>' + `/${kdProgram}`,
            dataType: "json",
            success: (data) => {
                $("#club-prolanis-table").dataTable().fnDestroy()
                $("#club-prolanis-table").dataTable({
                    aaData: data,
                    aoColumns: [
                        {
                            mData: "id",
                            sClass: "text-center",
                            mRender: (data) => {
                                return data
                            }
                        },
                        {
                            mData: "clubId",
                            sClass: "text-center"
                        },
                        {
                            mData: "kdProgram",
                            sClass: "text-center",
                            mRender: (data) => {
                                if (data == "01") {
                                    return `${data} Diabetes Melitus`
                                }

                                return `${data} Hipertensi`
                            }
                        },
                        {mData: "nama"},
                        {mData: "alamat"},
                        {
                            mData: "tglMulai",
                            sClass: "text-center",
                            mRender: (data) => {
                                return moment(data).format("LL")
                            }
                        },
                        {
                            mData: "tglAkhir",
                            sClass: "text-center",
                            mRender: (data) => {
                                return moment(data).format("LL")
                            }
                        },
                        {
                            mData: (source) => {
                                return {
                                    nama: source.ketua_nama,
                                    contact: source.ketua_noHP
                                }
                            },
                            mRender: (data) => {
                                return `${data.nama} (HP: ${data.contact})`
                            }
                        }
                    ]
                })
            },
            error: (jqXHR, textStatus, errorThrown) => {
                swal({
                    type: "error",
                    title: "Gagal",
                    html: `<p>${jqXHR.status} - ${errorThrown}</p>`
                })
            }
        })
    }

    function showKelompokForm () {
        $("#kelompok-modal").modal("show")
    }

    function addKelompok() {
        $.ajax({
            url: '<?= base_url("api/pcare/add_kegiatan_kelompok") ?>',
            type: "POST",
            dataType: "json",
            data: $("#kelompok-add").serialize(),
            success: (data) => {
                console.log(data)
            },
            error: (jqXHR, textStatus, errorThrown) => {
                var errors = jqXHR.responseJSON
                var errorString = ""
                
                errors.forEach((el) => {
                    errorString = errorString + `<p>${el.field} - ${el.message}</p>`
                })

                swal({
                    type: "error",
                    title: "Gagal!",
                    html: `${jqXHR.status} - ${errorThrown} ${errorString}`
                })
            }
        })
    }
</script>


<?php  
    $this->load->view('template/v_report');
?>