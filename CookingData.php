<?php

echo "cook data";

exec("python3 cook.py", $output, $return);

print_r($output); // python print값.

echo $return."\n";

?>
