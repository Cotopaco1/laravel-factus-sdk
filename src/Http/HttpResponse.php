<?php

namespace Cotopaco\Factus\Http;

/* Base HttpResponse */
abstract class HttpResponse
{
    public int $statusCode;

    public string $status;

    public string $message;

    public array $rawData;

    public function __construct(public \Illuminate\Http\Client\Response $request)
    {
        $this->statusCode = $this->request->getStatusCode();
        $data = $this->request->json();
        $this->rawData = $data;
        $this->status = $data['status'] ?? '';
        $this->message = $data['message'] ?? '';

    }

    /**
     * Represents of response in array
     * */
    public function toArray() {}
}
