<?php
	$this->assign('title', 'Report by employee');
	echo $this->Html->charset();
    echo $this->Html->css(['callcenter', 'components']);
    $colors = ['#5FC0DD','#FEEB9E','#FD8720','#FF0000','#FFFF46','#46FF46','#6CFFFF','#1A1AD8','#FF8612'];
    for($i = 0; $i < count($logData); $i++) {
        array_push($colors, 'rgba('.rand(50,250).', '.rand(50,250).', '.rand(50,250).', 1)');
    }
?>
<?php echo $this->Flash->render() ?>
<div class="content-bar">
    <div class="pull-right">
    	<?= $this->Html->link(
    		'Week',
    		[
                'action' => 'reportByEmployee', 'week'
            ],
    		[
    			'type' => 'week',
    			'class' => 'btn btn-default _set-date'.($statisticBy=='week'?' focus':'')
    		]
    	)
    	?>
    	<?= $this->Html->link(
    		'Month',
            [
                'action' => 'reportByEmployee', 'month'
            ],
    		[
    			'type' => 'month',
    			'class' => 'btn btn-default _set-date'.($statisticBy=='month'?' focus':'')
    		]
    	)
    	?>
    	<?= $this->Html->link(
    		'Year',
            [
                'action' => 'reportByEmployee', 'year'
            ],
    		[
    			'type' => 'year',
    			'class' => 'btn btn-default _set-date'.($statisticBy=='year'?' focus':'')
    		]
    	)
    	?>
    </div>
    <h2><span>REPORT</span> レポート</h2>
