<!DOCTYPE html>
<html>
<head>
	<title><?php echo azlang('Sales Transaction');?></title>
	<style type="text/css" media="print">
	  @page { size: landscape; }
	</style>
</head>
<body style="font-family:'Helvetica';font-size:12px;" onload="window.print();">
	<div align="center">
		<h3><?php echo azlang('Sales Transaction');?></h3>
		<?php echo azlang('Period').' '.$date_1.' s/d '.$date_2;?>
		<br><br>
	</div>
	<div>
		<table style="width:100%;border-collapse:collapse;" border="1" cellpadding="5" cellspacing="5">
			<tr>
				<th>#</th>
				<th><?php echo azlang('Outlet');?></th>
				<th><?php echo azlang('Status');?></th>
				<th><?php echo azlang('Invoice Code');?></th>
				<th><?php echo azlang('Date');?></th>
				<th><?php echo azlang('Customer');?></th>
				<th><?php echo azlang('Deadline');?></th>
				<th><?php echo azlang('Pay');?></th>
				<th><?php echo azlang('Total');?></th>
			</tr>
			<?php 
				$t_grand_total_final = 0;
				foreach ($data->result() as $key => $value) {
					$t_grand_total_final += $value->grand_total_final;
			?>
			<tr>
				<td><?php echo ($key + 1);?></td>
				<td><?php echo $value->outlet_name;?></td>
				<td><?php echo azlang($value->transaction_group_status);?></td>
				<td><?php echo $value->code;?></td>
				<td><?php echo $value->date;?></td>
				<td><?php echo $value->customer_name;?></td>
				<td><?php echo $value->duedate;?></td>
				<td><?php echo azlang($value->pay);?></td>
				<td align="right"><?php echo number_format($value->grand_total_final, 0, 2, '.');?></td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td align="right" colspan="8">TOTAL</td>
				<td align="right"><?php echo number_format($t_grand_total_final, 0, 2,'.');?></td>
			</tr>
		</table>
	</div>
</body>
</html>