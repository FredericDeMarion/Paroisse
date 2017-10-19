/************************************************************************************************************
	@fileoverview
	Ajax tooltip
	Copyright (C) 2006  Alf Magne Kalleland(post@dhtmlgoodies.com)
	
	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 2.1 of the License, or (at your option) any later version.
	
	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.
	
	You should have received a copy of the GNU Lesser General Public
	License along with this library; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
	
	
	www.dhtmlgoodies.com 
	Alf Magne Kalleland

************************************************************************************************************/

/* Custom variables */

/* Offset position of tooltip */
var x_offset_tooltip = 5;
var y_offset_tooltip = 0;

/* Don't change anything below here */


var ajax_tooltipObj = false;
var ajax_tooltipObj_iframe = false;

var ajax_tooltip_MSIE = false;
if(navigator.userAgent.indexOf('MSIE')>=0)ajax_tooltip_MSIE=true;



var currentTooltipObject = false;

function ajax_showTooltip(e,externalFile,inputObj)
{
	currentTooltipObject = inputObj;
	//window.onresize = function(e) { ajax_positionTooltip(e); } ;
   if(document.all)e = event;

   
	if(!ajax_tooltipObj)	/* Tooltip div not created yet ? */
	{
		ajax_tooltipObj = document.createElement('DIV');
		ajax_tooltipObj.style.position = 'absolute';
		ajax_tooltipObj.id = 'ajax_tooltipObj';		
		
		document.body.appendChild(ajax_tooltipObj);

		
		var leftDiv = document.createElement('DIV');	/* Create arrow div */
		leftDiv.className='ajax_tooltip_arrow';
		leftDiv.id = 'ajax_tooltip_arrow';
		ajax_tooltipObj.appendChild(leftDiv);
		
		var contentDiv = document.createElement('DIV'); /* Create tooltip content div */
		contentDiv.className = 'ajax_tooltip_content';
		ajax_tooltipObj.appendChild(contentDiv);
		contentDiv.id = 'ajax_tooltip_content';
		contentDiv.style.marginBottom = '15px';
		
		// Creating button div
		var buttonDiv = document.createElement('DIV');
		buttonDiv.style.cssText = 'position:absolute;left:50%;bottom:20px;text-align:center;background-color:#FFF;font-size:0.8em;height:15px;z-index:10000000';
		buttonDiv.innerHTML = '<a href="#" onclick="ajax_hideTooltip();return false">Close</a>';
		ajax_tooltipObj.appendChild(buttonDiv);

		if(ajax_tooltip_MSIE){	/* Create iframe object for MSIE in order to make the tooltip cover select boxes */
			ajax_tooltipObj.style.cursor = 'move';
			ajax_tooltipObj_iframe = document.createElement('<IFRAME frameborder="0">');
			ajax_tooltipObj_iframe.style.position = 'absolute';
			ajax_tooltipObj_iframe.border='0';
			ajax_tooltipObj_iframe.frameborder=0;
			ajax_tooltipObj_iframe.style.backgroundColor='#FFF';
			ajax_tooltipObj_iframe.src = 'about:blank';
			contentDiv.appendChild(ajax_tooltipObj_iframe);
			ajax_tooltipObj_iframe.style.left = '0px';
			ajax_tooltipObj_iframe.style.top = '0px';
		}		
	}
	// Find position of tooltip
	ajax_tooltipObj.style.display='block';
	ajax_loadContent('ajax_tooltip_content',externalFile);
	if(ajax_tooltip_MSIE){
		ajax_tooltipObj_iframe.style.width = ajax_tooltipObj.clientWidth + 'px';
		ajax_tooltipObj_iframe.style.height = ajax_tooltipObj.clientHeight + 'px';
	}

	
	ajax_positionTooltip(e,inputObj); 
}

function ajax_positionTooltip(e,inputObj)
{
	if(!inputObj)inputObj=currentTooltipObject;
	if(inputObj){
		var leftPos = (ajaxTooltip_getLeftPos(inputObj) + inputObj.offsetWidth);
		var topPos = ajaxTooltip_getTopPos(inputObj);
	}else{		
	   var leftPos = e.clientX;
	   var topPos = e.clientY;
	}
   var tooltipWidth = document.getElementById('ajax_tooltip_content').offsetWidth +  document.getElementById('ajax_tooltip_arrow').offsetWidth;
   ajax_tooltipObj.style.left = leftPos + 'px';
   ajax_tooltipObj.style.top = topPos + 'px';   
} 

function ajax_hideTooltip()
{
	ajax_tooltipObj.style.display='none';
}

function ajaxTooltip_getTopPos(inputObj)
{		
  var returnValue = inputObj.offsetTop;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
  }
  return returnValue;
}

function ajaxTooltip_getLeftPos(inputObj)
{
  var returnValue = inputObj.offsetLeft;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
  }
  return returnValue;
}