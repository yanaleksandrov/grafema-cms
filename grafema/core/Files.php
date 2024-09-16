<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

use Grafema\Dir;

/**
 * The Files class provides a convenient and easy-to-use API for working with files.
 * It supports working with various types: CSV, SVG and images of different formats.
 *
 * You can perform a wide range of operations: reading and writing to a file,
 * downloading and capturing, moving and copying files, and much more.
 *
 * @since      2025.1
 */
class Files extends Files\Handler
{

	public string $basename = '';

	public string $dirpath = '';

	public string $extension = '';

	public string $filename = '';

	public string $mime = '';

	public string $modified = '';

	public string $hash = '';

	public string $type = '';

	public string $url = '';

	public int $size = 0;

	public float $sizeKb = 0;

	public float $sizeMb = 0;

	public string $sizeHumanize = '';

	public array $errors = [];

	/**
	 * Files constructor.
	 *
	 * @param string $filepath
	 */
	private function __construct( string $filepath = '' ) {
		parent::__construct( $filepath );

		$this->basename     = $this->basename();
		$this->dirpath      = $this->dirname();
		$this->filename     = $this->filename();
		$this->url          = $this->getUrl();
		$this->extension    = $this->extension();
		$this->hash         = $this->getHash();
		$this->type         = $this->getType();
		$this->mime         = $this->mime();
		$this->modified     = $this->modified();
		$this->size         = $this->size();
		$this->sizeKb       = self::reformat( $this->size, 'Kb' );
		$this->sizeMb       = self::reformat( $this->size, 'Mb' );
		$this->sizeHumanize = self::humanise( $this->size );
	}

	/**
	 * Open file.
	 *
	 * @param string        $filepath Path to exists file.
	 * @param callable|null $callback
	 * @return Files|Error
	 *
	 * @since  2025.1
	 */
	public static function open( string $filepath = '', ?callable $callback = null ): Files|Error {
		if ( ! file_exists( $filepath ) ) {
			return new Error(  'file-not-exists', I18n::_f( "File with ':filepath' path is not exists.", $filepath ) );
		}

		$file = new self( $filepath );
		if ( is_callable( $callback ) ) {
			call_user_func( $callback, $file );
		}
		return $file;
	}

	/**
	 * Upload file to server.
	 *
	 * @param array $file
	 * @param string $targetDir
	 * @param callable|null $callback
	 * @return Files|Error Data about current uploaded file or errors list.
	 *
	 * @since  2025.1
	 */
	public static function upload( array $file, string $targetDir, ?callable $callback = null ): Files|Error {
		$maxFileSize = parent::getMaxUploadSizeInBytes();
		$mimeTypes   = parent::getAllowedMimeTypes();
		$mimes       = implode( ',', array_values( $mimeTypes ) );
		$extensions  = str_replace( '|', ',', implode( ',', array_keys( $mimeTypes ) ) );

		/**
		 * Check file validation.
		 *
		 * @since 2025.1
		 */
		$validator = ( new Validator(
			$file,
			[
				'type'     => 'type:' . $mimes,
				'error'    => 'equal:0',
				'tmp_name' => 'required',
				'size'     => 'min:0|max:' . $maxFileSize,
				'name'     => 'extension:' . $extensions,
			]
		) )->extend(
			'type:type',
			I18n::_t( 'Sorry, you are not allowed to upload this file type.' )
		)->extend(
			'error:equal',
			I18n::_t( 'An error occurred while uploading the file, please try again.' ),
			function( $validator, $value, $comparison_value ) {
				$value            = intval( $value );
				$comparison_value = intval( $comparison_value );

				// Courtesy of php.net, the strings that describe the error indicated in $_FILES[{form field}]['error'].
				$uploadErrorMessages = [
					false,
					I18n::_f( 'The uploaded file exceeds the %1$s directive in %2$s.', 'upload_max_filesize', 'php.ini' ),
					I18n::_f( 'The uploaded file exceeds the %s directive that was specified in the HTML form.', 'MAX_FILE_SIZE' ),
					I18n::_t( 'The uploaded file was only partially uploaded.' ),
					I18n::_t( 'No file was uploaded.' ),
					'',
					I18n::_t( 'Missing a temporary folder.' ),
					I18n::_t( 'Failed to write file to disk.' ),
					I18n::_t( 'File upload stopped by extension.' ),
				];

				$validator->messages['error:equal'] = $uploadErrorMessages[ $value ];

				return $value === $comparison_value;
			}
		)->extend(
			'size:min',
			I18n::_t( 'File is empty. Please upload something more substantial.' )
		)->extend(
			'size:max',
			I18n::_f( 'The maximum file size is :fileMaxSize.', parent::humanise( $maxFileSize, 'Mb' ) )
		)->apply();

		/**
		 * If the incoming data has been checked for validity, continue uploading
		 *
		 * @since 2025.1
		 */
		if ( $validator instanceof Validator ) {
			return new Error( 'file-upload', $validator );
		}

		$basename = Sanitizer::filename( $file['name'] ?? '' );
		$filepath = sprintf( '%s%s', $targetDir, $basename );
		if ( empty( $basename ) ) {
			return new Error( 'file-upload', I18n::_t( 'File name must not contain illegal characters and must not be empty.' ) );
		}

		/**
		 * Check that the uploaded file is unique.
		 *
		 * @since 2025.1
		 */
		if ( file_exists( $filepath ) ) {
			$filename  = pathinfo( $basename, PATHINFO_FILENAME );
			$extension = pathinfo( $basename, PATHINFO_EXTENSION );

			// check that the existing and uploaded file are the same
			if ( hash_file( 'md5', $filepath ) === hash_file( 'md5', $file['tmp_name'] ) ) {
				unlink( $file['tmp_name'] );

				return new Error( 'file-upload', I18n::_t( 'File already exists.' ) );
			} else {
				// make sure that the file name in the folder is unique
				$suffix = 1;
				while ( file_exists( $filepath ) ) {
					$suffix++;
					$filepath = sprintf( '%s%s-%d.%s', $targetDir, $filename, $suffix, $extension );
				}
			}
		}

		/**
		 * Create new file.
		 *
		 * @since 2025.1
		 */
		if ( ! isset( $_file ) ) {
			if ( ! is_dir( $targetDir ) ) {
				mkdir( $targetDir, 0755, true );
			}

			$uploaded = move_uploaded_file( $file['tmp_name'], $filepath );
			if ( $uploaded ) {
				$_file = new self( $filepath );
			} else {
				return new Error( 'file-upload', I18n::_t( 'Something went wrong, upload is failed.' ) );
			}
		}

		if ( is_callable( $callback ) ) {
			call_user_func( $callback, $_file );
		}

		/**
		 * Filters the data array for the uploaded file.
		 *
		 * @since 2025.1
		 */
		return Hook::apply( 'grafema_file_uploaded', $_file );
	}

