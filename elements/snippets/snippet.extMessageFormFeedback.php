<?php
/* Form feedback */

$modx->setLogLevel(3);
$modx->setLogTarget('HTML');

if(isset($_POST['submit'])){
	/*$fields = array(
						'email' => array(
								'required' => true,
								'error_message' => 'Fill email',
						),

				);*/
$output = '';

	$processorProps = array(
				'email' => array(
								'required' => true,
								'error_message' => 'Fill email',
						),
			);
	$otherProps = array(
			'processors_path' => $modx->getOption('core_path') . 'components/extmessage/processors/'
			);
			$response = $modx->runProcessor('web/feedback', $processorProps, $otherProps);

			/*if($response){
				return $response[object];
			}*/

			if($response->isError()){
				//$output = $modx->setPlaceholder('ff.error_message',$response->getMessage());

				/*foreach ($response->errors as $value) {
					foreach ($value as $key => $val) {
							$output .= $modx->setPlaceholder($key, $val);
					}
					$output = $value;
				}*/

$res = $response->getOne('errors');
//$output = $res->get('msg');

				//$output = $modx->setPlaceholders($response->errors, 'ff.');

			}
			else {
				$output = $response->getObject();

			}
return var_dump($output);
}