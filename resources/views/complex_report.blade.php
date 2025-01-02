<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_title ?? 'Relatório' }}</title>
    <style>
        /* Estilos customizados para PDF */
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cccccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>{{ $report_title ?? 'Relatório' }}</h1>
    <p>Período: {{ request('start_date') }} - {{ request('end_date') }}</p>

    @if(isset($projects) && count($projects))
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Tecnologias</th>
                    <th>Participantes</th>
                </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->technologies }}</td>
                    <td>
                        @foreach($project->participants as $participant)
                            - {{ $participant->user->name ?? 'Sem nome' }}<br/>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhum projeto encontrado para esse filtro.</p>
    @endif
</body>
</html>
