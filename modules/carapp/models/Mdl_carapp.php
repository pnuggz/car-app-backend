<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_carapp extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_makes() {
    $result = array();
    $query = $this->db->query("
      SELECT * FROM `carsales_makes`
    ");
    $query;

    foreach($query->result() as $row)
    {
      $result[] = array(
        "make_id" => $row->id,
        "make_name" => $row->make_name
      );
    }
    
    return $result;
  }

  function get_models() {
    $result = array();
    $query = $this->db->query("
      SELECT * FROM `carsales_models`
    ");
    $query;

    foreach($query->result() as $row)
    {
      $result[] = array(
        "model_id" => $row->id,
        "make_id" => $row->makes_id,
        "model_name" => $row->model_name
      );
    }

    return $result;
  }

  function get_locations() {
    $result = array();
    $query = $this->db->query("
      SELECT * FROM `carsales_locations`
    ");
    $query;

    foreach($query->result() as $row)
    {
      $result[] = array(
        "location_id" => $row->id,
        "location" => $row->location,
        "location_link" => $row->location_link
      );
    }

    return $result;
  }

  function get_search() {
    $result = array();
    $query = $this->db->query("
      SELECT 
      carsales_users_search.id AS search_id,
      carsales_makes.id AS make_id,
      carsales_makes.make_name,
      carsales_models.id AS model_id,
      carsales_models.model_name,
      carsales_locations.id AS location_id,
      carsales_locations.location,
      carsales_users_search.status
      FROM `carsales_users_search`
      JOIN users ON users.id = carsales_users_search.user_id
      JOIN carsales_makes ON carsales_makes.id = carsales_users_search.make_id
      JOIN carsales_models ON carsales_models.id = carsales_users_search.model_id
      JOIN carsales_locations ON carsales_locations.id = carsales_users_search.location_id
      WHERE carsales_users_search.status = 0
    ");
    $query;

    foreach($query->result() as $row)
    {
      $result[] = array(
        "search_id" => $row->search_id,
        "make_id" => $row->make_id,
        "make_name" => $row->make_name,
        "model_id"	=> $row->model_id,
        "model_name" => $row->model_name,
        "location_id" => $row->location_id,
        "location" => $row->location,
        "status" => $row->status
      );
    }

    return $result;
  }

  function removesearch($searchid) {
	  $query = $this->db->query('
      DELETE FROM carsales_users_search 
      WHERE carsales_users_search.id = '. $searchid .'
		');
  }

  function _insert($data) 
  {
    $this->db->insert('carsales_users_search', $data);
    $query = $this->db->affected_rows();
    if($query > 0)
    {
      $success= TRUE;
    }else{
      $success = FALSE;
    }	
    
    return $success;
  }

}
?>
