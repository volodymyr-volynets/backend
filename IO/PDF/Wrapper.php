<?php

namespace Numbers\Backend\IO\PDF;
class Wrapper extends \TCPDF {

	/**
	 * Options
	 *
	 * @var array
	 */
	public $__options = [];

	/**
	 * Odd color
	 */
	const ODD_COLOR = 'EEEEEE';

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = []) {
		// preset settings
		$this->__options['orientation'] = $options['orientation'] ?? 'P';
		$this->__options['encoding'] = 'UTF-8';
		$this->__options['unit'] = 'mm';
		$this->__options['format'] = $options['format'] ?? \I18n::$options['print_format'] ?? 'LETTER';
		$this->__options['font'] = ['family' => $options['font'] ?? \I18n::$options['print_font'] ?? 'helvetica', 'style' => '', 'size' => 8];
		$this->__options['title'] = $options['title'] ?? null;
		$this->__options['skip_header_time'] = $options['skip_header_time'] ?? false;
		// call parent constructor
		parent::__construct(
			$this->__options['orientation'],
			$this->__options['unit'],
			$this->__options['format'],
			true,
			$this->__options['encoding'],
			false
		);
		// set document information
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor(\User::get('name'));
		$this->SetTitle(\Application::$controller->title);
		$this->SetSubject(\Application::$controller->title);
		$this->SetKeywords(\Application::$controller->title);
		// set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// disable auto break
		$this->SetAutoPageBreak(false, 0);
		// set default font subsetting mode
		$this->setFontSubsetting(true);
		// set color for background
		$this->SetFillColor(255, 255, 255);
		// set font
		$this->SetFont($this->__options['font']['family'], $this->__options['font']['style'], $this->__options['font']['size']);
	}

	/**
	 * Header
	 */
	public function Header() {
		$this->SetFont($this->__options['font']['family'], 'B', 8);
		$this->SetXY(15, 10);
		$title = $this->__options['title'] ?? i18n(null, \Application::$controller->title);
		$this->Cell(0, 10, $title, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		// timestamp
		if (!$this->__options['skip_header_time']) {
			$this->SetXY(15, 15);
			$this->SetFont($this->__options['font']['family'], '', 8);
			$this->Cell(0, 10, \Format::id(\Format::datetime(\Format::now('datetime'))), 0, false, 'L', 0, '', 0, false, 'T', 'M');
		}
	}

	/**
	 * Footer
	 */
	public function Footer() {
		$this->SetFont($this->__options['font']['family'], '', 8);
		$page_number = i18n(null, 'Page [number]/[total]', [
			'replace' => [
				'[number]' => $this->getAliasNumPage(),
				'[total]' => $this->getAliasNbPages()
			]
		]);
		$this->SetXY(15, -15);
		$this->Cell(0, 10, $page_number, 0, false, 'R', 0, '', 0, false, 'T', 'M');
		$this->SetXY(15, -20);
		$this->Cell(0, 10, i18n(null, \Application::$controller->title) . ' (#' . \Format::id(\Application::$controller->controller_id) . ')', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetXY(15, -15);
		$this->Cell(0, 10, i18n(null, 'By:') . ' ' . \User::get('name') . ', ' . i18n(null, ' On:') . ' ' . \Format::id(\Format::datetime(\Format::now('datetime'))), 0, false, 'L', 0, '', 0, false, 'T', 'M');
	}

}
