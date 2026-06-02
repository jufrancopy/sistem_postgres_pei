<?php

namespace Database\Seeders;

use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use Illuminate\Database\Seeder;

class RiissFormularioSeeder extends Seeder
{
    public function run(): void
    {
        $seccionesData = [
            ['Introducción', null, 1],
            ['Datos de Identificación', null, 2],
            ['Datos del encargado de llenado del formulario', null, 3],
            ['Requerimientos documentales', null, 4],
            ['Datos generales del establecimiento', 'Edificio y terreno', 5],
            ['Datos generales del establecimiento', 'Tipo de servicio', 6],
            ['Datos generales del establecimiento', 'Regencia - Dirección', 7],
            ['Datos generales del establecimiento', 'Instalaciones', 8],
            ['Datos generales del establecimiento', 'Funcionalidad', 9],
            ['Datos generales del establecimiento', 'Circulación', 10],
            ['Datos generales del establecimiento', 'Accesibilidad', 11],
            ['Datos generales del establecimiento', 'Baños', 12],
            ['Consulta externa', 'Consulta externa', 13],
            ['Consulta externa', 'Consulta externa, Baños', 14],
            ['Consulta externa', 'Características de consultorios', 15],
            ['Internación', 'Internación, con lugar destinado para:', 16],
            ['Internación', 'Características de las salas de internación', 17],
            ['Urgencias', 'El Establecimiento sanitario cuenta en el área de Urgencias con:', 18],
            ['Urgencias', 'Características del área de Urgencias', 19],
            ['Quirófano', 'El Establecimiento sanitario cuenta en el área de Quirúrgica con:', 20],
            ['Quirófano', 'Características del Quirófano', 21],
            ['U.T.I.', 'U.T.I.', 22],
            ['U.T.I.', 'El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:', 23],
            ['Oncologia', 'El área de servicios oncológicos cuenta con:', 24],
            ['Nefrología', 'Servicio de Hemodiálisis cuenta con:', 25],
            ['Rehabilitación', 'El área de Rehabilitación cuenta con:', 26],
            ['Clínica odontológica', 'Recursos fisicos funcionales', 27],
            ['Servicios intermedios', 'El establecimiento cuenta con los Servicios intermedios:', 28],
            ['Farmacia', 'Características de la Farmacia', 29],
            ['Laboratorio', 'Regencia', 30],
            ['Laboratorio', 'El establecimiento cuenta en Laboratorio con los de:', 31],
            ['Imágenes', 'Regencia', 32],
            ['Imágenes', 'Características del área de Diagnóstico:', 33],
            ['Imágenes', 'El área para la realización de ultrasonido cuenta con:', 34],
            ['Imágenes', 'El área para la realización de radiología convencional cuenta con:', 35],
            ['Servicios generales', 'El establecimiento cuenta área de Servicios generales con:', 36],
            ['Área administrativa', 'El establecimiento cuenta en el área de Administrativa con:', 37],
        ];

        $seccionesMap = [];
        foreach ($seccionesData as $data) {
            $seccion = FormularioSeccion::create([
                'seccion'     => $data[0],
                'sub_seccion' => $data[1],
                'orden'       => $data[2],
            ]);
            $seccionesMap[$data[0] . '|' . ($data[1] ?? '')] = $seccion->id;
        }

        $this->insertarPreguntas($seccionesMap);

        // Desactivar preguntas duplicadas (cubiertas por otros campos del sistema)
        FormularioPregunta::whereIn('pregunta', [
            'Fecha de evaluación',
            'Departamento',
            'Ciudad o localidad, barrio',
        ])->update(['activa' => false]);

        $this->command->info('Secciones: ' . count($seccionesData));
    }

