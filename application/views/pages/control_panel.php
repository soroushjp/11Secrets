<div class="panel_container">
	
	<div class="panel_header">
		<div class="panel_title">Admin Control Panel</div>
		
		<div class="filters">
			<div class="campaign_filter">
			<form action="/admin/panel" method="POST" >
			Like <input type="radio" name="search_type" value="like" <?php if($search_type == "like" || $search_type != "exact") { echo "checked"; } ?>>
			Exact <input type="radio" name="search_type" value="exact" <?php if($search_type == "exact") { echo "checked"; } ?>>
			<input type="text" id="utm_campaign" name="utm_campaign" value="<?php if($traits['utm_campaign'] != "") { echo $traits['utm_campaign']; } else { echo "utm_campaign"; } ?>" size="20" onFocus="if(this.value == 'utm_campaign') { this.value=''; }" onBlur="if(this.value == '') { this.value='utm_campaign'; }"/>
			<input type="text" id="utm_source" name="utm_source" value="<?php if($traits['utm_source'] != "") { echo $traits['utm_source']; } else { echo "utm_source"; } ?>" size="20" onFocus="if(this.value == 'utm_source') { this.value=''; }" onBlur="if(this.value == '') { this.value='utm_source'; }"/>
			<input type="text" id="utm_medium" name="utm_medium" value="<?php if($traits['utm_medium'] != "") { echo $traits['utm_medium']; } else { echo "utm_medium"; } ?>" size="20" onFocus="if(this.value == 'utm_medium') { this.value=''; }" onBlur="if(this.value == '') { this.value='utm_medium'; }"/>
			<input type="text" id="utm_content" name="utm_content" value="<?php if($traits['utm_content'] != "") { echo $traits['utm_content']; } else { echo "utm_content"; } ?>" size="20" onFocus="if(this.value == 'utm_content') { this.value=''; }" onBlur="if(this.value == '') { this.value='utm_content'; }"/>
			<input type="text" id="utm_term" name="utm_term" value="<?php if($traits['utm_term'] != "") { echo $traits['utm_term']; } else { echo "utm_term"; } ?>" size="20" onFocus="if(this.value == 'utm_term') { this.value=''; }" onBlur="if(this.value == '') { this.value='utm_term'; }"/>
			<input type="text" id="country" name="country" value="<?php if($traits['country'] != "") { echo $traits['country']; } else { echo "country"; } ?>" size="20" onFocus="if(this.value == 'country') { this.value=''; }" onBlur="if(this.value == '') { this.value='country'; }"/>
			</div>

			<div class="date_filter">
			<div class="date_title">Date Range</div>
			<select name="start_day" id="start_day">
			 <option value=''>Day</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select>

			<select name="start_month" id="start_month">
			 <option value=''>Month</option>
			<option value = "01">January</option>
			<option value = "02">February</option>
			<option value = "03">March</option>
			<option value = "04">April</option>
			<option value = "05">May</option>
			<option value = "06">June</option>
			<option value = "07">July</option>
			<option value = "08">August</option>
			<option value = "09">September</option>
			<option value = "10">October</option>
			<option value = "11">November</option>
			<option value = "12">December</option>
			</select>

			<select name="start_year" id="start_year">
			 <option value=''>Year</option>
			<option value="2011">2011</option>
			<option value="2012">2012</option>
			</select>

			<span>-</span>

			<select name="end_day" id="end_day">
			 <option value=''>Day</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select>

			<select name="end_month" id="end_month">
			 <option value=''>Month</option>
			<option value = "01">January</option>
			<option value = "02">February</option>
			<option value = "03">March</option>
			<option value = "04">April</option>
			<option value = "05">May</option>
			<option value = "06">June</option>
			<option value = "07">July</option>
			<option value = "08">August</option>
			<option value = "09">September</option>
			<option value = "10">October</option>
			<option value = "11">November</option>
			<option value = "12">December</option>
			</select>

			<select name="end_year" id="end_year">
			 <option value=''>Year</option>
			<option value="2011">2011</option>
			<option value="2012">2012</option>
			</select>

			</div>

			<input type="submit" value="submit" style="visibility: block;" />
			</form>
		</div>
	</div>
	<div class="panel_box">
		<div class="box_title">Number Statistics</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name"># Users:</span> <?=number_format($stats['no_users'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Logins:</span> <?=number_format($stats['no_logins'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Lifts:</span> <?=number_format($stats['no_lifts'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Comments:</span> <?=number_format($stats['no_comments'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Articles Read:</span> <?=number_format($stats['no_read'], 0); ?></div>
			
		</div>
	</div>
	
	<div class="panel_box">
		<div class="box_title">User Type Statistics</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name"># Google Users:</span> <?=number_format($stats['no_goog_users'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Facebook Users:</span> <?=number_format($stats['no_fb_users'], 0); ?></div>			
		</div>
	</div>
	
	<div class="panel_box">
		<div class="box_title">Demographic Statistics</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name"># Men:</span> <?=number_format($stats['no_male'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Females:</span> <?=number_format($stats['no_female'], 0); ?></div>
			<div class="content_row"><span class="row_name">Avg. Age:</span> <?=number_format($stats['avg_age'], 2); ?></div>			
		</div>
	</div>
	
	<div class="panel_box">
		<div class="box_title">Average Statistics (per user)</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name">Avg. # Logins:</span> <?=number_format($stats['avg_logins'], 2); ?></div>
			<div class="content_row"><span class="row_name">Avg. # Lifts:</span> <?=number_format($stats['avg_lifts'], 2); ?></div>
			<div class="content_row"><span class="row_name">Avg. # Comments:</span> <?=number_format($stats['avg_comments'], 2); ?></div>			
		</div>
	</div>
	
	
	<div class="panel_box">
		<div class="box_title">Social Sharing Statistics</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name"># Facebook Recommends:</span> <?=$stats['no_fb_recommends']; ?></div>
			<div class="content_row"><span class="row_name"># Tweets:</span> <?=number_format($stats['no_tweets'], 0); ?></div>
			<div class="content_row"><span class="row_name"># Pinterest pins:</span> <?=number_format($stats['no_pinterestpins'], 0); ?></div>							
			<div class="content_row"><span class="row_name"># Google Plus One's:</span> <?=number_format($stats['no_google_pluses'], 0); ?></div>		
		</div>
	</div>
	
	<div class="panel_box">
		<div class="box_title">Automatic Sharing Statistics</div>
		<div class="box_contents">
			<div class="content_row"><span class="row_name"># Auto FB Read Shares:</span> <?=number_format($stats['no_fbaction_read'], 0); ?></div>	
			<div class="content_row"><span class="row_name"># Auto FB Reveal Shares:</span> <?=number_format($stats['no_fbaction_reveal'], 0); ?></div>							
		</div>
	</div>
	
	<hr />
	
	<div class="panel_title">Time Series Statistics</div>
	
	<div class="cumul_users">
		<div id="chart_div"></div>
	</div>
	
</div>

<script type="text/javascript">

el = window;

if (el.addEventListener){
  el.addEventListener('load', repopulate, false); 
} else if (el.attachEvent){
  el.attachEvent('onload', repopulate);
}					

function repopulate() {

	document.getElementById("start_day").value = "<?php echo $date_array['start_day']; ?>";
	document.getElementById("start_month").value = "<?php echo $date_array['start_month']; ?>";
	document.getElementById("start_year").value = "<?php echo $date_array['start_year']; ?>";
	document.getElementById("end_day").value = "<?php echo $date_array['end_day']; ?>";
	document.getElementById("end_month").value = "<?php echo $date_array['end_month']; ?>";
	document.getElementById("end_year").value = "<?php echo $date_array['end_year']; ?>";
	
}
	

</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<!-- Javascript for the Bar Graph of Monthly Active Users -->
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'No. Active Users');
        data.addRows([
		<?php print_r($stats['js_active_users']); ?>
        ]);

        var options = {
          width: 450, height: 290,
          title: 'Monthly Active Users (logged in at least once)',
          hAxis: {title: 'Month', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>