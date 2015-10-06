<?php
class v_HeaderFooter{
	public function getHeader($head_params=null){
		ob_start();
		?>
			<html>
				<head>
					<title><?php echo $head_params['title']; ?></title>
					<link rel="stylesheet" href="<?php echo auto_version('lib/v/base.css'); ?>" type="text/css" />
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
				</head>
				<body>
		<?php
		return ob_get_clean();
	}

	function yell(){
		echo "i AM ALIVE";
	}
	
	public function getFooter($foot_params=null){
		ob_start();
		?>
				<script src="<?php echo auto_version('lib/v/base.js'); ?>" type="text/javascript"></script>
			</body>
			</html>
		<?php
		return ob_get_clean();
	}
	
}
?>
