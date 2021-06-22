
</br>
<b><h1><?php _e( 'Kasha SMS Notification', KASHA_SMS_TEXT_DOMAIN );?></h1></b>
</br></br>
<?php if ( isset( $message ) && !empty( $message ) ) { ?>
<div class="alert" style="padding: 20px;background-color: <?php echo($message_color);?>;color: white;">
  <span class="closebtn" style=" margin-left: 15px;color: white;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;transition: 0.3s;" onclick="this.parentElement.style.display='none';">&times;</span> 
  <?php _e( $message, KASHA_SMS_TEXT_DOMAIN ); ?>
</div>
</br>
<?php } ?>
<ul class="kasha_tabs">
  <li><button  class="button-primary" style="background: #ec407e;border-color:#ec407e"><?php _e( 'Oprational Events', KASHA_SMS_TEXT_DOMAIN );?></button></li>
  <li><button  class="button-primary" style="background: #ec407e;border-color:#ec407e"><?php _e( 'Create Contact Groups', KASHA_SMS_TEXT_DOMAIN );?></button></li>
  <li><button  class="button-primary" style="background: #ec407e;border-color:#ec407e"><?php _e( 'Promotional SMS', KASHA_SMS_TEXT_DOMAIN );?></button></li>
  <li><button  class="button-primary" style="background: #ec407e;border-color:#ec407e"><?php _e( 'Blacklist', KASHA_SMS_TEXT_DOMAIN );?></button></li>
  <li><button  class="button-primary" style="background: #ec407e;border-color:#ec407e"><?php _e( 'History', KASHA_SMS_TEXT_DOMAIN );?></button></li>
