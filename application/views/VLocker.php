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
                            <div class="row">
                                <div class="col">&nbsp;</div>
                                <div class="col">
                                    <div class="form-group form-inline py-1 pr-0">
                                        <label for="inlineinput" class="col-md-4 col-form-label">Tahun</label>
                                        <div class="col-md-8 p-0">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn-filter"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <input id="tahun" name="tahun" type="text" class="form-control text-center" placeholder="Tahun" aria-label="Tahun" aria-describedby="btn-filter" data-toggle="datetimepicker" data-target="#tahun" value="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Unit Kerja</th>
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
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <label style="width: 100%;">Unit Kerja
                                        <div class="float-right">
                                            <input type="checkbox" class="form-check-input" id="cb_all">
                                            <label class="form-check-label mr-0" for="cb_all">Semua Unit</label>
                                        </div>
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="uk_id" name="uk_id[]" multiple="multiple"><?= $opt_uk ?></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" name="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" autocomplete="off">
                                    </div>
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
        $('#tahun').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
            autoclose: true,
            defaultDate: new Date()
        });
        $('#tahun').on("change.datetimepicker", function() {
            var i = $('i#btn-filter'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Locker/list_') ?>/' + $('#tahun').val(),
                dataType: 'JSON',
                async: false,
                done: function(r) {},
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    setDataTable('#table-data', r.tbody);
                    $('#btn-cancel').trigger('click');
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        });
        $('#tahun').trigger('change.datetimepicker');
        $('#tanggal').datetimepicker({
            viewMode: 'months',
            format: 'MM/YYYY',
            autoclose: true
        });
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input.form-control').val('');
            $('#form-data').find("#uk_id > option").prop("selected", false);
            $('#form-data').find("#uk_id").trigger('change');
            $('#form-data').find("#cb_all").prop('checked', false);
        });
        $('#btn-cancel').click();
        $('#uk_id').select2({
            width: '100%',
            placeholder: 'Pilih Unit Kerja',
        });
        $("#cb_all").click(function() {
            if ($("#cb_all").is(':checked')) {
                $("#uk_id > option").prop("selected", "selected");
            } else {
                $("#uk_id > option").prop("selected", false);
            }
            $("#uk_id").trigger('change');
        });
    }).on('submit', '#form-data', function(e) {
        e.preventDefault();
        var b = $('#btn-save'),
            i = b.find('i'),
            cls = i.attr('class');
        var form = $(this),
            dt = form.serializeArray();
        $.ajax({
            type: 'POST',
            url: '<?= base_url('Locker/save') ?>',
            data: dt,
            dataType: 'JSON',
            beforeSend: function() {
                b.attr("disabled", true);
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                if (r.status) {
                    $('#tahun').trigger('change.datetimepicker');
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
            url: '<?= base_url('Locker/edit') ?>/' + b.data('id'),
            dataType: 'JSON',
            async: false,
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                i.removeClass().addClass(cls);
                $('#form-data').find('#id').val(r.lc_id);
                $('#form-data').find('#tanggal').val(r.tanggal).trigger('change');
                $('#form-data').find('#uk_id').html(r.opt_uk);
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
                    url: '<?= base_url('Locker/hapus') ?>/' + btn.data('id'),
                    dataType: 'JSON',
                    done: function(r) {},
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        if (r.status) {
                            $('#tahun').trigger('change.datetimepicker');
                            $('#btn-cancel').trigger('click');
                            sweetMsg('success', r.message);
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
        $(a).find('tbody').html(tbody);
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
                "className": "text-center",
                "width": "7%",
            }, {
                "targets": [1],
                "width": "15%",
            }, {
                "targets": [2],
                "width": "65%",
            }, {
                "targets": [3],
                "className": "text-center",
                "width": "13%",
                "orderable": false,
            }],
            "initComplete": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>