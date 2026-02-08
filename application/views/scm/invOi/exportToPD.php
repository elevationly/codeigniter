<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1500"  border="1">
		        <tr>
				    <td colspan="8" align="center"><H3>仓库汇总</H3></td>
				</tr>
				<tr>
				    <th width="150" align="center">物料编号</th>
					<th width="280" >物料描述</th>
					<th width="100" align="center">库存数量</th>
					<th width="100" align="center">单位</th>
					<th width="150" align="center">采购订单号</th>	
					<th width="100" align="center">单价</th>	
					<th width="120" align="center">出库金额</th>
					<th width="150" align="center">仓库</th>	
				</tr>
			  <?php 
			  $i = 1;
			  foreach($list as $arr=>$row) { 
			  ?>
				<tr target="id">
					<td ><?php echo $row['goodsnumber'];?></td>
					<td ><?php echo $row['mdescription']?></td>
					<td ><?php echo $row['inventoryNew']?></td>
					<td ><?php echo $row['mainUnit']?></td>
					<td ><?php echo $row['ordernumber']?></td>
					<td ><?php echo $row['price']?></td>
					<td ><?php echo $row['amount']?></td>
					<td ><?php echo $row['locationName']?></td>
				</tr>
				<?php $i++;}?>
</table>
