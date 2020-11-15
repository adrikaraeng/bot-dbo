<?php
$this->title="Dashboard";
$connection = \Yii::$app->db;
$dn = date('Y-m-d');
$mn = date('Y-m');
$yn = date('Y');
// echo $dn.' '.date('H:i:s'); 
$ods = $connection->createCommand("SELECT count(*) FROM cases as a WHERE DATE(a.tanggal_masuk) LIKE '$mn%' AND DATEDIFF((CASE WHEN a.tanggal_closed IS NULL THEN '$dn' ELSE DATE(a.tanggal_closed) END), DATE(a.tanggal_masuk))='0'")->queryScalar();
$nods2 = $connection->createCommand("SELECT count(*) FROM cases as a WHERE DATE(a.tanggal_masuk) LIKE '$mn%' AND DATEDIFF((CASE WHEN a.tanggal_closed IS NULL THEN '$dn' ELSE DATE(a.tanggal_closed) END),DATE(a.tanggal_masuk))='1' OR DATE(a.tanggal_masuk) LIKE '$mn%' AND DATEDIFF((CASE WHEN a.tanggal_closed IS NULL THEN '$dn' ELSE DATE(a.tanggal_closed) END), DATE(a.tanggal_masuk))='2'")->queryScalar();
$nods_more2 = $connection->createCommand("SELECT count(*) FROM cases as a WHERE DATE(a.tanggal_masuk) LIKE '$mn%' AND DATEDIFF((CASE WHEN a.tanggal_closed IS NULL THEN '$dn' ELSE DATE(a.tanggal_closed) END),DATE(a.tanggal_masuk))>'2'")->queryScalar();

//All month
$list12month = $connection->createCommand("SELECT *, 
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-01',1,0)) AS JAN,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-02',1,0)) AS FEB,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-03',1,0)) AS MAR,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-04',1,0)) AS APR,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-05',1,0)) AS MEI,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-06',1,0)) AS JUN,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-07',1,0)) AS JUL,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-08',1,0)) AS AUG,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-09',1,0)) AS SEP,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-10',1,0)) AS OKT,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-11',1,0)) AS NOV,
SUM(IF(DATE_FORMAT(tanggal_masuk,'%Y-%m')='$yn-12',1,0)) AS DCM
FROM cases")->queryOne();

//Performance closed
$yn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE status_owner='Closed' AND YEAR(tanggal_masuk)='$yn'")->queryScalar();
$total_yn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE YEAR(tanggal_masuk)='$yn'")->queryScalar();
$min_yn_perform = $total_yn_perform - $yn_perform;
if($total_yn_perform != 0):
  $percent_yn_perform = round(($yn_perform/$total_yn_perform)*100);
  $percent_min_yn_perform = 100-$percent_yn_perform;
else:
  $percent_yn_perform = '0';
  $percent_min_yn_perform = 100-$percent_yn_perform;
endif;

$mn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE status_owner='Closed' AND DATE(tanggal_masuk) LIKE '$mn%'")->queryScalar();
$total_mn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE DATE(tanggal_masuk) LIKE '$mn%'")->queryScalar();
$min_mn_perform = $total_mn_perform - $mn_perform;
if($total_mn_perform != 0):
$percent_mn_perform = round(($mn_perform/$total_mn_perform)*100);
$percent_min_mn_perform = 100-$percent_mn_perform;
else:
  $percent_mn_perform = '0';
  $percent_min_mn_perform = 100-$percent_yn_perform;
endif;

$dn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE status_owner='Closed' AND DATE(tanggal_masuk)='$dn'")->queryScalar();
$total_dn_perform = $connection->createCommand("SELECT count(*) FROM cases WHERE DATE(tanggal_masuk)='$dn'")->queryScalar();
$min_dn_perform = $total_dn_perform - $dn_perform;

if($total_dn_perform != 0):
$percent_dn_perform = round(($dn_perform/$total_dn_perform)*100);
$percent_min_dn_perform = 100-$percent_dn_perform;
else:
  $percent_dn_perform = '0';
  $percent_min_dn_perform = 100-$percent_dn_perform;
