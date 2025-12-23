<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Database\Factories\UserFactory;

use function Pest\Laravel\actingAs;

describe('appointments', function (): void {
    /** @see DeleteAppointmentController */

    it('soft deletes an appointment and returns a successful response', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        /** @var \Lightit\Users\Domain\Models\User $user */
        $user = $appointment->user;

        $response = actingAs($user)->deleteJson("api/appointments/$appointment->id");

        $response->assertNoContent();
        $appointment->refresh();

        expect($appointment->deleted_at)->not()->toBeNull();
    });

    it('returns a 404 response when appointment is not found', function (): void {
        $user = UserFactory::new()->createOne();
        $nonExistentAppointmentId = 123321;

        actingAs($user)->deleteJson("api/appointments/$nonExistentAppointmentId")->assertNotFound();
    });
});
