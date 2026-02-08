<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $transType==150501 ? '物资出库单' :'购货退货单'?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.goodsnumber{width:80px}
goodsnumber{width:80px}
</style>
</head>
<body>
<?php for($t=1; $t<=$countpage; $t++){?>
		<table  width="800"  align="center">
		     
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:50px;"></td>
			</tr> 
		    <tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;"><?php echo $system['companyName']?></td>
			</tr> 
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;"><?php echo $transType==150501 ? '物资出库单' :'购货退货单'?></td>
			</tr>
		</table>	
		
		
		<table style="width:800px;" align="center">
			<tr height="15" align="left" >
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:50%;text-align:left;padding-left:20px;" >项目名称：<?php echo $contactNo.' '.$contactName?> </td>
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:20%">单据日期：<?php echo $billDate?></td>
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:20%">单据编号：<?php echo $billNo?></td>
				<!--<td width="60" >币别：RMB</td>-->
 
			</tr>
		</table>
		<table    align="center" style="width:800px">
		  <tr height="25" align="center">
			 
				<td  style="font-family:'宋体'; font-size:14px;height:50px;width:100%;text-align:left;padding-left:40px;">领料人：<?php echo $liname?> </td>
 
		  </tr>
		</table>	
 
			
		<table   border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;width:800px; word-break: break-all">
		         
				<tr style="height:20px;width:800px;">
				    <td  style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:5%"  align="center">序号</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:8%" align="center">物料编号</td> 
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:30%" align="center">物料描述</td> 
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:5%" align="center">数量</td>
					<td  style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:5%" align="center">单位</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:5%" align="center">物资出库单价</td>							
					<td  style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:10%" align="center">单据类型</td>	
					<td  style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:15%;text-align:center;">备注</td>	
		
					<!--<td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">仓库</td>-->
				</tr>
				
		       <?php 
			   $i = ($t-1)*$num + 1;
			   foreach($list as $arr=>$row) {
			       if ($row['i']>=(($t-1)*$num + 1) && $row['i'] <=$t*$num) {
			   ?>
				<tr style="height:20px;width:800px;">
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:5%;text-align:center;"><?php echo $row['i']?></td>
					<td  align="center" style="width:8%" id="goodsnumber"><?php echo $row['goods'];?></td>
					<td  style="word-break:break-all;width:30%;word-wrap:break-word" ><?php echo $row['mdescription'];?></td>
					<td  style="word-break:break-all;width:5%;word-wrap:break-word;text-align:center;"><?php echo $row['qty']?></td>
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:5%;text-align:center;"><?php echo $row['mainUnit']?></td>
					
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:5%;text-align:center;"><?php echo $row['price']?></td>
					
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:10%" align="right"><?php echo $row['BillName']?></td>
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:32%" align="right"><?php echo $row['description']?></td>
					
				</tr>
				<?php 
				    $s = $row['i'];
				    }
				    $i++;
				}
				?>
				
	            <?php 
				//补全
				if ($t==$countpage) {
				    for ($m=$s+1;$m<=$t*$num;$m++) {
				?>
				<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;width:800px;">
				    
					<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:5%;text-align:center;"><?php echo $m?></td>
					<td width="220" style="width:8%"></td>
					<td width="30" style="width:30%" align="center"></td>
					<td width="40" style="width:5%" align="center"></td>
					<td width="60" style="width:5%" align="center"></td>
					<td width="60" style="width:5%" align="center"></td>
					<td width="60" style="width:10%" align="center"></td>
					<td width="80" style="width:32%" align="center"></td>
				</tr>
				<?php }}?>
				
				 <?php if ($t==$countpage) {?>
				  
				 
				<tr target="id">
				    <td colspan="9" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;">合计 金额大写： <?php echo str_num2rmb(abs($amount))?> </td> 
				</tr>
				<?php }?>
		</table>
		
 
			 
		
		<table style="width:800px">
			<tr height="30" align="left">
				<td align="left"  style="font-family:'宋体'; font-size:14px;height:40px;width:300px;text-align:left;padding-left:40px;">制单人：<?php echo $userName?> </td>
				<td  style="font-family:'宋体'; font-size:14px;height:40px;;width:200px; text-align:center">审核人：</td>
				
				<td  style="font-family:'宋体'; font-size:14px;height:30px;;width:300px" >手写签名：<?php echo !empty($sign) ? '<img class="ui-input ui-input-dis"  id="handwritingimg" style="height:30px"  src="data:image/gif;base64,'.$sign.'"/>' : '';?> </td>
		 
 
			</tr>
		</table>	
<?php echo $t==$countpage?'':'<br><br>';}?>		
		
		
		 
</body>
</html>		