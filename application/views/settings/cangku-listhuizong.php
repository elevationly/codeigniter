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
.show{
	width:150px;
}
.show li{width:140px;height:25px;line-height:25px;background:#fafdfe;padding-left:10px;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
				<div style="position:relative;width:151px;float:left;margin-right:5px;">
				<input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" placeholder="按商品编号查询" style="width:140px;">

					<ul class="show show_ul" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
				</div>
				<div style="position:relative;width:151px;float:left;margin-right:5px;">
					<input type="text" id="mdescription" class="ui-input ui-input-ph mdescription" placeholder="按物料描述查询" style="width:140px;">
					<ul class="show show_ul2" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>
			  <div style="position:relative;width:151px;float:left;margin-right:5px;">
				<input type="text" id="ordernumber" class="ui-input ui-input-ph ordernumber" placeholder="按订单编号查询" style="width:140px;">
				<ul class="show show_ul3" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>
			  <div style="position:relative;width:151px;float:left;margin-right:5px;">
				<input type="text" id="Arrivaltime" class="ui-input ui-input-ph Arrivaltime" placeholder="按出库时间查询" style="width:140px;">
				<ul class="show show_ul4" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>
			  <div style="position:relative;width:151px;float:left;margin-right:5px;">
				<input type="text" id="sign" class="ui-input ui-input-ph Arrivaltime" placeholder="按领料人查询" style="width:140px;">
				<ul class="show show_ul5" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>
                <div style="position:relative;width:151px;float:left;margin-right:5px;">
                    <input type="text" id="flagcontact" class="ui-input ui-input-ph Arrivaltime" placeholder="按供应商查询" style="width:140px;">
                    <ul class="show show_ul7" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
                    </ul>
                </div>
			  <div style="position:relative;width:151px;float:left;margin-right:5px;">
				<input type="text" id="beizhu" class="ui-input ui-input-ph Arrivaltime" placeholder="按项目备注查询" style="width:140px;">
				<ul class="show show_ul6" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
			  </div>

			  <!--<div style="position:relative;width:101px;float:left;margin-right:5px;">
				<input type="text" id="flagNo" class="ui-input ui-input-ph flagNo" placeholder="按是否到货查询" style="width:150px;">
				<ul class="show_ul5" style="position:absolute;top:30px;left:0px;z-index:999;border:1px solid #ddd;display:none;overflow-y:auto;max-height:300px;">
					</ul>
					<select id="flagNo" style="width:100px;height:30px;">
						<option>全部</option>
						<option>已出库</option>
						<option>未出库</option>
					</select>
			  </div>-->

                <div style="position: relative;width:111px;float:left;margin-right:5px;">
                    <select name="status" style="width: 90px;height: 31px;">
                        <option value="">全部</option>
                        <option value="1">已领用</option>
                        <option value="0">未领用</option>
                    </select>
                </div>
	        </li>
	        <li><a class="ui-btn mrb" id="search">查询</a><a class="ui-btn mrb" id="shuaxin">刷新</a></li>

	      </ul>
	    </div>
	    <div class="fr"><!--<a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a>
	    <a href="#" class="ui-btn mrb" id="btn-print">打印</a>
	    <a class="ui-btn mrb" id="daohuo">出库</a>
	    <a class="ui-btn mrb" id="daoru">导入</a>-->
            <!--<a class="ui-btn mrb" id="btn-disable">禁用</a>
            <a class="ui-btn mrb" id="btn-enable">启用</a>
            <a href="#" class="ui-btn mrb" id="btn-import">导入</a>-->
            <a href="#" class="ui-btn mrb" id="btn-export">导出</a>
            <a href="#" class="ui-btn a_del" id="btn-batchDel">删除</a>
        </div>
	  </div>
	  <div class="cf">
	    <div class="grid-wrap fl cf">
	    	<h3>当前分类：<span id='currentCategory'></span><!--<a href="javascript:void(0);" id='hideTree'>&gt;&gt;</a>--></h3>
		    <table id="grid">
		    </table>
		    <div id="page"></div>
		</div>
		<div class="fl cf" id='tree'>
			<h3>快速查询</h3>
			<div class="quickSearchField dn">
				<form class="ui-search" id="searchCategory">
					<input type="text" class="ui-input" /><button type="submit" title="点击搜索" >搜索</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="Covering">
</div>
<div class="update_box">
	<h2>库存修改</h2>
	<form action="../right/upduser?action=upduser" method="post">
		<input type="hidden" value="" name="h_us" class="h_us"/>
		物料编号：<input type="text" name="goodsnumber" class="goodsnumber"/>
		物料描述：<input type="text" name="mdescription" class="mdescriptions"/><br/><br/>
		申请数量：<input type="text" name="inventoryOld" class="inventoryOld"/>
		领用数量：<input type="text" name="number" class="number"  disabled="disabled"/><br/><br/>
		库存数量：<input type="text" name="inventoryNew" class="inventoryNew"/>
		单&ensp;&ensp;&ensp;&ensp;位：<input type="text" name="mainUnit" class="mainUnit"/><br/><br/>
		订&ensp;单&ensp;号：<input type="text" name="ordernumber" class="ordernumbers"/>
		单&ensp;&ensp;&ensp;&ensp;价：<input type="text" name="price" class="price"/><br/><br/>
		出库金额：<input type="text" name="amount" class="amount"/>
		仓&ensp;&ensp;&ensp;&ensp;库：<input type="text" name="locationName" class="locationName"/><br/><br/>
		申请时间：<input type="text" name="Arrivaltime" class="Arrivaltimes"/>
		是否到货：<input type="text" name="flagNo" class="flagNos"/><br/><br/>
		到货时间：<input type="text" name="flagtime" class="flagtime"/>
		供&ensp;应&ensp;商：<input type="text" name="flagcontact" class="flagcontact"/><br/><br/>
		备&ensp;&ensp;&ensp;&ensp;注：<input type="text" name="beizhu" class="beizhu"/><br/><br/>
		<input type="button" value="更改" class="ordersbtn"/>&ensp;<input type="button" value="取消" class="close"/>
	</form>
</div>
<style>
	.Prompt{font-size:14px;color:red;visibility:hidden;}
	form{padding:20px;font-size:15px;}
	form input{width:230px;height:30px;}
	h2{font-size:20px;text-align:center;padding:10px 0;}
	.Covering{position:absolute;width:100%;height:100%;background:rgba(0,0,0,0.3);z-index:999;left:0;top:0;display:none;}
	.update_box{
		width:700px;height:600px;position:absolute;left:50%;margin-left:-350px;top:50%;margin-top:-300px;z-index:999;background:white;box-shadow:0px 0px 20px rgba(0,0,0,0.3);display:none;
	}
	.ordersbtn,.close{padding:3px 20px;border:none;width:100px;}
	.ordersbtn{background:#4da916;color:white;}
</style>
<script>
    $("#shuaxin").click(function(){
        location.reload();
    })
	$(".close").click(function(){
		$(".update_box").hide();
		$(".Covering").hide();
	})
</script>
<script src="<?php echo base_url()?>statics/js/dist/cangkuListhuizong.js?ver=20140430"></script>

<script>
    $("#flagcontact").focus(function(){
        $(".show_ul7").css({display:"block"});
    })

    $(document).bind("click",function(e){
        //id为menu的是菜单，id为open的是打开菜单的按钮
        if($(e.target).closest("#flagcontact").length == 0 && $(e.target).closest(".show_ul7").length == 0){
            //点击id为menu之外且id不是不是open，则触发
            $(".show_ul7").css({display:"none"});
        }
    })
    $("#flagcontact").bind("input propertychange", function () {
        var flagcontact = $("#flagcontact").val(); //项目备注
        $.ajax({
            type: "post",
            url: "../basedata/inventory/querystock13xmb?action=querystock13xmb",
            data: { flagcontact: flagcontact },
            dataType: "json",
            success: function (data) {
                $(".show_ul7").empty();
                for(i in data ){
                    for(j in data[i]){
                        // alert(j);           //获得属性
                        //alert(data[i][j]);  //获得属性值
                        $(".show_ul7").append("<li>"+data[i][j]+"</li>");
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

    $(".show_ul7").on("click","li", function() {
        var a=$(this).text();
        $("#flagcontact").val(a);
        $(".show_ul7").css({display:"none"})
    });
</script>
<script>
$("#beizhu").focus(function(){
	$(".show_ul6").css({display:"block"});
})

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮
            if($(e.target).closest("#beizhu").length == 0 && $(e.target).closest(".show_ul6").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul6").css({display:"none"});
            }
        })
$("#beizhu").bind("input propertychange", function () {
       var beizhu = $("#beizhu").val(); //公司名称
       $.ajax({
           type: "post",
           url: "../basedata/inventory/querystock13b?action=querystock13b",
           data: { beizhu: beizhu },
           dataType: "json",
           success: function (data) {
				$(".show_ul6").empty();
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性
							//alert(data[i][j]);  //获得属性值
							$(".show_ul6").append("<li>"+data[i][j]+"</li>");
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

   $(".show_ul6").on("click","li", function() {
		var a=$(this).text();
		$("#beizhu").val(a);
		$(".show_ul6").css({display:"none"})
	});
</script>


<script>
$("#sign").focus(function(){
	$(".show_ul5").css({display:"block"});
})

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮
            if($(e.target).closest("#sign").length == 0 && $(e.target).closest(".show_ul5").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul5").css({display:"none"});
            }
        })
$("#sign").bind("input propertychange", function () {
       var sign = $("#sign").val(); //公司名称
       $.ajax({
           type: "post",
           url: "../basedata/inventory/querystock13s?action=querystock13s",
           data: { sign: sign },
           dataType: "json",
           success: function (data) {
				$(".show_ul5").empty();
				  for(i in data ){
					  for(j in data[i]){
						 // alert(j);           //获得属性
							//alert(data[i][j]);  //获得属性值
							$(".show_ul5").append("<li>"+data[i][j]+"</li>");
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

   $(".show_ul5").on("click","li", function() {
		var a=$(this).text();
		$("#sign").val(a);
		$(".show_ul5").css({display:"none"})
	});
</script>


<script>
$("#Arrivaltime").focus(function(){
	$(".show_ul4").css({display:"block"});
})

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮
            if($(e.target).closest("#Arrivaltime").length == 0 && $(e.target).closest(".show_ul4").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul4").css({display:"none"});
            }
        })
$("#Arrivaltime").bind("input propertychange", function () {
       var Arrivaltime = $("#Arrivaltime").val(); //公司名称
       $.ajax({
           type: "post",
           url: "../basedata/inventory/querystockhuizong4?action=querystockhuizong4",
           data: { Arrivaltime: Arrivaltime },
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
		$("#Arrivaltime").val(a);
		$(".show_ul4").css({display:"none"})
	});
</script>


<script>
$("#ordernumber").focus(function(){
	$(".show_ul3").css({display:"block"});
})

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮
            if($(e.target).closest("#ordernumber").length == 0 && $(e.target).closest(".show_ul3").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul3").css({display:"none"});
            }
        })
$("#ordernumber").bind("input propertychange", function () {
       var ordernumber = $("#ordernumber").val(); //公司名称
       $.ajax({
           type: "post",
           url: "../basedata/inventory/querystockhuizong3?action=querystockhuizong3",
           data: { ordernumber: ordernumber },
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
		$("#ordernumber").val(a);
		$(".show_ul3").css({display:"none"})
	});
</script>


<script>
$("#mdescription").focus(function(){
	$(".show_ul2").css({display:"block"});
})

$(document).bind("click",function(e){
            //id为menu的是菜单，id为open的是打开菜单的按钮
            if($(e.target).closest("#mdescription").length == 0 && $(e.target).closest(".show_ul2").length == 0){
            //点击id为menu之外且id不是不是open，则触发
                $(".show_ul2").css({display:"none"});
            }
        })
$("#mdescription").bind("input propertychange", function () {
       var mdescription = $("#mdescription").val(); //公司名称
       $.ajax({
           type: "post",
           url: "../basedata/inventory/querystockhuizong2?action=querystockhuizong2",
           data: { mdescription: mdescription },
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
		$("#mdescription").val(a);
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
           url: "../basedata/inventory/querystockhuizong1?action=querystockhuizong1",
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
/*	var systems = parent.SYSTEM;
if(systems.userName!="admin"){
	$(".a_del").css({display:"none"});
}*/
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
