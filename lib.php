<?php

defined('MOODLE_INTERNAL') || die;

require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');

function generate_pdf01($bookid, $courseid) {
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

global $DB;

$name = $DB->get_field('book', 'name', array('id' => $bookid), IGNORE_MULTIPLE);

$course_name = $DB->get_field('course', 'fullname', array('id' => $courseid), MUST_EXIST);
// set document information
$pdf->SetCreator('EFST');
//$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle($name);
$pdf->SetSubject($course_name);
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 055', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
/*if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}*/

// ---------------------------------------------------------

$pdf->AddPage();
$pdf->SetFont('freeserif', 'B', 20);
$pdf->Write(10, $name, '', 0, 'C', true, 0, false, false, 0);
$pdf->SetFont('freeserif', 'B', 16);
$pdf->Write(10, $course_name, '', 0, 'C', true, 0, false, false, 0);

$chapterids = $DB->get_fieldset_sql('SELECT id FROM {book_chapters} WHERE bookid = ?', array($bookid));

$chapter = 1;
$subchapter = 1;

foreach ($chapterids as $id) {

    ChromePhp::log($id);

    $pdf->AddPage();

    $sql = 'SELECT id, subchapter, title, content FROM
            {book_chapters} WHERE id = ? AND bookid = ?';
    $params = array('id'=>$id, 'bookid' => $bookid);

    ChromePhp::log('get records');
    $data = $DB->get_records_sql($sql, $params);

    if($data[$id]->subchapter == '1') {

        $subchapter_title = $chapter.".".$subchapter." ".$data[$id]->title;
        ChromePhp::log($subchapter_title);

        $pdf->SetFont('freeserif', 'B', 14);

        $pdf->Bookmark($subchapter_title, 1, 0, '', '', array(0,0,0));
        $pdf->Cell(0, 10, $subchapter_title, 0, 1, 'L');

        $subchapter++;

    } else {

        $pdf->SetFont('freeserif', 'B', 16);

        $chapter_title = $chapter." ".$data[$id]->title;

        ChromePhp::log($chapter_title);

        $pdf->Bookmark($chapter_title, 0, 0, '', 'B', array(0,0,0));
        $pdf->Cell(0, 6, $chapter_title, 0, 1, 'L');

        $chapter++;

    }

    $pdf->SetFont('freeserif', '', 12);

    $pdf->WriteHTML($data[$id]->content);
}


$pdf->addTOCPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true);
$pdf->addTOC('2', 'freeserif', '.', 'Sadržaj', 'B', array(0,0,0));
$pdf->endTOCPage();


$pdf->Output($name.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	
}