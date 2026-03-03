<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\AuthenticationException;

class UserService
{
    private UserRepository $userRepository;
    private LoggerService $logger;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->logger = new LoggerService();
    }

    /**
     * Registrar un nuevo usuario
     */
    public function register(array $data): int
    {
        // Verificar email único
        if ($this->userRepository->emailExists($data['email'])) {
            throw new AuthenticationException('El email ya está registrado.');
        }

        // Hash de contraseña
        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);

        // Asignar rol de usuario por defecto
        if (!isset($data['id_rol'])) {
            $data['id_rol'] = 2; // Usuario
        }

        $userId = $this->userRepository->create($data);

        $this->logger->log(
            $userId,
            'Registro de usuario',
            'usuarios',
            $userId
        );

        return $userId;
    }

    /**
     * Iniciar sesión
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException('No se encontró un usuario con ese email.');
        }

        if ($user['estado'] !== 'activo') {
            throw new AuthenticationException('Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        if (!password_verify($password, $user['contrasena'])) {
            throw new AuthenticationException('La contraseña es incorrecta.');
        }

        // Actualizar último acceso
        $this->userRepository->updateLastAccess((int) $user['id_usuario']);

        // Regenerar ID de sesión para prevenir Session Fixation
        session_regenerate_id(true);

        // Crear sesión
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = (int) $user['id_rol'];
        $_SESSION['user_role_name'] = $user['rol_nombre'];
        $_SESSION['last_activity'] = time();

        $this->logger->log(
            (int) $user['id_usuario'],
            'Inicio de sesión',
            'usuarios',
            (int) $user['id_usuario']
        );

        return $user;
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->logger->log(
                (int) $_SESSION['user_id'],
                'Cierre de sesión',
                'usuarios',
                (int) $_SESSION['user_id']
            );
        }
        session_unset();
        session_destroy();
    }

    /**
     * Obtener todos los usuarios
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAllWithRole();
    }

    /**
     * Obtener usuario por ID
     */
    public function getUserById(int $id): array
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * Cambiar estado del usuario
     */
    public function toggleUserStatus(int $id, string $estado): bool
    {
        $result = $this->userRepository->toggleStatus($id, $estado);
        if ($result && isset($_SESSION['user_id'])) {
            $this->logger->log(
                (int) $_SESSION['user_id'],
                "Cambio de estado de usuario a {$estado}",
                'usuarios',
                $id
            );
        }
        return $result;
    }

    /**
     * Estadísticas de usuarios
     */
    public function getStats(): array
    {
        return [
            'total' => $this->userRepository->count(),
            'activos' => $this->userRepository->countByStatus('activo'),
            'inactivos' => $this->userRepository->countByStatus('inactivo'),
        ];
    }
}
