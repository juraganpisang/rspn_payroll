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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-md-2 col-4 pl-1 pr-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="btn-generate" class="btn btn-primary btn-block"><i class="fas fa-layer-group"></i>&nbsp;Set Gaji</button>
                                </div>
                                <div class="col-md-4 pl-0">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="is_lock" class="form-check-input" type="checkbox" value="1">
                                            <span class="form-check-sign"><i class="fas fa-lock"></i> Kunci Slip gaji</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 pr-1 text-right">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-balance-scale"></i> +/- Kolektif</button>
                                    <div class="dropdown-menu" x-placement="top-start">
                                        <?php foreach ($adt as $key => $value) { ?>
                                            <a id="btn-modal-kolektif" data-id="<?= $value['p_id'] ?>" class="dropdown-item" href="#"><?= $value['p_nama'] ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <table id="table-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;">No</th>
                                        <th style="width: 36%;">Nama Unit Kerja</th>
                                        <th style="width: 5%;">TK</th>
                                        <th style="width: 12%;">Total Gaji</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 7%;">#</th>
                                        <th style="width: 12%;">Gaji diterima</th>
                                        <th style="width: 10%;">#</th>
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
            $('#btn-calendar').click(function() {
                $('#tanggal').datetimepicker('show');
            });
            reloadData();

            // Tombol generate gaji
            $('#btn-generate').click(function() {
                var b = $(this),
                    i = b.find('i'),
                    cls = i.attr('class'),
                    tanggal = $('#tanggal').val();
                Swal.fire({
                    title: "Set Gaji",
                    text: "Apakah anda akan melakukan Set Gaji bulan " + tanggal + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Lanjutkan'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var is_lock = 0;
                        if ($('#is_lock').is(':checked')) {
                            is_lock = 1;
                        }
                        $('#table-data > tfoot').html('');
                        i.removeClass().addClass('fa fa-spin fa-spinner');
                        b.prop('disabled', true);
                        intervalSet(0, is_lock);
                    }
                });
            });
        }).on('click', '#btn-modal-kolektif', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            var tr = b.parents('tr');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Gaji_generate/modal_additional_gaji_kolektif') ?>/' + b.data('id') + '/' + $('#tanggal').val(),
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
                        mdl.find('.currency').maskMoney({
                            thousands: ',',
                            allowNegative: false,
                            precision: 0,
                        });
                        mdl.find('#search').select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: 'Ketikkan Nama Pegawai',
                            dropdownParent: mdl,
                            ajax: {
                                url: '<?= base_url('Gaji_generate/cari') ?>',
                                delay: 1000,
                                data: function(params) {
                                    var query = {
                                        searchTerm: params.term,
                                        uk: '',
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
                            var id = data.id.replace(";", "-");
                            var n = mdl.find('#nilai').val(),
                                nilai = parseInt(n.split(',').join());
                            stn = mdl.find('#satuan').val();
                            var netto = parseInt(data.netto);
                            var potongan = nilai;
                            if (stn == '%') {
                                potongan = netto * nilai / 100;
                            }
                            if ($('#table-manual > tbody').find('#' + id).length > 0) {
                                sweetMsg('warning', 'Pegawai tersebut telah ditambahkan');
                            } else {
                                $('#table-manual > tbody').append('<tr id="' + id + '"><td class="text-center">#</td>' +
                                    '<td><input type="hidden" name="idpeg[]" value="' + data.id + '"><input type="hidden" id="netto" value="' + data.netto + '">' + data.text + '</td>' +
                                    '<td class="text-right">' + netto.formatMoney(0, '.', ',') + '</td>' +
                                    '<td id="td-result" class="text-right">' + potongan.formatMoney(0, '.', ',') + '</td>' +
                                    '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>');
                            }
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
                        mdl.find('#kolektif-kontrak').click(function() {
                            $.ajax({
                                type: 'POST',
                                url: '<?= base_url('Gaji_generate/set_table/kontrak') ?>',
                                data: ({
                                    nilai: mdl.find('#nilai').val(),
                                    satuan: mdl.find('#satuan').val(),
                                    b: mdl.find('#bulan').val(),
                                    t: mdl.find('#tahun').val(),
                                }),
                                dataType: 'JSON',
                                beforeSend: function() {},
                                success: function(r) {
                                    $('#table-manual > tbody').html(r.tbody);
                                },
                                error: function(e) {
                                    sweetMsg('error', 'Terjadi kesalahan!');
                                }
                            });
                        });
                        mdl.find('#form-modal').submit(function(e) {
                            e.preventDefault();
                            var btn = mdl.find('#btn-save'),
                                ib = btn.find('i'),
                                clss = ib.attr('class');
                            var form = $(this),
                                dt = form.serializeArray();
                            $.ajax({
                                type: 'POST',
                                url: '<?= base_url('Gaji_generate/save_additional_gaji_kolektif') ?>',
                                data: dt,
                                dataType: 'JSON',
                                beforeSend: function() {
                                    btn.attr("disabled", true);
                                    ib.removeClass().addClass('fa fa-spin fa-spinner');
                                },
                                success: function(r) {
                                    if (r.status) {
                                        sweetMsg('success', r.message);
                                        mdl.modal('hide');
                                        reloadData();
                                    } else {
                                        sweetMsg('error', result.message);
                                    }
                                    btn.removeAttr("disabled");
                                    ib.removeClass().addClass(clss);
                                },
                                error: function(e) {
                                    sweetMsg('error', 'Terjadi kesalahan!');
                                    btn.removeAttr("disabled");
                                    ib.removeClass().addClass(clss);
                                }
                            });
                        });
                    });
                },
                error: function() {
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-additional-gaji', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class');
            var tr = b.parents('tr');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Gaji_generate/modal_additional_gaji') ?>/' + b.data('id') + '/' + $('#tanggal').val(),
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
                        $.ajax({
                            type: 'GET',
                            url: '<?= base_url('Gaji_generate/reload_tr') ?>/' + b.data('id') + '/' + $('#tanggal').val(),
                            dataType: 'JSON',
                            beforeSend: function() {},
                            success: function(r) {
                                tr.find('td:eq(2)').html(r.tk);
                                tr.find('td:eq(3)').html(r.tnetto);
                                tr.find('td:eq(6)').html(r.terima_nominal);
                                if (r.tnetto == r.terima_nominal) {
                                    tr.find('td:eq(6)').removeClass('text-danger');
                                } else {
                                    tr.find('td:eq(6)').addClass('text-danger');
                                }
                            },
                            error: function(e) {
                                sweetMsg('error', 'Terjadi kesalahan!');
                            }
                        });
                        $(this).remove();
                    }).on('shown.bs.modal', function() {
                        mdl.find('.currency').maskMoney({
                            thousands: ',',
                            allowNegative: false,
                            precision: 0,
                        });
                        mdl.find('#search').select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: 'Ketikkan Nama Pegawai',
                            dropdownParent: mdl,
                            ajax: {
                                url: '<?= base_url('Gaji_generate/cari') ?>',
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
                            $('#netto').val(parseInt(data.netto).formatMoney(0, '.', ','));
                            $('.additional').val('');
                            $.each(data.terima_json, function(index, value) {
                                $('#p-' + index).val(parseInt(value).formatMoney(0, '.', ','));
                            });
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
                        mdl.find('#form-modal').submit(function(e) {
                            e.preventDefault();
                            var btn = mdl.find('#btn-save'),
                                ib = btn.find('i'),
                                clss = ib.attr('class');
                            var form = $(this),
                                dt = form.serializeArray();
                            $.ajax({
                                type: 'POST',
                                url: '<?= base_url('Gaji_generate/save_additional_gaji') ?>',
                                data: dt,
                                dataType: 'JSON',
                                beforeSend: function() {
                                    btn.attr("disabled", true);
                                    ib.removeClass().addClass('fa fa-spin fa-spinner');
                                },
                                success: function(result) {
                                    if (result.status) {
                                        sweetMsg('success', result.message);
                                        mdl.find('.form-control').val('');
                                    } else {
                                        sweetMsg('error', result.message);
                                    }
                                    btn.removeAttr("disabled");
                                    ib.removeClass().addClass(clss);
                                },
                                error: function(e) {
                                    sweetMsg('error', 'Terjadi kesalahan!');
                                    btn.removeAttr("disabled");
                                    ib.removeClass().addClass(clss);
                                }
                            });
                        });
                    });
                },
                error: function() {
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-refresh', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class'),
                uk_id = b.data('id'),
                tr = b.parents('tr:first');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Gaji_generate/set_gaji') ?>/' + uk_id + '/' + $('#tanggal').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.addClass('fa-spin');
                },
                success: function(r) {
                    if (r.status == 1 || r.status == 2) {
                        if (r.status == 1) {
                            tr.find('td:eq(2)').html(r.tk);
                            tr.find('td:eq(3)').html(r.tnetto);
                            tr.find('td:eq(6)').html(r.terima_nominal);
                            if (r.tnetto == r.terima_nominal) {
                                tr.find('td:eq(6)').removeClass('text-danger');
                            } else {
                                tr.find('td:eq(6)').addClass('text-danger');
                            }
                        } else {
                            sweetMsg('warning', r.message);
                        }
                    } else {
                        sweetMsg('error', r.message);
                    }
                    setTimeout(function() {
                        i.removeClass('fa-spin');
                    }, 1500);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }).on('click', '#btn-lock', function() {
            var b = $(this),
                i = b.find('i'),
                cls = i.attr('class'),
                status = b.attr('status'),
                uk_id = b.data('id');
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
                                    b.removeClass().addClass('btn btn-sm btn-icon btn-round btn-danger');
                                    i.removeClass().addClass('fa fa-lock');
                                    b.parent().find('span#text').html('Terkunci');
                                    b.attr('status', 0);
                                } else {
                                    b.removeClass().addClass('btn btn-sm btn-icon btn-round btn-success');
                                    i.removeClass().addClass('fa fa-unlock');
                                    b.parent().find('span#text').html('Terbuka');
                                    b.attr('status', 1);
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
        }).on('click', '#btn-delete', function() {
            var btn = $(this),
                i = btn.find('i'),
                cls = i.attr('class'),
                uk_id = btn.data('id'),
                tr = btn.parents('tr:first');
            Swal.fire({
                title: "Hapus data",
                text: "Apakah anda akan menghapus data tersebut?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'GET',
                        url: '<?= base_url('Gaji_generate/hapus') ?>/' + uk_id + '/' + $('#tanggal').val(),
                        dataType: 'JSON',
                        done: function(r) {},
                        beforeSend: function() {
                            i.removeClass().addClass('fa fa-spin fa-spinner');
                        },
                        success: function(r) {
                            if (r.status) {
                                tr.find('td:eq(2)').html('0');
                                tr.find('td:eq(3)').html('0');
                                tr.find('td:eq(6)').html('0');
                                tr.find('td:eq(6)').removeClass('text-danger');
                                $('#table-data > tfoot').html('');
                                sweetMsg('success', r.message);
                            } else {
                                sweetMsg('error', r.message);
                            }
                            i.removeClass().addClass(cls);
                        },
                        error: function(e) {
                            sweetMsg('error', 'Terjadi kesalahan!!');
                            i.removeClass().addClass(cls);
                        }
                    });
                }
            });
        }).on('click', '#btn-remove', function() {
            $(this).parents('tr:first').remove();
        });

        function reloadData() {
            var b = $('#btn-calendar'),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Gaji_generate/list_') ?>/' + $('#tanggal').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    $('#table-data > tbody').html(r.tbody);
                    $('#table-data > tfoot').html(r.tfoot);
                    $('#table-data > tbody').find('[data-toggle="tooltip"]').tooltip();
                    i.removeClass().addClass(cls);
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }

        function intervalSet(index, is_lock) {
            var tr = $('#table-data > tbody > tr:eq(' + index + ')');
            tr.addClass('bg-warning');
            if (is_lock) {
                b = tr.find('#btn-lock'), icon = b.find('i');
                b.removeClass().addClass('btn btn-sm btn-icon btn-round btn-danger');
                b.attr('status', 0);
                icon.removeClass().addClass('fa fa-lock');
                tr.find('span#text').html('Terkunci');
            }
            var b = tr.find('#btn-refresh'),
                i = b.find('i'),
                cls = i.attr('class'),
                uk_id = b.data('id');
            $.ajax({
                type: 'GET',
                url: '<?= base_url('Gaji_generate/set_gaji') ?>/' + uk_id + '/' + $('#tanggal').val(),
                dataType: 'JSON',
                beforeSend: function() {
                    i.addClass('fa-spin');
                },
                success: function(r) {
                    i.removeClass('fa-spin');
                    if (r.status == 1 || r.status == 2) {
                        if (r.status == 1) {
                            tr.find('td:eq(2)').html(r.tk);
                            tr.find('td:eq(3)').html(r.tnetto);
                            tr.find('td:eq(6)').html(r.terima_nominal);
                            if (r.tnetto == r.terima_nominal) {
                                tr.find('td:eq(6)').removeClass('text-danger');
                            } else {
                                tr.find('td:eq(6)').addClass('text-danger');
                            }
                        } else {
                            sweetMsg('warning', r.message);
                        }
                        setTimeout(function() {
                            tr.removeClass('bg-warning');
                            var next_i = index + 1;
                            if ($('#table-data > tbody > tr:eq(' + next_i + ')').length == 0) {
                                var btn = $('#btn-generate');
                                btn.find('i').removeClass().addClass('fas fa-layer-group');
                                btn.prop('disabled', false);
                                if (is_lock) {
                                    $.ajax({
                                        type: 'GET',
                                        url: '<?= base_url('Locker/lock_all') ?>/' + $('#tanggal').val(),
                                        dataType: 'JSON',
                                        beforeSend: function() {},
                                        success: function(result) {
                                            if (!result.status) {
                                                sweetMsg('error', r.message);
                                            }
                                        },
                                        error: function(e) {
                                            sweetMsg('error', 'Terjadi kesalahan!');
                                        }
                                    });
                                }
                                sweetMsg('success', 'Data gaji berhasil diset');
                                return true;
                            }
                            intervalSet(next_i, is_lock);
                        }, 500);
                    } else {
                        sweetMsg('error', r.message);
                    }
                },
                error: function(e) {
                    sweetMsg('error', 'Terjadi kesalahan!');
                    i.removeClass().addClass(cls);
                }
            });
        }

        function currencyFormat() {
            $('.currency').maskMoney({
                thousands: ',',
                allowNegative: false,
                precision: 0,
            });
        }

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