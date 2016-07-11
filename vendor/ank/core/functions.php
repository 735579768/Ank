<?php
function ank_filter(&$value, $key) {
	// 过滤查询特殊字符
	if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
		$value .= ' ';
	}
	// $data = preg_replace('/<.*\?\>/is', '', $data);
}