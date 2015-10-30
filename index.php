<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Arananlar Listesi İstatistiki Grafikler</title>	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>
	<style>
		body {
			background: rgba(0, 0, 0, 0) url("img/pattern.bmp") repeat scroll 0 0;
		}
		.first-row {
			margin-top:30px;
			background-color: #fff;
			border: 5px solid #e6e6e6;
			border-top-left-radius: 15px;
			border-top-right-radius: 15px;
		}
		.second-row {
			background-color: #fff;
			border-color: #e6e6e6;
			border-style: none solid;
			border-width: 0 5px;
			padding: 30px 15px 0;
		}
		.third-row {
			margin-bottom: 35px;
			padding: 15px 15px 30px;
			background-color: #fff;
			border-width: 0 5px 5px;
			border-color: #e6e6e6;
			border-style: solid solid;
			border-radius: 0 0 15px 15px;
		}
		.jumbotron h1, .jumbotron .h1 {
			font-size: 56px;
			margin-top: 0;
		}
		.jumbotron {
			padding: 30px 30px 0 !important;
			margin-bottom: 0;
			border-radius: 0 !important;
		}
		.jumbotron p {
			font-size: 17px;
			text-indent: 15px;
		}
		.jumbotron h1 small {
			font-size: 18px;
		}
		.code, .license {
			color: #fff;
		}
		.code > a {
			color: #fff;
		}
		.code > a:hover {
			color: #f00;
			text-decoration: none;
		}
		.tip, .tip:hover {
			cursor: pointer;
		}
		.row.code {
			margin-bottom: 20px;
		}
		.jumbotron .tip {
			cursor: help;
			text-decoration: none;
		}
		blockquote h3 {
			margin: 0 0 5px;
		}
		.third-row h2 {
			font-size: 40px;
			font-weight: 500;
			margin: 25px;
		}
		.google-visualization-table-th {
			text-align: center;
		}
	</style>
</head>
<body>

	<?php
		header ('Content-type: text/html; charset=utf-8'); 
		
		function find($first, $latest, $text) {
			@preg_match_all('/' . preg_quote($first, '/') .
			'(.*?)'. preg_quote($latest, '/').'/i', $text, $m);
			return @$m[1];
		}
		
		function removeKeys(array $array) {
			$array = array_values($array);
			foreach ($array as & $value) {
				if (is_array($value)) {
					$value = removeKeys( $value );
				}
			}
			return $array;
		}
		
		$sletter = array('ç', 'ğ', 'i', 'ı', 'ö', 'ş', 'ü');
		$bletter = array('Ç', 'Ğ', 'İ', 'I', 'Ö', 'Ş', 'Ü'); 

		function tr_strtolower($owertext) {
			global $sletter, $bletter;   		
			return strtolower(str_replace($bletter, $sletter, $owertext));
		}
		
		function tr_strtoupper($uppertext) {   
			global $sletter, $bletter;    		
			return strtoupper(str_replace($sletter, $bletter, $uppertext));
		}

		function tr_ucfirst($ucfirsttext, $e='utf-8') {
			$ik = tr_strtoupper(mb_substr($ucfirsttext, 0, 1, $e), $e);      
			return $ik.mb_substr($ucfirsttext, 1, mb_strlen($ucfirsttext, $e), $e);
		}
		
		function edit_city($c) {
			if		($c == "Suriye afrin")	{return $city = "Suriye Afrin";}
			elseif	($c == "K.maraş")		{return $city = "Kahramanmaraş";}
			elseif	($c == "K. maraş")		{return $city = "Kahramanmaraş";}
			elseif	($c == "Hakari")		{return $city = "Hakkari";}
			elseif	($c == "Ağri")			{return $city = "Ağrı";}
			elseif	($c == "Balikesir")		{return $city = "Balıkesir";}
			elseif	($c == "İğdır")			{return $city = "Iğdır";}
			else							{return $city = $c;}
		}

		function old($o) {
			if		($o < 18)				{return $old = "-18";}
			elseif	($o > 17 && $o < 25)	{return $old = "18-24";}
			elseif	($o > 24 && $o < 35)	{return $old = "25-34";}
			elseif	($o > 34 && $o < 45)	{return $old = "35-44";}
			elseif	($o > 44)				{return $old = "45+";}
			else							{return $old = "N/A";}
		}
	
