<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WelcomeSection as WelcomeModel;

use Livewire\WithFileUploads;

class WelcomeSection extends Component
{
    use WithFileUploads;
    public $logoFile; 

    // Propiedades públicas que se bindean con los inputs
    public $header_title;
    public $header_subtitle;
    public $indicador;
    public $main_encabezado;
    public $main_encabezado2;
    public $main_subencabezado;
    public $requisito_avance_academico;
    public $horas_total;
    public $creditos;
    public $meses_maximo;
    public $nombre_institucion;
    public $abreviatura;
    public $logo;
    public $email_contacto;
    public $telefono_contacto;
    public $direccion_contacto;

    // Modelo completo
    public $welcome;

    public function mount()
    {
        // Cargar solo el registro con id=1
        $this->welcome = WelcomeModel::find(1);

        // Si no existe, crear uno por defecto
        if (!$this->welcome) {
            $this->welcome = WelcomeModel::create([
                'header_title' => 'Servicio Social',
                'header_subtitle' => 'ITSCO',
                'indicador' => 'Agiliza en línea',
                'main_encabezado' => 'Gestión de',
                'main_encabezado2' => 'Servicio Social',
                'main_subencabezado' => 'Instituto Tecnológico Superior de Cosamaloapan',
                'requisito_avance_academico' => '70%',
                'horas_total' => '500',
                'creditos' => '10',
                'meses_maximo' => '6',
                'nombre_institucion' => 'Instituto Tecnológico Superior de Cosamaloapan',
                'abreviatura' => 'ITSCO',
                'logo' => 'logo.png',
                'email_contacto' => 'serviciosocial@itsco.edu.mx',
                'telefono_contacto' => '(288) 882-4000',
                'direccion_contacto' => 'Cosamaloapan, Veracruz',
            ]);
        }

        // Llenar las propiedades públicas para que los inputs funcionen
        $this->fill([
            'header_title' => $this->welcome->header_title,
            'header_subtitle' => $this->welcome->header_subtitle,
            'indicador' => $this->welcome->indicador,
            'main_encabezado' => $this->welcome->main_encabezado,
            'main_encabezado2' => $this->welcome->main_encabezado2,
            'main_subencabezado' => $this->welcome->main_subencabezado,
            'requisito_avance_academico' => $this->welcome->requisito_avance_academico,
            'horas_total' => $this->welcome->horas_total,
            'creditos' => $this->welcome->creditos,
            'meses_maximo' => $this->welcome->meses_maximo,
            'nombre_institucion' => $this->welcome->nombre_institucion,
            'abreviatura' => $this->welcome->abreviatura,
            'logo' => $this->welcome->logo,
            'email_contacto' => $this->welcome->email_contacto,
            'telefono_contacto' => $this->welcome->telefono_contacto,
            'direccion_contacto' => $this->welcome->direccion_contacto,
        ]);
    }

    public function update()
    {
        $validatedData = $this->validate([
            'header_title' => 'required|string',
            'header_subtitle' => 'required|string',
            'indicador' => 'required|string',
            'main_encabezado' => 'required|string',
            'main_encabezado2' => 'required|string',
            'main_subencabezado' => 'required|string',
            'requisito_avance_academico' => 'required|string',
            'horas_total' => 'required|string',
            'creditos' => 'required|string',
            'meses_maximo' => 'required|string',
            'nombre_institucion' => 'required|string',
            'abreviatura' => 'required|string',
            'email_contacto' => 'required|string',
            'telefono_contacto' => 'required|string',
            'direccion_contacto' => 'required|string',
            'logoFile' => 'nullable|image|max:1024', // máximo 1MB
        ]);

        // Si se subió un archivo de logo
        if ($this->logoFile) {
            $path = $this->logoFile->store('logos', 'public'); // guarda en storage/app/public/logos
            $this->welcome->logo = $path;
        }

        // Guardar los demás campos
        $this->welcome->header_title = $validatedData['header_title'];
        $this->welcome->header_subtitle = $validatedData['header_subtitle'];
        $this->welcome->indicador = $validatedData['indicador'];
        $this->welcome->main_encabezado = $validatedData['main_encabezado'];
        $this->welcome->main_encabezado2 = $validatedData['main_encabezado2'];
        $this->welcome->main_subencabezado = $validatedData['main_subencabezado'];
        $this->welcome->requisito_avance_academico = $validatedData['requisito_avance_academico'];
        $this->welcome->horas_total = $validatedData['horas_total'];
        $this->welcome->creditos = $validatedData['creditos'];
        $this->welcome->meses_maximo = $validatedData['meses_maximo'];
        $this->welcome->nombre_institucion = $validatedData['nombre_institucion'];
        $this->welcome->abreviatura = $validatedData['abreviatura'];
        $this->welcome->email_contacto = $validatedData['email_contacto'];
        $this->welcome->telefono_contacto = $validatedData['telefono_contacto'];
        $this->welcome->direccion_contacto = $validatedData['direccion_contacto'];

        $this->welcome->save();

        $this->dispatch('notify', type: 'success', message: "Datos actualizados correctamente");
    }


    public function render()
    {
        return view('livewire.admin.welcome-section');
    }
}
