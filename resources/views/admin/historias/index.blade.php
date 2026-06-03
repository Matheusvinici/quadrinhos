@extends('admin.layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800;">📖 Todas as Histórias</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-orange">← Dashboard</a>
    </div>

    <div class="card">
        @if($historias->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Escola</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historias as $historia)
                        <tr>
                            <td>#{{ $historia->id }}</td>
                            <td>{{ $historia->aluno->nome }}</td>
                            <td>{{ $historia->aluno->escola }}</td>
                            <td>
                                @if($historia->status == 'concluido')
                                    <span style="color: #27AE60; font-weight: 700;">✅ Concluída</span>
                                @else
                                    <span style="color: #E67E22; font-weight: 700;">🔄 Rascunho</span>
                                @endif
                            </td>
                            <td>{{ $historia->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.historias.show', $historia->id) }}" class="btn btn-blue btn-sm">👁 Ver</a>
                                @if($historia->pdf_path)
                                    <a href="{{ route('admin.historias.download-pdf', $historia->id) }}" class="btn btn-green btn-sm">📥 PDF</a>
                                @endif
                                <form method="POST" action="{{ route('admin.historias.destroy', $historia->id) }}" style="display: inline;"
                                      onsubmit="return confirm('Excluir esta história?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-red btn-sm">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {{ $historias->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 2rem;">Nenhuma história cadastrada.</p>
        @endif
    </div>
@endsection
