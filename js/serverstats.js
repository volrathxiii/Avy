/**
 * 
 */
var $info = false;
function LoadServerStats() {
    $.getJSON("phpsysinfo/xml.php?plugin=complete&json",
            function (data) {
                $info = data;
                updateCPU($info.Vitals["@attributes"].LoadAvg, $info.Vitals["@attributes"].Processes);
                updateRAM($info.Memory["@attributes"].Percent);
                updateHDD($info.FileSystem.Mount[0]["@attributes"].Percent);
                /** OTHER INFO **/
                updateIP($info.Network.NetDevice);
                updateUptime($info.Vitals["@attributes"].Uptime);
                updateUSB($info.Hardware.USB.Device);
            });
    setTimeout(LoadServerStats, 10000);
}
function updateUSB(devices) {
    var $html = "";
    $.each(devices, function (i, val) {
        $html += '<div class="row"><span class="value">' + val['@attributes'].Name + '</span></div>';
    });
    $('.other-info-stats .usb-devices .usb-list').html($html);
}
function updateUptime(value) {
    var d = new Date(0, 0, 0, 0, 0, value);

    var s = d.getSeconds();
    var m = d.getMinutes();
    var h = d.getHours();
    var D = d.getDate();
    var M = d.getMonth();
    var Y = d.getYear();
    var formated = '';
    if (Y > 0)
        formated += ' ' + Y + 'Y'
    if (M > 0)
        formated += ' ' + M + 'M'
    if (D > 0)
        formated += ' ' + D + 'D'
    if (h > 0)
        formated += ' ' + h + 'h'
    if (m > 0)
        formated += ' ' + m + 'm'
    if (s > 0)
        formated += ' ' + s + 's'
    $('.other-info-stats .uptime span.value').html(formated);
}
function updateIP(value)
{
    var ipadd = ""
    if ($.isArray(value)) {
        $.each(value, function (i, val) {
            var list = val['@attributes'].Info;
            var arr = list.split(';');
            ipadd += ' ' + arr[1];
        });
    } else {
        var list = value['@attributes'].Info;
        var arr = list.split(';');
        ipadd += ' ' + arr[1];
    }
    $('.other-info-stats .ip-address span.value').html(ipadd);
}
function updateHDD(value)
{
    $('.usage .disk .progress-bar').css('width', value + '%');
    $('.usage .disk .progress-bar span.value').html(value + '%');
}
function updateRAM(value)
{
    $('.ram.chart').data('easyPieChart').update(Math.round(value));
}
function updateCPU(loadavg, processesCount)
{
    var data = loadavg;
    var arr = data.split(' ');
    var $value = (arr[0] / processesCount) * 100;
    $('.cpu.chart').data('easyPieChart').update(Math.round($value));
}