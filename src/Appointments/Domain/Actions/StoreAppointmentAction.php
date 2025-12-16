<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Carbon\CarbonImmutable;

use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDto;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;
use RuntimeException;

class StoreAppointmentAction
{
    public function execute(AppointmentDto $dto): Appointment
    {
        $startsAt = CarbonImmutable::parse($dto->startsAt);
        $endsAt = CarbonImmutable::parse($dto->endsAt);

        if ($endsAt->lte($startsAt)) {
            throw new RuntimeException('The appointment end time must be after the start time.');
        }

        if ($startsAt->isPast()) {
            throw new RuntimeException('Appointments cannot be scheduled in the past.');
        }
        $this->checkRelatedResources($dto->doctorId, $dto->clinicId);
        $this->checkOverlapping($dto, $startsAt, $endsAt);

        $appointment = new Appointment();

        /** @var int<0, max> $userId */
        $userId = (int) $dto->userId;
        /** @var int<0, max> $doctorId */
        $doctorId = (int) $dto->doctorId;
        /** @var int<0, max> $clinicId */
        $clinicId = (int) $dto->clinicId;

        $appointment->user_id = $userId;
        $appointment->doctor_id = $doctorId;
        $appointment->clinic_id = $clinicId;
        $appointment->starts_at = $startsAt;
        $appointment->ends_at = $endsAt;
        $appointment->saveOrFail();

        return $appointment->load('doctor', 'clinic');
    }

    private function checkRelatedResources(int $doctorId, int $clinicId): void
    {
        $exists = Doctor::query()
            ->where('id', $doctorId)
            ->whereHas('clinics', function ($q) use ($clinicId) {
                $q->where('clinics.id', $clinicId);
            })
            ->exists();
        if (! $exists) {
            throw new RuntimeException('The selected clinic is not assigned to the given doctor.');
        }
    }

    private function checkOverlapping(AppointmentDto $dto, CarbonImmutable $startsAt, CarbonImmutable $endsAt): void
    {
        $doctorOverlapping = Appointment::query()
            ->where('doctor_id', $dto->doctorId)
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt);
            })
            ->exists();

        if ($doctorOverlapping) {
            throw new RuntimeException('The doctor already has an overlapping appointment.');
        }
        $patientOverlapping = Appointment::query()
            ->where('user_id', $dto->userId)
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt);
            })
            ->exists();

        if ($patientOverlapping) {
            throw new RuntimeException('The patient already has an overlapping appointment.');
        }
    }
}
