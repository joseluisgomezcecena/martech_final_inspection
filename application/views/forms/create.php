<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Captura de horas Aplicadas</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="fw-normal">Captura de horas aplicadas</a></li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>




<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Contenido -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">
		<div class="col-lg-12 col-md-12">

			<?php if($this->session->flashdata('tiempo')): ?>

				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>Success</bdi></strong>
					Se ha registrado el tiempo extra
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

				</div>

			<?php endif; ?>

			<?php if($this->session->flashdata('asistencia')): ?>

				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>Success</bdi></strong>
					Se ha registrado la asistencia
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>

			<?php endif; ?>

			<?php if($this->session->flashdata('movimiento')): ?>

				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>Success</bdi></strong>
					Se ha registrado el movimiento
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>

			<?php endif; ?>


			<div class="white-box analytics-info">
				<h3 class="box-title">Forma de captura</h3>

				<div class="row">
					<div class="col-lg-4">
						<?php echo form_open('forms/create') ?>
						<h3 class="box-title mb-2 text-danger">Asistencia</h3>

						<?php echo validation_errors(); ?>


						<div class="form-group">
							<label for="">Fecha</label>
							<input type="date" class="form-control" name="asistencia_fecha" value="<?php echo date("Y-m-d"); ?>" required>
						</div>


						<div class="form-group">
							<label for="">Turno</label>
							<select class="form-control" name="asistencia_turno" required>
								<option value="">Seleccione</option>
								<option>1er</option>
								<option >2do</option>
								<option >3er</option>
								<option >3X4 A</option>
								<option >3X4 B</option>
								<option >3X4 C</option>
								<option >3X4 D</option>
								<option >Fin de Sem.Mat</option>
								<option >Fin de Sem.Vesp</option>
								<option >T.E. Sabado</option>
								<option >T.E. Domingo</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">Planta</label>
							<select class="form-control" name="asistencia_planta" id="plant_id" required>
								<option value="">Seleccione una planta</option>
								<?php
								foreach ($plantas as $planta):
									?>
									<option value="<?php echo $planta['planta_id'] ?>"><?php echo $planta['planta_nombre'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>


						<div class="form-group">
							<label for="">Linea</label>
							<select class="form-control" name="asistencia_linea" id="linea_id" required>
							</select>
						</div>

						<div class="form-group">
							<label for="">Operadores</label>
							<input type="number" pattern="/^[1-9]+\d*$/" name="asistencia_operadores" class="form-control" min="0" step="1">
						</div>

						<div class="form-group">
							<input style="width: 100%" type="submit" name="save_asistencia" class="btn btn-danger text-white btn-lg" value="Guardar Asistencia">
						</div>
						<?php echo form_close() ?>
					</div>







					<div class="col-lg-4">
						<?php echo form_open('forms/create_movimiento') ?>
						<h3 class="box-title mb-2 text-danger">Movimientos</h3>

						<div class="form-group">
							<label for="">Fecha</label>
							<input type="date" class="form-control" name="movimiento_fecha" value="<?php echo date("Y-m-d"); ?>" required>
						</div>


						<div class="form-group">
							<label for="">Turno</label>
							<select class="form-control" name="movimiento_turno" required>
								<option value="">Seleccione</option>
								<option>1er</option>
								<option >2do</option>
								<option >3er</option>
								<option >3X4 A</option>
								<option >3X4 B</option>
								<option >3X4 C</option>
								<option >3X4 D</option>
								<option >Fin de Sem.Mat</option>
								<option >Fin de Sem.Vesp</option>
								<option >T.E. Sabado</option>
								<option >T.E. Domingo</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">De Planta</label>
							<select class="form-control" name="movimiento_planta_origen" id="plant_id_one" required>
								<option value="">Seleccione una planta</option>
								<?php
								foreach ($plantas as $planta):
								?>
									<option value="<?php echo $planta['planta_id'];?>"><?php echo $planta['planta_nombre'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label for="">A Planta</label>
							<select class="form-control" name="movimiento_planta_destino" id="plant_id_two" required>
								<option value="">Seleccione una planta</option>
								<?php
								foreach ($plantas as $planta):
									?>
									<option value="<?php echo $planta['planta_id'];?>"><?php echo $planta['planta_nombre'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>


						<div class="form-group">
							<label for="">De Linea</label>
							<select class="form-control" name="movimiento_linea_origen" id="line_id_one" required>
								<option value="">Linea 1</option>
							</select>
						</div>



						<div class="form-group">
							<label for="">A Linea</label>
							<select class="form-control" name="movimiento_linea_destino" id="line_id_two" required>
								<option value="">Linea 1</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">Operadores</label>
							<input type="number" pattern="/^[1-9]+\d*$/" name="movimiento_operadores" class="form-control" min="0" step="1">
						</div>


						<div class="form-group">
							<label for="">Cantidad de horas</label>
							<input type="number" pattern="/^[1-9]+\d*$/" class="form-control" name="movimiento_horas" min="0" step="1">
						</div>

						<div class="form-group">
							<input style="width: 100%"  type="submit" name="save_movimiento" class="btn btn-danger text-white btn-lg" value="Guardar Movimientos">
						</div>
						<?php echo form_close() ?>
					</div>





					<div class="col-lg-4">
						<?php echo form_open('forms/create_tiempo_extra') ?>
						<h3 class="box-title mb-2 text-danger">Tiempo Extra</h3>

						<div class="form-group">
							<label for="">Fecha</label>
							<input type="date" class="form-control" name="te_fecha" value="<?php echo date("Y-m-d"); ?>" required>
						</div>


						<div class="form-group">
							<label for="">Turno</label>
							<select class="form-control" name="te_turno" required>
								<option value="">Seleccione</option>
								<option>1er</option>
								<option >2do</option>
								<option >3er</option>
								<option >3X4 A</option>
								<option >3X4 B</option>
								<option >3X4 C</option>
								<option >3X4 D</option>
								<option >Fin de Sem.Mat</option>
								<option >Fin de Sem.Vesp</option>
								<option >T.E. Sabado</option>
								<option >T.E. Domingo</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">Planta</label>
							<select class="form-control" name="te_planta" id="te_planta" required>
								<option value="">Seleccione una planta</option>
								<?php
								foreach ($plantas as $planta):
									?>
									<option value="<?php echo $planta['planta_id'];?>"><?php echo $planta['planta_nombre'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>


						<div class="form-group">
							<label for="">Linea</label>
							<select class="form-control" name="te_linea" id="te_linea" required>
							</select>
						</div>

						<div class="form-group">
							<label for="">Operadores</label>
							<input type="number" pattern="/^[1-9]+\d*$/" class="form-control" name="te_operadores" min="0" step="1">
						</div>


						<div class="form-group">
							<label for="">Cantidad de horas</label>
							<input type="number" pattern="/^[1-9]+\d*$/" class="form-control" min="0" step="1" name="te_horas">
						</div>

						<div class="form-group">
							<input style="width: 100%" type="submit" name="save_te" class="btn btn-danger text-white btn-lg" value="Guardar Tiempo Extra">
						</div>
						<?php echo form_close() ?>
					</div>



				</div>




			</div>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type='text/javascript'>
	// baseURL variable
	var baseURL= "<?php echo base_url();?>";

	//Asistencia
	$(document).ready(function() {
		// Plant change
		$('#plant_id').change(function () {

			console.log("Cambio");

			var plant_id = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?=base_url()?>index.php/Forms/get_sites',
				method: 'post',
				data: {plant_id: plant_id},
				dataType: 'json',
				success: function (response) {

					$('#linea_id').find('option').remove();
					// fill select
					$.each(response, function (index, data) {
						$('#linea_id').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});


		//Movimientos


		$('#plant_id_one').change(function () {

			console.log("Cambio");

			var plant_id_one = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?=base_url()?>index.php/Forms/get_sites',
				method: 'post',
				data: {plant_id: plant_id_one},
				dataType: 'json',
				success: function (response) {

					$('#line_id_one').find('option').remove();
					// fill select
					$.each(response, function (index, data) {
						$('#line_id_one').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});



		$('#plant_id_two').change(function () {

			console.log("Cambio");

			var plant_id_two = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?=base_url()?>index.php/Forms/get_sites',
				method: 'post',
				data: {plant_id: plant_id_two},
				dataType: 'json',
				success: function (response) {

					$('#line_id_two').find('option').remove();
					// fill select
					$.each(response, function (index, data) {
						$('#line_id_two').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});




		//tiempo extra


		$('#te_planta').change(function () {

			console.log("Cambio");

			var plant_te = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?=base_url()?>index.php/Forms/get_sites',
				method: 'post',
				data: {plant_id: plant_te},
				dataType: 'json',
				success: function (response) {

					$('#te_linea').find('option').remove();
					// fill select
					$.each(response, function (index, data) {
						$('#te_linea').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});

	});//end document ready.
</script>


