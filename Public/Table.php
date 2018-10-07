<?php
header('Content-Type: text/html; charset=utf-8');

// 定义数据配置信息
$host 	= '192.168.6.71';
$dbname = 'yoshop_db';
$user 	= 'root';
$pass 	= 'NvGHHsQvo3!90YS@';

$down = !empty($_GET['d']) ? trim($_GET['d']) : false;
if($down){
	header ( "Content-type:application/vnd.ms-excel" );
	header ( "Content-Disposition:attachment;filename=".$dbname."数据字典.xls" );
}

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
	
	// 获取表数据
    $stmt = $pdo->prepare('SHOW TABLES');
	$stmt->execute();
	$table_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$table = array_map(function($var) use ($dbname){
		return $var['Tables_in_'.$dbname];
	},$table_list);
	

    // 获取所有表结构(TABLES)
	$table_result = $tables = [];
	foreach($table as $k=>$val){
		$fields = [];
		$sql = 'SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = :table_schema  AND  TABLE_NAME = :table_name';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([':table_schema'=>$dbname,':table_name'=>$val]);
		$table_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($table_result as $k1 => $v1){
			$tables[$k]['TABLE_COMMENT'] = $v1['TABLE_COMMENT'];
			$tables[$k]['TABLE_NAME']	 = $v1['TABLE_NAME'];
		}
		
		$sql = 'SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :table_schema  AND  TABLE_NAME = :table_name';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([':table_schema'=>$dbname,':table_name'=>$val]);
		$field_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($field_result as $k2 => $v2){
			$fields[] = $v2;
		}
		$tables[$k]['COLUMN'] = $fields;
		
	}
	
	// 分组表结构
	$html = '';
	foreach($tables as $k => $v)
	{
	  $html .= '<table border="1" cellspacing="0" cellpadding="0" align="center">';
	  $html .= '<caption>表名：' . $v['TABLE_NAME'] . ' ' . $v['TABLE_COMMENT'] . '</caption>';
	  $html .= '<tbody><tr><th>字段名</th><th>数据类型</th><th>默认值</th><th>允许非空</th><th>自动递增</th><th>备注</th></tr>';
	  $html .= '';
	  foreach($v['COLUMN'] AS $f)
	  {
		$html .= '<td class="c1">' . $f['COLUMN_NAME'] . '</td>';
		$html .= '<td class="c2">' . $f['COLUMN_TYPE'] . '</td>';
		$html .= '<td class="c3">' . $f['COLUMN_DEFAULT'] . '</td>';
		$html .= '<td class="c4">' . $f['IS_NULLABLE'] . '</td>';
		$html .= '<td class="c5">' . ($f['EXTRA'] == 'auto_increment'?'是':' ') . '</td>';
		$html .= '<td class="c6">' . $f['COLUMN_COMMENT'] . '</td>';
		$html .= '</tr>';
	  }
	  $html .= '</tbody></table></p>';
	}
	
	
	// 输出
	echo '<html>
	  <meta charset="utf-8">
	  <title>自动生成数据字典</title>
	  <style>
		body,td,th {font-family:"宋体"; font-size:12px;}
		table,h1,p{width:960px;margin:0px auto;}
		table{border-collapse:collapse;border:1px solid #CCC;background:#efefef;}
		table caption{text-align:left; background-color:#fff; line-height:2em; font-size:14px; font-weight:bold; }
		table th{text-align:left; font-weight:bold;height:26px; line-height:26px; font-size:12px; border:1px solid #CCC;padding-left:5px;}
		table td{height:20px; font-size:12px; border:1px solid #CCC;background-color:#fff;padding-left:5px;}
		.c1{ width: 150px;}.c2{ width: 150px;}.c3{ width: 80px;}.c4{ width: 100px;}.c5{ width: 100px;}.c6{ width: 300px;}
	  </style>
	  <body>';
	echo '<h1 style="text-align:center;">'.$dbname.'数据字典<a href="?d=download">下载</a></h1>';
	echo '<p style="text-align:center;margin:20px auto;">生成时间：' . date('Y-m-d H:i:s') . '</p>';
	echo $html;
	echo '<p style="text-align:left;margin:20px auto;">总共：' . count($tables) . '个数据表</p>';
	echo '</body></html>';
	
	$stmt->closeCursor();
	
} catch (PDOException $e) {
    die( "Error!: " . $e->getMessage());
}

