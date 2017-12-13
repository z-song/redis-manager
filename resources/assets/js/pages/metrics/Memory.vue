<template>

    <div class="small">
        <line-chart :chart-data="datacollection" :options="options" :height="200"></line-chart>
    </div>

</template>

<script>

    import Vue from 'vue';
    import LineChart from '../../components/Charts/LineChart.js'

export default {

    components: {
        LineChart
    },

    data() {
        return {
            datacollection: null,
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Memory information'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            },
        }
    },

    mounted () {
        this.fillData();

        this.refreshPeriodically()
    },

    methods: {
        refreshPeriodically() {
            this.interval = setInterval(() => {
                this.fillData();
            }, 3000);
        },

        time () {
            const d = new Date();

            return d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
        },

        fillData () {

            this.$redis.info('memory').then(response => {

                if (!this.datacollection) {

                    let data = {
                        labels: [],
                        datasets: [],
                    };

                    if (data.labels.length < 10) {
                        data.labels.push(this.time())
                    }

                    data.datasets = [{
                        label: "used_memory",
                        fill: false,
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: [response.data.Memory.used_memory],
                    }, {
                        label: "used_memory_rss",
                        fill: false,
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: [response.data.Memory.used_memory_rss],
                    }, {
                        label: "used_memory_peak",
                        fill: false,
                        backgroundColor: 'rgb(255, 205, 86)',
                        borderColor: 'rgb(255, 205, 86)',
                        data: [response.data.Memory.used_memory_peak],
                    }];

                    this.datacollection = data
                } else {
                    this.datacollection = this.newData(response.data)
                }

            });

        },

        newData(response) {

            let data = {
                labels: this.datacollection.labels,
                datasets: this.datacollection.datasets
            };

            data.labels.push(this.time());
            data.datasets[0].data.push(response.Memory.used_memory);
            data.datasets[1].data.push(response.Memory.used_memory_rss);
            data.datasets[2].data.push(response.Memory.used_memory_peak);

            if (data.labels.length > 10) {
                data.labels.shift();
                data.datasets[0].data.shift();
                data.datasets[1].data.shift();
                data.datasets[2].data.shift();
            }

            return data;
        }
    },

    beforeDestroy() {
        clearInterval(this.interval);
    },
}

</script>

<style>
    .small {
        width: 100%;
        margin:  10px auto;
    }
</style>