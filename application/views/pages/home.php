<div class="page-breadcrumb bg-white">
	<div class="row align-items-center">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Dashboard</h4>
		</div>
		<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
			<div class="d-md-flex">
				<ol class="breadcrumb ms-auto">
					<li><a href="#" class="fw-normal">Dashboard</a></li>
				</ol>
				<a href="<?php echo base_url() ?>entries/create" target=""
				   class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
					Registrar una entrada
				</a>
			</div>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>


<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Three charts -->
	<!-- ============================================================== -->
	<div class="row justify-content-center">
		<div class="col-lg-4 col-md-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Planta 1</h3>
				<ul class="list-inline two-part d-flex align-items-center mb-0">
					<li>
						<div id="sparklinedash"><canvas width="67" height="30"
														style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
						</div>
					</li>
					<li class="ms-auto"><span class="counter text-success">659</span></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Planta 2</h3>
				<ul class="list-inline two-part d-flex align-items-center mb-0">
					<li>
						<div id="sparklinedash2"><canvas width="67" height="30"
														 style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
						</div>
					</li>
					<li class="ms-auto"><span class="counter text-purple">869</span></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Planta 3</h3>
				<ul class="list-inline two-part d-flex align-items-center mb-0">
					<li>
						<div id="sparklinedash3"><canvas width="67" height="30"
														 style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
						</div>
					</li>
					<li class="ms-auto"><span class="counter text-info">911</span>
					</li>
				</ul>
			</div>
		</div>




		<div class="col-lg-12">
			<div class="white-box analytics-info">
				<h3 class="box-title">Pendientes</h3>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<th>Folio</th>
							<th>Parte</th>
							<th>Lote</th>
							<th>Cantidad</th>
							<th>Planta</th>
							<th>Progreso</th>
							<th>Accion</th>
						</thead>
						<tbody>
						<?php
						foreach ($entries as $entry):
						if($entry['progress'] == 0)
						{
							$btn_title = "Asignar";
							$link = "entries/assign/{$entry['id']}";
						}
						elseif($entry['progress'] == 1)
						{
							$btn_title = "Liberar";
							$link = "entries/release/{$entry['id']}";
						}
						elseif($entry['progress'] == 2)
						{
							$btn_title = "Cerrar";
							$link = "entries/close/{$entry['id']}";
						}
						?>
							<tr>
								<td><?php echo $entry['id'] ?></td>
								<td><?php echo $entry['part_no'] ?></td>
								<td><?php echo $entry['lot_no'] ?></td>
								<td><?php echo $entry['qty'] ?></td>
								<td><?php echo $entry['plant'] ?></td>
								<td><?php echo $entry['progress'] ?></td>
								<td>
									<a class="btn btn-primary" href="<?php echo base_url() ?>$link"><?php echo $btn_title ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>



	</div>
</div>
