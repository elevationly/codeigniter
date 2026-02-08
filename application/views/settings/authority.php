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


<script>
function validMaxForShare(e){
	if (Business.verifyRight("zinzentong")) {
    $.ajax({
      url: '../right/isMaxShareUser?action=isMaxShareUser',
      dataType: 'json',
      type: 'POST',
      success: function(data){
        if(data.status === 200){
        	var json = data.data;
        	if(json.shareTotal >= json.totalUserNum)
        	{
        		parent.Public.tips({type:2, content : '共享用户已经达到上限值：'+json.totalUserNum});
        		return false;
        	}else
        	{
        		window.location.href='../settings/authority_new';
        	}	
        }
      }
  });
	}
}
</script>
</head>
<body>
<div class="wrapper">
    <div class="mod-toolbar-top">
       <a href="javascript:validMaxForShare();" class="ui-btn ui-btn-sp mrb">新增同事</a>
       <span class="tit" id="shareInfo" style="display:none;">该账套主服务最多支持<strong id="totalUser"></strong>用户共同管理，已共享<strong id="usedTotal"></strong>人，剩余<strong id="leftTotal"></strong>。</span>
    </div>    
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
</div>
<div class="Covering">
</div>
<div class="update_box">
	<h2>管理员信息修改</h2>
	<form action="../right/upduser?action=upduser" method="post">
		<input type="hidden" value="" name="h_us" class="h_us"/>
		用&ensp;户&ensp;名：<input type="text" name="username" class="us" style="height:30px;padding-left:10px"/><br/><br/>
		真实姓名：<input type="text" name="trueusername" class="tus" style="height:30px;padding-left:10px"/><br/><br/>
		原始密码：<input type="password" name="username" class="uso" style="height:30px;padding-left:10px"/><br/>
		<div class="Prompt">原始密码错误，请检查！</div><br/>
		更改密码：<input type="password" name="updp" class="usn" style="height:30px;padding-left:10px"/><br/><br/><br/>
		<input type="button" value="更改" class="btn"/>&ensp;<input type="button" value="取消" class="close"/>
	</form>
