<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="20"><H3>出库单<H3></td></tr>
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
					<th width="100" align="center" colspan="3">备注</th>
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
					<td colspan="3"><?php echo $row['description']?></td>
				</tr>
				  <?php }?>
				
				
				 
				
 </tbody>
</table>	

 