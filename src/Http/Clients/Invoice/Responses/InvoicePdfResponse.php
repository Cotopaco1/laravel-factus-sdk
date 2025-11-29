<?php

namespace Cotopaco\Factus\Http\Clients\Invoice\Responses;

use Cotopaco\Factus\Http\HttpResponse;
use Illuminate\Http\Client\Response;

class InvoicePdfResponse extends HttpResponse
{
    public string $fileName;

    public string $pdfBase64Encoded;

    public function __construct(Response $response)
    {
        parent::__construct($response);

        $responseData = $this->rawData['data'] ?? [];

        $this->fileName = $responseData['file_name'] ?? '';
        $this->pdfBase64Encoded = $responseData['pdf_base_64_encoded'] ?? '';
    }

    /**
     * Obtiene el nombre del archivo PDF
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Obtiene el contenido PDF codificado en base64
     */
    public function getPdfBase64(): string
    {
        return $this->pdfBase64Encoded;
    }

    /**
     * Decodifica el PDF de base64 a binario
     */
    public function getPdfBinary(): string
    {
        return base64_decode($this->pdfBase64Encoded);
    }

    /**
     * Guarda el PDF en el sistema de archivos
     *
     * @param  string  $path  - Ruta donde guardar el archivo
     * @return bool - true si se guardó exitosamente
     */
    public function savePdfToFile(string $path): bool
    {
        $binaryContent = $this->getPdfBinary();

        return file_put_contents($path, $binaryContent) !== false;
    }

    /**
     * Obtiene el PDF como respuesta HTTP para descarga
     */
    public function getPdfDownloadResponse(): array
    {
        return [
            'content' => $this->getPdfBinary(),
            'headers' => [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$this->getFileName().'.pdf"',
                'Content-Length' => strlen($this->getPdfBinary()),
            ],
        ];
    }

    /**
     * Verifica si el PDF fue descargado exitosamente
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'OK' && ! empty($this->pdfBase64Encoded);
    }
}
