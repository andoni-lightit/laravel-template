<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Appointments\Domain\Models\Appointment;
use Carbon\CarbonImmutable;
use Lightit\Users\Domain\Models\User;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Clinics\Domain\Models\Clinic;
use Database\Factories\UserFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\ClinicFactory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? UserFactory::new()->createOne();
        $doctor = Doctor::inRandomOrder()->first() ?? DoctorFactory::new()->createOne();

        $clinic = $doctor->clinics()->inRandomOrder()->first();
        if (! $clinic) {
            $clinic = ClinicFactory::new()->createOne();
            $doctor->clinics()->attach($clinic->id);
        }

        $startsAt = CarbonImmutable::now()
            ->addDays($this->faker->numberBetween(1, 30))
            ->setHour($this->faker->numberBetween(8, 16))
            ->setMinute(0)
            ->setSecond(0);
        $endsAt = $startsAt->addMinutes(30);

        return [
            'user_id' => $user->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];
    }
}