</ul>
<div id="kasha_sms_container" style="min-height: 500px">
  <section>
	<h2><?php _e( 'Created Oprational Events', KASHA_SMS_TEXT_DOMAIN );?></h2>
		<?php
			foreach ( $operational_sms_event as $value ) {
				$sms_content = str_replace( ' ', '_', $value );
				$sms_content_of_operation_event = get_option( $sms_content );
				$sms_template_status = get_option( $sms_content.'_status' );
				if( $sms_template_status == 'on' ) {
					$sms_template_status = 'checked';
					$sms_template_status_display = '<span style="color:green"> ( Active ) </span>';
				} else {
					$sms_template_status = '';
					$sms_template_status_display = '<span style="color:red">  ( Deactive )  </span>';
				}?>
				<form action="" method="post" onsubmit="return confirm( 'Are you sure about updating the content?' )">
					<button type="button" class="kasha_collapsible"> <?php echo( $value . $sms_template_status_display );?> </button>
					<div class="kasha_content"></br>
						<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
							<tr>
							    <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><b><?php _e( 'Shortcode Category', KASHA_SMS_TEXT_DOMAIN ); ?></b></th>
							    <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><b><?php _e( 'Shortcodes', KASHA_SMS_TEXT_DOMAIN );         ?></b></th>
						  	</tr>
							<?php
								foreach ( $kasha_sms_short_codes as $kasha_sms_short_code_key => $kasha_sms_short_code_value ) { ?>
									<tr>
									    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><b><?php echo ($kasha_sms_short_code_key); ?></b></td>
									  	<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php
										  	foreach (  $kasha_sms_short_code_value as $kasha_sms_short_code_key_sub => $kasha_sms_short_code_value_sub ) { ?>
											    <?php echo ( $kasha_sms_short_code_value_sub.',' ); ?><?php
							    			}?>
							    		</td>
							    	</tr><?php
								}
								
							?>
						</table></br>
						<label for="message"><b><h2 style="color: #53beac"><?php _e( 'SMS Content', KASHA_SMS_TEXT_DOMAIN );?></h2></b>
						</label>
						</br></br>
						<label for="kasha_remove_number_to_block_list"><b> <h4><?php echo(' Activate / Deactivate');?></h4> </b></label>
						<input type="checkbox" name="kasha_sms_checkbox_actiavate"  <?php echo ( $sms_template_status );?>/><?php _e( 'Enable the checkbox to actiavte/deactivate event.', KASHA_SMS_TEXT_DOMAIN );?> 
						</br></br>
						<?php _e( 'Method to use short code : [shortcode]. It should be wrap with [].' , KASHA_SMS_TEXT_DOMAIN );?>
						</br></br>
						<label for="kasha_remove_number_to_block_list"><b> <h4><?php _e( 'Select Template' , KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>
						<?php $id = 'kasha_template_selection_'.str_replace(' ', '_', strtolower($value));?>
						<select name='kasha_selected_template_for_operational_sms' id='<?php echo $id; ?>' style="width:50%;">
							<?php
								foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
									?><option value="<?php echo( $sms_templates_value->template_id );?>"><?php echo( $sms_templates_value->template_name . ' - '. $sms_templates_value->template_data );?></option><?php
								}
							?>
						</select>
						</br></br>
						<h4><?php _e( 'Prmotional SMS Content', KASHA_SMS_TEXT_DOMAIN ); ?></h4></br>
						<?php
							$find_time = 0;
							$options = '';
							foreach ( $kasha_sms_short_codes as $kasha_sms_short_code_cat ) {
								foreach ( $kasha_sms_short_code_cat as $kasha_sms_short_code_value ) {
									$options .= '<option>'.$kasha_sms_short_code_value.'</option>';
								}
							}
							$matches = array();
							foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
								$search = "/\[([^\]]*)\]/";
								$replace = "<select name='kasha_replace[]'>".$options."</select>";
								$string = $sms_templates_value->template_data;
								$string =  preg_replace( $search, $replace, $string );
								$actual_template = '<div style="color:green">'.$sms_templates_value->template_data.'</div></br></br>'.$string;
								if( $find_time != 0 ){
									echo'<div style="display:none" class="kasha_sms_template_content_cm_class_'.str_replace(' ', '_',strtolower( $value ) ).'" id="'.$sms_templates_value->template_id.'_'.str_replace( ' ', '_', strtolower( $value ) ).'" >'.$actual_template.'</div>';
									echo "</br>";
								}else{
									echo'<div class="kasha_sms_template_content_cm_class_'.str_replace( ' ', '_',strtolower( $value ) ).'" id="'.$sms_templates_value->template_id.'_'.str_replace( ' ', '_',strtolower( $value ) ).'" >'.$actual_template.'</div>';
									echo "</br>";
								}
								$find_time++;
							}
						?>
						</br>
						<h4><?php _e( 'Saved SMS Template', KASHA_SMS_TEXT_DOMAIN );?></h4></br>

						<textarea name="<?php echo($value)?>" style="width: 100%;height: 200px;" value='<?php echo($sms_content_of_operation_event);?>' readonly><?php echo( $sms_content_of_operation_event );?></textarea>
						<button name='kasha_template_update' style="background:#53beac;border-color: #53beac" class="button-primary" type="submit"> <?php _e( 'Update', KASHA_SMS_TEXT_DOMAIN );?> </button>
						</br></br>
					</div></br></br>
				</form><?php
			}
		?>
  </section>
  <section class="kasha_content">
  	<form action="" method="post" enctype="multipart/form-data" onSubmit="return confirm('Are you sure about creating new contact group?') ">
  		<h2 style="color: #53beac"><?php _e( 'Create Contact Groups', KASHA_SMS_TEXT_DOMAIN );?></h2>
  		<h4><?php _e( 'Group Name', KASHA_SMS_TEXT_DOMAIN );?></h4>
  		<input type="text" name="group_name" placeholder="Enter Group Name"/>
		<label for="kasha_remove_number_to_block_list"><b> <h4><?php _e( 'Kasha Users', KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>

		<h4><?php _e( 'WordPress Users', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<select class="chosen_select kasha_all_users_select_filed" name="kasha_promotional_sms[]" multiple="multiple">
			<?php
				foreach ( $all_users as $all_users_key => $all_users_id_name ) {
					$user = get_userdata( $all_users_id_name->ID );
					?><option value="<?php echo( $all_users_id_name->ID );?>"><?php echo( $user->display_name );?></option><?php
				}
			?>
		</select>
		</br>
		<h4><?php _e( 'CSV File Upload', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<input type="file" accept=".csv" name="kasha_csv" />
		</br>
		<h4><?php _e( 'Add Phone Numbers By Using Comma(,) Separator.', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<textarea style='width: 100%;height: 100px;' name='kasha_contact_list_manual'></textarea>
		</br>
		<button class="button-primary" type="submit" name='kasha_create_new_contact_list' value="Submit" style=" background: #53beac;border-color:#53beac"> <?php _e( 'Create', KASHA_SMS_TEXT_DOMAIN );?> </button>
		</br>
	</form>
	<table style="width: 100%;">
			<thead>
				<tr>
					<th><?php _e( 'Group Name', KASHA_SMS_TEXT_DOMAIN ); ?></th>
					<th><?php _e( 'Group Members', KASHA_SMS_TEXT_DOMAIN ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php

				foreach ( $all_created_contact_groups as $all_created_contact_groups_key => $all_created_contact_groups_value ) {?>
					<tr>
					<td><?php echo( $all_created_contact_groups_key );?></td>
					<td>
					<select class="chosen_select kasha_all_users_select_filed" name="kasha_promotional_sms[]" multiple="multiple">
						<?php
							if( !empty( $all_created_contact_groups_value ) ) {
								foreach ( $all_created_contact_groups_value as $all_created_contact_groups_key => $all_users_id_name ) {
									$user = get_userdata( $all_users_id_name);
									if( !empty( $user ) ){
										?><option selected value="<?php echo( $all_users_id_name );?>"><?php echo( $user->display_name );?></option><?php
									}else{
										?><option selected value="<?php echo( $all_users_id_name );?>"><?php echo( $all_users_id_name );?></option><?php
									}
								}
							}
						?>
					</select></td><tr><?php
				}
			?></tr>
			</tbody>
		</table>
  </section>
  <section class="kasha_content">
  	<form action="" method="post" enctype="multipart/form-data" onSubmit="return confirm('Are you sure about sending sms to this group?') ">
	  	<h2><?php _e( 'Prmotional SMS', KASHA_SMS_TEXT_DOMAIN );?></h2>
	  	<label for="kasha_remove_number_to_block_list"><b> <h4><?php _e( 'Select Template', KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>
		<select class="chosen_select kasha_remove_number_to_block_list" name='kasha_selected_template_for_promotional_sms' id='kasha_template_selection'>
			<?php
				foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
					?><option value="<?php echo( $sms_templates_value->template_id );?>"><?php echo( $sms_templates_value->template_name . ' - '. $sms_templates_value->template_data );?></option><?php
				}
			?>
		</select>
		</br></br>
		<h4><?php _e( 'Prmotional SMS Content', KASHA_SMS_TEXT_DOMAIN );?></h4></br>
		<?php
			$find_time = 0;
			$options = '';
			foreach ( $kasha_sms_short_codes as $kasha_sms_short_code_cat ) {
				foreach ( $kasha_sms_short_code_cat as $kasha_sms_short_code_value ) {
					$options .= '<option>'.$kasha_sms_short_code_value.'</option>';
				}
			}
			foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
				$search = "/\[([^\]]*)\]/";
				$replace = "<input type='text' name='kasha_replace[]'></input>";
				$string = $sms_templates_value->template_data;
				$string =  preg_replace($search,$replace,$string);
				//preg_match_all("/\[([^\]]*)\]/", $string, $matches);
				$actual_template = '<div style="color:green">'.$sms_templates_value->template_data.'</div></br></br>'.$string;
				if( $find_time != 0 ){
					echo'<div style="display:none" class="kasha_sms_template_content_cm_class" id="'.$sms_templates_value->template_id.'" >'.$actual_template.'</div>';
					echo "</br>";
				}else{
					echo'<div class="kasha_sms_template_content_cm_class" id="'.$sms_templates_value->template_id.'" >'.$actual_template.'</div>';
					echo "</br>";
				}
				$find_time++;
			}
		?>
		</br>
		<label for="kasha_remove_number_to_block_list"><b> <h4><?php _e( 'Select Contact Group', KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>
		<select class="chosen_select kasha_remove_number_to_block_list" name='promotional_sms_group'>
			<option><?php echo(' ');?></option>
			<?php
				foreach ( $all_created_contact_groups as $black_list_data_key => $black_list_data_number ) {
					?><option><?php echo( $black_list_data_key );?></option><?php
				}
			?>
		</select>
		</br></br>
		<h4><?php _e( 'CSV File Upload', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<input type="file" accept=".csv" name="kasha_promotional_csv" />
		</br>
		<h4><?php _e( 'Add Phone Numbers By Using Comma(,) Separator.', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<textarea style='width: 100%;height: 100px;' name='kasha_promotional_contact_list_manual'></textarea>
		</br>
		<button class="button-primary" style=" background: #53beac;border-color:#53beac" type="submit" name='kasha_send_sms_promotional_type'><?php _e( 'Send SMS', KASHA_SMS_TEXT_DOMAIN ); ?></button>
	</form>
  </section>
  <section>
	<h2><?php _e( 'BlackList', KASHA_SMS_TEXT_DOMAIN );?></h2>
	<form action="" method="post" enctype="multipart/form-data" onSubmit="return confirm('Are you sure about adding this to blacklist?') ">
		<label for="kasha_add_new_number_to_block_list"><b> <h4><?php _e( 'Add to Blacklist', KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>
		 
		</br>
		<h4><?php _e( 'CSV File Upload', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<input type="file" accept=".csv" name="kasha_blacklist_csv" />
		</br>
		<h4><?php _e( 'Add Phone Numbers By Using Comma(,) Separator.', KASHA_SMS_TEXT_DOMAIN );?></h4>
		<textarea style='width: 100%;height: 100px;' name='kasha_blacklist_contact_list_manual'></textarea>
		</br>
		<button class="button-primary" type="submit" value="Submit" name='kasha_blacklist_add' style=" background: #53beac;border-color:#53beac"> Add </button>
		<i><?php _e( 'Add phonenumbers here to append in the blacklist. Do not use char like (+,-,..etc)', KASHA_SMS_TEXT_DOMAIN );?></i>
	</form>
	<form action="" method="post" onSubmit="return confirm('Are you sure about removing these numbers from the blacklist?') ">
		<label for="kasha_remove_number_to_block_list"><b> <h4><?php _e( 'Whitelist Numbers Here', KASHA_SMS_TEXT_DOMAIN );?></h4> </b></label>
		<select class="chosen_select kasha_remove_number_to_block_list" name="kasha_remove_number_to_block_list[]" multiple="multiple">
			<?php
				foreach ( $black_list_data as $black_list_data_key => $black_list_data_number ) {
					?><option><?php echo( $black_list_data_number->phone_number );?></option><?php
				}
			?>
		</select>
		</br></br>
		<button class="button-primary" type="submit" value="Submit" name="white_list_numbers" style=" background: #53beac;border-color:#53beac"> <?php _e( 'Make Whitelist', KASHA_SMS_TEXT_DOMAIN );?> </button>
		</br>
	</form>

  </section>
  <section class="kasha_content">
	<h2><?php _e( 'History', KASHA_SMS_TEXT_DOMAIN );?></h2>
	<div class="container" style="width: 100%;">
      	<div class="header_wrap">
	        <div class="num_rows">
				<div class="form-group">
			 		<select class  ="form-control" name="state" id="maxRows">
						<option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="70">70</option>
						<option value="100">100</option>
	        			<option value="5000"><?php _e( 'Show ALL Rows', KASHA_SMS_TEXT_DOMAIN );?></option>
					</select>
			  	</div>
	        </div>
	        <div class="tb_search">
				<input type="text" id="search_input_all"  placeholder="Search.." class="form-control">
	        </div>
  		</div>
  		</br>
		<table class="table table-striped table-class kasha_history_table" id= "table-id" style="width: 100%;">
			<thead>
				<tr>
					<th><?php _e( 'Message ID', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Submission Date', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Delivery Date', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Cost', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Currency', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Sent Status', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Message Mode', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'Provider', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'SMS Type', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
					<th><?php _e( 'User', KASHA_SMS_TEXT_DOMAIN  ); ?></th>
				</tr>
			  </thead>
			<tbody>
					<?php
						foreach ( $history_data as $history_data_key => $history_data_value ) { ?>
							<tr>
							<td><?php echo( $history_data_value->message_id );?></td>
							<td><?php echo( $history_data_value->submission_date );?></td>
							<td><?php echo( $history_data_value->delivery_date );?></td>
							<td><?php echo( $history_data_value->cost );?></td>
							<td><?php echo( $history_data_value->currency );?></td>
							<td>
								<?php if( $history_data_value->is_sent == 1 ) { ?>
									<span class="dashicons dashicons-yes" style="color: green"></span>
								<?php } else{?>
									<span class="dashicons dashicons-no-alt" style="color: red"></span><?php
								}?>
							</td>
							<td><?php echo( $history_data_value->is_auto );?></td>
							<td><?php echo( $history_data_value->provider );?></td>
							<td><?php echo( $history_data_value->sms_type->name );?></td>
							<td><?php echo( $history_data_value->user->username );?></td>
							</tr><?php
						}
					?>
			<tbody>
		</table>
		<div class='pagination-container'>
			<nav>
			  <ul class="pagination">
			  </ul>
			</nav>
		</div>
		<div class="rows_count"></div>
	</div>
</section>
</div>