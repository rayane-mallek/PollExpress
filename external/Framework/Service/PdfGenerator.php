<?php

namespace Framework\Service;

use Dompdf\Dompdf;

class PdfGenerator
{
    protected Dompdf $dompdf;

    public function initialize()
    {
        $this->dompdf = new Dompdf();
    }

    public function generate(string $html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();

        $attachment=false;

        $options=[
            "Attachment" => $attachment
        ];
        $this->dompdf->stream('Sondage', $options);
        exit();
    }
}