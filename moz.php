<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<div class="container">
   <h1>Moz Link Explorer Bulk</h1>
 <form method="POST" action="">
  <textarea name="url" class="form-control" id="txtcomment" style="width:100%; height: 300px;"></textarea><br /><br />
  <button type="submit" style="float: right; cursor:pointer;" class="btn btn-success" value="Check">Check URL</button>
 </form>   
   </br>

   <table id="example" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th>Website URL</th>
            <th>Domain Authority</th>
            <th>Page Authority</th>
            <th>Moz Rank</th>
            <th>Backlink to Root Domain</th>
            <th>Check Index</th>
            <th>Registered</th>			
         </tr>
      </thead>
      <tbody>	  
         <?php
		    // Load composer framework
            if (file_exists(__DIR__ . '/vendor/autoload.php')) {
               require(__DIR__ . '/vendor/autoload.php');
            }
            use phpWhois\Whois;
     		set_time_limit(0);	
			function rupiah($angka){
	        $hasil_rupiah = number_format($angka,0,',','.');
	        return $hasil_rupiah;
            }
            $url_array = explode(PHP_EOL, $_POST["url"]);			
			if (!empty($_POST["url"])) {
            for( $i = 0; $i<count($url_array); $i = $i+10) {
            $accessID         = "mozscape-xxx";
            $secretKey        = "xxx";
            $expires          = time() + 300;
            $stringToSign     = $accessID . "\n" . $expires;
            $binarySignature  = hash_hmac('sha1', $stringToSign, $secretKey, true);
            $urlSafeSignature = urlencode(base64_encode($binarySignature));
            $cols             = "103079232132";
            $requestUrl       = 'http://lsapi.seomoz.com/linkscape/url-metrics/'.urlencode($url_array[$i]).'?Cols='. $cols . '&AccessID=' . $accessID . '&Expires=' . $expires . '&Signature=' . $urlSafeSignature;
            $options          = array(
            CURLOPT_RETURNTRANSFER => true
            );
            $ch               = curl_init($requestUrl);
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);
			$x = json_decode($content, true);			
			$whois = new Whois();
			$whois->deepWhois = false;
            $result = $whois->lookup(urlencode($url_array[$i]),false);
			$av = $result['regrinfo']['registered'];
            if (empty($av)) {
				$av = 'Unknown';
			}				
			echo '<tr> <td>'.$x['uu'].'</td><td>'.$x['pda'].'</td><td>'.$x['upa'].'</td><td>'.round($x['umrp']).'</td><td>'.rupiah($x['peid']).'</td><td><a href="https://www.google.com/search?q=site%3A'.$x['uu'].'">Check Index</a></td><td>'.ucwords($av).'</td></tr>';
				flush();
                ob_flush();
			sleep(7);				
			}	
            echo '<script>alert("Done")</script>';
			}
            ?>
      </tbody>
   </table>
   <?php 
   
   ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>$(document).ready(function() {
  $('#example').DataTable();
});</script>