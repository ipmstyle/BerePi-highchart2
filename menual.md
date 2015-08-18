# 온,습도 센서를 이용한 highchart(실시간 추가)
- MySQL 버전 - 5.5.44
- Apache 버전 - 2.2.22
- php 버전 - 5.4.41


---

#### 센서 이용 - BerePi
BerePi 센서 모듈은 온습도, CO2, CO, DUST 등의 센서 등으로 이용 가능

![BerePi 사진1](http://postfiles3.naver.net/20150817_130/jiy5520_1439797601447OpzL2_PNG/rasberiPi1.png?type=w3)
![BerePi 사진2](http://postfiles3.naver.net/20150817_258/jiy5520_1439797602115txj5n_JPEG/rasberiPi2.jpg?type=w3)

**설치**-
BerePi 사용을 위한 OS img파일을 아래 주소에서 다운.
<https://github.com/jeonghoonkang/BerePi/blob/master/Install_Raspi_OS.md>

img파일을 Win32DiskImager를 이용하여 SD카드에 구우면 됨.

- **BerePi 소스 디렉토리** - /home/pi/devel/BerePi/apps

- **온,습도 센서 실행 소스** - /home/pi/devel/BerePi/apps/sht20/sht20.py

- **온,습도 센서 실행** - python sht20.py

#### 실행 결과
![sth20.py 실행 결과](http://blogfiles.naver.net/20150817_272/jiy5520_1439799896410Eusem_PNG/result1.png)

---

ajax로 실시간 데이터 값을 전송할 수 있게 json형식을 추가.

![json부분](http://postfiles7.naver.net/20150818_198/jiy5520_1439861735370r9NMW_PNG/json.png?type=w3)

**웹 데이터 전송부분**

![json부분](http://postfiles4.naver.net/20150818_259/jiy5520_1439861735133CbE0y_PNG/ajax.png?type=w3)

**highchart.php**
```sh
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
```

**결과 확인 창**

![DB값 확인](http://postfiles7.naver.net/20150818_278/jiy5520_1439861735704h8NLs_PNG/RESULT.png?type=w3)
