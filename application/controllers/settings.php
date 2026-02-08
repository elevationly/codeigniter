<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	public function Contract() {
		$this->load->view('settings/Contract');	
	}
	public function Contractorders() {
		$this->load->view('settings/Contractorders');	
	}
	public function Contractcangku() {
		$this->load->view('settings/Contractcangku');	
	}
	public function xiangmu() {
		$this->load->view('settings/xiangmu');	
	}
	public function Contractorderss() {
		$this->load->view('settings/Contractorderss');	
	}
	public function Contractorderc() {
		$this->load->view('settings/Contractorderc');	
	}
	
	public function Contractgoods() {
		$this->load->view('settings/Contractgoods');	
	}
    public function Contractmaterial() {
        $this->load->view('settings/Contractmaterial');
    }
	
	public function customer_list() {
	    $this->common_model->checkpurview(58);
		$this->load->view('settings/customer-list');	
	}
	
	

	public function customer_manage() {
		$this->load->view('settings/customer-manage');	
	}
	
	public function customer_stock() {
		$this->load->view('settings/customer-stock');	
	}
	public function customer_cangku() {
		$this->load->view('settings/customer-cangku');	
	}
	

	public function vendor_list() {
	    $this->common_model->checkpurview(63);
		$this->load->view('settings/vendor-list');	
	}
	
	

	public function vendor_manage() {
		$this->load->view('settings/vendor-manage');	
	}
	
	

	public function addressmanage() {
		$this->load->view('settings/addressmanage');	
	}
	

	public function goods_list() {
	    $this->common_model->checkpurview(68);
		$this->load->view('settings/goods-list');	
	}

    public function lading_list() {
        $this->common_model->checkpurview(68);
        $this->load->view('settings/lading-list');
    }
	
	public function orders_list() {
	    $this->common_model->checkpurview(68);
		$this->load->view('settings/orders-list');	
	}
	
	public function cangku_list() {
	    $this->common_model->checkpurview(68);
		$this->load->view('settings/cangku-list');	
	}
	public function cangku_listhuizong() {
	    $this->common_model->checkpurview(68);
		$this->load->view('settings/cangku-listhuizong');	
	}
	
	public function xiangmu_list() {
	    $this->common_model->checkpurview(68);
		$this->load->view('settings/xiangmu-list');	
	}

	public function storage_list() {
	    $this->common_model->checkpurview(155);
		$this->load->view('settings/storage-list');	
	}
	

	public function storage_manage() {
		$this->load->view('settings/storage-manage');	
	}
	

	public function staff_list() {
	    $this->common_model->checkpurview(97);
		$this->load->view('settings/staff-list');	
	}
	

	public function staff_manage() {
		$this->load->view('settings/staff-manage');	
	}
	

	public function shippingaddress() {
		$this->load->view('settings/shippingaddress');	
	}
	

	public function shippingaddressmanage() {
		$this->load->view('settings/shippingaddressmanage');	
	}
	

	public function settlement_account() {
	    $this->common_model->checkpurview(98);
		$this->load->view('settings/settlement-account');	
	}
	

	public function settlementaccount_manager() {
		$this->load->view('settings/settlementaccount-manager');	
	}
	

	public function system_parameter() {
	    $this->common_model->checkpurview(81);
		$this->load->view('settings/system-parameter');	
	}
	

	public function unit_list() {
	    $this->common_model->checkpurview(77);
		$this->load->view('settings/unit-list');	
	}
	

	public function unit_manage() {
		$this->load->view('settings/unit-manage');	
	}
	
	

	public function unitgroup_manage() {
		$this->load->view('settings/unitgroup-manage');	
	}
	
	

	public function backup() {
	    $this->common_model->checkpurview(84);
		$this->load->view('settings/backup');	
	}
	

	public function settlement_category_list() {
	    $this->common_model->checkpurview(159);
		$this->load->view('settings/settlement-category-list');	
	}
	

	public function settlement_category_manager() {
		$this->load->view('settings/settlement-category-manage');	
	}
	

	public function category_list() {
	    $type = str_enhtml($this->input->get('typeNumber',TRUE));
		$info = array('customertype'=>73,'supplytype'=>163,'trade'=>167,'paccttype'=>171,'raccttype'=>175);
		$this->common_model->checkpurview($info[$type]);
		$this->load->view('settings/category-list');	
	}
	

	public function choose_account() {
		$this->load->view('settings/choose-account');	
	}
	
	

	public function inventory_warning() {
		$this->load->view('settings/inventory-warning');	
	}
	

	public function log() {
	    $this->common_model->checkpurview(83);
		$this->load->view('settings/log-initloglist');	
	}
	

	public function authority() {
	    $this->common_model->checkpurview(82);
		$this->load->view('settings/authority');	
	}
	

	public function authority_new() {
	    $this->common_model->checkpurview(82);
		$this->load->view('settings/authority-new');	
	}
	

	public function authority_setting() {
	    $this->common_model->checkpurview(82);
		$this->load->view('settings/authority-setting');	
	}
	

	public function authority_setting_data() {
	    $this->common_model->checkpurview(82);
		$this->load->view('settings/authority-setting-data');	
	}
	

	public function goods_manage() {
		$this->load->view('settings/goods-manage');	
	}
    public function material_manage() {
        $this->load->view('settings/material-manage');
    }


    public function fileupload() {
		$this->load->view('settings/fileupload');	
	}
	

	public function assistingprop() {
		$this->load->view('settings/assistingprop');	
	}
	

	public function prop_list() {
		$this->load->view('settings/prop-list');	
	}
	

	public function propmanage() {
		$this->load->view('settings/propmanage');	
	}
	

	public function import() {
		$this->load->view('settings/import');	
	}
	

	public function select_customer() {
		$this->load->view('settings/select-customer');	
	}
	

	public function goods_batch() {
		$this->load->view('settings/goods-batch');	
	}

    public function material_batch() {
        $this->load->view('settings/material-batch');
    }

	public function goods_dingdan() {
		$this->load->view('settings/goods-dingdan');	
	}
	public function goods_daohuobatch() {
		$type['id']   = $this->input->get('id',TRUE);
		$this->load->view('settings/goods-daohuobatch',$type);	
	}

    public function goods_daohuobatch_scan() {
        $type['id']   = $this->input->get('id',TRUE);
        $this->load->view('settings/goods-daohuobatch_scan',$type);
    }
	
	public function goods_chukubatch() {
		$type['id']   = $this->input->get('id',TRUE);
		$this->load->view('settings/goods-chukubatch',$type);	
	}

    public function goods_chukubatch_scan() {
        $type['id']   = $this->input->get('id',TRUE);
        $type['liname']   = $this->input->get('liname',TRUE);
        $type['billNo']   = $this->input->get('billNo',TRUE);
        $this->load->view('settings/goods-chukubatch_scan',$type);
    }
	
	public function goods_dingdanbatch() {
		$type['ordert']   = $this->input->get('dingdanhao',TRUE);
		$this->load->view('settings/goods-dingdanbatch',$type);	
	}
	
	


	public function addedServiceList() {
		$this->load->view('settings/addedServiceList');	
	}
	

	public function assistingProp_batch() {
		$this->load->view('settings/assistingProp-batch');	
	}
	

	public function assistingPropGroupManage() {
		$this->load->view('settings/assistingPropGroupManage');	
	}
	

	public function storage_batch() {
		$this->load->view('settings/storage-batch');	
	}
	

	public function saler_batch() {
		$this->load->view('settings/saler-batch');	
	}
	

	public function customer_batch() {
		$this->load->view('settings/customer-batch');	
	}
	

	public function supplier_batch() {
		$this->load->view('settings/supplier-batch');	
	}
	

	public function settlementAccount_batch() {
		$this->load->view('settings/settlementAccount-batch');	
	}
	
	public function print_templates() {
		$this->load->view('settings/print-templates');	
	} 
	
	public function print_templates_manage() {
		$this->load->view('settings/print-templates-manage');	
	} 
	 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */