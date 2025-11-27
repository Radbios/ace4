<template>
    <div class="chart-area" style="height: 300px;">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
    import {ref, onMounted, watch} from 'vue'
    import {Chart} from 'chart.js/auto'

    const props = defineProps({
        data: {type: Object, required: true},
        currencyFormatter: {type: Function, required: true}
    })

    const chartCanvas = ref(null)
    let chartInstance = null

    const renderChart = () => {
        const labels = Object.keys(props.data)
        const values = Object.values(props.data)

        if (!chartCanvas.value) return
        if (chartInstance) chartInstance.destroy()

        chartInstance = new Chart(chartCanvas.value, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: "Orçamento Total",
                    data: values,
                    backgroundColor: '#4e73df'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) =>
                                props.currencyFormatter(ctx.raw)
                        }
                    }
                }
            }
        })
    }

    onMounted(renderChart)
    watch(() => props.data, renderChart)
</script>
