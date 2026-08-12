<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="2200"  border="1">
			<thead>
			    <tr>
				    <th colspan="17" align="center"><h3>领料单物资库</h3></th>
				</tr>
				
				<tr>
				    <th width="100" >物料编号</th>
					<th width="400" >物料描述</th>
					<th width="100" >申请数量</th>
					<th width="100" >已领用数量</th>
					<th width="100" >库存数量</th>
					<th width="100" >已到货数量</th>
					<th width="110" >仓储库存数量</th>
					<th width="100" >单位</th>
					<th width="200" >采购订单号</th>
					<th width="100" >单价</th>
					<th width="100" >出库金额</th>
					<th width="100" >仓库</th>
					<th width="100" >申请时间</th>
					<th width="100" >是否到货</th>
					<th width="100" >到货时间</th>
					<th width="200" >供应商</th>
					<th width="200" >项目备注</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td  align="center"><?php echo $row['goodsnumber']?></td>
					<td  align="center"><?php echo $row['mdescription']?></td>
					<td  align="center"><?php echo $row['inventoryOld']?></td>
					<td  align="center"><?php echo $row['number']?></td>
					<td  align="center"><?php echo $row['inventoryNew']?></td>
					<td  align="center"><?php echo $row['daohuo']?></td>
					<td  align="center"><?php echo number_format(isset($row['cangkuInventory']) ? floatval($row['cangkuInventory']) : 0, 3, '.', '')?></td>
					<td  align="center"><?php echo $row['mainUnit']?></td>
					<td  align="center"><?php echo $row['ordernumber']?></td>
					<td  align="center"><?php echo $row['price']?></td>
					<td  align="center"><?php echo $row['amount']?></td>
					<td  align="center"><?php echo $row['locationName']?></td>
					<td  align="center"><?php echo $row['Arrivaltime']?></td>
					<td  align="center"><?php echo $row['flagNo']?></td>
					<td  align="center"><?php echo $row['flagtime']?></td>
					<td  align="center"><?php echo $row['flagcontact']?></td>
					<td  align="center"><?php echo $row['beizhu']?></td>
				</tr>
				<?php }?>
			</tbody>
			<?php
				if(isset($fg) && $fg=="true"){
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
					<td  align="center"></td>
					<td  align="center"></td>
				</tr>
				<?php }?>
</table>	
