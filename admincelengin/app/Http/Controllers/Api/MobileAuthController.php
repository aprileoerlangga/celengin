<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MobileAuthController extends BaseController
{
    /**
     * Register pengguna baru untuk aplikasi mobile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);
        }

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'terakhir_login' => now(),
        ]);

        $token = $pengguna->createToken('mobile-app')->plainTextToken;

        $result = [
            'pengguna' => $pengguna,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return $this->sendResponse($result, 'Registrasi berhasil');
    }

    /**
     * Login pengguna untuk aplikasi mobile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);
        }

        // Cari pengguna berdasarkan email
        $pengguna = Pengguna::where('email', $request->email)->first();

        // Verifikasi password
        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return $this->sendError('Kredensial tidak valid', [], 401);
        }

        // Update waktu login terakhir
        $pengguna->update(['terakhir_login' => now()]);

        // Hapus token lama jika ada
        if ($request->has('device_name')) {
            $pengguna->tokens()->where('name', $request->device_name)->delete();
            $token = $pengguna->createToken($request->device_name)->plainTextToken;
        } else {
            $pengguna->tokens()->where('name', 'mobile-app')->delete();
            $token = $pengguna->createToken('mobile-app')->plainTextToken;
        }

        $result = [
            'pengguna' => $pengguna,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return $this->sendResponse($result, 'Login berhasil');
    }

    /**
     * Logout pengguna dari aplikasi mobile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'Logout berhasil');
    }

    /**
     * Mendapatkan profil pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user(), 'Profil pengguna');
    }
}