</div>
<div class="content">
    <div class="panel" data-fill-color="true">
        <div class="panel-heading">
            <div class="panel-control pull-right">
                <a href="#call-report" class="btn btn-icon big" data-toggle="collapse" title="collapse" aria-expanded="true"><i class="fa fa-minus-square"></i></a>
            </div>
            <h3 class="panel-title">
                <big>
                    <i class="fa fa-phone"></i>&nbsp;
                    <span class="upcase-text">Call overview</span>
                    <small>&nbsp;コールレポート</small>
                </big>
            </h3>
        </div>
    </div>
    <div class="show" id="call-report">
        <div class="row">
            <?php
            if(count($logData)) {
                $col = count($logData)<5?12/count($logData):3;
                foreach ($logData as $key => $data) {
                    $callNo = $data['statistic']['callin']['call'] + $data['statistic']['callout']['call'];
                    $total = $statistic['callin']['call'] + $statistic['callout']['call'];
                    ?>
                    <div class="col-lg-<?= $col ?> col-md-6">
                        <div class="panel media zoom-element" zoom-index="<?= $key ?>" zoom-type="call">
                            <div class="panel-body">
                                <div class="media-left">
                                    <?= $this->Html->image(
                                        'user/'.$data['employee']['User__image'],
                                        [
                                            'class' => 'status-icon _circle'
                                        ]
                                    )
                                    ?>
                                </div>
                                <div class="media-body">
                                    <p>
                                        <span class="text-muted pull-right">
                                            <small class="mr-2x"><i class="color _blue fa fa-circle"></i> 受信</small>
                                            <small class="mr-2x"><i class="color _yellow fa fa-circle"></i> 発信</small><br>
                                            <small><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
                                        </span>
                                        <span class="fa-2x">
                                            <?= number_format($callNo) ?><span class="x-small">件</span>
                                        </span><br>
                                        <small class="text-muted"><?= $data['employee']['User__name'] ?></small>
                                    </p>
                                </div>
                                <span id="spark-call-<?= $key ?>"></span>
                            </div>
                            <div class="progress progress-xs no-margin">
                                <div class="progress-bar progress-bar-primary" style="width:<?= $callNo*100/$total ?>%" title="<?= number_format($callNo).'/'.number_format($total) ?>"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <h4 class="title">
                    <span class="upcase-text">Call-in</span>
                    <small>&nbsp;受信コール</small>
                </h4>
            </div>
            <div class="panel-body">
                <div class="row mb-2x">
                    <div id="morris-call-callin" class="morris-chart mb-4x"></div>
                </div>
                <hr>
                <div class="row">
                    <?php
                    if(count($logData)) {
                        foreach ($logData as $key => $data) {
                            if($statistic['callin']['call']==0){
                                $percent=0;
                            }else{
                                $percent = round($data['statistic']['callin']['time']*100/$statistic['callin']['time']);
                            }
                           
                            ?>
                            <div class="col-md-2 col-sm-6 mb-4x">
                                <div class="help-block text-center">
                                    <div class="easyPieChart" data-percent="<?= $percent ?>" data-size="150"  data-line-width="15" data-scale-color="false"  data-bar-color="<?= $colors[$key] ?>">
                                        <span class="percentage text-dark">
                                            <?= number_format($data['statistic']['callin']['call']) ?><small>/<?= number_format($statistic['callin']['call']) ?></small>
                                        </span>
                                    </div>
                                </div>
                                <div class="help-block text-center pl-2x pr-2x">
                                    <h4><?= $data['employee']['User__name'] ?></h4>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <h4 class="title">
                    <span class="upcase-text">Call-out</span>
                    <small>&nbsp;発信コール</small>
                </h4>
            </div>
            <div class="panel-body">
                <div class="row mb-2x">
                    <div id="morris-call-callout" class="morris-chart mb-4x"></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?php
                if(count($logData)) {
                    foreach ($logData as $key => $data) {
                        if($statistic['callout']['call']!=0){
                            $percent = round($data['statistic']['callout']['call']*100/$statistic['callout']['call']);
                        }else{
                             $percent=0;
                        }
                        
                        ?>
                        <div class="col-md-2 col-sm-6 mb-4x">
                            <div class="help-block text-center">
                                <div class="easyPieChart" data-percent="<?= $percent ?>" data-size="150"  data-line-width="15" data-scale-color="false"  data-bar-color="<?= $colors[$key] ?>">
                                    <span class="percentage text-dark fa">
                                        <?= number_format($data['statistic']['callout']['call']) ?><small>/<?= number_format($statistic['callout']['call']) ?></small>
                                    </span>
                                </div>
                            </div>
                            <div class="help-block text-center pl-2x pr-2x">
                                <h4><?= $data['employee']['User__name'] ?></h4>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="panel" data-fill-color="true">
        <div class="panel-heading">
            <div class="panel-control pull-right">
                <a href="#time-report" class="btn btn-icon big" data-toggle="collapse" title="collapse" aria-expanded="true"><i class="fa fa-minus-square"></i></a>
            </div>
            <h3 class="panel-title">
                <big>
                    <i class="fa fa-clock-o"></i>&nbsp;
                    <span class="upcase-text">Time overview</span>
                    <small>&nbsp;タイムレポート</small>
                </big>
            </h3>
        </div>
    </div>
    <div class="show" id="time-report">
        <div class="row">
            <?php
            if(count($logData)) {
                $col = count($logData)<5?12/count($logData):3;
                foreach ($logData as $key => $data) {
                    $timeNo = $data['statistic']['callin']['time'] + $data['statistic']['callout']['time'];
                    $total = $statistic['callin']['time'] + $statistic['callout']['time'];
                    ?>
                    <div class="col-lg-<?= $col ?> col-md-6">
                        <div class="panel media zoom-element" zoom-index="<?= $key ?>" zoom-type="time">
                            <div class="panel-body">
                                <div class="media-left">
                                    <?= $this->Html->image(
                                        'user/'.$data['employee']['User__image'],
                                        [
                                            'class' => 'status-icon _circle'
                                        ]
                                    )
                                    ?>
                                </div>
                                <div class="media-body">
                                    <p>
                                        <span class="text-muted pull-right">
                                            <small class="mr-2x"><i class="color _blue fa fa-circle"></i> 受信</small>
                                            <small class="mr-2x"><i class="color _yellow fa fa-circle"></i> 発信</small><br>
                                            <small><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
                                        </span>
                                        <span class="fa-2x">
                                            <?= number_format($timeNo) ?><span class="x-small">秒</span>
                                        </span><br>
                                        <small class="text-muted"><?= $data['employee']['User__name'] ?></small>
                                    </p>
                                </div>
                                <span id="spark-time-<?= $key ?>"></span>
                            </div>
                            <div class="progress progress-xs no-margin">
                                <div class="progress-bar progress-bar-primary" style="width:<?= $timeNo*100/$total ?>%" title="<?= number_format($timeNo).'/'.number_format($total) ?>"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <h4 class="title">
                    <span class="upcase-text">Call-in</span>
                    <small>&nbsp;受信タイム</small>
                </h4>
            </div>
            <div class="panel-body">
                <div class="row mb-2x">
                    <div id="morris-time-callin" class="morris-chart mb-4x"></div>
                </div>
                <hr>
                <div class="row">
                    <?php
                    if(count($logData)) {
                        foreach ($logData as $key => $data) {
                            if($statistic['callin']['time']==0){
                                $percent=0;
                            }else{
                                $percent = round($data['statistic']['callin']['time']*100/$statistic['callin']['time']);
                            }
                            
                            ?>
                            <div class="col-md-2 col-sm-6 mb-4x">
                                <div class="help-block text-center">
                                    <div class="easyPieChart" data-percent="<?= $percent ?>" data-size="150"  data-line-width="15" data-scale-color="false"  data-bar-color="<?= $colors[$key] ?>">
                                        <span class="percentage text-dark">
                                            <?= number_format($data['statistic']['callin']['time']) ?><small>/<?= number_format($statistic['callin']['time']) ?></small>
                                        </span>
                                    </div>
                                </div>
                                <div class="help-block text-center pl-2x pr-2x">
                                    <h4><?= $data['employee']['User__name'] ?></h4>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <h4 class="title">
                    <span class="upcase-text">Call-out</span>
                    <small>&nbsp;発信タイム</small>
                </h4>
            </div>
            <div class="panel-body">
                <div class="row mb-2x">
                    <div id="morris-time-callout" class="morris-chart mb-4x"></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?php
                if(count($logData)) {
                    foreach ($logData as $key => $data) {
                        if($statistic['callout']['time']!=0){
                            $percent = round($data['statistic']['callout']['time']*100/$statistic['callout']['time']);
                        }else{
                             $percent =0;
                        }
                       
                        ?>
                        <div class="col-md-2 col-sm-6 mb-4x">
                            <div class="help-block text-center">
                                <div class="easyPieChart" data-percent="<?= $percent ?>" data-size="150"  data-line-width="15" data-scale-color="false" data-bar-color="<?= $colors[$key] ?>">
                                    <span class="percentage text-dark fa">
                                        <?= number_format($data['statistic']['callout']['time']) ?><small>/<?= number_format($statistic['callout']['time']) ?></small>
                                    </span>
                                </div>
                            </div>
                            <div class="help-block text-center pl-2x pr-2x">
                                <h4><?= $data['employee']['User__name'] ?></h4>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-expanded hide" id="zoom-panel">
    <div class="panel-body">
        <div id="zoom-morris" class="morris-chart mb-4x"></div>
    </div>
</div>
<?= $this->Html->script(['twilio.min', 'morris.bundle', 'jquery.sparkline', 'jquery.easypiechart']) ?>
<!-- spark graph -->
<script type="text/javascript">
    var colors = <?= json_encode($colors) ?>;
    var data = [];
    <?php
    foreach ($logData as $key => $data) {
        $data = $data['data'];
        echo 'data['.$key.']=[';
        foreach ($data as $k => $value) {
            echo'{date:\''.$k.
                '\',callin:{call:'.$value['callin']['call'].
                ',time:'.$value['callin']['time'].
                '}, callout:{call:'.$value['callout']['call'].
                ', time:'.$value['callout']['time'].
                '}},';
        }
        echo '];';
        ?>
        loadSpark(<?= $key ?>,'call', 'small');
        loadSpark(<?= $key ?>,'time', 'small');
        <?php
    }
    ?>
    // spark graph
    function loadSpark(id, type, size) {
        if(size=='small') {
            value = {callin:[], callout:[]};
            for(var i = 0; i < data[id].length; i++) {
                value.callin.push(data[id][i]['callin'][type]);
                value.callout.push(data[id][i]['callout'][type]);
            }
            $('#spark-'+type+'-'+id).sparkline(
                value.callout,
                { type: 'line', fillColor: false, lineColor: '#ffe062', width: '90%', height: '32' }
            );
            $('#spark-'+type+'-'+id).sparkline(
                value.callin,
                { type: 'line', fillColor: false, lineColor: '#5bc0de', width: '90%', height: '32', composite: true }
            );
        }
        else {
            value = [];
            for(var i = 0 ; i < data[id].length; i++) {
                value.push({
                    date: data[id][i]['date'],
                    callin: data[id][i]['callin'][type],
                    callout: data[id][i]['callout'][type]
                });
            }
            var morris = Morris.Bar({
                element: 'zoom-morris',
                fillOpacity: 0.1,
                data: value,
                xkey: 'date',
                xLabelFormat: function (x) {
                    x = x.src.date;
                    x = new Date(x);
                    var IndexToMonth = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                    IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
                    day = IndexToWeek[x.getDay()],
                    month = IndexToMonth[x.getMonth()],
                    day = IndexToWeek[x.getDay()],
                    month = x.getMonth()+1,
                    date = x.getDate(),
                    year = x.getFullYear();
                    return <?php
                        switch ($statisticBy) {
                            case 'month':
                                echo 'month+"/"+date';
                                break;
                            case 'year':
                                echo 'month+"月"';
                                break;
                            default:
                                echo 'day';
                                break;
                        }
                    ?>;
                },
                ykeys: ['callin', 'callout'],
                labels: ['受信', '発信'],
                pointSize: 0,
                hideHover: true,
                barColors: ['#5bc0de', '#ffe062'],
                grid: false,
                gridTextColor: '#454b56',
                resize: true
            });
        }
    }
    // morris graph
    var keys = [],
        labels = [];
    <?php
    $array = [];
    foreach ($logData as $key => $data) {
        ?>
        keys.push('<?= $key ?>');
        labels.push('<?= $data['employee']['User__name'] ?>');
        <?php
        foreach ($data['data'] as $k => $value) {
            $array[$k][$key] = [
                'call' => [
                    'callin' => $value['callin']['call'],
                    'callout' => $value['callout']['call']
                ],
                'time' => [
                    'callin' => $value['callin']['time'],
                    'callout' => $value['callout']['time']
                ],
            ];
        }
    }
    ?>
    var array = [
    <?php
    foreach ($array as $key => $value) {
        echo '{date:\''.$key.'\', ';
        foreach ($value as $k => $v) {
            echo $k.':\''.$v['call']['callin'].'\',';
        }
        echo '},';
    }
    ?>
    ];
	var callIn = Morris.Bar({
        element: 'morris-call-callin',
        fillOpacity: 0.1,
        data: array,
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToMonth = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            IndexToWeek =['日', '月', '火', '水', '木', '金', '土'],
            day = IndexToWeek[x.getDay()],
            month = IndexToMonth[x.getMonth()],
            day = IndexToWeek[x.getDay()],
            month = x.getMonth()+1,
            date = x.getDate(),
            year = x.getFullYear();
            return <?php
                switch ($statisticBy) {
                    case 'month':
                        echo 'month+"/"+date';
                        break;
                    case 'year':
                        echo 'month+"月"';
                        break;
                    default:
                        echo 'day';
                        break;
                }
            ?>;
        },
        ykeys: keys,
        labels: labels,
        pointSize: 0,
        hideHover: true,
        barColors: colors,
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    array = [
    <?php
    foreach ($array as $key => $value) {
        echo '{date:\''.$key.'\', ';
        foreach ($value as $k => $v) {
            echo $k.':\''.$v['call']['callout'].'\',';
        }
        echo '},';
    }
    ?>
    ];
    var callIn = Morris.Bar({
        element: 'morris-call-callout',
        fillOpacity: 0.1,
        data: array,
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToMonth = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
            day = IndexToWeek[x.getDay()],
            month = IndexToMonth[x.getMonth()],
            day = IndexToWeek[x.getDay()],
            month = x.getMonth()+1,
            date = x.getDate(),
            year = x.getFullYear();
            return <?php
                switch ($statisticBy) {
                    case 'month':
                        echo 'month+"/"+date';
                        break;
                    case 'year':
                        echo 'month+"月"';
                        break;
                    default:
                        echo 'day';
                        break;
                }
            ?>;
        },
        ykeys: keys,
        labels: labels,
        pointSize: 0,
        hideHover: true,
        barColors: colors,
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    array = [
    <?php
    foreach ($array as $key => $value) {
        echo '{date:\''.$key.'\', ';
        foreach ($value as $k => $v) {
            echo $k.':\''.$v['time']['callin'].'\',';
        }
        echo '},';
    }
    ?>
    ];
    var callIn = Morris.Bar({
        element: 'morris-time-callin',
        fillOpacity: 0.1,
        data: array,
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToMonth = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            IndexToWeek =['日', '月', '火', '水', '木', '金', '土'],
            day = IndexToWeek[x.getDay()],
            month = IndexToMonth[x.getMonth()],
            day = IndexToWeek[x.getDay()],
            month = x.getMonth()+1,
            date = x.getDate(),
            year = x.getFullYear();
            return <?php
                switch ($statisticBy) {
                    case 'month':
                        echo 'month+"/"+date';
                        break;
                    case 'year':
                        echo 'month+"月"';
                        break;
                    default:
                        echo 'day';
                        break;
                }
            ?>;
        },
        ykeys: keys,
        labels: labels,
        pointSize: 0,
        hideHover: true,
        barColors: colors,
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    var array = [
    <?php
    foreach ($array as $key => $value) {
        echo '{date:\''.$key.'\', ';
        foreach ($value as $k => $v) {
            echo $k.':\''.$v['time']['callout'].'\',';
        }
        echo '},';
    }
    ?>
    ];
    var callIn = Morris.Bar({
        element: 'morris-time-callout',
        fillOpacity: 0.1,
        data: array,
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToMonth = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
            day = IndexToWeek[x.getDay()],
            month = IndexToMonth[x.getMonth()],
            day = IndexToWeek[x.getDay()],
            month = x.getMonth()+1,
            date = x.getDate(),
            year = x.getFullYear();
            return <?php
                switch ($statisticBy) {
                    case 'month':
                        echo 'month+"/"+date';
                        break;
                    case 'year':
                        echo 'month+"月"';
                        break;
                    default:
                        echo 'day';
                        break;
                }
            ?>;
        },
        ykeys: keys,
        labels: labels,
        pointSize: 0,
        hideHover: true,
        barColors: colors,
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    $('.easyPieChart').easyPieChart({
        onStep: function(from, to, currentValue) {
            $(this.el).find('.data-percent').text(currentValue.toFixed(0));
        },
        onStart: function() {
            var canvas = $(this.el).children('canvas'),
            size = canvas.height() + 'px';

            $(this.el).css({
                width: size,
                height: size,
                lineHeight: size
            });
        }
    });
</script>
<script type="text/javascript">
    $('.zoom-element').click(function(){
        var index = $(this).attr('zoom-index');
        var type = $(this).attr('zoom-type');
        $('#zoom-panel').removeClass('hide');
        loadSpark(index, type, 'big');
    })
    $('#zoom-panel').click(function(){
        $('#zoom-morris')[0].innerHTML = '';
        $(this).addClass('hide');
    })
    
</script>