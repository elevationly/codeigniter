<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }

    //商品列表
	public function index() {
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$tmparray = explode("*",$skey);


		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));
		$where = '(a.isDelete=0)';
		if(count($tmparray)>1){

			  $where .= $skey ? ' and (name like "%'.$tmparray[0].'%" and name like "%'.$tmparray[1].'%")': '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $skey ? ' and number="'.$skey.'" or name like "%'.$skey.'%"': '';
		  }

		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_goods($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		foreach ($list as $arr=>$row) {
		    $v[$arr]['amount']        = (float)$row['iniamount'];
			$v[$arr]['barCode']       = $row['barCode'];
			$v[$arr]['categoryName']  = $row['categoryName'];
			$v[$arr]['currentQty']    = $row['totalqty'];                            //当前库存
			$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			$v[$arr]['discountRate']  = 0;
			$v[$arr]['id']            = intval($row['id']);
			$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['josl']          = $row['josl'];
			$v[$arr]['name']          = $row['name'];
			$v[$arr]['number']        = $row['number'];
			$v[$arr]['pinYin']        = $row['pinYin'];
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];

		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_goods($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		die(json_encode($json));

	}

	public function updyj(){
		$yjid = str_enhtml($this->input->get_post('yjid',TRUE));
		$yjgoodsnumner = str_enhtml($this->input->get_post('yjgoodsnumnerupd',TRUE));
		$yjnum = str_enhtml($this->input->get_post('yjnumupd',TRUE));
		$data=array(
			'number'=>$yjgoodsnumner,
			'yjnum'=>$yjnum,

		);
		$bool=$this->db->update('ci_stock_yj',$data,array('id'=>$yjid));//将数据库.user表里id=3的用户密码给为12345
		if($bool){
			$json['status'] = 200;
			$json['msg']    = 'success';
		}else{
			$json['status'] = 400;
			$json['msg']    = 'error';
		}

		die(json_encode($json));




		//$this->db->query("insert into ci_stock_yj values('$yjgoodsnumner','$yjnum',$result['id'])");
	}

	public function addyj(){
		$yjgoodsnumner = str_enhtml($this->input->get_post('yjgoodsnumner',TRUE));
		$yjnum = str_enhtml($this->input->get_post('yjnum',TRUE));
		$query=$this->db->query("select id from ci_stock where goodsnumber='$yjgoodsnumner'");
		$result=$query->row_array();


		$query2=$this->db->query("select count(*) as num from ci_stock_yj where number='$yjgoodsnumner'");
		$result2=$query2->result_array();
		foreach ($result2 as $arr=>$row) {
			$json[$arr]['num']=$row['num'];
			if($row['num']==1){
				die(json_encode($json));
			}
		}
		$data=array(
			'number'=>$yjgoodsnumner,
			'yjnum'=>$yjnum,
			'sid'=>$result['id'],
		);
		if($result['id']){
			$bool=$this->db->insert('ci_stock_yj',$data);
			if($bool){
				$json['status'] = 200;
				$json['msg']    = 'success';
				die(json_encode($json));
			}
		}else{
			$json['status'] = 200;
			$json['msg']    = 'error';
			die(json_encode($json));
		}


		//$this->db->query("insert into ci_stock_yj values('$yjgoodsnumner','$yjnum',$result['id'])");
	}

	public function querystock23(){
		$goodsnumber = str_enhtml($this->input->get_post('goodsnumber',TRUE));
		$query=$this->db->query("select distinct(number) from ci_invoice_info as a inner join ci_goods as b on a.invId=b.id where number like '%$goodsnumber%' ");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['number']=$row['number'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock22(){
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$query=$this->db->query("select distinct(ordernumber) from ci_orders_info where ordernumber like '%$ordernumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['ordernumber']=$row['ordernumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock21(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(name) from ci_xiangmuku where name like '%$matchCon%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['name']=$row['name'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock20(){
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$query=$this->db->query("select distinct(ordernumber) from ci_xiangmuku where ordernumber like '%$ordernumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['ordernumber']=$row['ordernumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock19(){
		$number = str_enhtml($this->input->get_post('number',TRUE));
		$query=$this->db->query("select distinct(number) from ci_xiangmuku where number like '%$number%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['number']=$row['number'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock18(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_xiangmuku where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock17(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_orders_info where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock16(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_invoice_info where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock15(){
		$mnumber = str_enhtml($this->input->get_post('mnumber',TRUE));
		$query=$this->db->query("select distinct(a.goodsnumber) from ci_stock as a left join ci_stock_yj as b on a.id=b.sid where goodsnumber like '%$mnumber%' ");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock14(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(a.mdescription) from ci_stock as a left join ci_stock_yj as b on a.id=b.sid where a.mdescription like '%$matchCon%' ");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['name']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock13(){
		$flagNo = str_enhtml($this->input->get_post('flagNo',TRUE));
		$query=$this->db->query("select distinct(flagNo) from ci_stock where flagNo like '%$flagNo%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['flagNo']=$row['flagNo'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}
	public function querystock13s(){
		$sign = str_enhtml($this->input->get_post('sign',TRUE));
		$query=$this->db->query("select distinct(sign) from ci_cangku_huizong where sign like '%$sign%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['sign']=$row['sign'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}
	public function querystock13b(){
		$beizhu = str_enhtml($this->input->get_post('beizhu',TRUE));
		$query=$this->db->query("select distinct(beizhu) from ci_cangku_huizong where beizhu like '%$beizhu%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['beizhu']=$row['beizhu'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

    public function querystock13xmb(){
        $flagcontact = str_enhtml($this->input->get_post('flagcontact',TRUE));
        $query=$this->db->query("select distinct(flagcontact) from ci_cangku_huizong where flagcontact like '%$flagcontact%' and isDelete=0");
        $result=$query->result_array();
        foreach ($result as $arr=>$row) {
            $json[$arr]['flagcontact']=$row['flagcontact'];
        }
        //$json['data']=isset($v) ? $v :'';
        die(json_encode($json));
    }

	public function querystock12(){
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$query=$this->db->query("select distinct(Arrivaltime) from ci_stock where Arrivaltime like '%$Arrivaltime%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['Arrivaltime']=$row['Arrivaltime'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querycangku4(){
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$query=$this->db->query("select distinct(Arrivaltime) from ci_cangku where Arrivaltime like '%$Arrivaltime%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['Arrivaltime']=$row['Arrivaltime'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystockhuizong4(){
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$query=$this->db->query("select distinct(flagtime) from ci_cangku_huizong where flagtime like '%$Arrivaltime%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['Arrivaltime']=$row['flagtime'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock11(){
		$mnumber = str_enhtml($this->input->get_post('mnumber',TRUE));
		$query=$this->db->query("select distinct(billNo) from ci_invoice where billNo like '%$mnumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['billNo']=$row['billNo'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

    public function querystock31(){
        $mnumber = str_enhtml($this->input->get_post('mnumber',TRUE));
        $query=$this->db->query("select distinct(billNo) from ci_orders where billNo like '%$mnumber%' and isDelete=0");
        $result=$query->result_array();
        foreach ($result as $arr=>$row) {
            $json[$arr]['billNo']=$row['billNo'];
        }
        //$json['data']=isset($v) ? $v :'';
        die(json_encode($json));
    }

	public function querystock10(){
		$mname = str_enhtml($this->input->get_post('mname',TRUE));
		$query=$this->db->query("select distinct(liname) from ci_invoice where liname like '%$mname%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['liname']=$row['liname'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock9(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(a.name),a.number from ci_contact as a inner join ci_invoice_info as b on b.buId=a.id where name like '%$matchCon%' or number like '%$matchCon%' ");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['name']=$row['number']." ".$row['name'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock8(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_orders_info  where mdescription like '%$mdescription%' union all select distinct(mdescription) from ci_invoice_info   where mdescription like '%$mdescription%'");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock7(){
		$mnumber = str_enhtml($this->input->get_post('mnumber',TRUE));
		$query=$this->db->query("select distinct(goodsnumber) from ci_orders_info where goodsnumber like '%$mnumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock6(){
		$mname = str_enhtml($this->input->get_post('mname',TRUE));
		$query=$this->db->query("select distinct(liname) from ci_orders_info where liname like '%$mname%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['liname']=$row['liname'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock5(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(a.name),a.number from ci_contact as a inner join ci_invoice_info as b on b.buId=a.id where name like '%$matchCon%' or number like '%$matchCon%' ");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['name']=$row['number']." ".$row['name'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock4(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(number) from ci_goods where number like '%$matchCon%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['number']=$row['number'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	public function querystock3(){
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$query=$this->db->query("select distinct(ordernumber) from ci_stock where ordernumber like '%$ordernumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['ordernumber']=$row['ordernumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querycangku3(){
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$query=$this->db->query("select distinct(ordernumber) from ci_cangku where ordernumber like '%$ordernumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['ordernumber']=$row['ordernumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystockhuizong3(){
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$query=$this->db->query("select distinct(ordernumber) from ci_cangku_huizong where ordernumber like '%$ordernumber%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['ordernumber']=$row['ordernumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock2(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_stock where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querycangku2(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_cangku where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystockhuizong2(){
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$query=$this->db->query("select distinct(mdescription) from ci_cangku_huizong where mdescription like '%$mdescription%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['mdescription']=$row['mdescription'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystock(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(goodsnumber) from ci_stock where goodsnumber like '%$matchCon%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querycangku(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(goodsnumber) from ci_cangku where goodsnumber like '%$matchCon%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}

	public function querystockhuizong1(){
		$matchCon = str_enhtml($this->input->get_post('matchCon',TRUE));
		$query=$this->db->query("select distinct(goodsnumber) from ci_cangku_huizong where goodsnumber like '%$matchCon%' and isDelete=0");
		$result=$query->result_array();
		foreach ($result as $arr=>$row) {
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
		}
		//$json['data']=isset($v) ? $v :'';
		die(json_encode($json));
	}


	//商品列表
	public function xiangmulist() {
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按项目名称查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按项目定义号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$number = str_enhtml($this->input->get_post('number',TRUE));
		$number=$number=='按物料号查询'?'':$number;
		$tmparray = explode("*",$mdescription);
		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));
		$where = '(a.isDelete=0)';
		$where .= $skey ? ' and a.name like "%'.$skey.'%"': '';
		$where .= $ordernumber ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $number ? ' and a.number="'.$number.'"': '';
		if(count($tmparray)>1){

			  $where .= $mdescription ? ' and (mdescription like "%'.$tmparray[0].'%" and mdescription like "%'.$tmparray[1].'%")' : '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		  }

		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_xiangmu($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		foreach ($list as $arr=>$row) {
		     // $v[$arr]['amount']        = (float)$row['iniamount'];
			$v[$arr]['id']       = $row['id'];
			$v[$arr]['names']       = $row['name'];
			$v[$arr]['ordernumbers']  = $row['ordernumber'];
			$v[$arr]['numbers']    = $row['number'];
			$v[$arr]['mdescription']    = $row['mdescription']; 			//当前库存
			$v[$arr]['num']    = $row['num'];
			//$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			//$v[$arr]['discountRate']  = 0;
			//$v[$arr]['id']            = intval($row['id']);
			//$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['mainUnit']          = $row['mainUnit'];
			$v[$arr]['price']          = $row['price'];
			$v[$arr]['amount']        = $row['amount'];
			$v[$arr]['duiwu']        = $row['duiwu'];
			$v[$arr]['beizhu']  = $row['beizhu'];

			/*$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];*/

		}



		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_xiangmu($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		//print_r($v);
		//exit();
		die(json_encode($json));

	}

	//商品列表 领料单物资库
	public function orderslist() {
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);

		$where = $this->getorderslistwhere();

		$list = $this->data_model->get_stocks($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		// 仅对本页物料批量补仓储库存，避免主列表 JOIN
		$cangkuMap = $this->buildCangkuInventoryMap($list);
		//print_r($this->db->last_query());
		foreach ($list as $arr=>$row) {
		     // $v[$arr]['amount']        = (float)$row['iniamount'];
			$v[$arr]['id']       = $row['id'];
			$v[$arr]['goodsnumber']       = $row['goodsnumber'];
			$v[$arr]['mdescription']  = $row['mdescription'];
			$v[$arr]['inventoryNew']    = $row['inventoryNew'];
			$v[$arr]['inventoryOld']    = $row['inventoryOld']; 			//当前库存
			$v[$arr]['inventorya']    = $row['number'];
			//$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			//$v[$arr]['discountRate']  = 0;
			//$v[$arr]['id']            = intval($row['id']);
			//$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['mainUnit']          = $row['mainUnit'];
			$v[$arr]['ordernumber']          = $row['ordernumber'];
			$v[$arr]['price']        = $row['price'];
			$v[$arr]['amount']        = $row['number']*$row['price'];
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['Arrivaltime']  = $row['Arrivaltime'];
			$v[$arr]['flagtime']  = $row['flagtime'];
			$v[$arr]['flagcontact']  = $row['flagcontact'];
			$v[$arr]['beizhu']  = $row['beizhu'];
			$v[$arr]['daohuo']  = $row['daohuo'];
			$cangkuKey = $row['goodsnumber']."\t".$row['ordernumber'];
			$v[$arr]['cangkuInventory'] = number_format(isset($cangkuMap[$cangkuKey]) ? floatval($cangkuMap[$cangkuKey]) : 0, 3, '.', '');
			if($row['flagNo']=="未到货"){
				$v[$arr]['flagNo']  = "<span style='color:red;'>".$row['flagNo']."</span>";
			}else{
				$v[$arr]['flagNo']  = $row['flagNo'];
			}

			/*$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];*/

		}



		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_stocks($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		//print_r($v);
		//exit();
		die(json_encode($json));

	}

	/**
	 * 按物料编号+采购订单号汇总仓储物资库库存（仅查本页/导出涉及的物料，不 JOIN 主列表）
	 */
	private function buildCangkuInventoryMap($list) {
		$map = array();
		if (empty($list) || !is_array($list)) {
			return $map;
		}
		$goodsnumbers = array();
		foreach ($list as $row) {
			if (!empty($row['goodsnumber'])) {
				$goodsnumbers[$row['goodsnumber']] = 1;
			}
		}
		$goodsnumbers = array_keys($goodsnumbers);
		if (empty($goodsnumbers)) {
			return $map;
		}
		$rows = $this->db->select('goodsnumber,ordernumber,inventoryNew')
			->where('isDelete', 0)
			->where_in('goodsnumber', $goodsnumbers)
			->get('cangku')
			->result_array();
		if (!is_array($rows)) {
			return $map;
		}
		foreach ($rows as $r) {
			$key = $r['goodsnumber']."\t".$r['ordernumber'];
			if (!isset($map[$key])) {
				$map[$key] = 0;
			}
			$map[$key] += floatval($r['inventoryNew']);
		}
		return $map;
	}

    public function getorderslistwhere()
    {
        $skey = str_enhtml($this->input->get_post('skey',TRUE));
        $skey=$skey=='按商品编号查询'?'':$skey;
        $ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
        $ordernumber=$ordernumber=='按订单编号查询'?'':$ordernumber;
        $mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
        $mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
        $Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
        $Arrivaltime=$Arrivaltime=='按申请时间查询'?'':$Arrivaltime;
        $beizhu = str_enhtml($this->input->get_post('beizhu',TRUE));
        $beizhu=$beizhu=='按项目备注查询'?'':$beizhu;
        $cangkuwei = str_enhtml($this->input->get_post('cangkuwei',TRUE));
        $cangkuwei=$cangkuwei=='按仓库位置查询'?'':$cangkuwei;
        $gongyinshang = str_enhtml($this->input->get_post('gongyinshang',TRUE));
        $gongyinshang=$gongyinshang=='按供应商查询'?'':$gongyinshang;
        $flagNo = str_enhtml($this->input->get_post('flagNo',TRUE));
        $flagNo=$flagNo=='全部'?'':$flagNo;
        $chec = str_enhtml($this->input->get_post('chec',TRUE));
        $chec=$chec==''?1:$chec;
        $tmparray = explode("*",$mdescription);
        $categoryid   = intval($this->input->get_post('assistId',TRUE));
        $barCode = intval($this->input->get_post('barCode',TRUE));
        if($chec==1){

            $where = '(a.isDelete=0 and (a.inventoryOld != a.daohuo or (a.inventoryOld = a.daohuo and a.inventoryNew > 0)))';
        }else{
            $where = '(a.isDelete=0)';
        }

        $where .= $skey ? ' and a.goodsnumber="'.$skey.'"': '';
        $where .= $ordernumber ? ' and a.ordernumber like "'.$ordernumber.'%"': '';
        $where .= $Arrivaltime ? ' and a.Arrivaltime like "%'.$Arrivaltime.'%"': '';
        $where .= $beizhu ? ' and a.beizhu like "%'.$beizhu.'%"': '';
        $where .= $cangkuwei ? ' and a.locationName like "%'.$cangkuwei.'%"': '';
        $where .= $gongyinshang ? ' and a.flagcontact like "%'.$gongyinshang.'%"': '';
        $where .= $flagNo ? ' and a.flagNo="'.$flagNo.'"': '';
        if(count($tmparray)>1){

            $where .= $mdescription ? ' and (mdescription like "%'.$tmparray[0].'%" and mdescription like "%'.$tmparray[1].'%")' : '';
            // echo $where."===";
            // exit();

        } else{

            $where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
        }

        //$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
        if ($categoryid > 0) {
            $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
            if (count($cid)>0) {
                $cid = join(',',$cid);
                $where .= ' and categoryid in('.$cid.')';
            }
        }
        return $where;
    }
	//商品列表
	public function cangkulist() {
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按商品编号查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按订单编号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$Arrivaltime=$Arrivaltime=='按申请时间查询'?'':$Arrivaltime;
		$beizhu = str_enhtml($this->input->get_post('beizhu',TRUE));
		$beizhu=$beizhu=='按项目备注查询'?'':$beizhu;
		$cangkuwei = str_enhtml($this->input->get_post('cangkuwei',TRUE));
		$cangkuwei=$cangkuwei=='按仓库位置查询'?'':$cangkuwei;
		$gongyinshang = str_enhtml($this->input->get_post('gongyinshang',TRUE));
		$gongyinshang=$gongyinshang=='按供应商查询'?'':$gongyinshang;
		$chec = str_enhtml($this->input->get_post('chec',TRUE));
		$chec=$chec==''?1:$chec;
		$flagNo = str_enhtml($this->input->get_post('flagNo',TRUE));
		if($flagNo==""){
			$flagNo="已到货";

		}else{
			$flagNo=$flagNo=='全部'?'':$flagNo;
		}

		$tmparray = explode("*",$mdescription);
		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));

		if($chec==1){

            $where = '(a.isDelete=0 and (a.inventoryOld != a.daohuo or (a.inventoryOld = a.daohuo and a.inventoryNew > 0)))';
        }else{
			$where = '(a.isDelete=0)';
		}
		$where .= $skey ? ' and a.goodsnumber="'.$skey.'"': '';
		$where .= $ordernumber ? ' and a.ordernumber like "'.$ordernumber.'%"': '';
		$where .= $Arrivaltime ? ' and a.Arrivaltime like "%'.$Arrivaltime.'%"': '';
		$where .= $beizhu ? ' and a.beizhu like "%'.$beizhu.'%"': '';
		$where .= $cangkuwei ? ' and a.locationName like "%'.$cangkuwei.'%"': '';
		$where .= $gongyinshang ? ' and a.flagcontact like "%'.$gongyinshang.'%"': '';
		if($flagNo == '已到货|部分到货'){
            $where .= " and (a.flagNo='已到货' or a.flagNo='部分到货')";
        }else{
            $where .= $flagNo ? ' and a.flagNo="'.$flagNo.'"': '';
        }
		if(count($tmparray)>1){

			  $where .= $mdescription ? ' and (mdescription like "%'.$tmparray[0].'%" and mdescription like "%'.$tmparray[1].'%")' : '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		  }

		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_cangku($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		//print_r($this->db->last_query());
		foreach ($list as $arr=>$row) {
		     // $v[$arr]['amount']        = (float)$row['iniamount'];
			$v[$arr]['id']       = $row['id'];
			$v[$arr]['goodsnumber']       = $row['goodsnumber'];
			$v[$arr]['mdescription']  = $row['mdescription'];
			$v[$arr]['inventoryNew']    = $row['inventoryNew'];
			$v[$arr]['inventoryOld']    = $row['inventoryOld']; 			//当前库存
			$v[$arr]['inventorya']    = $row['number'];
			//$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			//$v[$arr]['discountRate']  = 0;
			//$v[$arr]['id']            = intval($row['id']);
			//$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['mainUnit']          = $row['mainUnit'];
			$v[$arr]['ordernumber']          = $row['ordernumber'];
			$v[$arr]['price']        = $row['price'];
			$v[$arr]['amount']        = $row['number']*$row['price'];
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['Arrivaltime']  = $row['Arrivaltime'];
			$v[$arr]['flagtime']  = $row['flagtime'];
			$v[$arr]['flagcontact']  = $row['flagcontact'];
			$v[$arr]['beizhu']  = $row['beizhu'];
			$v[$arr]['daohuo']  = $row['daohuo'];
			if($row['flagNo']=="未到货"){
				$v[$arr]['flagNo']  = "<span style='color:red;'>".$row['flagNo']."</span>";
			}else{
				$v[$arr]['flagNo']  = $row['flagNo'];
			}

			/*$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];*/

		}



		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_cangku($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		//print_r($v);
		//exit();
		die(json_encode($json));

	}

	//商品列表
	public function cangkulisthuizong() {
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按商品编号查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按订单编号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$Arrivaltime=$Arrivaltime=='按出库时间查询'?'':$Arrivaltime;
		$sign = str_enhtml($this->input->get_post('sign',TRUE));
		$sign=$sign=='按领料人查询'?'':$sign;
		$beizhu = str_enhtml($this->input->get_post('beizhu',TRUE));
		$beizhu=$beizhu=='按项目备注查询'?'':$beizhu;
        $flagcontact = str_enhtml($this->input->get_post('flagcontact',TRUE));
        $flagcontact=$flagcontact=='按供应商查询'?'':$flagcontact;
        //$flagNo = str_enhtml($this->input->get_post('flagNo',TRUE));
		//$flagNo=$flagNo=='全部'?'':$flagNo;
		$tmparray = explode("*",$mdescription);
		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));
        // 状态筛选
        $receiveStatus = str_enhtml($this->input->get_post('reveiveStatus',TRUE));

		$where = '(a.isDelete=0)';
		$where .= $skey ? ' and a.goodsnumber like "%'.$skey.'%"': '';
		$where .= $ordernumber ? ' and a.ordernumber like "%'.$ordernumber.'%"': '';
		$where .= $Arrivaltime ? ' and a.flagtime like "%'.$Arrivaltime.'%"': '';
		$where .= $sign ? ' and a.sign like "%'.$sign.'%"': '';
		$where .= $beizhu ? ' and a.beizhu like "%'.$beizhu.'%"': '';
		$where .= $flagcontact ? ' and a.flagcontact like "%'.$flagcontact.'%"': '';
		//$where .= $flagNo ? ' and a.flagNo="'.$flagNo.'"': '';
        $where .= $receiveStatus !== '' ? ' and a.receive_status="'.$receiveStatus.'"': '';
        if(count($tmparray)>1){

			  $where .= $mdescription ? ' and (mdescription like "%'.$tmparray[0].'%" and mdescription like "%'.$tmparray[1].'%")' : '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		  }

		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_cangkuhuizong($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		foreach ($list as $arr=>$row) {
		     // $v[$arr]['amount']        = (float)$row['iniamount'];
			$v[$arr]['id']       = $row['id'];
			$v[$arr]['goodsnumber']       = $row['goodsnumber'];
			$v[$arr]['mdescription']  = $row['mdescription'];
			$v[$arr]['inventoryNews']    = $row['inventoryNew'];
			$v[$arr]['inventoryOld']    = $row['inventoryOld']; 			//当前库存
			$v[$arr]['inventorya']    = $row['number'];
			//$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			//$v[$arr]['discountRate']  = 0;
			//$v[$arr]['id']            = intval($row['id']);
			//$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['mainUnit']          = $row['mainUnit'];
			$v[$arr]['ordernumber']          = $row['ordernumber'];
			$v[$arr]['price']        = $row['price'];
			$v[$arr]['sign']        = $row['sign'];
			$v[$arr]['amounta']        = $row['amount'];
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['Arrivaltime']  = $row['Arrivaltime'];
			$v[$arr]['flagtimes']  = $row['flagtime'];
			$v[$arr]['flagcontacts']  = $row['flagcontact'];
			$v[$arr]['beizhus']  = $row['beizhu'];
			if($row['flagNo']=="未出库"){
				$v[$arr]['flagNo']  = "<span style='color:red;'>".$row['flagNo']."</span>";
			}else{
				$v[$arr]['flagNo']  = $row['flagNo'];
			}
			$v[$arr]['receive_status'] = $row['receive_status'];
			$v[$arr]['receive_time'] = $row['receive_time'];
			$v[$arr]['receive_note'] = $row['receive_note'];

			/*$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];*/

		}


		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_cangkuhuizong($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		//print_r($v);
		//exit();
		die(json_encode($json));

	}


	public function addstock(){
		$mdescription = $this->input->get_post('mdescription',TRUE);
		$goodsnumber = $this->input->get_post('goodsnumber',TRUE);
		$inventoryNew = $this->input->get_post('inventoryNew',TRUE);
		$inventoryOld = $this->input->get_post('inventoryOld',TRUE);
		$number = $this->input->get_post('number',TRUE);
		$mainUnit = $this->input->get_post('mainUnit',TRUE);
		$ordernumber = $this->input->get_post('ordernumber',TRUE);
		$price = $this->input->get_post('price',TRUE);
		$amount = $this->input->get_post('amount',TRUE);
		$locationName = $this->input->get_post('locationName',TRUE);
		$Arrivaltime = $this->input->get_post('Arrivaltime',TRUE);
		$flagNo = $this->input->get_post('flagNo',TRUE);
		$flagtime = $this->input->get_post('flagtime',TRUE);
		$flagcontact = $this->input->get_post('flagcontact',TRUE);
		$beizhu = $this->input->get_post('beizhu',TRUE);
		$daohuo = $this->input->get_post('daohuo',TRUE);
		$data=array(
			'mdescription'=>$mdescription,
			'goodsnumber'=>$goodsnumber,
			'inventoryOld'=>$inventoryOld,
			'inventoryNew'=>$inventoryNew,
			'number'=>$number,
			'mainUnit'=>$mainUnit,
			'ordernumber'=>$ordernumber,
			'price'=>$price,
			'amount'=>$amount,
			'locationName'=>$locationName,
			'Arrivaltime'=>$Arrivaltime,
			'flagtime'=>$flagtime,
			'flagcontact'=>$flagcontact,
			'beizhu'=>$beizhu,
			'flagNo'=>$flagNo,
			'daohuo'=>$daohuo
		);

        $count_stock = $this->mysql_model->get_count('stock',array('goodsnumber'=>"$goodsnumber",'ordernumber'=>"$ordernumber",'isDelete'=>0));
        $count_cangku = $this->mysql_model->get_count('cangku',array('goodsnumber'=>"$goodsnumber",'ordernumber'=>"$ordernumber",'isDelete'=>0));
        if($count_stock >0 || $count_cangku>0){
            $json['status'] = 300;
            $json['msg']    = '存在重复项，请检查！';
            die(json_encode($json));
        }else{
            $this->db->trans_start();
            $this->db->insert('ci_cangku',$data);
            $this->db->insert('ci_stock',$data);
            $bool = $this->db->trans_complete();
            if($bool){
                $json['status'] = 200;
                $json['msg']    = 'success';
                die(json_encode($json));
            }
        }
	}
	public function addcangku(){
		$mdescription = $this->input->get_post('mdescription',TRUE);
		$goodsnumber = $this->input->get_post('goodsnumber',TRUE);
		$inventoryNew = $this->input->get_post('inventoryNew',TRUE);
		$inventoryOld = $this->input->get_post('inventoryOld',TRUE);
		$number = $this->input->get_post('number',TRUE);
		$mainUnit = $this->input->get_post('mainUnit',TRUE);
		$ordernumber = $this->input->get_post('ordernumber',TRUE);
		$price = $this->input->get_post('price',TRUE);
		$amount = $this->input->get_post('amount',TRUE);
		$locationName = $this->input->get_post('locationName',TRUE);
		$Arrivaltime = $this->input->get_post('Arrivaltime',TRUE);
		$flagNo = $this->input->get_post('flagNo',TRUE);
		$flagtime = $this->input->get_post('flagtime',TRUE);
		$flagcontact = $this->input->get_post('flagcontact',TRUE);
		$beizhu = $this->input->get_post('beizhu',TRUE);
		$daohuo = $this->input->get_post('daohuo',TRUE);
		$data=array(
			'mdescription'=>$mdescription,
			'goodsnumber'=>$goodsnumber,
			'inventoryOld'=>$inventoryOld,
			'inventoryNew'=>$inventoryNew,
			'number'=>$number,
			'mainUnit'=>$mainUnit,
			'ordernumber'=>$ordernumber,
			'price'=>$price,
			'amount'=>$amount,
			'locationName'=>$locationName,
			'Arrivaltime'=>$Arrivaltime,
			'flagtime'=>$flagtime,
			'flagcontact'=>$flagcontact,
			'beizhu'=>$beizhu,
			'flagNo'=>$flagNo,
			'daohuo'=>$daohuo


		);

        $count_stock = $this->mysql_model->get_count('stock',array('goodsnumber'=>"$goodsnumber",'ordernumber'=>"$ordernumber",'isDelete'=>0));
        $count_cangku = $this->mysql_model->get_count('cangku',array('goodsnumber'=>"$goodsnumber",'ordernumber'=>"$ordernumber",'isDelete'=>0));
        if($count_stock >0 || $count_cangku>0){
            $json['status'] = 300;
            $json['msg']    = '存在重复项，请检查！';
            die(json_encode($json));
        }else{
            $this->db->trans_start();
            $this->db->insert('ci_cangku',$data);
            $this->db->insert('ci_stock',$data);
            $bool = $this->db->trans_complete();
            if($bool){
                $json['status'] = 200;
                $json['msg']    = 'success';
                die(json_encode($json));
            }
        }
	}

	/*public function addcangku(){
		$mdescription = $this->input->get_post('mdescription',TRUE);
		$goodsnumber = $this->input->get_post('goodsnumber',TRUE);
		$inventoryNew = $this->input->get_post('inventoryNew',TRUE);
		$inventoryOld = $this->input->get_post('inventoryOld',TRUE);
		$number = $this->input->get_post('number',TRUE);
		$mainUnit = $this->input->get_post('mainUnit',TRUE);
		$ordernumber = $this->input->get_post('ordernumber',TRUE);
		$price = $this->input->get_post('price',TRUE);
		$amount = $this->input->get_post('amount',TRUE);
		$locationName = $this->input->get_post('locationName',TRUE);
		$Arrivaltime = $this->input->get_post('Arrivaltime',TRUE);
		//$flagNo = $this->input->get_post('flagNo',TRUE);
		//$flagtime = $this->input->get_post('flagtime',TRUE);
		//$flagcontact = $this->input->get_post('flagcontact',TRUE);
		$beizhu = $this->input->get_post('beizhu',TRUE);
		$data=array(
			'mdescription'=>$mdescription,
			'goodsnumber'=>$goodsnumber,
			'inventoryOld'=>$inventoryOld,
			'inventoryNew'=>$inventoryNew,
			'number'=>$number,
			'mainUnit'=>$mainUnit,
			'ordernumber'=>$ordernumber,
			'price'=>$price,
			'amount'=>$amount,
			'locationName'=>$locationName,
			'Arrivaltime'=>$Arrivaltime,
			//'flagtime'=>$flagtime,
			//'flagcontact'=>$flagcontact,
			'beizhu'=>$beizhu,
			//'flagNo'=>$flagNo,
		);
		$bool=$this->db->insert('ci_cangku',$data);
		if($bool){
			$json['status'] = 200;
			$json['msg']    = 'success';
			die(json_encode($json));
		}
	}*/
	//商品列表
	public function order() {
		$type   = $this->input->get('dingdanhao',TRUE);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$tmparray = explode("*",$skey);
		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));
		//$where = '(a.isDelete=0)';
        $where = '';
		$where .= $type ? ' a.ordernumber="'.$type.'"': '';

		if(count($tmparray)>1){

			  $where .= $skey ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $skey ? ' and (a.mdescription like "%'.$skey.'%" or a.goodsnumber like "%'.$skey.'%")' : '';
		  }


		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_goodsorder($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		//print_r($this->db->last_query());
		foreach ($list as $arr=>$row) {
		    $v[$arr]['amount']        = (float)$row['amount'];
			$v[$arr]['barCode']       = $row['barCode'];
			$v[$arr]['categoryName']  = $row['categoryName'];
			$v[$arr]['currentQty']    = $row['totalqty'];                            //当前库存
			$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			$v[$arr]['discountRate']  = 0;
			$v[$arr]['id']            = intval($row['id']);
			$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['josl']          = $row['josl'];
			$v[$arr]['name']          = $row['name'];
			$v[$arr]['number']        = $row['goodsnumber'];
			$v[$arr]['orderid']        = $row['ordernumber'];
			$v[$arr]['mdescription']        = $row['mdescription'];
			$v[$arr]['mainUnit']        = $row['mainUnit'];
			$v[$arr]['price']        = $row['price'];
			$v[$arr]['pinYin']        = $row['pinYin'];
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];

		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_goodsorder($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		die(json_encode($json));

	}

	//商品列表
	public function ordergoods() {

		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$tmparray = explode("*",$skey);
		$locationName = $this->db->escape_str(trim($this->input->get_post('locationName',TRUE)));
		$categoryid   = intval($this->input->get_post('assistId',TRUE));
		$barCode = intval($this->input->get_post('barCode',TRUE));
		$where = "(a.isDelete=0 and (a.flagNo='已到货' or a.flagNo='部分到货')  and a.inventoryNew>0 )";

		//$where .= $type ? ' a.ordernumber="'.$type.'"': '';

		if(count($tmparray)>1){

			  $where .= $skey ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
			 // echo $where."===";
			 // exit();

		   } else{

			$where .= $skey ? ' and (a.mdescription like "%'.$skey.'%" or a.goodsnumber like "%'.$skey.'%" or a.ordernumber like "%'.$skey.'%" or a.beizhu like "%'.$skey.'%")' : '';
		  }
		if ($locationName !== '') {
			$where .= ' and a.locationName like "%'.$this->db->escape_like_str($locationName).'%"';
		}

		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}
		$list = $this->data_model->get_goodsorder($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);
		//print_r($list);
		//exit();
		foreach ($list as $arr=>$row) {
		    $v[$arr]['amount']        = (float)$row['amount'];
			$v[$arr]['barCode']       = $row['barCode'];
			$v[$arr]['categoryName']  = $row['categoryName'];
			$v[$arr]['currentQty']    = $row['totalqty'];                            //当前库存
			$v[$arr]['delete']        = intval($row['disable'])==1 ? true : false;   //是否禁用
			$v[$arr]['discountRate']  = 0;
			$v[$arr]['id']            = intval($row['id']);
			$v[$arr]['isSerNum']      = intval($row['isSerNum']);
			$v[$arr]['josl']          = $row['josl'];
			$v[$arr]['name']          = $row['name'];
			$v[$arr]['number']        = $row['goodsnumber'];
			$v[$arr]['orderid']        = $row['ordernumber'];
			$v[$arr]['mdescription']        = $row['mdescription'];
			$v[$arr]['mainUnit']        = $row['mainUnit'];
			$v[$arr]['inventoryNew']        = $row['inventoryNew'];
			$v[$arr]['beizhu']        = $row['beizhu'];
			$v[$arr]['price']        = $row['price'];
			$v[$arr]['pinYin']        = $row['pinYin'];
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['locationNo']    = '';
			$v[$arr]['purPrice']      = $row['purPrice'];
			$v[$arr]['quantity']      = $row['iniqty'];
			$v[$arr]['salePrice']     = $row['salePrice'];
			$v[$arr]['skuClassId']    = $row['skuClassId'];
			$v[$arr]['spec']          = $row['spec'];
			$v[$arr]['unitCost']      = $row['iniunitCost'];
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['remark']        = $row['remark'];

		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_goodsorder($where,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? $v :'';
		die(json_encode($json));

	}

	//商品选择 最近出售价格
	public function listBySelected() {
	    $arr = array('so'=>150601,'sa'=>150501);
	    $contactid = intval($this->input->post('contactId',TRUE));
		$type = str_enhtml($this->input->post('type',TRUE));
		$id   = intval($this->input->post('ids',TRUE));
		$list = $this->data_model->get_invoice_info('a.isDelete=0 and transType='.$arr[$type].' and a.invId='.$id.' and a.buId='.$contactid.' limit 0,3',2);
		foreach ($list as $arr=>$row) {
		    $v[$arr]['advanceDays']  = 0;
		    $v[$arr]['amount']       = (float)$row['amount'];
			$v[$arr]['barCode']      = '';
			$v[$arr]['categoryName'] = '';
			$v[$arr]['currentQty']   = 0;
			$v[$arr]['delete']       = false;
			$v[$arr]['discountRate'] = 0;
			$v[$arr]['id']           = intval($row['invId']);
			$v[$arr]['isSerNum']     = 0;
			$v[$arr]['isWarranty']   = 0;
			$v[$arr]['josl']         = '';
			$v[$arr]['locationId']   = intval($row['locationId']);
			$v[$arr]['locationName'] = $row['locationName'];
			$v[$arr]['locationNo']   = $row['locationNo'];
			$v[$arr]['name']         = $row['invName'];
			$v[$arr]['nearPrice']    = $row['price'];
			$v[$arr]['number']       = $row['invNumber'];
			$v[$arr]['pinYin']       = $row['pinYin'];
			$v[$arr]['purPrice']     = $row['purPrice'];
			$v[$arr]['quantity']     = $row['quantity'];
			$v[$arr]['salePrice']    = $row['salePrice'];
			$v[$arr]['skuClassId']   = 0;
			$v[$arr]['skuId']        = 0;
			$v[$arr]['skuName']      = 0;
			$v[$arr]['skuNumber']    = 0;
			$v[$arr]['spec']         = $row['invSpec'];
			$v[$arr]['unitCost']     = 0;
			$v[$arr]['unitId']       = intval($row['unitId']);
			$v[$arr]['unitName']     = $row['mainUnit'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['result']      = isset($v) ? $v : array();
		die(json_encode($json));
	}


	//获取信息
	public function query() {
	    $id = intval($this->input->post('id',TRUE));
		str_alert(200,'success',$this->get_goods_info($id));
	}


	//检测编号
	public function getNextNo() {
		$skey = str_enhtml($this->input->post('skey',TRUE));
		$this->mysql_model->get_count('goods',array('isDelete'=>0,'number'=>$skey)) > 0 && str_alert(-1,'商品编号已经存在');
		str_alert(200,'success');
	}

	//检测条码
	public function checkBarCode() {
		 $barCode = str_enhtml($this->input->post('barCode',TRUE));
		 $this->mysql_model->get_count('goods',array('isDelete'=>0,'barCode'=>$barCode)) > 0 && str_alert(-1,'商品条码已经存在');
		 str_alert(200,'success');
	}

	//检测规格
	public function checkSpec() {
		 $spec = str_enhtml($this->input->post('spec',TRUE));
		 $this->mysql_model->get_count('assistsku',array('isDelete'=>0,'skuName'=>$spec)) > 0 && str_alert(-1,'商品规格已经存在');
		 str_alert(200,'success');
	}

	//检测名称
	public function checkname() {
		 $skey = str_enhtml($this->input->post('barCode',TRUE));
		 echo '{"status":200,"msg":"success","data":{"number":""}}';
	}

	//获取图片信息
	public function getImagesById() {
	    $id = intval($this->input->post('id',TRUE));
	    $list = $this->mysql_model->get_results('goods_img',array('isDelete'=>0,'invId'=>$id));
		foreach ($list as $arr=>$row) {
		    $v[$arr]['pid']          = $row['id'];
			$v[$arr]['status']       = 1;
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['url']          = site_url().'/basedata/inventory/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['thumbnailUrl'] = site_url().'/basedata/inventory/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['deleteUrl']    = '';
			$v[$arr]['deleteType']   = '';
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = isset($v) ? $v : array();
		die(json_encode($json));
	}

	//上传图片信息
	public function uploadImages() {
	    require_once './application/libraries/UploadHandler.php';
		$config = array(
			'script_url' => base_url().'inventory/uploadimages',
			'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/data/upfile/goods/',
			'upload_url' => base_url().'data/upfile/goods/',
			'delete_type' =>'',
			'print_response' =>false
		);
		$uploadHandler = new UploadHandler($config);
		$list  = (array)json_decode(json_encode($uploadHandler->response['files'][0]), true);
		$info  = elements(array('name','size','type','url','thumbnailUrl','deleteUrl','deleteType'),$list,NULL);
		$newid = $this->mysql_model->insert('goods_img',$info);
		$files[0]['pid']          = intval($newid);
		$files[0]['status']       = 1;
		$files[0]['size']         = (float)$list['size'];
		$files[0]['name']         = $list['name'];
		$files[0]['url']          = site_url().'/basedata/inventory/getImage?action=getImage&pid='.$newid;
		$files[0]['thumbnailUrl'] = site_url().'/basedata/inventory/getImage?action=getImage&pid='.$newid;
		$files[0]['deleteUrl']    = '';
		$files[0]['deleteType']   = '';
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = $files;
        die(json_encode($json));
	}

	//保存上传图片信息
	public function addImagesToInv() {
	    $data = $this->input->post('postData');
		if (strlen($data)>0) {
		    $v = $s = array();
		    $data = (array)json_decode($data, true);
			$id   = isset($data['id']) ? $data['id'] : 0;
		    !isset($data['files']) || count($data['files']) < 1 && str_alert(-1,'请先添加图片！');
			foreach($data['files'] as $arr=>$row) {
			    if ($row['status']==1) {
					$v[$arr]['id']       = $row['pid'];
					$v[$arr]['invId']    = $id;
				} else {
				    $s[$arr]['id']       = $row['pid'];
					$s[$arr]['invId']    = $id;
					$s[$arr]['isDelete'] = 1;
				}
			}
			$this->mysql_model->update('goods_img',array_values($v),'id');
			$this->mysql_model->update('goods_img',array_values($s),'id');
			str_alert(200,'success');
	    }
		str_alert(-1,'保存失败');
	}

	//获取图片信息
	public function getImage() {
	    $id = intval($this->input->get_post('pid',TRUE));
	    $data = $this->mysql_model->get_rows('goods_img',array('id'=>$id));
		if (count($data)>0) {
		    $url     = './data/upfile/goods/'.$data['name'];
			$info    = getimagesize($url);
			$imgdata = fread(fopen($url,'rb'),filesize($url));
			header('content-type:'.$info['mime'].'');
			echo $imgdata;
		}
	}

	//新增
	public function add(){
		$this->common_model->checkpurview(69);
		$data = $this->input->post(NULL,TRUE);
		if ($data) {
			$data = $this->validform($data);
			$this->mysql_model->get_count('goods',array('isDelete'=>0,'number'=>$data['number'])) > 0 && str_alert(-1,'商品编号重复');
			$this->db->trans_begin();
			$info = array(
			    'barCode','baseUnitId','unitName','categoryId','categoryName','propertys',
				'discountRate1','discountRate2','highQty','locationId','pinYin',
				'locationName','lowQty','name','number','purPrice','warehouseWarning',
				'remark','salePrice','spec','vipPrice','wholesalePrice','warehousePropertys'
			);
			$info = elements($info,$data,NULL);
			$data['id'] = $this->mysql_model->insert('goods',$info);
			if (strlen($data['propertys'])>0) {
				$list = (array)json_decode($data['propertys'],true);
				foreach ($list as $arr=>$row) {
					$v[$arr]['invId']         = $data['id'];
					$v[$arr]['locationId']    = intval($row['locationId']);
					$v[$arr]['qty']           = (float)$row['quantity'];
					$v[$arr]['price']         = (float)$row['unitCost'];
					$v[$arr]['amount']        = (float)$row['amount'];
					$v[$arr]['skuId']         = intval($row['skuId']);
					$v[$arr]['billDate']      = date('Y-m-d');;
					$v[$arr]['billNo']        = '期初数量';
					$v[$arr]['billType']      = 'INI';
					$v[$arr]['transTypeName'] = '期初数量';
				}
				if (isset($v)) {
					$this->mysql_model->insert('invoice_info',$v);
				}
			}
			if (strlen($data['warehousePropertys'])>0) {
			    $list = (array)json_decode($data['warehousePropertys'],true);
				foreach ($list as $arr=>$row) {
					$s[$arr]['invId']         = $data['id'];
					$s[$arr]['locationId']    = intval($row['locationId']);
					$s[$arr]['highQty']       = (float)$row['highQty'];
					$s[$arr]['lowQty']        = (float)$row['lowQty'];
				}
				if (isset($s)) {
					$this->mysql_model->insert('warehouse',$s);
				}
			}
            if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚');
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增商品:'.$data['name']);
				str_alert(200,'success',$data);
			}
		}
		str_alert(-1,'添加失败');
	}
	public function updateorders(){
		$id =$this->input->get_post('id',TRUE);
		$query=$this->db->query("select * from ci_stock where id='$id'");
		$list = $query->result_array();
		foreach ($list as $arr=>$row) {
			$json['id']       = $row['id'];
			$json['ordernumber']       = $row['ordernumber'];
			$json['goodsnumber']            = $row['goodsnumber'];
			$json['inventoryNew']        =$row['inventoryNew'];
			$json['inventoryOld']        =$row['inventoryOld'];
			$json['number']        =$row['number'];
			$json['mdescription']          = $row['mdescription'];
			$json['mainUnit']  = $row['mainUnit'];
			$json['price']        = $row['price'];
			$json['amount']  = $row['amount'];
			$json['locationName']      = $row['locationName'];
			$json['Arrivaltime']      = $row['Arrivaltime'];
			$json['flagNo']      = $row['flagNo'];
			$json['flagtime']      = $row['flagtime'];
			$json['flagcontact']      = $row['flagcontact'];
			$json['beizhu']      = $row['beizhu'];
			$json['daohuo']      = $row['daohuo'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		die(json_encode($json));
	}

	public function updateorderscangku(){
		$id =$this->input->get_post('id',TRUE);
		$query=$this->db->query("select * from ci_cangku where id='$id'");
		$list = $query->result_array();
		foreach ($list as $arr=>$row) {
			$json['id']       = $row['id'];
			$json['ordernumber']       = $row['ordernumber'];
			$json['goodsnumber']            = $row['goodsnumber'];
			$json['inventoryNew']        =$row['inventoryNew'];
			$json['inventoryOld']        =$row['inventoryOld'];
			$json['number']        =$row['number'];
			$json['mdescription']          = $row['mdescription'];
			$json['mainUnit']  = $row['mainUnit'];
			$json['price']        = $row['price'];
			$json['amount']  = $row['amount'];
			$json['locationName']      = $row['locationName'];
			$json['Arrivaltime']      = $row['Arrivaltime'];
			$json['flagNo']      = $row['flagNo'];
			$json['flagtime']      = $row['flagtime'];
			$json['flagcontact']      = $row['flagcontact'];
			$json['beizhu']      = $row['beizhu'];
			$json['daohuo']      = $row['daohuo'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		die(json_encode($json));
	}
	public function updatexiangmu(){
		$id =$this->input->get_post('id',TRUE);
		$query=$this->db->query("select * from ci_xiangmuku where id='$id'");
		$list = $query->result_array();
		foreach ($list as $arr=>$row) {
			$json['id']       = $row['id'];
			$json['name']       = $row['name'];
			$json['ordernumber']            = $row['ordernumber'];
			$json['number']        =$row['number'];
			$json['mdescription']        =$row['mdescription'];
			$json['num']        =$row['num'];
			$json['mainUnit']          = $row['mainUnit'];
			$json['price']  = $row['price'];
			$json['amount']        = $row['amount'];
			$json['duiwu']  = $row['duiwu'];
			$json['beizhu']      = $row['beizhu'];

		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		die(json_encode($json));
	}

	public function updatexiangmus(){
		$id =$this->input->get_post('id',TRUE);
		$name =$this->input->get_post('name',TRUE);
		$ordernumber =$this->input->get_post('ordernumber',TRUE);
		$number =$this->input->get_post('number',TRUE);
		$mdescription =$this->input->get_post('mdescription',TRUE);
		$num =$this->input->get_post('num',TRUE);
		$mainUnit =$this->input->get_post('mainUnit',TRUE);
		$price =$this->input->get_post('price',TRUE);
		$amount =$this->input->get_post('amount',TRUE);
		$duiwu =$this->input->get_post('duiwu',TRUE);
		$beizhu =$this->input->get_post('beizhu',TRUE);
		$data=array(
			'name'=>$name,
			'ordernumber'=>$ordernumber,
			'number'=>$number,
			'mdescription'=>$mdescription,
			'num'=>$num,
			'mainUnit'=>$mainUnit,
			'price'=>$price,
			'amount'=>$amount,
			'duiwu'=>$duiwu,
			'beizhu'=>$beizhu,
		);
	//	print_r($data);
		//exit();
		$bool=$this->db->update('ci_xiangmuku',$data,array('id'=>$id));//将数据库.user表里id=3的用户密码给为12345
		if($bool){
			$json['status'] = 200;
			$json['msg']    = 'success';
		}

		die(json_encode($json));
	}
	public function updatestock(){
		$id =$this->input->get_post('id',TRUE);
		$goodsnumber =$this->input->get_post('goodsnumber',TRUE);
		$mdescription =$this->input->get_post('mdescription',TRUE);
		$inventoryNew =$this->input->get_post('inventoryNew',TRUE);
		$inventoryOld =$this->input->get_post('inventoryOld',TRUE);
		$number =$this->input->get_post('number',TRUE);
		$mainUnit =$this->input->get_post('mainUnit',TRUE);
		$ordernumber =$this->input->get_post('ordernumber',TRUE);
		$price =$this->input->get_post('price',TRUE);
		$amount =$this->input->get_post('amount',TRUE);
		$locationName =$this->input->get_post('locationName',TRUE);
		$Arrivaltime =$this->input->get_post('Arrivaltime',TRUE);
		$flagNo =$this->input->get_post('flagNo',TRUE);
		$flagtime =$this->input->get_post('flagtime',TRUE);
		$flagcontact =$this->input->get_post('flagcontact',TRUE);
		$beizhu =$this->input->get_post('beizhu',TRUE);
		$daohuo =$this->input->get_post('daohuo',TRUE);
		$data=array(
			'goodsnumber'=>$goodsnumber,
			'mdescription'=>$mdescription,
			'inventoryNew'=>$inventoryNew,
			'inventoryOld'=>$inventoryOld,
			'number'=>$number,
			'mainUnit'=>$mainUnit,
			'ordernumber'=>$ordernumber,
			'price'=>$price,
			'amount'=>$amount,
			'locationName'=>$locationName,
			'Arrivaltime'=>$Arrivaltime,
			'flagNo'=>$flagNo,
			'flagtime'=>$flagtime,
			'flagcontact'=>$flagcontact,
			'beizhu'=>$beizhu,
			'daohuo'=>$daohuo,
		);
	//	print_r($data);
		//exit();
		$bool=$this->db->update('ci_stock',$data,array('id'=>$id));//将数据库.user表里id=3的用户密码给为12345
		if($bool){
			$json['status'] = 200;
			$json['msg']    = 'success';
		}

		die(json_encode($json));
	}

	public function updatcangku(){
		$id =$this->input->get_post('id',TRUE);
		$goodsnumber =$this->input->get_post('goodsnumber',TRUE);
		$mdescription =$this->input->get_post('mdescription',TRUE);
		$inventoryNew =$this->input->get_post('inventoryNew',TRUE);
		$inventoryOld =$this->input->get_post('inventoryOld',TRUE);
		$number =$this->input->get_post('number',TRUE);
		$mainUnit =$this->input->get_post('mainUnit',TRUE);
		$ordernumber =$this->input->get_post('ordernumber',TRUE);
		$price =$this->input->get_post('price',TRUE);
		$amount =$this->input->get_post('amount',TRUE);
		$locationName =$this->input->get_post('locationName',TRUE);
		$Arrivaltime =$this->input->get_post('Arrivaltime',TRUE);
		$flagNo =$this->input->get_post('flagNo',TRUE);
		$flagtime =$this->input->get_post('flagtime',TRUE);
		$flagcontact =$this->input->get_post('flagcontact',TRUE);
		$beizhu =$this->input->get_post('beizhu',TRUE);
		$daohuo =$this->input->get_post('daohuo',TRUE);
		$data=array(
			'goodsnumber'=>$goodsnumber,
			'mdescription'=>$mdescription,
			'inventoryNew'=>$inventoryNew,
			'inventoryOld'=>$inventoryOld,
			'number'=>$number,
			'mainUnit'=>$mainUnit,
			'ordernumber'=>$ordernumber,
			'price'=>$price,
			'amount'=>$amount,
			'locationName'=>$locationName,
			'Arrivaltime'=>$Arrivaltime,
			'flagNo'=>$flagNo,
			'flagtime'=>$flagtime,
			'flagcontact'=>$flagcontact,
			'beizhu'=>$beizhu,
			'daohuo'=>$daohuo,
		);
	//	print_r($data);
		//exit();
		$bool=$this->db->update('ci_cangku',$data,array('id'=>$id));//将数据库.user表里id=3的用户密码给为12345
		if($bool){
			$json['status'] = 200;
			$json['msg']    = 'success';
		}

		die(json_encode($json));
	}


	//修改
	public function update(){
		$this->common_model->checkpurview(70);
		$data = $this->input->post(NULL,TRUE);
		if ($data) {
			$data = $this->validform($data);
			$this->mysql_model->get_count('goods',array('id !='=>$data['id'],'isDelete'=>0,'number'=>$data['number'])) > 0 && str_alert(-1,'商品编号重复');
			$this->db->trans_begin();
			$info = array(
			    'barCode','baseUnitId','unitName','categoryId','categoryName','propertys',
				'discountRate1','discountRate2','highQty','locationId','pinYin',
				'locationName','lowQty','name','number','purPrice','warehouseWarning',
				'remark','salePrice','spec','vipPrice','wholesalePrice','warehousePropertys'
			);
			$info = elements($info, $data,NULL);
			$this->mysql_model->update('goods',$info,array('id'=>$data['id']));
			if (strlen($data['propertys'])>0) {
				$list = (array)json_decode($data['propertys'],true);
				foreach ($list as $arr=>$row) {
					$v[$arr]['invId']         = $data['id'];
					$v[$arr]['locationId']    = isset($row['locationId']) ? $row['locationId'] : 0;
					$v[$arr]['qty']           = isset($row['quantity']) ? $row['quantity']:0;
					$v[$arr]['price']         = isset($row['unitCost']) ? $row['unitCost']:0;
					$v[$arr]['amount']        = isset($row['amount']) ? $row['amount']:0;
					$v[$arr]['skuId']         = isset($row['skuId']) ? $row['skuId']:0;
					$v[$arr]['billDate']      = date('Y-m-d');
					$v[$arr]['billNo']        = '期初数量';
					$v[$arr]['billType']      = 'INI';
					$v[$arr]['transTypeName'] = '期初数量';
				}
				if (isset($v)) {
				    $this->mysql_model->delete('invoice_info',array('invId'=>$data['id'],'billType'=>'INI'));
					$this->mysql_model->insert('invoice_info',$v);
				}
			}
			if (strlen($data['warehousePropertys'])>0) {
			    $list = (array)json_decode($data['warehousePropertys'],true);
				foreach ($list as $arr=>$row) {
					$s[$arr]['invId']         = $data['id'];
					$s[$arr]['locationId']    = isset($row['locationId']) ? $row['locationId'] : 0;
					$s[$arr]['highQty']       = isset($row['highQty']) ? $row['highQty']:0;
					$s[$arr]['lowQty']        = isset($row['lowQty']) ? $row['lowQty']:0;
				}
				if (isset($s)) {
				    $this->mysql_model->delete('warehouse',array('invId'=>$data['id']));
					$this->mysql_model->insert('warehouse',$s);
				}
			}
            if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚');
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改商品:ID='.$data['id'].'名称:'.$data['name']);
				str_alert(200,'success',$this->get_goods_info($data['id']));
			}
		}
		str_alert(-1,'修改失败');
	}

	//删除
	public function delete(){
		$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('goods','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		    $this->mysql_model->get_count('invoice_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('goods',array('isDelete'=>1),'(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('删除商品:ID='.$id.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'删除成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'删除失败');
		}
	}
	//删除
	public function deleteorders(){
		$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('stock','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		    $this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('stock',array('isDelete'=>1),'(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('删除商品:ID='.$id.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'删除成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'删除失败');
		}
	}

	//删除
	public function deletecangku(){
		$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('cangku','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		    //$this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('cangku',array('isDelete'=>1),'(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('删除商品:ID='.$id.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'删除成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'删除失败');
		}
	}

	//删除
	public function deletecangkuhuizong(){
		$this->common_model->checkpurview(71);
		$ids = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('cangku_huizong','(id in('.$ids.')) and (isDelete=0)');
		$arrs=explode(',',$ids);
		for($i=0;$i<count($arrs);$i++){

			$id=$arrs[$i];
		$result=$this->data_model->get_cangkuhuizong("id=$id");

			foreach($result as $key=>$row){
				$number=$row['inventoryNew'];
				$ordernumber=$row['ordernumber'];
				$goodsnumber=$row['goodsnumber'];
				$res_tmp = $this->db->query("select * from ci_cangku where ordernumber = $ordernumber and goodsnumber=$goodsnumber")->result_array();
				$res = $this->db->query("update ci_cangku set number=number-$number where ordernumber = $ordernumber and goodsnumber=$goodsnumber");
				if($res){
                    foreach ($res_tmp as $tmp){
                        $this->common_model->cangku_logs('number:'.$tmp['number'].'-'.$number,$tmp['id'],$ordernumber,$goodsnumber);
                    }
                }
                $res = $this->db->query("update ci_cangku set inventoryNew=inventoryNew+$number where ordernumber = $ordernumber and goodsnumber=$goodsnumber");
                if($res){
                    foreach ($res_tmp as $tmp){
                        $this->common_model->cangku_logs('inventoryNew:'.$tmp['inventoryNew'].'+'.$number,$tmp['id'],$ordernumber,$goodsnumber);
                    }
                }
			}
		}

		if (count($data) > 0) {
		    //$this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('cangku_huizong',array('isDelete'=>1),'(id in('.$ids.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('删除商品:ID='.$ids.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'删除成功','id'=>'['.$ids.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'删除失败');
		}
	}

	public function deletexiangmu(){
		$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('xiangmuku','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		   // $this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('xiangmuku',array('isDelete'=>1),'(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('删除商品:ID='.$id.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'删除成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'删除失败');
		}
	}

	public function ordersdaohuo(){
		//$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$dao = str_enhtml($this->input->post('dao',TRUE));
		$daotime = str_enhtml($this->input->post('daotime',TRUE));
		$time=date("Y/m/d");
		$res=$this->mysql_model->get_results('stock','(id in('.$id.')) and (isDelete=0)');
		$ress=$this->mysql_model->get_results('cangku','(isDelete=0)');
		foreach($res as $arr=>$row){
			foreach($ress as $arrs=>$rows){
				if($row['ordernumber']==$rows['ordernumber'] && $row['goodsnumber']==$rows['goodsnumber']){
					$rowsid=$rows['id'];
					$this->mysql_model->update('cangku',array('flagNo'=>'已到货'),'(id in('.$rowsid.'))');
					$this->mysql_model->update('cangku',array('locationName'=>$dao),'(id in('.$rowsid.'))');
					$this->mysql_model->update('cangku',array('flagtime'=>$daotime),'(id in('.$rowsid.'))');

				}

			}

		}

		$data = $this->mysql_model->get_results('stock','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		    $this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('stock',array('flagNo'=>'已到货'),'(id in('.$id.'))');
			$this->mysql_model->update('stock',array('locationName'=>$dao),'(id in('.$id.'))');
			$this->mysql_model->update('stock',array('flagtime'=>$daotime),'(id in('.$id.'))');


		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('到货商品:ID='.$id.' 名称:'.join(',',$name));
				//str_alert(200,'success',array('msg'=>'到货成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
				exit("到货成功！");
			}
			str_alert(-1,'操作失败');
		}
	}

	//物资到货
    public function ordersdaohuo_scan(){
        //$this->common_model->checkpurview(71);
        $id = str_enhtml($this->input->post('ids',TRUE));
        $id = implode(',',$id);
        $dao = str_enhtml($this->input->post('dao',TRUE));
        $daotime = str_enhtml($this->input->post('daotime',TRUE));
        $daohuo = str_enhtml($this->input->post('daohuo',TRUE));//到货数量
        $res=$this->mysql_model->get_results('stock','(id in('.$id.')) and (isDelete=0)');
        $ress=$this->mysql_model->get_results('cangku','(isDelete=0)');
        foreach($res as $key=>$row){
            //到货数量+已到货数量 不能大于初始库存
            if($daohuo[$key]+$row['daohuo']>$row['inventoryOld']){
                exit('到货数量过大！');
            }
            if($row['inventoryNew']<$row['inventoryOld'] && $daohuo[$key]+$row['inventoryNew']>$row['inventoryOld']){
                exit('到货数量过大！');
            }
        }
        $this->db->trans_start();
        foreach($res as $key=>$row){
            //到货数量+已到货数量 不能大于初始库存
            if($daohuo[$key]+$row['daohuo']<=$row['inventoryOld']){
                //如果之和不相等 则为 部分到货  否则为 已到货
                if($daohuo[$key]+$row['daohuo']<$row['inventoryOld']){
                    $daohuo_status = '部分到货';
                }elseif($daohuo[$key]+$row['daohuo']==$row['inventoryOld']){
                    $daohuo_status = '已到货';
                }
                //cangku表更新
                foreach($ress as $keys=>$rows){
                    if($row['ordernumber']==$rows['ordernumber'] && $row['goodsnumber']==$rows['goodsnumber']){
                        $this->db->where('id',$rows['id']);
                        if($rows['inventoryNew']==$rows['inventoryOld']){
                            $this->db->set('inventoryNew',$daohuo[$key]);
                        }else{
                            $this->db->set('inventoryNew','inventoryNew+'.$daohuo[$key],false);
                        }
                        $this->db->set('flagNo',$daohuo_status);
                        $this->db->set('locationName',$dao);
                        $this->db->set('flagtime',$daotime);
                        $this->db->set('daohuo','daohuo+'.$daohuo[$key],false);
                        $res_ = $this->db->update('ci_cangku');

                        if($res_){
                            if($rows['inventoryNew']==$rows['inventoryOld']){
                                $this->common_model->cangku_logs('inventoryNew赋值:'.$daohuo[$key],$rows['id'],$rows['ordernumber'],$rows['goodsnumber']);
                            }else{
                                $this->common_model->cangku_logs('inventoryNew:'.$rows['inventoryNew'].'+'.$daohuo[$key],$rows['id'],$rows['ordernumber'],$rows['goodsnumber']);
                            }
                            $this->common_model->cangku_logs('daohuo:'.$rows['daohuo'].'+'.$daohuo[$key],$rows['id'],$rows['ordernumber'],$rows['goodsnumber']);
                        }
                    }

                }
                //stock表更新
                $this->db->where('id',$row['id']);
                if($row['inventoryNew']==$row['inventoryOld']){
                    $this->db->set('inventoryNew',$daohuo[$key]);
                }else{
                    $this->db->set('inventoryNew','inventoryNew+'.$daohuo[$key],false);
                }
                $this->db->set('flagNo',$daohuo_status);
                $this->db->set('locationName',$dao);
                $this->db->set('flagtime',$daotime);
                $this->db->set('daohuo','daohuo+'.$daohuo[$key],false);
                $res_ = $this->db->update('ci_stock');
                if($res_){
                    if($row['inventoryNew']==$row['inventoryOld']){
                        $this->common_model->stock_logs('inventoryNew赋值:'.$daohuo[$key],$row['id'],$row['ordernumber'],$row['goodsnumber']);
                    }else{
                        $this->common_model->stock_logs('inventoryNew:'.$row['inventoryNew'].'+'.$daohuo[$key],$row['id'],$row['ordernumber'],$row['goodsnumber']);
                    }
                    $this->common_model->stock_logs('daohuo:'.$row['daohuo'].'+'.$daohuo[$key],$row['id'],$row['ordernumber'],$row['goodsnumber']);
                }
            }
        }
        $trans_res = $this->db->trans_complete();
        if($trans_res){
            $name = array_column($res,'name');
            $this->common_model->logs('到货商品:ID='.$id.' 名称:'.join(',',$name));
            //str_alert(200,'success',array('msg'=>'到货成功','id'=>'['.$id.']'));
            //str_alert(200,'删除成功');
            exit("到货成功！");
        }else{
            exit('操作失败');
        }
    }

	public function querychuku(){
		$id = str_enhtml($this->input->post('id',TRUE));
		if (substr($id, -1) == ',') {
			$id = substr($id, 0, strlen($id)-1);
		}
		$billNo = trim($this->input->post('billNo',TRUE));
		$data_1 = $this->mysql_model->get_results('cangku','(id in('.$id.')) and (inventoryNew=0) and (isDelete=0)');
        if(count($data_1) > 0){
            $json['status'] = 200;
            $json['msg']    = '存在库存为0的订单，请检查！！';
            die(json_encode($json));
        }
        $data = $this->mysql_model->get_results('cangku','(id in('.$id.')) and (flagNo="未到货") and (isDelete=0)');
        if (count($data) > 0) {

			$json['status'] = 200;
			$json['msg']    = '存在未到货的订单，请检查！！';
			die(json_encode($json));

		}else{
            $id = explode(',',$id);
            $res = $this->db->select('ordernumber,goodsnumber')->where_in('id',$id)->where('isDelete',0)->get('cangku')->result_array();
            if(!empty($res)){
                $chuku_status = 0;
                foreach ($res as $row){
                    $chuku_status_ = $this->db->where('ordernumber',$row['ordernumber'])->where('billNo',$billNo)->where('goodsnumber',$row['goodsnumber'])->where('chuku_status',1)->get('ci_orders_info')->row_array();
                    if(!empty($chuku_status_)){
                        $chuku_status = 1;
                        break;
                    }
					    //判断订单是否已经作废或未审核
						$checked = $this->db->select('checked')->where('billNo', $billNo)->get('ci_orders')->row_array();
						if ($checked && $checked['checked'] == 3) {
							$json['status'] = 200;
							$json['msg'] = '该订单已经作废，不能出库！';
							die(json_encode($json));
						}
						elseif ($checked &&$checked['checked'] == 0) {
							$json['status'] = 200;
							$json['msg'] = '该订单未审核！';
							die(json_encode($json));
						}

                }

                if($chuku_status == 1){
                    $json['status'] = 200;
                    $json['msg']    = '订单已出库，请检查！！';
                    die(json_encode($json));
                }
            }
            $this->load->library('session');
            // $query_chuku_session = $this->session->userdata('query_chuku_session');
            if($query_chuku_session == [$id,$billNo]){
                $json['status'] = 200;
                $json['msg']    = '已扫描，请等待结果！！';
                die(json_encode($json));
            }else{
                $this->session->set_userdata('query_chuku_session',[$id,$billNo]);
                $json['status'] = 000;
                $json['msg']    = 'success';
                die(json_encode($json));
            }
		}
	}

    public function querydaohuo(){
		$id = str_enhtml($this->input->post('id',TRUE));
		if (substr($id, -1) == ',') {
			$id = substr($id, 0, strlen($id)-1);
		}
        $data = $this->mysql_model->get_results('stock','(id in('.$id.')) and (inventoryOld = daohuo)');

        if (count($data) > 0) {

            $json['status'] = 200;
            $json['msg']    = 'success';
            die(json_encode($json));

        }else{
            $json['status'] = 000;
            $json['msg']    = 'success';
            die(json_encode($json));
        }
    }

		public function orderschuku(){
		$this->common_model->checkpurview(71);
		$id = str_enhtml($this->input->post('id',TRUE));
		$name = str_enhtml($this->input->post('name',TRUE));
		$number = str_enhtml($this->input->post('number',TRUE));
		$chukutime = str_enhtml($this->input->post('chukutime',TRUE));
		$beizhus = str_enhtml($this->input->post('beizhu',TRUE));

		//$result=M("cangku")->where("id=$id")->setInc('number',3); // 用户的积分加3

		$arrs=explode(',',$id);
		for($i=0;$i<count($arrs);$i++){

			$id=$arrs[$i];
					$result=$this->data_model->get_cangku("id=$id");
		//print_r($result);
		foreach($result as $key=>$row){
			if($row['inventoryNew']<$number){

				str_alert(-1,'库存不足！');
				exit();
			}

		}
		}

		for($i=0;$i<count($arrs);$i++){

			$id=$arrs[$i];
					$result=$this->data_model->get_cangku("id=$id");
		//print_r($result);
		foreach($result as $key=>$row){
			if($row['inventoryNew']<$number){

				str_alert(-1,'库存不足！');
				exit();
			}

			$data=array(
			'inventoryNew'=>$number,
			'ordernumber'=>$row['ordernumber'],
			'goodsnumber'=>$row['goodsnumber'],
			'mdescription'=>$row['mdescription'],
			'mainUnit'=>$row['mainUnit'],
			'price'=>$row['price'],
			'amount'=>$row['price']*$number,
			'flagtime'=>$chukutime,
			'sign'=>$name,
			'flagcontact'=>$row['beizhu'],
			'beizhu'=>$beizhus,

		);

		//print_r($data);
		$bool=$this->db->insert('ci_cangku_huizong',$data);
		if($bool){
			$this->db->query("update ci_cangku set number=number+$number where id = $id");
			$this->db->query("update ci_cangku set inventoryNew=inventoryNew-$number where id = $id");

		}


		}
		if($i==count($arrs)-1){
			$json['status'] = 200;
			$json['msg']    = 'success';
			die(json_encode($json));

		}
		}


		/*$name = str_enhtml($this->input->post('name',TRUE));
		$number = str_enhtml($this->input->post('number',TRUE));
		$chukutime = str_enhtml($this->input->post('chukutime',TRUE));
		$beizhu = str_enhtml($this->input->post('beizhu',TRUE));
		$time=date("Y/m/d");
		$data = $this->mysql_model->get_results('cangku','(id in('.$id.')) and (isDelete=0)');
		if (count($data) > 0) {
		    $this->mysql_model->get_count('orders_info','(invId in('.$id.')) and (isDelete=0)')>0 && str_alert(-1,'其中有商品发生业务不可删除');
		    $sql  = $this->mysql_model->update('cangku',array('flagNo'=>'已出库'),'(id in('.$id.'))');
			$this->mysql_model->update('cangku',array('locationName'=>$dao),'(id in('.$id.'))');
			$this->mysql_model->update('cangku',array('flagtime'=>$chukutime),'(id in('.$id.'))');
			$this->mysql_model->update('cangku',array('beizhu'=>$beizhu),'(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs('出库商品:ID='.$id.' 名称:'.join(',',$name));
				str_alert(200,'success',array('msg'=>'出库成功','id'=>'['.$id.']'));
				//str_alert(200,'删除成功');
			}
			str_alert(-1,'操作失败');
		}*/
	}

	public function showcangkus(){

		$ids = str_enhtml($this->input->get_post('ids',TRUE));
if (substr($ids, -1) == ',') {
    $ids = substr($ids, 0, strlen($ids)-1);
}
		$billNo = trim($this->input->get_post('billNo',TRUE));
		$data = $this->mysql_model->get_results('cangku','(id in('.$ids.')) and (isDelete=0)');
		foreach ($data as $arr=>$row) {
			$json[$arr]['id']=$row['id'];
			$json[$arr]['ordernumber']=$row['ordernumber'];
			$json[$arr]['goodsnumber']=$row['goodsnumber'];
			$json[$arr]['mdescription']=$row['mdescription'];
			$json[$arr]['inventoryNew']=$row['inventoryNew'];
			$qty_ = $this->db->select('qty,liname')->where('isDelete',0)
                ->where('billNo',$billNo)
                ->where('ordernumber',$row['ordernumber'])
                ->where('goodsnumber',$row['goodsnumber'])
                ->get('ci_orders_info')->row_array();
				if ($billNo) {
					$json[$arr]['qty'] = $qty_['qty'];
				} else {

					$json[$arr]['qty'] =$row['inventoryNew'];
				}
			$json[$arr]['liname']=$qty_['liname'];
			$json[$arr]['mainUnit']=$row['mainUnit'];
		}
		die(json_encode($json));
	}

	public function updcangkus(){
        $this->load->library('session');
        $chuku_session = $this->session->userdata('chuku_session');

        $ids = str_enhtml($this->input->get_post('ids',TRUE));
        $number = str_enhtml($this->input->get_post('number',TRUE));
        $sign = str_enhtml($this->input->get_post('sign',TRUE));
        $chukutime = str_enhtml($this->input->get_post('time',TRUE));
        $beizhus = str_enhtml($this->input->get_post('beizhu',TRUE));
		if([$ids,$number,$sign,$chukutime,$beizhus] != $chuku_session)
		{
		    $this->session->set_userdata('chuku_session',[$ids,$number,$sign,$chukutime,$beizhus]);
		for($i=0;$i<count($ids);$i++){

			$id=$ids[$i];
					$result=$this->data_model->get_cangku("id=$id");
			foreach($result as $key=>$row){

				if($row['inventoryNew']<$number[$i]){

				  die(json_encode(['error_code'=>1000,'msg'=>'物料编号:'.$row['goodsnumber']."库存不足,出库失败！"]));
				}

			}
		}


		for($i=0;$i<count($ids);$i++){

			$id=$ids[$i];
					$result=$this->data_model->get_cangku("id=$id");
		foreach($result as $key=>$row){


			$data=array(
			'inventoryNew'=>$number[$i],
			'ordernumber'=>$row['ordernumber'],
			'goodsnumber'=>$row['goodsnumber'],
			'mdescription'=>$row['mdescription'],
			'mainUnit'=>$row['mainUnit'],
			'price'=>$row['price'],
			'amount'=>$row['price']*$number[$i],
			'flagtime'=>$chukutime,
			'sign'=>$sign,
			'flagcontact'=>$row['beizhu'],
                        'locationName'=>$row['locationName'],
			'beizhu'=>$beizhus,

		);

		//print_r($data);
		$bool=$this->db->insert('ci_cangku_huizong',$data);
		if($bool){
			$nums=$number[$i];
            $res_tmp = $this->db->query("select * from ci_cangku where id = $id")->row_array();
			$res = $this->db->query("update ci_cangku set number=number+$nums where id = $id");
			if($res){
                $this->common_model->cangku_logs('number:'.$res_tmp['number'].'+'.$nums,$id,$row['ordernumber'],$row['goodsnumber']);
            }
            $res = $this->db->query("update ci_cangku set inventoryNew=inventoryNew-$nums where id = $id");
            if($res){
                $this->common_model->cangku_logs('inventoryNew:'.$res_tmp['inventoryNew'].'-'.$nums,$id,$row['ordernumber'],$row['goodsnumber']);
            }
            $this->db->where('ordernumber',$row['ordernumber'])->where('goodsnumber',$row['goodsnumber'])->where('billNo',$beizhus)->update('ci_orders_info',['chuku_status'=>1]);
        }


		}
		if($i==count($ids)-1){
            die(json_encode(['error_code'=>0000,'msg'=>"出库成功！"]));
        }
		}
		}
		else
		 {
             die(json_encode(['error_code'=>1000,'msg'=>"不要重复提交"]));
         }


	}

    //导出
	public function exporter() {
	    $this->common_model->checkpurview(72);
		$name = 'goods_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出商品:'.$name);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		//$categoryid   = intval($this->input->get_post('assistId',TRUE));
		//$barCode      = intval($this->input->get_post('barCode',TRUE));
		$where = '(a.isDelete=0)';
		$where .= $skey ? ' and (name like "%'.$skey.'%" or number like "%'.$skey.'%" or spec like "%'.$skey.'%")' : '';
		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		/*if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}  */
		//$data['storage']  =  array_column($this->mysql_model->get_results('storage'),'name','id');
		$data['list']     = $this->data_model->get_goods($where.' order by a.id desc');
        $this->load->view('settings/goods-export',$data);

	}

	  //导出
	public function exporterorder() {
	    $this->common_model->checkpurview(72);
		$name = 'goods_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出商品:'.$name);

		$where = $this->getorderslistwhere();

		$list = $this->data_model->get_stock($where.' order by a.id desc');
		$cangkuMap = $this->buildCangkuInventoryMap($list);
		if (is_array($list)) {
			foreach ($list as $k => $row) {
				$key = $row['goodsnumber']."\t".$row['ordernumber'];
				$list[$k]['cangkuInventory'] = number_format(isset($cangkuMap[$key]) ? floatval($cangkuMap[$key]) : 0, 3, '.', '');
				$list[$k]['amount'] = $row['number'] * $row['price'];
			}
		}
		$data['list'] = $list;
		$data['fg'] = str_enhtml($this->input->get_post('fg',TRUE));
		$data['oldnumber'] = str_enhtml($this->input->get_post('oldnumber',TRUE));
		$data['newnumber'] = str_enhtml($this->input->get_post('newnumber',TRUE));
		$data['number'] = str_enhtml($this->input->get_post('number',TRUE));
        $this->load->view('settings/goods-exportorder',$data);

	}

	  //导出
	public function exportcangku() {
	    $this->common_model->checkpurview(72);
		$name = 'goods_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出商品:'.$name);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按商品编号查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按订单编号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$Arrivaltime=$Arrivaltime=='按申请时间查询'?'':$Arrivaltime;
		$beizhu = str_enhtml($this->input->get_post('beizhu',TRUE));
		$beizhu=$beizhu=='按项目备注查询'?'':$beizhu;
		$cangkuwei = str_enhtml($this->input->get_post('cangkuwei',TRUE));
		$cangkuwei=$cangkuwei=='按仓库位置查询'?'':$cangkuwei;
		$chec = str_enhtml($this->input->get_post('chec',TRUE));
		$chec=$chec==''?1:$chec;
		$data['fg'] = str_enhtml($this->input->get_post('fg',TRUE));
		$data['oldnumber'] = str_enhtml($this->input->get_post('newnumber',TRUE));
		$data['newnumber'] = str_enhtml($this->input->get_post('oldnumber',TRUE));
		$data['number'] = str_enhtml($this->input->get_post('number',TRUE));
		//$categoryid   = intval($this->input->get_post('assistId',TRUE));
		//$barCode      = intval($this->input->get_post('barCode',TRUE));

		if($chec==1){

			$where = '(a.isDelete=0 and a.inventoryNew>0)';
		}else{
			$where = '(a.isDelete=0)';
		}
		$where .= $skey ? ' and a.goodsnumber="'.$skey.'"': '';
		$where .= $ordernumber ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $Arrivaltime ? ' and a.Arrivaltime="'.$Arrivaltime.'"': '';
		$where .= $beizhu ? ' and a.beizhu="'.$beizhu.'"': '';
		$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		$where .= $cangkuwei ? ' and (locationName like "%'.$cangkuwei.'%")' : '';
		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		/*if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}  */
		//$data['storage']  =  array_column($this->mysql_model->get_results('storage'),'name','id');
		$data['list']     = $this->data_model->get_cangku($where.' order by a.id desc');
        $this->load->view('settings/goods-exportcangku',$data);

	}

	public function exportcangkuhuizong() {
	    $this->common_model->checkpurview(72);
		$name = 'goods_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出商品:'.$name);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按商品编号查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按订单编号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$Arrivaltime = str_enhtml($this->input->get_post('Arrivaltime',TRUE));
		$Arrivaltime=$Arrivaltime=='按出库时间查询'?'':$Arrivaltime;
        // 领用状态筛选
		$receiveStatus = str_enhtml($this->input->get_post('receiveStatus',TRUE));
		$data['fg'] = str_enhtml($this->input->get_post('fg',TRUE));
		$data['oldnumber'] = str_enhtml($this->input->get_post('newnumber',TRUE));
		$data['newnumber'] = str_enhtml($this->input->get_post('oldnumber',TRUE));
		$data['number'] = str_enhtml($this->input->get_post('number',TRUE));
		//$categoryid   = intval($this->input->get_post('assistId',TRUE));
		//$barCode      = intval($this->input->get_post('barCode',TRUE));
		$where = '(a.isDelete=0)';
		$where .= $skey ? ' and a.goodsnumber="'.$skey.'"': '';
		$where .= $ordernumber ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $Arrivaltime ? ' and a.flagtime="'.$Arrivaltime.'"': '';
		$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		// 领用状态筛选
		$where .= $receiveStatus != '' ? ' and a.receive_status="'.$receiveStatus.'"' : '';
		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		/*if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}  */
		//$data['storage']  =  array_column($this->mysql_model->get_results('storage'),'name','id');
		$data['list'] = $this->data_model->get_cangkuhuizong($where.' order by a.id desc');
        $this->load->view('settings/goods-exportcangkuhuizong',$data);

	}

	  //导出
	public function exporterxiangmu() {
	    $this->common_model->checkpurview(72);
		$name = 'goods_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出商品:'.$name);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$skey=$skey=='按项目名称查询'?'':$skey;
		$ordernumber = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$ordernumber=$ordernumber=='按项目定义号查询'?'':$ordernumber;
		$mdescription = str_enhtml($this->input->get_post('mdescription',TRUE));
		$mdescription=$mdescription=='按物料描述查询'?'':$mdescription;
		$numbers = str_enhtml($this->input->get_post('numbers',TRUE));
		$numbers=$numbers=='按物料号查询'?'':$numbers;
		$data['fg'] = str_enhtml($this->input->get_post('fg',TRUE));
		$data['oldnumber'] = str_enhtml($this->input->get_post('oldnumber',TRUE));
		//$data['newnumber'] = str_enhtml($this->input->get_post('oldnumber',TRUE));
		$data['number'] = str_enhtml($this->input->get_post('number',TRUE));
		$matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
		//$categoryid   = intval($this->input->get_post('assistId',TRUE));
		//$barCode      = intval($this->input->get_post('barCode',TRUE));
		$where = '(a.isDelete=0)';
		$where .= $skey ? ' and a.name="'.$skey.'"': '';
		$where .= $ordernumber ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $numbers ? ' and a.number="'.$numbers.'"': '';
		$where .= $mdescription ? ' and (mdescription like "%'.$mdescription.'%")' : '';
		//$where .= $barCode ? ' and barCode="'.$barCode.'"' : '';
		/*if ($categoryid > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$categoryid.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and categoryid in('.$cid.')';
			}
		}  */
		//$data['storage']  =  array_column($this->mysql_model->get_results('storage'),'name','id');
		$data['list']     = $this->data_model->get_xiangmu($where.' order by a.id desc');
        $this->load->view('settings/goods-exportxiangmu',$data);

	}

	//状态
	public function disable(){
		$this->common_model->checkpurview(72);
		$disable = intval($this->input->post('disable',TRUE));
		$id = str_enhtml($this->input->post('invIds',TRUE));
		if (strlen($id) > 0) {
			$sql = $this->mysql_model->update('goods',array('disable'=>$disable),'(id in('.$id.'))');
		    if ($sql) {
				$this->common_model->logs('商品'.$disable==1?'禁用':'启用'.':ID:'.$id.'');
				str_alert(200,'success');
			}
		}
		str_alert(-1,'操作失败');
	}

	//库存预警
	public function listinventoryqtywarning() {
		$locationId  = intval($this->input->get_post('locationId',TRUE));
		$warnType    = intval($this->input->get_post('warnType',TRUE));
		$assistId    = intval($this->input->get_post('assistId',TRUE));
		$skey        = str_enhtml($this->input->get_post('skey',TRUE));
		$page        = max(intval($this->input->get_post('page',TRUE)),1);
		$rows        = max(intval($this->input->get_post('rows',TRUE)),20);
		$where = 'a.isDelete=0';
		if ($warnType==1) {
		    $having = 'HAVING qty<lowQty';
		} elseif($warnType==2) {
		    $having = 'HAVING qty>highQty';
		} else {
		    $having = 'HAVING qty>highQty or qty<lowQty';
		}
		if ($assistId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$assistId.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			}
		}
		$where .= $skey ? ' and (b.name like "%'.$skey.'%" or b.number like "%'.$skey.'%" or b.spec like "%'.$skey.'%")' : '';
		$where .= $locationId>0 ? ' and a.locationId='.$locationId.'' : '';
		$where .= $this->common_model->get_location_purview();
		$offset = $rows*($page-1);
		$list = $this->data_model->get_inventory($where.' GROUP BY invId,locationId '.$having.' limit '.$offset.','.$rows);
		foreach ($list as $arr=>$row) {
			$v[$arr]['highQty']       = (float)$row['highQty'];
			$v[$arr]['id']            = intval($row['invId']);
			$v[$arr]['lowQty']        = (float)$row['lowQty'];
			$v[$arr]['name']          = $row['invName'];
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['number']        = $row['invNumber'];
			$v[$arr]['categoryName']  = $row['categoryName'];
			$v[$arr]['warning']       = $row['qty1'] > 0 ? $row['qty1'] : $row['qty2'];
			$v[$arr]['qty']           = (float)$row['qty'];
			$v[$arr]['unitName']      = $row['unitName'];
			$v[$arr]['spec']          = $row['invSpec'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_inventory($where.' GROUP BY invId,locationId '.$having,3);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? array_values($v) : array();
		die(json_encode($json));
	}


	public function warningExporter() {
	    $this->common_model->checkpurview();
		$name = 'InventoryWarning_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出库存预警商品:'.$name);
		$locationId  = intval($this->input->get_post('locationId',TRUE));
		$warnType    = intval($this->input->get_post('warnType',TRUE));
		$assistId    = intval($this->input->get_post('assistId',TRUE));
		$skey        = str_enhtml($this->input->get_post('skey',TRUE));
		$where = 'a.isDelete=0';
		if ($warnType==1) {
		    $having = 'HAVING qty<lowQty';
		} elseif($warnType==2) {
		    $having = 'HAVING qty>highQty';
		} else {
		    $having = 'HAVING qty>highQty or qty<lowQty';
		}
		if ($assistId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=1) and find_in_set('.$assistId.',path)'),'id');
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			}
		}
		$where .= $skey ? ' and (b.name like "%'.$skey.'%" or b.number like "%'.$skey.'%" or b.spec like "%'.$skey.'%")' : '';
		$where .= $locationId>0 ? ' and a.locationId='.$locationId.'' : '';
		$where .= $this->common_model->get_location_purview();
		$data['list']  = $this->data_model->get_inventory($where.' GROUP BY invId,locationId '.$having);
		$this->load->view('settings/inventory-warning-exporter',$data);
	}



	//通过ID 获取商品信息
	private function get_goods_info($id) {
	    $data = $this->mysql_model->get_rows('goods',array('id'=>$id,'isDelete'=>0));
		if (count($data)>0) {
			$data['id']            = $id;
			$data['count']         = 0;
			$data['name']          = $data['name'];
			$data['spec']          = $data['spec'];
			$data['number']        = $data['number'];
			$data['salePrice']     = (float)$data['salePrice'];
			$data['purPrice']      = (float)$data['purPrice'];
			$data['wholesalePrice']= (float)$data['wholesalePrice'];
			$data['vipPrice']      = (float)$data['vipPrice'];
			$data['discountRate1'] = (float)$data['discountRate1'];
			$data['discountRate2'] = (float)$data['discountRate2'];
			$data['unitTypeId']    = intval($data['unitTypeId']);
			$data['baseUnitId']    = intval($data['baseUnitId']);
			$data['locationId']    = intval($data['locationId']);
			$data['assistIds']     = '';
			$data['assistName']    = '';
			$data['assistUnit']    = '';
			$data['remark']        = $data['remark'];
			$data['categoryId']    = intval($data['categoryId']);
			$data['unitId']        = intval($data['unitId']);
			$data['length']        = '';
			$data['weight']        = '';
			$data['jianxing']      = '';
			$data['barCode']       = $data['barCode'];
			$data['josl']          = '';
			$data['warehouseWarning']          = intval($data['warehouseWarning']);
			$data['warehouseWarningSku']       = 0;
			$data['skuClassId']          = 0;
			$data['isSerNum']            = 0;
			$data['pinYin']              = $data['pinYin'];
			$data['delete']              = false;
			$data['isWarranty']    = 0;
			$data['safeDays']      = 0;
			$data['advanceDay']    = 0;
			$data['property']      = $data['property'] ? $data['property'] : NULL;
			$propertys = $this->data_model->get_invoice_info('a.isDelete=0 and a.invId='.$id.' and a.billType="INI"');
			foreach ($propertys as $arr=>$row) {
				$v[$arr]['id']            = intval($row['id']);
				$v[$arr]['locationId']    = intval($row['locationId']);
				$v[$arr]['inventoryId']   = intval($row['invId']);
				$v[$arr]['locationName']  = $row['locationName'];
				$v[$arr]['quantity']      = (float)$row['qty'];
				$v[$arr]['unitCost']      = (float)$row['price'];
				$v[$arr]['amount']        = (float)$row['amount'];
				$v[$arr]['skuId']         = intval($row['skuId']);
				$v[$arr]['skuName']       = '';
				$v[$arr]['date']          = $row['billDate'];
				$v[$arr]['tempId']        = 0;
				$v[$arr]['batch']         = '';
				$v[$arr]['invSerNumList'] = '';
			}
			$data['propertys']            = isset($v) ? $v : array();
			if ($data['warehousePropertys']) {
			    $warehouse = (array)json_decode($data['warehousePropertys'],true);
				foreach ($warehouse as $arr=>$row) {
					$s[$arr]['locationId']    = intval($row['locationId']);
					$s[$arr]['locationName']  = $row['locationName'];
					$s[$arr]['highQty']       = (float)$row['highQty'];
					$s[$arr]['lowQty']        = (float)$row['lowQty'];
				}
			}
			$data['warehousePropertys']   = isset($s) ? $s : array();

		}
		return $data;
	}


	//公共验证
	private function validform($data) {
	    $this->load->library('lib_cn2pinyin');
	    strlen($data['name']) < 1 && str_alert(-1,'商品名称不能为空');
		strlen($data['number']) < 1 && str_alert(-1,'商品编号不能为空');
		$data['categoryId'] = intval($data['categoryId']);
		$data['baseUnitId'] = intval($data['baseUnitId']);
		if($data['unitname']==""){
			str_alert(-1,'计量单位不能为空');
		}else{
			$data['unitName']=$data['unitname'];
		}
		//$data['categoryId'] < 1 && str_alert(-1,'商品类别不能为空');
		//$data['baseUnitId'] < 1 && str_alert(-1,'计量单位不能为空');
		$data['id']        = isset($data['id']) ? intval($data['id']):0;
		$data['lowQty']    = isset($data['lowQty']) ? (float)$data['lowQty'] :0;
		$data['highQty']   = isset($data['highQty']) ? (float)$data['highQty']:0;
		$data['purPrice']  = isset($data['purPrice']) ? (float)$data['purPrice']:0;
		$data['salePrice'] = isset($data['salePrice']) ? (float)$data['salePrice']:0;
		$data['vipPrice']  = isset($data['vipPrice']) ? (float)$data['vipPrice']:0;
		$data['warehouseWarning']  = isset($data['warehouseWarning']) ? intval($data['warehouseWarning']):0;
		$data['discountRate1']  = (float)$data['discountRate1'];
		$data['discountRate2']  = (float)$data['discountRate2'];
		$data['wholesalePrice'] = isset($data['wholesalePrice']) ? (float)$data['wholesalePrice']:0;
		//$data['unitName']     = $this->mysql_model->get_row('unit',array('id'=>$data['baseUnitId']),'name');
		$data['categoryName'] = $this->mysql_model->get_row('category',array('id'=>$data['categoryId']),'name');
		$data['pinYin'] = $this->lib_cn2pinyin->encode($data['name']);
		//!$data['categoryName'] && str_alert(-1,'商品类别不存在');
	    if (strlen($data['propertys'])>0) {
			$list         = (array)json_decode($data['propertys'],true);
			$storage      = $this->mysql_model->get_results('storage',array('disable'=>0));
			$locationId   =  array_column($storage,'id');
			$locationName =  array_column($storage,'name','id');
			foreach ($list as $arr=>$row) {
				!in_array($row['locationId'],$locationId) && str_alert(-1,$locationName[$row['locationId']].'仓库不存在或不可用！');
			}
		}
		$data['warehousePropertys'] = isset($data['warehousePropertys']) ? $data['warehousePropertys'] :'[]';
		$data['warehousePropertys'] = count(json_decode($data['warehousePropertys'],true))>0 ? $data['warehousePropertys'] :'';
		return $data;
	}

    public function getChuku()
    {
        $billNo = $this->input->get_post('billNo',TRUE);
        $res = $this->mysql_model->getChuku(trim($billNo));
        //var_dump($this->db->last_query());
        if(empty($res)){
            die(json_encode(['data'=>$res,'error_code'=>'1000']));
        }else{
            die(json_encode(['data'=>$res,'error_code'=>'0000']));
        }
	}

    public function getDaohuo()
    {
        $ordernumber = $this->input->get_post('ordernumber',TRUE);
        $res = $this->mysql_model->getDaohuo(trim($ordernumber));
        if(empty($res)){
            die(json_encode(['data'=>$res,'error_code'=>'1000']));
        }else{
            die(json_encode(['data'=>$res,'error_code'=>'0000']));
        }
    }

    public function showstocks(){

        $ids = str_enhtml($this->input->get_post('ids',TRUE));
		if (substr($ids, -1) == ',') {
			$ids = substr($ids, 0, strlen($ids)-1);
		}
        $data = $this->mysql_model->get_results('stock','(id in('.$ids.')) and (isDelete=0)');
        foreach ($data as $arr=>$row) {
            $json[$arr]['id']=$row['id'];
            $json[$arr]['ordernumber']=$row['ordernumber'];
            $json[$arr]['goodsnumber']=$row['goodsnumber'];
            $json[$arr]['mdescription']=$row['mdescription'];
            $json[$arr]['inventoryNew']=$row['inventoryNew'];
            $json[$arr]['inventoryOld']=$row['inventoryOld'];
            $json[$arr]['daohuo']=$row['daohuo'];
            $json[$arr]['mainUnit']=$row['mainUnit'];
        }
        die(json_encode($json));
    }

    public function runTableDaohuo()
    {
        $cangku = $this->db->select('id,inventoryOld')->where('flagNo','已到货')->where('daohuo',0)->get('ci_cangku')->result_array();
        $stock = $this->db->select('id,inventoryOld')->where('flagNo','已到货')->where('daohuo',0)->get('ci_stock')->result_array();

        $this->db->trans_start();
        foreach ($cangku as $value){
            $this->db->where('id',$value['id'])->update('ci_cangku',['daohuo'=>$value['inventoryOld']]);
        }

        foreach ($stock as $value){
            $this->db->where('id',$value['id'])->update('ci_stock',['daohuo'=>$value['inventoryOld']]);
        }
        $res = $this->db->trans_complete();
        if($res){
            echo '已修改'.count($cangku).'条cangku 数据'.PHP_EOL;
            echo '已修改'.count($stock).'条stock 数据';
        }else{
            echo '运行错误';
        }
	}

    public function jianchachongfu()
    {
        $table = $this->input->get_post('a',true);
        $res_a = $this->db->select('id,ordernumber,goodsnumber')->where('isDelete',0)->get($table)->result_array();
        $string = '在table:'.$table.'里</br>';
        foreach ($res_a as $a){
            $res_b = $this->db->select('id')->where('isDelete',0)->where('id !=',$a['id'])->where('ordernumber',$a['ordernumber'])->where('goodsnumber',$a['goodsnumber'])->get($table)->result_array();
            if(is_array($res_b) && !empty($res_b)){
                $string .= '与id为'.$a['id'].'重复的有:(';
                foreach ($res_b as $b){
                    $string .= $b['id'].',';
                }
                $string .= ')</br>';
            }
        }
        echo $string;
	}

}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
