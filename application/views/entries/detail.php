<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Detalle de la Orden</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
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
				<h3 class="box-title text-center">DATOS DEL REGISTRO (<?php if (isset($entry['created_at'])) {
																			$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['created_at']);
																			echo $date->format('m/d/Y');
																		} ?>)</h3>

				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class=" col-lg-3">
								<label for="browser">Numero de parte:</label>
								<input class="form-control" list="part" value="<?php echo $entry['part_no'] ?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Numero de lote</label>
								<input type="text" class="form-control" value="<?php echo $entry['lot_no'] ?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Cantidad enviada</label>
								<input type="number" class="form-control" value="<?php echo $entry['qty'] ?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Planta</label>
								<input type="text" class="form-control" value="<?php echo $entry['plant_name'] ?>" readonly>
							</div>


							<div class="col-lg-9 mt-2">
								<label for="origen">Causa de Origen</label>

								<div class="input-group" id="origen">

									<div class="form-check form-check-inline mr-2">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="parcial" <?php if ($entry['parcial'] == 1) echo "checked" ?> disabled>
										<label for="inlineCheckbox1">Parcial</label>
									</div>
									<div class="form-check form-check-inline mr-2">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="reinspeccion" <?php if ($entry['reinspeccion'] == 1) echo "checked" ?> disabled>
										<label for="inlineCheckbox2">Reinspeccion</label>
									</div>
									<div class="form-check form-check-inline mr-2">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="discrepancia" <?php if ($entry['ficticio'] == 1) echo "checked" ?> disabled>
										<label for="inlineCheckbox2">Discrepancia</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="ficticio" <?php if ($entry['discrepancia'] == 1) echo "checked" ?> disabled>
										<label for="inlineCheckbox2">Ficticio</label>
									</div>
								</div>
							</div>





						</div>

					</div>
				</div>
			</div>
		</div>
		<!--end col-4-->


		<div class="col-lg-12 col-md-12">

			<div class="white-box analytics-info">
				<h3 class="box-title text-center">1. DESIGNACION (<?php if (isset($entry['asignada_date'])) {
																		$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['asignada_date']);
																		echo $date->format('m/d/Y');
																	} ?>)</h3>

				<div class="row">
					<div class="col-lg-12">

						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>" />

						<div class="row">

							<div class=" col-lg-6">
								<label for="">Fecha/Hora de Asignación:</label>
								<input class="form-control" list="part" name="asignada" value="<?php if (isset($entry['asignada'])) {
																									$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['asignada_date']);
																									echo $date->format('m/d/Y') . ' a las ' . $date->format('H:i') . ' hrs.';
																								} else {
																									echo "";
																								} ?>" id="part_no" readonly>
							</div>
							<div class=" col-lg-6">
								<label for="">Orden asignada a:</label>
								<input class="form-control" list="part" name="asignada" value="<?php if (isset($entry['asignada'])) {

																									echo $entry['asignada'];
																								} else {
																									echo "";
																								} ?>" id="part_no" readonly>
							</div>


						</div>

					</div>
				</div>
			</div>

		</div>




		<div class="col-lg-12 col-md-12">

			<div class="white-box analytics-info">
				<h3 class="box-title text-center">2. LIBERACION DE ORDEN (<?php if (isset($entry['liberada_date'])) {
																				$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['liberada_date']);
																				echo $date->format('m/d/Y');
																			} ?>)</h3>

				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class=" col-lg-3">
								<label for="">Fecha/Hora de Liberación:</label>
								<input class="form-control" list="part" name="asignada" value="<?php if (isset($entry['asignada'])) {
																									$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['asignada_date']);
																									echo $date->format('m/d/Y') . ' a las ' . $date->format('H:i') . ' hrs.';
																								} else {
																									echo "";
																								} ?>" id="part_no" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Status</label>
								<input type="text" class="form-control" value="<?php
																				//$entry['status'] 0 sin asignar, 1 rechazado, 2 aceptado, 3 en espera por cambio de prioridad
																				if ($entry['status'] == STATUS_NOT_ASSIGNED) {
																					echo 'Sin asignar';
																				} else if ($entry['status'] == STATUS_REJECTED) {
																					echo 'Rechazado';
																				} else if ($entry['status'] == STATUS_ACCEPTED) {
																					echo 'Aceptado';
																				} else if ($entry['status'] == STATUS_WAITING) {
																					echo 'En espera por cambio de prioridad';
																				}
																				?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Cantidad Final</label>
								<input type="number" class="form-control" value="<?php echo $entry['final_qty'] ?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Location</label>
								<input type="text" class="form-control" value="<?php

																				if ($entry['location'] == LOCATION_MOQC) {
																					echo LOCATION_MOQC_STR;
																				} else if ($entry['location'] == LOCATION_ASQC) {
																					echo LOCATION_ASQC_STR;
																				} else if ($entry['location'] == LOCATION_MPQC) {
																					echo LOCATION_MPQC_STR;
																				} else if ($entry['location'] == LOCATION_MPQCWH) {
																					echo LOCATION_MPQCWH_STR;
																				} else if ($entry['location'] == LOCATION_INGE3S) {
																					echo LOCATION_INGE3S_STR;
																				} else if ($entry['location'] == LOCATION_INGE) {
																					echo LOCATION_INGE_STR;
																				}

																				?>" readonly>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-lg-3">
								<label for="">Work orders escaneadas</label>
								<input class="form-control" list="part" name="wo_escaneadas" value="<?php echo $entry['wo_escaneadas'] ?>" readonly>
							</div>

							<!-- has_fecha_exp, fecha_exp -->
							<div class="col-lg-3">
								<label for="">Fecha de Expiración</label>
								<?php
								if ($entry['has_fecha_exp'] == 1) {
									echo '<input class="form-control" list="part" value="' . $entry['fecha_exp'] . '" readonly>';
								} else {
									echo '<input type="text" class="form-control" value="N/A" readonly>';
								}
								?>
							</div>

							<div class="col-lg-3">
								<label for="">Revisión de Dibujo</label>
								<input class="form-control" list="part" name="rev_dibujo" value="<?php echo $entry['rev_dibujo'] ?>" readonly>
							</div>


							<div class="col-lg-3">
								<label for="">Empaque</label>
								<input class="form-control" list="part" name="empaque" value="<?php echo $entry['empaque'] ?>" readonly>
							</div>


						</div>


						<div class="row mt-3">
							<div class="col-lg-3">
								<label for="">Documentos Revisados</label>
								<input class="form-control" list="part" name="documentos_rev" value="<?php echo $entry['documentos_rev'] ?>" readonly>
							</div>


							<?php if ($entry['status'] == STATUS_REJECTED) : ?>
								<div class="col-lg-9">
									<label for="">Razón del Rechazo</label>
									<textarea class="form-control" list="part" name="razon_rechazo" readonly><?php echo $entry['razon_rechazo'] ?></textarea>
								</div>
							<?php endif; ?>



						</div>

					</div>
				</div>


			</div>
		</div>



		<div class="col-lg-12 col-md-12">

			<div class="white-box analytics-info">
				<h3 class="box-title text-center">3. CIERRE DE LA ORDEN (<?php if (isset($entry['cerrada_date'])) {
																				$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['cerrada_date']);
																				echo $date->format('m/d/Y');
																			} ?>)</h3>

				<div class="row">
					<div class="col-lg-12">

						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>" />

						<div class="row">

							<div class=" col-lg-3">
								<label for="">Fecha/Hora del Cierre</label>
								<input class="form-control" name="asignada" value="<?php if (isset($entry['cerrada_date'])) {
																						$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry['cerrada_date']);
																						echo $date->format('m/d/Y') . ' a las ' . $date->format('H:i') . ' hrs.';
																					} else {
																						echo "";
																					} ?>" readonly>
							</div>


							<div class="col-lg-3">
								<label for="">Resultado del Cierre</label>
								<input class="form-control" name="asignada" value="<?php
																					if ($entry['final_result'] == FINAL_RESULT_NOT_DEFINED) {
																						echo 'N/A';
																					} else if ($entry['final_result'] == FINAL_RESULT_NOT_CLOSED) {
																						echo 'No Aceptado';
																					} else if ($entry['final_result'] == FINAL_RESULT_CLOSED) {
																						echo 'Cerrado';
																					} else if ($entry['final_result'] == FINAL_RESULT_WAITING) {
																						echo 'En espera por cambio de prioridad';
																					}
																					?>" readonly>
							</div>


							<div class=" col-lg-3">
								<label for="">Cerrada por</label>
								<input class="form-control" name="asignada" value="<?php echo $entry['cerrada_por'] ?>" readonly>
							</div>

							<div class=" col-lg-3">
								<label for="">Revisión vs Mapics</label>
								<input class="form-control" name="asignada" value="<?php echo $entry['rev_mapics'] ?>" readonly>
							</div>

							<?php if ($entry['final_result'] == FINAL_RESULT_NOT_CLOSED) : ?>
								<div class="col-lg-6">
									<label for="">Descripción de la Discrepancia</label>
									<textarea class="form-control" list="part" name="discrepancia_descr" readonly><?php echo $entry['discrepancia_descr'] ?></textarea>
								</div>
							<?php endif; ?>





						</div>

					</div>
				</div>
			</div>

		</div>


	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
	$(document).ready(function() {

		$('#razon_rechazo').hide();

		$('#status').change(function() {
			var status = $('#status').val();

			if (status == 0) {
				$('#razon_rechazo').show("300");
			} else {
				$('#razon_rechazo').hide();
			}
		});
	});
</script>