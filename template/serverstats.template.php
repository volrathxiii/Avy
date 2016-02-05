            <script>
                $(document).ready(function () {
                    $('.cpu.chart').easyPieChart({
                        barColor: '#39FF14',
                        trackColor: '#054015',
                        onStep: function (from, to, percent) {
                            $(this.el).find('.percent').text(Math.round(percent));
                        }
                    });
                    $('.ram.chart').easyPieChart({
                        barColor: '#39FF14',
                        trackColor: '#054015',
                        onStep: function (from, to, percent) {
                            $(this.el).find('.percent').text(Math.round(percent));
                        }
                    });
                    LoadServerStats();
                });
            </script>
            <div class="content-wrapper">
                <div class="server-data row"></div>
                <div class="server-stats col-xs-12 col-sm-8">
                    <div class="cpu usage col-xs-6">
                        <div class="chartbox">
                            <span class="cpu chart" data-percent="0">
                                <span class="percent-wrapper"><span class="percent"></span>%</span>
                                <span class="chart-name">CPU</span>
                            </span>
                        </div>
                    </div>
                    <div class="ram usage col-xs-6">
                        <div class="chartbox">
                            <span class="ram chart" data-percent="0">
                                <span class="percent-wrapper"><span class="percent"></span>%</span>
                                <span class="chart-name">RAM</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="other-stats col-xs-12 col-sm-4">
                    <div class="other usage col-xs-12">
                        <div class="chartbox">
                            <div class="disk">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        <span class="value">0%</span>
                                    </div>
                                    <span class="label">DISK</span>
                                </div>
                            </div>
                            <div class="other-info-stats row">
                                <div class="row ip-address"><span class="label">IP</span><span class="value">0.0.0.0</span></div>
                                <div class="row uptime"><span class="label">Uptime</span><span class="value">3h</span></div>
                                <div class="usb-devices">
                                    <div class="row"><span class="label">USB Devices</span></div>
                                    <div class="usb-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
