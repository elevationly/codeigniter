<?php $this->load->view('header');?>
<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
try{
	document.domain = '<?php echo base_url()?>';
}catch(e){
}
//ctrl+F5 增加版本号来清空iframe的缓存的
$(document).keydown(function(event) {
	/* Act on the event */
	if(event.keyCode === 116 && event.ctrlKey){
		var defaultPage = Public.getDefaultPage();
		var href = defaultPage.location.href.split('?')[0] + '?';
		var params = Public.urlParam();
		params['version'] = Date.parse((new Date()));
		for(i in params){
			if(i && typeof i != 'function'){
				href += i + '=' + params[i] + '&';
			}
		}
		defaultPage.location.href = href;
		event.preventDefault();
	}
});
</script>
<link rel="stylesheet" href="<?php echo base_url()?>statics/js/common/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?php echo base_url()?>statics/js/common/plugins/validator/jquery.validator.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>statics/js/common/plugins/validator/local/zh_CN.js"></script>

<style>
body{background: #fff;}
.mod-form-rows .label-wrap{font-size:12px;}
.mod-form-rows .row-item {padding-bottom: 15px;margin-bottom: 0;}/*兼容IE7 ，重写common的演示*/
.manage-wrapper{margin:20px auto 10px;width:700px;padding-left:40px;}
.manage-wrap .ui-input{width: 198px;}
.base-form{*zoom: 1;}
.base-form:after{content: '.';display: block;clear: both;height: 0;overflow: hidden;}
.base-form li{float: left;width: 290px;}
.base-form li.odd{padding-right:20px;}
.manage-wrap textarea.ui-input{width: 588px;height: 60px;overflow:hidden;}
#receiveFunds,#periodReceiveFunds{text-align: right;}

.contacters{margin-bottom: 10px;}
.contacters h3{margin-bottom: 10px;font-weight: normal;}
.mod-form-rows .pb0{padding-bottom:0;}
.mod-form-rows .ctn-wrap{overflow: visible;}
.ui-combo-wrap {position: static;}

</style>
</head>
<body>
<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
    	<form id="manage-form" action="">
    		<ul class="mod-form-rows base-form" id="base-form">
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="goodsnumber">物料编号</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="goodsnumber" id="goodsnumber"></div>
    			</li>
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="mdescription">物料描述</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="mdescription" id="mdescription"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="inventoryOld">申请数量</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="inventoryOld" id="inventoryOld"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="number">领用数量</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="number" id="number"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="inventoryNew">库存数量</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="inventoryNew" id="inventoryNew"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="mainUnit">单位</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="mainUnit" id="mainUnit"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="ordernumber">采购订单号</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="ordernumber" id="ordernumber"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="price">单价</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="price" id="price"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="amount">出库金额</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="amount" id="amount"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="locationName">仓库</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="locationName" id="locationName"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="Arrivaltime">申请时间</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="Arrivaltime" id="Arrivaltime"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="flagNo">是否到货</label></div>
					
					<select name="flagNo" id="flagNo">
						<option>已到货</option>
						<option>未到货</option>
                        <option>部分到货</option>
                    </select>
					
    				<!--<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="flagNo" id="flagNo"></div>-->
    			</li>
                <li class="row-item odd">
                    <div class="label-wrap"><label for="daohuo">已到货数量</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="daohuo" id="daohuo"></div>
                </li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="flagtime">出库时间</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="flagtime" id="flagtime"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="flagcontact">供应商</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="flagcontact" id="flagcontact"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="beizhu">备注</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="beizhu" id="beizhu"></div>
    			</li>
    			<!--<li class="row-item odd row-category">
    				<div class="label-wrap"><label for="category">项目类别</label></div>
    				<div class="ctn-wrap"><span id="category"></span></div>
    			</li>
    		
            	<li class="row-item">
    				<div class="label-wrap"><label for="customerLevel">项目等级</label></div>
    				<div class="ctn-wrap"><span id="customerLevel"></span></div>
    			</li>
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="date">余额日期</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input ui-datepicker-input" name="date" id="date" /></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="receiveFunds">期初应收款</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="receiveFunds" id="receiveFunds"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="periodReceiveFunds">期初预收款</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="periodReceiveFunds" id="periodReceiveFunds"></div>
    			</li>
                -->
    		</ul>
           <!--   
    		<div class="contacters">
    			<h3 class="dn">联系方式</h3>
    			<div class="grid-wrap">
				  <table id="grid">
				  </table>
				  <div id="page"></div>
				</div>
    		</div>
            -->
               <!-- 
    		<ul class="mod-form-rows">
    			<li class="row-item pb0">
                -->
    				<!-- <div class="label-wrap"><label for="note">备注</label></div> -->
    				 <!-- <div class="ctn-wrap"><textarea name="" id="note" class="ui-input ui-input-ph">添加备注信息</textarea></div>
    			</li>
    		</ul>
             -->
    	</form>
    </div>
    <div class="hideFile dn">
	    <input type="text" class="textbox address" name="address" id="address" autocomplete="off" readonly>
	</div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/customerstock.js?ver=201510141132"></script>
</body>
</html>




 