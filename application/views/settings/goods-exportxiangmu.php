<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1900"  border="1">
			<thead>
			    <tr>
				    <th colspan="10" align="center"><h3>项目库</h3></th>
				</tr>
				
				<tr>
				    <th width="400" >项目名称</th>
					<th width="300" >项目定义号</th>
					<th width="100" >物料编号</th>
					<th width="400" >物料描述</th>
					<th width="100" >出库数量</th>
					<th width="100" >单位</th>
					<th width="100" >单价</th>
					<th width="100" >出库金额</th>
					<th width="100" >施工队伍</th>
					<th width="200" >备注</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td  align="center"><?php echo $row['name']?></td>
					<td  align="center"><?php echo $row['ordernumber']?></td>
					<td  align="center"><?php echo $row['number']?></td>
					<td  align="center"><?php echo $row['mdescription']?></td>
					<td  align="center"><?php echo $row['num']?></td>
					<td  align="center"><?php echo $row['mainUnit']?></td>
					<td  align="center"><?php echo $row['price']?></td>
					<td  align="center"><?php echo $row['amount']?></td>
					<td  align="center"><?php echo $row['duiwu']?></td>
					<td  align="center"><?php echo $row['beizhu']?></td>
					<td  align="center"><?php echo $row['Arrivaltime']?></td>
				</tr>
				<?php }?>
			</tbody>
			<?php
				if($fg=="true"){
					?>
					<tr>
				    <td  align="center">合计：</td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"><?php echo $oldnumber?></td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"><?php echo $number?></td>
					<td  align="center"></td>
					<td  align="center"></td>
				</tr>
				<?php }?>
</table>	