function topla($sayi1) {
$sonuc = $sayi1 + 24;
return $sonuc;
}
		$year = date("Y");
		$p = 0;
		$control = "x";
		$red_control = "x";
		$blue_control = "x";
		$green_control = "x";
		$orange_control = "x";
		$grey_control = "x";
		
		while($control == "x") {
			
			$x = 0;
			while($red_control == "x") {

				$site = "http://www.terorarananlar.pol.tr/detaylar/Sayfalar/kirmizitamliste.aspx?Paged=TRUE&p_ID=$x";
				$content = file_get_contents($site);
				$info = find('<td class="ms-vb2">', '</td>', $content);
				
				if (empty($info[0])) {$red_control = "xx";}
				
				$y = 0;
				while(isset($info[$y])) {
					
					$name = $info[$y];
					$y++;
					$city_date = $info[$y];
					$city_date = explode("-", $city_date);
						$city = tr_ucfirst(tr_strtolower($city_date[0]));
							$city = edit_city($city);
						$date = $city_date[1];
					$y++;
					$organization = $info[$y];
					$y++;
					
					$old = $year - $date;
						$old = old($old);
					$person[$p] = array("$name", "$city", "$date", "$organization", "#DC3912", "Kırmızı", "$old");
					$p++;
				}
				$x = $x+30;
			}
			
			$x = 0;
			while($blue_control == "x") {

				$site = "http://www.terorarananlar.pol.tr/detaylar/Sayfalar/mavitamliste.aspx?Paged=TRUE&p_ID=$x";
				$content = file_get_contents($site);
				$info = find('<td class="ms-vb2">', '</td>', $content);
				
				if (empty($info[0])) {$blue_control = "xx";}
				
				$y = 0;
				while(isset($info[$y])) {
					
					$name = $info[$y];
					$y++;
					$city_date = $info[$y];
					$city_date = explode("-", $city_date);
						$city = tr_ucfirst(tr_strtolower($city_date[0]));
							$city = edit_city($city);
						$date = $city_date[1];
					$y++;
					$organization = $info[$y];
					$y++;
					
					$old = $year - $date;
						$old = old($old);
					$person[$p] = array("$name", "$city", "$date", "$organization", "#3366CC", "Mavi", "$old");
					$p++;
				}
				$x = $x+30;
			}
			
			$x = 0;
			while($green_control == "x") {

				$site = "http://www.terorarananlar.pol.tr/detaylar/Sayfalar/yesiltamliste.aspx?Paged=TRUE&p_ID=$x";
				$content = file_get_contents($site);
				$info = find('<td class="ms-vb2">', '</td>', $content);
				
				if (empty($info[0])) {$green_control = "xx";}
				
				$y = 0;
				while(isset($info[$y])) {
					
					$name = $info[$y];
					$y++;
					$city_date = $info[$y];
					$city_date = explode("-", $city_date);
						$city = tr_ucfirst(tr_strtolower($city_date[0]));
							$city = edit_city($city);
						$date = $city_date[1];
					$y++;
					$organization = $info[$y];
					$y++;
					
					$old = $year - $date;
						$old = old($old);
					$person[$p] = array("$name", "$city", "$date", "$organization", "#109618", "Yeşil", "$old");
					$p++;
				}
				$x = $x+30;
			}
			
			$x = 0;
			while($orange_control == "x") {

				$site = "http://www.terorarananlar.pol.tr/detaylar/Sayfalar/turuncutamliste.aspx?Paged=TRUE&p_ID=$x";
				$content = file_get_contents($site);
				$info = find('<td class="ms-vb2">', '</td>', $content);
				
				if (empty($info[0])) {$orange_control = "xx";}
				
				$y = 0;
				while(isset($info[$y])) {
					
					$name = $info[$y];
					$y++;
					$city_date = $info[$y];
					$city_date = explode("-", $city_date);
						$city = tr_ucfirst(tr_strtolower($city_date[0]));
							$city = edit_city($city);
						$date = $city_date[1];
					$y++;
					$organization = $info[$y];
					$y++;
					
					$old = $year - $date;
						$old = old($old);
					$person[$p] = array("$name", "$city", "$date", "$organization", "#FF9900", "Turuncu", "$old");
					$p++;
				}
				$x = $x+30;
			}
			
			$x = 0;
			while($grey_control == "x") {

				$site = "http://www.terorarananlar.pol.tr/detaylar/Sayfalar/gritamliste.aspx?Paged=TRUE&p_ID=$x";
				$content = file_get_contents($site);
				$info = find('<td class="ms-vb2">', '</td>', $content);
				
				if (empty($info[0])) {$grey_control = "xx";}
				
				$y = 0;
				while(isset($info[$y])) {
					
					$name = $info[$y];
					$y++;
					$city_date = $info[$y];
					$city_date = explode("-", $city_date);
						$city = tr_ucfirst(tr_strtolower($city_date[0]));
							$city = edit_city($city);
						$date = $city_date[1];
					$y++;
					$organization = $info[$y];
					$y++;
					
					$old = $year - $date;
						$old = old($old);
					$person[$p] = array("$name", "$city", "$date", "$organization", "#808080", "Gri", "$old");
					$p++;
				}
				$x = $x+30;
			}
			$control = "xx";
		}

		$org_name_array = array_column($person, 3);
		$org_name_array_2 = array_count_values($org_name_array);
		arsort($org_name_array_2);
		$org_count = removeKeys($org_name_array_2);
		$org_name = array_keys($org_name_array_2);
		
		$city_name_array = array_column($person, 1);
		$city_name_array_2 = array_count_values($city_name_array);
		$city_count = removeKeys($city_name_array_2);
		$city_name = array_keys($city_name_array_2);
		
		$color_name_array = array_column($person, 4);
		$color_name_array_2 = array_count_values($color_name_array);
		$color_count = removeKeys($color_name_array_2);
		$color_name = array_keys($color_name_array_2);
	
		$colortr_name_array = array_column($person, 5);
		$colortr_name_array_2 = array_count_values($colortr_name_array);
		$colortr_name = array_keys($colortr_name_array_2);
		
		$old_name_array = array_column($person, 6);
		$old_name_array_2 = array_count_values($old_name_array);
		arsort($old_name_array_2);
		$old_count = removeKeys($old_name_array_2);
		$old_name = array_keys($old_name_array_2);
	
	?>
	
