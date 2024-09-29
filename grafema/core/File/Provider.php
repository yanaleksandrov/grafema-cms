<?php
namespace Grafema\File;

use Grafema\Helpers\Humanize;

class Provider {

	/**
	 * Max file size.
	 */
	public int $maxSize;

	/**
	 * Allowed mime types.
	 */
	public array $mimes = [
		// image formats
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'webp'         => 'image/webp',
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

	/**
	 * Validation errors.
	 */
	public array $errors = [];

	/**
	 *
	 */
	public function __constructor() {
		$this->maxSize = Humanize::toBytes( ini_get( 'upload_max_filesize' ) );
	}
}
