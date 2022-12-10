<style>
    #table-data tr:nth-child(odd)>td {
        background-color: #fff;
        white-space: nowrap;
    }

    #table-data tr:nth-child(even)>td {
        background-color: #e2e2e2;
        white-space: nowrap;
    }

    #table-data tr>th {
        background-color: #d7d7d7;
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
                        <a href="#">Gaji Karyawan</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-md-6 pl-1">
                                    <button type="button" id="btn-lock" status="1" class="btn btn-danger"><i class="fa fa-lock"></i>&nbsp;<span id="text">Terkunci</span></button>
                                    <button type="button" id="modal-additional" class="btn btn-primary"><i class="fas fa-balance-scale"></i>&nbsp;+/- Gaji</button>
                                </div>
                                <select class="form-control col-md-4 col-5" id="uk">
                                    <?= $opt_uk ?>
                                </select>
                                <div class="col-md-2 col-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="10/2022">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="table-responsive"> -->
                            <div class="sticky-table sticky-ltr-cells">
                                <table id="table-data" class="table table-sm table-bordered table-striped">
                                    <thead class="sticky-headers">
                                        <tr>
                                            <th rowspan="3" style="width: 3%;">No</th>
                                            <!-- <th rowspan="2">Register</th> -->
                                            <th rowspan="3" class="sticky-cell" style="width: 32%;">Nama Pegawai</th>
                                            <th rowspan="3" style="width: 12%;">Tanggal Mulai Bekerja</th>
                                            <!-- <th rowspan="2">Masa Kerja</th> -->
                                            <th rowspan="3" style="width: 10%;">Golongan</th>
                                            <th rowspan="3" style="width: 7%;">Status</th>
                                            <th rowspan="3" style="width: 7%;">Gaji Pokok</th>
                                            <th colspan="3">Tunjangan</th>
                                            <th colspan="3">Diberikan oleh RS</th>
                                            <th rowspan="3" style="width: 7%;">Insentif</th>
                                            <th rowspan="3" style="width: 7%;">Bruto</th>
                                            <th colspan="8">Potongan</th>
                                            <th rowspan="3" style="width: 7%;">Netto</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" style="width: 7%;">Jabatan</th>
                                            <th rowspan="2" style="width: 7%;">Fungsi</th>
                                            <th rowspan="2" style="width: 7%;">Transport</th>
                                            <th style="width: 7%;">BPJS jkk jht jkm</th>
                                            <th style="width: 7%;">BPJS Pensiun</th>
                                            <th style="width: 7%;">BPJS Kesehatan</th>
                                            <th colspan="2">BPJS jkk jht jkm</th>
                                            <th colspan="2">BPJS Pensiun</th>
                                            <th rowspan="2" style="width: 7%;">Pajak</th>
                                            <th colspan="2">BPJS Kesehatan</th>
                                            <th rowspan="2" style="width: 7%;">Jumlah</th>
                                        </tr>
                                        <tr>
                                            <th><?= $s['persen_rs_bpjs_jkk'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_rs_bpjs_pensiun'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_rs_bpjs_kesehatan'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_bpjs_jkk'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_rs_bpjs_jkk'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_bpjs_pensiun'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_rs_bpjs_pensiun'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_rs_bpjs_kesehatan'] . '&nbsp;%' ?></th>
                                            <th><?= $s['persen_bpjs_kesehatan'] . '&nbsp;%' ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tanggal').datetimepicker({
                viewMode: 'months',
                format: 'MM/YYYY',
                autoclose: true,
                // defaultDate: new Date()
            });
            $('#tanggal').on("change.datetimepicker", function() {
                $('#uk').trigger('change');
            });
            $('#btn-calendar').click(function() {
                $('#tanggal').datetimepicker('show');
            });
            $('#uk').change(function() {
                if ($(this).val() == '') {
                    $('#table-data > tbody').html('<tr><td colspan="22" class="text-center">Tidak ada data...</td></tr>');
                    return true;
                }
                var b = $('#loader'),
                    i = b.find('i'),
                    cls = i.attr('class');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Gaji/list_') ?>/' + $(this).val() + '/' + $('#tanggal').val(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        $('#table-data > tbody').html(r.tbody);
                        $('#btn-lock').removeClass().addClass(r.bg);
                        $('#btn-lock').find('i').removeClass().addClass(r.icon);
                        $('#btn-lock').find('span#text').html(r.text);
                        $('#btn-lock').attr('status', r.status);
                        i.removeClass().addClass(cls);
                    },
                    error: function(e) {
                        sweetMsg('error', 'Terjadi kesalahan!');
                        i.removeClass().addClass(cls);
                    }
                });
            });
            $('#uk').trigger('change');
            $('#modal-additional').click(function() {
                var b = $(this),
                    i = b.find('i'),
                    cls = i.attr('class');
                var tr = b.parents('tr');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Gaji/modal_additional') ?>/' + $('#uk').val() + '/' + $('#tanggal').val(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(result) {
                        i.removeClass().addClass(cls);
                        if (!result.status) {
                            sweetMsg('warning', result.message);
                            return true;
                        }
                        var mdl = $(result.modal);
                        mdl.modal({
                            backdrop: 'static',
                            keydrop: false
                        }).on('hidden.bs.modal', function() {
                            $(this).remove();
                        }).on('shown.bs.modal', function() {
                            $('#search').select2({
                                width: '100%',
                                allowClear: true,
                                placeholder: 'Ketikkan Nama Pegawai',
                                dropdownParent: mdl,
                                ajax: {
                                    url: '<?= base_url('gaji/cari') ?>',
                                    delay: 1000,
                                    data: function(params) {
                                        var query = {
                                            searchTerm: params.term,
                                            uk: mdl.find('#uk').val(),
                                            b: mdl.find('#bulan').val(),
                                            t: mdl.find('#tahun').val(),
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
                                $('#id').val(data.id);
                                $('#nama').val(data.text);
                                $('#search').val('').trigger('change');
                            }).on('select2:open', function(e) {
                                const selectId = e.target.id
                                $(".select2-search__field[aria-controls='select2-" + selectId + "-results']").each(function(
                                    key,
                                    value
                                ) {
                                    value.focus();
                                })
                            });
                        });
                    },
                    error: function() {
                        i.removeClass().addClass(cls);
                    }
                });
            });
        }).on('click', '#btn-lock', function() {
            if ($('#uk').val() == '') {
                sweetMsg('warning', 'Unit kerja tidak boleh kosong.');
                return true;
            }
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class'),
                status = b.attr('status'),
                id = $('#id').val(),
                uk_id = $('#uk').val();
            var warning = status == 1 ? 'mengunci' : 'membuka kunci';
            Swal.fire({
                title: "Update data",
                text: "Apakah anda akan " + warning + " data pada unit kerja tersebut?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Lanjutkan'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('Locker/update_status') ?>',
                        data: ({
                            'uk_id': uk_id,
                            'tanggal': $('#tanggal').val(),
                            'status': status
                        }),
                        dataType: 'JSON',
                        beforeSend: function() {
                            i.removeClass().addClass('fa fa-spin fa-spinner');
                        },
                        success: function(r) {
                            if (r.status) {
                                sweetMsg('success', r.message);
                                if (status == 1) {
                                    $('#btn-lock').removeClass().addClass('btn btn-danger');
                                    $('#btn-lock').find('i').removeClass().addClass('fa fa-lock');
                                    $('#btn-lock').find('span#text').html('Terkunci');
                                    $('#btn-lock').attr('status', 0);
                                } else {
                                    $('#btn-lock').removeClass().addClass('btn btn-success');
                                    $('#btn-lock').find('i').removeClass().addClass('fa fa-unlock');
                                    $('#btn-lock').find('span#text').html('Terbuka');
                                    $('#btn-lock').attr('status', 1);
                                    $('#uk').trigger('change');
                                }
                            } else {
                                sweetMsg('error', r.message);
                                i.removeClass().addClass(cls);
                            }
                        },
                        error: function(e) {
                            sweetMsg('error', 'Terjadi kesalahan!');
                            i.removeClass().addClass(cls);
                        }
                    });
                }
            });
        });

        function currencyFormat() {
            $('.currency').maskMoney({
                thousands: ',',
                allowNegative: false,
                precision: 0,
            });
        }
    </script>