<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinics\Domain\Models\Clinic;
use Lightit\Doctors\Domain\DataTransferObjects\DoctorDto;

class UpsertDoctorRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string CLINIC_IDS = 'clinic_ids';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string', 'min:4', 'max:80'],
            self::CLINIC_IDS => ['required', 'array', Rule::exists(Clinic::class, 'id')],
        ];
    }

    public function toDto(): DoctorDto
    {
        /** @var array<int, int> $clinicIds */
        $clinicIds = $this->array(self::CLINIC_IDS);

        return new DoctorDto(
            name: $this->string(self::NAME)->toString(),
            clinicIds: $clinicIds
        );
    }
}
