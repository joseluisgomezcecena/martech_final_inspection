<form method="get" action="<?= base_url() . $reload_route ?>">
	<div class="page-breadcrumb bg-white">
		<div class="row align-items-center">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title"><?= $title ?></h4>
			</div>


			<div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
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

	<div class="row justify-content-center">

		<!-- Other corlors text-info text-purple -->
		<div class="col-lg-12">
			<div class="white-box analytics-info">

				<div class="table-responsive">
					<table style="width: 100%" id="entries-rejected-list" class="table table-striped">
						<thead>
							<th>Folio</th>
							<th>Fecha Registro</th>
							<th>Parte</th>
							<th>Lote</th>
							<th>Cantidad</th>
							<th>Planta</th>
							<th>Progreso</th>
							<th>Status</th>
							<th>Comentario</th>

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
	$('#entries-rejected-list').DataTable({
		'scrollX': true,

		dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

		'ajax': {
			'url': '<?php echo base_url() ?>entries/quality-all?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&reload_route=<?= $reload_route ?>'
		},
		buttons: [{
			extend: 'copy',
			title: 'Listado de Ordenes Rechazadas',
			text: '<i class="fa fa-copy"></i> Copiar'
		}, {
			extend: 'excelHtml5',
			title: 'Listado de Ordenes Rechazadas',
			text: '<i class="fa fa-file-excel-o"></i> Excel'
		}, {
			extend: 'pdfHtml5',
			title: 'Listado de Ordenes Rechazadas',
			text: '<i class="fa fa-file-pdf-o"></i> Pdf'
		}, {
			extend: 'print',
			title: 'Listado de Ordenes Rechazadas',
			text: '<i class="fa fa-print"></i> Impr'
		}],
		'columns': [{
				data: 'id'
			},
			{
				data: 'created_at'
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
				data: 'plant'
			},
			{
				data: 'progress'
			},
			{
				data: 'status'
			},
			{
				data: 'razon_rechazo'
			}

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