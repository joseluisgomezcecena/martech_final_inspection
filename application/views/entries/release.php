<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Liberar orden</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="fw-normal">Liberar orden</a></li>
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

		<div class="col-lg-12">
			<?php if($this->session->flashdata('liberada')): ?>

				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>Liberada</bdi></strong>
					Se ha liberadao la orden y esta esperando a ser cerrada.
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
								<input type="text" class="form-control"  value="<?php echo $entry['lot_no'] ?>" disabled>
							</div>


							<div class=" col-lg-12">
								<label for="">Cantidad enviada</label>
								<input type="number" class="form-control" value="<?php echo $entry['qty'] ?>"  disabled>
							</div>


							<div class="col-lg-12">
								<label for="">Planta</label>
								<select class="form-control" name="plant" id="plant_id" disabled>
									<option value="">Seleccione una planta</option>
									<option value="1">Planta 1</option>
									<?php
									foreach ($plantas as $planta):
										?>
										<option value="<?php echo $planta['planta_id'] ?>"><?php echo $planta['planta_nombre'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>




							<div class="col-lg-12 mt-5 mb-5 text-primary">

								<?php
								if($entry['parcial'] == 1){ echo "Parcial<br>";} else {echo "";}
								if($entry['reinspeccion'] == 1){ echo "Reinspeccion<br>";} else {echo "";}
								if($entry['ficticio'] == 1){ echo "Ficticio<br>";} else {echo "";}
								if($entry['discrepancia'] == 1){ echo "Discrepancia<br>";} else {echo "";}
								?>

							</div>


						</div>

					</div>
				</div>
			</div>
		</div> <!--end col-4-->


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
						</div>



						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>"/>

						<div class="row">
							<div class=" col-lg-3">
								<label for="browser">Resultado de la inspeccion:</label>
								<select name="status" id="status" class="form-control">
									<option value="">Seleccione Resultado</option>
									<option value="2">En espera</option>
									<option value="0">Rechazado</option>
									<option value="1">Aceptado</option>
								</select>
							</div>


							<div class=" col-lg-3">
								<label for="">Cantidad Final</label>
								<input type="number" class="form-control" name="final_qty"  required>
							</div>


							<div class=" col-lg-3">
								<label for="browser">Locacion:</label>
								<select name="location" id="" class="form-control" required>
									<option value="">Seleccione Locacion</option>
									<?php foreach ($locations as $location): ?>
										<option value="<?php echo $location['location_id'] ?>"><?php echo $location['location_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>


							<div class=" col-lg-3">
								<label for="">Work orders escaneadas</label>
								<input type="text" class="form-control" name="wo_escaneadas"  required>
							</div>


							<div class=" col-lg-3">
								<label for="">Tiene fecha de expiracion?</label>
								<select  class="form-control" name="has_fecha_exp" id="has_exp_date"  required>
									<option value="">Seleccione</option>
									<option value="1">Si</option>
									<option value="0">No</option>
								</select>
							</div>

							<div class=" col-lg-3" id="fecha_exp">
								<label for="">Fecha de expiracion</label>
								<input type="date" class="form-control" name="fecha_exp"  >
							</div>


							<div class=" col-lg-3">
								<label for="">Revision de dibujo</label>
								<input type="text" class="form-control" name="rev_dibujo"  required>
							</div>



							<div class=" col-lg-3">
								<label for="">Empaque</label>
								<input type="text" class="form-control" name="empaque"  required>
							</div>


							<div class=" col-lg-3">
								<label for="">Documentos revisados</label>
								<input type="text" class="form-control" name="documentos_rev"  required>
							</div>


							<div class=" mb-2 mt-2 col-lg-12" id="razon_rechazo">
								<label for="">Razon del rechazo si aplica</label>
								<textarea class="form-control" name="razon_rechazo" rows="8"></textarea>
							</div>

						</div>




						<div class="form-group mt-5">
							<input style="width: 100%" type="submit" name="save_release" class="btn btn-primary text-white btn-lg" value="Liberar Entrada">
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

	$(document).ready(function() {

		$('#razon_rechazo').hide();
		$('#fecha_exp').hide();

		$('#status').change(function () {
			var status = $('#status').val();

			if(status == 0){
				$('#razon_rechazo').show("300");
			}else{
				$('#razon_rechazo').hide();
			}
		});


		$('#has_exp_date').change(function () {
			var has = $('#has_exp_date').val();

			if(has == 1){
				$('#fecha_exp').show("300");
			}else{
				$('#fecha_exp').hide("300");
			}
		});




	});

</script>
