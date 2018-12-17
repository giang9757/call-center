<?php
	$this->assign('title', 'Call log');
	echo $this->Html->charset();
    echo $this->Html->css(['callcenter', 'components']);
?>
<?php echo $this->Flash->render() ?>
<div class="content-bar">
    <div class="pull-right">
    	<?= $this->Html->link(
    		'Week',
    		[
                'action' => 'report', 'week'
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
                'action' => 'report', 'month'
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
                'action' => 'report', 'year'
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
    <div class="row">
        <?php 
        $total = $statistic['callin']['total']+$statistic['callout']['total'];
        $cancel = $statistic['callin']['cancel']+$statistic['callout']['cancel'];
        ?>
    	<div class="col-lg-3 col-md-6">
    		<div class="panel zoom-element" zoom-index="callin">
    			<div class="panel-body">
    				<p>
                        <small class="text-muted pull-right"><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
    					<span class="fa-2x"><?= number_format($statistic['callin']['total']) ?></span><br>
    					<small class="text-muted">受信</small>
    				</p>
    				<span id="spark-callin"></span>
    			</div>
    			<div class="progress progress-xs no-margin">
                  <div class="progress-bar progress-bar-info" style="width:<?= $statistic['callin']['total']*100/$total ?>%" title="<?= number_format($statistic['callin']['total']).'/'.number_format($total) ?>"></div>
                </div>
    		</div>
    	</div>
    	<div class="col-lg-3 col-md-6">
    		<div class="panel zoom-element" zoom-index="callout">
    			<div class="panel-body">
    				<p>
                        <small class="text-muted pull-right"><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
                        
    					<span class="fa-2x"><?= number_format($statistic['callout']['total']) ?></span><br>
    					<small class="text-muted">発信</small>
    				</p>
    				<span id="spark-callout"></span>
    			</div>
    			<div class="progress progress-xs no-margin">
                  <div class="progress-bar progress-bar-success" style="width:<?= $statistic['callout']['total']*100/$total ?>%" title="<?= number_format($statistic['callout']['total']).'/'.number_format($total) ?>"></div>
                </div>
    		</div>
    	</div>
    	<div class="col-lg-3 col-md-6">
    		<div class="panel zoom-element" zoom-index="cancel">
    			<div class="panel-body">
    				<p>
                        <small class="text-muted pull-right"><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
                        
    					<span class="fa-2x"><?= number_format($cancel) ?></span><br>
    					<small class="text-muted">キャンセル</small>
    				</p>
    				<span id="spark-cancel"></span>
    			</div>
    			<div class="progress progress-xs no-margin">
                  <div class="progress-bar progress-bar-danger" style="width:<?= $cancel*100/$total ?>%" title="<?= number_format($cancel).'/'.number_format($total) ?>"></div>
                </div>
    		</div>
    	</div>
    	<div class="col-lg-3 col-md-6">
    		<div class="panel zoom-element" zoom-index="busy">
    			<div class="panel-body">
    				<p>
                        <small class="text-muted pull-right"><?= $startDate.($endDate==$startDate?'':' <i class="fa fa-long-arrow-right"></i> '.$endDate) ?></small>
                        
    					<span class="fa-2x"><?= number_format($statistic['callin']['busy']) ?></span><br>
    					<small class="text-muted">ビジー</small>
    				</p>
    				<span id="spark-busy"></span>
    			</div>
    			<div class="progress progress-xs no-margin">
                  <div class="progress-bar progress-bar-warning" style="width:<?= $statistic['callin']['busy']*100/$total ?>%" title="<?= number_format($statistic['callin']['busy']).'/'.number_format($total) ?>"></div>
                </div>
    		</div>
    	</div>
    </div>
    <div class="panel" data-fill-color="true">
        <div class="panel-heading">
            <div class="panel-control pull-right">
                <a href="#call-report" class="btn btn-icon big" data-toggle="collapse" title="collapse" aria-expanded="true"><i class="fa fa-minus-square"></i></a>
            </div>
            <h3 class="panel-title">
                <big>
                    <i class="fa fa-bar-chart"></i>&nbsp;
                    <span class="upcase-text">Call overview</span>
                    <small>&nbsp;コールレポート</small>
                </big>
            </h3>
        </div>
        <div class="panel-body show" id="call-report">
            <h4 class="title">
                <span class="upcase-text">Call-in</span>
                <small>&nbsp;受信</small>
            </h4>
            <div class="row mb-2x">
                <div class="col-md-8 col-md-push-4">
                    <div class="text-right text-muted">
                        <small class="mr-2x"><i class="color _blue fa fa-circle"></i> 応答</small>
                        <small class="cmr-2x"><i class="color _red fa fa-circle"></i> キャンセル</small>
                        <small class="mr-2x"><i class="color _yellow fa fa-circle"></i> ビジー</small>
                    </div>
                    <div id="morris-bar-callin" class="morris-chart mb-4x"></div>
                </div>
                <div class="col-md-4 col-md-pull-8">
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callin']['accept']) ?>件</span>
                        <span>応答総数</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-info" style="width:<?= $statistic['callin']['accept']*100/$statistic['callin']['total'] ?>%"></div>
                    </div>
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callin']['cancel']) ?>件</span>
                        <span>キャンセル総数</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-danger" style="width:<?= $statistic['callin']['cancel']*100/$statistic['callin']['total'] ?>%"></div>
                    </div>
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callin']['busy']) ?>件</span>
                        <span>ビジー総数</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-warning" style="width:<?= $statistic['callin']['busy']*100/$statistic['callin']['total'] ?>%"></div>
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="title">
                <span class="upcase-text">Call-out</span>
                <small>&nbsp;発信</small>
            </h4>
            <div class="row mb-2x">
                <div class="col-md-8 col-md-push-4">
                    <div class="text-right text-muted">
                        <small class="mr-2x"><i class="color _green fa fa-circle"></i> 応答</small>
                        <small class="cmr-2x"><i class="color _red fa fa-circle"></i> キャンセル</small>
                    </div>
                    <div id="morris-bar-callout" class="morris-chart mb-4x"></div>
                </div>
                <div class="col-md-4 col-md-pull-8">
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callout']['accept']) ?>件</span>
                        <span>応答総数</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" style="width:<?= $statistic['callout']['accept']*100/$statistic['callout']['total'] ?>%"></div>
                    </div>
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callout']['cancel']) ?>件</span>
                        <span>キャンセル総数</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-danger" style="width:<?= $statistic['callout']['cancel']*100/$statistic['callout']['total'] ?>%"></div>
                    </div>
                </div>
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
                    <i class="fa fa-line-chart"></i>&nbsp;
                    <span class="upcase-text">Time overview</span>
                    <small>&nbsp;タイムレポート</small>
                </big>
            </h3>
        </div>
        <div class="panel-body show" id="time-report">
            <div class="row mb-2x">
                <div class="col-md-8 col-md-push-4">
                    <div class="text-right text-muted">
                        <small class="mr-2x"><i class="color _blue fa fa-circle"></i> 受信</small>
                        <small class="cmr-2x"><i class="color _green fa fa-circle"></i> 発信</small>
                    </div>
                    <div id="morris-line-time" class="morris-chart mb-4x"></div>
                </div>
                <?php
                    $total = $statistic['callin']['time'] + $statistic['callout']['time'];
                ?>
                <div class="col-md-4 col-md-pull-8">
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callin']['time']) ?>秒</span>
                        <span>受信タイム</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-info" style="width:<?= $statistic['callin']['time']*100/$total ?>%"></div>
                    </div>
                    <div class="help-block">
                        <span class="pull-right"><?= number_format($statistic['callout']['time']) ?>秒</span>
                        <span>発信タイム</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" style="width:<?= $statistic['callout']['time']*100/$total ?>%"></div>
                    </div>
                </div>
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
<!-- morris graph -->
<script type="text/javascript">
    var callIn = Morris.Bar({
        element: 'morris-bar-callin',
        fillOpacity: 0.1,
        data: [
            <?php
            foreach ($logData as $key => $value) {
                echo '{date: \''.$value['date'].
                    '\', accept: '.$value['callin']['accept'].
                    ', cancel: '.$value['callin']['cancel'].
                    ', busy: '.$value['callin']['busy'].
                    '},';
            }
            ?>
        ],
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
        ykeys: ['accept', 'cancel', 'busy'],
        labels: ['応答', 'キャンセル', 'ビジー'],
        pointSize: 0,
        hideHover: true,
        barColors: ['#5bc0de', '#ee502a', '#ffe062'],
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    var callOut = Morris.Bar({
        element: 'morris-bar-callout',
        fillOpacity: 0.1,
        data: [
            <?php
            foreach ($logData as $key => $value) {
                echo '{date: \''.$value['date'].
                    '\', accept: '.$value['callin']['accept'].
                    ', cancel: '.$value['callin']['cancel'].
                    '},';
            }
            ?>
        ],
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
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
        ykeys: ['accept', 'cancel'],
        labels: ['応答', 'キャンセル'],
        pointSize: 0,
        hideHover: true,
        barColors: ['#5cb85c', '#ee502a'],
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });
    var callin = Morris.Bar({
        element: 'morris-line-time',
        fillOpacity: 0.1,
        data: [
            <?php
            foreach ($logData as $key => $value) {
                echo '{date: \''.str_replace('/', '-', $value['date']).
                    '\', callin: '.$value['callin']['time'].
                    ', callout: '.$value['callout']['time'].
                    '},';
            }
            ?>
            
        ],
        xkey: 'date',
        xLabelFormat: function (x) {
            x = x.src.date;
            x = new Date(x);
            var IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
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
        barColors: ['#5bc0de', '#5cb85c'],
        grid: false,
        gridTextColor: '#454b56',
        resize: true
    });

</script>
<!-- end morris graph -->
<!-- spark graph -->
<script type="text/javascript">
    loadSpark('callin', 'small');
    loadSpark('callout', 'small');
    loadSpark('cancel', 'small');
    loadSpark('busy', 'small');
    function loadSpark(id, type) {
        var value = [],
            name = '',
            color = '';
        var date = [
            <?php
            foreach ($logData as $key => $value) {
                echo '\''.$value['date'].'\',';
            }
            ?>
        ];

        switch(id) {
            case 'callin':
                value = [
                    <?php
                    foreach ($logData as $key => $value) {
                        echo $value['callin']['accept']+$value['callin']['cancel']+$value['callin']['busy'].',';
                    }
                    ?>
                    ];
                name = '受信';
                color = '#5bc0de';
                break;
            case 'callout':
                value = [
                    <?php
                    foreach ($logData as $key => $value) {
                        echo $value['callout']['accept']+$value['callout']['cancel'].',';
                    }
                    ?>
                    ];
                name = '発信';
                color = '#5cb85c';
                break;
            case 'cancel':
                value = [
                    <?php
                    foreach ($logData as $key => $value) {
                        echo $value['callin']['cancel']+$value['callout']['cancel'].',';
                    }
                    ?>
                    ];
                name = 'キャンセル';
                color = '#ee502a';
                break;
            case 'busy':
                value = [
                    <?php
                    foreach ($logData as $key => $value) {
                        echo $value['callin']['busy'].',';
                    }
                    ?>
                    ];
                name = 'ビジー';
                color = '#ffe062';
                break;
            default:
                break;
        }
        if(type=='small') {
            $('#spark-'+id).sparkline(
                value,
                { type: 'bar', barColor: color, height: 32, barWidth: 6 }
            )
        }
        else {
            var data = [];
            for(var i = 0; i < value.length; i++) {
                data.push({date:date[i], value:value[i]});
            }
            var spark = Morris.Bar({
                element: 'zoom-morris',
                fillOpacity: 0.1,
                data: data,
                xkey: 'date',
                xLabelFormat: function (x) {
                    x = x.src.date;
                    x = new Date(x);
                    var IndexToWeek = ['日', '月', '火', '水', '木', '金', '土'],
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
                ykeys: ['value'],
                labels: [name],
                pointSize: 0,
                hideHover: true,
                barColors: [color],
                grid: false,
                gridTextColor: '#454b56',
                resize: true
            });
        }
    }
	
</script>
<!-- end spark graph -->
<script type="text/javascript">
    $('.zoom-element').click(function(){
        var index = $(this).attr('zoom-index');
        $('#zoom-panel').removeClass('hide');
        loadSpark(index, 'big');
    })
    $('#zoom-panel').click(function(){
        $('#zoom-morris')[0].innerHTML = '';
        $(this).addClass('hide');
    })
    
</script>