<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use App\Models\Usuario; // Importa el modelo Usuario
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    // Mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('login');
    }
    public function showOurTeam()
    {
        return view('inicio.inicio');
    }

    // Procesar el inicio de sesión
    public function login(Request $request)
{
    // Validar los datos del formulario de inicio de sesión
    $credentials = $request->validate([
        'CorreoElectronico' => 'required|email',
        'Contrasena' => 'required',
    ]);

    // Obtener el usuario por su correo electrónico
    $user = Usuario::where('CorreoElectronico', $credentials['CorreoElectronico'])->first();

    // Verificar si el usuario existe
    if ($user) {
      
        
        // Verificar si la contraseña proporcionada coincide con la almacenada
        if (password_verify($credentials['Contrasena'], $user->Contrasena) || $user->Contrasena === $credentials['Contrasena']) {
            // Contraseña válida, iniciar sesión
            Auth::login($user);

            // Redirigir al usuario a la página deseada después del inicio de sesión
            if ($user->TipoUsuario === 'Administrador') {
                return redirect()->route('admin.index'); // Cambia 'admin.index' a la ruta deseada para administradores
            } elseif ($user->TipoUsuario === 'Profesor') {
                // Verificar el estado del usuario
                if ($user->Estado === 'Director-Departamento' || $user->Estado === 'Director-Carrera') {
                    return redirect()->route('director.indexProyectos'); // Cambia 'dashboard' a la ruta deseada
                } elseif ($user->Estado === 'Vinculacion') {
                    return redirect()->route('coordinador.index'); // Redirige a la ruta de coordinadores
                } elseif ($user->Estado === 'DirectorVinculacion') {
                    return redirect()->route('director_vinculacion.index'); // Ruta para Directores de Vinculación
                } elseif ($user->Estado === 'ParticipanteVinculacion') {
                    return redirect()->route('ParticipanteVinculacion.index'); // Ruta para Participantes de Vinculación
                } else {
                    return back()->withErrors([
                        'CorreoElectronico' => 'Su estado no permite el acceso en este momento.',
                    ]);
                }
            } else {
                return redirect()->route('estudiantes.create'); // Cambia 'dashboard' a la ruta deseada para estudiantes
            }
        }
    }

    // Si las credenciales o el inicio de sesión fallan, redirige de nuevo al formulario de inicio de sesión con un mensaje de error
    return back()->withErrors([
        'CorreoElectronico' => 'Las credenciales proporcionadas son incorrectas.',
    ]);
}




////funcion para cerrar la sesion del usuario

public function logout(Request $request) {
    // Obtener el identificador de sesión único
    $uniqueSessionId = $request->session()->get('unique_session_id');

    // Cerrar la sesión del usuario actual solo si el identificador de sesión coincide
    if ($uniqueSessionId === $request->session()->getId()) {
        Auth::logout();
    }
    
    // Eliminar el identificador de sesión único del usuario
    Session::forget('unique_session_id');
    
    return redirect('/login');
}



}