endif;
//By Kategori
$yn_all_case = $connection->createCommand("SELECT count(*) FROM cases")->queryScalar();
$yn_kategori = $connection->createCommand("SELECT *, count(a.kategori) AS c_kategori FROM cases AS a 
INNER JOIN kategori AS b ON b.id=a.kategori
WHERE YEAR(a.tanggal_masuk)='$yn'
GROUP BY kategori ORDER BY c_kategori ASC LIMIT 10")->queryAll();

$mn_kategori = $connection->createCommand("SELECT *, count(a.kategori) AS c_kategori FROM cases AS a 
INNER JOIN kategori AS b ON b.id=a.kategori
WHERE DATE(a.tanggal_masuk) LIKE '$mn%'
GROUP BY kategori ORDER BY c_kategori ASC LIMIT 10")->queryAll();


$dn_kategori = $connection->createCommand("SELECT *, count(a.kategori) AS c_kategori FROM cases AS a 
INNER JOIN kategori AS b ON b.id=a.kategori
WHERE DATE(a.tanggal_masuk) LIKE '$dn%'
GROUP BY kategori ORDER BY c_kategori ASC LIMIT 10")->queryAll();
?>
<style>
  .panel-row{
    background-color:rgba(211,211,211,0.5);
    border-radius:8px;
    border:0.1px solid #fff;
    padding:5px;
    margin-top:10px;
  }
  #performance-rate-close-yn{
    background-color:#fff;
    height:90px;
    width:90px;
    border:3px solid #32c617;
    border-radius:50%;
    padding-top:25px;
    text-align:center;
  }
  #performance-rate-close-mn{
    background-color:#fff;
    height:90px;
    width:90px;
    border:3px solid #8618fe;
    border-radius:50%;
    padding-top:25px;
    text-align:center;
  }
  #performance-rate-close-dn{
    background-color:#fff;
    height:90px;
    width:90px;
    border:3px solid #0021bb;
    border-radius:50%;
    padding-top:25px;
    text-align:center;
  }
  #title-panel{
    text-transform:uppercase;
    text-align:center;
    font-weight:bold;
    font-size:1em;
    color:#000;
  }
  #count_kategori,
  #count_kategori_month,
  #count_kategori_today,
  #status_case{
    height:300px;
  }
  #month12{
    height:230px;
  }
  .canvasjs-chart-credit {
    display: none;
  }
  .canvasjs-chart-tooltip{
    margin-bottom: 80%;
  }
  #panel-total-case{
    height:130px;
  }
  #total-case-month,
  #total-case-close-month,
  #total-case-on-progress-month{
    font-size:2em;
    background-color:rgba(255,255,255);
    padding:4px;
    border-radius:4px;
  }
