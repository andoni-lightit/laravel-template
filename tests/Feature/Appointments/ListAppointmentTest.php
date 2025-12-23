<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Database\Factories\UserFactory;

use function Pest\Laravel\actingAs;

describe('appointments', function (): void {
    /** @see ListAppointmentController */

    it('can list appointments successfully', function (): void {
        /** @var \Lightit\Users\Domain\Models\User $user */
        $user = UserFactory::new()->create();

        AppointmentFactory::new()
            ->createMany(15);

        actingAs($user)
            ->getJson(url('/api/appointments'))
            ->assertSuccessful()
            ->assertJsonCount(15, 'data');
    });
});
