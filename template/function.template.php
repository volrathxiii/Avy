<?php
$AllClasses = $Triggers->GetClasses();
$AllFunctions = $Triggers->GetFunctions();
//var_dump($AllFunctions);
/**
 * Function Call Back
 */
if (isset($_GET['f']) && $_GET['f'] != '') {
    $functionTrigger = $_GET['f'];

    $classTriggerIndex = $Triggers->GetClassTrigger($page);
    $callParameters = implode(" ", $AllClasses[$page][$classTriggerIndex]);

    $functionTriggerIndex = $Triggers->GetFunctionTrigger($page, $functionTrigger);
    $callParameters = $callParameters . " " . implode(" ", $AllFunctions[$page][$functionTrigger][$functionTriggerIndex]);
    ?>
    <div class="avy-response-result">
        <?php $Avy = new Avy($callParameters, false); ?>
    </div>
    <?php
}
?>
<div class="row function-page <?php echo $page; ?>">
    <?php
    if (array_key_exists($page, $AllClasses)) {
        $class = new $page();
        ?>
        <h1><?php echo $class->Name; ?></h1>
        <?php
        $layout = $class->dashboard();
        if($layout){
			echo $layout;
        }
        else{
	        foreach ($AllFunctions as $class => $functions) {
	            if ($class == $page) {

	                foreach ($functions as $function => $keys) {
	                    if (array_key_exists("btn_label", $keys)) {
	                        $link = "?p=" . $page . "&f=" . $function;
	                        ?>
	        <a class="function-btn <?php echo $keys['btn_class']; ?>" href="<?php echo $link; ?>"><span><?php echo $keys['btn_label']; ?></span></a>
	                        <?php
	                    }
	                }
	                return true;
	            }
	        }
        }
    }else{
    	?>
    	<span class="error">Function or module not existing.</span>
    	<?php
    }
    ?>
</div>