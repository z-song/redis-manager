<template>

    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>Clients</span>
        </div>
        <div class="small">
            <line-chart :chart-data="collection" :options="options" :height="200"></line-chart>
        </div>

     </el-card>

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
                collection: null,
                length: 20,
                options: {
                    responsive: true,
                    title:{
                        display:true,
                        text:'Clients'
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
                                labelString: 'Count'
                            }
                        }]
                    }
                },
            }
        },

        mounted () {
            this.fillData();

            this.refreshPeriodically();

            Bus.$on("connectionChanged", data => {
            
                this.collection = null;
                this.fillData();
            });
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

                this.$redis.info('clients').then(response => {

                    if (!this.collection) {

                        let data = {
                            labels: [],
                            datasets: [],
                        };

                        if (data.labels.length < this.length) {
                            data.labels.push(this.time())
                        }

                        data.datasets = [{
                            label: "connected_clients",
                            fill: false,
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [response.data.connected_clients],
                        }, {
                            label: "blocked_clients",
                            fill: false,
                            backgroundColor: 'rgb(54, 162, 235)',
                            borderColor: 'rgb(54, 162, 235)',
                            data: [response.data.blocked_clients],
                        }];

                        this.collection = data
                    } else {
                        this.collection = this.newData(response.data)
                    }

                });

            },

            newData(response) {

                let data = {
                    labels: this.collection.labels,
                    datasets: this.collection.datasets
                };

                data.labels.push(this.time());

                data.datasets[0].data.push(response.connected_clients);
                data.datasets[1].data.push(response.blocked_clients);

                if (data.labels.length > this.length) {
                    data.labels.shift();
                    data.datasets[0].data.shift();
                    data.datasets[1].data.shift();
                }

                return data;
            }
        },

        beforeDestroy() {
            clearInterval(this.interval);
            
            Bus.$off("connectionChanged");
        },
    }

</script>

<style>
    .small {
        width: 100%;
        margin:  10px auto;
    }
</style>