<?php
return [
    'EmailSend_OK' => 'Correo enviado correctamente a ',
    'EmailSend_KO' => 'Error al enviar el correo: ',
    'State_OK' => 'Estado del Certitificado, tu certificado está activo y válido',
    'EmailHtml_OK' => 'Confirmamos que <strong>{{:name}}</strong> posee un certificado con número <strong>{{:num_regis}}</strong> activo y válido otorgado por Vaelsys.<br><br>Gracias por su consulta.',
    'EmailTxt_OK' => 'Confirmamos que {{:name}} posee un certificado con número {{:num_regis}} activo y válido otorgado por Vaelsys. Gracias por su consulta',
    'State_KO' => 'Estado del Certitificado, no se encontró el certificado',
    'EmailHtml_KO' => 'No se ha encontrado ningún certificado activo en nuestros registros a nombre de <strong>{{:name}}</strong> y número de certificado <strong>{{:num_regis}}</strong>.<br><br>Por favor, verifica que la información ingresada sea correcta.<br><br>Gracias por su consulta.',
    'EmailTxt_KO' => 'No se ha encontrado ningún certificado activo en nuestros registros a nombre de {{:name}} y número de certificado {{:num_regis}}. Por favor, verifica que la información ingresada sea correcta. Gracias por su consulta.',
    'State_Lapsed' => 'Estado del Certitificado, tu certificado ha caducado',
    'EmailHtml_Lapsed' => '<strong>{{:name}}</strong> cuenta con un certificado emitido por Vaelsys, pero su vigencia ha expirado.<br><br>Gracias por su consulta.',
    'EmailTxt_Lapsed' => '{{:name}} cuenta con un certificado emitido por Vaelsys, pero su vigencia ha expirado. Gracias por su consulta.',
];