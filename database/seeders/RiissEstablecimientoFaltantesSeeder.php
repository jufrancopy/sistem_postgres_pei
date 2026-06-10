<?php
namespace Database\Seeders;
use App\Models\Riiss\Establecimiento;
use Illuminate\Database\Seeder;

class RiissEstablecimientoFaltantesSeeder extends Seeder
{
    private array $datos = [
        // [id, nombre, codigo, tipo, complejidad, departamento, microred, prestador, nro_depto, tipologia, lat, lng, nm_empresa, access_nm, area_gestion, situacion, observacion, sistema]
        ['07-PS-24', 'BELLA VISTA SUR PS CONVENIO', 211, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.04224, -55.57899, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['06-PS-19', 'BUENA VISTA  PS CONVENIO', 253, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAZAPA', 'Red Guaira Caazapá', 'CONVENIO', 6, 'PUESTO SANITARIO', -26.18486, -56.08283, 'BUENA VISTA P.S.', 'BUENA VISTA P.S.', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['09-PS-49', 'CAAPUCU PS CONVENIO', 14, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Misiones', 'CONVENIO', 9, 'PUESTO SANITARIO', -26.22946, -57.18393, 'CAAPUCU PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['02-PS-01', 'CAPIIBARY PS CONVENIO', 249, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'CONVENIO', 2, 'PUESTO SANITARIO', -24.72903, -56.01698, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['07-PS-25', 'CAPITAN MEZA PS CONVENIO', 232, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.82969, -55.3412, 'CAPITÀN MEZA PS', 'CAPITÀN MEZA PS', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['07-PS-26', 'CAPITAN MIRANDA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.2172, -55.80003, 'P.S. CAPITAN MIRANDA', 'P.S. CAPITAN MIRANDA', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-PS-07', 'CARAGUATAY PS', 120, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'IPS', 3, 'PUESTO SANITARIO', -25.2379, -56.82782, 'CARAGUATAY PS', 'CHORE PS', 'AREA INTERIOR', 'IPS', null, 'SIN SISTEMA'],
        ['09-PS-50', 'CARAPEGUA PS', 94, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Cordillera Paraguari', 'IPS', 9, 'PUESTO SANITARIO', -25.76677, -57.24775, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['07-PS-27', 'CARLOS ANTONIO LOPEZ PS CONVENIO', 177, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Alto Paraná', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.4013, -54.75497, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['17-PS-71', 'CARMELO PERALTA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ALTO PARAGUAY', 'Red Concepción', 'CONVENIO', 17, 'PUESTO SANITARIO', -21.68585, -57.90271, 'PTO. CASADO US', null, 'AREA INTERIOR', 'IPS', 'MSPYBS', 'SIN SISTEMA'],
        ['07-PS-28', 'CARMEN DEL PARANA PS CONVENIO', 96, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.2238, -56.15683, 'CARMEN DEL PARANA PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['02-PS-02', 'CHORE CONVENIO PS', 162, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'CONVENIO', 2, 'PUESTO SANITARIO', -24.18952, -56.57867, 'CHORE PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['11-CE-01', 'MARISCAL LÓPEZ C.P.', null, 'CE', 'No Hospitalario de Mediana Complejidad', 'CENTRAL', 'Red HC', 'IPS', 11, 'CENTROS', -25.3168, -57.52607, null, null, 'AREA CENTRAL', 'IPS', null, 'SIN SISTEMA'],
        ['16-TE-07', 'COLONIA FERNHEIM(FILADELFIA)', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'BOQUERON', 'Red Chaco Central', 'TERCERIZADO', 16, 'HOSPITAL', -22.359, -60.03823, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['02-CO-01', 'COLONIA FRIESLAND CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'CONVENIO', 2, 'HOSPITAL', -24.61148, -56.79312, 'HOSPITAL COLONIA FRIESLAND (SAN PEDRO)', 'HOSPITAL COLONIA FRIESLAND (SAN PEDRO)', 'AREA INTERIOR', 'HOSPITAL TABEA', null, 'SIN SISTEMA'],
        ['16-TE-08', 'COLONIA NEULAND (HOSPITAL CONCORDIA)', null, 'HO', 'Hospitalario 1 Baja Complejidad', 'BOQUERON', 'Red Chaco Central', 'TERCERIZADO', 16, 'HOSPITAL', -22.65758, -60.13236, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['07-PS-29', 'CORONEL BOGADO PS CONVENIO', 125, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.16998, -56.23269, 'CNEL. BOGADO PS', 'CNEL. BOGADO PS', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-OT-02', 'CREAM', null, 'OT', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'IPS', 3, 'OTROS', -25.32495, -57.28605, null, null, 'MEDICINA PREVENTIVA', 'IPS', null, 'SIN SISTEMA'],
        ['18-TE-10', 'CRISTIAN', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'ASUNCIÓN', 'Red HC', 'TERCERIZADO', 18, 'HOSPITAL', -25.34126, -57.50664, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['18-TE-11', 'DOCTO S.R.L.', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'ASUNCIÓN', 'Red HC', 'TERCERIZADO', 18, 'HOSPITAL', -25.28602, -57.61522, 'SANATORIO DOCTO SRL', 'SANATORIO DOCTO SRL', 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['07-PS-30', 'EDELIRA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.77028, -55.28198, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-PS-08', 'EUSEBIO AYALA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'CONVENIO', 3, 'PUESTO SANITARIO', -25.38172, -56.95883, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['07-US-32', 'FRAM US', null, 'PS', 'Hospitalario 1 Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.09669, -56.01279, 'FRAM U.S', 'FRAM U.S', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['17-PS-69', 'FUERTE OLIMPO PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ALTO PARAGUAY', 'Red Chaco Central', 'CONVENIO', 17, 'PUESTO SANITARIO', -21.03815, -57.87026, 'FUERTE OLIMPO PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['06-PS-20', 'FULGENCIO YEGROS PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAZAPA', 'Red Guaira Caazapá', 'IPS', 6, 'PUESTO SANITARIO', -26.45402, -56.40286, 'FULGENCIO YEGROS PS', 'FULGENCIO YEGROS PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['07-PS-32', 'GENERAL ARTIGAS PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.93264, -56.2142, 'GRAL.ARTIGAS PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['11-PS-58', 'GUARAMBARE PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CENTRAL', 'Red 12 de Junio', 'IPS', 11, 'PUESTO SANITARIO', -25.4907, -57.45723, 'GUARAMBARE PS', 'GUARAMBARE PS', 'AREA CENTRAL', 'IPS', null, 'SIN SISTEMA'],
        ['11-TE-03', 'HOSPITAL DEL CORAZON', null, 'HO', 'Hospitalario 1 Baja Complejidad', 'CENTRAL', 'Red HC', 'TERCERIZADO', 11, 'HOSPITAL', -25.355, -57.63094, 'HOSPITAL  DEL CORAZÒN', 'HOSPITAL  DEL CORAZÒN', 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['18-HO-03', 'HOSPITAL MILITAR', null, 'HO', 'Hospitalario 1 Baja Complejidad', 'CAPITAL', 'Red 12 de Junio', 'TERCERIZADO', 18, 'HOSPITAL', -25.27951, -57.64573, 'HOSPITAL MILITAR', null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['15-PS-65', 'IRALA FERNANDEZ PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PRESIDENTE HAYES', 'Red Chaco Central', 'CONVENIO', 15, 'PUESTO SANITARIO', -22.85611, -59.48045, 'TTE.IRALA FERNÀNDEZ PS', 'TTE.IRALA FERNÀNDEZ PS', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-PS-09', 'ISLA PUCU PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'CONVENIO', 3, 'PUESTO SANITARIO', -25.31238, -56.90228, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['02-PS-03', 'ITACURUBI DEL ROSARIO PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'IPS', 2, 'PUESTO SANITARIO', -24.53444, -56.82223, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['10-PS-56', 'ITAKYRY PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ALTO PARANA', 'Red Alto Paraná', 'IPS', 10, 'PUESTO SANITARIO', -24.98671, -55.14702, 'ITAKYRY US', 'ITAKYRY US', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['07-PS-33', 'ITAPUA POTY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.63209, -55.51942, null, 'DPTO DE MEDICINA FISICA Y REHABILITACION', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['11-PS-59', 'ITAUGUA PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CENTRAL', 'Red Ingavi', 'IPS', 11, 'PUESTO SANITARIO', -25.39356, -57.35282, 'ITAUGUA PS', 'ITAUGUA PS', 'AREA CENTRAL', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['04-PS-13', 'JOSE FASSARDI PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'GUAIRA', 'Red Guaira Caazapá', 'CONVENIO', 4, 'PUESTO SANITARIO', -25.98171, -56.12188, 'JOSE FASSARDI PS', 'JOSE FASSARDI PS', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['05-PS-16', 'JUAN MANUEL FRUTOS PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAGUAZU', 'Red Caaguazu', 'IPS', 5, 'PUESTO SANITARIO', -25.38361, -55.83273, 'JUAN MANUEL FRUTOS PS', 'JUAN MANUEL FRUTOS PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['07-PS-34', 'KRESS BURGO (FRUTIKA) PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'IPS', 7, 'PUESTO SANITARIO', -26.3209, -55.03199, 'KRESSBURGO P.S.', 'KRESSBURGO P.S.', 'AREA INTERIOR', 'FABRICA KRESBURGO', null, 'SIN SISTEMA'],
        ['09-PS-51', 'LA COLMENA PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Guaira Caazapá', 'IPS', 9, 'PUESTO SANITARIO', -25.69245, -56.8608, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['17-US-26', 'LA VICTORIA US', null, 'US', 'No Hospitalario de Mediana Complejidad', 'ALTO PARAGUAY', 'Red Concepción', 'IPS', 17, 'UNIDAD SANITARIA', -22.28264, -57.93537, 'PTO. CASADO US', null, 'AREA INTERIOR', 'IPS', 'MSPYBS EN NUESTRO LOCAL', 'SIN SISTEMA'],
        ['16-TE-09', 'LOMA PLATA HOSPITAL', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'BOQUERON', 'Red Chaco Central', 'TERCERIZADO', 16, 'HOSPITAL', -22.37874, -59.83153, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['07-PS-35', 'MARIA AUXILIADORA PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'IPS', 7, 'PUESTO SANITARIO', -26.53941, -55.26009, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['04-PS-14', 'MAURICIO J. TROCHE PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'GUAIRA', 'Red Guaira Caazapá', 'IPS', 4, 'PUESTO SANITARIO', -25.6279, -56.2777, 'MAURICIO JOSE TROCHE PS', 'MAURICIO JOSE TROCHE PS', 'AREA INTERIOR', 'ALQUILADO PETROPAR', null, 'SIN SISTEMA'],
        ['07-PS-36', 'MAYOR OTAÑO PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Alto Paraná', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.35038, -54.72499, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['09-PS-52', 'MBUYAPEY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Guaira Caazapá', 'CONVENIO', 9, 'PUESTO SANITARIO', -26.21845, -56.75497, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['18-OT-10', 'MEDICASA', null, 'OT', 'No Hospitalario de Baja Complejidad', 'ASUNCIÓN', 'Red HC', 'IPS', 18, 'OTROS', -25.27374, -57.58084, null, null, 'AREA CENTRAL', 'IPS', null, 'SIN SISTEMA'],
        ['10-PS-57', 'MINGA GUAZU PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ALTO PARANA', 'Red Alto Paraná', 'IPS', 10, 'PUESTO SANITARIO', -25.48454, -54.76285, 'MINGA GUAZU PS', 'MINGA GUAZU PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['07-PS-12', 'NATALIO PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'UNIDAD SANITARIA', -26.75978, -55.13887, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['04-PS-15', 'PASO YOBAI PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'GUAIRA', 'Red Caaguazu', 'IPS', 4, 'PUESTO SANITARIO', -25.71751, -56.00245, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['11-PS-60', 'PIQUETE CUE PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CENTRAL', 'Red Alto Central', 'IPS', 11, 'PUESTO SANITARIO', -25.10238, -57.48948, 'PIQUETE CUE PS', 'PIQUETE CUE PS', 'AREA CENTRAL', 'IPS', null, 'SIN SISTEMA'],
        ['07-PS-37', 'PIRAPO PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.86604, -55.55759, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-PS-10', 'PIRIBEBUY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'CONVENIO', 3, 'PUESTO SANITARIO', -25.46822, -57.03722, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['02-PS-04', 'PUERTO ANTEQUERA PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'IPS', 2, 'PUESTO SANITARIO', -24.0837, -57.2055, 'P.S.  PTO. ANTEQUERA', 'P.S.  PTO. ANTEQUERA', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['15-PS-66', 'PUERTO PINASCO PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PRESIDENTE HAYES', 'Red Concepción', 'IPS', 15, 'PUESTO SANITARIO', -22.64782, -57.84156, null, null, 'AREA INTERIOR', 'IPS', null, 'SIN SISTEMA'],
        ['14-PS-73', 'PUESTO SANITARIA  LA PALOMA', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CANINDEYU', 'Red Alto Paraná', 'CONVENIO', 14, 'PUESTO SANITARIO', -24.13113, -54.6215, null, null, null, null, null, null],
        ['09-PS-53', 'QUIINDY PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Cordillera Paraguari', 'IPS', 9, 'PUESTO SANITARIO', -25.97443, -57.2334, 'QUIINDY PS', 'QUIINDY PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['09-PS-54', 'QUYQUYHO PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Guaira Caazapá', 'CONVENIO', 9, 'PUESTO SANITARIO', -26.22751, -56.99014, 'QUYQUYO PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['14-PS-64', 'SALTOS DEL GUAIRA PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CANINDEYU', 'Red Canindeyu', 'IPS', 14, 'PUESTO SANITARIO', -24.07191, -54.3107, 'SALTOS DEL GUAIRA PS', null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['03-PS-11', 'SAN BERNARDINO PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'IPS', 3, 'PUESTO SANITARIO', -25.30594, -57.30165, 'SAN BERNARDINO PS', null, 'AREA INTERIOR', 'IPS', null, 'SIN SISTEMA'],
        ['07-PS-38', 'SAN COSME Y SAN DAMIAN PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -27.31366, -56.3292, 'SAN COSME Y DAMIAN PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['05-PS-17', 'SAN JOSE DE LOS ARROYOS PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAGUAZU', 'Red Caaguazu', 'IPS', 5, 'PUESTO SANITARIO', -25.53443, -56.73176, 'SAN JOSE DE LOS ARROYOS PS', 'SAN JOSE DE LOS ARROYOS PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['06-PS-21', 'SAN JUAN NEPOMUCENO PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAZAPA', 'Red Guaira Caazapá', 'IPS', 6, 'PUESTO SANITARIO', -26.11088, -55.93566, 'SAN JUAN NEPOMUCENO PS', 'SAN JUAN NEPOMUCENO PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['08-PS-42', 'SAN MIGUEL PS CONVENIO', null, 'PS', 'No Hospitalario de Mediana Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -26.5328, -57.03873, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['18-PS-70', 'San Miguel-Ande', null, 'PS', 'No Hospitalario de Mediana Complejidad', 'ASUNCIÓN', 'Red 12 de Junio', 'IPS', 18, 'PUESTO SANITARIO', -25.28855, -57.613, null, null, 'AREA CENTRAL', 'IPS', null, 'SIN SISTEMA'],
        ['07-PS-39', 'SAN PEDRO DEL PARANA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.83575, -56.20359, 'SAN PEDRO DEL PARANA PS', null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['11-TE-04', 'SAN SEBASTIAN', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CENTRAL', 'Red HC', 'TERCERIZADO', 11, 'HOSPITAL', -25.30408, -57.54026, 'SANATORIO SAN SEBASTIAN', 'SANATORIO SAN SEBASTIAN', 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['18-TE-12', 'SANAT. SAN LUCAS', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'ASUNCIÓN', 'Red HC', 'TERCERIZADO', 18, 'HOSPITAL', -25.30805, -57.59921, 'SANATORIO SAN LUCAS', 'SANATORIO SAN LUCAS', 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['18-TE-13', 'SANATORIO ADVENTISTA', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CAPITAL', 'Red 12 de Junio', 'TERCERIZADO', 18, 'HOSPITAL', null, null, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['18-TE-15', 'SANATORIO BRITANICO', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CAPITAL', 'Red 12 de Junio', 'TERCERIZADO', 18, 'HOSPITAL', -25.29474, -57.63301, 'SANATORIO BRITANICO', null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['11-TE-05', 'SANATORIO INTERNACIONAL', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CENTRAL', 'Red Alto Central', 'TERCERIZADO', 11, 'HOSPITAL', -25.26894, -57.49219, 'SANATORIO INTERNACIONAL', 'SANATORIO INTERNACIONAL', 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['05-TE-02', 'SANATORIO LUZ Y VIDA TERCERIZADO', null, 'OT', 'No Hospitalario de Mediana Complejidad', 'CAAGUAZU', 'Red Caaguazu', 'TERCERIZADO', 5, 'HOSPITAL', -25.38124, -55.70479, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['05-TE-14', 'SANATORIO SAN CARLOS', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CAAGUAZU', 'Red Caaguazu', 'TERCERIZADO', 5, 'HOSPITAL', null, null, null, null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['11-TE-06', 'SANATORIO SANTA BARBARA', null, 'OT', 'Hospitalario 1 Baja Complejidad', 'CENTRAL', 'Red HC', 'TERCERIZADO', 11, 'HOSPITAL', -25.25851, -57.58114, 'SANATORIO SANTA BARBARA', null, 'GESTION MÉDICA', 'PRIVADO', null, 'SIN SISTEMA'],
        ['08-PS-43', 'SANTA MARIA DE FE CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -26.78426, -56.94379, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['10-US-18', 'SANTA RITA US', null, 'US', 'No Hospitalario de Baja Complejidad', 'ALTO PARANA', 'Red Alto Paraná', 'IPS', 10, 'UNIDAD SANITARIA', -25.7991, -55.08606, 'P.S. SANTA RITA', 'P.S. SANTA RITA', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['02-PS-05', 'SANTA ROSA DEL AGUARAY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'SAN PEDRO DEL YCUAMANDIYU', 'Red San Pedro', 'IPS', 2, 'PUESTO SANITARIO', -23.82705, -56.51946, 'SANTA  ROSA  DEL AGUARAY PS', 'SANTA  ROSA  DEL AGUARAY PS', 'AREA INTERIOR', 'EN LITIGIO', null, 'SIN SISTEMA'],
        ['08-PS-44', 'SANTA ROSA PS CONVENIO', null, 'PS', 'No Hospitalario de Mediana Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -26.8894, -56.84639, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['08-PS-45', 'SANTIAGO PS CONV.', null, 'PS', 'No Hospitalario de Baja Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -27.13957, -56.76138, 'SANTIAGO MISIONES PS', 'SANTIAGO MISIONES PS', 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['03-PS-12', 'TOBATI PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CORDILLERA', 'Red Cordillera Paraguari', 'IPS', 3, 'PUESTO SANITARIO', -25.25956, -57.07946, null, null, 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['11-PS-61', 'UBAS ITA', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CENTRAL', 'Red Ingavi', 'IPS', 11, 'PUESTO SANITARIO', -25.50937, -57.35873, 'UBAS  ITÀ', 'UBAS  ITÀ', 'AREA CENTRAL', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['16-PS-68', 'VILLA CHOFERES PS CONVENIO', null, 'PS', 'Hospitalario 1 Baja Complejidad', 'BOQUERON', 'Red Chaco Central', 'CONVENIO', 16, 'PUESTO SANITARIO', -22.47045, -60.05528, 'VILLA CHOFERES PS', 'VILLA CHOFERES PS', 'AREA INTERIOR', 'GOBERNACION', null, 'SIN SISTEMA'],
        ['08-PS-46', 'VILLA FLORIDA PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -26.40786, -57.12454, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['15-PS-67', 'VILLA HAYES PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PRESIDENTE HAYES', 'Red Alto Central', 'IPS', 15, 'PUESTO SANITARIO', -25.0958, -57.5217, 'VILLA HAYES PS', 'VILLA HAYES PS', 'AREA INTERIOR', 'ALQUILADO', 'MSPYBS CONVENIO INTERNADO', 'SIN SISTEMA'],
        ['08-PS-47', 'YABEBYRY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'MISIONES', 'Red Misiones', 'CONVENIO', 8, 'PUESTO SANITARIO', -27.38071, -57.16606, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['07-PS-40', 'YATYTAY PS CONVENIO', null, 'PS', 'No Hospitalario de Baja Complejidad', 'ITAPUA', 'Red Itapua', 'CONVENIO', 7, 'PUESTO SANITARIO', -26.67448, -55.08645, null, null, 'AREA INTERIOR', 'MSPYBS', null, 'SIN SISTEMA'],
        ['09-PS-55', 'YBYCUI PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'PARAGUARI', 'Red Cordillera Paraguari', 'IPS', 9, 'PUESTO SANITARIO', -26.01904, -57.026, null, null, 'AREA INTERIOR', 'IPS', null, 'SIN SISTEMA'],
        ['05-PS-18', 'YHU PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAGUAZU', 'Red Caaguazu', 'IPS', 5, 'PUESTO SANITARIO', -25.05985, -55.92534, 'YHU PS', 'YHU PS', 'AREA INTERIOR', 'IPS', null, 'SIN SISTEMA'],
        ['06-PS-22', 'YUTY PS', null, 'PS', 'No Hospitalario de Baja Complejidad', 'CAAZAPA', 'Red Guaira Caazapá', 'IPS', 6, 'PUESTO SANITARIO', -26.61335, -56.24556, 'YUTY PS', 'YUTY PS', 'AREA INTERIOR', 'ALQUILADO', null, 'SIN SISTEMA'],
        ['01-MP-01', 'PARQUE SALUD', null, 'OT', 'No Hospitalario de Baja Complejidad', 'ASUNCIÓN', 'Red HC', 'IPS', 18, 'OTROS', null, null, null, null, 'MEDICINA PREVENTIVA', 'IPS', null, 'SIN SISTEMA'],
    ];

    public function run(): void
    {
        $insertados = 0;
        $omitidos = 0;
        foreach ($this->datos as $row) {
            $existe = Establecimiento::where('id_establecimiento', $row[0])->exists();
            if ($existe) { $omitidos++; continue; }
            Establecimiento::create([
                'id_establecimiento'      => $row[0],
                'nombre_oficial'          => $row[1],
                'codigo'                  => $row[2],
                'tipo_est'                => $row[3],
                'complejidad'             => $row[4],
                'departamento'            => trim($row[5] ?? ''),
                'microred'                => $row[6],
                'prestador'               => $row[7],
                'nro_departamento'        => (int)($row[8] ?? 0),
                'tipologia_clasificacion' => $row[9],
                'latitude'                => $row[10],
                'longitude'               => $row[11],
                'nm_empresa_costos'       => $row[12],
                'access_nm_empresa'       => $row[13],
                'area_gestion'            => $row[14],
                'situacion_inmueble'      => $row[15],
                'observacion'             => $row[16],
                'sistema_hospitalario'    => $row[17],
                'activo'                  => true,
            ]);
            $insertados++;
        }
        // Recalcular campos derivados solo en los nuevos
        Establecimiento::whereNull('nivel_atencion')->chunk(50, function($items) {
            foreach ($items as $est) { $est->recalcularCamposDerivados(); }
        });
        $this->command->info("Insertados: $insertados | Ya existian: $omitidos");
    }
}