	/**
	 * Upload file via URL.
	 *
	 * @param string $url
	 * @param string $targetDir
	 * @param callable|null $callback
	 * @return Files|Error
	 * @since  2025.1
	 */
	public static function grab( string $url, string $targetDir, ?callable $callback = null ): Files|Error
	{
		$url = Sanitizer::url( $url );
		if ( empty( $url ) ) {
			return new Error( 'file-grab', I18n::_t( 'File URL is not valid.' ) );
		}

		$basename   = basename( $url );
		$extension  = pathinfo( $url, PATHINFO_EXTENSION );
		$filepath   = sprintf( '%s%s', $targetDir, $basename );

		if ( empty( $extension ) ) {
			return new Error( 'file-grab', I18n::_t( 'The file cannot be grabbed because it does not contain an extension.' ) );
		}

		if ( ! is_dir( $targetDir ) ) {
			mkdir( $targetDir, 0755, true );
		}

		$ch   = curl_init( $url );
		$file = fopen( $filepath, 'wb' );

		curl_setopt( $ch, CURLOPT_FILE, $file );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		curl_exec( $ch );

		// check errors
		$error = curl_errno( $ch );
		if ( $error ) {
			$errors = [
				1  => I18n::_t('The URL you passed to the libcurl function uses an unsupported protocol.'),
				3  => I18n::_t('The URL you provided is not properly formatted.'),
				6  => I18n::_t('Couldn\'t resolve the host specified in the URL.'),
				7  => I18n::_t('Failed to connect to the remote host.'),
				8  => I18n::_t('The server sent a strange reply to a FTP-related command.'),
				9  => I18n::_t('Access denied to the resource on the server.'),
				18 => I18n::_t('The file transfer was only partially completed.'),
				22 => I18n::_t('The HTTP server returned an error code.'),
				23 => I18n::_t('An error occurred when writing received data to a local file.'),
				25 => I18n::_t('The upload failed.'),
				27 => I18n::_t('A memory allocation request failed.'),
				28 => I18n::_t('The operation timed out.'),
				35 => I18n::_t('A problem occurred while establishing an SSL/TLS connection.'),
				37 => I18n::_t('The FTP server couldn\'t retrieve the specified file.'),
				47 => I18n::_t('Too many redirects were followed during the request.'),
				51 => I18n::_t('The remote server\'s SSL certificate or SSH md5 fingerprint was deemed not OK.'),
				52 => I18n::_t('The server returned nothing during the request.'),
				56 => I18n::_t('Failure with receiving network data.'),
				58 => I18n::_t('Problem with the local client certificate.'),
				63 => I18n::_t('The requested file size exceeds the allowed limits.'),
				67 => I18n::_t('Failure with sending network data.'),
				94 => I18n::_t('The last received HTTP, FTP, or SMTP response code.'),
				95 => I18n::_t('An SSL cipher problem occurred.'),
				99 => I18n::_t('Something went wrong when uploading the file.'),
			];

			return new Error( 'file-upload', $errors[ $error ] ?? $errors[99] );
		}

		curl_close( $ch );
		fclose( $file );

		$_file = new self( $filepath );
		print_r( $_file );

		/**
		 * Filters the data array for the grabbed file.
		 *
		 * @since 2025.1
		 */
		return Hook::apply( 'grafema_file_grabbed', $_file );
	}

