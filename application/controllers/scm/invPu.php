<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvPu extends MY_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys  = $this->session->userdata('jxcsys');
    }
	
	public function index() {
	    $action = $this->input->get('action',TRUE);
		switch ($action) {
			case 'initPur':
			    $this->common_model->checkpurview(2);
				$data['billNo'] = $this->str_number();
			    $this->load->view('scm/invPu/initPur',$data);	
				break;  
			case 'editPur':
			    $this->common_model->checkpurview(1);
				$id = intval($this->input->get_post('id',TRUE));
				$data['billNo'] = $this->mysql_model->get_row('invoice',array('id'=>$id,'billType'=>'PUR'),'billNo');  
			    $this->load->view('scm/invPu/initPur',$data);	
				break;  	
			case 'initPurList':
			    $this->common_model->checkpurview(1); 
			    $this->load->view('scm/invPu/initPurList');
				break; 
			case 'initPurListzuofei':
			    $this->common_model->checkpurview(1); 
			    $this->load->view('scm/invPu/initPurListzuofei');
				break; 
			default: 
			    $this->common_model->checkpurview(1); 
			    $this->purList(); 	
		}
	}
	public function showpwd(){
		$username = $this->input->get_post('username',TRUE);
		$userpwd = $this->input->get_post('userpwd',TRUE);
		$data = $this->mysql_model->query("select userpwd from ci_admin where username='$username'",1);
		if(md5($userpwd)==$data['userpwd']){
			$json['status']              = 200;
			$json['msg']                 = 'success'; 
		}else{
			$json['status']              = 0;
			$json['msg']                 = '密码错误，删除失败！'; 
		}
 
		die(json_encode($json));
	}
	public function purList() {		
		$type   = $this->input->get('type',TRUE);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
	
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		 $matchCon1=explode(" ",$matchCon);
	   if(count($matchCon1)>1){
		   $matchCon=$matchCon1[0];
		}
		 
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$mdescription  = str_enhtml($this->input->get_post('mdescription',TRUE));
		$goodsnumber  = str_enhtml($this->input->get_post('goodsnumber',TRUE));
         $matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
         $mname=$mname=='请输入领料人'?'':$mname;
          $mnumber=$mnumber=='请输入单据编号'?'':$mnumber;
           $mdescription=$mdescription=='请输入物料描述'?'':$mdescription;
		    $goodsnumber=$goodsnumber=='请输入物料编号'?'':$goodsnumber;
        
		$tmparray = explode("*",$mdescription);
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		if($type=="chukudan"){
		$where = 'a.isDelete=0 and f.checked!=3'; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $goodsnumber  ? ' and b.number="'.$goodsnumber.'"': ''; 
		//$where .= $mdescription  ? ' and a.mdescription like "%'.$mdescription.'%"' : ''; 
		$where .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		//$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : ''; 
	   $where .= $matchCon  ? ' and c.name like "%'.$matchCon.'%"' : '';
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();    
		}else{
			$where = 'a.isDelete=0 and a.checked!=3'; 
		//$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and (b.name like "%'.$matchCon.'%" or b.number like "%'.$matchCon.'%")' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();    
		}
		
			if($type=="chukudan"){
				//echo $type."=============";
				$lists = $this->data_model->get_invoice_info($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows); 
				//print_r($this->db->last_query());exit;
					foreach ($lists as $arr=>$row) {
						$v[$arr]['invNumber']           = $row['invNumber'];
						$v[$arr]['mdescription']           = $row['mdescription'];
						$v[$arr]['totalQty']           = $row['qty'];
						$v[$arr]['mainUnit']           = $row['mainUnit'];
						$v[$arr]['price']           = $row['price'];
						$v[$arr]['amount']           = $row['qty']*$row['price'];
						$v[$arr]['BillName']           = $row['BillName'];
						$v[$arr]['billNo']           = $row['billNo']; 
						$v[$arr]['contactName']    = $row['contactNo'].' '.$row['contactName'];
						$v[$arr]['liname']    = $row['liname'];
						$v[$arr]['description']    = $row['description'];
					}
			}else{
				$list = $this->data_model->get_invoice($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows); 
				foreach ($list as $arr=>$row) {
				$v[$arr]['hxStateCode']  = intval($row['hxStateCode']);
		    
				$v[$arr]['id']           = intval($row['id']);
				$v[$arr]['checkName']    = $row['checkName'];
				$v[$arr]['contactName']    = $row['contactName'];
				$v[$arr]['salesName']    = $row['salesName'];
				$v[$arr]['checked']      = intval($row['checked']);
				$v[$arr]['billDate']     = $row['billDate'];
				$v[$arr]['totalQty']     = $row['totalQty'];
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['transType']    = intval($row['transType']); 
				$v[$arr]['rpAmount']     = (float)abs($row['hasCheck']);
				$v[$arr]['contactName']  = $row['contactNo'].' '.$row['contactName'];
				//$v[$arr]['contactName']  = $row['contactName'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['billNo']       = $row['billNo'];
				$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
				$v[$arr]['userName']     = $row['userName'];
				$v[$arr]['transTypeName']= $row['transTypeName'];
				$v[$arr]['liname']= $row['liname'];
				$v[$arr]['disEditable']  = 0;
			}
		    				
			
		}
		
		$json['status']              = 200;
		$json['msg']                 = 'success'; 
		$json['data']['page']        = $page;
		if($type=="chukudan"){
			$json['data']['records']     = $this->data_model->get_invoice_info($where,3);                             
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}else{
			$json['data']['records']     = $this->data_model->get_invoice($where,3);                             
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}
		
		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}
	
	public function zuofei() {		
		$type   = $this->input->get('type',TRUE);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
	
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		 $matchCon1=explode(" ",$matchCon);
	   if(count($matchCon1)>1){
		   $matchCon=$matchCon1[0];
		}
		 
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$mdescription  = str_enhtml($this->input->get_post('mdescription',TRUE));
		$goodsnumber  = str_enhtml($this->input->get_post('goodsnumber',TRUE));
         $matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
         $mname=$mname=='请输入领料人'?'':$mname;
          $mnumber=$mnumber=='请输入单据编号'?'':$mnumber;
           $mdescription=$mdescription=='请输入物料描述'?'':$mdescription;
		    $goodsnumber=$goodsnumber=='请输入物料编号'?'':$goodsnumber;
        
		$tmparray = explode("*",$mdescription);
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		if($type=="chukudan"){
		$where = 'a.isDelete=0 and f.checked=3'; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $goodsnumber  ? ' and b.number="'.$goodsnumber.'"': ''; 
		//$where .= $mdescription  ? ' and a.mdescription like "%'.$mdescription.'%"' : ''; 
		$where .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		//$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : ''; 
	   $where .= $matchCon  ? ' and (c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%")' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();    
		}else{
			$where = 'a.isDelete=0 and a.checked=3'; 
		//$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and (b.name like "%'.$matchCon.'%" or b.number like "%'.$matchCon.'%")' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();    
		}
		
			if($type=="chukudan"){
				//echo $type."=============";
				$lists = $this->data_model->get_invoice_info($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows); 
				//print_r($lists);
					//exit();
					foreach ($lists as $arr=>$row) {
						$v[$arr]['invNumber']           = $row['invNumber'];
						$v[$arr]['mdescription']           = $row['mdescription'];
						$v[$arr]['totalQty']           = $row['qty'];
						$v[$arr]['mainUnit']           = $row['mainUnit'];
						$v[$arr]['price']           = $row['price'];
						$v[$arr]['amount']           = $row['qty']*$row['price'];
						$v[$arr]['BillName']           = $row['BillName'];
						$v[$arr]['billNo']           = $row['billNo']; 
						$v[$arr]['contactName']    = $row['contactNo'].' '.$row['contactName'];
						$v[$arr]['liname']    = $row['liname'];
						$v[$arr]['description']    = $row['description'];
					}
			}else{
				$list = $this->data_model->get_invoice($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows); 
				foreach ($list as $arr=>$row) {
				$v[$arr]['hxStateCode']  = intval($row['hxStateCode']);
		    
				$v[$arr]['id']           = intval($row['id']);
				$v[$arr]['checkName']    = $row['checkName'];
				$v[$arr]['contactName']    = $row['contactName'];
				$v[$arr]['salesName']    = $row['salesName'];
				$v[$arr]['checked']      = intval($row['checked']);
				$v[$arr]['billDate']     = $row['billDate'];
				$v[$arr]['totalQty']     = $row['totalQty'];
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['transType']    = intval($row['transType']); 
				$v[$arr]['rpAmount']     = (float)abs($row['hasCheck']);
				$v[$arr]['contactName']  = $row['contactNo'].' '.$row['contactName'];
				//$v[$arr]['contactName']  = $row['contactName'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['billNo']       = $row['billNo'];
				$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
				$v[$arr]['userName']     = $row['userName'];
				$v[$arr]['transTypeName']= $row['transTypeName'];
				$v[$arr]['liname']= $row['liname'];
				$v[$arr]['disEditable']  = 0;
			}
		    				
			
		}
		
		$json['status']              = 200;
		$json['msg']                 = 'success'; 
		$json['data']['page']        = $page;
		if($type=="chukudan"){
			$json['data']['records']     = $this->data_model->get_invoice_info($where,3);                             
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}else{
			$json['data']['records']     = $this->data_model->get_invoice($where,3);                             
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}
		
		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}
	
	
	public function purListmain() {		
		$type   = $this->input->get('type',TRUE);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),20);
	
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		if($type=="chukudan"){
		$where = 'a.isDelete=0 and a.checked=0'; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();    
		}else{
			$where = 'a.isDelete=0 and a.checked=0 and a.billType="PUR"'; 
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();    
		}
		
			if($type=="chukudan"){
				//echo $type."=============";
				$lists = $this->data_model->get_invoice_info($where); 
				//print_r($lists);
					//exit();
					// 计算总记录数
					$totalRecords = count($lists);
					// 计算分页偏移量
					$offset = ($page - 1) * $rows;
					// 分页处理
					$lists = array_slice($lists, $offset, $rows);
					
					foreach ($lists as $arr=>$row) {
						$v[$arr]['invNumber']           = $row['invNumber'];
						$v[$arr]['mdescription']           = $row['mdescription'];
						$v[$arr]['totalQty']           = $row['qty'];
						$v[$arr]['mainUnit']           = $row['mainUnit'];
						$v[$arr]['price']           = $row['price'];
						$v[$arr]['amount']           = $row['qty']*$row['price'];
						$v[$arr]['BillName']           = $row['BillName'];
						$v[$arr]['billNo']           = $row['billNo']; 
						$v[$arr]['contactName']    = $row['contactName'];
						$v[$arr]['liname']    = $row['liname'];
						$v[$arr]['description']    = $row['description'];
					}
			}else{
				// 计算分页偏移量
				$offset = ($page - 1) * $rows;
				
				$list = $this->data_model->get_invoice($where.' order by '.$order.' limit '.$offset.','.$rows); 
				foreach ($list as $arr=>$row) {
				$v[$arr]['hxStateCode']  = intval($row['hxStateCode']);
		    
				$v[$arr]['id']           = intval($row['id']);
				$v[$arr]['checkName']    = $row['checkName'];
				$v[$arr]['contactName']    = $row['contactName'];
				$v[$arr]['salesName']    = $row['salesName'];
				$v[$arr]['checked']      = intval($row['checked']);
				$v[$arr]['billDate']     = $row['billDate'];
				$v[$arr]['totalQty']     = $row['totalQty'];
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['transType']    = intval($row['transType']); 
				$v[$arr]['rpAmount']     = (float)abs($row['hasCheck']);
				//$v[$arr]['contactName']  = $row['contactNo'].' '.$row['contactName'];
				$v[$arr]['contactName']  = $row['contactName'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['billNo']       = $row['billNo'];
				$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
				$v[$arr]['userName']     = $row['userName'];
				$v[$arr]['transTypeName']= $row['transTypeName'];
				$v[$arr]['liname']= $row['liname'];
				$v[$arr]['disEditable']  = 0;
			}
		    				
			
		}
		
		$json['status']              = 200;
		$json['msg']                 = 'success'; 
		$json['data']['page']        = $page;
		// 获取总记录数
		if($type=="chukudan"){
			$json['data']['records'] = isset($totalRecords) ? $totalRecords : 0;
		}else{
			$json['data']['records'] = $this->data_model->get_invoice($where,3);
		}
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}
	
	public function exportInvPu(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$row=split(' ',$matchCon);
		$matchCon=$row[1];
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$goodsnumber  = str_enhtml($this->input->get_post('goodsnumber',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and f.checked!=3';
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $goodsnumber  ? ' and b.number="'.$goodsnumber.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : ''; 
		//$where = 'a.isDelete=0 and a.transType='.$transType.''; 
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		///$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();
		//$data['list'] = $this->data_model->get_invoice($where.' order by '.$order); 
		//$list = $this->data_model->get_invoice($where.' order by '.$order);		
		//foreach ($list as $arr=>$row) {
			
				$lists = $this->data_model->get_invoice_info($where.' order by a.id');
				//print_r($this->db->last_query());exit;
			foreach ($lists as $arr=>$row) {
				$v[$arr]['invNumber']           = $row['invNumber'];
				$v[$arr]['mdescription']           = $row['mdescription'];
				$v[$arr]['totalQty']           = $row['qty'];
				$v[$arr]['mainUnit']           = $row['mainUnit'];
				$v[$arr]['price']           = $row['price'];
				$v[$arr]['amount']           = $row['qty']*$row['price'];
				$v[$arr]['BillName']           = $row['BillName'];
				$v[$arr]['billNo']           = $row['billNo']; 
				$v[$arr]['contactName']    = $row['contactName'];
				$v[$arr]['liname']    = $row['liname'];
			}
		//}
		//print_r($v);
		//exit();
		$data['list']=$v;
		$this->load->view('scm/invPu/exportInvPu',$data);	
	}
	public function exportInvPus(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$row=split(' ',$matchCon);
		$matchCon=$row[1];
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0'; 
		$where .= $mname ? ' and a.liname="'.$mname.'"': ''; 
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
  
		//$where = 'a.isDelete=0 and a.transType='.$transType.''; 
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		///$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		//$where .= $this->common_model->get_admin_purview();
		//$data['list'] = $this->data_model->get_invoice($where.' order by '.$order); 
		//$list = $this->data_model->get_invoice($where.' order by '.$order);		
		//foreach ($list as $arr=>$row) {
				$lists = $this->data_model->get_invoice($where); 
			foreach ($lists as $arr=>$row) {
				$v[$arr]['hxStateCode']  = intval($row['hxStateCode']);
		    
				$v[$arr]['id']           = intval($row['id']);
				$v[$arr]['checkName']    = $row['checkName'];
				$v[$arr]['contactName']    = $row['contactName'];
				$v[$arr]['salesName']    = $row['salesName'];
				$v[$arr]['checked']      = intval($row['checked']);
				$v[$arr]['billDate']     = $row['billDate'];
				$v[$arr]['totalQty']     = $row['totalQty'];
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['transType']    = intval($row['transType']); 
				$v[$arr]['rpAmount']     = (float)abs($row['hasCheck']);
				//$v[$arr]['contactName']  = $row['contactNo'].' '.$row['contactName'];
				$v[$arr]['contactName']  = $row['contactName'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['billNo']       = $row['billNo'];
				$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
				$v[$arr]['userName']     = $row['userName'];
				$v[$arr]['transTypeName']= $row['transTypeName'];
				$v[$arr]['liname']= $row['liname'];
				$v[$arr]['disEditable']  = 0;
			}
		//}
		//print_r($v);
		//exit();
		$data['list']=$v;
		$this->load->view('scm/invPu/exportInvPus',$data);	
	}

	public function findUnhxList(){
		$billno = str_enhtml($this->input->get_post('billNo',TRUE));
		$buid = intval($this->input->get_post('buId',TRUE));
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$begindate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$enddate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '(a.billType="PUR") and checked=1';
		$where .= $billno ? ' and a.billNo="'.$billno.'"' : ''; 
		$where .= $buid > 0 ? ' and a.buId='.$buid.'' : ''; 
		$where .= strlen($begindate)>0 ? ' and a.billDate>="'.$begindate.'"' : ''; 
		$where .= strlen($enddate)>0 ? ' and a.billDate<="'.$enddate.'"' : ''; 
		$list = $this->data_model->get_unhx($where.' HAVING notCheck>0');  
		foreach ($list as $arr=>$row) {
			$v[$arr]['type']         = 1;
			$v[$arr]['billId']       = intval($row['id']);
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['billType']     = $row['billType'];
			$v[$arr]['transType']    = $row['transType']==150501 ? '购货' : '退货';
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['billPrice']    = (float)$row['amount'];
			$v[$arr]['hasCheck']     = (float)$row['nowCheck'];
			$v[$arr]['notCheck']     = (float)$row['notCheck'];
		}
		$json['status']              = 200;
		$json['msg']                 = 'success'; 
		$json['data']['totalsize']   = $this->data_model->get_unhx($where.' HAVING notCheck>0',3);    
		$json['data']['items']       = isset($v) ? $v : array();
		die(json_encode($json));
	}

	
	

	public function add(){
	    $this->common_model->checkpurview(2);
	    $data = $this->input->post('postData',TRUE);
	 
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
				'billNo','billType','transType','transTypeName','buId','billDate','postData','hxStateCode',
				'description','totalQty','amount','arrears','rpAmount','totalAmount','createTime',
				'totalArrears','disRate','disAmount','uid','userName','srcOrderNo','srcOrderId',
				'accId','modifyTime','liname','sign'),$data,NULL);
			 		
			$this->db->trans_begin();
			$iid = $this->mysql_model->insert('invoice',$info);   
			$this->invoice_info($iid,$data);
			$this->account_info($iid,$data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit(); 
				$this->common_model->logs('新增购货 单据编号：'.$info['billNo']);
				str_alert(200,'success',array('id'=>intval($iid))); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	

	public function addnew(){
	    $this->add();
    }
	
	 

	public function updateInvPu(){
	    $this->common_model->checkpurview(3);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
				'billType','transType','transTypeName','buId','billDate','hxStateCode',
				'description','totalQty','amount','arrears','rpAmount','uid','userName',
				'totalAmount','totalArrears','disRate','postData',
				'disAmount','accId','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->invoice_info($data['id'],$data);
			$this->account_info($data['id'],$data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改物资出库单 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	

	public function update() {
	    $this->common_model->checkpurview(1);
	 
	    $id   = intval($this->input->get_post('id',TRUE));
		$data =  $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.billType="PUR"',1);
		//print_r($data);
		//exit;
		if (count($data)>0) {
			$info['status'] = 200;
			$info['msg']    = 'success'; 
			$info['data']['id']                 = intval($data['id']);
			$info['data']['buId']               = intval($data['buId']);
			$info['data']['contactName']        = $data['contactName'];
			$info['data']['date']               = $data['billDate'];
			$info['data']['billNo']             = $data['billNo'];
			$info['data']['billType']           = $data['billType'];
			$info['data']['modifyTime']         = $data['modifyTime'];
			$info['data']['createTime']         = $data['createTime'];
			$info['data']['checked']            = intval($data['checked']); 
			$info['data']['checkName']          = $data['checkName'];
			$info['data']['transType']          = intval($data['transType']);
			$info['data']['totalQty']           = (float)$data['totalQty'];
			$info['data']['totalTaxAmount']     = (float)$data['totalTaxAmount'];
			$info['data']['billStatus']         = intval($data['billStatus']);
			$info['data']['disRate']            = (float)$data['disRate'];
			$info['data']['disAmount']          = (float)$data['disAmount'];
			$info['data']['amount']             = (float)abs($data['amount']);
			$info['data']['rpAmount']           = (float)abs($data['rpAmount']);
			$info['data']['arrears']            = (float)abs($data['arrears']);
			$info['data']['userName']           = $data['userName'];
			$info['data']['liname']           = $data['liname'];
			$info['data']['sign']           = $data['sign'];
			$info['data']['status']             = intval($data['checked'])==1 ? 'view' : 'edit';    //edit
			$info['data']['totalDiscount']      = (float)$data['totalDiscount'];
			$info['data']['totalTax']           = (float)$data['totalTax'];
			$info['data']['totalAmount']        = (float)abs($data['totalAmount']);
			$info['data']['description']        = $data['description']; 
			$list = $this->data_model->get_invoice_info('a.isDelete=0 and a.iid='.$id.' order by a.id'); 
            			
			//print_r($list);
			//exit();
		 
			foreach ($list as $arr=>$row) {
				
				 //echo $row['postData'];
				 	//exit();
				$v[$arr]['invSpec']             = $row['invSpec'];
				$v[$arr]['srcOrderEntryId']     = $row['srcOrderEntryId'];
				$v[$arr]['srcOrderNo']          = $row['srcOrderNo'];
				$v[$arr]['srcOrderId']          = $row['srcOrderId'];
				//$v[$arr]['goods']               = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['goodsnumber']               = $row['invNumber'];
				$v[$arr]['invName']             = $row['invName'];
				$v[$arr]['qty']                 = (float)abs($row['qty']);
				$v[$arr]['amount']              = (float)abs($row['amount']);
				$v[$arr]['taxAmount']           = (float)abs($row['taxAmount']);
				$v[$arr]['price']               = (float)$row['price'];
				$v[$arr]['tax']                 = (float)$row['tax'];
				$v[$arr]['taxRate']             = (float)$row['taxRate'];
				$v[$arr]['mainUnit']            = $row['mainUnit'];
				$v[$arr]['deduction']           = (float)$row['deduction'];
				$v[$arr]['invId']               = intval($row['invId']);
				$v[$arr]['invNumber']           = $row['invNumber'];
				$v[$arr]['transTypeName']           = $row['transTypeName'];
				$v[$arr]['locationId']          = intval($row['locationId']);
				$v[$arr]['locationName']        = $row['locationName'];
				$v[$arr]['discountRate']        = $row['discountRate'];
				$v[$arr]['unitId']              = intval($row['unitId']);
				$v[$arr]['description']         = $row['description'];
				$v[$arr]['BillName']            = $row['BillName'];
				$v[$arr]['mdescription']        = $row['mdescription'];
				$v[$arr]['skuId']               = intval($row['skuId']);
				$v[$arr]['skuName']             = '';
			}
			$info['data']['entries']            = isset($v) ? $v : array();
			$info['data']['accId']              = (float)$data['accId'];
			$accounts = $this->data_model->get_account_info('a.isDelete=0 and a.iid='.$id.' order by a.id');  
			foreach ($accounts as $arr=>$row) {
				$s[$arr]['invoiceId']           = intval($id);
				$s[$arr]['billNo']              = $row['billNo'];
				$s[$arr]['buId']                = intval($row['buId']);
			    $s[$arr]['billType']            = $row['billType'];
				$s[$arr]['transType']           = $row['transType'];
				$s[$arr]['transTypeName']       = $row['transTypeName'];
				$s[$arr]['billDate']            = $row['billDate']; 
			    $s[$arr]['accId']               = intval($row['accId']);
				$s[$arr]['account']             = $row['accountNumber'].''.$row['accountName']; 
				$s[$arr]['payment']             = (float)abs($row['payment']); 
				$s[$arr]['wayId']               = (float)$row['wayId']; 
				$s[$arr]['way']                 = $row['categoryName']; 
				$s[$arr]['settlement']          = $row['settlement']; 
		    }  
			$info['data']['accounts']           = isset($s) ? $s : array();
			die(json_encode($info));
			//print_r($info);
		//exit;
		}
		str_alert(-1,'单据不存在、或者已删除');  
    }
	public function daoru(){
		
		$data = $this->mysql_model->query("select * from ci_invoice_cursor",2); 
		die(json_encode($data));
	}
	public function daorudelete(){
		 $this->mysql_model->delete('invoice_cursor','');
		 $this->mysql_model->delete('orders_cursor','');
	}
	 
	
 
    public function toPdf() {
	    $this->common_model->checkpurview(85);
	    $id   = intval($this->input->get('id',TRUE));
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.billType="PUR"',1);  
		$tempmdescription="";
		$tempdescription="";
		$tempname="";
		
		if (count($data)>0) { 
			$data['num']    = 8;
			$data['system'] = $this->common_model->get_option('system'); 
			$postData = unserialize($data['postData']);
			$mdess =$this->mbStrSplit($postData['contactName'], 20);
				foreach ($mdess as $key2=>$row3) {
					$tempname.=$row3."<br/>";
				}
				$data['contactName']=$tempname;
		    foreach ($postData['entries'] as $arr=>$row) {
			    $v[$arr]['i']               = $arr + 1;
				$v[$arr]['invId']           = intval($row['invId']);
				$v[$arr]['invNumber']       = $row['invNumber'];
				$v[$arr]['invSpec']         = $row['invSpec'];
				$v[$arr]['invName']         = $row['invName'];
				//$v[$arr]['goods']           = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['goods']           = $row['invNumber'];
				$v[$arr]['qty']             = (float)abs($row['qty']);
				$v[$arr]['price']           = $row['price'];
				$v[$arr]['mainUnit']        = $row['mainUnit'];
				$v[$arr]['amount']          = $row['amount'];
				$v[$arr]['deduction']       = $row['deduction'];
				$v[$arr]['discountRate']    = $row['discountRate'];
				$v[$arr]['unitId']          = intval($row['unitId']);
				$v[$arr]['locationName']    = $row['locationName'];
				$v[$arr]['BillName']            = $row['BillName'];
				$v[$arr]['mdescription']        = $row['mdescription'];
				$mdes =$this->mbStrSplit($row['mdescription'], 20);
				
				foreach ($mdes as $key=>$row1) {
					$tempmdescription.=$row1."<br/>";
				}
				//$v[$arr]['mdescription']=$tempmdescription;
				
				
				
				$v[$arr]['description']        = $row['description'];
				$mdes1 =$this->mbStrSplit($row['description'], 10);
				  foreach ($mdes1 as $key1=>$row2) {
					  $tempdescription.=$row2."<br/>";
					 }
				 //$v[$arr]['description']=$tempdescription;
				$v[$arr]['sign']        = $row['sign'];
			}  
			$data['countpage']  = ceil(count($postData['entries'])/$data['num']); 
			$data['list']       = isset($v) ? $v : array();                           
		    ob_start();
			//print_r($data['list']);
			//exit();
			$this->load->view('scm/invPu/toPdf',$data);
			$content = ob_get_clean();
			require_once('./application/libraries/html2pdf/html2pdf.php');
			
			 
			try {
				$html2pdf = new HTML2PDF('P', 'A4', 'tr');
				$html2pdf->setDefaultFont('javiergb');
				$html2pdf->pdf->SetDisplayMode('fullpage');
				$html2pdf->writeHTML($content, '');
				$html2pdf->Output('invPur_'.date('YmdHis').'.pdf');
			}catch(HTML2PDF_exception $e) {
				echo $e;
				exit;
			}  	  
		} 
		str_alert(-1,'单据不存在、或者已删除');  	   
	}
	
	
	
	//购购单删除
    public function delete() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE));
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="PUR"');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] >0 && str_alert(-1,'其中已有审核的不可删除'); 
				$ids[]           = $row['id'];
				$billNo[]        = $row['billNo'];
				$msg[$arr]['id'] = $row['billNo'];
				$msg[$arr]['isSuccess'] = 1;
				$msg[$arr]['msg'] = '删除成功！';
			}
			$id     = join(',',$ids);
			$billNo = join(',',$billNo); 
			 
		    $this->db->trans_begin();
			$this->mysql_model->update('invoice',array('isDelete'=>1),'(id in('.$id.'))');   
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			$this->mysql_model->update('account_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除购货订单 单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在');  
	}
	
	//购购单删除
    public function deletestock() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE)); 
		    $this->db->trans_begin();
			$this->mysql_model->update('stock',array('isDelete'=>1),'(id in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除购货订单 单据编号：'.$billNo);
				str_alert(200,'删除成功！'); 	 
			}
		str_alert(-1,'单据不存在');  
	}
	
	//购购单删除
    public function deletecangku() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE)); 
		    $this->db->trans_begin();
			$this->mysql_model->update('cangku',array('isDelete'=>1),'(id in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除购货订单 单据编号：'.$billNo);
				str_alert(200,'删除成功！'); 	 
			}
		str_alert(-1,'单据不存在');  
	}
	
	//购购单删除
    public function deletecangkuhuizong() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE)); 
		    $this->db->trans_begin();
			$this->mysql_model->update('cangku_huizong',array('isDelete'=>1),'(id in('.$id.'))');   
			
			$result=$this->data_model->get_cangkuhuizong("id=$id");  
			
			foreach($result as $key=>$row){
				$number=$row['inventoryNew'];
				$ordernumber=$row['ordernumber'];
				$goodsnumber=$row['goodsnumber'];
				$this->db->query("update ci_cangku set number=number-$number where ordernumber ='$ordernumber' and goodsnumber='$goodsnumber'");
				$this->db->query("update ci_cangku set inventoryNew=inventoryNew+$number where ordernumber ='$ordernumber' and goodsnumber='$goodsnumber'");
			}
			
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除购货订单 单据编号：'.$billNo);
				str_alert(200,'删除成功！'); 	 
			}
		str_alert(-1,'单据不存在');  
	}
	public function deletexiangmu() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE)); 
		    $this->db->trans_begin();
			$this->mysql_model->update('xiangmuku',array('isDelete'=>1),'(id in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除：'.$billNo);
				str_alert(200,'删除成功！'); 	 
			}
		str_alert(-1,'单据不存在');  
	}
	 //购购单删除
    public function deletegoods() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE)); 
		    $this->db->trans_begin();
			$this->mysql_model->update('goods',array('isDelete'=>1),'(id in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除购货订单 单据编号：'.$billNo);
				str_alert(200,'删除成功！'); 	 
			}
		str_alert(-1,'单据不存在');  
	}
    public function delete1() {
	    $this->common_model->checkpurview(4);
	    $id   = intval($this->input->get('id',TRUE));
		$data = $this->mysql_model->get_rows('invoice',array('id'=>$id,'billType'=>'PUR'));  
		if (count($data)>0) {
		    //$data['checked'] >0 && str_alert(-1,'已审核的不可删除'); 
		    $this->db->trans_begin();
			$this->mysql_model->update('invoice',array('isDelete'=>1),array('id'=>$id));   
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),array('iid'=>$id));  
			if ($data['accId']>0) { 
				$this->mysql_model->update('account_info',array('isDelete'=>1),array('iid'=>$id));   
			}
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除订单 单据编号：'.$data['billNo']);
				str_alert(200,'success'); 	 
			}
		}
		str_alert(-1,'单据不存在、或者已删除');  
	}
	
	
	
	
	//单个审核   
	public function checkInvPu() {
	    $this->common_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		 
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			
		
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$info = elements(array(
				'billType','transType','transTypeName','buId','billDate','checked','checkName',
				'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode',
				'totalArrears','disRate','postData','disAmount','accId','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			
			//特殊情况
			if ($data['id'] < 0) {
			    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId','billDate','checked','checkName',
						'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode', 
						'totalArrears','disRate','disAmount','postData','createTime',
						'salesId','uid','userName','accId','modifyTime'),$data,NULL);
			    $iid = $this->mysql_model->insert('invoice',$info);
			    $this->invoice_info($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			    $this->invoice_info($data['id'],$data);
			}
			 
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单据编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	//单个审核   
	public function RejectInvPu() {
	    $this->common_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		 
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			
		
			$data['checked']         = 2;
			$data['checkName']       = $this->jxcsys['name']; 
			$info = elements(array(
				'billType','transType','transTypeName','buId','billDate','checked','checkName',
				'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode',
				'totalArrears','disRate','postData','disAmount','accId','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			
			//特殊情况
			if ($data['id'] < 0) {
			    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId','billDate','checked','checkName',
						'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode', 
						'totalArrears','disRate','disAmount','postData','createTime',
						'salesId','uid','userName','accId','modifyTime'),$data,NULL);
			    $iid = $this->mysql_model->insert('invoice',$info);
			    $this->invoice_info($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			    $this->invoice_info($data['id'],$data);
			}
			 
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单据编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	public function zuofeiCheckInvPu() {
	    $this->common_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		 
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			
		
			$data['checked']         = 3;
			$data['checkName']       = $this->jxcsys['name']; 
			$info = elements(array(
				'billType','transType','transTypeName','buId','billDate','checked','checkName',
				'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode',
				'totalArrears','disRate','postData','disAmount','accId','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			
			//特殊情况
			if ($data['id'] < 0) {
			    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId','billDate','checked','checkName',
						'description','totalQty','amount','arrears','rpAmount','totalAmount','hxStateCode', 
						'totalArrears','disRate','disAmount','postData','createTime',
						'salesId','uid','userName','accId','modifyTime'),$data,NULL);
			    $iid = $this->mysql_model->insert('invoice',$info);
			    $this->invoice_info($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			    $this->invoice_info($data['id'],$data);
			}
			 
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单据编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	//批量审核  
    public function batchCheckInvPu() {
	    $this->common_model->checkpurview(86);
	    $id   = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('invoice','(id in('.$id.')) and billType="PUR" and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] > 0 && str_alert(-1,'勾选当中已有审核，不可重复审核'); 
			    $ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			    $srcOrderId[] = $row['srcOrderId'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
			$srcOrderId = join(',',array_filter($srcOrderId));
			$sql = $this->mysql_model->update('invoice',array('checked'=>1,'checkName'=>$this->jxcsys['name']),'(id in('.$id.'))'); 
			if ($sql) {
			    //$this->mysql_model->update('invoice_info',array('checked'=>1),'(iid in('.$id.'))'); 
				$this->common_model->logs('物资出库单编号：'.$billNo.'的单据已被审核！');
				str_alert(200,'单据编号：'.$billNo.'的单据已被审核！');
			} 
			str_alert(-1,'审核失败');  
		}
		str_alert(-1,'单据不存在！'); 
	}
	
	//批量反审核
    public function rsBatchCheckInvPu() {
	    $this->common_model->checkpurview(87);
	    $id   = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('invoice','(id in('.$id.')) and billType="PUR" and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
				$srcOrderId[] = $row['srcOrderId'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
			$srcOrderId = join(',',array_filter($srcOrderId));
			
			$sql = $this->mysql_model->update('invoice',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			if ($sql) {
			    //$this->mysql_model->update('invoice_info',array('checked'=>0),'(iid in('.$id.'))'); 
				$this->common_model->logs('物资出库单单号：'.$billNo.'的单据已被反审核！');
				str_alert(200,'物资出库单编号：'.$billNo.'的单据已被反审核！'); 
			} 
			str_alert(-1,'反审核失败');  
		}
		str_alert(-1,'单据不存在！'); 
	}

	//公共验证
	private function validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['billType']        = 'PUR';
		$data['transTypeName']   = $data['transType']==150501 ? '购货' : '退货';
		$data['billDate']        = $data['date'];
		$data['buId']            = intval($data['buId']);
		$data['accId']           = intval($data['accId']);
		$data['transType']       = intval($data['transType']);
		$data['amount']          = (float)$data['amount'];
		$data['arrears']         = (float)$data['arrears'];
		$data['disRate']         = (float)$data['disRate'];
		$data['disAmount']       = (float)$data['disAmount'];
		$data['rpAmount']        = (float)$data['rpAmount'];
		$data['totalQty']        = (float)$data['totalQty'];
		$data['totalArrears']    = (float)$data['totalArrears'];
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		
		$data['arrears'] < 0 && str_alert(-1,'本次欠款要为数字，请输入有效数字！'); 
		$data['disRate'] < 0 && str_alert(-1,'折扣率要为数字，请输入有效数字！'); 
		$data['rpAmount'] < 0  && str_alert(-1,'本次收款要为数字，请输入有效数字！'); 
		$data['amount'] < $data['rpAmount']  && str_alert(-1,'本次收款不能大于折后金额！'); 
		$data['amount'] < $data['disAmount'] && str_alert(-1,'折扣额不能大于合计金额！'); 
		
		if ($data['amount']==$data['rpAmount']) {
			$data['hxStateCode'] = 2;
		} else {	
		    $data['hxStateCode'] = $data['rpAmount']!=0 ? 1 : 0;
		}
		
		$data['amount']          = $data['transType']==150501 ? abs($data['amount']) : -abs($data['amount']);
		$data['arrears']         = $data['transType']==150501 ? abs($data['arrears']) : -abs($data['arrears']);
		$data['rpAmount']        = $data['transType']==150501 ? abs($data['rpAmount']) : -abs($data['rpAmount']);
		$data['totalAmount']     = $data['transType']==150501 ? abs($data['totalAmount']) : -abs($data['totalAmount']);
		$data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		
		
		
		strlen($data['billNo']) < 1 && str_alert(-1,'单据编号不为空');  
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		
		
		
		 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'billType'=>'PUR','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo']  = $invoice['billNo'];	
		} else {
		    //$data['billNo']  = str_no('CG');    
		}
	    
		 
		foreach ($data['accounts'] as $arr=>$row) {
			(float)$row['payment'] < 0 && str_alert(-1,'结算金额要为数字，请输入有效数字！');
		} 
		
 
		$this->mysql_model->get_count('contact',array('id'=>$data['buId'])) < 1 && str_alert(-1,'物资出库单位不存在');   
		
		 
		$system  = $this->common_model->get_option('system');
		
	 
		if ($system['requiredCheckStore']==1) {
		    $inventory = $this->data_model->get_invoice_info_inventory();
		}
		 
		$storage = array_column($this->mysql_model->get_results('storage',array('disable'=>0)),'id');  
		foreach ($data['entries'] as $arr=>$row) {
			
			
			//intval($row['invId'])<1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			(float)$row['price'] < 0  && str_alert(-1,'商品销售单价要为数字，请输入有效数字！'); 
			(float)$row['discountRate'] < 0  && str_alert(-1,'折扣率要为数字，请输入有效数字！');
			//intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			//!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
		 
			if ($system['requiredCheckStore']==1 && $data['id']<1) {  
				if ($data['transType']==150502) {                        
					if (isset($inventory[$row['invId']][$row['locationId']])) {
						$inventory[$row['invId']][$row['locationId']] < $row['qty'] && str_alert(-1,$row['locationName'].$row['invName'].'商品库存不足！'); 
					} else {
						str_alert(-1,$row['invName'].'库存不足！');
					}
				}
			}
		} 
		$data['srcOrderNo'] = $data['entries'][0]['srcOrderNo'] ? $data['entries'][0]['srcOrderNo'] : 0; 
		$data['srcOrderId'] = $data['entries'][0]['srcOrderId'] ? $data['entries'][0]['srcOrderId'] : 0; 
		$data['postData'] = serialize($data);
		
		return $data;
	}
	
	
 
	private function invoice_info($iid,$data) {
		foreach ($data['entries'] as $arr=>$row) {
			$v[$arr]['iid']              = $iid;
			$v[$arr]['uid']              = $data['uid'];
			$v[$arr]['billNo']           = $data['billNo'];
			$v[$arr]['buId']             = $data['buId'];
			$v[$arr]['billDate']         = $data['billDate']; 
			$v[$arr]['billType']         = $data['billType'];
			$v[$arr]['transType']        = $data['transType'];
			$v[$arr]['transTypeName']    = $data['transTypeName'];
			$v[$arr]['invId']            = intval($row['invId']);
			$v[$arr]['skuId']            = intval($row['skuId']);
			$v[$arr]['unitId']           = intval($row['unitId']);
			$v[$arr]['locationId']       = intval($row['locationId']);
			$v[$arr]['qty']              = $data['transType']==150501 ? abs($row['qty']) :-abs($row['qty']); 
			$v[$arr]['amount']           = $data['transType']==150501 ? abs($row['amount']) :-abs($row['amount']); 
			$v[$arr]['price']            = abs($row['price']);  
			$v[$arr]['discountRate']     = $row['discountRate'];  
			$v[$arr]['deduction']        = $row['deduction'];  
			$v[$arr]['description']      = $row['description']; 
			$v[$arr]['BillName']         = $row['BillName'];
			$v[$arr]['mdescription']     = $row['mdescription'];
			$v[$arr]['liname']     = $data['liname'];
			if (intval($row['srcOrderId'])>0) {   
			    $v[$arr]['srcOrderEntryId']  = intval($row['srcOrderEntryId']);  
				$v[$arr]['srcOrderId']       = intval($row['srcOrderId']);  
				$v[$arr]['srcOrderNo']       = $row['srcOrderNo']; 
			} else {
			    $v[$arr]['srcOrderEntryId']  = 0;  
				$v[$arr]['srcOrderId']       = 0;  
				$v[$arr]['srcOrderNo']       = ''; 
			}
		}
		if (isset($v)) {
			if ($data['id']>0) {                     
				$this->mysql_model->delete('invoice_info',array('iid'=>$iid));
			}
			$this->mysql_model->insert('invoice_info',$v);
		}
	}
	
 
	private function account_info($iid,$data) {
		foreach ($data['accounts'] as $arr=>$row) {
			$v[$arr]['iid']               = $iid;
			$v[$arr]['uid']               = $data['uid'];
			$v[$arr]['billNo']            = $data['billNo'];
			$v[$arr]['buId']              = $data['buId'];
			$v[$arr]['billType']          = $data['billType'];
			$v[$arr]['transType']         = $data['transType'];
			$v[$arr]['transTypeName']     = $data['transType']==150501 ? '普通采购' : '采购退回'; 
			$v[$arr]['payment']           = $data['transType']==150501 ? -abs($row['payment']) : abs($row['payment']); 
			$v[$arr]['billDate']          = $data['billDate']; 
			$v[$arr]['accId']             = $row['accId']; 
			$v[$arr]['wayId']             = $row['wayId'];
			$v[$arr]['settlement']        = $row['settlement'];
		} 
		if ($data['id']>0) {                      
			$this->mysql_model->delete('account_info',array('iid'=>$iid));
		}
		if (isset($v)) {
			$this->mysql_model->insert('account_info',$v);
		}
	}
	
	
	public function getImagesById() {
	    if (!$this->common_model->checkpurviews(204)){
		    str_alert(-1,'没有上传权限'); 
		}
	    $id = str_enhtml($this->input->post('id',TRUE));
	    $list = $this->mysql_model->get_results('invoice_img',array('isDelete'=>0,'billNo'=>$id));
		foreach ($list as $arr=>$row) {
		    $v[$arr]['pid']          = $row['id'];
			$v[$arr]['status']       = 1;
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['url']          = site_url().'/scm/invPu/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['thumbnailUrl'] = site_url().'/scm/invPu/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['deleteUrl']    = '';
			$v[$arr]['deleteType']   = '';
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = isset($v) ? $v : array();
		die(json_encode($json));  
	}
	
	
	//导入excel
	
	public function postexcel($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT']; 
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcel($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcel('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
	}
	
	//上传excel
	//public function uploadExcel() {
		
		
		
	//}
	public function uploadexcel(){
			 
			$name=$_FILES['file']['name']; //获取客户端机器原文件的名称 
			$type=strstr($name,"."); //获取从"."到最后的字符 
			if($type==".xlsx"||$type==".xls"){ 
			$name=explode('.',$_FILES["file"]["name"]);
				$date=date('Ymdhis');
				$newPath=$date.'.'.$name[1];
				//$_FILES["file"]["name"]=iconv("UTF-8","gb2312",$_FILES["file"]["name"]);
				// 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
				move_uploaded_file($_FILES["file"]["tmp_name"], "./data/upfile/excel/" . $newPath);
				//$_FILES["file"]["name"]=iconv("gb2312","UTF-8",$_FILES["file"]["name"]);
				$this->postexcel($newPath);
				//echo "<br/>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;导入成功，请关闭窗口！";		
			}else{
				//echo iconv("GB2312","UTF-8",'导入完成');
				header("Content-type:text/html;charset=utf-8"); 
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!"; 
				exit();		
			}
	}
	public function uploadexcelgoods(){
			 
			$name=$_FILES['file']['name']; //获取客户端机器原文件的名称 
			$type=strstr($name,"."); //获取从"."到最后的字符 
			if($type==".xlsx"||$type==".xls"){ 
			$name=explode('.',$_FILES["file"]["name"]);
				$date=date('Ymdhis');
				$newPath=$date.'.'.$name[1];
				//$_FILES["file"]["name"]=iconv("UTF-8","gb2312",$_FILES["file"]["name"]);
				// 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
				move_uploaded_file($_FILES["file"]["tmp_name"], "./data/upfile/excel/" . $newPath);
				//$_FILES["file"]["name"]=iconv("gb2312","UTF-8",$_FILES["file"]["name"]);
				$this->postexcelgoods($newPath);
				//echo "<br/>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;导入成功，请关闭窗口！";		
			}else{
				header("Content-type:text/html;charset=utf-8"); 
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!"; 
				//echo iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				exit();		
			}
	}
	public function postexcelgoods($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT']; 
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcelgoods($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcel('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
	}
	
	//上传图片信息
	public function uploadImages() {
	    if (!$this->common_model->checkpurviews(203)){
		    str_alert(-1,'没有上传权限'); 
		}
	    require_once './application/libraries/UploadExcel.php';
		$config = array(
			'script_url' => base_url().'inventory/uploadimages',
			'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/data/upfile/Contract/',
			'upload_url' => base_url().'data/upfile/Contract/',
			'delete_type' =>'',
			'print_response' =>false
		);
		$UploadExcel = new UploadExcel($config);
		$list  = (array)json_decode(json_encode($UploadExcel->response['files'][0]), true); 
		$info  = elements(array('name','size','type','url','thumbnailUrl','deleteUrl','deleteType'),$list,NULL);
		$newid = $this->mysql_model->insert('goods_img',$info);
		
		 
		$files[0]['pid']          = intval($newid);
		$files[0]['status']       = 1;
		$files[0]['size']         = (float)$list['size'];
		$files[0]['name']         = $list['name'];
		$files[0]['url']          = site_url().'/scm/invPu/getImage?action=getImage&pid='.$newid;
		$files[0]['thumbnailUrl'] = site_url().'/scm/invPu/getImage?action=getImage&pid='.$newid;
		$files[0]['deleteUrl']    = '';
		$files[0]['deleteType']   = '';
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = $files;
        die(json_encode($json)); 
	}
	
	//保存上传图片信息
	public function addImagesToInv() {
	    if (!$this->common_model->checkpurviews(205)){
		    str_alert(-1,'没有上传权限'); 
		}
	    $data = $this->input->post('postData');
		if (strlen($data)>0) {
		    $v = $s = array();
		    $data = (array)json_decode($data, true); 
			$id   = isset($data['id']) ? $data['id'] : 0;
		    !isset($data['files']) || count($data['files']) < 1 && str_alert(-1,'请先添加图片！'); 
			foreach($data['files'] as $arr=>$row) {
			    if ($row['status']==1) {
					$v[$arr]['id']       = $row['pid'];
					$v[$arr]['billNo']   = $id;
				} else {
				    $s[$arr]['id']       = $row['pid'];
					$s[$arr]['billNo']   = $id;
					$s[$arr]['isDelete'] = 1;
				}
			}
			$this->mysql_model->update('invoice_img',array_values($v),'id');
			$this->mysql_model->update('invoice_img',array_values($s),'id');
			str_alert(200,'success'); 
	    }
		str_alert(-1,'保存失败'); 
	}
	
	//获取图片信息
	public function getImage() {
	    $id = intval($this->input->get_post('pid',TRUE));
	    $data = $this->mysql_model->get_rows('invoice_img',array('id'=>$id));
		if (count($data)>0) {
		    $url     = './data/upfile/Contract/'.$data['name'];
			$info    = getimagesize($url);  
			$imgdata = fread(fopen($url,'rb'),filesize($url));   
			header('content-type:'.$info['mime'].'');  
			echo $imgdata;   
		}	 
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */