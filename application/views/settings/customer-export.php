<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1500"  border="1">
			<thead>
				<tr>
				    <th width="120" align="center">项目类别</th>
					<th width="150" >项目编号</th>
					<th width="200" align="center">项目名称</th>
					<th width="100" align="center">状态</th>
					<th width="100" align="center">WBS元素号</th>
					<th width="100" align="center">工单号</th>
					<th width="100" align="center">项目设计</th>
					<th width="120" align="center">物资申请</th>
					<th width="120" align="center">备注</th>
					<th width="120" align="center">物资数统计</th>
					<th width="120" align="center">是否核对</th>
					<th width="120" align="center">下达名称</th>
					<th width="120" align="center">下达编号</th>
				</tr>
			</thead>
			<tbody>
			    <?php
			    foreach($list as $arr=>$row) {
			    ?>
				<tr target="id">
					<td ><?php echo isset($row['cCategoryName']) ? htmlspecialchars($row['cCategoryName']) : ''; ?></td>
					<td ><?php echo isset($row['number']) ? htmlspecialchars($row['number']) : ''; ?></td>
					<td ><?php echo isset($row['name']) ? htmlspecialchars($row['name']) : ''; ?></td>
					<td ><?php echo isset($row['disable']) && isset($disable[$row['disable']]) ? $disable[$row['disable']] : (isset($row['disable']) ? (int)$row['disable'] : ''); ?></td>
					<td ><?php echo isset($row['wbs']) ? htmlspecialchars($row['wbs']) : ''; ?></td>
					<td ><?php echo isset($row['gdnumber']) ? htmlspecialchars($row['gdnumber']) : ''; ?></td>
					<td ><?php echo isset($row['design']) && isset($design[$row['design']]) ? $design[$row['design']] : (isset($row['design']) ? (int)$row['design'] : ''); ?></td>
					<td ><?php echo isset($row['apply']) && isset($apply[$row['apply']]) ? $apply[$row['apply']] : (isset($row['apply']) ? (int)$row['apply'] : ''); ?></td>
					<td ><?php echo isset($row['remark_']) ? htmlspecialchars($row['remark_']) : ''; ?></td>
					<td ><?php echo isset($row['orders_num']) ? (int)$row['orders_num'] : 0; ?></td>
					<td ><?php echo isset($row['check']) && isset($check[$row['check']]) ? $check[$row['check']] : (isset($row['check']) ? (int)$row['check'] : ''); ?></td>
					<td ><?php echo isset($row['xd_name']) ? htmlspecialchars($row['xd_name']) : ''; ?></td>
					<td ><?php echo isset($row['xd_order']) ? htmlspecialchars($row['xd_order']) : ''; ?></td>
				</tr>
                <?php }?>


 </tbody>
</table>
