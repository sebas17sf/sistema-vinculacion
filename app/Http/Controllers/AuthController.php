<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; // Importa el modelo Usuario

class AuthController extends Controller
{
    // Mostrar el formulario de registro
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Procesar el registro de usuarios
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'CorreoElectronico' => 'required|string|email|unique:Usuarios',
            'Contrasena' => 'required|string|min:6',
            'TipoUsuario' => 'required|string', // Asegúrate de que el campo TipoUsuario esté presente en el formulario
        ]);

        // Crear un nuevo usuario
        $user = new Usuario;
        $user->Nombre = $request->Nombre;
        $user->Apellido = $request->Apellido;
        $user->CorreoElectronico = $request->CorreoElectronico;
        $user->Contrasena = bcrypt($request->Contrasena);
        $user->TipoUsuario = $request->TipoUsuario;

        // Establecer el estado del usuario según el TipoUsuario
        if ($request->TipoUsuario === 'Profesor') {
            $user->Estado = 'Pendiente'; // Profesor tiene estado Pendiente
            $mensaje = 'Su registro será comprobado antes de que pueda iniciar sesión.';

        } else {
            $user->Estado = 'Aprobado'; // Estudiante tiene estado Aprobado
            $mensaje = 'Usuario creado';

        }

        $user->save();

        // Redirigir al usuario a su perfil u otra página después del registro
        return redirect()->route('login')->with('success', $mensaje);
    }

  
}