<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1400"  border="1">
			<thead>
			    <tr>
				    <th colspan="4" align="center"><h3>提货单物资库</h3></th>
				</tr>
				
				<tr>
				    <th width="300" >物料编号</th>
					<th width="500" >物料描述</th>
                    <th width="500" >扩展描述</th>
                    <th width="300" >补充描述</th>
					<th width="100" >单位</th>
					<th width="100" >单重(KG)</th>
                    <th width="300" >钢印号</th>
                    <th width="300" >备注</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td  align="center"><?php echo $row['number']?></td>
					<td  align="center"><?php echo $row['name']?></td>
                    <td  align="center"><?php echo $row['spec']?></td>
                    <td  align="center"><?php echo $row['jianxing']?></td>
					<td  align="center"><?php echo $row['unitName']?></td>
                    <td  align="center"><?php echo $row['unitCost']?></td>
					<td  align="center"><?php echo $row['goods']?></td>
                    <td  align="center"><?php echo $row['remark']?></td>
				</tr>
				<?php }?>
			</tbody>
</table>	
