<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Historia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HistoriaController extends Controller
{
    public function index()
    {
        $historias = Historia::with('aluno')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.historias.index', compact('historias'));
    }

    public function show($id)
    {
        $historia = Historia::with(['aluno', 'respostas'])->findOrFail($id);
        $respostasAgrupadas = $historia->respostas->groupBy('etapa');
        $etapas = [
            1 => 'Quem é você?',
            2 => 'Onde você vive?',
            3 => 'Quem está com você?',
            4 => 'O que te move?',
        ];

        return view('admin.historias.show', compact('historia', 'respostasAgrupadas', 'etapas'));
    }

    public function downloadPdf($id)
    {
        $historia = Historia::findOrFail($id);

        return redirect()->route('site.criar.imprimir', ['slug' => $historia->slug]);
    }

    public function destroy($id)
    {
        $historia = Historia::findOrFail($id);
        $slug = $historia->slug;

        Storage::disk('public')->deleteDirectory("hqs/{$slug}");
        $historia->respostas()->delete();
        $historia->delete();

        return redirect()->route('admin.historias.index')->with('success', 'História excluída com sucesso!');
    }
}
