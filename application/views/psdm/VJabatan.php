<style>
    .select2-container .select2-selection--single {
        height: 36px;
    }

    .select2-selection__arrow b {
        display: none !important;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?= $title; ?></h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Mapping</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow text-primary"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#"><?= $title; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <label class="col-md-8 col-3 pt-2 text-right" for="filter-uk">Unit Kerja</label>
                                <select class="form-control col-md-4 col-9" id="filter-uk">
                                    <?= $opt_uk ?>
                                </select>
                            </div>
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jabatan</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <form id="form-data">
                        <div class="card">
                            <div class="card-header py-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Form</h4>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <input type="hidden" name="id" id="id" value="">
                                <div class="form-group form-group-default">
                                    <label>Unit Kerja</label>
                                    <select class="form-control" id="uk" name="uk">
                                        <?= $opt_uk ?>
                                    </select>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Jabatan</label>
                                    <select class="form-control" id="jabatan" name="jabatan">
                                        <?= $opt_jabatan ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer no-bd">
                                <button type="button" id="btn-cancel" class="btn btn-danger">Batal</button>
                                <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#filter-uk').change(function() {
            if ($(this).val() == '') {
                setDataTable('#table-data', '');
                return true;
            }
            $.ajax({
                type: 'GET',
                url: '<?= base_url('Mapping_jabatan/list_') ?>/' + $(this).val(),
                dataType: 'JSON',
                beforeSend: function() {
                    // b.attr("disabled", true);
                    // i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    setDataTable('#table-data', r.tbody);
                    // b.removeAttr("disabled");
                    // i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    // b.removeAttr("disabled");
                    // i.removeClass().addClass(cls);
                }
            });
        });
        $('#filter-uk').trigger('change');
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input.form-control').val('');
            $('#form-data').find('select.form-control').val('');
        });
        $('#btn-cancel').click();
    }).on('submit', '#form-data', function(e) {
        e.preventDefault();
        var b = $('#btn-save'),
            i = b.find('i'),
            cls = i.attr('class');
        var form = $(this),
            dt = form.serializeArray();
        $.ajax({
            type: 'POST',
            url: '<?= base_url('Mapping_jabatan/save') ?>',
            data: dt,
            dataType: 'JSON',
            beforeSend: function() {
                b.attr("disabled", true);
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                if (r.status) {
                    $('#filter-uk').trigger('change');
                    $('#btn-cancel').trigger('click');
                    sweetMsg('success', r.message);
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
            url: '<?= base_url('Mapping_jabatan/edit') ?>/' + b.data('id'),
            dataType: 'JSON',
            async: false,
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                var form = $('#form-data');
                form.find('#id').val(r.jm_id);
                form.find('#uk').val(r.jm_uk_id);
                form.find('#jabatan').val(r.jm_jb_id);
                i.removeClass().addClass(cls);
            },
            error: function(e) {
                sweetMsg('error', 'Terjadi kesalahan!');
                i.removeClass().addClass(cls);
            }
        });
    }).on('click', '#btn-delete', function() {
        var btn = $(this),
            i = btn.find('i'),
            cls = i.attr('class'),
            sep = btn.data('id');
        Swal.fire({
            title: "Hapus data",
            text: "Apakah anda akan menghapus data tersebut?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: '<?= base_url('Mapping_jabatan/delete') ?>/' + btn.data('id'),
                    dataType: 'JSON',
                    done: function(r) {},
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        if (r.status) {
                            sweetMsg('success', r.message);
                            $('#btn-cancel').trigger('click');
                            $('#filter-uk').trigger('change');
                        } else {
                            sweetMsg('error', r.message);
                        }
                        i.removeClass().addClass(cls);
                    },
                    error: function(e) {
                        sweetMsg('error', 'Terjadi kesalahan!!');
                        i.removeClass().addClass(cls);
                    }
                });
            }
        });
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
            "autoWidth": false,
            "scrollCollapse": true,
            "paging": true,
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": [0],
                "width": "12%",
            }, {
                "targets": [1],
                "width": "70%",
            }, {
                "targets": [2],
                "width": "18%",
                "orderable": false,
            }],
            "initComplete": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>