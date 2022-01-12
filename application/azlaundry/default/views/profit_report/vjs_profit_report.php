<script>
	function get_profit() {
		show_loading();
		jQuery.ajax({
			url: app_url + 'profit_report/get_profit',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_profit').serialize(),
			success: function(response) {
				hide_loading();
				jQuery('#table_profit_report tbody').html('');
				jQuery(response.data).each(function(adata, bdata) {
					var table = "";
					table += "<tr>";
					table += "	<td>";
					table += bdata.day;
					table += "	</td>";
					table += "	<td align='right'>";
					table += bdata.transaction;
					table += "	</td>";
					table += "	<td align='right'>";
					table += bdata.outlay;
					table += "	</td>";
					table += "	<td align='right'>";
					table += bdata.total;
					table += "	</td>";
					table += "<tr>";

					jQuery('#table_profit_report tbody').append(table);
				});
				jQuery('.txt-total-transaction').text(response.detail.grand_transaction);
				jQuery('.txt-total-outlay').text(response.detail.grand_outlay);
				jQuery('.txt-total').text(response.detail.grand_total);
			},
			error: function(response) {
			}
		});
	}

	get_profit();

	jQuery("#btn_filter").on('click', function() {
		get_profit();
	});

	jQuery('#btn_print_report').on('click', function() {
        var mywindow = window.open('', 'PRINT', 'height=600,width=800');
        var date_1 = jQuery('#date_1').val();
        var date_2 = jQuery('#date_2').val();

        mywindow.document.write('<html><head><title>' + document.title  + '</title>');
        mywindow.document.write('</head><body style="font-family:\'Arial\'" onload="window.print();"><?php echo azlang("Profit Report");?><br>');
        mywindow.document.write('<?php echo azlang("Period");?> ' + date_1 + ' s/d ' + date_2 + '<br><br>');
        mywindow.document.write(jQuery('.table-profit-wrapper').html());
        mywindow.document.write('</body></html>');

        mywindow.document.close(); 
        mywindow.focus(); 

        // mywindow.print();
        // mywindow.close();

        return true;
	});