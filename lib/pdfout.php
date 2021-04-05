<?php
use Dompdf\Dompdf;
class PdfOut extends Dompdf
{
 public static function sendpdf($html = '', $defaultFont ='Courier', $Attachment = false)
 {
  rex_response::cleanOutputBuffers(); // OutputBuffer leeren
        $dompdf = new self();
        $dompdf->loadHtml($html);
        // Optionen festlegen
        $dompdf->set_option('font_cache', rex_path::addonCache('pdfout', 'fonts'));
        $dompdf->set_option('defaultFont', $defaultFont);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('dpi', '100');
        // Rendern des PDF
        $dompdf->render();
        // Ausliefern des PDF
        header('Content-Type: application/pdf');
        $dompdf->stream('readme', array('Attachment' => $Attachment)); // bei true wird Download erzwungen
        die();
 }
 
}