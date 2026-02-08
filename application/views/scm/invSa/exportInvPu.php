<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="20"><H3>领料单汇总<H3></td></tr>
  		</table>
		<table width="1500px" class="list"  border="1">
			<thead>
				<tr>

					<th width="150" align="center">物料编号</th>
				    <th width="200" align="center">物料描述</th>
					<th width="150" align="center" >数量</th>
					<th width="50" align="center" >单位</th>
					<th width="100" align="center" >单价</th>
					<th width="100" align="center" >总金额</th>
					<th width="150" align="center" >采购订单号</th>
					<th width="150" align="center" >单据类型</th>
					<th width="100" align="center" >编号</th>
					<th width="150" align="center" >项目名</th>
				    <th width="100" align="center" >领料人</th>
				    <th width="100" align="center" >单据日期</th>
				    <th width="100" align="center" >仓库位置</th>
				    <th width="100" align="center" >超期时间提示</th>
				    <th width="100" align="center" >出库状态</th>
                    <!--                    -->
                    <th width="100" align="center" >项目类别</th>
                    <th width="100" align="center" >项目状态</th>
                    <th width="100" align="center" >是否下达</th>
                    <th width="100" align="center" >是否报审</th>
                    <th width="100" align="center" >是否核对</th>
				</tr>
			</thead>
			<tbody>
			    <?php
				  foreach($list as $arr=>$row) {
				?>
				<tr>
					<td ><?php echo $row['goodsnumber']?></td>
					<td ><?php echo $row['mdescription']?></td>
					<td ><?php echo abs($row['totalQty'])?></td>
					<td ><?php echo $row['mainUnit']?></td>
					<td ><?php echo $row['price']?></td>
					<td ><?php echo $row['amount']?></td>
					<td ><?php echo $row['ordernumber']?></td>
					<td ><?php echo $row['BillName']?></td>
					<td ><?php echo $row['billNo']?></td>
					<td ><?php echo $row['contactName']?></td>
					<td ><?php echo $row['liname']?></td>
					<td ><?php echo $row['billDate']?></td>
					<td ><?php echo $row['locationNames']?></td>
					<td ><?php echo $row['is_chaoqi']?></td>
					<td ><?php echo $row['chuku_status']?></td>
                    <td ><?php echo $row['customerType']?></td>
                    <!--                    状态-->
                    <td  ><?php echo $disable[$row['disable']]?></td>
                    <!--                    是否下达-->
                    <td  ><?php echo $design[$row['design']]?></td>
                    <!--                    是否送审-->
                    <td  ><?php echo $apply[$row['apply']]?></td>
                    <!--                    是否核对-->
                    <td  ><?php echo $check[$row['check']]?></td>

                </tr>
				  <?php }?>




 </tbody>
</table>

