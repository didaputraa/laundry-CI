<?php 
	$this->load->helper('az_core');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo azlang('Invoice');?></title>
	<style type="text/css">
		
		.table-product td {
			padding: 4px;
		}
		.table-price input {
			width: 130px;
		}
		.table-price td {
			text-align: right;
			padding: 4px;
		}
		
	</style>
</head>
<body style="font-family:'Helvetica';font-size:12px;" onload="window.print();">
	<div>
		<div>
			<table width="100%">
				<tr>
					<td width="10px"><img height="60px" src="<?php echo base_url().AZAPP;?>assets/images/logo.png"></td>
					<td>
						<div style="padding-left:10px;font-size:14px;font-weight:bold;">
							<?php echo $outlet['outlet_name'];?><br>
							<?php echo $outlet['address'];;?><br>
							<?php echo $outlet['phone'];;?><br>
						</div>
					</td>
					<td align="right" style="font-size:25px;font-weight:bold;color:#00b6ff;;"><u>INVOICE</u></td>
				</tr>	
			</table>
		</div>

		<div style="margin-top: 20px">
			<table width="100%">
				<tr>
					<td width="10px"><?php echo azlang('Customer');?></td>
					<td width="5px;">&nbsp;:&nbsp;</td>
					<td><?php echo $data['customer_name'];?></td>
					<td width="140px"><?php echo azlang('Invoice Code');?></td>
					<td width="5px">&nbsp;:&nbsp;</td>
					<td width="140px;" class="transaction-group-code"><?php echo $data['code'];?></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td><?php echo $data['address'];?></td>
					<td><?php echo azlang('Invoice Date');?></td>
					<td>&nbsp;:&nbsp;</td>
					<td><?php echo Date('d-m-Y H:i:s', strtotime($data['date']));?></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td><?php echo $data['phone'];?></td>
					<td><?php echo azlang('Duedate');?></td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<?php 
							if (strlen($data['duedate']) > 0) {
								echo Date('d-m-Y H:i:s', strtotime($data['duedate']));
							}
						?>
					</td>
				</tr>
			</table>
		</div>

		<div style="margin-top:20px;overflow-x:auto;">
			<table width="100%" class="table-product" cellpadding="5" cellspacing="0" border="1">
				<tr>
					<th><?php echo azlang('#');?></th>
					<th><?php echo azlang('Product');?></th>
					<th><?php echo azlang('Qty');?></th>
					<th><?php echo azlang('Price');?></th>
					<th><?php echo azlang('Discount');?></th>
					<th><?php echo azlang('Add Cost');?></th>
					<th><?php echo azlang('Tax');?></th>
					<th><?php echo azlang('Total');?></th>
				</tr>
				<?php 
					foreach ($transaction as $key => $value) {
				?>
				<tr>
					<td align="center"><?php echo $key+1;?></td>
					<td><?php echo $value['product_name'];?></td>
					<td align="center"><?php echo $value['qty'];?></td>
					<td align="right"><?php echo az_thousand_separator_decimal($value['price']);?></td>
					<td align="right"><?php echo az_thousand_separator_decimal($value['discount']);?></td>
					<td align="right"><?php echo az_thousand_separator_decimal($value['add_cost']);?></td>
					<td align="right"><?php echo az_thousand_separator_decimal($value['tax']);?></td>
					<td align="right"><?php echo az_thousand_separator_decimal($value['total']);?></td>
				</tr>
				<?php
					}
				?>
			</table>
		</div>

		<div style="margin-top:20px">
			<table width="100%">
				<tr>
					<td style="vertical-align:top;">
						<table cellpadding="4" cellspacing="0" class="table-description" style="border-collapse: collapse" border="1">
							<tr>
								<th><?php echo azlang('#');?></th>
								<th><?php echo azlang('Description');?></th>
								<th><?php echo azlang('Qty');?></th>
							</tr>
							<?php 
								$total_qty = 0;
								foreach ($transaction_detail as $key => $value) {
									$total_qty += $value['detail_qty'];
							?>
							<tr>
								<td align="center"><?php echo $key + 1;?></td>
								<td><?php echo $value['detail_description'];?></td>
								<td align="center"><?php echo $value['detail_qty'];?></td>
							</tr>
							<?php
								}
							?>
							<tr>
								<td colspan="2" align="right">Total</td>
								<td align="center"><?php echo $total_qty;?></td>
							</tr>
						</table>
						<br>
						<p style="font-style:italic"><?php echo $data['note'];?></p>
					</td>
					<td align="right" style="vertical-align:top;">
						<table class="table-price">
							<tr>
								<td><?php echo azlang('Total');?></td>
								<td><?php echo az_thousand_separator_decimal($data['grand_total']);?></td>
							</tr>
							<tr>
								<td><?php echo azlang('Discount');?></td>
								<td><?php echo az_thousand_separator_decimal($data['grand_discount']);?></td>
							</tr>
							<tr>
								<td><?php echo azlang('Add Cost');?></td>
								<td><?php echo az_thousand_separator_decimal($data['grand_add_cost']);?></td>
							</tr>
							<tr>
								<td><?php echo azlang('Tax');?></td>
								<td><?php echo az_thousand_separator_decimal($data['grand_tax']);?></td>
							</tr>
							<tr>
								<td><?php echo azlang('Grand Total Final');?></td>
								<td><?php echo az_thousand_separator_decimal($data['grand_total_final']);?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>


		<div style="margin-top:10px">
			<table width="100%">
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td><?php echo azlang('Status');?></td>
								<td>&nbsp;:&nbsp;</td>
								<td><?php echo azlang($data['transaction_group_status']);?></td>
							</tr>
							<tr>
								<td><?php echo azlang('Pay');?></td>
								<td>&nbsp;:&nbsp;</td>
								<td><?php echo azlang($data['pay']);?></td>
							</tr>
						</table>
					</td>
					<td align="center" width="50%">
						<?php echo azlang('Best Regards');?>
						<br><br><br><br>
						<b>
						<?php 
							echo az_get_config('app_name');
						?>
						</b>
					</td>
				</tr>
			</table>
		</div>

		<div style="margin-top:10px">
			<?php echo az_get_config('app_footer_nota');?>
		</div>
	</div>
</body>
</html>