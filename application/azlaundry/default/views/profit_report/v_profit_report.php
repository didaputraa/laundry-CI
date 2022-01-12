<div class="container-profit">
	<form class="form-horizontal" id="form_profit">
		<div class="form-group">
			<label class="control-label col-sm-1"><?php echo azlang('Date');?></label>
			<div class="col-sm-4">
				<table>
					<tr>
						<td><?php echo $datetime1;?></td>
						<td>&nbsp;<?php echo azlang('to');?>&nbsp;</td>
						<td><?php echo $datetime2;?></td>
					</tr>
				</table>
			</div>
		</div>
		<?php 
			if (strlen($this->session->userdata('idoutlet')) == 0) {
		?>
		<div class="form-group">
			<label class="control-label col-sm-1"><?php echo azlang('Outlet');?></label>
			<div class="col-sm-4">
				<?php echo az_select_outlet();?>
			</div>
		</div>
		<?php
			}
		?>
		<button class="btn btn-info" id="btn_filter" type="button"><?php echo azlang('Filter');?></button>
		<button class="btn btn-default" id="btn_print_report" type="button"><?php echo azlang('Print');?></button>
	</form>
</div>

<div class="table-profit-wrapper table-responsive">
	<table class="table table-condensed table-bordered" id="table_profit_report" border="1" cellpadding="5" cellspacing="5" style="border-collapse: collapse" width="100%">
		<thead>
			<tr>
				<td align="center" style="font-weight:bold;"><?php echo azlang('Date');?></td>
				<td align="center" style="font-weight:bold;"><?php echo azlang('Transaction');?></td>
				<td align="center" style="font-weight:bold;"><?php echo azlang('Outlay');?></td>
				<td align="center" style="font-weight:bold;"><?php echo azlang('Total');?></td>
			</tr>
		</thead>
		<tbody>

		</tbody>
		<tfoot>
			<tr>
				<td align="right">Total</td>
				<td align="right" class="txt-total-transaction">0,00</td>
				<td align="right" class="txt-total-outlay">0,00</td>
				<td align="right" class="txt-total">0,00</td>
			</tr>
		</tfoot>
	</table>
</div>