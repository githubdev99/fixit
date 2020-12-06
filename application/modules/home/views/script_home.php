<script>
    ! function(r) {
        "use strict";

        function t() {
            this.$body = r("body"), this.$chatInput = r(".chat-input"), this.$chatList = r(".conversation-list"), this.$chatSendBtn = r(".chat-send"), this.$chatForm = r("#chat-form")
        }
        t.prototype.save = function() {
            var t = this.$chatInput.val(),
                e = moment().format("h:mm");
            return "" == t ? (this.$chatInput.focus(), !1) : (r('<li class="clearfix odd"><div class="chat-avatar"><img src="asset/images/users/avatar-7.jpg" alt="male"><i>' + e + '</i></div><div class="conversation-text"><div class="ctext-wrap"><i>Shreyu</i><p>' + t + "</p></div></div></li>").appendTo(".conversation-list"), this.$chatInput.focus(), this.$chatList.animate({
                scrollTop: this.$chatList.prop("scrollHeight")
            }, 1e3), !0)
        }, t.prototype.init = function() {
            var e = this;
            e.$chatInput.keypress(function(t) {
                if (13 == t.which) return e.save(), !1
            }), e.$chatForm.on("submit", function(t) {
                return t.preventDefault(), e.save(), e.$chatForm.removeClass("was-validated"), e.$chatInput.val(""), !1
            })
        }, r.ChatApp = new t, r.ChatApp.Constructor = t
    }(window.jQuery),
    function(o) {
        "use strict";

        function t() {}
        t.prototype.initCharts = function() {        
            // $label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"]
            <?php 
                if(empty($jan)){
                    $jan = 0;
                }if(empty($des)){
                    $des = 0;
                }
            ?>
            var e = new Date,
                r = {
                    chart: {
                        height: 400,
                        type: "area"
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        curve: "smooth",
                        width: 4
                    },
                    series: [{
                        name: "Penjualan",
                        data: [<?= $jan ?>, <?= $feb ?>, <?= $mar ?>, <?= $apr ?>, <?= $mei ?>, <?= $jun ?>, <?= $jul ?>, <?= $agus?>, <?= $sept?>, <?= $okt ?>, <?= $nov ?>, <?= $des ?>]
                    }],
                    zoom: {
                        enabled: !1
                    },
                    legend: {
                        show: !1
                    },
                    colors: ["#43d39e"],
                    xaxis: {

                        type: "string",
                        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul","Agus","Sept","Okt","Nov","Des"],
                        tooltip: {
                            enabled: !1
                        },
                        axisBorder: {
                            show: !1
                        },
                        labels: {}
                    },
                    // yaxis: {
                    //     labels: {
                    //         formatter: function(t) {
                    //             return t + "k"
                    //         }
                    //     }
                    // },
                    fill: {
                        type: "gradient",
                        gradient: {
                            type: "vertical",
                            shadeIntensity: 1,
                            inverseColors: !1,
                            opacityFrom: .45,
                            opacityTo: .05,
                            stops: [45, 100]
                        }
                    }
                };
            new ApexCharts(document.querySelector("#revenue-chart"), r).render();


            r = {
                chart: {
                    height: 296,
                    type: "bar",
                    stacked: !0,
                    toolbar: {
                        show: !1
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "45%"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                series: [{
                    name: "Net Profit",
                    data: [35, 44, 55, 57, 56, 61]
                }, {
                    name: "Revenue",
                    data: [52, 76, 85, 101, 98, 87]
                }],
                xaxis: {
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    axisBorder: {
                        show: !1
                    }
                },
                legend: {
                    show: !1
                },
                grid: {
                    row: {
                        colors: ["transparent", "transparent"],
                        opacity: .2
                    },
                    borderColor: "#f3f4f7"
                },
                tooltip: {
                    y: {
                        formatter: function(t) {
                            return "$ " + t + " thousands"
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#targets-chart"), r).render();

            r = {
                plotOptions: {
                    pie: {
                        donut: {
                            size: "70%"
                        },
                        expandOnClick: !1
                    }
                },
                chart: {
                    height: 298,
                    type: "donut"
                },
                legend: {
                    show: !0,
                    position: "right",
                    horizontalAlign: "left",
                    itemMargin: {
                        horizontal: 6,
                        vertical: 3
                    }
                },
                series: [44, 55, 41],
                labels: ["Laki-laki", "Perempuan", "Tidak disebutkan"],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function(t) {
                            return t + "k"
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart_jenis_kelamin"), r).render()

            r = {
                plotOptions: {
                    pie: {
                        donut: {
                            size: "70%"
                        },
                        expandOnClick: !1
                    }
                },
                chart: {
                    height: 298,
                    type: "donut"
                },
                legend: {
                    show: !0,
                    position: "right",
                    horizontalAlign: "left",
                    itemMargin: {
                        horizontal: 6,
                        vertical: 3
                    }
                },
                series: [44, 55],
                labels: ["Pembeli Baru", "Pembeli Lama"],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function(t) {
                            return t + "k"
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart_tipe_pembeli"), r).render()

        }, t.prototype.init = function() {
            o("#dash-daterange").flatpickr({
                mode: "range",
                defaultDate: [moment().subtract(7, "days").format("YYYY-MM-DD"), moment().format("YYYY-MM-DD")]
            }), o("#calendar-widget").flatpickr({
                inline: !0,
                shorthandCurrentMonth: !0
            }), o.ChatApp.init(), this.initCharts()
        }, o.Dashboard = new t, o.Dashboard.Constructor = t
    }(window.jQuery),
    function() {
        "use strict";
        window.jQuery.Dashboard.init()
    }();
</script>