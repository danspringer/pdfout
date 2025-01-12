<?php
use Dompdf\Dompdf;

class PdfOut extends Dompdf
{
    public static function sendPdf($name = 'pdf_file', $html = '', $orientation = 'portrait', $defaultFont = 'Courier', $attachment = false, $remoteFiles = true, $saveToPath = '')
    {
        rex_response::cleanOutputBuffers(); // OutputBuffer leeren
        $pdf = new self();
        $pdf->loadHtml($html);

        // Optionen festlegen
        $options = $pdf->getOptions();
        $options->setChroot(rex_path::frontend());
        $options->setDefaultFont($defaultFont);
        $options->setDpi(100);
        $options->setFontCache(rex_path::addonCache('pdfout', 'fonts'));
        $options->setIsRemoteEnabled($remoteFiles);
        $pdf->setOptions($options);
        $pdf->setPaper('A4', $orientation);

        // Rendern des PDF
        $pdf->render();

        // Ausliefern des PDF - entweder anzeigen der File oder auf Server speichern
        if($saveToPath == '') {
            header('Content-Type: application/pdf');
            $pdf->stream(rex_string::normalize($name), array('Attachment' => $attachment));
            die();
        } else {
            rex_file::put($saveToPath.rex_string::normalize($name).'.pdf', $pdf->output());
        }

    }

    public static function viewer($file = '')
    {
        if ($file!='')
        {
            return rex_url::assets('addons/pdfout/vendor/web/viewer.html?file='.urlencode($file));
        }
        else {
            return '#pdf_missing';
        }
    }

}

