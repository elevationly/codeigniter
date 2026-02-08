<?php $this->load->view('header');?>
<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
try{
	document.domain = '<?php echo base_url()?>';
}catch(e){
}
</script>

<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/index.css?2" rel="stylesheet" type="text/css" id="indexFile">
<script src="<?php echo base_url()?>statics/js/dist/template.js?2"></script>

</head>
<body>
<div id="hd" class="cf">
  <div class="fl welcome cf">
	  <strong><span id="greetings"></span>，<span id="username"></span></strong>
	  <a href="javascrip:void(0);" id="manageAcct">账号管理</a>
	  <!--<a href="" target="_blank" id="newGuide" title="新手入门">新手入门</a>-->
  </div>
  
  
  
  <div class="fr storages-search"><label><a class="b" id="invWarning">库存预警</a></label><label for="">库存查询</label><span class="ui-search"><input type="text" id="goodsAuto" class="ui-input" /></span><span id="stockSearch"></span></div>
</div>
<script>
var greetings = "", cur_time = new Date().getHours();
if(cur_time >= 0 && cur_time <= 4 ) {
	greetings = "已经夜深了，请注意休息"
} else if (cur_time > 4 && cur_time <= 7 ) {
	greetings = "早上好";
} else if (cur_time > 7 && cur_time < 12 ) {
	greetings = "上午好";
} else if (cur_time >= 12 && cur_time <= 18 ) {
	greetings = "下午好";
} else {
	greetings = "晚上好";
};
$("#greetings").text(greetings);
$("#username").text(parent.SYSTEM.realName);
</script>
<div id="bd" class="index-body cf" >
  <div class="col-main"  style="width:100%">
    <div class="main-wrap cf" style="width:100%" >
      <div class="m-top cf" id="profileDom">
 
		<table width="100%" border="0" cellspacing="0" cellpadding="20">
		  <tr>
		    <td><a class="ta t1" tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="商品库存余额" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/goods_balance')?>">商品库存余额</a></td>
		    <td><a class="ta t2" tabid="report-cashBankJournal" data-right="SettAcctReport_QUERY" tabTxt="现金银行报表" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/bankBalance_detail?action=detail')?>">现金银行报表</a></td>
		    <td><a class="ta t3" tabid="report-contactDebt" data-right="ContactDebtReport_QUERY" tabTxt="往来单位欠款表" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/contact_debt_new?action=detail')?>">往来单位欠款表</a></td>
		    <td><a class="ta t4" tabid="report-salesSummary" data-right="SAREPORTINV_QUERY" tabTxt="销售汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/sales_summary')?>">销售汇总表</a></td>
		    <td><a class="ta t5" tabid="report-puSummary" data-right="PUREPORTINV_QUERY" tabTxt="采购汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/puDetail_inv')?>">采购汇总表</a></td>  
		  </tr>
		</table>
      </div>
      <ul class="quick-links">
        <li class="purchase-purchase">
        	<a tabid="purchase-purchase" data-right="PU_ADD" tabTxt="物资出库单" parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invPu?action=initPur')?>"><span></span>采购入库</a>
        </li>
        <li class="sales-sales">
        	<a tabid="sales-sales" data-right="SA_ADD" tabTxt="销货单" parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invSa?action=initSale')?>"><span></span>销货出库</a>
        </li>
        <li class="storage-transfers">
        	<a tabid="storage-transfers" data-right="TF_ADD" tabTxt="调拨单" parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invTf?action=initTf')?>"><span></span>仓库调拨</a>
        </li>
        <li class="storage-inventory">
        	<a tabid="storage-inventory" data-right="PD_GENPD" tabTxt="盘点" parentOpen="true" rel="pageTab" href="<?php echo site_url('storage/inventory')?>"><span></span>库存盘点</a>
        </li>
        <li class="storage-otherWarehouse">
        	<a tabid="storage-otherWarehouse" data-right="IO_ADD" tabTxt="其他入库" parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invOi?action=initOi&type=in')?>"><span></span>其他入库</a>
        </li>
        <li class="storage-otherOutbound">
        	<a tabid="storage-otherOutbound" data-right="OO_ADD" tabTxt="其他出库" parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invOi?action=initOi&type=out')?>"><span></span>其他出库</a>
        </li>
        <li class="added-service">
        	<!--<a tabid="setting-addedServiceList" tabTxt="增值服务" parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/addedServiceList')?>"><span></span>增值服务</a>-->
			<a href="#" id="feedback"><span></span>增值服务</a>
        </li>
        <li class="feedback">
        	<a href="#" id="feedback"><span></span>意见反馈</a>
        </li>
      </ul>
    </div>
  </div>
 
</div>
<script id="profile" type="text/html">
		<table width="100%" border="0" cellspacing="0" cellpadding="20">
		  <tr>
		    <td><a class="tad t1" tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="库存总量" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/goods_balance')?>?search=true" ><span> <div class="ziti">库存总量:<#= items[0].total1 #></div></span> </a></td>
		    <td><a class="tad t2" tabid="report-cashBankJournal" data-right="SettAcctReport_QUERY" tabTxt="项目总量" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/cash_bank_journal_new')?>" ><span>  <div class="ziti">项目总量:<#= items[1].total1 #></div>  </a></td>
		    <td><a class="tad t3" tabid="report-contactDebt" data-right="ContactDebtReport_QUERY" tabTxt="出库单未审核数量" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/contact_debt_new')?>"><span><div class="ziti">出库单未审核数量: <#= items[2].total1 #></div></span> </a></td>
		    <td><a class="tad t4" tabid="report-salesSummary" data-right="SAREPORTINV_QUERY" tabTxt="领料人总数" parentOpen="true" rel="pageTab" href="<?php echo site_url('report/sales_summary')?>" ><span><div class="ziti">领料人总数: <#= items[3].total1 #></div> </span> </a></td>
		      
		  </tr>
		</table>
		<i></i>  
