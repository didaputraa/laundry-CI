<input type="hidden" id="product_selected">
<form class="form-horizontal" id="form_transaction">
	<input type="hidden" id="idtransaction_group" name="idtransaction_group">
	<input type="hidden" id="x_transaction" name="x_transaction">
	<input type="hidden" id="x_transaction_detail" name="x_transaction_detail">
	<div class="box-field purple">
		<div class="row">
			<div class="col-sm-5">
				<?php
					if (strlen($this->session->userdata('idoutlet')) == 0) {
				?>
				<div class="form-group">
					<label class="control-label col-sm-4"><?php echo azlang('Outlet');?></label>
					<div class="col-sm-8">
						<?php echo az_select_outlet();?>
					</div>
				</div>
				<?php
					}
					else {
				?>
					<input type="hidden" name="idoutlet" id="idoutlet" value="<?php echo $this->session->userdata('idoutlet');?>">
				<?php
					}
				?>
				<div class="form-group">
					<label class="control-label col-sm-4"><?php echo azlang('Customer');?></label>
					<div class="col-sm-8">
						<table width="100%">
							<tr>
								<td>
									<?php echo az_select_customer();?>
								</td>
								<td>&nbsp;</td>
								<td><button class="btn btn-default" id="btn_add_customer" type="button"><i class="fa fa-plus"></i></button></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label col-sm-4"><?php echo azlang('Date');?></label>
					<div class="col-sm-8">
						<?php echo $date;?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?php echo azlang('Due Date');?></label>
					<div class="col-sm-8">
						<?php echo $duedate;?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="box-field green box-sales-transaction">
		<div class="table-responsive">
			<table class="table table-condensed table-transaction">
				<thead>
					<tr>
						<th width="10px"></th>
						<th width="45px">No</th>
						<th><?php echo azlang('Product');?></th>
						<th width="100px"><?php echo azlang('Product Type');?></th>
						<th width="100px"><?php echo azlang('Qty');?></th>
						<th width="120px"><?php echo azlang('Price');?></th>
						<th width="100px"><?php echo azlang('Discount');?></th>
						<th width="100px"><?php echo azlang('Add Cost');?></th>
						<th width="100px"><?php echo azlang('Tax');?></th>
						<th width="120px"><?php echo azlang('Total');?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<button class="btn btn-danger btn-remove-row" data-id="transaction" type="button"><i class="fa fa-remove"></i></button>
						</td>
						<td><input readonly type="text" class="form-control numb" value="1"></td>
						<td>
							<input type="hidden" name="idtransaction[]" class="product-idtransaction">
							<input type="hidden" name="idproduct[]" class="product-idproduct">
							<div class="container-product-search">
								<input readonly type="text" class="form-control product-name" placeholder="<?php echo azlang('Search Product');?>">
								<button class="btn btn-default btn-search-product" type="button"><i class="fa fa-search"></i></button>
							</div>
						</td>
						<td><input type="text" readonly class="form-control product-type"></td>
						<td><input type="text" value="1,00" class="form-control product-qty format-number-decimal" name="qty[]"></td>
						<td><input type="text" value="0,00" class="form-control product-price format-number-decimal" name="price[]"></td>
						<td><input type="text" value="0,00" class="form-control product-discount format-number-decimal" name="discount[]"></td>
						<td><input type="text" value="0,00" class="form-control product-add-cost format-number-decimal" name="add_cost[]"></td>
						<td><input type="text" value="0,00" class="form-control product-tax format-number-decimal" name="tax[]"></td>
						<td>
							<input type="text" value="0,00" class="form-control product-total format-number-decimal" readonly name="total[]">
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="10"><button class="btn btn-warning btn-sm btn-add-product-transaction" type="button"><i class="fa fa-plus"></i> Tambah Produk</button></td>
					</tr>
				</tfoot>			
			</table>
		</div>

		<div class="container-detail">
			<div class="box-table-description box-detail">
				<table class="table-detail table table-condensed">
					<thead>
						<tr>
							<th width="10px"></th>
							<th width="50px">No</th>
							<th><?php echo azlang('Description');?></th>
							<th width="90px"><?php echo azlang('Qty');?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input type="hidden" name="idtransaction_detail[]" class="detail-idtransaction-detail"><button class="btn btn-danger btn-remove-row" data-id="transaction-detail" type="button"><i class="fa fa-remove"></i></button></td>
							<td><input type="text" readonly class="form-control detail-numb numb" value="1"></td>
							<td><input type="text" name="detail_description[]" class="form-control detail-description"></td>
							<td width="120px"><input type="number" name="detail_qty[]" class="form-control detail-qty txt-center">
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" align="right">TOTAL</td>
							<td align="right"><span class="txt-total-qty"></span></td>
						</tr>
						<tr>
							<td colspan="5"><button class="btn btn-warning btn-sm btn-add-detail" type="button"><i class="fa fa-plus"></i> Tambah Keterangan</button></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="box-price">
				<table class="table-info">
					<tr>
						<td width="150px"><?php echo azlang('Grand Total');?></td>
						<td colspan="3"><input readonly type="text" class="form-control txt-right" id="info_total" name="info_grand_total"></td>
					</tr>
					<tr>
						<td><?php echo azlang('Discount');?></td>
						<td width="120px">
							<div class="input-group">
									<input type="text" class="form-control txt-right format-number-decimal" value="0,00" id="info_discount_percent" name="info_discount_percent">
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</td>
						<td width="130px">
							<input type="text" name="info_discount" id="info_discount" value="0,00" class="form-control txt-right format-number-decimal">
						</td>
					</tr>
					<tr>
						<td><?php echo azlang('Tax');?></td>
						<td width="100px">
							<div class="input-group">
									<input type="text" class="form-control txt-right format-number-decimal" value="0,00" id="info_tax_percent" name="info_tax_percent">
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</td>
						<td width="130px">
							<input type="text" name="info_tax" id="info_tax" value="0,00" class="form-control txt-right format-number-decimal">
						</td>
					</tr>
					<tr>
						<td><?php echo azlang('Add Cost');?></td>
						<td colspan="3"><input type="text" class="form-control txt-right format-number-decimal" id="info_add_cost" name="info_add_cost" value="0,00"></td>
					</tr>
					<tr>
						<td><?php echo azlang('Grand Total Final');?></td>
						<td colspan="3"><input readonly type="text" class="form-control txt-right" id="info_total_final" value="0" name="info_total_final"></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="bottom-field">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo azlang('Pay');?></label>
				<div class="col-sm-4">
					<select class="form-control" id="pay" name="pay">
						<option value="PAID"><?php echo azlang('PAID');?></option>
						<option value="NOT PAID YET"><?php echo azlang('NOT PAID YET');?></option>
					</select>
				</div>
			</div>
			<div class="form-group container-pay-date">
				<label class="control-label col-sm-2"><?php echo azlang('Pay Date');?></label>
				<div class="col-sm-4">
					<?php echo $pay_date;?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo azlang('Status');?></label>
				<div class="col-sm-4">
					<select class="form-control" id="transaction_group_status" name="transaction_group_status">
						<option value="NEW"><?php echo azlang('NEW');?></option>
						<option value="PROGRESS"><?php echo azlang('PROGRESS');?></option>
						<option value="FINISH"><?php echo azlang('FINISH');?></option>
						<option value="ACCEPTED"><?php echo azlang('ACCEPTED');?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo azlang('Note');?></label>
				<div class="col-sm-4">
					<textarea class="form-control" name="info_note" id="info_note" placeholder="<?php echo azlang('Note');?>"></textarea>
				</div>
			</div>
		</div>

		<div>
			<a href="<?php echo app_url();?>sales_transaction"><button class="btn btn-default" type="button"><?php echo azlang('Back');?></button></a>
			<a href="<?php echo app_url();?>sales_transaction/add"><button class="btn btn-default" type="button"><?php echo azlang('New Transaction');?></button></a>
			<button class="btn btn-info" type="button" id="btn_save_transaction"><?php echo azlang('Save');?></button>
			<button class="btn btn-info" type="button" id="btn_save_print_transaction"><?php echo azlang('Save & Print Nota');?></button>
		</div>
	</div>
</form>