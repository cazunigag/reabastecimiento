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
$route['pasillos/tipoCartones/todos'] = "centrodistribucion/estanteria/getCartonTypes";
$route['pasillos/utilizacion'] = "centrodistribucion/estanteria/getUtilizacionPasillo";
$route['pasillos/actClase'] = "centrodistribucion/estanteria/actualizarClassTabla";
$route['pasillos/actTipoCarton'] = "centrodistribucion/estanteria/actualizarCartonType";
$route['locaciones/vacias'] = "centrodistribucion/estanteria/getEmptyLocn";
$route['locaciones/detalle/cabecera'] = "centrodistribucion/estanteria/getHeader";
$route['locaciones/antiguedad/descConteoCilcico/(:num)/(:any)'] = "centrodistribucion/estanteria/downloadAntiguedadContCiclico/$1/$2";
$route['locaciones/antiguedad/descSku/(:num)/(:any)'] = "centrodistribucion/estanteria/downloadExcelAntiguedadSku/$1/$2";
$route['locaciones/antiguedad/ConteoCilcico'] = "centrodistribucion/estanteria/getAntiguedadContCiclico";
$route['locaciones/antiguedad/Sku'] = "centrodistribucion/estanteria/getAntiguedadSku";
$route['locaciones/detalle/cabecera'] = "centrodistribucion/estanteria/getHeader";
$route['locaciones/detalle'] = "centrodistribucion/estanteria/getDetalleLocn";
$route['locaciones/detalle/imagen'] = "centrodistribucion/estanteria/getImagenSku";
//rutas Centro Alertas
$route['alertas/wms'] = "centroAlertas/centroAlertas/alertasWMS";
$route['alertas/bt'] = "centroAlertas/centroAlertas/alertasBT";
$route['alertas/pmm'] = "centroAlertas/centroAlertas/alertasPMM";
//rutas alertas WMS
$route['alertas/wms/errores/PKT'] = "centroAlertas/WMS/alertasWMS/erroresPKT";
$route['alertas/wms/errores/cantPKT'] = "centroAlertas/WMS/alertasWMS/cantErroresPKT";
$route['alertas/wms/pkt/totPKT'] = "centroAlertas/WMS/alertasWMS/totPKTBajados";
$route['alertas/wms/pkt/resumen'] = "centroAlertas/WMS/alertasWMS/resumenPKT";
$route['alertas/wms/pkt/actualizar'] = "centroAlertas/WMS/alertasWMS/reprocesarPKT";
$route['alertas/wms/pkt/eliminar'] = "centroAlertas/WMS/alertasWMS/eliminarPKT";

$route['alertas/wms/errores/PO'] = "centroAlertas/WMS/alertasWMS/erroresPO";
$route['alertas/wms/errores/cantPO'] = "centroAlertas/WMS/alertasWMS/cantErroresPO";
$route['alertas/wms/PO/totPO'] = "centroAlertas/WMS/alertasWMS/totPOBajados";
$route['alertas/wms/PO/actualizar'] = "centroAlertas/WMS/alertasWMS/reprocesarPO";
$route['alertas/wms/PO/eliminar'] = "centroAlertas/WMS/alertasWMS/eliminarPO";

$route['alertas/wms/errores/BRCD'] = "centroAlertas/WMS/alertasWMS/erroresBRCD";
$route['alertas/wms/errores/cantBRCD'] = "centroAlertas/WMS/alertasWMS/cantErroresBRCD";
$route['alertas/wms/brcd/totBRCD'] = "centroAlertas/WMS/alertasWMS/totBRCDBajados";
$route['alertas/wms/brcd/actualizar'] = "centroAlertas/WMS/alertasWMS/reprocesarBRCD";
$route['alertas/wms/brcd/eliminar'] = "centroAlertas/WMS/alertasWMS/eliminarBRCD";

$route['alertas/wms/errores/ART'] = "centroAlertas/WMS/alertasWMS/erroresART";
$route['alertas/wms/errores/cantART'] = "centroAlertas/WMS/alertasWMS/cantErroresART";
$route['alertas/wms/art/totART'] = "centroAlertas/WMS/alertasWMS/totoARTMod";
$route['alertas/wms/art/actualizar'] = "centroAlertas/WMS/alertasWMS/reprocesarART";
$route['alertas/wms/art/eliminar'] = "centroAlertas/WMS/alertasWMS/eliminarART";

