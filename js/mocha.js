/*
 * --------------------------------------------------------------------
 * Bar Graph standalone version
 * by Siddharth S, lordtottuu@gmail.com
 * for Net Tuts, www.net.tutsplus.com

 * Copyright (c) 2009 Siddharth
 * Dual licensed under MIT and GPL
 * Usage Notes: Please refer to the Net Tuts article
 * Version: 1.0, 08.05.2009 	
 * --------------------------------------------------------------------
 */
 
$(document).ready(function() {
	// Graph option variables					   
    var
		barSpacing = 20,
	 	barWidth = 20, 
	    cvHeight = 220,
		numYlabels = 8,
		xOffset = 20,
		maxVal, 
		gWidth=550, 
		gHeight=200;

	// Graph variables
	var gValues = [];
	var xLabels = [];
	var yLabels = [];
 		  
	// Canvas Variables
    var cv, ctx;
	 
	grabValues();
	initCanvas();
	maxValues(gValues);
	drawXlabels();
    drawYlabels();
	drawGraph();

	function grabValues ()
	 {
	 	// Access the required table cell, extract and add its value to the values array.
		 // $("tr").children("td:odd").each(function(){
		 // $("#"+dataSource).find("td:odd").each(function(){
		 // $("#"+dataSource+" tr td:odd").each(function(){
		 $("#data tr td:nth-child(2)").each(function(){
		 gValues.push($(this).text());
	 	 });
	 
		 // Access the required table cell, extract and add its value to the xLabels array.
		 $("#data tr td:nth-child(1)").each(function(){
	 	xLabels.push($(this).text());
	 	 });
	 } 
	 
	function initCanvas ()
	 {
	 	// Try to access the canvas element and throw an error if it isn't available
     	cv = $("#graph").get(0);
	 	if (!cv) 
	 	{ return; }
	 
     	// Try to get a 2D context for the canvas and throw an error if unable to
     	ctx = cv.getContext('2d');
	 	if (!ctx) 
	 	{ return; }
	 }
	   
    function drawGraph ()
	 {
	    for(index=0; index<gValues.length; index++)
	      {
		    ctx.save();
			ctx.fillStyle = "#B7B7B7";
	        ctx.fillRect( x(index), y(gValues[index]), width(), height(gValues[index]));  
		    ctx.restore();
	      }
	 }

	function drawYlabels()
      {
		 ctx.save(); 
	     for(index=0; index<numYlabels; index++)
	      {
		   yLabels.push(Math.round(maxVal/numYlabels*(index+1)));
		   ctx.fillStyle = "#000";
		   ctx.fillText(yLabels[index], xOffset, y(yLabels[index])+10);
	       }
	       ctx.fillText("0", xOffset, gHeight+7);
		   ctx.restore();
      }  

	function drawXlabels ()
      {
		 ctx.save();
		 ctx.font = "10px 'arial'";
		 ctx.fillStyle = "#000";
		 for(index=0; index<gValues.length; index++)
	     {
		 ctx.fillText(xLabels[index], x(index), gHeight+17);
		 }
		 ctx.restore();
      }
	 
	function width ()
      {
	   return barWidth;
      }
	 
	function height (param)
      {
	   return scale(param);
      }
	 
	function x (param)
      {
	   return (param*barWidth)+((param+1)*barSpacing)+xOffset;
      }
	 
	function y (param)
      {
	   return gHeight - scale (param) ;
      }
	  
	function scale (param)
      {
	   return  Math.round((param/maxVal)*gHeight);
      }
	 
	function maxValues (arr)
     {
		maxVal=0;
		
	    for(i=0; i<arr.length; i++)
	    {
		 if (maxVal<parseInt(arr[i]))
		 {
		 maxVal=parseInt(arr[i]);
	     } 
	    }
		
	   maxVal*= 1.1;
	 }
});         
