<form method="get" action="<?= base_url() ?>pages/home">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">PANORAMA GENERAL</h4>
            </div>


            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex breadcrumb">
                    <ol class="breadcrumb ms-auto">

                        <li>
                            <div class="input-group mb-3">
                                <span class="input-group-text" for="inputStartDate">Desde</span>
                                <input type="date" class="form-control" id="inputStartDate" name="start_date" value="<?= $start_date ?>" aria-describedby="Fecha Inicial" placeholder="Seleccionar Fecha">
                            </div>
                        </li>

                        <li>
                            <div class="input-group mb-3">
                                <span class="input-group-text" for="inputEndDate">Hasta</span>
                                <input type="date" class="form-control" id="inputEndDate" name="end_date" value="<?= $end_date ?>" aria-describedby="Fecha Final">
                                <button type="submit" class="btn btn-primary">Ver Periodo</button>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>


            <div class="col-lg-12">
                <?php if ($user_type == PRODUCTION_USER) : ?>
                    <a href="<?php echo base_url() ?>entries/create" target="" class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
                        Registrar una entrada
                    </a>
                <?php endif; ?>
            </div>

        </div>
        <!-- /.col-lg-12 -->
    </div>
</form>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Three charts -->
    <!-- ============================================================== -->
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-12">
            <div class="white-box analytics-info">
                <canvas id="chartGlobal"></canvas>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="white-box analytics-info">
                <canvas id="chartGlobalStatusOpened"></canvas>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="white-box analytics-info">
                <canvas id="chartGlobalAcceptedRejected"></canvas>
            </div>
        </div>
    </div>




    <?php foreach ($plants as $plant) : ?>


        <div class="row justify-content-center">

            <h3 class="page-title text-center"><?= $plant['plant'] ?></h3>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info">
                    <canvas id="chartGlobal<?= $plant['planta_id'] ?>"></canvas>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info">
                    <canvas id="chartGlobalStatusOpened<?= $plant['planta_id'] ?>"></canvas>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info">
                    <canvas id="chartGlobalAcceptedRejected<?= $plant['planta_id'] ?>"></canvas>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>



<script>
    const configGlobalOpenedClosed = {
        type: 'pie',
        data: {
            labels: ['Abiertas', 'Cerradas'],
            datasets: [{
                label: 'Dataset 1',
                data: [<?php echo array_sum(array_column($plants, 'opened')) ?>, <?php echo array_sum(array_column($plants, 'closed')) ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                ],

            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'TOTAL DE ORDENES'
                },

            },
            'onClick': function(evt, item) {
                //console.log('legend onClick', evt);
                if (item.length > 0) {
                    console.log(item[0].index);
                }

            }
        },
    };
    const ctx = document.getElementById('chartGlobal').getContext('2d');
    const myChart = new Chart(ctx, configGlobalOpenedClosed);


    //chartGlobalStatusOpened
    const configChartGlobalStatusOpened = {
        type: 'pie',
        data: {
            labels: ['Sin asignar', 'Asignadas', 'Liberadas'],
            datasets: [{
                label: 'Dataset 1',
                data: [<?php echo array_sum(array_column($plants, 'not_assigned')) ?>, <?php echo array_sum(array_column($plants, 'assigned')) ?>, <?php echo array_sum(array_column($plants, 'released')) ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(75, 192, 192)',
                ],

            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'ORDENES ABIERTAS'
                },

            }
        },
    };

    const ctxStatusOpened = document.getElementById('chartGlobalStatusOpened').getContext('2d');
    const myChartStatusOpened = new Chart(ctxStatusOpened, configChartGlobalStatusOpened);


    //chartGlobalAcceptedRejected
    const configChartGlobalAcceptedRejected = {
        type: 'pie',
        data: {
            labels: ['Rechazadas', 'Esperando', 'Aceptadas'],
            datasets: [{
                label: 'Dataset 1',
                data: [<?php echo array_sum(array_column($plants, 'rejected')) ?>, <?php echo array_sum(array_column($plants, 'waiting')) ?>, <?php echo array_sum(array_column($plants, 'accepted')) ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(75, 192, 192)',
                ],

            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'ESTADO DE LAS ORDENES'
                },

            }
        },
    };

    const ctxAcceptedRejected = document.getElementById('chartGlobalAcceptedRejected').getContext('2d');
    const myChartAcceptedRejected = new Chart(ctxAcceptedRejected, configChartGlobalAcceptedRejected);


    <?php foreach ($plants as $plant) : ?>

        const configGlobalOpenedClosed<?= $plant['planta_id'] ?> = {
            type: 'pie',
            data: {
                labels: ['Abiertas', 'Cerradas'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [<?php echo $plant['opened'] ?>, <?php echo  $plant['closed'] ?>],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                    ],

                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'TOTAL DE ORDENES'
                    },

                }
            },
        };
        const ctx<?= $plant['planta_id'] ?> = document.getElementById('chartGlobal<?= $plant['planta_id'] ?>').getContext('2d');
        const myChart<?= $plant['planta_id'] ?> = new Chart(ctx<?= $plant['planta_id'] ?>, configGlobalOpenedClosed<?= $plant['planta_id'] ?>);





        const configChartGlobalStatusOpened<?= $plant['planta_id'] ?> = {
            type: 'pie',
            data: {
                labels: ['Sin asignar', 'Asignadas', 'Liberadas'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [<?php echo $plant['not_assigned'] ?>, <?php echo $plant['assigned'] ?>, <?php echo $plant['released'] ?>],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(75, 192, 192)',
                    ],

                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'ORDENES ABIERTAS'
                    },

                }
            },
        };

        const ctxStatusOpened<?= $plant['planta_id'] ?> = document.getElementById('chartGlobalStatusOpened<?= $plant['planta_id'] ?>').getContext('2d');
        const myChartStatusOpened<?= $plant['planta_id'] ?> = new Chart(ctxStatusOpened<?= $plant['planta_id'] ?>, configChartGlobalStatusOpened<?= $plant['planta_id'] ?>);



        //chartGlobalAcceptedRejected
        const configChartGlobalAcceptedRejected<?= $plant['planta_id'] ?> = {
            type: 'pie',
            data: {
                labels: ['Rechazadas', 'Esperando', 'Aceptadas'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [<?php echo $plant['rejected'] ?>, <?php echo $plant['waiting'] ?>, <?php echo $plant['accepted'] ?>],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(75, 192, 192)',
                    ],

                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'ESTADO DE LAS ORDENES'
                    },

                }
            },
        };

        const ctxAcceptedRejected<?= $plant['planta_id'] ?> = document.getElementById('chartGlobalAcceptedRejected<?= $plant['planta_id'] ?>').getContext('2d');
        const myChartAcceptedRejected<?= $plant['planta_id'] ?> = new Chart(ctxAcceptedRejected<?= $plant['planta_id'] ?>, configChartGlobalAcceptedRejected<?= $plant['planta_id'] ?>);

    <?php endforeach; ?>
</script>