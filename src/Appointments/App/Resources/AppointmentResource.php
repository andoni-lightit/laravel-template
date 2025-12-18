<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Clinics\App\Resources\ClinicResource;
use Lightit\Doctors\App\Resources\DoctorResource;

/**
 * @mixin Appointment
 */
#[SchemaName('Appointment')]
class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'doctor' => $this->whenLoaded('doctor', fn () => DoctorResource::make($this->doctor)),
            'clinic' => $this->whenLoaded('clinic', fn () => ClinicResource::make($this->clinic)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
