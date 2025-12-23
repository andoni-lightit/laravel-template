<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

describe('appointments', function (): void {
    /** @see StoreAppointmentController */

    it('saves an appointment successfully', function (): void {
        $appointment = AppointmentFactory::new()->makeOne();

        $payload = [
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id,
            'clinic_id' => $appointment->clinic_id,
            'starts_at' => $appointment->starts_at->toIsoString(),
            'ends_at' => $appointment->ends_at->toIsoString(),
        ];

        /** @var \Lightit\Users\Domain\Models\User $user */
        $user = $appointment->user;

        $response = actingAs($user)->postJson(url('/api/appointments'), $payload);
        $response->assertCreated();

        assertDatabaseHas('appointments', [
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id,
            'clinic_id' => $appointment->clinic_id,
            'starts_at' => $appointment->starts_at,
            'ends_at' => $appointment->ends_at,
        ]);
    });

    it('prevents overlapping appointments for the same doctor', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        $startsAt = $appointment->starts_at->addMinutes(5);
        $endsAt = $startsAt->addMinutes(30);

        $payload = [
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id,
            'clinic_id' => $appointment->clinic_id,
            'starts_at' => $startsAt->toIsoString(),
            'ends_at' => $endsAt->toIsoString(),
        ];

        /** @var \Lightit\Users\Domain\Models\User $user */
        $user = $appointment->user;

        $response = actingAs($user)->postJson(url('/api/appointments'), $payload);
        $response->assertUnprocessable();
    });
});
