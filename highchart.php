
<HTML>
<HEAD>
<TITLE>Crunchify - Dynamic Spline HighChart Example with
	Multiple Y Axis</TITLE>
<script type="text/javascript"
	src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>
$i=1;
$(function () {
    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
 
        var chart;
        $('#container').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function() {

                        // set up the updating of the chart each second
                        var series = this.series[0];  
                        var series2 = this.series[1];
                        setInterval(function() {

                        	$.ajax({
                        		url: "Temp,Humi2.py",
                        		data:$('form').serialize(), 
                        		type:'post',
                        		dataType: "json",
                        		success: function(data){

                        			  var x = (new Date()).getTime();

                                  series.addPoint([x,data.temper], false, true);  //x시간대에, y값을 추가 해주는거임
                                  series2.addPoint([x, data.humi], true, true);
									
                        		}
                        	});
                        }, 3000); //settimeout 1초
                    }
                }
            },
            title: {
                text: 'Temperature/Huminity'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150   //x축 시간  나타내는 일정 시간
            },
            yAxis: [{
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            {
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            }],
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);  //마우스 올렸을 때 나오는 작은 창
                }
            },
            legend: {
                enabled: true  //밑에, 각 차트의 이름 보이게 할건지
            },
            exporting: {
                enabled: true //우측상단에 도구 탭 나오게 할건지
            },
            series: [{					//데이터 값 초기화
                name: 'Temperature',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
 
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,  //x는 시간 
                            y: Math.random()	//차트 켜지면 맨 처음 찍히는 임시 데이터 값들
                        });
                    }
                    return data;
                })()
            },
                    {
                name: 'Huminity',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
 
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000, //
                            y: Math.random()  //차트 켜지면 맨 처음 찍히는 임시 데이터 값들
                        });
                    }
                    return data;
                })()
            }] 
        });
    });
 
});
</script>

</HEAD>
<BODY>
	<div id="container" style="min-width: 728px; height: 400px; margin: 0 auto"></div>
</BODY>
</HTML>