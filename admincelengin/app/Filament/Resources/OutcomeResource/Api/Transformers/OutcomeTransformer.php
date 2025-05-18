<?php
namespace App\Filament\Resources\OutcomeResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Outcome;

/**
 * @property Outcome $resource
 * @property-read int $id
 * @property-read string $nama
 * @property-read string|null $icon
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 */
class OutcomeTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'nama' => $this->resource->nama,
            'icon' => $this->resource->icon ? asset('storage/' . $this->resource->icon) : null,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}