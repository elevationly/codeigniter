<?php $this->load->view('header');?>

<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
var SITE_URL = "<?php echo rtrim(site_url(), '/'); ?>";
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
    .disable{background-color: #9997a9}
    .design{background-color: #93a3a9}
    .apply{background-color: #a89ca9}
    .check{background-color: #779fff
    }
.matchCon{width:280px;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	      	<li>
	        	<span id="catorage"></span>
	        </li>
              <li>
                  <select name="disable" id="disable" style="width: 90px;height: 31px;">
                      <option value="">全部</option>
                      <option value="1">竣工禁用</option>
                      <option value="-1">项目启用</option>
                  </select>
              </li>
              <li>
                  <select name="design" id="design" style="width: 90px;height: 31px;">
                      <option value="">全部</option>
                      <option value="1">已设计</option>
                      <option value="-1">未设计</option>
                  </select>
              </li>
              <li>
                  <select name="apply" id="apply" style="width: 90px;height: 31px;">
                      <option value="">全部</option>
                      <option value="1">已申请</option>
                      <option value="-1">未申请</option>
                  </select>
              </li>
	        <li>
	          <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="输入项目编号/ 名称">
	        </li>
              <li>
                  <input type="text" id="remark_" class="ui-input ui-input-ph remark_" value="输入备注内容">
              </li>
	        <li><a class="ui-btn mrb" id="search">查询</a><a class="ui-btn mrb" id="shuaxin">刷新</a></li>
	      </ul>
	    </div>
	    <div class="fr">
            <a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a>
            <a class="ui-btn" id="daoru">导入</a>
            <a class="ui-btn mrb disable" id="btn-disable">禁用</a><a class="ui-btn mrb disable" id="btn-enable">启用</a>
            <a class="ui-btn mrb design" id="btn-is-design">已设计</a><a class="ui-btn mrb design" id="btn-no-design">未设计</a>
            <a class="ui-btn mrb apply" id="btn-is-apply">已申请</a><a class="ui-btn mrb apply" id="btn-no-apply">未申请</a>
            <a class="ui-btn mrb check" id="btn-is-check">已核对</a>
            <!--<a href="#" class="ui-btn mrb" id="btn-print">打印</a>-->
            <!--<a href="#" class="ui-btn mrb" id="btn-import">导入</a>
            <a href="#" class="ui-btn mrb" id="btn-export">导出</a>-->
            <a href="#" class="ui-btn mrb" id="btn-export">导出</a>
            <a href="#" class="ui-btn" id="btn-batchDel">删除</a>
        </div>
	  </div>
    <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/customerList.js?ver=20140431"></script>
<script>
    $("#shuaxin").click(function(){
        location.reload();
    })
</script>
</body>
</html>


