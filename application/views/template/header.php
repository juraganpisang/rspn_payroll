<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Payroll | <?= $title; ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= config_item('img') ?>logo_pantinirmala.png" type="image/x-icon" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= config_item('css') ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= config_item('css') ?>atlantis.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= config_item('vendor') ?>sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Datatable -->
    <link rel="stylesheet" href="<?= config_item('vendor') ?>datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= config_item('vendor') ?>datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= config_item('vendor') ?>datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- dari controller -->
    <?php
    if (isset($css)) {
        foreach ($css as $value) { ?>
            <link rel="stylesheet" href="<?= config_item('vendor') . $value ?>">
    <?php }
    } ?>

    <!-- SCRIPT -->
    <!-- Fonts and icons -->
    <script src="<?= config_item('js') ?>webfont.min.js"></script>
    <!--   Core JS Files   -->
    <script src="<?= config_item('js') ?>jquery.3.2.1.min.js"></script>
    <script src="<?= config_item('js') ?>popper.min.js"></script>
    <script src="<?= config_item('js') ?>bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?= config_item('vendor') ?>jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="<?= config_item('vendor') ?>jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="<?= config_item('vendor') ?>jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="<?= config_item('vendor') ?>jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- DataTables -->
    <script src="<?= config_item('vendor') ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= config_item('vendor') ?>jszip/jszip.min.js"></script>
    <script src="<?= config_item('vendor') ?>pdfmake/pdfmake.min.js"></script>
    <script src="<?= config_item('vendor') ?>pdfmake/vfs_fonts.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= config_item('vendor') ?>datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="<?= config_item('vendor') ?>jqvmap/jquery.vmap.min.js"></script>
    <script src="<?= config_item('vendor') ?>jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert2 -->
    <script src="<?= config_item('vendor') ?>sweetalert2/sweetalert2.min.js"></script>

    <!-- Atlantis JS -->
    <script src="<?= config_item('js') ?>atlantis.min.js"></script>

    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- dari controller -->
    <?php if (isset($js)) {
        foreach ($js as $value) { ?>
            <script src="<?= config_item('vendor') . $value ?>"></script>
    <?php }
    } ?>
    <script>
        // type = 'success, info, error, warning'
        var sweetMsg = function(type, text) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: type,
                title: text
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            var nav_active = '<?= $nav_id; ?>';
            $("#" + nav_active).parent().addClass("active");
            if ($("#" + nav_active).parents('.collapse').length == 1) {
                $("#" + nav_active).parents('.collapse').parent().addClass('active submenu');
                $("#" + nav_active).parents('.collapse').parent().find('a[data-toggle=collapse]').trigger('click');
            }
        });
    </script>
</head>

<body data-background-color="bg3">
    <div class="wrapper <?= $sidebar_mode ?>">
        <div class="main-header">
            <!-- Logo Header -->
            <?php if ($sidebar_mode == 'overlay-sidebar') { ?>
                <div class="logo-header" data-background-color="white">
                    <a href="<?= base_url('dashboard') ?>" class="logo">
                        <img src="<?= config_item('img') ?>logo_pantinirmala_panjang.png" alt="navbar brand" class="navbar-brand" style="max-height:50px;">
                    </a>
                    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="icon-menu"></i>
                        </span>
                    </button>
                    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle sidenav-overlay-toggler toggled"><i class="icon-options-vertical"></i></button>
                    </div>
                </div>
            <?php } else { ?>
                <div class="logo-header" data-background-color="white">
                    <a href="<?= base_url('dashboard') ?>" class="logo">
                        <img src="<?= config_item('img') ?>logo_pantinirmala_panjang.png" alt="navbar brand" class="navbar-brand" style="max-height:50px;">
                    </a>
                    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="fas fa-bars"></i>
                        </span>
                    </button>
                    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            <?php } ?>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue">

                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="<?= config_item('img') ?>/nurse.png" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img src="<?= config_item('img') ?>/nurse.png" alt="image profile" class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4><?= $u_fullname; ?></h4>
                                                <p class="text-muted"><?= $u_name; ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Profile</a>
                                        <a class="dropdown-item" href="<?= base_url('history') ?>">History</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= base_url('Auth/do_logout') ?>">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar sidebar-style-1">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-primary">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard') ?>" id="nav_dashboard">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('gaji_generate') ?>" id="nav_gaji">
                                <i class="fas fa-wallet"></i>
                                <p>Gaji Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('locker') ?>" id="nav_locker">
                                <i class="fas fa-lock text-warning"></i>
                                <p>Locker</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#laporan">
                                <i class="fas fa-copy"></i>
                                <p>Laporan</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="laporan">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('lap_gaji') ?>" id="nav_l_gaji">
                                            <span class="sub-item">Gaji</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('lap_gaji_unit') ?>" id="nav_l_gaji_unit">
                                            <span class="sub-item">Gaji Unit</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#pegawai">
                                <i class="fas fa-user-friends"></i>
                                <p>Pegawai</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="pegawai">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('pengangkatan') ?>" id="nav_pengangkatan">
                                            <span class="sub-item text-primary">Pengangkatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('catatan') ?>" id="nav_catatan">
                                            <span class="sub-item">Catatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('punishment') ?>" id="nav_punishment">
                                            <span class="sub-item text-danger">Punishment</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#setting">
                                <i class="fas fa-file-signature"></i>
                                <p>Adjustment</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="setting">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('mutasi_slip') ?>" id="nav_mutasi_slip">
                                            <span class="sub-item">Pindah Slip Gaji</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('revisi_slip') ?>" id="nav_revisi_slip">
                                            <span class="sub-item">Revisi Slip Gaji</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#mapping">
                                <i class="fas fa-sitemap"></i>
                                <p>Mapping</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="mapping">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('mapping_gaji') ?>" id="nav_mapping_gaji">
                                            <span class="sub-item">Gaji</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('mapping_jabatan') ?>" id="nav_jabatan">
                                            <span class="sub-item">Jabatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('mapping_slip') ?>" id="nav_mapping_slip">
                                            <span class="sub-item">Kelompok Slip</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#master">
                                <i class="fas fa-database"></i>
                                <p>Master</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="master">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('organisasi') ?>" id="nav_organisasi">
                                            <span class="sub-item">Struktur Organisasi</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('gaji_table') ?>" id="nav_gaji_table">
                                            <span class="sub-item">Tabel Gaji</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('tunjangan_fungsi') ?>" id="nav_tunjangan_fungsi">
                                            <span class="sub-item">Tunjangan Fungsi</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('tunjangan_jabatan') ?>" id="nav_tunjangan_jabatan">
                                            <span class="sub-item">Tunjangan Jabatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('insentif_jabatan') ?>" id="nav_insentif_jabatan">
                                            <span class="sub-item">Insentif Jabatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('additional_gaji') ?>" id="nav_additional_gaji">
                                            <span class="sub-item">Penambahan & Potongan Gaji</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('additional') ?>" id="nav_additional">
                                            <span class="sub-item">Penambahan & Potongan PPh</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->