<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }

    //客户、供应商列表
	public function index() {
		$type   = intval($this->input->get('type',TRUE))==10 ? 10 : -10;
		$design   = intval($this->input->get('design',TRUE));
		$apply   = intval($this->input->get('apply',TRUE));
        $skey   = str_enhtml($this->input->get_post('skey',TRUE));
        $disable   = intval($this->input->get_post('disable',TRUE));
		$remark_   = str_enhtml($this->input->get_post('remark_',TRUE));
		$page   = max(intval($this->input->get_post('page',TRUE)),1);
		$categoryid   = intval($this->input->get_post('categoryId',TRUE));
		$rows   = max(intval($this->input->get_post('rows',TRUE)),100);
		$where  = '(isDelete=0)  and type='.$type;
        //默认开放所有内容
		//$where .= $this->common_model->get_contact_purview();
	    $where .= $skey ? ' and (number like "%'.$skey.'%" or name like "%'.$skey.'%" or linkMans like "%'.$skey.'%")' : '';
	    $where .= $remark_ ? " and remark_ like '%$remark_%' " : '';
		$where .= $categoryid>0 ? ' and cCategory = '.$categoryid.'' : '';
		if($disable != 0){
            $disable = $disable == -1 ? 0 : $disable;
            $where .= " and disable = $disable ";
        }
		if($design != 0){
            $design = $design == -1 ? 0 : $design;
            $where .= " and design = $design ";
        }
        if($apply != 0){
            $apply = $apply == -1 ? 0 : $apply;
            $where .= " and apply = $apply ";
        }
		$list = $this->mysql_model->get_results('contact',$where,'id desc',$rows*($page-1),$rows);
		//var_dump($this->db->last_query());exit;
		//print_r($list);
		//exit();
        $id_arr = array_column($list,'id');
        $tm_arr = $this->db->select('buId,count(buId) as total')->where_in('buId',$id_arr)->group_by('buId')->get('ci_orders_info')->result_array();
        $tm_arr = array_column($tm_arr,'total','buId');
		foreach ($list as $arr=>$row) {
		    //物资数统计  为该项目在领料单汇总中的物资领用总条目数（用来直接判断该项目是否有发生领料）
            //$orders_info_num = $this->db->where('buId',$row['id'])->from('ci_orders_info')->count_all_results();
            $orders_info_num = $tm_arr[$row['id']];

		    $v[$arr]['id']           = intval($row['id']);
			$v[$arr]['number']       = $row['number'];
			$v[$arr]['cCategory']    = intval($row['cCategory']);
			$v[$arr]['customerType'] = $row['cCategoryName'];
			$v[$arr]['pinYin']       = $row['pinYin'];
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['type']         = $row['type'];
			$v[$arr]['delete']       = intval($row['disable'])==1 ? true : false;
			$v[$arr]['design']       = intval($row['design'])==1 ? true : false;
			$v[$arr]['apply']       = intval($row['apply'])==1 ? true : false;
			$v[$arr]['check']       = intval($row['check'])==1 ? true : false;
            $v[$arr]['remark_']         = $row['remark_'];
            $v[$arr]['wbs']         = $row['wbs'];
            $v[$arr]['gdnumber']         = $row['gdnumber'];
			$v[$arr]['cLevel']       = intval($row['cLevel']);
			$v[$arr]['amount']       = (float)$row['amount'];
			$v[$arr]['periodMoney']  = (float)$row['periodMoney'];
			$v[$arr]['difMoney']     = (float)$row['difMoney'];
			$v[$arr]['remark']       = $row['remark'];
			$v[$arr]['xd_name']       = $row['xd_name'];
			$v[$arr]['xd_order']       = $row['xd_order'];
			$v[$arr]['taxRate']      = (float)$row['taxRate'];
			$v[$arr]['orders_num']      = empty($orders_info_num) ? 0 : $orders_info_num;//物资数统计
			$v[$arr]['links']        = '';
			if (strlen($row['linkMans'])>0) {
				$list = (array)json_decode($row['linkMans'],true);
				foreach ($list as $arr1=>$row1) {
					if ($row1['linkFirst']==1) {
						$v[$arr]['contacter']            = $row1['linkName'];
						$v[$arr]['mobile']               = $row1['linkMobile'];
						$v[$arr]['telephone']            = $row1['linkPhone'];
						$v[$arr]['linkIm']               = $row1['linkIm'];
						$v[$arr]['city']                 = $row1['city'];
						$v[$arr]['county']               = $row1['county'];
			            $v[$arr]['province']             = $row1['province'];
						$v[$arr]['deliveryAddress']      = $row1['address'];
						$v[$arr]['firstLink']['first']   = $row1['linkFirst'];
					}
				}
		    }
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->mysql_model->get_count('contact',$where);
		$json['data']['total']     = ceil($json['data']['records']/$rows);
		$json['data']['rows']      = isset($v) ? array_values($v) : array();
		die(json_encode($json));
	}

	//校验客户编号
	public function getNextNo(){
	     $type = intval($this->input->get('type',TRUE));
		 $skey = intval($this->input->post('skey',TRUE));
		 str_alert(200,'success',array('number'=>$skey));
	}


	//检测客户名称
	public function checkName(){
	    $id   = intval($this->input->post('id',TRUE));
		$name = str_enhtml($this->input->post('name',TRUE));
		$where['name']      = $name;
		$where['isDelete']  = 0;
		$where['id !='] = $id>0 ? $id :'';
	    $data = $this->mysql_model->get_rows('contact',array_filter($where));
		if (count($data)>0) {
		    str_alert(-1,'客户名称重复');
		}


		str_alert(200,'success');
	}

	public function getRecentlyContact(){
		$billType  = str_enhtml($this->input->post('billType',TRUE));
		$transType = intval($this->input->post('transType',TRUE));
		$where = '(isDelete=0)';
		$where .= $transType==150501 ? ' and type=10' :' and type=-10';
		$where .= $this->common_model->get_contact_purview();
	    $data = $this->mysql_model->get_rows('contact',$where);
		if (count($data)>0) {
			die('{"status":200,"msg":"success","data":{"contactName":"'.$data['name'].'","buId":'.$data['id'].',"cLevel":0}}');
		} else {
		    str_alert(-1,'');
		}
	}


	//获取信息
	public function query() {
	    $id   = intval($this->input->get_post('id',TRUE));
		$type = intval($this->input->get_post('type',TRUE));
		$data = $this->mysql_model->get_rows('contact',array('isDelete'=>0,'id'=>$id));
		if (count($data)>0) {
			$info['id']           = $id;
			$info['cCategory']    = intval($data['cCategory']);
			$info['cLevel']       = intval($data['cLevel']);
			$info['number']       = $data['number'];
			$info['name']         = $data['name'];
			$info['amount']       = (float)$data['amount'];
			$info['remark']       = $data['remark'];
			$info['beginDate']    = $data['beginDate'];
			$info['periodMoney']  = (float)$data['periodMoney'];
			$info['difMoney']     = (float)$data['difMoney'];
            $info['remark_']         = $data['remark_'];
            $info['wbs']         = $data['wbs'];
            $info['gdnumber']         = $data['gdnumber'];
            $info['xd_name']         = $data['xd_name'];
            $info['xd_order']         = $data['xd_order'];
			if ($type==10) {
			    $info['taxRate']  = (float)$data['taxRate'];
			}
			$info['pinYin']       = $data['pinYin'];
			if (strlen($data['linkMans'])>0) {
				$list = (array)json_decode($data['linkMans'],true);
				foreach ($list as $arr=>$row) {
					$v[$arr]['address']         = $row['address'];
					$v[$arr]['city']            = $row['city'];
					$v[$arr]['contactId']       = $id;
					$v[$arr]['county']          = $row['county'];
					$v[$arr]['email']           = isset($row['email']) ? $row['email'] : '';
					$v[$arr]['first']           = $row['linkFirst']==1 ? true : '';
					$v[$arr]['id']              = $id;
					$v[$arr]['im']              = $row['linkIm'];
					$v[$arr]['mobile']          = $row['linkMobile'];
					$v[$arr]['name']            = $row['linkName'];
					$v[$arr]['phone']           = $row['linkPhone'];
					$v[$arr]['province']        = $row['province'];
					$v[$arr]['tempId']          = 0;
				}
		    }
			$info['links']  = isset($v) ? $v : array();
			$json['status'] = 200;
			$json['msg']    = 'success';
			$json['data']   = $info;
			die(json_encode($json));
		}
		str_alert(-1,'没有数据');
	}

	//新增
	public function add(){
		$data = $this->validform($this->input->post(NULL,TRUE));
		switch ($data['type']) {
			case 10:
				$this->common_model->checkpurview(59);
				$success = '新增客户:';
				break;
			case -10:
				$this->common_model->checkpurview(64);
				$success = '新增供应商:';
				break;
			default:
				str_alert(-1,'参数错误');
		}
		$this->mysql_model->get_count('contact',array('isDelete'=>0,'type'=>$data['type'],'number'=>$data['number'])) > 0 && str_alert(-1,'编号重复');
		$data = elements(array(
					'name','number','amount','beginDate','cCategory',
					'cCategoryName','cLevel','cLevelName','linkMans','xd_name','xd_order','wbs','gdnumber','remark_'
					,'periodMoney','remark','type','difMoney'),$data,NULL);
		$sql = $this->mysql_model->insert('contact',$data);
		if ($sql) {
			$data['id'] = $sql;
			$data['cCategory'] = intval($data['cCategory']);
			$data['linkMans']  = (array)json_decode($data['linkMans'],true);
			$this->common_model->logs($success.$data['name']);
			str_alert(200,'success',$data);
		}
		str_alert(-1,'添加失败');
	}


	//修改
	public function update(){
		$data = $this->validform($this->input->post(NULL,TRUE));
		switch ($data['type']) {
			case 10:
				$this->common_model->checkpurview(60);
				$success = '修改客户:';
				break;
			case -10:
				$this->common_model->checkpurview(65);
				$success = '修改供应商:';
				break;
			default:
				str_alert(-1,'参数错误');
		}
		$this->mysql_model->get_count('contact',array('id !='=>$data['id'],'isDelete'=>0,'type'=>$data['type'],'number'=>$data['number'])) > 0 && str_alert(-1,'编号重复');
		$info = elements(array(
					'name','number','amount','beginDate','cCategory',
					'cCategoryName','cLevel','cLevelName','linkMans','xd_name','xd_order','wbs','gdnumber','remark_'
					,'periodMoney','remark','type','difMoney','remark_','wbs','gdnumber'),$data,NULL);
		$sql = $this->mysql_model->update('contact',$info,array('id'=>$data['id']));
		if ($sql) {
			$data['cCategory']    = intval($data['cCategory']);
			$data['customerType'] = $data['cCategoryName'];
			$data['linkMans']     = (array)json_decode($data['linkMans'],true);
			$this->common_model->logs($success.$data['name']);
			str_alert(200,'success',$data);
		}
		str_alert(-1,'更新失败');
	}

	//删除
	public function delete(){
	    $id   = str_enhtml($this->input->post('id',TRUE));
		$type = intval($this->input->get_post('type',TRUE))==10 ? 10 : -10;
		switch ($type) {
			case 10:
				$this->common_model->checkpurview(61);
				$success = '删除客户:';
				break;
			case -10:
				$this->common_model->checkpurview(66);
				$success = '删除供应商:';
				break;
			default:
				str_alert(-1,'参数错误');
		}
		$data = $this->mysql_model->get_results('contact','(id in('.$id.'))');
		if (count($data) > 0) {
		    $info['isDelete'] = 1;
		    $this->mysql_model->get_count('invoice','(isDelete=0) and (buId in('.$id.'))')>0 && str_alert(-1,'不能删除有业务往来的客户或供应商！');
		   // $sql = $this->mysql_model->update('contact',$info,'(id in('.$id.'))');
		   $sql = $this->mysql_model->delete('contact','(id in('.$id.'))');
		    if ($sql) {
			    $name = array_column($data,'name');
				$this->common_model->logs($success.'ID='.$id.' 名称:'.join(',',$name));
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			}
		}
		str_alert(-1,'客户或供应商不存在');
	}


	//状态
	public function disable(){
		$disable = intval($this->input->post('disable',TRUE));
		$id = str_enhtml($this->input->post('contactIds',TRUE));
		if($disable==1){
            $this->common_model->checkpurview(226);
        }else{
            $this->common_model->checkpurview(227);
        }
		if (strlen($id) > 0) {
			$sql = $this->mysql_model->update('contact',array('disable'=>$disable),'(id in('.$id.'))');
		    if ($sql) {
				$this->common_model->logs('客户'.$disable==1?'禁用':'启用'.':ID:'.$id.'');
				str_alert(200,'success');
			}
		}
		str_alert(-1,'操作失败');
	}

    //设计状态
    public function design(){
        $design = intval($this->input->post('design',TRUE));
        $id = str_enhtml($this->input->post('contactIds',TRUE));
        if($design==1){
            $this->common_model->checkpurview(228);
        }else{
            $this->common_model->checkpurview(229);
        }
        if (strlen($id) > 0) {
            $sql = $this->mysql_model->update('contact',array('design'=>$design),'(id in('.$id.'))');
            if ($sql) {
                $this->common_model->logs('客户'.$design==1?'已设计':'未设计'.':ID:'.$id.'');
                str_alert(200,'success');
            }
        }
        str_alert(-1,'操作失败');
    }

    //申请状态
    public function apply(){
        $apply = intval($this->input->post('apply',TRUE));
        $id = str_enhtml($this->input->post('contactIds',TRUE));
        if($apply==1){
            $this->common_model->checkpurview(230);
        }else{
            $this->common_model->checkpurview(231);
        }
        if (strlen($id) > 0) {
            $sql = $this->mysql_model->update('contact',array('apply'=>$apply),'(id in('.$id.'))');
            if ($sql) {
                $this->common_model->logs('客户'.$apply==1?'已申请':'未申请'.':ID:'.$id.'');
                str_alert(200,'success');
            }
        }
        str_alert(-1,'操作失败');
    }

    //出库状态
    public function chukuStatus(){
        $this->common_model->checkpurview(232);
        $status = intval($this->input->post('chuku_status',TRUE));
        $id = $this->input->post('contactIds',TRUE);
        if(is_numeric($id)){
            $id = [$id];
        }
        if (is_array($id)) {
            $this->db->trans_start();
            //修改orders_info里仓库状态
            $this->db->where_in('iid',$id)->update('ci_orders_info',['chuku_status'=>1]);
            // foreach ($id as $value){
            //     $orders_infos = $this->db->select('billNo,ordernumber,goodsnumber')->where('iid',$value)->get('ci_orders_info')->result_array();
            //     if(is_array($orders_infos)){
            //         foreach ($orders_infos as $value){
            //             //修改ci_cangku_huizong备注
            //             $this->db->where('ordernumber',$value['ordernumber'])->where('goodsnumber',$value['goodsnumber'])->update('ci_cangku_huizong',['beizhu'=>$value['billNo']]);

            //         }
            //     }else{
            //         str_alert(-1,'操作失败');
            //     }
            // }


            $trans = $this->db->trans_complete();
            if ($trans) {
                $this->common_model->logs('客户'.$status==1?'已到货':'未到货'.':ID:'.$id.'');
                str_alert(200,'success');
            }
        }
        str_alert(-1,'操作失败');
    }

    //审核状态
    public function check(){
        $apply = intval($this->input->post('check',TRUE));
        $id = str_enhtml($this->input->post('contactIds',TRUE));
        if($apply==1){
            $this->common_model->checkpurview(233);
        }else{
            $this->common_model->checkpurview(234);
        }
        if (strlen($id) > 0) {
            $sql = $this->mysql_model->update('contact',array('check'=>$apply),'(id in('.$id.'))');
            if ($sql) {
                $this->common_model->logs('客户'.$apply==1?'已核对':'未核对'.':ID:'.$id.'');
                str_alert(200,'success');
            }
        }
        str_alert(-1,'操作失败');
    }

	//公共验证
	private function validform($data) {
	    $this->load->library('lib_pinyin');
	    strlen($data['name']) < 1 && str_alert(-1,'名称不能为空');
		strlen($data['number']) < 1 && str_alert(-1,'编号不能为空');
		$data['cCategory']     = intval($data['cCategory']);
		$data['cLevel']        = (float)$data['cLevel'];
		$data['taxRate']       = isset($data['taxRate']) ? (float)$data['taxRate'] :0;
		$data['periodMoney']   = (float)$data['periodMoney'];
		$data['amount']        = (float)$data['amount'];
		$data['linkMans']      = $data['linkMans'] ? $data['linkMans'] :"[]";
		$data['beginDate']     = $data['beginDate'] ? $data['beginDate'] : date('Y-m-d');
		$data['type']          = intval($this->input->get_post('type',TRUE))==10 ? 10 : -10;
		$data['pinYin']        = $this->lib_pinyin->str2pinyin($data['name']);
		$data['contact']       = $data['number'].' '.$data['name'];
		$data['difMoney']      = $data['amount'] - $data['periodMoney'];
		$data['cCategoryName'] = $this->mysql_model->get_row('category',array('id'=>$data['cCategory']),'name');
		$data['cCategory'] < 1 && str_alert(-1,'类别名称不能为空');
        $data['xd_name'] = trim($data['xd_name']);
        $data['xd_order'] = trim($data['xd_order']);
        $data['wbs'] = trim($data['wbs']);
        $data['gdnumber'] = trim($data['gdnumber']);
        $data['remark_'] = trim($data['remark_']);
        $data['xd_name'] === trim($data['name']) && str_alert(-1,'下达名称不能与项目名称相同');
        $data['xd_order'] === trim($data['number']) && str_alert(-1,'下达编号不能与项目编号相同');

        $id   = intval($this->input->post('id',TRUE));
        if(!empty($data['xd_name'])){
            $where = array();
            $where['xd_name'] = $data['xd_name'];
            $where['isDelete']  = 0;
            $where['id !='] = $id>0 ? $id :'';
            $res = $this->mysql_model->get_rows('contact',array_filter($where));
            if (count($res)>0) {
                str_alert(-1,'下达名称重复');
            }

            $where = array();
            $where['name'] = $data['xd_name'];
            $where['isDelete']  = 0;
            $where['id !='] = $id>0 ? $id :'';
            $res = $this->mysql_model->get_rows('contact',array_filter($where));
            if (count($res)>0) {
                str_alert(-1,'下达名称已存在于项目名');
            }
        }

        if(!empty($data['xd_order'])){
            $where = array();
            $where['xd_order'] = $data['xd_order'];
            $where['isDelete']  = 0;
            $where['id !='] = $id>0 ? $id :'';
            $res = $this->mysql_model->get_rows('contact',array_filter($where));
            if (count($res)>0) {
                str_alert(-1,'下达编号名称重复');
            }

            $where = array();
            $where['number'] = $data['xd_order'];
            $where['isDelete']  = 0;
            $where['id !='] = $id>0 ? $id :'';
            $res = $this->mysql_model->get_rows('contact',array_filter($where));
            if (count($res)>0) {
                str_alert(-1,'下达编号已存在于项目编号');
            }
        }


		return $data;
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
