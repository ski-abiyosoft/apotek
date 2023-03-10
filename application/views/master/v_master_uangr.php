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
        <span class="title-web">Master <small>Setting Uang R</small>
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
            <a href="<?php echo base_url(); ?>Master_seting_r">
            Daftar Setting
            </a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">
            Master Setting
            </a>
        </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="col-md-6 caption">
                    Daftar Kode Setting
                </div>
                <div class="pull-right" style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 30px">
                        <label class="form-label">Cabang : </label>
                        <select class="form-control input-large" id="cabang" name="cabang" onchange="getcabang()">
                            <?php foreach ($cabang as $key => $value): ?>
                                <option value="<?= $value->koders ?>"><?= $value->namars ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <button class="btn btn-success" onclick="openModal()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                </div>
                <div class="col-md-8">
                    <table id="table_setting" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead class="breadcrumb header-custom">
                            <tr>
                                <th class="title-white" style="text-align: center">Cabang</th>
                                <th class="title-white" style="text-align: center">Uang Resep (per item)</th>
                                <th class="title-white" style="text-align: center">Uang Racik</th>
                                <th class="title-white" style="text-align: center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings as $key => $value) : ?>
                                <tr>
                                    <td><?= $value->koders ?></td>
                                    <td><?= $value->uang_r ?></td>
                                    <td><?= $value->uang_racik ?></td>
                                    <td>
                                        <div style="display: flex; gap: 30px;">
                                            <button class="btn blue" onclick="editData('<?= $value->id ?>')"><i class="fa fa-pencil"></i> Edit</button>
                                            <button class="btn red" onclick="deleteData('<?= $value->id ?>')"><i class="fa fa-trash"></i> Hapus</button>
                                        </div>
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

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="add-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Tambah Setting Farmasi</strong></h4>
            </div>
            <div class="modal-body">
                <form id="form-setting">
                    <input type="hidden" id="id" value="">
                    <div class="form-group" style="margin-bottom: 50px; border: 1px solid white">
                        <label for="koders" class="col-sm-4 control-label">Cabang</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="koders" id="koders">
                                <?php foreach ($cabang as $key => $value): ?>
                                    <option value="<?= $value->koders ?>"><?= $value->namars ?> (<?= $value->koders ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <span id="uang_r_help" class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 100px;" >
                        <label for="uang_r" class="col-sm-4 control-label">Uang Resep</label>
                        <div class="col-sm-8">
                            <input type="number" name="uang_r" class="form-control" id="uang_r" placeholder="1000">
                            <span id="uang_r_help" class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 100px;">
                        <label for="uang_racik" class="col-sm-4 control-label">Uang Racik</label>
                        <div class="col-sm-8">
                            <input type="number" name="uang_racik" class="form-control" id="uang_racik" placeholder="1000">
                            <span id="uang_racik_help" class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="margin-top: 100px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveChange()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('template/footer_tb');
?>

<script>

    $(document).ready(() => {
        var input = document.querySelectorAll('input')
        var select = document.querySelectorAll('select')

        input.forEach((el) => {
            el.addEventListener('change', function () {
                this.parentElement.parentElement.classList.remove('has-error')
                this.nextElementSibling.innerHTML = ''
            })
        })

        select.forEach((el) => {
            el.addEventListener('change', function () {
                this.parentElement.parentElement.classList.remove('has-error')
                this.nextElementSibling.innerHTML = ''
            })
        })
    })

    function openModal() {
        $("#add-modal").modal("show")
        $('#form-setting')[0].reset()
    }

    function saveChange() {
        var id = $("#id").val()
        var url = `<?= base_url('master_seting_r/store') ?>`

        if (id) url = `<?= base_url('master_seting_r/update') ?>/${id}`

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $('#form-setting').serialize(),
            success: (data) => {
                console.log(data)

                var message = `Setting pada cabang ${data.koders} telah dibuat!`
                var title = `Data berhasil ditambahkan!`

                if (id) {
                    message = `Setting pada cabang ${data.koders} telah diubah!`
                    title = "Data berhasil diubah!"
                }
                
                swal({
                    type: 'success',
                    title: title,
                    html: message
                }).then((value) => {
                    location.reload()
                })
            },
            error: (jqXHR, textStatus, errorThrown) => {
                for (var prop in jqXHR.responseJSON.errors) {
                    $(`#${prop}`).parent().parent().addClass('has-error')
                    $(`#${prop}`).next().text(jqXHR.responseJSON.errors[prop])
                }
            }
        })
    }

    function editData(id) {
        $.ajax({
            url: `<?= base_url('master_seting_r/show') ?>/${id}`,
            dataType: 'json',
            success: (data) => {
                $("#koders").val(data.koders)
                $("#uang_r").val(data.uang_r)
                $("#id").val(data.id)
                $("#uang_racik").val(data.uang_racik)

                $("#add-modal").modal("show")
                clearError()
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR.responseJSON)
            }
        })
    }

    function deleteData(id) {
        swal({
            type: 'warning',
            title: 'Menghapus data setting!',
            html: `Apakah ada yakin akan menghapus data ini?`,
            showCancelButton: true,
            cancelButtonText: "Batal",
            cancelButtonColor: 'red',
        }).then((value) => {
            if (value) {
                $.ajax({
                    url: `<?= base_url('master_seting_r/destroy') ?>/${id}`,
                    dataType: 'json',
                    success: (data) => {
                        swal({
                            type: 'success',
                            title: 'Data berhasil dihapus!',
                            html: `Setting pada cabang ${data.koders} telah dihapus!`
                        }).then((value) => {
                            location.reload()
                        })
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.log(jqXHR.responseJSON)
                    }
                })
            }
        })
    }

    function clearError () {
        var helpBlock = document.querySelectorAll('.help-block')
        var hasError = document.querySelectorAll('.has-error')

        helpBlock.forEach((el) => {
            el.innerHTML =  ''
        })

        hasError.forEach((el) => {
            el.classList.remove('has-error')
        })
    }
</script>