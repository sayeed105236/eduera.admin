<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link name="favicon" type="image/x-icon" href="<?php echo base_url().'uploads/system/eduera-favicon.png' ?>" rel="shortcut icon" />

    <title>Login | Eduera admin panel</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <!-- NProgress -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/nprogress/nprogress.css') ?>" type="text/css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/animate.css/animate.min.css') ?>" type="text/css">

    <!-- Custom Theme Style -->
    <link rel="stylesheet" href="<?= base_url('assets/build/css/custom.min.css') ?>" type="text/css">
</head>
<body class="login">
    <div>      
        <div style="padding: 10px 142px; margin-top: 20px;">
            <?php echo validation_errors(); ?>


            <?php if ($this->session->flashdata('invalid_credential')) { ?>

            <div class="alert alert-danger alert-dismissible show" role="alert">
                <strong></strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= $this->session->flashdata('invalid_credential') ?>
            </div>

            <?php } ?>

        </div>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="<?= base_url('login') ?>" method="post">
                        <h1>Login Form</h1>
                        <div>
                            <input type="email" class="form-control" name="login_email" placeholder="Email" />
                        </div>
                        <div>
                            <input type="password" class="form-control" name="login_password" placeholder="Password" />
                        </div>
                        <div>
                            <button class="btn" type="submit">Login</button>
                            <a class="reset_pass" href="#">Lost your password?</a>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div>
                                <img src="<?php echo base_url().'uploads/system/eduera-logo-small.png';?>" alt="" style="padding: 25px; width: 200px;">
                                <p>©2020 All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>

    </div>
    <script src="<?php echo base_url('assets/vendors/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
