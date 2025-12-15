<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\Actions;

use Illuminate\Support\Facades\DB;
use Lightit\Clinics\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinics\Domain\Models\Clinic;

class StoreClinicAction
{
    public function execute(ClinicDto $data): Clinic
    {
        return DB::transaction(function () use ($data): Clinic {
            $clinic = new Clinic();
            $clinic->name = $data->name;
            $clinic->address = $data->address;
            $clinic->saveOrFail();

            if ($data->doctorIds !== []) {
                $clinic->doctors()->syncWithoutDetaching($data->doctorIds);
            }

            return $clinic;
        });
    }
}
