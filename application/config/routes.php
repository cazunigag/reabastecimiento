<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//rutas Generales
$route['home'] = "login/valida_login";
$route['home/home'] = "login/valida_login/home";
$route['articuloLocacion'] = "articulolocacion/articulo_locacion";
$route['asignacionPedido'] = "asignacionpedido/asignacion_pedido";
$route['redex'] = "centrodistribucion/estanteria/loadCDLayout";
$route['centroAlertas'] = "centroAlertas/centroAlertas";
//rutas Estanteria
$route['estanteria']  = "centrodistribucion/estanteria/loadPisosEstanteria";
$route['piso/(:num)'] = "centrodistribucion/estanteria/loadPasillosEstanteria/$1";
$route['sku/locacion'] = "centrodistribucion/estanteria/getLocnSKU";
$route['sku/pasillo'] = "centrodistribucion/estanteria/getPasilloSKU";
$route['pasillo/(:any)'] = "centrodistribucion/estanteria/loadLocacionesEstanteria/$1";
$route['pasillos/tipoCarton'] = "centrodistribucion/estanteria/getCartonTypePasillos";
$route['pasillos/tipoCartones'] = "centrodistribucion/estanteria/getCartonTypePasillo";
$route['pasillos/utilizacion'] = "centrodistribucion/estanteria/getUtilizacionPasillo";
$route['pasillos/actClase'] = "centrodistribucion/estanteria/actualizarClassTabla";
$route['pasillos/actTipoCarton'] = "centrodistribucion/estanteria/actualizarCartonType";
$route['locaciones/vacias'] = "centrodistribucion/estanteria/getEmptyLocn";
$route['locaciones/detalle/cabecera'] = "centrodistribucion/estanteria/getHeader";
$route['locaciones/antiguedad/descConteoCilcico/(:num)/(:any)'] = "centrodistribucion/estanteria/downloadAntiguedadContCiclico/$1/$2";
$route['locaciones/antiguedad/descSku/(:num)/(:any)'] = "centrodistribucion/estanteria/downloadAntiguedadContCiclico/$1/$2";
$route['locaciones/antiguedad/ConteoCilcico'] = "centrodistribucion/estanteria/getAntiguedadContCiclico";
$route['locaciones/antiguedad/Sku'] = "centrodistribucion/estanteria/getAntiguedadSku";
$route['locaciones/detalle/cabecera'] = "centrodistribucion/estanteria/getHeader";
$route['locaciones/detalle'] = "centrodistribucion/estanteria/getDetalleLocn";
$route['locaciones/detalle/imagen'] = "centrodistribucion/estanteria/getImagenSku";
//rutas Centro Alertas
$route['alertas/wms'] = "centroAlertas/centroAlertas/alertasWMS";

$route['alertas/errores/PKT'] = "centroAlertas/centroAlertas/erroresPKT";
$route['alertas/errores/cantPKT'] = "centroAlertas/centroAlertas/cantErroresPKT";
$route['alertas/pkt/totPKT'] = "centroAlertas/centroAlertas/totPKTBajados";
$route['alertas/pkt/resumen'] = "centroAlertas/centroAlertas/resumenPKT";
$route['alertas/pkt/actualizar'] = "centroAlertas/centroAlertas/reprocesarPKT";
$route['alertas/pkt/eliminar'] = "centroAlertas/centroAlertas/eliminarPKT";

$route['alertas/errores/PO'] = "centroAlertas/centroAlertas/erroresPO";
$route['alertas/errores/cantPO'] = "centroAlertas/centroAlertas/cantErroresPO";
$route['alertas/PO/totPO'] = "centroAlertas/centroAlertas/totPOBajados";
$route['alertas/PO/actualizar'] = "centroAlertas/centroAlertas/reprocesarPO";
$route['alertas/PO/eliminar'] = "centroAlertas/centroAlertas/eliminarPO";

$route['alertas/errores/BRCD'] = "centroAlertas/centroAlertas/erroresBRCD";
$route['alertas/errores/cantBRCD'] = "centroAlertas/centroAlertas/cantErroresBRCD";
$route['alertas/brcd/totBRCD'] = "centroAlertas/centroAlertas/totBRCDBajados";
$route['alertas/brcd/actualizar'] = "centroAlertas/centroAlertas/reprocesarBRCD";
$route['alertas/brcd/eliminar'] = "centroAlertas/centroAlertas/eliminarBRCD";

