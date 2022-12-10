<style>
    .tooltip-inner {
        max-width: 450px;
        /* If max-width does not work, try using width instead */
        width: 450px;
        text-align: justify;
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <label class="col-md-8 col-3 pt-2 text-right" for="uk">Unit Kerja</label>
                                <select class="form-control col-md-4 col-9" id="uk">
                                    <?= $opt_uk ?>
                                </select>
                            </div>
                            <form id="form-data">
                                <table id="table-data" class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="width: 3%;">No</th>
                                            <!-- <th rowspan="2">Register</th> -->
                                            <th rowspan="2" style="width: 20%;">Nama Pegawai</th>
                                            <th rowspan="2" style="width: 11%;">Tgl. Mulai Bekerja</th>
                                            <!-- <th rowspan="2">Masa Kerja</th> -->
                                            <th rowspan="2" style="width: 8%;">Gol</th>
                                            <th rowspan="2" style="width: 7%;">Status</th>
                                            <th colspan="4">Tunjangan</th>
                                            <th rowspan="2" style="width: 5%;">BPJS</th>
                                            <th rowspan="2" style="width: 5%;">Kontrak</th>
                                            <th rowspan="2" style="width: 12%;">Pajak</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 8%;">Jabatan</th>
                                            <th style="width: 7%;">Fungsi</th>
                                            <th style="width: 7%;">Insentif</th>
                                            <th style="width: 7%;">Transport</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="12" class="text-right">
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

    <script>
        $(document).ready(function() {
            $('#uk').change(function() {
                if ($(this).val() == '') {
                    $('#table-data > tbody').html('<tr><td colspan="12" class="text-center">Tidak ada data...</td></tr>');
                    return true;
                }
                var b = $('#loader'),
                    i = b.find('i'),
                    cls = i.attr('class');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Mapping_gaji/list_') ?>/' + $(this).val(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('#table-data > tbody').html('<tr><td colspan="12" class="text-center"><i class="fa fa-spin fa-spinner"></i> Loading...</td></tr>');
                        // i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        setTimeout(function() {
                            $('#table-data > tbody').html(r.tbody);
                            $('#table-data > tbody').find('.currency').maskMoney({
                                thousands: ',',
                                allowNegative: false,
                                precision: 0,
                            });
                            $('#table-data > tbody').find('[data-toggle="tooltip"]').tooltip({
                                html: true,
                                container: 'body'
                            });
                        }, 1500);
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
            $('.is_jamsostek').each(function() {
                var val = 0;
                if ($(this).is(':checked')) {
                    val = 1;
                }
                dt.push({
                    name: 'is_jamsostek[]',
                    value: val
                });
            });
            $('.is_kontrak').each(function() {
                var val = 0;
                if ($(this).is(':checked')) {
                    val = 1;
                }
                dt.push({
                    name: 'is_kontrak[]',
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
                        $('#uk').trigger('change');
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
        }).on('click', '.is_bpjs', function(e) {
            var cb = $(this);
            if (cb.is(':checked')) {
                cb.parents('tr').find('.is_jamsostek').prop('checked', true);
            } else {
                cb.parents('tr').find('.is_jamsostek').prop('checked', false);
            }
        });

        function currencyFormat() {
            $('.currency').maskMoney({
                thousands: ',',
                allowNegative: false,
                precision: 0,
            });
        }
    </script>