@extends('admin.layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800;">👤 {{ $aluno->nome }}</h2>
        <a href="{{ route('admin.alunos.index') }}" class="btn btn-orange">← Alunos</a>
    </div>

    <div class="card">
        <p><strong>Nome:</strong> {{ $aluno->nome }}</p>
        <p><strong>Escola:</strong> {{ $aluno->escola }}</p>
        <p><strong>Cadastro:</strong> {{ $aluno->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Total de histórias:</strong> {{ $historias->count() }}</p>
    </div>

    <div class="card">
        <h3 style="font-weight: 800; margin-bottom: 1rem;">📖 Histórias</h3>
        @if($historias->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historias as $historia)
                        <tr>
                            <td>#{{ $historia->id }}</td>
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #888;">Nenhuma história criada por este aluno.</p>
        @endif
    </div>
@endsection
