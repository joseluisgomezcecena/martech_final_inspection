<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">CREAR CAPTURA POR RETRABAJO</h4>
		</div>

		<div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<a href="javascript:history.back()" target="" class="btn btn-light  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
						<i class="fa fa-arrow-left" style="color:#000;" aria-hidden="true"></i>
					</a>
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

			<div class="white-box analytics-info">
				<h3 class="box-title">Captura de orden</h3>

				<div class="row">
					<div class="col-lg-12">
						<?php echo form_open('entries/rework') ?>
						<h3 class="box-title mb-2 text-primary"><?php echo $message ?></h3>


						<div class="mt-5 mb-5">
							<?php echo validation_errors(
								'<div class="alert alert-danger alert-dismissible fade show" role="alert">',
								'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
							); ?>
						</div>


						<!-- DO NOT DELETE THESSE ARE FOR REDIRECTION PURPOSES -->
						<input type="text" hidden class="form-control" name="from" value="<?= $from ?>">
						<input type="text" hidden class="form-control" name="reload_route" value="<?= $reload_route ?>">
						<input type="text" hidden class="form-control" name="start_date" value="<?= $start_date ?>">
						<input type="text" hidden class="form-control" name="end_date" value="<?= $end_date ?>">

						<div class="row">
							<div class="col-lg-4 col-md-6 col-sm-12">
								<label for="browser" class="text-primary">NO. DE PARTE</label>
								<input class="form-control" list="part" name="part_no" value="<?= $old["part_no"] ?>" id="part_no" required oninvalid="this.setCustomValidity('Escriba el no. de Parte')" oninput="this.setCustomValidity('')" style="text-transform: uppercase;">

								<datalist id="part">
									<?php foreach ($parts as $part) : ?>
										<option value="<?php echo $part['COL 1'] ?>">
										<?php endforeach; ?>
								</datalist>
							</div>


							<div class="col-lg-4 col-md-6 col-sm-12">
								<label for="" class="text-primary">NO. DE LOTE</label>
								<input type="text" class="form-control" name="lot_no" value="<?= $old["lot_no"] ?>" required oninvalid="this.setCustomValidity('Escriba el no. de Lote')" oninput="this.setCustomValidity('')" style="text-transform: uppercase;">
							</div>


							<div class="col-lg-4 col-md-6 col-sm-12">
								<label for="" class="text-primary">CANTIDAD ENVIADA</label>
								<input type="number" class="form-control" name="qty" value="<?= $old["qty"] ?>" required oninvalid="this.setCustomValidity('Coloque la cantidad enviada')" oninput="this.setCustomValidity('')">
							</div>


							<div class="col-lg-4 col-md-6 col-sm-12 mt-5">
								<label for="" class="text-primary">PLANTA</label>
								<select class="form-control" name="plant" id="plant_id" required oninvalid="this.setCustomValidity('Elija la planta')" oninput="this.setCustomValidity('')" <?php if ($this->session->userdata(PLANT_ID) > 0) echo ' readonly' ?> style="text-transform: uppercase;">
									<option value="">Seleccione una planta</option>
									<?php
									foreach ($plantas as $planta) {
										$str_option = '<option value="' . $planta['planta_id'] . '" ';
										if ($planta['planta_id'] == $old["plant"] || $planta['planta_id'] ==  $this->session->userdata(PLANT_ID))
											$str_option .= 'selected';
										$str_option .= ' >' . $planta['planta_nombre'] . '</option>';
										echo $str_option;
									}
									?>
								</select>
							</div>


							<div class="col-lg-4 col-md-6 col-sm-12 mt-5">
								<label for="assigned_by" class="text-primary">SUPERVISOR / GUIA</label>
								<input class="form-control" list="production_users" name="assigned_by" id="assigned_by" value="<?= $old["assigned_by"] ?>" id="assigned_by" required oninvalid="this.setCustomValidity('Escriba el Nombre del Supervisor o Guía')" oninput="this.setCustomValidity('')" style="text-transform: uppercase;">

								<datalist id="production_users">
									<?php foreach ($production_users as $supervisor) : ?>
										<option value="<?php echo $supervisor['user_martech_sign'] ?>">
										<?php endforeach; ?>
								</datalist>
							</div>


							<div class="col-lg-4 col-md-6 col-sm-12 mt-5">

								<label for="assigned_by" class="text-primary">CARACTERÍSTICA(S)</label>
								<div class="form-group">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="parcial" <?php if ($parcial == '1') echo 'checked'; ?>>
										<label class="form-check-label" for="inlineCheckbox1">Parcial</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="reinspeccion" <?php if ($reinspeccion == '1') echo 'checked'; ?>>
										<label class="form-check-label" for="inlineCheckbox2">Reinspeccion</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="rm" value="1">
										<label class="form-check-label" for="inlineCheckbox3">RM</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox4" name="ficticio" <?php if ($ficticio == '1') echo 'checked'; ?>>
										<label class="form-check-label" for="inlineCheckbox4">Ficticio</label>
									</div>
								</div>



							</div>

							<div class="col-lg-12 mt-5 mb-5 text-start">

								<input class="form-check-input" type="checkbox" name="has_urgency" id="flexCheckDefault">
								<label class="form-check-label" for="flexCheckDefault">
									Es Urgente?
								</label>
							</div>



						</div>




						<div class="form-group">
							<input style="width: 100%" type="submit" name="save_asistencia" class="btn btn-primary text-white btn-lg" value="Enviar a Inspección">
						</div>
						<?php echo form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="<?= base_url() ?>assets/jquery/jquery-3.5.1.js"></script>

<script type='text/javascript'>
	// baseURL variable
	var baseURL = "<?php echo base_url(); ?>";

	//Asistencia
	$(document).ready(function() {
		// Plant change
		$('#plant_id').change(function() {

			console.log("Cambio");

			var plant_id = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?= base_url() ?>index.php/Forms/get_sites',
				method: 'post',
				data: {
					plant_id: plant_id
				},
				dataType: 'json',
				success: function(response) {

					$('#linea_id').find('option').remove();
					// fill select
					$.each(response, function(index, data) {
						$('#linea_id').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});


		//Movimientos


		$('#plant_id_one').change(function() {

			console.log("Cambio");

			var plant_id_one = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?= base_url() ?>index.php/Forms/get_sites',
				method: 'post',
				data: {
					plant_id: plant_id_one
				},
				dataType: 'json',
				success: function(response) {

					$('#line_id_one').find('option').remove();
					// fill select
					$.each(response, function(index, data) {
						$('#line_id_one').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});



		$('#plant_id_two').change(function() {

			console.log("Cambio");

			var plant_id_two = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?= base_url() ?>index.php/Forms/get_sites',
				method: 'post',
				data: {
					plant_id: plant_id_two
				},
				dataType: 'json',
				success: function(response) {

					$('#line_id_two').find('option').remove();
					// fill select
					$.each(response, function(index, data) {
						$('#line_id_two').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});




		//tiempo extra


		$('#te_planta').change(function() {

			console.log("Cambio");

			var plant_te = $(this).val();

			// Ajax request
			$.ajax({
				url: '<?= base_url() ?>index.php/Forms/get_sites',
				method: 'post',
				data: {
					plant_id: plant_te
				},
				dataType: 'json',
				success: function(response) {

					$('#te_linea').find('option').remove();
					// fill select
					$.each(response, function(index, data) {
						$('#te_linea').append('<option value="' + data['linea_id'] + '">' + data['linea_nombre'] + '</option>');
					});
				}
			});
		});

	}); //end document ready.
</script>