<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformationController extends Controller
{
    // Mengambil semua informasi
    public function index()
    {
        $info = Information::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $info
        ]);
    }

    // Menambah Informasi Baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $info = Information::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi berhasil dipublikasikan',
            'data' => $info
        ], 201);
    }

    // Update Informasi
    public function update(Request $request, $id)
    {
        $info = Information::findOrFail($id);
        $info->update($request->only(['title', 'content', 'is_active']));

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi berhasil diperbarui',
            'data' => $info
        ]);
    }

    // Mengubah Status Aktif/Arsip tanpa hapus
    public function toggleStatus($id)
    {
        $info = Information::findOrFail($id);
        $info->is_active = !$info->is_active;
        $info->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status informasi berhasil diubah',
            'is_active' => $info->is_active
        ]);
    }

    // Menghapus Informasi
    public function destroy($id)
    {
        $info = Information::findOrFail($id);
        $info->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi telah dihapus'
        ]);
    }
}