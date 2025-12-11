<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\DataTransferObjects;


readonly class DoctorDto
{
    /**
     * @param array<int, int> $clinicIds
     */
    public function __construct(
        public string $name,
        public array $clinicIds = [],
    ) {
    }
}
