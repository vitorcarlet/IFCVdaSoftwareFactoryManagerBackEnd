<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ReportController extends Controller
{
    /**
     * Generate a report for a specific project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectReport($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Generate the report for the project
        $report = [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
        ];

        return response()->json($report);
    }

    /**
     * Generate a report for all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProjects()
    {
        $projects = Project::all();

        // Generate the report for all projects
        $report = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
            ];
        });

        return response()->json($report);
    }

    /**
     * Gera um relatório complexo com base em parâmetros recebidos no body JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  {
    //     "start_date": "2024-01-27",
    //     "end_date": "2024-04-28",
    //     "technologies": ["NextJs"],
    //     "sort_by": "title",
    //     "sort_order": "asc"
    // }
    public function complexReport(Request $request)
    {
        // Validação básica dos campos que você espera receber
        $validatedData = $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'technologies' => 'required|array', 
            'sort_by'      => 'sometimes|string|in:name,title', // ou campos que você permitir
            'sort_order'   => 'sometimes|string|in:asc,desc'
        ]);

        // Extraindo variáveis
        $startDate   = $validatedData['start_date'];
        $endDate     = $validatedData['end_date'];
        $technologies = $validatedData['technologies'];
        $sortBy      = $validatedData['sort_by'] ?? 'name';   // fallback
        $sortOrder   = $validatedData['sort_order'] ?? 'asc'; // fallback

        // Exemplo de query:
        // 1) Filtrar por data
        // 2) Filtrar por tecnologias
        // 3) Ordenar pelo campo escolhido
        // 4) Carregar relacionamentos (participants, manager, etc.) se necessário

        // Exemplificando a consulta. 
        // Note que se o campo "technologies" no seu Model for um array ou json, 
        // pode ser preciso ajustar a busca (por ex., 'whereJsonContains').
        //preciso testar essa consulta no mYSQL antes de vir aqui confirmar se deu certo.
        $projectsQuery = Project::whereBetween('start_date', [$startDate, $endDate])
            ->where(function ($query) use ($technologies) {
                foreach ($technologies as $tech) {
                    $query->orWhere('technologies', 'LIKE', '%' . $tech . '%');
                }
            })
            ->with(['participants.user', 'manager']); 
            // 'participants.user' depende de como está seu relacionamento

        // Ordenação (aqui estou assumindo que 'name' é um campo, mas você pode adaptar)
        $projects = $projectsQuery->orderBy($sortBy, $sortOrder)->get();

        // Agora, se você quer filtrar participantes (por ex. em ordem alfabética) 
        // que atuaram nesses projetos, você pode iterar ou criar uma subquery 
        // que referencie a relação 'participants'.
        //
        // Exemplo (bem simplificado):
        //   ->whereHas('participants', function ($query) {
        //       $query->where('whatever', 'whatever');
        //   })
        //
        // E depois formatar no PDF.

        // Vamos agora montar os dados para enviar ao PDF
        $data = [
            'projects' => $projects,
            'report_title' => 'Relatório de Projetos' // Exemplo
        ];

        // Usando a biblioteca barryvdh/laravel-dompdf (instale via Composer se não tiver):
        // composer require barryvdh/laravel-dompdf
        // Crie uma view em resources/views/reports/complex_report.blade.php, por exemplo.
        
        $pdf = PDF::loadView('reports.complex_report', $data);

        // Retorna o PDF para download:
        return $pdf->download('relatorio-projetos.pdf');

        // Ou, se quiser exibir inline no navegador, use:
        // return $pdf->stream('relatorio-projetos.pdf');
    }
}