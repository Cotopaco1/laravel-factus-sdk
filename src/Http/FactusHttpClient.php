<?php

namespace Cotopaco\Factus\Http;

use Cotopaco\Factus\Constants\CacheConstants;
use Cotopaco\Factus\Contracts\FactusClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


abstract class FactusHttpClient
{
    protected string $version = 'v1';
    protected string $baseUrl;

    public function __construct(

    )
    {
        $this->baseUrl = config('factus.production')
            ? config('factus.base_url')
            : config('factus.sandbox_base_url');

    }

    public function getHeaders() : array
    {
        return [
            'Authorization' => 'Bearer ' . self::getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    public  function getAccessToken(): String
    {
        $tkn = Cache::get(CacheConstants::ACCESS_TOKEN);

        if($tkn) return $tkn;

        $data = $this->requestNewAccessToken();

        $ttl = max($data['expires_in'] - 60, 60); // expira un poco antes

        Cache::put(CacheConstants::ACCESS_TOKEN, $data['access_token'], $ttl);

        Cache::put(CacheConstants::REFRESH_ACCESS_TOKEN, $data['refresh_token'], $ttl);

        return  $data['access_token'];
    }

    /**
     *
     * Solicita un nuevo access_token
     *
     * @return array{access_token: string, refresh_token: string, expires_in: int, token_type: string}
     */
    public function requestNewAccessToken() : array
    {
        $url = $this->baseUrl . '/oauth/token';
        logger("RequestNewAccessToken : ", compact('url'));
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
        ])
            ->asForm()
            ->post( $url, [
            'grant_type'    => 'password',
            'client_id'     => config('factus.client.id'),
            'client_secret' => config('factus.client.secret'),
            'username'      => config('factus.username'),
            'password'      => config('factus.password'),
        ]);

        if($response->successful()){
            return $response->json();

        }else{
            abort(500, 'Error al obtener el access_token');
        }
    }

    /**
     * Inicializa el cliente con los headers de autorizacion + headers de argumento.
     * Lanza throw si hay error en http code.
     */
    protected function initClient(array $headers = []) : PendingRequest
    {
        return Http::withHeaders(
            [
                ... $this->getHeaders(),
                ... $headers
            ]
        )   ->baseUrl($this->baseUrl . "/{$this->version}")
            ->throw();
    }

    /**
     * Envia y recibe la peticion en json
     * */
    protected function jsonClient() : PendingRequest
    {
        return $this->initClient(['Accept' => 'application/json']);
    }

    /**
     * Maneja los errores al hacer peticion con un http client
     * */
    protected function handleError(\Closure $cb) : mixed
    {
        try {
            return $cb();
        }catch (RequestException $exception){
            $payload = [
                'base_url'   => $this->baseUrl,
                'version'    => $this->version,
                'statusCode' => $exception->getCode(),
                'message'    => $exception->getMessage(),
                'response'   => $exception->response->json(),
            ];

            logger()->error(
                "Request error to Factus API:\n" .
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            throw $exception;
        } catch (ConnectionException $exception){
            $payload = [
                'base_url' => $this->baseUrl,
                'version' => $this->version,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ];
            logger()->error(
                "Connection error to Factus API : \n" .
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            throw $exception;
        }
    }

}
