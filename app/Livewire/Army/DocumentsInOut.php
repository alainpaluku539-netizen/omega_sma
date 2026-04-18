<?php

namespace App\Livewire\Army;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentsInOut extends Component
{
    use WithPagination;

    // Propriétés du formulaire
    public $title, $doc_type = 'NOTE', $direction = 'IN', $origin_destination;
    public $mention = 'ORDINAIRE', $description, $action_date;

    // Filtres et recherche
    public $search = '';
    public $filterDirection = 'all'; // all, IN, OUT

    // Reset de la pagination lors d'une recherche
    public function updatingSearch() { $this->resetPage(); }

    /**
     * Enregistre un nouveau document avec calcul automatique du numéro d'entrée
     */
    public function saveDocument()
    {
        $this->validate([
            'title' => 'required|min:3',
            'doc_type' => 'required',
            'direction' => 'required|in:IN,OUT',
            'origin_destination' => 'required',
            'action_date' => 'required|date',
        ]);

        // Calcul automatique du numéro chronologique pour l'année en cours
        $currentYear = now()->year;
        $lastNumber = Document::where('entry_year', $currentYear)
            ->where('direction', $this->direction)
            ->max('entry_number');

        $nextNumber = ($lastNumber ?? 0) + 1;
        $refPrefix = $this->direction === 'IN' ? 'IN' : 'OUT';

        Document::create([
            'reference' => "{$refPrefix}-{$currentYear}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
            'entry_number' => $nextNumber,
            'entry_year' => $currentYear,
            'title' => $this->title,
            'doc_type' => $this->doc_type,
            'direction' => $this->direction,
            'origin_destination' => $this->origin_destination,
            'mention' => strtoupper($this->mention),
            'description' => $this->description,
            'action_date' => $this->action_date,
            'user_id' => auth()->id(),
        ]);

        $this->reset(['title', 'origin_destination', 'description', 'action_date']);
        session()->flash('success', 'Document enregistré dans le registre OMEGA.');
    }

    public function render()
    {
        $query = Document::with('user')
            ->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('reference', 'like', '%' . $this->search . '%');
            });

        if ($this->filterDirection !== 'all') {
            $query->where('direction', $this->filterDirection);
        }

        return view('livewire.army.documents-in-out', [
            'documents' => $query->latest()->paginate(10)
        ]);
    }
}
