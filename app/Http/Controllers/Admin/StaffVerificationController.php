<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffVerificationController extends Controller
{
    public function index()
    {
        $staffCodes = StaffCode::orderBy('created_at', 'desc')->get();
        return view('admin.staff-verification.index', compact('staffCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:3|max:20|unique:staff_codes,code|regex:/^[A-Z0-9]+$/',
            'name' => 'required|string|max:100',
            'role' => 'required|in:admin,supervisor,scanner',
            'description' => 'nullable|string|max:255',
            'access_permissions' => 'nullable|array',
            'access_permissions.tickets' => 'nullable|boolean',
            'access_permissions.vouchers' => 'nullable|boolean',
        ], [
            'code.required' => 'Kode staff wajib diisi',
            'code.unique' => 'Kode staff sudah digunakan',
            'code.regex' => 'Kode staff hanya boleh huruf kapital dan angka',
            'name.required' => 'Nama staff wajib diisi',
            'role.required' => 'Role wajib dipilih',
        ]);

        // Process access permissions
        $accessPermissions = [
            'tickets' => $request->input('access_permissions.tickets', false) ? true : false,
            'vouchers' => $request->input('access_permissions.vouchers', false) ? true : false,
        ];

        StaffCode::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'role' => $request->role,
            'description' => $request->description,
            'is_active' => true,
            'access_permissions' => $accessPermissions,
        ]);

        return redirect()->route('admin.staff.verification.index')
            ->with('success', 'Kode staff berhasil ditambahkan!');
    }

    public function update(Request $request, StaffCode $staffCode)
    {
        $request->validate([
            'code' => 'required|string|min:3|max:20|regex:/^[A-Z0-9]+$/|unique:staff_codes,code,' . $staffCode->id,
            'name' => 'required|string|max:100',
            'role' => 'required|in:admin,supervisor,scanner',
            'description' => 'nullable|string|max:255',
            'access_permissions' => 'nullable|array',
            'access_permissions.tickets' => 'nullable|boolean',
            'access_permissions.vouchers' => 'nullable|boolean',
        ]);

        // Process access permissions
        $accessPermissions = [
            'tickets' => $request->input('access_permissions.tickets', false) ? true : false,
            'vouchers' => $request->input('access_permissions.vouchers', false) ? true : false,
        ];

        $staffCode->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'role' => $request->role,
            'description' => $request->description,
            'access_permissions' => $accessPermissions,
        ]);

        return redirect()->route('admin.staff.verification.index')
            ->with('success', 'Kode staff berhasil diupdate!');
    }

    // Method toggle - untuk kompatibilitas
    public function toggle(StaffCode $staffCode)
    {
        return $this->toggleStatus($staffCode);
    }

    // Method toggleStatus - yang baru
    public function toggleStatus(StaffCode $staffCode)
    {
        $staffCode->update([
            'is_active' => !$staffCode->is_active
        ]);

        $status = $staffCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.staff.verification.index')
            ->with('success', "Kode staff berhasil {$status}!");
    }

    public function destroy(StaffCode $staffCode)
    {
        $staffCode->delete();
        
        return redirect()->route('admin.staff.verification.index')
            ->with('success', 'Kode staff berhasil dihapus!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:staff_codes,id'
        ]);

        $count = 0;

        switch ($request->action) {
            case 'activate':
                $count = StaffCode::whereIn('id', $request->ids)->update(['is_active' => true]);
                $message = "{$count} kode staff berhasil diaktifkan";
                break;
                
            case 'deactivate':
                $count = StaffCode::whereIn('id', $request->ids)->update(['is_active' => false]);
                $message = "{$count} kode staff berhasil dinonaktifkan";
                break;
                
            case 'delete':
                $count = StaffCode::whereIn('id', $request->ids)->delete();
                $message = "{$count} kode staff berhasil dihapus";
                break;
        }

        return redirect()->route('admin.staff.verification.index')
            ->with('success', $message);
    }

    public function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $exists = StaffCode::where('code', strtoupper($request->code))->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }

    public function generateCustomCode(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:10',
            'format' => 'required|in:random,sequential,custom',
            'length' => 'required|integer|min:3|max:10'
        ]);

        $prefix = strtoupper($request->prefix ?? '');
        $format = $request->format;
        $length = $request->length;

        $code = '';

        switch ($format) {
            case 'random':
                do {
                    $code = $prefix . strtoupper(Str::random($length));
                    $code = preg_replace('/[^A-Z0-9]/', '', $code);
                } while (StaffCode::where('code', $code)->exists());
                break;

            case 'sequential':
                $lastCode = StaffCode::where('code', 'LIKE', $prefix . '%')
                    ->orderBy('code', 'desc')
                    ->first();
                
                if ($lastCode) {
                    $lastNumber = (int) preg_replace('/[^0-9]/', '', substr($lastCode->code, strlen($prefix)));
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }
                
                $code = $prefix . str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
                break;

            case 'custom':
                // Generate random alphanumeric
                do {
                    $randomPart = '';
                    for ($i = 0; $i < $length; $i++) {
                        $randomPart .= (rand(0, 1) === 0) 
                            ? chr(rand(65, 90))  // A-Z
                            : rand(0, 9);         // 0-9
                    }
                    $code = $prefix . $randomPart;
                } while (StaffCode::where('code', $code)->exists());
                break;
        }

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }

    public function suggestions(Request $request)
    {
        $role = $request->query('role', 'scanner');
        
        $prefixes = [
            'admin' => 'ADMIN',
            'supervisor' => 'SUPER',
            'scanner' => 'SCAN'
        ];
        
        $prefix = $prefixes[$role] ?? 'STAFF';
        $suggestions = [];

        // Generate 6 suggestions
        for ($i = 0; $i < 6; $i++) {
            do {
                $random = rand(100, 999);
                $code = $prefix . $random;
            } while (in_array($code, $suggestions) || StaffCode::where('code', $code)->exists());
            
            $suggestions[] = $code;
        }

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }
}