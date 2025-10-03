<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffVerificationController extends Controller
{
    public function index()
    {
        $staffCodes = StaffCode::orderBy('created_at', 'desc')->get();
        
        return view('admin.staff-verification.index', compact('staffCodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:staff_codes,code|max:20',
            'name' => 'required|string|max:100',
            'role' => ['required', 'string', Rule::in(['scanner', 'admin', 'supervisor'])],
            'description' => 'nullable|string|max:255'
        ], [
            'code.required' => 'Kode staff wajib diisi',
            'code.unique' => 'Kode staff sudah digunakan',
            'name.required' => 'Nama staff wajib diisi',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        StaffCode::create([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'role' => $validated['role'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
            'usage_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Kode staff berhasil ditambahkan!');
    }

    public function generateCode()
    {
        do {
            $code = 'STAFF' . strtoupper(Str::random(6));
        } while (StaffCode::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    public function update(Request $request, $id)
    {
        $staffCode = StaffCode::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('staff_codes')->ignore($id)],
            'name' => 'required|string|max:100',
            'role' => ['required', 'string', Rule::in(['scanner', 'admin', 'supervisor'])],
            'description' => 'nullable|string|max:255'
        ], [
            'code.required' => 'Kode staff wajib diisi',
            'code.unique' => 'Kode staff sudah digunakan',
            'name.required' => 'Nama staff wajib diisi',
            'role.required' => 'Role wajib dipilih',
        ]);

        $staffCode->update([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'role' => $validated['role'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Kode staff berhasil diupdate!');
    }

    public function toggleStatus($id)
    {
        $staffCode = StaffCode::findOrFail($id);
        $staffCode->update(['is_active' => !$staffCode->is_active]);

        $status = $staffCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('success', "Kode staff berhasil {$status}!");
    }

    public function destroy($id)
    {
        try {
            $staffCode = StaffCode::findOrFail($id);
            
            // Cek apakah kode pernah digunakan
            if ($staffCode->usage_count > 0) {
                return redirect()->back()->with('warning', 'Kode staff ini pernah digunakan. Sebaiknya nonaktifkan daripada menghapus.');
            }
            
            $staffCode->delete();

            return redirect()->back()->with('success', 'Kode staff berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kode staff: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:staff_codes,id'
        ], [
            'action.required' => 'Pilih aksi yang akan dilakukan',
            'ids.required' => 'Pilih minimal satu kode staff',
            'ids.min' => 'Pilih minimal satu kode staff',
        ]);

        $staffCodes = StaffCode::whereIn('id', $validated['ids']);

        try {
            switch ($validated['action']) {
                case 'activate':
                    $staffCodes->update(['is_active' => true]);
                    $message = 'Kode staff berhasil diaktifkan!';
                    break;
                    
                case 'deactivate':
                    $staffCodes->update(['is_active' => false]);
                    $message = 'Kode staff berhasil dinonaktifkan!';
                    break;
                    
                case 'delete':
                    $count = $staffCodes->count();
                    $staffCodes->delete();
                    $message = "{$count} kode staff berhasil dihapus!";
                    break;
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan bulk action: ' . $e->getMessage());
        }
    }

    public function generateCustomCode(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:10|regex:/^[A-Z0-9]*$/',
            'format' => 'required|in:random,sequential,custom',
            'length' => 'nullable|integer|min:3|max:10'
        ]);

        $prefix = strtoupper($request->prefix ?? 'STAFF');
        $format = $request->format ?? 'random';
        $length = $request->length ?? 6;

        do {
            switch ($format) {
                case 'sequential':
                    // Cari kode terakhir dengan prefix yang sama
                    $lastCode = StaffCode::where('code', 'like', $prefix . '%')
                        ->orderBy('code', 'desc')
                        ->first();
                    
                    if ($lastCode) {
                        // Extract nomor dari kode terakhir
                        $lastNumber = intval(preg_replace('/[^0-9]/', '', substr($lastCode->code, strlen($prefix))));
                        $nextNumber = $lastNumber + 1;
                    } else {
                        $nextNumber = 1;
                    }
                    
                    $code = $prefix . str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
                    break;

                case 'random':
                    $code = $prefix . strtoupper(Str::random($length));
                    break;

                case 'custom':
                    // Generate kode random sebagai saran
                    $code = $prefix . strtoupper(Str::random($length));
                    break;
            }
        } while (StaffCode::where('code', $code)->exists());

        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => 'Kode berhasil di-generate'
        ]);
    }

    public function checkCode(Request $request)
    {
        $code = strtoupper($request->code);
        $exists = StaffCode::where('code', $code)->exists();

        return response()->json([
            'exists' => $exists,
            'available' => !$exists,
            'message' => $exists ? 'Kode sudah digunakan' : 'Kode tersedia'
        ]);
    }
    
    public function getCodeSuggestions(Request $request)
    {
        $prefix = strtoupper($request->prefix ?? '');
        $suggestions = [];

        // Suggestion berdasarkan role
        $rolePrefixes = [
            'admin' => ['ADMIN', 'ADM', 'MANAGER'],
            'supervisor' => ['SUPER', 'SUP', 'SPV'],
            'scanner' => ['SCAN', 'SCN', 'GATE']
        ];

        $role = $request->role ?? 'scanner';
        $prefixes = $rolePrefixes[$role] ?? ['STAFF'];

        foreach ($prefixes as $pfx) {
            do {
                $code = $pfx . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            } while (StaffCode::where('code', $code)->exists());
            
            $suggestions[] = $code;
        }

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }
}