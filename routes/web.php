<?php

use App\Http\Controllers\ADD_Clientes_servicios_Controller;
use App\Http\Controllers\AdministraciónInventarioController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\CargarMateriaPrimaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CifController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Costos_produccionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\horasController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemsOrdenCompraController;
use App\Http\Controllers\ItemSTEController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MateriaPrimaDirectaController;
use App\Http\Controllers\MateriaPrimaIndirectaController;
use App\Http\Controllers\MateriaPrimasController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\OperativoController;
use App\Http\Controllers\Ordenes_compraController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RemicionesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SDPController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SueldoController;
use App\Http\Controllers\TalentoHConroller;
use App\Http\Controllers\TiemposProduccionController;
use App\Http\Controllers\TrabajadoresController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\servicioExternoController;
use App\Http\Controllers\ServicioSdpController;
use App\Http\Controllers\SolicitudServicioExternoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::middleware(['auth'])->group(function () {

    // home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // offline
    Route::get('/offline', function () {
        return view('vendor.laravelpwa.offline');
    });

    // gestion humana
    Route::get('/gestion-humana', [TalentoHConroller::class, 'index'])->name('gestion-humana');

    // trabajadores
    Route::resource('trabajadores', TrabajadoresController::class);
    Route::get('/butons', [TrabajadoresController::class, 'butons'])->name('trabajador.butons');
    Route::get('/activos', [TrabajadoresController::class, 'activos'])->name('trabajadores.activos');
    Route::get('/inactivos', [TrabajadoresController::class, 'inactivos'])->name('trabajadores.inactivos');
    Route::post('/trabajadores/{id}/disable', [TrabajadoresController::class, 'disable'])->name('trabajadores.disable');
    Route::post('/trabajadores/{id}/enable', [TrabajadoresController::class, 'enable'])->name('trabajadores.enable');
    Route::post('/generate-print-list', [TrabajadoresController::class, 'generatePrintList'])->name('generate.print.list');

    // operarios
    Route::resource('/operarios', OperativoController::class);
    Route::get('/listar-operativos', [OperativoController::class, 'listarOperativos'])->name('listar.operarios');

    // Tiempos de Producción
    Route::resource('tiempos-produccion', TiemposProduccionController::class);
    Route::get('/sdp/{id}/articulos-seleccionados', [TiemposProduccionController::class, 'mostrarArticulosSeleccionados'])
        ->name('sdp.articulos-seleccionados');
    Route::get('/printLista/{id}', [TiemposProduccionController::class, 'print'])->name('tiempos.print');
    Route::patch('/sdps/{id}/abrir', [SdpController::class, 'abrir'])->name('sdps.abrir');
    Route::patch('/sdps/{id}/cerrar', [SdpController::class, 'cerrar'])->name('sdps.cerrar');
    Route::get('/getServiciosSdp/{numeroSdp}', [TiemposProduccionController::class, 'getServiciosSdp']);

    // operarios
    Route::get('grupos', [TiemposProduccionController::class, 'groupByOperario'])->name('tiempos.group');
    Route::get('tiempos-produccion/operario/{codigoOperario}', [TiemposProduccionController::class, 'index'])
        ->name('tiempos-produccion.operario');

    // sueldo
    Route::resource('sueldo', SueldoController::class);
    Route::get('sueldos/create/{trabajador}', [SueldoController::class, 'create'])->name('sueldos.create');
    Route::get('sueldos/{sueldo}/edit', [SueldoController::class, 'edit'])->name('sueldos.edit');

    // nomina pack
    Route::get('/nomina', [NominaController::class, 'index'])->name('nomina.index');
    Route::post('/nomina/crear-paquete', [NominaController::class, 'crearPaquete'])->name('nomina.crearPaquete');
    Route::get('/nomina/{paquete}', [NominaController::class, 'show'])->name('nomina.show');
    Route::get('/nominas/paquete/{paquete}', [NominaController::class, 'obtenerNominasEspecificas']);
    Route::post('/nominas/update-bulk', [NominaController::class, 'updateBulk'])->name('nominas.update-bulk');
    Route::delete('/paquete_nominas/{id}', [NominaController::class, 'destroy'])->name('paquete_nominas.destroy');
    Route::get('/nomina/{id}/desprendible', [NominaController::class, 'mostrarDesprendible'])->name('nomina.desprendible');
    // export
    Route::get('/nomina/export/{paquete}', [ExportController::class, 'export'])->name('nominas.export');
    Route::get('paquteNomina/{id}/edit', [NominaController::class, 'editNomina'])->name('paqueteNomina.edit');
    Route::put('paquteNomina/{id}/update', [NominaController::class, 'updateNomina'])->name('paquetaNomina.update');
    Route::post('/nomina/{paquete}/add-worker', [NominaController::class, 'addWorker'])->name('nomina.addWorker');
    // horas extras
    Route::resource('horas-extras', horasController::class);

    // configuraciones
    Route::resource('configuraciones', ConfiguracionController::class);


    // Administracion de clientes y servicios
    Route::get('/ADD_Clientes_servicios', [ADD_Clientes_servicios_Controller::class, 'index'])->name('ADD_C_S');


    // SDP
    Route::resource('sdp', SDPController::class);
    Route::get('/sdp-paquetes', [SDPController::class, 'indexPaquetes'])->name('sdp.paquetes');
    Route::get('/sdp-ver/{id}', [SDPController::class, 'ver'])->name('sdp.ver');
    Route::get('/api/getArticulos/{sdpId}', [TiemposProduccionController::class, 'getArticulos']);
    Route::post('/tiemposproduccion/select-sdp', [TiemposProduccionController::class, 'selectSdp'])
        ->name('tiemposproduccion.selectSdp');
    Route::patch('/sdps/{id}/abrir', [SdpController::class, 'abrir'])->name('sdps.abrir');
    Route::patch('/sdps/{id}/cerrar', [SdpController::class, 'cerrar'])->name('sdps.cerrar');


    // Artículos
    Route::get('articulos', [ArticuloController::class, 'index'])->name('articulos.index');
    Route::post('/api/articulos', [ArticuloController::class, 'store'])->name('articulos.store');
    Route::get('/api/buscar-articulos', [ArticuloController::class, 'buscarArticulos']);
    Route::delete('/articulos/{id}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');
    Route::get('/articulos/{id}/edit', [ArticuloController::class, 'edit'])->name('articulos.edit');
    Route::put('/articulos/{id}/update', [ArticuloController::class, 'update'])->name('articulos.update');
    Route::get('/api/precio-articulo-sdp', [ArticuloController::class, 'getPrecioArticuloSdp']);
    // vendedores
    Route::resource('vendedor', VendedorController::class);
    Route::post('vendedor/{id}/disable', [VendedorController::class, 'disable'])->name('vendedor.disable');
    Route::post('vendedor/{id}/enable', [VendedorController::class, 'enable'])->name('vendedor.enable');

    // clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('buttons', [ClienteController::class, 'buttons'])->name('clientes-comerciales');
    Route::get('clientes-william', [ClienteController::class, 'william'])->name('clientes-william');
    Route::get('clientes-fabian', [ClienteController::class, 'fabian'])->name('clientes-fabian');
    Route::get('clientes-ochoa', [ClienteController::class, 'ochoa'])->name('clientes-ochoa');

    // Ubicaciones
    Route::get('/departamentos', [LocationController::class, 'getDepartamentos']);
    Route::get('/municipios/{departamentoId}', [MunicipioController::class, 'getMunicipios']);
    Route::post('/municipios/{departamentoId}/add', [MunicipioController::class, 'addMunicipio']);
    Route::post('/municipios', [MunicipioController::class, 'store']);


    // remiciones despacho
    Route::resource('remiciones', RemicionesController::class);


    Route::get('/remisiones/despacho', [RemicionesController::class, 'Despacho'])->name('remision.despacho');
    Route::get('/remisiones/despacho/create', [RemicionesController::class, 'createDespacho'])->name('remision.despacho.create');
    Route::post('/remisiones/despacho/store', [RemicionesController::class, 'storeDespacho'])->name('remision.despacho.store');
    Route::get('/remisiones/despacho/{id}/show', [RemicionesController::class, 'showDespacho'])->name('remision.despacho.show');
    Route::get('/remisiones/despacho/{id}/edit', [RemicionesController::class, 'editDespacho'])->name('remision.despacho.edit');
    Route::put('/remisiones/despacho/{id}/update', [RemicionesController::class, 'updateDespacho'])->name('remision.despacho.update');
    Route::delete('/remisiones/despacho/{id}/destroy', [RemicionesController::class, 'destroyDespacho'])->name('remision.despacho.destroy');

    // remisiones ingreso
    Route::get('/remisiones/ingreso/create', [RemicionesController::class, 'createIngreso'])->name('remision.ingreso.create');
    Route::post('/remisiones/ingreso/store', [RemicionesController::class, 'storeIngreso'])->name('remision.ingreso.store');
    Route::get('/remisiones/ingreso', [RemicionesController::class, 'Ingreso'])->name('remision.ingreso');
    Route::get('/cliente/{cliente}/sdps', [RemicionesController::class, 'getSdpsByCliente']);
    Route::get('/remisiones/ingreso/{id}/show', [RemicionesController::class, 'showIngreso'])->name('remision.ingreso.show');
    Route::get('/remisiones/ingreso/{id}/edit', [RemicionesController::class, 'editIngreso'])->name('remision.ingreso.edit');
    Route::put('/remisiones/ingreso/{id}/update', [RemicionesController::class, 'updateIngreso'])->name('remision.ingreso.update');
    Route::delete('/remisiones/ingreso/{id}/destroy', [RemicionesController::class, 'destroyIngreso'])->name('remision.ingreso.destroy');

    // items 
    Route::resource('items', ItemController::class);
    Route::get('api/buscar-items', [ItemController::class, 'searchItem']);

    // solicitud servicio Externo
    Route::resource('SSE', SolicitudServicioExternoController::class);

    // item ste 
    Route::resource('item-ste', ItemSTEController::class);
    Route::get('api/buscar-item-ste', [ItemSTEController::class, 'searchIem']);

    // servicios
    route::get('/servicio', [ServicioController::class, 'mainS'])->name('servicio');
    Route::resource('servicios', ServicioController::class);
    Route::get('/servicios-sdps', [ServicioController::class, 'indexSdp'])->name('servicio.index');

    // servicios costos
    Route::get('/sdps/{sdp}/servicios', [ServicioSdpController::class, 'show'])->name('serviciosCostos.show');
    Route::put('/sdps/{sdp}/servicios/{servicio}', [ServicioSdpController::class, 'actualizarPrecioServicio'])->name('sdp_servicios.actualizar');

    // almacen
    Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen');

    // MATERIAS PRIMAS
    Route::resource('materias_primas', MateriaPrimasController::class);
    Route::get('listaMateriasDirectas', [MateriaPrimaDirectaController::class, 'indexDirectas'])->name('materiaDirecta.index');
    Route::get('listaMateriasIndirectas', [MateriaPrimaIndirectaController::class, 'indexIndirectas'])->name('materiaIndirecta.index');

    // materias primas directas
    Route::get('materiasPrimasDirectas/create', [MateriaPrimaDirectaController::class, 'create'])->name('materiasPrimasDirectas.create');
    Route::post('materiasPrimasDirectas', [MateriaPrimaDirectaController::class, 'store'])->name('materiasPrimasDirectas.store');
    Route::get('materiasPrimasDirectas/{id}/edit', [MateriaPrimaDirectaController::class, 'edit'])->name('materiasPrimasDirectas.edit');
    Route::put('materiasPrimasDirectas/{id}/update', [MateriaPrimaDirectaController::class, 'update'])->name('materiasPrimasDirectas.update');
    Route::delete('materiasPrimasDirectas/{id}/delete', [MateriaPrimaDirectaController::class, 'destroy'])->name('materiasPrimasDirectas.destroy');

    // materias primas indirectas
    Route::get('materiasPrimasIndirectas/create', [MateriaPrimaIndirectaController::class, 'create'])->name('materiasPrimasIndirectas.create');
    Route::post('materiasPrimasIndirectas', [MateriaPrimaIndirectaController::class, 'store'])->name('materiasPrimasIndirectas.store');
    Route::get('materiasPrimasIndirectas/{id}', [MateriaPrimaIndirectaController::class, 'edit'])->name('materiasPrimasIndirectas.edit');
    Route::put('materiasPrimasIndirectas/{id}/update', [MateriaPrimaIndirectaController::class, 'update'])->name('materiasPrimasIndirectas.update');
    Route::delete('materiasPrimasIndirectas/{id}/delete', [MateriaPrimaIndirectaController::class, 'destroy'])->name('materiasPrimasIndirectas.destroy');

    // cargar materia prima
    Route::get('lista-spd-cargar', [cargarMateriaPrimaController::class, 'lista'])->name('lista.sdp.cargar');
    Route::get('cargar-materias/{numero_sdp}', [CargarMateriaPrimaController::class, 'create'])->name('cargar.materias.form');
    Route::post('cargar-materias/{numero_sdp}', [CargarMateriaPrimaController::class, 'store'])->name('materias.store');
    Route::get('/api/buscar-materias', [CargarMateriaPrimaController::class, 'buscarMaterias']);
    Route::get('/ver-materias-primas-cragada/{numero_sdp}', [CargarMateriaPrimaController::class, 'verMateriasPrimas'])->name('verMateriasPrimas');



    // proveedor
    Route::resource('proveedor', ProveedorController::class);

    // compras
    Route::resource('Ordencompras', Ordenes_compraController::class);
    Route::get('/api/buscar-items-orden-compra', [Ordenes_compraController::class, 'BuscarItems']);

    // itemsOrdenCompra
    Route::post('/item-orden-compra/store', [ItemsOrdenCompraController::class, 'store'])->name('itemOrden.store');

    // inventario
    Route::resource('inventario', InventoryController::class);
    Route::get('inventario/bajo_minimos', [InventoryController::class, 'bajoMinimos'])->name('inventario.bajo_minimos');

    // categorias
    Route::resource('categorias', CategoryController::class);

    // productos

    route::resource('productos', ProductosController::class);

    // CIF
    Route::get('cif', [CifController::class, 'index'])->name('cif.index');
    Route::get('cif/{id}/edit', [CifController::class, 'edit'])->name('cif.edit');
    Route::put('cif/{id}/update', [CifController::class, 'update'])->name('cif.update');

    // costos de produccion
    Route::get('/costos_produccion', [Costos_produccionController::class, 'index'])->name('costos_produccion.index');
    Route::get('/costos_produccion/{id}', [Costos_produccionController::class, 'show'])->name('costos_produccion.show');
    Route::post('/recalcular-mano-obra-directa', [Costos_produccionController::class, 'recalcularManoObraDirecta'])
        ->name('recalcular.mano.obra.directa');
    Route::get('/costos_produccion/{id}/resumen', [costos_produccionController::class, 'resumen'])->name('resumen.costos');

    // Roles
    Route::resource('/roles', RoleController::class);

    // permisos
    Route::resource('/permisos', PermissionController::class);
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/{permission}/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permisos-role/{id}', [RoleController::class, 'show'])->name('permisos.role');
    // usuarios
    Route::resource('/users', UsersController::class);

    // Administración de Inventario
    Route::get('AdministraciónInventario', [AdministraciónInventarioController::class, 'index'])->name('AdministraciónInventario');

    // servocios Externos 
    Route::resource('serviciosExternos', servicioExternoController::class);
});
