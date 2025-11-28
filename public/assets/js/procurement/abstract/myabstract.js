var __mysys_abstract_ent = new __mysys_abstract_ent();
function __mysys_abstract_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.my_add_abstract_line = function () {
		try {
			// Get the total number of rows, excluding the footer row
			var rowCount = jQuery('#abstract_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the last data row (not the footer)
			var clonedRow = jQuery('#abstract_line_items tbody tr:eq(' + (rowCount - 1) + ')').clone();

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
			jQuery(clonedRow).find('input[type=number]').eq(4).attr('id', 'col7' + mid);
			jQuery(clonedRow).find('input[type=number]').eq(5).attr('id', 'col8' + mid);

			// Reset values
			jQuery(clonedRow).find('textarea').eq(0).val(''); 
			jQuery(clonedRow).find('input[type=text]').eq(0).val('');
			jQuery(clonedRow).find('input[type=number]').eq(0).val('').attr('data-dtid', '');
			jQuery(clonedRow).find('input[type=number]').eq(1).val('');
			jQuery(clonedRow).find('input[type=number]').eq(2).val('');
			jQuery(clonedRow).find('input[type=number]').eq(3).val('');
			jQuery(clonedRow).find('input[type=number]').eq(4).val('');
			jQuery(clonedRow).find('input[type=number]').eq(5).val('');

			// Insert the cloned row before the last row (footer row)
			jQuery('#abstract_line_items tbody').append(clonedRow);

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

	this.my_add_abstract_line_above = function (elem) {
		try {
			var rowCount = jQuery('#abstract_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the hidden template row
			var templateRow = jQuery('#abstract_line_items tbody tr:hidden:first').clone();

			// Set new IDs and clear values
			jQuery(templateRow).find('textarea').eq(0).attr('id', 'col2' + mid); // replaced input[type=text] with textarea
			jQuery(templateRow).find('input[type=text]').eq(0).val('').attr('id', 'col1' + mid);
			jQuery(templateRow).find('input[type=number]').each(function (i) {
				jQuery(this).val('').attr('id', 'col' + (5 + i) + mid).attr('data-dtid', '');
			});

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

	function generateRandomID(length) {
		const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		let result = '';
		for (let i = 0; i < length; i++) {
			result += chars.charAt(Math.floor(Math.random() * chars.length));
		}
		return result;
	}

	this.__abstract_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myabstract-validation')
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
					var prno = document.getElementById("prno");
					var transaction_no = document.getElementById("transaction_no");
					var abstract_date = document.getElementById("abstract_date");
					var bidder_1 = document.getElementById("bidder_1");
					var bidder_2 = document.getElementById("bidder_2");
					var bidder_3 = document.getElementById("bidder_3");
					var bidder_4 = document.getElementById("bidder_4");
					var bidder_5 = document.getElementById("bidder_5");


					//Prepare PS data DIRECT --
					var rowcount1 = jQuery('.abstractdata-list tr').length;
					var abstractdtdata = [];
					var abstractdata = '';
	
					for (var aa = 2; aa < rowcount1; aa++) {
						var clonedRow = jQuery('.abstractdata-list tr:eq(' + aa + ')'); 

						var quantity = clonedRow.find('input[type=number]').eq(0).val();  
						var unit = clonedRow.find('input[type=text]').eq(0).val();
						var item_desc = clonedRow.find('textarea').eq(0).val();
						var bidder_dt1 = clonedRow.find('input[type=number]').eq(1).val();  
						var bidder_dt2 = clonedRow.find('input[type=number]').eq(2).val();
						var bidder_dt3 = clonedRow.find('input[type=number]').eq(3).val();
						var bidder_dt4 = clonedRow.find('input[type=number]').eq(4).val();
						var bidder_dt5 = clonedRow.find('input[type=number]').eq(5).val();
						abstractdata = quantity + 'x|x' + unit + 'x|x' + item_desc + 'x|x' + bidder_dt1 + 'x|x' + bidder_dt2 + 'x|x' + bidder_dt3 + 'x|x' + bidder_dt4 + 'x|x' + bidder_dt5;
						abstractdtdata.push(abstractdata);
					}

					var mparam = { 
						recid: recid.value,
						prno: prno.value,
						transaction_no: transaction_no.value,
						abstract_date: abstract_date.value,
						bidder_1: bidder_1.value,
						bidder_2: bidder_2.value,
						bidder_3: bidder_3.value,
						bidder_4: bidder_4.value,
						bidder_5: bidder_5.value,
						abstractdtdata: abstractdtdata,
						meaction: 'ABSTRACT-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myabstract',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) {
							jQuery('.myabstract-outp-msg').html(data);
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

	this.__showPdfInModal = function(pdfUrl) {
		var pdfFrame = document.getElementById("pdfFrame");
		var pdfModal = new bootstrap.Modal(document.getElementById("pdfModal"));

		pdfFrame.src = pdfUrl;
		pdfModal.show();
	};

}; //end main
