<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dingdan extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	//订单列表
	public function index(){
		$isDelete = $this->input->get_post('isDelete', TRUE);
		$where = '1=1';
		if ($isDelete !== '' && $isDelete !== null && $isDelete !== false) {
			$isDelete = intval($isDelete);
			$where .= ' and isDelete='.$isDelete;
		}
		$table = $this->db->dbprefix('stock');
		$sql = "SELECT MIN(id) AS id, ordernumber FROM {$table} WHERE {$where} GROUP BY ordernumber ORDER BY ordernumber";
		$list = $this->mysql_model->query($sql, 2);
		if ($list === false) {
			$list = array();
		}
		$v = array();
		foreach ($list as $arr => $row) {
			$v[$arr]['id'] = isset($row['id']) ? intval($row['id']) : 0;
			$v[$arr]['typename'] = isset($row['ordernumber']) ? $row['ordernumber'] : '';
		}
		$json['status'] = 200;
		$json['msg'] = 'success';
		$json['data']['items'] = $v;
		$json['data']['totalsize'] = count($v);
		die(json_encode($json));
	}
	
	 
	 
	
	 
	 
	

}

 