<!-- for organization -->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Örgüt',							'Aranan Sayısı'],
				<?php 
					for ($x=0; isset($org_name[$x]); $x++) {
						echo "['". $org_name[$x] . "',". $org_count[$x] ."],";
					}
				?>

			]);
			var options = {
				title: 'Aranan Sayısına Göre Örgütler',
				chartArea:{left:10,right:0,top:10,bottom:0,width:'100%',height:'90%'}
			};
			var chart = new google.visualization.PieChart(document.getElementById('for_org'));
			chart.draw(data, options);
		}
	</script>
<!-- for organization end -->

<!-- for organization without pkk/kck -->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Örgüt',							'Aranan Sayısı'],
				<?php 
					for ($x=1; isset($org_name[$x]); $x++) {
						echo "['". $org_name[$x] . "',". $org_count[$x] ."],";
					}
				?>

			]);
			var options = {
				title: 'Aranan Sayısına Göre Örgütler (<?php echo $org_name[0]; ?> olmadan)',
				chartArea:{left:10,right:0,top:10,bottom:0,width:'100%',height:'90%'}
			};
			var chart = new google.visualization.PieChart(document.getElementById('for_org_w'));
			chart.draw(data, options);
		}
	</script>
<!-- for organization without pkk/kck end -->

<!-- for color -->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Aranma Derecesi', 'Kişi'],
				<?php 
					for ($x=0; isset($color_name[$x]); $x++) {
						echo "['". $colortr_name[$x] . "',". $color_count[$x] ."],";
					}
				?>
				
			]);
			var options = {
				title: 'Aranma Renklerine Göre Kişi Sayısı',
				pieHole: 0.4,
				chartArea:{left:10,right:0,top:10,bottom:0,width:'100%',height:'90%'},
				colors:['<?php echo $color_name[0]; ?>','<?php echo $color_name[1]; ?>','<?php echo $color_name[2]; ?>','<?php echo $color_name[3]; ?>','<?php echo $color_name[4]; ?>']
			};
			var chart = new google.visualization.PieChart(document.getElementById('color'));
			chart.draw(data, options);
		}
	</script>
<!-- for color end -->

