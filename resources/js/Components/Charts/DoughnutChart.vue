<template>
    <div class="chart-area" style="height: 300px;">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
    import {ref, onMounted, watch} from 'vue'
    import {Chart} from 'chart.js/auto'

    const props = defineProps({
        data: {type: Object, required: true}
    })

    const chartCanvas = ref(null)
    let chartInstance = null

    const generateColors = (count) => {
        const colors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
            '#858796', '#5a5c69', '#3b5998', '#ff7f0e', '#2ca02c'
        ]
        return Array.from({length: count}, (_, i) => colors[i % colors.length])
    }

    const renderChart = () => {
        const labels = Object.keys(props.data)
        const values = Object.values(props.data)

        if (!chartCanvas.value) return

        if (chartInstance) chartInstance.destroy()

        chartInstance = new Chart(chartCanvas.value, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: generateColors(labels.length)
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    }

    onMounted(renderChart)
    watch(() => props.data, renderChart)
</script>
