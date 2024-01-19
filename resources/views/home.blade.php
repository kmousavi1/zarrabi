@extends('layouts.app')

@section('content')


<div class="tab">

<div class="tab-heading">
    <span class="active">Live Charts</span>
    <span>Historical Chart</span>
</div>

<div class="tab-content" >

<div id="live_charts">
    <h3 style="margin: 50px 20px">Live Charts</h3>
    <div style="display: flex">
        <canvas id="line-chart1" class="chart chart1"></canvas>
        <canvas id="line-chart2" class="chart chart2"></canvas>
        <canvas id="line-chart3" class="chart chart3"></canvas>
    </div>
</div>



<div id="historical_charts">
    <h3 style="margin: 50px 20px">Historical Chart</h3>
    <div style="display: flex">
        <canvas id="line-chart4" class="chart chart4"></canvas>
        <canvas id="line-chart5" class="chart chart5"></canvas>
        <canvas id="line-chart6" class="chart chart6"></canvas>
    </div>
</div>


</div>

</div>








<script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>

<script>


var status;

$(document).ready(function () {

$(".tab-content div").eq(0).show();
status="live";
all(status);
$(".tab-heading span").click(function (){
    $(this).addClass("active").css({opacity:1}).siblings().removeClass("active").css({opacity: 0.3})
    var index=$(this).index();
    if (index==0) {
        $('#live_charts').show();
        $('#historical_charts').hide();
        status="live";
        all(status);
    }else if (index==1) {
        $('#live_charts').hide();
        $('#historical_charts').show();
        status="history";
        all(status);
    }
})





})



//
// $(document).ready(function () {
//     $(".tab-content div").eq(0).show();
//     $(".tab-heading span").click(function (){
//         $(this).addClass("active").css({opacity:1}).siblings().removeClass("active").css({opacity: 0.3})
//         var index=$(this).index();
//         if (index==0) {
//             $('#live_charts').show();
//             $('#historical_charts').hide();
//             status='live';
//         }else if (index==1) {
//             $('#live_charts').hide();
//             $('#historical_charts').show();
//             status='history';
//         }
//     })
// })


// setInterval(all, 5000);








function all(status) {


console.log(status);

var datetime_start='2024-01-11*00:00:00';
var datetime_end='2024-01-14*00:00:00';
var url_live='display_data_live/';
var url_history='display_data_history/'+datetime_start+'/'+datetime_end;
var current_url;
if(status=="history") { current_url=url_history; } else{ current_url=url_live; }

    console.log(current_url);

    $.ajax({
        url: current_url,
        method: "get",
        success: function(response) {
            response = JSON.parse(response)
            console.log(response)

        }
    })
}


x_data=[ 1500, 1600, 1700, 1750, 1800, 1850, 1900, 1950, 1999, 2050 ];
y_data=[ 186, 205, 1321, 1516, 2107, 2191, 3133, 3221, 4783, 5487 ];
chart_disply("line-chart1" , x_data , y_data)
chart_disply("line-chart2" , x_data , y_data)
chart_disply("line-chart3" , x_data , y_data)
chart_disply("line-chart4" , x_data , y_data)
chart_disply("line-chart5" , x_data , y_data)
chart_disply("line-chart6" , x_data , y_data)



function chart_disply(chart_id , x_data , y_data) {

    new Chart(document.getElementById(chart_id), {
        type : 'line',
        data : {
            labels : x_data,
            datasets : [
                {
                    data : y_data,
                    label : "America",
                    borderColor : "#3cba9f",
                    fill : false
                }]
        },
        options : {
            title : {
                display : true,
                text : 'Chart JS Line Chart Example'
            }
        }
    });

}


</script>






















@endsection