<!-- for old -->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Yaş Aralığı', 'Kişi'],
				<?php 
					for ($x=0; isset($old_name[$x]); $x++) {
						echo "['". $old_name[$x] . "',". $old_count[$x] ."],";
					}
				?>
				
			]);
			var options = {
				title: 'Aranma Renklerine Göre Kişi Sayısı',
				pieHole: 0.4,
				chartArea:{left:10,right:0,top:10,bottom:0,width:'100%',height:'90%'}
			};
			var chart = new google.visualization.PieChart(document.getElementById('old'));
			chart.draw(data, options);
		}
	</script>
<!-- for old end -->

<!-- for city  -->
	<script type='text/javascript'>
		google.load('visualization', '1', {'packages': ['geochart']});
		google.setOnLoadCallback(drawMarkersMap);

		function drawMarkersMap() {
			var data = google.visualization.arrayToDataTable([
				['Şehir:',   'Kişi'],
				<?php 
					for ($x=0; isset($city_name[$x]); $x++) {
						echo "['". $city_name[$x] . "',". $city_count[$x] ."],";
					}
				?>
			]);
			var options = {
				region: 'TR',
				displayMode: 'markers',
				chartArea:{left:0,right:0,top:0,bottom:0,width:'100%',height:'100%'},
				colorAxis: {colors: ['yellow', 'red']}
			};
			var chart = new google.visualization.GeoChart(document.getElementById('map'));
			chart.draw(data, options);
		};
	</script>
<!-- for city end -->
<!-- list -->
	<script type="text/javascript">
		google.load("visualization", "1.1", {packages:["table"]});
		google.setOnLoadCallback(drawTable);

		function drawTable() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Ad Soyad');
			data.addColumn('string', 'Doğum Tarihi');
			data.addColumn('string', 'Kayıtlı Olduğu Yer');
			data.addColumn('string', 'Örgüt');
			data.addColumn('string', 'Aranma Derecesi');
			data.addRows([
				<?php
					for($z = 0; isset($person[$z]); $z++) {
						echo "['" . $person[$z][0] . "', '". $person[$z][2] . "', '". $person[$z][1] . "', '". $person[$z][3] . "', '". $person[$z][5] . "'],";
					}
				?>
			]);
			var table = new google.visualization.Table(document.getElementById('list'));
			table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
		}
	</script>
