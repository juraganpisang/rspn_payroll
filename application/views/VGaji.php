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
                                <label class="col-md-8 col-3 pt-2 text-right" for="uk">Unit Kerja</label>
                                <select class="form-control col-md-4 col-9" id="uk">
                                    <?= $opt_uk ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table-data" class="table table-sm table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th rowspan="3" style="width: 3%;">No</th>
                                                <!-- <th rowspan="2">Register</th> -->
                                                <th rowspan="3" style="width: 32%;">Nama Pegawai</th>
                                                <th rowspan="3" style="width: 12%;">Tanggal Mulai Bekerja</th>
                                                <!-- <th rowspan="2">Masa Kerja</th> -->
                                                <th rowspan="3" style="width: 10%;">Golongan</th>
                                                <th rowspan="3" style="width: 7%;">Status</th>
                                                <th rowspan="3" style="width: 7%;">Gaji Pokok</th>
                                                <th colspan="3">Tunjangan</th>
                                                <th colspan="3">Diberikan oleh RS</th>
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
    </div>

    <script>
        $(document).ready(function() {
            $('#uk').change(function() {
                var b = $('#loader'),
                    i = b.find('i'),
                    cls = i.attr('class');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('Gaji/list_') ?>/' + $(this).val(),
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