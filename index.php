<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/chromephp/ChromePhp.php');

global $DB, $CFG, $PAGE, $COURSE;

ChromePhp::log('hi');
//$DB->set_debug(true);

require_login();

$context = context_system::instance();

require_capability('local/pdf:download', $context);

$PAGE->set_context($context);

$PAGE->set_url(new moodle_url('/local/pdf/index.php'));

$courseid = $COURSE->id;

$bookid = '2';
ChromePhp::log('function');

/*$chapterids = $DB->get_fieldset_sql('SELECT id FROM {book_chapters} WHERE bookid = ?', array($bookid));

foreach ($chapterids as $id) {

	$sql = 'SELECT id, subchapter, title, content FROM
            {book_chapters} WHERE id = ? AND bookid = ?';
    $params = array('id'=>$id, 'bookid' => $bookid);

    $data = $DB->get_records_sql($sql, $params);

    var_dump($data);
}*/

generate_pdf01($bookid, $courseid);

/*global $DB, $CFG;

ChromePhp::log('hi');
$DB->set_debug(true);

require_login();

$context = context_system::instance();

require_capability('local/pdf:download', $context);

$PAGE->set_context($context);

$PAGE->set_url(new moodle_url('/local/pdf/index.php'));

$name = $DB->get_field('book', 'name', array('id' => 2), IGNORE_MULTIPLE);

//convert to better charser
//$name = utf8_decode($name);
ChromePhp::log('new TCPDF');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetTitle($name);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set font
$pdf->SetFont('times', '', 14, '', true);

$content = $DB->get_records_sql('SELECT content FROM {book_chapters} WHERE bookid = ?', array(2));
// Add a page
$pdf->AddPage();

$html = '
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
';

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$pdf->Output($name.'.pdf', 'I');*/

/*$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 16);
$pdf->SetTitle($name, TRUE);

$pdf->Cell($name, 0, 1, C);

$pdf->Output();

$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,$name);
$pdf->AddPage();

$chapterids = $DB->get_records_sql('SELECT id FROM {book_chapters} WHERE bookid = ?', array(2));

foreach ($chapterids as $id) {

	ChromePhp::log($id);

	$sql = 'SELECT subchapter, title AND content FROM
			{book_chapters} WHERE bookid = ? AND id= ? ';
	$params = array('bookid' => 2, 'id'=>$id);
	
	$data = $DB->get_records_sql($sql, $params);

	$title = $DB->get_field('book_chapters', 'title', array('id' => $id->id, 'bookid' => '2'));

	if($data->subchapter == '1') {

		$pdf->Bookmark($data->title,1,-1);
		$pdf->Cell(0,6,$data->title);
	} else {

		$pdf->Bookmark($data->title);
		$pdf->Cell(0,6,$data->title,0,1);
	}

	$pdf->WriteHTML($data->content);
}

$pdf->Output($name.".pdf", 'D');
$pdf->Output();*/