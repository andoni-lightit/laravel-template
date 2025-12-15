<?php

declare(strict_types=1);

namespace Lightit\Clinics\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinics\Domain\DataTransferObjects\ClinicDto;
use Lightit\Doctors\Domain\Models\Doctor;

class UpsertClinicRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string ADDRESS = 'address';

    public const string DOCTOR_IDS = 'doctor_ids';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string', 'min:4', 'max:80'],
            self::ADDRESS => ['nullable', 'string', 'min:4', 'max:120'],
            self::DOCTOR_IDS => ['required', 'array', Rule::exists(Doctor::class, 'id')],
        ];
    }

    public function toDto(): ClinicDto
    {
        /** @var array<int, int> $doctorIds */
        $doctorIds = $this->array(self::DOCTOR_IDS);

        return new ClinicDto(
            name: $this->string(self::NAME)->toString(),
            address: $this->string(self::ADDRESS)->toString(),
            doctorIds: $doctorIds,
        );
    }
}
