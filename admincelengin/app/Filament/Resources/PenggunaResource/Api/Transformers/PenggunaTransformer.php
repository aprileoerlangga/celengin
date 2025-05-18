<?php
namespace App\Filament\Resources\PenggunaResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pengguna;

/**
 * @property Pengguna $resource
 * @property-read int $id
 * @property-read string $nama
 * @property-read string $email
 * @property-read string $password
 * @property-read \Carbon\Carbon|null $terakhir_login
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 */
class PenggunaTransformer extends JsonResource
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
            'email' => $this->resource->email,
            // Jangan tampilkan password dalam response API
            'terakhir_login' => $this->resource->terakhir_login,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}