<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Dashboard</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="fw-normal">Dashboard</a></li>
				</ol>
				<a href="<?php echo base_url() ?>forms/create" target=""
				   class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
					Agregar Datos
				</a>
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
				<h3 class="box-title">Base de datos asistencia</h3>
				<div class="col-lg-12" id="table">

					<div class="table-responsive">
						<table class="table text-nowrap">
							<thead>
							<tr>
								<th class="border-top-0">Fecha</th>
								<th class="border-top-0">Turno</th>
								<th class="border-top-0">Planta</th>
								<th class="border-top-0">Linea</th>
								<th class="border-top-0">Operadores</th>
								<th class="border-top-0">Registrado:</th>
							</tr>
							</thead>
							<tbody>
								<?php
								foreach ($asistencias as $asistencia):
								?>
									<tr>
										<td><?php echo date_format(date_create($asistencia['asistencia_fecha']),"m/d/Y")  ?></td>
										<td><?php echo $asistencia['asistencia_turno'] ?></td>
										<td><?php echo $asistencia['planta_nombre'] ?></td>
										<td><?php echo $asistencia['linea_nombre'] ?></td>
										<td><?php echo $asistencia['asistencia_operadores'] ?></td>
										<td><?php echo date_format( date_create($asistencia['created_at']),"m/d/Y H:i:s") ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>









	<div class="row justify-content-center">
		<div class="col-lg-12 col-md-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Base de datos movimientos</h3>
				<div class="col-lg-12" id="table">

					<div class="table-responsive">
						<table class="table text-nowrap">
							<thead>
							<tr>
								<th class="border-top-0">Fecha</th>
								<th class="border-top-0">Turno</th>
								<th class="border-top-0">De Planta</th>
								<th class="border-top-0">A Planta</th>
								<th class="border-top-0">De Linea</th>
								<th class="border-top-0">A Linea</th>
								<th class="border-top-0">Operadores</th>
								<th class="border-top-0">Horas</th>
								<th class="border-top-0">Registrado:</th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ($movimientos as $movimiento):
								?>
								<tr>
									<td><?php echo date_format(date_create($movimiento['movimientos_fecha']),"m/d/Y")  ?></td>
									<td><?php echo $movimiento['movimientos_turno'] ?></td>
									<td><?php echo $movimiento['planta_origen'] ?></td>
									<td><?php echo $movimiento['planta_destino'] ?></td>
									<td><?php echo $movimiento['linea_origen'] ?></td>
									<td><?php echo $movimiento['linea_destino'] ?></td>
									<td><?php echo $movimiento['movimientos_operadores'] ?></td>
									<td><?php echo $movimiento['movimientos_horas'] ?></td>
									<td><?php echo date_format( date_create($movimiento['created_at']),"m/d/Y H:i:s") ?></td>
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
