<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>永德信提货单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.goodsnumber{width:80px}
goodsnumber{width:80px}
</style>
</head>
<body>
<?php for($t=1; $t<=$countpage; $t++){?>
		<table style="width:800px;page-break-before: always;" align="center">
		     
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:50px;"></td>
			</tr> 
		    <tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;"><?php echo $system['companyName']?></td>
			</tr> 
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:26px; font-weight:bold;height:26px;">永德信提货单</td>
			</tr>
		</table>	
		
		
		<table style="width:800px" align="center">
			<tr height="15" align="left" >
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:45%;text-align:left;padding-left:3px;" >项目名称：<?=$contactNo.' '.mb_str_split($contactName, 15)?> </td>
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:25%">单据日期：<?php echo $billDate?></td>
				<td style="font-family:'宋体'; font-size:14px;height:20px;word-break:break-all; width:20%; text-align:right;">单据编号：<?=$billNo?></td>
				<!--<td width="60" >币别：RMB</td>-->
 
			</tr>
		</table>
		<table align="center" style="width:800px">
		  <tr height="25" align="center">
              <td  style="font-family:'宋体'; font-size:14px;height:50px;width:200px;text-align:left;padding-left:20px;">领料人：<?php echo $liname?> </td>

              <td width="150" style="font-family:'宋体'; font-size:12px;height:30px;width:200px;">
                  <img width="200px" height="30px" src="<?=base_url().'statics/barcode.png'?>" alt="" />
              </td>
              <td style="font-family:'宋体'; font-size:14px;height:50px;width:200px" ></td>
              <td style="font-family:'宋体'; font-size:14px;height:50px;width:200px" ></td>
		  </tr>
		</table>	
 
			
		<table border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;width:800px; word-break: break-all">
		         <!--序号、物料编号、物料描述、数量、单位、钢印号 -->
				<tr style="height:20px;">
				    <td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:40px;text-align: center;"  align="center">序号</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:80px;text-align: center;" align="center">物料编号</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:200px;text-align: center;" align="center">物料描述</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:40px;text-align: center;" align="center">数量</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:40px;text-align: center;" align="center">单位</td>
					<td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:60px;text-align: center;" align="center">钢印号</td>
                    <td style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;width:160px; text-align: center;" align="center">备注</td>
				</tr>
				
		       <?php 
			   $i = ($t-1)*$num + 1;
			   foreach($list as $arr => $row) {
			       if ($row['i'] >= (($t-1)*$num + 1) && $row['i'] <= $t*$num) {
			   ?>
				<tr style="height:20px;width:800px;">
					<td style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:14px;width:40px;text-align:center;"><?php echo $row['i']?></td>
					<td align="center" style="width:80px; font-size:14px;" id="goodsnumber"><?php echo $row['goods'];?></td>
					<td style="word-break:break-all;width:240px; font-size:14px;word-wrap:break-word" align="left"><?=mb_str_split($row['mdescription'], 21);?></td>
					<td  style="word-break:break-all;width:40px; font-size:14px; word-wrap:break-word;text-align:center;"><?php echo $row['qty']?></td>
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:14px;width:40px;text-align:center;"><?php echo $row['mainUnit']?></td>
					<td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:14px;width:60px;" align="center";><?php echo $row['BillName']?></td>
                    <td  style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:14px;width:160px;" align="left";><?=mb_str_split($row['description'], 10)?></td>
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
				    
					<td style="border:solid #000000;border-width:0 1px 1px 0;font-family:'宋体'; font-size:12px;width:40px;text-align:center;"><?php echo $m?></td>
					<td style="width:80px"></td>
					<td style="width:200px" align="left"></td>
					<td style="width:40px" align="center"></td>
					<td style="width:40px" align="center"></td>
					<td style="width:60px" align="center"></td>
                    <td style="width:160px" align="left"></td>
				</tr>
				<?php }}?>
		</table>

		<table align="center" style="width:800px">
			<tr height="30" align="left">
				<td align="left"  style="font-family:'宋体'; font-size:14px;height:40px;width:45%;text-align:left;padding-left:20px;">领料人：</td>
				<td  style="font-family:'宋体'; font-size:14px;height:40px;;width:25%;">审核人：</td>
				
				<td  style="font-family:'宋体'; font-size:14px;height:30px;;width:30%" >手写签名：<?php echo !empty($sign) ? '<img class="ui-input ui-input-dis"  id="handwritingimg" style="height:30px"  src="data:image/gif;base64,'.$sign.'"/>' : '';?></td>
			</tr>
		</table>
<?php echo $t==$countpage?'':'<br><br>';}?>
</body>
</html>		