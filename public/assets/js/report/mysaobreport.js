var __mysys_saob_rpt_ent = new __mysys_saob_rpt_ent();
function __mysys_saob_rpt_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');


	
	this.__budget_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.mybudgetallotment-validation')
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
					var trxno = document.getElementById("trxno");
					var project_title = document.getElementById("selProjectTitle");
					var responsibility_code = document.getElementById("responsibility_code");
					var fund_cluster_code = document.getElementById("fund_cluster_code");
					var division_name = document.getElementById("division_name");
					var project_leader = document.getElementById("project_leader");

					//newly added fields
					var program_title = document.getElementById("program_title");
					var project_duration = document.getElementById("project_duration");
					var duration_from = document.getElementById("duration_from");
					var duration_to = document.getElementById("duration_to");
					var program_leader = document.getElementById("program_leader");
					var monitoring_agency = document.getElementById("monitoring_agency");
					var collaborating_agencies = document.getElementById("collaborating_agencies");
					var implementing_agency = document.getElementById("implementing_agency");
					var funding_agency = document.getElementById("funding_agency");

					//new checkbox fields
					let is_realign1 = document.getElementById("is_realign1").checked ? 1 : 0;
					let is_realign2 = document.getElementById("is_realign2").checked ? 1 : 0;
					let is_realign3 = document.getElementById("is_realign3").checked ? 1 : 0;

					//total of ps,mooe & co 
					var total_approved_combined = document.getElementById("total_approved_combined");
					var total_proposed_combined = document.getElementById("total_proposed_combined");

					var tagging = document.getElementById("tagging");

					var with_extension = document.getElementById("with_extension").checked ? 1 : 0;
					var extended_from = document.getElementById("extended_from");
					var extended_to = document.getElementById("extended_to");
					var lddap_refno = document.getElementById("lddap_refno");

					// Prepare PS data DIRECT --
					var rowcount1 = jQuery('.budgetdata-list tr').length;
					var budgetdtdata = [];
					var psdata = '';
	
					for (var aa = 2; aa < rowcount1; aa++) {
						var clonedRow = jQuery('.budgetdata-list tr:eq(' + aa + ')'); 
						var expense_item = clonedRow.find('input[type=text]').eq(0).val();
						var particulars = clonedRow.find('select.selUacs').val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();  
						psdata = expense_item + 'x|x' + particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetdtdata.push(psdata);
					}

					// Prepare PS data INDIRECT --
					var rowcount11 = jQuery('.budgetdata-indirect-list tr').length;
					var budgetdtindirectdata = [];
					var psindirectdata = '';
	
					for (var aa = 2; aa < rowcount11; aa++) {
						var clonedRow = jQuery('.budgetdata-indirect-list tr:eq(' + aa + ')'); 
						var expense_item = clonedRow.find('input[type=text]').eq(0).val();
						var particulars = clonedRow.find('select.selUacs').val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();
						
						psindirectdata = expense_item + 'x|x' + particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetdtindirectdata.push(psindirectdata);
					}

					// Prepare MOEE data
					var rowcount2 = jQuery('.budgetmooedata-list tr').length;
					var budgetmooedtdata = [];
					var mooedata = '';
	
					for (var aa = 2; aa < rowcount2; aa++) {
						var clonedRow = jQuery('.budgetmooedata-list tr:eq(' + aa + ')'); 
						var expense_item = clonedRow.find('input[type=text]').eq(0).val();
						var particulars = clonedRow.find('select.selUacs').val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();
						
						mooedata = expense_item + 'x|x' + particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetmooedtdata.push(mooedata);
					}

					// Prepare MOEE data
					var rowcount22 = jQuery('.budgetmooedata-indirect-list tr').length;
					var budgetmooeindirectdtdata = [];
					var mooeindirectdata = '';
	
					for (var aa = 2; aa < rowcount22; aa++) {
						var clonedRow = jQuery('.budgetmooedata-indirect-list tr:eq(' + aa + ')'); 
						var expense_item = clonedRow.find('input[type=text]').eq(0).val();
						var particulars = clonedRow.find('select.selUacs').val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();
						
						mooeindirectdata = expense_item + 'x|x' + particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetmooeindirectdtdata.push(mooeindirectdata);
					}

					// Prepare CO data
					var rowcount3 = jQuery('.budgetcodata-list tr').length;
					var budgetcodtdata = [];
					var codata = '';
	
					for (var aa = 2; aa < rowcount3; aa++) {
						var clonedRow = jQuery('.budgetcodata-list tr:eq(' + aa + ')'); 
						var particulars = clonedRow.find('input[type=text]').eq(0).val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();  
						
						codata = particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetcodtdata.push(codata);
					}

					// Prepare CO data
					var rowcount33 = jQuery('.budgetcodata-indirect-list tr').length;
					var budgetindirectcodtdata = [];
					var coindirectdata = '';
	
					for (var aa = 2; aa < rowcount33; aa++) {
						var clonedRow = jQuery('.budgetcodata-indirect-list tr:eq(' + aa + ')'); 
						var particulars = clonedRow.find('input[type=text]').eq(0).val();
						var uacs = clonedRow.find('input[type=text]').eq(1).val();
						var approved_budget = clonedRow.find('input[type=number]').eq(0).val();  
						var dtid = clonedRow.find('input[type=number]').eq(0).attr('data-dtid');
						var r1_approved_budget = clonedRow.find('input[type=number]').eq(1).val();  
						var r2_approved_budget = clonedRow.find('input[type=number]').eq(2).val();  
						var r3_approved_budget = clonedRow.find('input[type=number]').eq(3).val();  
						var proposed_realignment = clonedRow.find('input[type=number]').eq(4).val();  
						
						coindirectdata = particulars + 'x|x' + uacs + 'x|x' + approved_budget + 'x|x' + dtid + 'x|x' + r1_approved_budget + 'x|x' + r2_approved_budget + 'x|x' + r3_approved_budget + 'x|x' + proposed_realignment;
						budgetindirectcodtdata.push(coindirectdata);
					}

					var mparam = { 
						recid: recid.value,
						trxno: trxno.value,
						project_title: project_title.value,
						responsibility_code: responsibility_code.value,
						fund_cluster_code: fund_cluster_code.value,
						division_name: division_name.value,
						project_leader: project_leader.value,
						//newly added fields
						program_title: program_title.value,
						project_duration: project_duration.value,
						duration_from: duration_from.value,
						duration_to: duration_to.value,
						program_leader: program_leader.value,
						monitoring_agency: monitoring_agency.value,
						collaborating_agencies: collaborating_agencies.value,
						implementing_agency: implementing_agency.value,
						funding_agency: funding_agency.value,
						tagging: tagging.value,
						budgetdtdata: budgetdtdata,
						budgetdtindirectdata: budgetdtindirectdata,
						budgetmooedtdata: budgetmooedtdata,
						budgetmooeindirectdtdata: budgetmooeindirectdtdata,
						budgetcodtdata: budgetcodtdata,
						budgetindirectcodtdata: budgetindirectcodtdata,
						//checkboxes
						is_realign1: is_realign1,
						is_realign2: is_realign2,
						is_realign3: is_realign3,
						//total
						total_approved_combined:total_approved_combined.value,
						total_proposed_combined: total_proposed_combined.value,
						//extended duration
						with_extension: with_extension,
						extended_from:extended_from.value,
						extended_to:extended_to.value,
						lddap_refno:lddap_refno.value,
						meaction: 'MAIN-SAVE'
					}


					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'mybudgetallotment',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) {
							jQuery('.mybudgetallotment-outp-msg').html(data);
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

	this.me_apvtrx_pdfprint = function(mtknractr,memdl) { 
		try { 
			var mparam = { 
				mtknractr: mtknractr,
				meaction: memdl
			}
			
			jQuery.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: mesiteurl + 'mysaobrpt',
				context: document.body,
				data: mparam,
				global: false,
				cache: false,
				success: function(data) { //display html using divID

					const myModal = new window.bootstrap.Modal('#mybudgetallotment_print');
					myModal.show();
					return false;
				},
				error: function(xhr, status, error) { // display global error on the menu function
					alert('Error sa ajax saob print');
					return false;
				} 
			}); 
			
		} catch(err) { 
			alert('Error sa try catch saob print');
			return false;
		}  //end try			
	}; //end me_apvtrx_pdfprint


}; //end main
