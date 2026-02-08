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

<style>
body{overflow-y:hidden;}
.matchCon{width:280px;}
#tree{background-color: #fff;width: 225px;border: solid #ddd 1px;margin-left: 5px;height:100%;}
h3{background: #EEEEEE;border: 1px solid #ddd;padding: 5px 10px;}
.grid-wrap{position:relative;}
.grid-wrap h3{border-bottom: none;}
#tree h3{border-style:none;border-bottom:solid 1px #D8D8D8;}
.quickSearchField{padding :10px; background-color: #f5f5f5;border-bottom:solid 1px #D8D8D8;}
#searchCategory input{width:165px;}
.innerTree{overflow-y:auto;}
#hideTree{cursor: pointer;color:#fff;padding: 0 4px;background-color: #B9B9B9;border-radius: 3px;position: absolute;top: 5px;right: 5px;}
#hideTree:hover{background-color: #AAAAAA;}
#clear{display:none;}
.show_ul{
	width:210px;
}
.show_ul li{width:200px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
.show_ul li:hover{background:#eee;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
			<div style="position:relative;width:161px;float:left;margin-right:5px;">
	          <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" placeholder="按物料编号或物料描述查询" style="width:200px;">
			  <ul class="show_ul" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>
	        </li>
	        <li style="margin-left:50px;"><a class="ui-btn mrb" id="search">查询</a></li>
	      </ul>
	    </div>
	    <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a><!--<a href="#" class="ui-btn mrb" id="btn-print">打印</a>--><a class="ui-btn mrb" id="daoru">导入</a><!--<a class="ui-btn mrb" id="btn-disable">禁用</a><a class="ui-btn mrb" id="btn-enable">启用</a><a href="#" class="ui-btn mrb" id="btn-import">导入</a>--><a href="#" class="ui-btn mrb" id="btn-export">导出</a><a href="#" class="ui-btn a_del" id="btn-batchDel">删除</a></div>
	  </div>
	  <div class="cf">
	    <div class="grid-wrap fl cf">
	    	<h3>当前分类：<span id='currentCategory'></span><!--<a href="javascript:void(0);" id='hideTree'>&gt;&gt;</a>--></h3>
		    <table id="grid"></table>
		    <div id="page"></div>
		</div>
		<!--<div class="fl cf" id='tree'>
			<h3>快速查询</h3>
			<div class="quickSearchField dn">
				<form class="ui-search" id="searchCategory">
					<input type="text" class="ui-input" /><button type="submit" title="点击搜索" >搜索</button>
				</form>
			</div>
		</div>-->
	</div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/lading/goodsList.js?ver=20190730"></script>


<script>	
$("#matchCon").focus(function(){
	$(".show_ul").css({display:"block"});
})	

$(document).bind("click",function(e){
    //id为menu的是菜单，id为open的是打开菜单的按钮
    if($(e.target).closest("#matchCon").length == 0 && $(e.target).closest(".show_ul").length == 0){
    //点击id为menu之外且id不是不是open，则触发
        $(".show_ul").css({display:"none"});
    }
})
$("#matchCon").bind("input propertychange", function () {  
       var matchCon = $("#matchCon").val(); //公司名称  
       $.ajax({  
           type: "post",  
           url: "../basedata/lading/querystock4?action=querystock4",
           data: { matchCon: matchCon },  
           dataType: "json",  
           success: function (data) {
				$(".show_ul").empty();			   
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性 
							//alert(data[i][j]);  //获得属性值
							$(".show_ul").append("<li>"+data[i][j]+"</li>");
					  }
						
				}
               /*if (data.statue == true) {  
                   alert(data.message);  
                   //$("#txt_gsName").val("");  
                   return false;  
               }  */
           }  
       });  
   }); 
   
   $(".show_ul").on("click","li", function() {
		var a=$(this).text();
		$("#matchCon").val(a);
		$(".show_ul").css({display:"none"})
	});
</script>

<script type="text/javascript">
	var systems = parent.SYSTEM;
if(systems.userName!="admin"){
	$(".a_del").css({display:"none"});
}
</script>

<script>
	$(function() {
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
