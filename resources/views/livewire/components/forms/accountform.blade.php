<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\AccountForm;
new class extends Component {
    public AccountForm $form;
    public ?string $accountId = null;
    public bool $edit;
    public ?bool $disable;
    public bool $delete = false;

    public function submit(): void
    {
        if ($this->edit) {
            $this->form->edit($this->accountId);
            $this->redirect(route('pages.accounts.show', ['account' => $this->accountId]));
        } else {
            $this->form->create();
            $this->redirect(route('pages.accounts.index'));
        }
    }
    public function mount(?string $accountId = null, bool $edit = false, bool $disable = false): void
    {
        $this->accountId = $accountId;
        $this->edit = $edit;
        $this->disable = $disable;
        if ($this->accountId) {
            $this->form->mount($this->accountId);
            // load account data
        }
    }
    public function goToIndex(): void
    {
        $this->redirect(route('pages.accounts.index'));
    }
    public function deleteItem(): void
    {
        if ($this->accountId) {
            $this->form->deleteAccount($this->accountId);
            $this->redirect(route('pages.accounts.index'));
        }
    }
}; ?>
<x-templates.form :edit="$edit" :disable="$disable" :delete="$delete">

    <flux:input :disabled="$this->disable" name="form.name" label="Name" placeholder="Enter account name"
        wire:model.defer="form.name" required />

    <flux:select :disabled="$this->disable" name="form.type" label="Type" wire:model.defer="form.type" required>
        @php
            $types = [
                ['label' => 'Cash', 'value' => 'cash'],
                ['label' => 'Bank', 'value' => 'bank'],
                ['label' => 'Mpesa', 'value' => 'mpesa'],
                ['label' => 'Others', 'value' => 'others'],
            ];
        @endphp
        @foreach ($types as $type)
            <flux:select.option value="{{ $type['value'] }}">{{ $type['label'] }}</flux:select.option>
        @endforeach
    </flux:select>

    <div class="col-span-1 md:col-span-2">
        <flux:input :disabled="$this->disable" type="number" name="form.balance" label="Balance" placeholder="0.00"
            step="0.01" wire:model.defer="form.balance" required />
    </div>
</x-templates.form>
