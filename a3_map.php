<?

function cmp_by_subitem($a, $b, $subitem_name) {
  return $a[$subitem_name] - $b[$subitem_name];
}

$claim_list = array();
$file = fopen('claims.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
	$claim_list[] = $line;
}
fclose($file);
unset($claim_list[0]); // remove the header

// order of columns in csv file
$name = 0;
$size = 1;
$members = 2;
$wealth = 3;
$supplies = 4;
$x_coord = 5; // raw
$y_coord = 6; // raw
$waystone = 7;

$map_scale = 4096;
$image_scale = 4096;
foreach ($claim_list as $key => $claim)
{
	// convert coordinates to position on page
	$claim_list[$key]['y'] = 100* (($map_scale-$claim_list[$key][$x_coord]) / $map_scale);
	$claim_list[$key]['x'] = 100* (($claim_list[$key][$y_coord]) / $map_scale );
}

usort($claim_list, "x");

// TODO sort claims by location from bottom left to top right (?) so the names dont overlap
?>

<link rel="stylesheet" href="a3_map.css">

<div class="map">
  <img src="a3_map.png" alt="" />

    <?
		foreach ($claim_list as $key => $claim)
		{
			$icon = "pin_claim";
			if($claim[$waystone] == 1)
				$icon = "pin_waystone";
			 	?>
		 	  <div class="<?=$icon?>" style="left:<?=$claim['x'];?>%; top:<?=$claim['y'];?>%;">
			    <div class="pin-text">
			      <h3><?=$claim[$name]?> (<?=$claim[$x_coord]?>,<?=$claim[$y_coord]?>) <? if($claim[$waystone] == 1) { echo " Waystone";}?></h3>
			    </div>
			  </div>
			  <?	
		} 
		?>
  </div>
</div>

<?
/*
// TODO check if map works better than basic divs
<img src="a3_map.png" alt="world" usemap="#workmap" width="100%" height="auto">

<map name="workmap">
  <area shape="circle" coords="34,44,270,350" alt="Computer" href="computer.htm">
</map>*/
?>