<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль tegs.php</p>"; require "footer.php"; exit(); }
function bold($text) {
	return "<span class=\"fontbold\">$text</span>";
}
function centerWrap($content) {
	return "<center>".$content."</center>";
}
function newLineBefore($text) {
	return "<br>".$text;
}
function newLineAfter($text) {
	return $text."<br>";
}
function tableWrapper($content) {
	return "<table>".$content."</table>";
}
function tableRowWrapper($content) {
	return "<tr>".$content."</tr>";
}
function tableHeaderWrapper($content) {
	return "<th>".$content."</th>";
}
function tableDigitWrapper($content, $option="") {
	return "<td ".$option.">".$content."</td>";
}
function abbrDekanModule($title, $content, $contentExt) {
	return "<abbr title=\"".$title." максимум ".$contentExt." балів\">".$content." ".$contentExt."</abbr>";
}
?>
