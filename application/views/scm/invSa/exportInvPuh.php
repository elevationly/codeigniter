<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="20"><H3>项目汇总<H3></td></tr>
  		</table>
		<table width="1500px" class="list"  border="1">
			<thead>
				<tr>
				    
					<th width="150" align="center">物料编号</th>
				    <th width="200" align="center" colspan="2">物料描述</th>
					<th width="150" align="center" colspan="2">数量</th>
					<th width="50" align="center" colspan="2">单位</th>
					<th width="100" align="center" colspan="2">单价</th>
					<th width="100" align="center" colspan="2">总金额</th>					
					<th width="150" align="center" colspan="2">单据类型</th>
					<th width="100" align="center" colspan="2">编号</th>
					<th width="150" align="center" colspan="2">项目名</th>
				    <th width="100" align="center" colspan="2">领料人</th>
					<th width="150" align="center" colspan="2">是否归档</th>
				</tr>
			</thead>
			<tbody>
			    <?php 
				  foreach($list as $arr=>$row) {
				?>
				<tr>
					<td ><?php echo $row['goodsnumber']?></td>
					<td colspan="2"><?php echo $row['mdescription']?></td>
					<td colspan="2"><?php echo $row['totalQty']?></td>
					<td colspan="2"><?php echo $row['mainUnit']?></td>
					<td colspan="2"><?php echo $row['price']?></td>
					<td colspan="2"><?php echo $row['amount']?></td>					
					<td colspan="2"><?php echo $row['BillName']?></td>
					<td colspan="2"><?php echo $row['billNo']?></td>
					<td colspan="2"><?php echo $row['contactName']?></td>
					<td colspan="2"><?php echo $row['liname']?></td>
					<td colspan="2"><?php echo $row['flag']?></td>
				</tr>
				  <?php }?>
				
				
				 
				
 </tbody>
</table>	

 