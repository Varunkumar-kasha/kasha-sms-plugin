jQuery(document).ready(function () {

	jQuery("ul.kasha_tabs button").click(function (e) {
	  /* stop default action of navigating to # */
	  e.preventDefault();
	  /* make the clicked link active and others inactive */
	  jQuery(this).closest("li").addClass("kasha_tab_active").siblings().removeClass("kasha_tab_active");
	  /* get the index of the tab (zero indexed) - first (0), second (1), etc. */
	  var i = jQuery(this).closest("li").index();
	  /* show the section with the same index and hide others - easier than using IDs but means HTML must be in strict order */
	  jQuery("#kasha_sms_container section:eq(" + i + ")").show().siblings().hide();
	});

	jQuery('#kasha_template_selection').change(function(e){
		var id_name = jQuery('#kasha_template_selection').val();
		jQuery('.kasha_sms_template_content_cm_class').hide();
		jQuery('#'+id_name).css('display','block');
	});


	jQuery('#kasha_template_selection_new_order').change(function(e){
		var id_name = jQuery('#kasha_template_selection_new_order').val();
		jQuery('.kasha_sms_template_content_cm_class_new_order').hide();
		jQuery('#'+id_name+'_new_order').css('display','block');
	});

	jQuery('#kasha_template_selection_back_order_made_but_product_is_out_of_stock').change(function(e){
		var id_name = jQuery('#kasha_template_selection_back_order_made_but_product_is_out_of_stock').val();
		jQuery('.kasha_sms_template_content_cm_class_back_order_made_but_product_is_out_of_stock').hide();
		jQuery('#'+id_name+'_back_order_made_but_product_is_out_of_stock').css('display','block');
	});

	jQuery('#kasha_template_selection_payment_complete').change(function(e){
		var id_name = jQuery('#kasha_template_selection_payment_complete').val();
		jQuery('.kasha_sms_template_content_cm_class_payment_complete').hide();
		jQuery('#'+id_name+'_payment_complete').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_pending_payment').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_pending_payment').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_pending_payment').hide();
		jQuery('#'+id_name+'_order_status_change_pending_payment').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_processing').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_processing').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_processing').hide();
		jQuery('#'+id_name+'_order_status_change_processing').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_verified').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_verified').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_verified').hide();
		jQuery('#'+id_name+'_order_status_change_verified').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_dispatched').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_dispatched').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_dispatched').hide();
		jQuery('#'+id_name+'_order_status_change_dispatched').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_on_hold').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_on_hold').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_on_hold').hide();
		jQuery('#'+id_name+'_order_status_change_on_hold').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_completed').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_completed').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_completed').hide();
		jQuery('#'+id_name+'_order_status_change_completed').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_cancelled').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_cancelled').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_cancelled').hide();
		jQuery('#'+id_name+'_order_status_change_cancelled').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_refunded').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_refunded').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_refunded').hide();
		jQuery('#'+id_name+'_order_status_change_refunded').css('display','block');
	});

	jQuery('#kasha_template_selection_order_status_change_failed').change(function(e){
		var id_name = jQuery('#kasha_template_selection_order_status_change_failed').val();
		jQuery('.kasha_sms_template_content_cm_class_order_status_change_failed').hide();
		jQuery('#'+id_name+'_order_status_change_failed').css('display','block');
	});

	jQuery('#kasha_template_selection_new_customer').change(function(e){
		var id_name = jQuery('#kasha_template_selection_new_customer').val();
		jQuery('.kasha_sms_template_content_cm_class_new_customer').hide();
		jQuery('#'+id_name+'_new_customer').css('display','block');
	});

	jQuery(".kasha_all_users_select_filed").select2({
	    width: '50%',
	    minimumResultsForSearch: -1
	});

	jQuery(".kasha_remove_number_to_block_list").select2({
	    width: '50%',
	    minimumResultsForSearch: -1
	});
	

	var coll = document.getElementsByClassName("kasha_collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("kasha_active");
	    var content = this.nextElementSibling;
	    if (content.style.display === "block") {
	      content.style.display = "none";
	    } else {
	      content.style.display = "block";
	    }
	  });
	}


  	getPagination('#table-id');
	jQuery('#maxRows').trigger('change');
	function getPagination (table){

	  jQuery('#maxRows').on('change',function(){
	  	jQuery('.pagination').html('');						// reset pagination div
	  	var trnum = 0 ;									// reset tr counter 
	  	var maxRows = parseInt(jQuery(this).val());			// get Max Rows from select option
    
	  	var totalRows = jQuery(table+' tbody tr').length;		// numbers of rows 
		 jQuery(table+' tr:gt(0)').each(function(){			// each TR in  table and not the header
		 	trnum++;									// Start Counter 
		 	if (trnum > maxRows ){						// if tr number gt maxRows
		 		
		 		jQuery(this).hide();							// fade it out 
		 	}if (trnum <= maxRows ){jQuery(this).show();}// else fade in Important in case if it ..
		 });											//  was fade out to fade it in 
		 if (totalRows > maxRows){						// if tr total rows gt max rows option
		 	var pagenum = Math.ceil(totalRows/maxRows);	// ceil total(rows/maxrows) to get ..  
		 												//	numbers of pages 
		 	for (var i = 1; i <= pagenum ;){			// for each page append pagination li 
		 	jQuery('.pagination').append('<li data-page="'+i+'">\
							      <span>'+ i++ +'<span class="sr-only"></span></span>\
							    </li>').show();
		 	}											// end for i 
		} 												// end if row count > max rows
		jQuery('.pagination li:first-child').addClass('active'); // add active class to the first li 
        
        
	        //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT
	        showig_rows_count(maxRows, 1, totalRows);
	        //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT

	        jQuery('.pagination li').on('click',function(e){		// on click each page
		        e.preventDefault();
						var pageNum = jQuery(this).attr('data-page');	// get it's number
						var trIndex = 0 ;							// reset tr counter
						jQuery('.pagination li').removeClass('active');	// remove active class from all li 
						jQuery(this).addClass('active');					// add active class to the clicked 
		        
		        
		        //SHOWING ROWS NUMBER OUT OF TOTAL
		        showig_rows_count(maxRows, pageNum, totalRows);
		        //SHOWING ROWS NUMBER OUT OF TOTAL
		        
		        
		        
				jQuery(table+' tr:gt(0)').each(function(){		// each tr in table not the header
				 	trIndex++;								// tr index counter 
				 	// if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
				 	if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
				 		jQuery(this).hide();		
				 	}else {jQuery(this).show();} 				//else fade in 
				}); 										// end of for each tr in table
			});										// end of on click pagination list
		});										// end of on select change 		 								// END OF PAGINATION    
	}	
	// SI SETTING
	jQuery(function(){ 
		default_index();						
	});

	//ROWS SHOWING FUNCTION
	function showig_rows_count(maxRows, pageNum, totalRows) {
	   //Default rows showing
	        var end_index = maxRows*pageNum;
	        var start_index = ((maxRows*pageNum)- maxRows) + parseFloat(1);
	        var string = 'Showing '+ start_index + ' to ' + end_index +' of ' + totalRows + ' entries';               
	        jQuery('.rows_count').html(string);
	}

	// CREATING INDEX
	function default_index() {
	  jQuery('.kasha_history_table tr:eq(0)').prepend('<th> ID </th>')

		var id = 0;

		jQuery('.kasha_history_table tr:gt(0)').each(function(){	
			id++
			jQuery(this).prepend('<td>'+id+'</td>');
		});
	}

	jQuery("#search_input_all").keyup(function (e) {
		FilterkeyWord_all_table();
	});
	// All Table search script
	function FilterkeyWord_all_table() {
	  
	// Count td if you want to search on all table instead of specific column

	  var count = jQuery('.kasha_history_table').children('tbody').children('tr:first-child').children('td').length; 

	        // Declare variables
	  var input, filter, table, tr, td, i;
	  input = document.getElementById("search_input_all");
	  var input_value =     document.getElementById("search_input_all").value;
	        filter = input.value.toLowerCase();
	  if(input_value !=''){
	        table = document.getElementById("table-id");
	        tr = table.getElementsByTagName("tr");

	        // Loop through all table rows, and hide those who don't match the search query
	        for (i = 1; i < tr.length; i++) {
	          
	          var flag = 0;
	           
	          for(j = 0; j < count; j++){
	            td = tr[i].getElementsByTagName("td")[j];
	            if (td) {
	             
	                var td_text = td.innerHTML;  
	                if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
	                //var td_text = td.innerHTML;  
	                //td.innerHTML = 'shaban';
	                  flag = 1;
	                } else {
	                  //DO NOTHING
	                }
	              }
	            }
	          if(flag==1){
	                     tr[i].style.display = "";
	          }else {
	             tr[i].style.display = "none";
	          }
	        }
	    }else {
	      //RESET TABLE
	      jQuery('#maxRows').trigger('change');
	    }
	}
});
