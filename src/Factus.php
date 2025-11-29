<?php

namespace Cotopaco\Factus;

use Cotopaco\Factus\Http\Clients\Invoice\InvoiceClient;

class Factus
{
    public function __construct(
        public InvoiceClient $invoice
    )
    {
    }/**/

    /**
     * Get invoice client
     * @return InvoiceClient
     * */
    public function invoice(): InvoiceClient
    {
        return $this->invoice;
    }

}
