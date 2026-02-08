<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="13"><H3>提货单汇总<H3></td></tr>
  		</table>
		<table width="1500px" class="list"  border="1">
			<thead>
				<tr>
				    
					<th width="150" align="center">物料编号</th>
				    <th width="200" align="center" colspan="1">物料描述</th>
					<th width="150" align="center" colspan="1">数量</th>
					<th width="50" align="center" colspan="1">单位</th>
					<th width="100" align="center" colspan="1">单价</th>
					<th width="100" align="center" colspan="1">总金额</th>
					<th width="100" align="center" colspan="1">单据类型</th>
					<th width="150" align="center" colspan="1">编号</th>
					<th width="200" align="center" colspan="1">项目名</th>
				    <th width="100" align="center">领料人</th>
					<th width="100" align="center" colspan="3">备注</th>
				</tr>
			</thead>
			<tbody>
			    <?php 
				  foreach($list as $arr=>$row) {
				?>
				<tr>
					<td ><?php echo $row['invNumber']?></td>
					<td colspan="1"><?php echo $row['mdescription']?></td>
					<td colspan="1"><?php echo abs($row['totalQty'])?></td>
					<td colspan="1"><?php echo $row['mainUnit']?></td>
					<td colspan="1"><?php echo $row['price']?></td>
					<td colspan="1"><?php echo $row['amount']?></td>
					<td colspan="1"><?php echo $row['BillName']?></td>
					<td colspan="1"><?php echo $row['billNo']?></td>
					<td colspan="1"><?php echo $row['contactName']?></td>
					<td ><?php echo $row['liname']?></td>
					<td colspan="3"><?php echo $row['description']?></td>
				</tr>
				  <?php }?>
				
				
				 
				
 </tbody>
</table>	

 