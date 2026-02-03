<?php

namespace App\Http\Controllers;
use App\Exports\CodesWeeklyExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Code;
use App\Models\CodeBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:200',
        ]);

        $quantity = $request->quantity;

        // Crear batch
        $batch = CodeBatch::create([
            'quantity' => $quantity
        ]);

        $codes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $code = strtoupper(Str::random(6)); // 6 caracteres
            $codes[] = [
                'code' => $code,
                'expires_at' => null, // se define cuando se use

                'batch_id' => $batch->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Code::insert($codes);

        return redirect()->route('admin.codes.show', $batch->id);
    }

    public function show($batchId)
    {
        $batch = CodeBatch::with('codes')->findOrFail($batchId);
        return view('admin.codes.show', compact('batch'));
    }

    public function index()
{
    $codes = Code::with('girl')
        ->orderBy('created_at', 'desc') // 1️⃣ FLUJO NORMAL POR FECHA
        ->orderBy('girl_id', 'asc')     // 2️⃣ SOLO PARA ORDEN VISUAL
        ->paginate(50);

    return view('admin.codes.index', compact('codes'));
}


public function exportExcel()
{
    return Excel::download(
        new CodesWeeklyExport,
        'codigos_semanales.xlsx'
    );
}

}