<!-- list end -->





	<div class="container">
		<div class="row first-row">
			<div class="col-xs-12 jumbotron">
				<h1 class="text-center">Arananlar Listesi İstatistiki Grafikler<small>v1</small></h1><?php echo $year ."-". $person[0][2]."-".$person[0][6]; ?>
				<p>Aşağıdaki veriler Türkiye Cumhuriyeti İçişleri Bakanlığı tarafından <a href="http://terorarananlar.pol.tr" rel="nofollow" target="_blank">terorarananlar.pol.tr</a> adresinde "Terör Arananlar" adıyla yayınlanan listelere göre hazırlanmıştır. Veriler doğrudan bu siteden çekildiği için daima günceldir. Listeden herhangi bir ad çıkartıldığında ya da eklendiğinde kişi ve kişiyle ilgili veriler otomatik ve anlık olarak güncellenmektedir.</p>
				<p>Yayınlanan içerikteki doğum yeri bilgileri <a class="tip" data-toggle="tooltip" data-placement="top" title="Sadece Kahramanmaraş için bile 'K.maraş', 'K. maraş', 'K.MARAŞ', 'Kahramanmaras' gibi birçok verinin yer alması gibi.">düzenlilikten uzak</a> olduğu için şehirler ile ilgili bilgilerde otomatik <a class="tip" data-toggle="tooltip" data-placement="top" title="Şehir adlarının ilk harflerinin büyük diğerlerinin küçük yazılması, Türkçe karakter hatalarının giderilmesi, farklı şekillerde yazımların tek çatıda birleştirilmesi gibi.">düzenleme işlemleri</a> yapılmış ancak <strong>bazı hatalar giderilememiştir</strong>.</p>
				<p>Çalışma, Creative Commons Lisansı (<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/" class="tip" data-toggle="tooltip" data-placement="top" title="Attribution, NonCommercial, ShareAlike 4.0 International">CC BY-NC-SA 4.0</a>) ile lisanslanmıştır. <a xmlns:cc="http://creativecommons.org/ns#" href="http://mertskaplan.com" property="cc:attributionName" rel="cc:attributionURL">Mert Salih Kaplan</a> adına atıfta bulunmak ya da <a xmlns:dct="http://purl.org/dc/terms/" href="http://teror.mertskaplan.com" rel="dct:source">http://teror.mertskaplan.com</a> adresini kaynak göstermek şartıyla ticari olmayan kullanımlarda özgürce kullanılabilir ve aynı ya da daha özgür bir lisans altında yayınlanması şartıyla özgürce çatallandırılabilir.</p>
			</div>
		</div>
		<div class="row second-row">
			<div class="col-md-6">
				<h4>Aranan Sayısına Göre Örgütler<small><br>&nbsp;</small></h4>
				<div id="for_org" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
			</div>
			<div class="col-md-6">
				<h4>Aranan Sayısına Göre Örgütler <small><br>(<?php echo $org_name[0]; ?> olmadan)</small></h4>
				<div id="for_org_w" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
			</div>
			<div class="col-md-6">
				<h4>Aranma Renklerine Göre Kişi Sayısı<small><br>&nbsp;</small></h4>
				<div id="color" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
			</div>
			<div class="col-md-6">
				<h4>Arananların Yaş Aralıklarına Göre Kişi Sayısı<small><br>&nbsp;</small></h4>
				<div id="old" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
			</div>
			<div class="col-xs-12">
				<hr>
				<h4>Arananların Kayıtlı Olduğu Şehirler</h4>
				<div id="map" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
			</div>
			<div class="col-xs-12">
				<hr>
				<h4>Arananlar Tüm Liste</h4>
				<div id="list" class="text-center" style="width: 100%; height: 100%; margin:0 auto;"></div>
				<h4>Örgüt Adlarındaki Kısaltmalar</h4>
				<dl class="dl-horizontal">
					<dt>DEAŞ</dt>
					<dd>Devlet'ül Irak ve'ş Şam (Da'iş) <em>veya</em> IŞİD (Irak ve Şam İslam Devlet) <em>veya</em> Irak ve Levant İslam Devleti (ILİD) <em>veya</em> İslam Devleti (İD) <em>veya</em> DAİŞ <em>veya</em> DEAŞ</dd>
					<dt>DHKP/C</dt>
					<dd>Devrimci Halk Kurtuluş Partisi-Cephesi</dd>
					<dt>MKP</dt>
					<dd>Maoist Komünist Partisi</dd>
					<dt>MLKP</dt>
					<dd>Marksist Leninist Komünist Parti</dd>
					<dt>PDY</dt>
					<dd>Paralel Devlet Yapılanması</dd>
					<dt>PKK/KCK</dt>
					<dd>Partiya Karkerên Kurdistanê (Kürdistan İşçi Partisi) / Koma Civakên Kurdistan (Kürdistan Topluluklar Birliği)</dd>
					<dt>THKP/C Acilciler</dt>
					<dd>Türkiye Halk Kurtuluş Partisi - Cephesi Acilciler</dd>
					<dt>TKP/ML</dt>
					<dd>Türkiye Komünist Partisi - Marksist Leninist Hareketi</dd>
					<dt>TİKB</dt>
					<dd>Türkiye İhtilalci Komünistler Birliği</dd>
				</dl>
				<hr>
			</div>
		</div>
		<div class="row third-row">
			<h2>Listeyle ilgili bazı haberler</h2>
			<blockquote>
				<h3><a href="www.cumhuriyet.com.tr/haber/turkiye/399405/Madimak_katilleri_listede_yok.html">Madımak katilleri listede yok</a></h3>
				<footer>30 Ekim 2015 Cuma | <cite title="Cumhuriyet Gazetesi">Cumhuriyet Gazetesi - Alican Uludağ</cite></footer>
			</blockquote>
			<blockquote>
				<h3><a href="http://tr.sputniknews.com/turkiye/20151030/1018705965/yesil-icisleribakanligi-teror-liste-.html">Devlet artık 'Yeşil'i aramıyor mu?</a></h3>
				<footer>30 Ekim 2015 Cuma | <cite title="Cumhuriyet Gazetesi">Sputnik Türkiye</cite></footer>
			</blockquote>
		</div>
		<div class="row github">
			<p class="code text-center">
				<a href="http://mertskaplan.com/" target="_blank">mertskaplan</a> tarafından <a class="tip" data-toggle="tooltip" data-placement="top" title="PHP, HTML, CSS, JS">❤</a> ile kodlandı. | <a href="https://github.com/mertskaplan/Arananlar-Listesi-statistiki-Grafikler" target="_blank">GitHub</a>
			</p>
		</div>
	</div>

	
  </body>
</html>
