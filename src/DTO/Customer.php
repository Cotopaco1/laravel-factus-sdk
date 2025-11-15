<?php

namespace Cotopaco\Factus\DTO;

class Customer
{
    public function __construct(
        public int $identificationDocumentId,
        public string $identification,
        public int $legalOrganizationId,
        public int $tributeId,
        public ?int $dv = null,
        public ?string $company = null,
        public ?string $tradeName = null,
        public ?string $names = null,
        public ?string $address = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?int $municipalityId = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'identification_document_id' => $this->identificationDocumentId,
            'identification' => $this->identification,
            'dv' => $this->dv,
            'company' => $this->company,
            'trade_name' => $this->tradeName,
            'names' => $this->names,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'legal_organization_id' => $this->legalOrganizationId,
            'tribute_id' => $this->tributeId,
            'municipality_id' => $this->municipalityId,
        ], fn ($value) => $value !== null);
    }
}
