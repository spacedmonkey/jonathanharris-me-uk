<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 padding-top box item">
<?php global $sm_data; 
	
	$book = $sm_data;
	
	
	$rating = round($book['book']['average_rating']);
	echo "<div class='media'>";
    echo "<a href='" . $book['book']['link'] . "' class='pull-left' title='" . strip_tags($book['book']['title']) . "'><img src='" . $book['book']['image_url'] . "' class='media-object enty-image' alt='" . strip_tags($book['book']['title']) . "'/></a>";
    echo "<div class='media-body'><h4 class='media-heading summary'><a href='" . $book['book']['link'] . "' title='" . strip_tags($book['book']['title']) . "'>" . $book['book']['title'] . "</a></h4>";
    echo "<span class='sr-only rating'>".$book['book']['average_rating']."</span>";
    echo "<p>";
    
    for($i = 1; $i < 6; $i++ ){
	    printf('<span class="glyphicon glyphicon-star%s"></span>',($i > $rating ) ? '-empty' : '');
    }
    
    echo "</p>";
    echo "</div>";
   
    echo "</div>";

?>
<div class="icon-corner genericon genericon-book"></div>
</div>