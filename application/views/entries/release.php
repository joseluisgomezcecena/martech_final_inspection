<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Liberar orden</h4>
		</div>


		<div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<a href="<?php echo base_url() . $reload_route ?>" target="" class="btn btn-light  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
						<i class="fa fa-arrow-left" style="color:#000;" aria-hidden="true"></i>
					</a>
				</ol>

			</div>
		</div>
		-
	</div>
	<!-- /.col-lg-12 -->
</div>




<div class="container-fluid" ng-app="myApp" ng-controller="myCtrl">
	<!-- ============================================================== -->
	<!-- Contenido -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">

		<div class="col-lg-12">
			<?php if ($this->session->flashdata('liberada')) : ?>

				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>CAMBIOS GUARDADOS</bdi></strong>
					<?php echo $this->session->flashdata('liberada'); ?>.
					HAZ CLICK <a href="<?php echo  base_url() ?>">AQUI</a> PARA REGRESAR
					O EDITE ESTE FORMULARIO PARA MODIFICAR LOS DATOS DE ESTA INSPECCION.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

				</div>

			<?php endif; ?>
		</div>


		<div class="col-lg-4 col-md-4">

			<div class="white-box analytics-info">
				<h3 class="box-title">Datos del registro</h3>

				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class=" col-lg-12">
								<label for="browser">Numero de parte:</label>
								<input class="form-control" list="part" value="<?php echo $entry['part_no'] ?>" disabled>
							</div>


							<div class=" col-lg-12">
								<label for="">Numero de lote</label>
								<input type="text" class="form-control" value="<?php echo $entry['lot_no'] ?>" disabled>
							</div>


							<div class=" col-lg-12">
								<label for="">Cantidad enviada</label>
								<input type="number" class="form-control" value="<?php echo $entry['qty'] ?>" disabled>
							</div>


							<div class="col-lg-12">
								<label for="">Planta</label>
								<select class="form-control" name="plant" id="plant_id" disabled>
									<option value="">Seleccione una planta</option>
									<?php
									foreach ($plants as $plant) {
										echo '<option value="' . $plant['planta_id'] . '"';

										if ($plant['planta_id'] == $entry['plant']) {
											echo ' selected';
										}

										echo '>';
										echo $plant['planta_nombre'];
										echo '</option>';
									}
									?>
								</select>
							</div>


							<div class="col-lg-12 mt-3 mb-3 text-primary">
								<?php
								if ($entry['parcial'] == 1) {
									echo "Parcial<br>";
								} else {
									echo "";
								}
								if ($entry['reinspeccion'] == 1) {
									echo "Reinspeccion<br>";
								} else {
									echo "";
								}
								if ($entry['ficticio'] == 1) {
									echo "Ficticio<br>";
								} else {
									echo "";
								}
								if ($entry['discrepancia'] == 1) {
									echo "Discrepancia<br>";
								} else {
									echo "";
								}
								?>

							</div>


						</div>

					</div>
				</div>
			</div>
		</div>
		<!--end col-4-->


		<div class="col-lg-8 col-md-8">

			<div class="white-box analytics-info">
				<h3 class="box-title">Forma de captura</h3>

				<div class="row">
					<div class="col-lg-12">
						<?php echo form_open('entries/release/' .  $entry['id'], $_GET) ?>
						<h3 class="box-title mb-2 text-primary">Liberar Orden</h3>

						<div class="mt-5 mb-5">
							<?php echo validation_errors(
								'<div class="alert alert-danger alert-dismissible fade show" role="alert">',
								'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
							); ?>

							<?php
							if (isset($error_message)) {
								echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> ' . $error_message . '
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
									</button>
								</div>';
							}
							?>
						</div>



						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>" />


						<div class="row">
							<div class=" col-lg-6">

								<label for="status" class="text-primary">RESULTADO DE LA INSPECCI??N</label>
								<select name="status" id="status" class="form-control" ng-model="status" ng-change="select_status()">
									<option value="">-- Seleccione Resultado --</option>
									<option value="<?= STATUS_WAITING ?>" <?php if ($entry['status'] == STATUS_WAITING) echo ' selected'; ?>>EN ESPERA</option>
									<option value="<?= STATUS_REJECTED_BY_PRODUCT ?>" <?php if ($entry['status'] == STATUS_REJECTED_BY_PRODUCT) echo ' selected'; ?>>RECHAZADO POR PRODUCTO</option>
									<option value="<?= STATUS_DISCREPANCY ?>" <?php if ($entry['status'] == STATUS_DISCREPANCY) echo ' selected'; ?>>DISCREPANCIA</option>
									<option value="<?= STATUS_PACK ?>" <?php if ($entry['status'] == STATUS_PACK) echo ' selected'; ?>>PACK</option>
									<option value="<?= STATUS_ACCEPTED ?>" <?php if ($entry['status'] == STATUS_ACCEPTED) echo ' selected'; ?>>ACEPTADO</option>
								</select>
								<small class="text-danger" ng-show="(validate_status && status == '') || (validate_status && status == null)">Seleccione el Resultado</small>
							</div>


							<div class="col-lg-6" ng-show="validate_final_qty">
								<label for="" class="text-primary">CANTIDAD FINAL</label>
								<input type="number" id="qty" class="form-control" name="final_qty" ng-model="final_qty">
								<small class="text-danger" ng-show="(validate_final_qty && final_qty == '')">Elija la cantidad final de la parte</small>
							</div>

						</div>

						<div class="row mt-3">

							<div class=" col-lg-6" ng-show="validate_location">
								<label for="location" class="text-primary">LOCACI??N</label>
								<input name="location" list="list_locations" id="location" class="form-control" ng-model="location" style="text-transform: uppercase;" />

								<datalist id="list_locations">
									<?php foreach ($locations as $location) : ?>
										<option value="<?php echo $location['location_name'] ?>">
										<?php endforeach; ?>
								</datalist>


								<small class=" text-danger" ng-show="(validate_location && location == '')">Seleccione la locaci??n</small>
							</div>


							<div class=" col-lg-6" ng-show="validate_wo_escaneadas">
								<label for="" class="text-primary">WO ESCANEADAS</label>
								<input type="text" id="wo_escaneadas" class="form-control" name="wo_escaneadas" ng-model="wo_escaneadas" style="text-transform: uppercase;">
								<small class=" text-danger" ng-show="(validate_wo_escaneadas && wo_escaneadas == '')">Esciba las work orders escaneadas</small>
							</div>
						</div>


						<div class="row mt-3">
							<div class="col-lg-6" ng-show="validate_has_exp_date">
								<label for="" class="text-primary">TIENE FECHA DE EXPIRACI??N?</label>
								<select class="form-control" name="has_fecha_exp" id="has_exp_date" ng-model="has_exp_date" ng-change="select_fecha_exp()">
									<option value="">-- Seleccione --</option>
									<option value="1">SI</option>
									<option value="0">NO</option>
								</select>
								<small class=" text-danger" ng-show="(validate_has_exp_date && has_exp_date == '')">Seleccione si existe fecha de expiracion</small>
							</div>

							<div class="col-lg-6" id="fecha_exp" ng-show="validate_fecha_exp">
								<label for="" class="text-primary">FECHA DE EXPIRACI??N</label>
								<input type="date" class="form-control" name="fecha_exp" ng-model="fecha_exp" ng-disabled="has_exp_date != 1">
								<small class=" text-danger" ng-show="(validate_fecha_exp && fecha_exp == '')">Establezca la fecha de expiracion</small>
							</div>
						</div>


						<div class="row mt-3">

							<div class="col-lg-6" ng-show="validate_rev_dibujo">
								<label for="" class="text-primary">REVISI??N DE DIBUJO</label>
								<input type="text" class="form-control" id="rev_dibujo" name="rev_dibujo" ng-model="rev_dibujo" style="text-transform: uppercase;">
								<small class=" text-danger" ng-show="(validate_rev_dibujo && rev_dibujo == '')">Escriba la revisi??n del dibujo</small>
							</div>



						</div>

						<div class="row mt-3">
							<div class="col-lg-12" ng-show="validate_empaque">
								<label for="" class="text-primary">EMPAQUE DEL MATERIAL (PSF)</label>
								<input type="text" class="form-control" id="empaque" name="empaque" ng-model="empaque" style="text-transform: uppercase;">
								<small class=" text-danger" ng-show="(validate_empaque && empaque == '')">Escriba la revisi??n del dibujo</small>
							</div>
						</div>

						<div class="row mt-3">
							<div class=" col-lg-6" ng-show="validate_documentos_rev">
								<label for="documentos_rev" class="text-primary">DOCUMENTOS REVISADOS POR</label>

								<input class="form-control" list="list_documentos_rev" id="input_documentos_rev" name="documentos_rev" ng-model="documentos_rev" style="text-transform: uppercase;">
								<datalist id="list_documentos_rev">
									<?php foreach ($quality_users as $user) : ?>
										<option value="<?php echo strtoupper($user['user_martech_sign']); ?>">
										<?php endforeach; ?>
								</datalist>

								<small class=" text-danger" ng-show="(validate_documentos_rev && documentos_rev == '')">Indique los documentos revisados</small>

							</div>

							<div class=" col-lg-6" ng-show="validate_label_zebra_rev">
								<label for="input_label_zebra_rev" class="text-primary">REVISION ETIQUETA MATERIAL ZEBRA</label>

								<input class="form-control" list="list_label_zebra_rev" id="input_label_zebra_rev" name="label_zebra_rev" ng-model="label_zebra_rev" style="text-transform: uppercase;">
								<datalist id="list_label_zebra_rev">
									<?php foreach ($quality_users as $user) : ?>
										<option value="<?php echo strtoupper($user['user_martech_sign']); ?>">
										<?php endforeach; ?>
								</datalist>



							</div>


							<div class=" mb-2 mt-2 col-lg-12" id="razon_rechazo" ng-show="validate_razon_rechazo">
								<label for="" class="text-primary"> {{comments_title}}</label>
								<textarea class="form-control" name="razon_rechazo" rows="8" ng-model="razon_rechazo" style="text-transform: uppercase;"></textarea>
								<small class="text-danger" ng-show="(validate_razon_rechazo && razon_rechazo == '')">{{comments_message_error}}</small>
							</div>

						</div>


						<div class="form-group mt-5">
							<input style="width: 100%" type="submit" name="save_release" class="btn btn-primary text-white btn-lg" value="{{btn_save_title}}" ng-disabled="!( 
								(  validate_status && status != '') && 
								( validate_final_qty == false ? true : (validate_final_qty && final_qty != '')) && 
								( validate_location == false ? true : (validate_location && location != '')) && 
								( validate_wo_escaneadas == false ? true : (validate_wo_escaneadas && wo_escaneadas != ''))  && 
								( validate_has_exp_date == false ? true : (validate_has_exp_date && has_exp_date != ''))    && 
								( validate_fecha_exp == false ? true : (validate_fecha_exp && fecha_exp != ''))    && 
								( validate_rev_dibujo == false ? true : (validate_rev_dibujo && rev_dibujo != ''))    && 
								( validate_empaque == false ? true : (validate_empaque && empaque != ''))    && 
								( validate_documentos_rev == false ? true : (validate_documentos_rev && documentos_rev != ''))    && 
								( validate_razon_rechazo == false ? true : (validate_razon_rechazo && razon_rechazo != ''))  )">

						</div>
						<?php echo form_close() ?>
					</div>
				</div>
			</div>

		</div>


	</div>