</script>

<script>
parent.dataReflush = function(){
	if(parent.SYSTEM.isAdmin || parent.SYSTEM.rights.INDEXREPORT_QUERY) {
		template.openTag = '<#';
		template.closeTag = '#>';
		Public.ajaxGet('../report/index?action=getInvData', {finishDate: parent.SYSTEM.endDate, beginDate: parent.SYSTEM.beginDate, endDate: parent.SYSTEM.endDate }, function(data){
			if(data.status === 200) {
				var html = template.render('profile', data.data);
				document.getElementById('profileDom').innerHTML = html;
				reportParam();
			} else {
				parent.Public.tips({type: 1, content : data.msg});
			}
		});
	};
};
parent.dataReflush();
$('#profileDom').on('click','i',function(){
	parent.dataReflush();
});
</script>

<script>
Public.pageTab();
reportParam();
function reportParam(){
	$("[tabid^='report']").each(function(){
		var dateParams = "beginDate="+parent.SYSTEM.beginDate+"&endDate="+parent.SYSTEM.endDate;
		var href = this.href;
		href += (this.href.lastIndexOf("?")===-1) ? "?" : "&";
		if($(this).html() === '商品库存余额表'){
			this.href = href + "beginDate="+parent.SYSTEM.startDate+"&endDate="+parent.SYSTEM.endDate;
		}
		else{
			this.href = href + dateParams;
		}
	});
}

var goodsCombo = Business.goodsCombo($('#goodsAuto'), {
	extraListHtml: ''
});

$('#goodsAuto').click(function(){
	var _self = this;
	setTimeout(function(){
		_self.select();
	}, 50);
});

$('#invWarning').click(function(){
	if (!Business.verifyRight('INVENTORY_WARNING')) {
		return ;
	};
	$.dialog({
		width: 800,
		height: 410,
		title: '商品库存预警',
		//content: 'url:/inventory-warning.jsp',
		content: 'url:../settings/inventory_warning',
		cancel: true,
		lock: true,
		cancelVal: '关闭'
	});
});

$('#stockSearch').click(function(e){
	e.preventDefault();
	var id = goodsCombo.getValue();
	var text = $('#goodsAuto').val();
	Business.forSearch(id, text);
	$('#goodsAuto').val('');
});

$("#feedback").click(function(e){
	e.preventDefault();
	parent.tab.addTabItem({tabid: 'myService', text: '服务支持', url: '../service', callback: function(){
		parent.document.getElementById('myService').contentWindow.openTab(3);
	}});
});

$('.bulk-import').click(function(e){
  e.preventDefault();
  if (!Business.verifyRight('BaseData_IMPORT')) {
	  return ;
  };
  parent.$.dialog({
	  width: 560,
	  height: 300,
	  title: '批量导入',
	  content: 'url:../import',
	  data: {
		  callback: function(row){

		  }
	  },
	  lock: true
  });
});

$('#manageAcct').click(function(e){
	e.preventDefault();
    var updateUrl = location.protocol + '//' + location.host + '/update_info';
    $.dialog({
        min: false,
        max: false,
        cancle: false,
        lock: true,
        width: 500,
        height: 380,
        title: '账号管理',
        //content: 'url:' + url
		content: 'url:../home/set_password'
    });
});

//公告
(function (){
	var URL = parent.CONFIG.SERVICE_URL, SYSTEM = parent.SYSTEM;
	var version;
	switch (SYSTEM.siVersion) {
		case 3:
		  version = '1';
		  break;
		case 4:
		  version = '3';
		  break;
		default:
		  version = '2';
	};
	var param = '?eventType=2&serviceId=' + SYSTEM.DBID;	//自带参数
	$.getJSON("../home/Services?callback=?", {coid : SYSTEM.DBID, loginuserno: SYSTEM.UserName, version: version, type: 'getsystemmsg' + SYSTEM.servicePro}, function(data){ 
		if(data.msg == 'success'){
			if(data.data.length == 0){
				return;
			}
			var $notices = $('<span class="notices" id="notices"></span>'), 
				html = [], 
				notice,
				li = '';
			data = data.data;
			for(var i=0; i<data.length; i++){
				notice = data[i];
				if(notice.msglink){
					li = '<li><a target="_blank" href="' + notice.msglink + param + '" title="' + notice.msgtitle + '" data-id="' + notice.msgid + '"><i></i>' + notice.msgtitle + '</a></li>'
				}else{
					li = '<li><a href="../home/Services?newsId=' + notice.msgid + '" rel="pageTab" tabId="myService" tabTxt="服务支持" parentOpen="true" title="' + notice.msgtitle + '" data-id="' + notice.msgid + '"><i></i>' + notice.msgtitle + '</a></li>'
				}
				html.push(li);
			}
			$notices.append('<ul>' + html.join('') + '</ul>').appendTo('.welcome');
			Public.txtSlide();
		}
	});
})();
</script>
</body>
</html>