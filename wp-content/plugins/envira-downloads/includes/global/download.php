<?php
/**
 * Download class.
 *
 * @since 1.0.0
 *
 * @package Envira_Downloads
 * @author	Envira Team
 */
class Envira_Downloads_Download {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'force_download' ) );

	}

	/**
	* Forces a browser download of the requested image from the requested Envira Gallery
	*
	* @since 1.0
	*/
	public function force_download() {

		// Check if a gallery ID and download ID have been specified
		if ( ! isset( $_REQUEST['envira-downloads-gallery-id'] ) || ! isset( $_REQUEST['envira-downloads-gallery-image'] ) ) {
			return;
		}

		// Prepare vars
		$gallery_data		= false;
		$file_path			= false;
		$third_party		= false;
		$gallery_id			= absint( $_REQUEST['envira-downloads-gallery-id'] );
		$gallery_image_id	= sanitize_text_field( $_REQUEST['envira-downloads-gallery-image'] ); // might not always be a number. example: 'all'
		$dynamic_gallery_id = ( isset($_REQUEST['envira-downloads-dynamic-gallery-id']) ? absint( $_REQUEST['envira-downloads-dynamic-gallery-id'] ) : false);
		$wp_gallery_images	= ( isset($_REQUEST['envira-downloads-gallery-images']) ? sanitize_text_field( $_REQUEST['envira-downloads-gallery-images'] ) : false); 
		$envira_dynamic		= ( isset($_REQUEST['envira-dynamic']) ? absint( $_REQUEST['envira-dynamic'] ) : false);
		// Get gallery, unless we are a dynamic gallery with "Render all WordPress Galleries using Envira?" enabled
		
		if ( strpos( $_REQUEST['envira-downloads-gallery-id'], 'custom_gallery_') !== false || strpos( $_REQUEST['envira-downloads-gallery-id'], 'custom-gallery-') !== false ) { // with 'custom_gallery', we assume it's a converted WP gallery

			// Before we would set this to wp_gallery, but we have to check to 
			// see if the user is downloading all and if so then it's wp_gallery_all

			if ( $gallery_image_id == 'all' ) {
				$gallery_image_id = 'wp_gallery_all';
			} else {
				// like previous, simply change this to wp_gallery
				$gallery_image_id = 'wp_gallery';
			}			

		} else if ( $envira_dynamic == 1 && is_integer( $gallery_image_id ) && is_integer( $dynamic_gallery_id ) ) {

			$gallery_data = Envira_Gallery::get_instance()->get_gallery( $dynamic_gallery_id );

			// if this is an instagram gallery, inject images
			if ( $gallery_data['config']['type'] == 'instagram' ) {
				$gallery_data = Envira_Instagram_Shortcode::get_instance()->inject_images( $gallery_data, $dynamic_gallery_id );
				$third_party  = true;
			}

			if ( ! $gallery_data ) {
				return;
			}

		} else if ( $envira_dynamic == 1 ) {

			$tags = explode(",", $_REQUEST['envira-downloads-gallery-id']);
			$tag_array = array( 'dynamic' => $tags);
			$gallery_data = Envira_Dynamic_Gallery_Shortcode::get_instance()->parse_shortcode_attributes( false, $tag_array, false );
			$gallery_id = 'dynamic';

		} else { // it's a run-of-the-mill gallery, so attempt to grab data

			$gallery_data = Envira_Gallery::get_instance()->get_gallery( $gallery_id );

			// if this is an instagram gallery, inject images
			if ( $gallery_data['config']['type'] == 'instagram' ) {
				$gallery_data = Envira_Instagram_Shortcode::get_instance()->inject_images( $gallery_data, $gallery_id );
				$third_party  = true;
			}

			if ( ! $gallery_data ) {
				return;
			}

		}	

		// If The Gallery Image Is Zero and If We Are Requesting All Images, Make The Var "all"
		if ( $gallery_image_id != 'wp_gallery' && $gallery_image_id != 'wp_gallery_all' && ( ( $gallery_image_id == 0 && $gallery_data['config']['type'] != 'instagram' ) || $_REQUEST['envira-downloads-gallery-image'] == "all" ) ) {
			$gallery_image_id = 'all';
		}

		if ( ( intval( $gallery_image_id ) > 0 ) || $gallery_image_id == "all" ) :

			/**
			* If Password Protection is enabled on this gallery:
			* - Check if a cookie exists for this gallery ID. If so, check it matches the password
			* - Check if a password was sent as part of the request. If so, check it matches the password + store as a cookie
			* - Bail, as we don't have a password from the user
			*/
			// If Password Protection is enabled, check we have a cookie set
			if ( isset( $gallery_data['config']['password_protection_download'] ) && ! empty( $gallery_data['config']['password_protection_download'] ) ) {
				// Password required
				$password_success = false;
				$password = $gallery_data['config']['password_protection_download'];

				// Check cookies
				if ( isset( $_COOKIE['envira_password_protection_download_' . $gallery_id ] ) ) {
					if ( wp_check_password( $password, $_COOKIE['envira_password_protection_download_' . $gallery_id ] ) ) {
						// OK
						$password_success = true;
						setcookie( 'envira_password_protection_download_' . $gallery_id, wp_hash_password( $password ), time() + ( 3600 * 24 ) );
					}
				}

				// Check request
				if ( isset( $_REQUEST['envira_password_protection_download'] ) ) {
					if ( $_REQUEST['envira_password_protection_download'] == $password ) {
						// OK
						$password_success = true;
						setcookie( 'envira_password_protection_download_' . $gallery_id, wp_hash_password( $password ), time() + ( 3600 * 24 ) );
					}
				}

				// If password was not successful, redirect with an error message so the user knows what went wrong.
				if ( ! $password_success ) {
					// Clear any cookie that might have been set. This ensures users can re-attempt authentication
					// when a Gallery password is changed.
					setcookie( 'envira_password_protection_download_' . $gallery_id, '', time() - ( 3600 * 24 ) );

					// Build the redirect URL, by removing the existing query args and adding a new message.
					$redirect_url = remove_query_arg( array(
						'envira-downloads-gallery-image',
						'envira_password_protection_download',
					), $_SERVER['REQUEST_URI'] );

					// Add an error flag.
					$redirect_url = add_query_arg( array(
						'envira-downloads-invalid-password' => 1
					), $redirect_url );

					wp_redirect( $redirect_url );
					die();
				}
			}

			if ( ! isset( $gallery_data['gallery'] ) && $gallery_data['config']['type'] != 'instagram' ) {
				return;
			}

		endif;

		// For instagram it's possible to pass in a zero, which breaks the switch
		if ( $gallery_data && $gallery_data['config']['type'] == 'instagram' ) {
			$instagram_high_res_index = $gallery_image_id; // this holds the oringial index, including a possible zero
			$gallery_image_id = 1;
		}

		// If the requested image ID is 'all', build a ZIP file comprising of all images in the Gallery
		switch ( $gallery_image_id ) {

			/**
			* Download Single From WordPress Gallery
			*/
			case 'wp_gallery':
				// envira-downloads-gallery-image is the image attachment ID
				$attachment_id = intval( $_REQUEST['envira-downloads-gallery-image'] );
				if ( !$attachment_id ) { return; }

				// Get image and filename
				$requested_file = get_attached_file( $attachment_id ); // Full path
				$file_path		= str_replace( content_url(), WP_CONTENT_DIR, $requested_file );

				$this->envira_download_file( $file_path, $requested_file );
				break;

			/**
			* Download All From WordPress Gallery
			*/
			case 'wp_gallery_all':

				if ( empty( $wp_gallery_images ) ) { continue; }
				$temp_images = explode( ',', $wp_gallery_images );

				$images = array();
				foreach ( $temp_images as $image_id ) {
					$images[] = get_attached_file( intval($image_id) );
				}
			  
				// ZIP
				$upload_dir			= wp_upload_dir();
				// Update: Customer can add a custom name in settings... if there is no custom, we stick with envira-gallery.zip
				if ( !empty( $gallery_data['config']['download_custom_name'] ) ) {
					$custom_name = sanitize_text_field( $gallery_data['config']['download_custom_name'] );
					// remove extension, if it exists.
					$custom_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $custom_name);
				} else {
					$custom_name = 'envira-downloads';
				}

				$requested_file		= $upload_dir['basedir'] . '/' . $custom_name . '.zip';
				$result				= $this->zip( $images, $requested_file );

				$file_path	= str_replace( content_url(), WP_CONTENT_DIR, $requested_file );
				
				$this->envira_download_file( $file_path, $requested_file );
				break;


			/**
			* Download All
			*/
			case 'all':
				// Build an array of image paths
				$images  = array();
				$uploads = wp_upload_dir();
				foreach ( $gallery_data['gallery'] as $image_id => $image ) {
					
					// Skip over images that are pending (ignore if in Preview mode).
					if ( isset( $image['status'] ) && 'pending' == $image['status'] ) {
						continue;
					}

				   	if ( isset( $gallery_data['config']['download_image_size'] ) && !empty( $gallery_data['config']['download_image_size'] ) ) {
						// Get the image object
						$image_object 	  = wp_get_attachment_image_src ( $image_id, $gallery_data['config']['download_image_size'] );
						// Isolate the url
						$image_url = $image_object[0];
						// Using the wp_upload_dir replace the baseurl with the basedir
						$image_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $image_url );
						if ( $image_path ) {
							$images[] = $image_path;
						}
				   	} else {			 
						$images[] = get_attached_file( $image_id );
					}
				
				}

				// ZIP
				$upload_dir			= wp_upload_dir();
				// Update: Customer can add a custom name in settings... if there is no custom, we stick with envira-gallery.zip
				if ( !empty( $gallery_data['config']['download_custom_name'] ) ) {
					$custom_name = sanitize_text_field( $gallery_data['config']['download_custom_name'] );
					// remove extension, if it exists.
					$custom_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $custom_name);
				} else {
					$custom_name = 'envira-gallery';
				}

				$requested_file		= $upload_dir['basedir'] . '/' . $custom_name . '.zip';
				$result				= $this->zip( $images, $requested_file );

				$file_path	= str_replace( content_url(), WP_CONTENT_DIR, $requested_file );
				
				$this->envira_download_file( $file_path, $requested_file );
				break;

			/**
			* Download Specific Image
			*/
			default:

				if ( ! isset( $gallery_data['gallery'][ $gallery_image_id ] ) && ! $dynamic_gallery_id ) {
					return;
				}

				$requested_file = false;

				// if this is an instagram gallery, pull the high res image
				if ( $gallery_data['config']['type'] == 'instagram' ) {

					$image			= $gallery_data['gallery'][ $instagram_high_res_index ];
					$requested_file = $gallery_data['gallery'][ $instagram_high_res_index ]['instagram_high_res'];

				} else {

					// otherwise get image and filename as normal

				   if ( isset( $gallery_data['config']['download_image_size'] ) && !empty( $gallery_data['config']['download_image_size'] ) ) {
						// if the user has defined an image size, let's attempt to get that src
						$image = wp_get_attachment_image_src ( $gallery_image_id, $gallery_data['config']['download_image_size'] );
						$requested_file = $image[0];
				   }

				   if ( !$requested_file ) {
						// if for some reason the attempts above didn't result in anything, fall back to default
						$image			= $gallery_data['gallery'][ $gallery_image_id ];
						$requested_file = $image['src'];
				   }

				}

				// if we couldn't get the requested file, bail.
				if ( !$requested_file ) { 
					return; 
				}

				// determine path to file
				if ( strpos( $requested_file, content_url() ) !== false ) {

					$file_path	= str_replace( content_url(), WP_CONTENT_DIR, $requested_file );
					$file_path	= realpath( $file_path );
					$direct		= true;

				} else if ( strpos( $requested_file, set_url_scheme( content_url(), 'https' ) ) !== false ) {
					
					$file_path	= str_replace( set_url_scheme( content_url(), 'https' ), WP_CONTENT_DIR, $requested_file );
					$file_path	= realpath( $file_path );
					$direct		= true;

				}

				$this->envira_download_file ( $file_path, $requested_file, null, $third_party );

				break;

		}

	}


	/**
	 * Handles the actual download procedure
	 *
	 * @since 1.0
	 * 
	 * @param	 string	   file extension
	 * @return	 string
	 */
	function envira_download_file( $file_path, $requested_file, $direct = true, $third_party = false ) {

		if ( $file_path === false && $third_party ) {
			// likely instagram
			header( "Pragma: no-cache");
			header( "Expires: 0");
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header( "Cache-Control: public");
			header( "Content-Description: File Transfer");
			header( "Content-type: application/octet-stream");
			header( "Content-Disposition: attachment; filename=" . $requested_file );
			header( "Content-Transfer-Encoding: binary");
			echo file_get_contents( $requested_file );
			exit();
		}

		// get the file extension & content type
		$parts			= explode( '.', $requested_file );
		$file_extension = end( $parts );
		$content_type	= $this->envira_get_file_content_type( $file_extension );

		@session_write_close();
		if( function_exists( 'apache_setenv' ) ) {
			@apache_setenv('no-gzip', 1);
		}
		@ini_set( 'zlib.output_compression', 'Off' );

		nocache_headers();
		header( "Pragma: no-cache");
		header( "Expires: 0");
		header( "Robots: none");
		header( "Content-Type: " . $content_type . "");
		header( "Content-Description: File Transfer");
		header( "Content-Disposition: attachment; filename=\"" . basename( $requested_file ) . "\"");
		header( "Content-Transfer-Encoding: binary");

		// Set the file size header
		header( "Content-Length: " . @filesize( $file_path ) );

		// Now deliver the file based on the kind of software the server is running / has enabled
		if ( stristr( getenv( 'SERVER_SOFTWARE' ), 'lighttpd' ) ) {

			header( "X-LIGHTTPD-send-file: $file_path" );

		} elseif ( $direct && ( stristr( getenv( 'SERVER_SOFTWARE' ), 'nginx' ) || stristr( getenv( 'SERVER_SOFTWARE' ), 'cherokee' ) ) ) {

			// We need a path relative to the domain
			$file_path = str_ireplace( realpath( $_SERVER['DOCUMENT_ROOT'] ), '', $file_path );
			header( "X-Accel-Redirect: /$file_path" );

		}

		$this->envira_readfile_chunked( $file_path );
		exit();

	}  

	/**
	 * Get the file content type
	 *
	 * @since 1.0
	 * 
	 * @param	 string	   file extension
	 * @return	 string
	 */
	function envira_get_file_content_type( $extension ) {
		switch ( $extension ) :
			case 'gif'		: $content_type = "image/gif"; break;
			case 'jp2'		: $content_type = "image/jp2"; break;
			case 'jpe'		: $content_type = "image/jpeg"; break;
			case 'jpeg'		: $content_type = "image/jpeg"; break;
			case 'jpg'		: $content_type = "image/jpeg"; break;
			case 'zip'		: $content_type = "application/zip"; break;
			default			: $content_type = "application/force-download";
		endswitch;

		if ( envira_mobile_detect()->isMobile() ) {
			$content_type = 'application/octet-stream';
		}

		return apply_filters( 'envira_file_content_type', $content_type );
	}	 


	/**
	 * Reads file in chunks so big downloads are possible without changing PHP.INI
	 * See http://codeigniter.com/wiki/Download_helper_for_large_files/
	 *
	 * @since 1.0
	 * 
	 * @access	 public
	 * @param	 string	 $file		The file
	 * @param	 boolean $retbytes	Return the bytes of file
	 * @return	 bool|string		If string, $status || $cnt
	 */
	function envira_readfile_chunked( $file, $retbytes = true ) {

		$chunksize = 1024 * 1024;
		$buffer	   = '';
		$cnt	   = 0;
		$handle	   = @fopen( $file, 'r' );

		if ( $size = @filesize( $file ) ) {
			header("Content-Length: " . $size );
		}

		if ( false === $handle ) {
			return false;
		}

		while ( ! @feof( $handle ) ) {
			$buffer = @fread( $handle, $chunksize );
			echo $buffer;

			if ( $retbytes ) {
				$cnt += strlen( $buffer );
			}
		}

		$status = @fclose( $handle );

		if ( $retbytes && $status ) {
			return $cnt;
		}

		return $status;
	}


	/**
	 * Zips the given array of files into the given destination ZIP file.
	 *
	 * @since	1.0.1
	 *
	 * @param	array	$files			Absolute paths and filename to source files
	 * @param	string	$destination	Absolute path and filename of destination ZIP file
	 * @return
	 */
	private function zip( $files, $destination ) {
		
		// Check the ZIP extension is loaded
		if ( ! extension_loaded( 'zip' ) ) {
			return false;
		}

		// Delete the ZIP file if it already exists
		if ( file_exists( $destination ) ) {
			unlink ( $destination );
		}

		$zip = new ZipArchive();
		if ( ! $zip->open( $destination, ZIPARCHIVE::CREATE ) ) {
			return false;
		}

		foreach ( $files as $file ) {
			$zip->addFile( $file, basename( $file ) );
		}

		$zip->close();

		return true;

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_Downloads_Download object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Downloads_Download ) ) {
			self::$instance = new Envira_Downloads_Download();
		}

		return self::$instance;

	}

}

// Load the download class.
$envira_downloads_download = Envira_Downloads_Download::get_instance();