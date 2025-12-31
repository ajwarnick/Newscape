<!DOCTYPE html>
<html lang="en">
	<head>
		<?php

		// $feeds = [
		// 	"https://rss.nytimes.com/services/xml/rss/nyt/World.xml",
		// 	"https://rss.nytimes.com/services/xml/rss/nyt/US.xml",
		// 	"https://rss.nytimes.com/services/xml/rss/nyt/Arts.xml"
		// ];

		// $r = rand(0, count($feeds));
		// $content = file_get_contents($feeds[0]);

		$context = stream_context_create(
			array(
				"http" => array(
					'method' 	=> 	"GET",
					'header'	=>	"Accept-language: en\r\n" .
              						"Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              						"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
  )
			)
		);

		$content = file_get_contents("https://rss.nytimes.com/services/xml/rss/nyt/World.xml");
		
		// Instantiate XML element
		$xml = new SimpleXMLElement($content); 
		$num = count($xml->channel->item) - 1;

		$link = $xml->channel->item[rand(0, $num)]->link;
		$html = file_get_contents($link, false, $context);

		
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
		
		$tags = $doc->getElementsByTagName('img');
		
		do {
			$t = rand(0, count($tags)-1);
			$image = $tags[$t]->getAttribute('src');
			list($width, $height) = getimagesize($image);
		} while ($width < 599);

		// A few settings
		// $image = 'landscape.jpg';
		// $image = 'portraitNew.jpeg';
		// $image = 'https://static01.nyt.com/images/2022/03/30/business/00amazonlabor1/00amazonlabor1-threeByTwoMediumAt2X.jpg?format=pjpg&quality=75&auto=webp&disable=upscale';
		
		// Read image path, convert to base64 encoding
		$imageData = base64_encode(file_get_contents($image));

		// Format the image SRC:  data:{mime};base64,{data};
		$src = 'data: '.mime_content_type($image).';base64,'.$imageData;
		$src = 'data: image/*;base64,'.$imageData;
		?>
		
		<title>"Newscape", 2022 - Anthony Warnick</title>
		<!--
			Title: Newscape 
			Artist: Anthony Warnick
			Date: April 1, 2022
		-->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge"> 
		<style> body {background-color:black;padding: 0; margin: 0; overflow: hidden;} img{max-width:100%;} </style>
	</head>
	<body>
		<div>
			<?php echo '<img id="image" style="display:none;" src="', $src, '">'; ?>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/p5@1.0.0/lib/p5.js"></script>
		<script src="sketch.js"></script>
	</body>
</html>
