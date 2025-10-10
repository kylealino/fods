var __mysys_proc_pr_ent = new __mysys_proc_pr_ent();
function __mysys_proc_pr_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	// this.my_add_budget_line = function () {
	// 	try {
	// 		// Get the total number of rows, excluding the footer row
	// 		var rowCount = jQuery('#budget_line_items tbody tr').length;
	// 		var mid = generateRandomID(10) + (rowCount + 1);
	
	// 		// Clone the last data row (not the footer)
	// 		var clonedRow = jQuery('#budget_line_items tbody tr:eq(' + (rowCount - 1) + ')').clone();
	// 			// Enable the delete icon for the new row
	// 		jQuery(clonedRow).find('.text-danger').removeClass('text-muted').off('click').on('click', function () {
	// 			jQuery(this).closest('tr').remove();
	// 		});

	// 		jQuery(clonedRow).find('select').eq(0).val('').attr('id', 'col4' + mid);
	// 		jQuery(clonedRow).find('input[type=text]').eq(0).attr('id', 'col2' + mid); // ID for second text field
	// 		jQuery(clonedRow).find('input[type=number]').eq(0).attr('id', 'col3' + mid); // ID for date field
	// 		jQuery(clonedRow).find('input[type=number]').eq(1).attr('id', 'col4' + mid); // ID for date field
	// 		jQuery(clonedRow).find('input[type=number]').eq(2).attr('id', 'col5' + mid); // ID for date field
	// 		jQuery(clonedRow).find('input[type=number]').eq(3).attr('id', 'col6' + mid); // ID for date field
	// 		// Now reset only the debit and credit fields (input[type=number])
			
	// 		jQuery(clonedRow).find('select').eq(0).val('');
	// 		jQuery(clonedRow).find('input[type=text]').eq(0).val('');  // Clear credit value
	// 		jQuery(clonedRow).find('input[type=number]').eq(0).val('').attr('data-dtid', '');  // Clear credit value
	// 		jQuery(clonedRow).find('input[type=number]').eq(1).val('');
	// 		jQuery(clonedRow).find('input[type=number]').eq(2).val('');
	// 		jQuery(clonedRow).find('input[type=number]').eq(3).val('');
	
	// 		// Insert the cloned row before the last row (footer row)
	// 		jQuery('#budget_line_items tbody').append(clonedRow);

	// 		this.__direct_ps_totals();
	
	// 		// Make the new row visible
	// 		jQuery(clonedRow).css({ 'display': '' });
	
	// 		// Set the ID for the new row
	// 		jQuery(clonedRow).attr('id', 'tr_rec_' + mid);
	
	// 		// Focus on the first input field of the cloned row
	// 		var xobjArtItem = jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
	// 		jQuery('#' + xobjArtItem).focus();
	
	// 	} catch (err) {
	// 		var mtxt = 'There was an error on this page.\\n';
	// 		mtxt += 'Error description: ' + err.message;
	// 		mtxt += '\\nClick OK to continue.';
	// 		alert(mtxt);
	// 		return false;
	// 	}
	// }

	this.my_add_pr_line = function () {
		try {
			// Get the total number of rows, excluding the footer row
			var rowCount = jQuery('#pr_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the last data row (not the footer)
			var clonedRow = jQuery('#pr_line_items tbody tr:eq(' + (rowCount - 1) + ')').clone();

			// Enable the delete icon for the new row
			jQuery(clonedRow).find('.text-danger').removeClass('text-muted').off('click').on('click', function () {
				jQuery(this).closest('tr').remove();
			});

			// Assign new IDs
			jQuery(clonedRow).find('textarea').eq(0).attr('id', 'col2' + mid); // replaced input[type=text] with textarea
			jQuery(clonedRow).find('input[type=text]').eq(0).attr('id', 'col1' + mid); // ID for second text field
			jQuery(clonedRow).find('input[type=number]').eq(0).attr('id', 'col3' + mid);
			jQuery(clonedRow).find('input[type=number]').eq(1).attr('id', 'col4' + mid);
			jQuery(clonedRow).find('input[type=number]').eq(2).attr('id', 'col5' + mid);
			jQuery(clonedRow).find('input[type=number]').eq(3).attr('id', 'col6' + mid);

			// Reset values
			jQuery(clonedRow).find('textarea').eq(0).val(''); 
			jQuery(clonedRow).find('input[type=text]').eq(0).val();
			jQuery(clonedRow).find('input[type=number]').eq(0).val('').attr('data-dtid', '');
			jQuery(clonedRow).find('input[type=number]').eq(1).val('');
			jQuery(clonedRow).find('input[type=number]').eq(2).val('');
			jQuery(clonedRow).find('input[type=number]').eq(3).val('');

			// Insert the cloned row before the last row (footer row)
			jQuery('#pr_line_items tbody').append(clonedRow);

			this.__direct_ps_totals();

			// Make the new row visible
			jQuery(clonedRow).css({ 'display': '' });

			// Set the ID for the new row
			jQuery(clonedRow).attr('id', 'tr_rec_' + mid);

			// Focus on the first textarea of the cloned row
			var xobjArtItem = jQuery(clonedRow).find('textarea').eq(0).attr('id');
			jQuery('#' + xobjArtItem).focus();

		} catch (err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			alert(mtxt);
			return false;
		}
	}

	this.my_add_pr_line_above = function (elem) {
		try {
			var rowCount = jQuery('#pr_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the hidden template row
			var templateRow = jQuery('#pr_line_items tbody tr:hidden:first').clone();

			// Set new IDs and clear values
			jQuery(templateRow).find('textarea').eq(0).attr('id', 'col2' + mid); // replaced input[type=text] with textarea
			jQuery(templateRow).find('input[type=text]').eq(0).val('').attr('id', 'col1' + mid);
			jQuery(templateRow).find('input[type=number]').each(function (i) {
				jQuery(this).val('').attr('id', 'col' + (3 + i) + mid).attr('data-dtid', '');
			});

			// Insert above the clicked row
			var currentRow = jQuery(elem).closest('tr');
			templateRow.css('display', '').attr('id', 'tr_rec_' + mid);
			templateRow.insertAfter(currentRow);

			// Optional: focus the first input field
			jQuery(templateRow).find('input[type=text]').eq(0).focus();

			// Recalculate if needed
			this.__direct_ps_totals();

		} catch (err) {
			alert('Error: ' + err.message);
		}
	}

	function generateRandomID(length) {
		const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		let result = '';
		for (let i = 0; i < length; i++) {
			result += chars.charAt(Math.floor(Math.random() * chars.length));
		}
		return result;
	}
	
	this.__pr_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myprocurement-validation')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				try {
					event.preventDefault();
					event.stopPropagation();

					var recid = document.getElementById("recid");
					var entity_name = document.getElementById("entity_name");
					var office = document.getElementById("office");
					var prno = document.getElementById("prno");
					var responsibility_code = document.getElementById("responsibility_code");
					var fund_cluster = document.getElementById("fund_cluster");
					var pr_date = document.getElementById("pr_date");
					var end_user = document.getElementById("end_user");
					var charge_to = document.getElementById("charge_to");
					var purpose = document.getElementById("purpose");
					var estimated_cost = document.getElementById("estimated_cost");

					//Prepare PS data DIRECT --
					var rowcount1 = jQuery('.prdata-list tr').length;
					var prdtdata = [];
					var psdata = '';
	
					for (var aa = 2; aa < rowcount1; aa++) {
						var clonedRow = jQuery('.prdata-list tr:eq(' + aa + ')'); 

						var item_desc = clonedRow.find('textarea').eq(0).val();
						var unit = clonedRow.find('input[type=text]').eq(0).val();
						var quantity = clonedRow.find('input[type=number]').eq(0).val();  
						var unit_cost = clonedRow.find('input[type=number]').eq(1).val();  
						var total_cost = clonedRow.find('input[type=number]').eq(2).val();  
						psdata = item_desc + 'x|x' + unit + 'x|x' + quantity + 'x|x' + unit_cost + 'x|x' + total_cost;
						prdtdata.push(psdata);
					}

					// console.log('PS Table Fetching----------------------');
					// console.log(prdtdata);

					
					var mparam = { 
						recid: recid.value,
						entity_name: entity_name.value,
						office: office.value,
						prno: prno.value,
						responsibility_code: responsibility_code.value,
						fund_cluster: fund_cluster.value,
						pr_date: pr_date.value,
						end_user: end_user.value,
						charge_to: charge_to.value,
						purpose: purpose.value,
						estimated_cost: estimated_cost.value,
						prdtdata: prdtdata,
						meaction: 'PR-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myprocurement',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) {
							jQuery('.myprocpr-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							alert('Error: ' + error);
							return false;
						} 
					}); 

				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	};

	//PS - TOTAL PER LINE - DIRECT
	this.__direct_ps_totals = function () {
        jQuery('.prdata-list tr').each(function () {
            var row = jQuery(this);

            var quantity = parseFloat(row.find('.quantity').val()) || 0;
            var unit_cost = parseFloat(row.find('.unit_cost').val()) || 0;

            var total = quantity * unit_cost;

            row.find('.total_cost').val(total.toFixed(2));
        });
    };

	this.__showPdfInModal = function(pdfUrl) {
		var pdfFrame = document.getElementById("pdfFrame");
		var pdfModal = new bootstrap.Modal(document.getElementById("pdfModal"));

		pdfFrame.src = pdfUrl;
		pdfModal.show();
	};


	this.__combined_totals = function () {
		let totalApprovedCombined = 0;
		let totalCostCombined = 0;

		jQuery('.prdata-list tr').each(function () {
			let row = jQuery(this);

			let	quantity = parseFloat(row.find('.quantity').val()) || 0;

			let unit_cost = parseFloat(row.find('.unit_cost').val()) || 0;

			let total = quantity * unit_cost;

			row.find('.total_cost').val(total.toFixed(2));

			totalCostCombined += total;

		});

		$('#estimated_cost').val(
		Number(totalCostCombined || 0).toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		})
		);


	};

	this.__toggleExtensionFields = function(checkbox){
		var extFields = document.getElementById("extension_fields");
        extFields.style.display = checkbox.checked ? "block" : "none";
	}

	this.__saob_print = function(pdfUrl) {
		var pdfFrame = document.getElementById("pdfFrame");
		var placeholder = document.getElementById("pdfPlaceholder");

		const month = document.getElementById("month").value;
		const year = document.getElementById("year").value;

		if (!month || !year) {
			toastr.error('Please select both month and year.', 'Oops!', {
				progressBar: true,
				closeButton: true,
				timeOut: 2000,
			});
			return;
		}

		let url = new URL(pdfUrl, window.location.origin);
		url.searchParams.set('month', month);
		url.searchParams.set('year', year);

		// Set iframe src and show iframe
		pdfFrame.src = url.toString();
		pdfFrame.style.display = "block";

		// Hide the placeholder
		placeholder.style.display = "none";
	};

	$(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            order: [[2, 'desc']],
            language: {
            search: "Search:"
            }
        });

        $('.revision').prop('disabled', true);

    });

    $(document).on('change', '.selUacs', function() {
        var selectedCode = $(this).find('option:selected').data('uacs');
        $(this).closest('tr').find('.uacs').val(selectedCode);
    });

    $(document).on('change', '#selProjectTitle', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var fund = selected.data('fund') || '';
        var division = selected.data('division') || '';
        var responsibility = selected.data('responsibility') || '';

        // Set the values into inputs
        $('#fund_cluster_code').val(fund);
        $('#division_name').val(division);
        $('#responsibility_code').val(responsibility);
    });

}; //end main
