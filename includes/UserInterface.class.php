<?php
class UserInterface
{
	static public function Group ($group_id, $html_data="", $additional_class=""){
		return '<div id="layout-group-'.$group_id.'" class="layout-group layout-group-'.$group_id.' '.$additional_class.'">'.$html_data.'</div>';
	}

	static public function Button ($label, $link="", $additional_class=""){
		/*<a class="function-btn <?php echo $keys['btn_class']; ?>" href="<?php echo $link; ?>"><span><?php echo $keys['btn_label']; ?></span></a>*/
		if($link != ""){
			$link = 'href="'.$link.'"';
		}
		return '<a class="function-btn '.$additional_class.'" '.$link.'><span>'.$label.'</span></a>';
	}

	static public function Dropdown($label, $list, $dropdown_unique_id="", $additional_class="",$active_id=""){
		$selection = '<li class="dropdown-header">'.$label.'</li>'."\n";
		if(is_array($list))
		{
			foreach($list as $item_key => $item)
			{
				if($item_key == $active_id){
					$selection .= '<li class="active">'.$item.'</li>'."\n";
				}else{
					$selection .= '<li>'.$item.'</li>'."\n";
				}
			}
		}else{
			$selection = '<li>'.$list.'</li>'."\n";
		}

		if($dropdown_unique_id != "")
		{
			$dropdown_unique_id = 'id="'.$dropdown_unique_id.'"';
		}

		$html = '<div '.$dropdown_unique_id.' class="dropdown '.$additional_class.'">
			  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'.$label.'
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			    '.$selection.'
			  </ul>
			</div>';
		return $html;
	}

	static public function Slider($unique_id,$target_url, $label, $default_value, $max, $min=0)
	{
		$js = '<script type="text/javascript">$(document).ready(function(){'.
				   '$("#'.$unique_id.' .slider-input").slider('.
				   '{ range: "min", min:'.$min.',max:'.$max.',value:'.$default_value.',
				     slide:function(event,ui){
				       $("#'.$unique_id.' span.value").text(ui.value);
				     },
				     stop:function(e, ui) {
				       console.log(ui.value);
				       window.location.href = "'.$target_url.'"+ui.value;
				     }
				  	});
				});</script>'."\n";
		$html = '<div id="'.$unique_id.'" class="ui-slider">
					<span class="label">'.$label.'</span><span class="value">'.$default_value.'</span>
					<div class="slider-input"></div>
				</div>';

		return $html.$js;
	}
}