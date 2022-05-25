<?php
	function viewentry()
	{
		$connection = mysqli_connect('localhost','root', '', 'martech_final_inspection');

		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value

		## Search
		$searchQuery = " ";
		if($searchValue != ''){
		$searchQuery = " AND (entry.id like '%".$searchValue."%' OR
		entry.part_no like '%".$searchValue."%' OR
		entry.lot_no like '%".$searchValue."%' 
		)";
		}



		## Total number of records without filtering
		$sel = mysqli_query($connection,"SELECT count(*) AS allcount from entry");
		$records = mysqli_fetch_assoc($sel);
		$totalRecords = $records['allcount'];

		## Total number of record with filtering
		$sel = mysqli_query($connection,"SELECT count(*) AS allcount from entry
		WHERE  1  ".$searchQuery);
		$records = mysqli_fetch_assoc($sel);
		$totalRecordwithFilter = $records['allcount'];

		## Fetch records
		$empQuery = "SELECT * FROM entry
		WHERE  1  " .$searchQuery. " ORDER BY ". $columnName. "  " .$columnSortOrder. " LIMIT " .$row. " , ".$rowperpage;
		$empRecords = mysqli_query($connection, $empQuery);
		$data = array();

		while ($row = mysqli_fetch_assoc($empRecords))
		{

			//progress
			if($row['progress'] == 0)
			{
				$btn_title = "Asignar";
				$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				$color =  "bg-danger";
			}
			elseif($row['progress'] == 1)
			{
				$btn_title = "Liberar";
				$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado en espera a Liberar";
				$color =  "bg-warning";
			}
			elseif($row['progress'] == 2)
			{
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado en espera a Cerrar";
				$color =  "bg-primary";
			}
			elseif($row['progress'] == 3)
			{
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				$color =  "bg-success disabled";
			}


			$data[] = array(
				"id"=>$row['id'],
				"part_no"=>$row['part_no'],
				"lot_no"=>$row['lot_no'],
				"qty"=>$row['qty'],
				"planta"=>$row['plant'],
				"progress"=> "<h4><span class='badge $color'>$text</span></h4>",
				"btn_id"=>"<a href='index.php/$link' class='btn btn-primary'>$btn_title</a>"
			);
		}

		## Response
		$response = array(
		"draw" => intval($draw),
		"iTotalRecords" => $totalRecords,
		"iTotalDisplayRecords" => $totalRecordwithFilter,
		"aaData" => $data
		);

		echo json_encode($response);
	}
viewentry();