</style>
<div class="index-dashboard">
    <?php
      $yn_nama_kategori = [];
      $yn_c_kategori = [];
      $yn_count = 0;
      
      $mn_nama_kategori = [];
      $mn_c_kategori = [];
      $mn_count = 0;
      
      $dn_nama_kategori = [];
      $dn_c_kategori = [];
      $dn_count = 0;
    ?>
    <?php foreach($yn_kategori as $k => $kat):?>
        <?php
          $yn_nama_kategori[] = $kat['nama_kategori'];
          $yn_c_kategori[] = $kat['c_kategori'];
          $yn_count++;
        ?>
    <?php endforeach;?>
    
    <?php foreach($mn_kategori as $km => $katm):?>
        <?php
          $mn_nama_kategori[] = $katm['nama_kategori'];
          $mn_c_kategori[] = $katm['c_kategori'];
          $mn_count++;
        ?>
    <?php endforeach;?>
    
    <?php foreach($dn_kategori as $kd => $katd):?>
        <?php
          $dn_nama_kategori[] = $katd['nama_kategori'];
          $dn_c_kategori[] = $katd['c_kategori'];
          $dn_count++;
        ?>
    <?php endforeach;?>
    <!-- Detail by kategori Chart -->
    <div class="row">
      <div class="col-lg-9">
        <div class="col-lg-12 panel-row">
          <div id="title-panel">TOP 10 KATEGORI</div>
          <div style="border-bottom:2px solid #000;"></div>
          <div class="col-lg-4">
            <div id="title-panel"><?=date('Y')?></div>
            <div id="count_kategori"></div>
          </div>
          <div class="col-lg-4">
            <div id="title-panel"><?=date('M Y')?></div>
            <div id="count_kategori_month"></div>
          </div>
          <div class="col-lg-4">
            <div id="title-panel">TODAY</div>
            <div id="count_kategori_today"></div>
          </div>
        </div>
        
        <div class="col-lg-12 panel-row">
          <div id="title-panel">HANDLING CASE <?=$yn?></div>
          <div id="month12"></div>
        </div>
      </div>
      
      <div class="col-lg-3">
        <div class="col-lg-12 panel-row">
          <div id="title-panel">CLOSED RATE</div>
          <div style="border-bottom:2px solid #000;"></div>
          <div class="col-lg-4">
            <div id="title-panel"><?=date('Y')?></div>
            <div id="performance-rate-close-yn">
              <div style="font-size:2.0em;font-weight:bold;color:#32c617;"><span class="count"><?=$percent_yn_perform?></span>%</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div id="title-panel"><?=date('M Y')?></div>
            <div id="performance-rate-close-mn">
              <div style="font-size:2.0em;font-weight:bold;color:#8618fe;"><span class="count"><?=$percent_mn_perform?></span>%</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div id="title-panel">TODAY</div>
            <div id="performance-rate-close-dn">
              <div style="font-size:2.0em;font-weight:bold;color:#0021bb;"><span class="count"><?=$percent_dn_perform?></span>%</div>
            </div>
          </div>
        </div> <!-- End col-lg-12 panel-row -->

        <div class="col-lg-12 panel-row">
          <div id="title-panel">SERVICES STATUS <?=date('F Y')?></div>
          <div id="status_case"></div>
        </div>
        <div class="col-lg-12 panel-row" id="panel-total-case">
          <div id="title-panel">Total Case <?=date('F Y')?></div>
          <div style="padding-top:30px;"></div>
          <div class="col-lg-4">
            <div id="total-case-month"><i class="fab fa-telegram" style="color:#0b7ec4;"></i><span class="count" style="float:right;font-size:0.6em;font-weight:bold;"><?=$total_mn_perform?></span><div style="font-size:0.3em;font-weight:bold;">TOTAL CASE</div></div>
            
          </div>
          <div class="col-lg-4">
            <div id="total-case-close-month"><i class="fa fa-thumbs-up" style="color:#0ecfca;"></i><span class="count" style="float:right;font-size:0.6em;font-weight:bold;"><?=$mn_perform?></span><div style="font-size:0.3em;font-weight:bold;">CLOSED</div></div>
            
          </div>
          <div class="col-lg-4">
            <div id="total-case-on-progress-month"><i class="fa fa-wrench" style="color:#f86b0e;"></i><span class="count" style="float:right;font-size:0.6em;font-weight:bold;"><?=$min_mn_perform?></span><div style="font-size:0.3em;font-weight:bold;">ON PROGRESS</div></div>
            
          </div>
        </div>
      </div>

    </div> <!-- End Row -->
