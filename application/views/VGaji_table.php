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
                        <a href="#">Gaji Pegawai</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12 mt--5 text-right">
                    <b>Nomor SK : </b>-
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 col-4 pl-1 pr-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                    </div>
                                    <select class="form-control mt-2" id="gol" name="gol">
                                        <?= $opt_golongan ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <table id="table-data" class="table table-bordered table-striped">
                                        <thead></thead>
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
            $('#tanggal').datetimepicker({
                viewMode: 'years',
                format: 'YYYY',
                autoclose: true,
                defaultDate: new Date()
            });
            $('#tanggal').on("change.datetimepicker", function() {
                reloadData();
            });
            $('#btn-calendar').click(function() {
                $('#tanggal').datetimepicker('show');
            });
            $('#gol').change(function() {
                reloadData();
            });
            // reloadData();
        });

        function reloadData() {
            var b = $('#btn-calendar'),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Gaji_table/list_') ?>/' + $('#tanggal').val() + '/' + $('#gol').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    $('#table-data > thead').html(r.thead);
                    $('#table-data > tbody').html(r.tbody);
                    $('#table-data > tbody').find('[data-toggle="tooltip"]').tooltip();
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }
    </script>