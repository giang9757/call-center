<?php
  	$this->assign('title', 'Payment');
  	echo $this->Html->charset();
  	echo $this->Html->css('payment');
  	if($phonenumbers['price_unit']=='jpy'){
  		$unit='&yen;';
  	}
  	else{
  		$unit='$';
  	}
  	$total = $recordings['price']+$phonenumbers['price']+$callsClient['price']+$voiceMinutes['price'];
?>
<div class="content-bar">
    <h2><span>PAYMENT</span> お支払い</h2>
</div>
<div class="content">
	<div class="row">
		<div class="col-md-4">
            <div class="pricing-table">
                <div class="pricing-header background _green">
                  	<h3 class="pricing-title">BASIC</h3>

                  	<p class="pricing-price">
                  		<sup>¥</sup>10,000 <small>/ MO</small>
                  	</p>
                </div>
                <ul class="pricing-features">
                  	<li><strong>3</strong> Callers</li>
                  	<li>Standard Support</li>
                  	<li>1 Phone Number</li>
                  	<li><strong>No</strong> Report</li>
                  	<li><strong>No</strong> CSV File</li>
                </ul>
                <div class="pricing-footer">
                  	<a href="#" class="button _green _small" dotted dot-color="red">申し込む</a>
                </div>
            </div>
        </div>
		<div class="col-md-4">
            <div class="pricing-table">
                <div class="pricing-header background _blue">
                  	<h3 class="pricing-title">SILVER</h3>
                  	<p class="pricing-price">
                  		<sup>¥</sup>15,000 <small>/ MO</small>
                  	</p>
                </div>
                <ul class="pricing-features">
                  	<li><strong>5</strong> Callers</li>
                  	<li>Standard Support</li>
                  	<li>1 Phone Number</li>
                  	<li><strong>Full</strong> Report</li>
                  	<li><strong>No</strong> CSV File</li>
                </ul>
                <div class="pricing-footer">
                  	<a href="#" class="button _blue _small">申し込む</a>
                </div>
            </div>
        </div>
		<div class="col-md-4">
            <div class="pricing-table">
                <div class="pricing-header background _yellow">
                  	<h3 class="pricing-title">GOLD</h3>
                  	<p class="pricing-price">
                  		<sup>¥</sup>20,000 <small>/ MO</small>
                  	</p>
                </div>
                <ul class="pricing-features">
                  	<li><strong>8</strong> Callers</li>
                  	<li>Standard Support</li>
                  	<li>1 Phone Number</li>
                  	<li><strong>Full</strong> Report</li>
                  	<li><strong>Full</strong> CSV File</li>
                </ul>
                <div class="pricing-footer">
                  	<a href="#" class="button _yellow _small">申し込む</a>
                </div>
            </div>
        </div>
	</div>
	<div class="panel">
		<div class="panel-heading">
            <h3 class="panel-title">
                <big>
                    <i class="fa fa-credit-card"></i>&nbsp;
                    <span class="upcase-text">payment</span>
                    <small>&nbsp;お支払い</small>
                </big>
            </h3>
        </div>
        <div class="panel-body">
			<p text-align="center">
				<span class="fa-3x"><sup>¥</sup><?= number_format($total) ?></span> for this month
			</p>
			<p text-align="center" id="loading-icon">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			</p>
			
			<div class="hide" id="payment-content">
				<hr>
				<h4 text-align="center">Payment Detail</h4>
				<table class="table">
		          	<thead class="background _red">
			            <tr>
			              	<th>#</th>
			              	<th>カテゴリー</th>
			              	<th>量</th>
			              	<th>平均の値段</th>
			              	<th>金額</th>
			            </tr>
		          	</thead>
		          	<tbody>
		            	<tr data-element="number">
			              	<td>1</td>
			              	<td><strong>電話番号</strong></td>
			              	<td sub-element="usage"></td>
			              	<td sub-element="average"></td>
			              	<td sub-element="price"></td>
		            	</tr>
			            <tr data-element="voice">
			              	<td>2</td>
			              	<td><strong>録音時間</strong></td>
			              	<td sub-element="usage"></td>
			              	<td sub-element="average"></td>
			              	<td sub-element="price"></td>
			            </tr>
		            	<tr data-element="call">
			              	<td>3</td>
		              		<td><strong>応対時間</strong></td>
			              	<td sub-element="usage"></td>
			              	<td sub-element="average"></td>
			              	<td sub-element="price"></td>
		            	</tr>
		            	<tr data-element="record">
			              	<td>4</td>
		              		<td><strong>録音時間</strong></td>
			              	<td sub-element="usage"></td>
			              	<td sub-element="average"></td>
			              	<td sub-element="price"></td>
		            	</tr>
		          	</tbody>
		        </table>
		    </div>
    		<div>
	        	<div class="pull-right">
	        		<div class='payment-block'>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="business" value="ULG93BQLZDSCL">
							<input type="hidden" name="lc" value="VN">
							<input type="hidden" name="item_name" value="Call Services">
							<input type="hidden" name="amount" value="10">
							<input type="hidden" name="accountSid" value="<?php echo $user->accountSid?>">
							<input type="hidden" name="currency_code" value="JPY">
							<input type="hidden" name="button_subtype" value="services">
							<input type="hidden" name="no_note" value="0">
							<input type="hidden" name="cn" value="Add special instructions to the seller:">
							<input type="hidden" name="no_shipping" value="2">
							<input type="hidden" name="rm" value="1">
							<input type="hidden" name="return" value="http://ntg-center.com/payment/payment-success">
							<input type="hidden" name="cancel_return" value="http://ntg-center.com/payment/cancel-payment">
							<input type="hidden" name="bn" value="PP-BuyNowBF:paybut.png:NonHosted">
							<input type="hidden" name="account" value="1">
							<input type="image" src="http://ntg-center.com/img/paybut.png" name="submit" alt="PayPal - The safer, easier way to pay online!" border="none" title="Payment by PayPal">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
	        	</div>
	        </div>
    	</div>
    </div>
