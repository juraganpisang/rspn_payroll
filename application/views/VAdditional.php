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
                        <a href="#">Master</a>
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
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody><?= $tbody ?></tbody>
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
                                    <label>Kode</label>
                                    <input class="form-control" id="kode" name="kode" autocomplete="off">
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Nama</label>
                                    <input class="form-control" id="nama" name="nama" autocomplete="off">
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="+" selected>Penambahan</option>
                                        <option value="-">Pemotongan</option>
                                    </select>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Urut</label>
                                    <input class="form-control col-md-2" id="urut" name="urut" autocomplete="off">
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
        setDataTable('#table-data', '');
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input.form-control').val('');
        });
        $('#btn-cancel').trigger('click');
    }).on('submit', '#form-data', function(e) {
        e.preventDefault();
        var b = $('#btn-save'),
            i = b.find('i'),
            cls = i.attr('class');
        var form = $(this),
            dt = form.serializeArray();
        $.ajax({
            type: 'POST',
            url: '<?= base_url('Additional/save') ?>',
            data: dt,
            dataType: 'JSON',
            beforeSend: function() {
                b.attr("disabled", true);
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                if (r.status) {
                    setDataTable('#table-data', r.tbody);
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
            type: 'GET',
            url: '<?= base_url('Additional/edit') ?>/' + b.data('id'),
            dataType: 'JSON',
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                var form = $('#form-data');
                form.find('#id').val(r.p_id);
                form.find('#kode').val(r.p_kode);
                form.find('#nama').val(r.p_nama);
                form.find('#jenis').val(r.p_jenis);
                form.find('#urut').val(r.p_urut);
                i.removeClass().addClass(cls);
            },
            error: function(e) {
                sweetMsg('error', 'Terjadi kesalahan!');
                i.removeClass().addClass(cls);
            }
        });
    }).on('click', '#btn-disable', function() {
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
                    url: '<?= base_url('Additional/disable') ?>/' + btn.data('id'),
                    dataType: 'JSON',
                    done: function(r) {},
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        if (r.status) {
                            sweetMsg('success', r.message);
                            $('#btn-cancel').trigger('click');
                            setDataTable('#table-data', r.tbody);
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
    }).on('click', '#btn-delete', function() {
        var btn = $(this),
            i = btn.find('i'),
            cls = i.attr('class');
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
                    url: '<?= base_url('Additional/delete') ?>/' + btn.data('id'),
                    dataType: 'JSON',
                    done: function(r) {},
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        if (r.status) {
                            sweetMsg('success', r.message);
                            $('#btn-cancel').trigger('click');
                            setDataTable('#table-data', r.tbody);
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
                "width": "15%",
            }, {
                "targets": [2],
                "width": "55%",
            }, {
                "targets": [3],
                "width": "18%",
                "orderable": false,
            }],
            "initComplete": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>