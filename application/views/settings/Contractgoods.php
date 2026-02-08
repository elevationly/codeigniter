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
.container{position:relative;}
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
	bottom:-55px;
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
	
	<form action="../scm/invPu/uploadexcelgoods?action=uploadexcelgoods" method="post" id="form" enctype="multipart/form-data">
		<input type="file" name="file" id="file" class="file"/><br/><br/>
		<input type="button" value="提交" class="btn">
	</form>
	<img src="<?php echo base_url()?>statics\saas\scm\app2_release\css\green\img\loading.gif" alt="加载中。。。" class="loadimg"/>
</div>
<script>
	$(function(){
		$(".btn").click(function(){
			$(this).prop({disabled:"disabled"});
			$(this).css({
				background:"#cdcdcd"
			})
			$(".loadimg").css({
				display:"block"
			})
			$("#form").submit(); 
			$("#form").css({
				display:"none"
			})			 
		})
	})
</script>
</body>
</html>