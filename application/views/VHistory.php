<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?= $title; ?></h4>
                <ul class="breadcrumbs">
                    <li>
                        Riwayat aktivitas user selama login ke sistem
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title"><i class="fab fa-hubspot"></i> Aktivitas</div>
                        <div class="card-tools">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ol class="activity-feed"></ol>
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
            $('#tanggal').on("change.datetimepicker", function() {
                reloadData();
            });
            reloadData();
        });

        function reloadData() {
            var b = $('#btn-calendar'),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('History/list_') ?>/' + $('#tanggal').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    $('.activity-feed').html(r.result);
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }
    </script>