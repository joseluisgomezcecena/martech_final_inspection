<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Asignar orden</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="fw-normal">Asignar orden</a></li>
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
			<?php if($this->session->flashdata('assigned')): ?>

				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong class="uppercase"><bdi>Asignada</bdi></strong>
					La orden ha sigo asignada y sera insepccionada. Haga Click <a href="<?php echo base_url() ?>"> Aqui </a> para regresar o
					cierre este mensaje para cambiar el inspector asignado.
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
				<h3 class="box-title">Asignar Orden</h3>

				<div class="row">
					<div class="col-lg-12">
						<?php echo form_open('entries/assign/' .  $entry['id'], $_GET) ?>
						<h3 class="box-title mb-2 text-primary">Asigne una orden a un inspector</h3>

						<div class="mt-5 mb-5">
							<?php echo validation_errors(
									'<div class="alert alert-danger alert-dismissible fade show" role="alert">',
									'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
							); ?>
						</div>




						<input type="hidden" name="id" value="<?php echo $entry['id'] ?>"/>

						<div class="row">
							<div class=" col-lg-12">
								<label for="">Asignar orden</label>
								<input class="form-control" list="part" name="asignada" value="<?php if(isset($entry['asignada'])){echo $entry['asignada'];}else{echo "";} ?>" id="part_no" required>

								<datalist id="part">
									<?php foreach ($users as $user): ?>
									<option value="<?php echo $user['user_name'] ?>">
										<?php endforeach; ?>
								</datalist>
								<small>El usuario asignado sera responsable de liberar la orden.</small>
							</div>
						</div>




						<div class="form-group mt-5">
							<input style="width: 100%" type="submit" name="save_release" class="btn btn-primary text-white btn-lg" value="Asignar Orden">
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

		$('#status').change(function () {
			var status = $('#status').val();

			if(status == 0){
				$('#razon_rechazo').show("300");
			}else{
				$('#razon_rechazo').hide();
			}
		});
	});

</script>
