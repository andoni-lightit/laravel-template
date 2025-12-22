<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Lightit\Appointments\Domain\Models\Appointment;

final readonly class DeleteAppointmentAction
{
    public function execute(Appointment $appointment): void
    {
        $appointment->deleteOrFail();
    }
}