    private function insertarPreguntas(array $map): void
    {
        // [seccion, sub_seccion, pregunta, tipo, opciones, grupo_cartera, orden]
        $preguntas = [
            ['Introducción','','Dirección de correo electrónico','texto',null,null,1],
            ['Introducción','','Fecha de evaluación','date',null,null,2],  // inactiva — se usa la del header
            ['Introducción','','Nombre o Razón Social','texto',null,null,3],
            ['Datos de Identificación','','Nombre del establecimiento sanitario:','texto',null,null,1],
            ['Datos de Identificación','','Dirección del establecimiento Sanitario','texto',null,null,2],
            ['Datos de Identificación','','Teléfono del establecimiento','texto',null,null,3],
            ['Datos de Identificación','','Departamento','texto',null,null,4],              // inactiva — cubierta por Ubicación
            ['Datos de Identificación','','Ciudad o localidad, barrio','texto',null,null,5], // inactiva — cubierta por Ubicación
            ['Datos del encargado de llenado del formulario','','Nombre y apellido del encargado:','texto',null,null,1],
            ['Datos del encargado de llenado del formulario','','Teléfono del encargado','texto',null,null,2],
            ['Datos del encargado de llenado del formulario','','Usuario institucional','texto',null,null,3],
            ['Requerimientos documentales','','1. Formato de Declaración Jurada','si_no',null,null,1],
            ['Requerimientos documentales','','2. Formulario de Solicitud','si_no',null,null,2],
            ['Requerimientos documentales','','3. Formulario de Rótulo (Dos ejemplares)','si_no',null,null,3],
            ['Requerimientos documentales','','4. Contrato de Prestación de Servicios con regente','si_no',null,null,4],
            ['Requerimientos documentales','','5. Planos originales de arquitectura','si_no_na',null,null,5],
            ['Requerimientos documentales','','6. Fotocopia autenticada cédula identidad propietario','si_no_na',null,null,6],
            ['Requerimientos documentales','','7. Fotocopia cédula identidad y registro profesional del regente','si_no',null,null,7],
            ['Requerimientos documentales','','8. Manual de procedimientos médicos','si_no',null,null,8],
            ['Requerimientos documentales','','9. Nómina de personal','si_no',null,null,9],
            ['Requerimientos documentales','','10. Inventario de muebles y equipos','si_no',null,null,10],
            ['Datos generales del establecimiento','Tipo de servicio','Carácter del establecimiento (Público, Privado, etc.)','texto',null,null,1],
            ['Datos generales del establecimiento','Tipo de servicio','Acción sanitaria','checklist',['Preventiva','Curativa','Rehabilitacion','Formativa','Especializado','Internacion'],null,2],
            ['Datos generales del establecimiento','Regencia - Dirección','La Regencia es ejercida por un médico con Registro Profesional','texto',null,null,1],
            ['Datos generales del establecimiento','Instalaciones','Instalación eléctrica (ANDE, Generador, otros)','checklist',['ANDE','Generador de electricidad de emergencia','Otros'],null,1],
            ['Datos generales del establecimiento','Instalaciones','Comunicación (Teléfono, Radio, Celular, Internet)','checklist',['Telefono','Radio','Celular','Internet'],null,2],
            ['Datos generales del establecimiento','Instalaciones','Instalación Sanitaria (ESSAP, SENASA, Tanque elevado)','checklist',['ESSAP','SENASA','Tanque elevado','Otros'],null,3],
            ['Datos generales del establecimiento','Instalaciones','Cuenta con gases medicinales','si_no',null,null,4],
        ];

        foreach ($preguntas as $p) {
            $key = $p[0] . '|' . $p[1];
            if (!isset($map[$key])) continue;
            FormularioPregunta::create([
                'formulario_seccion_id'  => $map[$key],
                'pregunta'               => $p[2],
                'tipo_respuesta'         => $p[3],
                'opciones'               => $p[4],
                'servicio_cartera_grupo' => $p[5],
                'tags_cartera'           => $p[5] ? [$p[5]] : null,
                'orden'                  => $p[6],
            ]);
        }

        $this->insertarPreguntasConsulta($map);
    }

