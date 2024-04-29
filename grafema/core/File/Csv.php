<?php

namespace Grafema\File;

class Csv {

	/**
	 * @var string|mixed
	 */
	private string $delimiter;

	/**
	 * @var string|mixed
	 */
	private string $enclosure;

	/**
	 * @var string|mixed
	 */
	private string $linebreak;

	/**
	 * @var string
	 */
	private string $csv = '';

	/**
	 * @param string $delimiter
	 * @param string $enclosure
	 * @param string $linebreak
	 */
	public function __construct( string $delimiter = 'auto', string $enclosure = 'auto', string $linebreak = 'auto' ) {
		$this->delimiter = $delimiter;
		$this->enclosure = $enclosure;
		$this->linebreak = $linebreak;
	}

	/**
	 * @param $filename_or_data
	 * @param string $delimiter
	 * @param string $enclosure
	 * @param string $linebreak
	 * @return array
	 */
	public static function import( $filename_or_data, string $delimiter = 'auto', string $enclosure = 'auto', string $linebreak = 'auto' ): array {
		$csv = new static( $delimiter, $enclosure, $linebreak );
		return $csv->toArray( $filename_or_data );
	}

	/**
	 * @param $items
	 * @param string $delimiter
	 * @param string $enclosure
	 * @param string $linebreak
	 * @return false|string
	 */
	public static function export( $items, string $delimiter = ',', string $enclosure = '"', string $linebreak = "\r\n" ): bool|string {
		$csv = new static( $delimiter, $enclosure, $linebreak );
		return $csv->fromArray( $items );
	}

	/**
	 * @param bool $set
	 * @return string
	 */
	public function delimiterOldNonOptimized( bool $set = false ): string {
		if ( $set !== false ) {
			return $this->delimiter = $set;
		}
		if ( $this->delimiter === 'auto' ) {
			// detect delimiter
			if ( str_contains( $this->csv, $this->enclosure . ',' ) ) {
				$this->delimiter = ',';
			} elseif ( str_contains( $this->csv, $this->enclosure . "\t" ) ) {
				$this->delimiter = "\t";
			} elseif ( str_contains( $this->csv, $this->enclosure . ';' ) ) {
				$this->delimiter = ';';
			} elseif ( str_contains( $this->csv, ',' ) ) {
				$this->delimiter = ',';
			} elseif ( str_contains( $this->csv, "\t" ) ) {
				$this->delimiter = "\t";
			} elseif ( str_contains( $this->csv, ';' ) ) {
				$this->delimiter = ';';
			} else {
				$this->delimiter = ',';
			}
		}
		return $this->delimiter;
	}

	public function delimiter( bool $set = false ): string {
		if ( $set !== false ) {
			return $this->delimiter = $set;
		}

		if ( $this->delimiter === 'auto' ) {
			$delimiters = [ ',', "\t", ';' ];
			foreach ( $delimiters as $delimiter ) {
				if ( str_contains( $this->csv, $this->enclosure . $delimiter ) ) {
					return $this->delimiter = $delimiter;
				}
			}
			$this->delimiter = ',';
		}

		return $this->delimiter;
	}

	/**
	 * @param bool $set
	 * @return string
	 */
	public function enclosure( bool $set = false ): string {
		if ( $set !== false ) {
			return $this->enclosure = $set;
		}
		if ( $this->enclosure === 'auto' ) {
			if ( str_contains( $this->csv, '"' ) ) {
				$this->enclosure = '"';
			} elseif ( str_contains( $this->csv, "'" ) ) {
				$this->enclosure = "'";
			} else {
				$this->enclosure = '"';
			}
		}
		return $this->enclosure;
	}

	/**
	 * @param bool $set
	 * @return string
	 */
	public function linebreak( bool $set = false ): string {
		if ( $set !== false ) {
			return $this->linebreak = $set;
		}
		if ( $this->linebreak === 'auto' ) {
			if ( str_contains( $this->csv, "\r\n" ) ) {
				$this->linebreak = "\r\n";
			} elseif ( str_contains( $this->csv, "\n" ) ) {
				$this->linebreak = "\n";
			} elseif ( str_contains( $this->csv, "\r" ) ) {
				$this->linebreak = "\r";
			} else {
				$this->linebreak = "\r\n";
			}
		}
		return $this->linebreak;
	}

	/**
	 * @param $filename
	 * @return array
	 */
	private function toArray( $filename ): array {
		if ( ! file_exists( $filename ) ) {
			return [];
		}

		$this->csv = file_get_contents( $filename );

		$linebreak = $this->linebreak();
		$enclosure = $this->enclosure();
		$delimiter = $this->delimiter();

		//$this->csv = mb_convert_encoding( $this->csv, 'UTF-8', mb_detect_encoding( $this->csv, $file_encodings ) );
		$this->csv = iconv( mb_detect_encoding( $this->csv, mb_detect_order(), true ), 'UTF-8', $this->csv );

		$lines = explode( $linebreak, trim( $this->csv ) );
		$lines = array_filter( $lines );
		$lines = array_map( 'trim', $lines );

		unset( $this->csv );

		$data = [];
		foreach ( $lines as $key => $line ) {
			$data[] = str_getcsv( $line, $delimiter, $enclosure );
			unset( $lines[ $key ] );
		}

		return $data;
	}

	/**
	 * @param $items
	 * @return false|string
	 */
	private function fromArray( $items ): false|string {
		if ( ! is_array( $items ) ) {
			trigger_error( 'CSV::export array required', E_USER_WARNING );
			return false;
		}

		$delimiter = $this->delimiter();
		$enclosure = $this->enclosure();
		$linebreak = $this->linebreak();

		$result = '';
		foreach ( $items as $i ) {
			$line = '';

			foreach ( $i as $v ) {
				if ( str_contains( $v, $enclosure ) ) {
					$v = str_replace( $enclosure, $enclosure . $enclosure, $v );
				}

				if ( str_contains( $v, $delimiter ) || str_contains( $v, $enclosure ) || str_contains( $v, $linebreak ) ) {
					$v = $enclosure . $v . $enclosure;
				}
				$line .= $line ? $delimiter . $v : $v;
			}
			$result .= $result ? $linebreak . $line : $line;
		}

		return $result;
	}
}
