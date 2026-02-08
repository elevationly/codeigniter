<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1400"  border="1">
			<thead>
			    <tr>
				    <th colspan="4" align="center"><h3>出库单物资库</h3></th>
				</tr>
				
				<tr>
				    <th width="300" >物料编号</th>
					<th width="500" >物料描述</th>
					<th width="300" >单位</th>
					<th width="300" >单价</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td  align="center"><?php echo $row['number']?></td>
					<td  align="center"><?php echo $row['name']?></td>
					<td  align="center"><?php echo $row['unitName']?></td>
					<td  align="center"><?php echo $row['purPrice']?></td>
				</tr>
				<?php }?>
			</tbody>
</table>	
