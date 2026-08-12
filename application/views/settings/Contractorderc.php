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
.container{position:relative; padding-bottom:56px; min-height:120px;}
form{margin:20px;}
.file {
    position: relative;
    display: inline-block;
    background: #D0EEFF;
    border: 1px solid #99D3F5;
    border-radius: 4px;
    padding: 4px 12px;
    overflow: hidden;
    color: #1E88C7;
    text-decoration: none;
    text-indent: 0;
    line-height: 20px;
	margin-left:20px;
}
.file input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
}
.file:hover {
    background: #AADFFD;
    border-color: #78C3F3;
    color: #004974;
    text-decoration: none;
}
.btn{
	position:absolute;
	right:10px;
	bottom:10px;
	padding:5px 20px;
	background:#4da916;
	border-radius:5px;
	cursor:pointer;
	color:white;
	border:none;
}
.btn:hover{
	color:white;
	background:#2e9ddd;
}
.loadimg{
	position:absolute;
	left:50%;
	top:50%;
	margin-left:-14px;
	margin-top:25px;
	display:none;
}
</style>
</head>
<body>
<div class="container">
	<p style="margin-bottom:12px;">请先<a href="<?php echo base_url(); ?>data/download/<?php echo urlencode('项目库导入模板.xls'); ?>" target="_blank">下载项目库导入模板</a>，按模板格式填写后再上传。</p>
	<form id="form" enctype="multipart/form-data">
		<input type="file" name="file" id="file" class="file" accept=".xls,.xlsx"/><br/><br/>
		<input type="button" value="提交" class="btn">
	</form>
	<img src="<?php echo base_url()?>statics\saas\scm\app2_release\css\green\img\loading.gif" alt="加载中。。。" class="loadimg"/>
	<div id="resultMsg" class="result-msg" style="display:none; margin-top:12px; padding:10px; background:#f5f5f5; border-radius:4px; max-height:180px; overflow-y:auto;"></div>
</div>
<style>.result-msg p{margin:6px 0;}</style>
<script>
	$(function(){
		$(".btn").click(function(){
			var $file = $("#file");
			if(!$file.val()){
				alert("请先选择要上传的 Excel 文件！");
				return;
			}
			var $btn = $(this);
			$btn.prop("disabled", true).css("background","#cdcdcd");
			$(".loadimg").show();
			$("#resultMsg").hide().empty();

			var formData = new FormData($("#form")[0]);
			$.ajax({
				url: "../scm/invSa/uploadexcelc?action=uploadexcelc",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function(res){
					$(".loadimg").hide();
					$btn.prop("disabled", false).css("background","#4da916");
					var html = [];
					if(res.status === 200){
						if(res.stoppedByDuplicate){
							html.push("<p><strong style='color:#c00'>导入已停止（存在重复）</strong></p>");
							html.push("<p>已成功导入：" + (res.successCount || 0) + " 条</p>");
							html.push("<p style='color:#c00'>第 " + (res.duplicateRow || 0) + " 行存在重复（项目编号或项目名称已在系统中存在），请修改 Excel 中该行或删除重复项后重新导入。</p>");
							if(res.duplicateNumbers && res.duplicateNumbers.length > 0){
								html.push("<p>重复项：" + res.duplicateNumbers.join("、") + "</p>");
							}
						} else {
							html.push("<p><strong>导入完成</strong></p>");
							html.push("<p>成功导入：" + (res.successCount || 0) + " 条</p>");
						}
						if(res.errors && res.errors.length > 0){
							html.push("<p style='color:#c00'>问题提示：</p><ul>");
							$(res.errors).each(function(i, e){ html.push("<li>" + e + "</li>"); });
							html.push("</ul>");
						}
					} else {
						html.push("<p style='color:#c00'>" + (res.msg || "导入失败") + "</p>");
					}
					$("#resultMsg").html(html.join("")).show();
				},
				error: function(xhr){
					$(".loadimg").hide();
					$btn.prop("disabled", false).css("background","#4da916");
					var msg = "上传失败，请检查网络或文件格式。";
					try{ var r = JSON.parse(xhr.responseText); if(r.msg) msg = r.msg; }catch(e){}
					$("#resultMsg").html("<p style='color:#c00'>" + msg + "</p>").show();
				}
			});
		});
	});
</script>
</body>
</html>