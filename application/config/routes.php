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
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//rutas Generales
$route['home'] = "login/home";
$route['articuloLocacion'] = "articulolocacion/articulo_locacion";
$route['asignacionPedido'] = "asignacionpedido/asignacion_pedido";
$route['redex'] = "centrodistribucion/estanteria/loadCDLayout";
$route['centroAlertas'] = "centroAlertas/centroAlertas";
$route['seteoAttr'] = "seteoAtributos/SeteoAtributos";
$route['LPNDemora'] = "LPNDemora/LPNDemora";
$route['CambioDemora'] = "LPNDemora/CambioDemoraLPN";
$route['CambioDemoraLOCN'] = "LPNDemora/CambioDemoraLOCN";
$route['CambioDemoraCarton'] = "LPNDemora/CambioDemoraCarton";
$route['seteo308'] = "Reabastecimiento/Departamentos/Departamentos";
$route['almacenamientolocn'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn";
$route['sublineas'] = "Reabastecimiento/Mantenedores/Sublineas";
$route['cartontype'] = "centrodistribucion/CartonType";
$route['cartontypearticulo'] = "centrodistribucion/CartonTypeArticulo";
$route['diferenciaInventario'] = "DiferenciaInventario/DiferenciaInventario";
$route['PKTCancelados'] = "DiferenciaInventario/PKTCancelados/PKTCancelados";
$route['AseguramientoCalidad'] = "AseguramientoCalidad/AseguramientoCalidad";
$route['lector'] = "LectorCUD/LectorCUD/picking";
$route['lectorv2'] = "LectorCUD/LectorCUD/Testpicking";
$route['faltantes'] = "LectorCUD/LectorCUD/faltantes";
$route['lector/dashboard'] = "LectorCUD/LectorCUD/dashboard";
$route['devueltos'] = "LectorCUD/LectorCUD/devueltos";
$route['asignacionManual'] = "LectorCUD/LectorCUD/asignacionManual";
$route['workarea'] = "centrodistribucion/WorkArea";
$route['descuadraturainv'] = "DescuadraturaInv/DescuadraturaInv";

//rutas login
$route['auth'] = "login/auth";
$route['logout'] = "login/logOut";
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
$route['pasillos/actTipoCartonArticulo'] = "centrodistribucion/estanteria/actualizarCartonTypeArticulo";
$route['pasillos/actTipoCartonEstilo'] = "centrodistribucion/estanteria/actualizarCartonTypeEstilo";
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
$route['alertas/eis'] = "centroAlertas/centroAlertas/alertasEIS";

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
$route['alertas/wms/PO/verificar'] = "centroAlertas/WMS/alertasWMS/verificarOC";

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
$route['alertas/wms/asn/verificar'] =  "centroAlertas/WMS/alertasWMS/verificarASN";
$route['alertas/wms/asn/untshpd'] =  "centroAlertas/WMS/alertasWMS/unidadesEnviadasASN";

$route['alertas/wms/errores/LPN'] =  "centroAlertas/WMS/alertasWMS/erroresLPN";
$route['alertas/wms/errores/cantLPN'] =  "centroAlertas/WMS/alertasWMS/cantErroresLPN";
$route['alertas/wms/lpn/totLPN'] =  "centroAlertas/WMS/alertasWMS/totLPNBajados";
$route['alertas/wms/lpn/resumen'] =  "centroAlertas/WMS/alertasWMS/resumenLPN";
$route['alertas/wms/lpn/actualizar'] =  "centroAlertas/WMS/alertasWMS/reprocesarLPN";
$route['alertas/wms/lpn/eliminar'] =  "centroAlertas/WMS/alertasWMS/eliminarLPN";
$route['alertas/wms/lpn/verificar'] =  "centroAlertas/WMS/alertasWMS/verificarLPN";

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

$route['alertas/wms/errores/PST'] =  "centroAlertas/WMS/alertasWMS/pasillosSinWorkGroup";
$route['alertas/wms/errores/cantPST'] =  "centroAlertas/WMS/alertasWMS/cantPasillosSinWorkGroup";

$route['alertas/wms/errores/INTF'] =  "centroAlertas/WMS/alertasWMS/invnNeedTypeFaltantes";
$route['alertas/wms/errores/cantINTF'] =  "centroAlertas/WMS/alertasWMS/cantInvnNeedTypeFaltantes";

$route['alertas/wms/errores/LPNNOALM'] =  "centroAlertas/WMS/alertasWMS/LPNNOALM";
$route['alertas/wms/errores/cantLPNNOALM'] =  "centroAlertas/WMS/alertasWMS/cantLPNNOALM";

//rutas alertas BT

$route['alertas/bt/errores/sinProcSDI'] = "centroAlertas/BT/alertasBT/sinProcesarSDI";
$route['alertas/bt/errores/cantSinProcSDI'] = "centroAlertas/BT/alertasBT/cantSinProcesarSDI";

$route['alertas/bt/errores/malEnviadosBT'] = "centroAlertas/BT/alertasBT/malEnviadosBT";
$route['alertas/bt/errores/cantMalEnviadosBT'] = "centroAlertas/BT/alertasBT/cantMalEnviadosBT";

$route['alertas/bt/errores/pickTicketDuplicados'] = "centroAlertas/BT/alertasBT/pickTicketDuplicados";
$route['alertas/bt/errores/cantPickTicketDuplicados'] = "centroAlertas/BT/alertasBT/cantPickTicketDuplicados";
$route['alertas/bt/actualizarPKT'] = "centroAlertas/BT/alertasBT/actualizarPKT";
$route['alertas/bt/PSinStock'] = "centroAlertas/BT/alertasBT/PedidosSinStock";
$route['alertas/bt/PP'] = "centroAlertas/BT/alertasBT/PP";
$route['alertas/bt/RR'] = "centroAlertas/BT/alertasBT/reserva";
$route['alertas/bt/soloreabastecer'] = "centroAlertas/BT/alertasBT/soloReabastecer";
$route['alertas/bt/PSinStockBT'] = "centroAlertas/BT/alertasBT/readPSSBT";
$route['alertas/bt/cargar'] = "centroAlertas/BT/alertasBT/cargarTabla";

//rutas alertas PMM

$route['alertas/pmm/errores/difPMMWMS'] = "centroAlertas/PMM/alertasPMM/diferenciasPMMWMS";
$route['alertas/pmm/errores/cantDifPMMWMS'] = "centroAlertas/PMM/alertasPMM/cantDifPMMWMS";
$route['alertas/pmm/errores/detDifPMMWMS'] = "centroAlertas/PMM/alertasPMM/resErrDocPMM";

$route['alertas/pmm/errores/difCargaPMMWMS'] = "centroAlertas/PMM/alertasPMM/difCargaPMMWMS";
$route['alertas/pmm/errores/cantDifCargaPMMWMS'] = "centroAlertas/PMM/alertasPMM/cantDifCargaPMMWMS";
$route['alertas/pmm/errores/detErrCarga'] = "centroAlertas/PMM/alertasPMM/detalleErrCargaPMM";

$route['alertas/pmm/errores/errLPND'] = "centroAlertas/PMM/alertasPMM/ErrLPNDisposicion";

$route['alertas/pmm/errores/errAlm'] = "centroAlertas/PMM/alertasPMM/ErrAlmacenaje";

$route['alertas/pmm/errores/errCC'] = "centroAlertas/PMM/alertasPMM/ErrConteoCiclico";

$route['alertas/pmm/errores/errLPNM'] = "centroAlertas/PMM/alertasPMM/ErrLPNModificado";


//rutas alertas EIS

$route['alertas/EIS/errores/msgEIS'] = "centroAlertas/EIS/alertasEIS/msgEIS";
$route['alertas/EIS/errores/cantErrEIS'] = "centroAlertas/EIS/alertasEIS/cantErrEIS";
$route['alertas/EIS/errores/resumenEIS'] = "centroAlertas/EIS/alertasEIS/resumenEIS";

//rutas seteo atributos

$route['SeteoAttr/infoSku'] = "seteoAtributos/SeteoAtributos/infoSku";
$route['SeteoAttr/cboCartonType'] = "seteoAtributos/SeteoAtributos/cboCartonType";
$route['SeteoAttr/attrSKU'] = "seteoAtributos/SeteoAtributos/attrSKU";

//rutas LPN Demora

$route['LPNDemora/totales'] = "LPNDemora/LPNDemora/totalDemoraFecha";
$route['LPNDemora/resumen'] = "LPNDemora/LPNDemora/resumenDemoraFecha";

$route['CambioDemoraLPN/read'] = "LPNDemora/CambioDemoraLPN/importarEXCEL";
$route['CambioDemoraLPN/save'] = "LPNDemora/CambioDemoraLPN/save";

$route['CambioDemoraLOCN/read'] = "LPNDemora/CambioDemoraLOCN/importarEXCEL";
$route['CambioDemoraLOCN/save'] = "LPNDemora/CambioDemoraLOCN/save";
$route['CambioDemoraLOCN/detalleLocns'] = "LPNDemora/CambioDemoraLOCN/detalleLocns";

$route['CambioDemoraCarton/read'] = "LPNDemora/CambioDemoraCarton/importarEXCEL";
$route['CambioDemoraCarton/save'] = "LPNDemora/CambioDemoraCarton/save";

//rutas almacenamiento locacion

$route['AlmLonc/info'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/info";
$route['AlmLonc/valid/aisles'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/aisles";
$route['AlmLonc/valid/putwy_types'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/putwy_types";
$route['AlmLonc/valid/locn_class'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/locn_class";
$route['AlmLonc/update'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/update";
$route['AlmLonc/create'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/create";
$route['AlmLonc/delete'] = "Reabastecimiento/AlmacenamientoLocn/Almacenamiento_Locn/delete";

$route['Deptos/select'] = "Reabastecimiento/Departamentos/Departamentos/selectDepto";
$route['Deptos/pasillos'] = "Reabastecimiento/Departamentos/Departamentos/pasillosPutwy";
$route['Deptos/locaciones'] = "Reabastecimiento/Departamentos/Departamentos/availableLocn";
$route['Deptos/configurar'] = "Reabastecimiento/Departamentos/Departamentos/configurar";

// rutas mantenedores

$route['sublineas/read'] = "Reabastecimiento/Mantenedores/Sublineas/obtenerTabla";
$route['sublineas/save'] = "Reabastecimiento/Mantenedores/Sublineas/guardarCambios";

//rutas CartonType

$route['cartontype/data'] = "centrodistribucion/CartonType/dataGrid1";

//rutas Diferencia Inventario

$route['difinvn/read'] = "DiferenciaInventario/DiferenciaInventario/read";
$route['difinvn/readDiffPMM'] = "DiferenciaInventario/DiferenciaInventario/detalleDiffPMM";
$route['difinvn/readDiffWMS'] = "DiferenciaInventario/DiferenciaInventario/detalleDiffWMS";

//rutas calendario PKT

$route['calendarioPKT'] = "centroAlertas/BT/CalendarioPickTicket/calendarioPKT";
$route['calendario/read'] = "centroAlertas/BT/CalendarioPickTicket/calendarioPKT/calendar";
$route['calendario/estados'] = "centroAlertas/BT/CalendarioPickTicket/calendarioPKT/EstadosPKT";
$route['calendario/detalle'] = "centroAlertas/BT/CalendarioPickTicket/calendarioPKT/DetallePKT";

// rutas Diferencia Stock BT-WMS

$route['DiffBT-WMS'] = "centroAlertas/BT/SinStockWMS/DiferenciaStockWMS";
$route['DiffBT-WMS/read'] = "centroAlertas/BT/SinStockWMS/DiferenciaStockWMS/read";
$route['DiffBT-WMS/detalle'] = "centroAlertas/BT/SinStockWMS/DiferenciaStockWMS/detalleBloqueo";

//rutas pkt cancelados

$route['PKTCancelados/read'] = "DiferenciaInventario/PKTCancelados/PKTCancelados/read";
$route['PKTCancelados/detalle'] = "DiferenciaInventario/PKTCancelados/PKTCancelados/detalle";

// rutas aseguramiento calidad

$route['aseguramientoCalidad/recepcion'] = "AseguramientoCalidad/AseguramientoCalidad/recepcion";
$route['aseguramientoCalidad/recepcion/read'] = "AseguramientoCalidad/Recepcion/recepcion/read";
$route['aseguramientoCalidad/recepcion/detalle'] = "AseguramientoCalidad/Recepcion/recepcion/detalle";
$route['aseguramientoCalidad/recepcion/reprocesar'] = "AseguramientoCalidad/Recepcion/recepcion/reprocesar";
$route['aseguramientoCalidad/recepcion/interfaz'] = "AseguramientoCalidad/Recepcion/recepcion/detalleErrInterfaz";

// rutas lector

$route['lector/cargar'] = "LectorCUD/LectorCUD/importarEXCEL";
$route['lector/cargarv2'] = "LectorCUD/LectorCUD/importarEXCELv2";
$route['lector/buscar'] = "LectorCUD/LectorCUD/search";
$route['lector/pick'] = "LectorCUD/LectorCUD/Pick";
$route['lector/testpick'] = "LectorCUD/LectorCUD/TestPick";
$route['lector/pickfaltantes'] = "LectorCUD/LectorCUD/PickFaltantes";
$route['lector/tiendas'] = "LectorCUD/LectorCUD/tiendas";
$route['lector/cierreCarga'] = "LectorCUD/LectorCUD/cerrarCarga";
$route['lector/detcierreCarga'] = "LectorCUD/LectorCUD/detalleCierreCarga";
$route['lector/detfaltantes'] = "LectorCUD/LectorCUD/detalleFaltantes";
$route['lector/detcierreCargav2'] = "LectorCUD/LectorCUD/detalleCierreCarga_V2";
$route['lector/total'] = "LectorCUD/LectorCUD/totalPick";
$route['lector/totalfaltantes'] = "LectorCUD/LectorCUD/totalFaltantes";
$route['lector/resumen/(:any)/(:any)/(:any)'] = "LectorCUD/LectorCUD/resumenDespacho/$1/$2/$3";
$route['lector/resumenV2/(:any)/(:any)/(:any)/(:any)'] = "LectorCUD/LectorCUD/resumenDespacho_V2/$1/$2/$3/$4";
$route['lector/ids'] = "LectorCUD/LectorCUD/getIds";
$route['lector/idsV2'] = "LectorCUD/LectorCUD/getIds_V2";
$route['lector/dashboard/data'] = "LectorCUD/LectorCUD/dataDashboard";
$route['lector/devolver'] = "LectorCUD/LectorCUD/devolver";
$route['lector/datacud'] = "LectorCUD/LectorCUD/buscarCud";
$route['lector/guardarinfodesp'] = "LectorCUD/LectorCUD/guardarInfoDespacho";
$route['lector/detdevueltos'] = "LectorCUD/LectorCUD/detalleDevueltos";
$route['lector/cantdevueltos'] = "LectorCUD/LectorCUD/contarDevueltos";
$route['lector/datosTransporte'] = "LectorCUD/LectorCUD/datosTransporte";
$route['lector/getopl'] = "LectorCUD/LectorCUD/getOPL";

// RUTAS SETEO WORK AREA

$route['workarea/data'] = "centrodistribucion/WorkArea/gridWorkArea";
$route['listar/wa'] = "centrodistribucion/WorkArea/listWorkArea";
$route['listar/wg'] = "centrodistribucion/WorkArea/listWorkGroup";
$route['workarea/actualizar'] = "centrodistribucion/WorkArea/actualizarWorkArea";

//RUTAS DESCUADRATURA INVENTARIO

$route['descuadraturainv/data'] = "DescuadraturaInv/DescuadraturaInv/mainGrid";
$route['descuadraturainv/actualizar'] = "DescuadraturaInv/DescuadraturaInv/update";
$route['descuadraturainv/grafico'] = "DescuadraturaInv/DescuadraturaInv/dataGrafico";
