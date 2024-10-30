<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}


	class CBXSCRatingReviewRatingAvgLog_List_Table extends WP_List_Table {

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
				'singular' => 'cbxscratingreviewratingavglog',     //singular name of the listed records
				'plural'   => 'cbxscratingreviewratingavglogs',    //plural name of the listed records
				'ajax'     => false,      //does this table support ajax?
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
			) );
		}

		/**
		 * Callback for column 'Post'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_post_id( $item ) {
			$edit_link = $item['post_id'];

			$edit_url = esc_url( get_edit_post_link( $item['post_id'] ) );
			if ( ! is_null( $edit_url ) ) {
				$edit_link .= '<a href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxscratingreview' ) . '">' . esc_html__( ' (Edit)', 'cbxscratingreview' ) . '</a>';
			}

			return $edit_link;
		}

		/**
		 * Callback for column 'post_title'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_post_title( $item ) {
			$post_id = intval( $item['post_id'] );

			$post_url = esc_url( get_permalink( $post_id ) );

			if ( ! is_null( $post_url ) ) {
				$post_url = '<a target="_blank" href="' . $post_url . '" target="_blank" title="' . esc_html__( 'Visit Post', 'cbxscratingreview' ) . '">' . get_the_title( $post_id ) . '</a>';
			}

			return $post_url;
		}


		/**
		 * Callback for column 'Avg Rating'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_avg_rating( $item ) {

			return number_format_i18n( floatval( $item['avg_rating'] ), 2 );
		}

		/**
		 * Callback for column 'Total'
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_total_count( $item ) {

			$post_id = intval( $item['post_id'] );

			return intval( $item['total_count'] ) . ' - <a target="_blank" href="' . esc_url( add_query_arg( 'post_id', $post_id, admin_url( 'admin.php?page=cbxscratingreviewreviewlist' ) ) ) . '">' . esc_html__( 'View All', 'cbxscratingreview' ) . '</a>';
		}


		function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				/*$1%s*/
				$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
				/*$2%s*/
				$item['id']                //The value of the checkbox should be the record's id
			);
		}

		function column_default( $item, $column_name ) {

			switch ( $column_name ) {
				case 'id':
					return $item[ $column_name ];
				case 'post_id':
					return $item[ $column_name ];
				case 'post_title':
					return $item[ $column_name ];
				case 'avg_rating':
					return $item[ $column_name ];
				case 'total_count':
					return $item[ $column_name ];
				case 'date_created':
					return $item[ $column_name ];
				case 'date_modified':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ); //Show the whole array for troubleshooting purposes
			}
		}

		function get_columns() {
			$columns = array(
				'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
				'id'            => esc_html__( 'ID', 'cbxscratingreview' ),
				'post_id'       => esc_html__( 'Post', 'cbxscratingreview' ),
				'post_title'    => esc_html__( 'Post Title', 'cbxscratingreview' ),
				'avg_rating'    => esc_html__( 'Average Rating', 'cbxscratingreview' ),
				'total_count'   => esc_html__( 'Total', 'cbxscratingreview' ),
				'date_created'  => esc_html__( 'Created', 'cbxscratingreview' ),
				'date_modified' => esc_html__( 'Modified', 'cbxscratingreview' ),
			);

			return apply_filters( 'cbxscratingreview_admin_avglog_listing_columns', $columns );
		}

		/**
		 * Sortable columns
		 *
		 * @return array|mixed|void
		 */
		function get_sortable_columns() {
			$sortable_columns = array(
				'id'            => array( 'logs.id', false ), //true means it's already sorted
				'post_id'       => array( 'logs.post_id', false ),
				'avg_rating'    => array( 'logs.avg_rating', false ),
				'total_count'   => array( 'logs.total_count', false ),
				'date_created'  => array( 'logs.date_created', false ),
				'date_modified' => array( 'logs.date_modified', false ),
			);

			return apply_filters( 'cbxscratingreview_admin_avglog_listing_sortable_columns', $sortable_columns );
		}

		function get_bulk_actions() {
			$avg_bulk_arr['delete'] = esc_html__( 'Delete', 'cbxscratingreview' );

			$bulk_actions = apply_filters( 'cbxscratingreview_review_avg_bulk_action', $avg_bulk_arr );

			return $bulk_actions;
		}


		function process_bulk_action() {

			$new_status = $this->current_action();

			if ( 'delete' === $new_status ) {
				//Detect when a bulk action is being triggered...
				if ( ! empty( $_REQUEST['cbxscratingreviewratingavglog'] ) ) {
					global $wpdb;
					$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';


					$results = $_REQUEST['cbxscratingreviewratingavglog'];
					foreach ( $results as $id ) {
						$id = intval( $id );

						//get avg rating info
						$rating_avg_info = cbxscratingreview_singleAvgRatingInfo( $id );
						$post_id         = $rating_avg_info['post_id'];
						$post_reviews    = cbxscratingreview_postReviews( $post_id, - 1 );

						//here we will delete all single review for this avg and
						if ( is_array( $post_reviews ) && sizeof( $post_reviews ) > 0 ) {
							foreach ( $post_reviews as $index => $post_review ) {
								$review_id = intval( $post_review['id'] );

								do_action( 'cbxscratingreview_review_delete_before', $post_review );
								$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_log WHERE id=%d", $review_id ) );

								if ( $delete_status !== false ) {
									do_action( 'cbxscratingreview_review_delete_after', $post_review );
								}
							}
						}//end all reviews
					}
				}
			}

			return;
		}

		function prepare_items() {


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
			$order   = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? $_REQUEST['order'] : 'DESC';
			$orderby = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? $_REQUEST['orderby'] : 'logs.id';

			$data = $this->getLogData( $orderby, $order, $per_page, $current_page );

			$total_items = intval( $this->getLogDataCount() );

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
		 * Get Data
		 *
		 * @param string $orderby
		 * @param string $order
		 * @param int    $perpage
		 * @param int    $page
		 *
		 * @return array|null|object
		 */
		public function getLogData( $orderby = 'logs.id', $order = 'DESC', $perpage = 20, $page = 1 ) {

			global $wpdb;
			$table_rating_avg = $wpdb->prefix . 'cbxscratingreview_log_avg';

			$sql_select = "SELECT * FROM $table_rating_avg as logs";

			$join = $where_sql = '';


			if ( $where_sql == '' ) {
				$where_sql = '1';
			}

			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$sortingOrder = " ORDER BY $orderby $order ";

			$data = $wpdb->get_results( "$sql_select $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );

			return $data;
		}

		/**
		 * Get total data count
		 *
		 *
		 * @return array|null|object
		 */
		public function getLogDataCount() {

			global $wpdb;
			$table_rating_avg = $wpdb->prefix . 'cbxscratingreview_log_avg';

			$sql_select = "SELECT COUNT(*) FROM $table_rating_avg as logs";

			$join = $where_sql = '';


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
			$row_class = 'cbxscratingreview_avg_row';
			$row_class = apply_filters( 'cbxscratingreview_avg_row_class', $row_class, $item );
			echo '<tr id="cbxscratingreview_avg_row_' . $item['id'] . '" class="' . $row_class . '">';
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
			echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No rating average found. Please change your search criteria for better result.', 'cbxscratingreview' ) . '</p></div>';
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

			$search = isset( $_REQUEST['s'] ) ? esc_attr( wp_unslash( $_REQUEST['s'] ) ) : '';
			//$logdate = ( isset( $_REQUEST['logdate'] ) && $_REQUEST['logdate'] != '' ) ? sanitize_text_field( $_REQUEST['logdate'] ) : '';

			$pagination_params = array();

			if ( $search != '' ) {
				$pagination_params['s'] = $search;
			}

			/*if ($logdate != '') {
				$pagination_params['logdate'] = $logdate;
			}*/


			$pagination_params = apply_filters( 'cbxscratingreview_pagination_avg_params', $pagination_params );

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
	}//end class CBXSCRatingReviewRatingAvgLog_List_Table