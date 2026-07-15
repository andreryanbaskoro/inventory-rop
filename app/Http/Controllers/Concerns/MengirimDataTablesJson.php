<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait MengirimDataTablesJson
{
    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query  Sudah termasuk filter pencarian
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $queryTanpaFilter  Untuk recordsTotal
     * @param  array<int, string>  $urutanKolom  indeks kolom DataTables => nama kolom DB
     */
    protected function responseDataTables(
        Request $request,
        Builder $query,
        Builder $queryTanpaFilter,
        array $urutanKolom,
        callable $mapBaris
    ): JsonResponse {
        $recordsTotal = $queryTanpaFilter->count();

        $pencarian = $request->input('search.value');
        if (is_string($pencarian) && $pencarian !== '') {
            // diterapkan di controller lewat $query
        }

        $recordsFiltered = (clone $query)->count();

        $orderIndex = (int) data_get($request->input('order'), '0.column', 0);
        $orderDir = data_get($request->input('order'), '0.dir', 'asc');
        $kolomUrut = $urutanKolom[$orderIndex] ?? $urutanKolom[0];

        $mulai = max(0, (int) $request->input('start', 0));
        $panjang = min(100, max(10, (int) $request->input('length', 10)));

        $baris = (clone $query)
            ->orderBy($kolomUrut, $orderDir === 'desc' ? 'desc' : 'asc')
            ->skip($mulai)
            ->take($panjang)
            ->get()
            ->map($mapBaris)
            ->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $baris,
        ]);
    }
}
