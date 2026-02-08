<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $transType==150601 ? '销货单' :'销货退货单'?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style></style>
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
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;"><?php echo $transType==150601 ? '销货单' :'领料单'?></td>
			</tr>
            <?php if($chuku_status):?>
            <tr height="15px">
                <td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;"><?=$chuku_status?></td>
            </tr>
            <?php endif;?>
		</table>	
		
		
		<table width="800" align="center">
			<tr height="15" align="center">
				<td width="220" style="font-family:'宋体'; font-size:14px;height:20px;width:200px;text-align:left;padding-left:10px;">客户：<?php echo $contactNo.' '.$contactName?> </td>
				<td style="font-family:'宋体'; font-size:14px;height:20px;width:30px;"></td>
				<td width="120" style="font-family:'宋体'; font-size:14px;height:20px;width:200px;">单据日期：<?php echo $billDate?></td>
				<td width="180" style="font-family:'宋体'; font-size:14px;height:20px;width:200px;">单据编号：<?php echo $billNo?></td>
				
				<!--<td width="50" style="font-family:'宋体'; font-size:12px;height:20px;">币别：RMB</td>-->
 
			</tr>
		</table>	
		<table    align="center" style="width:800px">
		  <tr height="25" align="center">
			 
				<td  style="font-family:'宋体'; font-size:14px;height:50px;width:200px;text-align:left;padding-left:50px;">领料人：<?php echo $liname?> </td>
              <td width="150" style="font-family:'宋体'; font-size:12px;height:30px;width:200px;">
                  <img src="<?=base_url().'statics/barcode.png'?>" alt="">
              </td>
              <td style="font-family:'宋体'; font-size:14px;height:50px;width:200px" ></td>
              <td style="font-family:'宋体'; font-size:14px;height:50px;width:200px" ></td>
 
		  </tr>
		</table>	
			
		<table width="900" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;">   
			<tr style="height:20px">
				    <td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">序号</td>
					<td width="220" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">采购订单号</td> 
					<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">物料编号</td>
					<td width="40" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">物料描述</td>
					<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">数量</td>	
					<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">单位</td>	
					<td width="50" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">仓库</td>	
					<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">物资出库单价</td>	
					<td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">出库金额</td>
					<td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">单据类型</td>					
				</tr>
		       <?php 
			   $i = ($t-1)*$num + 1;
			   foreach($list as $arr=>$row) {
			       if ($row['i']>=(($t-1)*$num + 1) && $row['i'] <=$t*$num) {
			   ?>
				<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">
				   <td width="30"  align="center"><?php echo $row['i']?></td>
					<td width="220" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $row['ordernumber'];?></td>
					<td width="30" align="center" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $row['goodsnumber']?></td>
					<td width="40" align="center"><?php
				 
					$strdesc=$row['mdescription'];
				 
					$slen=mb_strlen($strdesc,'utf-8');
		 
					if($slen>14)
					{
						
						echo mb_substr($strdesc,0,14,"utf-8")."<br/>";
						$strdesc2=mb_substr($strdesc,14, $slen,"utf-8");
						$slen2=mb_strlen($strdesc2,'utf-8');
						if($slen2>14)
						{
						  echo mb_substr($strdesc2,0,14,"utf-8")."<br/>";
						  $strdesc3=mb_substr($strdesc2,14, $slen2,"utf-8");
						  
						  $slen4==mb_strlen($strdesc3,'utf-8');
					  
						  if($slen4>14)
						  {
					 
							   echo mb_substr($strdesc3,0,14,"utf-8")."<br/>";
							   echo mb_substr($strdesc3,14,$slen4,"utf-8")."<br/>";
						  }
						  else 
						  {
							    echo $strdesc3;
						  }
						
						  
						  
						}
						else 
						{
							echo $strdesc2;
						}
               						
						
					}
					else 
					{
						echo $strdesc;
					}
					
					
					?><br/></td>
					<td width="60" align="center"><?php echo $row['qty']?></td>
					<td width="60" align="center"><?php echo $row['mainUnit']?></td>
					<td width="50" align="center"><?php echo $row['locationName']?></td>
					<td width="60" align="center"><?php echo $row['price']?></td>
					<td width="80" align="center"><?php echo $row['amount']?></td>
					<td width="80" align="center"><?php echo $row['BillName']?></td>
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
				<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">
				   <td width="30" align="center" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $m?></td>
					<td width="220"></td>
					<td width="30"></td>
					<td width="40"></td>
					<td width="60"></td>
					<td width="60"></td>
					<td width="50"></td>
					<td width="60"></td>
					<td width="80"></td>
					<td width="80"></td>
				</tr>
				<?php }}?>
				
				 <?php if ($t==$countpage) {?>
				  
				 
				<tr target="id">
				    <td colspan="10" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:12px;height:15px;">合计 金额大写：<?php echo str_num2rmb(abs($totalAmount))?> </td> 
				</tr>
				<?php }?>
		</table>
		
		
		
		
		<!--<table  width="800" align="center">
		  <tr height="15" align="left">
				<td align="left" width="200" style="font-family:'宋体'; font-size:12px;height:25px;">折扣额：<?php echo str_money(abs($disAmount),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">折扣后金额：<?php echo str_money(abs($amount),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;"><?php echo $transType==150601 ? '本次收款：' :'本次退款：'?> <?php echo str_money(abs($rpAmount),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">本次欠款：<?php echo str_money(abs($arrears),2)?></td>
				<td width="50" ></td>
 
		  </tr>
		</table>	
		
		<table  width="800" align="center">
		  <tr height="15" align="left">
				<td align="left" width="700" style="font-family:'宋体'; font-size:12px;height:25px;">备注： <?php echo $description?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
 
		  </tr>
		</table>	 -->
		
		<table  width="800" align="center">
			<tr height="15" align="left">
<!--				<td align="left" width="200" style="font-family:'宋体'; font-size:12px;height:30px;width:200px;">制单人：--><?php //echo $userName?><!-- </td>-->
				<td width="150" style="font-family:'宋体'; font-size:12px;height:30px;width:200px;">审核人：</td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:30px;width:200px;">领料人：<?php echo !empty($sign) ? '<img class="ui-input ui-input-dis"  id="handwritingimg" style="height:30px"  src="data:image/gif;base64,'.$sign.'"/>' : '';?></td>
				<td width="150">

                </td>
				<td width="50" ></td>
			</tr>
		</table>	
<?php echo $t==$countpage?'':'<br><br><br>';}?>		
		
		
		 
</body>
</html>		