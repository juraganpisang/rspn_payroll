<style>
    #image-preview {
        width: 200px;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?= $title; ?></h4>
                <!-- <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Master</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#"><?= $title; ?></a>
                    </li>
                </ul> -->
            </div>
            <div class="row">
                <div class="col-md-8">
                    <!-- Modal -->
                    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="form-edit">
                                <div class="modal-content">
                                    <div class="modal-header no-bd">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">
                                                Detail</span>
                                            <span class="fw-light">
                                                Golongan
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="contentModal"></div>
                                    </div>
                                    <div class="modal-footer no-bd">
                                        <button type="button" id="btn-edit-detail" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" id="btn-batal" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">List Data</h4>
                                <div class="form-group">
                                    <button id="btn-export" type="button" class="btn btn-success btn-sm w-100"><i class="fa fa-file-excel"></i> Export Data</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Golongan</th>
                                            <th>Pendidikan</th>
                                            <th>Keterangan</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $tbody; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <form id="form-data">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Form Data</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" id="id">
                                <p class="small">Silahkan isi semua form nya</p>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>Nama Golongan</label>
                                            <input id="nama" name="nama" type="text" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>Pendidikan</label>
                                            <input id="pendidikan" name="pendidikan" type="text" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" id="keterangan" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer no-bd">
                                <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                <button type="button" id="btn-cancel" class="btn btn-danger">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            setDataTable('#table-data', '');

            $('#btn-cancel').click(function() {
                $('#form-data').find('input#id').val('');
                $('#form-data').find('input.form-control').val('');
                $('#form-data').find('textarea.form-control').val('');
            });

            $('#btn-cancel').click();

        }).on('submit', '#form-data', function(e) {
            e.preventDefault();
            var b = $('#btn-save'),
                i = b.find('i'),
                cls = i.attr('class');

            $.ajax({
                type: 'POST',
                url: '<?= base_url('Golongan/save') ?>',
                data: $(this).serializeArray(),
                dataType: 'JSON',
                beforeSend: function() {
                    b.attr("disabled", true);
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    if (r.status) {
                        setDataTable('#table-data', r.tbody);
                        sweetMsg('success', r.message);
                        $('#btn-cancel').trigger('click');
                    } else {
                        sweetMsg('error', r.message);
                    }
                    b.removeAttr("disabled");
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    b.removeAttr("disabled");
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-edit', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Golongan/edit') ?>/' + b.data('id'),
                dataType: 'JSON',
                async: false,
                done: function(r) {},
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    i.removeClass().addClass(cls);

                    $('#form-data').find('#id').val(r.g_id);
                    $('#form-data').find('#nama').val(r.g_nama);
                    $('#form-data').find('#pendidikan').val(r.g_pendidikan);
                    $('#form-data').find('#keterangan').val(r.g_keterangan);
                },

                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-export', function(e) {
            e.preventDefault();
            window.open('<?php echo base_url('Golongan/export_excel/') ?>', '_blank');
        }).on('click', '#btn-detail', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Golongan/detail') ?>/' + b.data('id'),
                dataType: 'JSON',
                async: false,
                done: function(r) {},
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    i.removeClass().addClass(cls);

                    $('#detailModal').modal('show');
                    $('#contentModal').html(r);
                },

                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-edit-detail', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
                
                var id = $('#form-edit').find('input#id').val();

                window.open('<?php echo base_url('Golongan/edit_detail') ?>/' + id, '_blank');

        });

        function setDataTable(a, tbody = '') {
            if ($.fn.DataTable.isDataTable(a)) {
                $(a).dataTable().fnDestroy();
            }

            if (tbody != '') {
                $(a).find('tbody').html(tbody);
            }

            $(a).DataTable({
                "responsive": true,
                // "lengthChange": false,
                "autoWidth": false,
                // "scrollX": true,
                // "scrollY": "400px",
                "scrollCollapse": true,
                "paging": true,
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": [{
                    "targets": [0],
                    "width": "10%",
                }, {
                    "targets": [1],
                    "width": "20%",
                },  {
                    "targets": [2],
                    "width": "20%",
                },  {
                    "targets": [3],
                    "width": "40%",
                },{
                    "targets": [4],
                    "className": "text-center",
                    "width": "10%",
                    "orderable": false,
                }],
                "initComplete": function() {

                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    </script>