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
                        text:'Throughput'
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
                                labelString: 'Calls'
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

                this.$redis.info('commandstats').then(response => {

                    if (!this.datacollection) {

                        let data = {
                            labels: [],
                            datasets: [],
                        };

                        if (data.labels.length < 10) {
                            data.labels.push(this.time())
                        }

                        data.datasets = [{
                            label: "used_cpu_user",
                            fill: false,
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [response.data.CPU.used_cpu_user],
                        }, {
                            label: "used_cpu_sys",
                            fill: false,
                            backgroundColor: 'rgb(54, 162, 235)',
                            borderColor: 'rgb(54, 162, 235)',
                            data: [response.data.CPU.used_cpu_sys],
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

                data.datasets[0].data.push(response.CPU.used_cpu_user);
                data.datasets[1].data.push(response.CPU.used_cpu_sys);

                if (data.labels.length > 10) {
                    data.labels.shift();
                    data.datasets[0].data.shift();
                    data.datasets[1].data.shift();
                }

                return data;
            }
        }
    }

</script>

<style>
    .small {
        width: 100%;
        margin:  10px auto;
    }
</style>