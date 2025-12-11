<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\Actions;

use Illuminate\Support\Facades\DB;
use Lightit\Doctors\Domain\DataTransferObjects\DoctorDto;
use Lightit\Doctors\Domain\Models\Doctor;

class StoreDoctorAction
{
    public function execute(DoctorDto $doctorDto): Doctor
    {
        return DB::transaction(function () use ($doctorDto) : Doctor
        {
            $doctor = new Doctor();
            $doctor->name = $doctorDto->name;
            $doctor->saveOrFail();

            if ($doctorDto->clinicIds !== []) {
                $doctor->clinics()->sync($doctorDto->clinicIds);
            }

            return $doctor;
        });
    }
}
