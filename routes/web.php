<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Login
$router->post('login', 'LoginController@login');

// Contactos
$router->post('registrar', 'PersonaController@store');
$router->get('listar_personas/{id_usuario}', 'PersonaController@index');
$router->get('eliminar_persona/{id}', 'PersonaController@delete');
$router->get('detalle_persona/{id}', 'PersonaController@show');
$router->post('actualizar_persona', 'PersonaController@update');

$router->get('profesiones', 'PersonaController@profesiones');
$router->get('vias_contacto', 'PersonaController@vias_contacto');

// Medios de Contacto
$router->get('obtener_medios_contacto/{id}', 'MedioContactoController@medios_contacto');
$router->get('medios_contacto', 'MedioContactoController@tipos_contacto');
$router->post('registrar_medio_contacto', 'MedioContactoController@registrar');
$router->get('eliminar_medio_contacto/{id}', 'MedioContactoController@eliminar');
$router->get('detalle_medio_contacto/{id}', 'MedioContactoController@detalle');
$router->post('editar_medio_contacto', 'MedioContactoController@editar');

// Usuarios
$router->get('obtener_usuarios/{id}', 'UsuarioController@obtener_usuarios');
$router->post('registrar_usuario', 'UsuarioController@registrar_usuario');
$router->get('eliminar_usuario/{id}', 'UsuarioController@eliminar_usuario');
$router->get('detalle_usuario/{id}', 'UsuarioController@detalle_usuario');
$router->post('actualizar_usuario', 'UsuarioController@actualizar_usuario');
$router->get('zonas_usuario/{id_usuario}', 'UsuarioController@zonas_usuario');

$router->get('zonas_completas_usuario/{id_usuario}', 'UsuarioController@zonas_completas_usuario');

$router->get('t_zonas_usuario/{id_usuario}', 'UsuarioController@t_zonas_usuario');
$router->post('eliminar_zona_usuario', 'UsuarioController@eliminar_zona_usuario');
$router->get('zonas_disponibles_usuario/{id_usuario}', 'UsuarioController@zonas_disponibles_usuario');
$router->post('registrar_zonas_usuario', 'UsuarioController@registrar_zonas_usuario');

// Clasificación de contactos
$router->get('obtener_clasificaciones/{zona}', 'ClasificacionController@obtener_clasificaciones');

// Tipos de contactos
$router->get('tipos_contactos', 'TipoContactoController@tipos_contactos');

// Profesiones
$router->get('obtener_profesiones', 'ProfesionController@obtener_profesiones');
$router->post('registrar_profesion', 'ProfesionController@registrar');
$router->post('editar_profesion', 'ProfesionController@editar');
$router->get('eliminar_profesion/{id}', 'ProfesionController@delete');
$router->get('detalle_profesion/{id}', 'ProfesionController@show');

// Vias de contacto
$router->get('obtener_vias_contacto', 'ViaContactoController@obtener_vias');
$router->post('registrar_via_contacto', 'ViaContactoController@store');
$router->get('eliminar_via_contacto/{id}', 'ViaContactoController@delete');
$router->get('detalle_via_contacto/{id}', 'ViaContactoController@show');
$router->post('editar_via_contacto', 'ViaContactoController@edit');

// Indicadores
$router->get('obtener_indicadores', 'IndicadorController@obtener_indicadores');
$router->post('indicador_total_clasificacion', 'IndicadorController@indicador_total_clasificacion');
$router->post('indicador_contactos_ingresados', 'IndicadorController@indicador_contactos_ingresados');
$router->post('indicador_reuniones', 'IndicadorController@indicador_reuniones');
$router->post('indicador_convivencias', 'IndicadorController@indicador_convivencias');
$router->post('indicador_organizaciones', 'IndicadorController@indicador_organizaciones');
$router->post('indicador_visitas', 'IndicadorController@indicador_visitas');
$router->post('indicador_total_indicadores', 'IndicadorController@indicador_total_indicadores');
$router->post('total_ponderado', 'IndicadorController@total_ponderado');

// Metas
$router->post('registrar_meta', 'MetaController@store');
$router->get('obtener_metas/{id}', 'MetaController@index');
$router->get('eliminar_meta/{id}', 'MetaController@delete');
$router->get('detalle_meta/{id}', 'MetaController@show');
$router->post('editar_meta', 'MetaController@edit');

// Home
$router->get('obtener_accesos/{id}', 'MenuController@accesos_usuario');
$router->get('menu_usuario/{id}', 'MenuController@menu_usuario');
$router->get('menu_disponible_usuario/{id}', 'MenuController@menu_disponible_usuario');
$router->post('eliminar_acceso', 'MenuController@eliminar_acceso');
$router->post('registrar_accesos', 'MenuController@registrar_accesos');

// Rangos de edad
$router->get('obtener_rangos', 'RangoController@index');

// Reuniones
$router->post('obtener_reuniones', 'ReunionController@index');
$router->post('registrar_reunion', 'ReunionController@store');
$router->get('eliminar_reunion/{id}', 'ReunionController@delete');
$router->get('detalle_reunion/{id}', 'ReunionController@show');
$router->post('editar_reunion', 'ReunionController@edit');
$router->get('tipo_reunion/{id}', 'ReunionController@tipo_reunion');

// Convivencias
$router->get('tipo_convivencia/{id}', 'ConvivenciaController@tipo_convivencia');
$router->post('obtener_convivencias', 'ConvivenciaController@index');
$router->post('registrar_convivencia', 'ConvivenciaController@store');
$router->get('eliminar_convivencia/{id}', 'ConvivenciaController@delete');
$router->get('detalle_convivencia/{id}', 'ConvivenciaController@show');
$router->post('editar_convivencia', 'ConvivenciaController@edit');

// Organizaciones
$router->post('obtener_organizaciones', 'OrganizacionController@index');
$router->post('registrar_organizacion', 'OrganizacionController@store');
$router->get('eliminar_organizacion/{id}', 'OrganizacionController@delete');
$router->get('detalle_organizacion/{id}', 'OrganizacionController@show');
$router->post('editar_organizacion', 'OrganizacionController@edit');

// Visitas
$router->post('obtener_visitas', 'VisitaController@index');
$router->post('registrar_visita', 'VisitaController@store');
$router->get('eliminar_visita/{id}', 'VisitaController@delete');
$router->get('detalle_visita/{id}', 'VisitaController@show');
$router->post('editar_visita', 'VisitaController@edit');

// Imagenes
$router->post('subir_imagen_reunion', 'ImagenController@subir_imagen_reunion');
$router->post('subir_imagen_convivencia', 'ImagenController@subir_imagen_convivencia');
$router->get('descargar_imagen', 'ImagenController@descargar_imagen');

// Documentos
$router->post('subir_documento_reunion', 'DocumentoController@subir_documento_reunion');
$router->get('descargar_documento/{id}', 'DocumentoController@descargar_documento');

// Calendario
$router->post('registrar_evento', 'CalendarioController@registrar_evento');
$router->post('obtener_eventos', 'CalendarioController@obtener_eventos');
$router->get('detalle_evento/{id}', 'CalendarioController@detalle_evento');
$router->get('eliminar_evento/{id}', 'CalendarioController@eliminar_evento');
$router->post('editar_evento', 'CalendarioController@editar_evento');
