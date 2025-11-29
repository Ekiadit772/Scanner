<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modals extends Component
{
    public $modalId;
    public $titleId;
    public $titleDefault;
    public $titleEdit;
    public $formId;
    public $errorBoxId;
    public $openEvent;
    public $editEvent;
    public $closeEvent;
    public $buttonLabel;
    public $detailEvent;

    /**
     * Create the component instance.
     *
     * @param string $modalId
     * @param string $titleId
     * @param string $titleDefault
     * @param string $titleEdit
     * @param string $formId
     * @param string $errorBoxId
     * @param string $openEvent
     * @param string $editEvent
     * @param string $closeEvent
     * @param string $buttonLabel
     */
    public function __construct(
        $modalId = 'modal',
        $titleId = 'modal-title',
        $titleDefault = 'Tambah',
        $titleEdit = 'Edit',
        $formId = 'form',
        $errorBoxId = 'formError',
        $openEvent = 'open-modal',
        $editEvent = 'open-edit-modal',
        $closeEvent = 'close-modal',
        $buttonLabel = 'Open',
        $detailEvent = null
    ) {
        $this->modalId = $modalId;
        $this->titleId = $titleId;
        $this->titleDefault = $titleDefault;
        $this->titleEdit = $titleEdit;
        $this->formId = $formId;
        $this->errorBoxId = $errorBoxId;
        $this->openEvent = $openEvent;
        $this->editEvent = $editEvent;
        $this->closeEvent = $closeEvent;
        $this->buttonLabel = $buttonLabel;
        $this->detailEvent = $detailEvent;
    }

    public function render()
    {
        return view('components.modals');
    }
}
