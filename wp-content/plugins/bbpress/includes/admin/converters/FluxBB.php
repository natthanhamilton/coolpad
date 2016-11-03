<?php

/**
 * Implementation of FluxBB Forum converter.
 *
 * @since bbPress (r5138)
 * @link  Codex Docs http://codex.bbpress.org/import-forums/fluxbb
 */
class FluxBB extends BBP_Converter_Base {
	/**
	 * Main Constructor
	 *
	 * @uses FluxBB::setup_globals()
	 */
	function __construct() {
		parent::__construct();
		$this->setup_globals();
	}

	/**
	 * Sets up the field mappings
	 */
	public function setup_globals() {
		/** Forum Section *****************************************************/
		// Forum id (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'id',
			'to_type'        => 'forum',
			'to_fieldname'   => '_bbp_forum_id'
		];
		// Forum topic count (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'num_topics',
			'to_type'        => 'forum',
			'to_fieldname'   => '_bbp_topic_count'
		];
		// Forum reply count (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'num_posts',
			'to_type'        => 'forum',
			'to_fieldname'   => '_bbp_reply_count'
		];
		// Forum total topic count (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'num_topics',
			'to_type'        => 'forum',
			'to_fieldname'   => '_bbp_total_topic_count'
		];
		// Forum total reply count (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'num_posts',
			'to_type'        => 'forum',
			'to_fieldname'   => '_bbp_total_reply_count'
		];
		// Forum title.
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'forum_name',
			'to_type'        => 'forum',
			'to_fieldname'   => 'post_title'
		];
		// Forum slug (Clean name to avoid conflicts)
		$this->field_map[] = [
			'from_tablename'  => 'forums',
			'from_fieldname'  => 'forum_name',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_name',
			'callback_method' => 'callback_slug'
		];
		// Forum description.
		$this->field_map[] = [
			'from_tablename'  => 'forums',
			'from_fieldname'  => 'forum_desc',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_null'
		];
		// Forum display order (Starts from 1)
		$this->field_map[] = [
			'from_tablename' => 'forums',
			'from_fieldname' => 'disp_position',
			'to_type'        => 'forum',
			'to_fieldname'   => 'menu_order'
		];
		// Forum dates.
		$this->field_map[] = [
			'to_type'      => 'forum',
			'to_fieldname' => 'post_date',
			'default'      => date('Y-m-d H:i:s')
		];
		$this->field_map[] = [
			'to_type'      => 'forum',
			'to_fieldname' => 'post_date_gmt',
			'default'      => date('Y-m-d H:i:s')
		];
		$this->field_map[] = [
			'to_type'      => 'forum',
			'to_fieldname' => 'post_modified',
			'default'      => date('Y-m-d H:i:s')
		];
		$this->field_map[] = [
			'to_type'      => 'forum',
			'to_fieldname' => 'post_modified_gmt',
			'default'      => date('Y-m-d H:i:s')
		];
		/** Topic Section *****************************************************/
		// Topic id (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'topics',
			'from_fieldname' => 'id',
			'to_type'        => 'topic',
			'to_fieldname'   => '_bbp_topic_id'
		];
		// Topic reply count (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'num_replies',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_reply_count',
			'callback_method' => 'callback_topic_reply_count'
		];
		// Topic total reply count (Includes unpublished replies, Stored in postmeta)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'num_replies',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_total_reply_count',
			'callback_method' => 'callback_topic_reply_count'
		];
		// Topic parent forum id (If no parent, then 0, Stored in postmeta)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'forum_id',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_forumid'
		];
		// Topic author.
		// Note: We join the 'posts' table because 'topics' table does include numeric user id.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'poster_id',
			'join_tablename'  => 'topics',
			'join_type'       => 'INNER',
			'join_expression' => 'ON topics.first_post_id = posts.id',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_author',
			'callback_method' => 'callback_userid'
		];
		// Topic Author ip (Stored in postmeta)
		// Note: We join the 'posts' table because 'topics' table does not include author IP addresses.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'poster_ip',
			'join_tablename'  => 'topics',
			'join_type'       => 'INNER',
			'join_expression' => 'ON topics.first_post_id = posts.id',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_author_ip'
		];
		// Topic content.
		// Note: We join the 'posts' table because 'topics' table does not include topic content.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'message',
			'join_tablename'  => 'topics',
			'join_type'       => 'INNER',
			'join_expression' => 'ON topics.first_post_id = posts.id',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_html'
		];
		// Topic title.
		$this->field_map[] = [
			'from_tablename' => 'topics',
			'from_fieldname' => 'subject',
			'to_type'        => 'topic',
			'to_fieldname'   => 'post_title'
		];
		// Topic slug (Clean name to avoid conflicts)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'subject',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_name',
			'callback_method' => 'callback_slug'
		];
		// Topic parent forum id (If no parent, then 0)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'forum_id',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_parent',
			'callback_method' => 'callback_forumid'
		];
		// Sticky status (Stored in postmeta))
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'sticky',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_old_sticky_status',
			'callback_method' => 'callback_sticky_status'
		];
		// Topic dates.
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'posted',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_date',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'posted',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_date_gmt',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'posted',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_modified',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'posted',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_modified_gmt',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'posted',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_last_active_time',
			'callback_method' => 'callback_datetime'
		];
		// Topic status (Open = 0 or Closed = 1, FluxBB v1.5.3)
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'closed',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_status',
			'callback_method' => 'callback_topic_status'
		];
		/** Tags Section ******************************************************/
		/**
		 * FluxBB v1.5.3 Forums do not support topic tags out of the box
		 */
		/** Reply Section *****************************************************/
		// Reply id (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'posts',
			'from_fieldname' => 'id',
			'to_type'        => 'reply',
			'to_fieldname'   => '_bbp_post_id'
		];
		// Reply parent forum id (If no parent, then 0, Stored in postmeta)
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'topic_id',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_topicid_to_forumid'
		];
		// Reply parent topic id (If no parent, then 0, Stored in postmeta)
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'topic_id',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_topic_id',
			'callback_method' => 'callback_topicid'
		];
		// Reply author ip (Stored in postmeta)
		$this->field_map[] = [
			'from_tablename' => 'posts',
			'from_fieldname' => 'poster_ip',
			'to_type'        => 'reply',
			'to_fieldname'   => '_bbp_author_ip'
		];
		// Reply author.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'poster_id',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_author',
			'callback_method' => 'callback_userid'
		];
		// Reply title.
		// Note: We join the 'topics' table because 'posts' table does not include reply title.
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'subject',
			'join_tablename'  => 'posts',
			'join_type'       => 'INNER',
			'join_expression' => 'ON topics.id = posts.topic_id WHERE topics.first_post_id != posts.id',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_title',
			'callback_method' => 'callback_reply_title'
		];
		// Reply slug (Clean name to avoid conflicts)
		// Note: We join the 'topics' table because 'posts' table does not include slug title.
		$this->field_map[] = [
			'from_tablename'  => 'topics',
			'from_fieldname'  => 'subject',
			'join_tablename'  => 'posts',
			'join_type'       => 'INNER',
			'join_expression' => 'ON topics.id = posts.topic_id WHERE topics.first_post_id != posts.id',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_name',
			'callback_method' => 'callback_slug'
		];
		// Reply content.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'message',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_html'
		];
		// Reply parent topic id (If no parent, then 0)
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'topic_id',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_parent',
			'callback_method' => 'callback_topicid'
		];
		// Reply dates.
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'posted',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_date',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'posted',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_date_gmt',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'posted',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_modified',
			'callback_method' => 'callback_datetime'
		];
		$this->field_map[] = [
			'from_tablename'  => 'posts',
			'from_fieldname'  => 'posted',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_modified_gmt',
			'callback_method' => 'callback_datetime'
		];
		/** User Section ******************************************************/
		// Store old User id (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'id',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_user_id'
		];
		// Store old User password (Stored in usermeta serialized with salt)
		$this->field_map[] = [
			'from_tablename'  => 'users',
			'from_fieldname'  => 'password',
			'to_type'         => 'user',
			'to_fieldname'    => '_bbp_password',
			'callback_method' => 'callback_savepass'
		];
		// Store old User Salt (This is only used for the SELECT row info for the above password save)