$route['alertas/wms/errores/OLA'] = "centroAlertas/WMS/alertasWMS/erroresOLA";
$route['alertas/wms/errores/cantOLA'] = "centroAlertas/WMS/alertasWMS/cantErroresOLA";
$route['alertas/wms/ola/resumen'] = "centroAlertas/WMS/alertasWMS/resumenOLA";
$route['alertas/wms/ola/totOLA'] = "centroAlertas/WMS/alertasWMS/totOLA";

$route['alertas/wms/errores/CITA'] = "centroAlertas/WMS/alertasWMS/erroresCITA";
$route['alertas/wms/errores/cantCITA'] = "centroAlertas/WMS/alertasWMS/cantErroresCITA";
$route['alertas/wms/cita/totCITA'] = "centroAlertas/WMS/alertasWMS/totCITASBajadas";
$route['alertas/wms/cita/resumen'] = "centroAlertas/WMS/alertasWMS/resumenCITA";
$route['alertas/wms/cita/resumenCod'] = "centroAlertas/WMS/alertasWMS/detCodCITA";
$route['alertas/wms/cita/actualizar'] = "centroAlertas/WMS/alertasWMS/reprocesarCITA";
$route['alertas/wms/cita/eliminar'] = "centroAlertas/WMS/alertasWMS/eliminarCITA";

$route['alertas/wms/errores/ASN'] =  "centroAlertas/WMS/alertasWMS/erroresASN";
$route['alertas/wms/errores/cantASN'] =  "centroAlertas/WMS/alertasWMS/cantErroresASN";
$route['alertas/wms/asn/totASN'] =  "centroAlertas/WMS/alertasWMS/totASNBajados";
$route['alertas/wms/asn/resumen'] =  "centroAlertas/WMS/alertasWMS/resumenASN";
$route['alertas/wms/asn/resumencod'] =  "centroAlertas/WMS/alertasWMS/detCodASN";
$route['alertas/wms/asn/actualizar'] =  "centroAlertas/WMS/alertasWMS/reprocesarASN";
$route['alertas/wms/asn/eliminar'] =  "centroAlertas/WMS/alertasWMS/eliminarASN";

$route['alertas/wms/errores/LPN'] =  "centroAlertas/WMS/alertasWMS/erroresLPN";
$route['alertas/wms/errores/cantLPN'] =  "centroAlertas/WMS/alertasWMS/cantErroresLPN";
$route['alertas/wms/lpn/totLPN'] =  "centroAlertas/WMS/alertasWMS/totLPNBajados";
$route['alertas/wms/lpn/resumen'] =  "centroAlertas/WMS/alertasWMS/resumenLPN";
$route['alertas/wms/lpn/actualizar'] =  "centroAlertas/WMS/alertasWMS/reprocesarLPN";
$route['alertas/wms/lpn/eliminar'] =  "centroAlertas/WMS/alertasWMS/eliminarLPN";

$route['alertas/wms/errores/DISTRO'] =  "centroAlertas/WMS/alertasWMS/erroresDISTRO";
$route['alertas/wms/errores/cantDISTRO'] =  "centroAlertas/WMS/alertasWMS/cantErroresDISTRO";
$route['alertas/wms/distro/actualizar'] =  "centroAlertas/WMS/alertasWMS/reprocesarDISTRO";
$route['alertas/wms/distro/eliminar'] =  "centroAlertas/WMS/alertasWMS/eliminarDISTRO";

$route['alertas/wms/errores/CARGA'] =  "centroAlertas/WMS/alertasWMS/erroresCARGA";
$route['alertas/wms/errores/cantCARGA'] =  "centroAlertas/WMS/alertasWMS/cantErroresCARGA";
$route['alertas/wms/carga/totCARGA'] =  "centroAlertas/WMS/alertasWMS/totCARGASEnviadas";
$route['alertas/wms/carga/resumen'] =  "centroAlertas/WMS/alertasWMS/resumenCARGA";
$route['alertas/wms/carga/actualizar'] =  "centroAlertas/WMS/alertasWMS/reporcesarCARGA";

$route['alertas/wms/errores/FASN'] =  "centroAlertas/WMS/alertasWMS/erroresFASN";
$route['alertas/wms/errores/cantFASN'] =  "centroAlertas/WMS/alertasWMS/cantErroresFASN";
$route['alertas/wms/fasn/actualizar'] =  "centroAlertas/WMS/alertasWMS/reporcesarFASN";

//rutas alertas BT

$route['alertas/bt/errores/sinProcSDI'] = "centroAlertas/BT/alertasBT/sinProcesarSDI";
$route['alertas/bt/errores/cantSinProcSDI'] = "centroAlertas/BT/alertasBT/cantSinProcesarSDI";