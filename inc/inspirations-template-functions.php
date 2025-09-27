<?php
/**
 * Helper functions for Inspiracja template
 *
 * @package mroomy_s
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INSPIRATION_ROOMS_SORT', 'random' );

/**
 * Start session for tracking viewed rooms
 */
function mroomy_start_session() {
	if ( ! session_id() ) {
		session_start();
	}
}
add_action( 'init', 'mroomy_start_session', 1 );

/**
 * Get viewed rooms from session for specific inspiration
 *
 * @param int $inspiration_id Inspiration post ID.
 * @return array Array of viewed room IDs.
 */
function mroomy_get_viewed_rooms_session( $inspiration_id = 0 ) {
	if ( ! session_id() ) {
		return array();
	}

	if ( $inspiration_id ) {
		$key = 'viewed_rooms_' . $inspiration_id;
		return isset( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : array();
	}

	return isset( $_SESSION['viewed_rooms'] ) ? $_SESSION['viewed_rooms'] : array();
}

/**
 * Add viewed rooms to session for specific inspiration
 *
 * @param array $room_ids Array of room post IDs.
 * @param int   $inspiration_id Inspiration post ID.
 */
function mroomy_add_viewed_rooms_session( $room_ids, $inspiration_id = 0 ) {
	if ( ! session_id() ) {
		return;
	}

	if ( $inspiration_id ) {
		$key = 'viewed_rooms_' . $inspiration_id;
		if ( ! isset( $_SESSION[ $key ] ) ) {
			$_SESSION[ $key ] = array();
		}
		$_SESSION[ $key ] = array_merge( $_SESSION[ $key ], $room_ids );
	} else {
		if ( ! isset( $_SESSION['viewed_rooms'] ) ) {
			$_SESSION['viewed_rooms'] = array();
		}
		$_SESSION['viewed_rooms'] = array_merge( $_SESSION['viewed_rooms'], $room_ids );
	}
}

/**
 * Clear viewed rooms from session
 *
 * @param int $inspiration_id Optional. Inspiration post ID. If provided, clears only for that inspiration.
 */
function mroomy_clear_viewed_rooms_session( $inspiration_id = 0 ) {
	if ( ! session_id() ) {
		return;
	}

	if ( $inspiration_id ) {
		$key = 'viewed_rooms_' . $inspiration_id;
		unset( $_SESSION[ $key ] );
	} else {
		unset( $_SESSION['viewed_rooms'] );
	}
}

/**
 * Get rooms query for inspiration page
 *
 * Retrieves rooms (pokoje-dla-dzieci) that share the same category (kategoria-pokoi)
 * as the given inspiration post. Only returns rooms with featured images.
 *
 * @param int   $inspiration_id The inspiration post ID.
 * @param array $args {
 *     Optional. Array of query arguments.
 *
 *     @type int    $posts_per_page Number of posts per page. Default 12.
 *     @type int    $paged          Page number for pagination. Default from get_query_var('paged').
 *     @type string $orderby        Order by: 'date' or 'rand'. Default from INSPIRATION_ROOMS_SORT constant.
 *     @type string $order          Sort order: 'ASC' or 'DESC'. Default 'DESC'.
 *     @type array  $exclude        Array of post IDs to exclude. Default empty array.
 * }
 * @return WP_Query|null WP_Query object or null if no category found.
 */
function mroomy_get_inspiration_rooms_query( $inspiration_id, $args = array() ) {
	$inspiration_terms = wp_get_post_terms( $inspiration_id, 'kategoria-pokoi' );

	if ( empty( $inspiration_terms ) || is_wp_error( $inspiration_terms ) ) {
		return null;
	}

	$defaults = array(
		'posts_per_page' => 12,
		'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		'orderby'        => defined( 'INSPIRATION_ROOMS_SORT' ) ? INSPIRATION_ROOMS_SORT : 'date',
		'order'          => 'DESC',
		'exclude'        => array(),
	);

	$args = wp_parse_args( $args, $defaults );

	$query_args = array(
		'post_type'      => 'pokoje-dla-dzieci',
		'posts_per_page' => $args['posts_per_page'],
		'paged'          => $args['paged'],
		'tax_query'      => array(
			array(
				'taxonomy' => 'kategoria-pokoi',
				'field'    => 'term_id',
				'terms'    => $inspiration_terms[0]->term_id,
			),
		),
		'meta_query'     => array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		),
	);

	if ( 'random' === $args['orderby'] || 'rand' === $args['orderby'] ) {
		$query_args['orderby']      = 'rand';
		$query_args['post__not_in'] = array_merge(
			mroomy_get_viewed_rooms_session( $inspiration_id ),
			$args['exclude']
		);
	} else {
		$query_args['orderby'] = $args['orderby'];
		$query_args['order']   = $args['order'];
		if ( ! empty( $args['exclude'] ) ) {
			$query_args['post__not_in'] = $args['exclude'];
		}
	}

	return new WP_Query( $query_args );
}

/**
 * Save viewed rooms to session after displaying them
 *
 * @param WP_Query $query Rooms query.
 * @param int      $inspiration_id Inspiration post ID.
 */
function mroomy_save_viewed_rooms( $query, $inspiration_id ) {
	if ( ! $query || ! $query->have_posts() ) {
		return;
	}

	$room_ids = wp_list_pluck( $query->posts, 'ID' );
	mroomy_add_viewed_rooms_session( $room_ids, $inspiration_id );
}