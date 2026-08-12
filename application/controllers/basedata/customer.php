<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview(62);
    }

    public function exporter()
    {
        $name = 'customer_'.date('YmdHis').'.xls';
        sys_csv($name);
        $this->common_model->logs('导出客户:'.$name);
        $type   = intval($this->input->get('type',TRUE))==10 ? 10 : -10;
        $design   = intval($this->input->get('design',TRUE));
        $apply   = intval($this->input->get('apply',TRUE));
        $disable = intval($this->input->get('disable',TRUE));
        $skey   = str_enhtml($this->input->get_post('skey',TRUE));
        $remark_   = str_enhtml($this->input->get_post('remark_',TRUE));
        $categoryid   = intval($this->input->get_post('categoryId',TRUE));
        $where  = '(isDelete=0) and type='.$type;
        //默认开放所有内容
        //$where .= $this->common_model->get_contact_purview();
        $where .= $skey ? ' and (number like "%'.$skey.'%" or name like "%'.$skey.'%" or linkMans like "%'.$skey.'%")' : '';
        $where .= $remark_ ? " and remark_ like '%$remark_%' " : '';
        $where .= $categoryid>0 ? ' and cCategory = '.$categoryid.'' : '';
        if($design != 0){
            $design = $design == -1 ? 0 : $design;
            $where .= " and design = $design ";
        }
        if($apply != 0){
            $apply = $apply == -1 ? 0 : $apply;
            $where .= " and apply = $apply ";
        }
        if($disable != 0){
            $disable = $disable == -1 ? 0 : $disable;
            $where .= " and disable = $disable ";
        }
        $data['list'] = $this->mysql_model->get_results('contact',$where,'id desc');
        foreach ($data['list'] as $arr=>$row){
            //物资数统计  为该项目在领料单汇总中的物资领用总条目数（用来直接判断该项目是否有发生领料）
            $orders_info_num = $this->db->where('buId',$row['id'])->from('ci_orders_info')->count_all_results();
            $data['list'][$arr]['orders_num'] = empty($orders_info_num) ? 0 : $orders_info_num;//物资数统计
        }
        $data['design'] = ['未设计','已设计'];
        $data['apply'] = ['未申请','已申请'];
        $data['disable'] = ['项目启用','竣工禁用'];
        $data['check'] = ['未核对','已核对'];
        $this->load->view('settings/customer-export',$data);

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
