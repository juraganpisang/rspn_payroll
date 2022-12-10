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
                        <a href="#">Laporan</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Gaji</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-md-2 pl-1"></div>
                                <label class="col-md-4 col-3 pt-2 text-right" for="uk">Kelompok Slip</label>
                                <select class="form-control col-md-4 col-5" id="uk">
                                    <?= $opt_slip ?>
                                </select>
                                <div class="col-md-2 col-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt--4">
                                <div class="col-md-3 col-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-searchData"><i class="fa fa-search"></i></span>
                                        </div>
                                        <input type="text" id="searchData" class="form-control" value="" placeholder="Cari Pegawai...">
                                    </div>
                                </div>
                            </div>
                            <div class="sticky-table sticky-ltr-cells">
                                <table id="table-data" class="table table-sm table-bordered table-striped">
                                    <thead class="sticky-headers">
                                        <tr>
                                            <th rowspan="3" style="width: 3%;">No</th>
                                            <th rowspan="3" class="sticky-cell" style="width: 32%;">Nama Pegawai</th>
                                            <th rowspan="3" style="width: 10%;">Tgl. Mulai Bekerja</th>
                                            <th rowspan="3" style="width: 10%;">Masa Bekerja</th>
                                            <th rowspan="3" style="width: 7%;">Gol</th>
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
                                    <tfoot></tfoot>
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
            $('#searchData').keyup(delay(function(e) {
                var word = $(this).val();
                $('#table-data > tbody > tr').hide().filter(function(i, v) {
                    var col = $(this).find('td:eq(1)');
                    return col.text().toLowerCase().indexOf(word.toLowerCase()) > -1;
                }).show();
            }, 500));
            $('#tanggal').datetimepicker({
                viewMode: 'months',
                format: 'MM/YYYY',
                autoclose: true,
                defaultDate: new Date()
            });
            $('#tanggal').on("change.datetimepicker", function() {
                $('#uk').trigger('change');
            });
            $('#btn-calendar').click(function() {
                $('#tanggal').datetimepicker('show');
            });
            $('#uk').change(function() {
                if ($(this).val() == '') {
                    $('#table-data > tbody').html('<tr><td colspan="24" class="text-center">Tidak ada data...</td></tr>');
                    return true;
                }
                var b = $('#btn-calendar'),
                    i = b.find('i'),
                    cls = i.attr('class');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Lap_gaji/list_') ?>/' + $(this).val() + '/' + $('#tanggal').val(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                    },
                    success: function(r) {
                        $('#table-data > tbody').html(r.tbody);
                        $('#table-data > tfoot').html(r.tfoot);
                        $('#searchData').val('');
                        i.removeClass().addClass(cls);
                    },
                    error: function(e) {
                        sweetMsg('error', 'Terjadi kesalahan!');
                        i.removeClass().addClass(cls);
                    }
                });
            });
            $('#uk').trigger('change');
        }).on('click', '#btn-export', function(e) {
            e.preventDefault();
            window.open('<?php echo base_url('Tunjangan_fungsi/export_excel/') ?>', '_blank');
        });

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
    </script>