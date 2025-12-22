<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Appointments\Domain\Actions\DeleteAppointmentAction;
use Lightit\Appointments\Domain\Models\Appointment;

#[Group('Appointments')]
final readonly class DeleteAppointmentController
{
    public function __invoke(DeleteAppointmentAction $action, Appointment $appointment): JsonResponse
    {
        $action->execute($appointment);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
