<?php

namespace Database\Seeders;

use App\Models\Riiss\Establecimiento;
use Illuminate\Database\Seeder;

class RiissEstablecimientoSeeder extends Seeder
{
    /**
     * Datos extraídos del archivo DIM_ESTABLECIMIENTO_CON_ID.xlsx
     * Formato: [id, nombre, codigo, tipo, complejidad, departamento, microred,
     *           prestador, nro_depto, tipologia, lat, lng, nm_empresa, access_nm,
     *           area_gestion, situacion_inmueble, observacion, sistema_hospitalario]
     */
    private array $datos = [
        ['09-PS-48','ACAHAY PS CONVENIO',186,'PS','No Hospitalario de Baja Complejidad','PARAGUARI','Red Cordillera Paraguari','CONVENIO',9,'PUESTO SANITARIO',-25.91341,-57.11226,null,'Nm Empresa','AREA INTERIOR','MSPYBS',null,'SIN SISTEMA'],
        ['12-PS-62','ALBERDI PS',132,'PS','No Hospitalario de Baja Complejidad','NEEMBUCU','Red Ñeembucu','CONVENIO',12,'PUESTO SANITARIO',-26.18521,-58.14434,'CONVENIO ALBERDI PS','CONVENIO ALBERDI PS','AREA INTERIOR','MSPYBS',null,'SIN SISTEMA'],
        ['07-PS-23','ALTO VERA PS CONVENIO',null,'PS','No Hospitalario de Baja Complejidad','ITAPUA','Red Itapua','CONVENIO',7,'PUESTO SANITARIO',-26.75053,-55.76873,null,null,'AREA INTERIOR','MSPYBS',null,'SIN SISTEMA'],
        ['18-PS-72','ANDE BOGGIANI',null,'PS','No Hospitalario de Baja Complejidad','CAPITAL','Red Ingavi','CONVENIO',18,'PUESTO SANITARIO',-25.31308,-57.5662,'ANDE BOGGIANI PS CONVENIO',null,null,null,null,'SIN SISTEMA'],
        ['03-PS-06','ARROYOS Y ESTEROS PS',93,'PS','No Hospitalario de Baja Complejidad','CORDILLERA','Red Cordillera Paraguari','CONVENIO',3,'PUESTO SANITARIO',-25.05249,-57.09603,null,null,'AREA INTERIOR','MSPYBS',null,'SIN SISTEMA'],
        ['08-HR-07','AYOLAS HR',95,'HR','Hospitalario 1 Baja Complejidad','MISIONES','Red Misiones','IPS',8,'HOSPITAL REGIONAL',-27.38748,-56.81882,'AYOLAS H.R','AYOLAS H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['17-US-25','BAHIA NEGRA US',58,'US','No Hospitalario de Baja Complejidad','ALTO PARAGUAY','Red Chaco Central','IPS',17,'UNIDAD SANITARIA',-20.23215,-58.16779,'BAHIA NEGRA US',null,'AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['13-PS-63','BELLA VISTA NORTE PS',145,'PS','No Hospitalario de Baja Complejidad','AMAMBAY','Red Amambay','IPS',13,'PUESTO SANITARIO',-22.12204,-56.51607,null,null,'AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['15-HR-11','BENJAMIN ACEVAL HR',9,'HR','Hospitalario 1 Baja Complejidad','PRESIDENTE HAYES','Red Alto Central','IPS',15,'HOSPITAL REGIONAL',-24.99337,-57.55616,'BENJAMIN ACEVAL H.R','BENJAMIN ACEVAL H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['03-US-05','CAACUPE US',70,'US','No Hospitalario de Mediana Complejidad','CORDILLERA','Red Cordillera Paraguari','IPS',3,'UNIDAD SANITARIA',-25.38339,-57.13686,'CAACUPE U.S','CAACUPE U.S','AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['05-US-09','CAAGUAZU US',38,'US','Hospitalario 1 Baja Complejidad','CAAGUAZU','Red Caaguazu','IPS',5,'UNIDAD SANITARIA',-25.46542,-56.00989,'CAAGUAZU U.S','CAAGUAZU U.S','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['06-US-10','CAAZAPA US',47,'US','Hospitalario 1 Baja Complejidad','CAAZAPA','Red Guaira Caazapá','IPS',6,'UNIDAD SANITARIA',-26.19644,-56.36908,'CAAZAPA U.S','CAAZAPA U.S','AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['13-US-22','CAPITAN BADO US',8,'US','Hospitalario 1 Baja Complejidad','AMAMBAY','Red Amambay','IPS',13,'UNIDAD SANITARIA',-23.27134,-55.54552,'CAPITAN BADO U.S','CAPITAN BADO U.S','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['18-CE-02','CENTRO DE MEDICINA FISICA Y REHABILITACION',240,'CE','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red HC','IPS',18,'CENTROS',-25.2682,-57.58295,'DPTO DE MEDICINA FISICA Y REHABILITACION',null,'AREA CENTRAL','IPS',null,'SIN SISTEMA'],
        ['18-CE-03','CENTRO DE PSIQUIATRIA Y PSICOTERAPIA',241,'CE','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red HC','IPS',18,'CENTROS',-25.2682,-57.58295,'DPTO PSIQUITRIA Y PSICOLOGIA','DPTO PSIQUITRIA Y PSICOLOGIA','AREA CENTRAL','IPS',null,'SIN SISTEMA'],
        ['18-CE-04','CENTRO ODONTOLOGICO',26,'CE','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red 12 de Junio','IPS',18,'CENTROS',-25.29178,-57.62896,'CENTRO ODONTOLOGICO','CENTRO ODONTOLOGICO','AREA CENTRAL','IPS',null,'SIH'],
        ['10-HR-08','CIUDAD DEL ESTE HR',73,'HR','Hospitalario 2 Mediana Complejidad','ALTO PARANA','Red Alto Paraná','IPS',10,'HOSPITAL REGIONAL',-25.512,-54.62405,'CIUDAD DEL ESTE HR','CIUDAD DEL ESTE HR','AREA INTERIOR','IPS',null,'SIH'],
        ['18-CP-03','BOQUERON C.P.',5,'CP','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red 12 de Junio','IPS',18,'CLINICA PERIFERICA',-25.29501,-57.6248,'CLINICA PERIFERICA BOQUERON','CLINICA PERIFERICA BOQUERON','AREA CENTRAL','IPS',null,'SIH'],
        ['18-CP-04','NANAWA C.P.',24,'CP','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red HC','IPS',18,'CLINICA PERIFERICA',-25.25844,-57.58538,'CLINICA NANAWA','CLINICA NANAWA','AREA CENTRAL','IPS',null,'SIH'],
        ['11-CP-01','CAMPO VIA C.P.',60,'CP','No Hospitalario de Mediana Complejidad','CENTRAL','Red Ingavi','IPS',11,'CLINICA PERIFERICA',-25.37399,-57.40907,'CLINICA PERIFERICA CAMPO VIA CAPIATA','CLINICA PERIFERICA CAMPO VIA CAPIATA','AREA CENTRAL','IPS',null,'SIH'],
        ['11-CP-02','YRENDAGUE C.P.',237,'CP','No Hospitalario de Mediana Complejidad','CENTRAL','Red Alto Central','IPS',11,'CLINICA PERIFERICA',-25.20077,-57.52867,'CLINICA PERIFERICA YRENDAGUE','CLINICA PERIFERICA YRENDAGUE','AREA CENTRAL','IPS',null,'SIH'],
        ['05-HR-05','CNEL. OVIEDO HR',28,'HR','Hospitalario 2 Mediana Complejidad','CAAGUAZU','Red Caaguazu','IPS',5,'HOSPITAL REGIONAL',-25.4665,-56.44445,'CNEL. OVIEDO H.R','CNEL. OVIEDO H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['01-HR-01','CONCEPCIÓN HR',null,'HR','Hospitalario 2 Mediana Complejidad','CONCEPCION','Red Concepción','IPS',1,'HOSPITAL REGIONAL',-23.40883,-57.44414,'CONCEPCION HR','CONCEPCION HR','AREA INTERIOR','IPS',null,'SIH'],
        ['18-HE-03','DR.GERARDO BUONGERMINI HOSPITAL',null,'HO','Hospitalario 2 Mediana Complejidad','ASUNCIÓN','Red HC','IPS',18,'HOSPITAL',-25.2579,-57.58459,'HOSPITAL BUONGERMINI - GERIATRICO','HOSPITAL BUONGERMINI - GERIATRICO','AREA CENTRAL','IPS',null,'SIH'],
        ['07-HR-06','ENCARNACION HR',null,'HR','Hospitalario 2 Mediana Complejidad','ITAPUA','Red Itapua','IPS',7,'HOSPITAL REGIONAL',-27.32377,-55.86,'ENCARNACION H.R','ENCARNACION H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['10-US-16','HERNANDARIAS US',null,'US','Hospitalario 1 Baja Complejidad','ALTO PARANA','Red Alto Paraná','IPS',10,'UNIDAD SANITARIA',-25.40828,-54.63754,'HERNANDARIAS U.S','HERNANDARIAS U.S','AREA INTERIOR','ALQUILADO',null,'SIH'],
        ['07-US-12','HOHENAU US',null,'US','Hospitalario 1 Baja Complejidad','ITAPUA','Red Itapua','IPS',7,'UNIDAD SANITARIA',-27.0826,-55.64786,'HOHENAU U.S','HOHENAU U.S','AREA INTERIOR','IPS',null,'SIH'],
        ['01-US-01','HORQUETA US',null,'US','Hospitalario 1 Baja Complejidad','CONCEPCION','Red Concepción','IPS',1,'UNIDAD SANITARIA',-23.34628,-57.06076,'HORQUETA US','HORQUETA US','AREA INTERIOR','IPS',null,'SIH'],
        ['18-HE-01','HOSPITAL CENTRAL',null,'HC','Hospitalario 3 Alta Complejidad','ASUNCIÓN','Red HC','IPS',18,'HOSPITAL ESPECIALIZADO',-25.27215,-57.58118,'HOSPITAL CENTRAL','HOSPITAL CENTRAL','HOSPITAL CENTRAL','IPS',null,'SIH'],
        ['11-HO-01','LUQUE HOSPITAL',null,'HO','Hospitalario 1 Baja Complejidad','CENTRAL','Red Alto Central','IPS',11,'HOSPITAL',-25.28154,-57.48442,'HOSPITAL DE LUQUE','HOSPITAL DE LUQUE','AREA CENTRAL','IPS',null,'SIH'],
        ['11-HE-02','INGAVI HOSPITAL',null,'HO','Hospitalario 3 Alta Complejidad','CENTRAL','Red Ingavi','IPS',11,'HOSPITAL ESPECIALIZADO',-25.33288,-57.53308,'HOSPITAL INGAVI','HOSPITAL INGAVI','HOSPITALES DE ESPECIALIDADES QUIRURJICAS','IPS',null,'SIH'],
        ['04-US-06','INDEPENDENCIA US',null,'US','Hospitalario 1 Baja Complejidad','GUAIRA','Red Guaira Caazapá','IPS',4,'UNIDAD SANITARIA',-25.6808,-56.29622,'COLONIA INDEPENDENCIA US','COLONIA INDEPENDENCIA US','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['18-CP-05','ISLA PO\'I PS',null,'CP','No Hospitalario de Mediana Complejidad','ASUNCIÓN','Red Alto Central','IPS',18,'CLINICA PERIFERICA',-25.23095,-57.56468,'CLINICA PERIFERICA ISLA POI','CLINICA PERIFERICA ISLA POI','AREA CENTRAL','IPS',null,'SIH'],
        ['04-US-07','ITURBE US',null,'US','Hospitalario 1 Baja Complejidad','GUAIRA','Red Guaira Caazapá','IPS',4,'UNIDAD SANITARIA',-26.05676,-56.48942,'ITURBE US','ITURBE US','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['09-US-15','PARAGUARI US',null,'US','No Hospitalario de Mediana Complejidad','PARAGUARI','Red Cordillera Paraguari','IPS',9,'UNIDAD SANITARIA',-25.62551,-57.14947,'PARAGUARI US','PARAGUARI US','AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['13-HR-10','PEDRO JUAN CABALLERO HR',null,'HR','Hospitalario 1 Baja Complejidad','AMAMBAY','Red Amambay','IPS',13,'HOSPITAL REGIONAL',-22.56061,-55.7185,'PEDRO JUAN CABALLERO HR','PEDRO JUAN CABALLERO HR','AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['12-HR-09','PILAR HR',null,'HR','Hospitalario 1 Baja Complejidad','NEEMBUCU','Red Ñeembucu','IPS',12,'HOSPITAL REGIONAL',-26.85907,-58.30444,'PILAR H.R','PILAR H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['10-US-17','PTO. PTE. FRANCO US',null,'US','Hospitalario 1 Baja Complejidad','ALTO PARANA','Red Alto Paraná','IPS',10,'UNIDAD SANITARIA',-25.55785,-54.60167,'PRESIDENTE FRANCO US','PRESIDENTE FRANCO US','AREA INTERIOR','IPS',null,'SIH'],
        ['14-US-23','PUENTE KYHA US',null,'US','No Hospitalario de Mediana Complejidad','CANINDEYU','Red Canindeyu','IPS',14,'UNIDAD SANITARIA',-24.15876,-54.67034,'PUENTE KYJHA US',null,'AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['02-US-03','PUERTO ROSARIO US',null,'US','No Hospitalario de Mediana Complejidad','SAN PEDRO DEL YCUAMANDIYU','Red San Pedro','IPS',2,'UNIDAD SANITARIA',-24.44013,-57.1442,'PTO. ROSARIO U.S',null,'AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['11-US-19','SAN ANTONIO US',null,'US','No Hospitalario de Mediana Complejidad','CENTRAL','Red 12 de Junio','IPS',11,'UNIDAD SANITARIA',-25.42075,-57.56576,'SAN ANTONIO US','SAN ANTONIO US','AREA CENTRAL','IPS',null,'SIN SISTEMA'],
        ['02-US-04','SAN ESTANISLAO US',null,'US','Hospitalario 1 Baja Complejidad','SAN PEDRO DEL YCUAMANDIYU','Red San Pedro','IPS',2,'UNIDAD SANITARIA',-24.66484,-56.43076,'SAN ESTANISLAO US','SAN ESTANISLAO US','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['08-US-13','SAN IGNACIO US',null,'US','Hospitalario 1 Baja Complejidad','MISIONES','Red Misiones','IPS',8,'UNIDAD SANITARIA',-26.88827,-57.03132,'SAN IGNACIO U.S','SAN IGNACIO U.S','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['14-US-24','SAN ISIDRO DE CURUGUATY US',null,'US','Hospitalario 1 Baja Complejidad','CANINDEYU','Red Canindeyu','IPS',14,'UNIDAD SANITARIA',-24.47933,-55.68754,'SAN ISIDRO DEL CURUGUATY US','SAN ISIDRO DEL CURUGUATY US','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['08-US-14','SAN JUAN BAUTISTA US',null,'US','No Hospitalario de Mediana Complejidad','MISIONES','Red Misiones','IPS',8,'UNIDAD SANITARIA',-26.67038,-57.1383,'SAN JUAN BAUTISTA PS',null,'AREA INTERIOR','ALQUILADO',null,'SIN SISTEMA'],
        ['02-HR-02','SAN PEDRO DEL YCUAMANDY YU HR',null,'HR','Hospitalario 1 Baja Complejidad','SAN PEDRO DEL YCUAMANDIYU','Red San Pedro','IPS',2,'HOSPITAL REGIONAL',-24.08397,-57.07103,'SAN PEDRO DEL YCUAMANDIYU','SAN PEDRO DEL YCUAMANDIYU','AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['04-US-08','TEBICUARY US',null,'US','Hospitalario 1 Baja Complejidad','GUAIRA','Red Guaira Caazapá','IPS',4,'UNIDAD SANITARIA',-25.77444,-56.64816,'TEBICUARY US',null,'AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['01-US-02','VALLEMI US',null,'US','No Hospitalario de Mediana Complejidad','CONCEPCION','Red Concepción','IPS',1,'UNIDAD SANITARIA',-22.16305,-57.9488,'VALLEMI U.S.',null,'AREA INTERIOR','IPS',null,'SIN SISTEMA'],
        ['04-HR-04','VILLARRICA HR',null,'HR','Hospitalario 2 Mediana Complejidad','GUAIRA','Red Guaira Caazapá','IPS',4,'HOSPITAL REGIONAL',-25.77761,-56.45073,'VILLARRICA H.R','VILLARRICA H.R','AREA INTERIOR','IPS',null,'SIH'],
        ['11-US-20','VILLETA US',null,'US','No Hospitalario de Mediana Complejidad','CENTRAL','Red 12 de Junio','IPS',11,'UNIDAD SANITARIA',-25.50708,-57.5603,'VILLETA U.S','VILLETA U.S','AREA CENTRAL','IPS',null,'SIN SISTEMA'],
        ['11-US-21','YPACARAI US',null,'US','No Hospitalario de Mediana Complejidad','CENTRAL','Red Ingavi','IPS',11,'UNIDAD SANITARIA',-25.40479,-57.28862,'YPACARAI US','YPACARAI US','AREA CENTRAL','IPS',null,'SIN SISTEMA'],
        ['18-HO-04','12 DE JUNIO H.',null,'H','Hospitalario 1 Baja Complejidad','ASUNCIÓN','Red 12 de Junio','IPS',18,'HOSPITAL',-25.326,-57.62509,'CLINICA PERIFERICA 12 DE JUNIO','CLINICA PERIFERICA 12 DE JUNIO','AREA CENTRAL','IPS',null,'SIH'],
    ];

    public function run(): void
    {
        foreach ($this->datos as $row) {
            Establecimiento::updateOrCreate(
                ['id_establecimiento' => $row[0]],
                [
                    'nombre_oficial'          => $row[1],
                    'codigo'                  => $row[2],
                    'tipo_est'                => $row[3],
                    'complejidad'             => $row[4],
                    'departamento'            => $row[5],
                    'microred'                => $row[6],
                    'prestador'               => $row[7],
                    'nro_departamento'        => $row[8],
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
                ]
            );
        }

        // Recalcular campos derivados
        Establecimiento::activos()->chunk(100, function ($items) {
            foreach ($items as $est) {
                $est->recalcularCamposDerivados();
            }
        });

        $this->command->info('Establecimientos insertados/actualizados: ' . count($this->datos));
    }
}
