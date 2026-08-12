<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvSa extends MY_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys  = $this->session->userdata('jxcsys');
    }

	public function index() {
	    $action = $this->input->get('action',TRUE);
		switch ($action) {
			case 'initSale':
			    $this->common_model->checkpurview(2);
				// $data['billNo'] = $this->str_number1();
				$data['billNo']=$this->mysql_model->max_bill_no();
			    $this->load->view('scm/invSa/initSale',$data);
				break;
			case 'editSale':
			    $this->common_model->checkpurview(1);
				$id = intval($this->input->get_post('id',TRUE));
				$data['billNo'] = $this->mysql_model->get_row('invoice',array('id'=>$id,'billType'=>'PUR'),'billNo');
			    $this->load->view('scm/invSa/initSale',$data);
				break;
			case 'initSaleList':
			    $this->common_model->checkpurview(1);
			    $this->load->view('scm/invSa/initSaleList');
				break;
            case 'initSaleListSearch':
                $this->common_model->checkpurview(1);
                $this->load->view('scm/invSa/initSaleListSearch');
                break;
            case 'initSaleListCancel':
                $this->common_model->checkpurview(1);
                $this->load->view('scm/invSa/initSaleListCancel');
                break;
            case 'initSaleListzuofei':
			    $this->common_model->checkpurview(1);
			    $this->load->view('scm/invSa/initSaleListzuofei');
				break;
			case 'initSaleLists':
			    $this->common_model->checkpurview(1);
			    $this->load->view('scm/invSa/initSaleLists');
				break;
			case 'initSaleListyj':
			    $this->common_model->checkpurview(1);
			    $this->load->view('scm/invSa/initSaleListyj');
				break;
			default:
			    $this->common_model->checkpurview(1);
			    $this->purList();
		}
	}

	public function backupshow(){

		header ( "content-Type: text/html; charset=utf-8" );
		//备份数据库
		$host=$this->db->hostname;
		$user=$this->db->username;//数据库账号
		$password=$this->db->password;//数据库密码
		$dbname=$this->db->database;//数据库名称
		//这里的账号、密码、名称都是从页面传过来的
		if(!mysql_connect($host,$user,$password)) //连接mysql数据库
		{
		 echo '数据库连接失败，请核对后再试';
		 exit;
		}
		if(!mysql_select_db($dbname)) //是否存在该数据库
		{
		 echo '不存在数据库:'.$dbname.',请核对后再试';
		 exit;
		}
		mysql_query("set names 'utf8'");
		$mysql= "set charset utf8;\r\n";
		$q1=mysql_query("show tables");
		while($t=mysql_fetch_array($q1)){
		  $table=$t[0];
		  $q2=mysql_query("show create table `$table`");
		  $sql=mysql_fetch_array($q2);
		  $mysql.=$sql['Create Table'].";\r\n";
		  $q3=mysql_query("select * from `$table`");
		  while($data=mysql_fetch_assoc($q3)){
			$keys=array_keys($data);
			$keys=array_map('addslashes',$keys);
			$keys=join('`,`',$keys);
			$keys="`".$keys."`";
			$vals=array_values($data);
			$vals=array_map('addslashes',$vals);
			$vals=join("','",$vals);
			$vals="'".$vals."'";
			$mysql.="insert into `$table`($keys) values($vals);\r\n";
		  }
		}
		$filename="data/backup/".$dbname.date('Ymjgi').".sql"; //存放路径，默认存放到项目最外层
		$fp = fopen($filename,'w');
		fputs($fp,$mysql);
		fclose($fp);
		$json['status']              = 200;
		$json['msg']                 = 'success';
		die(json_encode($json));
	}
	public function queryBackupFile(){
		$result=$this->dir_size("data/backup/","");
		foreach($result as $arr=>$row){
			$v[$arr]['filename']           = $row['name'];
			$v[$arr]['createTime']           = $row['time'];
			$v[$arr]['size']           = $row['size'];
		}
		$json['status']              = 200;
		$json['msg']                 = 'success';
		$json['data']['items']        = isset($v) ? $v : array();
		 die(json_encode($json));
		//print_r($result);
		//exit();
	}

	public function recover(){
		$id   = $this->input->get('id',TRUE);

		$filename = $id;
		$host=$this->db->hostname;
		$user=$this->db->username;//数据库账号
		$password=$this->db->password;//数据库密码
		$dbname=$this->db->database;//数据库名称

		mysql_connect($host,$user,$password);

		mysql_select_db($dbname);

		$mysql_file="data/backup/".$filename; //指定要恢复的MySQL备份文件路径,请自已修改此路径

		$this->restore($mysql_file); //执行MySQL恢复命令


	}

	function restore($fname){
		if (file_exists($fname)) {
			$sql_value="";
			$cg=0;
			$sb=0;
			$sqls=file($fname);
			foreach($sqls as $sql){
				$sql_value.=$sql;
			}
			$a=explode(";\r\n", $sql_value); //根据";\r\n"条件对数据库中分条执行
			$total=count($a)-1;
			mysql_query("set names 'utf8'");
			for ($i=0;$i<$total;$i++){
				mysql_query("set names 'utf8'");
				//执行命令
				if(mysql_query($a[$i])){
					$cg+=1;
				}else{
					$sb+=1;
					$sb_command[$sb]=$a[$i];
				}
			}
			//echo "操作完毕，共处理 $total 条命令，成功 $cg 条，失败 $sb 条";
			//显示错误信息
			if ($sb>0){
				//echo "<hr><br><br>失败命令如下：<br>";
				for ($ii=1;$ii<=$sb;$ii++){
					//echo "<p><b>第 ".$ii." 条命令（内容如下）：</b><br>".$sb_command[$ii]."</p><br>";
				}
			}
			$json['status']              = 200;
			$json['msg']                 = '恢复备份成功';

			die(json_encode($json));

		}else{
			//echo "MySQL备份文件不存在，请检查文件路径是否正确！";
		}
	}

	public function deleteBackupFile(){
		$id   = $this->input->get('id',TRUE);
		$path="data/backup/".$id;
		$bool=$this->delDirAndFile($path);
		if($bool){
			$json['status']              = 200;
			$json['msg']                 = '删除备份成功';
		}else{
			$json['status']              = 400;
			$json['msg']                 = '删除备份失败';
		}


		die(json_encode($json));
	}

	function delDirAndFile($path, $delDir = FALSE) {
		$handle = opendir($path);
		if ($handle) {
			while (false !== ( $item = readdir($handle) )) {
				if ($item != "." && $item != "..")
					is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
			}
			closedir($handle);
			if ($delDir)
				return rmdir($path);
		}else {
			if (file_exists($path)) {
				return unlink($path);
			} else {
				return FALSE;
			}
		}
	}

	function dir_size($dir,$url){
     $dh = @opendir($dir);             //打开目录，返回一个目录流
     $return = array();
      $i = 0;
          while($file = @readdir($dh)){     //循环读取目录下的文件
			$arr=array();
             if($file!='.' and $file!='..'){
              $path = $dir.'/'.$file;     //设置目录，用于含有子目录的情况
              if(is_dir($path)){
          }elseif(is_file($path)){
              $filesize[] =  round((filesize($path)/1024),2);//获取文件大小
              $filename[] = $path;//获取文件名称
              $filetime[] = date("Y-m-d H:i:s",filemtime($path));//获取文件最近修改日期

              $arr['name'] =  $file;
			  $arr['size'] =  round((filesize($path)/1024),2)."KB";
			  $arr['time'] =  date("Y-m-d H:i:s",filemtime($path));
			  $return[]=$arr;
			}
          }
          }
          @closedir($dh);             //关闭目录流
          array_multisort($filesize,SORT_DESC,SORT_NUMERIC, $return);//按大小排序
          //array_multisort($filename,SORT_DESC,SORT_STRING, $files);//按名字排序
          //array_multisort($filetime,SORT_DESC,SORT_STRING, $files);//按时间排序
          return $return;               //返回文件
     }

     //领料单查询
	public function purList() {
        $param = $this->purListWhere();
        $where = $param['where'];
        $order = $param['order'];
        $rows = $param['rows'];
        $page = $param['page'];
        $checked = $param['checked'];
        $status = $param['status'];
        $type = $param['type'];
            //领料单汇总
			if($type=="chukudan"){
				//echo $type."=============";
				$lists = $this->data_model->get_orders_info($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows,2,$checked);
				//var_dump($this->db->last_query());
                //var_dump($lists);
					//exit();
					foreach ($lists as $arr=>$row) {
                        //查询仓库汇总表是否出库
                        $is_chuku = '未出库';
                        $c_res = $row['chuku_status'];
                        if($c_res){
                            $is_chuku = '已出库';
                        }
                        //判断是否超期
                        $is_chaoqi = '';
                        if($is_chuku == '未出库' && strtotime($row['billDate'])<strtotime('-14 day')){
                            $is_chaoqi = '超期未领用';
                        }
                        //出库状态
                        $chuku_status = $is_chuku == '未出库' ? '0' : '1';

						$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
						$v[$arr]['ordernumber']    = $row['ordernumber'];
                        $v[$arr]['billDate']     = $row['billDate'];
                        $v[$arr]['locationNames']  = $row['locationName'];
                        $v[$arr]['is_chaoqi']  = $is_chaoqi;
                        $v[$arr]['chuku_status']  = $chuku_status;
                        //                         新加字段
                        $v[$arr]['customerType']  = $row['customerType']; // 项目类别
                        $v[$arr]['delete']  = intval($row['disable'])==1 ? true : false; // 状态
                        $v[$arr]['design']  = intval($row['design'])==1 ? true : false; // 项目设计
                        $v[$arr]['apply']  = intval($row['apply'])==1 ? true : false; // 物资申请
                        $v[$arr]['check']  = intval($row['cCheck'])==1 ? true : false; // 是否核对
                    }
			}
            //物资领料单 查询
			else {
				$list = $this->data_model->get_orders($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
				if(empty($list)){
				    $v = [];
                }else{
                    //die($this->db->last_query());
                    //记录要删除的key
                    $del_arr = [];
                    $id_arr = array_column($list,'id');
                    $rders_info_arr = $this->db->select('iid,locationName,chuku_status')->where_in('iid',$id_arr)->get('ci_orders_info')->result_array();
                    //仓库
                    $locationNames_tmp = [];
                    //出库与否
                    $c_res_tmp = [];
                    if(is_array($rders_info_arr)){
                        foreach ($rders_info_arr as $value) {
                            $locationNames_tmp[$value['iid']][] = $value['locationName'];
                            if($value['chuku_status'] == 1){
                                $c_res_tmp[$value['iid']] = 1;
                            }
                        }
                    }
                    foreach ($list as $arr=>$row) {
                        //查询仓库位置
                        //$locationNames = $this->db->select('locationName,ordernumber,goodsnumber')->where('iid',$row['id'])->get('ci_orders_info')->result_array();
                        //$locationNames_tmp = [];
                        $locationNames_ = '';
                        if(!empty($locationNames_tmp)){
                            /*foreach ($locationNames as $value){
                                $locationNames_tmp[] = $value['locationName'];
                            }*/
                            $locationNames_ = implode('、',array_unique($locationNames_tmp[$row['id']]));
                        }
                        //查询仓库汇总表是否出库
                        $is_chuku = '未出库';
                        //$c_res = $this->db->select('*')->where('iid',$row['id'])->where('chuku_status',1)->get('ci_orders_info')->row_array();
                        if(isset($c_res_tmp[$row['id']]) && $c_res_tmp[$row['id']] === 1){
                            $is_chuku = '已出库';
                        }
                        //判断是否超期
                        $is_chaoqi = '';
                        if($is_chuku == '未出库' && strtotime($row['billDate'])<strtotime('-14 day')){
                            $is_chaoqi = '超期未领用';
                        }
                        //出库状态
                        $chuku_status = $is_chuku == '未出库' ? '0' : '1';
                        //如果搜索条件 出库状态 就删除
                        if($status == 2 && $chuku_status==1){
                            $del_arr[] = $arr;
                        }if($status == 1 && $chuku_status==0 ){
                            $del_arr[] = $arr;
                        }

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
                        $v[$arr]['description']  = $is_chuku;
                        $v[$arr]['billNo']       = $row['billNo'];
                        $v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
                        $v[$arr]['userName']     = $row['userName'];
                        $v[$arr]['transTypeName']= $row['transTypeName'];
                        $v[$arr]['liname']= $row['liname'];
                        $v[$arr]['disEditable']  = 0;
                        $v[$arr]['locationNames']  = $locationNames_;
                        $v[$arr]['is_chaoqi']  = $is_chaoqi;
                        $v[$arr]['chuku_status']  = $chuku_status;

                    }
                }
			foreach ($del_arr as $del){
				    unset($v[$del]);

            }
            $v = array_merge($v);


		}
        $json['status']              = 200;
		$json['msg']                 = 'success';
		$json['data']['page']        = $page;
		if($type=="chukudan"){
			$json['data']['records']     = $this->data_model->get_orders_info($where,3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}else{
			$json['data']['records']     = $this->data_model->get_orders($where,3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}

		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}

    public function purListWhere()
    {
        $param = [];
        $type   = $this->input->get('type',TRUE);
        $checked   = $this->input->get('checked',TRUE);
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);

        $sidx = str_enhtml($this->input->get_post('sidx',TRUE));
        $sord = str_enhtml($this->input->get_post('sord',TRUE));
        $transType = intval($this->input->get_post('transType',TRUE));
        $status = $this->input->get_post('status',TRUE);
        $matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $matchCon1=explode(" ",$matchCon);
        if(count($matchCon1)>1){
            $matchCon=$matchCon1[0];
        }



        $mname  = str_enhtml($this->input->get_post('mname',TRUE));
        $mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
        $ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
        $mdescription  = str_enhtml($this->input->get_post('mdescription',TRUE));
        $billNo  = str_enhtml($this->input->get_post('billNo',TRUE));

        $matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
        $mname=$mname=='请输入领料人'?'':$mname;
        $mnumber=$mnumber=='请输入物料编码'?'':$mnumber;
        $mdescription=$mdescription=='请输入物料描述'?'':$mdescription;
        $ordernumber=$ordernumber=='请输入订单编号'?'':$ordernumber;
        $billNo=$billNo=='请输入单据编号'?'':$billNo;


        $tmparray = explode("*",$mdescription);
        $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
        $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
        $order = $sidx ? $sidx.' '.$sord :' a.id desc';
        if($type=="chukudan"){
            $where = 'a.isDelete=0 ';
            $where .= $checked == 3 ? 'and d.checked=3 ' : 'and d.checked!=3 ';
            $where .= $mname ? ' and a.liname="'.$mname.'"': '';
            $where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
            $where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
            $where .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
            //$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
            //$where .= $matchCon  ? ' and c.name like "%'.$matchCon.'%"' : '';
            $where .= $matchCon  ? ' and (c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%")' : '';
            $where .= $billNo ? " and a.billNo='$billNo'" : '';
            //$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
            //$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
            //$where .= $this->common_model->get_admin_purview();
        }else{
            $where = 'a.isDelete=0 and a.billType="PUR" and a.checked!=3';
            $where .= $transType ? ' and a.transType='.$transType : '';
            $where .= $mname ? ' and a.liname="'.$mname.'"': '';
            $where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': '';
            //$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
            $where .= $matchCon  ? ' and (b.name like "%'.$matchCon.'%" or b.number like "%'.$matchCon.'%")' : '';

            //$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
            //$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
            //$where .= $this->common_model->get_admin_purview();
        }
        $param['where'] = $where;
        $param['order'] = $order;
        $param['rows'] = $rows;
        $param['page'] = $page;
        $param['checked'] = $checked;
        $param['status'] = $status;
        $param['type'] = $type;

        return $param;
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
		$ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$mdescription  = str_enhtml($this->input->get_post('mdescription',TRUE));

          $matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
         $mname=$mname=='请输入领料人'?'':$mname;
          $mnumber=$mnumber=='请输入物料编码'?'':$mnumber;
           $mdescription=$mdescription=='请输入物料描述'?'':$mdescription;
           $ordernumber=$ordernumber=='请输入订单编号'?'':$ordernumber;


		$tmparray = explode("*",$mdescription);
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		if($type=="chukudan"){
		$where = 'a.isDelete=0 and d.checked=3';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
		$where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		//$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
		//$where .= $matchCon  ? ' and c.name like "%'.$matchCon.'%"' : '';
			$where .= $matchCon  ? ' and (c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%")' : '';

		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();
		}else{
			$where = 'a.isDelete=0 and a.billType="PUR" and a.checked=3';
		$where .= $transType ? ' and a.transType='.$transType : '';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': '';
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
		$where .= $matchCon  ? ' and (b.name like "%'.$matchCon.'%" or b.number like "%'.$matchCon.'%")' : '';

		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();
		}

			if($type=="chukudan"){
				//echo $type."=============";
				$lists = $this->data_model->get_orders_info($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
				//print_r($lists);
					//exit();
					foreach ($lists as $arr=>$row) {
						$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
						$v[$arr]['ordernumber']    = $row['ordernumber'];
						$v[$arr]['locationNames']  = isset($row['locationName']) ? $row['locationName'] : '';
					}
			}else{

				$list = $this->data_model->get_orders($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
				$locationNames_tmp = [];
				if (!empty($list)) {
					$id_arr = array_column($list, 'id');
					if (!empty($id_arr)) {
						$orders_info_arr = $this->db->select('iid,locationName')->where_in('iid', $id_arr)->get('ci_orders_info')->result_array();
						if (is_array($orders_info_arr)) {
							foreach ($orders_info_arr as $value) {
								if ($value['locationName'] !== '' && $value['locationName'] !== null) {
									$locationNames_tmp[$value['iid']][] = $value['locationName'];
								}
							}
						}
					}
				}
				foreach ($list as $arr=>$row) {
				$locationNames_ = '';
				if (!empty($locationNames_tmp[$row['id']])) {
					$locationNames_ = implode('、', array_unique($locationNames_tmp[$row['id']]));
				}
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
				$v[$arr]['locationNames'] = $locationNames_;
			}


		}
        /*$last = $this->db->last_query();
        die(json_encode(['a'=>$last]));*/

		$json['status']              = 200;
		$json['msg']                 = 'success';
		$json['data']['page']        = $page;
		if($type=="chukudan"){
			$json['data']['records']     = $this->data_model->get_orders_info($where,3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}else{
			$json['data']['records']     = $this->data_model->get_orders($where,3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}

		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}

	public function purListyj(){

		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);

		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$matchCon=$matchCon=='请输入物料描述'?'':$matchCon;
		$tmparray = explode("*",$matchCon);
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$mnumber=$mnumber=='请输入物料编码'?'':$mnumber;
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';

		$wheres = 'a.isDelete=0';
		$wheres .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
		$wheres .= $matchCon ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		$lists = $this->data_model->get_stock_yj($wheres,' order by '.$order.' limit '.$rows*($page-1).','.$rows);
		//print_r($lists);
		//exit();
		//$query=$this->db->query("select a.goodsnumber,a.mdescription,sum(a.inventoryNew) as numyj,a.mainUnit,b.yjnum from ci_stock as a left join ci_stock_yj as b on a.id=b.sid group by a.goodsnumber having a.goodsnumber in (select number from ci_stock_yj)");
		//$result=$query->result_array();
		foreach ($lists as $arr=>$row) {
			$v[$arr]['id']           = $row['id'];
			$v[$arr]['goodsnumber']           = $row['goodsnumber'];
			$v[$arr]['mdescription']           = $row['mdescription'];
			$v[$arr]['numyj']           = $row['numyj'];
			$v[$arr]['mainUnit']           = $row['mainUnit'];
			$v[$arr]['yjnum']           = $row['yjnum'];
			if($row['numyj']<$row['yjnum']){
				$v[$arr]['Remarks']           = "<span style='color:red'>库存数量不足，请申请补充！</span>";
			}
		}
		$json['status']              = 200;
		$json['msg']                 = 'success';
		$json['data']['page']        = $page;
		$json['data']['records']     = $this->data_model->get_stock_yj($wheres,"",3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);

		$json['data']['rows']        = isset($v) ? $v : array();
		 //print_r($json);
		//exit();
		 die(json_encode($json));
	}

	//出库
	public function purLists() {
		$type   = $this->input->get('type',TRUE);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);

		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		 $matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		//$row=split(' ',$matchCon);
		//$matchCon=$row[1];
	  $matchCon1=explode(" ",$matchCon);
	   if(count($matchCon1)>1){
		   $matchCon=$matchCon1[0];
		}

		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$mdescription  = str_enhtml($this->input->get_post('mdescription',TRUE));
        $billNo  = str_enhtml($this->input->get_post('billNo',TRUE));

        $matchCon=$matchCon=='请输入项目名称'?'':$matchCon;
         $mname=$mname=='请输入领料人'?'':$mname;
          $mnumber=$mnumber=='请输入物料编号'?'':$mnumber;
           $mdescription=$mdescription=='请输入物料描述'?'':$mdescription;
           $ordernumber=$ordernumber=='请输入订单编号'?'':$ordernumber;
        $billNo=$billNo=='请输入单据编号'?'':$billNo;


		$tmparray = explode("*",$mdescription);
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		if($type=="chukudan"){
		$where = 'a.isDelete=0 and d.checked!=3';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
		 $where .= $matchCon  ? ' and ( c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%")' : '';
		 $where .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		$wheres = 'a.isDelete=0 and d.checked!=3';
		$wheres .= $mname ? ' and a.liname="'.$mname.'"': '';
		 $wheres.=$matchCon  ? ' and ( c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%" )' : '';
		  $wheres .= $mdescription ? ' and (a.mdescription like "%'.$tmparray[0].'%" and a.mdescription like "%'.$tmparray[1].'%")': '';
		//$goodswhere.= $mnumber  ? ' and number="'.$mnumber.'"': '';


		 $wheres .= $mnumber  ? ' and b.number="'.$mnumber.'"': '';
            $where .= $billNo  ? ' and a.billNo="'.$billNo.'"': '';
            $wheres .= $billNo  ? ' and a.billNo="'.$billNo.'"': '';
            //$wheres .= $matchCon  ? ' and c.name like "%'.$matchCon.'%" or c.number like "%'.$matchCon.'%"' : '';
		//$where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
		//$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';


		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();

		}else{
			$where = 'a.isDelete=0 and a.billType="PUR"';
		$where .= $transType ? ' and a.transType='.$transType : '';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': '';
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
		 $where .= $matchCon  ? ' and b.name like "%'.$matchCon.'%" or b.number like "%'.$matchCon.'%"' : '';
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_admin_purview();
		}

			if($type=="chukudan"){
				//echo $type."=============";
				$keyarr=0;
				$lists = $this->data_model->get_orders_info($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
				//print_r($this->db->last_query());exit;
					foreach ($lists as $arr=>$row) {
						$v[$arr]['id']           = $row['id']."o";
						$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
						if($row['flag']=="已归档"){
							$v[$arr]['flag']    = "<span style='color:red;'>".$row['flag']."</span>";
						}else{
							$v[$arr]['flag']    = $row['flag'];
						}

						//$v[$arr]['ordernumber']    = $row['ordernumber'];
						$keyarr++;
					}
					$listss = $this->data_model->get_invoice_infos($wheres.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
                   // var_dump($this->db->last_query());exit;

					foreach ($listss as $arr=>$row) {
						$v[$arr+$keyarr]['id']           = $row['id']."i";
						$v[$arr+$keyarr]['goodsnumber']           = $row['invNumber'];
						$v[$arr+$keyarr]['mdescription']           = $row['mdescription'];
						$v[$arr+$keyarr]['totalQty']           = $row['qty'] * -1;
						$v[$arr+$keyarr]['mainUnit']           = $row['mainUnit'];
						$v[$arr+$keyarr]['price']           = $row['price'];
						$v[$arr+$keyarr]['amount']           = $row['qty']*$row['price'];
						$v[$arr+$keyarr]['BillName']           = $row['BillName'];
						$v[$arr+$keyarr]['billNo']           = $row['billNo'];
						$v[$arr+$keyarr]['contactName']    = $row['contactNo'].' '.$row['contactName'];
						$v[$arr+$keyarr]['liname']    = $row['liname'];
						$v[$arr+$keyarr]['description']    = $row['description'];
						$v[$arr+$keyarr]['flag']    = $row['flag'];
						if($row['flag']=="已归档"){
							$v[$arr+$keyarr]['flag']    = "<span style='color:red;'>".$row['flag']."</span>";
						}else{
							$v[$arr+$keyarr]['flag']    = $row['flag'];
						}
					}
			}else{

				$list = $this->data_model->get_orders($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);
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
			$num=$this->data_model->get_orders_info($where,3);
			$num+=$this->data_model->get_invoice_infos($wheres,3);
			$json['data']['records']     = $num;
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}else{
			$json['data']['records']     = $this->data_model->get_orders($where,3);
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		}

		$json['data']['rows']        = isset($v) ? $v : array();
		// print_r($v);
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
		$where = 'a.isDelete=0';
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
				$lists = $this->data_model->get_orders_info($where);
				//print_r($lists);
					//exit();
					// 计算总记录数
					$totalRecords = count($lists);
					// 计算分页偏移量
					$offset = ($page - 1) * $rows;
					// 分页处理
					$lists = array_slice($lists, $offset, $rows);
					
					foreach ($lists as $arr=>$row) {
						$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
						$v[$arr]['ordernumber']    = $row['ordernumber'];
					}
			}else{
				// 计算分页偏移量
				$offset = ($page - 1) * $rows;
				
				$list = $this->data_model->get_orders($where.' order by '.$order.' limit '.$offset.','.$rows);
				$locationNames_tmp = [];
				if (!empty($list)) {
					$id_arr = array_column($list, 'id');
					if (!empty($id_arr)) {
						$orders_info_arr = $this->db->select('iid,locationName')->where_in('iid', $id_arr)->get('ci_orders_info')->result_array();
						if (is_array($orders_info_arr)) {
							foreach ($orders_info_arr as $value) {
								if ($value['locationName'] !== '' && $value['locationName'] !== null) {
									$locationNames_tmp[$value['iid']][] = $value['locationName'];
								}
							}
						}
					}
				}
				foreach ($list as $arr=>$row) {
				$locationNames_ = '';
				if (!empty($locationNames_tmp[$row['id']])) {
					$locationNames_ = implode('、', array_unique($locationNames_tmp[$row['id']]));
				}
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
				$v[$arr]['locationNames'] = $locationNames_;
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


    /**
     * 领料单汇总导出excel
     */
	public function exportInvPu(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$row=split(' ',$matchCon);
		$matchCon=$row[1];
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and d.checked != 3';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
		$where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
		//$where = 'a.isDelete=0 and a.transType='.$transType.'';
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();
		//$data['list'] = $this->data_model->get_invoice($where.' order by '.$order);
		//$list = $this->data_model->get_invoice($where.' order by '.$order);
		//foreach ($list as $arr=>$row) {
				$lists = $this->data_model->get_orders_info($where.' order by a.id');
				//var_dump($this->db->last_query());exit();
			foreach ($lists as $arr=>$row) {
                //查询仓库汇总表是否出库
                $is_chuku = '未出库';
                $c_res = $row['chuku_status'];
                if($c_res){
                    $is_chuku = '已出库';
                }
                //判断是否超期
                $is_chaoqi = '';
                if($is_chuku == '未出库' && strtotime($row['billDate'])<strtotime('-14 day')){
                    $is_chaoqi = '超期未领用';
                }
				$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
                $v[$arr]['ordernumber']    = $row['ordernumber'];
                $v[$arr]['billDate']     = $row['billDate'];
                $v[$arr]['locationNames']  = $row['locationName'];
                $v[$arr]['is_chaoqi']  = $is_chaoqi;
                $v[$arr]['chuku_status']  = $is_chuku;
                //                         新加字段
                $v[$arr]['customerType']  = $row['customerType']; // 项目类别
                $v[$arr]['disable']  = intval($row['disable']); // 状态
                $v[$arr]['design']  = intval($row['design']); // 项目设计
                $v[$arr]['apply']  = intval($row['apply']); // 物资申请
                $v[$arr]['check']  = intval($row['cCheck']); // 是否核对

			}
		//}
		//print_r($v);
		//exit();
		$data['list']=$v;
        $data['design'] = ['已下达','计划外'];
        $data['apply'] = ['未送审','已送审'];
        $data['disable'] = ['项目启用','竣工禁用'];
        $data['check'] = ['未核对','已核对'];
		$this->load->view('scm/invSa/exportInvPu',$data);
	}

    /**
     * 作废单汇总导出excel
     */
    public function exportInvPuZuofei(){
        $this->common_model->checkpurview(5);
        $name = 'purchase_record_'.date('YmdHis').'.xls';
        sys_csv($name);
        $this->common_model->logs('导出采购单据:'.$name);
        $sidx = str_enhtml($this->input->get_post('sidx',TRUE));
        $sord = str_enhtml($this->input->get_post('sord',TRUE));
        $transType = intval($this->input->get_post('transType',TRUE));
        $matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
        $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
        $matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $row=split(' ',$matchCon);
        $matchCon=$row[1];
        $mname  = str_enhtml($this->input->get_post('mname',TRUE));
        $mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
        $ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
        $order = $sidx ? $sidx.' '.$sord :' a.id desc';
        $where = 'a.isDelete=0 and d.checked = 3';
        $where .= $mname ? ' and a.liname="'.$mname.'"': '';
        $where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
        $where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
        $where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
        //$where = 'a.isDelete=0 and a.transType='.$transType.'';
        //$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
        //$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
        //$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
        //$where .= $this->common_model->get_admin_purview();
        //$data['list'] = $this->data_model->get_invoice($where.' order by '.$order);
        //$list = $this->data_model->get_invoice($where.' order by '.$order);
        //foreach ($list as $arr=>$row) {
        $lists = $this->data_model->get_orders_info($where.' order by a.id',2,3);
        //print_r($this->db->last_query());exit();
        foreach ($lists as $arr=>$row) {
            $v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
            $v[$arr]['ordernumber']    = $row['ordernumber'];
        }
        //}
        //print_r($v);
        //exit();
        $data['list']=$v;
        $this->load->view('scm/invSa/exportInvPu',$data);
    }

    //出库excel
	public function exportInvPuh(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $billNo  = str_enhtml($this->input->get_post('billNo',TRUE));

		$row=split(' ',$matchCon);
		$matchCon=$row[1];
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and d.checked !=3';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$wheres = 'a.isDelete=0 and f.checked !=3';
		$wheres .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.goodsnumber="'.$mnumber.'"': '';
		$wheres .= $mnumber  ? ' and b.number="'.$mnumber.'"': '';
		//$where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
        $where .= $billNo  ? ' and a.billNo="'.$billNo.'"': '';
        $wheres .= $billNo  ? ' and a.billNo="'.$billNo.'"': '';
		//$where = 'a.isDelete=0 and a.transType='.$transType.'';
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();
		//$data['list'] = $this->data_model->get_invoice($where.' order by '.$order);
		//$list = $this->data_model->get_invoice($where.' order by '.$order);
		//foreach ($list as $arr=>$row) {
				$keyarr=0;
				$lists = $this->data_model->get_orders_info($where);
				//print_r($this->db->last_query());exit;
					foreach ($lists as $arr=>$row) {
						$v[$arr]['id']           = $row['id']."o";
						$v[$arr]['goodsnumber']           = $row['goodsnumber'];
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
						$v[$arr]['flag']    = $row['flag'];
						//$v[$arr]['ordernumber']    = $row['ordernumber'];
						$keyarr++;
					}

					$listss = $this->data_model->get_invoice_info($wheres,2,'!3');
                    //print_r($this->db->last_query());exit;


        foreach ($listss as $arr=>$row) {
						$v[$arr+$keyarr]['id']           = $row['id']."i";
						$v[$arr+$keyarr]['goodsnumber']           = $row['invNumber'];
						$v[$arr+$keyarr]['mdescription']           = $row['mdescription'];
						$v[$arr+$keyarr]['totalQty']           = $row['qty'] * -1;
						$v[$arr+$keyarr]['mainUnit']           = $row['mainUnit'];
						$v[$arr+$keyarr]['price']           = $row['price'];
						$v[$arr+$keyarr]['amount']           = $row['qty']*$row['price'];
						$v[$arr+$keyarr]['BillName']           = $row['BillName'];
						$v[$arr+$keyarr]['billNo']           = $row['billNo'];
						$v[$arr+$keyarr]['contactName']    = $row['contactNo'].' '.$row['contactName'];
						$v[$arr+$keyarr]['liname']    = $row['liname'];
						$v[$arr+$keyarr]['description']    = $row['description'];
						$v[$arr+$keyarr]['flag']    = $row['flag'];
					}
		//}
		//print_r($v);
		//exit();
		$data['list']=$v;
		$this->load->view('scm/invSa/exportInvPuh',$data);
	}

	/*public function exportInvPus(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$row=split(' ',$matchCon);
		$matchCon=$row[1];
		$mname  = str_enhtml($this->input->get_post('mname',TRUE));
		$mnumber  = str_enhtml($this->input->get_post('mnumber',TRUE));
		$ordernumber  = str_enhtml($this->input->get_post('ordernumber',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0';
		$where .= $mname ? ' and a.liname="'.$mname.'"': '';
		$where .= $mnumber  ? ' and a.billNo="'.$mnumber.'"': '';
		$where .= $ordernumber  ? ' and a.ordernumber="'.$ordernumber.'"': '';
		$where .= $matchCon  ? ' and c.name="'.$matchCon.'"' : '';
		//$where = 'a.isDelete=0 and a.transType='.$transType.'';
		//$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : '';
		//$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		//$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		//$where .= $this->common_model->get_admin_purview();
		//$data['list'] = $this->data_model->get_invoice($where.' order by '.$order);
		//$list = $this->data_model->get_invoice($where.' order by '.$order);
		//foreach ($list as $arr=>$row) {
				$lists = $this->data_model->get_orders($where);
				// print_r($lists);
		//exit();
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
		//}
		//print_r($v);
		//exit();
		$data['list']=$v;
		$this->load->view('scm/invSa/exportInvPus',$data);
	}*/

    public function exportInvPus(){
        $this->common_model->checkpurview(5);
        $name = 'purchase_record_'.date('YmdHis').'.xls';
        sys_csv($name);
        $this->common_model->logs('导出采购单据:'.$name);
        $param = $this->purListWhere();
        $where = $param['where'];
        $order = $param['order'];
        $rows = $param['rows'];
        $page = $param['page'];
        $checked = $param['checked'];
        $status = $param['status'];

        $list = $this->data_model->get_orders($where.' order by '.$order);
        //记录要删除的key
        $del_arr = [];
        foreach ($list as $arr=>$row) {
            //查询仓库位置
            $locationNames = $this->db->select('locationName,ordernumber,goodsnumber')->where('iid',$row['id'])->get('ci_orders_info')->result_array();
            $locationNames_tmp = [];
            $locationNames_ = '';
            if(is_array($locationNames)){
                foreach ($locationNames as $value){
                    $locationNames_tmp[] = $value['locationName'];
                }
                $locationNames_ = implode('、',array_unique($locationNames_tmp));
            }
            //查询仓库汇总表是否出库
            $is_chuku = '未出库';
            $c_res = $this->db->select('*')->where('iid',$row['id'])->where('chuku_status',1)->get('ci_orders_info')->row_array();
            if($c_res){
                $is_chuku = '已出库';
            }
            //判断是否超期
            $is_chaoqi = '';
            if($is_chuku == '未出库' && strtotime($row['billDate'])<strtotime('-14 day')){
                $is_chaoqi = '超期未领用';
            }
            //出库状态
            $chuku_status = $is_chuku == '未出库' ? '0' : '1';
            //如果搜索条件 出库状态 就删除
            if($status == 2 && $chuku_status==1){
                $del_arr[] = $arr;
            }if($status == 1 && $chuku_status==0 ){
                $del_arr[] = $arr;
            }

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
            $v[$arr]['description']  = $is_chuku;
            $v[$arr]['billNo']       = $row['billNo'];
            $v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
            $v[$arr]['userName']     = $row['userName'];
            $v[$arr]['transTypeName']= $row['transTypeName'];
            $v[$arr]['liname']= $row['liname'];
            $v[$arr]['disEditable']  = 0;
            $v[$arr]['locationNames']  = $locationNames_;
            $v[$arr]['is_chaoqi']  = $is_chaoqi;
            $v[$arr]['chuku_status']  = $chuku_status;

        }
        foreach ($del_arr as $del){
            unset($v[$del]);

        }
        $v = array_merge($v);
        //}
        //print_r($v);
        //exit();
        $data['list']=$v;
        $this->load->view('scm/invSa/exportInvPus',$data);
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
		//print_r($data);
		//exit;
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
				'billNo','billType','transType','transTypeName','buId','billDate','postData','hxStateCode',
				'description','totalQty','amount','arrears','rpAmount','totalAmount','createTime',
				'totalArrears','disRate','disAmount','uid','userName','srcOrderNo','srcOrderId',
				'accId','modifyTime','liname','sign'),$data,NULL);


			$this->db->trans_begin();
			$info[billNo]=$this->mysql_model->get_new_bill_no($info[billNo]);
			$data[billNo]=$info[billNo];
            $count = count($data['entries']);
			$ordernumber_c = count(array_column($data['entries'],'locationName','ordernumber'));
            $goodsnumber_c = count(array_column($data['entries'],'locationName','goodsnumber'));
            if($ordernumber_c == $goodsnumber_c && $count != $ordernumber_c){
                str_alert(-1,'请汇总物料需求!');
            }
            $qty_arr = array();
            $is_arr = array();
			foreach ($data['entries'] as $arr=>$row) {
				$invId=$row['invId'];
				$locationName=$row['locationName'];
				$ordernumber=$row['ordernumber'];
				$goodsnumber=$row['goodsnumber'];
				if(isset($is_arr[$ordernumber.$goodsnumber])){
                    str_alert(-1,$locationName.',存在重复行！');
                }
                $is_arr[$ordernumber.$goodsnumber] = 1;
				$qty=$row['qty'];
				$num=$this->db->query("select inventoryNew from ci_stock where id='$invId' and isDelete=0");
				$result = $num->row_array();
				$stock_qty = isset($result['inventoryNew']) ? floatval($result['inventoryNew']) : 0;
				if(empty($result)){
					str_alert(-1,'物料编号'.$goodsnumber.'(订单号'.$ordernumber.')库存记录不存在，请从仓储物资库重新选择！');
				}
                $qty_arr[$invId]['qty'] = isset($qty_arr[$invId]['qty']) ? $qty_arr[$invId]['qty'] + $qty : $qty;
                $qty_arr[$invId]['name'] = $locationName;
                $qty_arr[$invId]['stock'] = $stock_qty;
			}
			foreach ($qty_arr as $qty_v){
                if($qty_v['qty']>$qty_v['stock']){
                    str_alert(-1,$qty_v['name'].',库存不足！');
                }
            }

			$iid = $this->mysql_model->insert('orders',$info);
			$this->invoice_info($iid,$data);
			//echo "=========";
			//exit();
			$this->account_info($iid,$data);

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				$db_error = $this->db->error();
				$err_msg = !empty($db_error['message']) ? 'SQL错误：'.$db_error['message'] : 'SQL错误';
				str_alert(-1,$err_msg);
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增购货 单据编号：'.$info['billNo']);
				str_alert(200,'success',array('id'=>intval($iid),'billNo'=>$info['billNo']));
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
		$data =  $this->data_model->get_orders('a.isDelete=0 and a.id='.$id.' and a.billType="PUR"',1);
		//print_r($this->db->last_query());exit();
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
			$list = $this->data_model->get_orders_info('a.isDelete=0 and a.iid='.$id.' order by a.goodsnumber');

            //print_r($this->db->last_query());exit();

			foreach ($list as $arr=>$row) {

				 //echo $row['postData'];
				 	//exit();
				$v[$arr]['invSpec']             = $row['invSpec'];
				$v[$arr]['srcOrderEntryId']     = $row['srcOrderEntryId'];
				$v[$arr]['srcOrderNo']          = $row['srcOrderNo'];
				$v[$arr]['srcOrderId']          = $row['srcOrderId'];
				//$v[$arr]['goods']               = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['goodsnumber']               = $row['goodsnumber'];
				$v[$arr]['ordernumber']               = $row['ordernumber'];
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
	public function daorusales(){

		$data = $this->mysql_model->query("select * from ci_orders_cursor",2);
		die(json_encode($data));
	}

	//导入excel

	public function postexcel($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT'];
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcelorders($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcelorders('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
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
				header("Content-type:text/html;charset=utf-8");
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!";
				//echo iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				exit();
			}
	}

	public function postexcelc($filename)
	{
		$jddir = $_SERVER['DOCUMENT_ROOT'];
		$jddir = str_replace('/','\\',$jddir);
		return $this->importexcelc($jddir.'\data\upfile\excel\\'.$filename);
	}

	//上传excel
	//public function uploadExcel() {



	//}
	public function uploadexcelc(){

			$name=$_FILES['file']['name']; //获取客户端机器原文件的名称
			$type=strstr($name,"."); //获取从"."到最后的字符
			if($type==".xlsx"||$type==".xls"){
			$name=explode('.',$_FILES["file"]["name"]);
				$date=date('Ymdhis');
				$newPath=$date.'.'.$name[1];
				move_uploaded_file($_FILES["file"]["tmp_name"], "./data/upfile/excel/" . $newPath);
				$stats = $this->postexcelc($newPath);
				header("Content-type: application/json; charset=utf-8");
				echo json_encode(array(
					'status' => 200,
					'msg' => isset($stats['stoppedByDuplicate']) && $stats['stoppedByDuplicate'] ? '导入已停止（存在重复）' : '导入完成',
					'successCount' => isset($stats['success']) ? (int)$stats['success'] : 0,
					'duplicateCount' => isset($stats['duplicate']) ? (int)$stats['duplicate'] : 0,
					'duplicateNumbers' => isset($stats['duplicateNumbers']) ? $stats['duplicateNumbers'] : array(),
					'stoppedByDuplicate' => isset($stats['stoppedByDuplicate']) ? (bool)$stats['stoppedByDuplicate'] : false,
					'duplicateRow' => isset($stats['duplicateRow']) ? (int)$stats['duplicateRow'] : 0,
					'errors' => isset($stats['errors']) ? $stats['errors'] : array()
				), JSON_UNESCAPED_UNICODE);
				return;
			}else{
				header("Content-type: application/json; charset=utf-8");
				echo json_encode(array('status' => -1, 'msg' => '对不起，您上传文件的格式不正确！请上传 .xls 或 .xlsx 文件。'), JSON_UNESCAPED_UNICODE);
				return;
			}
	}
	public function postexcelorders($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT'];
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcelorderss($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcelorders('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
	}


	//上传excel
	//public function uploadExcel() {



	//}
	public function uploadexcelorders(){
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


			 	$this->postexcelorders($newPath);
				//echo "<br/>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;导入成功，请关闭窗口！";
			}else{
				header("Content-type:text/html;charset=utf-8");
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!";
				//echo  iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				//echo iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				exit();
			}
	}

	public function postexcelcangku($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT'];
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcelcangku($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcelorders('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
	}


	//上传excel
	//public function uploadExcel() {



	//}
	public function uploadexcelcangku(){

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


			 	$this->postexcelcangku($newPath);
				//echo "<br/>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;导入成功，请关闭窗口！";
			}else{
				header("Content-type:text/html;charset=utf-8");
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!";
				//echo  iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				//echo iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				exit();
			}
	}



	public function postexcelxiangmu($filename)
	{
		//需要传入绝对路径
		 $jddir=$_SERVER['DOCUMENT_ROOT'];
		 $jddir=str_replace('/','\\',$jddir);
	    $this->importexcelxiangmu($jddir.'\data\upfile\excel\\'.$filename);
	// $this->importexcelorders('D:\webserver\www\toolzhubajie1\data\upfile\excel\moban1.xls');
	}


	//上传excel
	//public function uploadExcel() {



	//}
	public function uploadexcelxiangmu(){

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


			 	$this->postexcelxiangmu($newPath);
				//echo "<br/>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;导入成功，请关闭窗口！";
			}else{
				header("Content-type:text/html;charset=utf-8");
				echo "<br/>&ensp;&ensp;对不起,您上传文件的格式不正确!!";
				//echo iconv("GB2312","UTF-8",'对不起,您上传文件的格式不正确!!');
				exit();
			}
	}




    public function toPdf() {
	    $this->common_model->checkpurview(85);
	    $id   = intval($this->input->get('id',TRUE));
		$data = $this->data_model->get_orders('a.isDelete=0 and a.id='.$id.' and a.billType="PUR"',1);
		$tempmdescription="";
		$tempdescription="";
		$tempname="";
		$totalamout=0;
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
				$v[$arr]['ordernumber']        = $row['ordernumber'];
				$v[$arr]['goodsnumber']        = $row['goodsnumber'];
				//$v[$arr]['mdescription']        = $row['mdescription'];
				$v[$arr]['sign']        = $row['sign'];
				$totalamout+=$row['amount'];
			}
			$data['countpage']  = ceil(count($postData['entries'])/$data['num']);
			$data['list']       = isset($v) ? $v : array();
			$data['totalAmount']=$totalamout;
			$chuku_orders = $this->db->select('id')->where('iid',$id)->where('isDelete',0)->where('chuku_status',1)->get('ci_orders_info')->result_array();
            $data['chuku_status'] = count($chuku_orders)>0 ? '已出库' : '' ;
            $this->common_model->barcode($data['billNo']);//条形码
		    ob_start();
			$this->load->view('scm/invSa/toPdf', $data);
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
		$data = $this->mysql_model->get_results('orders','(isDelete=0) and (id in('.$id.')) and billType="PUR"');
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
			$this->mysql_model->update('orders',array('isDelete'=>1),'(id in('.$id.'))');
			$this->mysql_model->update('orders_info',array('isDelete'=>1),'(iid in('.$id.'))');
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
    public function guidang() {
	    $this->common_model->checkpurview(4);
		$id   = str_enhtml($this->input->get_post('id',TRUE));
		$str = explode(',',$id);
		$ido="";
		$idi="";
		for($index=0;$index<count($str);$index++){
			 $tmparray = explode("o",$str[$index]);

				if(count($tmparray)>1){
					$str[$index]=substr_replace($str[$index] ,"",-1);
					$ido.= $str[$index].",";

				} else{
					$str[$index]=substr_replace($str[$index] ,"",-1);
					$idi.= $str[$index].",";

				}
		}
		$ido=substr_replace($ido ,"",-1);
		$idi=substr_replace($idi ,"",-1);
		$result="";
		$result2="";
		if($ido!=""){
			$result=$this->db->query("update ci_orders_info set flag='已归档' where id in(".$ido.")");
		}
		if($idi!=""){
			$result2=$this->db->query("update ci_invoice_info set flag='已归档' where id in(".$idi.")");
		}
		if($result=="true" || $result2=="true"){
			str_alert(200,"归档成功！");
		}else{

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
				$this->common_model->logs('删除购货订单 单据编号：'.$data['billNo']);
				str_alert(200,'success');
			}
		}
		str_alert(-1,'单据不存在、或者已删除');
	}

	public function deleteyj() {
	    $this->common_model->checkpurview(4);
	    $id   = intval($this->input->get('id',TRUE));
		$data = $this->mysql_model->get_rows('stock_yj',array('id'=>$id));
		if (count($data)>0) {
		    //$data['checked'] >0 && str_alert(-1,'已审核的不可删除');
		    $this->db->trans_begin();
			//$this->mysql_model->update('invoice',array('isDelete'=>1),array('id'=>$id));
			$this->db->query("delete from ci_stock_yj where id='$id'");
			//$this->mysql_model->update('invoice_info',array('isDelete'=>1),array('iid'=>$id));
			//if ($data['accId']>0) {
			//	$this->mysql_model->update('account_info',array('isDelete'=>1),array('iid'=>$id));
			//}
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败');
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除成功');
				str_alert(200,'success');
			}
		}
		str_alert(-1,'单据不存在、或者已删除');
	}

	public function showyj(){
		$id   = intval($this->input->get('id',TRUE));

		$query=$this->db->query("select * from ci_stock_yj where id='$id'");
		$result=$query->result_array();
		foreach($result as $arr=>$row){
			$json['number'] = $row['number'];
			$json['yjnum']    = $row['yjnum'];
			die(json_encode($json));
		}

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
			    $iid = $this->mysql_model->insert('orders',$info);
			    //$this->invoice_info($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('orders',$info,array('id'=>$data['id']));
			   // $this->invoice_info($data['id'],$data);
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
			    $iid = $this->mysql_model->insert('orders',$info);
			    $this->invoice_info($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('orders',$info,array('id'=>$data['id']));
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
            $c_res = $this->db->select('*')->where('iid',$data['id'])->where('chuku_status',1)->get('ci_orders_info')->row_array();
            if($c_res){
                str_alert(-1,'已出库的不能作废');
            }
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
			    $iid = $this->mysql_model->insert('orders',$info);
			    $this->invoice_infozfei($iid,$data);
				$data['id'] = $iid;
			} else {
				$this->mysql_model->update('orders',$info,array('id'=>$data['id']));
			    $this->invoice_infozfei($data['id'],$data);
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
		    $invoice = $this->mysql_model->get_rows('orders',array('id'=>$data['id'],'billType'=>'PUR','isDelete'=>0));
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
		$v = array();
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
			$v[$arr]['ordernumber']     = $row['ordernumber'];
			$v[$arr]['goodsnumber']     = $row['goodsnumber'];
			$v[$arr]['mainUnit']     = $row['mainUnit'];
			$v[$arr]['locationName']     = $row['locationName'];
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

			$locationName=$row['locationName'];
			$ordernumber=$row['ordernumber'];
			$goodsnumber=$row['goodsnumber'];
			$qty=$row['qty'];
			$res_tmp = $this->db->query("select * from ci_stock where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber' for update")->result_array();
			$sql="update ci_stock set inventoryNew=inventoryNew-$qty where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber'";
			$this->db->query($sql);
			foreach ($res_tmp as $tmp){
				$this->common_model->stock_logs('inventoryNew:'.$tmp['inventoryNew'].'-'.$qty,$tmp['id'],$ordernumber,$goodsnumber);
			}
			$sqls="update ci_stock set number=number+$qty where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber'";
			$this->db->query($sqls);
			foreach ($res_tmp as $tmp){
				$this->common_model->stock_logs('number:'.$tmp['number'].'+'.$qty,$tmp['id'],$ordernumber,$goodsnumber);
			}
		}

		if (!empty($v)) {
			if ($data['id']>0) {
				$this->mysql_model->delete('orders_info',array('iid'=>$iid));
			}
			$this->mysql_model->insert('orders_info',$v);
		}
	}


	private function invoice_infozfei($iid,$data) {
		$v = array();
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
			$v[$arr]['ordernumber']     = $row['ordernumber'];
			$v[$arr]['goodsnumber']     = $row['goodsnumber'];
			$v[$arr]['mainUnit']     = $row['mainUnit'];
			$v[$arr]['locationName']     = $row['locationName'];
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

			$locationName=$row['locationName'];
			$ordernumber=$row['ordernumber'];
			$goodsnumber=$row['goodsnumber'];
			$qty=$row['qty'];
			$sql="update ci_stock set inventoryNew=inventoryNew+$qty where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber'";
            $res_tmp = $this->db->query("select * from ci_stock where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber'")->result_array();
			$res=$this->db->query($sql);
			if($res){
			    foreach ($res_tmp as $tmp){
                    $this->common_model->stock_logs('inventoryNew:'.$tmp['inventoryNew'].'+'.$qty,$tmp['id'],$ordernumber,$goodsnumber);
                }
            }
			$sqls="update ci_stock set number=number-$qty where locationName='$locationName' and ordernumber='$ordernumber' and goodsnumber='$goodsnumber'";
			$res=$this->db->query($sqls);
            if($res){
                foreach ($res_tmp as $tmp){
                    $this->common_model->stock_logs('number:'.$tmp['number'].'-'.$qty,$tmp['id'],$ordernumber,$goodsnumber);
                }
            }
			//echo $data['id']."============".$res."-------";
			//exit();
			//if($res){
			//	$this->db->query("COMMIT");
			//}else{
			//	$this->db->query("ROLLBACK");
			//}
		}

		if (!empty($v)) {
			if ($data['id']>0) {
				$this->mysql_model->delete('orders_info',array('iid'=>$iid));
			}
			$this->mysql_model->insert('orders_info',$v);
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


	//上传图片信息
	public function uploadImages() {
	    if (!$this->common_model->checkpurviews(203)){
		    str_alert(-1,'没有上传权限');
		}
	    require_once './application/libraries/UploadHandler.php';
		$config = array(
			'script_url' => base_url().'inventory/uploadimages',
			'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/data/upfile/Contract/',
			'upload_url' => base_url().'data/upfile/Contract/',
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
