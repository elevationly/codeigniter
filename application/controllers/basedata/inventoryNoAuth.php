<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InventoryNoAuth extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    // 仓储出库汇总
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
        $receiveTime = str_enhtml($this->input->get_post('receiveTime',TRUE));
        $where = '(a.isDelete=0)';
        $where .= $skey ? ' and a.goodsnumber="'.$skey.'"': '';
        $where .= $ordernumber ? ' and a.ordernumber="'.$ordernumber.'"': '';
        $where .= $Arrivaltime ? ' and a.flagtime="'.$Arrivaltime.'"': '';
        $where .= $sign ? ' and a.sign like "%'.$sign.'%"': '';
        $where .= $beizhu ? ' and a.beizhu like "%'.$beizhu.'%"': '';
        $where .= $flagcontact ? ' and a.flagcontact like "%'.$flagcontact.'%"': '';
        //$where .= $flagNo ? ' and a.flagNo="'.$flagNo.'"': '';
        $where .= $receiveStatus !== '' ? ' and a.receive_status="'.$receiveStatus.'"': '';
        $where .= $receiveTime !== '' ? ' and a.receive_time="'.$receiveTime.'"': '';
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
    // 标记领用状态
    public function receive () {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $time = isset($_POST['time']) ? $_POST['time'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';

        $info=array(
            'receive_status'=> 1,
            'receive_note'  => $note,
            'receive_time'  => $time
        );
        if ($id == '') {
            $json['status'] = 500;
            $json['msg']    = '参数丢失';
            die(json_encode($json));
        }
        $this->mysql_model->update('cangku_huizong',$info,'(id in('.$id.'))');

        $json['status'] = 200;
        $json['msg']    = 'success';
        die(json_encode($json));
    }
    // 标记领用状态
    public function removeReceive () {
        $id = isset($_POST['id']) ? $_POST['id'] : '';

        $info=array(
            'receive_status'=>0,
            'receive_note'=>'',
            'receive_time'=>''
        );

        if ($id == '') {
            $json['status'] = 500;
            $json['msg']    = '参数丢失';
            die(json_encode($json));
        }

        $this->mysql_model->update('cangku_huizong',$info,'(id in('.$id.'))');


        $json['status'] = 200;
        $json['msg']    = 'success';
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

    public function queryreceivetime(){
        $receive_time = str_enhtml($this->input->get_post('receive_time',TRUE));
        $query=$this->db->query("select distinct(receive_time) from ci_cangku_huizong where receive_time like '%$receive_time%' and isDelete=0");
        $result=$query->result_array();
        foreach ($result as $arr=>$row) {
            $json[$arr]['receive_time']=$row['receive_time'];
        }
        //$json['data']=isset($v) ? $v :'';
        die(json_encode($json));
    }

    public function exportcangkuhuizong() {
//        $this->common_model->checkpurview(72);
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
        $receiveTime = str_enhtml($this->input->get_post('receiveTime',TRUE));
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
        $where .= $receiveTime != '' ? ' and a.receive_time="'.$receiveTime.'"' : '';
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
        $this->load->view('settings/goods-exportcangkuhuizong-new',$data);

    }

    public function token() {
       die(token());
    }

    public function logout() {
        $this->session->sess_destroy();
    }
}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
