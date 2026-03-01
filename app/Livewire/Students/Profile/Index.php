<?php

namespace App\Livewire\Students\Profile;

use App\Livewire\Students\Profile\Concerns\ManagesProfile;
use Livewire\Component;

class Index extends Component
{
    use ManagesProfile;

    public function render()
    {
        return view('livewire.students.profile.index', $this->getProfileViewData());
    }
}