<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Files;

use Grafema\Helpers\Humanize;

/**
 * The File class provides a convenient and easy-to-use API for working with files.
 * It supports working with various types: CSV, SVG and images of different formats.
 *
 * You can perform a wide range of operations: reading and writing to a file,
 * downloading and capturing, moving and copying files, and much more.
 *
 * @since      1.0.0
 */
class Handler {

	public string $path = '';

	/**
	 * Handler constructor.
	 *
	 * @param string $filepath
	 */
	public function __construct( string $filepath = '' ) {
		$this->path = $filepath;
	}

	/**
	 *
	 */
	protected static function getAllowedMimeTypes() {
		return [
			// image formats
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'webp'         => 'image/webp',
			'avif'         => 'image/avif',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',

			// vector formats
			'svg' => 'image/svg+xml',

			// video formats
			'asf|asx'      => 'video/x-ms-asf',
			'wmv'          => 'video/x-ms-wmv',
			'wmx'          => 'video/x-ms-wmx',
			'wm'           => 'video/x-ms-wm',
			'avi'          => 'video/avi',
			'divx'         => 'video/divx',
			'flv'          => 'video/x-flv',
			'mov|qt'       => 'video/quicktime',
			'mpeg|mpg|mpe' => 'video/mpeg',
			'mp4|m4v'      => 'video/mp4',
			'ogv'          => 'video/ogg',
			'webm'         => 'video/webm',
			'mkv'          => 'video/x-matroska',

			// text formats
			'txt' => 'text/plain',
			'csv' => 'text/csv',
			'tsv' => 'text/tab-separated-values',
			'ics' => 'text/calendar',
			'rtx' => 'text/richtext',

			// audio formats
			'mp3|m4a|m4b' => 'audio/mpeg',
			'ra|ram'      => 'audio/x-realaudio',
			'wav'         => 'audio/wav',
			'ogg|oga'     => 'audio/ogg',
			'mid|midi'    => 'audio/midi',
			'wma'         => 'audio/x-ms-wma',
			'wax'         => 'audio/x-ms-wax',
			'mka'         => 'audio/x-matroska',

			// misc application formats
			'rtf'     => 'application/rtf',
			'pdf'     => 'application/pdf',
			'swf'     => 'application/x-shockwave-flash',
			'class'   => 'application/java',
			'tar'     => 'application/x-tar',
			'zip'     => 'application/zip',
			'gz|gzip' => 'application/x-gzip',
			'rar'     => 'application/rar',
			'7z'      => 'application/x-7z-compressed',
			'exe'     => 'application/x-msdownload',

			// MS Office formats
			'doc'                          => 'application/msword',
			'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
			'wri'                          => 'application/vnd.ms-write',
			'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
			'mdb'                          => 'application/vnd.ms-access',
			'mpp'                          => 'application/vnd.ms-project',
			'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
			'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
			'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
			'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
			'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
			'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
			'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
			'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
			'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
			'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
			'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
			'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
			'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
			'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',

			// OpenOffice formats
			'odt' => 'application/vnd.oasis.opendocument.text',
			'odp' => 'application/vnd.oasis.opendocument.presentation',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			'odg' => 'application/vnd.oasis.opendocument.graphics',
			'odc' => 'application/vnd.oasis.opendocument.chart',
			'odb' => 'application/vnd.oasis.opendocument.database',
			'odf' => 'application/vnd.oasis.opendocument.formula',
		];
	}

	/**
	 * Get file url.
	 *
	 * @return string
	 */
	protected function getUrl(): string {
		return str_replace( GRFM_PATH, '', $this->path );
	}

	/**
	 * Get file type.
	 *
	 * @return string
	 */
	protected function getType(): string {
		[ $type ] = explode( '/', $this->mime() );

		return $type;
	}

