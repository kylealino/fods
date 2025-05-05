var __mysys_payee_ent = new __mysys_payee_ent();
function __mysys_payee_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__payee_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.mypayee-validation')
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
					var payee_name = document.getElementById("payee_name");
					var payee_account_num = document.getElementById("payee_account_num");
					var payee_office = document.getElementById("payee_office");
					var payee_tin = document.getElementById("payee_tin");
					var payee_address = document.getElementById("payee_address");
					var disb_method = document.getElementById("disb_method");
					var currency = document.getElementById("currency");
					
					var mparam = { 
						recid: recid.value,
						payee_name: payee_name.value,
						payee_account_num: payee_account_num.value,
						payee_office: payee_office.value,
						payee_tin: payee_tin.value,
						payee_address: payee_address.value,
						disb_method: disb_method.value,
						currency: currency.value,
						meaction: 'MAIN-SAVE'
					}


					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'mypayee',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-mypayee-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[MYPAYEE-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'mypayee');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	this.__delete_payee = function() {
		const deleteBtn = document.getElementById('btn_delete');
		const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
	
		let recid = null; // store recid for use after confirmation
	
		deleteBtn.addEventListener('click', function () {
			recid = document.getElementById("recid").value;
	
			// Show the modal
			const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
			deleteModal.show();
		});
	
		confirmDeleteBtn.addEventListener('click', function () {
			if (!recid) return;
	
			const mparam = {
				recid: recid,
				meaction: 'MAIN-DELETE'
			};
	
			jQuery.ajax({
				type: "POST",
				url: mesiteurl + 'mypayee',
				context: document.body,
				data: mparam,
				global: false,
				cache: false,
				success: function(data) {
					jQuery('.me-mypayee-outp-msg').html(data);
				},
				error: function(xhr, status, error) {
					toastr.error('[MYPAYEE-ENT', "Hello, Error Loading Page..." + error, {
						closeButton: true,
					});
				}
			});
	
			// Close the modal
			const deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
			deleteModal.hide();
		});
	};
	
	
	// this.__delete_payee = function() {
    //     document.getElementById('btn_delete').addEventListener('click', function (event) { 
	// 		try { 
	// 			var recid = document.getElementById("recid");
	// 			var fname = document.getElementById("fname");
	// 			var lname = document.getElementById("lname");
				
	// 			var mparam = { 
	// 				recid: recid.value,
	// 				fname: fname.value,
	// 				lname: lname.value,
	// 				meaction: 'MAIN-DELETE'
	// 			}

	// 			jQuery.ajax({ // default declaration of ajax parameters
	// 				type: "POST",
	// 				url: mesiteurl + 'mycrud',
	// 				context: document.body,
	// 				data: eval(mparam),
	// 				global: false,
	// 				cache: false,
	// 				success: function(data) { //display html using divID
	// 					jQuery('.me-mycrud-outp-msg').html(data);
	// 					return false;
	// 				},
	// 				error: function(xhr, status, error) { // display global error on the menu function
	// 					//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
	// 					toastr.error('[MYCRUD-ENT', "Hello, Error Loading Page..." + error, {
	// 					closeButton: true,
	// 					});
	// 					return false;
	// 				} 
	// 			});  
                            
    //             } catch(err) {
    //                 var mtxt = 'There was an error on this page.\n';
    //                 mtxt += 'Error description: ' + err.message;
    //                 mtxt += '\nClick OK to continue.';
    //                 alert(mtxt);
                    
    //                 return false;
        
    //             }  //end try	
	// 	}); 
    // }

}; //end main

__mysys_payee_ent.__delete_payee();