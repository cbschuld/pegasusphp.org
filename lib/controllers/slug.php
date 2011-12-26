<?php
	class SiteController extends Controller {
		function processGet() {


			$xhtml = '';

			Slug::setFixedLength(false);
			Slug::setMaxLength(64);
			Slug::setUseMaxLength(true);

			$slugtest = array(
								"this is a test of the slug component!",
								"I wonder what will happen if I do this?",
								"I really like R2D2, he is great!!!!!",
								"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In pretium molestie lorem. Pellentesque tincidunt erat id nisl. Nulla suscipit, elit sit amet aliquam aliquet, quam ante congue nulla, sit amet luctus neque sapien id eros. Maecenas magna metus, rhoncus sit amet, rhoncus vitae, convallis non, nibh. Mauris sem leo, posuere vel, venenatis quis, sodales ac, leo. In vel nunc non metus ultricies tempor. Sed dictum euismod quam. Nam adipiscing, neque eget fermentum condimentum, ligula neque blandit metus, at feugiat lacus mauris id lacus. Proin blandit est ac sapien. Donec tempor libero eget nulla. Vestibulum quis tellus eget nibh euismod euismod. Maecenas quis tortor. Mauris a orci eu tellus sodales tempus. ",
								"@#$^%#$^@$%&@%^@#$%^#@$%! $%!@#$%!@#$% !@#%!@#%!@#$% ! ^! $^ !$^@#$^@#$^@# $%^@#$%!#$%",
								"No. 4-seeded Serena struggled with her serve early against Dementieva, then staged a rally in the final set. Williams overcame two match points during an 18-point game to hold for 5-3."
						);

			$xhtml .= '<h1>No Fixed Length with a Max Length of 64</h1>';
			foreach( $slugtest as $slug ) {
				$xhtml .= 'Testing: '.$slug.'<br/><strong>';
				$xhtml .= Slug::generate($slug).'</strong><br/><br/>';
			}

			Slug::setFixedLength(true);
			Slug::setMaxLength(12);
			Slug::setUseMaxLength(true);

			$xhtml .= '<h1>Fixed Length with a Max Length of 12</h1>';
			foreach( $slugtest as $slug ) {
				$xhtml .= 'Testing: '.$slug.'<br/><strong>';
				$xhtml .= Slug::generate($slug).'</strong><br/><br/>';
			}

			Slug::setFixedLength(false);
			Slug::setMaxLength(12);
			Slug::setUseMaxLength(false);

			$xhtml .= '<h1>No Fixed Length with a NO Use of Max Length</h1>';
			foreach( $slugtest as $slug ) {
				$xhtml .= 'Testing: '.$slug.'<br/><strong>';
				$xhtml .= Slug::generate($slug).'</strong><br/><br/>';
			}


			View::assign('content',$xhtml);
		}
	}
?>