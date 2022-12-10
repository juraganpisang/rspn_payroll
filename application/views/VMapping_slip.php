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
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="filter-tanggal" type="text" class="form-control text-center" placeholder="Tahun" value="<?= date('Y') ?>" aria-label="Tahun" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#filter-tanggal" onClick="this.select();" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bagian</th>
                                            <th></th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <form id="form-data">
                        <div class="card">
                            <div class="card-header py-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Form</h4>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <input type="hidden" name="id" id="id" value="">
                                <div class="row">
                                    <div class="form-group col-md-4 pl-3">
                                        <input id="urut" name="urut" type="text" class="form-control text-center" placeholder="Urut" value="" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input id="tanggal" name="tanggal" type="text" class="form-control text-center" placeholder="Tahun" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" onClick="this.select();" value="<?= date('Y') ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Nama Bagian</label>
                                    <input id="nama" name="nama" type="text" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="0">Berdasarkan Jabatan</option>
                                        <option value="1" selected>Berdasarkan Unit Kerja</option>
                                        <option value="2">Berdasarkan Pegawai</option>
                                        <option value="3">Berdasarkan Golongan</option>
                                    </select>
                                </div>
                                <div class="form-group jenis jenis-0 d-none">
                                    <div class="row">
                                        <div class="col-md-11 p-0">
                                            <select id="search"></select>
                                        </div>
                                        <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                            <i class="fa fa-2x fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group jenis jenis-2 d-none">
                                    <div class="row">
                                        <div class="col-md-11 p-0">
                                            <select id="searchPeg"></select>
                                        </div>
                                        <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                            <i class="fa fa-2x fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <table id="table-manual" class="display table table-striped jenis jenis-0 jenis-2 d-none">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 80%;">Nama</th>
                                            <th style="width: 10%;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="form-group form-group-default jenis jenis-1">
                                    <label>Unit Kerja</label>
                                    <select class="form-control" id="uk" name="uk">
                                        <?= $opt_uk ?>
                                    </select>
                                </div>
                                <div class="form-group form-group-default jenis jenis-3 d-none">
                                    <label>Golongan</label>
                                    <select class="form-control" id="golongan" name="golongan">
                                        <?= $opt_golongan ?>
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
        $('#filter-tanggal').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
            autoclose: true
        });
        $('#filter-tanggal').on("change.datetimepicker", function() {
            reloadData();
        });
        reloadData();
        //form
        $('#tanggal').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
            autoclose: true
        });
        $('#jenis').change(function() {
            var sel = $(this).val();
            $('.jenis').addClass('d-none');
            $('.jenis-' + sel).removeClass('d-none');
            $('#uk_id').val('');
            $('#table-manual > tbody').html('');
        });
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input#urut').val('');
            $('#form-data').find('input#nama').val('');
            $('#form-data').find('#uk').val('');
            $('#form-data').find('#jenis').val(1).trigger('change');
        });
        $('#btn-cancel').trigger('click');
        $('#search').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Ketikkan Nama Jabatan',
            ajax: {
                url: '<?= base_url('Mapping_slip/cari') ?>',
                delay: 1000,
                data: function(params) {
                    var query = {
                        searchTerm: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if ($('#table-manual > tbody').find('#' + data.id).length > 0) {
                sweetMsg('warning', 'Jabatan tersebut telah ditambahkan');
            } else {
                $('#table-manual > tbody').append('<tr id="' + data.id + '"><td class="text-center">#</td>' +
                    '<td><input type="hidden" name="idjm[]" value="' + data.id + '">' + data.text + '</td>' +
                    '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>');
            }
            $('#search').val('').trigger('change');
        });
        $('#searchPeg').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Ketikkan Nama Pegawai',
            ajax: {
                url: '<?= base_url('Punishment/cari') ?>',
                delay: 1000,
                data: function(params) {
                    var query = {
                        searchTerm: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if ($('#table-manual > tbody').find('#' + data.id).length > 0) {
                sweetMsg('warning', 'Pegawai tersebut telah ditambahkan');
            } else {
                $('#table-manual > tbody').append('<tr id="' + data.id + '"><td class="text-center">#</td>' +
                    '<td><input type="hidden" name="idpeg[]" value="' + data.id + '">' + data.text + '</td>' +
                    '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>');
            }
            $('#searchPeg').val('').trigger('change');
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
            url: '<?= base_url('Mapping_slip/save') ?>',
            data: dt,
            dataType: 'JSON',
            beforeSend: function() {
                b.attr("disabled", true);
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                if (r.status) {
                    reloadData();
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
            url: '<?= base_url('Mapping_slip/edit') ?>/' + b.data('id'),
            dataType: 'JSON',
            async: false,
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                i.removeClass().addClass(cls);
                $('#form-data').find('#id').val(r.sl_id);
                $('#form-data').find('#urut').val(r.sl_urut);
                $('#form-data').find('#tahun').val(r.sl_tahun);
                $('#form-data').find('#nama').val(r.sl_nama);
                $('#form-data').find('#jenis').val(r.sl_jenis).trigger('change');
                if (r.sl_jenis == 0) {
                    $('#table-manual > tbody').html(r.table);
                }
                if (r.sl_jenis == 2) {
                    $('#table-manual > tbody').html(r.table);
                } else if (r.sl_jenis == 3) {
                    $('#golongan').val(r.sl_g_id);
                } else {
                    $('#uk').val(r.sl_uk_id);
                }
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
                    url: '<?= base_url('Mapping_slip/hapus') ?>/' + btn.data('id'),
                    dataType: 'JSON',
                    done: function(r) {},
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        if (r.status) {
                            reloadData();
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
    }).on('click', '#btn-remove', function() {
        $(this).parents('tr:first').remove();
    });

    function reloadData() {
        var i = $('i#btn-calendar'),
            cls = i.attr('class');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('Mapping_slip/list_') ?>/' + $('#filter-tanggal').val(),
            dataType: 'JSON',
            async: false,
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                setDataTable('#table-data', r.tbody);
                i.removeClass().addClass(cls);
            },
            error: function(e) {
                sweetMsg('error', 'Terjadi kesalahan!');
                i.removeClass().addClass(cls);
            }
        });
    }

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
                "width": "30%",
            }, {
                "targets": [2],
                "width": "40%",
            }, {
                "targets": [3],
                "className": "text-center",
                "width": "18%",
                "orderable": false,
            }],
            "initComplete": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>