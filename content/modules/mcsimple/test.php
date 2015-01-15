<?
function mcsimple_newtab() {
	?>
	<ul class="grid cs-style-3">
    <li>
        <figure>
            <img src="http://collectionminecraft.com/wp-content/uploads/2014/01/minecraft-grass-block-texture-topmy-first-website-opinions----website-reviews-and-feedback---web-bpc1dkzh.jpg" alt="img01">
            <figcaption>
                <h3>MC-simple.cz</h3><br />
                <span>Minecraft server s mnoha instalovanými pluginy, výborným zázemím, slušnými hráči a zkušenými adminy!</span>
                <!-- <a href="#">Take a look</a> -->
            </figcaption>
        </figure>
    </li>
</ul>
	<?
}
function mcsimple_registertab() {
	$taborder = $GLOBALS["taborder"];
	$taborder++;
	?>
	<li><a href="#tabs-<?=$taborder?>" class="tab">MC-Simple.cz</a></li>
	<?
}

function mcsimple_registercss() {
	echo '<link rel="stylesheet" type="text/css" href="' . "/linux/frames/content/modules/mcsimple" . "/captionhovereffects.css" . '">';
}
?>
<html>
	<head>
		<? mcsimple_registercss(); ?>
	</head>
	<body>
				<? mcsimple_newtab(); ?>

	</body>
</html>