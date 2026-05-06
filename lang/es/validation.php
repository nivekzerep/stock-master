<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'max' => [
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
        'file'    => 'El archivo :attribute no debe pesar más de :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'string'  => 'El campo :attribute no debe ser mayor a :max caracteres.',
    ],
    'unique' => 'El :attribute ya ha sido registrado.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'min' => [
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
        'file'    => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
    ],
    'exists' => 'El :attribute seleccionado es inválido.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    
    // --- NUEVAS TRADUCCIONES PARA CONTRASEÑAS ---
    'current_password' => 'La contraseña actual es incorrecta.',
    'password' => 'La contraseña es incorrecta.',
    'same' => 'El campo :attribute y :other deben coincidir.',

    /* Personalización de los nombres de los atributos */
    'attributes' => [
        'name' => 'nombre',
        'username' => 'usuario',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        'category_id' => 'categoría',
        'sku' => 'SKU',
        'price' => 'precio',
        'stock' => 'cantidad',
    ],
];