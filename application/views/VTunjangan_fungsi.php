<style>
    #image-preview {
        width: 200px;
    }

    ul.breadcrumbs>li>a,
    ul.breadcrumbs>li.separator {
        color: #fff;
        text-decoration: none;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="page-header mb-0">
                    <h4 class="page-title text-white"><?= $title; ?></h4>
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
                            <a href="#">Master</a>
                        </li>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="#"><?= $title; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="form-group d-none">
                            <button id="btn-export" type="button" class="btn btn-success btn-sm w-100"><i class="fa fa-file-excel"></i> Export Data</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-data" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Status</th>
                                            <th>Nama</th>
                                            <th>Keterangan</th>
                                            <th>Nominal Masa Kerja</th>
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
                                    <h4 class="card-title">Form</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" id="id">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>Nama</label>
                                            <input id="nama" name="nama" type="text" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" id="keterangan" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group p-0">
                                            <label>Nominal Masa Kerja Baru</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">Rp </span>
                                                </div>
                                                <input type="baru" id="baru" class="form-control currency" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group p-0">
                                            <label for="lama">Nominal Masa Kerja Lama</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon2">Rp</span>
                                                </div>
                                                <input type="text" id="lama" name="lama" class="form-control currency" aria-describedby="basic-addon2">
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

    <script>
        $(document).ready(function() {

            setDataTable('#table-data', '');

            $('#btn-cancel').click(function() {
                $('#form-data').find('input#id').val('');
                $('#form-data').find('input.form-control').val('');
                $('#form-data').find('textarea.form-control').val('');
            });

            $('#btn-cancel').click();

            currencyFormat();

        }).on('submit', '#form-data', function(e) {
            e.preventDefault();
            var b = $('#btn-save'),
                i = b.find('i'),
                cls = i.attr('class');

            var dt = new FormData();
            dt.append('id', $('input#id').val());
            dt.append('nama', $('input#nama').val());
            dt.append('keterangan', $('textarea#keterangan').val());
            dt.append('baru', $('#baru').val());
            dt.append('lama', $('#lama').val());

            $.ajax({
                type: 'POST',
                url: '<?= base_url('Tunjangan_fungsi/save') ?>',
                cache: false,
                contentType: false,
                processData: false,
                data: dt,
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
                url: '<?= base_url('Tunjangan_fungsi/edit') ?>/' + b.data('id'),
                dataType: 'JSON',
                async: false,
                done: function(r) {},
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    i.removeClass().addClass(cls);

                    baru = parseInt(r.tj_baru).formatMoney(0, '.', ',');
                    lama = parseInt(r.tj_lama).formatMoney(0, '.', ',');

                    currencyFormat();
                    $('#form-data').find('#id').val(r.tf_id);
                    $('#form-data').find('#nama').val(r.tf_nama);
                    $('#form-data').find('#keterangan').val(r.tf_keterangan);
                    $('#form-data').find('#baru').val(baru);
                    $('#form-data').find('#lama').val(lama);
                },

                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-aktif', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success m-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                text: "Apakah anda yakin akan mengaktifkan data berikut?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('Tunjangan_fungsi/aktif') ?>/' + b.data('id'),
                        dataType: 'JSON',
                        // async: false,
                        done: function(r) {},
                        beforeSend: function() {
                            i.removeClass().addClass('fa fa-spin fa-spinner');
                        },
                        success: function(r) {
                            if (r.status) {
                                Swal.fire(
                                    '',
                                    r.message,
                                    'success'
                                )
                                setDataTable('#table-data', r.tbody);
                            } else {
                                Swal.fire(
                                    '',
                                    r.message,
                                    'error'
                                )
                            }
                            i.removeClass().addClass(cls);
                        },
                        error: function(e) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan!!',
                                'error'
                            )
                            i.removeClass().addClass(cls);
                        }
                    });
                }
            });
        }).on('click', '#btn-nonaktif', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger m-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                text: "Apakah anda yakin akan menghapus data berikut?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('Tunjangan_fungsi/nonaktif') ?>/' + b.data('id'),
                        dataType: 'JSON',
                        // async: false,
                        done: function(r) {},
                        beforeSend: function() {
                            i.removeClass().addClass('fa fa-spin fa-spinner');
                        },
                        success: function(r) {
                            if (r.status) {
                                Swal.fire(
                                    '',
                                    r.message,
                                    'success'
                                )
                                setDataTable('#table-data', r.tbody);
                            } else {
                                Swal.fire(
                                    '',
                                    r.message,
                                    'error'
                                )
                            }
                            i.removeClass().addClass(cls);
                        },
                        error: function(e) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan!!',
                                'error'
                            )
                            i.removeClass().addClass(cls);
                        }
                    });
                }
            });
        }).on('click', '#btn-export', function(e) {
            e.preventDefault();
            window.open('<?php echo base_url('Tunjangan_fungsi/export_excel/') ?>', '_blank');
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
                    "width": "3%",
                }, {
                    "targets": [1],
                    "className": "text-center",
                    "width": "10%",
                    "orderable": false,
                }, {
                    "targets": [2],
                    "width": "20%",
                }, {
                    "targets": [3],
                    "width": "30%",
                }, {
                    "targets": [4],
                    "width": "30%",
                }, {
                    "targets": [5],
                    "className": "text-center",
                    "width": "7%",
                    "orderable": false,
                }],
                "initComplete": function() {

                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }

        function currencyFormat() {

            $('.currency').maskMoney({
                thousands: ',',
                allowNegative: false,
                precision: 0,
            });
        }

        Number.prototype.formatMoney = function(c, d, t) {
            var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        }
    </script>