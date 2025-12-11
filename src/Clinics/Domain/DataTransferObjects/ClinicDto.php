<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\DataTransferObjects;

readonly class ClinicDto
{
    /**
     * @param array<int, int> $doctorIds
     */
    public function __construct(
        public string $name,
        public string|null $address,
        public array $doctorIds = [],
    ) {
    }
}
