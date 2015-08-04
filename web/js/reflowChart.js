$(function() {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var chart = $("#chartcontainer").highcharts();
        chart.reflow();
    })
});
