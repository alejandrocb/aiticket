<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    //Muestra el formulario para loguearse
    public function loginForm()
    {
        return view('login'); // Muestra a la vista llamada 'login.php' en la carpeta app/Views
    }

    public function login()
    {
        $session = session();
        $modelo = new UsuarioModel();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $modelo->verificarUsuario($email, $password);
        
        if ($user) {
            $ses_data = [
                'id' => $user['id'],
                'nombre' => $user['nombre'],
                'email' => $user['email'],
                'rol_id' => $user['tipo_usuario_id'],
                'isLoggedIn' => true,
            ];
            $session->set($ses_data);

            $response = [
                'success' => true,
                'redirect' => '/Home',
                'user' => [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email']
                    // Puedes incluir más datos aquí si es necesario
                ]
            ];
            
            //return $this->response->setJSON($response);
            return redirect()->to('/');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email o contraseña incorrectos');
        }
    }

    // Método para cerrar sesión
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}