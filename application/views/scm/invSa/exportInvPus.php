<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="20"><H3>领料单<H3></td></tr>
  		</table>
		<table width="1500px" class="list"  border="1">
			<thead>
				<tr>
				    
					<th width="150" align="center">单据日期</th>
				    <th width="200" align="center" colspan="2">单据编号</th>
					<th width="150" align="center" colspan="2">项目名</th>
					<th width="100" align="center" colspan="2">领料人</th>
					<th width="100" align="center" colspan="2">制单人</th>
					<th width="150" align="center" colspan="2">审核人</th>				    
					<th width="150" align="center" colspan="2">仓库</th>
					<th width="100" align="center" colspan="3">备注</th>
					<th width="100" align="center" colspan="3">超期时间提示</th>
					<th width="100" align="center" colspan="3">出库状态</th>
				</tr>
			</thead>
			<tbody>
			    <?php 
				  foreach($list as $arr=>$row) {
				?>
				<tr>
					<td ><?php echo $row['billDate']?></td>
					<td colspan="2"><?php echo $row['billNo']?></td>
					<td colspan="2"><?php echo $row['contactName']?></td>
					<td colspan="2"><?php echo $row['liname']?></td>
					<td colspan="2"><?php echo $row['userName']?></td>
					<td colspan="2"><?php echo $row['checkName']?></td>
					<td colspan="2"><?php echo $row['locationNames']?></td>
					<td colspan="3"><?php echo $row['description']?></td>
					<td colspan="3"><?php echo $row['is_chaoqi']?></td>
					<td colspan="3"><?php echo $row['chuku_status']==1 ? '已出库' : '未出库'?></td>
				</tr>
				  <?php }?>
				
				
				 
				
 </tbody>
</table>	

 