</div>
<style>
	.Prompt{font-size:14px;color:red;visibility:hidden;}
	form{padding:20px;font-size:15px;}
	h2{font-size:20px;text-align:center;padding:10px 0;}
	.Covering{position:absolute;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:999;left:0;top:0;display:none;}
	.update_box{
		width:300px;height:400px;position:absolute;left:50%;margin-left:-150px;top:50%;margin-top:-200px;z-index:999;background:white;display:none;
	}
	.btn,.close{padding:3px 20px;border:none;}
	.btn{background:#4da916;color:white;}
</style>
<script>
  (function($){
	  var systems = parent.SYSTEM;
	  

    var totalUser, usedTotal, leftTotal;
    initGrid();
	$(".close").click(function(){
		$(".update_box").hide();
		$(".Covering").hide();
	})
	$(".btn").click(function(){
		var h_us=$(".h_us").val();
		var us=$(".us").val();
		var uso=$(".uso").val();
		var usn=$(".usn").val();
		var trname=$(".tus").val();
		$.ajax({
		  url: '../right/upduser?action=upduser&h_us='+h_us+'&username='+us+'&updp='+usn+'&uso='+uso+'&truename='+trname,
		  dataType: 'json',
		  type: 'POST',
		  success: function(data){
			if(data.status === 200){
				$(".update_box").hide();
				$(".Covering").hide();
				 parent.Public.tips({content: '修改成功！'});
				 window.location.reload();
			}else{
				parent.Public.tips({content:'修改失败！', type: 1});
			}
		  }
	  });
		
	})
	$(".uso").blur(function(){
		var usr=$(".us").val();
		var pwd=$(this).val();
		$.ajax({
		  url: '../right/showuser?action=showuser&pwd='+pwd+'&user='+usr,
		  dataType: 'json',
		  type: 'POST',
		  success: function(data){
			if(data.status != 200){
				$(".Prompt").css({visibility:"visible"});
			}
		  }
	  });
	})
	$(".uso").focus(function(){
		$(".Prompt").css({visibility:"hidden"});
	})
	$('.grid-wrap').on('click', '.update', function(e){
		$(".uso").val("");
		$(".usn").val("");
		var username=$(this).siblings(".username").text();
		var trueusername=$(this).siblings(".truename").text();
		$(".update_box").show();
		$(".Covering").show();
		$(".us").val(username);
		$(".h_us").val(username);
		$(".tus").val(trueusername);
	});
    $('.grid-wrap').on('click', '.delete', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
      $.ajax({
        url: '../right/auth2UserCancel?action=auth2UserCancel&userName=' + userName,
        type: 'POST',
        dataType: 'json',
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content: '取消用户授权成功！'});
            usedTotal--;
            leftTotal++;
            showShareCount();
            if (rowData.isCom) {
                rowData.share = false;
                $("#grid").jqGrid('setRowData', id, rowData);
            } else {
                $("#grid").jqGrid('delRowData',id);
            }
           
          } else {
            parent.Public.tips({type: 1, content: '取消用户授权失败！' + data.msg});
          }
        },
        error: function(){
           parent.Public.tips({content:'取消用户授权失败！请重试。', type: 1});
        }
      });
    });
	
	$('.grid-wrap').on('click', '.deletes', function(e){
		if(confirm("确认删除？")){
			
			var id = $(this).parents('tr').attr('id');
      $.ajax({
		  url: '../right/deluser?action=deluser&id='+id,
		  dataType: 'json',
		  type: 'POST',
		  success: function(data){
			if(data.status == 200){
				parent.Public.tips({content: '删除用户成功！'});
				window.location.reload();
			}
		  }
	  });
		}
      
    });
	

    $('.grid-wrap').on('click', '.authorize', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
       $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../right/auth2User?action=auth2User&userName=' + userName,
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content : '授权成功！'});
            rowData.share = true;
            $("#grid").jqGrid('setRowData', id, rowData);
            usedTotal++;
            leftTotal--;
            showShareCount();
            //window.location.href = 'authority-setting.jsp?userName=' + userName + '&right=0';
          } else {
            parent.Public.tips({type:1, content : data.msg});
          }
        },
        error: function(){
          parent.Public.tips({type:1, content : '用户授权失败！请重试。'});
        }
      });
    });

   
    function initGrid(){
		if(systems.userName=="admin"){
			 $('#grid').jqGrid({
        url: '../right/queryAllUser?action=queryAllUser',
        datatype: 'json',
        height: Public.setGrid().h,
        colNames:['用户', '真实姓名', '公司','功能授权',/*'数据授权',*/'启用授权','修改密码','删除'],
        colModel:[
          {name:'userName',index:'userName',classes: "username", width:200},
          {name:'realName', index:'realName',classes:"truename", width:200},
          {name:'isCom', index:'isCom', hidden: true},
          {name:'setting', index:'setting', width:100, align:"center", title:false, formatter: settingFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter, hidden:(parent.SYSTEM.siType == 1)},
		  {name:'share', index:'share', width:100, align:"center", title:false, formatter: shareFormatter},
		  {name:'update', index:'update', width:100, classes: "update",align:"center", title:false,formatter: function() { return "修改"}},
		  {name:'deletes', index:'deletes', width:100, classes: "deletes",align:"center", title:false,formatter: function() { return "删除"}}

        ],
        altRows:true,
        gridview: true,
        page: 1,
        scroll: 1,
        autowidth: true,
        cmTemplate: {sortable:false}, 
        rowNum:150,
        shrinkToFit:false,
        forceFit:false,
        pager: '#page',
        viewrecords: true,
        jsonReader: {
          root: 'data.items', 
          records: 'data.totalsize',  
          repeatitems : false,
          id: 'userId'
        },
        loadComplete: function(data){
          if (data.status == 200) {
            data = data.data;
            totalUser = data.totalUserNum;
            usedTotal = data.shareTotal;
            leftTotal = totalUser - usedTotal;
            showShareCount();
            $('#shareInfo').show();
          } else {
        	  parent.Public.tips({type: 1, content: data.msg});
          }
          
        },
        loadonce: true
      });
		}else{
			 $('#grid').jqGrid({
        url: '../right/queryAllUser?action=queryAllUser',
        datatype: 'json',
        height: Public.setGrid().h,
        colNames:['用户', '真实姓名', '公司','功能授权','数据授权','启用授权'],
        colModel:[
          {name:'userName',index:'userName',classes: "username", width:200},
          {name:'realName', index:'realName', width:200},
          {name:'isCom', index:'isCom', hidden: true},
          {name:'setting', index:'setting', width:100, align:"center", title:false, formatter: settingFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  {name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter, hidden:(parent.SYSTEM.siType == 1)},
		  {name:'share', index:'share', width:100, align:"center", title:false, formatter: shareFormatter}

        ],
        altRows:true,
        gridview: true,
        page: 1,
        scroll: 1,
        autowidth: true,
        cmTemplate: {sortable:false}, 
        rowNum:150,
        shrinkToFit:false,
        forceFit:false,
        pager: '#page',
        viewrecords: true,
        jsonReader: {
          root: 'data.items', 
          records: 'data.totalsize',  
          repeatitems : false,
          id: 'userId'
        },
        loadComplete: function(data){
          if (data.status == 200) {
            data = data.data;
            totalUser = data.totalUserNum;
            usedTotal = data.shareTotal;
            leftTotal = totalUser - usedTotal;
            showShareCount();
            $('#shareInfo').show();
          } else {
        	  parent.Public.tips({type: 1, content: data.msg});
          }
          
        },
        loadonce: true
      });
		}
     
    }


    function showShareCount(){
        $('#totalUser').text(totalUser);
        $('#usedTotal').text(usedTotal);
        $('#leftTotal').text(leftTotal);
    }
	
	
	function shareFormatter(val, opt, row) {
        if (val || row.admin) {
          if (row.admin) {
              return '管理员';
          } else {
               return '<div class="operating" data-id="' + row.userId + '"><span class="delete ui-label ui-label-success">已启用</span></div>';
          }
        } else {
          return '<p class="operate-wrap"><span class="authorize ui-label ui-label-default">已停用</span></p>';
        } 
    };
    function settingFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
			return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="../settings/authority_setting?userName=' + row.userName + '"></a></div>';
		}
    };
    function settingDataFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
			return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="../settings/authority_setting_data?userName=' + row.userName + '"></a></div>';
		}
    };
	
  })(jQuery)
  
  $(window).resize(function(){
	  Public.resizeGrid();
  });
</script>
</body>
</html>


 