</div>


<script src="<?= base_url() ?>assets/jquery/jquery-3.5.1.js"></script>

<script>
	var app = angular.module('myApp', []);
	app.controller('myCtrl', function($scope) {

		$scope.btn_save_title = 'Liberar Entrada';

		$scope.status = '';
		$scope.validate_status = true;


		$scope.final_qty = <?php echo $entry['final_qty'] ?>;
		$scope.validate_final_qty = false;

		$scope.location = '<?php echo $entry['location'] ?>';
		$scope.validate_location = false;

		$scope.wo_escaneadas = '<?php echo $entry['wo_escaneadas'] ?>';
		$scope.validate_wo_escaneadas = false;

		$scope.has_exp_date = '<?php echo $entry['has_fecha_exp'] ?>';
		$scope.validate_has_exp_date = false;

		$scope.fecha_exp = new Date('<?php echo $entry['fecha_exp'] ?>');
		$scope.validate_fecha_exp = false;

		$scope.rev_dibujo = '<?php echo $entry['rev_dibujo'] ?>';
		$scope.validate_rev_dibujo = false;

		$scope.empaque = '<?php echo $entry['empaque'] ?>';
		$scope.validate_empaque = false;

		$scope.documentos_rev = '<?php echo $entry['documentos_rev'] ?>';
		$scope.validate_documentos_rev = false;

		$scope.label_zebra_rev = '<?php echo $entry['label_zebra_rev'] ?>';
		$scope.validate_label_zebra_rev = false;


		$scope.razon_rechazo = <?php echo json_encode($entry['razon_rechazo']); ?>;
		$scope.validate_razon_rechazo = false;

		$scope.comments_title = 'RAZ??N DE RECHAZO / COMENTARIO';
		$scope.comments_message_error = 'Indique la razon del rechazo';

		$scope.select_status = function() {

			console.log('select status' + $scope.status);

			if ($scope.status == null || $scope.status == '') {
				$scope.validate_final_qty = false;
				$scope.validate_location = false;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = false;
				$scope.validate_label_zebra_rev = false;
				$scope.validate_razon_rechazo = false;
			} else if ($scope.status == <?= STATUS_REJECTED_BY_PRODUCT ?>) {
				//No
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = true;
				$scope.validate_label_zebra_rev = false;
				$scope.validate_razon_rechazo = true;
				//console.log('entering here' + $scope.status);
				$scope.btn_save_title = 'Rechazar Producto';
				$scope.comments_title = 'RAZONES DEL RECHAZO';
				$scope.comments_message_error = 'Indique las razones por las cu??les se rechaza la orden';
			} else if ($scope.status == <?= STATUS_DISCREPANCY ?>) {
				//No
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = true;
				$scope.validate_label_zebra_rev = false;
				$scope.validate_razon_rechazo = true;
				console.log('entering here' + $scope.status);
				$scope.btn_save_title = 'Guardar Discrepancia';
				$scope.comments_title = 'DISCREPANCIAS';
				$scope.comments_message_error = 'Escriba las discrepancias ';
			} else if ($scope.status == <?= STATUS_PACK ?>) {
				//No
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = true;
				$scope.validate_label_zebra_rev = false;
				$scope.validate_razon_rechazo = true;

				$scope.btn_save_title = 'Colocar en Pack';
				$scope.comments_title = 'DISCREPANCIAS / COMENTARIOS';
				$scope.comments_message_error = 'Escriba discrepancias o comentarios para este pack';
			} else if ($scope.status == <?= STATUS_ACCEPTED ?>) {
				//Si
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = true;
				$scope.validate_has_exp_date = true;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = true;
				$scope.validate_empaque = true;
				$scope.validate_documentos_rev = true;
				$scope.validate_label_zebra_rev = true;
				$scope.validate_razon_rechazo = false;
				$scope.btn_save_title = 'Liberar Producto';

			} else if ($scope.status == <?= STATUS_WAITING ?>) {
				$scope.validate_final_qty = false;
				$scope.validate_location = false;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = false;
				$scope.validate_label_zebra_rev = false;
				$scope.validate_razon_rechazo = false;
				$scope.btn_save_title = 'Colocar en Espera';
			}

		}

		$scope.select_fecha_exp = function() {
			if ($scope.has_exp_date == 1) {
				$scope.validate_fecha_exp = true;
			} else if ($scope.has_exp_date == 0) {
				$scope.validate_fecha_exp = false;
			}
		}

	});
</script>