</div><!-- End index-dashboard -->
<?php
$this->registerJS("
$('.count').each(function () {
  $(this).prop('Counter',0).animate({
      Counter: $(this).text()
  }, {
      duration: 1000,
      easing: 'swing',
      step: function (now) {
          $(this).text(Math.ceil(now));
      }
  });
});

setTimeout(function() {
  location.reload();
}, 10000);
");
?>
<script>

window.onload = function() {
//Year
  var chart = new CanvasJS.Chart("count_kategori", {
    backgroundColor: "rgba(255, 255, 255, .2)",
    animationEnabled: true,
    
    title:{
      text:""
    },
    axisX:{
      // interval: 1,
      title: "Kategori Case",
      titleFontWeight: "bold",
      titleFontSize: 12,
      titleFontColor: "#000", 
      labelFontWeight: "bold",
      labelFontSize: 10,
      labelFontColor: "#000",
      labelMaxWidth: 50,
      labelWrap: true,
    },
    axisY:{
      interval: 1,
      interlacedColor: "rgba(1,77,101,.2)",
      gridColor: "rgba(1,77,101,.1)",
      title: "Jumlah Tiket",
      titleFontSize: 12,
      titleFontWeight: "bold",
      titleFontColor: "#000",
      labelWrap: true,    
      labelMaxWidth: 50,
      labelFontSize: 10,
      labelFontColor: "#000",
    },
    toolTip:{
      enabled: true,       //disable here
      animationEnabled: true //disable here
    },
    data: [{
      type: "bar",
      name: "companies",
      // axisYType: "secondary",
      // color: "#014D65",
      indexLabelPlacement: "inside",
      indexLabelFontColor: "#fff",
      indexLabelFontSize: 14,
      indexLabelMaxWidth: 50,
      labelAlign: "near",//"center", "near"
      // indexLabelOrientation: "vertical",
      indexLabel: "{y}",
      dataPoints: [
        <?php for($i=0;$i<$yn_count;$i++){?>
          {
            y: <?=$yn_c_kategori[$i]?>,
            label: "<?=$yn_nama_kategori[$i]?>"
          },
        <?php } ?>
      ]
    }]
  });
  chart.render();

//Month

var chart_m = new CanvasJS.Chart("count_kategori_month", {
    backgroundColor: "rgba(255, 255, 255, .2)",
    animationEnabled: true,
    
    title:{
      text:""
    },
    axisX:{
      // interval: 1,
      title: "Kategori Case",
      titleFontWeight: "bold",
      titleFontSize: 12,
      titleFontColor: "#000", 
      labelFontWeight: "bold",
      labelFontSize: 10,
      labelFontColor: "#000",
      labelMaxWidth: 50,
      labelWrap: true,
    },
    axisY:{
      interval: 1,
      interlacedColor: "rgba(1,77,101,.2)",
      gridColor: "rgba(1,77,101,.1)",
      title: "Jumlah Tiket",
      titleFontSize: 12,
      titleFontWeight: "bold",
      titleFontColor: "#000",
      labelWrap: true,    
      labelMaxWidth: 50,
      labelFontSize: 10,
      labelFontColor: "#000",
    },
    toolTip:{
      enabled: true,       //disable here
      animationEnabled: true //disable here
    },
    data: [{
      type: "bar",
      name: "companies",
      // axisYType: "secondary",
      // color: "#014D65",
      indexLabelPlacement: "inside",
      indexLabelFontColor: "#fff",
      indexLabelFontSize: 14,
      indexLabelMaxWidth: 50,
      labelAlign: "near",//"center", "near"
      // indexLabelOrientation: "vertical",
      indexLabel: "{y}",
      dataPoints: [
        <?php for($i=0;$i<$mn_count;$i++){?>
          {
            y: <?=$mn_c_kategori[$i]?>,
            label: "<?=$mn_nama_kategori[$i]?>"
          },
        <?php } ?>
      ]
    }]
  });
  chart_m.render();

//Today

var chart_d = new CanvasJS.Chart("count_kategori_today", {
    backgroundColor: "rgba(255, 255, 255, .2)",
    animationEnabled: true,
    
    title:{
      text:""
    },
    axisX:{
      // interval: 1,
      title: "Kategori Case",
      titleFontWeight: "bold",
      titleFontSize: 12,
      titleFontColor: "#000", 
      labelFontWeight: "bold",
      labelFontSize: 10,
      labelFontColor: "#000",
      labelMaxWidth: 50,
      labelWrap: true,
    },
    axisY:{
      interval: 1,
      interlacedColor: "rgba(1,77,101,.2)",
      gridColor: "rgba(1,77,101,.1)",
      title: "Jumlah Tiket",
      titleFontSize: 12,
      titleFontWeight: "bold",
      titleFontColor: "#000",
      labelWrap: true,    
      labelMaxWidth: 50,
      labelFontSize: 10,
      labelFontColor: "#000",
    },
    toolTip:{
      enabled: true,       //disable here
      animationEnabled: true //disable here
    },
    data: [{
      type: "bar",
      name: "companies",
      // axisYType: "secondary",
      // color: "#014D65",
      indexLabelPlacement: "inside",
      indexLabelFontColor: "#fff",
      indexLabelFontSize: 14,
      indexLabelMaxWidth: 50,
      labelAlign: "near",//"center", "near"
      // indexLabelOrientation: "vertical",
      indexLabel: "{y}",
      dataPoints: [
        <?php for($i=0;$i<$dn_count;$i++){?>
          {
            y: <?=$dn_c_kategori[$i]?>,
            label: "<?=$dn_nama_kategori[$i]?>"
          },
        <?php } ?>
      ]
    }]
  });
  chart_d.render();

  //Pie closed : on progress  

  var chart_case = new CanvasJS.Chart("status_case", {
    backgroundColor: "rgba(255, 255, 255, .2)",
    animationEnabled: true,
    theme: "light2", //"light1", "dark1", "dark2"
    title:{
      text: ""             
    },
    axisY:{
      // interval: 10
      title: "Jumlah Tiket",
      titleFontSize: 12,
      titleFontWeight: "bold",
      titleFontColor: "#000",
    },
    toolTip:{   
			content: "{label}: {y}"      
		},
	  data: [{        
      type: "column",  
      // showInLegend: false, 
      legendMarkerColor: "grey",
      indexLabelPlacement: "inside",
      indexLabelFontColor: "#fff",
      indexLabelFontSize: 14,
      legendText: "{label}",
      indexLabel: "{y}",
      dataPoints: [      
        { y: <?=$ods?>, label:"ODS" },
        { y: <?=$nods2?>,  label:"NODS 2" },
        { y: <?=$nods_more2?>,  label:"NODS > 2" },
      ]
    }]
  });
  chart_case.render();

  var chart_month = new CanvasJS.Chart("month12", {
    backgroundColor: "rgba(255, 255, 255, .2)",
    animationEnabled: true,
    theme: "light2", //"light1", "dark1", "dark2"
    title:{
      text: ""             
    },
    axisY:{
      // interval: 10
      title: "Jumlah Tiket",
      titleFontSize: 12,
      titleFontWeight: "bold",
      titleFontColor: "#000",
    },
    toolTip:{   
			content: "{label}: {y}"      
		},
	  data: [{        
      type: "column",  
      // showInLegend: false, 
      legendMarkerColor: "grey",
      indexLabelPlacement: "inside",
      indexLabelFontColor: "#fff",
      indexLabelFontSize: 14,
      legendText: "{label}",
      indexLabel: "{y}",
      dataPoints: [
        { y: <?=$list12month['JAN']?>, label: "JAN" },
        { y: <?=$list12month['FEB']?>,  label: "FEB" },
        { y: <?=$list12month['MAR']?>,  label: "MAR" },
        { y: <?=$list12month['APR']?>,  label: "APR" },
        { y: <?=$list12month['MEI']?>,  label: "MEI" },
        { y: <?=$list12month['JUN']?>, label: "JUN" },
        { y: <?=$list12month['JUL']?>,  label: "JUL" },
        { y: <?=$list12month['AUG']?>,  label: "AUG" },
        { y: <?=$list12month['SEP']?>,  label: "SEP" },
        { y: <?=$list12month['OKT']?>,  label: "OKT" },
        { y: <?=$list12month['NOV']?>,  label: "NOV" },
        { y: <?=$list12month['DCM']?>,  label: "DEC" },
      ]
    }]
  });
  chart_month.render();
}
</script>