	/**
	 * Set maximum file size.
	 *
	 * @param string $size String designation of the file size, e.g.: 300kb, 20Mb, 0.3Gb, 3Tb
	 * @return Handler
	 * @since 1.0.0
	 */
	protected function setMaxSize( string $size ): Handler
	{
		if ( $size ) {
			$this->maxSize = Humanize::toBytes( $size );
		}
		return $this;
	}

	/**
	 * Convert bytes to human-readable file size.
	 *
	 * @param string|int $bytes
	 * @param string $format
	 * @return float
	 * @since 1.0.0
	 */
	protected static function reformat( string|int $bytes, string $format = '' ): float {
		$format  = ucfirst( strtolower( $format ) );
		$formats = [
			'Kb' => 1,
			'Mb' => 2,
			'Gb' => 3,
			'Tb' => 4,
		];

		$result = $bytes / pow( 1024, $formats[$format] ?? 0 );
		$result = number_format( $result, 2, '.' );
		$result = rtrim( $result, '0' );
		if ( substr( $result, -1 ) === '.' ) {
			$result = rtrim( $result, '.' );
		}

		return round( floatval( $result ), 2 );
	}

	/**
	 * Convert bytes to human-readable file size.
	 *
	 * @param string|int $bytes
	 * @param string $format
	 * @return string
	 * @since 1.0.0
	 */
	protected static function humanise( string|int $bytes, string $format = '' ): string {
		$format  = ucfirst( strtolower( $format ) );
		$i       = floor( log( $bytes ) / log( 1024 ) );
		$sizes   = [ 'b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb' ];

		if ( $format && in_array( $format, $sizes, true ) ) {
			$i = array_search( $format, $sizes );
		}

		$result = round( $bytes / pow(1024, $i ), 2 );
		$result = number_format( $result, 2 );
		$result = rtrim( $result, '0' );
		if ( substr( $result, -1 ) === '.' ) {
			$result = rtrim( $result, '.' );
		}

		return sprintf( '%s %s', $result, $sizes[ $i ] );
	}

	/**
	 * Retrieves the maximum upload file size in bytes.
	 *
	 * @return int The size in bytes.
	 */
	public static function getMaxUploadSizeInBytes(): int {
		$maxUploadSize = trim(ini_get('upload_max_filesize'));
		$last          = strtolower($maxUploadSize[strlen($maxUploadSize) - 1]);
		$maxUploadSize = intval( $maxUploadSize );

		switch ($last) {
			case 'g':
				$maxUploadSize *= 1024;
			case 'm':
				$maxUploadSize *= 1024;
			case 'k':
				$maxUploadSize *= 1024;
		}

		return $maxUploadSize;
	}

	/**
	 * Get the file's last modification time.
	 */
	protected function modified(): int
	{
		return filemtime( $this->path );
	}

	/**
	 * Get the MIME type of file.
	 */
	protected function mime(): string
	{
		return finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $this->path );
	}

	/**
	 * Get the extension of a file.
	 */
	protected function extension(): string
	{
		return pathinfo( $this->path, PATHINFO_EXTENSION );
	}

	/**
	 * Determine if a file exists.
	 */
	protected function exists(): bool
	{
		return file_exists( $this->path );
	}

	/**
	 * Get the size of a file.
	 */
	protected function size(): int
	{
		return filesize( $this->path );
	}

	/**
	 * Create unique signature of the file.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	protected function getHash(): string
	{
		return hash_file( 'md5', $this->path );
	}

	/**
	 * Get the file name from a path.
	 */
	protected function filename(): string
	{
		return pathinfo( $this->path, PATHINFO_FILENAME );
	}

	/**
	 * Get the base name from a path.
	 */
	protected function basename(): string
	{
		return pathinfo( $this->path, PATHINFO_BASENAME );
	}

	/**
	 * Get the directory name from a path.
	 */
	protected function dirname(): string
	{
		return pathinfo( $this->path, PATHINFO_DIRNAME );
	}
}
