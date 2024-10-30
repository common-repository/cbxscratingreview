<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}


	/**
	 * This function ensures that all necessary js and css for this plugin is added properly
	 *
	 * enqueue css and js
	 */
	function cbxscratingreview_AddJsCss() {
		return CBXSCRatingReviewHelper::AddJsCss();
	}//end cbxscratingreview_AddJsCss

	/**
	 * All necessary css and js for review form
	 */
	function cbxscratingreview_AddRatingFormJsCss() {
		return CBXSCRatingReviewHelper::AddRatingFormJsCss();
	}//end cbxscratingreview_AddRatingFormJsCss

	/**
	 * All necessary css and js for review edit form
	 *
	 *
	 */
	function cbxscratingreview_AddRatingEditFormJsCss() {
		return CBXSCRatingReviewHelper::AddRatingEditFormJsCss();
	}//end cbxscratingreview_AddRatingEditFormJsCss

	/**
	 * is this post rated at least once
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_isPostRated( $post_id = 0 ) {
		return CBXSCRatingReviewHelper::isPostRated( $post_id );
	}//end cbxscratingreview_isPostRated

	/**
	 * Total reviews count of a Post
	 *
	 * @param int    $post_id
	 * @param string $status
	 * @param string $score
	 *
	 * @return mixed
	 */
	function cbxscratingreview_totalPostReviewsCount( $post_id = 0, $status = '', $score = '' ) {
		return CBXSCRatingReviewHelper::totalPostReviewsCount( $post_id, $status, $score );
	}//end cbxscratingreview_totalPostReviewsCount

	/**
	 * is this post rated by user at least once
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return boolean - true/false
	 */
	function cbxscratingreview_isPostRatedByUser( $post_id = 0, $user_id = 0 ) {
		return CBXSCRatingReviewHelper::isPostRatedByUser( $post_id, $user_id );
	}//end cbxscratingreview_isPostRatedByUser

	/**
	 * Total reviews count of a Post by a User
	 *
	 * @param int    $post_id
	 * @param int    $user_id
	 * @param string $status
	 *
	 * @return int
	 */
	function cbxscratingreview_totalPostReviewsCountByUser( $post_id = 0, $user_id = 0, $status = '' ) {
		return CBXSCRatingReviewHelper::totalPostReviewsCountByUser( $post_id, $user_id, $status );
	}//end cbxscratingreview_totalPostReviewsCountByUser

	/**
	 * User's last review date for a post by user id
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return boolean - true/false
	 */
	function cbxscratingreview_lastPostReviewDateByUser( $post_id = 0, $user_id = 0 ) {
		return CBXSCRatingReviewHelper::lastPostReviewDateByUser( $post_id, $user_id );
	}//end cbxscratingreview_lastPostReviewDateByUser


	/**
	 * Single review data
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_singleReview( $review_id = 0 ) {
		return CBXSCRatingReviewHelper::singleReview( $review_id );
	}//end cbxscratingreview_singleReview

	/**
	 * Single review data render
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_singleReviewRender( $review_id = 0 ) {
		cbxscratingreview_AddJsCss(); //moved here from static class

		return CBXSCRatingReviewHelper::singleReviewRender( $review_id );
	}//end cbxscratingreview_singleReviewRender

	/**
	 * Single review edit data render
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_singleReviewEditRender( $review_id = 0 ) {
		return CBXSCRatingReviewHelper::singleReviewEditRender( $review_id );
	}//end cbxscratingreview_singleReviewEditRender

	/**
	 * Review lists data of a Post
	 *
	 * @param int    $post_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return array|null|object
	 */
	function cbxscratingreview_postReviews( $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {
		return CBXSCRatingReviewHelper::postReviews( $post_id, $perpage, $page, $status, $score, $orderby, $order );
	}//end cbxscratingreview_postReviews


	/**
	 * Reviews filter for Single post
	 *
	 * @param int    $post_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return false|string
	 */
	function cbxscratingreview_postReviewsFilterRender( $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC' ) {
		return CBXSCRatingReviewHelper::postReviewsFilterRender( $post_id, $perpage, $page, 1, $score, $orderby, $order );
	}//end cbxscratingreview_postReviewsFilterRender


	/**
	 * Render Review lists data of a Post
	 *
	 * @param int    $post_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 * @param bool   $load_more
	 * @param bool   $show_filter
	 *
	 * @return string
	 */
	function cbxscratingreview_postReviewsRender( $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC', $load_more = false, $show_filter = true ) {
		cbxscratingreview_AddJsCss(); //moved here from static class

		return CBXSCRatingReviewHelper::postReviewsRender( $post_id, $perpage, $page, 1, $score, $orderby, $order, $load_more, $show_filter );
	}//end cbxscratingreview_postReviewsRender

	/**
	 * Reviews list data
	 *
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $orderby
	 * @param string $order
	 * @param string $score
	 *
	 * @return mixed
	 */
	function cbxscratingreview_Reviews( $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $score = '' ) {
		return CBXSCRatingReviewHelper::Reviews( $perpage, $page, $status, $orderby, $order, $score );
	}//end cbxscratingreview_Reviews

	/**
	 * Total reviews count
	 *
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxscratingreview_totalReviewsCount( $status = '', $filter_score = '' ) {
		return CBXSCRatingReviewHelper::totalReviewsCount( $status, $filter_score );
	}//end cbxscratingreview_totalReviewsCount

	/**
	 * Total reviews count by post type, status and filter
	 *
	 * @param string $post_type
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxscratingreview_totalReviewsCountPostType( $post_type = 'post', $status = '', $filter_score = '' ) {
		return CBXSCRatingReviewHelper::totalReviewsCountPostType( $post_type, $status, $filter_score );
	}//end cbxscratingreview_totalReviewsCountPostType

	/**
	 * Total reviews count by User
	 *
	 * @param int    $user_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxscratingreview_totalReviewsCountByUser( $user_id = 0, $status = '', $filter_score = '' ) {
		return CBXSCRatingReviewHelper::totalReviewsCountByUser( $user_id, $status, $filter_score );
	}//end cbxscratingreview_totalReviewsCountByUser


	/**
	 * Reviews list data by a User
	 *
	 * @param int    $user_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $orderby
	 * @param string $order
	 * @param string $filter_score
	 *
	 * @return array|null|object
	 */
	function cbxscratingreview_ReviewsByUser( $user_id = 0, $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $filter_score = '' ) {
		return CBXSCRatingReviewHelper::ReviewsByUser( $user_id, $perpage, $page, $status, $orderby, $order, $filter_score );
	}


	/**
	 * Average rating information of a post by post id
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_postAvgRatingInfo( $post_id = 0 ) {
		return CBXSCRatingReviewHelper::postAvgRatingInfo( $post_id );
	}//end cbxscratingreview_postAvgRatingInfo

	/**
	 * Render single post avg rating
	 *
	 * @param int $post_id
	 * @param int $show_score
	 * @param int $show_star
	 * @param int $show_chart
	 *
	 * @return false|string
	 */
	function cbxscratingreview_postAvgRatingRender( $post_id = 0, $show_score = 1, $show_star = 1, $show_chart = 0 ) {
		return CBXSCRatingReviewHelper::postAvgRatingRender( $post_id, $show_score, $show_star, $show_chart );
	}//end cbxscratingreview_postAvgRatingRender

	/**
	 * Single avg rating info by avg id
	 *
	 * @param int $avg_id
	 *
	 * @return null|string
	 */
	function cbxscratingreview_singleAvgRatingInfo( $avg_id = 0 ) {
		return CBXSCRatingReviewHelper::singleAvgRatingInfo( $avg_id );
	}//end cbxscratingreview_singleAvgRatingInfo

	/**
	 * Get most rated posts
	 *
	 * @param int    $limit
	 * @param string $orderby
	 * @param string $order
	 * @param string $type
	 *
	 * @return array|null|object
	 */
	function cbxscratingreview_most_rated_posts( $limit = 10, $orderby = 'avg_rating', $order = 'DESC', $type = 'post' ) {
		return CBXSCRatingReviewHelper::most_rated_posts( $limit, $orderby, $order, $type );
	}//end cbxscratingreview_mostRatedPosts

	/**
	 * latest ratings of all post
	 */
	function cbxscratingreview_lastest_ratings( $limit = 10, $orderby = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
		return CBXSCRatingReviewHelper::lastest_ratings( $limit, $orderby, $order, $type, $user_id );
	}//end cbxscratingreview_latestRatings

	/**
	 * Render rating review form
	 *
	 * @param int    $post_id
	 * @param string $title
	 * @param string $scope
	 * @param int    $guest_login
	 *
	 * @return string
	 */
	function cbxscratingreview_reviewformRender( $post_id = 0, $title = '', $scope = 'shortcode', $guest_login = 1 ) {
		return CBXSCRatingReviewHelper::reviewformRender( $post_id, $title, $scope, $guest_login );
	}//end cbxscratingreview_reviewformRender

	/**
	 * paginate_links_as_bootstrap()
	 * JPS 20170330
	 * Wraps paginate_links data in Twitter bootstrap pagination component
	 *
	 * @param array $args      {
	 *                         Optional. {@see 'paginate_links'} for native argument list.
	 *
	 * @type string $nav_class classes for <nav> element. Default empty.
	 * @type string $ul_class  additional classes for <ul.pagination> element. Default empty.
	 * @type string $li_class  additional classes for <li> elements.
	 * }
	 * @return array|string|void String of page links or array of page links.
	 */
	function cbxscratingreview_paginate_links_as_bootstrap( $args = '' ) {
		$args['type'] = 'array';
		$defaults     = array(
			'nav_class' => '',
			'ul_class'  => '',
			'li_class'  => ''
		);
		$args         = wp_parse_args( $args, $defaults );
		$page_links   = paginate_links( $args );

		if ( $page_links ) {
			$r         = '';
			$nav_class = empty( $args['nav_class'] ) ? '' : 'class="' . $args['nav_class'] . '"';
			$ul_class  = empty( $args['ul_class'] ) ? '' : ' ' . $args['ul_class'];

			//$r .= '<nav '. $nav_class .' aria-label="navigation">' . "\n\t";
			$r .= '<div ' . $nav_class . ' aria-label="navigation">' . "\n\t";

			$r .= '<ul class="pagination' . $ul_class . '">' . "\n";
			foreach ( $page_links as $link ) {
				$li_classes = explode( " ", $args['li_class'] );
				strpos( $link, 'current' ) !== false ? array_push( $li_classes, 'active' ) : ( strpos( $link, 'dots' ) !== false ? array_push( $li_classes, 'disabled' ) : '' );
				$class = empty( $li_classes ) ? '' : 'class="' . join( " ", $li_classes ) . '"';
				$r     .= "\t\t" . '<li ' . $class . '>' . $link . '</li>' . "\n";
			}
			$r .= "\t</ul>";
			$r .= "\n</div>";

			return '<div class="clearfix"></div><nav class="blog-page--pagination cbxscratingreview_paginate_links">' . $r . '</nav><div class="clearfix"></div>';
		}
	}//end function cbxscratingreview_paginate_links_as_bootstrap


	if ( ! function_exists( 'get_reviewer_posts_url' ) ) {
		/**
		 * Return author profile by user id
		 *
		 * @param int $user_id
		 */
		/*	function get_reviewer_posts_url( $user_id = 0 ) {
				global $wp_rewrite;

				$user_id = ( $user_id == 0 ) ? get_current_user_id() : $user_id;

				$publicprofile_page_id  = intval( get_theme_mod( 'publicprofile_page_id', 0 ) );
				$publicprofile_page_url = get_permalink( $publicprofile_page_id );

				$get_reviewer_posts_url = '';

				if ( $wp_rewrite->using_permalinks() ) {
					$get_reviewer_posts_url = trailingslashit( $publicprofile_page_url ) . $user_id;
				} else {
					$get_reviewer_posts_url = add_query_arg( array(
						'member_id' => $user_id
					), $publicprofile_page_url );
				}

				return $get_reviewer_posts_url;
			}

			add_filter( 'cbxscratingreview_reviewer_posts_url', 'cbxscratingreview_reviewer_posts_url_modified', 10, 2 );

			function cbxscratingreview_reviewer_posts_url_modified( $current_url, $user_id ) {
				return get_reviewer_posts_url( $user_id );
			}*/
	}


	function cbxscratingreview_review_permalink( $review_id ) {
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();;

		//global $wp_rewrite;
		$review_id = intval( $review_id );


		if ( $review_id == 0 ) {
			return '#';
		}

		$singlereview_page_id = intval( $cbxscratingreview_setting->get_option( 'single_review_view_id', 'cbxscratingreview_tools', 0 ) );


		if ( $singlereview_page_id > 0 ) {

			$singlereview_page_link = get_permalink( $singlereview_page_id );

			return add_query_arg( array( 'review_id' => $review_id ), $singlereview_page_link ) . '#cbxscratingreview_review_list_item_' . $review_id;
		}

		return '#';
	}

	function cbxscratingreview_reviewToolbarRender( $post_review ) {
		return CBXSCRatingReviewHelper::reviewToolbarRender( $post_review );
	}

	/**
	 * render single review delete button
	 *
	 * @param array $post_review
	 *
	 * @return string
	 */
	function cbxscratingreview_reviewDeleteButtonRender( $post_review = array() ) {
		return CBXSCRatingReviewHelper::reviewDeleteButtonRender( $post_review );
	}