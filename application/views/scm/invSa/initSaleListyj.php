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
#matchCon { width: 220px; }
#print{margin-left:10px;}
a.ui-btn{margin-left:10px;}
#reAudit,#audit{display:none;}
.show_ul,.show_ul2,.show_ul3,.show_ul4{
	width:210px;
}
.show_ul li{width:200px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
.show_ul li:hover{background:#eee;}
.show_ul2 li{width:200px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
.show_ul2 li:hover{background:#eee;}
.show_ul3 li{width:200px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
.show_ul3 li:hover{background:#eee;}
.show_ul4 li{width:200px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
.show_ul4 li:hover{background:#eee;}
</style>
</head>

<body>
<div class="wrapper">
  <div class="mod-search cf">
    <div class="fl">
      <ul class="ul-inline">
        <li>
		
          <!--<input type="text" id="matchCon" class="ui-input ui-input-ph" value="请输入单据号或客户名或备注">-->
		  <div style="position:relative;width:211px;float:left;margin-right:5px;">
		  <input type="text" id="matchCon" class="ui-input ui-input-ph" placeholder="请输入物料描述" style="width:200px;">
		  <ul class="show_ul" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
		  </div>
		  <div style="position:relative;width:211px;float:left;margin-right:5px;">
		  <input type="text" id="mnumber" class="ui-input ui-input-ph" placeholder="请输入物料编码" style="width:200px;">
		  <ul class="show_ul3" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
		  </div>
        </li>
		<!--
        <li>
          <label>日期:</label>
          <input type="text" id="beginDate" value="2015-04-10" class="ui-input ui-datepicker-input">

          <i>-</i>
          <input type="text" id="endDate" value="2015-04-16" class="ui-input ui-datepicker-input">
        </li>
		-->
        <li><!--<a class="mrb more" id="moreCon">(高级搜索)</a>--><a class="ui-btn" id="search">查询</a><!--<a class
="ui-btn ui-btn-refresh" id="refresh" title="刷新"><b></b></a>--></li>
      </ul>
    </div>
    <div class="fr"><a class="ui-btn ui-btn-sp" id="addyj">新增</a><!--<a class="ui-btn ui-btn-sp" id="guidang">归档</a><a class="ui-btn" id="export" target="_blank" href="javascript:void(0);">导出</a> <a href="#" class="ui-btn d_del" id="btn-batchDel">删除</a><a class="ui-btn" id="print" target="_blank"
 href="javascript:void(0);">打印</a> a class="ui-btn" id="import" target="_blank" href="javascript
:void(0);">导入</a>-->
<!--<a class="ui-btn dn" id="audit">审核</a><a class="ui-btn" id="reAudit">反审核</a>--></div>
  </div>
<!--  <div class="mod-toolbar-top cf">
    <div class="fl"><strong class="tit">仓库</strong></div>
    <div class="fr"><a class="ui-btn ui-btn-sp mrb" id="search">新增</a><a class="ui-btn" id="export">
导出</a></div>
  </div>-->
  <div class="grid-wrap">
    <table id="grid">
    </table>
    <div id="page"></div>
  </div>
</div>
<div class="Covering">
</div>
<div class="yjbox">
	<h2>添加库存预警</h2>
	<form action="../right/upduser?action=upduser" method="post">
		物料编号：<input type="text" name="yjgoodsnumner" class="yjgoodsnumner"/><br/><br/>
		预警数量：<input type="text" name="yjnum" class="yjnum"/><br/><br/>
		<input type="button" value="添加" class="ordersbtn"/>&ensp;<input type="button" value="取消" class="close"/>
	</form>
</div>
<div class="yjboxupd">
	<h2>修改库存预警</h2>
	<form action="../right/upduser?action=upduser" method="post">
		<input type="hidden" class="yjid" name="yjid" value=""/>
		物料编号：<input type="text" name="yjgoodsnumnerupd" class="yjgoodsnumnerupd"/><br/><br/>
		预警数量：<input type="text" name="yjnumupd" class="yjnumupd"/><br/><br/>
		<input type="button" value="修改" class="ordersbtnupd"/>&ensp;<input type="button" value="取消" class="close"/>
	</form>
</div>
<script>
	$(".ordersbtnupd").click(function(){
		var yjid=$(".yjid").val();
		var yjgoodsnumnerupd=$(".yjgoodsnumnerupd").val();
		var yjnumupd=$(".yjnumupd").val();
		$.ajax({  
           type: "post",  
           url: "../basedata/inventory/updyj?action=updyj",  
           data: { yjid:yjid,yjgoodsnumnerupd: yjgoodsnumnerupd,yjnumupd:yjnumupd },  
           dataType: "json",  
           success: function (data) {
				for(i in data ){
					  if(data[i]=="success"){
						  alert("修改成功");
						  window.location.reload();
					  }
					  if(data[i]=="error"){
						   alert("修改失败！");
					  }
					 
				}
           }  
       }); 
	})
</script>
<style>
	form{padding:20px;font-size:15px;}
	form input{width:230px;height:30px;}
	h2{font-size:20px;text-align:center;padding:10px 0;}
	.Covering{position:absolute;width:100%;height:100%;background:rgba(0,0,0,0.3);z-index:999;left:0;top:0;display:none;}
	.yjbox{
		width:400px;height:280px;position:absolute;left:50%;margin-left:-200px;top:50%;margin-top:-140px;z-index:999;background:white;box-shadow:0px 0px 20px rgba(0,0,0,0.3);display:none;
	}
	.yjboxupd{
		width:400px;height:280px;position:absolute;left:50%;margin-left:-200px;top:50%;margin-top:-140px;z-index:999;background:white;box-shadow:0px 0px 20px rgba(0,0,0,0.3);display:none;
	}
	.ordersbtn,.ordersbtnupd,.close{padding:3px 20px;border:none;width:100px;}
	.ordersbtn,.ordersbtnupd{background:#4da916;color:white;}
</style>
<script>
	//$("#addyj").click(function(){
	//	$(".yjbox").show();
	//	$(".Covering").show();
	//})
	$(".close").click(function(){
		$(".yjbox").hide();
		$(".yjboxupd").hide();
		$(".Covering").hide();
	})
	$(".ordersbtn").click(function(){
		
		var yjgoodsnumner=$(".yjgoodsnumner").val();
		var yjnum=$(".yjnum").val();
		
		  $.ajax({  
           type: "post",  
           url: "../basedata/inventory/addyj?action=addyj",  
           data: { yjgoodsnumner: yjgoodsnumner,yjnum:yjnum },  
           dataType: "json",  
           success: function (data) {
				for(i in data ){
					  for(j in data[i]){
						 if(data[i][j]==1){
							 alert("已添加过，请修改！");
							 window.location.reload();
						 }
						
					  }
					  if(data[i]=="success"){
						  alert("添加成功");
						  window.location.reload();
					  }
					  if(data[i]=="error"){
						   alert("物料编号不存在！");
					  }
					 
				}
           }  
       }); 
		
	})
</script>
<script src="<?php echo base_url()?>statics/js/dist/salesListyj.js?ver=<?php echo time(); ?>"></script>
 


<script>	
$("#ordernumber").focus(function(){
	$(".show_ul4").css({display:"block"});
})	

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮            
            if($(e.target).closest("#ordernumber").length == 0 && $(e.target).closest(".show_ul4").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul4").css({display:"none"});
            }
        }) 
$("#ordernumber").bind("input propertychange", function () {  
       var ordernumber = $("#ordernumber").val(); //公司名称  
       $.ajax({  
           type: "post",  
           url: "../basedata/inventory/querystock8?action=querystock8",  
           data: { ordernumber: ordernumber },  
           dataType: "json",  
           success: function (data) {
				$(".show_ul4").empty();			   
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性 
							//alert(data[i][j]);  //获得属性值
							$(".show_ul4").append("<li>"+data[i][j]+"</li>");
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
   
   $(".show_ul4").on("click","li", function() {
		var a=$(this).text();
		$("#ordernumber").val(a);
		$(".show_ul4").css({display:"none"})
	});
</script>


<script>	
$("#mnumber").focus(function(){
	$(".show_ul3").css({display:"block"});
})	

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮            
            if($(e.target).closest("#mnumber").length == 0 && $(e.target).closest(".show_ul3").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul3").css({display:"none"});
            }
        }) 
$("#mnumber").bind("input propertychange", function () {  
       var mnumber = $("#mnumber").val(); //公司名称  
       $.ajax({  
           type: "post",  
           url: "../basedata/inventory/querystock15?action=querystock15",  
           data: { mnumber: mnumber },  
           dataType: "json",  
           success: function (data) {
				$(".show_ul3").empty();			   
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性 
							//alert(data[i][j]);  //获得属性值
							$(".show_ul3").append("<li>"+data[i][j]+"</li>");
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
   
   $(".show_ul3").on("click","li", function() {
		var a=$(this).text();
		$("#mnumber").val(a);
		$(".show_ul3").css({display:"none"})
	});
</script>



<script>	
$("#mname").focus(function(){
	$(".show_ul2").css({display:"block"});
})	

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮            
            if($(e.target).closest("#mname").length == 0 && $(e.target).closest(".show_ul2").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul2").css({display:"none"});
            }
        }) 
$("#mname").bind("input propertychange", function () {  
       var mname = $("#mname").val(); //公司名称  
       $.ajax({  
           type: "post",  
           url: "../basedata/inventory/querystock6?action=querystock6",  
           data: { mname: mname },  
           dataType: "json",  
           success: function (data) {
				$(".show_ul2").empty();			   
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性 
							//alert(data[i][j]);  //获得属性值
							$(".show_ul2").append("<li>"+data[i][j]+"</li>");
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
   
   $(".show_ul2").on("click","li", function() {
		var a=$(this).text();
		$("#mname").val(a);
		$(".show_ul2").css({display:"none"})
	});
</script>



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
           url: "../basedata/inventory/querystock14?action=querystock14",  
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
if(urlParam.Type=="chukudan"){
	$(".d_del").css({display:"none"});
}
	var systems = parent.SYSTEM;
if(systems.userName!="admin"){
	$(".fr").css({display:"none"});
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


