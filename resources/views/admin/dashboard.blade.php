@extends('admin.layouts.admin')

@section('admin-content')
    <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 1.5rem;">📊 Dashboard</h2>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="num">{{ $totalAlunos }}</div>
            <div class="label">Alunos Cadastrados</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $totalHistorias }}</div>
            <div class="label">Total de Histórias</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $historiasHoje }}</div>
            <div class="label">Histórias Hoje</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $historiasConcluidas }}</div>
            <div class="label">Histórias Concluídas</div>
        </div>
    </div>

    <div class="card">
        <h3 style="font-weight: 800; margin-bottom: 1rem;">Últimas Histórias</h3>
        @if($ultimasHistorias->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Escola</th>
                        <th>Criada em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasHistorias as $historia)
                        <tr>
                            <td>{{ $historia->aluno->nome }}</td>
                            <td>{{ $historia->aluno->escola }}</td>
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
            <p style="color: #888;">Nenhuma história concluída ainda.</p>
        @endif
    </div>

    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('admin.historias.index') }}" class="btn btn-orange">📖 Todas as Histórias</a>
        <a href="{{ route('admin.alunos.index') }}" class="btn btn-blue">👤 Alunos</a>
    </div>
@endsection
