<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Nombre de usuario.
     *
     * @var string
     */
    public static string $name = "Abel David";

    /**
     * Dirección de correo.
     *
     * @var string
     */
    public static string $email = "lleraabi@gmail.com";

    /**
     * Contraseña.
     *
     * @var string
     */
    public static string $password = "abcd1234";

    /**
     * Encabezados predeterminados.
     *
     * @var string[]
     */
    protected $defaultHeaders = [
        'ContentType' => 'application/json',
        'Accept' => 'application/json'
    ];
}
