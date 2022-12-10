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
                <div class="col-md-12">
                    <form id="form-data">
                        <div class="card">
                            <div class="card-header py-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Form</h4>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="form-group col-md-2 pl-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-10 pr-3">
                                        <div class="row">
                                            <div class="col-md-11 p-0">
                                                <select id="searchPeg"></select>
                                            </div>
                                            <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                                <i class="fa fa-2x fa-search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 px-0">
                                    <label>Nama Pegawai</label>
                                    <input type="hidden" name="id" id="id" value="">
                                    <input id="nama" name="nama" type="text" class="form-control" autocomplete="off" readonly>
                                </div>
                                <div class="form-group col-md-4 px-0">
                                    <label><i class="fas fa-medkit"></i> Gaji Pokok</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" id="gaji_nominal" name="gaji_nominal" value="" class="form-control currency">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 pb-0">
                                        <label class="mb-0"><i class="fas fa-layer-group"></i> Tunjangan</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Jabatan</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="tj_nominal" name="tj_nominal" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fungsi</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="tf_nominal" name="tf_nominal" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Transport</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="t_nominal" name="t_nominal" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Insentif</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="ti_nominal" name="ti_nominal" value="" class="form-control currency">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 pb-0">
                                        <label class="mb-0"><i class="fas fa-hospital"></i> Diberikan RS</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>BPJS&nbsp;Naker <?= $s['persen_rs_bpjs_jkk'] . '%' ?> (jkk&nbsp;jht&nbsp;jkm)</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="rs_bpjs_jkk" name="rs_bpjs_jkk" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>BPJS&nbsp;Naker <?= $s['persen_rs_bpjs_pensiun'] . '%' ?> (pensiun)</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="rs_bpjs_pensiun" name="rs_bpjs_pensiun" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>BPJS&nbsp;Kes <?= $s['persen_rs_bpjs_kesehatan'] . '%' ?></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="rs_bpjs_kesehatan" name="rs_bpjs_kesehatan" value="" class="form-control currency">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 pb-0">
                                        <label class="mb-0"><i class="fas fa-hand-holding-usd"></i> Potongan</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>BPJS&nbsp;Naker <?= $s['persen_bpjs_jkk'] . '%' ?> (jkk&nbsp;jht&nbsp;jkm)</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="bpjs_jkk" name="bpjs_jkk" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>BPJS&nbsp;Naker <?= $s['persen_bpjs_pensiun'] . '%' ?> (pensiun)</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="bpjs_pensiun" name="bpjs_pensiun" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>BPJS&nbsp;Kes <?= $s['persen_bpjs_kesehatan'] . '%' ?></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="bpjs_kesehatan" name="bpjs_kesehatan" value="" class="form-control currency">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Pajak</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="pajak_rp" name="pajak_rp" value="" class="form-control currency">
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
            $('#form-data').find('.currency').val('');
        });
        $('#btn-cancel').trigger('click');
        $('#searchPeg').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Ketikkan Nama Pegawai',
            ajax: {
                url: '<?= base_url('Revisi_slip/cari') ?>',
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
            $('#gaji_nominal').val(parseInt(data.gaji_nominal).formatMoney(0, '.', ','));
            $('#tj_nominal').val(parseInt(data.tj_nominal).formatMoney(0, '.', ','));
            $('#tf_nominal').val(parseInt(data.tf_nominal).formatMoney(0, '.', ','));
            $('#ti_nominal').val(parseInt(data.ti_nominal).formatMoney(0, '.', ','));
            $('#t_nominal').val(parseInt(data.t_nominal).formatMoney(0, '.', ','));
            $('#rs_bpjs_jkk').val(parseInt(data.rs_bpjs_jkk).formatMoney(0, '.', ','));
            $('#rs_bpjs_pensiun').val(parseInt(data.rs_bpjs_pensiun).formatMoney(0, '.', ','));
            $('#rs_bpjs_kesehatan').val(parseInt(data.rs_bpjs_kesehatan).formatMoney(0, '.', ','));
            $('#bpjs_jkk').val(parseInt(data.bpjs_jkk).formatMoney(0, '.', ','));
            $('#bpjs_pensiun').val(parseInt(data.bpjs_pensiun).formatMoney(0, '.', ','));
            $('#bpjs_kesehatan').val(parseInt(data.bpjs_kesehatan).formatMoney(0, '.', ','));
            $('#pajak_rp').val(parseInt(data.pajak_rp).formatMoney(0, '.', ','));
            $('#searchPeg').val('').trigger('change');
        });
        $('.currency').maskMoney({
            thousands: ',',
            allowNegative: false,
            precision: 0,
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
            url: '<?= base_url('Revisi_slip/save') ?>',
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

    Number.prototype.formatMoney = function(c, d, t) {
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>