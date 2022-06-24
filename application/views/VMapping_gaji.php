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
                        <a href="#">Mapping Gaji</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <label class="col-md-8 col-3 pt-2 text-right" for="uk">Unit Kerja</label>
                                <select class="form-control col-md-4 col-9" id="uk">
                                    <?= $opt_uk ?>
                                </select>
                            </div>
                            <div class="row">
                                <form id="form-data">
                                    <table id="table-data" class="table table-sm table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Register</th>
                                                <th rowspan="2">Nama Pegawai</th>
                                                <th rowspan="2">Tanggal Mulai Bekerja</th>
                                                <th rowspan="2">Masa Kerja</th>
                                                <th rowspan="2">Gol</th>
                                                <th rowspan="2">Status</th>
                                                <th colspan="3">Tunjangan</th>
                                                <th rowspan="2">BPJS</th>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <th>Fungsi</th>
                                                <th>Transport</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="11" class="text-right">
                                                    <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#uk').change(function() {
                var b = $('#loader'),
                    i = b.find('i'),
                    cls = i.attr('class');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Mapping_gaji/list_') ?>/' + $(this).val(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        $('#table-data > tbody').html(r.tbody);
                        i.removeClass().addClass(cls);
                    },
                    error: function(e) {
                        sweetMsg('error', 'Terjadi kesalahan!');
                        i.removeClass().addClass(cls);
                    }
                });
            });
            $('#uk').trigger('change');
        }).on('submit', '#form-data', function(e) {
            e.preventDefault();
            var b = $('#btn-save'),
                i = b.find('i'),
                cls = i.attr('class');
            var dt = $(this).serializeArray();
            $('.is_transport').each(function() {
                var val = 0;
                if ($(this).is(':checked')) {
                    val = 1;
                }
                dt.push({
                    name: 'is_transport[]',
                    value: val
                });
            });
            $('.is_bpjs').each(function() {
                var val = 0;
                if ($(this).is(':checked')) {
                    val = 1;
                }
                dt.push({
                    name: 'is_bpjs[]',
                    value: val
                });
            });
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Mapping_gaji/save') ?>',
                data: dt,
                dataType: 'JSON',
                beforeSend: function() {
                    b.attr("disabled", true);
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    if (r.status) {
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
        }).on('click', '#btn-export', function(e) {
            e.preventDefault();
            window.open('<?php echo base_url('Tunjangan_fungsi/export_excel/') ?>', '_blank');
        });

        function currencyFormat() {
            $('.currency').maskMoney({
                thousands: ',',
                allowNegative: false,
                precision: 0,
            });
        }
    </script>