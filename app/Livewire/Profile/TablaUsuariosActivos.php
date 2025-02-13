<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TablaUsuariosActivos extends Component
{
    public function deleteUser($userId)
    {
        $user = User::find($userId);
        
        if ($user) {
            $user->delete();
            session()->flash('message', 'Usuario eliminado correctamente.');
        }
    }

    public function render()
    {
        $tipo = '';
        if (Auth::user()->tipo == 'Admin') {
            $tipo = 'Admin';
        } else {
            $tipo = 'Lector';
        }
            
        $users = User::where('id', '!=', Auth::id())->get();
    
        return view('livewire.profile.tabla-usuarios-activos', ['users' => $users, 'tipo' => Auth::user()->tipo]);
    }
}
