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
                    <li class="nav-home">
                        <a href="#">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Adjustment</a>
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
                <div class="col-md-5">
                    <form id="form-data">
                        <div class="card">
                            <div class="card-header py-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Form</h4>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="form-group col-md-6 px-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-11 px-1">
                                            <select id="searchPeg"></select>
                                        </div>
                                        <div class="col-md-1 text-center px-0 pt-1">
                                            <i class="fa fa-2x fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 px-0">
                                    <label>Nama Pegawai</label>
                                    <input type="hidden" name="id" id="id" value="">
                                    <input id="nama" name="nama" type="text" class="form-control" autocomplete="off" readonly>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Unit Kerja</label>
                                    <select class="form-control" id="uk" name="uk">
                                        <?= $opt_uk ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer no-bd">
                                <button type="button" id="btn-cancel" class="btn btn-danger">Batal</button>
                                <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header py-2">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title"><i class="fa fa-exclamation-triangle text-danger"></i> Ketentuan</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <b>Fitur untuk memindahkan slip seorang pegawai ke bagian lain</b><br>
                            *) Pastikan <b>Semua data telah terkunci</b> dibulan tersebut<br>
                            *) Ketikkan <b>Nama pegawai</b> yang akan dipindahkan slip gajinya ke unit yang lain<br>
                            *) Pegawai yang telah dipindahkan, otomatis akan muncul di unit yang baru<br>
                            *) Apabila pegawai yang dicari tidak muncul, silahkan cek dimenu <a href="<?= base_url('gaji_generate') ?>" target="blank">berikut</a> untuk mengecek status kunci<br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //form
        $('#tanggal').datetimepicker({
            viewMode: 'months',
            format: 'MM/YYYY',
            autoclose: true,
            defaultDate: new Date()
        });
        $('#btn-cancel').click(function() {
            $('#form-data').find('input#id').val('');
            $('#form-data').find('input#nama').val('');
            $('#form-data').find('#uk').val('');
        });
        $('#btn-cancel').trigger('click');
        $('#searchPeg').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Ketikkan Nama Pegawai',
            ajax: {
                url: '<?= base_url('Mutasi_slip/cari') ?>',
                delay: 1000,
                data: function(params) {
                    var query = {
                        searchTerm: params.term,
                        tanggal: $('#tanggal').val()
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
            $('#uk').val(data.uk_id).trigger('change');
            $('#searchPeg').val('').trigger('change');
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
            url: '<?= base_url('Mutasi_slip/save') ?>',
            data: dt,
            dataType: 'JSON',
            beforeSend: function() {
                b.attr("disabled", true);
                i.removeClass().addClass('fa fa-spin fa-spinner');
            },
            success: function(r) {
                if (r.status) {
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
    });
</script>