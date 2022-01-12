<script>
	jQuery(document).ready(function() {
		calculate();
		function calculate() {
			var modal_price = jQuery('#modal_price').val();
			var sell_price = jQuery('#sell_price').val();
			var total = remove_separator(sell_price) - remove_separator(modal_price);
			jQuery('#profit').val(thousand_separator(total));
		}

		jQuery('#modal_price, #sell_price').on('change keyup keydown', function() {
			calculate();
		});
	});