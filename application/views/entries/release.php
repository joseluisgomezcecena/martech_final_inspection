<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Liberar orden</h4>
		</div>

		<!--
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<a href="javascript:history.back()" target="" class="btn btn-light  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
						<i class="fa fa-arrow-left" style="color:#000;" aria-hidden="true"></i>
					</a>
				</ol>

			</div>
		</div>
-->
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
					<strong class="uppercase"><bdi>Liberada</bdi></strong>
					Se ha liberadao la orden y esta esperando a ser cerrada.
					Haz click <a href="<?php echo  base_url() ?>">Aqui</a> para regresar
					o cierra este mensaje para cambiar los datos de esta liberación.
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

								<label for="status" class="text-primary">Resultado de la inspección</label>
								<select name="status" id="status" class="form-control" ng-model="status" ng-change="select_status()">
									<option value="">-- Seleccione Resultado --</option>
									<option value="3" <?php if ($entry['status'] == "3") echo ' selected'; ?>>En espera</option>
									<option value="1" <?php if ($entry['status'] == "1") echo ' selected'; ?>>Rechazado</option>
									<option value="2" <?php if ($entry['status'] == "2") echo ' selected'; ?>>Aceptado</option>
								</select>
								<small class="text-danger" ng-show="(validate_status && status == '') || (validate_status && status == null)">Seleccione el Resultado</small>
							</div>


							<div class="col-lg-6">
								<label for="" class="text-primary">Cantidad Final</label>
								<input type="number" id="qty" class="form-control" name="final_qty" ng-model="final_qty">
								<small class="text-danger" ng-show="(validate_final_qty && final_qty == '')">Elija la cantidad final de la parte</small>
							</div>

						</div>

						<div class="row mt-3">
							<div class=" col-lg-6">
								<label for="location" class="text-primary">Locacion:</label>
								<select name="location" id="location" class="form-control" ng-model="location">
									<option value="">Seleccione Locacion</option>
									<?php foreach ($locations as $location) {
										$str = '';

										if ($location['location_id'] == $entry['location'])
											$str = ' selected';

										echo '<option value="' . $location['location_id'] . '"' . $str . '>' . $location['location_name'] . '</option>';
									}
									?>
								</select>
								<small class=" text-danger" ng-show="(validate_location && location == '')">Seleccione la locación</small>
							</div>


							<div class=" col-lg-6">
								<label for="" class="text-primary">Work orders escaneadas</label>
								<input type="text" id="wo_escaneadas" class="form-control" name="wo_escaneadas" ng-model="wo_escaneadas">
								<small class=" text-danger" ng-show="(validate_wo_escaneadas && wo_escaneadas == '')">Esciba las work orders escaneadas</small>
							</div>
						</div>


						<div class="row mt-3">
							<div class=" col-lg-6">
								<label for="" class="text-primary">Tiene fecha de expiracion?</label>
								<select class="form-control" name="has_fecha_exp" id="has_exp_date" ng-model="has_exp_date" ng-change="select_fecha_exp()">
									<option value="">-- Seleccione --</option>
									<option value="1">Si</option>
									<option value="0">No</option>
								</select>
								<small class=" text-danger" ng-show="(validate_has_exp_date && has_exp_date == '')">Seleccione si existe fecha de expiracion</small>
							</div>

							<div class=" col-lg-6" id="fecha_exp" ng-show="has_exp_date == 1">
								<label for="" class="text-primary">Fecha de expiracion</label>
								<input type="date" class="form-control" name="fecha_exp" ng-model="fecha_exp">
								<small class=" text-danger" ng-show="(validate_fecha_exp && fecha_exp == '')">Establezca la fecha de expiracion</small>
							</div>
						</div>


						<div class="row mt-3">

							<div class=" col-lg-6">
								<label for="" class="text-primary">Revision de dibujo</label>
								<input type="text" class="form-control" id="rev_dibujo" name="rev_dibujo" ng-model="rev_dibujo">
								<small class=" text-danger" ng-show="(validate_rev_dibujo && rev_dibujo == '')">Escriba la revisión del dibujo</small>
							</div>


							<div class=" col-lg-6">
								<label for="" class="text-primary">Empaque</label>
								<input type="text" class="form-control" id="empaque" name="empaque" ng-model="empaque">
								<small class=" text-danger" ng-show="(validate_empaque && empaque == '')">Escriba la revisión del dibujo</small>
							</div>
						</div>

						<div class="row mt-3">
							<div class=" col-lg-6">
								<label for="" class="text-primary">Documentos revisados</label>
								<input type="text" class="form-control" id="documentos_rev" name="documentos_rev" ng-model="documentos_rev">
								<small class=" text-danger" ng-show="(validate_documentos_rev && documentos_rev == '')">Indique los documentos revisados</small>
							</div>


							<div class=" mb-2 mt-2 col-lg-12" id="razon_rechazo" ng-show="status == 1">
								<label for="" class="text-primary">Razón del rechazo si aplica</label>
								<textarea class="form-control" name="razon_rechazo" rows="8" ng-model="razon_rechazo"></textarea>
								<small class=" text-danger" ng-show="(validate_razon_rechazo && razon_rechazo == '')">Indique la razon del rechazo</small>
							</div>

						</div>


						<div class="form-group mt-5">
							<input style="width: 100%" type="submit" name="save_release" class="btn btn-primary text-white btn-lg" value="Liberar Entrada" ng-disabled="!( 
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
	var app = angular.module('myApp', []);
	app.controller('myCtrl', function($scope) {


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

		$scope.razon_rechazo = '<?php echo $entry['razon_rechazo'] ?>';
		$scope.validate_razon_rechazo = false;

		$scope.select_status = function() {

			if ($scope.status == null || $scope.status == '') {
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = true;
				$scope.validate_has_exp_date = true;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = true;
				$scope.validate_empaque = true;
				$scope.validate_documentos_rev = true;
				$scope.validate_razon_rechazo = false;
			} else if ($scope.status == 1) {
				//No
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = true;
				$scope.validate_has_exp_date = true;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = true;
				$scope.validate_empaque = true;
				$scope.validate_documentos_rev = true;
				$scope.validate_razon_rechazo = true;
			} else if ($scope.status == 2) {
				//Si
				$scope.validate_final_qty = true;
				$scope.validate_location = true;
				$scope.validate_wo_escaneadas = true;
				$scope.validate_has_exp_date = true;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = true;
				$scope.validate_empaque = true;
				$scope.validate_documentos_rev = true;
				$scope.validate_razon_rechazo = false;
			} else if ($scope.status == 3) {
				$scope.validate_final_qty = false;
				$scope.validate_location = false;
				$scope.validate_wo_escaneadas = false;
				$scope.validate_has_exp_date = false;
				//$scope.validate_fecha_exp = false;
				$scope.validate_rev_dibujo = false;
				$scope.validate_empaque = false;
				$scope.validate_documentos_rev = false;
				$scope.validate_razon_rechazo = false;
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