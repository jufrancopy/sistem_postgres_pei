{{-- ══ MODAL BANCO DE IDEAS E INSPIRACIÓN ODS 2030 (ONU) ══ --}}
<style>
.ods-grid-selector{display:flex;gap:6px;overflow-x:auto;padding-bottom:8px;margin-bottom:16px;scroll-behavior:smooth}
.ods-grid-selector::-webkit-scrollbar{height:5px}
.ods-grid-selector::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}

.ods-pill-btn{flex:0 0 auto;padding:6px 12px;border-radius:100px;font-size:0.75rem;font-weight:700;border:1.5px solid #e2e8f0;background:#fff;color:#475569;cursor:pointer;transition:all .15s ease;display:flex;align-items:center;gap:6px;user-select:none}
.ods-pill-btn:hover{transform:translateY(-1px);box-shadow:0 3px 10px rgba(0,0,0,.08)}
.ods-pill-btn.active{color:#fff;border-color:transparent;box-shadow:0 4px 14px rgba(0,0,0,.15);transform:scale(1.03)}

.ods-card-banner{padding:20px;border-radius:14px;color:#fff;margin-bottom:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);transition:all .2s ease}
.ods-num-badge{background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.4);color:#fff;padding:2px 10px;border-radius:100px;font-size:0.72rem;font-weight:800;letter-spacing:1px;text-transform:uppercase}

.ods-tab-nav{display:flex;gap:6px;border-bottom:2px solid #e2e8f0;margin-bottom:16px}
.ods-tab-btn{padding:8px 16px;border:none;background:none;font-size:0.83rem;font-weight:700;color:#64748b;cursor:pointer;border-bottom:3px solid transparent;margin-bottom:-2px;transition:all .15s ease}
.ods-tab-btn:hover{color:#0f172a}
.ods-tab-btn.active{color:#2563eb;border-bottom-color:#2563eb}

.ods-idea-item{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px;box-shadow:0 2px 8px rgba(0,0,0,.03);transition:all .15s ease}
.ods-idea-item:hover{border-color:#cbd5e1;box-shadow:0 4px 14px rgba(0,0,0,.06)}
.ods-idea-title{font-size:0.92rem;font-weight:800;color:#0f172a;margin-bottom:6px;display:flex;align-items:center;gap:8px}
.ods-idea-desc{font-size:0.83rem;color:#475569;line-height:1.55}

.ods-kpi-tag{display:inline-flex;align-items:center;gap:5px;background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;padding:3px 10px;border-radius:8px;font-size:0.75rem;font-weight:700;margin-right:6px;margin-bottom:6px}
</style>

<div class="modal fade" id="modalInspiracionOds" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 900px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            {{-- Header --}}
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1" style="border-radius: 8px; font-size: 0.7rem;">
                            <i class="fa fa-globe mr-1"></i> BANCO DE IDEAS ODS 2030 (ONU)
                        </span>
                        <span class="text-white-50 small">Ejemplos y Gestión Global</span>
                    </div>
                    <h5 class="modal-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                        Inspiración Estratégica e Indicadores Mundiales
                    </h5>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal" style="font-size: 1.5rem;">&times;</button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3 p-md-4" style="background: #f8fafc; max-height: 80vh; overflow-y: auto;">
                
                {{-- Selector de los 17 ODS --}}
                <div class="ods-grid-selector" id="odsSelectorBar">
                    {{-- Generado dinámicamente con JS --}}
                </div>

                {{-- Banner del ODS Seleccionado --}}
                <div class="ods-card-banner" id="odsBanner">
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
                        <span class="ods-num-badge" id="odsBannerNum">ODS 3</span>
                        <span class="small font-weight-bold text-white-50" id="odsBannerMetaCount">13 Metas de la ONU</span>
                    </div>
                    <h3 class="font-weight-bold text-white mt-2 mb-1" id="odsBannerTitle" style="font-size: 1.35rem; line-height: 1.3;">
                        Salud y Bienestar
                    </h3>
                    <p class="mb-0 text-white opacity-9 small" id="odsBannerDesc" style="line-height: 1.5;">
                        Garantizar una vida sana y promover el bienestar para todos en todas las edades.
                    </p>
                </div>

                {{-- Navegación por pestañas --}}
                <div class="ods-tab-nav">
                    <button type="button" class="ods-tab-btn active" onclick="switchOdsTab('ideas', this)">
                        <i class="fa fa-lightbulb text-warning mr-1"></i> Ideas de Gestión (¿Cómo se aplica?)
                    </button>
                    <button type="button" class="ods-tab-btn" onclick="switchOdsTab('targets', this)">
                        <i class="fa fa-bullseye text-info mr-1"></i> Metas Oficiales ONU
                    </button>
                    <button type="button" class="ods-tab-btn" onclick="switchOdsTab('kpis', this)">
                        <i class="fa fa-chart-bar text-success mr-1"></i> Indicadores Típicos
                    </button>
                </div>

                {{-- CONTENIDO PESTAÑA 1: IDEAS DE GESTIÓN --}}
                <div id="tabOdsIdeas">
                    <div id="odsIdeasList">
                        {{-- Cargado con JS --}}
                    </div>
                </div>

                {{-- CONTENIDO PESTAÑA 2: METAS OFICIALES ONU --}}
                <div id="tabOdsTargets" style="display: none;">
                    <div class="d-flex align-items-center justify-content-between mb-3 bg-white p-2.5 rounded border">
                        <span class="small text-muted font-weight-bold">
                            <i class="fa fa-sync fa-spin mr-1 text-primary" id="odsApiSpinner" style="display:none;"></i>
                            Metas Oficiales desde la API de Naciones Unidas (UN DESA)
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold rounded-pill" onclick="cargarMetasApiONU(currentOdsId)">
                            <i class="fa fa-redo mr-1"></i> Recargar API ONU
                        </button>
                    </div>
                    <div id="odsTargetsList">
                        {{-- Cargado dinámicamente --}}
                    </div>
                </div>

                {{-- CONTENIDO PESTAÑA 3: INDICADORES TÍPICOS --}}
                <div id="tabOdsKpis" style="display: none;">
                    <div class="p-3 bg-white rounded border mb-3">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-ruler-combined text-success mr-1"></i> Medición y Métricas de Éxito</h6>
                        <p class="small text-muted mb-0">Ejemplos de indicadores estándar utilizados en planes estratégicos de instituciones públicas y de salud:</p>
                    </div>
                    <div id="odsKpisList">
                        {{-- Cargado con JS --}}
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white py-2.5 px-4 justify-content-between">
                <span class="small text-muted">
                    <i class="fa fa-info-circle mr-1 text-primary"></i> Banco de Inspiración para Planificación Estratégica
                </span>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 font-weight-bold" data-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
var currentOdsId = 3;

var ODS_CATALOG = {
    1: {
        num: 1, name: "Fin de la Pobreza", color: "#e5243b", icon: "fa-hand-holding-usd",
        desc: "Erradicar la pobreza en todas sus formas en todo el mundo y fortalecer redes de protección social.",
        ideas: [
            { title: "Programas de Subsidios y Exoneración Sanitaria para Población Vulnerable", desc: "Establecer arancel cero o gratuidad total en medicamentos e intervenciones de alta complejidad para sectores en extrema vulnerabilidad." },
            { title: "Inclusión Financiera y Formalización Sanitaria", desc: "Integrar la cobertura médica con programas nacionales de empleo y desarrollo social para reducir el gasto de bolsillo." }
        ],
        kpis: ["Gasto de bolsillo en salud (% del ingreso familiar)", "% de población vulnerable cubierta por seguro médico", "Índice de cobertura de protección social"]
    },
    2: {
        num: 2, name: "Hambre Cero", color: "#dda63a", icon: "fa-utensils",
        desc: "Poner fin al hambre, lograr la seguridad alimentaria y la mejora de la nutrición.",
        ideas: [
            { title: "Programas de Nutrición Preventiva y Suplementación Infantil", desc: "Implementar controles nutricionales periódicos y entrega de micronutrientes a niños y gestantes." },
            { title: "Seguridad Alimentaria Hospitalaria y Escolar", desc: "Servicios de nutrición clínica de alta calidad y supervisión de dietas terapéuticas en centros de salud." }
        ],
        kpis: ["Prevalencia de desnutrición infantil o anemia", "% de pacientes con evaluación nutricional completa", "Tasa de recuperación nutricional asistida"]
    },
    3: {
        num: 3, name: "Salud y Bienestar", color: "#4c9f38", icon: "fa-heartbeat",
        desc: "Garantizar una vida sana y promover el bienestar de todos a todas las edades.",
        ideas: [
            { title: "Transformación Digital y Telemedicina Institucional", desc: "Plataformas de expediente clínico electrónico, agendamiento web de citas y consultas virtuales para reducir filas y tiempos de espera." },
            { title: "Optimización de la Red Sanitaria y Tiempos de Respuesta", desc: "Reestructuración de servicios de urgencia, triaje inteligente y ampliación de camas de terapia intensiva." },
            { title: "Manejo Integral de Enfermedades No Transmisibles (ENT)", desc: "Programas de control continuo para hipertensión, diabetes y salud cardiovascular con monitoreo descentralizado." },
            { title: "Atención Materno-Infantil de Alta Complejidad", desc: "Fortalecimiento de maternidades, salas de neonatología y tamizaje neonatal universal." }
        ],
        kpis: ["Tiempo promedio de espera en consulta externa (min)", "Tasa de cobertura de vacunación universal (%)", "% de pacientes crónicos con control vigente", "Tasa de ocupación y rotación de camas hospitalarias"]
    },
    4: {
        num: 4, name: "Educación de Calidad", color: "#c5192d", icon: "fa-graduation-cap",
        desc: "Garantizar una educación inclusiva, equitativa y de calidad y promover oportunidades de aprendizaje.",
        ideas: [
            { title: "Capacitación Continua del Personal de Salud (Residencias y Becas)", desc: "Programas de actualización médica continua, simulación clínica avanzada y posgrados institucionales." },
            { title: "Educación y Promoción de la Salud para la Comunidad", desc: "Talleres educativos comunitarios sobre hábitos saludables, prevención de adicciones y primeros auxilios." }
        ],
        kpis: ["Horas promedio de capacitación anual por funcionario", "% de profesionales de salud certificados en competencias", "Nivel de satisfacción en capacitaciones técnicas"]
    },
    5: {
        num: 5, name: "Igualdad de Género", color: "#ff3a21", icon: "fa-venus-mars",
        desc: "Lograr la igualdad entre los géneros y empoderar a todas las mujeres y las niñas.",
        ideas: [
            { title: "Políticas de Equidad de Género y Liderazgo Femenino", desc: "Equidad en cargos directivos y programas de conciliación de la vida laboral y familiar para el personal médico." },
            { title: "Unidades de Atención Integral a la Mujer y Prevención de Violencia", desc: "Protocolos clínicos especializados para la detección y asistencia a víctimas de violencia de género." }
        ],
        kpis: ["% de mujeres en cargos directivos y de toma de decisión", "% de personal capacitado en protocolos de género", "Tasa de respuesta inmediata en unidades de atención a la mujer"]
    },
    6: {
        num: 6, name: "Agua Limpia y Saneamiento", color: "#26bde2", icon: "fa-tint",
        desc: "Garantizar la disponibilidad de agua y su gestión sostenible y el saneamiento para todos.",
        ideas: [
            { title: "Gestión Segura de Residuos Hospitalarios y Efluentes", desc: "Plantas de tratamiento de aguas residuales clínicas y manejo riguroso de desechos biológicos infectocontagiosos." },
            { title: "Calidad Sanitaria del Agua Institucional", desc: "Monitoreo bacteriológico continuo del agua en hospitales y áreas de diálisis." }
        ],
        kpis: ["% de efluentes hospitalarios tratados con norma ambiental", "Índice de potabilidad y pureza en red interna de agua", "Volumen de residuos biológicos procesados en forma segura"]
    },
    7: {
        num: 7, name: "Energía Asequible y No Contaminante", color: "#fcc30b", icon: "fa-bolt",
        desc: "Garantizar el acceso a una energía asequible, segura, sostenible y moderna para todos.",
        ideas: [
            { title: "Eficiencia Energética y Paneles Solares en Hospitales", desc: "Implementación de energía solar fotovoltaica e iluminación LED de bajo consumo en instalaciones de salud." },
            { title: "Sistemas de Respaldo Energético Crítico", desc: "Grupos electrógenos y UPS redundantes para áreas quirúrgicas y salas de cuidados intensivos." }
        ],
        kpis: ["% de consumo cubierto por fuentes renovables", "Ahorro energético anual (kWh / kWh por m²)", "Disponibilidad del 99.99% en respaldos eléctricos críticos"]
    },
    8: {
        num: 8, name: "Trabajo Decente y Crecimiento Económico", color: "#a21942", icon: "fa-briefcase",
        desc: "Promover el crecimiento económico inclusivo y sostenible, el empleo y el trabajo decente.",
        ideas: [
            { title: "Salud Ocupacional y Bioseguridad del Personal Sanitario", desc: "Sistemas de prevención de riesgos laborales, vacunación a funcionarios y ergonomía médica." },
            { title: "Optimización de la Gestión de Compras y Suministros Sanitarios", desc: "Procesos licitatorios transparentes y eficientes que impulsen el desarrollo de proveedores locales." }
        ],
        kpis: ["Tasa de accidentes laborales por cada 1,000 funcionarios", "Índice de clima laboral y satisfacción interna", "% de ejecuciones presupuestarias eficientes"]
    },
    9: {
        num: 9, name: "Industria, Innovación e Infraestructura", color: "#fd6925", icon: "fa-industry",
        desc: "Construir infraestructuras resilientes, promover la industrialización inclusiva y fomentar la innovación.",
        ideas: [
            { title: "Modernización de la Infraestructura Hospitalaria y Equipamiento Médico", desc: "Adquisición de tomógrafos de última generación, aceleradores lineales y quirófanos inteligentes." },
            { title: "Laboratorio de Innovación e Inteligencia Artificial Médica", desc: "Pilotos de lectura asistida por IA en radiografías y analítica predictiva de demanda hospitalaria." }
        ],
        kpis: ["Monto invertido en renovación de equipamiento médico", "% de áreas hospitalarias remodeladas a norma internacional", "Proyectos de innovación médica implementados"]
    },
    10: {
        num: 10, name: "Reducción de las Desigualdades", color: "#dd1367", icon: "fa-equals",
        desc: "Reducir la desigualdad en y entre los países.",
        ideas: [
            { title: "Descentralización de Servicios Médicos de Alta Complejidad", desc: "Creación de centros de especialidades regionales para evitar que los ciudadanos se trasladen a la capital." },
            { title: "Accesibilidad Universal e Inclusión para Personas con Discapacidad", desc: "Infraestructura hospitalaria adaptada, rampas, señalética braille y lenguaje de señas en atención pública." }
        ],
        kpis: ["Nivel de descentralización de atenciones médicas (%)", "Índice de accesibilidad en edificios públicos y de salud", "Tasa de cobertura en zonas vulnerables o alejadas"]
    },
    11: {
        num: 11, name: "Ciudades y Comunidades Sostenibles", color: "#fd9d24", icon: "fa-city",
        desc: "Lograr que las ciudades sean inclusivas, seguras, resilientes y sostenibles.",
        ideas: [
            { title: "Red de Ambulancias y Movilidad Urbana Sostenible", desc: "Optimización de rutas de transporte sanitario de urgencia y uso de ambulancias eco-eficientes." },
            { title: "Planes de Contingencia Sanitarios ante Desastres Naturales", desc: "Infraestructura hospitalaria segura y preparada para epidemias o inundaciones." }
        ],
        kpis: ["Tiempo de llegada de ambulancia al sitio de urgencia (min)", "% de instalaciones con certificación de hospital seguro", "Capacidad de respuesta ante emergencias masivas"]
    },
    12: {
        num: 12, name: "Producción y Consumo Responsables", color: "#bf8b2e", icon: "fa-recycle",
        desc: "Garantizar modalidades de consumo y producción sostenibles.",
        ideas: [
            { title: "Gestión Eficiente del Stock de Medicamentos y Cero Desperdicio", desc: "Control farmacológico con código de barras y vencimiento inteligente para evitar pérdidas de insumos." },
            { title: "Digitalización de Procesos (Cero Papel)", desc: "Firma digital y expedientes institucionales 100% electrónicos." }
        ],
        kpis: ["% de reducción en descarte de medicamentos por vencimiento", "Hojas de papel economizadas al año (en millones)", "Porcentaje de expedientes tramitados digitalmente"]
    },
    13: {
        num: 13, name: "Acción por el Clima", color: "#3f7e44", icon: "fa-globe-americas",
        desc: "Adoptar medidas urgentes para combatir el cambio climático y sus efectos.",
        ideas: [
            { title: "Estrategia Hospital Verde e Impacto Ambiental Neutro", desc: "Reducción de la huella de carbono en la red hospitalaria mediante climatización inteligente y áreas verdes." },
            { title: "Respuesta Médica ante Enfermedades Vectoriales por Cambio Climático", desc: "Vigilancia epidemiológica temprana ante dengue, chikungunya u ondas de calor extremo." }
        ],
        kpis: ["Tasa de reducción de emisiones de CO2 hospitalario (%)", "% de áreas verdes preservadas en recintos de salud", "Índice de alerta epidemiológica temprana activa"]
    },
    14: {
        num: 14, name: "Vida Submarina", color: "#0a97d9", icon: "fa-fish",
        desc: "Conservar y utilizar sosteniblemente los océanos, los mares y los recursos marinos.",
        ideas: [
            { title: "Protección de Cuencas Hídricas y Prevención de Contaminación por Fármacos", desc: "Filtración avanzada en vertidos para impedir que residuos químicos o farmacéuticos contaminen ríos y costas." }
        ],
        kpis: ["% de reactivos y medicamentos neutralizados antes del vertido", "Cumplimiento de estándares de calidad de agua efluente"]
    },
    15: {
        num: 15, name: "Vida de Ecosistemas Terrestres", color: "#56c02b", icon: "fa-tree",
        desc: "Proteger, restablecer y promover el uso sostenible de los ecosistemas terrestres.",
        ideas: [
            { title: "Entornos Hospitalarios Saludables y Paisajismo Terapéutico", desc: "Integración de jardines terapéuticos y reforestación en predios hospitalarios para la recuperación de pacientes." }
        ],
        kpis: ["Superficie de jardines terapéuticos por cama (m²)", "Árboles nativos plantados en recintos sanitarios"]
    },
    16: {
        num: 16, name: "Paz, Justicia e Instituciones Sólidas", color: "#00689d", icon: "fa-balance-scale",
        desc: "Promover sociedades pacíficas e inclusivas, facilitar el acceso a la justicia y crear instituciones eficaces y transparentes.",
        ideas: [
            { title: "Transparencia Total, Datos Abiertos y Rendición de Cuentas", desc: "Portales públicos de compras médicas, tiempos de espera en tiempo real y buzón digital de denuncias." },
            { title: "Integridad y Código de Ética Institucional", desc: "Comités de ética médica y auditorías preventivas en la asignación de insumos de alto costo." }
        ],
        kpis: ["Índice de transparencia e información pública (%)", "% de denuncias investigadas y resueltas", "Puntaje de cumplimiento del código de ética"]
    },
    17: {
        num: 17, name: "Alianzas para Lograr los Objetivos", color: "#19486a", icon: "fa-hands-helping",
        desc: "Fortalecer los medios de ejecución y reavivar la Alianza Mundial para el Desarrollo Sostenible.",
        ideas: [
            { title: "Convenios Interinstitucionales y Cooperación Internacional", desc: "Alianzas con la OPS/OMS, universidades y ministerios para investigación y transferencia tecnológica." },
            { title: "Redes Públicas-Privadas para la Atención Médica", desc: "Convenios de integración con laboratorios y sanatorios para derivaciones de alta complejidad." }
        ],
        kpis: ["Número de convenios internacionales de salud vigentes", "Proyectos financiados por cooperación técnica externa"]
    }
};

function renderOdsSelectorBar() {
    var $bar = $('#odsSelectorBar').empty();
    for (var i = 1; i <= 17; i++) {
        var item = ODS_CATALOG[i];
        if (!item) continue;
        var btnClass = (i === currentOdsId) ? 'ods-pill-btn active' : 'ods-pill-btn';
        var style = (i === currentOdsId) ? 'background:' + item.color + ';' : '';
        
        var html = '<div class="' + btnClass + '" style="' + style + '" onclick="seleccionarOdsInspiracion(' + i + ')">' +
                   '<span style="font-weight:900;">' + i + '</span>' +
                   '<span>' + item.name + '</span>' +
                   '</div>';
        $bar.append(html);
    }
}

function seleccionarOdsInspiracion(odsId) {
    currentOdsId = odsId;
    var ods = ODS_CATALOG[odsId];
    if (!ods) return;

    renderOdsSelectorBar();

    // Actualizar Banner
    $('#odsBanner').css('background', 'linear-gradient(135deg, ' + ods.color + ' 0%, #0f172a 100%)');
    $('#odsBannerNum').text('ODS ' + ods.num);
    $('#odsBannerTitle').html('<i class="fa ' + ods.icon + ' mr-2"></i>' + ods.num + '. ' + ods.name);
    $('#odsBannerDesc').text(ods.desc);

    // Cargar Pestaña Ideas
    var $ideasContainer = $('#odsIdeasList').empty();
    if (ods.ideas && ods.ideas.length) {
        ods.ideas.forEach(function(idea) {
            $ideasContainer.append(
                '<div class="ods-idea-item">' +
                '<div class="ods-idea-title"><i class="fa fa-lightbulb text-warning"></i> ' + idea.title + '</div>' +
                '<div class="ods-idea-desc">' + idea.desc + '</div>' +
                '</div>'
            );
        });
    } else {
        $ideasContainer.html('<div class="p-3 text-muted text-center">Sin ideas registradas.</div>');
    }

    // Cargar Pestaña KPIs
    var $kpisContainer = $('#odsKpisList').empty();
    if (ods.kpis && ods.kpis.length) {
        ods.kpis.forEach(function(kpi) {
            $kpisContainer.append(
                '<div class="ods-idea-item" style="border-left: 4px solid ' + ods.color + ';">' +
                '<div class="ods-idea-title"><i class="fa fa-chart-line text-success"></i> ' + kpi + '</div>' +
                '<div class="small text-muted">Métrica clave utilizada para evaluar el avance estratégico de este objetivo.</div>' +
                '</div>'
            );
        });
    }

    // Cargar Metas API ONU
    cargarMetasApiONU(odsId);
}

function switchOdsTab(tabName, btnEl) {
    $('.ods-tab-btn').removeClass('active');
    $(btnEl).addClass('active');

    $('#tabOdsIdeas, #tabOdsTargets, #tabOdsKpis').hide();
    if (tabName === 'ideas') $('#tabOdsIdeas').fadeIn(150);
    if (tabName === 'targets') $('#tabOdsTargets').fadeIn(150);
    if (tabName === 'kpis') $('#tabOdsKpis').fadeIn(150);
}

function cargarMetasApiONU(odsId) {
    var $container = $('#odsTargetsList').empty();
    $('#odsApiSpinner').show();

    $.ajax({
        url: 'https://unstats.un.org/sdgapi/v1/sdg/Target/List?goal=' + odsId,
        type: 'GET',
        dataType: 'json',
        timeout: 5000,
        success: function(targets) {
            $('#odsApiSpinner').hide();
            if (Array.isArray(targets) && targets.length) {
                var goalTargets = targets.filter(function(t) { return String(t.goal) === String(odsId); });
                $('#odsBannerMetaCount').text(goalTargets.length + ' Metas Oficiales de la ONU');

                goalTargets.forEach(function(t) {
                    $container.append(
                        '<div class="ods-idea-item" style="border-left:4px solid #2563eb;">' +
                        '<div class="ods-idea-title"><span class="badge badge-primary px-2 py-1">Meta ' + t.code + '</span> ' + (t.title || 'Meta oficial') + '</div>' +
                        '<div class="ods-idea-desc mt-1">' + (t.description || t.title) + '</div>' +
                        '</div>'
                    );
                });
            } else {
                $container.html('<div class="p-3 text-muted text-center">No se encontraron metas de la ONU para este ODS.</div>');
            }
        },
        error: function() {
            $('#odsApiSpinner').hide();
            $container.html(
                '<div class="p-3 bg-white rounded border text-muted small text-center">' +
                '<i class="fa fa-exclamation-circle text-warning mr-1"></i> No se pudo conectar a la API de la ONU en tiempo real. Mostrando catálogo oficial en caché.' +
                '</div>'
            );
        }
    });
}

function abrirModalInspiracionOds(odsId) {
    var targetId = odsId || 3;
    seleccionarOdsInspiracion(targetId);
    $('#modalInspiracionOds').modal('show');
}
</script>
