<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;

class DossierExportController extends Controller
{
    public function export(Request $request)
    {
        // Construction de la requête en fonction des filtres
        $query = Dossier::query()
            ->with(['createur', 'service']);
            
        // Application des mêmes filtres que dans la recherche
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('titre', 'like', "%{$keyword}%")
                  ->orWhere('contenu', 'like', "%{$keyword}%")
                  ->orWhere('numero_dossier_judiciaire', 'like', "%{$keyword}%");
            });
        }
        
        // Autres filtres...
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        
        if ($request->has('createur_id') && !empty($request->createur_id)) {
            $query->where('createur_id', $request->createur_id);
        }
        
        if ($request->has('genre') && !empty($request->genre)) {
            $query->where('genre', 'like', "%{$request->genre}%");
        }
        
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_creation', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_creation', '<=', $request->date_fin);
        }
        
        // Tri
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['titre', 'numero_dossier_judiciaire', 'statut', 'created_at', 'date_creation'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $dossiers = $query->get();
        
        // Format d'exportation
        $format = $request->format ?? 'csv';
        
        // Préparer les données
        $exportData = [];
        
        // En-têtes
        $headers = [
            'Numéro de dossier',
            'Titre',
            'Statut',
            'Genre',
            'Créateur',
            'Service',
            'Date de création',
            'Contenu'
        ];
        
        $exportData[] = $headers;
        
        // Données
        foreach ($dossiers as $dossier) {
            $row = [
                $dossier->numero_dossier_judiciaire,
                $dossier->titre,
                $dossier->statut,
                $dossier->genre,
                $dossier->createur->name ?? 'N/A',
                $dossier->service->nom ?? 'N/A',
                $dossier->date_creation ? $dossier->date_creation->format('d/m/Y') : ($dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A'),
                substr($dossier->contenu, 0, 200) . (strlen($dossier->contenu) > 200 ? '...' : '') // Limiter la taille du contenu
            ];
            
            $exportData[] = $row;
        }
        
        // Exporter dans le format demandé
        switch ($format) {
            case 'csv':
                return $this->exportToCsv($exportData, 'dossiers_export_' . date('Ymd_His') . '.csv');
                break;
                
            case 'xlsx':
                return $this->exportToExcel($exportData, 'dossiers_export_' . date('Ymd_His') . '.xlsx');
                break;
                
            case 'pdf':
                return $this->exportToPdf($dossiers, 'dossiers_export_' . date('Ymd_His') . '.pdf');
                break;
                
            default:
                return redirect()->back()->with('error', 'Format d\'export non pris en charge');
        }
    }
    
    /**
     * Exporter au format CSV
     */
    private function exportToCsv(array $data, string $filename)
    {
        $handle = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($handle, $row, ';');
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
        ];
        
        return Response::make(chr(0xEF).chr(0xBB).chr(0xBF).$csv, 200, $headers); // Ajouter BOM pour UTF-8
    }
    
    /**
     * Exporter au format Excel
     */
    private function exportToExcel(array $data, string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Écrire les données
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $cell = $sheet->getCellByColumnAndRow($colIndex + 1, $rowIndex + 1);
                $cell->setValue($value);
            }
        }
        
        // Mise en forme
        $lastColumn = chr(64 + count($data[0])); // Déterminer la dernière colonne (A, B, C, ...)
        
        // Style pour les en-têtes
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'E6E6E6'],
            ],
        ]);
        
        // Auto-dimensionner les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Créer le fichier Excel
        $writer = new Xlsx($spreadsheet);
        
        $temp_file = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($temp_file);
        
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
    
    /**
     * Exporter au format PDF
     */
    private function exportToPdf($dossiers, string $filename)
    {
        $pdf = PDF::loadView('dossiers.pdf_export', compact('dossiers'));
        
        return $pdf->download($filename);
    }
}