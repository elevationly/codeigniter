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
.grid-wrap{position:relative;}
.ztreeDefault{position: absolute;right: 0;top: 0;background-color: #fff;border: 1px solid #D6D5D5;width: 140px;height: 406px;overflow-y: auto;}
.fl form table tr th{padding:8px 5px;border: 1px solid #D6DEE3;}
.fl form table tr td{padding:8px 5px;border: 1px solid #D6DEE3;}
.fl form table{border: 1px solid #D6DEE3;}
.fl form table input{border:none;}
</style>
</head>
<body class="bgwh">
<div class="container fix p20">
	  <div class="mod-search m0 cf">
	    <div class="fl">
	       <!--<ul class="ul-inline">
	       <li>
	          <input type="text" id="matchCon" class="ui-input ui-input-ph" placeholder="请输入商品编号或描述">
	        </li>
	        <li><a class="ui-btn mrb" id="search">查询</a><!-- <a class="ui-btn" id="refresh">刷新</a> </li>
	      </ul>-->
		  <form action="../basedata/inventory/ordersdaohuo?action=ordersdaohuo" method="post" id="formbtn">
		  <input type="hidden" value="<?php echo $id?>" name="id"/>
			<table width="300" border="1" cellspacing="0" cellpadding="0" >
				<tr>
					<th style="width:100px;">到货仓库</th>
					<td><input type="text" class="myfile" name="dao" value="" style="width:200px;"/></th>
				</tr>
				<tr>
					<th style="width:100px;">到货时间：</th>
					<td><input type="text" class="sang_Calender" id="chukutimes" value="" name="daotime" style="width:200px;"/></th>
				</tr>				
			</table><br/>
			<input type="button" value="提交" id="chukubtn" style="padding:10px 30px;background:#4da916;color:white;border:none;margin-top:30px;margin-left:100px;"/>
		  </form>
		  
	    </div>
	  </div>
</div>

<script src="<?php echo base_url()?>/statics/js/dist/datetimess.js?2"></script>

<script>
	$("#chukubtn").click(function(){
		if($("#chukutimes").val()==""){
			alert("请正确填写出库时间信息！");
		return false;
			
		}
		 $(".myfile").each(function(){

    　　if($(this).val() == "") {

        　　alert("请正确填写信息！");
		return false;
    　　}else{
		$("#formbtn").submit();
		
	}
    }); 
		
		
	})
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