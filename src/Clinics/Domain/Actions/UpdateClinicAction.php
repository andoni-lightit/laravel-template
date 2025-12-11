<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\Actions;

use Illuminate\Support\Facades\DB;
use Lightit\Clinics\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinics\Domain\Models\Clinic;

class UpdateClinicAction
{
    public function execute(Clinic $clinic, ClinicDto $data): Clinic
    {
        return DB::transaction(function () use ($clinic, $data) {
            $clinic->name = $data->name;
            $clinic->address = $data->address;
            $clinic->saveOrFail();

            if (!empty($data->doctorIds)) {
                $clinic->doctors()->sync($data->doctorIds);
            }
            return $clinic;
        });
    }
}
