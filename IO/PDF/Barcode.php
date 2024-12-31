<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\PDF;

class Barcode
{
    /**
     * Default barcode type
     */
    public const DEFAULT_BARCODE_TYPE = 'C39';

    /**
     * Render as HTML
     *
     * @param string $type
     *		C39 : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
     *		C39+ : CODE 39 with checksum
     *		C39E : CODE 39 EXTENDED
     *		C39E+ : CODE 39 EXTENDED + CHECKSUM
     *		C93 : CODE 93 - USS-93
     *		S25 : Standard 2 of 5
     *		S25+ : Standard 2 of 5 + CHECKSUM
     *		I25 : Interleaved 2 of 5
     *		I25+ : Interleaved 2 of 5 + CHECKSUM
     *		C128 : CODE 128
     *		C128A : CODE 128 A
     *		C128B : CODE 128 B
     *		C128C : CODE 128 C
     *		EAN2 : 2-Digits UPC-Based Extension
     *		EAN5 : 5-Digits UPC-Based Extension
     *		EAN8 : EAN 8
     *		EAN13 : EAN 13
     *		UPCA : UPC-A
     *		UPCE : UPC-E
     *		MSI : MSI (Variation of Plessey code)
     *		MSI+ : MSI + CHECKSUM (modulo 11)
     *		POSTNET : POSTNET
     *		PLANET : PLANET
     *		RMS4CC : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
     *		KIX : KIX (Klant index - Customer index)
     *		IMB: Intelligent Mail Barcode - Onecode - USPS-B-3200
     *		CODABAR : CODABAR
     *		CODE11 : CODE 11
     *		PHARMA : PHARMACODE
     *		PHARMA2T : PHARMACODE TWO-TRACKS
     * @param int $width
     * @param int $height
     * @param string $color
     * @return string
     */
    public static function renderAsHTML($code, $type, $width, $height, $color)
    {
        $barcodeobj = new \TCPDFBarcode($code, $type);
        return $barcodeobj->getBarcodeHTML(2, 30, 'black');
    }
}
