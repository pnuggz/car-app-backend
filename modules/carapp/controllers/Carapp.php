<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carapp extends MX_Controller {

  function __construct() {
    parent::__construct();
  }

  function cekToken($token){
    if(array_key_exists('Authorization',$token))
    {
      if($token['Authorization'] && substr($token['Authorization'],0,5) != "Basic")
      {
        $decode_token = $this->decode_token($token['Authorization']);
        
        if($decode_token->exp > time())
        {
          return array('userid'=>$decode_token->data->userid,'username'=>$decode_token->data->username);
        } else {
          echo json_encode(array('error'=>array('message'=>"Sorry, your session has expired please login again.")));http_response_code(401);exit;
        }
      }
    }
  }

  function makes()
  {
    $cektoken = $this->cekToken($this->input->request_headers());
    $this->load->model('mdl_carapp');
    
    if($cektoken)
    {
      $token = $this->generate_token($cektoken['userid'],$cektoken['username']);
    }else{
      $token = '';
    }

    $data = array(
      'token' => $token,
      'data'  => $this->mdl_carapp->get_makes()
    );
    
    $this->output->set_output(json_encode($data), 200);
  }

  function models()
  {
    $cektoken = $this->cekToken($this->input->request_headers());
    $this->load->model('mdl_carapp');
    
    if($cektoken)
    {
      $token = $this->generate_token($cektoken['userid'],$cektoken['username']);
    }else{
      $token = '';
    }

    $data = array(
      'token' => $token,
      'data'  => $this->mdl_carapp->get_models()
    );

    $this->output->set_output(json_encode($data), 200);
  }

  function locations()
  {
    $cektoken = $this->cekToken($this->input->request_headers());
    $this->load->model('mdl_carapp');

    if($cektoken)
    {
      $token = $this->generate_token($cektoken['userid'],$cektoken['username']);
    }else{
      $token = '';
    }

    $data = array(
      'token' => $token,
      'data'  => $this->mdl_carapp->get_locations()
    );

    $this->output->set_output(json_encode($data), 200);
  }

  function search()
  {
    $cektoken = $this->cekToken($this->input->request_headers());
    $this->load->model('mdl_carapp');

    if($cektoken)
    {
      $token = $this->generate_token($cektoken['userid'],$cektoken['username']);
    }else{
      $token = '';
    }

    $data = array(
      'token' => $token,
      'data'  => $this->mdl_carapp->get_search()
    );

    $this->output->set_output(json_encode($data), 200);
  }

  function removesearch() {
    $searchid = $this->uri->segment(3);
    $cektoken = $this->cekToken($this->input->request_headers());

    if($cektoken)
    {
      $this->load->model('mdl_carapp');
      $this->mdl_carapp->removesearch($searchid);
      $token = $this->generate_token($cektoken['userid'],$cektoken['username']);
      $this->output->set_output(json_encode("Success"), 200);
    }else{
      $token = '';
    }
  }

  function searchSubmit()
  {
    $cektoken = $this->cekToken($this->input->request_headers());
    $userid = $cektoken['userid'];

    $this->load->helper('security');
    $this->load->model('mdl_carapp');
    $this->form_validation->set_rules('make_id', 'Make', 'trim|required|max_length[11]|xss_clean');
    $this->form_validation->set_rules('model_id', 'Model', 'trim|required|max_length[11]|xss_clean');
    $this->form_validation->set_rules('location_id', 'Location', 'trim|required|max_length[11]|xss_clean');
    $this->form_validation->set_rules('min_price', 'Minimum Price', 'trim|max_length[11]|xss_clean');
    $this->form_validation->set_rules('max_price', 'Maximum Price', 'trim|max_length[11]|xss_clean');

    if($this->form_validation->run($this) == FALSE)
    {
      $new=array();

      foreach( $this->form_validation->error_array() as $key=>$value) {
        $new['message'][]= $this->form_validation->error_array()[$key];
      }

      $this->output->set_output(json_encode(array('error'=>$new)), 400);http_response_code(400);
    } else {
      $this->load->model('mdl_carapp');

      if($this->input->post('min_price') == "null") { $min_price = NULL; } else { $min_price = $this->input->post('min_price'); };
      if($this->input->post('max_price') == "null") { $max_price = NULL; } else { $max_price = $this->input->post('max_price'); };
      
      $data = array(
      'user_id'       =>      $userid,
      'make_id'       =>      $this->input->post('make_id'),
      'model_id'      =>      $this->input->post('model_id'),
      'location_id'   =>      $this->input->post('location_id'),
      'min_price'     =>      $min_price,
      'max_price'     =>      $max_price,
      'status'        =>      0
      );

      $insert = $this->mdl_carapp->_insert($data);

      $this->output->set_output(json_encode(array('success'=>array('message'=>$data))), 200);
    } 
  }

  function generate_token($userid,$username) {
    //hidden
  }

  function decode_token($jwt) {
    //hidden
  }

}
?>