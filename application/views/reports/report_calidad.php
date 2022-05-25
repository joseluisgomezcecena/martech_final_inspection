<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Dashboard</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex breadcrumb">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="breadcrumb-item">Dashboard</a></li>&nbsp;
					<li><a href="#" class="breadcrumb-item">Reporte de calidad</a></li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>


<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Three charts -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">
		<div class="col-lg-12 col-md-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Reporte de Calidad: Ordenes Cerradas</h3>
				<div class="col-lg-12" id="table">

					<div class="table-responsive">
						<table class="table text-nowrap">
							<thead>
							<tr>
								<th class="border-top-0">Folio</th>
								<th class="border-top-0">Parte</th>
								<th class="border-top-0">Lote</th>
								<th class="border-top-0">Cantidad</th>
								<th class="border-top-0">Planta</th>
								<th class="border-top-0">Inicio</th>
								<th class="border-top-0">Tiempo en Asignar</th>
								<th class="border-top-0">Tiempo en Liberar</th>
								<th class="border-top-0">Tiempo en Cerrar</th>
								<th class="border-top-0">Detalles</th>
							</tr>
							</thead>
							<tbody>
								<?php
								foreach ($orders as $order):
								?>
									<tr>
										<td><?php echo $order['id'] ?></td>
										<td><?php echo $order['partno'] ?></td>
										<td><?php echo $order['lotno'] ?></td>
										<td><?php echo $order['qty'] ?></td>
										<td><?php echo $order['plant'] ?></td>
										<td><?php echo date_format(date_create($order['created_at']),"m/d/Y")  ?></td>
										<td>
											<?php
												$t1 = strtotime( $order['created_at'] );
												$t2 = strtotime( $order['asignada_date'] );
												$diff = $t1 - $t2;
												echo $hours = $diff / ( 60 * 60 );
											?>
										</td>
										<td>
											<?php
											$t1 = strtotime( $order['created_at'] );
											$t2 = strtotime( $order['liberada_date'] );
											$diff = $t1 - $t2;
											echo $hours = $diff / ( 60 * 60 );
											?>
										</td>
										<td>
											<?php
											$t1 = strtotime( $order['created_at'] );
											$t2 = strtotime( $order['cerrada_date'] );
											$diff = $t1 - $t2;
											echo $hours = $diff / ( 60 * 60 );
											?>
										</td>
										<td>
											<a href="<?php echo base_url() ?>reports/details/<?php echo $order['id'] ?>" class="btn btn-primary">Detalles</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>












</div>
