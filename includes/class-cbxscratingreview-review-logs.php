<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}


	class CBXSCRatingReviewLog_List_Table extends WP_List_Table {

		/**
		 * The current list of all branches.
		 *
		 * @since  3.1.0
		 * @access public
		 * @var array
		 */
		function __construct() {

			//Set parent defaults
			parent::__construct( array(
				'singular' => 'cbxscratingreviewreviewlog',     //singular name of the listed records
				'plural'   => 'cbxscratingreviewreviewlogs',    //plural name of the listed records
				'ajax'     => false,      //does this table support ajax?
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
			) );

		}

		/**
		 * Callback for column 'id'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_id( $item ) {
			return '<a href="' . admin_url( 'admin.php?page=cbxscratingreviewreviewlist&view=view&id=' . $item['id'] ) . '" title="' . esc_html__( 'View Review', 'cbxscratingreview' ) . '">' . $item['id'] . '</a>' . ' (<a target="_blank" href="' . admin_url( 'admin.php?page=cbxscratingreviewreviewlist&view=addedit&id=' . $item['id'] ) . '" title="' . esc_html__( 'Edit Review', 'cbxscratingreview' ) . '">' . esc_html__( 'Edit', 'cbxscratingreview' ) . '</a>)';
		}


		/**
		 * Callback for column 'Post'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_post_id( $item ) {
			$post_id    = intval( $item['post_id'] );
			$post_title = get_the_title( intval( $post_id ) );
			$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxscratingreview' ) : $post_title;
			$edit_link  = '<a target="_blank" href="' . get_permalink( $post_id ) . '">' . esc_html( $post_title ) . '</a>';

			$edit_url = esc_url( get_edit_post_link( $post_id ) );
			if ( ! is_null( $edit_url ) ) {
				$edit_link .= ' - <a target="_blank" href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxscratingreview' ) . '">' . $post_id . '</a>';
			}

			return $edit_link;
		}//end column_post_id

		/**
		 * Callback for column 'User'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_user_id( $item ) {
			$user_id           = absint( $item['user_id'] );
			$user_display_name = $item['display_name'];
			$user_html         = '';


			if ( current_user_can( 'edit_user', $user_id ) ) {
				$user_html = '<a href="' . get_edit_user_link( $user_id ) . '" target="_blank" title="' . esc_html__( 'Edit User', 'cbxscratingreview' ) . '">' . esc_html( $user_display_name ) . '</a>';
			}

			return $user_html;
		}//end column_user_id

		/**
		 * Callback for column 'Rating'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_score( $item ) {
			return number_format_i18n( floatval( $item['score'] ), 2 );
		}//end column_score

		/**
		 * Callback for column 'post_type'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_post_type( $item ) {
			return esc_attr( $item['post_type'] );
		}//end column_post_type


		/**
		 * Callback for column 'Headline'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_headline( $item ) {
			$headline = wp_unslash( $item['headline'] );
			if ( strlen( $headline ) > 25 ) {
				$headline = substr( $headline, 0, 25 ) . '...';
			}

			return $headline;
		}//end column_headline

		/**
		 * Callback for column 'Comment'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_comment( $item ) {
			$headline = wp_unslash( $item['headline'] );
			$comment  = wpautop( wp_unslash( $item['comment'] ) );


			$comment_short = '';
			if ( strlen( strip_tags( $comment ) ) > 25 ) {
				$comment_short = substr( strip_tags( $comment ), 0, 25 ) . '...';
			} else {
				$comment_short = substr( strip_tags( $comment ), 0, 25 );
			}


			wp_add_inline_script( 'cbxscratingreview-admin',
				'
				
				var reviews_arr= cbxscratingreview_admin.reviews_arr;
				reviews_arr[' . $item['id'] . '] = ' . json_encode( $comment ) . ';
				cbxscratingreview_admin.reviews_arr = reviews_arr;							
				'
			);

			return $comment_short . '<a data-headline="' . sprintf( esc_html__( 'Review Heading: %s', 'cbxscratingreview' ), esc_html( $headline ) ) . '" data-reviewid="' . $item['id'] . '" href="#" class="cbxscratingreview_review_text_expand">' . esc_html__( '(details)', 'cbxscratingreview' ) . '</a>';
		}//end column_comment


		/**
		 * Callback for column 'Review Date'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		/*function column_review_date( $item ) {
			$review_date = '';
			if ( $item['review_date'] != '' ) {
				$review_date = CBXSCRatingReviewHelper::dateReadableFormat( wp_unslash( $item['review_date'] ), 'M j, Y H:i' );
			}

			return $review_date;
		}*/

		/**
		 * Callback for column 'Date Created'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_date_created( $item ) {
			$date_created = '';
			if ( $item['date_created'] != '' ) {
				$date_created = CBXSCRatingReviewHelper::dateReadableFormat( wp_unslash( $item['date_created'] ) );
			}

			return $date_created;
		}//end column_date_created

		/**
		 * Callback for column 'Location'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		/*	function column_location( $item ) {
				return $location = wp_unslash( $item['location'] );
			}*/


		/**
		 * Callback for column 'Review location lat'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		/*function column_lat( $item ) {

			return $item['lat'];
		}*/

		/**
		 * Callback for column 'Review location lon'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		/*function column_lon( $item ) {

			return $item['lon'];
		}*/


		/**
		 * Callback for column 'Status'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_status( $item ) {
			$status_key = $item['status'];

			$exprev_status_arr = CBXSCRatingReviewHelper::ReviewStatusOptions();

			$review_status = '';
			if ( isset( $exprev_status_arr[ $status_key ] ) ) {
				$review_status = $exprev_status_arr[ $status_key ];
			}

			return wp_unslash( $review_status );
		}//end column_default


		function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				/*$1%s*/
				$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
				/*$2%s*/
				$item['id']                //The value of the checkbox should be the record's id
			);
		}//end column_cb

		function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'id':
					return $item[ $column_name ];
				case 'post_id':
					return $item[ $column_name ];
				case 'user_id':
					return $item[ $column_name ];
				case 'score':
					return $item[ $column_name ];
				case 'post_type':
					return $item[ $column_name ];
				case 'headline':
					return $item[ $column_name ];
				case 'comment':
					return $item[ $column_name ];
				/*case 'review_date':
					return $item[ $column_name ];*/
				case 'date_created':
					return $item[ $column_name ];
				/*case 'location':
					return $item[ $column_name ];
				case 'lat':
					return $item[ $column_name ];
				case 'lon':
					return $item[ $column_name ];*/
				case 'status':
					return $item[ $column_name ];
				default:
					//return print_r( $item, true ); //Show the whole array for troubleshooting purposes
					echo apply_filters( 'cbxscratingreview_admin_log_listing_column_default', $item, $column_name );
			}
		}//end column_default

		function get_columns() {
			$columns = array(
				'cb'           => '<input type="checkbox" />', //Render a checkbox instead of text
				'id'           => esc_html__( 'ID', 'cbxscratingreview' ),
				'post_id'      => esc_html__( 'Post', 'cbxscratingreview' ),
				'user_id'      => esc_html__( 'User', 'cbxscratingreview' ),
				'score'        => esc_html__( 'Score', 'cbxscratingreview' ),
				'post_type'    => esc_html__( 'Post Type', 'cbxscratingreview' ),
				'headline'     => esc_html__( 'Headline', 'cbxscratingreview' ),
				'comment'      => esc_html__( 'Comment', 'cbxscratingreview' ),
				//'review_date'  => esc_html__( 'Exp. Date', 'cbxscratingreview' ),
				'date_created' => esc_html__( 'Created', 'cbxscratingreview' ),
				//'location'     => esc_html__( 'Location', 'cbxscratingreview' ),
				//'lat'          => esc_html__( 'Lat', 'cbxscratingreview' ),
				//'lon'          => esc_html__( 'Long', 'cbxscratingreview' ),
				'status'       => esc_html__( 'Status', 'cbxscratingreview' )
			);

			return apply_filters( 'cbxscratingreview_admin_log_listing_columns', $columns );
		}//end get_columns


		function get_sortable_columns() {
			$sortable_columns = array(
				'id'           => array( 'logs.id', false ), //true means it's already sorted
				'post_id'      => array( 'logs.post_id', false ),
				'user_id'      => array( 'logs.user_id', false ),
				'score'        => array( 'logs.score', false ),
				'post_type'    => array( 'logs.post_type', false ),
				'headline'     => array( 'logs.headline', false ),
				//'comment'     => array( 'comment', false ),
				//'review_date' => array( 'logs.review_date', false ),
				'date_created' => array( 'logs.date_created', false ),
				//'location'    => array( 'logs.location', false ),
				//'lat'         => array( 'logs.lat', false ),
				//'lon'         => array( 'logs.lon', false ),
				'status'       => array( 'logs.status', false ),
			);

			return apply_filters( 'cbxscratingreview_admin_log_listing_sortable_columns', $sortable_columns );
		}//end get_sortable_columns


		/**
		 * Bulk actions
		 */
		function get_bulk_actions() {
			$status_arr           = CBXSCRatingReviewHelper::ReviewStatusOptions();
			$status_arr['delete'] = esc_html__( 'Delete', 'cbxscratingreview' );

			$bulk_actions = apply_filters( 'cbxscratingreview_review_bulk_action', $status_arr );

			return $bulk_actions;
		}//end get_bulk_actions

		/**
		 * Process bulk action
		 */
		function process_bulk_action() {

			$new_status = $this->current_action();

			if ( $new_status == - 1 ) {
				return;
			}


			//Detect when a bulk action is being triggered...
			if ( ! empty( $_REQUEST['cbxscratingreviewreviewlog'] ) ) {
				global $wpdb;
				$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';


				$results = $_REQUEST['cbxscratingreviewreviewlog'];
				foreach ( $results as $id ) {

					$id = intval( $id );

					$review_info = cbxscratingreview_singleReview( $id );


					if ( 'delete' === $new_status ) {
						do_action( 'cbxscratingreview_review_delete_before', $review_info );

						$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxscratingreview_review WHERE id=%d", $id ) );

						if ( $delete_status !== false ) {
							do_action( 'cbxscratingreview_review_delete_after', $review_info );
						}
					} else {
						if ( is_numeric( $new_status ) && $new_status >= 0 ) {

							$old_status = $review_info['status'];

							if ( $old_status !== $new_status ) {
								$update_status = $wpdb->update(
									$table_cbxscratingreview_review,
									array(
										'status'        => intval( $new_status ),
										'mod_by'        => intval( get_current_user_id() ),
										'date_modified' => current_time( 'mysql' )
									),
									array( 'id' => $id ),
									array(
										'%d',
										'%d',
										'%s'
									),
									array( '%d' )
								);

								if ( $update_status !== false ) {
									if ( $new_status == 1 ) {
										do_action( 'cbxscratingreview_review_publish', $review_info );
									} else {
										do_action( 'cbxscratingreview_review_unpublish', $review_info );
									}

									do_action( 'cbxscratingreview_review_status_change', $old_status, $new_status, $review_info );
								}//if db update success

							}//if status changed

						}//if status related action
					}//if not delete action


				}
			}

			return;
		}


		/**
		 * Prepare the review log items
		 */
		function prepare_items() {
			global $wpdb; //This is used only if making any database queries

			$user   = get_current_user_id();
			$screen = get_current_screen();

			$current_page = $this->get_pagenum();

			$option_name = $screen->get_option( 'per_page', 'option' ); //the core class name is WP_Screen

			$per_page = intval( get_user_meta( $user, $option_name, true ) );

			if ( $per_page == 0 ) {
				$per_page = intval( $screen->get_option( 'per_page', 'default' ) );
			}


			$columns  = $this->get_columns();
			$hidden   = get_hidden_columns( $this->screen );
			$sortable = $this->get_sortable_columns();


			$this->_column_headers = array( $columns, $hidden, $sortable );


			$this->process_bulk_action();

			$search  = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
			$id      = ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != 0 ) ? intval( $_REQUEST['id'] ) : 0;
			$post_id = ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] != 0 ) ? intval( $_REQUEST['post_id'] ) : 0;
			$user_id = ( isset( $_REQUEST['user_id'] ) && $_REQUEST['user_id'] != 0 ) ? intval( $_REQUEST['user_id'] ) : 0;
			$order   = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? $_REQUEST['order'] : 'DESC';
			$orderby = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? $_REQUEST['orderby'] : 'logs.id';

			$status = isset( $_REQUEST['status'] ) ? wp_unslash( $_REQUEST['status'] ) : 'all';
			$data   = $this->getLogData( $search, $status, $post_id, $user_id, $id, $orderby, $order, $per_page, $current_page );

			$total_items = intval( $this->getLogDataCount( $search, $status, $post_id, $user_id, $id ) );

			$this->items = $data;

			/**
			 * REQUIRED. We also have to register our pagination options & calculations.
			 */
			$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil( $total_items / $per_page )   //WE have to calculate the total number of pages
			) );

		}

		/**
		 * Get review logs data
		 *
		 * @param string $search
		 * @param string $status
		 * @param int    $post_id
		 * @param int    $user_id
		 * @param int    $id
		 * @param string $orderby
		 * @param string $order
		 * @param int    $perpage
		 * @param int    $page
		 *
		 * @return array|null|object
		 */
		public function getLogData( $search = '', $status = 'all', $post_id = 0, $user_id = 0, $id = 0, $orderby = 'logs.id', $order = 'DESC', $perpage = 20, $page = 1 ) {

			global $wpdb;

			$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';
			$table_users                    = $wpdb->prefix . 'users';

			$sql_select = "logs.*, users.user_email, users.display_name";

			$sql_select = apply_filters( 'cbxscratingreview_admin_log_listing_select', $sql_select, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

			$join = $where_sql = '';

			$join = " LEFT JOIN $table_users AS users ON users.ID = logs.user_id ";

			$join = apply_filters( 'cbxscratingreview_admin_log_listing_join', $join, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

			if ( $search != '' ) {
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				//$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%' OR logs.location LIKE '%%%s%%'", $search, $search, $search );
				$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%'", $search, $search );
			}

			if ( $status !== 'all' ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.status=%s', $status );
			}

			if ( $post_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.post_id=%d', $post_id );
			}

			if ( $user_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', $user_id );
			}

			$where_sql = apply_filters( 'cbxscratingreview_admin_log_listing_where', $where_sql, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

			if ( $where_sql == '' ) {
				$where_sql = '1';
			}

			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$sortingOrder = " ORDER BY $orderby $order ";

			$data = $wpdb->get_results( "SELECT $sql_select FROM $table_cbxscratingreview_review as logs $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );

			return $data;
		}

		/**
		 * Review logs data count
		 *
		 * @param string $search
		 * @param string $status
		 * @param int    $post_id
		 * @param int    $user_id
		 * @param int    $id
		 *
		 * @return null|string
		 */
		public function getLogDataCount( $search = '', $status = 'all', $post_id = 0, $user_id = 0, $id = 0 ) {

			global $wpdb;
			$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';

			$sql_select = "SELECT COUNT(*) FROM $table_cbxscratingreview_review as logs";

			$join = $where_sql = '';

			$join = apply_filters( 'cbxscratingreview_admin_log_listing_join_total', $join, $search, $status, $post_id, $user_id, $id );

			if ( $search != '' ) {
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				//$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%' OR logs.location LIKE '%%%s%%'", $search, $search, $search );
				$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%'", $search, $search );
			}

			if ( $status != 'all' ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.status=%s', $status );
			}

			if ( $post_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.post_id=%d', $post_id );
			}

			if ( $user_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', $user_id );
			}

			$where_sql = apply_filters( 'cbxscratingreview_admin_log_listing_where_total', $where_sql, $search, $status, $post_id, $user_id, $id );

			if ( $where_sql == '' ) {
				$where_sql = '1';
			}


			$count = $wpdb->get_var( "$sql_select $join  WHERE  $where_sql" );

			return $count;
		}


		/**
		 * Generates content for a single row of the table
		 *
		 * @param object $item The current item
		 *
		 * @since  3.1.0
		 * @access public
		 *
		 */
		public function single_row( $item ) {
			$row_class = 'cbxscratingreview_row';
			$row_class = apply_filters( 'cbxscratingreview_row_class', $row_class, $item );
			echo '<tr id="cbxscratingreview_row_' . $item['id'] . '" class="' . $row_class . '">';
			$this->single_row_columns( $item );
			echo '</tr>';
		}

		/**
		 * Message to be displayed when there are no items
		 *
		 * @since  3.1.0
		 * @access public
		 */
		public function no_items() {
			echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No review found. Please change your search criteria for better result.', 'cbxscratingreview' ) . '</p></div>';
		}

		/**
		 * Pagination
		 *
		 * @param string $which
		 */
		protected function pagination( $which ) {

			if ( empty( $this->_pagination_args ) ) {
				return;
			}

			$total_items     = $this->_pagination_args['total_items'];
			$total_pages     = $this->_pagination_args['total_pages'];
			$infinite_scroll = false;
			if ( isset( $this->_pagination_args['infinite_scroll'] ) ) {
				$infinite_scroll = $this->_pagination_args['infinite_scroll'];
			}

			if ( 'top' === $which && $total_pages > 1 ) {
				$this->screen->render_screen_reader_content( 'heading_pagination' );
			}

			$output = '<span class="displaying-num">' . sprintf( _n( '%s item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';

			$current              = $this->get_pagenum();
			$removable_query_args = wp_removable_query_args();

			$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

			$current_url = remove_query_arg( $removable_query_args, $current_url );

			$page_links = array();

			$total_pages_before = '<span class="paging-input">';
			$total_pages_after  = '</span></span>';

			$disable_first = $disable_last = $disable_prev = $disable_next = false;

			if ( $current == 1 ) {
				$disable_first = true;
				$disable_prev  = true;
			}
			if ( $current == 2 ) {
				$disable_first = true;
			}
			if ( $current == $total_pages ) {
				$disable_last = true;
				$disable_next = true;
			}
			if ( $current == $total_pages - 1 ) {
				$disable_last = true;
			}

			$pagination_params = array();

			$search = isset( $_REQUEST['s'] ) ? esc_attr( wp_unslash( $_REQUEST['s'] ) ) : '';
			//$logdate = ( isset( $_REQUEST['logdate'] ) && $_REQUEST['logdate'] != '' ) ? sanitize_text_field( $_REQUEST['logdate'] ) : '';

			if ( $search != '' ) {
				$pagination_params['s'] = $search;
			}

			/*if ($logdate != '') {
				$pagination_params['logdate'] = $logdate;
			}*/


			$pagination_params = apply_filters( 'cbxscratingreview_pagination_log_params', $pagination_params );

			if ( $disable_first ) {
				$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>';
			} else {
				$page_links[] = sprintf( "<a class='first-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( remove_query_arg( 'paged', $current_url ) ),
					__( 'First page' ),
					'&laquo;'
				);
			}

			if ( $disable_prev ) {
				$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';
			} else {
				$pagination_params['paged'] = max( 1, $current - 1 );

				$page_links[] = sprintf( "<a class='prev-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( $pagination_params, $current_url ) ),
					__( 'Previous page' ),
					'&lsaquo;'
				);
			}

			if ( 'bottom' === $which ) {
				$html_current_page  = $current;
				$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
			} else {
				$html_current_page = sprintf( "%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
					'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
					$current,
					strlen( $total_pages )
				);
			}
			$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
			$page_links[]     = $total_pages_before . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . $total_pages_after;

			if ( $disable_next ) {
				$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>';
			} else {
				$pagination_params['paged'] = min( $total_pages, $current + 1 );

				$page_links[] = sprintf( "<a class='next-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( $pagination_params, $current_url ) ),
					__( 'Next page' ),
					'&rsaquo;'
				);
			}

			if ( $disable_last ) {
				$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';
			} else {
				$pagination_params['paged'] = $total_pages;

				$page_links[] = sprintf( "<a class='last-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( $pagination_params, $current_url ) ),
					__( 'Last page' ),
					'&raquo;'
				);
			}

			$pagination_links_class = 'pagination-links';
			if ( ! empty( $infinite_scroll ) ) {
				$pagination_links_class = ' hide-if-js';
			}
			$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

			if ( $total_pages ) {
				$page_class = $total_pages < 2 ? ' one-page' : '';
			} else {
				//$page_class = ' no-pages';
				$page_class = ' ';
			}
			$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

			echo $this->_pagination;
		}
	}//end class CBXSCRatingReviewLog_List_Table