<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Final Inspection</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png">
    <!-- Custom CSS -->
    <link href="<?php echo base_url() ?>assets/css/style.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/datatables/DataTables-1.12.1/css/dataTables.bootstrap5.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrappers" data-layout="vertical">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->



        <div class="page-wrapper">


            <div class="row justify-content-center">
                <div class="col-lg-2">
                    <img lass="img-fluid" src="<?php echo base_url() ?>assets/img/logo.png" />
                </div>

                <h2 class="box-title text-center">FINAL INSPECTION</h2>
            </div>





            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-12">


                    <!-- <a href="<?= base_url() ?>home/production">
                        <div class="white-box analytics-info mt-4">
                            <h3 class="box-title">PRODUCCION</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        </a>
                        -->

                    <div class="white-box analytics-info mt-4">

                        <?php
                        echo '<form action="' . base_url() . 'pages/default" method="post">';
                        ?>
                        <div class="row">

                            <input name="user" value="<?= PRODUCTION_USER ?>" hidden />
                            <input name="plant_name" value="Todas las Plantas" hidden />
                            <input name="plant_id" value="0" hidden />
                            <button class="btn btn-light" type="submit">
                                <h3 class="text-center">PRODUCCIÓN</h3>
                            </button>
                        </div>

                        </form>

                        <div class="row mt-2">

                            <?php
                            foreach ($plantas as $planta) {


                                echo '<form action="' . base_url() . 'pages/default" method="post">';
                                echo  '  <div class="row mt-2 mb-2">';

                                echo  '    <input name="user" value="' . PRODUCTION_USER . '" hidden/>';
                                echo  '    <input name="plant_name" value="' . $planta['planta_nombre'] . '" hidden/>';
                                echo  '    <input name="plant_id" value="' . $planta['planta_id'] . '" hidden/>
        
            <button class="btn btn-light">
                <h3 class="box-title" type="submit">' . $planta['planta_nombre'] . '</h3>
            </button>';


                                echo '</div>';
                                echo "</form>";
                            }
                            ?>



                        </div>

                    </div>

                </div>


                <div class="col-lg-5 col-md-12">


                    <div class="white-box analytics-info mt-4">

                        <?php
                        echo '<form action="' . base_url() . 'pages/default" method="post">';
                        ?>
                        <div class="row">

                            <input name="user" value="<?= QUALITY_USER ?>" hidden />
                            <input name="plant_name" value="Todas las Plantas" hidden />
                            <input name="plant_id" value="0" hidden />
                            <button class="btn btn-light" type="submit">
                                <h3 class="text-center">CALIDAD</h3>
                            </button>
                        </div>

                        </form>

                        <div class="row mt-2">

                            <?php
                            foreach ($plantas as $planta) {


                                echo '<form action="' . base_url() . 'pages/default" method="post">';
                                echo  '  <div class="row mt-2 mb-2">';

                                echo  '    <input name="user" value="' . QUALITY_USER . '" hidden/>';
                                echo  '    <input name="plant_name" value="' . $planta['planta_nombre'] . '" hidden/>';
                                echo  '    <input name="plant_id" value="' . $planta['planta_id'] . '" hidden/>
                                
                                    <button class="btn btn-light">
                                        <h3 class="box-title" type="submit">' . $planta['planta_nombre'] . '</h3>
                                    </button>';


                                echo '</div>';
                                echo "</form>";
                            }
                            ?>



                        </div>

                    </div>


                    <!--
                    <a href="<?= LOGIN_URL ?>">
                        <div class="white-box analytics-info mt-4">
                            <h3 class="box-title">CALIDAD</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </a>
                        -->


                </div>

            </div>


            <?php

            if (isset($error_message)) {
                echo '
                <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong class="uppercase"><bdi>Ha ocurrido un error. </bdi></strong>'
                    . $error_message .
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                </div>
                </div>';
            }

            ?>



            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo base_url() ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url() ?>assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url() ?>assets/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/custom.js"></script>
</body>

</html>