var __mysys_rci_ent = new __mysys_rci_ent();
function __mysys_rci_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.my_add_rci_line = function () {
		try {
			// Get the total number of rows, excluding the footer row
			var rowCount = jQuery('#rci_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);
	
			// Clone the last data row (not the footer)
			var clonedRow = jQuery('#rci_line_items tbody tr:eq(' + (rowCount - 1) + ')').clone();
				// Enable the delete icon for the new row

			jQuery(clonedRow).find('input[type=text]').eq(0).attr('id', 'col1' + mid); // ID for date field
			jQuery(clonedRow).find('input[type=text]').eq(1).attr('id', 'col2' + mid); // ID for date field
            jQuery(clonedRow).find('input[type=number]').eq(0).attr('id', 'col6' + mid); // ID for date field
			// Now reset only the debit and credit fields (input[type=number])
			
			jQuery(clonedRow).find('input[type=text]').eq(0).val('');  // Clear credit value
			jQuery(clonedRow).find('input[type=text]').eq(1).val('');
			jQuery(clonedRow).find('input[type=number]').eq(0).val('');
	
			// Insert the cloned row before the last row (footer row)
			jQuery('#rci_line_items tbody').append(clonedRow);

			// Make the new row visible
			jQuery(clonedRow).css({ 'display': '' });
	
			// Set the ID for the new row
			jQuery(clonedRow).attr('id', 'tr_rec_' + mid);
	
			// Focus on the first input field of the cloned row
			var xobjArtItem = jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
			jQuery('#' + xobjArtItem).focus();
	
		} catch (err) {
			var mtxt = 'There was an error on this page.\\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\\nClick OK to continue.';
			alert(mtxt);
			return false;
		}
	}

	this.my_add_rci_line_above = function (elem) {
		try {
			var rowCount = jQuery('#rci_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the hidden template row
			var templateRow = jQuery('#rci_line_items tbody tr:hidden:first').clone();

			// Set new IDs and clear values
			jQuery(templateRow).find('input[type=text]').each(function (i) {
				jQuery(this).val('').attr('id', 'col' + (2 + i) + mid);
			});
            jQuery(templateRow).find('input[type=number]').eq(0).val('').attr('id', 'col1' + mid);

			// Insert above the clicked row
			var currentRow = jQuery(elem).closest('tr');
			templateRow.css('display', '').attr('id', 'tr_rec_' + mid);
			templateRow.insertAfter(currentRow);

			// Optional: focus the first input field
			jQuery(templateRow).find('input[type=text]').eq(0).focus();


		} catch (err) {
			alert('Error: ' + err.message);
		}
	}

	this.__rci_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myrci-validation')
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
					var lddapadano = document.getElementById("lddapadano");
					var mds_branch = document.getElementById("mds_branch");
					var mds_accountno = document.getElementById("mds_accountno");
					var lddapada_date = document.getElementById("lddapada_date");
					var fund_cluster_code = document.getElementById("fund_cluster_code");
					var funding_source = document.getElementById("funding_source");
					var ada_approver = document.getElementById("ada_approver");
					let is_ci = document.getElementById("is_ci").checked ? 1 : 0;
					var container = document.getElementById("selected_dvno_list");
					var items = container.querySelectorAll('.badge');

					var dvno_list = [];

					items.forEach(function(el) {
						dvno_list.push(el.getAttribute('data-dvno'));
					});

					// Prepare PS data DIRECT --
					var rowcount1 = jQuery('.dvdata-list tr').length;
					var dvdtdata = [];
					var psdata = '';
	
					for (var aa = 2; aa < rowcount1; aa++) {
						var clonedRow = jQuery('.dvdata-list tr:eq(' + aa + ')'); 
						var dvno = clonedRow.find('input[type=text]').eq(0).val();
						var payee_name = clonedRow.find('input[type=text]').eq(1).val();
						var payee_account_num = clonedRow.find('input[type=text]').eq(2).val();
						var serialno = clonedRow.find('input[type=text]').eq(3).val();
						var uacs_code = clonedRow.find('input[type=text]').eq(4).val();
						var gross_amount = clonedRow.find('input[type=number]').eq(0).val();
						var total_deduction = clonedRow.find('input[type=number]').eq(1).val();
						var net_amount = clonedRow.find('input[type=number]').eq(2).val();  
						var remarks = clonedRow.find('textarea').eq(0).val(); 
						
						psdata = dvno + 'x|x' + payee_name + 'x|x' + payee_account_num + 'x|x' + serialno + 'x|x' + uacs_code + 'x|x' + gross_amount + 'x|x' + total_deduction + 'x|x' + net_amount + 'x|x' + remarks;
						dvdtdata.push(psdata);
					}

					var mparam = { 
						recid: recid.value,
						lddapadano: lddapadano.value,
						mds_branch: mds_branch.value,
						mds_accountno: mds_accountno.value,
						lddapada_date: lddapada_date.value,
						fund_cluster_code: fund_cluster_code.value,
						funding_source: funding_source.value,
						ada_approver: ada_approver.value,
						is_ci: is_ci,
						dvdtdata:dvdtdata,
						dvno_list: dvno_list,
						meaction: 'MAIN-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myrci',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) {
							jQuery('.myrci-outp-msg').html(data);
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

	function generateRandomID(length) {
		const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		let result = '';
		for (let i = 0; i < length; i++) {
			result += chars.charAt(Math.floor(Math.random() * chars.length));
		}
		return result;
	}

}; //end main
