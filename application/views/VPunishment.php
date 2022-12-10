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
                    <li>
                        Riwayat punishment pegawai yang berdampak pada pengurangan masa kerja
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
                                            <th>Nama Pegawai</th>
                                            <th>Masa Punishment</th>
                                            <th>Keterangan</th>
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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-11 p-0">
                                            <select id="search"></select>
                                        </div>
                                        <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                            <i class="fa fa-2x fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 px-0">
                                    <label>Nama Pegawai</label>
                                    <input type="hidden" name="id_peg" id="id_peg" class="form-control" value="">
                                    <input id="nama" name="nama" type="text" class="form-control" autocomplete="off" readonly>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" id="keterangan" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 pl-0">
                                            <label>Tanggal Mulai</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <input id="tanggal" name="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" onClick="this.select();" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-5 pr-0">
                                            <label>Durasi</label>
                                            <div class="input-group mb-3">
                                                <input type="number" id="durasi" name="durasi" class="form-control currency" aria-describedby="basic-addon1" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1">Bln</span>
                                                </div>
                                            </div>
                                        </div>
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
        setDataTable('#table-data', '');
        $('#tanggal').datetimepicker({
            viewMode: 'months',
            format: 'MM/YYYY',
            autoclose: true
        });
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input.form-control').val('');
            $('#form-data').find('textarea.form-control').val('');
        });
        $('#btn-cancel').click();
        $('#search').select2({
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
            $('#id_peg').val(data.id);
            $('#nama').val(data.text);
            $('#search').val('').trigger('change');
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
            url: '<?= base_url('Punishment/save') ?>',
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
            type: 'POST',
            url: '<?= base_url('Punishment/edit') ?>/' + b.data('id'),
            dataType: 'JSON',
            async: false,
            done: function(r) {},
            beforeSend: function() {
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                i.removeClass().addClass(cls);
                $('#form-data').find('#id').val(r.pn_id);
                $('#form-data').find('#id_peg').val(r.id_user);
                $('#form-data').find('#nama').val(r.us_nama);
                $('#form-data').find('#tanggal').val(r.tanggal).trigger('change');
                $('#form-data').find('#durasi').val(r.durasi);
                $('#form-data').find('#keterangan').val(r.keterangan);
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
            text: "Apakah anda akan menghapus data punishment tersebut?",
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
                    url: '<?= base_url('Punishment/hapus') ?>/' + btn.data('id'),
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
                "className": "text-center",
                "width": "7%",
            }, {
                "targets": [1],
                "width": "25%",
            }, {
                "targets": [2],
                "width": "20%",
            }, {
                "targets": [3],
                "width": "35%",
            }, {
                "targets": [4],
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