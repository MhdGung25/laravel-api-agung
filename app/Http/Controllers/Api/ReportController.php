<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Exception;

class ReportController extends Controller
{
    /**
     * [READ ALL]
     */
    public function index()
    {
        try {
            $reports = Report::with('user')->orderBy('created_at', 'desc')->get();
            return response()->json(['status' => 'success', 'data' => $reports], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * [CREATE] Menyimpan laporan baru (Bisa pilih status manual)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'required|string',
                'status'      => 'sometimes|string|in:pending,proses,selesai', // Bisa pilih manual
            ]);

            $report = Report::create([
                'user_id'     => auth()->id() ?? 1,
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status ?? 'pending', // Jika tidak pilih, default pending
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil disimpan',
                'data' => $report
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * [UPDATE] Mengubah data (Bisa ubah status manual)
     */
 public function update(Request $request, $id)
{
    try {
        $report = Report::find($id);
        if (!$report) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

        $request->validate([
            'title'       => 'sometimes|string',
            'description' => 'sometimes|string',
            'status'      => 'sometimes|string', 
        ]);

        // Gunakan trim() untuk membuang spasi liar
        if ($request->has('title')) $report->title = $request->title;
        if ($request->has('description')) $report->description = $request->description;
        if ($request->has('status')) {
            // Memaksa input menjadi huruf kecil dan tanpa spasi
            $report->status = strtolower(trim($request->status));
        }

        $report->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil diperbarui',
            'data' => $report
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete();
            return response()->json(['status' => 'success', 'message' => 'Dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}