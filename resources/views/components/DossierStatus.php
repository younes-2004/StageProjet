<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DossierStatus extends Component
{
    public $statut;
    public $size;
    
    /**
     * Create a new component instance.
     *
     * @param string $statut
     * @param string $size
     * @return void
     */
    public function __construct($statut, $size = 'md')
    {
        $this->statut = $statut;
        $this->size = $size;
    }
    
    /**
     * Get the CSS class for the status badge
     *
     * @return string
     */
    public function getStatusClass()
    {
        return match($this->statut) {
            'Créé' => 'bg-info',
            'Validé' => 'bg-success',
            'En attente' => 'bg-warning',
            'Transmis' => 'bg-primary',
            'Réaffecté' => 'bg-warning',
            'Archivé' => 'bg-secondary',
            default => 'bg-light text-dark',
        };
    }
    
    /**
     * Get the icon for the status badge
     *
     * @return string
     */
    public function getStatusIcon()
    {
        return match($this->statut) {
            'Créé' => 'fas fa-plus',
            'Validé' => 'fas fa-check',
            'En attente' => 'fas fa-clock',
            'Transmis' => 'fas fa-paper-plane',
            'Réaffecté' => 'fas fa-exchange-alt',
            'Archivé' => 'fas fa-archive',
            default => 'fas fa-info-circle',
        };
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier-status');
    }
}