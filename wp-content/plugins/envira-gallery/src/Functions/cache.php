<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Helper method to flush gallery caches once a gallery is updated.
 *
 * @since 1.0.0
 *
 * @param int $post_id The current post ID.
 * @param string $slug The unique gallery slug.
 */
function envira_flush_gallery_caches( $post_id, $slug = '' ) {

	// Delete known gallery caches.
	delete_transient( '_eg_cache_' . $post_id );
	delete_transient( '_eg_cache_all' );
	delete_transient( '_eg_fragment_' . $post_id );

	// Possibly delete slug gallery cache if available.
	if ( ! empty( $slug ) ) {
		delete_transient( '_eg_cache_' . $slug );
	}

	// Run a hook for Addons to access.
	do_action( 'envira_gallery_flush_caches', $post_id, $slug );

}