    private function insertarPreguntasConsulta(array $map): void
    {
        $preguntas = [
            ['Consulta externa','Consulta externa','Espera de consulta externa','si_no',null,null,1],
            ['Consulta externa','Consulta externa','Admisión y archivo en atención ambulatoria','si_no',null,null,2],
            ['Consulta externa','Consulta externa','SSHH público','si_no',null,null,3],
            ['Consulta externa','Consulta externa','SSHH para funcionario','si_no',null,null,4],
            ['Consulta externa','Consulta externa','Consultorio General','si_no',null,'Consulta Medicina Familiar',5],
            ['Consulta externa','Consulta externa','Consultorio Especifico','si_no',null,null,6],
            ['Consulta externa','Consulta externa','Vacunatorio','si_no',null,'Inmunizaciones',7],
            ['Consulta externa','Consulta externa','¿Con cuántos consultorios cuenta? discriminar por especialidad','texto',null,null,8],
            ['Consulta externa','Consulta externa','Cada consultorio cuenta con un responsable médico inscripto en el MSPBS','si_no',null,null,9],
            ['Consulta externa','Consulta externa, Baños','Baños para sala de espera con baño para discapacitados','si_no',null,null,1],
            ['Consulta externa','Características de consultorios','Superficie mínima 9m2','si_no',null,null,1],
            ['Consulta externa','Características de consultorios','Cuenta con lavamanos','si_no',null,null,2],
            ['Consulta externa','Características de consultorios','Baños de acceso directo','si_no',null,null,3],
            ['Consulta externa','Características de consultorios','Baños propios en urología, ginecología, ecografía','si_no',null,'Consulta Gineco-Obstetricia',4],
            ['Internación','Internación, con lugar destinado para:','Internados comunes','si_no',null,'Internación',1],
            ['Internación','Internación, con lugar destinado para:','Estación de enfermería','si_no',null,null,2],
            ['Internación','Internación, con lugar destinado para:','Sala de pediatría','si_no',null,'Consulta Pediatría',3],
            ['Internación','Internación, con lugar destinado para:','Lactarios','si_no',null,'Lactancia Materna',4],
            ['Internación','Internación, con lugar destinado para:','Con cuantas camas de internación cuenta?','texto',null,null,5],
            ['Internación','Características de las salas de internación','Área mínima de 9 m2 por internado','si_no',null,null,1],
            ['Internación','Características de las salas de internación','Elementos que permiten privacidad','si_no',null,null,2],
            ['Internación','Características de las salas de internación','Lavamanos','si_no',null,null,3],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Acceso cubierto ambulancias','si_no',null,null,1],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Sala de espera urgencias','si_no',null,null,2],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','R.A.C - triage','si_no',null,null,3],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Consultorio de urgencias adultos','si_no',null,'Consulta Clínica Médica',4],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Consultorio de urgencias niños','si_no',null,'Consulta Pediatría',5],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Sala de reanimación','si_no',null,null,6],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Sala de observación adultos','si_no',null,null,7],
            ['Urgencias','El Establecimiento sanitario cuenta en el área de Urgencias con:','Con cuantas camas de observación cuenta?','texto',null,null,8],
            ['Quirófano','El Establecimiento sanitario cuenta en el área de Quirúrgica con:','Quirófano de cirugía mayor','si_no',null,'Cirugía',1],
            ['Quirófano','El Establecimiento sanitario cuenta en el área de Quirúrgica con:','Sala de Parto','si_no',null,'Parto bajo riesgo',2],
            ['Quirófano','El Establecimiento sanitario cuenta en el área de Quirúrgica con:','Esterilización','si_no',null,null,3],
            ['Quirófano','El Establecimiento sanitario cuenta en el área de Quirúrgica con:','Recuperación','si_no',null,null,4],
            ['Quirófano','Características del Quirófano','Área mínima 25m2','si_no',null,null,1],
            ['Quirófano','Características del Quirófano','Gases medicinales en quirófano','si_no',null,null,2],
            ['U.T.I.','U.T.I.','La Regencia es ejercida por un médico especialista en cuidados intensivos','si_no_na',null,null,1],
            ['U.T.I.','El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:','Puesto de enfermería','si_no_na',null,'UTI',1],
            ['U.T.I.','El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:','Box diferenciados por paciente','si_no_na',null,null,2],
            ['U.T.I.','El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:','Área de control y monitoreo','si_no_na',null,null,3],
            ['U.T.I.','El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:','Cuarto Septico','si_no_na',null,null,4],
            ['Oncologia','El área de servicios oncológicos cuenta con:','Consultorio externo oncológico','si_no',null,'Oncología',1],
            ['Oncologia','El área de servicios oncológicos cuenta con:','Sala de quimioterapia','si_no',null,'Oncología',2],
            ['Nefrología','Servicio de Hemodiálisis cuenta con:','Sala de hemodiálisis','si_no',null,'Nefrología',1],
            ['Nefrología','Servicio de Hemodiálisis cuenta con:','Equipos de hemodiálisis','si_no',null,'Nefrología',2],
            ['Rehabilitación','El área de Rehabilitación cuenta con:','Consultorios de rehabilitación','si_no',null,null,1],
            ['Rehabilitación','El área de Rehabilitación cuenta con:','Sala de tratamiento','si_no',null,null,2],
            ['Rehabilitación','El área de Rehabilitación cuenta con:','Gimnasio','si_no',null,null,3],
            ['Clínica odontológica','Recursos fisicos funcionales','Sillón dental','si_no',null,'Consulta Odontología',1],
            ['Clínica odontológica','Recursos fisicos funcionales','Equipo de rayos x dental','si_no',null,'Consulta Odontología',2],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Inmunización e inyecciones','si_no',null,'Inmunizaciones',1],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Farmacia externa','si_no',null,'Farmacia',2],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Farmacia interna','si_no',null,'Farmacia',3],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Laboratorio Toma de muestra','si_no',null,'Laboratorio',4],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Laboratorio','si_no',null,'Laboratorio',5],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Rayos x','si_no',null,'Imágenes',6],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Electrocardiograma','si_no',null,'Consulta Clínica Médica',7],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Ecografía','si_no',null,'Imágenes',8],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Mamografía','si_no',null,'Prevención Oncológica',9],
            ['Servicios intermedios','El establecimiento cuenta con los Servicios intermedios:','Tomografía','si_no',null,'Imágenes',10],
            ['Farmacia','Características de la Farmacia','Farmacia interna con 15 m2 de superficie mínima','si_no',null,'Farmacia',1],
            ['Farmacia','Características de la Farmacia','Cuenta con lavamanos','si_no',null,null,2],
            ['Farmacia','Características de la Farmacia','Farmacia externa habilitada por MSPBS','si_no',null,'Farmacia',3],
            ['Laboratorio','Regencia','La Regencia es ejercida por un Bioquímico con Registro Profesional','si_no_na',null,'Laboratorio',1],
            ['Laboratorio','El establecimiento cuenta en Laboratorio con los de:','Laboratorio Toma de muestra','si_no',null,'Laboratorio',1],
            ['Laboratorio','El establecimiento cuenta en Laboratorio con los de:','Laboratorio de análisis clínico','si_no',null,'Laboratorio',2],
            ['Laboratorio','El establecimiento cuenta en Laboratorio con los de:','Laboratorio bacteriología','si_no',null,'Laboratorio',3],
            ['Laboratorio','El establecimiento cuenta en Laboratorio con los de:','Laboratorio Hematología','si_no',null,'Laboratorio',4],
            ['Imágenes','Regencia','La Regencia es ejercida por un médico con especialidad en imágenes','si_no_na',null,'Imágenes',1],
            ['Imágenes','Características del área de Diagnóstico:','Autorización de la A.R.R.N.','si_no',null,'Imágenes',1],
            ['Imágenes','El área para la realización de ultrasonido cuenta con:','Consultorios de ecografía','si_no',null,'Imágenes',1],
            ['Imágenes','El área para la realización de radiología convencional cuenta con:','Sala de radiología con área de comandos','si_no',null,'Imágenes',1],
            ['Servicios generales','El establecimiento cuenta área de Servicios generales con:','Cocina','si_no',null,null,1],
            ['Servicios generales','El establecimiento cuenta área de Servicios generales con:','Lavandería','si_no',null,null,2],
            ['Servicios generales','El establecimiento cuenta área de Servicios generales con:','Ambulancia','si_no',null,null,3],
            ['Servicios generales','El establecimiento cuenta área de Servicios generales con:','Morgue','si_no',null,null,4],
            ['Servicios generales','El establecimiento cuenta área de Servicios generales con:','Depósito basura patológica','si_no',null,null,5],
            ['Área administrativa','El establecimiento cuenta en el área de Administrativa con:','Área de espera','si_no',null,null,1],
            ['Área administrativa','El establecimiento cuenta en el área de Administrativa con:','Dirección','si_no',null,null,2],
            ['Área administrativa','El establecimiento cuenta en el área de Administrativa con:','Secretaría','si_no',null,null,3],
            ['Área administrativa','El establecimiento cuenta en el área de Administrativa con:','Archivo','si_no',null,null,4],
            ['Área administrativa','El establecimiento cuenta en el área de Administrativa con:','SSHH para personal','si_no',null,null,5],
        ];

        $count = 0;
        foreach ($preguntas as $p) {
            $key = $p[0] . '|' . $p[1];
            if (!isset($map[$key])) continue;
            FormularioPregunta::create([
                'formulario_seccion_id'  => $map[$key],
                'pregunta'               => $p[2],
                'tipo_respuesta'         => $p[3],
                'opciones'               => $p[4],
                'servicio_cartera_grupo' => $p[5],
                'tags_cartera'           => $p[5] ? [$p[5]] : null,
                'orden'                  => $p[6],
            ]);
            $count++;
        }

        $this->command->info("Preguntas insertadas: {$count}");
    }
}
