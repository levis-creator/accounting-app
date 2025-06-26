<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Request;

new class extends Component {
    #[Url(except: null)]
    public ?string $id = null;
    #[Url(except: false)]
    public bool $edit = false;
    public bool $showConfirmModal = false;
    public function updated($property)
    {
        if ($property === 'id' && $this->edit) {
            $this->showConfirmModal = true;
        }

        if ($property === 'edit' && $this->edit === false) {
            $this->showConfirmModal = false;
        }
    }
};
?>

<x-slot:title>
    Accounts
</x-slot:title>

<div>

    {{-- Delete modal --}}
    {{-- Edit modal --}}
    <flux:modal wire:model="showConfirmModal">
        <livewire:components.forms.accountform :accountId="$id" />
    </flux:modal>

    {{-- Create section --}}
    <livewire:components.page-header  title="Accounts" :show-create-button="true"
        redirect-url="{{ route('pages.accounts.create') }}" button-label="Add Account" />


    {{-- Table --}}
    <livewire:tables.accounts-table />
</div>