$route['alertas/errores/ART'] = "centroAlertas/centroAlertas/erroresART";
$route['alertas/errores/cantART'] = "centroAlertas/centroAlertas/cantErroresART";
$route['alertas/art/totART'] = "centroAlertas/centroAlertas/totoARTMod";
$route['alertas/art/actualizar'] = "centroAlertas/centroAlertas/reprocesarART";
$route['alertas/art/eliminar'] = "centroAlertas/centroAlertas/eliminarART";

$route['alertas/errores/OLA'] = "centroAlertas/centroAlertas/erroresOLA";
$route['alertas/errores/cantOLA'] = "centroAlertas/centroAlertas/cantErroresOLA";
$route['alertas/ola/resumen'] = "centroAlertas/centroAlertas/resumenOLA";
$route['alertas/ola/totOLA'] = "centroAlertas/centroAlertas/totOLA";

$route['alertas/errores/CITA'] = "centroAlertas/centroAlertas/erroresCITA";
$route['alertas/errores/cantCITA'] = "centroAlertas/centroAlertas/cantErroresCITA";
$route['alertas/cita/totCITA'] = "centroAlertas/centroAlertas/totCITASBajadas";
$route['alertas/cita/resumen'] = "centroAlertas/centroAlertas/resumenCITA";
$route['alertas/cita/resumenCod'] = "centroAlertas/centroAlertas/detCodCITA";
$route['alertas/cita/actualizar'] = "centroAlertas/centroAlertas/reprocesarCITA";
$route['alertas/cita/eliminar'] = "centroAlertas/centroAlertas/eliminarCITA";

$route['alertas/errores/ASN'] =  "centroAlertas/centroAlertas/erroresASN";
$route['alertas/errores/cantASN'] =  "centroAlertas/centroAlertas/cantErroresASN";
$route['alertas/asn/totASN'] =  "centroAlertas/centroAlertas/totASNBajados";
$route['alertas/asn/resumen'] =  "centroAlertas/centroAlertas/resumenASN";
$route['alertas/asn/resumencod'] =  "centroAlertas/centroAlertas/detCodASN";
$route['alertas/asn/actualizar'] =  "centroAlertas/centroAlertas/reprocesarASN";
$route['alertas/asn/eliminar'] =  "centroAlertas/centroAlertas/eliminarASN";

$route['alertas/errores/LPN'] =  "centroAlertas/centroAlertas/erroresLPN";
$route['alertas/errores/cantLPN'] =  "centroAlertas/centroAlertas/cantErroresLPN";
$route['alertas/lpn/totLPN'] =  "centroAlertas/centroAlertas/totLPNBajados";
$route['alertas/lpn/resumen'] =  "centroAlertas/centroAlertas/resumenLPN";
$route['alertas/lpn/actualizar'] =  "centroAlertas/centroAlertas/reprocesarLPN";
$route['alertas/lpn/eliminar'] =  "centroAlertas/centroAlertas/eliminarLPN";

$route['alertas/errores/DISTRO'] =  "centroAlertas/centroAlertas/erroresDISTRO";
$route['alertas/errores/cantDISTRO'] =  "centroAlertas/centroAlertas/cantErroresDISTRO";
$route['alertas/distro/actualizar'] =  "centroAlertas/centroAlertas/reprocesarDISTRO";
$route['alertas/distro/eliminar'] =  "centroAlertas/centroAlertas/eliminarDISTRO";

$route['alertas/errores/CARGA'] =  "centroAlertas/centroAlertas/erroresCARGA";
$route['alertas/errores/cantCARGA'] =  "centroAlertas/centroAlertas/cantErroresCARGA";
$route['alertas/carga/totCARGA'] =  "centroAlertas/centroAlertas/totCARGASEnviadas";
$route['alertas/carga/resumen'] =  "centroAlertas/centroAlertas/resumenCARGA";
$route['alertas/carga/actualizar'] =  "centroAlertas/centroAlertas/reporcesarCARGA";

$route['alertas/errores/FASN'] =  "centroAlertas/centroAlertas/erroresFASN";
$route['alertas/errores/cantFASN'] =  "centroAlertas/centroAlertas/cantErroresFASN";
$route['alertas/fasn/actualizar'] =  "centroAlertas/centroAlertas/reporcesarFASN";