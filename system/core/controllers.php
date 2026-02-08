<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controllers extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys  = $this->session->userdata('jxcsys');
    }
}