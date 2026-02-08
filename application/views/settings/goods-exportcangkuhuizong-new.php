<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="2100"  border="1">
			<thead>
			    <tr>
				    <th colspan="12" align="center"><h3>仓储出库汇总</h3></th>
				</tr>

				<tr>
				    <th width="100" >物料编号</th>
					<th width="400" >物料描述</th>
					<th width="100" >领用数量</th>
					<th width="100" >单位</th>
					<th width="200" >采购订单号</th>
					<th width="100" >单价</th>
					<th width="100" >出库金额</th>
					<th width="100" >领料人</th>
					<th width="100" >出库时间</th>
					<th width="200" >备注</th>
					<th width="200" >领用时间</th>
					<th width="200" >领用状态</th>
				</tr>
			</thead>
			<tbody>
			  <?php
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td  align="center"><?php echo $row['goodsnumber']?></td>
					<td  align="center"><?php echo $row['mdescription']?></td>
					<td  align="center"><?php echo $row['inventoryNew']?></td>
					<td  align="center"><?php echo $row['mainUnit']?></td>
					<td  align="center"><?php echo $row['ordernumber']?></td>
					<td  align="center"><?php echo $row['price']?></td>
					<td  align="center"><?php echo $row['amount']?></td>
					<td  align="center"><?php echo $row['sign']?></td>
					<td  align="center"><?php echo $row['flagtime']?></td>
					<td  align="center"><?php echo $row['beizhu']?></td>
					<td  align="center"><?php echo $row['receive_time'] ?></td>
					<td  align="center"><?php echo $row['receive_status'] == 1 ? '已领用' : '未领用' ?></td>
				</tr>
				<?php }?>
			</tbody>
			<?php
				if($fg=="true"){
					?>
					<tr>
				    <td  align="center">合计：</td>
					<td  align="center"></td>
					<td  align="center"><?php echo $newnumber;?></td>
					<td  align="center"><?php echo $oldnumber?></td>
					<td  align="center"><?php echo $number?></td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"></td>
					<td  align="center"></td>
				</tr>
				<?php }?>
</table>
