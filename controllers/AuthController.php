<?php
// controllers/AuthController.php
session_start();
require_once __DIR__ . '/../config/conexion.php';

class AuthController {
    public function loginForm() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        global $conexion;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }

        $dni  = $_POST['dni']  ?? '';
        $tipo = $_POST['tipo'] ?? '';

        // Caso ADMIN: sin DNI (o podrías pedir una clave)
        if ($tipo === 'admin') {
            $_SESSION['dni']  = null;
            $_SESSION['tipo'] = 'admin';
            header('Location: index.php?controller=admin&action=panel');
            exit();
        }

        if ($tipo === 'encargado') {
            $sql = "SELECT e.ApellidosNombres, p.Nro_Piso
                    FROM empleado e
                    JOIN personal_enfermeria p
                      ON e.DNI_Personal = p.DNI_Encargado
                   WHERE e.DNI_Personal = ?";
        } else {
            // empleado
            $sql = "SELECT DNI_Personal, ApellidosNombres
                      FROM empleado
                     WHERE DNI_Personal = ?";
        }

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $_SESSION['dni']  = $dni;
            $_SESSION['tipo'] = $tipo;

            if ($tipo === 'encargado') {
                header('Location: index.php?controller=encargado&action=panel');
            } else {
                header('Location: index.php?controller=empleado&action=panel');
            }
            exit();
        } else {
            $error = "DNI no encontrado o no corresponde al tipo seleccionado.";
            require __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
