<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Djtype extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	//员工列表
	public function index(){ 
		$list = $this->mysql_model->get_results('djtype','','id desc');  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['id']    =$row['id'];
		    $v[$arr]['typename']    = $row['typename'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['items']       = isset($v) ? $v : array();
		$json['data']['totalsize']   = count($list);
		die(json_encode($json));	  
	}
	
	 
	 
	
	 
	 
	

}

 