</div>
<script type="text/javascript">
    loadPayment();
    function loadPayment() {
    	var panel = $('#zoom-panel');
    	if(panel.is('[loaded]')) return;
    	$.ajax({
            type:"POST",
            cache:false,
            url:'<?= $this->Url->build(['action' => 'getDetails']) ?>',
            success: function (data) {
            	data=JSON.parse(data);

            	var element = $('[data-element="number"]')[0];
            	$(element.querySelector('[sub-element="usage"]')).html(data.phoneNumberUsage+' Number');
            	$(element.querySelector('[sub-element="price"]')).html('&yen;'+parseInt(data.phoneNumberPrice));
            	$(element.querySelector('[sub-element="average"]')).html('&yen;'+
            		(data.phoneNumberUsage?Math.round(data.phoneNumberPrice/data.phoneNumberUsage*100)/100:0)
            	);

            	element = $('[data-element="voice"]')[0];
            	$(element.querySelector('[sub-element="usage"]')).html(Math.round(data.voiceMinutesUsage*100)/100+ ' minutes');
            	$(element.querySelector('[sub-element="price"]')).html('&yen;'+parseInt(data.voiceMinutesPrice));
            	$(element.querySelector('[sub-element="average"]')).html('&yen;'+
            		(data.voiceMinutesUsage?Math.round(data.voiceMinutesPrice/data.voiceMinutesUsage*100)/100:0)
            	);
            	element = $('[data-element="call"]')[0];
            	$(element.querySelector('[sub-element="usage"]')).html(Math.round(data.callsClientUsage*100)/100+ ' minutes');
            	$(element.querySelector('[sub-element="price"]')).html('&yen;'+parseInt(data.callsClientPrice));
            	$(element.querySelector('[sub-element="average"]')).html('&yen;'+
            		(data.callsClientUsage?Math.round(data.callsClientPrice/data.callsClientUsage*100)/100:0)
            	);

            	element = $('[data-element="record"]')[0];
            	$(element.querySelector('[sub-element="usage"]')).html(Math.round(data.recordingsUsage*100)/100+ ' minutes');
            	$(element.querySelector('[sub-element="price"]')).html('&yen;'+parseInt(data.recordingsPrice));
            	$(element.querySelector('[sub-element="average"]')).html('&yen;'+
            		(data.recordingsUsage?Math.round(data.recordingsPrice/data.recordingsUsage*100)/100:0)
            	);

            	$('#payment-content').removeClass('hide');
            	$('#loading-icon').css('display','none');
        		panel.attr('loaded','');
          	}
        });
    }
</script>
