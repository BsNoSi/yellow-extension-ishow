<?php
// ishow extension for YELLOW, https://github.com/bsnosi/yellow-extension-adr
// Copyright ©2018-now Norbert Simon, https://nosi.de for
// YELLOW Copyright ©2013-now Datenstrom, http://datenstrom.se
// This file may be used and distributed under the terms of the public license.
// requires YELLOW 0.8.4 or higher

class YellowIshow {
   const VERSION = "1.4.1";
   
   public $yellow; //access to API
   // Handle initialisation
   public function onLoad($yellow) {
      $this->yellow = $yellow;
   }
   // Handle page content of shortcut
   public function onParseContentShortcut($page, $name, $text, $type) {
      $output = null;
      if ($name == "ishow" && ($type == "block" || $type == "inline")) {
         //		  if (empty($text) {} else …

         list($pattern, $duration, $swipe, $dtype, $dlink, $drim) = $this->yellow->toolbox->getTextArguments($text);
         if (empty($pattern)) {
            $files = $this->yellow->files->clean();
         } else {
            $images = $this->yellow->system->get("imageDir");
            $files = $this->yellow->media->index(true, true)->match("#$images$pattern#");
         }
         if (count($files)) {
            if (empty($duration))
               $duration = 4;
            if (empty($swipe))
               $swipe = 1;
            if (empty($dtype))
               $dtype = 1;
            if (empty($dlink))
               $dlink = "";
            //define standard boarder style here. For border set "x" as parameter:
            $drim = ($drim == "x") ? "border: solid rgb(230,230,230) 1px; border-radius:6px;" : "";

            $fcount = count($files);
            //	Basic style preparation
            $output = "<style>.iout {width: 100%; margin:0 auto; position:relative; overflow:hidden;".$drim."}";
            $output .= ".ishow div{width:100%;position:absolute; height:auto;text-align:center;transform:translateX(110%);";
            $output .= "display:flex; justify-content: center;  align-items: center;height:100%;}";
            $output .= ".ishow div img, .fill img {max-width:100%;height: auto;padding:0px;margin:0 auto;display:block;overflow: hidden;}";
            $output .= ".fill {vertical-align:center;}";
            $output .= ".fill img {visibility:hidden;}";
            // container creation for images
            $output .= "#ic";
            for ($i = $fcount - 1; $i > 0; $i--) {
               $output .= $i.",#ic";
            }
            $full = ($duration + $swipe) * $fcount;
            $output .= "0 {animation: ".$full."s theshow infinite ease-in-out; ".($dlink ? "cursor: pointer; " : "")."}";

            for ($i = 1; $i < $fcount; $i++) {
               $output .= "#ic".$i." {animation-delay:".($duration + $swipe) * $i."s}";
            }
            // time part of each image in the show
            $seq = round(($duration + $swipe) / $full * 100, 2);
            $sep = round(($swipe / $seq) * 100, 2);
            $output .= " @keyframes theshow {";
            // the presentation modes
            switch ($dtype) {
            case "left":
               $output .= "0%{transform: translateX(110%); }";
               $output .= $sep."% {transform: translateX(0);}";
               $output .= $seq."% {transform: translateX(0);}";
               $output .= $seq + $sep."%{transform: translateX(-110%); }";
               $output .= "100%{transform: translateX(-110%); }";
               break;
            case "right":
               $output .= "0%{transform: translateX(-110%); }";
               $output .= $sep."% {transform: translateX(0);}";
               $output .= $seq."% {transform: translateX(0);}";
               $output .= $seq + $sep."%{transform: translateX(110%); }";
               $output .= "100%{transform: translateX(110%); }";
               break;
            case "up":
               $output .= "0%{transform: translate(0%,110%);}";
               $output .= $sep."% {transform: translateY(0%)}";
               $output .= $seq."% {transform: translate(0%,0%);}";
               $output .= $seq + $sep."%{transform: translateY(-110%)}";
               $output .= "100%{transform: translateY(-110%)}";
               break;
            case "down":
               $output .= "0%{transform: translate(0%,-110%);}";
               $output .= $sep."%{transform: translateY(0%)}";
               $output .= $seq."%{transform: translate(0%,0%);}";
               $output .= $seq + $sep."%{transform: translateY(110%)}";
               $output .= "100%{transform: translateY(110%)}";
               break;
            case "pump":
               $output .= "0%{transform: scale(0);}";
               $output .= $sep."% {transform: scale(1);}";
               $output .= $seq."% {transform: scale(1);}";
               $output .= $seq + $sep."%{transform:scale(0);}";
               $output .= "100%{transform: translateX(-110%);}";
               break;
            case "zoom":
               $output .= "0%{transform: translateX(0%) scale(10);opacity: 0;}";
               $output .= $sep."% {transform: scale(1);opacity: 1;}";
               $output .= $seq."% {transform: scale(1);opacity: 1;}";
               $output .= $seq + $sep."%{transform:scale(10);opacity: 0;}";
               $output .= "100%{transform: translateX(-110%) scale(1);opacity: 0;}";
               break;
            case "fly":
               $output .= "0%{transform: scale(0.5) translate(-150%, -150%) skew(45deg, 0deg) rotate(180deg);}";
               $output .= $sep."% {transform: translateX(0%) rotate(0deg);}";
               $output .= $seq."% {transform: translateX(0%);}";
               $output .= $seq + $sep."%{transform: scale(0.5) translate(150%, -150%) skew(-45deg, 0deg) rotate(-180deg);}";
               $output .= "100%{transform: translateX(110%,0%);}";
               break;
            case "show":
            default:
               $output .= "0%{transform: translateX(0%);opacity: 0 ;}";
               $output .= $sep."% {opacity: 1; }";
               $output .= $seq."%{opacity: 1; } ";
               $output .= $seq + $sep."%{opacity: 0; }";
               $output .= "100%{transform: translateX(0%);opacity: 0;}";
               break;
            }
            // finalize style and start visual show block
            $output .= "}</style>\n<div class=\"iout\"><div class=\"ishow\">\n";
            // fetch image files
            $i = 0;
            foreach($files as $file) {
               // backup first file for space
               if (0 == $i) {
                  $first = $file->getLocation(true);
               }
               // image container
               $output .= "<div class=\"sld\"id=\"ic".$i."\"" .($dlink ? " target=\"".$dlink.pathinfo($file->getLocation(true), PATHINFO_FILENAME)."\"" : ""). "\"><img src=\"".$file->getLocation(true)."\"alt=\"\"/></div>\n";
		   $i++;		   
            }
            // place spacer for dynamic scaling
            $output .= "</div><div class=\"fill\"><img src=\"".$first."\" /></div></div>\n";
         } 
	   else {
            $page->error(500, "→ No matching images found: \"".$images.$pattern."\"\n");
         }
      }
      return $output;
   }
   
    public function onParsePageExtra($page, $name) {
    $output = null;
    
    if ($name == "footer" && $page->isExisting("ishow"))  {
      $output = "<script>\n";
	$output .= "var x = document.getElementsByClassName('sld');var i;for (i = 0; i < x.length; i++) {x[i].addEventListener('click', function() {window.location.href = this.getAttribute('target');});}";
	$output .= "</script>\n";
    }
    return $output;
  }
}
?>
