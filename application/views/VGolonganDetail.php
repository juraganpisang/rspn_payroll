<style>
    #image-preview {
        width: 200px;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?= $title; ?></h4>
                <!-- <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Master</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#"><?= $title; ?></a>
                    </li>
                </ul> -->
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Form Data</h4>
                            </div>
                        </div>
                        <form id="form-data">
                            <div class="card-body">
                                <input type="hidden" name="id_golongan" id="id_golongan" value="<?= $detail[0]['g_id'] ?>">
                                <h4>Data Golongan <?= $detail[0]['g_nama']; ?></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            Pendidikan
                                        </div>
                                        <div class="col">
                                            : <?= $detail[0]['g_pendidikan'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            Jenis Pekerjaan
                                        </div>
                                        <div class="col">
                                            : <?= $detail[0]['g_keterangan'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            Tahun
                                        </div>
                                        <div class="col">
                                            : <?= $detail[0]['gd_tahun'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <p class="small">Form Gaji</p>
                                    <script>
                                        WebFont.load({
                                            google: {
                                                "families": ["Lato:300,400,700,900"]
                                            },
                                            custom: {
                                                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                                                urls: ['../../assets/css/fonts.min.css']
                                            },
                                            active: function() {
                                                sessionStorage.fonts = true;
                                            }
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
                                        }
                                    </script>
                                    <?php
                                    $no = 0;
                                    foreach ($detail as $row) { ?>
                                        <input type="hidden" name="id[]" id="id<?= $row['gd_id'] ?>" value="<?= $row['gd_id'] ?>">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-default">
                                                    <label>Tahun ke <?= $no ?></label>
                                                    <input type="text" id="nominal_default<?= $no; ?>" class="form-control" value="<?= rupiah($row['gd_nominal']) ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-default">
                                                    <label>Nominal</label>
                                                    <input id="nominal<?= $no; ?>" name="nominal[]" type="text" class="form-control currency" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            var nominal = 0;
                                            nominal = parseInt(<?= $row['gd_nominal'] ?>).formatMoney(0, '.', ',');

                                            $('#nominal' + <?= $no ?>).val(nominal);
                                        </script>
                                    <?php
                                        $no++;
                                    } ?>
                                </div>
                            </div>
                            <div class="modal-footer no-bd">
                                <button type="button" class="btn btn-secondary" onclick="window.close();"><i class="fa fa-undo"></i> Kembali</button>

                                <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                <!-- <button type="button" id="btn-cancel" class="btn btn-danger">Batal</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            setDataTable('#table-data', '');
            currencyFormat();

        }).on('submit', '#form-data', function(e) {
            e.preventDefault();
            var b = $('#btn-save'),
                i = b.find('i'),
                cls = i.attr('class');

            $.ajax({
                type: 'POST',
                url: '<?= base_url('Golongan/save_detail') ?>',
                data: $(this).serializeArray(),
                dataType: 'JSON',
                beforeSend: function() {
                    b.attr("disabled", true);
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(r) {
                    if (r.status) {
                        var no = 0;
                        var nominal = 0;
                        $.each(r.detail, function(i, item) {
                            nominal = parseInt(item.gd_nominal).formatMoney(0, '.', ',');
                            $('#form-data').find('#nominal_default' + no).val("Rp " + nominal);

                            no++;
                        });

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

        function setDataTable(a, tbody = '') {
            if ($.fn.DataTable.isDataTable(a)) {
                $(a).dataTable().fnDestroy();
            }

            if (tbody != '') {
                $(a).find('tbody').html(tbody);
            }

            $(a).DataTable({
                "responsive": true,
                // "lengthChange": false,
                "autoWidth": false,
                // "scrollX": true,
                // "scrollY": "400px",
                "scrollCollapse": true,
                "paging": true,
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": [{
                    "targets": [0],
                    "width": "10%",
                }, {
                    "targets": [1],
                    "width": "20%",
                }, {
                    "targets": [2],
                    "width": "20%",
                }, {
                    "targets": [3],
                    "width": "40%",
                }, {
                    "targets": [4],
                    "className": "text-center",
                    "width": "10%",
                    "orderable": false,
                }],
                "initComplete": function() {

                    $('[data-toggle="tooltip"]').tooltip();
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
    </script>