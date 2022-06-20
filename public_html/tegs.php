<?php
if(!defined("IN_ADMIN")) die;
function bold($text) {
	return "<b>".$text."</b>";
}
function addSpan($text) {
	return "<span>".$text."</span>";
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
function tableHeaderWrapper($content, $option="") {
	return "<th ".$option.">".$content."</th>";
}
function tableFooterWrapper($content) {
	return "<th>".$content."</th>";
}
function tableAbbr($title, $content) {
	return "<abbr title=\"".$title."\">".$content."</abbr>";
}
function tableDigitWrapper($content, $option="") {
	return "<td ".$option.">".$content."</td>";
}
function abbrDekanModule($title, $content, $contentExt) {
	return "<abbr title=\"".$title." максимум ".$contentExt." балів\">".$content." ".$contentExt."</abbr>";
}
function fontS($text, $size) {
	return "<font size = ".$size.">".$text."</font>";
}
function radioWrap($name, $value, $view) {
	return "<input type=\"radio\" name=\"".$name."\" value=\"".$value."\">".$view."<br>";
}
?>