//		$this->field_map[] = array(
//			'from_tablename' => 'users',
//			'from_fieldname' => 'salt',
//			'to_type'        => 'user',
//			'to_fieldname'   => ''
//		);
		// User password verify class (Stored in usermeta for verifying password)
		$this->field_map[] = [
			'to_type'      => 'users',
			'to_fieldname' => '_bbp_class',
			'default'      => 'FluxBB'
		];
		// User name.
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'username',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_login'
		];
		// User nice name.
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'username',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_nicename'
		];
		// User email.
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'email',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_email'
		];
		// User homepage.
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'url',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_url'
		];
		// User registered.
		$this->field_map[] = [
			'from_tablename'  => 'users',
			'from_fieldname'  => 'registered',
			'to_type'         => 'user',
			'to_fieldname'    => 'user_registered',
			'callback_method' => 'callback_datetime'
		];
		// User display name.
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'realname',
			'to_type'        => 'user',
			'to_fieldname'   => 'display_name'
		];
		// User AIM (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'aim',
			'to_type'        => 'user',
			'to_fieldname'   => 'aim'
		];
		// User Yahoo (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'yahoo',
			'to_type'        => 'user',
			'to_fieldname'   => 'yim'
		];
		// Store Jabber
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'jabber',
			'to_type'        => 'user',
			'to_fieldname'   => 'jabber'
		];
		// Store ICQ (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'icq',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_fluxbb_user_icq'
		];
		// Store MSN (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'msn',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_fluxbb_user_msn'
		];
		// Store Location (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'location',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_fluxbb_user_location'
		];
		// Store Signature (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename'  => 'users',
			'from_fieldname'  => 'signature',
			'to_type'         => 'user',
			'to_fieldname'    => '_bbp_fluxbb_user_sig',
			'callback_method' => 'callback_html'
		];
		// Store Admin Note (Stored in usermeta)
		$this->field_map[] = [
			'from_tablename' => 'users',
			'from_fieldname' => 'admin_note',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_fluxbb_user_admin_note'
		];
	}

	/**
	 * This method allows us to indicates what is or is not converted for each
	 * converter.
	 */
	public function info() {
		return '';
	}

	/**
	 * This method is to take the pass out of the database and compare
	 * to a pass the user has typed in.
	 */
	public function authenticate_pass($password, $serialized_pass) {
		$pass_array = unserialize($serialized_pass);

		return ($pass_array['hash'] == md5(md5($password) . $pass_array['salt']));
	}

	/**
	 * This method is to save the salt and password together.  That
	 * way when we authenticate it we can get it out of the database
	 * as one value. Array values are auto sanitized by WordPress.
	 */
	public function callback_savepass($field, $row) {
		$pass_array = ['hash' => $field, 'salt' => $row['salt']];

		return $pass_array;
	}

	/**
	 * Translate the post status from FluxBB v1.5.3 numeric's to WordPress's strings.
	 *
	 * @param int $status FluxBB v1.5.3 numeric topic status
	 *
	 * @return string WordPress safe
	 */
	public function callback_topic_status($status = 0) {
		switch ($status) {
			case 1 :
				$status = 'closed';
				break;
			case 0  :
			default :
				$status = 'publish';
				break;
		}

		return $status;
	}

	/**
	 * Translate the topic sticky status type from FluxBB v1.5.3 numeric's to WordPress's strings.
	 *
	 * @param int $status FluxBB v1.5.3 numeric forum type
	 *
	 * @return string WordPress safe
	 */
	public function callback_sticky_status($status = 0) {
		switch ($status) {
			case 1 :
				$status = 'sticky';       // FluxBB Sticky 'sticky = 1'
				break;
			case 0  :
			default :
				$status = 'normal';       // FluxBB Normal Topic 'sticky = 0'
				break;
		}

		return $status;
	}

	/**
	 * Verify the topic/reply count.
	 *
	 * @param int $count FluxBB v1.5.3 topic/reply counts
	 *
	 * @return string WordPress safe
	 */
	public function callback_topic_reply_count($count = 1) {
		$count = absint((int)$count - 1);

		return $count;
	}

	/**
	 * Set the reply title
	 *
	 * @param string $title FluxBB v1.5.3 topic title of this reply
	 *
	 * @return string Prefixed topic title, or empty string
	 */
	public function callback_reply_title($title = '') {
		$title = !empty($title) ? __('Re: ', 'bbpress') . html_entity_decode($title) : '';

		return $title;
	}
}