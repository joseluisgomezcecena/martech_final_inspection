<form method="get" action="<?= base_url() . $reload_route ?>">
	<div class="page-breadcrumb bg-white">
		<div class="row align-items-center">
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title">ORDENES CERRADAS Y ACEPTADAS</h4>
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
		</div>
		<!-- /.col-lg-12 -->
	</div>
</form>



<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Three charts -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">

		<!-- Other corlors text-info text-purple -->

		<div class="col-lg-12">
			<div class="white-box analytics-info">

				<div class="table-responsive">
					<table style="width: 100%" id="entries-list" class="table table-striped">
						<thead>
							<tr>
								<th class="border-top-0">Folio</th>
								<th class="border-top-0">Parte</th>
								<th class="border-top-0">Lote</th>
								<th class="border-top-0">Cantidad</th>
								<th class="border-top-0">Planta</th>
								<th class="border-top-0">Inicio</th>
								<th class="border-top-0">Hrs en Asignar</th>
								<th class="border-top-0">Hrs en Liberar</th>
								<th class="border-top-0">Hrs en Cerrar</th>


								<th class="border-top-0">Hrs en Espera</th>
								<th class="border-top-0">Hrs en Rechazo </th>
								<th class="border-top-0">Hrs en Calidad (Estimadas)</th>


								<th class="border-top-0">Status</th>
								<th class="border-top-0">Comentarios</th>
								<th class="border-top-0">Detalles</th>
							</tr>

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
	//created_at between '2011-03-17 06:42:10' and '2011-03-17 07:42:50';


	var table = $('#entries-list').DataTable({
		dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		'ajax': {
			'url': '<?php echo base_url() ?>entries/all-closed?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>'
		},
		buttons: [{
			extend: 'copy',
			title: 'Listado de Ordenes Cerradas',
			text: '<i class="fa fa-copy"></i> Copiar'
		}, {
			extend: 'excelHtml5',
			title: 'Listado de Ordenes Cerradas',
			text: '<i class="fa fa-file-excel-o"></i> Excel'
		}, {
			extend: 'pdfHtml5',
			title: 'Listado de Ordenes Cerradas',
			text: '<i class="fa fa-file-pdf-o"></i> Pdf'
		}, {
			extend: 'print',
			title: 'Listado de Ordenes Cerradas',
			text: '<i class="fa fa-print"></i> Impr'
		}],
		'columns': [{
				data: 'id'
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
				data: 'created_at'
			},
			{
				data: 'assigned_elapsed_time'
			},
			{
				data: 'released_elapsed_time'
			},
			{
				data: 'closed_elapsed_time'
			},
			{
				data: 'waiting_hours'
			},
			{
				data: 'rejected_doc_hours'
			},
			{
				data: 'estimated'
			},
			{
				data: 'status'
			},
			{
				data: 'comments'
			},
			{
				data: 'entry_id'
			},

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


	table.buttons().container()
		.appendTo('#entries-list .col-md-6:eq(0)');


	$('#inputStartDate').change(function() {
		//console.log('Updated Date');
		var str = $('#inputStartDate').val();

		console.log(str);

		$('#inputEndDate').attr({
			"min": str
		});
	});

	$(document).ready(function() {
		var currentDate = new Date();
		currentDate = getStringFromDate(currentDate);

		console.log(currentDate);

		$('#inputEndDate').attr({
			"max": currentDate
		});

		$('#inputStartDate').attr({
			"max": currentDate
		});
	});


	function getStringFromDate(date) {
		//var d = new Date(date),
		month = '' + (date.getMonth() + 1),
			day = '' + date.getDate(),
			year = date.getFullYear();

		if (month.length < 2)
			month = '0' + month;
		if (day.length < 2)
			day = '0' + day;

		return [year, month, day].join('-');
	}
</script>