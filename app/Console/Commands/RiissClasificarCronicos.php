<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RiissMedicamento;

class RiissClasificarCronicos extends Command
{
    protected $signature = 'riiss:clasificar-cronicos';
    protected $description = 'Clasifica los medicamentos de RIISS según la Resolución C.A. N° 007-043/2022 y N° 035-001/2023 (Patologías Crónicas y Psicotrópicos)';

    public function handle()
    {
        $this->info("Iniciando clasificación de medicamentos para Patologías Crónicas...");

        $categorias = [
            'Cardiovascular e Hipertensión' => [
                'ACENOCUMAROL', 'ACIDO ACETILSALICILICO', 'ASPIRINA', 'ALFAMETIL DOPA', 'AMIODARONA',
                'AMLODIPINA', 'ATENOLOL', 'ATORVASTATINA', 'BISOPROLOL', 'CARVEDILOL', 'CIPROFIBRATO',
                'CLOPIDOGREL', 'DABIGATRAN', 'DIGOXINA', 'DILTIAZEM', 'DIOSMINA', 'ENALAPRIL',
                'ESPIRONOLACTONA', 'ENOXAPARINA', 'FUROSEMIDA', 'HIDROCLOROTIAZIDA', 'AMILORIDA',
                'ISOSORBIDE', 'LOSARTAN', 'NEBIVOLOL', 'NIMODIPINA', 'NITROGLICERINA', 'PENTOXIFILINA',
                'PROPRANOLOL', 'TELMISARTAN', 'WARFARINA', 'SILDENAFIL', 'VALSARTAN', 'ROSUVASTATINA',
                'FENOFIBRATO', 'CANDESARTAN', 'VERAPAMILO', 'CAPTOPRIL', 'SIMVASTATINA'
            ],
            'Diabetes y Endocrinología' => [
                'DAPAGLIFLOZINA', 'GLIMEPIRIDA', 'INSULINA', 'LIRAGLUTIDA', 'METFORMINA', 'SITAGLIPTINA',
                'LEVOTIROXINA', 'PREDNISONA', 'PROPILTIOURACILO', 'SOMATROPINA', 'CABERGOLINA',
                'TESTOSTERONA', 'EMPAGLIFLOZINA', 'GLICLAZIDA', 'GLIBENCLAMIDA', 'METIMAZOL',
                'HIDROCORTISONA', 'DEXAMETASONA', 'FLUDROCORTISONA', 'TIAMAZOL'
            ],
            'Neurología' => [
                'ACETAZOLAMIDA', 'AMANTADINA', 'BETA-HISTINA', 'BETAHISTINA', 'CARBAMAZEPINA', 'CLONAZEPAM',
                'DIFENHIDANTOINA', 'FENITOINA', 'VALPROATO', 'ACIDO VALPROICO', 'DIVALPROATO', 'FENOBARBITAL',
                'GABAPENTINA', 'LAMOTRIGINA', 'LEVETIRACETAM', 'LEVODOPA', 'CARBIDOPA', 'OXCARBAZEPINA',
                'PRIMIDONA', 'TOPIRAMATO', 'PREGABALINA', 'PIRIDOSTIGMINA', 'GALANTAMINA', 'DONEPECILO',
                'DONEPEZILO', 'RIVASTIGMINA', 'RILUZOLE', 'RILUZOL', 'CITICOLINA', 'PRAMIPEXOL',
                'INTERFERON', 'GLATIRAMER', 'BACLOFENO', 'MEMANTINA', 'BIPERIDENO'
            ],
            'Psiquiatría (Psicotrópicos)' => [
                'ALPRAZOLAM', 'AMITRIPTILINA', 'FLUOXETINA', 'HALOPERIDOL', 'HIDROXICINA',
                'LITIO', 'OLANZAPINA', 'QUETIAPINA', 'RISPERIDONA', 'SERTRALINA', 'TRAZODONA',
                'VENLAFAXINA', 'ZOLPIDEM', 'LEVOMEPROMAZINA', 'DIAZEPAM', 'LORAZEPAM', 'MIDAZOLAM',
                'CLONAZEPAM', 'CLORPROMAZINA', 'CLOZAPINA', 'ESCITALOPRAM', 'PAROXETINA',
                'ARIPIPRAZOL', 'DULOXETINA', 'BROMAZEPAM'
            ],
            'Respiratorio' => [
                'BROMURO DE IPRATROPIO', 'IPRATROPIO', 'FENOTEROL', 'BUDESONIDA', 'DEXTROMETORFANO',
                'FLUTICASONA', 'MONTELUKAST', 'SALBUTAMOL', 'SALMETEROL', 'TEOFILINA', 'TIOTROPIO',
                'NINTEDANIB', 'ILOPROST', 'BECLOMETASONA', 'FORMOTEROL', 'ACETILCISTEINA'
            ],
            'Gastroenterología' => [
                'CINITAPRIDA', 'DOMPERIDONA', 'LACTULOSA', 'MESALAZINA', 'MESALASINA', 'MISOPROSTOL',
                'N-ACETIL-CISTEINA', 'OMEPRAZOL', 'PANTOPRAZOL', 'ESOMEPRAZOL', 'PANCREATINA',
                'TRIMETBUTRINA', 'TRIMEBUTINA', 'DESMOPRESINA', 'SUCRALFATO', 'METOCLOPRAMIDA'
            ],
            'Reumatología e Inmunología' => [
                'ADALIMUMAB', 'ETANERCEPT', 'INFLIXIMAB', 'TOCILIZUMAB', 'TOFACITINIB', 'UPADACITINIB',
                'LEFLUNOMIDA', 'METOTREXATO', 'HIDROXICLOROQUINA', 'ALOPURINOL', 'GLUCOSAMINA',
                'CONDROITINA', 'MELOXICAM', 'COLCHICINA', 'SULFASALAZINA', 'FEBUXOSTAT'
            ],
            'Trasplantes e Inmunosupresores' => [
                'AZATIOPRINA', 'CICLOSPORINA', 'TACROLIMUS', 'MICOFENOLATO', 'ACIDO MICOFENOLICO',
                'SIROLIMUS', 'VALGANCICLOVIR', 'EVEROLIMUS'
            ],
            'Hematología y Oncología' => [
                'ERITROPOYETINA', 'HIDROXIUREA', 'ACIDO TRANEXAMICO', 'TRANEXAMICO', 'ACIDO FOLICO',
                'SULFATO FERROSO', 'FACTOR VIII', 'FACTOR IX', 'TALIDOMIDA', 'LENALIDOMIDA',
                'IMATINIB', 'NILOTINIB', 'BICALUTAMIDA', 'CICLOFOSFAMIDA', 'LETROZOL',
                'LEUPROLIDE', 'TAMOXIFENO', 'MERCAPTOPURINA', 'OCTREOTIDA', 'ANASTROZOL', 'FILGRASTIM'
            ],
            'Oftalmología' => [
                'BRIMONIDINA', 'DORZOLAMIDA', 'TIMOLOL', 'LATANOPROST', 'TRAVOPROST', 'BIMATOPROST',
                'PILOCARPINA', 'ANTAZOLINA', 'TETRACICLINA', 'LAGRIMAS', 'CARBOXIMETILCELULOSA'
            ],
            'Urología y Nefrología' => [
                'TAMSULOSINA', 'SERENOA REPENS', 'TOLTERODINA', 'OXIBUTININA', 'CITRATO DE POTASIO',
                'SEVELAMER', 'POLIESTIRENO SULFONATO', 'POTASIO', 'FINASTERIDE', 'DUTASTERIDE'
            ],
            'Dermatología y Osteoporosis' => [
                'BETAMETASONA', 'CLOBETASOL', 'ISOTRETINOINA', 'FLUCONAZOL', 'TERBINAFINA',
                'COLAGENASA', 'CLORANFENICOL', 'ALENDRONATO', 'ACIDO ZOLEDRONICO', 'ZOLEDRONATO',
                'CALCIO', 'VITAMINA D3', 'COLECALCIFEROL', 'ALFACALCIDOL'
            ]
        ];

        $psicotropicosKeywords = [
            'ALPRAZOLAM', 'AMITRIPTILINA', 'CLONAZEPAM', 'DIAZEPAM', 'LORAZEPAM', 'MIDAZOLAM',
            'FENOBARBITAL', 'ZOLPIDEM', 'BROMAZEPAM', 'LEVOMEPROMAZINA', 'HALOPERIDOL',
            'RISPERIDONA', 'OLANZAPINA', 'QUETIAPINA', 'CLOZAPINA', 'MORFINA', 'FENTANILO',
            'METADONA', 'TRAMADOL', 'CODEINA', 'BUPRENORFINA', 'OXICODONA'
        ];

        // Reset previo
        RiissMedicamento::query()->update([
            'es_cronico'            => false,
            'categoria_terapeutica' => null,
            'es_psicotropico'       => false,
            'resolucion_respaldo'   => null,
        ]);

        $totalMarcados = 0;
        $totalPsicotropicos = 0;

        $todosMedicamentos = RiissMedicamento::all();

        foreach ($todosMedicamentos as $med) {
            $nombreNorm = mb_strtoupper($med->nombre, 'UTF-8');
            $categoriaAsignada = null;
            $esPsicotropico = false;

            // Verificar psicotrópico
            foreach ($psicotropicosKeywords as $pk) {
                if (str_contains($nombreNorm, $pk)) {
                    $esPsicotropico = true;
                    break;
                }
            }

            // Buscar categoría
            foreach ($categorias as $catNombre => $keywords) {
                foreach ($keywords as $kw) {
                    if (str_contains($nombreNorm, $kw)) {
                        $categoriaAsignada = $catNombre;
                        break 2;
                    }
                }
            }

            if ($categoriaAsignada || $esPsicotropico) {
                $med->update([
                    'es_cronico'            => true,
                    'categoria_terapeutica' => $categoriaAsignada ?? 'Psiquiatría (Psicotrópicos)',
                    'es_psicotropico'       => $esPsicotropico,
                    'resolucion_respaldo'   => 'RCA 007-043/22 - RCA 035-001/23',
                ]);
                $totalMarcados++;
                if ($esPsicotropico) $totalPsicotropicos++;
            }
        }

        $this->info("✅ Clasificación completada:");
        $this->info(" - Total medicamentos evaluados: " . $todosMedicamentos->count());
        $this->info(" - Medicamentos clasificados como Crónicos: {$totalMarcados}");
        $this->info(" - Medicamentos clasificados como Psicotrópicos (Ley 1340/88): {$totalPsicotropicos}");

        return 0;
    }
}
