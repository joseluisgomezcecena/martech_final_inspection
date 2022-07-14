<form method="get" action="<?= base_url() . $reload_route ?>">
	<div class="page-breadcrumb bg-white">
		<div class="row align-items-center">
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title">ORDENES ABIERTAS</h4>
			</div>


			<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
				<div class="d-md-flex breadcrumb">
					<ol class="breadcrumb ms-auto">

						<li>
							<div class="input-group mb-3">
								<span class="input-group-text" for="inputStartDate">Desde</span>
								<input type="date" class="form-control" id="inputStartDate" name="start_date" value="<?= $start_date ?>" aria-describedby="Fecha Inicial" placeholder="Seleccionar Fecha">
							</div>
						</li>

						<li>
							<div class="input-group mb-3">
								<span class="input-group-text" for="inputEndDate">Hasta</span>
								<input type="date" class="form-control" id="inputEndDate" name="end_date" value="<?= $end_date ?>" aria-describedby="Fecha Final">
								<button type="submit" class="btn btn-primary">VER PERIODO</button>
							</div>
						</li>
					</ol>
				</div>
			</div>


			<!--
			<div class="col-lg-12">
				<?php if ($user_type == PRODUCTION_USER) : ?>
					<a href="<?php echo base_url() ?>entries/create" target="" class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
						Registrar una entrada
					</a>
				<?php endif; ?>
			</div>
				-->

		</div>
		<!-- /.col-lg-12 -->
	</div>
</form>




<div class="container-fluid">



	<?php
	if (isset($success_message)) {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
		' . $success_message . '
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
	?>



	<!-- ============================================================== -->
	<!-- Three charts -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">

		<!-- Other corlors text-info text-purple -->
		<div class="col-lg-12">
			<div class="white-box analytics-info">



				<?php if ($user_type == PRODUCTION_USER) : ?>
					<h3 class="text-center text-primary">ORDENES ABIERTAS </h3>
				<?php endif; ?>

				<div class="table-responsive">
					<table style="width: 100%" id="entries-list" class="table table-striped">
						<thead>
							<th>FOLIO</th>
							<th>PRIORIDAD</th>
							<th>FECHA DE REGISTRO</th>
							<th>TIEMPO TRANS.</th>
							<th>PARTE</th>
							<th>LOTE</th>
							<th>CANTIDAD</th>
							<th>ASIGNADO A</th>
							<th>PLANTA</th>
							<th>PROGRESO</th>
							<th>STATUS</th>
							<?php if ($user_type == QUALITY_USER) : ?>
								<th>ACCION</th>
							<?php endif; ?>


						</thead>
						<tbody>
						</tbody>
					</table>
				</div>


			</div>
		</div>


	</div>
</div>


<?php
$this->load->view('templates/datatables');
?>

<script>
	$('#entries-list').DataTable({
		'scrollX': true,

		dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

		'ajax': {
			'url': '<?php echo base_url() ?>entries/all-opened?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>'
		},
		buttons: [{
			extend: 'copy',
			title: 'LISTADO DE ORDENES ABIERTAS',
			text: '<i class="fa fa-copy"></i> Copiar'
		}, {
			extend: 'excelHtml5',
			title: 'LISTADO DE ORDENES ABIERTAS',
			text: '<i class="fa fa-file-excel-o"></i> Excel'
		}, {
			extend: 'pdfHtml5',
			title: 'LISTADO DE ORDENES ABIERTAS',
			text: '<i class="fa fa-file-pdf-o"></i> Pdf'
		}, {
			extend: 'print',
			title: 'LISTADO DE ORDENES ABIERTAS',
			text: '<i class="fa fa-print"></i> Impr'
		}],
		'columns': [{
				data: 'id'
			},
			{
				data: 'has_urgency'
			},
			{
				data: 'created_at'
			},
			{
				data: 'elapsed_time'
			},
			{
				data: 'part_no'
			},
			{
				data: 'lot_no'
			},
			{
				data: 'qty'
			},
			{
				data: 'asignada'
			},
			{
				data: 'planta'
			},
			{
				data: 'progress'
			},
			{
				data: 'status'
			},
			<?php if ($user_type == QUALITY_USER) : ?> {
					data: 'btn_id'
				},
			<?php endif; ?>
		],
		"oLanguage": {
			"sEmptyTable": "No hay datos disponibles",
			"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sSearch": "Buscar:",
			"oPaginate": {
				"sFirst": "Primero",
				"sPrevious": "Previo",
				"sNext": "Siguiente",
				"sLast": "Ultimo"
			},
		}
	});
</script>