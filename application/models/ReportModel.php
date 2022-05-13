<?php

class ReportModel extends CI_Model{

	public function __construct()
	{
		$this->load->database();
	}

	public function get_asistencia()
	{
		//$query = $this->db->get("asistencia");
		$this->db->select('*');
		$this->db->from('asistencia');
		$this->db->join('plantas', 'plantas.planta_id = asistencia.asistencia_planta', 'left');
		$this->db->join('lineas', 'lineas.linea_id = asistencia.asistencia_linea', 'left');
		$query = $this->db->get();

		return $query->result_array();
	}


	public function get_movimientos()
	{

		//SELECT `m`.`id`, `m`.`movimientos_fecha`, `m`.`movimientos_turno`, `m`.`movimientos_linea_origen`, `m`.`movimientos_linea_destino`, `m`.`movimientos_planta_origen`, `m`.`movimientos_planta_destino`, `m`.`movimientos_operadores`, `m`.`movimientos_horas`, `m`.`created_at`, `po`.`planta_nombre`, `pd`.`planta_nombre`, `lo`.`linea_nombre`, `ld`.`linea_nombre` FROM `movimientos` as `m` LEFT JOIN `plantas` as `po` ON `po`.`planta_id` = `m`.`movimientos_planta_origen` LEFT JOIN `plantas` as `pd` ON `pd`.`planta_id` = `m`.`movimientos_planta_destino` LEFT JOIN `lineas` as `lo` ON `lo`.`linea_id` = `m`.`movimientos_linea_origen` LEFT JOIN `lineas` as `ld` ON `ld`.`linea_id` = `m`.`movimientos_linea_destino`


		$this->db->select("`m`.`id`, `m`.`movimientos_fecha`, `m`.`movimientos_turno`, `m`.`movimientos_linea_origen`, `m`.`movimientos_linea_destino`, `m`.`movimientos_planta_origen`, `m`.`movimientos_planta_destino`, `m`.`movimientos_operadores`, `m`.`movimientos_horas`, `m`.`created_at`, `po`.`planta_nombre` as planta_origen, `pd`.`planta_nombre` as planta_destino, `lo`.`linea_nombre` as linea_origen, `ld`.`linea_nombre` as linea_destino");
		$this->db->from('movimientos as m');
		$this->db->join('plantas as po', 'po.planta_id = m.movimientos_planta_origen', 'left');
		$this->db->join('plantas as pd', 'pd.planta_id = m.movimientos_planta_destino', 'left');
		$this->db->join('lineas as lo', 'lo.linea_id = m.movimientos_linea_origen', 'left');
		$this->db->join('lineas as ld', 'ld.linea_id = m.movimientos_linea_destino', 'left');
		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_extras()
	{

		$this->db->select('*');
		$this->db->from('tiempo_extra');
		$this->db->join('plantas', 'plantas.planta_id = tiempo_extra.te_planta', 'left');
		$this->db->join('lineas', 'lineas.linea_id = tiempo_extra.te_linea', 'left');
		$query = $this->db->get();

		return $query->result_array();
	}




}
