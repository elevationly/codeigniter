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

<div id="hd" class="cf">
  <div class="fl welcome cf">
	  <strong><span id="greetings"></span>，<span id="username"></span></strong>
	  <a href="javascrip:void(0);" id="manageAcct">账号管理</a>
	  <!--<a href="" target="_blank" id="newGuide" title="新手入门">新手入门</a>-->
  </div>
  
  
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
<style>
	h2{font-size:20px;}
	.a{font-size:16px;margin-left:18px;padding:5px 20px;background:#1B96A9;color:white;border-radius:5px 5px 0 0;margin-bottom:10px;cursor:pointer;}
</style>
<script type="text/javascript">
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

$(function() { 
	$("#Contract").click(function (){  
		$.dialog({
			content: "url:../settings/Contract",
			data: {
				title: '合同上传',
				id: "<?php echo $billNo?>",
				callback: function() {}
				},
			title: '合同上传',
			width: 775,
			height: 470,
			max: !1,
			min: !1,
			cache: !1,
			lock: !0
		})
	}); 
	$("#Contract1").click(function (){  
		$.dialog({
			content: "url:../settings/Contract",
			data: {
				title: '合同上传',
				id: "<?php echo $billNo?>",
				callback: function() {}
				},
			title: '合同上传',
			width: 775,
			height: 470,
			max: !1,
			min: !1,
			cache: !1,
			lock: !0
		})
	})
}); 
</script>

<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/bills.css?ver=201505122" rel="stylesheet" type="text/css">
<style>
#barCodeInsert{margin-left: 10px;font-weight: 100;font-size: 12px;color: #fff;background-color: #B1B1B1;padding: 0 5px;border-radius: 2px;line-height: 19px;height: 20px;display: inline-block;}
#barCodeInsert.active{background-color: #23B317;}
</style>
</head>
<body>

<div class="dis_box" style="display:none;">
    <span class="a" id="a">出库单</span>
    <span class="a" id="b">领料单</span>
    <span class="a" id="c">提货单</span>
<div>

<div id="bd" class="index-body cf c" style="display:none;">
  <div class="grid-wrap">
    <table id="gridss">
    </table>
  </div>   
</div>



<div>
<div id="bd" class="index-body cf d" >
  <div class="grid-wrap">
    <table id="grids">
    </table>
  </div>   
</div>
</div>

    <div>
        <div id="bd" class="index-body cf e" style="display:none;">
            <div class="grid-wrap">
                <table id="ladingGrids">
                </table>
            </div>
        </div>
    </div>



<div class="wrapper dis_big" style="display:none;">
  <span id="config" class="ui-icon ui-state-default ui-icon-config" style="margin-top:20px;"></span>
  <div class="mod-toolbar-top mr0 cf dn" id="toolTop"></div>
  <div class="bills cf">
    <div class="con-header">
      <dl class="cf">
        <dd class="pct30" style="width:100%">
          <label>项目名:</label>
          <span id="customer"  class="ui-combo-wrap"   style="width:530px;" >
          <input type="text" name="" class="input-txt" autocomplete="off" value="" data-ref="date" readonly style="width:530px">
          <i class="ui-icon-ellipsis"></i></span></dd>
          
 
           <dd class="pct30">
          <label>领料人:</label>
          <span class="ui-combo-wrap" id="sales">
              <input type="text" class="input-txt" autocomplete="off" readonly >
          <i class="trigger"></i></span></dd>
          
          
        <dd class="pct25 tc">
          <label>单据日期:</label>
          <input type="text" id="date" class="ui-input ui-datepicker-input" value="2015-08-27">
        </dd>
        <dd id="identifier" class="pct25 tc">
          <label>单据编号:</label>
          <span id="number"><?php echo $billNo?></span></dd>
      </dl>
    </div>
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
    <div class="con-footer cf">
    <!--
      <div class="mb10">
      	<textarea type="text" id="note" class="ui-input ui-input-ph">暂无备注信息</textarea>
      </div>
      -->
      <ul id="amountArea" class="cf" >
      
      <!--
        <li>
          <label>优惠率:</label>
          <input type="text" id="discountRate" class="ui-input" data-ref="deduction">%
        </li>
        <li>
          <label>优惠金额:</label>
          <input type="text" id="deduction" class="ui-input" data-ref="payment">
        </li>
        <li>
          <label>优惠后金额:</label>
          <input type="text" id="discount" class="ui-input ui-input-dis" data-ref="discountRate" disabled>
        </li>
        <li>
          <label id="paymentTxt">本次付款:</label>
          <input type="text" id="payment" class="ui-input">&emsp;
        </li>
        <li id="accountWrap" class="dn">
          <label>结算账户:</label>
          <span class="ui-combo-wrap" id="account" style="padding:0;">
          <input type="text" class="input-txt" autocomplete="off">
          <i class="trigger"></i></span><a id="accountInfo" class="ui-icon ui-icon-folder-open" style="display:none;"></a>
        </li>
        <li>
          <label>本次欠款:</label>
          <input type="text" id="arrears" class="ui-input ui-input-dis" disabled>
        </li>
        
        -->
		  <span class="ui-combo-wrap" id="account" style="padding:0;">
          <li>
          <label>制单人:</label>
          <span id="userName"></span>
        </li>
		
		<li>
            
          <label>手写签名:</label>
          
            <img class="ui-input ui-input-dis"  id="handwritingimg" style="height:30px"  src="/statics/css/green/img/side_rp.png"  onClick="handwriting()" > 
          
            <input type="hidden" class="ui-input ui-input-dis"  style="width:200px"  id="sign" > 
          <script>
		//调用手写板签名返回数据
	//弹出来的签名板子
		function handwriting(){
		  var img=$("#handwritingimg").prop("src");
		//if(img=="data:image/gif;base64,"){
			$(".handwriteborder").show();
	  $("#handwriteborder").show();
	//  $("#ldg_lockmask").show();
	  
	  
		$.dialog({
			title : '签字区域',
			content : 'url:/handwrite.php',
			data: {oper: 'add', callback: function(data, oper, dialogWin){
				
				dialogWin && dialogWin.api.close();
			
			}},
		 close: function(event, ui) {  
	 
		 }, 
			width : 600,
			height :350,
			max : false,
			min : false,
			cache : false,
			lock: true
		});
	//	}
	  
	} 
	   
//调用手写板签名结束
		</script>
         <!--  
		  <?php 
		  if ($this->common_model->checkpurviews(203)){
		  ?>
		  <a id="Contract" class="ui-btn">上传</a>
		  <?php 
		  }
		  if ($this->common_model->checkpurviews(204)){
		  ?>
		  <a id="Contract1" class="ui-btn">查看</a>
		  <?php 
		  }
		  ?>
          -->
        </li>
		<!--
        <li class="dn">
          <label>累计欠款:</label>
          <input type="text" id="totalArrears" class="ui-input ui-input-dis" disabled>
        </li>
        -->
      </ul>
      <ul class="c999 cf">
        
        <li>
          <label>审核人:</label>
          <span id="checkName"></span>
        </li>
		<li>
          <label>录单时间:</label>
          <span id="createTime"></span>
        </li>
        <li>
          <label>最后修改时间:</label>
          <span id="modifyTime"></span>
        </li>
      </ul>
    </div>
    <div class="cf" id="bottomField">
    	<div class="fr" id="toolBottom"></div>
    </div>
    <div id="mark"></div>
  </div>
  
  <div id="initCombo" class="dn">
    <input type="text" class="textbox goodsAuto" name="goods" autocomplete="off">
    <input type="text" class="textbox storageAuto" name="storage" autocomplete="off">
    <input type="text" class="textbox unitAuto" name="unit" autocomplete="off">
	<input type="text" class="textbox djtypeAuto" name="djtype" autocomplete="off">
	
    <input type="text" class="textbox batchAuto" name="batch" autocomplete="off">
    <input type="text" class="textbox dateAuto" name="date" autocomplete="off">
    <input type="text" class="textbox priceAuto" name="price" autocomplete="off">
    <input type="text" class="textbox skuAuto" name="price" autocomplete="off">
  </div>
  <div id="storageBox" class="shadow target_box dn" >
  </div>
</div>

<script>
	$(function(){
		
		$(".ui-jqgrid-bdiv").css({
	
			height:"auto"
		})
		
		
	})
</script>

<script language="javascript">
$(function(){
	var systems = parent.SYSTEM;
	if(systems.userName=="admin"||systems.userName=="shenhe"||systems.isAdmin){
		$(".dis_box").css({display:"block"})
		$("#b").css({background:"#1B96A9",color:"white"})
		$("#a").css({background:"white",color:"black"})
        $("#c").css({background:"white",color:"black"})
		var url="<?php echo base_url()?>statics/js/dist/salesListmain.js?ver=20260813";
		$("#a").click(function(){
			url="<?php echo base_url()?>statics/js/dist/purchaseListmain.js?ver=20140430";
			$(this).css({background:"#1B96A9",color:"white"})
			$("#b").css({background:"white",color:"black"})
            $("#c").css({background:"white",color:"black"})
			$(".c").css({display:"block"})
			$(".d").css({display:"none"})
            $(".e").css({display:"none"})
			var script = document.createElement('script');
			script.type = "text/javascript";
			script.src = url;
			document.body.appendChild(script);
		})
		$("#b").click(function(){
			url="<?php echo base_url()?>statics/js/dist/salesListmain.js?ver=20260813";
			$(this).css({background:"#1B96A9",color:"white"})
			$("#a").css({background:"white",color:"black"})
            $("#c").css({background:"white",color:"black"})
			$(".c").css({display:"none"})
			$(".d").css({display:"block"})
            $(".e").css({display:"none"})
			var script = document.createElement('script');
			script.type = "text/javascript";
			script.src = url;
			document.body.appendChild(script);
		})
        $("#c").click(function(){
            url="<?php echo base_url()?>statics/js/dist/lading/purchaseListmain.js?ver=20190430";
            $(this).css({background:"#1B96A9",color:"white"})
            $("#a").css({background:"white",color:"black"})
            $("#b").css({background:"white",color:"black"})
            $(".c").css({display:"none"})
            $(".d").css({display:"none"})
            $(".e").css({display:"block"})
            var script = document.createElement('script');
            script.type = "text/javascript";
            script.src = url;
            document.body.appendChild(script);
        })
        var script = document.createElement('script');
			script.type = "text/javascript";
			script.src = url;
			document.body.appendChild(script);	
	}else{
		$(".dis_big").css({display:"block"})
		var urls="<?php echo base_url()?>statics/js/dist/purchase.js?ver=201510241556";
		var script = document.createElement('script');
		script.type = "text/javascript";
		script.src = urls;
		document.body.appendChild(script);
	}
		
	
})
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