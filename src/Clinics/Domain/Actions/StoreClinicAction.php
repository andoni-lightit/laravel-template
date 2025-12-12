<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\Actions;

use Lightit\Clinics\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinics\Domain\Models\Clinic;

class StoreClinicAction
{
    public function execute(ClinicDto $data): Clinic
    {
        $clinic = new Clinic();
        $clinic->name = $data->name;
        $clinic->address = $data->address;

        $clinic->saveOrFail();

        return $clinic;
    }
}