	/**
	 * Write content to file.
	 *
	 * @param string $content
	 * @param bool $after
	 * @return Files
	 */
	public function write( string $content, bool $after = true ): Files
	{
		if ( is_writable( $this->path ) ) {
			$fp = fopen( $this->path, $after ? 'a' : 'w' );
			if ( ! $fp ) {
				$this->errors[] = new Error( 'file-manipulation', I18n::_f( "I can't open the file ':filepath'", $this->path ) );
			} else {
				// writing $content to open file
				if ( fwrite( $fp, $content ) === false ) {
					$this->errors[] = new Error( 'file-manipulation', I18n::_f( "I can't write to the file ':filepath'", $this->path ) );
				}

				fclose( $fp );
			}
		}
		return $this;
	}

	/**
	 * Rewrite some strings in file.
	 *
	 * @param array $content
	 * @return Files
	 */
	public function rewrite( array $content ): Files
	{
		if ( file_exists( $this->path ) && is_readable( $this->path ) && $this->size > 0 ) {
			$file_content = file_get_contents( $this->path );

			foreach ( $content as $field => $value ) {
				$file_content = str_replace( $field, $value, $file_content );
			}

			file_put_contents( $this->path, $file_content );
		}

		return $this;
	}

	/**
	 * Set target directory.
	 *
	 * @param  string $dirpath
	 * @return Files
	 */
	public function relocate( string $dirpath ): Files
	{
		$directory = ( new Dir\Dir( $dirpath ) )->create()->getPath();
		if ( is_dir( $directory ) ) {
			$this->dirpath = $directory;
			// TODO: end this function
		}
		return $this;
	}

	/**
	 * Update file name.
	 *
	 * @param  string $filename New file name.
	 * @return Files
	 */
	public function rename( string $filename ): Files
	{
		return $this;
	}

	/**
	 * Move files.
	 *
	 * @param string $source Name of file or directory with path
	 * @param string $target Target directory
	 * @param array $files   Files to be moved
	 * @return Files
	 */
	public function move( string $source, string $target, array $files ): Files
	{
		$this->mkDir( $target );
		foreach ( $files as $value ) {
			if ( file_exists( $source . $value ) ) {
				rename( $source . $value, $target . $value );
			}
		}
		return $this;
	}

	/**
	 * Copy file.
	 *
	 * @param string $new_name
	 * @return Files
	 */
	public function copy( string $new_name = '' ): Files
	{
		if ( file_exists( $this->path ) ) {
			$filename = pathinfo( $this->path, PATHINFO_FILENAME );
			if ( ! empty( $new_name ) ) {
				$filename = $new_name;
			}

			// TODO: add a check that the name of the new file was not the same as the old one
			$new_filepath = sprintf( '%s/%s.%s', $this->dirpath, $filename, $this->extension );
			if ( ! file_exists( $new_filepath ) ) {
				copy( $this->path, $new_filepath );
			}

			// rewrite path to file
			if ( file_exists( $new_filepath ) ) {
				$this->path = $new_filepath;
			}
		}

		return $this;
	}

	/**
	 * Delete files.
	 *
	 * @return Files
	 */
	public function delete(): Files
	{
		if ( file_exists( $this->path ) ) {
			unlink( $this->path );
		}
		return $this;
	}

	/**
	 * Download file.
	 *
	 * @since  2025.1
	 */
	public function download(): void
	{
		if ( file_exists( $this->path ) ) {
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . $this->basename . '"' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . $this->size );

			// read the file and output it to the browser
			ob_clean();
			flush();
			readfile( $this->path );
			exit;
		}
	}

	/**
	 * Set maximum file size.
	 *
	 * @param int|string $size String designation of the file size, e.g.: 300kb, 20Mb, 0.3Gb, 3Tb
	 * @param string $format
	 * @return Files
	 * @since 2025.1
	 */
	public function setMaxSize( int|string $size, string $format = 'Кb' ): Files
	{
		if ( $size ) {
			$this->maxFileSize = self::reformat( $size, $format );
		}
		return $this;
	}

	/**
	 * Set allowed mime types.
	 *
	 * @param array $mimes
	 * @return Files
	 * @since 2025.1
	 */
	public function setAllowedMimeTypes( array $mimes ): Files
	{
		if ( is_array( $mimes ) ) {
			$this->mimes = $mimes;
		}
		return $this;
	}

	/**
	 * Change the mode of a file.
	 *
	 * @param int $mode
	 * @return Files
	 */
	public function setMode( int $mode ): Files
	{
		if ( ! chmod( $this->path, $mode ) ) {
			$this->errors[] = new Error( 'file-manipulations', I18n::_t( 'Failed to update file access rights' ) );
		}
		return $this;
	}
}
