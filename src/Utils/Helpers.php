<?php
declare(strict_types=1);

namespace App\Utils;

class Helpers
{
    /**
     * Redirigir a una URL
     */
    public static function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /**
     * Verificar si el usuario ha iniciado sesión
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 1;
    }

    /**
     * Requerir autenticación
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            self::setFlash('error', 'Debes iniciar sesión para acceder a esta página.');
            self::redirect('/login.php');
        }
    }

    /**
     * Requerir rol de administrador
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::setFlash('error', 'No tienes permisos para acceder a esta sección.');
            self::redirect('/catalogo.php');
        }
    }

    /**
     * Sanitizar entrada
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Establecer mensaje flash
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * Obtener y eliminar mensaje flash
     */
    public static function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Obtener datos del usuario en sesión
     */
    public static function currentUser(): array
    {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 2,
            'role_name' => $_SESSION['user_role_name'] ?? 'Usuario',
        ];
    }

    /**
     * Formatear fecha
     */
    public static function formatDate(string $date, string $format = 'd/m/Y'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Generar estrellas para calificación
     */
    public static function renderStars(float $rating): string
    {
        $html = '<div class="stars">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $html .= '<svg class="star filled" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
            } elseif ($i - 0.5 <= $rating) {
                $html .= '<svg class="star half" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
            } else {
                $html .= '<svg class="star empty" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}
