@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $agendamentosHoje->count() }}</h3>
                <p>Agendamentos Hoje</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-day"></i></div>
            <a href="{{ route('admin.agendamentos.index') }}" class="small-box-footer">Ver mais <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendentes }}</h3>
                <p>Pendentes</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('admin.agendamentos.index') }}" class="small-box-footer">Ver mais <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>R$ {{ number_format($totalFaturamentoHoje, 2, ',', '.') }}</h3>
                <p>Faturamento Hoje</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill"></i></div>
            <a href="{{ route('admin.relatorios.faturamento') }}" class="small-box-footer">Ver mais <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>R$ {{ number_format($despesasVencidas, 2, ',', '.') }}</h3>
                <p>Despesas Vencidas</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="{{ route('admin.despesas.index') }}" class="small-box-footer">Ver mais <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Agendamentos de Hoje ({{ now()->format('d/m/Y') }})</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Cliente</th>
                            <th>Barbeiro</th>
                            <th>Serviços</th>
                            <th>Status</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentosHoje as $ag)
                        <tr>
                            <td>{{ substr($ag->hora_inicio, 0, 5) }}</td>
                            <td>{{ $ag->cliente->nome }}</td>
                            <td>{{ $ag->barbeiro->nome }}</td>
                            <td>{{ $ag->servicos->pluck('nome')->implode(', ') }}</td>
                            <td><span class="badge-status status-{{ $ag->status }}">{{ ucfirst($ag->status) }}</span></td>
                            <td>R$ {{ number_format($ag->total ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhum agendamento para hoje</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Resumo do Dia</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Confirmados:</span>
                    <span class="badge bg-info">{{ $confirmados }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Realizados:</span>
                    <span class="badge bg-success">{{ $realizados }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Pendentes:</span>
                    <span class="badge bg-warning">{{ $pendentes }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Próximos 7 dias:</span>
                    <span class="badge bg-primary">{{ $agendamentosSemana }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Caixa:</span>
                    @if($caixaHoje)
                        <span class="badge bg-{{ $caixaHoje->fechado ? 'secondary' : 'success' }}">
                            {{ $caixaHoje->fechado ? 'Fechado' : 'Aberto' }}
                        </span>
                    @else
                        <a href="{{ route('admin.caixa.index') }}" class="btn btn-sm btn-outline-primary px-3">Abrir Caixa</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.agendamentos.index') }}" class="btn btn-outline-primary btn-block mb-2">
                    <i class="fas fa-plus me-2"></i> Novo Agendamento
                </a>
                <a href="{{ route('admin.relatorios.faturamento') }}" class="btn btn-outline-primary btn-block mb-2">
                    <i class="fas fa-chart-bar me-2"></i> Relatório de Faturamento
                </a>
                <a href="{{ route('admin.caixa.index') }}" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-cash-register me-2"></i> Gerenciar Caixa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
