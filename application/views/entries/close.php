<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Cerrar entrada</h4>
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
			<div class="col-lg-12">
				<?php if ($this->session->flashdata('cerrada')) : ?>

					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong class="uppercase"><bdi>Cerrada</bdi></strong>
						La orden ha sido cerrada.
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

					</div>

				<?php endif; ?>
			</div>
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

							<div class=" col-lg-12">
								<label for="">Cantidad final</label>
								<input type="text" class="form-control" value="<?php echo $entry['final_qty'] ?>" disabled>
							</div>



							<div class=" col-lg-12">
								<label for="">Locacion</label>
								<input type="text" class="form-control" value="<?php echo $location ?>" disabled>
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
						<?php
						$attributes = array('name' => 'myform');
						echo form_open('entries/close/' .  $entry['id'], $attributes) ?>
						<h3 class="box-title mb-2 text-primary">Cerrar orden</h3>

						<div class="mt-5 mb-5">
							<?php echo validation_errors(
								'<div class="alert alert-danger alert-dismissible fade show" role="alert">',
								'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
							); ?>
						</div>



						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>" />

						<div class="row">
							<div class=" col-lg-3">

								<label for="status" class="text-primary">CERRAR ORDEN</label>
								<select name="final_result" id="status" ng-model="status" ng-change="select_status()" class="form-control">

									<option value="">---Seleccione Resultado---</option>
									<option value="<?= FINAL_RESULT_CLOSED ?>">Si</option>
									<option value="<?= FINAL_RESULT_REJECTED_BY_PRODUCT ?>">Rechazar por Producto</option>
									<option value="<?= FINAL_RESULT_DISCREPANCY ?>">Discrepancia</option>
									<option value="<?= FINAL_RESULT_WAITING ?>">En espera por cambio de prioridad</option>

								</select>
								<small class="text-danger" ng-show="(validate_status && status == '') || (validate_status && status == null)">Seleccione el Resultado</small>
							</div>


							<div class=" col-lg-9">
								<label for="" class="text-primary">REVISIÓN DE ORDEN VS MAPICS Y CERRADA POR</label>
								<input type="text" class="form-control" list="part" name="rev_mapics" id="rev_mapics" ng-model="rev_mapics" value="<?php echo $entry['rev_mapics']; ?>">
								<small class="text-danger" ng-show="validate_rev_mapics && rev_mapics == ''">Se requiere la Revisión contra mapics</small>


								<datalist id="part">
									<?php foreach ($users as $user) : ?>
										<option value="<?php echo $user['user_martech_sign'] ?>">
										<?php endforeach; ?>
								</datalist>
							</div>


							<div class=" mb-2 mt-2 col-lg-12" id="razon_rechazo">
								<label for="" class="text-primary">DISCREPANCIA / COMENTARIO</label>
								<textarea class="form-control" name="discrepancia_descr" ng-model="discrepancia_descr" rows="8" id="discrepancia_descr">value="<?php echo $entry['discrepancia_descr']; ?>"</textarea>
								<small class="text-danger" ng-show="validate_discrepancia_descr && discrepancia_descr == ''">Se requiere la Revisión contra mapics</small>
							</div>

						</div>


						<div class="form-group mt-5">
							<input style="width: 100%" type="submit" name="save_close" class="btn btn-primary text-white btn-lg" value="Cerrar Orden" ng-disabled="!( 
								(  validate_status == false ? true : (validate_status && status != '')) && 
								( validate_rev_mapics == false ? true : (validate_rev_mapics && rev_mapics != '')) && 
								( validate_discrepancia_descr == false ? true : (validate_discrepancia_descr && discrepancia_descr != ''))  )">
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

		$scope.rev_mapics = '<?php echo $entry['rev_mapics']; ?>';
		$scope.discrepancia_descr = '<?php echo $entry['discrepancia_descr']; ?>';

		$scope.validate_status = true;

		$scope.validate_rev_mapics = false;
		$scope.validate_discrepancia_descr = true;


		$scope.select_status = function() {

			if ($scope.status == '') {

				$scope.validate_rev_mapics = true;
				$scope.validate_discrepancia_descr = true;
			} else if ($scope.status == <?= FINAL_RESULT_REJECTED_BY_PRODUCT ?>) {
				//No rechazar por producto
				$scope.validate_rev_mapics = true;
				$scope.validate_discrepancia_descr = true;
			} else if ($scope.status == <?= FINAL_RESULT_DISCREPANCY ?>) {
				//No rechazar por documentacion
				$scope.validate_rev_mapics = true;
				$scope.validate_discrepancia_descr = true;
			} else if ($scope.status == <?= FINAL_RESULT_CLOSED ?>) {
				//Si
				$scope.validate_rev_mapics = true;
				$scope.validate_discrepancia_descr = false;
			} else if ($scope.status == <?= FINAL_RESULT_WAITING ?>) {
				//En espera
				$scope.validate_rev_mapics = false;
				$scope.validate_discrepancia_descr = false;
			} else {
				$scope.validate_rev_mapics = false;
				$scope.validate_discrepancia_descr = false;
			}

		}

	});
</script>