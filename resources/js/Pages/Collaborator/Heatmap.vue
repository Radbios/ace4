<template>

    <Head title="GESTIC - Projetos" />
    <div class="container-fluid pb-compensation">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-globe-americas mr-3 text-2xl"></i> Mapa Global de Densidade de Colaboradores
            </h2>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-xl border border-gray-100">
            <div ref="chartContainer" id="value-map-chart" class="min-h-[70vh] w-full"></div>
            <footer class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-sm text-blue-800 rounded-lg"></footer>
        </div>
    </div>
</template>

<script setup>
    import {onMounted, ref, watch, computed, onBeforeUnmount} from 'vue';
    import {Head, Link, router} from '@inertiajs/vue3';
    import {route} from 'ziggy-js';

    const props = defineProps({
        data: {
            type: Array,
            required: true,
            default: () => []
        }
    });

    const GEOJSON_URL = 'https://cdn.jsdelivr.net/npm/echarts@4.9.0/map/json/world.json';
    const MAP_NAME = 'world_complete_fetch';
    const chartElementId = 'value-map-chart';

    const chartContainer = ref(null);
    const chartInstance = ref(null);
    const isMapRegistered = ref(false);
    const loadingMessage = ref('Aguardando o carregamento da biblioteca ECharts...');

    const formattedData = computed(() => {
        return props.data.map(item => ({
            name: item.country,
            value: item.count,
        }));
    });

    const setChartOptions = () => {
        if (typeof echarts === 'undefined' || !isMapRegistered.value) return;

        if (!chartInstance.value && chartContainer.value) {
            chartInstance.value = echarts.init(chartContainer.value);
        }

        if (chartInstance.value) {
            chartInstance.value.clear();
            chartInstance.value.resize();
        } else {
            return;
        }

        const maxValue = Math.max(...formattedData.value.map(d => d.value || 0));

        const option = {
            title: {
                text: 'Mapa de Calor de Colaboradores por País',
                left: 'center',
                textStyle: {color: '#3f51b5', fontSize: 18}
            },
            tooltip: {
                trigger: 'item',
                formatter: function (params) {
                    if (params.value !== undefined) {
                        return `País: <strong>${params.name}</strong><br/>Colaboradores: <strong>${params.value}</strong>`;
                    }
                    return `País: ${params.name}<br/>Dados não disponíveis`;
                }
            },
            visualMap: {
                min: 0,
                max: maxValue > 0 ? maxValue : 100,
                left: 'left',
                top: 'bottom',
                text: ['Alto', 'Baixo'],
                calculable: true,
                inRange: {
                    color: ['#e0f3f8', '#aed7e7', '#7ab0d2', '#4e85bd', '#3f51b5']
                },
                textStyle: {
                    color: '#333'
                }
            },
            series: [
                {
                    name: 'Contagem de Colaboradores',
                    type: 'map',
                    map: MAP_NAME,
                    roam: true,
                    data: formattedData.value,
                    label: {show: false},
                    itemStyle: {areaColor: '#f3f4f6', borderColor: '#999'},
                    emphasis: {
                        label: {show: true, color: '#000', fontWeight: 'bold'},
                        itemStyle: {areaColor: '#ff9800'}
                    }
                }
            ]
        };

        try {
            chartInstance.value.setOption(option, {notMerge: true});
        } catch (e) {
            console.error("Error setting ECharts options.", e);
        }
    };

    const registerMapFromURL = async () => {
        loadingMessage.value = 'Carregando dados GeoJSON do mapa...';

        if (isMapRegistered.value) {
            setChartOptions();
            return;
        }

        if (chartInstance.value) {
            chartInstance.value.showLoading({text: loadingMessage.value});
        }

        try {
            const response = await fetch(GEOJSON_URL);
            if (!response.ok) throw new Error(`Failed to fetch GeoJSON: ${response.statusText}`);

            const geoJson = await response.json();
            echarts.registerMap(MAP_NAME, geoJson);
            isMapRegistered.value = true;

            if (chartInstance.value) {
                chartInstance.value.hideLoading();
            }

            setChartOptions();
            window.addEventListener('resize', () => chartInstance.value?.resize());

        } catch (e) {
            console.error("Erro CRÍTICO: Não foi possível carregar ou registrar o GeoJSON.", e);
            isMapRegistered.value = false;

            if (chartContainer.value) {
                chartContainer.value.innerHTML = `<div class="p-8 text-center text-red-700 bg-red-100 rounded-lg">Falha: Não foi possível carregar o mapa GeoJSON completo.</div>`;
            }
        }
    };

    watch(formattedData, () => {
        if (chartInstance.value && isMapRegistered.value) {
            setChartOptions();
        }
    }, {deep: true});

    onMounted(() => {
        const loadEChartsScripts = () => {
            if (!chartContainer.value) {
                console.error("Chart container element not available in the DOM.");
                return;
            }

            if (typeof echarts === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js';

                script.onload = () => {
                    chartInstance.value = echarts.init(chartContainer.value);
                    registerMapFromURL();
                };
                script.onerror = () => {
                    loadingMessage.value = 'Falha ao carregar a biblioteca ECharts.';
                };
                document.head.appendChild(script);
            } else {
                chartInstance.value = echarts.init(chartContainer.value);
                registerMapFromURL();
            }
        };

        loadEChartsScripts();
    });

    onBeforeUnmount(() => {
        if (chartInstance.value) {
            chartInstance.value.dispose();
            chartInstance.value = null;
        }
        window.removeEventListener('resize', () => chartInstance.value?.resize());
    });
</script>

<style scoped>
    .text-primary {
        color: #3f51b5 !important;
    }

    .font-sans {
        font-family: 'Inter', sans-serif;
    }

    .shadow-xl {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .rounded-lg {
        border-radius: 0.5rem;
    }

    #value-map-chart {
        width: 100%;
        min-height: 70vh;
    }
</style>
