
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

                                  series.addPoint([x,data.temper], false, true);  //x�ð��뿡, y���� �߰� ���ִ°���
                                  series2.addPoint([x, data.humi], true, true);
									
                        		}
                        	});
                        }, 3000); //settimeout 1��
                    }
                }
            },
            title: {
                text: 'Temperature/Huminity'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150   //x�� �ð�  ��Ÿ���� ���� �ð�
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
                        Highcharts.numberFormat(this.y, 2);  //���콺 �÷��� �� ������ ���� â
                }
            },
            legend: {
                enabled: true  //�ؿ�, �� ��Ʈ�� �̸� ���̰� �Ұ���
            },
            exporting: {
                enabled: true //������ܿ� ���� �� ������ �Ұ���
            },
            series: [{					//������ �� �ʱ�ȭ
                name: 'Temperature',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
 
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,  //x�� �ð� 
                            y: Math.random()	//��Ʈ ������ �� ó�� ������ �ӽ� ������ ����
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
                            y: Math.random()  //��Ʈ ������ �� ó�� ������ �ӽ� ������ ����
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