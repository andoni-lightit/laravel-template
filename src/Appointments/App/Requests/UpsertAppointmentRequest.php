<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDto;
use Lightit\Clinics\Domain\Models\Clinic;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;

class UpsertAppointmentRequest extends FormRequest
{
    public const USER_ID = 'user_id';

    public const DOCTOR_ID = 'doctor_id';

    public const CLINIC_ID = 'clinic_id';

    public const STARTS_AT = 'starts_at';

    public const ENDS_AT = 'ends_at';

    public function rules(): array
    {
        return [
            self::USER_ID => ['required', 'integer', Rule::exists(User::class, 'id')],
            self::DOCTOR_ID => ['required', 'integer', Rule::exists(Doctor::class, 'id')],
            self::CLINIC_ID => ['required', 'integer', Rule::exists(Clinic::class, 'id')],
            self::STARTS_AT => ['required', Rule::date()->after(CarbonImmutable::now())],
            self::ENDS_AT => ['required', Rule::date()->after(self::STARTS_AT)],
        ];
    }

    public function toDto(): AppointmentDto
    {
        /** @var \Illuminate\Support\Carbon $startsAt */
        $startsAt = $this->date(self::STARTS_AT);

        /** @var \Illuminate\Support\Carbon $endsAt */
        $endsAt = $this->date(self::ENDS_AT);

        return new AppointmentDto(
            userId: $this->integer(self::USER_ID),
            doctorId: $this->integer(self::DOCTOR_ID),
            clinicId: $this->integer(self::CLINIC_ID),
            startsAt: $startsAt->toImmutable(),
            endsAt: $endsAt->toImmutable(),
        );
    }
}
