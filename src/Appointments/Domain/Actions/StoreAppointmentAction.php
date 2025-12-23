<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Lightit\Appointments\App\Notifications\AppointmentCreatedNotification;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDto;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;


use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StoreAppointmentAction
{
    public function execute(AppointmentDto $dto): Appointment
    {
        $startsAt = CarbonImmutable::parse($dto->startsAt);
        $endsAt = CarbonImmutable::parse($dto->endsAt);

        $this->checkRelatedResources($dto->doctorId, $dto->clinicId);
        $this->checkOverlapping($dto, $startsAt, $endsAt);

        $appointment = new Appointment();

        /** @var int<0, max> $userId */
        $userId = $dto->userId;
        /** @var int<0, max> $doctorId */
        $doctorId = $dto->doctorId;
        /** @var int<0, max> $clinicId */
        $clinicId = $dto->clinicId;

        $appointment->user_id = $userId;
        $appointment->doctor_id = $doctorId;
        $appointment->clinic_id = $clinicId;
        $appointment->starts_at = $startsAt;
        $appointment->ends_at = $endsAt;

        $appointment->saveOrFail();
        $appointment->load('doctor', 'clinic');

        $appointment->notify(new AppointmentCreatedNotification());

        return $appointment;
    }

    private function checkRelatedResources(int $doctorId, int $clinicId): void
    {
        $exists = Doctor::query()
            ->where('id', $doctorId)
            ->whereHas('clinics', function (Builder $q) use ($clinicId): void {
                $q->where('id', $clinicId);
            })
            ->exists();
        if (! $exists) {
            throw new UnprocessableEntityHttpException('The selected clinic is not assigned to the given doctor.');
        }
    }

    private function checkOverlapping(AppointmentDto $dto, CarbonImmutable $startsAt, CarbonImmutable $endsAt): void
    {
        $anyOverlapping = Appointment::query()
            ->where(function (Builder $q) use ($dto): void {
                $q->where('doctor_id', $dto->doctorId)
                    ->orWhere('user_id', $dto->userId);
            })
            ->where(function (Builder $q) use ($startsAt, $endsAt): void {
                $q->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt);
            })
            ->exists();

        if ($anyOverlapping) {
            throw new UnprocessableEntityHttpException('There is an overlapping appointment for the given doctor or patient.');
        }
    }
}
