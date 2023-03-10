<?php

class Batch_Create_Network_Main_Menu extends Origin_Admin_Page {
 	
 	public function __construct( $slug, $capability, $args ) {
 		parent::__construct( $slug, $capability, $args );

 		add_action( 'admin_init', array( &$this, 'process_form' ) );
 		add_action( 'batch_create_instructions', array( $this, 'render_instructions' ), 5 );
 	}

	public function render_content() {
		if ( 'upload' == $this->get_current_tab() ) {

			$this->display_notices();

			$this->show_process_queue_notice();

			$creator = batch_create_get_creator();
			$old_files = $creator->get_old_sources();

			if ( $old_files ) {
				$clear_link = add_query_arg(
					'action',
					'clear_old_sources',
					$this->get_permalink()
				);

				$message = sprintf( __( 'You have %s old source file(s) stored on your system. These are no longer needed. <a href="%s">Delete them now</a>', INCSUB_BATCH_CREATE_LANG_DOMAIN ), count( $old_files ), $clear_link );
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( $message );
			}

			$test_csv_url = INCSUB_BATCH_CREATE_PLUGIN_URL . 'inc/template.csv'; 

			$form_url = add_query_arg(
				'action',
				'process',
				$this->get_permalink()
			);
			?>
	
			<?php do_action( 'batch_create_instructions' ); ?>

			<h3><?php _e( 'Upload file', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></h3>
			<form action="<?php echo esc_url( $form_url ); ?>" method="post" enctype="multipart/form-data">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="csv_file"><?php _e( 'Select a file', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></label></th>
						<td>
							<input type="file" name="csv_file" id="csv_file" size="20" />
	 					</td>
					</tr>
				</table>

				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="header_row_yn"><?php _e('This file has a header row', INCSUB_BATCH_CREATE_LANG_DOMAIN );?></label></th>
						<td>
							<input type="checkbox" name="header_row_yn" id="header_row_yn" value="0" checked/> 
							<span class="description"><?php _e( 'If this box remains checked, the first row in the file will not be processed.', INCSUB_BATCH_CREATE_LANG_DOMAIN );?></span>
	 					</td>
					</tr>
				</table>

				<?php do_action( 'batch_create_upload_fields' ); ?>
					  
				<?php wp_nonce_field( 'upload_batch_file' ); ?>
				<?php submit_button( __( 'Upload', INCSUB_BATCH_CREATE_LANG_DOMAIN ) ); ?>
			</form>

			<style type="text/css">
				table.batch-create-sample-table {
					margin:0px;padding:0px;
					width:80%;
					border:1px solid #000000;
					border-collapse: collapse;
				        border-spacing: 0;
					width:80%;
					height:100%;
					margin:0px;padding:0px;
					table-layout: fixed;
					margin-bottom:20px;
					float:left;
				}

				.batch-create-table-character {
					box-sizing: border-box;
					width: 36px;
				    height: 36px;
				    padding: 7px;
					float:left;
					text-align: center;
					font-size:15px;
					font-weight: bold;
					color:#fff;
					margin-right:30px;
					-webkit-border-radius:50%;
					-moz-border-radius:50%;
					border-radius:50%;
					border:1px solid #0074a2;
					background:#2ea2cc;
				}

				.batch-create-sample-table tr td:first-child {
					width:3%;
				}

				.batch-create-sample-table tr:first-child {
					/* height: 10px; */
					line-height: 0.3em;
				}

				.batch-create-sample-table td{
					vertical-align:middle;
					
					background-color:#e5e5e5;

					border:1px solid #000000;
					text-align:center;
					padding:7px;
					font-weight:normal;
					color:#000000;
				}


				.batch-create-sample-table tr:last-child td{
					border-width:0px 1px 0px 0px;
				}
				.batch-create-sample-table tr td:last-child{
					border-width:0px 0px 1px 0px;
				}
				.batch-create-sample-table tr:last-child td:last-child{
					border-width:0px 0px 0px 0px;
				}
				.batch-create-sample-table th {
					background-color:#aaaaff;
					border:0px solid #000000;
					text-align:center;
					border-width:0px 0px 1px 1px;
					font-weight:bold;
					color:#000000;
				}
				.batch-create-sample-table tr th:first-child {
					background:#e5e5e5;
					font-weight: normal;
				}
				.batch-create-sample-table tr:first-child td:first-child{
					border-width:0px 0px 1px 0px;
				}
				.batch-create-sample-table tr:first-child td:last-child{
					border-width:0px 0px 1px 1px;
				}
				.batch-create-table-title {
					margin-top:3em;
				}
			</style>
			<?php
		}

		if ( 'log-file' == $this->get_current_tab() ) {
			$creator = batch_create_get_creator();

			$log_file = $creator->get_log_content();

			$log_file = $log_file ? $log_file : '<p>' . __( 'The log is empty', INCSUB_BATCH_CREATE_LANG_DOMAIN ) . '</p>';

			$form_url = add_query_arg(
				array(
					'action' => 'batch-create-delete-log-file',
					'tab' => 'log-file'
				),
				$this->get_permalink()
			);
			?>
				<form action="<?php echo esc_url( $form_url ); ?>" method="post" >
					<pre style="width:96%;border:1px solid #DEDEDE;padding:2%;"><?php echo $log_file; ?></pre>
					<?php wp_nonce_field( 'batch-create-delete-log-file' ); ?>
					<?php submit_button( __( 'Delete log file', INCSUB_BATCH_CREATE_LANG_DOMAIN ) ); ?>
				</form>
			<?php
		}

		if ( 'queue' == $this->get_current_tab() ) {
			$table = new Batch_Create_Queue_Table();
			$table->prepare_items();

			$this->show_process_queue_notice();

			?><form action="" method="post" ><?php
				$table->display();
			?></form><?php
		}

		
	}

	private function sample_table( $domain, $blogname, $username, $password, $email, $role, $character ) {
		?>
			<div class="batch-create-table-character">
				<?php echo $character; ?>
			</div>
			<table class="batch-create-sample-table">
				<tr>
					<td></td>
					<td>A</td>
					<td>B</td>
					<td>C</td>
					<td>D</td>
					<td>E</td>
					<td>F</td>
				</tr>
				<tr>
					<th>1</th>
					<th><?php _e( 'Blog Domain/Blog ID', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
					<th><?php _e( 'Blog Name', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
					<th><?php _e( 'Username', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
					<th><?php _e( 'User Password', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
					<th><?php _e( 'User Email', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
					<th><?php _e( 'User Role', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></th>
				</tr>
				<tr>
					<td>2</td>
					<td><?php echo $domain; ?></td>
					<td><?php echo $blogname; ?></td>
					<td><?php echo $username; ?></td>
					<td><?php echo $password; ?></td>
					<td><?php echo $email; ?></td>
					<td><?php echo $role; ?></td>
				</tr>
				<tr></tr>
			</table>
			<div class="clear"></div>
			
		<?php
	}

	public function render_instructions() {
		$test_csv_url = INCSUB_BATCH_CREATE_PLUGIN_URL . '/inc/template.csv';
		?>
			<h3><?php _e( 'Instructions', INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></h3>
			<p><?php _e( "Batch create is designed for quickly creating sites and/or usernames or adding users to an existing site in batches by uploading a CSV file.", INCSUB_BATCH_CREATE_LANG_DOMAIN ); ?></p>
			<p><?php _e( "Note that Batch Create will not send welcome emails to users who are added to sites, but Super Admins may still receive site creation and user registration notification emails." ); ?></p>

			<ol>
				<li><?php printf( __( 'Download <a href="%s">this .csv</a> file and use it as a template to create your batch file.'), $test_csv_url ); ?></li>
				<li><?php _e( "Add sites, username, emails, and roles to the file."); ?></li>
				<li><?php _e( "Keep 'Blog Title' empty unless you want to automatically create new sites when the 'URL/Blog ID' field doesn't match anything."); ?></li>
				<li><?php _e( "Keep 'Password' empty to automatically generate a secure password."); ?></li>
				<li><?php _e( "Upoad the file and check out the queue from the Current queue page. Start the queue when you are ready."); ?></li>
				<li><?php _e( "You'll see a status bar displaying their progress as they're being created/added."); ?></li>
		<?php
	}

	private function show_process_queue_notice() {
		$model = batch_create_get_model();
		$tmp_queue_count = $model->count_queue_items();

		if ( $tmp_queue_count > 0 ) {

			$proccess_link = add_query_arg(
				'action',
				'loop',
				$this->get_permalink()
			);

			$clear_link = add_query_arg(
				'action',
				'clear',
				$this->get_permalink()
			);

			$message = sprintf( 
				__( '<strong>Note:</strong> There are %d items (blogs/users) waiting to be processed. Click <a class="button-secondary" href="%s">here</a> to process the queue. If there is a problem, you can clear the queue by clicking <a href="%s">here</a>.', INCSUB_BATCH_CREATE_LANG_DOMAIN ), 
				$tmp_queue_count, 
				$proccess_link,
				$clear_link
			);

			Incsub_Batch_Create_Errors_Handler::show_updated_notice( $message, 'processing_result' );
		}
	}

	private function display_notices() {
		if ( isset( $_GET['page'] ) && $this->get_menu_slug() == $_GET['page'] ) {
			Incsub_Batch_Create_Errors_Handler::show_errors_notice();

			if ( isset( $_GET['uploaded'] ) )
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( __( 'Items added to queue.', INCSUB_BATCH_CREATE_LANG_DOMAIN ) );
			
			if ( isset( $_GET['queue_cleared'] ) )
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( __( 'Queue cleared.', INCSUB_BATCH_CREATE_LANG_DOMAIN ) );
			
			if ( isset( $_GET['old_cleared'] ) )
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( __( 'Old sources deleted', INCSUB_BATCH_CREATE_LANG_DOMAIN ) );

			if ( isset( $_GET['log_cleared'] ) )
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( __( 'Log file cleared', INCSUB_BATCH_CREATE_LANG_DOMAIN ) );

			if ( isset( $_GET['queue_updated'] ) ) {
				$log_link = add_query_arg('tab', 'log-file', $this->get_permalink() );
				Incsub_Batch_Create_Errors_Handler::show_updated_notice( sprintf( __( 'Queue processing complete. <a href="%s">See log file.</a>', INCSUB_BATCH_CREATE_LANG_DOMAIN ), $log_link ) );
			}
				
		}

		
	}

	public function process_form() {
		if ( isset( $_GET['page'] ) && $this->get_menu_slug() == $_GET['page'] ) {
			
			if ( isset( $_GET['action'] ) && 'process' == $_GET['action'] && isset( $_POST['submit'] ) ) {
				// Uploading file
				if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'upload_batch_file' ) || ! current_user_can( $this->get_capability() ) )
					return;	

				$header_row = ! isset( $_POST['header_row_yn'] );

				$creator = batch_create_get_creator();
				$done = $creator->process_file( $_FILES['csv_file'], $header_row, true );

				if ( ! $done )
					return;

				$redirect_to = add_query_arg(
					'uploaded',
					'true',
					$this->get_permalink()
				);

				wp_redirect( $redirect_to );
					
			}
			if ( isset( $_GET['action'] ) && 'clear' == $_GET['action'] ) {
				$model = batch_create_get_model();
				$model->clear_queue();

				$redirect_to = add_query_arg(
					'queue_cleared',
					'true',
					$this->get_permalink()
				);

				wp_redirect( $redirect_to );
			}
			if ( isset( $_GET['action'] ) && 'clear_old_sources' == $_GET['action'] ) {
				$creator = batch_create_get_creator();

				$olds = $creator->get_old_sources();
				foreach ( $olds as $old ) {
					@unlink($old);
				}

				$redirect_to = add_query_arg(
					'old_cleared',
					'true',
					$this->get_permalink()
				);

				wp_redirect( $redirect_to );
			}
			if ( isset( $_GET['action'] ) && 'loop' == $_GET['action'] ) {
				$creator = batch_create_get_creator();
				$creator->process_queue();
			}

			if ( isset( $_GET['action'] ) && 'batch-create-delete-log-file' == $_GET['action'] ) {

				if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'batch-create-delete-log-file' ) || ! current_user_can( $this->get_capability() ) )
					return;	

				$creator = batch_create_get_creator();
				$creator->clear_log();

				$redirect_to = add_query_arg(
					array(
						'log_cleared' => 'true',
						'tab' => 'log-file'
					),
					$this->get_permalink()
				);

				wp_redirect( $redirect_to );
			}
			

		}
	}

}

