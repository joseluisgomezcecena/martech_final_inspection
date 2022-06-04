<form method="get" action="<?= base_url() ?>reports/calidad">
	<div class="page-breadcrumb bg-white">
		<div class="row align-items-center">
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title">ORDENES CERRADAS</h4>
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
								<button type="submit" class="btn btn-primary">Ver Periodo</button>
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
					<table style="width: 100%" id="entries-list" class="table">
						<thead>
							<tr>
								<th class="border-top-0">Folio</th>
								<th class="border-top-0">Parte</th>
								<th class="border-top-0">Lote</th>
								<th class="border-top-0">Cantidad</th>
								<th class="border-top-0">Planta</th>
								<th class="border-top-0">Inicio</th>
								<th class="border-top-0">Tiempo en Asignar</th>
								<th class="border-top-0">Tiempo en Liberar</th>
								<th class="border-top-0">Tiempo en Cerrar</th>
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


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>


<script>
	//created_at between '2011-03-17 06:42:10' and '2011-03-17 07:42:50';


	$('#entries-list').DataTable({
		'scrollX': true,
		//'bSort': false,
		//'scrollCollapse': true,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copy',
			title: 'Listado de Ordenes Cerradas'
		}, {
			extend: 'csvHtml5',
			title: 'Listado de Ordenes Cerradas'
		}, {
			extend: 'excelHtml5',
			title: 'Listado de Ordenes Cerradas'
		}, {
			extend: 'pdfHtml5',
			title: 'Listado de Ordenes Cerradas'
		}, {
			extend: 'print',
			title: 'Listado de Ordenes Cerradas'
		}],
		processing: true,
		serverSide: true,
		serverMethod: 'post',
		ajax: {
			'url': '<?php echo base_url() ?>entries/all-closed?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>'
		},
		columns: [{
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
				data: 'entry_id'
			},

		]
	});


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