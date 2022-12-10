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
                                <div class="col-md-4 col-4 pl-1">
                                    <button type="button" id="btn-excel" class="btn btn-success"><i class="fa fa-file-excel"></i> Export</button>
                                    <button type="button" id="btn-excel-bca" class="btn btn-success"><i class="fa fa-file-excel"></i> Export BCA</button>
                                </div>
                                <div class="col-md-4 col-4"></div>
                                <div class="col-md-2 col-4 pr-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 pr-1">
                                    <button type="button" id="btn-cari" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Tampilkan</button>
                                </div>
                            </div>
                            <div class="sticky-table sticky-ltr-cells">
                                <table id="table-data" class="table table-bordered table-striped">
                                    <thead class="sticky-headers">
                                        <tr>
                                            <th style="width: 3%;">NO</th>
                                            <th class="sticky-cell" style="width: 30%;">BAGIAN</th>
                                            <th style="width: 7%;">TK</th>
                                            <th style="width: 10%;">POKOK/TUNJ</th>
                                            <th style="width: 10%;">BPJS&nbsp;Naker <?= $s['persen_rs_bpjs_jkk'] . '%' ?> (jkk&nbsp;jht&nbsp;jkm)</th>
                                            <th style="width: 10%;">BPJS&nbsp;Naker <?= $s['persen_rs_bpjs_pensiun'] . '%' ?> (pensiun)</th>
                                            <th style="width: 10%;">BPJS&nbsp;Kes <?= $s['persen_rs_bpjs_kesehatan'] . '%' ?></th>
                                            <th style="width: 15%;">JML KOTOR</th>
                                            <th style="width: 10%;">BPJS&nbsp;Naker <?= ($s['persen_rs_bpjs_jkk'] + $s['persen_bpjs_jkk']) . '%' ?> (jkk&nbsp;jht&nbsp;jkm)</th>
                                            <th style="width: 10%;">BPJS&nbsp;Naker <?= ($s['persen_rs_bpjs_pensiun'] + $s['persen_bpjs_pensiun']) . '%' ?> (pensiun)</th>
                                            <th style="width: 10%;">PAJAK</th>
                                            <th style="width: 10%;">BPJS&nbsp;Kes <?= ($s['persen_rs_bpjs_kesehatan'] + $s['persen_bpjs_kesehatan']) . '%' ?></th>
                                            <th style="width: 10%;">JUML.POT</th>
                                            <th style="width: 15%;">JML. BERSIH</th>
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
            $('#tanggal').datetimepicker({
                viewMode: 'months',
                format: 'MM/YYYY',
                autoclose: true,
                defaultDate: new Date()
            });
            $('#btn-calendar').click(function() {
                $('#tanggal').datetimepicker('show');
            });
            $('#btn-cari').click(function() {
                reloadData();
            });
            $('#btn-cari').trigger('click');
        }).on('click', '#btn-excel', function(e) {
            e.preventDefault();
            window.open('<?= base_url('Lap_gaji_unit/excel/') ?>' + $('#tanggal').val(), '_blank');
        }).on('click', '#btn-excel-bca', function(e) {
            e.preventDefault();
            window.open('<?= base_url('Lap_gaji_unit/excel_bca/') ?>' + $('#tanggal').val(), '_blank');
        });

        function reloadData() {
            if ($('#tanggal').val() == '') {
                $('#table-data > tbody').html('<tr><td colspan="22" class="text-center">Tidak ada data...</td></tr>');
                return true;
            }
            var b = $('#btn-cari'),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Lap_gaji_unit/list_') ?>/' + $('#tanggal').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    $('#table-data > tbody').html(r.tbody);
                    // $('#table-data > tfoot').html(r.tfoot);
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }
    </script>