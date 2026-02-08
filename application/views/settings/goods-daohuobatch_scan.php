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
	    
		  <form action="#" method="post" id="formbtn" onsubmit="checkdatas()" >
			<table width="700" border="1" cellspacing="0" cellpadding="0" id="cangkutable">
				<tr>
					<th>采购订单号</th>
					<th>物料号</th>
					<th>物料描述</th>
					<th>申请数量</th>
					<th>现有库存</th>
					<th>已到货</th>
					<th>单位</th>
					<th>到货数量</th>
				</tr>
			</table>
			<table width="700" border="1" cellspacing="0" cellpadding="0" >
                <tr>
                    <th style="width:100px;">到货仓库</th>
                    <td><input type="text" class="myfile" name="dao" value="" style="width:200px;"/></th>
                </tr>
				<tr>
					<th style="width:300px;">到货时间：</th>
					<td><input type="text" class="sang_Calender" id="daotime" value="<?=date('Y-m-d')?>" name="daotime" style="width:400px;"/></th>
				</tr>
			</table><br/>
			<input type="submit" value="提交" id="chukubtn" style="padding:10px 30px;background:#4da916;color:white;border:none;margin-top:30px;margin-left:300px;"/>
		   
		 
		  
		  </form>
		  
	    </div>
	  </div>
</div>
<input type="hidden" value="<?php echo $id?>" id="ids"/>

<script src="<?php echo base_url()?>/statics/js/dist/datetimess.js?2"></script>

<script>
      function checkdatas(){
		if($("#daotime").val()==""){
			alert("请正确填写到货时间信息！");
			return false;

		}
          if($("input[name='dao']").val()==""){
              alert("请填写到货仓库！");
              return false;

          }
		else{

			$("#formbtn").attr("action", "../basedata/inventory/ordersdaohuo_scan?action=ordersdaohuo_scan");

			return true;
		}









	}
</script>

<script>

	$(function() {
		var ids=$("#ids").val();
		$.ajax({  
           type: "post",  
           url: "../basedata/inventory/showstocks?action=showstocks",
           data: { ids: ids },  
           dataType: "json",  
           success: function (data) {
							var id;
							var ordernumber;
							var goodsnumber;
							var mdescription;
							var inventoryNew;
							var inventoryOld;
							var daohuo;
							var mainUnit;
							var num=0;							
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性 
							//alert(data[i][j]);  //获得属性值
							
							
							if(j=="id"){
								
								id=data[i][j];
							}
							if(j=="ordernumber"){
								
								ordernumber=data[i][j];
							}
							if(j=="goodsnumber"){
								
								goodsnumber=data[i][j];
							}
							if(j=="mdescription"){
								
								mdescription=data[i][j];
							}
							if(j=="inventoryNew"){
								
								inventoryNew=data[i][j];
							}
                          if(j=="inventoryOld"){

                              inventoryOld=data[i][j];
                          }
                          if(j=="daohuo"){

                              daohuo=data[i][j];
                          }
							if(j=="mainUnit"){
								
								mainUnit=data[i][j];
							}
							
						}
						var num_ = inventoryOld - daohuo;
						$("#cangkutable").append(
						    "<tr>" +
                            "<input type='hidden' name='ids["+num+"]' value='"+id+"'/>" +
                            "<td>"+ordernumber+"</td>" +
                            "<td>"+goodsnumber+"</td>" +
                            "<td>"+mdescription+"</td>" +
                            "<td>"+inventoryOld+"</td>" +
                            "<td>"+inventoryNew+"</td>" +
                            "<td>"+daohuo+"</td>" +
                            "<td>"+mainUnit+"</td>" +
                            "<td><input type='text' class='myfile' name='daohuo["+num+"]' value='"+num_+"' placeholder='0'/></td>" +
                            "</tr>"
                        );
					  num++;
				}
              
           }  
       });  
		
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