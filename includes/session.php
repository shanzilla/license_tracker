<?php
	session_start();
	
	function display_message() {
		
		if ( isset( $_SESSION["message"] ) ) {
			$output = '<div class="message ' . $_SESSION["class"] . '">';
			
			if ($_SESSION["class"] == "success") {
				$output .= '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>';
			} elseif ($_SESSION["class"] == "failure") {
				$output .= '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>';
			}
			
			
			$output .= htmlentities($_SESSION["message"]);
			$output .= '</div>';
			
			$_SESSION["message"] = null;
			echo $output;
		}
	}
	
	function errors() {
		
		if ( isset( $_SESSION["errors"] ) ) {
			$errors = $_SESSION["errors"];
			
			$_SESSION["errors"] = null;
			
			return $errors;
		}
	}
?>