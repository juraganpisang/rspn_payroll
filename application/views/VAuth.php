<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll | Login</title>
    <link rel="icon" href="<?= config_item('img') ?>logo_pantinirmala.png" type="image/x-icon" />
    <link rel="stylesheet" href="<?= config_item('css') ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= config_item('vendor') ?>sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        body {
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-color: #B0BEC5;
            background-repeat: no-repeat;
        }

        .card0 {
            box-shadow: 0px 4px 8px 0px #757575;
            border-radius: 0px;
        }

        .card2 {
            margin: 0px 40px;
        }

        .logo {
            width: 200px;
            height: 100px;
            margin-top: 20px;
            margin-left: 35px;
        }

        .image {
            width: 360px;
            height: 280px;
        }

        .border-line {
            border-right: 1px solid #EEEEEE;
        }

        .facebook {
            background-color: #3b5998;
            color: #fff;
            font-size: 18px;
            padding-top: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
        }

        .twitter {
            background-color: #1DA1F2;
            color: #fff;
            font-size: 18px;
            padding-top: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
        }

        .linkedin {
            background-color: #2867B2;
            color: #fff;
            font-size: 18px;
            padding-top: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
        }

        .line {
            height: 1px;
            width: 45%;
            background-color: #E0E0E0;
            margin-top: 10px;
        }

        .or {
            width: 10%;
            font-weight: bold;
        }

        .text-sm {
            font-size: 14px !important;
        }

        ::placeholder {
            color: #BDBDBD;
            opacity: 1;
            font-weight: 300
        }

        :-ms-input-placeholder {
            color: #BDBDBD;
            font-weight: 300
        }

        ::-ms-input-placeholder {
            color: #BDBDBD;
            font-weight: 300
        }

        input,
        textarea {
            padding: 10px 12px 10px 12px;
            border: 1px solid lightgrey;
            border-radius: 2px;
            margin-bottom: 5px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            color: #2C3E50;
            font-size: 14px;
            letter-spacing: 1px;
        }

        input:focus,
        textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #304FFE;
            outline-width: 0;
        }

        button:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            outline-width: 0;
        }

        a {
            color: inherit;
            cursor: pointer;
        }

        .btn-blue {
            background-color: #1572e8;
            width: 150px;
            color: #fff;
            border-radius: 2px;
        }

        .btn-blue:hover {
            background-color: #000;
            cursor: pointer;
        }

        .bg-blue {
            color: #fff;
            background-color: #1572e8;
        }

        @media screen and (max-width: 991px) {
            .logo {
                margin-left: 0px;
            }

            .image {
                width: 300px;
                height: 220px;
            }

            .border-line {
                border-right: none;
            }

            .card2 {
                border-top: 1px solid #EEEEEE !important;
                margin: 0px 15px;
            }
        }
    </style>

    <script src="<?= config_item('js') ?>jquery.3.2.1.min.js"></script>
    <script src="<?= config_item('js') ?>popper.min.js"></script>
    <script src="<?= config_item('js') ?>bootstrap.min.js"></script>
    <script src="<?= config_item('vendor') ?>sweetalert2/sweetalert2.min.js"></script>
    
    <!-- Fonts and icons -->
    <script src="<?= config_item('js') ?>webfont.min.js"></script>
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
</head>

<body>
    <div class="container-fluid px-1 px-md-4 px-lg-1 px-xl-4 py-4 m-auto h-100">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="h3 pl-4 mt-4" style="color: #1572e8"><b><?= config_item('app_name') ?></b> <small><?= config_item('version') ?> Web-based app</small></div>
                            </div>
                        </div>
                        <div class="row px-3 justify-content-center mb-5 border-line">
                            <img src="<?= config_item('img') ?>undraw_printing_invoices.svg" class="image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card2 card border-0 px-4 py-5 ">
                        <div class="row">
                            <div class="col text-center">
                                <img src="<?= config_item('img') ?>logo_pantinirmala_panjang.png" alt="navbar brand" class="navbar-brand" max-width="300px;" />
                            </div>
                        </div>
                        <form id="form-user">
                            <div class="row px-3">
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Username</h6>
                                </label>
                                <input class="mb-4" type="text" id="username" name="username" placeholder="Username ..." autocomplete="off">
                            </div>
                            <div class="row px-3 mb-4">
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Password</h6>
                                </label>
                                <input type="password" name="password" id="password" placeholder="Password ..." autocomplete="off">
                            </div>
                            <div class="row mb-3 px-3 float-right">
                                <button type="submit" id="btn-login" class="btn btn-primary text-center"><i class="fa fa-sign-in-alt"></i> Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-blue py-4">
                <div class="row px-3">
                    <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2022. All rights reserved. <i class="fa fa-laptop"></i> <small>Developed by</small> Subbag. SIMRS</small>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {}).on('submit', '#form-user', function(e) {

        e.preventDefault();

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        var username = $("#username").val();
        var password = $("#password").val();

        if (username.length == "") {
            Toast.fire({
                icon: 'warning',
                text: 'Username Wajib Diisi'
            });

        } else if (password.length == "") {
            Toast.fire({
                icon: 'warning',
                text: 'Password Wajib Diisi'
            });

        } else {
            e.preventDefault();
            var b = $('#btn-login'),
                i = b.find('i'),
                cls = i.attr('class');
            $.ajax({
                url: "<?php echo base_url() ?>auth/do_login",
                type: "POST",
                data: {
                    "username": username,
                    "password": password
                },
                beforeSend: function() {
                    b.attr("disabled", true);
                    i.removeClass().addClass('fa fa-spin fa-spinner');
                },
                success: function(response) {
                    if (response == "success") {
                        Toast.fire({
                                icon: 'success',
                                text: 'Login berhasil'
                            })
                            .then(function() {
                                window.location.href = "<?php echo base_url() ?>dashboard";
                            });

                    } else {
                        Toast.fire({
                            icon: 'error',
                            text: 'Username / password salah'
                        });

                        b.removeAttr("disabled");
                        i.removeClass().addClass(cls);
                    }
                    console.log(response);
                },

                error: function(response) {
                    Swal.fire({
                        type: 'error',
                        title: 'Opps!',
                        text: 'server error!'
                    });
                    b.removeAttr("disabled");
                    i.removeClass().addClass(cls);
                }
            });
        }
    });
</script>

</html>