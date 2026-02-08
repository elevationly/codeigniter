<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
 <script src="/statics/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<meta charset="utf-8">
    <title>显示姓名</title>
    <style type="text/css">
        .style1
        {
            font-size: xx-large;
            font-family: 宋体, Arial, Helvetica, sans-serif;
            color: #FF3399;
        }
       .style2
        {
            font-size: 16px;
            font-family: 宋体, Arial, Helvetica, sans-serif;
            color: #FF3399;
        }
       .style3
        {
            font-size: 12px;
            font-family: 宋体, Arial, Helvetica, sans-serif;
            color: #000000;
        }
		.style4
        {
            font-size: 12px;
            font-family: 宋体, Arial, Helvetica, sans-serif;
            color: #FF0000;
        }
		.sign_h{width:500px;height:300px;position:absolute;top:50%;left:50%;margin-left:-250px;margin-top:-150px;border:1px solid red;z-index:999;display:none;}
   </style>
   
<script language="javascript" type="text/javascript">
 
</script>

</head>
<body>
 

<img id="showimg" src="" style="width:500px;height:240px;position:absolute;top:50%;left:50%;margin-left:-250px;margin-top:-120px;" />
 <script>
   var imgsrc="";
    imgsrc=window.parent.document.getElementById('sign').value;

	if(imgsrc!="")
	{
	
      $("#showimg").attr("src", "data:image/gif;base64,"+imgsrc); 
	   $("#writeimg").css({display:"none"});
	  
	}
	else 
	{
		 $("#showimg").css({display:"none"});
	}
   
	  
	
</script> 
 
 
 
   
    
    <p id="stream"></p>
    
	 
  
</body>
</html>
       
        
