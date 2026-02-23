<?php
declare(strict_types=1);

namespace App\Utils;

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Campo obligatorio
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim((string) $this->data[$field]) === '') {
            $this->errors[$field] = "El campo {$label} es obligatorio.";
        }
        return $this;
    }

    /**
     * Validar email
     */
    public function email(string $field, string $label = 'email'): self
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "El {$label} no tiene un formato válido.";
        }
        return $this;
    }

    /**
     * Longitud mínima
     */
    public function minLength(string $field, int $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen((string) $this->data[$field]) < $min) {
            $this->errors[$field] = "El campo {$label} debe tener al menos {$min} caracteres.";
        }
        return $this;
    }

    /**
     * Longitud máxima
     */
    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen((string) $this->data[$field]) > $max) {
            $this->errors[$field] = "El campo {$label} no debe exceder {$max} caracteres.";
        }
        return $this;
    }

    /**
     * Confirmar que dos campos coinciden
     */
    public function matches(string $field1, string $field2, string $label = 'Las contraseñas'): self
    {
        if (isset($this->data[$field1], $this->data[$field2]) && $this->data[$field1] !== $this->data[$field2]) {
            $this->errors[$field2] = "{$label} no coinciden.";
        }
        return $this;
    }

    /**
     * Validar que es numérico
     */
    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "El campo {$label} debe ser numérico.";
        }
        return $this;
    }

    /**
     * Verificar si la validación pasó
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Verificar si la validación falló
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Obtener errores
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Obtener primer error
     */
    public function getFirstError(): string
    {
        return reset($this->errors) ?: '';
    }
}
