@extends('admin.layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800;">👤 Alunos</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-orange">← Dashboard</a>
    </div>

    <div class="card">
        @if($alunos->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Escola</th>
                        <th>Histórias</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunos as $aluno)
                        <tr>
                            <td>#{{ $aluno->id }}</td>
                            <td>{{ $aluno->nome }}</td>
                            <td>{{ $aluno->escola }}</td>
                            <td>{{ $aluno->historias_count }}</td>
                            <td>{{ $aluno->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.alunos.show', $aluno->id) }}" class="btn btn-blue btn-sm">👁 Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {{ $alunos->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 2rem;">Nenhum aluno cadastrado.</p>
        @endif
    </div>
@endsection
