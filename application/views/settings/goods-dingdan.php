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

<style>
#matchCon { width: 200px; }
#locationNameCon { width: 160px; margin-left: 8px; }
.mod-search .search-input-wrap { position: relative; display: inline-block; }
.mod-search .search-input-wrap .ui-input { padding-right: 22px; }
.mod-search .input-clear { position: absolute; right: 6px; top: 50%; margin-top: -8px; cursor: pointer; color: #999; font-size: 16px; line-height: 1; display: none; z-index: 1; }
.mod-search .search-input-wrap.has-text .input-clear { display: block; }
.mod-search .input-clear:hover { color: #333; }
.grid-wrap{position:relative;}
.ztreeDefault{position: absolute;right: 0;top: 0;background-color: #fff;border: 1px solid #D6D5D5;width: 140px;height: 406px;overflow-y: auto;}
</style>
</head>

<body class="bgwh">
<div class="container fix p20">
	  <div class="mod-search m0 cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
	          <span class="search-input-wrap"><input type="text" id="matchCon" class="ui-input ui-input-ph" placeholder="请输入商品编号或描述或订单号或项目备注" autocomplete="off"><i class="input-clear" title="清除">×</i></span>
	        </li>
	        <li>
	          <span class="search-input-wrap"><input type="text" id="locationNameCon" class="ui-input ui-input-ph" placeholder="仓库名称" autocomplete="off"><i class="input-clear" title="清除">×</i></span>
	        </li>
	        <li><a class="ui-btn mrb" id="search">查询</a><a class="ui-btn" id="refresh">刷新</a></li>
	      </ul>
	    </div>
	  </div>
	  <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<input type="hidden" value="<?php echo $ordert?>" id="ordert"/>
<script src="<?php echo base_url()?>/statics/js/dist/goodsorderBatchs.js?v=post"></script>
<script>
	$(function() {
		$(".ztree").css({display:"none"})
// 如果不支持placeholder，用jQuery来完成
if(!isSupportPlaceholder()) {
// 遍历所有input对象, 除了密码框
$('input').not("input[type='password']").each(
function() {
var self = $(this);
var val = self.attr("placeholder");
input(self, val);
}
);

/**//* 对password框的特殊处理
* 1.创建一个text框
* 2.获取焦点和失去焦点的时候切换
*/
$('input[type="password"]').each(
function() {
var pwdField = $(this);
var pwdVal = pwdField.attr('placeholder');
var pwdId = pwdField.attr('id');
// 重命名该input的id为原id后跟1
pwdField.after('<input id="' + pwdId +'1" type="text" value='+pwdVal+' autocomplete="off" />');
var pwdPlaceholder = $('#' + pwdId + '1');
pwdPlaceholder.show();
pwdField.hide();

pwdPlaceholder.focus(function(){
pwdPlaceholder.hide();
pwdField.show();
pwdField.focus();
});

pwdField.blur(function(){
if(pwdField.val() == '') {
pwdPlaceholder.show();
pwdField.hide();
}
});
}
);
}
});

// 判断浏览器是否支持placeholder属性
function isSupportPlaceholder() {
var input = document.createElement('input');
return 'placeholder' in input;
}

// jQuery替换placeholder的处理
function input(obj, val) {
var $input = obj;
var val = val;
$input.attr({value:val});
$input.focus(function() {
if ($input.val() == val) {
$(this).attr({value:""});
}
}).blur(function() {
if ($input.val() == "") {
$(this).attr({value:val});
}
});
}
</script>
</body>
</html>