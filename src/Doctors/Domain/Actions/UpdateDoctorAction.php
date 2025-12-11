<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\Actions;

use Illuminate\Support\Facades\DB;
use Lightit\Doctors\Domain\DataTransferObjects\DoctorDto;
use Lightit\Doctors\Domain\Models\Doctor;

class UpdateDoctorAction
{
    public function execute(Doctor $doctor, DoctorDto $doctorDto): Doctor
    {
        return DB::transaction(function () use ($doctor, $doctorDto): Doctor {
            $doctor->name = $doctorDto->name;
            $doctor->saveOrFail();

            if ($doctorDto->clinicIds !== []) {
                $doctor->clinics()->sync($doctorDto->clinicIds);
            }

            return $doctor;
        });
    }
}
